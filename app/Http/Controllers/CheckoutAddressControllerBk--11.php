<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutAddressController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService
    ) {
    }

    /**
     * Update checkout billing/shipping address.
     *
     * One Page Checkout:
     *
     * Address
     *   ↓
     * Session Address
     *   ↓
     * Tax / Shipping / Totals refresh
     */
    public function update(
        Request $request
    ): JsonResponse {
        if (!$request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request.',
            ], 400);
        }

        addLog(
            'CheckoutAddressUpdateStart',
            [
                'request' =>
                    json_encode(
                        $request->all()
                    ),
            ]
        );

        /*
         * ---------------------------------------------------------
         * Validate minimum address information.
         *
         * Do not force state for non-US because the existing
         * logic supports other_state.
         * ---------------------------------------------------------
         */
        $country =
            trim(
                (string)
                $request->input(
                    'ship_country',
                    $request->input(
                        'country',
                        ''
                    )
                )
            );

        $state =
            trim(
                (string)
                $request->input(
                    'ship_state',
                    $request->input(
                        'state',
                        ''
                    )
                )
            );

        $zip =
            trim(
                (string)
                $request->input(
                    'ship_zip',
                    $request->input(
                        'zip',
                        ''
                    )
                )
            );

        $city =
            trim(
                (string)
                $request->input(
                    'ship_city',
                    $request->input(
                        'city',
                        ''
                    )
                )
            );

        if (
            $country === ''
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Country is required.',
            ], 422);
        }

        /*
         * ---------------------------------------------------------
         * Build billing data.
         * ---------------------------------------------------------
         */
        $billing = $this->buildBillingData(
            $request
        );

        /*
         * ---------------------------------------------------------
         * Build shipping data.
         * ---------------------------------------------------------
         */
        $shipping = $this->buildShippingData(
            $request
        );

        /*
         * ---------------------------------------------------------
         * Same as billing.
         *
         * Existing SetShippingAddress() uses BillingAddress
         * when sameasbill = Yes.
         * ---------------------------------------------------------
         */
        $sameAsBilling =
            $request->input(
                'sameasbill',
                $request->input(
                    'same_as_billing',
                    'No'
                )
            );

        $context = [
            'billing' =>
                $billing,

            'shipping' =>
                $shipping,

            'sameAsBilling' =>
                $sameAsBilling,

            'onlyGCPurchased' =>
                (int)
                $request->input(
                    'onlyGCPurchased',
                    0
                ),

            /*
             * Preserve direct payment flows.
             */
            'action' =>
                $request->input(
                    'action',
                    ''
                ),

            'isPayPalSubTotal' =>
                $request->input(
                    'isPayPalSubTotal',
                    0
                ),

            'shippingChargePayPalProductPage' =>
                $request->input(
                    'shipping_charge_paypal_product_page',
                    0
                ),
        ];

        try {
            /*
             * -----------------------------------------------------
             * Save address + refresh checkout.
             * -----------------------------------------------------
             */
            $result =
                $this->checkoutService
                    ->updateAddress(
                        $context
                    );

            addLog(
                'CheckoutAddressUpdateEnd',
                [
                    'status' =>
                        $result['status']
                        ?? 'success',
                ]
            );

            return response()->json(
                $result
            );
        } catch (
            \Throwable $e
        ) {
            addLog(
                'CheckoutAddressUpdateError',
                [
                    'message' =>
                        $e->getMessage(),

                    'request' =>
                        $request->all(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'status' => 'error',
                'message' =>
                    'Unable to update checkout address.',
            ], 500);
        }
    }

    /**
     * Build billing payload using the existing field names.
     */
    protected function buildBillingData(
        Request $request
    ): array {
        $country =
            $request->input(
                'bill_country',
                $request->input(
                    'country',
                    ''
                )
            );

        $state =
            $request->input(
                'bill_state',
                $request->input(
                    'state',
                    ''
                )
            );

        $otherState =
            $request->input(
                'bill_other_state',
                ''
            );

        return [
            'bill_country' =>
                $country,

            'bill_fname' =>
                $request->input(
                    'bill_fname',
                    $request->input(
                        'first_name',
                        ''
                    )
                ),

            'bill_lname' =>
                $request->input(
                    'bill_lname',
                    $request->input(
                        'last_name',
                        ''
                    )
                ),

            'bill_company' =>
                $request->input(
                    'bill_company',
                    $request->input(
                        'company',
                        ''
                    )
                ),

            'bill_address1' =>
                $request->input(
                    'bill_address1',
                    $request->input(
                        'address1',
                        ''
                    )
                ),

            'bill_address2' =>
                $request->input(
                    'bill_address2',
                    $request->input(
                        'address2',
                        ''
                    )
                ),

            'bill_city' =>
                $request->input(
                    'bill_city',
                    $request->input(
                        'city',
                        ''
                    )
                ),

            'bill_state' =>
                $state,

            'bill_other_state' =>
                $otherState,

            'bill_zip' =>
                $request->input(
                    'bill_zip',
                    $request->input(
                        'zip',
                        ''
                    )
                ),

            'bill_phone' =>
                $request->input(
                    'bill_phone',
                    $request->input(
                        'phone',
                        ''
                    )
                ),

            'bill_email' =>
                $request->input(
                    'bill_email',
                    $request->input(
                        'email',
                        ''
                    )
                ),

            'bill_cemail' =>
                $request->input(
                    'bill_cemail',
                    $request->input(
                        'confirm_email',
                        $request->input(
                            'email',
                            ''
                        )
                    )
                ),

            'sameasbill' =>
                $request->input(
                    'sameasbill',
                    'No'
                ),
        ];
    }

    /**
     * Build shipping payload using the existing field names.
     */
    protected function buildShippingData(
        Request $request
    ): array {
        return [
            'ship_country' =>
                $request->input(
                    'ship_country',
                    $request->input(
                        'country',
                        ''
                    )
                ),

            'ship_fname' =>
                $request->input(
                    'ship_fname',
                    $request->input(
                        'first_name',
                        ''
                    )
                ),

            'ship_lname' =>
                $request->input(
                    'ship_lname',
                    $request->input(
                        'last_name',
                        ''
                    )
                ),

            'ship_company' =>
                $request->input(
                    'ship_company',
                    $request->input(
                        'company',
                        ''
                    )
                ),

            'ship_address1' =>
                $request->input(
                    'ship_address1',
                    $request->input(
                        'address1',
                        ''
                    )
                ),

            'ship_address2' =>
                $request->input(
                    'ship_address2',
                    $request->input(
                        'address2',
                        ''
                    )
                ),

            'ship_city' =>
                $request->input(
                    'ship_city',
                    $request->input(
                        'city',
                        ''
                    )
                ),

            'ship_state' =>
                $request->input(
                    'ship_state',
                    $request->input(
                        'state',
                        ''
                    )
                ),

            'ship_other_state' =>
                $request->input(
                    'ship_other_state',
                    ''
                ),

            'ship_zip' =>
                $request->input(
                    'ship_zip',
                    $request->input(
                        'zip',
                        ''
                    )
                ),

            'ship_phone' =>
                $request->input(
                    'ship_phone',
                    $request->input(
                        'phone',
                        ''
                    )
                ),

            'ship_email' =>
                $request->input(
                    'ship_email',
                    $request->input(
                        'email',
                        ''
                    )
                ),

            'sameasbill' =>
                $request->input(
                    'sameasbill',
                    'No'
                ),
        ];
    }
}