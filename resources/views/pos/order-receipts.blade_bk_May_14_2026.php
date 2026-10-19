@extends('layouts.app')
@section('content')
<style type="text/css">
	/*body #header-sticky{padding: 0!important; border: none;}*/
</style>
<div class="showDwnMessage" style="display:none">Please wait while we process your order… Kindly do not refresh the page or close the tab.</div>
<main id="checkout-page">
	<section class="checkout-page order-confirm">
		<div class="container">
			<div class="checkout-middle-sec">
				<div class="checkout-left-part">
					<div class="checkout-cont-lg">
						@if(isset($roktstr) && $roktstr != '')
							<div id="rokt-placeholder">
								<iframe aria-hidden="true" src="https://apps.rokt.com/wsdk/preload/index.html" sandbox="allow-scripts allow-same-origin" style="border: 0px; width: 100%; display: none;"></iframe>
							</div>
						@endif

						<div class="row pr-lg-4 pr-0">
							<div class="col-12">
								<div class="confm-top text-center">
									<h3 class="cart-hd-sub text-center pb-3"><strong>Hooray, your order is placed! Order Receipt!</strong></h3>
									<p>Your order number is <strong class="color-red">{{$WebsiteMainOrder->orders_no}}</strong><span class="orderType">(Online)</span></p>
									@if($Payment_Method_Message != '')
									{!! $Payment_Method_Message !!}
										@if($wholesale_terms!="")
											{!! $wholesale_terms !!}
										@endif
									@else
										<p>A confirmation email is headed your way at {{$WebsiteMainOrder->bill_email}}.</p>
										@if($wholesale_terms!="")
											{!! $wholesale_terms !!}
										@endif
										</p>
									@endif
								</div>
								<div class="confm-bottom w-100 text-center">
									<a href="#" class="mt-3 d-block">
										<!--<img src="{{config('global.SITE_IMAGES')}}cofim-banner.png" alt="" class="img-fluid">-->
										<img src="{{$order_receipt_image}}" alt="" class="img-fluid">
									</a>
									<div class="chekout-main-btn mt-3">
										<div class="row">
											<div class="col-12">
												<a class="btn btn-primary d-block checkout-btn" href="{{url('/store/store-dashboard.html')}}">Back To Store</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout-right">
						<div class="your-bag">
							<div class="actmenuhd">
								<h4>
									Order Receipt
									<svg id="down-arrow" class="sv-down-arrow vam ml-2" aria-hidden="true" role="img" width="14" height="12">
										<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
									</svg>
								</h4>
								<span class="float-right text-right">
									<div class="pb-2">
										<strong class="order-date">Date: {{date('m/d/Y')}}</strong>
										<a href="{{url('/order-detail-pdf/'.$WebsiteMainOrder->orders_id.'.html')}}" class="print-btn ml-2">
										<svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
											<use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
										</svg> Print
									</a>
									</div>
								</span>
							</div>
							<div class="actmenu-inner your-bag-inner mt-3" id="cart_items">
								<ul>
									@foreach($WebsiteOrderDetails as $key => $Order)
									<li>
										<div class="summary-row">
											<div class="summary-img">
												<img alt="" src="{{$Order->Image}}" class="product-img img-fluid">
											</div>
											<div class="cart-product pb-1">
												<div class="cart-product pb-1">
													<a href="{{$Order->ProdLink}}">{!!$Order->product_name!!} </a>
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
												<div class="itemType">Online Item</div>
											</div>
											<div class="text-right d-none d-sm-block">
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

						<div class="order-summry mt-4">
							<div class="row">
								<div class="col-12">
									<table class="table text-left table-border-none table-borderless mb-0">
										<tbody>
											<tr>
												<td>Subtotal:</td>
												<td class="text-right"><strong>{{Price($WebsiteMainOrder->sub_total)}}</strong></td>
											</tr>
											@foreach($WebsiteAllCharges as $ckey => $Charge)
												@if($WebsiteMainOrder->{$Charge['field']} > 0 )
												<tr>
													<td>{{$Charge['label']}}:</td>
													<td class="text-right"><strong>{{Price($WebsiteMainOrder->{$Charge['field']})}}</strong></td>
												</tr>
												@endif
											@endforeach
											@foreach($WebsiteAllDiscounts as $dkey => $Discount)
												@if($WebsiteMainOrder->{$Discount['field']} > 0 )
												<tr>
													<td>{{$Discount['label']}}:</td>
													<td class="text-right"><strong>-{{Price($WebsiteMainOrder->{$Discount['field']})}}</strong></td>
												</tr>
												@endif
											@endforeach
											<tr class="cart-total">
												<td>Order Total</td>
												<td class="text-right">{{Price($WebsiteMainOrder->order_total)}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
									<!-- d-none d-md-inline-block -->
						<div class="small-address w-100">
							<ul>
								@if($StoreMainOrder->is_only_gc == 0)
								<li>
									<h4 class="mb-1 mt-3">Shipping to:</h4>
									<p class="mb-0">
										{{$WebsiteMainOrder->ship_first_name}} {{$WebsiteMainOrder->ship_last_name}},<br>
										@if($WebsiteMainOrder->ship_company != '')
											Company: {{$WebsiteMainOrder->ship_company}}<br>
										@endif
										{{$WebsiteMainOrder->ship_address1}}
										@if($WebsiteMainOrder->ship_address2 != '')
											, {{$WebsiteMainOrder->ship_address2}}
										@endif
										, {{$WebsiteMainOrder->ship_city}}<br>
										{{$WebsiteMainOrder->ship_state}} - {{$WebsiteMainOrder->ship_zip}}, {{$WebsiteMainOrder->ship_country}}, {{$WebsiteMainOrder->ship_phone}}<br>
										{{$WebsiteMainOrder->ship_email}}
									</p>
								</li>
								@endif
								<li>
									<h4 class="mb-1 mt-3">Billing to:</h4>
									<p class="mb-0">
										{{$WebsiteMainOrder->bill_first_name}} {{$WebsiteMainOrder->bill_last_name}},<br>
										@if($WebsiteMainOrder->bill_company != '')
											Company: {{$StoreMainOrder->bill_company}}<br>
										@endif
										{{$WebsiteMainOrder->bill_address1}}
										@if($WebsiteMainOrder->bill_address2 != '')
											, {{$WebsiteMainOrder->bill_address2}}
										@endif
										, {{$WebsiteMainOrder->bill_city}}<br>
										{{$WebsiteMainOrder->bill_state}} - {{$WebsiteMainOrder->bill_zip}}, {{$WebsiteMainOrder->bill_country}}, {{$WebsiteMainOrder->bill_phone}}<br>
										{{$WebsiteMainOrder->bill_email}}
									</p>
								</li>

								<li>
									<h4 class="mb-1 mt-3">Payment Method:</h4>
									<p class="mb-0">{{$WebsiteMainOrder->payment_method}}</p>
								</li>

								@if($StoreMainOrder->is_only_gc == 0)
								<li>
									<h4 class="mb-1 mt-3">Shipping Method:</h4>
									<p class="mb-0">{!!$WebsiteMainOrder->fullshipping_info!!}</p>
								</li>
								@endif

								@if($WebsiteMainOrder->gift_from!="" || $WebsiteMainOrder->gift_to!="" || $WebsiteMainOrder->gift_message_customer!="" || $WebsiteMainOrder->free_gift!="")
									@if($WebsiteMainOrder->gift_from!="")
										<p class="mb-0"><strong>From:</strong> {{$WebsiteMainOrder->gift_from}}</p>
									@endif
									@if($WebsiteMainOrder->gift_to!="")
										<p class="mb-0"><strong>To:</strong> {{$WebsiteMainOrder->gift_to}}</p>
									@endif
									@if($WebsiteMainOrder->gift_message_customer!="")
										<p class="mb-0"><strong>Customer Message:</strong> {{$WebsiteMainOrder->gift_message_customer}}</p>
									@endif
									@if($WebsiteMainOrder->free_gift!="")
										<p class="mb-0"><strong>{{$WebsiteMainOrder->free_gift}}</strong></p>
									@endif
								@endif
							</ul>
						</div>

				</div>
			</div>
		</div>
	</section>

	<section class="checkout-page order-confirm">
		<div class="container">
			<div class="checkout-middle-sec">
				<div class="checkout-left-part">
					<div class="checkout-cont-lg">
						@if(isset($roktstr) && $roktstr != '')
							<div id="rokt-placeholder">
								<iframe aria-hidden="true" src="https://apps.rokt.com/wsdk/preload/index.html" sandbox="allow-scripts allow-same-origin" style="border: 0px; width: 100%; display: none;"></iframe>
							</div>
						@endif

						<div class="row pr-lg-4 pr-0">
							<div class="col-12">
								<div class="confm-top text-center">
									<h3 class="cart-hd-sub text-center pb-3"><strong>Hooray, your order is placed! Order Receipt!</strong></h3>
									<p>Your order number is <strong class="color-red">{{$StoreMainOrder->orders_no}}</strong><span class="orderType">(Store)</span></p>
									@if($Payment_Method_Message != '')
									<p>We hope you enjoyed shopping with us. Enjoy your new fragrance!</p>
									@else
										<p>A confirmation email is headed your way at {{$StoreMainOrder->bill_email}}.</p>

										</p>
									@endif
								</div>
								<div class="confm-bottom w-100 text-center">
									<a href="#" class="mt-3 d-block">
										<!--<img src="{{config('global.SITE_IMAGES')}}cofim-banner.png" alt="" class="img-fluid">-->
										<img src="{{$order_receipt_image}}" alt="" class="img-fluid">
									</a>
									<div class="chekout-main-btn mt-3">
										<div class="row">
											<div class="col-12">
												<a class="btn btn-primary d-block checkout-btn" href="{{url('/store/store-dashboard.html')}}">Back To Store</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="checkout-right">
						<div class="your-bag">
							<div class="actmenuhd">
								<h4>
									Order Receipt
									<svg id="down-arrow" class="sv-down-arrow vam ml-2" aria-hidden="true" role="img" width="14" height="12">
										<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
									</svg>
								</h4>
								<span class="float-right text-right">
									<div class="pb-2">
										<strong class="order-date">Date: {{date('m/d/Y')}}</strong>
										<a href="{{url('/order-detail-pdf/'.$StoreMainOrder->orders_id.'.html')}}" class="print-btn ml-2">
										<svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
											<use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
										</svg> Print
									</a>
									</div>
								</span>
							</div>
							<div class="actmenu-inner your-bag-inner mt-3" id="cart_items">
								<ul>
									@foreach($StoreOrderDetails as $key => $Order)
									<li>
										<div class="summary-row">
											<div class="summary-img">
												<img alt="" src="{{$Order->Image}}" class="product-img img-fluid">
											</div>
											<div class="cart-product pb-1">
												<div class="cart-product pb-1">
													<a href="{{$Order->ProdLink}}">{!!$Order->product_name!!} </a>
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
												<div class="itemType">Store Item</div>
											</div>
											<div class="text-right d-none d-sm-block">
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

						<div class="order-summry mt-4">
							<div class="row">
								<div class="col-12">
									<table class="table text-left table-border-none table-borderless mb-0">
										<tbody>
											<tr>
												<td>Subtotal:</td>
												<td class="text-right"><strong>{{Price($StoreMainOrder->sub_total)}}</strong></td>
											</tr>
											@foreach($StoreAllCharges as $ckey => $Charge)
												@if($StoreMainOrder->{$Charge['field']} > 0 )
												<tr>
													<td>{{$Charge['label']}}:</td>
													<td class="text-right"><strong>{{Price($StoreMainOrder->{$Charge['field']})}}</strong></td>
												</tr>
												@endif
											@endforeach
											@foreach($StoreAllDiscounts as $dkey => $Discount)
												@if($StoreMainOrder->{$Discount['field']} > 0 )
												<tr>
													<td>{{$Discount['label']}}:</td>
													<td class="text-right"><strong>-{{Price($StoreMainOrder->{$Discount['field']})}}</strong></td>
												</tr>
												@endif
											@endforeach
											<tr class="cart-total">
												<td>Order Total</td>
												<td class="text-right">{{Price($StoreMainOrder->order_total)}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
									<!-- d-none d-md-inline-block -->
						<div class="small-address w-100">
							<ul>

								<li>
									<h4 class="mb-1 mt-3">Billing to:</h4>
									<p class="mb-0">
										{{$StoreMainOrder->bill_first_name}} {{$StoreMainOrder->bill_last_name}},<br>
										@if($StoreMainOrder->bill_company != '')
											Company: {{$StoreMainOrder->bill_company}}<br>
										@endif
										{{$StoreMainOrder->bill_address1}}
										@if($StoreMainOrder->bill_address2 != '')
											, {{$StoreMainOrder->bill_address2}}
										@endif
										, {{$StoreMainOrder->bill_city}}<br>
										{{$StoreMainOrder->bill_state}} - {{$StoreMainOrder->bill_zip}}, {{$StoreMainOrder->bill_country}}, {{$StoreMainOrder->bill_phone}}<br>
										{{$StoreMainOrder->bill_email}}
									</p>
								</li>

								<li>
									<h4 class="mb-1 mt-3">Payment Method:</h4>
									<p class="mb-0">{{$StoreMainOrder->payment_method}}</p>
								</li>

								@if($StoreMainOrder->gift_from!="" || $StoreMainOrder->gift_to!="" || $StoreMainOrder->gift_message_customer!="" || $StoreMainOrder->free_gift!="")
									@if($StoreMainOrder->gift_from!="")
										<p class="mb-0"><strong>From:</strong> {{$StoreMainOrder->gift_from}}</p>
									@endif
									@if($StoreMainOrder->gift_to!="")
										<p class="mb-0"><strong>To:</strong> {{$StoreMainOrder->gift_to}}</p>
									@endif
									@if($StoreMainOrder->gift_message_customer!="")
										<p class="mb-0"><strong>Customer Message:</strong> {{$StoreMainOrder->gift_message_customer}}</p>
									@endif
									@if($StoreMainOrder->free_gift!="")
										<p class="mb-0"><strong>{{$StoreMainOrder->free_gift}}</strong></p>
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
			<div class="home-popup-content">
			  <div class="text-center">
				 <form method="post" name="frmCashDrawerChange" id="frmCashDrawerChange" onsubmit="return false;">
				 <input type="hidden" name="act_type" id="act_type" value="add">
				 <input type="hidden" name="pos_store_id" value="{{ Auth::guard('store')->user()->store_id }}">
				 <input type="hidden" name="pos_order_id" value="{{ $StoreMainOrder->orders_no }},{{ $WebsiteMainOrder->orders_no }}">
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
								<td class="">{{ $StoreMainOrder->orders_no }}(Store), {{ $WebsiteMainOrder->orders_no }}(Online)</td>
							</tr>
							<tr class="text-center" height="50">
								<td class="text-left pl-2 pr-2"><strong>Order Total:</strong></td>
								<td class="">{{Price($StoreMainOrder->order_total + $WebsiteMainOrder->order_total)}}</td>
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
						      <p class="value mb-0" aria-labelledby="orderNumberLabel">{{ $StoreMainOrder->orders_no }}(Store), {{ $WebsiteMainOrder->orders_no }}(Online)</p>
						    </div>

						    <div class="info-box info-bright">
						      <div class="label" id="orderTotalLabel">Order Total</div>
						      <p class="value mb-0" aria-labelledby="orderTotalLabel">{{Price($StoreMainOrder->order_total + $WebsiteMainOrder->order_total)}}</p>
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
							<button class="btn btn-primary" id="submitCashChangeBtn" onclick="javascript:cashDrawerChangeSubmit();" aria-label="Submit cash change details">Submit</button>
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
			//alert("Star WebPRNT Browser detected");
			storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Order Receipts Page','No');

			setTimeout(onConnect(),3000);
			setTimeout(onPrint(),2000);
		} else {
			//alert("Star WebPRNT Browser NOT detected");
			storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Order Receipts Page','No');
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

}*/

/*function cashDrawerReceiveEvent(event = '')
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
}
*/
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

// $(document).on('click', '.sv-down-arrow', function () {
//     const $yourBag = $(this).closest('.your-bag');
//     $yourBag.find('#cart_items').slideToggle();
//     $yourBag.next('.order-summry').slideToggle();
//     $yourBag.nextAll('.small-address:first').slideToggle();

// });
</script>

@endsection
