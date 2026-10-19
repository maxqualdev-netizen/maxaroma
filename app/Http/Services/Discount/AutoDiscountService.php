<?php

namespace App\Services\Discount;

use App\Models\AutoDiscount;
use App\Models\Coupon;
use App\Models\Manufacture;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Constants\CheckoutConstants;
class AutoDiscountService
{
  

    /**
     * Apply automatic discount to current cart.
     *
     * Existing ApplyAutoDiscount() behavior is being moved here.
     */
    public function apply(): ?void
    {
        $autoDiscount = 0;
        $newSubTotal = 0;
        $discountCouponFlag = '';

        $log = [
            'pocketPerfumeCategory' =>
                json_encode(
                    CheckoutConstants::POCKET_PERFUME_CATEGORIES
                ),
        ];

        addLog(
            'ApplyAutoDiscountStart',
            $log
        );

        /*
         * No cart -> nothing to calculate.
         */
        if (
            !Session::has('ShoppingCart.Cart') ||
            count(
                Session::get('ShoppingCart.Cart', [])
            ) === 0
        ) {
            return null;
        }

        /*
         * Existing logic clears previous Auto Discount
         * before recalculating.
         */
        $this->clearAutoDiscount();

        /*
         * ---------------------------------------------------------
         * Wholesaler restriction
         * ---------------------------------------------------------
         *
         * Existing:
         *
         * logged-in wholesaler -> no auto discount
         */
        $normalUser = Auth::user();

        if (Auth::guard('store')->check()) {
            $normalUser = Auth::guard('web')->user();
        }

        if (
            $normalUser &&
            Session::get('eusertype') === 'Wholesaler'
        ) {
            Session::put(
                'ShoppingCart.AutoDiscount',
                0
            );

            return null;
        }

        /*
         * Existing phone-order wholesaler restriction.
         */
        if (
            Session::has('isPhoneOrder') &&
            Session::has('eusertype') &&
            Session::get('eusertype') === 'Wholesaler'
        ) {
            Session::put(
                'ShoppingCart.AutoDiscount',
                0
            );

            return null;
        }

        /*
         * ---------------------------------------------------------
         * Prepare subtotal
         * ---------------------------------------------------------
         */
        if (
            Session::has(
                'ShoppingCart.SubTotal'
            )
        ) {
            $newSubTotal = NumberFormat(
                Session::get(
                    'ShoppingCart.SubTotal'
                )
            );
        }

        $giftCertificateTotal = 0;

        if (
            Session::has(
                'ShoppingCart.GiftCertiTotal'
            )
        ) {
            $giftCertificateTotal =
                NumberFormat(
                    Session::get(
                        'ShoppingCart.GiftCertiTotal'
                    )
                );
        }

        $subTotal =
            $newSubTotal
            - $giftCertificateTotal;

        /*
         * Existing getDealSubTotal() must be migrated
         * into a dedicated Cart/Discount helper.
         *
         * For now read the existing session value if present.
         */
        $dealSubTotal =
            $this->getDealSubTotal();

        $subTotal =
            $subTotal
            - $dealSubTotal;

        addLog(
            'ApplyAutoDiscount',
            [
                'NewSubTotal' =>
                    $newSubTotal,

                'GiftCertiTotal' =>
                    $giftCertificateTotal,

                'DealSubTotal' =>
                    $dealSubTotal,

                'subTotal' =>
                    $subTotal,
            ]
        );

        /*
         * Existing rule:
         * no positive subtotal => no auto discount.
         */
        if ($subTotal <= 0) {

            $this->clearAutoDiscount();

            return null;
        }

        /*
         * ---------------------------------------------------------
         * Coupon autodiscount flag
         * ---------------------------------------------------------
         */
        $couponCode = Session::get(
            'ShoppingCart.PromoCoupon.CouponCode',
            ''
        );

        $today = date('Y-m-d');

        if ($couponCode !== '') {

            $coupon = Coupon::select(
                'autodiscount_flag'
            )
                ->where(
                    'coupon_number',
                    $couponCode
                )
                ->where(
                    'status',
                    '1'
                )
                ->where(
                    'start_date',
                    '<=',
                    $today
                )
                ->where(
                    'end_date',
                    '>=',
                    $today
                )
                ->first();

            if ($coupon) {

                addLog(
                    'ApplyAutoDiscount',
                    [
                        'coupon_res' =>
                            $coupon,
                    ]
                );

                if (
                    $coupon->autodiscount_flag ===
                    'No'
                ) {
                    $this->clearAutoDiscount();

                    return null;
                }
            }
        }

        /*
         * ---------------------------------------------------------
         * Load applicable AutoDiscount rules.
         * ---------------------------------------------------------
         */
        $baseQuery = AutoDiscount::where(
            'start_date',
            '<=',
            $today
        )
            ->where(
                'end_date',
                '>=',
                $today
            )
            ->where(
                'status',
                '1'
            );

        /*
         * First preference:
         * subtotal falls inside order amount range.
         */
        $autoRules = (clone $baseQuery)
            ->where(
                'end_order_amount',
                '>=',
                $subTotal
            )
            ->where(
                'order_amount',
                '<=',
                $subTotal
            )
            ->orderByDesc(
                'end_order_amount'
            )
            ->get();

        /*
         * Existing fallback.
         */
        if ($autoRules->isEmpty()) {

            $autoRules = (clone $baseQuery)
                ->where(
                    'end_order_amount',
                    '<=',
                    $subTotal
                )
                ->orderByDesc(
                    'end_order_amount'
                )
                ->get();
        }

        addLog(
            'ApplyAutoDiscount',
            [
                'AutoRS' =>
                    $autoRules,
            ]
        );

        if ($autoRules->isEmpty()) {
            return null;
        }

        /*
         * ---------------------------------------------------------
         * Cart preparation
         * ---------------------------------------------------------
         */
        $cart = Session::get(
            'ShoppingCart.Cart',
            []
        );

        $totalItems =
            count($cart);

        $totalAutoDiscountRecords =
            $autoRules->count();

        $totalExcludePrice = 0;

        $amountBasedDiscountExcludeSku =
            'No';

        $skuRemoveArr = '';

        /*
         * Existing active manufacturer cache.
         */
        $activeBrandIds =
            $this->getActiveBrandIds();

        /*
         * ---------------------------------------------------------
         * Process each AutoDiscount rule
         * ---------------------------------------------------------
         */
        for (
            $i = 0;
            $i < $totalAutoDiscountRecords;
            $i++
        ) {

            $rule = $autoRules[$i];

            $discountCouponFlag =
                $rule->discount_coupon_flag;

            $excludeSkuList =
                $this->getExcludedSkus(
                    $rule->exclude_sku
                );

            $totalExcludePrice = 0;

            /*
             * Excluded SKU price.
             */
            if ($totalItems > 0) {

                $totalExcludePrice =
                    $this->calculateExcludedSkuPrice(
                        $cart,
                        $excludeSkuList
                    );
            }

            /*
             * Exclude pocket perfume.
             */
            if (
                $rule->exclude_pocketperfume ===
                'Yes'
            ) {
                $totalExcludePrice +=
                    $this->calculatePocketPerfumePrice(
                        $cart
                    );
            }

            /*
             * -----------------------------------------------------
             * SKU based rule
             * -----------------------------------------------------
             *
             * The existing code has SKU/order branches.
             */
            if (
                isset($rule->orders) &&
                $rule->orders === '0'
            ) {

                $result =
                    $this->applySkuRule(
                        $rule,
                        $cart,
                        $excludeSkuList,
                        $skuRemoveArr,
                        $subTotal,
                        $totalExcludePrice
                    );

                if ($result['matched']) {

                    $autoDiscount =
                        $this->mergeDiscount(
                            $autoDiscount,
                            $result['discount']
                        );

                    $skuRemoveArr =
                        $result['skuRemoveArr'];

                    $amountBasedDiscountExcludeSku =
                        $result[
                            'amountBasedDiscountExcludeSku'
                        ];

                    $discountCouponFlag =
                        $result['discountCouponFlag'];
                }
            }

            /*
             * -----------------------------------------------------
             * Brand / order rule
             * -----------------------------------------------------
             */
            elseif (
                isset($rule->orders) &&
                $rule->orders === '1'
            ) {

                if (
                    !empty(
                        trim(
                            (string) $rule->sku
                        )
                    )
                ) {

                    $result =
                        $this->applyBrandRule(
                            $rule,
                            $cart,
                            $excludeSkuList,
                            $skuRemoveArr,
                            $activeBrandIds
                        );

                    if ($result['matched']) {

                        $autoDiscount =
                            $this->mergeDiscount(
                                $autoDiscount,
                                $result['discount']
                            );

                        $skuRemoveArr =
                            $result['skuRemoveArr'];

                        $discountCouponFlag =
                            $result[
                                'discountCouponFlag'
                            ];
                    }
                }
            }

            /*
             * -----------------------------------------------------
             * Amount based fallback rule
             * -----------------------------------------------------
             */
            else {

                $result =
                    $this->applyAmountBasedRule(
                        $rule,
                        $cart,
                        $excludeSkuList,
                        $skuRemoveArr,
                        $subTotal,
                        $totalExcludePrice
                    );

                if ($result['matched']) {

                    $autoDiscount =
                        $result['discount'];

                    $discountCouponFlag =
                        $result[
                            'discountCouponFlag'
                        ];

                    break;
                }
            }
        }

        /*
         * ---------------------------------------------------------
         * Save final result
         * ---------------------------------------------------------
         */
        Session::put(
            'ShoppingCart.AutoDiscount',
            NumberFormat(
                $autoDiscount
            )
        );

        Session::put(
            'ShoppingCart.AutoDiscountFlag',
            $discountCouponFlag
        );

        addLog(
            'ApplyAutoDiscountEnd',
            [
                'AutoDiscount' =>
                    $autoDiscount,

                'discount_coupon_flag' =>
                    $discountCouponFlag,
            ]
        );

        return null;
    }

    /**
     * Clear existing automatic discount.
     */
    protected function clearAutoDiscount(): void
    {
        Session::put(
            'ShoppingCart.AutoDiscount',
            0
        );

        Session::put(
            'ShoppingCart.AutoDiscountFlag',
            ''
        );
    }

    /**
     * Existing pocket perfume categories.
     */
    protected function isPocketPerfume($categoryId): bool
    {
        return in_array(
            (int) $categoryId,
            CheckoutConstants::POCKET_PERFUME_CATEGORIES,
            true
        );
    }

    /**
     * Parse excluded SKU list.
     */
    protected function getExcludedSkus(
        ?string $value
    ): array {
        if (
            empty(trim((string) $value))
        ) {
            return [];
        }

        return array_values(
            array_filter(
                array_unique(
                    array_map(
                        'trim',
                        explode(',', $value)
                    )
                ),
                'strlen'
            )
        );
    }

    /**
     * Calculate excluded SKU price.
     */
    protected function calculateExcludedSkuPrice(
        array $cart,
        array $excludedSkus
    ): float {
        if (empty($excludedSkus)) {
            return 0;
        }

        $total = 0;

        foreach ($cart as $item) {

            if (
                isset($item['SKU']) &&
                in_array(
                    $item['SKU'],
                    $excludedSkus,
                    true
                )
            ) {
                $total +=
                    (float) (
                        $item['TotPrice'] ?? 0
                    );
            }
        }

        return $total;
    }

    /**
     * Calculate pocket perfume exclusion.
     */
    protected function calculatePocketPerfumePrice(
        array $cart
    ): float {
        $total = 0;

        foreach ($cart as $item) {

            if (
                !empty(
                    $item['CategoryID']
                ) &&
                $this->isPocketPerfume(
                    $item['CategoryID']
                )
            ) {
                $total +=
                    (float) (
                        $item['TotPrice'] ?? 0
                    );
            }
        }

        return $total;
    }

    /**
     * Active manufacturer IDs.
     *
     * Existing Cache::rememberForever() logic.
     */
    protected function getActiveBrandIds(): array
    {
        return Cache::rememberForever(
            'active_brand_ids',
            function () {
                return Manufacture::where(
                    'status',
                    '1'
                )
                    ->pluck(
                        'imanufactureid'
                    )
                    ->map(
                        fn ($id) =>
                            (string) $id
                    )
                    ->toArray();
            }
        );
    }

    /**
     * SKU rule processor.
     *
     * NOTE:
     * The source has additional branches which must remain
     * exactly aligned with the latest CartTrait.
     */
    protected function applySkuRule(
        $rule,
        array $cart,
        array $excludedSkus,
        string $skuRemoveArr,
        float $subTotal,
        float $totalExcludePrice
    ): array {
        $matched = false;
        $discount = 0;
        $amountBasedExclude = 'No';

        $ruleSkus = $this->getExcludedSkus(
            $rule->sku
        );

        $removeSkus = $this->csvToArray(
            $skuRemoveArr
        );

        $discountBase = 0;

        foreach ($cart as $item) {

            $sku = $item['SKU'] ?? '';

            if (
                !in_array(
                    $sku,
                    $ruleSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                ($item['IsDealProducts'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['IS_Free_Gift'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['Is_Free_Sample'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $excludedSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $removeSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                $rule->exclude_pocketperfume ===
                'Yes' &&
                $this->isPocketPerfume(
                    $item['CategoryID'] ?? 0
                )
            ) {
                continue;
            }

            $matched = true;

            $lineAmount =
                (float) (
                    $item['Price'] ?? 0
                )
                *
                (float) (
                    $item['Qty'] ?? 0
                );

            $discountBase +=
                $lineAmount;

            $skuRemoveArr .=
                $sku . ',';

            if (
                (int) $rule->type === 1
            ) {
                $itemDiscount =
                    $lineAmount
                    *
                    (
                        (float)
                        $rule->auto_discount_amount
                        / 100
                    );

                $this->putItemDiscount(
                    $item,
                    $cart,
                    $itemDiscount
                );
            }
        }

        if (!$matched) {
            return [
                'matched' => false,
                'discount' => 0,
                'skuRemoveArr' =>
                    $skuRemoveArr,
                'amountBasedDiscountExcludeSku' =>
                    $amountBasedExclude,
                'discountCouponFlag' =>
                    $rule->discount_coupon_flag,
            ];
        }

        if (
            (int) $rule->type === 1
        ) {
            $discount =
                $discountBase
                *
                (
                    (float)
                    $rule->auto_discount_amount
                    / 100
                );
        } else {
            $discount =
                (float)
                $rule->auto_discount_amount;
        }

        return [
            'matched' => true,
            'discount' => $discount,
            'skuRemoveArr' =>
                $skuRemoveArr,
            'amountBasedDiscountExcludeSku' =>
                $amountBasedExclude,
            'discountCouponFlag' =>
                $rule->discount_coupon_flag,
        ];
    }

    /**
     * Brand rule processor.
     */
    protected function applyBrandRule(
        $rule,
        array $cart,
        array $excludedSkus,
        string $skuRemoveArr,
        array $activeBrandIds
    ): array {
        $matched = false;
        $discountBase = 0;

        $brandIds =
            array_values(
                array_intersect(
                    $this->csvToArray(
                        $rule->sku
                    ),
                    $activeBrandIds
                )
            );

        if (empty($brandIds)) {
            return [
                'matched' => false,
                'discount' => 0,
                'skuRemoveArr' =>
                    $skuRemoveArr,
                'discountCouponFlag' =>
                    $rule->discount_coupon_flag,
            ];
        }

        $productIds =
            collect($cart)
                ->pluck('ProductID')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

        addLog(
            'ApplyAutoDiscount',
            [
                'arr_active_BrandID' =>
                    $brandIds,

                'temp_prod_id' =>
                    $productIds,
            ]
        );

        $brandProductIds =
            Products::whereIn(
                'imanufactureid',
                $brandIds
            )
                ->whereIn(
                    'products_id',
                    $productIds
                )
                ->distinct()
                ->pluck(
                    'products_id'
                )
                ->toArray();

        addLog(
            'ApplyAutoDiscount',
            [
                'brand_prod_id' =>
                    $brandProductIds,
            ]
        );

        $removeSkus =
            $this->csvToArray(
                $skuRemoveArr
            );

        foreach ($cart as $item) {

            $sku =
                $item['SKU'] ?? '';

            if (
                !in_array(
                    $item['ProductID'] ?? null,
                    $brandProductIds
                )
            ) {
                continue;
            }

            if (
                ($item['IsDealProducts'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['IS_Free_Gift'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['Is_Free_Sample'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $excludedSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $removeSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                $rule->exclude_pocketperfume ===
                'Yes' &&
                $this->isPocketPerfume(
                    $item['CategoryID'] ?? 0
                )
            ) {
                continue;
            }

            $matched = true;

            $lineAmount =
                (float) (
                    $item['Price'] ?? 0
                )
                *
                (float) (
                    $item['Qty'] ?? 0
                );

            $discountBase +=
                $lineAmount;

            $skuRemoveArr .=
                $sku . ',';

            if (
                (int) $rule->type === 1
            ) {
                $itemDiscount =
                    $lineAmount
                    *
                    (
                        (float)
                        $rule->auto_discount_amount
                        / 100
                    );

                $this->putItemDiscount(
                    $item,
                    $cart,
                    $itemDiscount
                );
            }
        }

        if (!$matched) {
            return [
                'matched' => false,
                'discount' => 0,
                'skuRemoveArr' =>
                    $skuRemoveArr,
                'discountCouponFlag' =>
                    $rule->discount_coupon_flag,
            ];
        }

        $discount =
            (int) $rule->type === 1
                ? $discountBase *
                    (
                        (float)
                        $rule->auto_discount_amount
                        / 100
                    )
                : (float)
                    $rule->auto_discount_amount;

        return [
            'matched' => true,
            'discount' => $discount,
            'skuRemoveArr' =>
                $skuRemoveArr,
            'discountCouponFlag' =>
                $rule->discount_coupon_flag,
        ];
    }

    /**
     * Amount-based fallback rule.
     */
    protected function applyAmountBasedRule(
        $rule,
        array $cart,
        array $excludedSkus,
        string $skuRemoveArr,
        float $subTotal,
        float $totalExcludePrice
    ): array {
        $baseQuery =
            AutoDiscount::where(
                'start_date',
                '<=',
                date('Y-m-d')
            )
                ->where(
                    'end_date',
                    '>=',
                    date('Y-m-d')
                )
                ->where(
                    'status',
                    '1'
                )
                ->where(
                    'sku',
                    ''
                );

        $autoRule =
            (clone $baseQuery)
                ->where(
                    'end_order_amount',
                    '>=',
                    $subTotal
                )
                ->where(
                    'order_amount',
                    '<=',
                    $subTotal
                )
                ->orderByDesc(
                    'end_order_amount'
                )
                ->first();

        if (!$autoRule) {
            $autoRule =
                (clone $baseQuery)
                    ->where(
                        'end_order_amount',
                        '<=',
                        $subTotal
                    )
                    ->orderByDesc(
                        'end_order_amount'
                    )
                    ->first();
        }

        if (!$autoRule) {
            return [
                'matched' => false,
                'discount' => 0,
                'discountCouponFlag' =>
                    $rule->discount_coupon_flag,
            ];
        }

        addLog(
            'ApplyAutoDiscount',
            [
                'AutoRS1' =>
                    $autoRule,
            ]
        );

        $tempCart =
            Session::get(
                'ShoppingCart.Cart',
                []
            );

        $removeSkus =
            $this->csvToArray(
                $skuRemoveArr
            );

        $totalPrice = 0;

        foreach ($tempCart as $index => $item) {

            $sku =
                $item['SKU'] ?? '';

            if (
                ($item['IsDealProducts'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['IS_Free_Gift'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                ($item['Is_Free_Sample'] ?? '') ===
                'Yes'
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $excludedSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                in_array(
                    $sku,
                    $removeSkus,
                    true
                )
            ) {
                continue;
            }

            if (
                $autoRule->exclude_pocketperfume ===
                'Yes' &&
                $this->isPocketPerfume(
                    $item['CategoryID'] ?? 0
                )
            ) {
                continue;
            }

            $totalPrice +=
                (float) (
                    $item['TotPrice'] ?? 0
                );

            /*
             * Existing percentage rule:
             * item-wise discount = Price * Qty * %
             */
            if (
                (int) $autoRule->type === 1
            ) {
                $itemDiscount =
                    (
                        (float)
                        ($item['Price'] ?? 0)
                        *
                        (float)
                        ($item['Qty'] ?? 0)
                    )
                    *
                    (
                        (float)
                        $autoRule->auto_discount_amount
                        / 100
                    );

                Session::put(
                    'ShoppingCart.Cart.'
                    . $index
                    . '.AutoItemWiseDiscout',
                    $itemDiscount
                );
            }

            $skuRemoveArr .=
                $sku . ',';
        }

        /*
         * Existing fixed amount rule distributes the
         * fixed discount proportionally across eligible
         * cart items.
         */
        if (
            (int) $autoRule->type === 0 &&
            $totalPrice > 0
        ) {

            foreach (
                $tempCart
                as $index => $item
            ) {

                $sku =
                    $item['SKU'] ?? '';

                if (
                    ($item['IsDealProducts'] ?? '') ===
                    'Yes'
                ) {
                    continue;
                }

                if (
                    ($item['IS_Free_Gift'] ?? '') ===
                    'Yes'
                ) {
                    continue;
                }

                if (
                    ($item['Is_Free_Sample'] ?? '') ===
                    'Yes'
                ) {
                    continue;
                }

                if (
                    in_array(
                        $sku,
                        $excludedSkus,
                        true
                    )
                ) {
                    continue;
                }

                if (
                    in_array(
                        $sku,
                        $this->csvToArray(
                            $skuRemoveArr
                        ),
                        true
                    )
                ) {
                    continue;
                }

                if (
                    $autoRule->exclude_pocketperfume ===
                    'Yes' &&
                    $this->isPocketPerfume(
                        $item['CategoryID'] ?? 0
                    )
                ) {
                    continue;
                }

                $itemPercentage =
                    (
                        (float)
                        $autoRule->auto_discount_amount
                        * 100
                    )
                    / $totalPrice;

                $itemDiscount =
                    (
                        (float)
                        ($item['TotPrice'] ?? 0)
                        *
                        $itemPercentage
                    )
                    / 100;

                Session::put(
                    'ShoppingCart.Cart.'
                    . $index
                    . '.AutoItemWiseDiscout',
                    $itemDiscount
                );
            }
        }

        $eligibleSubtotal =
            $subTotal
            - $totalExcludePrice;

        $discount =
            (int) $autoRule->type === 1
                ? $eligibleSubtotal *
                    (
                        (float)
                        $autoRule->auto_discount_amount
                        / 100
                    )
                : (float)
                    $autoRule->auto_discount_amount;

        return [
            'matched' => true,
            'discount' => $discount,
            'discountCouponFlag' =>
                $autoRule->discount_coupon_flag,
        ];
    }

    /**
     * Merge multiple applicable discounts.
     */
    protected function mergeDiscount(
        float $current,
        float $new
    ): float {
        if ($new <= 0) {
            return $current;
        }

        return $current + $new;
    }

    /**
     * Store item-wise discount.
     */
    protected function putItemDiscount(
        array $item,
        array $cart,
        float $discount
    ): void {
        $index = array_search(
            $item,
            $cart,
            true
        );

        if ($index === false) {
            return;
        }

        Session::put(
            'ShoppingCart.Cart.'
            . $index
            . '.AutoItemWiseDiscout',
            $discount
        );
    }

    /**
     * Existing getDealSubTotal() bridge.
     *
     * This MUST later move to CartCalculatorService.
     */
    protected function getDealSubTotal(): float
    {
        return (float) Session::get(
            'ShoppingCart.DealSubTotal',
            0
        );
    }

    /**
     * Convert CSV string to array.
     */
    protected function csvToArray(
        ?string $value
    ): array {
        if (
            empty(trim((string) $value))
        ) {
            return [];
        }

        return array_values(
            array_filter(
                array_unique(
                    array_map(
                        'trim',
                        explode(',', $value)
                    )
                ),
                'strlen'
            )
        );
    }
}