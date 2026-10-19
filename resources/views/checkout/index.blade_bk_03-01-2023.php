@extends('layouts.app')
@section('content')
	<div class="showDwnMessage" style="display:none">Please wait while your transaction is being processed.... Please do not refresh the page.</div>
	@include('checkout.checkout-header')
    @if(!Session::has('PayPalToken'))
	<div class="col-12 step1" style="{{$show_other_payments_checkout}}">
        <div class="express-ck text-center">
            <h3 class="cart-hd">Express Checkout</h3>
			@include('checkout.checkout-express',[$CartAttr])
        </div>
    </div>
    <div class="col-12 pt-5 step1" style="{{$show_other_payments_checkout}}">
        <div class="middle-border"><span>Or pay by card below</span></div>
    </div>
    @endif
    <div class="col-12 text-center" id="newacc" style="{{$show_have_acc_div}}">
		<input type="hidden" name="checkloginPop" id="checkloginPop" value="1">
        <h4 class="cart-hd-sub text-center">Have an account? <a href="{{config('global.SITE_URL').'login/checkout.html'}}" class="ulink">Sign In</a></h4> 
    </div>		
    
    <div class="col-12">
        <div class="checkout-cont">
            @if(Session::has('PlaceOrderError'))
                <x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_order']"/>
            @endif	
			
            <ul class="checkout-steps">
				@if(!Session::has('PayPalToken'))
                <li id="guestcheckout" style="{{$show_guest_checkout_div}}" >
                    @include('checkout.guest')
                </li>
                @endif
				
                <li class="billtab bill-info" style="{{$show_billing_info_div}}">
                    @include('checkout.billing-shipping',[$CartDetails,$CartAttr,$SelPayMethod])
                </li>
            </ul>
        </div>
    </div>
    @include('checkout.checkout-footer')
@endsection
