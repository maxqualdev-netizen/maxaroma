<main>
	<section class="checkout-page pb-md-5">
		<div class="container">
			<div class="chekout-top">
				<div class="mainhd text-left pt-4 pb-4 pt-lg-5 pb-lg-5">
					<h2>Checkout</h2>
				</div>
				<div class="progress">
					<ul>
						<li class="active" id="step1">
							<span class="prog-circal">
								<svg class="sv-chcek vam" aria-hidden="true" role="img" width="13" height="10">
									<use href="#sv-chcek" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chcek"></use>
								</svg>
							</span> <span>Billing & Shipping Address</span>
						</li>
						<li id="step2">
							<span class="prog-circal">
								<svg class="sv-chcek vam" aria-hidden="true" role="img" width="13" height="10">
									<use href="#sv-chcek" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-chcek"></use>
								</svg>
							</span> <span>Shipping Option</span>
						</li>
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
							<ul class="checkout-steps">
								<li id="billshipinfo" style="display:none"></li>
								<li class="billtab bill-info active">
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

					<div class="row">
						<div class="col-12 mt-3">
							<div class="max_coupon_box">
								<div class="coupan_boxhd">
									<div class="float-left w-50 text-left">
										<h6 class="">Promotional/Coupon Code</h6>
									</div>
									<div class="float-right w-50 text-right">
										<a href="javascript:void(0);" class="more-link">
											View promo codes 
											<svg class="sv-back vam" aria-hidden="true" role="img" width="14" height="14">
												<use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use>
											</svg>
										</a>
									</div>
								</div>
								<div class="pb-3 pb-md-4">
									<div class="cart-discount">
										<input type="text" placeholder="Enter Coupon Code" class="form-control">
										<a href="#" class="btn btn-primary">Apply</a>
									</div>
								</div>
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
							<div class="col-12 mt-3 mt-md-0">
								<a class="btn btn-secondary d-block checkout-btn" href="#">Ship To This Address</a>
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
								<img src="{{config('global.SITE_IMAGES')}}phon.jpg" alt="" class="left">
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
								<img src="{{config('global.SITE_IMAGES')}}email.jpg" alt="" class="left">
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