<?php

namespace App\Services\Checkout;

use Illuminate\Support\Facades\Session;
use App\Models\Products;
class GiftWrappingService
{
    /**
     * Apply or remove gift wrapping for One Page Checkout.
     *
     * Existing CartTrait business rules are preserved:
     *
     * - Charge comes from Settings.GIFT_WRAPPING_CHARGE.
     * - If ProductID is supplied, that product is wrapped.
     * - If ProductID is empty, existing cart items marked
     *   gift_wrap = Yes are charged.
     * - Charge is Qty x configured gift wrapping charge.
     */

    public function getCartGiftWrapDetails(): array
    {
        $shopcart =
            Session::get(
                'ShoppingCart.Cart',
                []
            );

        if (
            !is_array($shopcart)
            ||
            count($shopcart) === 0
        ) {
            Session::forget(
                'ShoppingCart.GiftWrapping'
            );

            return ['displayGiftWrapCharge' => 0.0, 'CntGiftWrapCheckedItems' => 0];
        }

        /*
         * Use the existing configured Global Setting.
         * Do NOT hard-code $5.
         */
        $giftWrappingCharge =
            (float)
            config(
                'Settings.GIFT_WRAPPING_CHARGE',
                0
            );

        $totalGiftCharge = 0.0;
        $totalGiftWrapItems = 0;
        $totalGiftWrapCheckedItems = 0;

        foreach ($shopcart as $item)
        {
            $isGiftWrapItem =(isset($item['gift_wrap']) && $item['gift_wrap'] === 'Yes');
            if(isset($item['IsGiftWrapProduct']) && $item['IsGiftWrapProduct'] == 'Yes')
            {
                $quantity = (float)($item['Qty'] ?? 0);
                $totalGiftCharge+= ($quantity * $giftWrappingCharge);
                $totalGiftWrapItems+= $quantity;
                if($isGiftWrapItem)
                {
                    $totalGiftWrapCheckedItems++;
                }
            }
        }
        return ['displayGiftWrapCharge' => (float)$totalGiftCharge, 'totalGiftWrapItems' => $totalGiftWrapItems, 'totalGiftWrapCheckedItems' => $totalGiftWrapCheckedItems];
    }
    public function calculate(
        string $action = 'add',
        string $productId = ''
    ): float {
        $action = strtolower(trim($action));

        if ($action === 'remove') {
            $shopcart = Session::get('ShoppingCart.Cart',[]);
            $NewShopCart = [];
            foreach ($shopcart as $item)
            {
                if(isset($item['IsGiftWrapProduct']) && $item['IsGiftWrapProduct'] == 'Yes')
                {
                    $item['gift_wrap'] = 'No';
                }
                $NewShopCart[]=$item;
            }
            Session::put('ShoppingCart.Cart',$NewShopCart);
            Session::forget('ShoppingCart.GiftWrapping');

            addLog(
                'RemoveGiftWrapping',
                [
                    'charge' => 0,
                ]
            );

            return 0.0;
        }

        if ($action !== 'add') {
            throw new \InvalidArgumentException(
                'Invalid gift wrapping action.'
            );
        }

        $shopcart =
            Session::get(
                'ShoppingCart.Cart',
                []
            );

        if (
            !is_array($shopcart)
            ||
            count($shopcart) === 0
        ) {
            Session::forget(
                'ShoppingCart.GiftWrapping'
            );

            return 0.0;
        }

        /*
         * Use the existing configured Global Setting.
         * Do NOT hard-code $5.
         */
        $giftWrappingCharge =
            (float)
            config(
                'Settings.GIFT_WRAPPING_CHARGE',
                0
            );

        $totalGiftCharge = 0.0;
        $NewShopCart = [];
        foreach ($shopcart as $item)
        {
            /*
            $isGiftWrapItem =(isset($item['gift_wrap']) && $item['gift_wrap'] === 'Yes');
            $isRequestedProduct =
                (
                    $productId !== ''
                    &&
                    (string)
                    ($item['ProductID'] ?? '')
                    ===
                    (string) $productId
              );
            */
            //if ($isGiftWrapItem || $isRequestedProduct)
            if(isset($item['IsGiftWrapProduct']) && $item['IsGiftWrapProduct'] == 'Yes')
            {
                $quantity =
                    (float)
                    ($item['Qty'] ?? 0);

                $totalGiftCharge +=
                    $quantity
                    *
                    $giftWrappingCharge;

                $item['gift_wrap'] = "Yes";
            }
            $NewShopCart[] = $item;
        }

        Session::put('ShoppingCart.Cart',$NewShopCart);

        $giftWrapping = [
            'Charge' =>
                NumberFormat(
                    $totalGiftCharge
                ),

            'Applied' =>
                'Yes',
        ];

        Session::put(
            'ShoppingCart.GiftWrapping',
            $giftWrapping
        );

        addLog(
            'ApplyGiftWrapping',
            $giftWrapping
        );

        return (float)
            $giftWrapping['Charge'];
    }

    /**
     * Return the current gift wrapping state.
     */
    public function getCurrentCharge(): float
    {
        $giftWrapping =
            Session::get(
                'ShoppingCart.GiftWrapping'
            );

        if (
            !is_array($giftWrapping)
        ) {
            return 0.0;
        }

        return (float)
            (
                $giftWrapping['Charge']
                ??
                0
            );
    }

    public function isApplied(): bool
    {
        return
            Session::get(
                'ShoppingCart.GiftWrapping.Applied'
            ) === 'Yes'
            ||
            (
                is_array(
                    Session::get(
                        'ShoppingCart.GiftWrapping'
                    )
                )
                &&
                Session::get(
                    'ShoppingCart.GiftWrapping.Applied'
                ) === 'Yes'
            );
    }
}
