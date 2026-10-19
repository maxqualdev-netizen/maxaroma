<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService
    ) {
    }

    /**
     * GET /checkout
     *
     * Initial checkout page load.
     */
    public function index(Request $request)
    {
        $customerId = auth()->id();

        $checkout = $this->checkoutService->getCheckout(
            $customerId
        );

        return view(
            'checkout.checkout',
            $checkout
        );
    }
}