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
                            @if($MainOrder->is_only_gc == 0 &&isset($MainOrder->order_type) && $MainOrder->order_type!= 'Store')
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
@endsection
