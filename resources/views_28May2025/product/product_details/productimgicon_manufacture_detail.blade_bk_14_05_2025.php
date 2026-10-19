<div id="psort_dealproducts">
    @if (count($dealofdayRS) > 0)
    <svg class="sv sv-topdeal" aria-hidden="true" role="img" width="28" height="28">
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

<div class="share-deal">
    <a href="javascript:void(0);" id="add_to_wishlist" class="dtl-wish-link svg-h" onclick="DisplayPopupBoxWishlist('{{$productDetails->products_id}}','yes');"><!-- <img src="{{config('global.SITE_URL')}}public/images/m_icon.jpg" class="mr-1 vam" alt="" /> -->
            <svg class="sv-heart sv-bor mr-1" aria-hidden="true" role="img" width="16" height="16">
            <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
        </svg>
        <svg class="sv-heart-fill sv-fill mr-1" aria-hidden="true" role="img" width="16" height="16">
            <use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
        </svg>
    </a>
    <a href="javascript:void(0);" data-pid="{{$productDetails->products_id}}" class="ml-2" id="emailafriend">
        <svg class="svg-newshare vam" aria-hidden="true" role="img" width="16" height="16">
            <use href="#svg-newshare" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newshare"></use>
        </svg>&nbsp; Share
    </a>
</div>

<span> {{ $logo_img_bogo }}</span>
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
<div class="dtl_large_man ">
    <div class="dtl-vslider">
        <div class="cover-spin"></div>
        <div class="videodiv">
            <iframe id="video" width="100%" height="100%" src="{{ $productDetails->youtubelink}}" frameborder="0" allowfullscreen style="display:none;" class="product-img"></iframe>
        </div>
        <div class="slider-for jzoom zoom_img">
            <div class="slider-banner-image slick-slide" >
                <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $productDetails->mainImage }}" alt="" title="{{ $productDetails->referencedName }}" class="product-img" id="ExtraImages" data-other-src="{{ $productDetails->mainImage }}" data-zoom-img="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $productDetails->large_image }}" width="{{(getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image) + 255)}}"  height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}" />
                @if(!isset($productDetails->isimage)) 
                    <span class="sptitle d-none d-md-block">Hover to zoom</span>
                @endif
            </div>
            
            @if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
                @if ($productDetails->extra_images)
                    @foreach ($productDetails->extra_images as $key => $extraimg)
                        <div class="slider-banner-image slick-slide">
                            <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$extraimg->Meduim_Image}}" alt="{{$productDetails->referencedName}}" title="{{$productDetails->referencedName}}">
                        </div>    
                    @endforeach
                @endif
            @endif
            @if($productDetails->youtubelink != '')
            <div class="slider-banner-image slick-slide">
                <iframe id="video" width="100%" height="100%" src="{{ $productDetails->youtubelink}}" frameborder="0" allowfullscreen  class="product-img"></iframe>
            </div> 
            @endif
        </div>
        <div class="slider-nav thumb-image">
            <div class="thumbnail-image">
                <div class="thumbImg" onclick="VideoPlayerNew('{{ $productDetails->mainImage }}','{{ $productDetails->large_image }}','image')" id="reinitialiseimg">
                    <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$productDetails->mainImage}}" alt="{{$productDetails->referencedName}}" title="{{$productDetails->referencedName}}" width="60" height="60">
                </div>
            </div> 
            @if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
                @if (count($productDetails->extra_images) > 0)
                    @foreach ($productDetails->extra_images as $key => $extraimg)
                        <div class="thumbnail-image" data-img = "{{$extraimg->Meduim_Image}}">
                            <div class="thumbImg" onclick="VideoPlayerNew('{{ $extraimg->Meduim_Image }}','{{$extraimg->Large_Image}}','image')" id="reinitialiseimg">
                                <img src="{{$extraimg->Meduim_Image}}" alt="{{$productDetails->referencedName}}" title="{{$productDetails->referencedName}}" width="60" height="60">
                            </div>
                        </div>    
                    @endforeach
                @endif
                @if ($productDetails->youtubelink != '')
                    <div class="thumbnail-image">
                        <div class="thumbImg" onclick="VideoPlayerNew('{{ $productDetails->youtubelink }}','','video')">
                            @if ($ytthumb != '')
                                <img src="{{ $ytthumb }}" alt="Youtube Video" height="60" width="60">
                            @else
                                <svg class="sv svg-dyt" aria-hidden="true" role="img">
                                    <use href="#sv-dyt" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-dyt"></use>
                                </svg>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{--
<div class="dtl_large_man ">
    <div class="dtl-vslider">
        <div class="slider-for">
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="">
            </div> 
             <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="">
            </div>
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="">
            </div> 
             <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="">
            </div> 
        </div>
        <div class="slider-nav thumb-image">
            <div class="thumbnail-image">
                <div class="thumbImg">
                    <img src="https://via.placeholder.com/75x75" alt="">
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg">
                    <img src="https://via.placeholder.com/75x75" alt="">
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg">
                    <img src="https://via.placeholder.com/75x75" alt="">
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg">
                    <img src="https://via.placeholder.com/75x75" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
--}}

@if ($productDetails->PromotionBanner)
<div class="pt-4 mt-1"><a href="{{ $productDetails->PromotionBanner['banner_url'] }}" target="_blank"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $productDetails->PromotionBanner['banner'] }}" alt="{{ $productDetails->PromotionBanner['banner_title'] }}" title="{{ $productDetails->PromotionBanner['banner_title'] }}"></a></div>
@endif
{{--
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
--}}

