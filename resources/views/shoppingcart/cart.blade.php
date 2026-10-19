<style type="text/css">/* .product .product_icon{opacity:1;} */</style>

@extends('layouts.app')
@section('content')
<main id="shopcartpage" class="business_page" data-total="{{$CartAttr['TotalValue']}}" role="main" id="main-content" tabindex="-1" aria-label="Shopping Cart Main Content">
    @if(!isset($CartDetails['Cart']) || count($CartDetails['Cart']) == 0 )
        @include('shoppingcart.empty')
    @else
        <section class="cartpage-page" role="region" aria-labelledby="cart-summary-heading">
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
                                                <h2 id="cart-summary-heading" class="sr-only">Order Summary</h2>
                                                @include('shoppingcart.subtotalbox',[$CartDetails])
                                            </div>
                                            <div class="col-12">
                                                <div class="chekout-main-btn" id="checkoutbox" role="region" aria-label="Checkout Actions">
                                                    @include('shoppingcart.checkoutbox',[$CartDetails])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mobile-shadow d-md-none" aria-hidden="true">&nbsp;</div>
                                    </div>
                                    <div class="col-12 f12 pt-4 order-3 order-md-0 lheight-18" role="note" aria-label="Cart Notice">
                                        <strong>Please note:</strong> Items are not reserved until checkout is complete. Prices are subject to change based on the price in effect the day you checkout.
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
        </section>
        
        <!-- Recommended Products Start -->
        <div id="setSectionMainCart" role="region" aria-labelledby="recommended-products-heading">
        <h2 id="recommended-products-heading" class="sr-only">Recommended Products</h2>
        <?php if(isset($allSectionRecommendedPrds) && count($allSectionRecommendedPrds) > 0){ ?>
            @include('shoppingcart.recommended', [$allSectionRecommendedPrds])
            <?php
			/*for($i=0;$i<count($allSectionRecommendedPrds);$i++){ 
			if(count($allSectionRecommendedPrds[$i]['slider_products']) > 0){ ?>
				<section class="home-product slider-arrow-space sectionMainCart">
					<div class="container">
						<div class="pt-3 text-center pb-4">
							<div class="main-heading">
								<h3><span><?=$allSectionRecommendedPrds[$i]['title'];?></span></h3>
								<!--<a href="javascript:void(0);" class="viewall">See More</a>-->
							</div>
						</div>
						<div class="cover-spin"></div>
						<div class="products-slider">
							@foreach($allSectionRecommendedPrds[$i]['slider_products'] as $key => $Product)
								<x-productbox :prodData="$Product" sliderClass="slick-slide" />
							@endforeach
						</div>
					</div>
				</section>
			<?php
			}
			}*/ ?>
        <?php } ?>
        </div>
        <!-- Recommended Products End -->
        
        <section class="cartpage-page" role="region" aria-labelledby="rewards-section-heading">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row pr-lg-4 pr-0"  id="shopcartinfo1">
                            <div class="col-12">
                                @if(isset($AllDiscounts['RewardDiscount']) && $AllDiscounts['RewardDiscount'] > 0 && Session::get('eusertype') == 'Retailer')
                                    <div class="row">
                                        <div class="col-12">
                                            <marquee behavior="alternate" aria-label="Reward Points Information">
                                                <strong>Total Reward Point {{$CartAttr['TotalRewardPoint']}} & Remaining Reward Point {{$CartAttr['RemainRewardPoint']}} & Got {{$AllDiscounts['RewardDiscount']}} Reward Discount on this Order</strong>
                                            </marquee>
                                        </div>
                                    </div>	
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1 pt-md-5 pb-md-5">
                                        <div class="refer-cart text-center" role="region" aria-labelledby="refer-friend-heading">
                                            <h3 class="cart-hd ttu" id="refer-friend-heading">REFER A FRIEND <span>Give $10, Get $10</span></h3>
                                            <p class="mb-0 pb-md-4 pb-3 pt-md-2">Give your friends $10 off on their first order of $100+ and get $10 (in points) for each successful referral.</p>
                                            @if(Session::has('sess_useremail'))
                                                <a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="ulink f12" aria-label="Join Now and Earn Rewards"><strong>Join Now</strong></a>
                                            @else
                                                <a href="javascript:void(0)" data-action="sign_upfirstpart"  class="ulink f12 signinsignup" aria-label="Sign Up to Join and Earn Rewards"><strong>Join Now</strong></a>
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