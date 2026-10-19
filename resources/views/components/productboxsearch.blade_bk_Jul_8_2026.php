<?php

$prodData->sale_item = '0';
if(isset($prodData->sale_price) && $prodData->sale_price > 0 && Auth::user() && Auth::user()->eusertype == 'Wholesaler')
{
	$prodData->sale_item = '1';
}

?>

<div class="product {{$sliderClass}}" role="group" aria-label="Product {{ $prodData->name }}">
	<a href="{{$prodData->product_url}}" class="p-thumb" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" aria-label="View details for {{ $prodData->name }}">
		<img src="{{$prodData->imageUrl}}" alt="{{$prodData->name}}" title="{{$prodData->name}}" />
		<span class="cornar">
		@if(isset($prodData->website_stock) && $prodData->website_stock > 0 && $prodData->max_two_day_delivery == "Yes" && (isset($prodData->is_deal_of_week) && $prodData->is_deal_of_week == "No"))
			<img src="{{config('global.SITE_IMAGES')}}maxtwoday.png" alt="2 Day Delivery Available" width="44" height="20" />
		@endif
		@if(isset($prodData->is_deal_of_week) && $prodData->is_deal_of_week == "Yes")
			<span class="d-block">
				<svg class="sv sv-topdeal" aria-hidden="true" role="img" width="24" height="24" aria-label="Top Deal">
					<use href="#sv-topdeal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-topdeal"></use>
				</svg>
			</span>
		@endif
		</span>
		@if(isset($prodData->sale_item) && $prodData->sale_item == 1)
			<svg class="sv-sale" aria-hidden="true" role="img" width="100" height="20" aria-label="Sale">
				<use href="#sv-sale" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-sale"></use>
			</svg>
		@endif
	</a>
	@if(isset($prodData->inventory_count) && $prodData->inventory_count > 0)
    <a href="javascript:void(0);" onclick="productquickview('{{$prodData->uid}}')" class="lable-quickview" data-pid="{{$prodData->uid}}" rel="nofollow"  onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" role="button" aria-label="Quick View {{ $prodData->name }}">Quick View</a>
	@endif
	@if(isset($prodData->inventory_count) && $prodData->inventory_count <= 0)
    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodData->uid}}','{{$prodData->sku}}');" class="lable-quickview" data-pid="{{$prodData->uid}}" rel="nofollow"  onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" role="button" aria-label="Notify me when {{ $prodData->name }} is in stock">Notify Me</a>
	@endif
	</span>
	<ul class="product_icon" role="list" aria-label="Product actions">
		<?php if(isset($prodData->gender)){?>
		<!--<li role="listitem">
		<?php
				if ($prodData->gender == 'M'){
					$prodData->gender = "sv-men";
					$prodData->gendernames = "Men";
					$for_gender = ' for Men';
				} elseif ($prodData->gender == 'W'){
					$prodData->gender = "sv-women";
					$prodData->gendernames = "Women";
					$for_gender = ' for Women';
				} elseif ($prodData->gender == 'K'){
					$prodData->gender = "sv-kids";
					$prodData->gendernames = "Kids";
					$for_gender = ' for Kids';
				} elseif ($prodData->gender == 'U'){
					$prodData->gender = "sv-unisex";
					$prodData->gendernames = "Unisex";
					$for_gender = ' Unisex';
				} else{
					$prodData->gender = "";
					$prodData->gendernames = "";
					$for_gender = '';
				}
			?>
			<a href="javascript:void(0);" title="{{$prodData->gendernames}} Item" aria-label="For {{$prodData->gendernames}}">
				<svg class="{{$prodData->gender}} sv-bor" aria-hidden="true" role="img" width="15" height="15">
					<use href="#{{$prodData->gender}}" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#{{$prodData->gender}}"></use>
				</svg>
			</a>
		</li>-->
		<?php }?>
		<li role="listitem">
			<a href="javascript:void(0);" class="svg-h" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('{{$prodData->uid}}','yes');" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" role="button" aria-label="Add {{ $prodData->name }} to wishlist">
				<svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
					<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
				</svg>
				<svg class="sv-heart-fill sv-fill" aria-hidden="true" role="img" width="14" height="14">
					<use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
				</svg>
			</a>
		</li>
		@if(isset($prodData->inventory_count) && $prodData->inventory_count <= 0)
		<li role="listitem">
			<a class="label-cart-outstock" title="Out of stock" href="javascript:void(0);" rel="nofollow" onclick="DisplayPopupBoxAlertMe('{{$prodData->uid}}','{{$prodData->sku}}');"  onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" role="button" aria-label="Out of stock: Notify me for {{ $prodData->name }}">
                <svg class="sv-cart-outstock" aria-hidden="true" role="img" width="14" height="13">
                    <use href="#sv-cart-outstock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart-outstock"></use>
                </svg>
            </a>
        </li>
        @elseif(Auth::user() && Auth::user()->eusertype == 'Wholesaler' && isset($prodData->product_type) && $prodData->product_type == 'retailer')
        @else
        <li role="listitem">
            <a href="javascript:void(0);" title="Add to cart" class="prodaddcart" data-product="{{$prodData->uid}}" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" role="button" aria-label="Add {{ $prodData->name }} to cart">
                <svg class="sv-cartnw vam" aria-hidden="true" role="img" width="15" height="15">
                    <use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
                </svg>
            </a>
        </li>
        @endif
    </ul>
    <div class="product-name" aria-label="Product name">
        <a href="{{$prodData->product_url}}" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})" aria-label="View details for {{ $prodData->name }}">
            <strong><u> {!! stripslashes($prodData->name) !!}</u></strong>
        </a>
    </div>

    <?php
		$prodData->yousave = $prodData->msrp - $prodData->price;
		if($prodData->yousave > 0){
			$prodData->yousave = ($prodData->yousave / $prodData->msrp) * 100;
			$prodData->yousave = (int)$prodData->yousave;
		}
		if(isset($prodData->rating) && $prodData->rating > 0){
			$setRatingAvg = (int)$prodData->rating_avg + 1;
		}
    ?>

    @if(isset($prodData->same_variation_min_price) && isset($prodData->same_variation_max_price) && $prodData->same_variation_min_price != $prodData->same_variation_max_price)
        <div class="minheightprodbox">
			<div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{$prodData->uid}}" data-product-models="{{$prodData->uid}}" data-name="{!! stripslashes($prodData->name) !!}" data-url="{{$prodData->product_url}}" data-image-url="{{ config('global.SPEED_SIZE_URL')}}{{$prodData->imageUrl}}" data-description="{!! stripslashes($prodData->name) !!}">&nbsp;</div>
			<div class="product-price" aria-label="Product price">
				<div class="price pb-1 opstion-avl">
					@if($prodData->same_variation_product_count < 1 || $prodData->same_variation_product_count == 1)
						@if($prodData->same_variation_min_price == $prodData->same_variation_max_price)
							{{Price($prodData->same_variation_min_price)}}
						@else
							{{Price($prodData->same_variation_min_price).' - '.Price($prodData->same_variation_max_price)}}
						@endif
					@endif
					@if($prodData->category_id == 2 && $prodData->same_variation_product_count <= 1)
					<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0" aria-label="Niche Fragrances" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})"> <span class="avl-round" ></span></a>
					@endif
				</div>
			</div>
			@if($prodData->inventory_count > 0)
			<div class="pb-2 product-price" aria-label="Product price">
				<div class="price">
					@if($prodData->same_variation_product_count > 1)
							@if($prodData->same_variation_min_price != $prodData->same_variation_max_price)
								{{Price($prodData->same_variation_min_price).' - '.Price($prodData->same_variation_max_price)}}
							@endif
					@endif
				</div>
			</div>
			@endif
			@if($prodData->category_id == 2)
				<div class="opstion-avl" aria-label="Options available">
					@if($prodData->same_variation_product_count > 1)
						{{$prodData->same_variation_product_count}} Options Available
						<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0" aria-label="Niche Fragrances" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})"> <span class="avl-round" ></span></a>
					@endif
				</div>
			@else
				@if($prodData->same_variation_product_count > 1)
					<div class="opstion-avl" aria-label="Options available">{{$prodData->same_variation_product_count}} Options Available</div>
				@endif
			@endif

			@if($prodData->yousave != '' && $prodData->yousave != 0 && $prodData->yousave > 0 && $prodData->inventory_count > 0 && $prodData->same_variation_product_count <= 1)
				<div class="you-save pb-2 pt-2" aria-label="You save {{$prodData->yousave}} percent">
					You Save: {{$prodData->yousave}}%
				</div>
			@endif
        </div>
    @else
        <div class="minheightprodbox">
			<div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{$prodData->uid}}" data-product-models="{{$prodData->uid}}" data-name="{!! stripslashes($prodData->name) !!}" data-url="{{$prodData->product_url}}" data-image-url="{{ config('global.SPEED_SIZE_URL')}}{{$prodData->imageUrl}}" data-description="{!! stripslashes($prodData->name) !!}">&nbsp;</div>
			@if($prodData->inventory_count > 0)
				<div class="product-price pb-2" aria-label="Product price">
					<div class="price opstion-avl">
						@if(isset($prodData->same_variation_product_count) && ($prodData->same_variation_product_count < 1 || $prodData->same_variation_product_count == 1))
							@if($prodData->same_variation_min_price == $prodData->same_variation_max_price)
								{{Price($prodData->same_variation_min_price)}}
							@else
								{{Price($prodData->same_variation_min_price).' - '.Price($prodData->same_variation_max_price)}}
							@endif
						@endif
						@if($prodData->category_id == 2 && isset($prodData->same_variation_product_count) && $prodData->same_variation_product_count <= 1)
							<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0" aria-label="Niche Fragrances" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})"> <span class="avl-round" ></span></a>
						@endif
					</div>
				</div>
			@endif
			@if($prodData->inventory_count > 0)
			<div class="product-price" aria-label="Product price">
				<div class="price">
					@if(isset($prodData->same_variation_product_count) && $prodData->same_variation_product_count > 1)
							@if($prodData->same_variation_min_price != $prodData->same_variation_max_price)
								{{Price($prodData->same_variation_min_price).' - '.Price($prodData->same_variation_max_price)}}
							@endif
					@endif
				</div>
			</div>
			@endif
			@if($prodData->category_id == 2)
				<div class="opstion-avl" aria-label="Options available">
					@if(isset($prodData->same_variation_product_count) && $prodData->same_variation_product_count > 1)
						{{$prodData->same_variation_product_count}} Options Available
						<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0" aria-label="Niche Fragrances" onmousedown="return IntelliSuggest.clickItem(this, {siteId: 'faltym', intellisuggestData: '{{$prodData->intellisuggestData}}', intellisuggestSignature: '{{$prodData->intellisuggestSignature}}'})"> <span class="avl-round" ></span></a>
					@endif
				</div>
			@else
				@if(isset($prodData->same_variation_product_count) && $prodData->same_variation_product_count > 1)
					<div class="opstion-avl" aria-label="Options available">{{$prodData->same_variation_product_count}} Options Available </div>
				@endif
			@endif
			@if($prodData->yousave != 0 && $prodData->yousave > 0 && $prodData->inventory_count > 0)
				<div class="you-save" aria-label="You save {{$prodData->yousave}} percent">
					You Save: {{$prodData->yousave}}%
				</div>
			@endif
        </div>
    @endif
</div>
