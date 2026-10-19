@if($Is_Afterpay_Checkout == "Yes")
<script>
	// ensure this function is defined before loading afterpay.js
	function initAfterpayCheckout() {        
		setTimeout(function(){
			AfterPay.initializeForPopup({
				countryCode: 'US',
				onCommenceCheckout: function (actions) {
					/* retrieve afterpay token from your server */
					/* then call `actions.resolve(token)` */
					// actions.resolve('$Afterpay_Token');
					
					 $.ajax({
							type: "POST",
							url: site_url+"afterpay/placeorder_express",
							headers: {
								'X-CSRF-TOKEN': token
							},
							data: "get_token=1",
							dataType: "JSON",
							success: function(response){
								  if(response.success == 1 && response.token != ""){
									  actions.resolve(response.token);
								  }else{
									  alert(response.message);
									  // location.reload();
									  return false;
								  }
						}
					})
				},onShippingAddressChange: function (data, actions) {
					/* address in `data` */
					/* calc options, then call `actions.resolve(options)` */
					// console.log(data);
					actions.resolve({!! $Shipping_Arr_AP !!}) 
				},
				onComplete: function (event) {
					$("#page-spinner").show()
					// console.log(event);
					/* handle success/failure of checkout */
					if (event.data.status == "SUCCESS") {
					  // The consumer has confirmed the payment schedule.
					  // Call your server here to retrieve the order details
					  var order_token =  event.data.orderToken;
					  var merchant_reference =  event.data.merchantReference;
					  
					   $.ajax({
							type: "GET",
							url: site_url+"afterpay/billing_checkout_express/2/"+order_token, 
							headers: {
								'X-CSRF-TOKEN': token
							},
							dataType: "JSON",
							success: function(res){
								  if(res==1){
									$("input[name='PaymentMethod']").after('<input type="hidden" name="is_btm_ap_chkout" value="Yes">')  
									$("#btnPlaceOrder").trigger("click"); 
								  }else{
									  $("#pschecksum").val('');
									  $("#ap_psChecksum").val('');
									  document.location = site_url+"checkout/view"; 
									  return false;
								  }
						}
					})
					  return false;
					} else {
					  var order_token =  "undefined";
					   
					  // The consumer cancelled the payment or closed the popup window.
					  location.href = site_url+"afterpay/billing_checkout_express/0/"+order_token;
					  return false;
					}
				},
				target: '#afterpay-button',
				shippingOptionRequired: true,
				buyNow: true,
			})
		},500);
	}
</script>
<!-- <script type="text/javascript" src="https://static-us.afterpay.com/javascript/present-afterpay.js"></script>-->
<script src="{!! $token_js_url !!}" async onload="initAfterpayCheckout()"></script>
@endif


<form method="post" action="{{route('placeorder')}}" name="order_process" id="order_process">
	<input type="hidden" name="shipping_signature" id="shipping_signature" value="{{$shipping_signature}}"/>
<div class="row">
    <div class="col-12 d-md-none">
        <div class="mobile-shadow mb-0">&nbsp;</div>
    </div>
	<div class="col-12">
		<h4 class="cart-hd-sub text-center">Payment</h4>
	</div>
	<div class="col-12">    
        @if($show=="Yes" || $MethodNoShow=="Yes")
            <div class="pt-3">
                <div class="alert alert-danger">
                    To complete the order, Please use 'Checkout with Paypal' OR 'Pay with Amazon' payment option on shopping cart page.  <a href="{{url('/shoppingcart/view')}}">click here </a> to go shopping cart.
                </div>
            </div>	
        @else
            <div>
            @if(count($PaymentMethodList) > 0)
                @if($OnlyWT == 1 && Session::get('eusertype') == "Retailer")
                    <div class="alert alert-danger">
                        Checkout is temporarily unavailable at this time, Please contact administrator.
                    </div>	
                @else
                    @if($onlyAmazonPaypal != 1)
                        <input type="hidden" name="shipsignatureflag" id="shipsignatureflag" value=""/>
                        <input type="hidden" name="ap_psChecksum" id="ap_psChecksum" value="">
						
                        {{ csrf_field() }}
                        @if($Is_Afterpay_Checkout == 'No')
                            <div style="display:none;">
                                <div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
                            </div>	
                        @endif	
                        <div class="pb-2 mb-1">All transactions are secure and encrypted.</div>
                        @foreach($PaymentMethodList as $pkey => $PMethod)				
                            @if((Session::get('eusertype') == "" || Session::get('eusertype') == "Retailer") && $PMethod['pm_name'] != "Wire Transfer" && $PMethod['pm_group_name'] !='PAYMENT_GIFT_CERTIFICATE')
                                <label @if($NetTotal <= 0 ) style="display:none !important;" @endif class="comcheck radio checkbox-label pay-radio card-pay credit-methods w-100">
                                    <div class="chebox">
                                        <input type="radio" name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}" @if($NetTotal > 0  && ((isset($arrPaymentDetail) && $arrPaymentDetail['Payment_Type'] == $PMethod['pm_group_name']) || $SelMethod == $PMethod['pm_group_name'] || $pkey==0 || $ptype == 'AP')) checked @endif >
                                        <span class="checkmark"></span>
                                        <span class="fw500">{{$PMethod['pm_name']}}
                                            @if($PMethod['pm_group_name'] == 'PAYMENT_STRIPE')
                                                <img src="{{config('global.SITE_IMAGES')}}/all-card.png" alt="" class="img-fluid all-card">
                                            @endif
                                        </span>
                                    </div>
                                    @if($PMethod['pm_group_name'] == 'PAYMENT_PAYWITHAFTERPAY')
                                        <div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
                                    @endif
                                </label>
                            @endif

                            @if(Session::get('eusertype') == "Wholesaler")
                                <label @if($NetTotal <= 0 ) style="display:none !important;" @endif class="comcheck radio checkbox-label pay-radio card-pay credit-methods">
                                    <div class="chebox">
                                        <input type="radio" checked name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}" @if($NetTotal > 0  && ((isset($arrPaymentDetail) && $arrPaymentDetail['Payment_Type'] == $PMethod['pm_group_name']) || $SelMethod == $PMethod['pm_group_name'] || $pkey==0 || $ptype == 'AP')) checked @endif >
                                        <span class="checkmark"></span>
                                        <span class="fw500">{{$PMethod['pm_name']}}
                                            @if($PMethod['pm_group_name'] == 'PAYMENT_STRIPE')
                                                <img src="{{config('global.SITE_IMAGES')}}/all-card.png" alt="" class="img-fluid all-card">
                                            @endif
                                        </span>
                                    </div>
                                    @if($PMethod['pm_group_name'] == 'PAYMENT_PAYWITHAFTERPAY')
                                        <div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
                                    @endif
                                </label>
                            @endif	
                        @endforeach


                         <label id="PaymentDetailsStoreView" @if($NetTotal != 0) style="display:none !important;" @endif class="comcheck radio d-inline-block w-100 checkbox-label pay-radio card-pay">
                            <div class="chebox">
                                @if($OnlyGiftCert==1)
                                <input type="radio" name="PaymentMethod" id="paytypeID0" value="PAYMENT_GIFT_CERTIFICATE" >
                                @else
                                <input type="radio" name="PaymentMethod" id="paytypeID0" value="PAYMENT_CL" >
                                @endif
                                
                                <span class="checkmark"></span>
                                <span class="float-left w-100">
									@if($OnlyGiftCert==1)
									Gift Certificate
									@else
									Credit Limit
									@endif
									</span>
                            </div>
                        </label>
                       
					    <!-- express checkout bottom buttons-->
						@if($is_afterpay != "yes" && $NetTotal > 0)
						   <div class="express-ck express-ck-inner text-center mt-md-3">
							<div class="row">
							<div class="col-12">
								<h3 class="cart-hd p-0">Express Checkout</h3>
							</div>
							<div class="col-12">
							   @if($Is_Afterpay_Checkout == "Yes" && $SelPayMethod != 'paypal')
									<a id="afterpay-button" data-afterpay-entry-point="cart" class="btn btn-primary inner-paybtn" href="javascript:void(0)"> 
										Pay with Afterpay
									</a> 	
								@endif					   
								@if($PaypalPayButton == 'Yes')
										<a class="paypal-btn btn btn-primary inner-paybtn" href="{{url('paypal/placeorder')}}"> 
											Pay with Paypal 
										</a> 
									
								@endif
							
									@if($AmazonPayButton == 'Yes' && $CartAttr['onlyGCPurchased'] != '1')
										<a class="amazon-btn btn btn-primary inner-paybtn" href="javascript:void(0);"> 
											Pay with Amazon 
										</a> 
										<div id="AmazonPayButtonAll" class="d-none"></div>
										<script type="text/javascript">
											window.onAmazonLoginReady = function() {
												amazon.Login.setClientId("{{config('CLIENT_ID')}}");
												amazon.Login.setUseCookie(true);
											};
											var call_url= "{{config('CALLBACK_CHECKOUT_URL')}}";
										</script>
										<script type="text/javascript" src="{{config('JS_SERVER_URL')}}"></script>
										<script>
											var authRequest;
												OffAmazonPayments.Button("AmazonPayButtonAll", "{{config('MERCHANT_ID')}}", {
													type: "PwA",
													size: "large",
													authorization: function () {
														loginOptions = { scope: "profile postal_code payments:widget payments:shipping_address", popup: true };
														authRequest = amazon.Login.authorize(loginOptions, call_url);
												},
												onError: function (error) {
													// something bad happened
												}
											});
										</script>
									@endif
								</div>
                               
                                 @if(config('global.StripeButton') == 'Show')
                                   <div class="col-12 mt-3 text-center" id="payment-request-button"></div>
                                 @endif
                                    <input type="hidden" name="is_stripe_wallet" id="is_stripe_wallet" value=""/>
                                    <input type="hidden" name="is_stripe_applepay" id="is_stripe_applepay" value=""/>
									<input type="hidden" name="is_paypal" id="is_paypal" value="{{$is_paypal}}"/>
									<input type="hidden" name="is_step_gpay" id="is_step_gpay" value="LastStep"/>
								</div>	
							</div>
						@endif
						<!-- express checkout bottom buttons-->
                    @endif
                @endif	 
            @else
                <div class="alert alert-danger">
                    @if(Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') =='Wholesaler')
                        You do not have sufficient balance in your dropship account. To complete your order, please <a href="javascript:void(0)" class="add-fund" >Add Fund</a> to your account.
                    @else	 
                        Checkout is temporarily unavailable at this time, Please contact administrator.
                    @endif
                </div>	
            @endif
            @if(Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') =='Wholesaler' && isset($DropshipperAccountDetails))
            <div class="row">	
                <div class="col-lg-12 mt-0 mt-md-4">
                    <div class="max_coupon_box">
                        <div class="coupan_boxhd">
                            <div class="col-12 text-center">
                                <div>Dropshipper Information</div>
                                @if(isset($DropshipperAccountDetails['fund_msg']) && $DropshipperAccountDetails['fund_msg'] != "")
                                    <div class="alert alert-danger">
                                        {{$DropshipperAccountDetails['fund_msg']}}
                                    </div>
                                @endif	
                                <table width="100%" border="0">
                                    <tr>
                                        <th>Total Fund</th>
                                        <th>Required Amount</th>
                                        <th>
                                            @if(isset($DropshipperAccountDetails['fund_available']) && $DropshipperAccountDetails['fund_available'] == 'Yes')
                                                Remaining Fund
                                            @else
                                                Required Fund
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <td align="center">{{Price($DropshipperAccountDetails['total_fund'])}}</td>
                                        <td align="center">{{Price($DropshipperAccountDetails['total_payment'])}}</td>
                                        <td align="center">
                                            @if(isset($DropshipperAccountDetails['fund_available']) && $DropshipperAccountDetails['fund_available'] == 'Yes')
                                                {{Price($DropshipperAccountDetails['remaining_fund'])}}
                                            @else
                                                {{Price($DropshipperAccountDetails['required_fund'])}} | <a href="javascript:void(0);" class="add-fund" >Add Fund</a>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            @endif	 
            @if(count($PaymentMethodList) > 0 && $onlyAmazonPaypal != 1 && $CartAttr['CreditLimitFlag'] != 0)
            <label class="checkbox-label che-box w-100 mt-3">
                <div class="chebox">
                    @if($CreditDiscount > 0 && $CartAttr['CreditLimitFlag'] == 2)
                        <input type="checkbox" checked name="chkcreditlimit" id="chkcreditlimit" value="{{$CartAttr['RemainCreditLimit']}}" @if($CartAttr['onlyGCPurchased'] == '1') disabled @endif />
                        <span class="checkmark"></span>
                        <span class="float-left w-100" id="credamt">
                            @if($CartAttr['RemainCreditLimit'] > 0)
                                Use your account balance, Your account balance is : {{Price($CartAttr['RemainCreditLimit'])}}
                            @else 
                                Your account balance has been applied.
                            @endif
                        </span>
                    @else
                        <input type="checkbox" name="chkcreditlimit" id="chkcreditlimit" value="{{$CartAttr['CreditLimit']}}" />
                        <span class="checkmark"></span>
                        <span class="float-left w-100" id="credamt">
                            Use your account balance, Your account balance is : {{Price($CartAttr['CreditLimit'])}}
                        </span>	
                    @endif
                </div>
            </label>
            @endif	
        </div>
        @if(count($PaymentMethodList) > 0 && $onlyAmazonPaypal != 1)
            @if($giftflag == 1)
                <div class="gift-card">
                    <div class="actmenuhd">
                        <svg class="svg-newgift vam" aria-hidden="true" role="img" width="24" height="24">
                            <use href="#svg-newgift" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newgift"></use>
                        </svg>
                        <span>Add gift card</span>
                        <svg id="giftcard-down-arraw" class="sv-down-arrow vam float-right" aria-hidden="true" role="img" width="20" height="20">
                            <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
                        </svg>
                    </div>
                    <div class="actmenu-inner mt-4" id="divgiftcard">
                        <form name="frmgiftcard" id="frmgiftcard">
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">From<span class="errmsg">*</span></label>
                                        <input id="gift_from" name="gift_from" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftFrom')}}" >
                                        <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_from']"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">To<span class="errmsg">*</span></label>
                                        <input id="gift_to" name="gift_to" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftTo')}}">
                                        <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_to']"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone" class="col-form-label">Gift Message</label>
                                        <textarea id="gift_message_customer" name="gift_message_customer" rows="4" cols="50" type="text" class="form-control">{{Session::get('ShoppingCart.GiftMessageCustomer')}}</textarea>
                                        <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_message_customer']"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Select Gift<span class="errmsg">*</span></label>
                                        {!! $freegiftcombo !!}
                                        <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_freegiftvalue']"/>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-actions clearfix pt-0 pb-0">                       
                                        <div class="float-left">
                                            <a href="javascript:void(0);" id="btnappygiftcard" class="btn btn-primary">Apply</a>
                                            <a href="#" class="ulink cancel-btn">Cancel</a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            @if($OnlyWT == 1 && Session::get('eusertype') == "Retailer")
            @else
            <div class="row">
                @if($dropshipFundSection == 'No')
                <div class="col-12">
                    <div class="form-group">
                        <h4 class="cart-hd-sub text-center pt-4 mt-md-3 mb-md-2 pb-4 fw500">Special Request</h4>
                        <textarea id="customer_comment" class="form-control" name="customer_comment" placeholder="Special Request" ></textarea>
                    </div>	
                </div>
                @endif	
                <div class="col-12 pt-1 pb-md-3">
                    <label class="checkbox-label che-box">
                        <div class="chebox">
                            <input type="checkbox" checked id="chkagree"><span class="checkmark"></span>
                            <span>By placing and order on MaxAroma.com you agree with the <a href="{{url('/terms-and-conditions.html')}}" target="_blank">terms and conditions</a>.</span>
                        </div>
                    </label>
                </div>
                <div class="col-12 d-md-none">
                    <div class="mobile-shadow">&nbsp;</div>
                </div>
                <div class="col-12 pt-md-3 pb-md-5">
                    <div class="chekout-main-btn">	
                        <a href="javascript:void(0);" class="btn btn-primary d-block checkout-btn" id="btnPlaceOrder">Place Order</a>
                    </div>
                </div>
                <div class="col-12 f12 text-center">
                     <a href="{{config('global.SITE_URL')}}shipping" class="ulink"><strong>Return to Shipping</strong></a>
                </div>
            </div>
            @endif
		@endif
	@endif
	 <input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
			<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>	
</form>
