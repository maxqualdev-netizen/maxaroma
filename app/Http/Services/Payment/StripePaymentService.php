<?php

namespace App\Services\Payment;

use App\Services\Checkout\CheckoutTotalsService;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Traits\EncryptTrait;
use RuntimeException;
use Stripe\PaymentIntent;
use App\Models\PaymentMethod;
use Stripe\Stripe;

class StripePaymentService
{
    use EncryptTrait;
    protected CheckoutTotalsService $checkoutTotals;

    public $publishable_key = '';
    public $secret_key = '';
    public function __construct(CheckoutTotalsService $checkoutTotals) {
        $this->checkoutTotals = $checkoutTotals;

        $db_res = PaymentMethod::select('pm_group_name', 'pm_gateway_name','pm_details')
							->where('pm_group_name','=', 'PAYMENT_STRIPE')
							->where('pm_status', '=', 'Active')
							->get();
		if($db_res->count() > 0)
		{
			$arrPEVar		= unserialize($db_res[0]->pm_details);
			$STRIPE_KEY = $this->decrypt($arrPEVar['Secret_Key']);
			$this->publishable_key = $this->decrypt($arrPEVar['Publishable_Key']);
			$this->secret_key = $this->decrypt($arrPEVar['Secret_Key']);
		}
		if(Session::get('sess_useremail') == 'wgequaldev@gmail.com' || Session::get('sess_useremail') == 'gequaldev@gmail.com' || Session::get('sess_useremail') == 'qqualdev@gmail.com' || Session::get('sess_useremail') == 'qualdev.devs@gmail.com' ||  Session::get('sess_useremail') =='testing12345678@gmail.com' ||  Session::get('sess_useremail') =='tetn@gmail.com' || Session::get('sess_useremail') =='qualdevtest344555@gmail.com' || Session::get('sess_useremail') =='maxdev86@gmail.com' )
		{
			$this->secret_key = 'sk_test_UQbJgAk4zBzJ0jksAKZUD4ey00O2mvNHXG';
            $this->publishable_key = 'pk_test_xWTUgWDaaFSIfHkKClwzQrhS00eb9PQSLX';
		}
        $this->secret_key = 'sk_test_UQbJgAk4zBzJ0jksAKZUD4ey00O2mvNHXG';
        $this->publishable_key = 'pk_test_xWTUgWDaaFSIfHkKClwzQrhS00eb9PQSLX';
        Stripe::setApiKey($this->secret_key);
        //Stripe::setApiKey('sk_test_UQbJgAk4zBzJ0jksAKZUD4ey00O2mvNHXG');
        //Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Get frontend-safe Stripe configuration.
     */
    public function config(): array
    {
        //return ['publishable_key' => config('services.stripe.striptkey')];
        return ['publishable_key' => $this->publishable_key];
    }

    /**
     * Create a PaymentIntent and confirm it using
     * a Stripe PaymentMethod created by Stripe.js.
     *
     * Laravel receives ONLY the PaymentMethod ID.
     */
    public function pay(
        string $paymentMethodId,
        string $order_id
    ): array {
        try{
            if (trim($paymentMethodId) === '') {
                throw new RuntimeException(
                    'Payment method is required.'
                );
            }

            /*
            * IMPORTANT:
            *
            * Always calculate the amount on the server.
            */
            //$totals = $this->checkoutTotals->calculate();
            //$total = (float) ( $totals['NetTotal'] ?? 0 );
            $total = (float)(Session::get('ShoppingCart.NetTotal','0'));

            if ($total <= 0) {
                throw new RuntimeException(
                    'Invalid checkout total.'
                );
            }

            $amount = $this->toStripeAmount($total);

            $currency = $this->currency();

            /*
            * Create and confirm PaymentIntent.
            */

            $intent =
                PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_method' => $paymentMethodId,
                    /*
                    * This tells Stripe to confirm the
                    * payment immediately.
                    */
                    'confirm' => true,
                    /*
                    * Required for cards that need
                    * 3DS/SCA authentication.
                    *
                    * Stripe may return requires_action.
                    */
                    'return_url' => route('checkout.payment.stripe.return'),
                    /*
                    * Keep this enabled because the same
                    * PaymentIntent architecture will later
                    * support Apple Pay / Google Pay.
                    */
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],

                    'metadata' => [
                        'order_number' => 'OR' . $order_id,
                        'source' => 'maxaroma_checkout',
                        'customer_id' => (string) Session::get('sess_icustomerid',''),
                    ],
                ]);

            /*
            * Keep PaymentIntent ID in checkout session.
            */
            Session::put(
                'ShoppingCart.Stripe.PaymentIntentID',
                $intent->id
            );

            return $this->result(
                $intent
            );
        }catch (\Stripe\Exception\CardException $e) {

        // Card declined / insufficient funds / etc.
        \Log::warning('Stripe card payment failed', [
            'message' => $e->getMessage(),
            'code' => $e->getError()?->code,
            'decline_code' => $e->getError()?->decline_code,
        ]);

        return [
            'success' => false,
            'status' => 'failed',
            'error_type' => 'card_error',
            'message' => $e->getError()?->message
                ?? 'Your card payment could not be completed.',
        ];

        } catch (\Stripe\Exception\ApiConnectionException $e) {

            // Stripe could not be reached
            \Log::error('Stripe API connection error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'error_type' => 'connection_error',
                'message' => 'Unable to connect to the payment service. Please try again.',
            ];

        } catch (\Stripe\Exception\ApiErrorException $e) {

            // Other Stripe API errors
            \Log::error('Stripe API error', [
                'message' => $e->getMessage(),
                'code' => $e->getError()?->code,
                'type' => $e->getError()?->type,
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'error_type' => 'stripe_error',
                'message' => 'Unable to process the payment.',
            ];

        } catch (\Throwable $e) {

            // Application / unexpected errors
            \Log::error('Stripe payment unexpected error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'error_type' => 'application_error',
                'message' => 'An unexpected error occurred while processing the payment.',
            ];
        }

    }

     /**
     * Create PaymentIntent WITHOUT confirming it.
     *
     * Used by:
     *
     * Google Pay
     * Apple Pay
     * Express Checkout
     *
     * Stripe.js will confirm the PaymentIntent.
     */
    public function createPaymentIntent(?string $orderId = null, ?string $paymentMethodId = null): array
    {
        /*
         * Calculate server-side total.
         */
        $total = (float)(Session::get('ShoppingCart.NetTotal','0'));

            if ($total <= 0) {
                throw new RuntimeException(
                    'Invalid checkout total.'
                );
            }

        $amount = $this->toStripeAmount($total);

        $currency =
            $this->currency();

        /*
         * Create PaymentIntent.
         *
         * DO NOT set confirm=true here.
         */
        $IntentInfo = [
            'amount' => $amount,
            'currency' => $currency,
            //'payment_method' => $paymentMethodId,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'metadata' => [
                'source' =>'maxaroma_checkout',
                'customer_id' => (string) Session::get('sess_icustomerid',''),
                'order_id' => (string) ($orderId ?? ''),
                'order_number' => $orderId? 'OR' . $orderId: '',
            ],
        ];
        if(!empty($paymentMethodId))
        {
            $IntentInfo['payment_method'] = $paymentMethodId;
        }
        $intent = PaymentIntent::create($IntentInfo);

        /*
         * Save PaymentIntent in session.
         */
        Session::put('ShoppingCart.Stripe.PaymentIntentID',$intent->id);

        return [
            'success' => true,
            'payment_intent_id' => $intent->id,
            'client_secret' => $intent->client_secret,
            'amount' => $intent->amount,
            'currency' => $intent->currency,
        ];
    }

    /**
     * Retrieve a PaymentIntent.
     */
    public function retrieve(
        string $paymentIntentId
    ): PaymentIntent {

        $sessionIntentId =
            (string) Session::get(
                'ShoppingCart.Stripe.PaymentIntentID',
                ''
            );

        /*
         * Don't allow a customer to inspect/verify
         * an unrelated PaymentIntent.
         */
        if (
            $sessionIntentId === ''
            ||
            !hash_equals(
                $sessionIntentId,
                $paymentIntentId
            )
        ) {
            throw new RuntimeException(
                'Invalid payment session.'
            );
        }

        return PaymentIntent::retrieve(
            $paymentIntentId
        );
    }

    /**
     * Verify a payment after Stripe.js
     * completes authentication.
     */
    public function verify(
        string $paymentIntentId
    ): array {

        $intent =
            $this->retrieve(
                $paymentIntentId
            );

        /*
         * Verify amount again.
         */
        //$totals = $this->checkoutTotals->calculate();
        $total = (float)(Session::get('ShoppingCart.NetTotal','0'));

        //$expectedAmount = $this->toStripeAmount((float) ($totals['NetTotal'] ?? 0));
        $expectedAmount = $this->toStripeAmount($total);
        if (
            (int) $intent->amount
            !== $expectedAmount
        ) {
            throw new RuntimeException(
                'Payment amount does not match checkout.'
            );
        }

        /*
         * Verify currency.
         */
        if (
            strtolower(
                (string) $intent->currency
            )
            !== strtolower(
                $this->currency()
            )
        ) {
            throw new RuntimeException(
                'Payment currency does not match checkout.'
            );
        }

        return $this->result(
            $intent
        );
    }

    /**
     * Convert decimal amount to Stripe amount.
     *
     * Example:
     *
     * 100.50 => 10050
     */
    protected function toStripeAmount(
        float $amount
    ): int {

        return (int) round(
            $amount * 100
        );
    }

    /**
     * Current checkout currency.
     *
     * Replace the session key with your existing
     * MaxAroma currency implementation if required.
     */
    protected function currency(): string
    {
        return strtolower(
            (string) Session::get(
                'currency_code',
                'usd'
            )
        );
    }

    /**
     * Normalize PaymentIntent response.
     */
    protected function result(
        PaymentIntent $intent
    ): array {

        $result = [
            'success' => false,

            'status' =>
                $intent->status,

            'payment_intent_id' =>
                $intent->id,
        ];

        /*
         * Payment completed.
         */
        if (
            $intent->status === 'succeeded'
        ) {
            $result['success'] = true;

            return $result;
        }

        /*
         * Stripe requires customer authentication.
         *
         * Stripe.js will handle this.
         */
        if ($intent->status === 'requires_action')
        {
            $result['requires_action'] = true;
            /*
             * client_secret is safe to send to
             * the browser.
             *
             * NEVER send the Stripe secret key.
             */
            $result['client_secret'] = $intent->client_secret;
            return $result;
        }

        /*
         * Payment is still processing.
         */
        if($intent->status === 'processing')
        {
            $result['processing'] = true;
            return $result;
        }

        /*
         * Payment failed or requires another
         * payment method.
         */
        $result['message'] =
            $intent
                ->last_payment_error
                ->message
                ??
                'Payment could not be completed.';

        return $result;
    }

    /**
     * Update existing PaymentIntent with latest checkout amount.
     *
     * Used when shipping method, insurance, gift wrapping,
     * discounts, or other checkout charges change.
     */
    public function updatePaymentIntent(string $paymentIntentId, float $total): array
    {
        $paymentIntentId = Session::get('ShoppingCart.Stripe.PaymentIntentID');
        //$total = (float)(Session::get('ShoppingCart.NetTotal','0'));
        try {

            if ($paymentIntentId === '') {
                throw new RuntimeException(
                    'PaymentIntent not found in checkout session.'
                );
            }

            if ($total <= 0) {
                throw new RuntimeException(
                    'Invalid checkout total.'
                );
            }

            $amount = $this->toStripeAmount($total);
            $currency = $this->currency();

            // Retrieve the existing PaymentIntent.
            $intent = $this->retrieve($paymentIntentId);

            // Do not modify completed or canceled payments.
            if (in_array($intent->status, ['succeeded', 'canceled', 'processing'], true)) {
                throw new RuntimeException(
                    'PaymentIntent cannot be updated in its current status: '
                    . $intent->status
                );
            }

            // Avoid unnecessary Stripe API calls.
            if (
                (int) $intent->amount === $amount &&
                strtolower((string) $intent->currency) === $currency
            ) {
                return [
                    'success' => true,
                    'payment_intent_id' => $intent->id,
                    'client_secret' => $intent->client_secret,
                    'amount' => $intent->amount,
                    'currency' => $intent->currency,
                    'status' => $intent->status,
                    'updated' => false,
                ];
            }

            // Update the existing PaymentIntent.
            $intent = PaymentIntent::update(
                $paymentIntentId,
                [
                    'amount' => $amount,
                    'currency' => $currency,
                ]
            );

            return [
                'success' => true,
                'payment_intent_id' => $intent->id,
                'client_secret' => $intent->client_secret,
                'amount' => $intent->amount,
                'currency' => $intent->currency,
                'status' => $intent->status,
                'updated' => true,
            ];

        } catch (\Stripe\Exception\ApiErrorException $e) {

            \Log::error('Stripe PaymentIntent update failed', [
                'message' => $e->getMessage(),
                'code' => $e->getError()?->code,
            ]);

            return [
                'success' => false,
                'message' => 'Unable to update Stripe payment amount.',
            ];

            throw new RuntimeException(
                'Unable to update Stripe payment amount.',
                0,
                $e
            );
        }
    }
}