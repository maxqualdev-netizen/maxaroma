<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface CouponRepositoryInterface
{
    public function findValidCoupon(
        string $couponCode,
        ?string $userType = null
    ): Collection;

    public function hasCouponBeenUsed(
        int $couponId,
        ?int $customerId = null,
        ?string $couponNumber = null,
        ?string $email = null,
        bool $isYotpo = false
    ): bool;
}