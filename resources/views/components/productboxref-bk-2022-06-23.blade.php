@foreach($prodData as $key1 => $value1)
<div class="dtl_size @if($key1 =='OutOfStock') outofstockitem @endif" style="@if($key1 =='OutOfStock') display: none @endif">
	<div class="titles">@if($key1 =='OutOfStock') Out Of Stock @else {{ $key1 }} @endif</div>
	<ul class="ul_col">
		@foreach($value1 as $key2 => $value2)
		<li class="{{$referencedProductclass}} {{ $value2['sku'] }} @if($value2['sku'] == $productDetailsku) active @endif" @if(count($value1)> 1) onclick="{{$referencedProductevent}}('{{$value2['products_id']}}');" @elseif (count($prodData) > 1) onclick="{{$referencedProductevent}}('{{$value2['products_id']}}');" @endif>
			<a href="@if(count($value1)<= 1 && count($prodData) <= 1) {{ $value2['product_url'] }} @else javascript:void(0) @endif">
				<span class="thumb"><img src="{{$value2['image']}}" alt="{{$value2['product_name']}}" title="{{$value2['product_name']}}" height="70" width="70" />
					@if($value2['DealIcon']!='')
					{!! $value2['DealIcon'] !!}
					@elseif($value2['SaleIcon']!='')
					{!! $value2['SaleIcon'] !!}
					@endif
				</span>
				<span class="info">
					@if($key1 =='OutOfStock')
					<strong>{{ stripslashes($value2['product_name']) }}</strong>
					@else
					<strong>{{ stripslashes($value2['product_name']) }}</strong>
					@endif
					@if($value2['stock'] == 'In')
					@if(!isset($value2['dealprice']) || $value2['dealprice'] == '')
					<p class="dtl_refprice"><strong>{{ Price($value2['product_price']) }} </strong> </p>
					@else
					<p class="dtl_refprice"><strong>{{ Price($value2['dealprice']) }}</strong> </p>
					@endif
					@endif
					
					@if($key1 =='Standard Size') 
						<p style="font-size: 9px" class="mt-2"><strong>{{ stripslashes($value2['short_description']) }}</strong> </p>
					@endif

					@if($value2['WebsiteStock'] != 'Out')
					<span class="dtl_salestext">
						@if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $value2['product_type'] == 'retailer')
						<span class="text-red">Retail Only</span>
						@else
						<!-- <span> Ready to ship</span> -->
						@endif
					</span>
					@endif
				</span>
			</a>
		</li>
		@endforeach
	</ul>
</div>
@endforeach
