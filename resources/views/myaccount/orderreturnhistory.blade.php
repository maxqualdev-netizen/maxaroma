@extends('layouts.app')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>
				<div class="col-lg-9 col-md-12 col-xs-12">
					<div class="myact_hd myact_hd_flex">
						<div class="myact_hdleft">
							<svg class="sv_orders" aria-hidden="true" role="img">
								<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
							</svg>
							<h2>My Returns Orders</h2>
						</div>
						<div class="myact_hdright">
							<a href="{{config('global.SITE_URL')}}site-page/returns-policy.html">Return Policy</a>&nbsp;|&nbsp;<a href="{{config('global.SITE_URL')}}faq.html">FAQ's</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="HelpChat getHelpChatNew" aria-label="Chat with us">Need Help?</a>
						</div>
					</div>

					<!-- <div class="myact_hd_search">
					<form action="" id="frmsearchorder" method="post">
					{{ csrf_field() }}
					<input type="text" placeholder="Search By Order No" class="form-control" name="orders_no" id="orders_no" value="" />
					<a href="javascript:void(0);" class="search_icon" id="search_order">
					<svg class="sv_search" aria-hidden="true" role="img">
					<use href="#sv_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_search"></use>
					</svg>
					</a>
					</form>
					</div>
					</div> -->
					
					@php
						$statusArr = ['Return Requested','Return Approved','Item Received','Refund'];
						$ReturnPercentArr = array();
						$ReturnPercent = 0;
						
						$dividePercent = 100 / (count($statusArr) - 1);
									
						for($s=0;$s<count($statusArr);$s++){
							if($s == 0){
								$ReturnPercentArr[$statusArr[$s]] = 0;
							}else{
								$ReturnPercentArr[$statusArr[$s]] = $ReturnPercent + $dividePercent;
								$ReturnPercent += $dividePercent;
							}
						}
						
					@endphp
					
					<div class="order-history-grid" id="order-history-grid">
						@if(count($OrdReturnHistory) > 0)
							@foreach($OrdReturnHistory as $as_key => $as_value)
							
								<div class="myact_obox myact_returns2">
									<div class="myact_oboxaccordion">
										<ul class="thead oboxaccordion-thead">
											<li class="col_1"><strong>order no</strong><span>{{ $as_value->orders_no}}</span></li>
											<li class="col_1"><strong>order DATE</strong><span>{{date('m/d/Y H:i',strtotime($as_value->order_datetime))}}</span></li>
											<li class="col_1"><strong>TOTAL AMOUNT</strong><span>{{ Price($as_value->total)}}</span> </li>
											<li class="col_1"><strong>order status</strong><span class="label"><span class="custlabel label-success">{{ $as_value->status}}</span></span></li>
											<li class="col_2 statustooltip-col">
												@if($as_value->status =='Return Requested' || $as_value->status =='Return Approved' || $as_value->status =='Item Received')	
													<div class="statustooltip">
														<b class="statustooltip-popup">What Happens Next<span>?</span></b>
														@if($as_value->status =='Return Requested')
															<span class="statustooltiptext">Your return request has been received. Our team will review it and update you once it's approved. You'll receive an email confirmation once it is approved.</span>
														@elseif($as_value->status =='Return Approved')
															<span class="statustooltiptext">Your return has been approved. We'll process your refund to your original payment method within 5–7 business days. You'll receive an email confirmation once completed. Please ship the item back using the provided instructions (If Any).</span>
														@elseif($as_value->status =='Item Received')
															<span class="statustooltiptext">We have received your returned item. We'll process your refund to your original payment method within 5–7 business days. You'll receive an email confirmation once completed.</span>
														@endif
													</div>
												@endif
											</li>
											<li class="col_5 orderProgress">
												
												<div class="progressbar-main returnProgressBarMain">
													<div id="returnProgressBarContainer_{{$as_value->orders_no}}" class="progressbar-top">
														<div id="returnProgressBar_{{$as_value->orders_no}}"  class="progressbar-complete" style="width:{{$ReturnPercentArr[$as_value->status]}}%;">{{--{{$ReturnPercentArr[$as_value->status]}}%--}}</div>
													</div>
													<div class="progressbar-bottom">
													@php
														$statusVal = str_replace('Return ', '', $as_value->status);
														$statusVal = str_replace('Item ', '', $statusVal);
														
														$OrderArr = [];  
														foreach ($statusArr as $idx => $status) {
															$norm = str_replace('Return ', '', $status);
															$norm = str_replace('Item ', '', $norm);
															$OrderArr[$idx] = $norm;
														}

														$selectedIndex = array_search($statusVal, $OrderArr);
														if ($selectedIndex === false) {
															$selectedIndex = -1;
														}
													@endphp

													@foreach ($statusArr as $s => $status)
														@php
															$showStatus = str_replace('Return ', '', $status);
															//$showStatus = str_replace('Item ', '', $showStatus);

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
										</ul>
										<div class="oboxaccordion-toggle" role="button">
											<svg class="sv-down-arrow" aria-hidden="true" role="img">
												<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
											</svg>
										</div>
									</div>
									<ul class="body oboxaccordion-body" style="display:none;">
										<li class="col_1"> <img src="{{$as_value->Image}}" alt="" /> </li>
										<li class="col_2">
											<div class="info"> 
												<a href="#"><strong>{!! $as_value->product_name !!}</strong></a><br />
												<span class="edu d-block pb-2"> </span> Item SKU : {{$as_value->sku}}<br />
												Unit Price: {{Price($as_value->unitprice)}} <br />
												Quantity : {{$as_value->quantity}}
											</div>
										</li>
										<li class="col_3">
											<div class="field statusfield">
												<label><strong>Return Reason</strong> : {{ $as_value->Reason }}</label>
												<label><strong>Status</strong> : <span class="custlabel label-success">{{ $as_value->status}}</span></label>
											</div>
										</li>
									</ul>
								</div>
							@endforeach
					
							<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
								{!! $OrdReturnHistory->onEachSide(0)->links('myaccount.pagination') !!}
							</div>
						@else
							<div class="row pt-3">
								<div class="col-md-12 text-center">
									<span class="errmsg tac">Sorry, No return orders found!</span>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

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

@endsection
