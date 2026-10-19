@extends('layouts.app')
@section('content')
<main>
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
									<p>Your order number is <strong class="color-red">{{$MainOrder->orders_no}}</strong></p>
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
								</div>
								<div class="confm-bottom w-100 text-center">
									<a href="#" class="mt-3 d-block">
										<!--<img src="{{config('global.SITE_IMAGES')}}cofim-banner.png" alt="" class="img-fluid">-->
										<img src="{{$order_receipt_image}}" alt="" class="img-fluid">
									</a>
									<div class="chekout-main-btn mt-3">
										<div class="row">
											<div class="col-12">
												<a class="btn btn-primary d-block checkout-btn" href="{{url('/')}}">Continue shopping</a>
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
										<a href="{{url('/order-detail-pdf/'.$MainOrder->orders_id.'.html')}}" class="print-btn ml-2">
										<svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
											<use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
										</svg> Print
									</a>
									</div>
								</span>
							</div>
							<div class="actmenu-inner your-bag-inner mt-3" id="cart_items">
								<ul>
									@foreach($OrderDetails as $key => $Order)
									<li>
										<div class="row">
											<div class="col-4 col-sm-3">
												<img alt="" src="{{ config('global.SPEED_SIZE_URL')}}{{$Order->Image}}" class="product-img img-fluid">
											</div>
											<div class="col-8 col-sm-6">
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

						<div class="order-summry mt-4">
							<div class="row"> 
								<div class="col-12">
									<table class="table text-left table-border-none table-borderless mb-0">
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
												<td>Order Total</td>
												<td class="text-right">{{Price($MainOrder->order_total)}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="small-address d-none d-md-inline-block w-100">
							<ul>
								@if($MainOrder->is_only_gc == 0)
								<li>
									<h4 class="mb-1 mt-3">Shipping to:</h4>
									<p class="mb-0">
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
								<li>
									<h4 class="mb-1 mt-3">Billing to:</h4>
									<p class="mb-0">
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
								
								<li>
									<h4 class="mb-1 mt-3">Payment Method:</h4>
									<p class="mb-0">{{$MainOrder->payment_method}}</p>
								</li>
								
								@if($MainOrder->is_only_gc == 0 || $MainOrder->payment_type == 'PAYMENT_PAYPALPROD')
								<li>
									<h4 class="mb-1 mt-3">Shipping Method:</h4>
									<p class="mb-0">{!!$MainOrder->fullshipping_info!!}</p>
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
@endsection
