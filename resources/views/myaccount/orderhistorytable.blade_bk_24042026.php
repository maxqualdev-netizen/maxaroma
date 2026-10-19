<!-- <style>
	.label{display:inline !important;padding:.2em .6em .3em !important;font-size:75% !important;font-weight:700 !important;line-height:1 !important;text-align:center !important;white-space:nowrap !important;vertical-align:baseline !important;border-radius:.25em !important;max-width:100% !important;margin-bottom:5px !important;}
	.custlabel{min-width:100px !important;font-size:12px !important;display:inline-block !important;padding:.3em .6em .3em !important;}
</style> -->
@if(count($OrdResult) > 0)

	@php
	$statusArr = ['Pending','Shipped','Delivered'];
	$ShippingPercentArr = array();
	$ShippingPercent = 0;

	$dividePercent = 100 / (count($statusArr) - 1);

	for($s=0;$s<count($statusArr);$s++){
		if($s == 0){
			$ShippingPercentArr[$statusArr[$s]] = 0;
		}else{
			$ShippingPercentArr[$statusArr[$s]] = $ShippingPercent + $dividePercent;
			$ShippingPercent += $dividePercent;
		}
	}
	
	@endphp
	
	@foreach($OrdResult as $order_result_key => $order_result_value)
		@php
			$setStatusColor = "";
			
			if($order_result_value->status == 'Pending'){
				$setStatusColor = 'label-info';
			}
			else if($order_result_value->status == 'Completed'){
				$setStatusColor = 'label-success';
			}
			else if($order_result_value->status == 'Canceled'){
				$setStatusColor = 'label-danger';
			}
			else if($order_result_value->status == 'Declined'){
				$setStatusColor = 'label-danger';
			}
			else if($order_result_value->status == 'Refund'){
				$setStatusColor = 'label-danger';
			}
			else if($order_result_value->status == 'Sent To Stripe'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Return Requested'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Return Approved'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Return Rejected'){
				$setStatusColor = 'label-danger';
			}
			else if($order_result_value->status == 'Request Cancellation'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Request Cancellation'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Pending By Amazon'){
				$setStatusColor = 'label-info';
			}
			else if($order_result_value->status == 'Pending - PhoneOrder'){
				$setStatusColor = 'label-info';
			}
			else if($order_result_value->status == 'Claim Approved'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Claim Rejected'){
				$setStatusColor = 'label-danger';
			}
			else if($order_result_value->status == 'Claim In Review'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Claim Requested'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Claim In Review - Police Report Required'){
				$setStatusColor = 'label-warning';
			}
			else if($order_result_value->status == 'Refund Initiated'){
				$setStatusColor = 'label-warning';
			}
			else{
				$setStatusColor = '';
			}
			
			$setPayStatusColor = "";
			if($order_result_value->pay_status == 'Paid'){
				$setPayStatusColor = 'label-success';
			}
			else if($order_result_value->pay_status == 'Unpaid'){
				$setPayStatusColor = 'label-danger';
			}
			else if($order_result_value->pay_status == 'Inprocess'){
				$setPayStatusColor = 'label-warning';
			}
			

			if($order_result_value->payment_type!='PAYMENT_CL' && $order_result_value->use_credit_limit=='Yes') {
				$pay_method = $order_result_value->payment_method.'/Credit Limit';
			} else {
				$pay_method = $order_result_value->payment_method;
			}

			$phoneorder_pay_url="";
			if($order_result_value->status == 'Pending - PhoneOrder' && $order_result_value->phoneorder_payby == 'Customer') {
				$enc_order = base64_encode($order_result_value->orders_id);
				$phoneorder_pay_url = config('global.SITE_URL').'payment/'.$enc_order;
			}
			
			if($order_result_value->show_ship_status == 'Delivered'){
				$order_result_value->ship_status = 'Delivered';
			}
						
		@endphp
				   
		<div class="myact_obox">
			<div class="myact_oboxaccordion">
				<ul class="thead oboxaccordion-thead">
					@if($order_result_value->status == 'Pending')
						<li class="col_1"><strong>order no</strong><span><a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html">{{ $order_result_value->orders_no }}</a></span></li>
						<li class="col_1"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
						<li class="col_1">
							<strong>PAYMENT STATUS</strong>
							<span class="label">
								<span class="custlabel {{$setPayStatusColor}}">{{ $order_result_value->pay_status }}</span>
							</span>
						</li>
						<li class="col_1">
							<strong>order status</strong>
							<span class="label">
								<span class="custlabel {{$setStatusColor}}">{{ $order_result_value->status }}</span>
							</span>
						</li>
						<li class="col_1">
							<strong>TOTAL AMOUNT</strong>
							<span><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</strong></span>
						</li>
						<li class="col_5 orderProgress">
							<div class="progressbar-main">
								<div id="shippingProgressBarContainer_{{$order_result_value->orders_id}}" class="progressbar-top">
									<div id="shippingProgressBar_{{$order_result_value->orders_id}}" class="progressbar-complete" style="width:{{$ShippingPercentArr[$order_result_value->ship_status]}}%;">{{--{{$ShippingPercentArr[$order_result_value->ship_status]}}%--}}</div>
								</div>
								<div class="progressbar-bottom">
									@php
										$statusVal = $order_result_value->ship_status;
										
										$OrderArr = [];  
										foreach ($statusArr as $idx => $status) {
											$norm = $status;
											$OrderArr[$idx] = $norm;
										}

										$selectedIndex = array_search($statusVal, $OrderArr);
										if ($selectedIndex === false) {
											$selectedIndex = -1;
										}
									@endphp

									@foreach ($statusArr as $s => $status)
										@php
											$showStatus = $status;

											$setClass = ($s <= $selectedIndex) ? ' is-complete' : '';
										@endphp

										<span class="progressbar-col {{ $setClass }}">
											<span class="progressbar-icon"></span>
											<span class="progressbar-label">{{ $showStatus }}</span>
										</span>
									@endforeach
								
								</div>
							</div>
						</li>
						<li class="col_1 orderTrackCancel">
							@if(isset($order_result_value->payment_type) &&	$order_result_value->payment_type != 'REPLACEMENT_PAYMENT')
								<a href="javascript:void(0);" class="cancel-btn" onclick="DisplayPopupBoxCancelOrder('{{$order_result_value->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">
									<!-- <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
										<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
									</svg> -->
									<svg class="sv_cancellation" aria-hidden="true" role="img">
										<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
									</svg>Cancel Order
								</a>
							@endif	
							
							@php
								if($order_result_value->tracking_no!='') {
									$track_url = 'javascript:void(0);';
									
									if($order_result_value->ship_method=='USPS') {
										$track_url = 'https://tools.usps.com/go/TrackConfirmAction.action?tRef=fullpage&tLc=1&tLabels='.$order_result_value->tracking_no;
									}
									elseif($order_result_value->ship_method=='FedEx') {
										$track_url = 'https://www.fedex.com/fedextrack/?trknbr='.$order_result_value->tracking_no;
									} 
									elseif($order_result_value->ship_method=='UPS') {
										$track_url = config('global.SITE_URL').'tracking/'.$order_result_value->tracking_no;
									}
								}
							@endphp
							
							@if($order_result_value->tracking_no!='')
								<a href="{{$track_url}}" class="track-btn" target="_blank">
									<svg class="svg-timelineshipping" aria-hidden="true" role="img">
										<use href="#svg-timelineshipping" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-timelineshipping"></use>
									</svg>Track Order
								</a>
							@endif
						</li>
					@else
						<li class="col_1"><strong>order no</strong><span><a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html">{{ $order_result_value->orders_no }}</a></span></li>
						<li class="col_1"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
						<li class="col_1">
							<strong>PAYMENT STATUS</strong>
							<span class="label">
								<span class="custlabel {{$setPayStatusColor}}">{{ $order_result_value->pay_status }}</span>
							</span>
						</li>
						<li class="col_1">
							<strong>order status</strong>
							<span class="label">
								<span class="custlabel {{$setStatusColor}}">
									<a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html">{{ $order_result_value->status }}</a>
								</span>
							</span>
						</li>
						<li class="col_1">
							<strong>TOTAL AMOUNT</strong>
							<span><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</strong></span>
						</li>
						<li class="col_5 orderProgress">
							<div class="progressbar-main">
								<div id="shippingProgressBarContainer_{{$order_result_value->orders_id}}" class="progressbar-top">
									<div id="shippingProgressBar_{{$order_result_value->orders_id}}" class="progressbar-complete" style="width:{{$ShippingPercentArr[$order_result_value->ship_status]}}%;">{{--{{$ShippingPercentArr[$order_result_value->ship_status]}}%--}}</div>
								</div>
								<div class="progressbar-bottom">
									@php
										$statusVal = $order_result_value->ship_status;
										
										$OrderArr = [];  
										foreach ($statusArr as $idx => $status) {
											$norm = $status;
											$OrderArr[$idx] = $norm;
										}

										$selectedIndex = array_search($statusVal, $OrderArr);
										if ($selectedIndex === false) {
											$selectedIndex = -1;
										}
									@endphp

									@foreach ($statusArr as $s => $status)
										@php
											$showStatus = $status;

											$setClass = ($s <= $selectedIndex) ? ' is-complete' : '';
										@endphp

										<span class="progressbar-col {{ $setClass }}">
											<span class="progressbar-icon"></span>
											<span class="progressbar-label">{{ $showStatus }}</span>
										</span>
									@endforeach
									
								</div>
							</div>
						</li>
						<li class="col_1 orderTrackCancel">
							@php
								if($order_result_value->tracking_no!='') {
									$track_url = 'javascript:void(0);';

									if($order_result_value->ship_method=='USPS') {
										$track_url = 'https://tools.usps.com/go/TrackConfirmAction.action?tRef=fullpage&tLc=1&tLabels='.$order_result_value->tracking_no;
									}
									elseif($order_result_value->ship_method=='FedEx') {
										$track_url = 'https://www.fedex.com/fedextrack/?trknbr='.$order_result_value->tracking_no;
									} 
									elseif($order_result_value->ship_method=='UPS') {
										$track_url = config('global.SITE_URL').'tracking/'.$order_result_value->tracking_no;
									}
								}
							@endphp
							@if($order_result_value->tracking_no!='')
								<a href="{{$track_url}}" class="track-btn" target="_blank">
									<svg class="svg-timelineshipping" aria-hidden="true" role="img">
										<use href="#svg-timelineshipping" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-timelineshipping"></use>
									</svg>Track Order
								</a>
							@endif
						</li>
					@endif
				</ul>
				<div class="oboxaccordion-toggle" role="button">
					<svg class="sv-down-arrow" aria-hidden="true" role="img">
						<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
					</svg>
				</div>
			</div>
			@php $i = 0; @endphp
			@if(count($order_result_value->order_items) > 0)
				@foreach($order_result_value->order_items as $order_items_key => $order_items_value)
					<ul class="body oboxaccordion-body" style="display:none;">
						<li class="col_1">
							<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $order_items_value->Image }}" alt="" />
						</li>
						<li class="col_2">
							<div class="info">
								<a href="#"><strong>{!! remove_html_entities($order_items_value->product_name) !!}</strong></a><br />
								Item SKU : {{ $order_items_value->sku }}<br />
								Unit Price: {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_items_value->price }}<br />
								Quantity : {{ $order_items_value->quantity }}
							</div>
							@if($order_items_value->excluded_flag!='' && trim($order_items_value->excluded_flag)=='Final Sale')
								<div class="info">									
									<strong>{{config('global.FINALSALE');}}</strong>								   
								</div>
							@endif
						</li>
					
						<li class="col_3">
							<div class="amount">TOTAL AMOUNT <strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</strong></div>
							<div class="refund">REFUND AMT. {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->refund_amount }}<br />
								PAYMENT METHOD: {{ $pay_method }}
							</div>
						</li>
					
						<li class="col_4">
							<div class="row row3">
								@if($i == 0)
									<div class="col-6">
										<a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html" class="btn btn-primary">View Order</a>
									</div>
									@if($order_result_value->is_only_gc==0 && isset($order_result_value->payment_type) && $order_result_value->payment_type != 'REPLACEMENT_PAYMENT')
										<div class="col-6">
											<a href="{{config('global.SITE_URL')}}re-order-detail/{{$order_result_value->orders_id}}.html" class="btn btn-secondary">reoRDER</a>
										</div>
									@endif
									@if($view_fileType == 'print')
										<div class="col-6 pt-2">
											<a href="javascript:void(0);" class="btn btn-secondary" onclick="window.open('{{config('global.SITE_URL')}}order-detail-print/{{$order_result_value->orders_id}}.html', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')">Print</a>
										</div>
									@else
										@if(isset($order_result_value->payment_type) && $order_result_value->payment_type != 'REPLACEMENT_PAYMENT')
											<div class="col-6 pt-2">
												<a href="{{config('global.SITE_URL')}}order-detail-pdf/{{$order_result_value->orders_id}}.html" class="btn btn-secondary">Invoice</a>
											</div>
										@else 
											<div class="col-6">
												<a href="{{config('global.SITE_URL')}}order-detail-pdf/{{$order_result_value->orders_id}}.html" class="btn btn-secondary">Invoice</a>
											</div>
										@endif
									@endif

									@if($phoneorder_pay_url!="")
										<div class="col-6 pt-2">
											<a href="{{ $phoneorder_pay_url }}" class="btn btn-secondary">Pay Now</a>
										</div>
									@endif
				
									@if(Session::get('sess_useremail') == 'gequaldev@gmail.com' && $order_result_value->orders_id == "547633"  )
										<div class="col-6 pt-2">
											<!--<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a>-->
											@if(isset($order_result_value->payment_type) && $order_result_value->payment_type != 'REPLACEMENT_PAYMENT')
												<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrderNew('{{$order_result_value->orders_no}}','{{$order_result_value->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
											@endif
										</div>
									@else    
										@if($order_result_value->days <= 30 && $order_result_value->ship_status =='Shipped' && in_array($order_result_value->status, ['Completed','Return Requested','Return Approved','Return Rejected','Item Received']) && in_array($order_items_value->ostatus,['Pending','Completed']) && $order_items_value->excluded_flag != 'Final Sale')
											<div class="col-6 pt-2">
												<!--<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a>-->
												@if(isset($order_result_value->payment_type) && $order_result_value->payment_type != 'REPLACEMENT_PAYMENT')
													<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrderNew('{{$order_result_value->orders_no}}','{{$order_result_value->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
												@endif
											</div>
										@endif
									@endif
								@endif
					
								@if($order_items_value->return_shipping_label != '')
									<div class="col-6 pt-2">{!! $order_items_value->return_shipping_label !!}</div>
								@endif
								@if($order_items_value->COrderNo != '')
									<div class="col-6 pt-2">
										<a href="{{ $order_items_value->COrderNo }}" target="_blank" class="btn btn-sm btn-primary" rel="nofollow">Return Label </a>
									</div>
								@endif
							
								@if($i == 0)
									@if(Session::get('sess_useremail') == '11gequaldev@gmail.com' && $order_result_value->pay_status =='Paid' &&$order_result_value->route_shipping_insurance_charge >0 && !in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
										<div class="col-6 pt-2">
											<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}','{{$order_result_value->bill_first_name}}','{{$order_result_value->bill_last_name}}','{{$order_result_value->bill_email}}','{{$order_result_value->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
										</div>
									@elseif($order_result_value->days <= 30 && $order_result_value->pay_status =='Paid' && $order_result_value->ship_status =='Shipped' &&$order_result_value->route_shipping_insurance_charge >0 && !in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
										<div class="col-6 pt-2">
											<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}','{{$order_result_value->bill_first_name}}','{{$order_result_value->bill_last_name}}','{{$order_result_value->bill_email}}','{{$order_result_value->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
										</div>
									@elseif(in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
										<div class="col-6 pt-2">
											<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimedOrder('{{$order_result_value->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claimed </a>
										</div>
									@endif
									@if($order_result_value->status == "Claim In Review - Police Report Required")
										<div class="col-6 pt-2">
											<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimPoliceReportOrder('{{$order_result_value->orders_id}}','Y');" data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Upload Police Report</a>
										</div>
										@if($order_result_value->policeReportAttach != "")
											<div class="col-6 pt-2">
												<a href="{{$order_result_value->policeReportAttach}}" class="btn btn-secondary" target="_blank">View Police Report</a>
											</div>
										@endif
									@endif
								@endif

								@if($order_items_value->Reason!='')
									<div class="info pt-2 col-12">Reason: {{ $order_items_value->Reason }}</div>
									<div class="info pt-1 col-12">Status: {{ $order_items_value->ostatus }}</div>
								@endif
							</div>
						</li>
					</ul>
					@php $i++; @endphp
				@endforeach
			@endif
		</div>
	@endforeach
	<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
		{!! $OrdResult->onEachSide(0)->links('myaccount.pagination') !!}
	</div>
@else
	<div class="row pt-3">
		<div class="col-md-12 text-center">
			<span class="errmsg tac">Sorry, No orders found!</span>
		</div>
	</div>
@endif


<script>
	$(".oboxaccordion-toggle").on("click", function() {
		const $this = $(this);
		$this.toggleClass("oboxaccordion-open");
		$this.closest(".myact_oboxaccordion").next(".oboxaccordion-body").slideToggle(300);
	});
</script>
<script>
	// $(".orderInfo").click(function(){
	// 	if($(this).text() == '+'){
	// 		$(this).text('-');
	// 		var mainEle = $(this).parent().attr('class');
	// 		$(this).parent().nextAll().toggle();
	// 	}else{
	// 		$(this).text('+');
	// 		var mainEle = $(this).parent().attr('class');
	// 		$(this).parent().nextAll().toggle();
	// 	}
	// });
</script>

<script>
	/*$(document).ready(function() {
		var orderId = "YOUR_ORDER_ID"; // Replace with actual order ID
		var intervalTime = 5000; // Poll every 5 seconds

		function updateShippingStatus() {
			$.ajax({
				url: "/api/getShippingStatus", // Your server-side endpoint
				method: "GET",
				data: { orderId: orderId },
				success: function(response) {
					// Assuming response.percentage is a number from 0-100
					// and response.statusText is a string describing the status
					//var percentage = response.percentage;
					var percentage = 50;
					//var statusText = response.statusText;
					var statusText = 'Shipped';

					$("#shippingProgressBar").css("width", percentage + "%").text(percentage + "%");
					$("#shippingStatusText").text(statusText);

					if (percentage < 100) {
						setTimeout(updateShippingStatus, intervalTime); // Continue polling
					} else {
						// Shipping complete, stop polling or perform final actions
						console.log("Order shipping complete!");
					}
			/}
				error: function(xhr, status, error) {
					console.error("Error fetching shipping status:", error);
					$("#shippingStatusText").text("Error fetching shipping status.");
					// Optionally, retry or display an error message
					setTimeout(updateShippingStatus, intervalTime * 2); // Retry after longer interval
				}
			});
		}

		updateShippingStatus(); // Initial call to start the polling
	});*/
</script>
