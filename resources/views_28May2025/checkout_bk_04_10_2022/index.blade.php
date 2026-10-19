@extends('layouts.app')
@section('content')
	<div class="showDwnMessage" style="display:none">Please wait while your transaction is being processed.... Please do not refresh the page.</div>
	<main id="checkout-page" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
		<section class="checkout-page pb-md-5" data-section="0">
			<div class="container">
				<div class="chekout-top">
					<div class="mainhd text-left pt-4 pb-4 pt-lg-5 pb-lg-5">
						<h2>Checkout</h2>
					</div>
					<div class="progress" @if(!Auth::user()) style="display:none;" @endif>
						<ul>
							<li class="active" id="step1">
								<span class="prog-circal">
									<svg class="sv-chcek vam" aria-hidden="true" role="img" width="13" height="10">
										<use href="#sv-chcek" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chcek"></use>
									</svg>
								</span> <span>Billing & Shipping Address</span>
							</li>
							@if($CartAttr['onlyGCPurchased'] == 0)
							<li id="step2">
								<span class="prog-circal">
									<svg class="sv-chcek vam" aria-hidden="true" role="img" width="13" height="10">
										<use href="#sv-chcek" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chcek"></use>
									</svg>
								</span> <span>Shipping Option</span>
							</li>
							@endif
							<li id="step3">
								<span class="prog-circal">
									<svg class="sv-chcek vam" aria-hidden="true" role="img" width="13" height="10">
										<use href="#sv-chcek" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chcek"></use>
									</svg>
								</span> <span>Payment Info</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<div class="row pr-lg-4 pr-0">
							<div class="col-12">
								@if(Session::has('PlaceOrderError'))
									<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_order']"/>
								@endif	
								<ul class="checkout-steps">
									@if(!Auth::user() && (isset($SelPayMethod) && $SelPayMethod != 'paypal'))
										<li id="guestcheckout">@include('checkout.guest')</li>
									@endif
									<li id="billshipinfo" @if(Auth::user() || (isset($SelPayMethod) && $SelPayMethod == 'paypal')) style="display:none" @endif>
										<div class="mb-0 mb-md-4"> 
											<h3 class="cart-hd d-none d-md-block">Billing Information</h3>
										</div>
									</li>
									<li class="billtab bill-info @if(Auth::user() || (isset($SelPayMethod) && $SelPayMethod == 'paypal')) active @endif" @if(!Auth::user() && (isset($SelPayMethod) && $SelPayMethod != 'paypal')) style="display:none" @endif>
										@include('checkout.billing-shipping',[$CartDetails])
									</li>
									@if($CartAttr['onlyGCPurchased'] == 0)
									<li id="shipinfo" style="display:none">
										@include('checkout.shippinginfo',[$CartDetails])
									</li>
									<li class="billtab shipping-method">
										<h3 class="cart-hd d-none d-md-block">Shipping Option</h3>
										<h3 class="cart-hd d-md-none">2. Shipping Method</h3>
									</li>
									@endif
									<li class="billtab payment-method">
										<h3 class="cart-hd d-none d-md-block">Payment Info</h3>
										<h3 class="cart-hd d-md-none">3. Payment</h3>
										<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 mt-4 mt-lg-0">
						<div class="order-summry p-3 p-md-4">
							<div class="row">
								<div class="col-12" id="cartsubtotal">
									@include('checkout.subtotalbox',[$CartDetails])
								</div>
							</div>
						</div>
						@if(isset($show) && $CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] == '')
						<div class="row">
							<div class="col-12 mt-3">
								<div class="max_coupon_box">
									<div class="coupan_boxhd">
										<div class="float-left text-left">
											<h6 class="">Promotional/Coupon Code</h6>
										</div>
									</div>
									<div class="pb-3 pb-md-4">
										<div class="cart-discount">
											<input type="text" id="coupon_number" name="coupon_number" placeholder="Enter Coupon Code" class="form-control">
											<a href="#" id="btncouponapply" class="btn btn-primary">Apply</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endif
						@if(isset($show) && (($CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] !='') || ($CartAttr['isSecondCouponAvalilabe'] == 1 && $CartAttr['second_coupon_number'] !='')))
						<div class="row">
							<div class="col-12 mt-3">
								<div class="max_coupon_box">
									<div class="coupan_boxhd">
										<div class="float-left text-left">
											@if($CartAttr['coupon_number'] !='')
												<h6 class="">Applied Coupon Code : {{$CartAttr['coupon_number']}}</h6>
												<a href="javascript:void(0);" class="remdis" title="Remove" data-id="CouponDiscount">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
														<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
														<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
													</svg>
												</a>
											@endif
											@if($CartAttr['second_coupon_number'] !='')
												<h6 class="">
													Applied Coupon Code : {{$CartAttr['second_coupon_number']}} 
												</h6>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>	
						@endif
						@if($CartAttr['FundFlag'] == 1)
						<div class="row">	
							<div class="col-lg-12 mt-3">
								<div class="max_coupon_box">
									<div class="coupan_boxhd">
										<div class="float-left">
											<h6 class="">Available Fund : {{Price($CartAttr['available_funds'])}}</h6><br>
											<x-button btype="button" btntext="Add New Fund" classname="btn btn-primary" bid="btnAdddFundBilling" />
										</div>
									</div>
								</div>
							</div>
						</div>	
						@endif						
						<div class="chekout-main-btn mt-0 mt-md-3">
							<div class="row">
								<div class="col-4 text-left d-md-none">
									<span>{{$CartDetails['TotalItemInCart']}} items</span>
								</div>
								<div class="col-8 text-right d-md-none">
									<strong>Estimated Total : {{Price($NetTotal)}}</strong>
								</div>
							</div>
						</div>

						<div class="your-bag mb-4">
							@include('checkout.cart_table',[$CartDetails])
						</div>

						<div class="max_need_help mt-4 d-none d-md-block">
							<h4 class="">Need Help? <a href="contact-us.html" class="">Contact Us</a></h4>
							<ul>
								<li>
									<img src="{{config('global.SITE_IMAGES')}}phon.jpg" width="23" height="23" alt="" class="left">
									<small class="">
										<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Toll Free), 
										<a href="tel:{{config('Settings.INTERNATIONAL_PHONE_NO')}}">{{config('Settings.INTERNATIONAL_PHONE_NO')}}</a><br>
									</small>
								</li>
								<li>
									<svg class="svg-whatsapp vam left" aria-hidden="true" role="img" width="20" height="20">
										<use href="#svg-whatsapp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-whatsapp"></use>
									</svg>
									<small class="">
										<a href="tel:{{config('Settings.TEXT_NO')}}">{{config('Settings.TEXT_NO')}}</a> (Text Number)
									</small>
								</li>
								<li>
									<img src="{{config('global.SITE_IMAGES')}}email.jpg" width="23" height="23" alt="" class="left">
									<small>
										<a href="mailto:{{config('Settings.CONTACT_MAIL')}}" class="grey_text">{{config('Settings.CONTACT_MAIL')}}</a>
									</small>
								</li>
							</ul>
						</div> 
					</div> 
				</div>
			</div>
			<input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
			<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>
			<input type="hidden" name="SelPayMethod" id="SelPayMethod" value="{{$SelPayMethod}}"/>
			<input type="hidden" name="onlyGCPurchased" id="onlyGCPurchased" value="{{$CartAttr['onlyGCPurchased']}}"/>
			<input type="hidden" name="IsVenderItem" id="IsVenderItem" value="{{$CartAttr['IsVenderItem']}}"/>
			<input type="hidden" name="Afterpay_Checkout" id="Afterpay_Checkout" value="{{$CartAttr['Afterpay_Checkout']}}"/>
			<input type="hidden" name="IsCosmo" id="IsCosmo" value="{{$CartAttr['IsCosmo']}}"/>
			<input type="hidden" name="IsNandansons" id="IsNandansons" value="{{$CartAttr['IsNandansons']}}"/>
			<input type="hidden" name="IsPerfumePW" id="IsPerfumePW" value="{{$CartAttr['IsPerfumePW']}}"/>
			<input type="hidden" name="IsPCA" id="IsPCA" value="{{$CartAttr['IsPCA']}}"/>
			<input type="hidden" name="IsMaxaromaTwoDelivery" id="IsMaxaromaTwoDelivery" value="{{$CartAttr['IsMaxaromaTwoDelivery']}}"/>
			<input type="hidden" name="ISMaxTwoItem" id="ISMaxTwoItem" value="{{$CartAttr['ISMaxTwoItem']}}"/>
		</section>
	</main>		
@endsection