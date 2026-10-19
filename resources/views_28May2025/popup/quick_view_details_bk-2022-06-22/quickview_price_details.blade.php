<div class="dtl_name">
    @if(!empty($productDetails->vmanufacture))
        <h2>{{ $productDetails->vmanufacture }}</h2>
        <div>{!! $productDetails->referencedName_main !!}
        </div>
    @else
        <h2>{{ $productDetails->brand_name }}</h2>
        <div>{!! $productDetails->referencedName_main !!}
        </div>
    @endif
</div>
<div class="dtl_addrview">
    <span id="quickview_prd_review_rating">
        <x-ratingstar :starRating="$avg_rate" />
    </span>
    <span id="quickview_prd_review">
        @if (count($arr_product_review) > 0)
            <a href="#productreview"> {{ count($arr_product_review) }} REVIEWS</a>
        @else
            <a href="javascript:void(0);">{{ count($arr_product_review) }} REVIEWS </a>
        @endif
    </span>
</div>
<div class="dtl_itemid" id="quickviewpsort_sku"><span>Item #:</span> {{ $productDetails->sku }} </div>
<div class="dtl_itemid"><span>Selected Size:</span> @if ($productDetails->short_description != ''){{ $productDetails->short_description }} @endif</div>
<div class="dtl_salestext" id="quickviewshow_sort_priceDiv">
    @if($productDetails->stock=='In')
        @if(count($dealofdayRS) > 0)
            @if(count($dealofdayRS)>0)
                <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike>&nbsp;</strong>
            @endif
        @else
            @if($productDetails->product_price!='')
                @if($productDetails->product_price!=$productDetails->retail_price)
                    <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike>&nbsp;</strong>
                @endif
                    Now: <strong>{{ Price($productDetails->product_price) }} </strong>
                @if($productDetails->category_id == 2 && $productDetails->stock=='In')
                    <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" /></a><br />
                @endif
                @if($productDetails->yousave!='0')
                    <span class="greentext f12">You Save: ({{$productDetails->yousave}})% OFF</span>
                @endif
                @php $proprice = $productDetails->product_price; @endphp
            @endif
        @endif
    @endif

    @if($productDetails->WebsiteStock=='In')
        @if(count($dealofdayRS)>0)
            <span class="pdetail_dealprice">Deal Price: {{ Price($productDetails->dealprice) }}</span>
            @if($productDetails->category_id == 2 && $productDetails->WebsiteStock=='In')
                <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" /></a><br />
            @endif
            @if($productDetails->yousave >0 && $productDetails->yousave!='')
                <span class="greentext f12">You Save: {{ $productDetails->yousave }}% OFF ({{Price($productDetails->yousaveprice)}})</span>
            @endif
            @php $proprice = $productDetails->dealprice; @endphp
        @endif
    @endif

    @if ($productDetails->WebsiteStock == 'In')
        <span>No Sales Tax (Non-NY Resident)</span>
    @endif
    <span class="greentext f16 pt-2"><strong>Free Standard Shipping</strong></span>

</div>