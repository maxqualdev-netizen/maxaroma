<div class="actmenuhd">
	<span>Your Bag ({{$CartDetails['TotalItemInCart']}} Total)<a href="{{config('global.SITE_URL').'shoppingcart'}}" class="ulink edit-btn">Edit</a></span>
	<svg id="down-arrow" class="sv-down-arrow vam float-right" aria-hidden="true" role="img" width="18" height="18">
		<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
	</svg>
</div>
@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
<div class="actmenu-inner your-bag-inner mt-4" id="cart_items">
	<ul>
		@foreach($CartDetails['Cart'] as $key => $CartItem)
		<li>
			<div class="row">
				<div class="col-4 pr-0 pr-sm-3 col-sm-3">
					@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
						<a href="javascript:void(0);">{!!$CartItem['Image']!!}</a>
					@else
						<a href="{{$CartItem['Prod_URL']}}">{!!$CartItem['Image']!!}</a>
					@endif
				</div>
				<div class="col-8 col-sm-6">
					<div class="cart-product pb-1">
						@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
							{{$CartItem['ProductName']}}
						@else 	
							<a href="{{$CartItem['Prod_URL']}}">{{$CartItem['ProductName']}}
								@if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
									<br>		
									<span class="sub-nm">{!! $CartItem['short_description'] !!}</span>
								@endif
							</a> 
						@endif
					</div>
					<div class="pb-1 SKU">
						<span>Item SKU: {{$CartItem['SKU']}}</span>
					</div>
					<div class="pb-1 qty">
						<span>Quantity: {{$CartItem['Qty']}}</span>
					</div>
					<div class="cart-price d-block d-sm-none">
						<span>{{Price($CartItem['TotPrice'])}}</span>
					</div>
				</div>
				<div class="col-sm-3 text-right d-none d-sm-block">
					<div class="cart-price">
						<span>{{Price($CartItem['TotPrice'])}}</span>
					</div>
				</div>
			</div>
		</li>
		@endforeach
	</ul>
</div>
@endif