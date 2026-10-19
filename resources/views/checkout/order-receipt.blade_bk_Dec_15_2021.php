@extends('layouts.app')
@section('content')
<main>
	<section class="checkout-page order-confirm pt-md-5 pb-md-5">
		<div class="container">
			@if(isset($roktstr) && $roktstr != '')
				<div id="rokt-placeholder"> 
					<iframe aria-hidden="true" src="https://apps.rokt.com/wsdk/preload/index.html" sandbox="allow-scripts allow-same-origin" style="border: 0px; width: 100%; display: none;"></iframe>
				</div>
			@endif
			<div class="mainhd text-left pt-4 pb-4 d-md-none">
				<h2>Order Receipt</h2>
			</div>
			<div class="row">
				<div class="col-lg-8">
					<div class="row pr-lg-4 pr-0">
						<div class="col-12">
							<div class="confm-top d-none d-md-inline-block w-100">
								<h3 class="cart-hd mb-2">Hooray, your order is placed! Order Receipt!</h3>
								<p>Your order number is {{$MainOrder->orders_no}}</p>
								@if($Payment_Method_Message != '')
								{!! $Payment_Method_Message !!}	
									@if($wholesale_terms!="")
										{!! $wholesale_terms !!}
									@endif
								@else
									<p>A confirmation email is headed your way at {{$MainOrder->bill_email}}.
									@if($wholesale_terms!="")
										{!! $wholesale_terms !!}
									@endif
									</p>
								@endif
							</div>
							<div class="confm-top d-md-none text-center">
								@if($Payment_Method_Message != '')
									{!! $Payment_Method_Message !!}	
									@if($wholesale_terms!="")
										{!! $wholesale_terms !!}
									@endif
								@else
									<h3 class="cart-hd mb-2 color-green">Thank you for placing your order.</h3>
									<p class="p-3">We hope you enjoyed shopping with us. Your order will be processed as soon as possible. We will contact you with updates. Please allow 24hrs to process the payment. An E-mail Confirmation will be sent upon payment received.
										@if($wholesale_terms!="")
											{!! $wholesale_terms !!}
										@endif
									</p>
								@endif
								<h6><strong>Your Order Number : <span class="color-red">{{$MainOrder->orders_no}}</span></strong></h6>
							</div>

							<div class="confm-middle d-md-none">
								<h3 class="cart-hd mb-3">
									<span class="order-date float-left">Date: {{date('m/d/Y h:i:s',$MainOrder->order_datetime)}}</span>
									<a href="{{url('/order-detail-pdf/'.$MainOrder->orders_id.'.html')}}" class="print-btn float-right">
										<svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
											<use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
										</svg>
									</a>
								</h3>
								<div class="your-bag">
									<div class="your-bag-inner">
										<ul>
											@foreach($OrderDetails as $key => $Order)
											<li>
												<div class="row">
													<div class="col-4 col-sm-3">
														<img alt="" src="{{$Order->Image}}" class="product-img img-fluid">
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
							</div>

							<div class="confm-bottom d-none d-md-inline-block w-100 text-center">
								<a href="#" class="mb-3">
									<img src="{{config('global.SITE_IMAGES')}}cofim-banner.png" alt="" class="img-fluid">
								</a>
								<a class="max_continuesp_btn btn btn-primary mt-3" href="{{url('/')}}">continue shopping</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 mt-4 mt-md-0">
					<h3 class="cart-hd mb-3 d-none d-md-inline-block w-100">
						<span>Order Receipt</span>
						<div class="float-right">
							<span class="order-date">Date: {{date('m/d/Y h:i:s',$MainOrder->order_datetime)}}</span>
							<a href="{{url('/order-detail-pdf/'.$MainOrder->orders_id.'.html')}}" class="print-btn">
								<svg class="sv-print vam" aria-hidden="true" role="img" width="14" height="14">
									<use href="#sv-print" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-print"></use>
								</svg> Print
							</a>
						</div>
					</h3>
					<div class="your-bag d-none d-md-inline-block w-100">
						<div class="your-bag-inner">
							<ul>
								@foreach($OrderDetails as $key => $Order)
								<li>
									<div class="row">
										<div class="col-4 col-sm-3">
											<img alt="" src="{{$Order->Image}}" class="product-img img-fluid">
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

					<div class="order-summry">
						<div class="row"> 
							<div class="col-12">
								<table class="table text-left table-border-none table-borderless">
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
											<td class="border-top">Order Total</td>
											<td class="text-right border-top">{{Price($MainOrder->order_total)}}</td>
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
								<h4 class="mb-2">Shipping to:</h4>
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
								<h4 class="mb-2">Billing to:</h4>
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
								<h4 class="mb-2">Payment Method:</h4>
								<p class="mb-0">{{$MainOrder->payment_method}}</p>
							</li>
							
							@if($MainOrder->is_only_gc == 0)
							<li>
								<h4 class="mb-2">Shipping Method:</h4>
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

					<div class="max_need_help mt-4">
						<h4 class="">Need Help? <a href="{{url('contact-us.html')}}" class="">Contact Us</a></h4>
						<ul>
							<li>
								<img src="{{config('global.SITE_IMAGES')}}phon.jpg" alt="" class="left">
								<small class="">
									<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Toll Free), <a href="tel:{{config('Settings.INTERNATIONAL_PHONE_NO')}}">{{config('Settings.INTERNATIONAL_PHONE_NO')}}</a> (Outside of USA)<br>
								</small>
							</li>
							<li>
								<svg class="svg-whatsapp vam left" aria-hidden="true" role="img" width="20" height="20">
									<use href="#svg-whatsapp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-whatsapp"></use>
								</svg>
								<small class="">
									<a href="tel:{{config('Settings.TOLL_FREE_NO')}}">{{config('Settings.TOLL_FREE_NO')}}</a> (Text Number)
								</small>
							</li>
							<li>
								<img src="{{config('global.SITE_IMAGES')}}email.jpg" alt="" class="left"><small><a href="mailto:{{config('Settings.ADMIN_MAIL')}}" class="grey_text">{{config('Settings.ADMIN_MAIL')}}</a></small>
							</li>
						</ul>
					</div> 

					<div class="chekout-main-btn">
						<div class="row">
							<div class="col-12 mt-md-0">
								<a class="btn btn-secondary d-block checkout-btn" href="{{url('/')}}">Continue</a>
							</div>
						</div>
					</div>

				</div> 
			</div>
		</div>
	</section>
</main>
@endsection