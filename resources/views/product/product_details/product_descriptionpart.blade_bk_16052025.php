<div class="dtl_name asa">
    @if(!empty($productDetails->vmanufacture))
        <h1><a target="_blank" href="{{$productDetails->vmanufacture_link}}">{{ stripslashes($productDetails->vmanufacture) }}</a></h1>
        <div>{!! stripslashes($productDetails->referencedName_main) !!} <small class="vtype">{{$productDetails->vtype}}</small></div>
    @else
        <h1>{{ stripslashes($productDetails->brand_name) }}</h1>
        <div>{{ stripslashes($productDetails->referencedName_main) }} <small class="vtype">$productDetails->vtype</small></div>
    @endif
</div>
<a id="#refresh"></a>
<div class="dtl_addrview">
    <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}"></div>
    <div>
    <span><a href="javascript:void(0)" onclick="$('.write-review-button').click();" >Write a Review</a></span>
    <span class="MobileAddToCart">&nbsp;|&nbsp;</span>
    <span class="MobileAddToCart"><a href="javascript:void(0)" onclick="$('.write-question-button').click();">Ask A Question</a></span></div>
    {{-- <span id="prd_review_rating" onclick="changetab('Read')">
        <x-ratingstar :starRating="$avg_rate" />
    </span>
    <span id="prd_review">
        ({{ count($arr_product_review) }})
        <!--<a href="javascript:void(0)" onclick="changetab('Read')">Write a Review</a>&nbsp; |-->
        <a href="javascript:void(0)" onclick="changetab('Read')">Write a Review</a>&nbsp; |
    </span>
    <span>
        <a href="<?=config('global.BASE_URL')?>/contact-us.html" target="_blank">Ask A Question</a>
    </span> --}}
</div>
@if($productDetails->stock=='In')
    <div class="dtl_salestext" id="show_sort_priceDiv">
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
        <input type="hidden" id="prod_pro_price" value="{{Price($proprice)}}">
        {{-- @if(config('typevalofcriteo') == 'd' && $Afterpay_Checkout == 'Yes')--}}
        @if($Afterpay_Checkout == 'Yes')
            <!-- {{Session::get('Afterpay.Min_AP_AMT')}} {{Session::get('Afterpay.Max_AP_AMT')}} -->
            <div class="dtl_salestext pt-0">
                @if(isset($proprice))
                    <!--<div id="shoptotal"  class="shoptotald" style="display:none;">{{$proprice * 100}}</div>-->
                    <afterpay-placement 
                    data-locale="en_US"
                    data-currency="USD" 
                    data-amount="{{$proprice}}"    
                    data-logo-type="lockup"
                    data-min="35" 
                    data-max="1000" 
                    data-size="xs"                
                 ></afterpay-placement>
                @else
                    <!--<div id="shoptotal" class="shoptotald" style="display:none;">0.00</div>-->
                    <afterpay-placement 
                    data-locale="en_US" 
                    data-currency="USD" 
                    data-amount="0.00"    
                    data-logo-type="lockup"
                    data-min="35" 
                    data-max="1000" 
                    data-size="xs"                
                 ></afterpay-placement>
                @endif
            </div>
        @endif
       
        {{-- @if(($produ_categoryid == 68 || $produ_categoryid == 69 || $produ_categoryid == 70 || $produ_categoryid == 71) && $productDetails->is_atomizer == 'Yes') --}}
            {{-- {{config('global.FINALSALE')}}         --}}
        @if($is_finalsale == 'Y')    
            <strong style="font-size:12px;">FINAL SALE - NOT ELIGIBLE FOR RETURN</strong><br>
        @endif
    </div>
@endif
@if(config('typevalofcriteo') == 'd')
    <div class="pb-2 pt-2">
        <div class="dtl_itemid" id="psort_descriptn">
            <span>Size:</span>
            @if (trim($productDetails->short_description) != '')
                {!! stripslashes(str_replace('\\r\n','',trim($productDetails->short_description))) !!}
            @endif
        </div>
    </div>
@endif
