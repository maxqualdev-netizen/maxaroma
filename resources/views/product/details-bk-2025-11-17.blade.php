@extends('layouts.app', ['body_class' => 'prd-bot-space'])
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="product-main-title">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            @foreach ($breadcrumbs as $key => $breadcrumb)
                @if ((count($breadcrumbs) - 1) == $key)
                    <span aria-current="page">{{ $breadcrumb['title'] }}</span>
                @else
                    <a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a>
                @endif
            @endforeach
        </div>
    </section>
    <section class="container" id="proddetails" data-type="{{Session::get('eusertype')}}" role="region" aria-label="Product Details Section">
        <div class="row">
            <div class="col-12 d-md-none">
                <div class="dtl_prinfo_left" id="product_descriptionmobilepart">
                   <div class="dtl_name asa" style="min-height:41px;">
                        @if(!empty($productDetails->vmanufacture))
                            <h1 id="product-main-title"><a target="_blank" href="{{$productDetails->vmanufacture_link}}">{{ stripslashes($productDetails->vmanufacture) }}</a></h1>
                            <div>{!! stripslashes($productDetails->referencedName_main) !!}
                                @if(isset($DealOfferTime))    
                                    <span class="dtl-offermob" aria-label="Offer ends in {{$DealOfferTime}}">Offer Ends in {{$DealOfferTime}}</span>
                                @endif
                            </div>
                        @else
                            <h1 id="product-main-title">{{ stripslashes($productDetails->brand_name) }}</h1>
                            <div>{{ stripslashes($productDetails->referencedName_main) }}</div>
                        @endif
                    </div>
                    <div class="dtl_addrview" style="column-gap: 5px;">
                        <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}" style="min-width:180px; min-height:20px;" aria-label="Product Ratings">&nbsp;</div>                        
                        <span class="productratingsreview" id="productratingsreviewtop">
                            <a href="javascript:void(0);" data-pid="{{ $productDetails->products_id }}" onclick="$('.write-review-button').click();" aria-label="Write a Review for {{ $productDetails->product_name }}">Write a Review</a>
                        </span>
                        <span class="MobileAddToCart">&nbsp;|&nbsp;</span>
                        <span class="productratingsreview MobileAddToCart">
                            <a href="javascript:void(0);" data-pid="{{ $productDetails->products_id }}" onclick="$('.write-question-button').click();" aria-label="Ask a Question about {{ $productDetails->product_name }}">Ask A Question</a>
                        </span>

                        {{-- <span id="prd_review_rating" onclick="changetab('Read')" role="img" aria-label="Product rating: {{$avg_rate}} out of 5 stars">
                            <x-ratingstar :starRating="$avg_rate" />
                        </span>
                        <span id="prd_review" role="region" aria-label="Product Reviews">
                            ({{ count($arr_product_review) }})
                            <!--<a href="javascript:void(0)" onclick="changetab('Read')">Write a Review</a>&nbsp; |-->
                            <a href="javascript:void(0)" onclick="changetab('Read')" aria-label="Write a Review for this product">Write a Review</a>&nbsp; |
                        </span>
                        <span>
                            <a href="<?=config('global.BASE_URL')?>/contact-us.html" target="_blank" aria-label="Ask a Question about this product">Ask A Question</a>
                        </span> --}}
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-5 col-xl-6" id="productimgicon_manufacture_detail" role="region" aria-label="Product Images">                
                @include('product.product_details.productimgicon_manufacture_detail')                    
            </div>
            
            <div class="col-md-6 col-lg-7 col-xl-6">
                 @if(config('typevalofcriteo') == 'm')
                    <div class="dtl_itemid" id="psort_descriptn">
                        <span>Size:</span>
                        @if ($productDetails->short_description != '')
                            {{ $productDetails->short_description }}
                        @endif
                    </div>
                @endif
                <div class="dtl_prinfo_left dtl-imgbot-mob" id="product_descriptionpart" role="region" aria-label="Product Description">
                    @include('product.product_details.product_descriptionpart')
                </div>
                
                <div class="dtl_prinfo_right" id="addtocart_max2dayreward" role="region" aria-label="Add to Cart and Rewards">
                    @include('product.product_details.addtocart_max2dayreward')
                </div>
                <div class="clearfix d-none d-lg-block"></div>
                
                <div id="SpecialPriceSort" role="region" aria-label="Special Price">
                    @include('product.product_details.specialprice')
                </div>
                
                @if (isset($productDetails->referenced_products))
                    <!-- (!$isMobile || $isIpad == 1) -->
                    <div role="region" aria-label="Related Products">
                        <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJ" referencedProductevent="change_product" new=1 referenceProductCategoryId="{{$produ_categoryid}}"  />
                    </div>
                @endif
                
                @if (($OutOfStockCount > 0 && !$isMobile) || ($OutOfStockCount > 0 && $isIpad == 1))
                    <div class="stockitem pb-2" role="region" aria-label="Out of Stock Items">
                        <div class="outofstockitems">
                            <a href="javascript:void(0);">This product has {{ $OutOfStockCount }} Out of Stock items</a>
                            <span class="d-none d-md-inline-block f12">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            <a href="javascript:void(0);" class="n-ppost ulink" id="togglestockitem"><strong>Show out of stock items</strong></a>
                        </div>
                    </div>
                @endif

                <div class="dtl_exp_sip-main" id="maxtwoandreward" role="region" aria-label="Max Two and Reward">
                    @include('product.product_details.maxtwoandreward')
                </div>
                <div id="dtl_tab" style="min-height:170px;" role="region" aria-label="Product Information Tabs">
                    <ul class="resp-tabs-list" role="tablist">
                        <li id="tab-details" role="tab" aria-controls="prodetail" tabindex="0"><span>Details</span></li>
                        <li id="tab-shippinginfo" role="tab" aria-controls="prship-tab" tabindex="0"><span>Shipping Information</span></li>
                        <li id="tab-returnexch" role="tab" aria-controls="prreturn-tab" tabindex="0"><span>Return & Exchange</span></li>
                    </ul>
                    <div class="resp-tabs-container static-inner" role="tablist">
                        <div class="prdetail-tab" id="prodetail" role="tabpanel" aria-labelledby="tab-details">
                            @include('product.product_details.product_detail')
                        </div>
                        <div class="prship-tab" id="prship-tab" role="tabpanel" aria-labelledby="tab-shippinginfo">
                            {!! $Shipping_info_Content !!}
                        </div>
                        <div class="prreturn-tab" id="prreturn-tab" role="tabpanel" aria-labelledby="tab-returnexch">{!! $Return_info_Content !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
	 @if(config('Settings.ESTREAMLY_CAROUSEL') == "Yes")        
        <div class="home_banner">
            <script src="https://scripts.estreamly.com/estreamly-utils.js" onload="includeEStreamlyStylesCarousel('estreamly_iframe_container_1748437051589','estreamly_iframe_1748437051589');" defer="true"></script><div id="estreamly_iframe_container_1748437051589" ><iframe loading="eager" style="width: 100% !important; height: 100% !important; border-radius: 10px; background-color: transparent;" width="100%" height="566" id="estreamly_iframe_1748437051589" name="estreamly_iframe_1748437051589" src="https://estreamly.com/carousel-embed/maxaromanew?isSmartView=true&tip=false&font=Roboto&btnColor=%23000000&btntextColor=%23ffffff&backColor=%23ffffff&textColor=%23101010&styleUpdate=true&iframeElementId=estreamly_iframe_1748437051589&pid={{$productDetails->sku}}&filterBy=product" title="eStreamly video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share;"allowfullscreen=""></iframe></div>
		</div>
        
	@endif
	
	
    <div class="home_banner dtl-reviewsl" role="region" aria-label="Product Reviews">
        <div class="container">
            <div class="yotpo-widget-instance" data-yotpo-instance-id="1071032" data-yotpo-product-id="{{ $productDetails->products_id }}"></div>        
        </div>
    </div>

    <div id="SortRelatedProducts" role="region" aria-label="Sorted Related Products"></div>
    @include('product.product_details.you_may_also_like')
    
    <section class="container" aria-label="Product Gallery"><div class="dtl_gallery"></div></section>

    
    <div class="dtl_exp_sip-main" id="productstorydetails" role="region" aria-label="Product Story">        
            @include('product.product_details.productstorydetails')        
    </div>    

      {{-- <div class="clearfix d-block"></div>
    <section class="dtl_rview" id="productreview" role="region" aria-label="Product Review">
        @include('product.product_details.product_review')
    </section> --}}

    
    <section class="dtl_rview detail-page-yotporeview mb-5" id="productreview" role="region" aria-label="Yotpo Product Reviews">        
            @include('product.product_details.yotporeward')        
    </section>
    
    
    <section class="dtl_sticky d-none d-md-block" id="productBottomsticky" role="region" aria-label="Sticky Product Actions">
        @include('product.product_details.product_bottomsticky')
    </section>
</main>

<script type="text/javascript">
    var Site_URL = "{{config('global.SITE_URL')}}";
    var isMobile = "{{isMobile()}}";
    var DealStartDate = "{{ $DealStartDate ?? '' }}";
    var DealEndDate = "{{ $DealEndDate ?? '' }}";    
</script>
@endsection
