<div class="dtl_name">
    @if(!empty($productDetails->vmanufacture))
        <h2>{{ remove_html_entities($productDetails->vmanufacture) }}</h2>
        <div>{!! remove_html_entities($productDetails->referencedName_main) !!}
        </div>
    @else
        <h2>{{ remove_html_entities($productDetails->brand_name) }}</h2>
        <div>{{ remove_html_entities($productDetails->referencedName_main) }}
        </div>
    @endif
</div>
<div class="dtl_addrview">
    <span id="quickview_prd_review_rating">
        <x-ratingstar :starRating="$avg_rate" />
    </span>
    <span id="quickview_prd_review">
        @if (count($arr_product_review) > 0)
            <a href="#productreview"> {{ count($arr_product_review) }} Reviews</a>
        @else
            <a href="javascript:void(0);">{{ count($arr_product_review) }} Reviews </a>
        @endif
    </span>
</div>
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
                    <span class="pdetail_dealprice">Now: <strong>{{ Price($productDetails->product_price) }} </strong></span>
                     @if($productDetails->yousave!='0')
                        <span class="yousave-dtl">(You Save: ({{$productDetails->yousave}})%)</span>
                    @endif
                @if($productDetails->category_id == 2 && $productDetails->stock=='In')
                    <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" /></a><br />
                @endif
               
                @php $proprice = $productDetails->product_price; @endphp
            @endif
        @endif
    @endif

    @if($productDetails->WebsiteStock=='In')
        @if(count($dealofdayRS)>0)
            <span class="pdetail_dealprice">Sale: <strong>{{ Price($productDetails->dealprice) }}</strong></span>
            @if($productDetails->yousave >0 && $productDetails->yousave!='')
                <span class="yousave-dtl">(You Save: {{ $productDetails->yousave }}%) ({{Price($productDetails->yousaveprice)}})</span>
            @endif
            @if($productDetails->category_id == 2 && $productDetails->WebsiteStock=='In')
                <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" /></a><br />
            @endif
            @php $proprice = $productDetails->dealprice; @endphp
        @endif
    @endif

    @if ($productDetails->WebsiteStock == 'In')
        <span>No Sales Tax (Non-NY Resident)</span>
    @endif
</div>