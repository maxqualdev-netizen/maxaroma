<div id="psort_dealproducts">
    @if (count($dealofdayRS) > 0)
    <svg class="sv sv-topdeal" aria-hidden="true" role="img" width="100" height="20">
        <use href="#sv-topdeal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-topdeal"></use>
    </svg>
    @else
        @if ($productDetails->SaleIcon != '')
        {!! $productDetails->SaleIcon !!}
        @endif
        @if ($productDetails->GiftwrapIcon != '')
        {!! $productDetails->GiftwrapIcon !!}
        @endif
    @endif
</div>
<!-- @if ($productDetails->manufacture_logo != '')
<img class="prd_brand_logo" id="psort_manufacture_logo" src="{{ $productDetails->manufacture_logo }}" alt="{{ $productDetails->vmanufacture }}" title="{{ $productDetails->vmanufacture }}" width="60px" height="60px" />
@endif -->
<div class="share-deal">
    <a href="javascript:void(0);" data-pid="{{$productDetails->products_id}}" id="emailafriend">
        <svg class="svg-newshare vam" aria-hidden="true" role="img" width="22" height="18">
            <use href="#svg-newshare" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newshare"></use>
        </svg>Share This Deal
    </a>
</div>
<div class="dtl_large_man jzoom @if(!isset($productDetails->isimage)) zoom_img @endif;">
    <div class="dtl_large">
        <div class="position-relative">
            <img src="{{ $productDetails->mainImage }}" alt="" title="{{ $productDetails->referencedName }}" class="product-img" id="ExtraImages" data-other-src="{{ $productDetails->mainImage }}" data-zoom-img="{{ $productDetails->large_image }}" width="{{getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}"  height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}" />
            @if(!isset($productDetails->isimage)) 
                <span class="sptitle">Hover to zoom</span>
            @endif
        </div>
    </div>
</div>
<div class="dtl_large_man">
    <iframe id="video" width="100%" height="100%" src="{{ $productDetails->youtubelink}}" frameborder="0" allowfullscreen style="display:none;" class="product-img"></iframe>
</div>

<!-- Modal -->
<div id="imgmodal" class="zoom-modal">
    <!-- The Close Button -->
    <span class="zoom-close" id="zoomimageclose">&times;</span>
    <!-- Modal Content (The Image) -->
    <img class="modal-content-zoom-image" id="img01">
    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>
<!-- Modal -->

<span> {{ $logo_img_bogo }}</span>

@if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
<div class="dtl_extra d-none d-md-block" id="ExtraImagesSlider">
    @if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
    <div class="item" onclick="VideoPlayer('{{ $productDetails->mainImage }}','{{ $productDetails->large_image }}','image')" id="reinitialiseimg">
        <img src="{{ $productDetails->mainImage }}" alt="{{ $productDetails->referencedName }}" title="{{ $productDetails->referencedName }}" height="60" width="60">
    </div>
    @endif

    @if ($productDetails->extra_images)
        @foreach ($productDetails->extra_images as $key => $extraimg)
        <div class="item" onclick="VideoPlayer('{{ $extraimg->Meduim_Image }}','{{$extraimg->Large_Image}}','image')" id="reinitialiseimg">
            <img src="{{$extraimg->Meduim_Image}}" alt="{{$productDetails->referencedName}}" title="{{$productDetails->referencedName}}" height="60" width="60">
        </div>
        @endforeach
    @endif

    @if ($productDetails->youtubelink != '')
    <div class="item" onclick="VideoPlayer('{{ $productDetails->youtubelink }}','','video')">
        @if ($ytthumb != '')
        <img src="{{ $ytthumb }}" alt="Youtube Video" height="60" width="60">
        @else
        <svg class="sv svg-dyt" aria-hidden="true" role="img">
            <use href="#sv-dyt" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-dyt"></use>
        </svg>
        @endif
    </div>
    @endif
</div>
@endif

@if ($productDetails->PromotionBanner)
<div class="pt-4 mt-1"><a href="{{ $productDetails->PromotionBanner['banner_url'] }}" target="_blank"><img src="{{ $productDetails->PromotionBanner['banner'] }}" alt="{{ $productDetails->PromotionBanner['banner_title'] }}" title="{{ $productDetails->PromotionBanner['banner_title'] }}"></a></div>
@endif

@if (count($manuf_prods_man) > 0 || count($manuf_prods_wom) > 0 || count($manuf_prods_uni) > 0)

<div class="d-none d-md-block ">
<div class="row pt-5 mt-2">
<div class="col-12 pb-2 mb-1 other-hd-prd">Other product by
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
            <ul class="other-scroll">
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
<div class="col-sm-6 col-xs-6 pt15 ">
    <div class="ship-faq-tab thumb_bot">
        <a name="womens" href="#"></a>
        <h5>WOMEN</h5>
        <ul class="other-scroll">
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
<div class="col-sm-6 col-xs-6 pt15 @if(count($manuf_prods_man)>0 && count($manuf_prods_wom)>0) clear @endif">
    <div class="ship-faq-tab thumb_bot">
        <a name="unisexs" href="#"></a>
        <h5>UNISEX</h5>
        <ul class="other-scroll">
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