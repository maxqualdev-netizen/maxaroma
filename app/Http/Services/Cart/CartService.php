<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        protected CartCalculatorService $cartCalculatorService,
        protected CartSessionService $cartSessionService,
        protected CartStockService $stockService,
        protected CartProductService $productService
    ) {
    }

    /**
     * Add product to cart.
     *
     * Product lookup, stock validation and SetProduct-equivalent
     * normalization are handled by CartStockService.
     */
    public function add(
        object $product,
        int $qty = 1,
        string $orderType = 'Website',
        string $cookie = 'No',
        string $giftWrap = 'No'
    ): array {
        $productId = (int) ($product->products_id ?? 0);
        $qty = $qty > 0 ? $qty : 1;

        if ($productId <= 0) {
            return [
                'success' => false,
                'StockInfo' => 1111,
                'message' => 'Product not available.',
            ];
        }

        $stock = $this->stockService->checkStock(
            $productId,
            $qty,
            'insert',
            $cookie,
            $orderType
        );

        if (($stock['StockInfo'] ?? 1111) !== 3333) {
            return [
                'success' => false,
                'StockInfo' => $stock['StockInfo'] ?? 1111,
                'availableStock' =>
                    $stock['availableStock'] ?? null,
                'message' =>
                    $this->stockMessage($stock),
            ];
        }

        $normalizedProduct = $stock['ProdInfo'] ?? null;

        if (!$normalizedProduct) {
            return [
                'success' => false,
                'StockInfo' => 1111,
                'message' => 'Product not available.',
            ];
        }

        $cart = $this->cartSessionService->getCart();

        foreach ($cart as $index => $item) {
            if (
                (int) ($item['ProductID'] ?? 0) === $productId
                && !isset($item['IS_Free_Gift'])
                && !isset($item['Is_Free_Sample'])
            ) {
                $existingQty = (int) ($item['Qty'] ?? 0);

                $newQty = $cookie === 'Yes'
                    ? (
                        $existingQty > $qty
                            ? $existingQty + $qty
                            : $existingQty
                    )
                    : $existingQty + $qty;

                /*
                 * Stock was checked against the final requested
                 * quantity by ProductCheckInStock-equivalent logic.
                 */
                $cart[$index]['Qty'] = $newQty;
                $cart[$index]['gift_wrap'] = $giftWrap;

                $cart[$index] =
                    $this->productService->applyPrice(
                        $cart[$index],
                        $normalizedProduct,
                        $newQty
                    );

                $this->cartSessionService->putCart($cart);
                $this->cartCalculatorService->calculateSubTotal();

                return [
                    'success' => true,
                    'updated' => true,
                    'cart' => $cart,
                ];
            }
        }

        $cartItem =
            $this->productService->buildCartItem(
                $normalizedProduct,
                $qty,
                $orderType
            );

        $cartItem =
            $this->productService->applyPrice(
                $cartItem,
                $normalizedProduct,
                $qty
            );

        $cartItem['gift_wrap'] = $giftWrap;

        $cart[] = $cartItem;

        $this->cartSessionService->putCart($cart);
        $this->cartCalculatorService->calculateSubTotal();

        return [
            'success' => true,
            'updated' => false,
            'cart' => $cart,
        ];
    }

    /**
     * Add a product using only its product ID.
     *
     * The stock service performs the existing product lookup and
     * normalization. The normalized product is then passed to add().
     */
    public function addByProductId(
        int $productId,
        int $qty = 1,
        string $orderType = 'Website',
        string $cookie = 'No',
        string $giftWrap = 'No'
    ): array {
        $qty = $qty > 0 ? $qty : 1;

        $stock = $this->stockService->checkStock(
            $productId,
            $qty,
            'insert',
            $cookie,
            $orderType
        );

        if (($stock['StockInfo'] ?? 1111) !== 3333) {
            return [
                'success' => false,
                'StockInfo' => $stock['StockInfo'] ?? 1111,
                'availableStock' =>
                    $stock['availableStock'] ?? null,
                'message' =>
                    $this->stockMessage($stock),
            ];
        }

        $product = $stock['ProdInfo'] ?? null;

        if (!$product) {
            return [
                'success' => false,
                'StockInfo' => 1111,
                'message' => 'Product not available.',
            ];
        }

        return $this->add(
            $product,
            $qty,
            $orderType,
            $cookie,
            $giftWrap
        );
    }

    /**
     * Update quantity for an existing cart product.
     */
    public function update(
        int $productId,
        int $qty,
        string $giftWrap = 'No'
    ): array {
        $qty = $qty > 0 ? $qty : 1;

        $cart = $this->cartSessionService->getCart();
        $index = null;
        $orderType = 'Website';

        foreach ($cart as $key => $item) {
            if (
                (int) ($item['ProductID'] ?? 0) === $productId
                && !isset($item['IS_Free_Gift'])
                && !isset($item['Is_Free_Sample'])
            ) {
                $index = $key;
                $orderType =
                    $item['OrderType'] ?? 'Website';
                break;
            }
        }

        if ($index === null) {
            return [
                'success' => false,
                'Update' => 0,
                'message' => 'Product not found in cart.',
            ];
        }

        $stock = $this->stockService->checkStock(
            $productId,
            $qty,
            'update',
            'No',
            $orderType
        );

        if (($stock['StockInfo'] ?? 1111) !== 3333) {
            return [
                'success' => false,
                'Update' => 0,
                'StockInfo' => $stock['StockInfo'] ?? 1111,
                'availableStock' =>
                    $stock['availableStock'] ?? null,
                'message' =>
                    $this->stockMessage($stock),
            ];
        }

        $normalizedProduct =
            $stock['ProdInfo'] ?? null;

        $cart[$index]['Qty'] = $qty;
        $cart[$index]['gift_wrap'] = $giftWrap;

        if ($normalizedProduct) {
            $cart[$index] =
                $this->productService->applyPrice(
                    $cart[$index],
                    $normalizedProduct,
                    $qty
                );
        } else {
            $cart[$index]['TotPrice'] =
                $this->numberFormat(
                    $qty
                    * (float) ($cart[$index]['Price'] ?? 0)
                );
        }

        $this->cartSessionService->putCart($cart);
        $this->cartCalculatorService->calculateSubTotal();

        return [
            'success' => true,
            'Update' => 1,
            'cart' => $cart,
        ];
    }

    /**
     * Remove cart item by session cart index.
     */
    public function remove(int $cartId): array
    {
        $cart = $this->cartSessionService->getCart();

        if (
            !isset($cart[$cartId])
            || !is_array($cart[$cartId])
        ) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }

        $removed = $cart[$cartId];

        if (
            isset($removed['IsYotpoFreeProduct'])
            && $removed['IsYotpoFreeProduct'] === 'Yes'
        ) {
            Session::forget(
                'ShoppingCart.YotpoFreeGiftCoupon'
            );
        }

        unset($cart[$cartId]);
        $cart = array_values($cart);

        if (
            count($cart) === 1
            && isset($cart[0]['IsYotpoFreeProduct'])
            && $cart[0]['IsYotpoFreeProduct'] === 'Yes'
        ) {
            $cart = [];
        }

        $this->cartSessionService->putCart($cart);
        $this->cartCalculatorService->calculateSubTotal();

        return [
            'success' => true,
            'message' => 'Item removed successfully.',
            'cart' => $cart,
            'removed' => $removed,
        ];
    }

    /**
     * Empty the cart.
     */
    public function clear(): array
    {
        $this->cartSessionService->putCart([]);
        $this->cartCalculatorService->calculateSubTotal();

        return [
            'success' => true,
            'cart' => [],
        ];
    }

    public function getCart(): array
    {
        return $this->cartSessionService->getCart();
    }

    public function getItems(): array
    {
        return Session::get(
            'ShoppingCart.Cart',
            []
        );
    }

    public function hasItems(): bool
    {
        return count($this->getItems()) > 0;
    }

    protected function stockMessage(array $stock): string
    {
        if (($stock['StockInfo'] ?? 0) === 2222) {
            $available =
                $stock['availableStock'] ?? null;

            if ($available !== null) {
                return
                    'The maximum quantity you can add is '
                    . (int) $available
                    . ' pieces.';
            }

            return 'Requested quantity is not available.';
        }

        return 'Product is not available.';
    }

    protected function numberFormat(
        float|int|string $value
    ): float {
        if (function_exists('NumberFormat')) {
            return (float) NumberFormat($value);
        }

        return round((float) $value, 2);
    }
}
