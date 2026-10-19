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
<div class="dtl_addrview" style="column-gap: 5px;">
    <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}"></div>
    <span id="yotpo_write_review" style="display: none;"><a href="{{ $productDetails->product_url }}#productreview">Write a Review</a></span>
    <span id="yotpo_qk_pipe" style="display: none;">|</span>
    <span id="yotpo_ask_question" style="display: none;"><a href="{{ $productDetails->product_url }}#productreview">Ask A Question</a></span>

    <a href="{{ $productDetails->product_url }}#productreview" id="star_rating" style="display: none;">
        <span id="quickview_prd_review_rating">
            <x-ratingstar :starRating="$avg_rate" />
        </span>
    </a>    
    <span id="prd_review" style="display: none;"><a href="{{ $productDetails->product_url }}#productreview">Write a Review</a></span>
    <span id="qk_pipe" style="display: none;">|</span>
    <span id="ask_question" style="display: none;"><a href="{{ $productDetails->product_url }}#productreview">Ask A Question</a></span>
</div>
<div class="dtl_salestext" id="quickviewshow_sort_priceDiv">
    @if($productDetails->stock=='In')
        @if(count($dealofdayRS)>0)
            <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong>
            @if(isset($productDetails->dealprice))
                <span class="pdetail_dealprice prodprice" data-flag="deal_price">DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong></span>
                @php $proprice = $productDetails->dealprice; @endphp
            @else
                @php $proprice = $productDetails->retail_price; @endphp
            @endif
        @else 
            @if($productDetails->product_price!='')
                @if($productDetails->product_price != $productDetails->retail_price)
                    <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong> 
                    <span class="pdetail_dealprice">SALE: </span> <strong class="prodprice pdetail_dealprice"> {{ Price($productDetails->product_price) }} </strong> 
                @else
                    <strong class="prodprice">{{ Price($productDetails->product_price) }} </strong>
                @endif
                @php $proprice = $productDetails->product_price; @endphp
            @endif
        @endif
        
        @if($productDetails->yousave!='' && $productDetails->yousave > 0 )
            @if($productDetails->retail_price < 100 )
                <span class="yousave-dtl">(You Save {{$productDetails->yousave}}%)</span>
            @else
                <span class="yousave-dtl">(You Save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})</span>
            @endif
        @endif

        @if($productDetails->category_id == 2 && $productDetails->stock=='In')
            <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" /></a>
        @endif

        <span>No Sales Tax (Non-NY Resident)</span>
    @endif
</div>