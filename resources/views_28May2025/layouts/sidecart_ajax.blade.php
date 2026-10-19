<style>
.mini-products-slider .product .product_icon{opacity:1;}
</style>

<!--<link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE_CORE')}}common.css?ver=6666">-->
<link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE_CORE')}}slick.css?ver=66667">

<div class="sp-content">
	@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
	<div class="sp-header-link border-bottom">
		<a href="{{url('shoppingcart')}}" id="view-bag" class="more-link">View Your Cart</a>
		<a href="javascript:void(0);" id="clear-bag" class="more-link">Clear Your Cart</a>
	</div>
	@endif
	@if(Session::has('CartErrors') && count(Session::get('CartErrors')) > 0)
		<div style="padding:8px;">
			@foreach(Session::get('CartErrors') as $ekey => $CartError)
			<x-message :attr="[
				'classname' => 'alert alert-danger', 
				'message' => $CartError ]"
			/>
			@endforeach
		</div>
	@endif
	<div class="sp-inner sp-basket">
		<ol class="items empty-items" style="
				@if(!isset($CartDetails['Cart']) || (isset($CartDetails['Cart']) && count($CartDetails['Cart']) == 0 )) display: block; @else display: none; @endif">
			<li>
				<p>Your Basket is Empty!</p>
				<p><a href="{{url('/')}}" class="btn_link action primary btn-full">Continue Shopping</a></p>
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
						<div class="product-info-right">
							<div class="product-name" style="min-height:60px;"> 
								<a href="{{$CartItem['Prod_URL']}}" tabindex="0"> 
									<strong>{{$CartItem['ProductName']}}</strong>
									@if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
										<div>{!! $CartItem['short_description'] !!}</div>
									@endif
								</a> 
							</div>
							@if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
								<div class="yotpo-product pb-2" style="font-size:11px;color:#000;"> 
									Free Product Reward - <strong>Yotpo</strong>
								</div>
							@endif
							@if(isset($CartItem['FinalSale']) && $CartItem['FinalSale']!='' )
								<div class="yotpo-product pb-2" style="font-size:11px;color:#000;"> 
									{{$CartItem['FinalSale']}}
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
								<!-- <a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" > Remove </a> -->
								@endif
							</div>
						</div>	
					</div>
					<div class="prd-cartbot">
						<a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" > Remove </a>
						<div class="qtybox product-cart-plusminus cart-table">
							@if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes') || (isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes'))
							<input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="1" data-qty="1" min="1" max="3" style="display:none;">
							@else 	
								<button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">-</button>
								<input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="{{$CartItem['Qty']}}" data-qty="{{$CartItem['Qty']}}" min="1" style="width:30%;">
								<button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">+</button>
							@endif	
						</div>
					</div>
				</li>
				@endforeach
			@endif	
		</ol>
		
		
		@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
			<!-- Recommended Products Start -->
			<?php
			if(isset($allSectionRecommendedPrds) && count($allSectionRecommendedPrds) > 0){ 
			for($i=0;$i<count($allSectionRecommendedPrds);$i++){ 
			if(count($allSectionRecommendedPrds[$i]['slider_products']) > 0){ ?>
				<section class="pt-3 pt-lg-4 sectionMiniCart">
					<div class="pt-3 text-center pb-3">
						<div class="text-left">
							<h3><span><?=$allSectionRecommendedPrds[$i]['title'];?></span></h3>
							<!--<a href="javascript:void(0);" class="viewall">See More</a>-->
						</div>
					</div>
					<div class="cover-spin"></div>
					<div class="mini-products-slider">
						 @foreach($allSectionRecommendedPrds[$i]['slider_products'] as $key => $Product)
								<x-productrec :prodData="$Product" sliderClass="slick-slide" />
						@endforeach
					</div>
				</section>	
			<?php
			}
			}
			} ?>
			<!-- Recommended Products End -->
		@endif
		
		
		@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0)
		<div class="sp-bottom">
			<p><span class="price-text d-flex align-items-center justify-content-between">CART SUBTOTAL: <strong id="subtotal">@if(isset($CartDetails['SubTotal'])) {{Price($CartDetails['SubTotal'])}} @else '0.00' @endif</strong></span></p>
			<a href="{{url('checkout')}}" class="btn btn-primary d-block">Proceed To Checkout</a>
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
     		<p><a href="{{url('shoppingcart')}}" class="btn_link">View Cart & Checkout</a></p>
			--}}
		</div>
		@endif
	</div>
</div>
<div id="ViewCartEventCode">

</div>

<script type="text/javascript">
{!! $GA4 ?? '' !!}
{!! $GA4RemoveCartCode ?? '' !!}

</script>

<script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}jquery-3.4.1.min.js?ver=1111112"></script> 
<script src="{{ config('global.SITE_JS_CORE')}}slick.js?ver=666677"></script>
<script src="{{ config('global.SITE_JS_CORE')}}jquery.validate.js?ver=1634928771"></script>
<script src="{{ config('global.SITE_JS_CORE')}}modal.js?ver=1662718081"></script>


<script>

$('.mini-products-slider').on('init', function(event, slick){
	$('.sectionMiniCart').find(".cover-spin").hide();
});

$('.mini-products-slider').slick({
		lazyLoad: 'ondemand',
		dots: true,
		infinite: false,
		speed: 300,
		arrows: true,
        centerMode: true,
        centerPadding: '0',    
		slidesToShow: 1,
		slidesToScroll: 2,
		/*responsive: [
			{ breakpoint: 1600, settings: { slidesToShow: 5, slidesToScroll: 5 } },
			{ breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
			{ breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
			{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } }
		],*/
		nextArrow: '<button class="slick-next round-btn-sl"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
		prevArrow: '<button class="slick-prev round-btn-sl"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
	});
</script>
