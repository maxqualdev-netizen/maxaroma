@extends('layouts.app')
@section('content')
<div id="myModalPopUpCancelOrder" class=" modal fade login_popup" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpReturnOrder" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpPoliceReport" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpClaimOrder" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpClaimTerms" class=" modal claim-term fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpClaimedOrder" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpReturnOrder" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
            	@auth
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
            	@endauth
				@php
					$class = (!Auth::user()) ? 'class="col-lg-12 col-md-12 col-xs-12"' : 'class="col-lg-9 col-md-12 col-xs-12"';
					$orderDetailId = ($OrderDetailRs->count() > 0) ? $OrderDetailRs[0]->orders_detail_id : '';
	            @endphp
				<div {!! $class !!}>
            		@auth
				   <div class="myact_hd">
				      <svg class="sv_orders" aria-hidden="true" role="img">
				         <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
				      </svg>
				      <h2>My Orders</h2>
				      <!-- <div class="myact_hd_search">
				         <input type="search" placeholder="Search By Order No" class="form-control" name="search" id="search" />
				         <a href="#" class="search_icon">
				            <svg class="sv_search" aria-hidden="true" role="img">
				               <use href="#sv_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_search"></use>
				            </svg>
				         </a>
				      </div> -->
				   </div>
            		@endauth
				   <div class="myorder_dtl">
					@if (Session::has('success'))
						<x-message :attr="[
							'classname' => 'frmsuccess text-center', 
							'message' => Session::get('success')]"
						/>
					@endif
				      <div class="myact_subhd">Order Details</div>
				      <div class="wlc_msg">
				         <div class="row">
				            <div class="col-md-8 pt-2 font_ssp"><!-- Your order has been sent. We hope you love it! <span class="d-block text_green">Estimated delivery Mar 26, 2020</span> --></div>
			                @if($view_fileType == 'print')
				            <div class="col-md-4 pt-2 text-left text-md-right">
				               <a href="javascript:void(0);" class="font_ssp print_link" onclick="window.open('{{config('global.SITE_URL')}}order-detail-print/{{$OrderRs->orders_id}}.html', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')">
				                  <svg class="sv-print" aria-hidden="true" role="img">
				                     <use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
				                  </svg>
				                  Print Order
				               </a>
				            </div>
			                @else
				            <div class="col-md-4 pt-2 text-left text-md-right">
				               <a href="{{config('global.SITE_URL')}}order-detail-pdf/{{$OrderRs->orders_id}}.html" class="font_ssp print_link">
				                  <svg class="sv-print" aria-hidden="true" role="img">
				                     <use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
				                  </svg>
				                  Print Invoice
				               </a>
				            </div>
			                @endif

				         </div>
				      </div>
				      <div class="order_info">
				         <div class="row row5">
				            <div class="col-md-8 pt-2">
				               <table>
				                  <tr>
				                     <td>Order Number :</td>
				                     <td><strong>{{ $OrderRs->orders_no }}</strong></td>
				                  </tr>
				                  @if($OrderRs->dropshipper_order_no != '')
				                  <tr>
				                     <td>Customer Order Number :</td>
				                     <td><strong>{{ $OrderRs->dropshipper_order_no }}</strong></td>
				                  </tr>
				                  @endif
				                  <tr>
				                     <td>Order Date :</td>
				                     <td><strong>{{ $OrderRs->datetime }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Payment Method :</td>
				                     <td><strong>{{ $OrderRs->payment_method }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Payment Status :</td>
				                     <td><strong>{{ $OrderRs->pay_status }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Shipment Status :</td>
				                     <td><strong>{{ $OrderRs->ship_status }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Order Status :</td>
				                     <td><strong>{{ $OrderRs->status }}</strong></td>
				                  </tr>
				                  @if($OrderRs->return_shipping_label > 0)
				                  <tr>
				                     <td>Return Shipping Label :</td>
				                     <td><strong>{!! $OrderRs->return_shipping_label !!}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->refund_amount > 0)
				                  <tr>
				                     <td>Refund Amount :</td>
				                     <td><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->refund_amount }}</strong></td>
				                  </tr>
				                  @endif
				               </table>
				            </div>
				            <div class="col-md-4 pt-2">
							<a href="{{config('global.SITE_URL')}}return-exchange-policy.html" class="btn btn-secondary btn-block mt-3">Returns Information</a>
							@if(Session::get('sess_useremail') == '11gequaldev@gmail.com' && $OrderRs->pay_status =='Paid' && $OrderRs->route_shipping_insurance_charge >0 && !in_array($OrderRs->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxClaimOrderTrackOrder('{{$OrderRs->orders_id}}',@if(isset($quantity) && $quantity > 0) '{{$quantity}}' @else '0' @endif,@if(isset($orders_detail_id)) '{{$orders_detail_id}}' @else '{{$orderDetailId}}' @endif,'{{$OrderRs->bill_first_name}}','{{$OrderRs->bill_last_name}}','{{$OrderRs->bill_email}}','{{$OrderRs->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
							@elseif($OrderRs->days <= 60 && $OrderRs->pay_status =='Paid' && $OrderRs->ship_status =='Shipped' && $OrderRs->route_shipping_insurance_charge >0 && !in_array($OrderRs->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxClaimOrderTrackOrder('{{$OrderRs->orders_id}}',@if(isset($quantity) && $quantity > 0) '{{$quantity}}' @else '0' @endif,@if(isset($orders_detail_id)) '{{$orders_detail_id}}' @else '{{$orderDetailId}}' @endif,'{{$OrderRs->bill_first_name}}','{{$OrderRs->bill_last_name}}','{{$OrderRs->bill_email}}','{{$OrderRs->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
							@elseif(in_array($OrderRs->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxClaimedOrder('{{$OrderRs->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claimed </a>
							@endif
							
							@if(Session::get('sess_useremail') == '11gequaldev@gmail.com' || $OrderRs->bill_email == '11gequaldev@gmail.com')
							@if(isset($trackorder) && $trackorder == 'trackorder')
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxReturnOrderNew('{{$OrderRs->orders_no}}','{{$OrderRs->bill_email}}', 'trackorder');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
							@else
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxReturnOrderNew('{{$OrderRs->orders_no}}','{{$OrderRs->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
							@endif
							@endif

							<a href="{{config('global.SITE_URL')}}order-detail-pdfs/{{$OrderRs->orders_id}}-{{$OrderRs->customer_id}}.html" class="btn btn-sm btn-primary mt-3 btn-block">Invoice</a>							
							
							@if((isset($is_returnable) && $is_returnable == 'yes'))
							@if(isset($trackorder) && $trackorder == 'trackorder')
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxReturnOrderNew('{{$OrderRs->orders_no}}','{{$OrderRs->bill_email}}', 'trackorder');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
							@else
							<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxReturnOrderNew('{{$OrderRs->orders_no}}','{{$OrderRs->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
							@endif
							@endif
							@if($OrderRs->status == "Pending")
								<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxCancelTrackOrder('{{$OrderRs->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Cancel Order</a>
							@endif
							@if($OrderRs->status == "Claim In Review - Police Report Required")
								<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxClaimPoliceReportOrder('{{$OrderRs->orders_id}}','Y');" rel="nofollow">Upload Police Report</a>
								@if(isset($policeReportAttach) && $policeReportAttach != '')<a href="{{$policeReportAttach}}" class="btn btn-secondary btn-block mt-3" target="_blank">View Police Report</a>@endif
							@endif
							@if($OrderRs->status == "Claim In Review - Pictures / Videos Required")
								<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3 btn-block" onclick="DisplayPopupBoxClaimPoliceReportOrder('{{$OrderRs->orders_id}}','N');" rel="nofollow">Upload Pictures / Videos</a>
								@if(isset($pic_arr) && !empty($pic_arr))	
									<a href="javascript:void(0);" class="btn btn-secondary btn-block mt-3" onclick="javascript:$('#view_links').toggle();">View Pictures / Videos</a>
									<span id="view_links" style="display: none;">
									@php
									$p = 1;	@endphp
									@foreach($pic_arr as $pic)										
										<a href="{{$pic}}" target="_blank"><small class="pt-1 d-block">View File {{$p}}</small></a>
										@php
										$p++; @endphp
									@endforeach
									</span>
								@endif
							@endif
							
					</div>
				         </div>
				      </div>
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
				            	@if(count($OrderDetailRs) > 0)
				            	@foreach($OrderDetailRs as $order_details_key => $order_details_value)
				               <tr>
				                  <td data-title="full center" class="text-left">
				                     <div class="row row5">
				                        <div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left"><a href="#"><img src="{{$order_details_value->Image}}" alt=""></a></div>
				                        <div class="col-lg-8 col-md-7 col-sm-6 text-center text-sm-left pt-2 pt-sm-0"> 
											<a href="#"><strong>{!! remove_html_entities($order_details_value->product_name) !!}</strong></a><br>
											Item SKU: {{ $order_details_value->sku }}
											@if($order_details_value->IsGiftCertificateItem == 'Yes')
												<br>Sender Name: {{$order_details_value->SenderName}}
												<br>Sender Email: {{$order_details_value->SenderEmail}}
												<br>Recipient Name : {{$order_details_value->RecipientName}}
												<br>Recipient Email : {{$order_details_value->RecipientEmail}}
											@endif
											<br>
											@if(in_array($order_details_value->ostatus, ['Return Requested','Return Approved','Return Rejected','Item Received']))
												{{$order_details_value->ostatus}}
											@endif
											{{--
											<br>											
											@if($OrderRs->bill_email == '11gequaldev@gmail.com' || $order_details_value->is_free_gift_products !='Yes' && $OrderRs->days <= 60 && $OrderRs->ship_status =='Shipped' && in_array($OrderRs->status, ['Completed','Return Requested','Return Approved','Return Rejected','Item Received']) && in_array($order_details_value->ostatus,['Pending','Completed']))
											@if(isset($trackorder) && $trackorder == 'trackorder')
											<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3" onclick="DisplayPopupBoxReturnTrackOrder('{{$OrderRs->orders_id}}','{{$order_details_value->quantity}}','{{$order_details_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a><br>
											@else
											<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3" onclick="DisplayPopupBoxReturnOrder('{{$OrderRs->orders_id}}','{{$order_details_value->quantity}}','{{$order_details_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a><br>
											@endif
											<!--<a href="javascript:void(0);" class="btn btn-sm btn-primary mt-3" onclick="DisplayPopupBoxReturnTrackOrder('{{$OrderRs->orders_id}}','{{$order_details_value->quantity}}','{{$order_details_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a><br>-->
											@endif
											--}}
											
											@if($order_details_value->return_shipping_label !='')
											<strong>{!! $order_details_value->return_shipping_label !!}</strong>
											@endif
											@if($order_details_value->return_label != '')
											<br><strong><a class="btn btn-sm btn-primary mt-3" href="{{ $order_details_value->return_label }}" target="_blank">Return Label </a></strong>
											@endif
	
				                        </div>
				                     </div>
				                  </td>
									<td data-title="Gift Wrap">
										@if($order_details_value->is_gift_wrap != '')
											{{ $order_details_value->is_gift_wrap }}
										@else
											No
										@endif
									</td>
				                  <td data-title="Shipment Status" style="width: 24%">
				                  	@if($order_details_value->IsGiftCertificateItem == 'No')
										<p>{{$order_details_value->dtl_ship_status}}</p>
									@endif
									@php
									if($order_details_value->dtl_tracking_no!='' && $order_details_value->IsGiftCertificateItem == 'No') {
										$track_url = 'javascript:void(0);';
										if($order_details_value->dtl_ship_method=='USPS') {
											$track_url = 'https://tools.usps.com/go/TrackConfirmAction.action?tRef=fullpage&tLc=1&tLabels='.$order_details_value->dtl_tracking_no;
										} elseif($order_details_value->dtl_ship_method=='FedEx') {
											$track_url = 'https://www.fedex.com/fedextrack/?trknbr='.$order_details_value->dtl_tracking_no;
										}  elseif($order_details_value->dtl_ship_method=='UPS') {
											$track_url = config('global.SITE_URL').'tracking/'.$order_details_value->dtl_tracking_no;
										}
										
										echo '<p>Via: <strong>'.$order_details_value->dtl_ship_method.'</strong></p>';
										echo '<p>Tracking: <strong><a href="'.$track_url.'" target="_blank">'.$order_details_value->dtl_tracking_no.'</a></strong></p>';
									}
									@endphp
									@if($GC_Only == 1)
									   -
									@endif
				                  </td>
				                  <td data-title="Unit price">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_details_value->price}}</td>
				                  <td data-title="Quantity">{{$order_details_value->quantity}}</td>
				                  <td data-title="Total Price" class="text-md-right text-left">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_details_value->total}}</td>
				               </tr>
				               @endforeach
				               @else
				               No items found!
				               @endif
				            </tbody>
				         </table>
				      </div>
				      <div class="deliv_dtl">
				         <div class="row row5">
				            <div class="col-md-4 order-md-2 pt-3">
				               <table class="st_table">
				                  <tr>
				                     <td width="48%">Subtotal:</td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->sub_total}}</strong></td>
				                  </tr>
								  @if($OrderRs->shipping_amt > 0)
				                  <tr>
				                     <td>Shipping:</td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->shipping_amt}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->shipping_signature > 0)
				                  <tr>
				                     <td>Shipping Signature: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->shipping_signature}}</strong></td>
				                  </tr>
				                  @endif
								  @if($OrderRs->route_shipping_insurance_charge > 0)
				                  <tr>
				                     <td>Shipping Insurance Charge: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->route_shipping_insurance_charge}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->tax > 0)
				                  <tr>
				                     <td>Sales Tax: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->tax}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->gift_charge > 0)
				                  <tr>
				                     <td>Gift Wrapping Charge: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->gift_charge}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->auto_discount > 0)
				                  <tr>
				                     <td>Auto Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->auto_discount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->quantity_discount > 0)
				                  <tr>
				                     <td>Quantity Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->quantity_discount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->coupon_amount > 0)
				                  <tr>
				                     <td>Coupon Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->coupon_amount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->gc_amount > 0)
				                  <tr>
				                     <td>Gift Certificate Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->gc_amount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->refer_amount > 0)
				                  <tr>
				                     <td>Refer Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->refer_amount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->reward_discount > 0)
				                  <tr>
				                     <td>Reward Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->reward_discount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->bogo_discount > 0)
				                  <tr>
				                     <td>Bogo Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->bogo_discount}}</strong></td>
				                  </tr>
				                  @endif
				                  @if($OrderRs->apply_credit > 0)
				                  <tr>
				                     <td>Credit Discount: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->apply_credit}}</strong></td>
				                  </tr>
				                  @endif
				                  <tr>
				                     <th>Order Total</th>
				                     <th class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->order_total}}</strong></th>
				                  </tr>
				               </table>
				            </div>
				            <div class="col-md-8 order-md-1 pt-3">
				               <div class="row">
				                  <div class="col-md-12 pb-2">
				                     <h3>Delivery Details</h3>
				                  </div>
				                  <div class="col-sm-4 yline mt-2">
									<div class="pb-2"><strong class="ttu">Estimated Delivery:</strong><br />
				                        @if(DateTime::createFromFormat('Y-m-d H:i:s', $OrderRs->EstimatedDeliveryDate) !== false)
											{{date('m/d/Y H:i',strtotime($OrderRs->EstimatedDeliveryDate))}}
										@else 
											N/A
										@endif
				                     </div>
				                     <strong>Delivery Method:</strong><br />
										@if($OrderRs->ship_method != '')
											{{ $OrderRs->ship_method }}
										@else
											N/A
										@endif
				                  </div>
				                  @if($GC_Only == 0)
				                  <div class="col-sm-4 yline mt-2"> <strong class="ttu">Shipping to:</strong><br />
									  {{$OrderRs->ship_first_name}} {{$OrderRs->ship_last_name}}<br />
									  {{$OrderRs->ship_address1}}, {{$OrderRs->ship_address2}}<br />
									  {{$OrderRs->ship_city}} -  {{$OrderRs->ship_zip}}<br />
									  {{$OrderRs->ship_state}} ,{{$OrderRs->ship_country}}
				                  </div>
				                  @endif
				                  <div class="col-sm-4 mt-2 "> <strong class="ttu">Billing to:</strong><br />
		                     		{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}<br />
								  	{{$OrderRs->bill_address1}}, {{$OrderRs->bill_address2}}<br />
								  	{{$OrderRs->bill_city}} - {{$OrderRs->bill_zip}}<br />
								  	{{$OrderRs->bill_state}}, {{$OrderRs->bill_country}}
				                  </div>
				                  @if($OrderRs->gift_from !='' || $OrderRs->gift_to !='' || $OrderRs->gift_message_customer !='' || $OrderRs->free_gift!="")
				                  <div class="col-sm-4 mt-2 "> <strong class="ttu">Billing to:</strong><br />
		                     		@if($OrderRs->gift_from !='')
		                     			<p><strong>Gift From: </strong>{{$OrderRs->gift_from}}</p>
		                     		@endif
		                     		@if($OrderRs->gift_to !='')
		                     			<p><strong>Gift To: </strong>{{$OrderRs->gift_to}}</p>
		                     		@endif
		                     		@if($OrderRs->gift_message_customer !='')
		                     			<p><strong>Customer Message: </strong>{{$OrderRs->gift_message_customer}}</p>
		                     		@endif
		                     		@if($OrderRs->free_gift !='')
		                     			<p><strong>Got a Free Gift Of {{$OrderRs->free_gift}}</strong></p>
		                     		@endif
				                  </div>
				                  @endif
				               </div>
				            </div>
				         </div>
				         <div class="row row5">
				            <div class="col-md-4 pt-4 offset-md-8">
				               <h3>Need Help? Contact Us</h3>
				               <ul class="help">
				                  <li>
				                     <img class="float-left pt-1" src="{{config('global.SITE_IMAGES')}}sv_phon.png" width="23" height="23" alt="" />
				                     <div> <a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Toll Free),<br />
										 <a href="tel:{{config('Settings.INTERNATIONAL_PHONE_NO')}}">{{config('Settings.INTERNATIONAL_PHONE_NO')}}</a> (Phone)<br />
										 <a href="tel:{{config('Settings.TEXT_NO')}}">{{config('Settings.TEXT_NO')}}</a> (Text US) 
				                     </div>
				                  </li>
				                  <li>
				                     <img class="float-left" src="{{config('global.SITE_IMAGES')}}sv_email_opan.png" width="23" height="19" alt="" />
				                     <div><a href="mailto:{{config('Settings.ADMIN_MAIL')}}">{{config('Settings.ADMIN_MAIL')}}</a></div>
				                  </li>
				               </ul>
				            </div>
				         </div>
				      </div>
				   </div>
				</div>

			</div>
		</div>
	</div>
</main>	

@endsection
