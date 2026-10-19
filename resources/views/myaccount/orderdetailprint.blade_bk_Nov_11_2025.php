@extends('layouts.printapp')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-12 col-md-12 col-xs-12">
	               <div class="myact_hd"> 
	               	<img src="{{ config('global.SITE_IMAGES') }}logo.jpg" class="logo-print">
	               </div>
	               <div class="myorder_dtl">
	               <!-- <div class="myact_subhd">Order Details</div> -->
	                  <div class="wlc_msg">
	                     <div class="row">
	                        <div class="col-md-8 pt-2 font_ssp"><strong>Order# {{ $OrderRs->orders_no }}</strong></div>
	                        <div class="col-md-4 pt-2 text-left text-md-right hiderow"><a href="javascript:printpreview();" class="font_ssp print_link"><svg class="sv-print" aria-hidden="true" role="img">
	                           <use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
	                           </svg>Print</a>
	                       	</div>
	                     </div>
	                  </div>
				      <div class="order_info">
				         <div class="row row5">
				            <div class="col-md-8 pt-2">
				               <table>
				                  <!-- <tr>
				                     <td>Order Number :</td>
				                     <td><strong>{{ $OrderRs->orders_no }}</strong></td>
				                  </tr> -->
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
				                  @if($OrderRs->refund_amount > 0)
				                  <tr>
				                     <td>Refund Amount :</td>
				                     <td><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->refund_amount }}</strong></td>
				                  </tr>
				                  @endif
				                  <tr>
				                     <td>Billing Address :</td>
				                     <td><strong>{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}, 
								  	{{$OrderRs->bill_address1}}, {{$OrderRs->bill_address2}}, 
								  	{{$OrderRs->bill_city}} - {{$OrderRs->bill_zip}}, 
								  	{{$OrderRs->bill_state}}, {{$OrderRs->bill_country}}</strong></td>
				                  </tr>
				                  @if($GC_Only == 0)
				                  <tr>
				                     <td>Shipping Address :</td>
				                     <td><strong>{{$OrderRs->ship_first_name}} {{$OrderRs->ship_last_name}}, 
									  {{$OrderRs->ship_address1}}, {{$OrderRs->ship_address2}}, 
									  {{$OrderRs->ship_city}} -  {{$OrderRs->ship_zip}}, 
									  {{$OrderRs->ship_state}}, {{$OrderRs->ship_country}}</strong></td>
				                  </tr>
				                  @endif

				               </table>
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
				                        <div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left"><a href="#"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$order_details_value->Image}}" width="185" height="185" alt=""></a></div>
				                        <div class="col-lg-8 col-md-7 col-sm-6 text-center text-sm-left pt-2 pt-sm-0"> <a href="#"><strong>{!! $order_details_value->product_name !!}</strong></a><br>Item SKU : {{ $order_details_value->sku }}
				                        </div>
				                     </div>
				                  </td>
				                  <td data-title="Gift Wrap">{{ $order_details_value->is_gift_wrap }}</td>
				                  <td data-title="Shipment Status">
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
										} elseif($order_details_value->dtl_ship_method=='UPS') {
											$track_url = config('global.GIFT_CERTIFICATE_SKU').'index.php?file=tracking_single&trackingno='.$order_details_value->dtl_tracking_no;
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
				                  <!-- <div class="col-md-12 pb-2">
				                     <h3>Delivery Details</h3>
				                  </div>
				                  <div class="col-sm-4 yline mt-2">
				                     <div class="pb-2"><strong class="ttu">Estimated Delivery:</strong><br />
				                        Monday 26th March 2020
				                     </div>
				                     <strong>Delivery Method:</strong><br />
				                     Standard Shipping
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
				                  </div> -->
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
				      </div>
	               </div>

				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
