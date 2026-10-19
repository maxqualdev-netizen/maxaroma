@extends('layouts.app')
@section('content')
<div class="showDwnMessage" style="display:none">Please wait while we process your order… Kindly do not refresh the page or close the tab.</div>
<main role="main" id="main-content" tabindex="-1" aria-label="Order Receipt Main Content">
    <section class="checkout-page order-confirm" role="region" aria-label="Order Confirmation Section">
        <div class="container">
            <div class="checkout-middle-sec">
                <div class="checkout-left-part">
                    <div class="checkout-cont-lg">
                        @if(isset($roktstr) && $roktstr != '')
                            <div id="rokt-placeholder" aria-hidden="true">
                                <iframe aria-hidden="true" src="https://apps.rokt.com/wsdk/preload/index.html" sandbox="allow-scripts allow-same-origin" style="border: 0px; width: 100%; display: none;"></iframe>
                            </div>
                        @endif
                        <div class="row pr-lg-4 pr-0">
                            <div class="col-12">
                                <div class="confm-top text-center" role="region" aria-label="Order Confirmation Message">
                                    <h3 class="cart-hd-sub text-center pb-3"><strong>Hooray, your order is placed! Order Receipt!</strong></h3>
                                    <p>Your order number is <strong class="color-red">{{$MainOrder->orders_no}}</strong></p>
                                   @if(isset($MainOrder->order_type) && $MainOrder->order_type== 'Store')
									<p>We hope you enjoyed shopping with usEnjoy your new fragrance!</p>
                                   @else
                                   
                                    @if($Payment_Method_Message != '')
                                    {!! $Payment_Method_Message !!}
                                        @if($wholesale_terms!="")
                                            {!! $wholesale_terms !!}
                                        @endif
                                    @else
                                        <p>A confirmation email is headed your way at {{$MainOrder->bill_email}}.</p>
                                        @if($wholesale_terms!="")
                                            {!! $wholesale_terms !!}
                                        @endif
                                        </p>
                                    @endif
                                    @endif
                                </div>
                                <div class="confm-bottom w-100 text-center" role="region" aria-label="Continue Shopping Section">
                                    <a href="#" class="mt-3 d-block" aria-label="Order Receipt Banner">
                                        <!--<img src="{{config('global.SITE_IMAGES')}}cofim-banner.png" alt="Order Receipt Banner" class="img-fluid">-->
                                        <img src="{{$order_receipt_image}}" alt="Order Receipt Banner" class="img-fluid">
                                    </a>
                                    <div class="chekout-main-btn mt-3">
                                        <div class="row">
                                            <div class="col-12 col-md-8 offset-md-2">
                                                <a class="btn btn-primary d-block checkout-btn" href="{{url('/')}}" role="button" aria-label="Continue Shopping">Continue shopping</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="row">
                        <div class="col-12" id="messages" role="status" aria-live="polite" aria-atomic="true"></div>

									@if(Session::has('etype') && Session::get('etype') == 'G')
									<div class="col-12 col-md-8 offset-md-2" id="maingmdiv">
										<div class="oneclick-account">
											<a href="javascript:void(0);" id="guestmember" class="btn btn-secondary d-block" aria-label="Sign up & Get 150 Points"><strong>Sign up &amp; Get 150 Points</strong></a>
											<!-- <a href="javascript:void(0);" id="guestmember"><strong>Create an account with your order details</strong></a> -->
											<form id="frmguestmember" method="post" role="form" aria-label="Guest to Member Conversion Form">
												@csrf()
												<div id="guest-to-member" class="guestregister" style="display: none;">
													<div class="form-group input-fcs">
														<label for="guest_password" class="col-form-label">Password<span class="errmsg">*</span></label>
														<input id="guest_password" name="guest_password" type="password" class="form-control" aria-required="true" aria-label="Password">
														<div class="frmerror" role="alert" id="error_guest_password" style=""></div>
													</div>
													<div class="form-group input-fcs">
														<label for="guest_confirm_password" class="col-form-label">Confirm Password<span class="errmsg">*</span></label>
														<input id="guest_confirm_password" name="guest_confirm_password" type="password" class="form-control" aria-required="true" aria-label="Confirm Password">
														<div class="frmerror" role="alert" id="error_guest_confirm_password" style=""></div>
													</div>
													<label class="checkbox-label mt-2">
                                                        <div class="chebox">
                                                            <input type="checkbox" id="termsprivacy" name="termsprivacy" checked="" aria-label="Terms and Privacy"><span class="checkmark"></span>
                                                        </div>
                                                        By creating an account, you agree to receive MAXAROMA's marketing emails to get first dibs on new arrivals, sales, exclusive content, and more! You may unsubscribe at any time.<br> By creating an account, you agree to MAXAROMA's <a href="http://27.109.8.106:8253/83/maxaroma/terms-and-conditions.html" target="_blank">Terms</a> of use and <a target="_blank" href="http://27.109.8.106:8253/83/maxaroma/privacy-policy.html">Privacy Policy</a>.<span class="errmsg">*</span>
                                                        <div class="frmerror" role="alert" id="error_termsprivacy" style=""></div>
                                                    </label>
													<button class="btn btn-primary d-block btn-create-account" aria-label="Create Account">Create Account</button>
												</div>
											</form>
										</div>

										<div class="eheight creating_text">
											<h3>Create an account</h3>
											<ul class="dots" role="list" aria-label="Benefits of creating an account">
												<li role="listitem">Giveaway Alerts</li>
												<li role="listitem">Easily redeem rewards</li>
												<li role="listitem">Brand Drops</li>
												<li role="listitem">Presale and exclusive access</li>
												<li role="listitem">Easily Presale track orders and view order history</li>
											</ul>
										</div>
									</div>
									@endif
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkout-right" role="region" aria-label="Order Summary and Details">
                    <div class="your-bag" role="region" aria-label="Order Receipt Items">
                        <div class="actmenuhd">
                            <h4 id="order-receipt-heading">
                                Order Receipt
                                <svg id="down-arrow" class="sv-down-arrow vam ml-2" aria-hidden="true" role="img" width="14" height="12">
                                    <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
                                </svg>
                            </h4>
                            <span class="float-right text-right">
                                <div class="pb-2">
                                    <strong class="order-date" aria-label="Order date">Date: {{date('m/d/Y')}}</strong>
                                    <a href="{{url('/order-detail-pdf/'.$MainOrder->orders_id.'.html')}}" class="print-btn ml-2" role="button" aria-label="Print Order Receipt">
                                        <svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
                                            <use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
                                        </svg> Print
                                    </a>
                                </div>
                            </span>
                        </div>
                        <div class="actmenu-inner your-bag-inner mt-3" id="cart_items" role="region" aria-labelledby="order-receipt-heading" aria-label="Ordered Items List">
                            <ul role="list" aria-label="Ordered items">
                                @foreach($OrderDetails as $key => $Order)
                                <li role="listitem">
                                    <div class="row">
                                        <div class="col-4 col-sm-3">
                                            <img alt="Product Image" src="{{ config('global.SPEED_SIZE_URL')}}{{$Order->Image}}" class="product-img img-fluid">
                                        </div>
                                        <div class="col-8 col-sm-6">
                                            <div class="cart-product pb-1">
                                                <a href="{{$Order->ProdLink}}" aria-label="View product: {!! strip_tags($Order->product_name) !!}">{!!$Order->product_name!!} </a>
                                            </div>
                                            <div class="pb-1 SKU">
                                                <span>Item SKU: {{$Order->sku}}</span>
                                            </div>
                                            <div class="pb-1 qty">
                                                <span>Quantity: {{$Order->quantity}}</span>
                                            </div>
                                            <div class="cart-price d-block d-sm-none">
                                                <span>{{Price($Order->total)}}</span>
                                            </div>
                                            @if(isset($Order->excluded_flag) && $Order->excluded_flag!='' )
                                            <div class="pb-1 qty">
                                                <span>{{$Order->excluded_flag}}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-3 text-right d-none d-sm-block">
                                            <div class="cart-price">
                                                <span>{{Price($Order->total)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="order-summry mt-4" role="region" aria-label="Order Summary Table">
                        <div class="row">
                            <div class="col-12">
                                <table class="table text-left table-border-none table-borderless mb-0" role="table" aria-label="Order Summary">
                                    <caption class="sr-only">Order Summary</caption>
                                    <tbody>
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-right"><strong>{{Price($MainOrder->sub_total)}}</strong></td>
                                        </tr>
                                        @foreach($AllCharges as $ckey => $Charge)
                                            @if($MainOrder->{$Charge['field']} > 0 )
                                            <tr>
                                                <td>{{$Charge['label']}}:</td>
                                                <td class="text-right"><strong>{{Price($MainOrder->{$Charge['field']})}}</strong></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @foreach($AllDiscounts as $dkey => $Discount)
                                            @if($MainOrder->{$Discount['field']} > 0 )
                                            <tr>
                                                <td>{{$Discount['label']}}:</td>
                                                <td class="text-right"><strong>-{{Price($MainOrder->{$Discount['field']})}}</strong></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        <tr class="cart-total">
                                            <td id="order-total-label">Order Total</td>
                                            <td class="text-right"><strong aria-labelledby="order-total-label">{{Price($MainOrder->order_total)}}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="small-address d-none d-md-inline-block w-100" role="region" aria-label="Order Addresses and Payment">
                        <ul role="list" aria-label="Addresses and payment details">
                            @if($MainOrder->is_only_gc == 0 && isset($MainOrder->order_type) && $MainOrder->order_type!= 'Store' && empty($SkipAddressPart))
                            <li role="listitem">
                                <h4 class="mb-1 mt-3" id="shipping-address-heading">Shipping to:</h4>
                                <p class="mb-0" aria-labelledby="shipping-address-heading">
                                    {{$MainOrder->ship_first_name}} {{$MainOrder->ship_last_name}},<br>
                                    @if($MainOrder->ship_company != '')
                                        Company: {{$MainOrder->ship_company}}<br>
                                    @endif
                                    {{$MainOrder->ship_address1}}
                                    @if($MainOrder->ship_address2 != '')
                                        , {{$MainOrder->ship_address2}}
                                    @endif
                                    , {{$MainOrder->ship_city}}<br>
                                    {{$MainOrder->ship_state}} - {{$MainOrder->ship_zip}}, {{$MainOrder->ship_country}}, {{$MainOrder->ship_phone}}<br>
                                    {{$MainOrder->ship_email}}
                                </p>
                            </li>
                            @endif
                            @if(empty($SkipAddressPart))
                            <li role="listitem">
                                <h4 class="mb-1 mt-3" id="billing-address-heading">Billing to:</h4>
                                <p class="mb-0" aria-labelledby="billing-address-heading">
                                    {{$MainOrder->bill_first_name}} {{$MainOrder->bill_last_name}},<br>
                                    @if($MainOrder->bill_company != '')
                                        Company: {{$MainOrder->bill_company}}<br>
                                    @endif
                                    {{$MainOrder->bill_address1}}
                                    @if($MainOrder->bill_address2 != '')
                                        , {{$MainOrder->bill_address2}}
                                    @endif
                                    , {{$MainOrder->bill_city}}<br>
                                    {{$MainOrder->bill_state}} - {{$MainOrder->bill_zip}}, {{$MainOrder->bill_country}}, {{$MainOrder->bill_phone}}<br>
                                    {{$MainOrder->bill_email}}
                                </p>
                            </li>
                            @endif
                            <li>
                                <h4 class="mb-1 mt-3" id="payment-method-heading">Payment Method:</h4>
                                <p class="mb-0" aria-labelledby="payment-method-heading">{{$MainOrder->payment_method}}</p>
                            </li>
                            @if(($MainOrder->is_only_gc == 0 || $MainOrder->payment_type == 'PAYMENT_PAYPALPROD') && isset($MainOrder->order_type) && $MainOrder->order_type!= 'Store')
                                <li role="listitem">
                                    <h4 class="mb-1 mt-3" id="shipping-method-heading">Shipping Method:</h4>
                                    <p class="mb-0" aria-labelledby="shipping-method-heading">{!!$MainOrder->fullshipping_info!!}</p>
                                </li>
                            @endif
                            @if($MainOrder->gift_from!="" || $MainOrder->gift_to!="" || $MainOrder->gift_message_customer!="" || $MainOrder->free_gift!="")
                                @if($MainOrder->gift_from!="")
                                    <p class="mb-0"><strong>From:</strong> {{$MainOrder->gift_from}}</p>
                                @endif
                                @if($MainOrder->gift_to!="")
                                    <p class="mb-0"><strong>To:</strong> {{$MainOrder->gift_to}}</p>
                                @endif
                                @if($MainOrder->gift_message_customer!="")
                                    <p class="mb-0"><strong>Customer Message:</strong> {{$MainOrder->gift_message_customer}}</p>
                                @endif
                                @if($MainOrder->free_gift!="")
                                    <p class="mb-0"><strong>{{$MainOrder->free_gift}}</strong></p>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@if(Auth::guard('store')->check() && $hasCashPayment == '1')

<!--<input type="hidden" id="qd_pos_load_source" value="">
<input type="hidden" id="qd_cash_drawer_receive_event" value="1">-->

<div id="pos-cashdrawer-popup" class="modal fade" role="dialog">
  <div class="vertical-alignment-helper">
	<div class="modal-dialog modal-sm vertical-align-center">
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-body">
		  <div class="home-popup-width">
			<div class="home-popup-content"> <a href="javascript:void(0);" type="button" class="close" data-dismiss="modal" role="button" aria-label="close popup">
			  <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
				<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
			  </svg>
			  </a>
			  <div class="text-center">
				 <form method="post" name="frmCashDrawerChange" id="frmCashDrawerChange" onsubmit="return false;">
				 <input type="hidden" name="act_type" id="act_type" value="add">
				 <input type="hidden" name="pos_store_id" value="{{ Auth::guard('store')->user()->store_id }}">
				 <input type="hidden" name="pos_order_id" value="{{ $MainOrder->orders_no }}">
				 <input type="hidden" name="pos_payable_cash_amount" value="{{$orderCashAmount}}">
				 <input type="hidden" name="pos_order_cashchange_log_id" value="">
				 <input type="hidden" name="pos_change_amount" value="">

				 <div class="row">
					<div class="col-12">
						<h2 class="popc-hd">Cash Change Calculator</h2>
						<p class="mb-3 alert alert-success p-2" id="success_msg" style="display:none;"></p>
						<p class="mb-3 alert alert-danger p-2" id="error_msg" style="display:none;"></p>
							<!-- <table class="text-center" role="presentation" width="100%" border="1" cellspacing="0" cellpadding="0">
									<tr class="text-center" height="50">
										<td class="text-left pl-2 pr-2"><strong>Order Number:</strong></td>
										<td class="">{{ $MainOrder->orders_no }}(Store)</td>
									</tr>
									<tr class="text-center" height="50">
										<td class="text-left pl-2 pr-2"><strong>Order Total:</strong></td>
										<td class="">{{Price($MainOrder->order_total)}}</td>
									</tr>
									<tr class="text-center" height="50">
										<td class="text-left pl-2 pr-2"><strong>Payable Cash Amount:</strong></td>
										<td class="" id="pos_payable_cash_amount" data-amount-value="{{$orderCashAmount}}">{{Price($orderCashAmount)}}</td>
									</tr>
									<tr class="text-center" height="50">
										<td class="text-left pl-2 pr-2"><strong>Received Amount:</strong></td>
										<td class=""><input name="pos_received_amount" id="pos_received_amount" type="text" class="form-control text-center" value="" pattern="^-?[0-9]*[.,]?[0-9]+$"></td>
									</tr>
									<tr class="text-center" height="50">
										<td class="text-left pl-2 pr-2"><strong>Change Amount:</strong></td>
										<td class="hd" id="pos_change_amount">$0.00</td>
									</tr>
								</table> -->
						<div class="cash-clc">
						  <div class="top-boxes">
						    <div class="info-box">
						      <div class="label" id="orderNumberLabel">Order Number</div>
						      <p class="value mb-0" aria-labelledby="orderNumberLabel">{{ $MainOrder->orders_no }}(Store)</p>
						    </div>

						    <div class="info-box info-bright">
						      <div class="label" id="orderTotalLabel">Order Total</div>
						      <p class="value mb-0" aria-labelledby="orderTotalLabel">{{Price($MainOrder->order_total)}}</p>
						      <svg class="sv-cartnw vam" aria-hidden="true" role="img" width="22" height="22">
										<use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
									</svg>
						    </div>
						  </div>

						  <div class="cash-row-text bor-bd">
						    <span class="row-label" id="payableLabel">Payable Cash Amount</span>
						    <span class="row-value" id="pos_payable_cash_amount" data-amount-value="{{$orderCashAmount}}" aria-labelledby="payableLabel" aria-live="polite">{{Price($orderCashAmount)}}</span>
						  </div>

						  <div class="cash-row-text">
						    <label for="pos_received_amount" class="row-label text-black">
						      Received Amount
						    </label>

						    <div class="input-box">
						      <input name="pos_received_amount" id="pos_received_amount"  aria-describedby="receivedAmountHelp" type="text" class="form-control" value="" pattern="^-?[0-9]*[.,]?[0-9]+$">
						    </div>
						  </div>

						  <div class="change-box">
						    <span id="changeLabel">Change Amount</span>
						    <span class="change-value"  aria-labelledby="changeLabel"  aria-live="polite" id="pos_change_amount">$0.00</span>
						  </div>

						</div>
					</div>
					<div class="col-12 pb-3">
					  <div class="form-actions clearfix">
						<div class="align-items-center text-center mt-3">
							<button class="btn btn-primary" id="submitCashChangeBtn" onclick="javascript:cashDrawerChangeSubmit();"  aria-label="Submit cash change details">Submit</button>
						</div>
					  </div>
					</div>
				 </div>
				 </form>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
@endif

<span id="cashdrawer_status" class="posstatus-span d-none" style="color:red;"></span>

<script>
var hasCashPayment		= "{{ $hasCashPayment }}";

if(hasCashPayment == '1'){
	window.onload = function () {
		let userAgent = navigator.userAgent;
		if (userAgent.includes('StarWebPRNTBrowser')) {
			alert("Star WebPRNT Browser detected");
			storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Order Receipt Page','No');

			setTimeout(onConnect(),3000);
			setTimeout(onPrint(),2000);
		} else {
			alert("Star WebPRNT Browser NOT detected");
			storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Order Receipt Page','No');
		}
	}
}

/*function setPOSLoadSource(source = '')
{
	document.getElementById("qd_pos_load_source").value = source;

	if(document.getElementById("qd_pos_load_source").value == 'app' && typeof hasCashPayment != 'undefined' && hasCashPayment == '1')
	{
		window.location.href = SITE_URL + 'store/open-cash-drawer-command';
	}

}

function cashDrawerReceiveEvent(event = '')
{
	if(event == 'open')
	{
		cashDrawerOpen = 1;
		cashDrawerLog(actionVal,'Open',actionNotes,cd_amount, 1);
	}
	else if(event == 'close')
	{
		cashDrawerOpen = 0;
		cashDrawerLog(actionVal,'Close',actionNotes,cd_amount, 1);
	}
}*/

function cashDrawerLog(action,action_type,actionNotes,cd_amount,isDeviceEvent=0) {
	var STR_POST_VAR = STR_POST_VAR + '&_token='+$('meta[name="csrf-token"]').attr('content');
		STR_POST_VAR = STR_POST_VAR + '&action='+action+'&action_type='+action_type+'&actionNotes='+actionNotes+'&cd_amount='+cd_amount+'&isDeviceEvent='+isDeviceEvent;
		//alert("onCashDrawerLog:"+STR_POST_VAR);
		//document.getElementById('textarea_response').value += STR_POST_VAR + '\n';
		//document.getElementById('textarea_response').value += SITE_URL+'store/cash-drawer/log' + '\n';
	$.ajax({
			type: 'POST',
			url: site_url+'store/cash-drawer/log',
			dataType: "json",
			'async': false,
			data: STR_POST_VAR,
			beforeSend: function()
			{
				//alert("onCashDrawerBeforeSend");
			},
			success: (function(data, status)
			{

				//alert("onCashDrawerBeforeSuccess");
			}),
			complete :(function()
			{
				//alert("onCashDrawerBeforeComplete");
			})
		});
}

</script>

@endsection
