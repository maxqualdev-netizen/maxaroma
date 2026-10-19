@extends('layouts.app')
@section('content')
<div id="myModalPopUpCancelOrder" class=" modal fade login_popup" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	

				<div class="col-lg-9 col-md-12 col-xs-12">
				   <div class="myact_hd">
				      <svg class="sv_cancellation" aria-hidden="true" role="img">
				         <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
				      </svg>
				      <h2>My Cancellation</h2>
				   </div>
				   <div class="row p-3">
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
				   </div>
				   @if(count($OrdResult) > 0)
				   @foreach($OrdResult as $order_result_key => $order_result_value)
				   @php
						if($order_result_value->payment_type!='PAYMENT_CL' && $order_result_value->use_credit_limit=='Yes') {
							$pay_method = $order_result_value->payment_method.'/Credit Limit';
						} else {
							$pay_method = $order_result_value->payment_method;
						}
				   @endphp
				   <div class="myact_obox myact_cc">
				      <ul class="thead">
				         <li class="col_1"><strong>order no</strong><span>{{ $order_result_value->orders_no }}</span></li>
				         <li class="col_2"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
				         <li class="col_3"><strong>Payment Status</strong><span>{{ $order_result_value->pay_status }}</span> </li>
				         <li class="col_4"><strong>order status</strong><span class="{{ GetOrderStatusClass($order_result_value->status) }}">{{ $order_result_value->status }}</span></li>
				      </ul>
				      @if(count($order_result_value->order_items) > 0)
					      @foreach($order_result_value->order_items as $order_items_key => $order_items_value)
					      <ul class="body">
					         <li class="col_1"> <img src="{{ $order_items_value->Image }}" alt="" /> </li>
					         <li class="col_2">
					            <div class="info"> <a href="#"><strong>{!! $order_items_value->product_name !!}</strong></a><br />
					               Item SKU : {{ $order_items_value->sku }}<br />
					               Unit Price: {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_items_value->price }} <br />
					               Quantity: {{ $order_items_value->quantity }}
					            </div>
					         </li>
					      	
							 <li class="col_3">
								 <table class="myact_cc_table">
								   <tr>
								      <td class="ttu">Sub Total</td>
								      <td>${{ $order_result_value->sub_total }}</td>
								   </tr>
								   <tr class="amount">
								      <td class="ttu">TOTAL AMOUNT</td>
								      <td><strong class="pt-0">${{ $order_result_value->order_total }}</strong></td>
								   </tr>
								   <tr>
								      <td class="ttu">REFUND AMT. </td>
								      <td>${{ $order_result_value->refund_amount }}</td>
								   </tr>
								   <tr>
								      <td class="ttu">PAYMENT METHOD:</td>
								      <td>{{ $pay_method }}</td>
								   </tr>
								 </table>
							 </li>
					         <!-- <li class="col_3">
					            <div class="amount">tOTAL aMOUNT <strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</strong></div>
					            <div class="refund">REFUND AMT. {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->refund_amount }}<br />
					               PAYMENT METHOD: {{ $pay_method }}
					            </div>
					         </li> -->
					        
					         @if($order_result_value->status == 'Pending')
					         <li class="col_4">
					            <a href="javascript:void(0);" class="btn btn-primary" onclick="DisplayPopupBoxCancelOrder('{{$order_result_value->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Cancel Order</a>
					         </li>
					         @endif
					      </ul>
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
					      <span class="errmsg tac">There are no cancel orders.</span>
					   </div>
					</div>
				   @endif
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection