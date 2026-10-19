<?php

namespace App\Services\Checkout;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    public function __construct(
        protected CartAttributeService $cartAttributeService,
        protected TaxService $taxService,
        protected ShippingService $shippingService,
        protected ShippingInsuranceService $shippingInsuranceService,
        protected GiftWrappingService $giftWrappingService,
        protected ShippingSignatureService $shippingSignatureService,
        protected GiftCertificateService $giftCertificateService,
        protected PaymentAvailabilityService $paymentAvailabilityService,
        protected CheckoutTotalsService $checkoutTotalsService
    ) {
    }


    /**
     * Prepare the initial One Page Checkout page.
     *
     * This replaces the page-initialization portion of the legacy
     * ShoppingcartController::CheckoutPage().
     *
     * Business calculations remain owned by the existing checkout
     * services. The Blade reads the current checkout state from
     * Session, so only required checkout initialization is done here.
     */
    public function prepareCheckout(
        \Illuminate\Http\Request $request
    ): array {
        addLog('CheckoutPrepareStart');

        /*
         * Preserve existing Afterpay / Store checkout session rules.
         */
        if (
            $request->input('method') === 'AP'
        ) {
            config([
                'global.address_verification' => false,
            ]);
        }

        if (
            Auth::guard('store')->check()
            &&
            Session::get('ShoppingCart.OrderType') === 'Store'
        ) {
            Session::forget('shipping_insurance_charge');
            Session::forget('ShoppingCart.ShippingSignature');
        }

        if (Auth::guard('store')->check()) {
            foreach ([
                'ShoppingCart.OrderID',
                'ShoppingCart.StoreOrderID',
                'ShoppingCart.StoreCashSplitPaymentId',
                'ShoppingCart.WebsiteCashSplitPaymentId',
                'ShoppingCart.StoreCashPaymentId',
                'ShoppingCart.WebsiteOrderID',
            ] as $key) {
                Session::forget($key);
            }
        }

        /*
         * Afterpay entry flow.
         */
        if ($request->input('method') === 'pa') {
            Session::forget(
                'ShoppingCart.AfterPay.Checkout_Token'
            );
        }

        /*
         * Checkout cannot be opened without a cart.
         */
        $cart = Session::get('ShoppingCart.Cart', []);

        if (
            !is_array($cart)
            ||
            count($cart) === 0
        ) {
            return [
                'redirect' => redirect('/shoppingcart'),
            ];
        }

        /*
         * Preserve the existing logged-in customer address
         * initialization from CheckoutPage().
         */
        $normalUser = Auth::user();

        if (Auth::guard('store')->check()) {
            $normalUser = Auth::guard('web')->user();
        }

        if ($normalUser) {
            $billingAddress = Session::get(
                'ShoppingCart.BillingAddress',
                []
            );

            if (empty($billingAddress)) {
                $billingAddress = [
                    'first_name' =>
                        $normalUser->first_name ?? '',
                    'last_name' =>
                        $normalUser->last_name ?? '',
                    'company' =>
                        $normalUser->company_name ?? '',
                    'address1' =>
                        $normalUser->address1 ?? '',
                    'address2' =>
                        $normalUser->address2 ?? '',
                    'city' =>
                        $normalUser->city ?? '',
                    'zip' =>
                        $normalUser->zip ?? '',
                    'state' =>
                        $normalUser->state ?? '',
                    'country' =>
                        $normalUser->country ?? 'US',
                    'phone' =>
                        $normalUser->phone ?? '',
                    'email' =>
                        $normalUser->email ?? '',
                    'confirm_email' => '',
                ];

                Session::put(
                    'ShoppingCart.BillingAddress',
                    $billingAddress
                );
            }

            if (
                !Session::has(
                    'ShoppingCart.ShippingAddress'
                )
            ) {
                Session::put(
                    'ShoppingCart.ShippingAddress',
                    [
                        'first_name' => '',
                        'last_name' => '',
                        'company' => '',
                        'address1' => '',
                        'address2' => '',
                        'city' => '',
                        'zip' => '',
                        'state' => '',
                        'country' => 'US',
                        'phone' => '',
                        'email' => '',
                        'confirm_email' => '',
                    ]
                );
            }
        }

        /*
         * Preserve BillingAsShipping when it already exists.
         * Default to Yes for the new checkout Blade.
         */
        if (
            !Session::has(
                'ShoppingCart.BillingAsShipping'
            )
        ) {
            Session::put(
                'ShoppingCart.BillingAsShipping',
                'Yes'
            );
        }

        /*
         * Refresh the checkout state through the new service
         * architecture. This performs tax/insurance/totals/payment
         * calculations using the current session state.
         */
        $checkout = $this->refresh('page');

        /*
         * The new Blade reads its display values directly from
         * the checkout session. Keep the controller/service
         * response available for future AJAX/page initialization.
         */
        $data = [
            'checkout' => $checkout,
        ];

        addLog('CheckoutPrepareEnd', [
            'status' => 'success',
            'only_gc_purchased' =>
                $checkout['onlyGCPurchased'] ?? 0,
        ]);

        return [
            'data' => $data,
        ];
    }

    /**
     * Refresh checkout state.
     *
     * This is the central orchestrator for One Page Checkout.
     *
     * IMPORTANT:
     * This method does not contain discount/tax/shipping
     * business rules itself. It only coordinates services.
     */
    public function refresh(
        string $pageFrom = ''
    ): array {
        addLog(
            'CheckoutRefreshStart',
            [
                'page_from' => $pageFrom,
            ]
        );

        /*
         * ---------------------------------------------------------
         * 1. Cart attributes
         * ---------------------------------------------------------
         */
        $cartAttributes =
            $this->cartAttributeService
                ->getAttributes();

        /*
         * ---------------------------------------------------------
         * 2. Gift Certificate only cart
         * ---------------------------------------------------------
         */
        if (
            ($cartAttributes['onlyGCPurchased'] ?? 0) == 1
        ) {
            $this->clearGiftCertificateOnlyCheckout();
        }

        /*
         * ---------------------------------------------------------
         * 3. Tax
         *
         * Only calculate tax when the cart is not a pure
         * Gift Certificate cart.
         *
         * Address is taken from the current checkout session.
         * ---------------------------------------------------------
         */
        if (
            ($cartAttributes['onlyGCPurchased'] ?? 0) != 1
        ) {
            $this->calculateTax(
                $cartAttributes
            );
        }

        /*
         * ---------------------------------------------------------
         * 4. Shipping insurance
         * ---------------------------------------------------------
         */
        if (
            ($cartAttributes['onlyGCPurchased'] ?? 0) != 1
        ) {
            $this->shippingInsuranceService
                ->calculate('add');
        }

        /*
         * ---------------------------------------------------------
         * 5. Final totals
         * ---------------------------------------------------------
         *
         * Coupon/Reward changes are already present in session.
         * CheckoutTotalsService is the single source of truth.
         */
        $totals =
            $this->checkoutTotalsService
                ->calculate();

        /*
         * ---------------------------------------------------------
         * 6. Payment availability
         * ---------------------------------------------------------
         *
         * Always use the freshly calculated checkout total.
         */
        $paymentAvailability =
            $this->paymentAvailabilityService
                ->getAvailability(
                    $this->resolveOrderTotal(
                        $totals
                    )
                );

        /*
         * ---------------------------------------------------------
         * 7. Final response
         * ---------------------------------------------------------
         */
        $result = [
            'status' => 'success',

            'cartAttributes' =>
                $cartAttributes,

            'paymentAvailability' =>
                $paymentAvailability,

            'totals' =>
                $totals,

            'onlyGCPurchased' =>
                $cartAttributes['onlyGCPurchased']
                ?? 0,
        ];

        addLog(
            'CheckoutRefreshEnd',
            [
                'only_gc_purchased' =>
                    $cartAttributes[
                        'onlyGCPurchased'
                    ]
                    ?? 0,
            ]
        );

        return $result;
    }

    /**
     * Apply or remove a Gift Certificate for One Page Checkout.
     */
    public function setGiftCertificate(
        string $action = 'apply',
        string $code = ''
    ): array {
        $action =
            strtolower(
                trim($action)
            );

        if (
            !in_array(
                $action,
                ['apply', 'remove'],
                true
            )
        ) {
            return [
                'status' => 'error',
                'message' =>
                    'Invalid Gift Certificate action.',
            ];
        }

        addLog(
            'CheckoutGiftCertificateActionStart',
            [
                'action' => $action,
            ]
        );

        try {
            $cartAttributes =
                $this->cartAttributeService
                    ->getAttributes();

            /*
             * Gift Certificate-only carts cannot apply another
             * Gift Certificate.
             */
            if (
                ($cartAttributes['onlyGCPurchased'] ?? 0)
                == 1
            ) {
                $this->giftCertificateService
                    ->remove();

                $totals =
                    $this->checkoutTotalsService
                        ->calculate();

                return [
                    'status' => 'success',
                    'applied' => 'No',
                    'giftCertificate' => [
                        'code' => '',
                        'value' => 0.0,
                    ],
                    'totals' => $totals,
                    'paymentAvailability' =>
                        $this->paymentAvailabilityService
                            ->getAvailability(
                                $this->resolveOrderTotal(
                                    $totals
                                )
                            ),
                    'onlyGCPurchased' => 1,
                ];
            }

            if ($action === 'remove') {
                $result =
                    $this->giftCertificateService
                        ->remove();
            } else {
                $result =
                    $this->giftCertificateService
                        ->apply($code);
            }

            /*
             * Recalculate totals after Gift Certificate state
             * changes. The totals service remains the final source
             * of truth.
             */
            $totals =
                $this->checkoutTotalsService
                    ->calculate();

            $paymentAvailability =
                $this->paymentAvailabilityService
                    ->getAvailability(
                        $this->resolveOrderTotal(
                            $totals
                        )
                    );

            $result['totals'] = $totals;
            $result['paymentAvailability'] =
                $paymentAvailability;
            $result['onlyGCPurchased'] =
                $cartAttributes[
                    'onlyGCPurchased'
                ] ?? 0;

            return $result;
        } catch (\Throwable $e) {
            addLog(
                'CheckoutGiftCertificateActionError',
                [
                    'action' => $action,
                    'code' => $code,
                    'message' =>
                        $e->getMessage(),
                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return [
                'status' => 'error',
                'message' =>
                    'Unable to update Gift Certificate.',
            ];
        }
    }

    /**
     * Set or remove Request Signature for One Page Checkout.
     */
    public function setShippingSignature(
        string $action = 'add'
    ): array {
        $action = strtolower(trim($action));

        if (!in_array($action, ['add', 'remove'], true)) {
            return [
                'status' => 'error',
                'message' =>
                    'Invalid shipping signature action.',
            ];
        }

        addLog('CheckoutShippingSignatureStart', [
            'action' => $action,
        ]);

        try {
            $attributes =
                $this->cartAttributeService->getAttributes();

            if (($attributes['onlyGCPurchased'] ?? 0) == 1) {
                $this->shippingSignatureService->remove();
                $charge = 0.0;
            } else {
                $charge =
                    $this->shippingSignatureService
                        ->calculate($action);
            }

            $totals =
                $this->checkoutTotalsService->calculate();

            $paymentAvailability =
                $this->paymentAvailabilityService
                    ->getAvailability(
                        $this->resolveOrderTotal($totals)
                    );

            return [
                'status' => 'success',
                'shippingSignature' => [
                    'charge' => (float) $charge,
                    'applied' =>
                        $charge > 0 ? 'Yes' : 'No',
                ],
                'totals' => $totals,
                'paymentAvailability' =>
                    $paymentAvailability,
                'onlyGCPurchased' =>
                    $attributes['onlyGCPurchased'] ?? 0,
            ];
        } catch (\Throwable $e) {
            addLog('CheckoutShippingSignatureError', [
                'action' => $action,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'status' => 'error',
                'message' =>
                    'Unable to update shipping signature.',
            ];
        }
    }

    /**
     * Set or remove gift wrapping for One Page Checkout.
     *
     * The configured gift wrapping charge and existing cart
     * selection rules are owned by GiftWrappingService.
     *
     * @param string $action add|remove
     * @param string $productId optional product id
     * @return array
     */
    public function setGiftWrapping(
        string $action = 'add',
        string $productId = ''
    ): array {
        $action =
            strtolower(
                trim($action)
            );

        if (
            !in_array(
                $action,
                ['add', 'remove'],
                true
            )
        ) {
            return [
                'status' => 'error',
                'message' =>
                    'Invalid gift wrapping action.',
            ];
        }

        addLog(
            'CheckoutGiftWrappingStart',
            [
                'action' => $action,
                'product_id' => $productId,
            ]
        );

        try {
            $cartAttributes =
                $this->cartAttributeService
                    ->getAttributes();

            /*
             * Gift Certificate-only carts do not have
             * shipping/gift wrapping charges.
             */
            if (
                ($cartAttributes['onlyGCPurchased'] ?? 0)
                == 1
            ) {
                Session::forget(
                    'ShoppingCart.GiftWrapping'
                );

                $giftWrappingCharge = 0.0;
            } else {
                $giftWrappingCharge =
                    $this->giftWrappingService
                        ->calculate(
                            $action,
                            $productId
                        );
            }

            /*
             * Recalculate totals after the gift wrapping
             * state changes.
             */
            $totals =
                $this->checkoutTotalsService
                    ->calculate();

            $paymentAvailability =
                $this->paymentAvailabilityService
                    ->getAvailability(
                        $this->resolveOrderTotal(
                            $totals
                        )
                    );

            $applied =
                $giftWrappingCharge > 0
                    ? 'Yes'
                    : (
                        $action === 'remove'
                            ? 'No'
                            : (
                                Session::get(
                                    'ShoppingCart.GiftWrapping.Applied'
                                )
                                === 'Yes'
                                    ? 'Yes'
                                    : 'No'
                            )
                    );

            addLog(
                'CheckoutGiftWrappingEnd',
                [
                    'action' => $action,
                    'charge' =>
                        $giftWrappingCharge,
                    'applied' => $applied,
                ]
            );

            return [
                'status' => 'success',

                'giftWrapping' => [
                    'charge' =>
                        (float)
                        $giftWrappingCharge,

                    'applied' =>
                        $applied,
                ],

                'totals' =>
                    $totals,

                'paymentAvailability' =>
                    $paymentAvailability,

                'onlyGCPurchased' =>
                    $cartAttributes[
                        'onlyGCPurchased'
                    ] ?? 0,
            ];
        } catch (\Throwable $e) {
            addLog(
                'CheckoutGiftWrappingError',
                [
                    'action' => $action,
                    'product_id' => $productId,
                    'message' =>
                        $e->getMessage(),
                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return [
                'status' => 'error',
                'message' =>
                    'Unable to update gift wrapping.',
            ];
        }
    }

    /**
     * Set or remove shipping insurance for One Page Checkout.
     *
     * The ShippingInsuranceService remains the source of truth
     * for the existing insurance business rules.
     *
     * @param string $action add|remove
     * @return array
     */
    public function setShippingInsurance(
        string $action = 'add'
    ): array {
        $action = strtolower(trim($action));

        if (!in_array($action, ['add', 'remove'], true)) {
            return [
                'status' => 'error',
                'message' => 'Invalid shipping insurance action.',
            ];
        }

        addLog(
            'CheckoutShippingInsuranceStart',
            [
                'action' => $action,
            ]
        );

        try {
            $cartAttributes =
                $this->cartAttributeService
                    ->getAttributes();

            /*
             * Gift Certificate only carts do not have shipping
             * insurance.
             */
            if (
                ($cartAttributes['onlyGCPurchased'] ?? 0) == 1
            ) {
                Session::forget(
                    'shipping_insurance_charge'
                );

                $insurance = 0;
            } else {
                $insurance =
                    $this->shippingInsuranceService
                        ->calculate($action);

                /*
                 * calculate('remove') intentionally returns null
                 * because the legacy behavior only clears the
                 * session value. Normalize it for the API response.
                 */
                if ($insurance === null) {
                    $insurance = 0;
                }
            }

            /*
             * Final totals are always recalculated after the
             * insurance state changes.
             */
            $totals =
                $this->checkoutTotalsService
                    ->calculate();

            $paymentAvailability =
                $this->paymentAvailabilityService
                    ->getAvailability(
                        $this->resolveOrderTotal(
                            $totals
                        )
                    );

            addLog(
                'CheckoutShippingInsuranceEnd',
                [
                    'action' => $action,
                    'insurance' => $insurance,
                ]
            );

            return [
                'status' => 'success',

                'insurance' =>
                    (float) $insurance,

                'shipping_insurance_charge' =>
                    (float)
                    Session::get(
                        'shipping_insurance_charge',
                        0
                    ),

                'totals' =>
                    $totals,

                'paymentAvailability' =>
                    $paymentAvailability,

                'onlyGCPurchased' =>
                    $cartAttributes[
                        'onlyGCPurchased'
                    ] ?? 0,
            ];
        } catch (\Throwable $e) {
            addLog(
                'CheckoutShippingInsuranceError',
                [
                    'action' => $action,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return [
                'status' => 'error',
                'message' =>
                    'Unable to update shipping insurance.',
            ];
        }
    }

    /**
     * Refresh checkout after shipping method selection.
     *
     * This is the One Page Checkout flow:
     *
     * Shipping
     *   ↓
     * Tax
     *   ↓
     * Insurance
     *   ↓
     * Totals
     */
   public function refreshAfterShippingMethod(
    array $context,
    array $address,
    array $paymentContext = []
): array {
    addLog(
        'CheckoutRefreshAfterShippingStart',
        [
            'shipping_method_id' =>
                $context['shippingMethodId'] ?? 0,

            'country' =>
                $address['country'] ?? '',

            'state' =>
                $address['state'] ?? '',

            'zip' =>
                $address['zip'] ?? '',

            'action' =>
                $paymentContext['action'] ?? '',
        ]
    );

    $shippingMethodId =
        (int) (
            $context['shippingMethodId']
            ?? 0
        );

    $shipCountry =
        trim(
            $address['country']
            ?? ''
        );

    $shipState =
        trim(
            $address['state']
            ?? ''
        );

    $shipZip =
        trim(
            $address['zip']
            ?? ''
        );

    $shipCity =
        trim(
            $address['city']
            ?? ''
        );

    $onlyGCPurchased =
        (int) (
            $context['onlyGCPurchased']
            ?? 0
        );

    $isPayPalSubTotal =
        $context['isPayPalSubTotal']
        ?? 0;

    $shippingChargePayPalProductPage =
        $context[
            'shippingChargePayPalProductPage'
        ]
        ?? 0;

    /*
     * ---------------------------------------------------------
     * 1. Save selected shipping method.
     * ---------------------------------------------------------
     */
    Session::put(
        'ShoppingCart.Shipping.ShippingMethodID',
        $shippingMethodId
    );

    /*
     * ---------------------------------------------------------
     * 2. Save estimated delivery date.
     * ---------------------------------------------------------
     */
    $estimatedDeliveryDate =
        $context[
            'estimatedDeliveryDate'
        ]
        ?? '';

    if (
        $estimatedDeliveryDate !== ''
    ) {
        Session::put(
            'ShoppingCart.EstimatedDeliveryDate',
            $estimatedDeliveryDate
        );

        Session::put(
            'ShoppingCart.Shipping.ShippingDays',
            $estimatedDeliveryDate
        );
    }

    /*
     * ---------------------------------------------------------
     * 3. Shipping method / shipping charge.
     *
     * IMPORTANT:
     * ShippingService owns shipping business rules.
     * ---------------------------------------------------------
     */
    $shippingResult =
        $this->shippingService
            ->setShippingMethod(
                $shippingMethodId,
                $shipCountry,
                $shipState,
                $shipZip,
                [
                    'IsCosmo' =>
                        $context['IsCosmo'] ?? 'No',

                    'IsNandansons' =>
                        $context['IsNandansons'] ?? 'No',

                    'IsPerfumePW' =>
                        $context['IsPerfumePW'] ?? 'No',

                    'IsPCA' =>
                        $context['IsPCA'] ?? 'No',

                    'IsND' =>
                        $context['IsND'] ?? 'No',

                    'IsVenderItem' =>
                        $context['IsVenderItem'] ?? 'No',

                    'action' =>
                        $paymentContext['action'] ?? '',

                    'isPayPalSubTotal' =>
                        $isPayPalSubTotal,

                    'shippingChargePayPalProductPage' =>
                        $shippingChargePayPalProductPage,
                ]
            );

    /*
     * Shipping failure.
     */
    if (
        is_array($shippingResult)
        &&
        isset(
            $shippingResult['status']
        )
        &&
        $shippingResult['status'] !== 'success'
    ) {
        return [
            'status' =>
                $shippingResult['status'],

            'shipping' =>
                $shippingResult,
        ];
    }

    /*
     * ---------------------------------------------------------
     * 4. Gift Certificate only cart.
     *
     * No shipping/tax/insurance.
     * ---------------------------------------------------------
     */
    if (
        $onlyGCPurchased === 1
    ) {
        Session::forget(
            'ShoppingCart.Shipping'
        );

        Session::forget(
            'ShoppingCart.Tax'
        );

        Session::forget(
            'ShoppingCart.GiftWrapping'
        );

        Session::forget(
            'ShoppingCart.ShippingSignature'
        );

        Session::forget(
            'shipping_insurance_charge'
        );

        $taxResult = null;
        $insurance = 0;
    } else {
        /*
         * -----------------------------------------------------
         * 5. Tax
         * -----------------------------------------------------
         */
        $taxResult =
            $this->taxService
                ->calculate(
                    $shipCountry,
                    $shipState,
                    $shipZip,
                    $onlyGCPurchased,
                    $isPayPalSubTotal,
                    $shipCity,
                    $shippingChargePayPalProductPage
                );

        /*
         * -----------------------------------------------------
         * 6. Shipping Insurance
         * -----------------------------------------------------
         */
        $insurance =
            $this->shippingInsuranceService
                ->calculate(
                    'add'
                );
    }

    /*
     * ---------------------------------------------------------
     * 7. Request Signature validation.
     *
     * If an already-applied signature is no longer eligible
     * after shipping/address/total changes, clear it.
     * ---------------------------------------------------------
     */
    $this->shippingSignatureService->sync();

    /*
     * ---------------------------------------------------------
     * 7. Final totals.
     *
     * Totals service is the final source of truth.
     * ---------------------------------------------------------
     */
    $totals =
        $this->checkoutTotalsService
            ->calculate();

    /*
     * ---------------------------------------------------------
     * 8. Payment availability.
     * ---------------------------------------------------------
     */
    $paymentAvailability =
        $this->paymentAvailabilityService
            ->getAvailability(
                $this->resolveOrderTotal(
                    $totals
                )
            );

    /*
     * ---------------------------------------------------------
     * 9. Response.
     * ---------------------------------------------------------
     */
    $result = [
        'status' =>
            'success',

        'shipping' =>
            $shippingResult,

        'tax' =>
            $taxResult,

        'insurance' =>
            $insurance,

        'totals' =>
            $totals,

        'paymentAvailability' =>
            $paymentAvailability,

        'onlyGCPurchased' =>
            $onlyGCPurchased,

        'estimatedDeliveryDate' =>
            $estimatedDeliveryDate,
    ];

    addLog(
        'CheckoutRefreshAfterShippingEnd',
        [
            'shipping_method_id' =>
                $shippingMethodId,

            'status' =>
                'success',
        ]
    );

    return $result;
}

protected function resolveOrderTotal(
    $totals
): float {
    /*
     * Prefer CheckoutTotalsService result.
     */
    if (
        is_array($totals)
    ) {
        foreach (
            [
                'NetTotal',
                'netTotal',
                'Total',
                'total',
                'GrandTotal',
                'grandTotal',
            ] as $key
        ) {
            if (
                isset(
                    $totals[$key]
                )
            ) {
                return (float)
                    $totals[$key];
            }
        }
    }

    /*
     * Fallback to existing session total.
     */
    return (float)
        Session::get(
            'ShoppingCart.NetTotal',
            0
        );
}


    /**
     * Calculate tax from current checkout address.
     */
    protected function calculateTax(
        array $cartAttributes
    ) {
        $shippingAddress =
            Session::get(
                'ShoppingCart.ShippingAddress',
                []
            );

        /*
         * If BillingAsShipping is used, preserve the existing
         * checkout behavior by resolving the address accordingly.
         */
        $billingAsShipping =
            Session::get(
                'ShoppingCart.BillingAsShipping'
            );

        if (
            $billingAsShipping === 'Yes'
        ) {
            $billingAddress =
                Session::get(
                    'ShoppingCart.BillingAddress',
                    []
                );

            if (
                !empty(
                    $billingAddress
                )
            ) {
                $shippingAddress =
                    $billingAddress;
            }
        }

        $country =
            $shippingAddress['country']
            ?? '';

        $state =
            $shippingAddress['state']
            ?? '';

        $zip =
            $shippingAddress['zip']
            ?? '';

        $city =
            $shippingAddress['city']
            ?? '';

        /*
         * No address = no tax calculation yet.
         *
         * One Page Checkout may call refresh before the
         * customer has completed the address.
         */
        if (
            $country === ''
            ||
            $state === ''
            ||
            $zip === ''
        ) {
            return null;
        }

        return $this->taxService
            ->calculate(
                $country,
                $state,
                $zip,
                (int) (
                    $cartAttributes[
                        'onlyGCPurchased'
                    ]
                    ?? 0
                ),
                0,
                $city,
                0
            );
    }

    /**
     * Remove checkout-only values for a pure Gift Certificate cart.
     *
     * This is the existing SetupCart() behavior.
     */
    protected function clearGiftCertificateOnlyCheckout(): void
    {
        Session::forget(
            'ShoppingCart.Shipping'
        );

        Session::forget(
            'ShoppingCart.Tax'
        );

        Session::forget(
            'ShoppingCart.GiftWrapping'
        );

        Session::forget(
            'ShoppingCart.ShippingSignature'
        );

        Session::forget(
            'shipping_insurance_charge'
        );
    }

    /**
     * Read the current order total.
     *
     * This is only used for payment availability.
     *
     * CheckoutTotalsService remains the source of truth for
     * the final checkout total.
     */
    protected function getCurrentOrderTotal(): float
    {
        /*
         * Compatibility wrapper for any legacy callers.
         *
         * CheckoutTotalsService owns the checkout total calculation.
         */
        return $this->checkoutTotalsService
            ->getNetTotal();
    }
public function getAvailableShippingMethods(
    array $address
): array {
    $country = trim(
        $address['country'] ?? ''
    );

    $state = trim(
        $address['state'] ?? ''
    );

    $zip = trim(
        $address['zip'] ?? ''
    );

    /*
     * Keep address in checkout session.
     */
    $currentAddress = Session::get(
        'ShoppingCart.ShippingAddress',
        []
    );

    Session::put(
        'ShoppingCart.ShippingAddress',
        array_merge(
            $currentAddress,
            $address
        )
    );

    /*
     * Cart vendor attributes are required by the existing
     * shipping availability logic.
     */
    $cartAttributes =
        $this->cartAttributeService
            ->getAttributes();

    /*
     * ShippingService is responsible for the actual
     * CheckAvailableShippingMethod() logic.
     */
    $shippingMethods =
        $this->shippingService
            ->getAvailableMethods(
                $zip,
                $state,
                $country,
                $cartAttributes
            );

    return [
        'status' =>
            'success',

        'shippingMethods' =>
            $shippingMethods,

        'selectedShippingMethodId' =>
            (int) Session::get(
                'ShoppingCart.Shipping.ShippingMethodID',
                0
            ),

        'address' =>
            Session::get(
                'ShoppingCart.ShippingAddress',
                []
            ),
    ];
}    
}