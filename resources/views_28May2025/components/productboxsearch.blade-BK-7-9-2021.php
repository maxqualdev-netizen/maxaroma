<div class="product {{$sliderClass}}">
	<a href="{{$prodData->product_url}}" class="p-thumb">
		<img src="{{$prodData->imageUrl}}" alt="{{$prodData->name}}" title="{{$prodData->name}}" />
	</a>
	@if($prodData->inventory_count > 0)
    <a href="javascript:void(0);" onclick="productquickview('{{$prodData->uid}}')" class=" lable-quickview" data-pid="{{$prodData->uid}}" rel="nofollow">Quick View</a>
	@endif
	@if($prodData->inventory_count <= 0)
    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodData->uid}}','{{$prodData->sku}}');" class="lable-quickview" data-pid="{{$prodData->uid}}" rel="nofollow">Notify Me</a>
	@endif
	</span>	
	<ul class="product_icon">
		<?php if(isset($prodData->gender)){?>
		<li>
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
			<a href="javascript:void(0);" title="{{$prodData->gendernames}} Item">
				<svg class="{{$prodData->gender}}" aria-hidden="true" role="img" width="8" height="14">
					<use href="#{{$prodData->gender}}" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#{{$prodData->gender}}"></use>
				</svg>
			</a>
		</li>
		<?php }?>
		<li>
			<a href="javascript:void(0);" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('{{$prodData->uid}}','yes');">
				<svg class="sv-heart" aria-hidden="true" role="img" width="14" height="13">
					<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
				</svg>
			</a>
		</li>
		@if($prodData->inventory_count <= 0)
		<li>
			<a class="label-cart-outstock" href="javascript:void(0);" rel="nofollow" onclick="DisplayPopupBoxAlertMe('{{$prodData->uid}}','{{$prodData->sku}}');">
    				<svg class="sv-cart-outstock" aria-hidden="true" role="img" width="14" height="13">
						<use href="#sv-cart-outstock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart-outstock"></use>
					</svg>
			</a>
		</li>
		@elseif(Auth::user() && Auth::user()->eusertype == 'Wholesaler' && $prodData->product_type == 'retailer')
		@else 	
		<li>	
			<a href="javascript:void(0);" title="Add to cart" class="prodaddcart" data-product="{{$prodData->uid}}">
				<svg class="sv-cart vam" aria-hidden="true" role="img" width="14" height="13">
					<use href="#sv-cart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart"></use>
				</svg>
			</a>	
		</li>	
		@endif
	</ul>
	<div class="product-name">
		<a href="{{$prodData->product_url}}"><strong><u> {!! $prodData->name !!}</u></strong></a>
	</div>
	
	<div class="product-price pt-1">
		<div class="price pb-1">
			${{number_format($prodData->price,2)}}
		</div>
	</div>
	
	<?php
	$prodData->yousave = $prodData->msrp - $prodData->price;
	$prodData->yousave = ($prodData->yousave / $prodData->msrp) * 100;
	$prodData->yousave = (int)$prodData->yousave;
	
	if(isset($prodData->rating) && $prodData->rating > 0){
		$setRatingAvg = (int)$prodData->rating_avg + 1;
	}
	?>
	
	@if($prodData->yousave != '' && $prodData->yousave > 0 && $prodData->inventory_count > 0)
		<div class="you-save pb-2">
			You Save: {{$prodData->yousave}}%
		</div>
	@endif
	
	@if(isset($prodData->rating) && $prodData->rating > 0)
		<div class="rating pb-2 d-flex align-items-center justify-content-center">
			<span class="d-inline-block">({{(int)$prodData->rating}})</span> 
			@for($s=$setRatingAvg;$s<=5;$s++)
				<span class="star"></span>
			@endfor
			@for($i=0;$i<$prodData->rating_avg;$i++)
				<span class="star active"></span>
			@endfor		
		</div>
	@endif
	
</div>
