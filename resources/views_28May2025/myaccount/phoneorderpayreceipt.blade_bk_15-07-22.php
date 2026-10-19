@extends('layouts.app')
@section('content')

<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-12">
				   <div class="myact_hd">
				   		<h2>Make Payment</h2>
				   </div>
				   <div class="make-payment">
						@if (Session::has('success'))
						<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
						@if (Session::has('error'))
						<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('error')]"
							/>
						@endif
						
				      <div class="payment-text">
				         <div class="row">
				         	<div class="col-12">
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
							</div>
							<div class="col-12">
								<div class="border-top pt-4 mt-3"></div>
								<p>
									<strong>Your Order Number</strong> : <span class="red"> {{$OrderRs->orders_no}}</span>
								</p>
								<p><strong>Date</strong> : {{$OrderRs->datetime_order}}</p>
								<p>
									@if($OrderRs->pay_status != 'Paid' && $OrderRs->status != 'Pending By Amazon')
										<strong>Amount Due : </strong>
										<b>&nbsp;{{Price($OrderRs->order_total,true)}}</b>
									@else
										<strong>Amount Paid : </strong>
										<b>&nbsp;{{Price($OrderRs->order_total,true)}}</b>
										&nbsp;on {{$OrderRs->phoneorder_paymentdate}}
									@endif
								</p>
							
						
							@if($OrderRs->pay_status == 'Paid' || $OrderRs->status == 'Pending By Amazon')
								<p class="receipt-order-number">
									<strong>Payment Method : </strong>
									&nbsp;{{$OrderRs->payment_method}}
								</p>
							
								@if($OrderRs->status == 'Pending By Amazon')
										<p class="receipt-order-number">
											<strong>Payment Status : </strong>
											&nbsp; Pending
										</p>
									@endif 
								@endif 
				        	</div>
				            <div class="col-12 pt-2">
								@if($OrderRs->pay_status != 'Paid' && $OrderRs->status != 'Pending By Amazon')
									<x-button link="{{$viewInvoiceUrl}}" btntext="Download Invoice" classname="btn btn-primary mb-4" bid="btinv" />
									<div class="order-paymethod border">
										<div class="order-paymethod-hd">Choose Payment Method</div>
										<div class="payment-method-inner p-3">
										@if($Checkout_Available == 'Yes')
										<ul>
											@if($IsStripeCheckout == 'Yes')
											<li>
												<label for="stripe" class="comcheck radio d-inline-block checkbox-label">
													<div class="chebox">
	                                                    <input type="radio" name="pay_method" id="stripe" value="stripe" checked>
	                                                    <span class="checkmark"></span>
	                                                    Pay With Card
	                                                </div>
                                            	</label>
											</li>
											@endif
											@if($IsPaypalExpressCheckout == 'Yes')
											<li>
												<label for="paypal" class="comcheck radio d-inline-block checkbox-label">
													<div class="chebox">
	                                                    <input type="radio" name="pay_method" id="paypal" value="paypal">
	                                                    <span class="checkmark"></span>
	                                                    Pay With Paypal
	                                                </div>
                                            	</label>

											</li>
											@endif
											@if($IsPayWithAmazonCheckout == 'Yes')
											<li>
												<label for="amazon" class="comcheck radio d-inline-block checkbox-label">
													<div class="chebox">
	                                                    <input type="radio" name="pay_method" id="amazon" value="amazon">
	                                                    <span class="checkmark"></span>
	                                                    Pay With Amazon
	                                                </div>
                                            	</label>

											</li>
											@endif
											@if($Afterpay_Checkout == 'Yes')
											<li>
												<label for="afterpay" class="comcheck radio d-inline-block checkbox-label">
													<div class="chebox">
	                                                    <input type="radio" name="pay_method" id="afterpay" value="afterpay">
	                                                    <span class="checkmark"></span>
	                                                    Pay With Afterpay
	                                                </div>
                                            	</label>
											</li>
											@endif
										</ul>
										@else
											<ul><li><label>
												Sorry, no payment methods available. Please contact administrator.
											</label></li></ul>
										@endif
									@if($IsStripeCheckout == 'Yes')
									<div class="pt10 stripe">
										<x-button link="{{$payInvoiceUrl}}" btntext="Proceed To Payment" classname="btn btn-primary checkout-btn" bid="btnpay" />
									</div>
									@endif
									
									<!-- Paypal checkout -->
									@if($IsPaypalExpressCheckout == 'Yes')
										<!-- 	<div class="pt10 hddiv paypal">
											<- a href="javascript:void(0)"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"></a ->
												
											<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
													 <input type="hidden" name="cmd" value="_xclick">
													 <input type="hidden" name="business" value="info@peraroma.com">
													 <input type="hidden" name="lc" value="BM">
													 <input type="hidden" name="item_name" value="DropShipper Deposit">
													 <input type="hidden" name="item_number" value="DDDeposit">
													 <input type="hidden" name="amount" value="{{$OrderRs->order_total}}">
													 <input type="hidden" name="currency_code" value="USD">
													 <input type="hidden" name="button_subtype" value="services">
													 <input type="hidden" name="no_note" value="0">
													 <input type="hidden" name="tax_rate" value="0.000">
													 <input type="hidden" name="shipping" value="0.00">
													 <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
													 <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
													 <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
													 <input name="notify_url" value="{{url('/paypal_fund_process').'/'.Session::get('sess_icustomerid')}}" type="hidden">
													 <input name="return" id ="paypal_return" value="{{url('/paypal_fund_response')}}" type="hidden">
											</form>
										</div>
										-->
									
									<div class="pt10 tac fsbold hddiv paypal">
										<div id="paypal-button-container"> 
											<a class="paypal-btn" href="{{url('paypal/phoneorder')}}"> 
												<img alt="Paypal" src="{{config('global.SITE_IMAGES')}}paypal.png" width="296" height="77" class="img-fluid"> 
											</a> 
											<a class="paypal-btn paypal-credit mt-3 mt-md-0" href="{{url('paypal/phoneorder')}}"> 
												<img alt="Paypal" src="{{config('global.SITE_IMAGES')}}paypal-credit.png" width="296" height="77" class="img-fluid"> 
											</a> 
										</div>
										<!-- script type="text/javascript">
											var CREATE_PAYMENT_URL  = "{{config('global.SITE_URL')}}paypal/phoneorder";
											var PaypalActionMode	= '{{$PaypalActionMode}}';
											var OrderID	= '{{$OrderID}}';
											var formdata = {"orderid":OrderID};
											
										</script -->
									</div>
									@endif
									<!-- Paypal checkout -->
									
									<!-- Amazon checkout Start-->
									@if($IsPayWithAmazonCheckout == 'Yes')
										<div class="pt10 fsbold hddiv amazon">
											<span id="AmazonPayButton"></span>
										</div>
									@endif
									<!--Amazon checkout End-->
									
									<!-- Afterpay checkout Start-->
									@if($Afterpay_Checkout == 'Yes')
										<div id="pricediv" style="display:none;" class="pt10 fsbold hddiv afterpay">
											{{Price($OrderRs->order_total,true)}}
										</div>
										<script>
											var currency_code = "{{Session::get('currency_code')}}";
											
											var Min_AP_AMT = "{{$Min_AP_AMT}}";
											Min_AP_AMT =  parseInt(Number(Min_AP_AMT.replace(/[^0-9.-]+/g,"")));
											
											var Max_AP_AMT = "{{$Max_AP_AMT}}";
											Max_AP_AMT =  parseInt(Number(Max_AP_AMT.replace(/[^0-9.-]+/g,"")));
										</script>
									@endif
									<!-- Afterpay checkout Start-->
									</div>
								</div>
									
								@else
									@if($OrderRs->status == 'Pending By Amazon')
										<p>
											Your payment is being processed and your order status will be updated as soon as possible.
										</p>
									@endif
									<x-button link="{{$viewInvoiceUrl}}" btntext="Download Invoice" classname="btn btn-primary" bid="btinv" />
								@endif
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
</script>
@endsection
