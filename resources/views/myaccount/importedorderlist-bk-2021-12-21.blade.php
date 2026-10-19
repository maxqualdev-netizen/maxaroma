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

					<div class="myact_hd">
					   <svg class="sv_dio_list" aria-hidden="true" role="img">
					      <use href="#sv_dio_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dio_list"></use>
					   </svg>
					   <h2>Dropshipper Imported Order List</h2>
					</div>
				   <div class="row">
			            @if($OutOfStockMsg!='')
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $OutOfStockMsg]"
							/>
			            @endif
			            @if($OrderProcessMsg!='')
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $OrderProcessMsg]"
							/>
			            @endif
				   </div>
					<div class="col-md-12 text-center pt-3" id="delete-success-message" style="display: none;">
						<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => 'Order is deleted successfully']"
						/>
					</div>
					<div class="myorder_dtl">
					   <div id="Rptable">
	  					<form action="" method="post" name="frmOrderList" id="frmOrderList">
						  <!-- <input type="hidden" name="count_paymentmethod" value="{$PaymentMethodList|@count}" /> -->
						  <input type="hidden" name="checkpayamazonmsg" id="checkpayamazonmsg" value="No">
						  <input type="hidden" name="order_action" value="" id="order_action">
				          <input type="hidden" name="dataselected" value="No" id="dataselected">
				          <input type="hidden" name="action" value="Delete">
					      <table class="text-left">
					         <thead>
					            <tr>
					               <td>
					                  <label class="checkbox-label d-none d-md-inline-block">
					                     <div class="chebox"><input type="checkbox"><span class="checkmark"></span></div>
					                  </label>
					               </td>
					               <td>Customer Order No.</td>
					               <td>Date</td>
					               <td>Time</td>
					               <td class="text-left">Customer</td>
					               <td>Subtotal</td>
					               <td>Shippling</td>
					               <td>Order&nbsp;Total</td>
					               <td></td>
					            </tr>
					         </thead>
					         <tbody class="align-top">
					         	@php $i=1; @endphp
								@php $order_totalfinal = 0; @endphp
              					@if(count($OrdResult) > 0)
              					@foreach($OrdResult as $order_result_key => $order_result_value)
              					@php

						              $status_bg = "";
						              if($order_result_value['shippingModeId'] > 0) {
									  	$order_totalfinal = $order_totalfinal + $order_result_value['order_total'];
						              }
						              if($order_result_value['is_outofstock'] == 'Yes') {
						              	$status_bg = "alert-pending";
						              }

              					@endphp
					            <tr id="tr_{{$order_result_value->orders_id}}">
					               <td data-title="">
					                  <label class="checkbox-label d-none d-md-inline-block">
					                     <div class="chebox"><input type="checkbox" @if($order_result_value->shippingModeId <=0 || $order_result_value->shippingModeId=='') disabled @endif id="orders_id" name="ch{{$i}}" value="{{$order_result_value->orders_id}}" onclick="getOrderTotal();"><span class="checkmark"></span></div>
					                  </label>
									<input type="hidden" id="order_{{$i}}" name="order_{{$i}}" value="{{$order_result_value->order_total}}">
									<input type="hidden" id="disabledfield{{$i}}" name="disabledfield{{$i}}" value="@if($order_result_value->shippingModeId <=0 || $order_result_value->shippingModeId=='')Yes @else No @endif">
					               </td>
					               <td data-title="Rder no."># {{$order_result_value->orders_no}}
										<input type="hidden" id="orders_no{{$i}}" name="orders_no{{$i}}" value="{{$order_result_value->orders_no}}">
										<input type="hidden" id="ordersnew_id{{$i}}" name="ordersnew_id{{$i}}" value="{{$order_result_value->orders_id}}">
					               </td>
					               <td data-title="Date">{{$order_result_value->order_date}}</td>
					               <td data-title="Time">{{$order_result_value->order_time}}</td>
					               <td data-title="Customer">{{$order_result_value->bill_first_name}} {{$order_result_value->bill_last_name}}</td>
					               <td data-title="Subtotal">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_result_value->sub_total}}</td>
					               <td data-title="Shippling">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_result_value->shipping_amt}}</td>
					               <td data-title="Order Total">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_result_value->order_total}}</td>
					               <td data-title="" onclick="submitImportOrder({{$order_result_value->orders_id}});">
					                  <svg class="sv-trash vam" aria-hidden="true" role="img" width="20" height="20">
					                     <use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
					                  </svg>
					               </td>
					            </tr>
					         	@php $i++; @endphp
					            @endforeach
					            @else
					            <tr>
					            	<td colspan="9" class="text-center">Sorry, there is no record to list..</td>
					            </tr>
					            @endif
					         </tbody>
					      </table>
					  	</form>
					   </div>
					</div>


				</div>
			</div>
		</div>
	</div>
</main>	

@endsection