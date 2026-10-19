<?php

namespace App\Contracts;

interface ProductSourceInterface
{
    /**
     * Get product information required for checkout.
     */
    public function getProduct(int|string $productId): ?array;

    /**
     * Get multiple products required for checkout.
     */
    public function getProducts(array $productIds): array;

    /**
     * Check whether the product exists in this source.
     */
    public function exists(int|string $productId): bool;

    /**
     * Get the source name.
     *
     * vendor
     * website
     * store
     * other
     */
    public function getSourceName(): string;
} 
?>