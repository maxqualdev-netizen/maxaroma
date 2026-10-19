@extends('layouts.app')
@section('content')

@if($Afterpay_Checkout == 'Yes' && $OrderRs->pay_status != 'Paid')
	<script>
		// ensure this function is defined before loading afterpay.js
		function initAfterpay () {
			setTimeout(function(){
				AfterPay.initializeForPopup({
					countryCode: 'US',
					onCommenceCheckout: function (actions) {
						/* retrieve afterpay token from your server */
						/* then call `actions.resolve(token)` */
						// actions.resolve('$Afterpay_Token');
						
						 $.ajax({
								type: "POST",
								url: site_url+"afterpay/phoneorder_express",
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
						console.log(data);
						
						 /*  actions.resolve([ {
							id: '29', name: "MAX2DAYS", description: "MAX2DAYS",
							shippingAmount: { amount: "0.00", currency: "USD"},
							taxAmount: { amount: '3.18', currency: 'USD'},
							orderAmount: { amount: '42.18', currency: 'USD'},
						  } 
						  ])  */
						  
						  actions.resolve({!! $Shipping_Arr_AP !!}) 
					},
					onComplete: function (event) {
						$("#page-spinner").show()
						
						/* handle success/failure of checkout */
						if (event.data.status == "SUCCESS") {
						  // The consumer has confirmed the payment schedule.
						  // Call your server here to retrieve the order details
						  var order_token =  event.data.orderToken;
						  var merchant_reference =  event.data.merchantReference;
						  
						  location.href = site_url+"afterpay/success_phoneorder_express/1/"+order_token;
						  return false;
						} else {
						  var order_token =  "undefined";
						  
						  // The consumer cancelled the payment or closed the popup window.
						  location.href = site_url+"afterpay/success_phoneorder_express/0/"+order_token;
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
	<script src="{!! $token_js_url !!}" async onload="initAfterpay()"></script>
@endif

<main>
	<div class="myact">
	   	<div class="make-payment">
			<div class="container">
				<div class="row row10">
					<div class="col-12 col-lg-10 offset-lg-1">
						@if($WebsiteAvailStock=="No" && $OrderRs->pay_status != 'Paid' )
						<x-message :attr="[
								'classname' => 'alert alert-danger text-center', 
								'message' => 'All the items are out of stock from your order, please contact the assigned person from Maxaroma or please wait till the item.']"
							/>
						
						@elseif($IsStockCondition=="No" && $OrderRs->pay_status != 'Paid' )
						<x-message :attr="[
								'classname' => 'alert alert-danger text-center', 
								'message' => 'Sorry some of your items are out of stock, please contact the assigned person from Maxaroma or please wait till the item is back in stock.']"
							/>
						
						@endif
						@if (Session::has('success'))
							<x-message :attr="[
								'classname' => 'frmsuccess text-center', 
								'message' => Session::get('success')]"
							/>
						@endif
						@if (Session::has('error'))
							<x-message :attr="[
								'classname' => 'frmerror frmerror_shw text-center', 
								'message' => Session::get('error')]"
							/>
						@endif
					    <div class="payment-text">
					        <div class="row">
					         	<!-- <div class="col-12">
						            @if($Payment_Method_Message != "")
										{!! $Payment_Method_Message !!}
						            @else
											<h4 class="pb-3">Thank you for placing your order at MaxAroma.</h4>
										   @if($OrderRs->pay_status == 'Paid')
												<p>Your payment has been received</p>
										   @else
												<p>Please complete payment process.</p>
											@endif
										
										   <p>We hope you enjoyed shopping with us. 
												@if($OrderRs->pay_status != 'Paid')
													Your order will be processed as soon as possible. We will contact you with updates.
												@endif
											</p>
											
											@if($OrderRs->pay_status != 'Paid')
											<p>Please allow 24hrs to process the payment. An E-mail Confirmation will be sent upon payment received.</p>
											@endif
									@endif
								</div> -->
								<div class="col-12 text-center">
									<!-- <h4 class="pb-3">THANK YOU FOR PLACING YOUR ORDER AT MAXAROMA.</h4>
									<p>We hope you enjoyed shopping with us.</p> -->
									@if($OrderRs->pay_status == 'Paid' || $OrderRs->status == 'Pending By Amazon')
										@if($OrderRs->status == 'Pending By Amazon')
											<p>Your payment is being processed and your order status will be <br class="d-md-block d-none"> updated as soon as possible.</p>
										@else
											<p>Your payment has been received. Your order will be processed as soon as <br class="d-md-block d-none"> possible & a tracking number will be emailed.</p>
										@endif
								    @else
										<p>Please choose a payment method below. Your order will be processed in 1 business <br class="d-md-block d-none">day & a tracking number will be emailed.</p>
										<h4 class="pb-3">Invoice is valid for 48 Hours.</h4>
									@endif
									
								</div>
								<div class="col-12 text-center order-hsec">
									<div class="mp-odetail">
										<p class="mp-onumber">
											<span><strong>Your Order Number:</strong></span>&nbsp;{{$OrderRs->orders_no}}
										</p>
										<p class="mp-odat">
											<span><strong>Date:</strong></span>&nbsp;{{$OrderRs->datetime_order}}
										</p>
									</div>
									<p>
										@if($OrderRs->pay_status != 'Paid' && $OrderRs->status != 'Pending By Amazon')
											<strong>Amount Due:</strong>&nbsp;<span>{{Price($OrderRs->order_total,true)}}</span>
										@else
											<strong>Amount Paid:</strong>&nbsp;<span>{{Price($OrderRs->order_total,true)}}</span>&nbsp;on {{$OrderRs->paymentdate_phoneorder}}
										@endif
									</p>
								
									@if($OrderRs->pay_status == 'Paid' || $OrderRs->status == 'Pending By Amazon')
										<p class="receipt-order-number">
											<strong>Payment Method:</strong>&nbsp;{{$OrderRs->payment_method}}
										</p>
									
										@if($OrderRs->status == 'Pending By Amazon')
											<p class="receipt-order-number">
												<strong>Payment Status:</strong>&nbsp; Pending
											</p>
										@endif 
									@endif 
					        	</div>
					        	<div class="col-12 text-center">
									@if($OrderRs->pay_status != 'Paid' && $OrderRs->status != 'Pending By Amazon' && $OrderRs->order_total > 0)
						        	<div class="mp-payment">
										@if($Checkout_Available == 'Yes' && $IsStockCondition=="Yes")
						        		<ul class="mp-payment-btn">
											@if($IsStripeCheckout == 'Yes')
											<li>
												<!-- <x-button link="{{$payInvoiceUrl}}" btntext="Pay With Card" classname="btn btn-primary checkout-btn mp-btn" bid="btnpay" /> -->
												<button class="btn btn-primary checkout-btn mp-btn btn_linked" data-linkto="{{$payInvoiceUrl}}" id="btnpay">Pay With Card</button>
											</li>
											@endif
											<!--
						        			<li>
						        				<button class="btn btn-primary mp-btn pay-card">Pay With Card</button>
						        			</li>
						        			-->
						        			@if($Afterpay_Checkout == 'Yes')
						        			<li>
						        				<!-- <button class="btn btn-primary mp-btn pay-afterpay">Pay With Afterpay</button> -->
						        				<button id="afterpay-button" data-afterpay-entry-point="cart" class="btn btn-primary mp-btn pay-afterpay">Pay with Afterpay </button>
						        			</li>
						        			@endif
											@if($IsPayWithAmazonCheckout == 'Yes')
						        			<li>
						        				<button class="btn btn-primary mp-btn pay-amazon">Pay With Amazon</button>
												<span id="AmazonPayButton" style="display:none"></span>
						        			</li>
						        			@endif
						        			@if($IsPaypalExpressCheckout == 'Yes')
						        			<!-- <li>
						        				<button class="btn btn-primary mp-btn pay-paypal btn_linked" data-linkto="{{url('paypal/phoneorder')}}">Pay With PayPal</button>
						        			</li> -->
						        			@endif
						        		</ul>
						        		@endif
						        	</div>
						        	@endif

						        	<div class="mp-otable">
						        		<div id="Rptable">
					                     	<table class="text-center">
						                        <thead>
						                           	<tr>
														<td class="text-left">Item Description</td>
														<td>Gift Wrap</td>
														<td>Shipment Status</td>
														<td>Unit price</td>
														<td>Quantity</td>
														<td class="text-right">Total Price</td>
						                           	</tr>
						                        </thead>
						                        <tbody class="align-top">
													@foreach ($OrderDetails as $key=>$details)
													@if(isset($RemoveOutOfStock) && $RemoveOutOfStock=="view" && $details['stock']=='Out')
													<!-- exclude out of stock items-->
													@else
													<tr>
						                              	<td data-title="full center" class="text-left">
						                              		<div class="row row5">
						                                    	<div class="col-3 col-lg-4 col-md-5 text-center text-sm-left">
						                                    		<a href="#">
						                                    			<img src="{{ $details['product_img'] }}" height="128" width="128" alt="">
						                                    		</a>
						                                    	</div>
							                                    <div class="col-9 col-lg-8 col-md-7">
							                                    	<a href="#">
							                                    		<strong>{{ $details['brand_name'] }}</strong>
							                                    	</a><br>
							                                       {{ $details['product_title'] }} <span class="text_dgray d-block pb-2">{{ $details['short_description'] }}</span> Item SKU: {{ $details['product_sku'] }}</br>
																   @if($details['final_sale']!='')
																   	<strong>{{$details['final_sale']}}</strong>
																   @endif	
							                                       @if($details['stock']=='Out')
							                                       <div class="pb5 pt-1"><span class="fsbold error sale-menu">
																		Out Of Stock</span>
																	</div>
																	@endif
							                                       
							                                       <div class="d-md-none m-dtltable">
							                                       Gift Wrap: {{ $details['gift_wrap'] }}</br>
							                                       Shipment Status: {{ $details['ship_status'] }}</br>
							                                       Unit price: {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $details['unit_price'] }}</br>
							                                       Quantity: {{ $details['quantity'] }}</br>
							                                       <strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $details['toal_price'] }}</strong>
							                                   	   </div>
							                                   	</div>
						                                 	</div>
					                                 	</td>
														<td class="none-mobile" data-title="Gift Wrap">{{ $details['gift_wrap'] }}</td>
														<td class="none-mobile" data-title="Shipment Status">{{ $details['ship_status'] }}</td>
														<td class="none-mobile" data-title="Unit price">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $details['unit_price'] }}</td>
														<td class="none-mobile" data-title="Quantity">{{ $details['quantity'] }}</td>
														<td class="none-mobile text-right" data-title="Total Price">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $details['toal_price'] }}</td>
													</tr>
													@endif
													@endforeach
						                        </tbody>
					                     	</table>
					                  	</div>
					                  	<div class="deliv_dtl">
											<div class="row row5">
												<div class="col-md-5 offset-md-7 col-lg-4 offset-lg-8">
													<table class="st_table">
														<tbody>
															<tr>
																<td width="55%" class="td-wf">Subtotal:</td>
																<td class="text-right td-wfr"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->sub_total }}</strong></td>
															</tr>
															<tr>
																<td>Shipping Charge:</td>
																<td class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->shipping_amt }}</strong></td>
															</tr>
															@if($OrderRs->route_shipping_insurance_charge > 0)
															<tr>
																<td>Shipping Insurance Charge:</td>
																<td class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->route_shipping_insurance_charge }}</strong></td>
															</tr>
															@endif
															<tr>
																<td>Sales Tax: </td>
																<td class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->tax }}</strong></td>
															</tr>
															@if($OrderRs->shipping_signature > 0)
															<tr>
																<td>Shipping Signature</td>
																<td class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->shipping_signature }}</strong></td>
															</tr>
															@endif
															
															@if($OrderRs->gift_charge > 0)
															<tr>
																<td>Gift Wrapping Charge</td>
																<td class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->gift_charge }}</strong></td>
															</tr>
															@endif
															
															@if($OrderRs->auto_discount > 0)
															<tr>
																<td>Auto Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->auto_discount }}</strong></td>
															</tr>
															@endif
															
															@if($OrderRs->quantity_discount > 0)
															<tr>
																<td>Quantity Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->quantity_discount }}</strong></td>
															</tr>
															@endif
															
															@if($OrderRs->reward_discount > 0)
															<tr>
																<td>Reward Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->reward_discount }}</strong></td>
															</tr>
															@endif
															@if($OrderRs->coupon_amount > 0)
															<tr>
																<td>Coupon Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->coupon_amount }}</strong></td>
															</tr>
															@endif
															@if($OrderRs->gc_amount > 0)
															<tr>
																<td>Gift Certificate Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->gc_amount }}</strong></td>
															</tr>
															@endif
															@if($OrderRs->bogo_discount > 0)
															<tr>
																<td>BOGO Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->bogo_discount }}</strong></td>
															</tr>
															@endif
															@if($OrderRs->apply_credit > 0)
															<tr>
																<td>Credit Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->apply_credit }}</strong></td>
															</tr>
															@endif
															@if($OrderRs->refer_amount > 0)
															<tr>
																<td>Refer Discount:</td>
																<td class="text-right"><strong>-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->refer_amount }}</strong></td>
															</tr>
															@endif
															<tr class="none-mobile">
																<th>Order Total</th>
																<th class="text-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->order_total }}</strong></th>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
						        	</div>
						        </div>
						        <div class="col-12 pb-md-3 text-center">
									<div class="btn-viewd">
						        		<div class="btn-ordert d-md-none">
						        			Order Total <strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->order_total }}</strong>
						        		</div>
					        			@if($IsStockCondition == "No" && $OrderRs->pay_status != 'Paid' && $OrderRs->status != 'Pending By Amazon' && $OrderRs->order_total > 0 && $WebsiteAvailStock=="Yes") 
					        			<a href="javascript:void(0);" class="btn btn-primary mt-md-4 mt-3 paymentset"id="btinv" onclick="ProceedPay()">Proceed to payment</a>
					        			@endif
					        			<x-button link="{{$viewInvoiceUrl}}" btntext="View / Download Invoice" classname="btn btn-primary mt-md-4 mt-3" bid="btinv" />
					        			
					        		</div>
								</div>

					      	</div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	
<script>
	var SITE_URL = "{{config('global.SITE_URL')}}";
	var PaymentURL = "{{$PaymentURL}}";
	function ProceedPay()
	{
		ans =confirm("Sorry some of your items are out of stock.\n\nPlease contact the assigned person from Maxaroma or Please wait till the item is back in stock.\n\nYou can also proceed with In-Stock items only. Are you sure you want to proceed with the partial order?");
		if(ans)
		{
			document.location = PaymentURL
		}
		
	}
</script>
@endsection
