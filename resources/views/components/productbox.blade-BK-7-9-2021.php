<div class="product {{$sliderClass}}"> 
	<a href="{{$prodData->product_url}}" class="p-thumb">
		<img src="{{$prodData->prod_image}}" alt="{{$prodData->product_name}}" />
		<span class="cornar">
		@if($prodData->WebsiteStock =='In' && $prodData->maxtwodaydelivery == "Yes")
			<img src="{{config('global.SITE_IMAGES')}}2day.png" alt="" />
		@endif
		</span>	
		@if(isset($prodData->deal_price) && $prodData->deal_price > 0)
		<svg id="sv-topdeal" viewBox="0 0 130 28" aria-hidden="true" role="img" width="100" height="20">
			<defs><style>.cls-1{fill:#ed2224;}.cls-2{font-size:16px;fill:#fff;font-family:Montserrat-Medium, Montserrat;font-weight:500;letter-spacing:0.02em;}.cls-3{letter-spacing:0.02em;}.cls-4{letter-spacing:0.02em;}</style></defs><polygon class="cls-1" points="116 28 0 28 0 0 130 0 116 28"/><text class="cls-2" transform="translate(11.88 20.17)">T<tspan class="cls-3" x="9.63" y="0">OP DEA</tspan><tspan class="cls-4" x="76.98" y="0">L</tspan><tspan class="cls-3" x="86.77" y="0">S</tspan></text>
		</svg>
		@endif
		@if(isset($prodData->sale_item) && $prodData->sale_item == 1)
			<svg class="sv-sale" aria-hidden="true" role="img" width="100" height="20">
				<use href="#sv-sale" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-sale"></use>
			</svg>
		@endif
	</a>
	@if($prodData->stock == 'In')
    <a href="javascript:void(0);" onclick="productquickview('{{$prodData->products_id}}')" class=" lable-quickview" data-pid="14160" rel="nofollow">Quick View</a>
	@endif
	@if($prodData->stock == 'Out')
    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodData->products_id}}','{{$prodData->sku}}');" class="lable-quickview" data-pid="14160" rel="nofollow">Notify Me</a>
	@endif
	</span>	
	<ul class="product_icon">
		<li>
			<a href="#"  title="{{$prodData->gendernames}} Item">
				<svg class="{{$prodData->gender}}" aria-hidden="true" role="img" width="8" height="14">
					<use href="#{{$prodData->gender}}" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#{{$prodData->gender}}"></use>
				</svg>
			</a>
		</li>
		<li>
			<a href="javascript:void(0);" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('{{$prodData->products_id}}','yes');">
				<svg class="sv-heart" aria-hidden="true" role="img" width="14" height="13">
					<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
				</svg>
			</a>
		</li>
		@if($prodData->stock == 'Out')
		<li>
			<a class="label-cart-outstock" title="Out of stock" href="javascript:void(0);" rel="nofollow" onclick="DisplayPopupBoxAlertMe('{{$prodData->products_id}}','{{$prodData->sku}}');">
    				<svg class="sv-cart-outstock" aria-hidden="true" role="img" width="14" height="13">
						<use href="#sv-cart-outstock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart-outstock"></use>
					</svg>
			</a>
		</li>
		@elseif(Auth::user() && Auth::user()->eusertype == 'Wholesaler' && $prodData->product_type == 'retailer')
		@else 	
		<li>	
			<a href="javascript:void(0);" title="Add to cart" class="prodaddcart" data-product="{{$prodData->products_id}}">
				<svg class="sv-cart vam" aria-hidden="true" role="img" width="14" height="13">
					<use href="#sv-cart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart"></use>
				</svg>
			</a>	
		</li>	
		@endif
	</ul>
	<div class="product-name">
		{!! $prodData->referencedName !!}
		@if($prodData->vtype != '' && isset($prodData->size_cnt) && $prodData->size_cnt > 1)
			{{substr($prodData->vtype,0,25)}}
		@else
		{{ \Illuminate\Support\Str::limit($prodData->short_description, 21, $end='...') }}
		@endif
	</div>

	@if($prodData->minPrice != $prodData->maxPrice)
		<div class="minheightprodbox">
		<div class="product-price">
			<div class="price pb-1 opstion-avl">
				@if($prodData->size_cnt < 1 || $prodData->size_cnt == 1)
					@if(isset($prodData->deal_price) && $prodData->deal_price > 0) 
						{{Price($prodData->deal_price)}}	
						@if($prodData->minPrice == $prodData->maxPrice)
							<strike>{{Price($prodData->retail_price)}}</strike>
						@else
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
					@else
						@if($prodData->minPrice == $prodData->maxPrice)
							{{Price($prodData->minPrice)}}
						@else
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
					@endif	
				@endif
				@if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
				<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0"> <span class="avl-round" ></span></a>
				@endif
			</div>
		</div>
		
		@if($prodData->category_id == 2)
			<div class="opstion-avl">
				@if($prodData->size_cnt > 1)
					{{$prodData->size_cnt}} Options Available  
					<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0"> <span class="avl-round" ></span></a>
				@endif
			</div>
		@else 
			@if($prodData->size_cnt > 1)
				<div class="opstion-avl">{{$prodData->size_cnt}} Options Available</div>
			@endif		
		@endif
		@if($prodData->yousave != '' && $prodData->yousave != 0 && $prodData->stock == 'In' && $prodData->size_cnt <= 1)
			<div class="you-save pb-2 pt-2">
				You Save: {{$prodData->yousave}}%
				@if(isset($prodData->BrandInSearch) && $prodData->BrandInSearch == 1)
					Off
				@endif
			</div>
		@endif
		<div class="pt-2 product-price">
			<div class="price">
				@if($prodData->size_cnt > 1)
						@if($prodData->minPrice != $prodData->maxPrice)
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
				@endif
			</div>
		</div>	
		</div>				
	@else
		<div class="minheightprodbox">
		@if($prodData->stock == 'In')
			<div class="product-price">
				<div class="price pb-1 opstion-avl">
					@if($prodData->size_cnt < 1 || $prodData->size_cnt == 1)
						@if(isset($prodData->deal_price) && $prodData->deal_price > 0) 
							{{Price($prodData->deal_price)}}	
							@if($prodData->minPrice == $prodData->maxPrice)
								<strike>{{Price($prodData->retail_price)}}</strike>
							@else
								{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
							@endif
						@else
							@if($prodData->minPrice == $prodData->maxPrice)
								{{Price($prodData->minPrice)}}
							@else
								{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
							@endif
						@endif	
					@endif		
					@if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
						<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0"> <span class="avl-round" ></span></a>
					@endif	
				</div>	
			</div>	
		@endif
		
		@if($prodData->category_id == 2)
			<div class="opstion-avl">
				@if($prodData->size_cnt > 1)
					{{$prodData->size_cnt}} Options Available
					<a href="javascript:void(0);" onclick="NicheFragrances()" tabindex="0"> <span class="avl-round" ></span></a>
				@endif
			</div>
		@else 
			@if($prodData->size_cnt > 1)
				<div class="opstion-avl">{{$prodData->size_cnt}} Options Available </div>
			@endif		
		@endif
		@if($prodData->yousave != 0 && $prodData->stock == 'In')
			<div class="you-save pb-2 pt-2">
				You Save: {{$prodData->yousave}}%
				@if(isset($prodData->BrandInSearch) && $prodData->BrandInSearch == 1)
					Off
				@endif	
			</div>
		@endif	
		<div class="pt-2 product-price">
			<div class="price">
				@if($prodData->size_cnt > 1)
						@if($prodData->minPrice != $prodData->maxPrice)
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
				@endif
			</div>
		</div>
		</div>					
	@endif	
</div>
