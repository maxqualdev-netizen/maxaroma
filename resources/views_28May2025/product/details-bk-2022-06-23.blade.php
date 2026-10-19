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
                    @include('product.product_details.product_descriptionpart')
                </div>
            </div>
            <div class="col-md-5" id="productimgicon_manufacture_detail">
                @include('product.product_details.productimgicon_manufacture_detail')
            </div>
            <div class="col-md-7">
                <div class="dtl_prinfo_left d-md-block d-none" id="product_descriptionpart">
                    @include('product.product_details.product_descriptionpart')
		    @if(config('typevalofcriteo') == 'd')
                        <div class="dtl_salestext pt-0">
                            @if(isset($proprice))
                            <div id="shoptotal"  class="shoptotald" style="display:none;">{{$proprice}}</div>
                            @else
                            <div id="shoptotal" class="shoptotald" style="display:none;">0.00</div>
                            @endif
                        </div>
                    @endif
                </div>
                @if(config('typevalofcriteo') == 'm')
                    <div class="dtl_itemid" id="psort_sku"><span>Item #:</span> {{ $productDetails->sku }}</div>
                    <div class="dtl_itemid" id="psort_descriptn">
                        <span>Selected Size:</span>
                        @if ($productDetails->short_description != '')
                        {!! $productDetails->short_description !!}
                        @endif
                    </div>
                @endif
                <div class="dtl_prinfo_right" id="addtocart_max2dayreward">
                    @include('product.product_details.addtocart_max2dayreward')
                </div>
                <div class="clearfix d-none d-lg-block"></div>
                <div id="SpecialPriceSort">
                    @include('product.product_details.specialprice')
                </div>
                @if (isset($productDetails->referenced_products))   <!-- (!$isMobile || $isIpad == 1) -->
                <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJ" referencedProductevent="change_product"  />
                @endif
                @if (($OutOfStockCount > 0 && !$isMobile) || ($OutOfStockCount > 0 && $isIpad == 1))
                <div class="stockitem border-bottom pb-3 mb-3">
                    <div class="outofstockitems">
                        <a href="javascript:void(0);">This product has {{ $OutOfStockCount }} Out of Stock
                            items</a>
                        <span class="d-none d-md-inline-block">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

                        <a href="javascript:void(0);" class="n-ppost uppercase" id="togglestockitem"><strong>Show out of stock items</strong></a>
                    </div>
                </div>
                @else
                @endif
                <ul class="dtl_slinks" id="add_towishlist">
                    @include('product.product_details.add_towishlist')
                </ul>
            </div>
{{--
            @if (count($manuf_prods_man) > 0 || count($manuf_prods_wom) > 0 || count($manuf_prods_uni) > 0)

<div class="d-md-none col-12 mb-4">
<div class="row mt-2">
<div class="col-12 pb-1 mb-1 other-hd-prd">other product by
        <strong>{{ $productDetails->vmanufacture }}</strong>
    </div>
</div>
<div class="other-prdlist">
    <div class="row row10">
    
    @if (count($manuf_prods_man) > 0)
    <div class="col-sm-6 col-xs-6 pb-2">
        <div class="ship-faq-tab thumb_bot">
            <a name="mens" href="#"></a>
            <h5>MEN</h5>
            <ul >
                <input type="hidden" name="other_man_count" id="man_other_count" value="{{ count($manuf_prods_man) }}" />
                @foreach ($manuf_prods_man as $key => $value)
                @if($key < 4) <div class="li_hide_other_men" style="display:block">
                    <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
        </div>
        @else
        <div style="display:none" class="li_show_other_men_{{$key}}">
            <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name'] }}</a></li>
        </div>
        @endif
        @endforeach
        </ul>
    </div>
    @if (count($manuf_prods_man) > 4)
    <div class="f-fix button_show_other_men" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_men(this);">See More</a></div>
    <div class="f-fix button_hide_other_men" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_men(this);">See Less</a>
    </div>
    @endif
</div>
@endif

@if (count($manuf_prods_wom)>0)
<div class="col-sm-6 col-xs-6 pt15 pb-1">
    <div class="ship-faq-tab thumb_bot">
        <a name="womens" href="#"></a>
        <h5>WOMEN</h5>
        <ul >
            <input type="hidden" name="other_women_count" id="women_other_count" value="{{count($manuf_prods_wom)}}" />
            @foreach($manuf_prods_wom as $key => $value)
            @if ($key < 4) <div class="li_hide_other_women" style="display:block">
                <li class="pt-1"><a href="{{$value['brand_link']}}">{{$value['brand_name']}}</a></li>
    </div>
    @else
    <div class="li_show_other_women_{{$key}}" style="display:none">
        <li class="pt-1"><a href="{{$value['brand_link']}}">{{$value['brand_name']}}</a></li>
    </div>
    @endif
    @endforeach
    </ul>
</div>
@if (count($manuf_prods_wom) > 4)
<div class="f-fix button_show_other_women" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_women(this);">See More </a></div>
<div class="f-fix button_hide_other_women" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_women(this);">See Less </a></div>
@endif
</div>
@endif

@if(count($manuf_prods_uni)>0)
<div class="col-sm-6 col-xs-6 pt15 pb-1 @if(count($manuf_prods_man)>0 && count($manuf_prods_wom)>0) clear @endif">
    <div class="ship-faq-tab thumb_bot">
        <a name="unisexs" href="#"></a>
        <h5>UNISEX</h5>
        <ul >
            <input type="hidden" name="other_uni_count" id="uni_other_count" value="{{count($manuf_prods_uni)}}" />
            @foreach($manuf_prods_uni as $key => $value)
            @if($key < 4) <div class="li_hide_other_unisex" style="display:block">
                <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
    </div>
    @else
    <div style="display:none" class="li_show_other_unisex_{{$key}}">
        <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
    </div>
    @endif
    @endforeach
    </ul>
</div>
@if(count($manuf_prods_uni) > 4)
<div class="f-fix button_show_other_unisex" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_unisex(this);">See More </a>
</div>
<div class="f-fix button_hide_other_unisex" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_unisex(this);">See Less </a>
</div>
@endif
</div>
@endif
</div>
</div>
</div>
@endif
--}}
            <div id="dtl_tab">
                <ul class="resp-tabs-list">
                    <li><span>Details</span></li>
                    <li><span>Shipping Information</span></li>
                    <li><span>Return & Exchange</span></li>
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
    </section>

    <section class="container"><div class="dtl_gallery"></div></section>

    <section class="container" id="product_story_notes">
        @include('product.product_details.product_story_notes')
    </section>
    <div class="clearfix d-block"></div>
    <section class="dtl_rview" id="productreview">
        @include('product.product_details.product_review')
    </section>
    <div id="SortRelatedProducts"></div>
    @include('product.product_details.you_may_also_like')
    
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
