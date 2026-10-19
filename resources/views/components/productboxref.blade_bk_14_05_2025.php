<div class="titles">Standard Size</div>
<div class="dtl_size">
	<ul class="ul_col">
        @foreach($prodData as $key1 => $value1)
            @foreach($value1 as $key2 => $value2)
            @php
                if($value2['is_atomizer'] == 'Yes'){
                    $refCatId = $referenceProductCategoryId;
                } else {
                    $refCatId = $value2['category_id'];
                }
            @endphp
            <li style="@if($key1 =='OutOfStock') display: none @endif" class="{{$referencedProductclass}} {{ $value2['sku'] }} @if($value2['sku'] == $productDetailsku) active @endif @if($key1 =='OutOfStock') outofstockitem @endif" @if(count($value1)> 1) onclick="{{$referencedProductevent}}('{{$value2['products_id']}}','{{$refCatId}}');" @elseif (count($prodData) > 1) onclick="{{$referencedProductevent}}('{{$value2['products_id']}}','{{$refCatId}}');" @endif>
                <a href="@if(count($value1)<= 1 && count($prodData) <= 1) {{ $value2['product_url'] }} @else javascript:void(0) @endif">
                    <span class="thumb"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$value2['image']}}" alt="{{$value2['product_name']}}" title="{{$value2['product_name']}}" height="70" width="70" />
                        @if($value2['DealIcon']!='')
                        {!! $value2['DealIcon'] !!}
                        @elseif($value2['SaleIcon']!='')
                        {!! $value2['SaleIcon'] !!}
                        @endif
                    </span>
                    <span class="info">
                        {{-- {{$value2['is_atomizer']}}--{{$value2['category_id']}}--{{$referenceProductCategoryId}} --}}
                        @if(trim($value2['short_description']) != '') 
                            <p style="font-size: 9px" class="mt-2"><strong>{{ stripslashes($value2['short_description']) }}</strong> </p>
                        @endif
                        @if($value2['WebsiteStock'] != 'Out')
                        <span class="dtl_salestext">
                            @if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $value2['product_type'] == 'retailer')
                            <span class="text-red">Retail Only</span>
                            @endif
                        </span>
                        @endif
                    </span>
                </a>
            </li>
            @endforeach
        @endforeach
	</ul>
</div>
