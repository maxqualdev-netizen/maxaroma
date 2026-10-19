<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutCartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {
    }

    /**
     * Add product to checkout cart.
     *
     * CartService owns stock/product/cart business logic.
     * Controller only validates HTTP input and returns JSON.
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'order_type' => ['nullable', 'string'],
            'cookie' => ['nullable', 'string'],
            'gift_wrap' => ['nullable', 'string'],
        ]);

        $result = $this->cartService->addByProductId(
            (int) $validated['product_id'],
            (int) ($validated['qty'] ?? 1),
            $validated['order_type'] ?? 'Website',
            $validated['cookie'] ?? 'No',
            $validated['gift_wrap'] ?? 'No'
        );

        if (($result['success'] ?? false) === true) {
            $result['checkout'] =
                $this->checkoutService
                    ->refresh('cart');
        }

        return response()->json($result);
    }

    /**
     * Update existing cart item quantity.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
            'qty' => ['required', 'integer', 'min:1'],
            'gift_wrap' => ['nullable', 'string'],
        ]);

        $result =
            $this->cartService->update(
                (int) $validated['product_id'],
                (int) $validated['qty'],
                $validated['gift_wrap'] ?? 'No'
            );

        if (($result['success'] ?? false) === true) {
            $result['checkout'] =
                $this->checkoutService
                    ->refresh('cart');
        }

        return response()->json($result);
    }

    /**
     * Remove by ShoppingCart.Cart array index.
     */
    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_id' => ['required', 'integer', 'min:0'],
        ]);

        $result =
            $this->cartService->remove(
                (int) $validated['cart_id']
            );

        if (($result['success'] ?? false) === true) {
            $result['checkout'] =
                $this->checkoutService
                    ->refresh('cart');
        }

        return response()->json($result);
    }


    /**
     * Return the current checkout cart state.
     *
     * This is the API replacement for the cart-data portion of the
     * legacy GetCart/GetCartPartial flow. HTML rendering remains in
     * the Blade layer and is intentionally not generated here.
     */
    public function summary(): JsonResponse
    {
        $cart = $this->cartService->getCart();

        $subtotal = (float) \Session::get(
            'ShoppingCart.SubTotal',
            0
        );

        $itemCount = 0;

        foreach ($cart as $item) {
            if (
                isset($item['Qty'])
                && !isset($item['IS_Free_Gift'])
                && !isset($item['Is_Free_Sample'])
            ) {
                $itemCount += (int) $item['Qty'];
            }
        }

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'item_count' => $itemCount,
            'cart_count' => count($cart),
        ]);
    }

    /**
     * Empty the checkout cart.
     */
    public function clear(): JsonResponse
    {
        $result =
            $this->cartService->clear();

        if (($result['success'] ?? false) === true) {
            $result['checkout'] =
                $this->checkoutService
                    ->refresh('cart');
        }

        return response()->json($result);
    }
}
