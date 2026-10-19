@extends('layouts.app')
@section('content')
<main id="shopcartpage" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
	@if(!isset($CartDetails['Cart']) || count($CartDetails['Cart']) == 0 )
		@include('shoppingcart.empty')
	@else
        <section class="cartpage-page">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row pr-lg-4 pr-0"  id="shopcartinfo">
                              @include('shoppingcart.cart_table', [$CartDetails['Cart']])
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-5 order-2 order-md-0" id="credcoupboxes">
                                        <div class="row">
                                            @include('shoppingcart.creditcouponboxes', [$CartDetails['Cart']])
                                        </div>
                                    </div>
                                    <div class="col-md-5 ml-auto order-1 order-md-0 mb-md-2">
                                        <div class="row">
                                            <div class="col-12 text-right pb-md-3" id="cartsubtotal">
                                                @include('shoppingcart.subtotalbox',[$CartDetails])
                                            </div>
                                            <div class="col-12">
                                                <div class="chekout-main-btn" id="checkoutbox">
                                                    @include('shoppingcart.checkoutbox',[$CartDetails])
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
                                    <div class="col-md-10 offset-md-1 pt-md-5 pb-md-5">
                                        <div class="refer-cart text-center">
                                            <h3 class="cart-hd ttu">REFER A FRIEND <span>Give $10, Get $10</span></h3>
                                            <p class="mb-0 pb-md-4 pb-3 pt-md-2">Give your friends $10 off on their first order of $100+ and get $10 (in points) for each successful referral.</p>
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
