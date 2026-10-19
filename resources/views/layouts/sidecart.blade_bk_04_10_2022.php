<div class="sidepanel" id="cart-open"> 
	<a href="javascript:void(0);" class="close">
		<svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
			<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
		</svg>
	</a>
	<div class="p-4 border-bottom">
		<h4 class="text-center">Your Cart</h4>
	</div>
	<div class="sp-header" id="shopcart">
		@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
		<div class="text-right"> 
			<a href="javascript:void(0);" id="clear-bag" class="more-link">Clear Your Cart</a> 
		</div>
		@endif
		<div class="sp-content">
			@if(Session::has('CartErrors') && count(Session::get('CartErrors')) > 0)
				@foreach(Session::get('CartErrors') as $ekey => $CartError)
				<x-message :attr="[
					'classname' => 'alert alert-danger', 
					'message' => $CartError ]"
				/>
				@endforeach
			@endif
			<div class="sp-inner sp-basket">
				<ol class="items empty-items" style="
						@if(!isset($CartDetails['Cart']) || (isset($CartDetails['Cart']) && count($CartDetails['Cart']) == 0 )) display: block; @else display: none; @endif">
					<li>
						<p>Your Basket is Empty!</p>
						<p><a href="{{config('global.SITE_URL')}}" class="btn_link action primary btn-full">Continue Shopping</a></p>
					</li>
				</ol>
				<ol class="items">
					@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
						@foreach($CartDetails['Cart'] as $key => $CartItem)
						<li class="item">
							<div class="product">
								<div class="product-thumb">
									<a href="{{$CartItem['Prod_URL']}}" tabindex="0">
										{!!$CartItem['image_forpopup']!!}
									</a>
								</div>
								<div class="product-name" style="min-height:60px;"> 
									<a href="{{$CartItem['Prod_URL']}}" tabindex="0"> 
										<strong>{{$CartItem['ProductName']}}</strong>
									</a> 
									@if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
										<div>{!! $CartItem['short_description'] !!}</div>
									@endif
								</div>
                                @if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
                                    <div class="yotpo-product pb-2" style="font-size:11px;color:#000;"> 
                                        Free Product Reward - <strong>Yotpo</strong>
                                    </div>
                                @endif
								<div class="product-price">
									@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
										<div class="price">{{$CartItem['Qty']}} X {{Price(0.00)}}</div>
									@else 
										@if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
											<div class="price">{{$CartItem['Qty']}} X {{Price($CartItem['Price'])}}</div>
										@else 	
											<div class="price">{{$CartItem['Qty']}} X {{Price($CartItem['ItemPrice'])}}</div>
										@endif
									@endif
									@if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
									@else
									<a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" > Remove </a>
									@endif
								</div>
							</div>	
						</li>
						@endforeach
					@endif	
				</ol>				
				@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0)
				<div class="sp-bottom">
					<p><span class="price-text d-flex align-items-center justify-content-between">CART SUBTOTAL: <strong id="subtotal">@if(isset($CartDetails['SubTotal'])) {{Price($CartDetails['SubTotal'])}} @else 0.00 @endif</strong></span></p>
					<p><a href="{{url('shoppingcart')}}" class="btn btn-checkout d-block f14">View Cart & Checkout</a></p>
					{{--
					<div id="SidePaymethods">
						@if($CartDetails['IsPaypalExpressCheckout'] == 'Yes')
						<div id="paypal-button-container" class="pb-2 mb-1"> 
							<a class="paypal-btn" href="{{url('paypal/placeorder')}}"> 
								<img alt="Paypal" src="{{config('global.SITE_IMAGES')}}paypal-cart.png" class="img-fluid"> 
							</a> 
						</div>
						@endif
						
						@if($CartDetails['Amazon_pay_Checkout'] == 'Yes')
						<div>
							<div id="AmazonPayButtonAll"></div>
						</div>
						@endif
					</div>
					<p><a href="{{url('shoppingcart')}}" class="btn_link">View Your Cart</a></p>
					--}}
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
