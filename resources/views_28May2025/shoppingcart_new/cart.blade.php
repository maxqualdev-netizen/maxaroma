@extends('layouts.app')
@section('content')
<main id="shopcartpage" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
	@if(!isset($CartDetails['Cart']) || count($CartDetails['Cart']) == 0 )
		@include('shoppingcart_new.empty')
	@else
        <section class="cartpage-page">
            <div class="container">
                <div class="row">
                    <div class="col-12" id="shopcartinfo">
                        <div class="row pr-lg-4 pr-0">
                            <div class="col-12 mb-md-4 mb-3">
                                <div class="cart-top text-center pb-md-5 pt-md-5">
                                    <h3 class="cart-hd">My Cart</h3>
                                    <p class="d-none d-md-inline-block mb-0">Free Shipping on all U.S orders.</p>
                                </div>
                                <div class="cart-table">
                                    <div class="cart-table-header">
                                        <div class="d-none d-md-block">
                                            <div class="row row10">
                                                <div class="col-md-7">
                                                    <div class="cart-table-head">PRODUCT</div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="cart-table-head">QUANTITY <span class="flot-right">PRICE</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" id="clear-bag" class="ulink f12">Clear Your Cart</a> 
                                    </div>
                                    @include('shoppingcart_new.cart_table', [$CartDetails['Cart']])
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-5 order-2 order-md-0" id="credcoupboxes">
                                        <div class="row">
                                            @include('shoppingcart_new.creditcouponboxes', [$CartDetails['Cart']])
                                        </div>
                                    </div>
                                    <div class="col-md-5 ml-auto order-1 order-md-0 mb-md-2">
                                        <div class="row">
                                            <div class="col-12 text-right pb-md-3" id="cartsubtotal">
                                                @include('shoppingcart_new.subtotalbox',[$CartDetails])
                                            </div>
                                            <div class="col-12">
                                                <div class="chekout-main-btn" id="checkoutbox">
                                                    @include('shoppingcart_new.checkoutbox',[$CartDetails])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mobile-shadow d-md-none">&nbsp;</div>
                                    </div>
                                    <div class="col-12 f12 pt-4 pb-md-5 order-3 order-md-0 lheight-18">
                                        <strong>Please note:</strong> Items are not reserved until checkout is complete. Prices are subject to change based on the price in effect the day you checkout.
                                    </div>	
                                </div>
                            </div>
                            <div class="col-12">
                                @if(isset($AllDiscounts['RewardDiscount']) && $AllDiscounts['RewardDiscount'] > 0 && Session::get('eusertype') == 'Retailer')
                                    <div class="row">
                                        <div class="col-12">
                                            <marquee behavior="alternate">
                                                <strong>Total Reward Point {{$CartAttr['TotalRewardPoint']}} & Remaining Reward Point {{$CartAttr['RemainRewardPoint']}} & Got {{$AllDiscounts['RewardDiscount']}} Reward Discount on this Order</strong>
                                            </marquee>
                                        </div>
                                    </div>	
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-8 offset-md-2 pt-md-5 pb-md-5">
                                        <div class="refer-cart text-center">
                                            <h3 class="cart-hd ttu">REFER A FRIEND <span>Give $25, Get $25</span></h3>
                                            <p class="mb-0 pb-md-4 pb-3 pt-md-2">Give your friends $25 off on their first order of $200+ and get $25 (in points) for each successful referral.</p>
                                            @if(Session::has('sess_useremail'))
                                                <a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="ulink f12"><strong>Join Now</strong></a>
                                            @else
                                                <a href="javascript:void(0)" data-action="sign_upfirstpart"  class="ulink f12 signinsignup"><strong>Join Now</strong></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
        </section>
    @endif
</main>
<input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>
@endsection
