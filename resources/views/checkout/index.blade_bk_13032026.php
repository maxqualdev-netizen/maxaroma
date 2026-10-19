@extends('layouts.app')
@section('content')
<style>
    .input-loader-wrapper{
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .input-loader-wrapper input{
        padding-right: 35px;
    }

    .input-loader{
        position: absolute;
        right: 10px;
        top: 70%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        border: 2px solid #ddd;
        border-top: 2px solid #333;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: none;
    }

    @keyframes spin{
        0%{ transform: translateY(-50%) rotate(0deg); }
        100%{ transform: translateY(-50%) rotate(360deg); }
    }

    #ui-id-1 li:nth-child(odd) {
        background-color: #f9f9f9;
    }

    #ui-id-1 li:nth-child(even) {
        background-color: #ffffff;
    }
    .highlight-match{
        background:#e0e0d9;
        font-weight:bold;
        padding:0 2px;
    }
</style>
    <div class="showDwnMessage" style="display:none" role="status" aria-live="polite">Please wait while your transaction is being processed.... Please do not refresh the page.</div>
    @include('checkout.checkout-header')
    @if(!Session::has('PayPalToken') && $NetTotal > 0)
    @if(Session::has('sess_storeuserid'))
        <div class="col-12">
            <div class="express-ck text-center">
                @include('checkout.pos.agent-info',[$CartAttr])
            </div>
        </div>
    @endif
    @if(!Auth::guard('store')->check())
    <div class="col-12 step1" style="{{$show_other_payments_checkout}}" role="region" aria-label="Express Checkout Section">
        <div class="express-ck text-center">
            <h3 class="cart-hd" id="express-checkout-heading">Express Checkout</h3>
            @include('checkout.checkout-express',[$CartAttr])
        </div>
    </div>
    @endif
    <div class="col-12 pt-3 @if(Auth::guard('store')->check()) pb-3 @endif step1" style="{{$show_other_payments_checkout}}" role="region" aria-label="Alternative Payment Section">
        <div class="middle-border"><span> @if(Auth::guard('store')->check()) Customer Information @else Or pay by card below @endif</span></div>
    </div>
    @endif

    @if(!Auth::guard('store')->check())
    <div class="col-12 text-center" id="newacc" style="{{$show_have_acc_div}}" role="region" aria-label="Account Sign In Section">
        <input type="hidden" name="checkloginPop" id="checkloginPop" value="1">
        <h4 class="cart-hd-sub text-center" id="signin-heading">
            Checkout as Guest or <a href="javascript:void(0);" data-action="sign_in" class="ulink signinsignup" aria-label="Sign In">Sign In</a>
            <div class="pt-2"><small>Enter your email to continue as a guest. You'll have the option to create an account later.</small></div>
            <span id="duplicate_ip" class="frmerror frmerror_shw" role="alert" style="padding-top:20px; font-weight:normal; display:none;"></span>
        </h4>
    </div>
    @endif
    <div class="col-12" role="region" aria-label="Checkout Steps Section">
        <div class="checkout-cont">
            @if(Session::has('PlaceOrderError'))
                <x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_order']"/>
            @endif

            <ul class="checkout-steps" role="list" aria-label="Checkout Steps">
                @if(!Session::has('PayPalToken'))
                <li id="guestcheckout" style="{{$show_guest_checkout_div}}" role="listitem" aria-label="Guest Checkout">
                    @include('checkout.guest')
                </li>
                @endif

                <li class="billtab bill-info" style="{{$show_billing_info_div}}" role="listitem" aria-label="Billing and Shipping Information">
                    @include('checkout.billing-shipping',[$CartDetails,$CartAttr,$SelPayMethod])
                </li>
            </ul>
        </div>
    </div>
    @include('checkout.checkout-footer')
@endsection
