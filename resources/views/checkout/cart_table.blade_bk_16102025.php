<div class="your-bag" role="region" aria-labelledby="OrderSummaryTogg">
    @if(Session::has('CartError'))
        <x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('CartError'), 'mid' => 'error_cart', 'role' => 'alert', 'aria-live' => 'assertive']"/>
    @endif
    @if(Session::has('CartSuccess'))
        <x-message :attr="[ 'classname' => 'alert alert-success', 'message' => Session::get('CartSuccess'), 'mid' => 'success_cart', 'role' => 'status', 'aria-live' => 'polite']"/>
    @endif
    <div class="actmenuhd" role="heading" aria-level="2">
        <h4 class=""><span id="OrderSummaryTogg">Order Summary / Enter Coupon</span> 
            <svg id="down-arrow" class="sv-down-arrow vam ml-2" aria-hidden="true" role="img" width="14" height="12" focusable="false">
                <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
            </svg>
        </h4>
        <span class="float-right">
            <a href="{{config('global.SITE_URL').'shoppingcart/view'}}" class="ulink f12 mr-2" aria-label="View Cart"><strong>Cart</strong></a>|
            <strong id="net_total_amt1" data-amt="{{$NetTotal}}" aria-label="Net total amount">{{Price($NetTotal)}}</strong>
        </span>
    </div>
    @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
        <div class="actmenu-inner your-bag-inner mt-3" id="cart_items" role="list" aria-label="Cart Items">
            <ul>
                @foreach($CartDetails['Cart'] as $key => $CartItem)
                <li role="listitem">
                    <div class="summary-row" aria-labelledby="cart-item-{{$key}}">
                        @if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
                            <a href="javascript:void(0);" class="summary-img" aria-label="Free Gift Image">{!!$CartItem['Image']!!}</a>
                        @else
                            <a href="{{$CartItem['Prod_URL']}}" class="summary-img" aria-label="Product Image: {{$CartItem['ProductName']}}">{!!$CartItem['Image']!!}</a>
                        @endif
                        <div class="cart-product pb-1" id="cart-item-{{$key}}">
                            @if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
                                <span aria-label="Product Name">{{$CartItem['ProductName']}}</span>
                            @else 	
                                <a href="{{$CartItem['Prod_URL']}}" aria-label="Product: {{$CartItem['ProductName']}}">
                                    {{$CartItem['ProductName']}}
                                    @if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
                                        <br>		
                                        <span class="d-inline-block" aria-label="Short Description">{{$CartItem['short_description']}}</span>
                                    @endif
                                </a> 
                            @endif
                            <div class="SKU"> <span aria-label="SKU">SKU: {{$CartItem['SKU']}}</span> </div>
                            <div class="SKU"> <span aria-label="Quantity">Qty: {{$CartItem['Qty']}}</span> </div>
                            @if(isset($CartItem['FinalSale']) && $CartItem['FinalSale']!='' )
                                <div class="SKU"> <span aria-label="Final Sale">{{$CartItem['FinalSale']}}</span></div>
                            @endif
                        </div>
                        <div class="cart-price text-right">
                            <span aria-label="Item Price">{{Price($CartItem['TotPrice'])}}</span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="order-summry mt-4" role="region" aria-label="Order Summary">
                <div class="row">
                    <div class="col-12" id="cartsubtotal">
                        @include('checkout.subtotalbox',[$CartDetails])
                    </div>
                </div>
            </div>
            
            @if($show_afterpay_widget_box == "Yes")
                <div class="order-summry mt-4" id="alert_ap" style="display:none" role="alert" aria-live="assertive">
                    <div class="row">
                        <div class="col-12"><div class="alert alert-danger" role="alert" id="alert_order"></div></div>
                    </div>
                </div>	
                <div class="order-summry mt-4" id="checkout_widget_ap" role="region" aria-label="AfterPay Payment Widget">
                    <div class="row">
                        <div class="col-12" id="ap_checkoutwidget">
                            <div id="afterpay-widget-container"></div>
                            <input type="hidden" name="pschecksum" id="pschecksum" value="" aria-hidden="true">
                            <script>
                                // Ensure this function is defined before loading afterpay.js
                                function createAfterpayWidget () {
                                    window.afterpayWidget = new AfterPay.Widgets.PaymentSchedule({
                                        token: '{{$afterpay_checkout_token}}',
                                        target: '#afterpay-widget-container',
                                        locale: 'en-US', 
                                        onReady: function (event) {
                                        var paymentScheduleChecksum;
                                        paymentScheduleChecksum = event.data.paymentScheduleChecksum;
                                            afterpayWidget.update({
                                                amount: { amount: "{{$NetTotal}}", currency: "USD" },
                                            })
                                            $("#pschecksum").val(paymentScheduleChecksum);
                                            if($("#ap_psChecksum").length > 0){
                                                $("#ap_psChecksum").val(paymentScheduleChecksum);
                                            }
                                        },
                                        onChange: function (event) {
                                            // Fires after each update and on any other state changes.
                                            // See "Getting the widget's state" for more details.
                                            // console.log(event);
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
                                            // console.log(event);
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
                    {{-- @if(isset($show) && $CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] == '') --}}
                    @if($CartAttr['isCouponsAvailable'] == 1 &&  !Session::has('PayPalToken'))
                        <div class="col-12 remdis checkout">
                            <div class="max_coupon_box" role="form" aria-labelledby="coupon-code-label">
                                <div class="coupan_boxhd plus-minus">
                                    <h6 class="f12" id="coupon-code-label">Promotional / Coupon Code</h6>
                                </div>
                                <div class="cart-discount">
                                    <div class="form-group mb-0">
                                        <input type="text" name="coupon_number" id="coupon_number" class="form-control mb-0" aria-label="Enter Coupon Code" aria-describedby="coupon-code-label">
                                        <label for="coupon_number" class="col-form-label">Enter Coupon Code</label>
                                        <a href="javascript:void(0);" id="btncouponapply" class="btn btn-secondary checkout" role="button" aria-label="Apply Coupon Code">Apply</a> 
                                    </div>
                                </div>
                            </div>
                        </div>				
                    @endif
            
                    @if($CartAttr['allow_gift'] == 1 && !Session::has('PayPalToken') && $CartAttr['onlyGCPurchased']==0 )
                        <div class="col-12 remdis checkout">
                            <div class="max_coupon_box" role="form" aria-labelledby="gift-card-label">
                                <div class="coupan_boxhd plus-minus">
                                    <h6 class="f12" id="gift-card-label">E-Gift Card Code</h6>
                                </div>
                                <div class="cart-discount">
                                    <div class="form-group mb-0">
                                        <input type="text" id="txtgiftcard" value="{{Session::get('ShoppingCart.GiftCoupon.Code')}}" class="form-control mb-0" aria-label="Enter E-Gift Card Code" aria-describedby="gift-card-label">
                                        <label for="txtgiftcard" class="col-form-label">Enter E-Gift Card Code</label>
                                        <a href="javascript:void(0);" id="btngiftcard" class="btn btn-secondary checkout" role="button" aria-label="Apply E-Gift Card Code">Apply</a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        @if($CurrentRoute == 'billing' && (!Session::has('ShoppingCart.YotpoRewardCode') || (Session::has('ShoppingCart.YotpoRewardCode') && trim(Session::get('ShoppingCart.YotpoRewardCode'))  == '')))	
            <div class="col-12">
                <div class="yotpo-widget-instance" data-yotpo-instance-id="90560" role="region" aria-label="Yotpo Rewards"></div>
            </div>
        @endif
    @endif
</div>
@if($CartAttr['FundFlag'] == 1 && $CurrentRoute == 'billing-payment')
    <div class="row">	
        <div class="col-lg-12 mt-3">
            <div class="max_coupon_box" role="region" aria-label="Available Fund">
                <div class="coupan_boxhd">
                    <div class="float-left">
                        <h6 class="" aria-label="Available Fund">Available Fund : {{Price($CartAttr['available_funds'])}}</h6><br>
                        <x-button btype="button" btntext="Add New Fund" classname="btn btn-primary" bid="btnAdddFundBilling" aria-label="Add New Fund"/>
                    </div>
                </div>
            </div>
        </div>
    </div>	
@endif						
<div class="max_need_help mt-4 d-none d-md-block" role="region" aria-label="Need Help Information">
    <ul>
        <li>
            <span>
                <svg class="svg-truck" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                    <use href="#svg-truck" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-truck"></use>
                </svg>
            </span>
            Free Standard Shipping
        </li>
        <li>
            <span>
                <svg class="svg-return" aria-hidden="true" role="img" width="18" height="18" focusable="false">
                    <use href="#svg-return" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-return"></use>
                </svg>
            </span>
            Free Returns & Exchanges (excludes final sale items)
        </li>
        <li>
            @if($CartAttr['onlyGCPurchased'] != 1)
                <a href="javascript:void(0);" title="Shipping Rate Calculator" class="shippingCalculate" role="button" aria-label="Shipping Rate Calculator">
                    <span>
                        <svg class="svg-calculator" aria-hidden="true" role="img" width="16" height="16" focusable="false">
                            <use href="#svg-calculator" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-calculator"></use>
                        </svg>
                    </span>
                    Shipping Rate Calculator 
                </a>
            @endif
        </li>
        <li>
        <span>
            <svg class="svg-doler" aria-hidden="true" role="img" width="16" height="16" focusable="false">
                <use href="#svg-doler" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-doler"></use>
            </svg>
        </span>
        Items are not reserved until checkout is complete. Prices are subject to change based on the price in effect the day you checkout.</li>
    </ul>
</div>