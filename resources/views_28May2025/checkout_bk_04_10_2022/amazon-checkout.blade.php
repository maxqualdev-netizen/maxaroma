@extends('layouts.app')
@section('content')
	<main>
        <section class="checkout-page pb-md-5">
            <div class="container">
                <div class="chekout-top">
                    <div class="mainhd text-left pt-4 pb-4 pt-lg-5 pb-lg-5">
                        <h2>Checkout</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row pr-lg-4 pr-0">
							<div class="col-12">
								@if(Session::has('PlaceOrderError'))
									<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_order']"/>
								@endif
								<form id="frmamazon" method="post" action="{{url('/placeorder')}}">
									{{ csrf_field() }}
									<input type="hidden" name="PaymentMethod" value="PAYMENT_PAYWITHAMAZON"/>
									<ul class="checkout-steps">
										<li class="bill-info active">
											<div class="mb-0 mb-md-4">
												<h3 class="cart-hd d-none d-md-block">Billing Shipping Information</h3>
												<div id="addressBookWidgetDiv" class="mt-3" style="height:300px;"></div>
											</div>
										</li>
										@if($CartAttr['onlyGCPurchased'] == 0)
										<li class="shipping-method active">
											<h3 class="cart-hd d-none d-md-block">Shipping Option</h3>
										</li>
										@endif
										@if($CartAttr['CreditLimitFlag'] != 0)
										<li class="active"> 	
											@include('checkout.credit-limit',[$CreditDiscount,$CartDetails])
										</li>	
										@endif
										<li class="payment-method active">
											<h3 class="cart-hd d-none d-md-block">Payment Info</h3>
											<div id="walletWidgetDiv" class="mt-3 mb-3" style="height:300px;"></div>
											<a href="javascript:void(0);" class="btn btn-primary" id="btnAmazonOrder">Place Order</a>
										</li>
									</ul>
								</form>	
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

						<div class="your-bag mt-3 mb-4">
							@include('checkout.cart_table',[$CartDetails])
						</div>

						<div class="max_need_help mt-4 d-none d-md-block">
							<h4 class="">Need Help? <a href="contact-us.html" class="">Contact Us</a></h4>
							<ul>
								<li>
									<img src="{{config('global.SITE_IMAGES')}}phon.jpg" width="23" height="23" alt="" class="left">
									<small class="">
										<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Toll Free), 
										<a href="tel:{{config('Settings.INTERNATIONAL_PHONE_NO')}}">{{config('Settings.INTERNATIONAL_PHONE_NO')}}</a> (Outside of USA)<br>
									</small>
								</li>
								<li>
									<svg class="svg-whatsapp vam left" aria-hidden="true" role="img" width="20" height="20">
										<use href="#svg-whatsapp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-whatsapp"></use>
									</svg>
									<small class="">
										<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Text Number)
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
			</div>
			<input type="hidden" name="onlyGCPurchased" id="onlyGCPurchased" value="{{$CartAttr['onlyGCPurchased']}}"/>
			<input type="hidden" name="IsVenderItem" id="IsVenderItem" value="{{$CartAttr['IsVenderItem']}}"/>
			<input type="hidden" name="IsCosmo" id="IsCosmo" value="{{$CartAttr['IsCosmo']}}"/>
			<input type="hidden" name="IsNandansons" id="IsNandansons" value="{{$CartAttr['IsNandansons']}}"/>
			<input type="hidden" name="IsPerfumePW" id="IsPerfumePW" value="{{$CartAttr['IsPerfumePW']}}"/>
			<input type="hidden" name="IsPCA" id="IsPCA" value="{{$CartAttr['IsPCA']}}"/>
			<input type="hidden" name="IsMaxaromaTwoDelivery" id="IsMaxaromaTwoDelivery" value="{{$CartAttr['IsMaxaromaTwoDelivery']}}"/>
        </section>
    </main>	
@endsection