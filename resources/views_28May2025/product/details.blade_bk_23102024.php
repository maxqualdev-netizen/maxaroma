@extends('layouts.app', ['body_class' => 'prd-bot-space'])
@section('content')
<main>
    <section class="breadcrumb">
        <div class="container">
            @foreach ($breadcrumbs as $key => $breadcrumb)
                @if ((count($breadcrumbs) - 1) == $key)
                    {{ $breadcrumb['title'] }}
                @else
                    <a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a>
                @endif
            @endforeach
        </div>
    </section>
    <section class="container" id="proddetails" data-type="{{Session::get('eusertype')}}">
        <div class="row">
            <div class="col-12 d-md-none">
                <div class="dtl_prinfo_left" id="product_descriptionmobilepart">
                   <div class="dtl_name asa">
                        @if(!empty($productDetails->vmanufacture))
                            <h1><a target="_blank" href="{{$productDetails->vmanufacture_link}}">{{ stripslashes($productDetails->vmanufacture) }}</a></h1>
                            <div>{!! stripslashes($productDetails->referencedName_main) !!}
                                @if(isset($DealOfferTime))    
                                    <span class="dtl-offermob">Offer Ends in {{$DealOfferTime}}</span>
                                @endif
                            </div>
                        @else
                            <h1>{{ stripslashes($productDetails->brand_name) }}</h1>
                            <div>{{ stripslashes($productDetails->referencedName_main) }}</div>
                        @endif
                    </div>
                    <div class="dtl_addrview" style="column-gap: 5px;">
                        <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}">&nbsp;</div>                        
                        <span class="productratingsreview" id="productratingsreviewtop">
                            <a href="javascript:void(0);" data-pid="{{ $productDetails->products_id }}" onclick="$('.write-review-button').click();">Write a Review</a>
                        </span>
                        <span class="MobileAddToCart">&nbsp;|&nbsp;</span>
                        <span class="productratingsreview MobileAddToCart">
                            <a href="javascript:void(0);" data-pid="{{ $productDetails->products_id }}" onclick="$('.write-question-button').click();">Ask A Question</a>
                        </span>
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
                </div>
            </div>
            <div class="col-md-6 col-lg-5 col-xl-6" id="productimgicon_manufacture_detail">
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
                <div class="dtl_prinfo_left dtl-imgbot-mob" id="product_descriptionpart">
                    @include('product.product_details.product_descriptionpart')
                </div>
                
                <div class="dtl_prinfo_right" id="addtocart_max2dayreward">
                    @include('product.product_details.addtocart_max2dayreward')
                </div>
                <div class="clearfix d-none d-lg-block"></div>
                
                <div id="SpecialPriceSort">
                    @include('product.product_details.specialprice')
                </div>
                
                @if (isset($productDetails->referenced_products))   <!-- (!$isMobile || $isIpad == 1) -->
                    <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJ MobileAddToCart" referencedProductevent="change_product" new=1 referenceProductCategoryId="{{$produ_categoryid}}"  />
                @endif
                
                @if (($OutOfStockCount > 0 && !$isMobile) || ($OutOfStockCount > 0 && $isIpad == 1))
                    <div class="stockitem pb-2">
                        <div class="outofstockitems">
                            <a href="javascript:void(0);">This product has {{ $OutOfStockCount }} Out of Stock
                                items</a>
                            <span class="d-none d-md-inline-block f12">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

                            <a href="javascript:void(0);" class="n-ppost ulink" id="togglestockitem"><strong>Show out of stock items</strong></a>
                        </div>
                    </div>
                @endif

                <div class="dtl_exp_sip-main" id="maxtwoandreward">
                    @include('product.product_details.maxtwoandreward')
                </div>
                <div id="dtl_tab">
                    <ul class="resp-tabs-list">
                        <li id="tab-details"><span>Details</span></li>
                        <li id="tab-shippinginfo"><span>Shipping Information</span></li>
                        <li id="tab-returnexch"><span>Return & Exchange</span></li>
                    </ul>
                    <div class="resp-tabs-container static-inner">
                        <div class="prdetail-tab" id="prodetail">
                            @include('product.product_details.product_detail')
                        </div>
                        <div class="prship-tab">
                            {!! $Shipping_info_Content !!}
                        </div>
                        <div class="prreturn-tab">{!! $Return_info_Content !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="SortRelatedProducts"></div>
    @include('product.product_details.you_may_also_like')
    
    <section class="container"><div class="dtl_gallery"></div></section>

    <div class="dtl_exp_sip-main" id="productstorydetails">
        @include('product.product_details.productstorydetails')
    </div>

    {{-- <div class="clearfix d-block"></div>
    <section class="dtl_rview" id="productreview">
        @include('product.product_details.product_review')
    </section> --}}
    
    <section class="dtl_rview detail-page-yotporeview mb-5" id="productreview">
        @include('product.product_details.yotporeward')
    </section>
    
    <section class="dtl_sticky d-none d-md-block" id="productBottomsticky">
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
