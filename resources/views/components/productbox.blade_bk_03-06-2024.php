
<div class="product {{$sliderClass}}" sku="{{$prodData->sku}}"  productname="{{$prodData->product_name}}" brand="{{$prodData->vmanufacture}}" > 
	<a href="{{$prodData->product_url}}" class="p-thumb" title="{{$prodData->product_name}}" onclick="GAEventVal('{{$prodData->sku}}','{{$prodData->product_name}}','{{$prodData->vmanufacture}}');">
		<img src="{{ config('global.SPEED_SIZE_URL')}}{{$prodData->prod_image}}" @if($page == 'listing') @if($prodIndex > 9) loading="lazy" @endif @else loading="lazy" @endif alt="{{$prodData->product_name}}" width="{{getImageWidth(config('global.PRD_THUMB_IMG_PATH').$prodData->image)}}" height="{{getImageHeight(config('global.PRD_THUMB_IMG_PATH').$prodData->image)}}" />
		<span class="cornar">
		@if($prodData->WebsiteStock =='In' && $prodData->maxtwodaydelivery == "Yes" && !isset($prodData->deal_price))
			<img src="{{config('global.SITE_IMAGES')}}maxtwoday.png" loading="lazy" alt="" width="44" height="20" class="pb-2" />
		@endif
		@if(isset($prodData->deal_price) && $prodData->deal_price > 0)
		<span class="d-block">
				<svg class="sv sv-topdeal" aria-hidden="true" role="img" width="24" height="24">
					<use href="#sv-topdeal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-topdeal"></use>
				</svg>
			</span>
		@endif
		</span>
		@if(isset($prodData->sale_item) && $prodData->sale_item == 1)
			<svg class="sv-sale" aria-hidden="true" role="img" width="100" height="20">
				<use href="#sv-sale" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-sale"></use>
			</svg>
		@endif
	</a>
	@if($prodData->stock == 'In')
		@if($prodData->size_cnt > 1)
			<a href="javascript:void(0);" onclick="productquickview('{{$prodData->products_id}}')" class=" lable-quickview" data-pid="{{$prodData->products_id}}" rel="nofollow">Quick View</a>
		@else
			<a href="javascript:void(0);" title="Add to cart" class="prodaddcart lable-quickview" data-product="{{$prodData->products_id}}" tabindex="0">Add to Cart</a>
		@endif
	@endif
	@if($prodData->stock == 'Out')
    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodData->products_id}}','{{$prodData->sku}}');" class="lable-quickview" data-pid="{{$prodData->products_id}}" rel="nofollow">Notify Me</a>
	@endif
	</span>	
	<ul class="product_icon">
		<li>
			<a href="javascript:void(0);" class="svg-h" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('{{$prodData->products_id}}','yes');">
				<svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
					<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
				</svg>
				<svg class="sv-heart-fill sv-fill" aria-hidden="true" role="img" width="14" height="14">
					<use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
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
				<svg class="sv-cartnw vam" aria-hidden="true" role="img" width="15" height="15">
					<use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
				</svg>
			</a>	
		</li>	
		@endif
	</ul>
	<div class="product-name">
		{!! stripslashes($prodData->referencedName) !!}
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
					@if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
						{{Price($prodData->deal_price)}}
						<strike>{{Price($prodData->retail_price)}}</strike>
					@else 	
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
				@endif
				@if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
				<a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0"> <span class="avl-round" ></span></a>
				@endif
			</div>
		</div>
		@if($prodData->stock == 'In')
		<div class="pb-2 product-price">
			<div class="price">
				@if($prodData->size_cnt > 1)
					@if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
						{{Price($prodData->deal_price)}}
						<strike>{{Price($prodData->retail_price)}}</strike>
					@else
						@if($prodData->minPrice != $prodData->maxPrice)
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
					@endif	
				@endif
			</div>
		</div>
		@endif
		@if($prodData->category_id == 2)
			<div class="opstion-avl">
				@if($prodData->size_cnt > 1)
					{{$prodData->size_cnt}} Options Available  
					<a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0"> <span class="avl-round" ></span></a>
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
		</div>				
	@else
		<div class="minheightprodbox">
		@if($prodData->stock == 'In')
			<div class="product-price pb-2">
				<div class="price opstion-avl">
					@if($prodData->size_cnt < 1 || $prodData->size_cnt == 1)
						@if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
							{{Price($prodData->deal_price)}}
							<strike>{{Price($prodData->retail_price)}}</strike>
						@else
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
					@endif		
					@if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
						<a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0"> <span class="avl-round" ></span></a>
					@endif	
				</div>	
			</div>	
		@endif
		@if($prodData->stock == 'In')	
		<div class="product-price">
			<div class="price">
				@if($prodData->size_cnt > 1)
					@if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
						{{Price($prodData->deal_price)}}
						<strike>{{Price($prodData->retail_price)}}</strike>
					@else
						@if($prodData->minPrice != $prodData->maxPrice)
							{{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
						@endif
					@endif	
				@endif
			</div>
		</div>
		@endif
		@if($prodData->category_id == 2)
			<div class="opstion-avl">
				@if($prodData->size_cnt > 1)
					{{$prodData->size_cnt}} Options Available
					<a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances"  tabindex="0"> <span class="avl-round" ></span></a>
				@endif
			</div>
		@else 
			@if($prodData->size_cnt > 1)
				<div class="opstion-avl">{{$prodData->size_cnt}} Options Available </div>
			@endif		
		@endif
		@if($prodData->yousave != 0 && $prodData->stock == 'In')
			<div class="you-save">
				You Save: {{$prodData->yousave}}%
				@if(isset($prodData->BrandInSearch) && $prodData->BrandInSearch == 1)
					Off
				@endif	
			</div>
		@endif
		
		</div>					
	@endif	
</div>
