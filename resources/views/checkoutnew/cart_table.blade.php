<div class="your-bag">
    @if(Session::has('CartError'))
		<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('CartError'), 'mid' => 'error_cart']"/>
	@endif
	@if(Session::has('CartSuccess'))
		<x-message :attr="[ 'classname' => 'alert alert-success', 'message' => Session::get('CartSuccess'), 'mid' => 'success_cart']"/>
	@endif
    <div class="actmenuhd">
        <h4 class="">Order Summary <svg id="down-arrow" class="sv-down-arrow vam ml-2" aria-hidden="true" role="img" width="14" height="12">
            <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
        </svg>
        </h4>
        <span class="float-right"><a href="{{config('global.SITE_URL').'shoppingcart/new'}}" class="ulink f12 mr-2">View Cart</a>  <strong>{{Price($NetTotal)}}</strong></span>

    </div>
    @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
    <div class="actmenu-inner your-bag-inner mt-3" id="cart_items">
        <ul>
            @foreach($CartDetails['Cart'] as $key => $CartItem)
            <li>
                <div class="summary-row">
                    @if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
                        <a href="javascript:void(0);" class="summary-img">{!!$CartItem['Image']!!}</a>
                    @else
                        <a href="{{$CartItem['Prod_URL']}}" class="summary-img">{!!$CartItem['Image']!!}</a>
                    @endif
                    <div class="cart-product pb-1">
                        @if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
                            {{$CartItem['ProductName']}}
                        @else 	
                            <a href="{{$CartItem['Prod_URL']}}">{{$CartItem['ProductName']}}
                                @if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
                                    <br>		
                                    <span class="d-inline-block">{{$CartItem['short_description']}}</span>
                                @endif
                            </a> 
                        @endif
                    </div>
                    <div class="cart-price text-right">
                        <span>{{Price($CartItem['TotPrice'])}}</span>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="order-summry mt-4">
            <div class="row">
                <div class="col-12" id="cartsubtotal">
                    @include('checkoutnew.subtotalbox',[$CartDetails])
                </div>
            </div>
        </div>
        
		@if($show_afterpay_widget_box == "Yes")
			<div class="order-summry mt-4" id="alert_ap" style="display:none">
				<div class="row">
					<div class="col-12"><div class="alert alert-danger" role="alert" id="alert_order"></div></div>
				</div>
			</div>	
			<div class="order-summry mt-4" id="checkout_widget_ap">
				<div class="row">
					<div class="col-12" id="ap_checkoutwidget">
						<div id="afterpay-widget-container"></div>
						<input type="hidden" name="pschecksum" id="pschecksum" value="">
						<script>
							 // Ensure this function is defined before loading afterpay.js
							 function createAfterpayWidget () {
							   window.afterpayWidget = new AfterPay.Widgets.PaymentSchedule({
								 token: '{{$afterpay_checkout_token}}',
								 target: '#afterpay-widget-container',
								 locale: 'en-US', 
								 onReady: function (event) {
								   // Fires when the widget is ready to accept updates.  
									afterpayWidget.update({
										amount: { amount: "{{$NetTotal}}", currency: "USD" },
									})
								 },
								 onChange: function (event) {
								   // Fires after each update and on any other state changes.
								   // See "Getting the widget's state" for more details.
								   console.log(event);
								   var paymentScheduleChecksum;
								   if(event.data.isValid == true){
										$("#alert_order").html("");
										$("#alert_ap").hide();
										$("#checkout_widget_ap").show();
										
										paymentScheduleChecksum = event.data.paymentScheduleChecksum;
										$("#pschecksum").val(paymentScheduleChecksum);
										if($("#ap_psChecksum").length > 0){
											$("#ap_psChecksum").val(paymentScheduleChecksum);
										}
								   }else{
									   //issue
										// console.log(event);
										$("#alert_order").html("AfterPay is disabled because of amount range limit reached.");
										$("#alert_ap").show();
										$("#checkout_widget_ap").hide();
								   }
								 },
								 onError: function (event) {
								   // See "Handling widget errors" for more details.
									console.log(event);
									$("#alert_order").html("AfterPay is disabled because of amount range limit reached.");
									$("#alert_ap").show();
									$("#checkout_widget_ap").hide();
									
									// if($("#net_total_amt").data("amt") >= $("#net_total_amt").data("minap") && $("#net_total_amt").data("amt") < $("#net_total_amt").data("maxap")){
										//code here
									// }
									// $("#ap_checkoutwidget").hide();
								 },
							   })
							 }
							 
					   </script>
					   <script src="{{$token_js_url}}" async onload="createAfterpayWidget()"> </script>
					</div>
				</div>
			</div>
		@endif
		
		@if($CurrentRoute == 'billing')
		<div class="row no-gutters">
			{{--@if(isset($show) && $CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] == '')--}}
			@if($CartAttr['isCouponsAvailable'] == 1 &&  !Session::has('PayPalToken'))
				<div class="col-12">
					<div class="max_coupon_box">
						<div class="coupan_boxhd plus-minus">
							<h6 class="f12">Promotional/Coupon Code</h6>
						</div>
						<div class="cart-discount">
							<div class="form-group mb-0">
								<input type="text" name="coupon_number" id="coupon_number" class="form-control mb-0">
								<label for="guest_confirm_password" class="col-form-label">Enter Coupon Code</label>
								<a href="javascript:void(0);" id="btncouponapply" class="btn btn-secondary checkout">Apply</a> 
							</div>
						</div>
					</div>
				</div>
			@endif
        
			@if(($CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] !='') || ($CartAttr['isSecondCouponAvalilabe'] == 1 && $CartAttr['second_coupon_number'] !=''))
				<div class="col-12">
					<div class="max_coupon_box active">
						<div class="coupan_boxhd">
							@if($CartAttr['coupon_number'] !='')
								<h6 class="f12">
									Applied Coupon Code : {{$CartAttr['coupon_number']}} 
									<a href="javascript:void(0);" class="remdis checkout" title="Remove" data-id="CouponDiscount">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
										</svg>
									</a>
								</h6>
							@endif
							@if($CartAttr['second_coupon_number'] !='')
								<h6 class="f12">
									Applied Coupon Code : {{$CartAttr['second_coupon_number']}} 
								</h6>
							@endif  
						</div>
					</div>
				</div>
			@endif
        
			@if($CartAttr['allow_gift'] == 1 && !Session::has('PayPalToken'))
				<div class="col-12">
					<div class="max_coupon_box">
						<div class="coupan_boxhd plus-minus">
							<h6 class="f12">E-Gift Card Code</h6>
						</div>
						<div class="cart-discount">
							<div class="form-group mb-0">
								<input type="text" id="txtgiftcard" value="{{Session::get('ShoppingCart.GiftCoupon.Code')}}" class="form-control mb-0">
								<label for="guest_confirm_password" class="col-form-label">Enter E-Gift Card Code</label>
								<a href="javascript:void(0);" id="btngiftcard" class="btn btn-secondary checkout">Apply</a> 
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
		@endif
    </div>
    @endif
</div>
@if($CartAttr['FundFlag'] == 1 && $CurrentRoute == 'billing-payment')
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
<div class="max_need_help mt-4 d-none d-md-block">
    <ul>
        <li>
            <span>
                <svg class="svg-truck" aria-hidden="true" role="img" width="22" height="22">
                    <use href="#svg-truck" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-truck"></use>
                </svg>
            </span>
            Free Standard Shipping
        </li>
        <li>
            <span>
                <svg class="svg-return" aria-hidden="true" role="img" width="18" height="18">
                    <use href="#svg-return" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-return"></use>
                </svg>
            </span>
            Free Returns & Exchanges (excludes final sale items)
        </li>
        <li>
            @if($CartAttr['onlyGCPurchased'] != 1)
                <a href="javascript:void(0);" title="Shipping Rate Calculator" class="shippingCalculate">
                    <span>
                        <svg class="svg-calculator" aria-hidden="true" role="img" width="16" height="16">
                            <use href="#svg-calculator" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-calculator"></use>
                        </svg>
                    </span>
                    Shipping Rate Calculator 
                </a>
            @endif
        </li>
        <li>
        <span>
            <svg class="svg-doler" aria-hidden="true" role="img" width="16" height="16">
                <use href="#svg-doler" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-doler"></use>
            </svg>
        </span>
        Items are not reserved until checkout is complete. Prices are subject to change based on the price in effect the day you checkout.</li>
    </ul>
</div>
