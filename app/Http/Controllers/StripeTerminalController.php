<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Terminal\ConnectionToken;
use Stripe\PaymentIntent;
use App\Models\StorePayment;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Traits\EncryptTrait;
use App\Models\Customer;

use Hash;
use Session;
use DB;
use Mail;
use Stripe\StripeClient;

use Carbon\Carbon;

class StripeTerminalController extends Controller
{
	protected $stripe;       // Stripe client
    protected $stripeKey;    // Stripe API key

	use EncryptTrait;

	public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $sess_useremail = Session::get('sess_useremail') ?? '';

            $db_res = PaymentMethod::select('pm_details')
                            ->where('pm_group_name','PAYMENT_STRIPE')
                            ->where('pm_status','Active')
                            ->first();

            if ($db_res) {
                $arrPEVar = unserialize($db_res->pm_details);
                $STRIPE_KEY = $this->decrypt($arrPEVar['Secret_Key']);
            }
            $devEmails = [
                'wgequaldev@gmail.com',
                'gequaldev@gmail.com',
                'qqualdev@gmail.com',
                'testing12345678@gmail.com',
                'tetn@gmail.com',
                'maxqual11@gmail.com',
                'storeguest2@gmail.com',
                'tempchecknew1223@gmail.com'
            ];

            if (in_array(strtolower($sess_useremail), $devEmails)) {
                $STRIPE_KEY = 'sk_test_UQbJgAk4zBzJ0jksAKZUD4ey00O2mvNHXG';
            }

            if ($STRIPE_KEY) {
                Stripe::setApiKey($STRIPE_KEY);
                $this->stripe = new StripeClient($STRIPE_KEY);
                $this->stripeKey = $STRIPE_KEY;
            }

            return $next($request);
        });
    }

    public function createPaymentIntent(Request $request)
    {
		
		$CartItem = Session::get('ShoppingCart.Cart');
		$grouped = collect($CartItem)->groupBy('OrderType');
		$storeCount = $grouped->get('Store', collect())->count();
		$websiteCount = $grouped->get('Website', collect())->count();
		

		$OrderStoreTypeValue = "Website";
		if($storeCount > 0)
		{
			$OrderStoreTypeValue = "Store";
		}
		

		
		
		$CustEmail = Session::get('ShoppingCart.BillingAddress.email') ?? '';

		$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;
		
		$NewOrderNo = GetStorePrefix() .$StoreOrderId;
		
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;
			$NewOrderNo = "OR".$StoreOrderId;
			
		}
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;
			
			if($OrderStoreTypeValue == "Store")
			{
				$NewOrderNo = GetStorePrefix() .$StoreOrderId;
			}
			else
			{
				$NewOrderNo = "OR".$StoreOrderId;	
			}
			
			
		}
		
         $paymentIntent = PaymentIntent::create([
            'amount' => intval(round($request->input('amount') * 100)),
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'capture_method' => 'automatic',
			'metadata' => [
				'order_id' => $NewOrderNo,
				'customer_email' => $CustEmail,
			],

        ]);

       $myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';

		if (fopen($myFile, 'a+'))
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');

			$stringData .= date("m/d/Y H:i:s") . " Regarding Stripe Client Secret : " .json_encode($paymentIntent). " :\n";

			fwrite($fh, $stringData);
			fclose($fh);
		}

		$this->StripeLog('PAYMENT_INTENT_CREATED',json_encode($paymentIntent));

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }
    public function createPaymentIntentCheckout(Request $request)
    {

		if($request->input('amount')<=0)
		{
			return response()->json([
					'status' => 'error',
					'message' => 'enter proper amount.'
			]);
		}
		
		$CartItem = Session::get('ShoppingCart.Cart');
		$grouped = collect($CartItem)->groupBy('OrderType');
		$storeCount = $grouped->get('Store', collect())->count();
		$websiteCount = $grouped->get('Website', collect())->count();
		

		$OrderStoreTypeValue = "Website";
		if($storeCount > 0)
		{
			$OrderStoreTypeValue = "Store";
		}
		

		$amount = $request->input('amount');

		$CustEmail = Session::get('ShoppingCart.BillingAddress.email') ?? '';

		$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;
		
		$NewOrderNo = GetStorePrefix() .$StoreOrderId;
		
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;
			$NewOrderNo = "OR" .$StoreOrderId;
		}
		if(empty($StoreOrderId))
		{
			$StoreOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;
			
			if($OrderStoreTypeValue == "Store")
			{
				$NewOrderNo = GetStorePrefix() .$StoreOrderId;
			}
			else
			{
				$NewOrderNo = "OR".$StoreOrderId;	
			}
		}

        $paymentIntent = PaymentIntent::create([
            'amount' => intval(round($request->input('amount') * 100)),
            'currency' => 'usd',
            'payment_method_types' => ['card_present'],
            'capture_method' => 'automatic',
            'metadata' => [
								'order_id' => $NewOrderNo,
								'customer_email' => $CustEmail,
						  ],

        ]);

        $myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';

		if (fopen($myFile, 'a+'))
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');

			$stringData .= date("m/d/Y H:i:s") . " Regarding Stripe Client Secret For Checkout : " .json_encode($paymentIntent). " :\n";

			fwrite($fh, $stringData);
			fclose($fh);
		}

		$this->StripeLog('PAYMENT_INTENT_CREATED_CHECKOUT',json_encode($paymentIntent));

		if(null !== $request->input('PaymentType') && $request->input('PaymentType') == "CreditCard" && isset($paymentIntent->id) && $paymentIntent->id!='')
		{

			$CartItem = Session::get('ShoppingCart.Cart');
			$grouped = collect($CartItem)->groupBy('OrderType');
			$storeCount = $grouped->get('Store', collect())->count();
			$websiteCount = $grouped->get('Website', collect())->count();

			$StoreOrderId = Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null;

			$WebsiteOrderId = Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null;

			$NormalOrderId = Session::has('ShoppingCart.OrderID') ? Session::get('ShoppingCart.OrderID') : null;

			$StoreCashSplitPaymentId = Session::has('ShoppingCart.StoreCashSplitPaymentId') ? Session::get('ShoppingCart.StoreCashSplitPaymentId') : null;

			$WebsiteCashSplitPaymentId = Session::has('ShoppingCart.WebsiteCashSplitPaymentId') ? Session::get('ShoppingCart.WebsiteCashSplitPaymentId') : null;

			$StoreCashPaymentId = Session::has('ShoppingCart.StoreCashPaymentId') ? Session::get('ShoppingCart.StoreCashPaymentId') : null;

			if($storeCount > 0 && $websiteCount > 0 && $StoreOrderId > 0 && $WebsiteOrderId > 0 && $request->input('amount') > 0)
			{
				$StoreInsert = array(
									'amount' => NumberFormat($request->input('amount')),
									'Payment_type' => $request->input('PaymentType'),
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'orders_id'	 => Session::has('ShoppingCart.StoreOrderID') ? Session::get('ShoppingCart.StoreOrderID') : null,
									'status'	=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);

				$StoreInsert = array(
									'amount' => NumberFormat($request->input('amount')),
									'Payment_type' => $request->input('PaymentType'),
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'orders_id'	 => Session::has('ShoppingCart.WebsiteOrderID') ? Session::get('ShoppingCart.WebsiteOrderID') : null,
									'status'	=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);
				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' received.',
					'id'	  => $paymentIntent->id
				]);

			}
			else if($NormalOrderId > 0 && $amount > 0)
			{

				$StoreInsert = array(
									'amount' 			=> NumberFormat($amount),
									'Payment_type' 		=> $request->input('PaymentType'),
									'orders_id'	 		=> $NormalOrderId,
									'transaction_info'	=> json_encode($paymentIntent),
									'paymentintentid'	=> $paymentIntent->id,
									'status'			=> "Created"
									);

				$PlaceStore = StorePayment::create($StoreInsert);

				return response()->json([
					'status' => 'success',
					'message' => 'Payment of $' . NumberFormat($amount) . ' created.',
					'id'	  => $paymentIntent->id
				]);

			}
			else
			{
				 return response()->json([
					'status' => 'error',
					'message' => 'Order not found,please try again.'
				]);
			}

		}
		else
		{
			 return response()->json([
				'status' => 'error',
				'message' => 'Please process again.'
			]);

		}

    }

public function PaymentProcessCheckout(Request $request)
{
    $paymentIntentId = $request->input('payment_intent_id');

    try {
        sleep(3);

        $readerId = Session::has('ConnectedCardReaderId')
            ? Session::get('ConnectedCardReaderId')
            : null;

        $status = '';
        $lastResponse = null;
        $attempts = 0;
        $maxAttempts = 60;

        $sess_useremail = Session::get('sess_useremail') ?? '';

        /*
         * Existing Stripe Terminal payment processing.
         */
        $reader = $this->stripe->terminal->readers->processPaymentIntent(
            $readerId,
            ['payment_intent' => $paymentIntentId]
        ); // This live mode only

        $myFile = env('LOG_BASE_PATH') . 'Logs/StripeLog.txt';

        if (fopen($myFile, 'a+')) {
            $stringData = '';
            $fh = fopen($myFile, 'a+');

            $stringData .= date("m/d/Y H:i:s")
                . " Regarding Reader For Checkout : "
                . json_encode($reader)
                . " :\n";

            fwrite($fh, $stringData);
            fclose($fh);
        }

        $this->StripeLog(
            'PAYMENT_PROCESS_INTENT',
            json_encode($reader)
        );

        $status = '';
        $PaymentRes = [];

        /*
         * Existing polling logic.
         *
         * IMPORTANT:
         * We continue checking Stripe for the actual PaymentIntent
         * status. POS response/lag is not considered payment failure.
         */
        while ($attempts < $maxAttempts) {

            try {

                if ($sess_useremail != "gequaldev@gmail.com") {

                    /*
                     * Existing LIVE mode logic.
                     */
                    $PaymentRes = $this->stripe
                        ->paymentIntents
                        ->retrieve($paymentIntentId);

                    $this->StripeLog(
                        'PAYMENT_PROCESS_INTENT_RETRIEVE',
                        json_encode($PaymentRes)
                    );

                    $status = $PaymentRes->status ?? '';

                    $paymentIntentId =
                        $PaymentRes->id ?? $paymentIntentId;

                    $myFile = env('LOG_BASE_PATH')
                        . 'Logs/StripeLog.txt';

                    if (fopen($myFile, 'a+')) {
                        $stringData = '';
                        $fh = fopen($myFile, 'a+');

                        $stringData .= date("m/d/Y H:i:s")
                            . " Regarding Reader Payment Response For Checkout : "
                            . json_encode($PaymentRes)
                            . " :\n";

                        fwrite($fh, $stringData);
                        fclose($fh);
                    }

                } else {

                    /*
                     * Existing TEST mode logic.
                     */
                    $PaymentRes = $this->stripe
                        ->testHelpers
                        ->terminal
                        ->readers
                        ->presentPaymentMethod(
                            $readerId,
                            []
                        );

                    $this->StripeLog(
                        'PAYMENT_PROCESS_TEST_MODE',
                        json_encode($PaymentRes)
                    );

                    $status =
                        $PaymentRes->action->status ?? '';

                    $paymentIntentId =
                        $PaymentRes->action
                            ->process_payment_intent
                            ->payment_intent
                        ?? $paymentIntentId;
                }

            } catch (\Exception $stripeException) {

                /*
                 * Stripe status lookup failed temporarily.
                 *
                 * DO NOT mark the payment as Declined.
                 *
                 * Customer may already have been charged.
                 */
                $this->StripeLog(
                    'PAYMENT_STATUS_RETRIEVE_ERROR',
                    json_encode([
                        'payment_intent_id' => $paymentIntentId,
                        'attempt' => $attempts,
                        'error' => $stripeException->getMessage()
                    ])
                );
            }

            /*
             * Existing final statuses are preserved.
             *
             * Added "canceled" because Stripe can return
             * canceled as the PaymentIntent status.
             */
            if (in_array($status, [
                'succeeded',
                'failed',
                'cancelled',
                'canceled'
            ])) {
                break;
            }

            $attempts++;

            sleep(2);
        }

        $lastResponse = $reader . "---" . $PaymentRes;

        $myFile = env('LOG_BASE_PATH')
            . 'Logs/StripeLog.txt';

        if (fopen($myFile, 'a+')) {
            $stringData = '';
            $fh = fopen($myFile, 'a+');

            $stringData .= date("m/d/Y H:i:s")
                . " Regarding Reader Payment Last Response For Checkout : "
                . json_encode($lastResponse)
                . " :\n";

            fwrite($fh, $stringData);
            fclose($fh);
        }

        /*
         * ---------------------------------------------------------
         * FINAL STRIPE VERIFICATION
         * ---------------------------------------------------------
         *
         * This is the important protection for:
         *
         * POS lag
         * POS freeze
         * network delay
         * delayed Terminal response
         *
         * If Stripe says succeeded, Stripe is the source of truth.
         */
        if (!empty($paymentIntentId)) {

            try {

                $FinalPaymentRes = $this->stripe
                    ->paymentIntents
                    ->retrieve($paymentIntentId);

                $FinalStatus =
                    $FinalPaymentRes->status ?? '';

                $this->StripeLog(
                    'PAYMENT_FINAL_STATUS_CHECK',
                    json_encode([
                        'payment_intent_id' => $paymentIntentId,
                        'status' => $FinalStatus,
                        'attempts' => $attempts
                    ])
                );

                /*
                 * Use the latest Stripe status.
                 */
                if (!empty($FinalStatus)) {

                    $status = $FinalStatus;

                    $PaymentRes = $FinalPaymentRes;

                    $lastResponse =
                        $reader . "---" . $PaymentRes;
                }

            } catch (\Exception $finalStripeException) {

                /*
                 * Do not convert a Stripe verification problem
                 * into a Declined payment.
                 */
                $this->StripeLog(
                    'PAYMENT_FINAL_STATUS_CHECK_ERROR',
                    json_encode([
                        'payment_intent_id' => $paymentIntentId,
                        'error' => $finalStripeException->getMessage()
                    ])
                );
            }
        }

        /*
         * ---------------------------------------------------------
         * EXISTING CART / ORDER LOGIC
         * ---------------------------------------------------------
         */
        $cartItems = collect(
            Session::get('ShoppingCart.Cart', [])
        );

        $grouped = $cartItems->groupBy('OrderType');

        $storeCount = $grouped
            ->get('Store', collect())
            ->count();

        $websiteCount = $grouped
            ->get('Website', collect())
            ->count();

        $storeOrderId = Session::get(
            'ShoppingCart.StoreOrderID'
        ) ?? 0;

        $websiteOrderId = Session::get(
            'ShoppingCart.WebsiteOrderID'
        ) ?? 0;

        $normalOrderId = Session::get(
            'ShoppingCart.OrderID'
        ) ?? 0;

        /*
         * Existing payment insert structure preserved.
         */
        $StoreInsert = array(
            'amount' => NumberFormat($request->amount),
            'Payment_type' => $request->input('PaymentType'),
            'transaction_info' => json_encode($lastResponse),
            'paymentintentid' => $paymentIntentId,
        );

        /*
         * ---------------------------------------------------------
         * PAYMENT STATUS
         * ---------------------------------------------------------
         */

        if ($status === 'succeeded') {

            /*
             * Stripe payment successfully completed.
             */
            $StoreInsert['status'] = 'Completed';

        } elseif (
            $status === 'failed' ||
            $status === 'requires_payment_method'
        ) {

            /*
             * Actual payment failure.
             *
             * Existing failed logic preserved.
             */
            $StoreInsert['status'] = 'Declined';

            $CancelReader = $this->stripe
                ->terminal
                ->readers
                ->cancelAction(
                    $readerId,
                    []
                );

            $this->StripeLog(
                'PAYMENT_FAILED_CANCEL_READER',
                json_encode($CancelReader)
            );

        } elseif (
            $status === 'cancelled' ||
            $status === 'canceled'
        ) {

            /*
             * Actual payment cancellation.
             *
             * Existing cancellation logic preserved.
             */
            $StoreInsert['status'] = 'Canceled';

            $CancelReader = $this->stripe
                ->terminal
                ->readers
                ->cancelAction(
                    $readerId,
                    []
                );

            $this->StripeLog(
                'PAYMENT_CANCELLED_CANCEL_READER',
                json_encode($CancelReader)
            );

        } else {

            /*
             * -----------------------------------------------------
             * IMPORTANT CHANGE
             * -----------------------------------------------------
             *
             * Unknown / processing / timeout status must NOT
             * become Declined.
             *
             * Existing code was doing:
             *
             *     $StoreInsert['status'] = 'Declined';
             *
             * This could create an unpaid/declined order even
             * though Stripe may have successfully charged the
             * customer.
             */

            $this->StripeLog(
                'PAYMENT_STATUS_UNCONFIRMED',
                json_encode([
                    'payment_intent_id' => $paymentIntentId,
                    'status' => $status,
                    'attempts' => $attempts
                ])
            );

            return response()->json([
                'status' => 'verify',
                'message' => 'Payment status could not be confirmed. Please verify the payment before retrying.',
                'payment_intent_id' => $paymentIntentId,
                'stripe_status' => $status
            ]);
        }

        /*
         * ---------------------------------------------------------
         * EXISTING STORE PAYMENT CREATION
         * ---------------------------------------------------------
         *
         * Existing Store + Website / Normal order logic preserved.
         */
        if (
            $storeCount > 0 &&
            $websiteCount > 0 &&
            $storeOrderId &&
            $websiteOrderId
        ) {

            $StoreInsert['orders_id'] = $storeOrderId;

            StorePayment::create($StoreInsert);

            $StoreInsert['orders_id'] = $websiteOrderId;

            StorePayment::create($StoreInsert);

        } elseif ($normalOrderId) {

            $StoreInsert['orders_id'] = $normalOrderId;

            StorePayment::create($StoreInsert);
        }

        /*
         * ---------------------------------------------------------
         * EXISTING RESPONSE LOGIC
         * ---------------------------------------------------------
         */

        if ($status === 'succeeded') {

            return response()->json([
                'status' => 'success',
                'message' => 'Payment of $'
                    . NumberFormat($request->input('amount'))
                    . ' received.',
            ]);

        } elseif (
            $status === 'failed' ||
            $status === 'requires_payment_method'
        ) {

            Session::put(
                'PaymentMessagePOSF',
                "Payment failed or declined"
            );

            return response()->json([
                'status' => 'failed',
                'message' => 'Payment failed or declined.'
            ]);

        } elseif (
            $status === 'cancelled' ||
            $status === 'canceled'
        ) {

            Session::put(
                'PaymentMessagePOS',
                "Payment was cancelled"
            );

            return response()->json([
                'status' => 'cancelled',
                'message' => 'Payment was cancelled by user.'
            ]);

        } else {

            /*
             * Unknown status is NOT payment failure.
             */
            $this->StripeLog(
                'PAYMENT_STATUS_UNCONFIRMED_RESPONSE',
                json_encode([
                    'payment_intent_id' => $paymentIntentId,
                    'status' => $status,
                    'attempts' => $attempts
                ])
            );

            return response()->json([
                'status' => 'verify',
                'message' => 'Payment status could not be confirmed. Please verify the payment before retrying.',
                'payment_intent_id' => $paymentIntentId,
                'stripe_status' => $status
            ]);
        }

    } catch (\Exception $e) {

        /*
         * ---------------------------------------------------------
         * CRITICAL EXCEPTION HANDLING
         * ---------------------------------------------------------
         *
         * Old behavior:
         *
         * Exception
         *    ↓
         * Payment failed
         *
         * This is unsafe because the exception can happen AFTER
         * Stripe has already charged the customer.
         *
         * Therefore check Stripe one final time.
         */

        $this->StripeLog(
            'PAYMENT_PROCESS_EXCEPTION',
            json_encode([
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage()
            ])
        );

        try {

            if (!empty($paymentIntentId)) {

                $ExceptionPaymentRes = $this->stripe
                    ->paymentIntents
                    ->retrieve($paymentIntentId);

                $ExceptionStatus =
                    $ExceptionPaymentRes->status ?? '';

                $this->StripeLog(
                    'PAYMENT_EXCEPTION_FINAL_STRIPE_CHECK',
                    json_encode([
                        'payment_intent_id' => $paymentIntentId,
                        'status' => $ExceptionStatus,
                        'error' => $e->getMessage()
                    ])
                );

                /*
                 * -------------------------------------------------
                 * STRIPE SUCCESS AFTER EXCEPTION
                 * -------------------------------------------------
                 *
                 * Customer has paid.
                 *
                 * We MUST save the payment as Completed before
                 * returning success.
                 */
                if ($ExceptionStatus === 'succeeded') {

                    $cartItems = collect(
                        Session::get(
                            'ShoppingCart.Cart',
                            []
                        )
                    );

                    $grouped =
                        $cartItems->groupBy('OrderType');

                    $storeCount =
                        $grouped
                            ->get('Store', collect())
                            ->count();

                    $websiteCount =
                        $grouped
                            ->get('Website', collect())
                            ->count();

                    $storeOrderId =
                        Session::get(
                            'ShoppingCart.StoreOrderID'
                        ) ?? 0;

                    $websiteOrderId =
                        Session::get(
                            'ShoppingCart.WebsiteOrderID'
                        ) ?? 0;

                    $normalOrderId =
                        Session::get(
                            'ShoppingCart.OrderID'
                        ) ?? 0;

                    $SuccessPaymentInsert = array(
                        'amount' =>
                            NumberFormat($request->amount),

                        'Payment_type' =>
                            $request->input('PaymentType'),

                        'transaction_info' =>
                            json_encode(
                                $ExceptionPaymentRes
                            ),

                        'paymentintentid' =>
                            $paymentIntentId,

                        'status' =>
                            'Completed',
                    );

                    /*
                     * Prevent duplicate payment records
                     * if the payment was already saved before
                     * the exception happened.
                     */
                    if (
                        $storeCount > 0 &&
                        $websiteCount > 0 &&
                        $storeOrderId &&
                        $websiteOrderId
                    ) {

                        $SuccessPaymentInsert['orders_id'] =
                            $storeOrderId;

                        $alreadyStorePayment =
                            StorePayment::where(
                                'paymentintentid',
                                $paymentIntentId
                            )
                            ->where(
                                'orders_id',
                                $storeOrderId
                            )
                            ->exists();

                        if (!$alreadyStorePayment) {
                            StorePayment::create(
                                $SuccessPaymentInsert
                            );
                        }

                        $SuccessPaymentInsert['orders_id'] =
                            $websiteOrderId;

                        $alreadyWebsitePayment =
                            StorePayment::where(
                                'paymentintentid',
                                $paymentIntentId
                            )
                            ->where(
                                'orders_id',
                                $websiteOrderId
                            )
                            ->exists();

                        if (!$alreadyWebsitePayment) {
                            StorePayment::create(
                                $SuccessPaymentInsert
                            );
                        }

                    } elseif ($normalOrderId) {

                        $SuccessPaymentInsert['orders_id'] =
                            $normalOrderId;

                        $alreadyNormalPayment =
                            StorePayment::where(
                                'paymentintentid',
                                $paymentIntentId
                            )
                            ->where(
                                'orders_id',
                                $normalOrderId
                            )
                            ->exists();

                        if (!$alreadyNormalPayment) {
                            StorePayment::create(
                                $SuccessPaymentInsert
                            );
                        }
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' =>
                            'Payment was successfully received.',
                        'payment_intent_id' =>
                            $paymentIntentId
                    ]);
                }

                /*
                 * Actual Stripe failure.
                 */
                if (
                    $ExceptionStatus === 'failed' ||
                    $ExceptionStatus ===
                        'requires_payment_method'
                ) {

                    Session::put(
                        'PaymentMessagePOSF',
                        "Payment failed or declined"
                    );

                    return response()->json([
                        'status' => 'failed',
                        'message' =>
                            'Payment failed or declined.'
                    ]);
                }

                /*
                 * Actual cancellation.
                 */
                if (
                    $ExceptionStatus === 'canceled' ||
                    $ExceptionStatus === 'cancelled'
                ) {

                    Session::put(
                        'PaymentMessagePOS',
                        "Payment was cancelled"
                    );

                    return response()->json([
                        'status' => 'cancelled',
                        'message' =>
                            'Payment was cancelled by user.'
                    ]);
                }
            }

        } catch (\Exception $stripeException) {

            /*
             * Stripe final verification itself failed.
             *
             * Still do NOT call this payment failed.
             */
            $this->StripeLog(
                'PAYMENT_EXCEPTION_STRIPE_CHECK_ERROR',
                json_encode([
                    'payment_intent_id' =>
                        $paymentIntentId,
                    'error' =>
                        $stripeException->getMessage()
                ])
            );
        }

        /*
         * Unknown exception/payment state.
         *
         * IMPORTANT:
         * Do not tell POS "Declined".
         */
        return response()->json([
            'status' => 'verify',
            'message' =>
                'Payment status could not be confirmed. Please verify the payment before retrying.',
            'payment_intent_id' =>
                $paymentIntentId
        ]);
    }
}

public function PaymentCancel(Request $request)
{
    $intentId = $request->input('payment_intent_id');

    try {
        //$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        try {
			 $readerId =  Session::has('ConnectedCardReaderId') ? Session::get('ConnectedCardReaderId') : null;
			$readerRes = $this->stripe->terminal->readers->cancelAction($readerId, []);
			$this->StripeLog('PAYMENT_CANCELLED_CANCEL_READER',json_encode($readerRes));

            $intent = $this->stripe->paymentIntents->cancel($intentId, []);
			$this->StripeLog('PAYMENT_INTENT_CANCEL',json_encode($intent));

            		$myFile = env('LOG_BASE_PATH') .'Logs/StripeLog.txt';

		if (fopen($myFile, 'a+'))
		{
			$stringData = '';
			$fh = fopen($myFile, 'a+');

			$stringData .= date("m/d/Y H:i:s") . " Regarding Reader Cancel Payment Response For Checkout : " .json_encode($readerRes)."--".json_encode($intent)." :\n";

			fwrite($fh, $stringData);
			fclose($fh);
		}

        } catch (\Stripe\Exception\InvalidRequestException $ex) {
			Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");
			$this->StripeLog('PAYMENT_INTENT_CANCEL',$ex->getMessage());
            return response()->json([
                'status'  => 'InvalidError',
                'message' => $ex->getMessage(),
            ]);
        }

        if (isset($intent->status) && ($intent->status === 'canceled'  || $intent->status === 'cancelSuccess')) {

            $cartItems = collect(Session::get('ShoppingCart.Cart', []));
            $grouped = $cartItems->groupBy('OrderType');
            $storeCount = $grouped->get('Store', collect())->count();
            $websiteCount = $grouped->get('Website', collect())->count();
            $storeOrderId = Session::get('ShoppingCart.StoreOrderID') ?? 0;
            $websiteOrderId = Session::get('ShoppingCart.WebsiteOrderID') ?? 0;
            $normalOrderId = Session::get('ShoppingCart.OrderID') ?? 0;

            $StoreInsert = [
                'amount'            => NumberFormat($request->amount),
                'Payment_type'      => $request->input('PaymentType'),
                'transaction_info'  => json_encode($intent),
                'paymentintentid'   => $intentId,
                'status'			=> 'Canceled'
            ];

            if ($storeCount > 0 && $websiteCount > 0 && $storeOrderId && $websiteOrderId) {
                $StoreInsert['orders_id'] = $storeOrderId;
                StorePayment::create($StoreInsert);
                $StoreInsert['orders_id'] = $websiteOrderId;
                StorePayment::create($StoreInsert);
            } elseif ($normalOrderId) {
                $StoreInsert['orders_id'] = $normalOrderId;
                StorePayment::create($StoreInsert);
            }
			Session::put('PaymentMessagePOS',"Payment of $". NumberFormat($request->amount). " has been canceled");
            return response()->json([
                'status' => 'cancelSuccess',
                'intent' => $intent
            ]);
        }
        Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");

        return response()->json([
            'status' => 'InvalidError',
            'intent' => $intent
        ]);

    } catch (\Exception $e) {
		Session::put('PaymentMessagePOS',"You can not cancel this payment,this payment not processed");
		$this->StripeLog('PAYMENT_INTENT_CANCEL',$e->getMessage());
        return response()->json([
            'status'  => 'InvalidError',
            'message' => $e->getMessage(),
        ]);
    }
}

public function StripeLog($event,$eventMessage)
{
	if(empty($event) && empty($eventMessage))
	{
		return;
	}
	$CustomerEmail="";
	if(Session::has('sess_useremail') && !empty(Session::get('sess_useremail')))
	{
		$CustomerEmail = Session::get('sess_useremail');
	}

	$PaymentLog = [
		'response' => $eventMessage,
		'event_name' => $event,
		'storeuser_email' => Session::get('sess_storeuseremail'),
		'customer_email' => $CustomerEmail
	];
	DB::table('pu_payment_logs')->insert($PaymentLog);
}

}
