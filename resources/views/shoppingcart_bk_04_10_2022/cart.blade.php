@extends('layouts.app')
@section('content')
<main id="shopcartpage" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
	@if(!isset($CartDetails['Cart']) || count($CartDetails['Cart']) == 0 )
		@include('shoppingcart.empty')
	@else
	<section class="cartpage-page pt-4">
		<div class="container">
			<div class="row">
				<div class="col-lg-8" id="shopcartinfo">
					<div class="row pr-lg-4 pr-0">
						<div class="col-12 mb-4">
							<div class="cart-top border-bottom">
								<h3 class="cart-hd">My Cart</h3>
								<div class="float-left"> <span>Free Shipping on all U.S orders.</span> </div>
								<div class="float-right d-none d-md-block"> 
									<a href="javascript:void(0);" id="clear-bag" class="more-link">Clear Your Cart</a> 
								</div>
							</div>
							<div class="cart-table">
								@include('shoppingcart.cart_table', [$CartDetails['Cart']])
							</div>
						</div>
					</div>
					<div id="credcoupboxes">
						@include('shoppingcart.creditcouponboxes', [$CartDetails['Cart']])
					</div>	
				</div>
				<div class="col-lg-4 mt-4 mt-lg-0">
					<div class="order-summry p-3 p-md-4">
						<div class="row">
							<div class="col-12" id="cartsubtotal">
								@include('shoppingcart.subtotalbox',[$CartDetails])
							</div>
						</div>
					</div>
					<div class="chekout-main-btn mt-0 mt-md-3" id="checkoutbox">
						@include('shoppingcart.checkoutbox',[$CartDetails])
					</div>
					
					<div class="row row10" id="Paymethods" @if($NetTotal == 0) style="display:none;" @endif>
						@if($CartAttr['IsPaypalExpressCheckout'] == 'Yes')
						<div class="col-6 mt-3">
							<div id="paypal-button-container"> 
								<a href="{{url('paypal/placeorder')}}"> 
									<img alt="Paypal" src="{{config('global.SITE_IMAGES')}}paypal.png" width="296" height="77" class="img-fluid"> 
								</a>	
							</div>
						</div>
						@endif
						@if($CartAttr['Amazon_pay_Checkout'] == 'Yes')
						<div class="col-6 mt-3 text-center">
							<a href="javascript:void(0);" id="AmazonPayButton"></a>
						</div>
						@endif
						
						@if(config('global.StripeButton') == 'Show')
						<div class="col-12 mt-3 text-center" id="payment-request-button">
							<!-- A Stripe Element will be inserted here. -->
						</div>
						<form id="frmstripe" method="post" action="{{url('placeorder')}}">
							{{ csrf_field() }}
							<input type="hidden" name="PaymentMethod" value="PAYMENT_STRIPE_BUTTON"/>
						</form>
						@endif
						<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
					</div>
					<div class="max_need_help"> 
						<div class="we-accept">
							<h4 class="mb-1">We Accept</h4>
							<ul class="botcard">
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-amex.svg" width="48" height="48" class="" alt=""></a></li>
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-visa.svg" width="48" height="48" class="" alt=""></a></li>
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-master.svg" width="48" height="48" class="" alt=""></a></li>
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-discover.svg" width="48" height="48" class="" alt=""></a></li>
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-paypal.svg" width="48" height="48" class="" alt=""></a></li>
								<li><a href="#"><img src="{{config('global.SITE_IMAGES')}}card-amazon.svg" width="48" height="48" class="" alt=""></a></li>
							</ul>
						</div>
						<!--
						<a href="javascript:void(0);" class="more-link">
							<svg class="sv-erth vam" aria-hidden="true" role="img" width="20" height="20">
								<use href="#sv-erth" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-erth"></use>
							</svg>
							Ship international
							<svg class="sv-back vam" aria-hidden="true" role="img" width="14" height="14">
								<use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use>
							</svg>
						</a>
						-->
						<h4 class="mt-2 d-none d-md-block">Need Help? <a href="contact-us.html" class="">Contact Us</a></h4>
						<ul class="d-none d-md-block">
							<li> 
								<img src="{{config('global.SITE_IMAGES')}}phon.jpg" width="23" height="23" alt="" class="left"> 
								<small class=""> <a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Toll Free), 
									<a href="tel:{{config('Settings.INTERNATIONAL_PHONE_NO')}}">{{config('Settings.INTERNATIONAL_PHONE_NO')}}</a> (Outside of USA)<br>
								</small> 
							</li>
							<li> 
								<img src="{{config('global.SITE_IMAGES')}}email.jpg" width="23" height="23" alt="" class="left">
								<small>
									<a href="mailto:{{config('Settings.ADMIN_MAIL')}}" class="grey_text">{{config('Settings.ADMIN_MAIL')}}</a>
								</small> 
							</li>
						</ul>
					</div>
				</div>
			</div>
			@if(isset($AllDiscounts['RewardDiscount']) && $AllDiscounts['RewardDiscount'] > 0 && Session::get('eusertype') == 'Retailer')
			<div class="row">
				<div class="col-12">
					<marquee behavior="alternate">
						<strong>Total Reward Point {{$CartAttr['TotalRewardPoint']}} & Remaining Reward Point {{$CartAttr['RemainRewardPoint']}} & Got {{$AllDiscounts['RewardDiscount']}} Reward Discount on this Order</strong>
					</marquee>
				</div>
			</div>	
			@endif
			@if(count($RecentViewProducts) > 0)
				<div class="max_people_mob">
					<div class="row">
						<div class="col-12">
							<x-slider :attr="$RecentViewAttr" :sliderData="$RecentViewProducts"/>
						</div>		
					</div>
				</div>
			@endif	
		</div>	
  </section>
	@endif
</main>
<input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>
@endsection