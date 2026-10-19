<div id="psort_dealproducts" role="region" aria-label="Product deal and icon section">
    @if (count($dealofdayRS) > 0)
    <svg class="sv sv-topdeal" aria-hidden="false" role="img" width="28" height="28" aria-label="Top Deal Icon">
        <use href="#sv-topdeal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-topdeal"></use>
    </svg>
    @else
        @if ($productDetails->SaleIcon != '')
        <span role="img" aria-label="Sale Icon">{!! $productDetails->SaleIcon !!}</span>
        @endif
        @if ($productDetails->GiftwrapIcon != '')
        <span role="img" aria-label="Gift Wrap Icon">{!! $productDetails->GiftwrapIcon !!}</span>   
        @endif
    @endif
</div>

<div class="share-deal" role="region" aria-label="Product sharing and wishlist options">
    <a href="javascript:void(0);" id="add_to_wishlist" class="dtl-wish-link svg-h"
       onclick="DisplayPopupBoxWishlist('{{$productDetails->products_id}}','yes');"
       aria-label="Add to wishlist"
       role="button">
        <svg class="sv-heart sv-bor mr-1" aria-hidden="true" role="img" width="16" height="16" aria-label="Wishlist outline icon">
            <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
        </svg>
        <svg class="sv-heart-fill sv-fill mr-1" aria-hidden="true" role="img" width="16" height="16" aria-label="Wishlist filled icon">
            <use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
        </svg>
    </a>
    <a href="javascript:void(0);" data-pid="{{$productDetails->products_id}}" class="ml-2" id="emailafriend"
       aria-label="Share product via email"
       role="button">
        <svg class="svg-newshare vam" aria-hidden="true" role="img" width="16" height="16" aria-label="Share icon">
            <use href="#svg-newshare" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newshare"></use>
        </svg>&nbsp; Share
    </a>
</div>

<span aria-hidden="true"> {{ $logo_img_bogo }}</span>
<!-- Modal -->
<div id="imgmodal" class="zoom-modal" role="dialog" aria-modal="true" aria-labelledby="zoom-modal-title" aria-describedby="zoom-modal-desc">
    <!-- The Close Button -->
    <span class="zoom-close" id="zoomimageclose" role="button" tabindex="0" aria-label="Close image zoom">&times;</span>
    <!-- Modal Content (The Image) -->
    <img class="modal-content-zoom-image" id="img01" alt="Zoomed product image" loading="lazy" aria-describedby="caption">
    <!-- Modal Caption (Image Text) -->
    <div id="caption" aria-live="polite"></div>
</div>
<!-- Modal -->
<div class="dtl_large_man" role="region" aria-label="Product images and video gallery">
    <div class="dtl-vslider"> 
        <div class="cover-spin" aria-hidden="true"></div>
        @if(isset($productDetails->youtubelink) && $productDetails->youtubelink != '')
            <div class="videodiv" role="region" aria-label="Product video">
                <iframe id="video" width="100%" height="100%" src="{{ $productDetails->youtubelink}}" frameborder="0" allowfullscreen style="display:none;" class="product-img" title="Product video"></iframe>
            </div>
        @endif
        
        <div class="slider-for jzoom zoom_img" aria-label="Product image gallery">
            <div class="slider-banner-image  slick-slide">
                <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->mainImage }}" alt="{{ $productDetails->referencedName }}" class="product-img" id="ExtraImages" data-other-src="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->mainImage }}" data-zoom-img="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->large_image }}" width="{{(getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image) + 255)}}" height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}" aria-label="Main product image" fetchpriority="high" />
                @if(!isset($productDetails->isimage)) 
                    <span class="sptitle d-none d-md-block" aria-hidden="true">Hover to zoom</span>
                @endif
            </div>
            
            @if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
                @if ($productDetails->extra_images)
                    @foreach ($productDetails->extra_images as $key => $extraimg)
                        <div class="slider-banner-image slick-slide">
                            <img src="{{ config('global.SPEED_SIZE_URL')}}{{$extraimg->Meduim_Image }}" alt="{{ $productDetails->referencedName }}" loading="lazy" aria-label="Additional product image {{ $key + 1 }}" width="605" height="600"/>
                        </div>    
                    @endforeach
                @endif
            @endif
            @if($productDetails->youtubelink != '')
            <div class="slider-banner-image slick-slide">
                <iframe id="video" width="100%" height="100%" src="{{ $productDetails->youtubelink}}" frameborder="0" allowfullscreen class="product-img" title="Product video"></iframe>
            </div> 
            @endif
        </div>
        <div class="slider-nav thumb-image" role="navigation" aria-label="Product image thumbnails">
            <div class="thumbnail-image">
                <div class="thumbImg" onclick="VideoPlayerNew('{{ $productDetails->mainImage }}','{{ $productDetails->large_image }}','image')" id="reinitialiseimg" role="button" tabindex="0" aria-label="Show main product image">
                    <img src="{{ config('global.SPEED_SIZE_URL')}}{{$productDetails->thumb_image}}" alt="{{ $productDetails->referencedName }}" loading="lazy" width="60" height="60">
                </div>
            </div> 
            @if (count($productDetails->extra_images) > 0 || $productDetails->youtubelink != '')
                @if (count($productDetails->extra_images) > 0)
                    @foreach ($productDetails->extra_images as $key => $extraimg)
                        <div class="thumbnail-image" data-img = "{{ config('global.SPEED_SIZE_URL')}}{{$extraimg->Meduim_Image}}">
                            <div class="thumbImg" onclick="VideoPlayerNew('{{ config('global.SPEED_SIZE_URL')}}{{ $extraimg->Meduim_Image }}','{{$extraimg->Large_Image}}','image')" id="reinitialiseimg" role="button" tabindex="0" aria-label="Show additional product image {{ $key + 1 }}">
                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{$extraimg->Meduim_Image}}" alt="{{ $productDetails->referencedName }}" loading="lazy" width="60" height="60">
                            </div>
                        </div>    
                    @endforeach
                @endif
                @if ($productDetails->youtubelink != '')
                    <div class="thumbnail-image">
                        <div class="thumbImg" onclick="VideoPlayerNew('{{ $productDetails->youtubelink }}','','video')" role="button" tabindex="0" aria-label="Show product video">
                            @if ($ytthumb != '')
                                <img src="{{ $ytthumb }}" alt="Youtube Video" loading="lazy" height="60" width="60">
                            @else
                                <svg class="sv svg-dyt" aria-hidden="true" role="img" aria-label="YouTube video icon">
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

{{-- <div class="dtl_large_man" role="region" aria-label="Product images and thumbnails">
    <div class="dtl-vslider">
        <div class="slider-for" aria-label="Product image gallery">
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="Product image 1" loading="lazy" aria-label="Product image 1" width="600" height="600" />
            </div> 
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="Product image 2" loading="lazy" aria-label="Product image 2" width="600" height="600" />
            </div>
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="Product image 3" loading="lazy" aria-label="Product image 3" width="600" height="600" />
            </div> 
            <div class="slider-banner-image">
               <img src="https://via.placeholder.com/600x600" alt="Product image 4" loading="lazy" aria-label="Product image 4" width="600" height="600" />
            </div> 
        </div>
        <div class="slider-nav thumb-image" role="navigation" aria-label="Product image thumbnails">
            <div class="thumbnail-image">
                <div class="thumbImg" role="button" tabindex="0" aria-label="Show product image 1">
                    <img src="https://via.placeholder.com/75x75" loading="lazy" alt="Thumbnail 1" width="75" height="75" />
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg" role="button" tabindex="0" aria-label="Show product image 2">
                    <img src="https://via.placeholder.com/75x75" loading="lazy" alt="Thumbnail 2" width="75" height="75" >
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg" role="button" tabindex="0" aria-label="Show product image 3">
                    <img src="https://via.placeholder.com/75x75" loading="lazy" alt="Thumbnail 3" width="75" height="75" >
                </div>
            </div>
            <div class="thumbnail-image">
                <div class="thumbImg" role="button" tabindex="0" aria-label="Show product image 4">
                    <img src="https://via.placeholder.com/75x75" loading="lazy" alt="Thumbnail 4" width="75" height="75" >
                </div>
            </div>
        </div>
    </div>
</div> --}}

@if ($productDetails->PromotionBanner)
<div class="pt-4 mt-1" role="region" aria-label="Promotion banner">
    <a href="{{ $productDetails->PromotionBanner['banner_url'] }}" target="_blank" aria-label="Promotion: {{ $productDetails->PromotionBanner['banner_title'] }}">
        <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->PromotionBanner['banner'] }}" height="150" width="150" loading="lazy" alt="{{ $productDetails->PromotionBanner['banner_title'] }}">
    </a>
</div>
@endif

{{-- @if (count($manuf_prods_man) > 0 || count($manuf_prods_wom) > 0 || count($manuf_prods_uni) > 0)
    <div class="d-none d-md-block" role="region" aria-label="Other products by manufacturer">
        <div class="row pt-5 mt-2">
            <div class="col-12 pb-2 mb-1 other-hd-prd" id="other-products-header">
                Other product by <strong>{{ $productDetails->vmanufacture }}</strong>
            </div>
        </div>
        <div class="other-prdlist" role="region" aria-labelledby="other-products-header">
            <div class="row row10">
                @if (count($manuf_prods_man) > 0)
                    <div class="col-sm-6 col-xs-6 pb-2" role="region" aria-label="Other men's products">
                        <div class="ship-faq-tab thumb_bot">
                            <a name="mens" href="#" tabindex="-1" aria-hidden="true"></a>
                            <h5 id="men-products-heading">MEN</h5>
                            <ul class="other-scroll" role="list" aria-labelledby="men-products-heading">
                                <input type="hidden" name="other_man_count" id="man_other_count" value="{{ count($manuf_prods_man) }}" />
                                @foreach ($manuf_prods_man as $key => $value)
                                    @if($key < 4) 
                                        <div class="li_hide_other_men" style="display:block" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{ $value['brand_link'] }}" aria-label="View product {{ $value['brand_name'] }}">{{ $value['brand_name']}}</a>
                                            </li>
                                        </div>
                                    @else
                                        <div style="display:none" class="li_show_other_men_{{$key}}" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{ $value['brand_link'] }}" aria-label="View product {{ $value['brand_name'] }}">{{ $value['brand_name'] }}</a>
                                            </li>
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @if (count($manuf_prods_man) > 4)
                            <div class="f-fix button_show_other_men" style="display:block">
                                <a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_men(this);" role="button" aria-label="Show more men's products">See More</a>
                            </div>
                            <div class="f-fix button_hide_other_men" style="display:none">
                                <a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_men(this);" role="button" aria-label="Show less men's products">See Less</a>
                            </div>
                        @endif
                    </div>
                @endif

                @if (count($manuf_prods_wom)>0)
                    <div class="col-sm-6 col-xs-6 pt15" role="region" aria-label="Other women's products">
                        <div class="ship-faq-tab thumb_bot">
                            <a name="womens" href="#" tabindex="-1" aria-hidden="true"></a>
                            <h5 id="women-products-heading">WOMEN</h5>
                            <ul class="other-scroll" role="list" aria-labelledby="women-products-heading">
                                <input type="hidden" name="other_women_count" id="women_other_count" value="{{count($manuf_prods_wom)}}" />
                                @foreach($manuf_prods_wom as $key => $value)
                                    @if ($key < 4)
                                        <div class="li_hide_other_women" style="display:block" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{$value['brand_link']}}" aria-label="View product {{$value['brand_name']}}">{{$value['brand_name']}}</a>
                                            </li>
                                        </div>
                                    @else
                                        <div class="li_show_other_women_{{$key}}" style="display:none" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{$value['brand_link']}}" aria-label="View product {{$value['brand_name']}}">{{$value['brand_name']}}</a>
                                            </li>
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @if (count($manuf_prods_wom) > 4)
                            <div class="f-fix button_show_other_women" style="display:block">
                                <a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_women(this);" role="button" aria-label="Show more women's products">See More </a>
                            </div>
                            <div class="f-fix button_hide_other_women" style="display:none">
                                <a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_women(this);" role="button" aria-label="Show less women's products">See Less </a>
                            </div>
                        @endif
                    </div>
                @endif

                @if(count($manuf_prods_uni)>0)
                    <div class="col-sm-6 col-xs-6 pt15 @if(count($manuf_prods_man)>0 && count($manuf_prods_wom)>0) clear @endif" role="region" aria-label="Other unisex products">
                        <div class="ship-faq-tab thumb_bot">
                            <a name="unisexs" href="#" tabindex="-1" aria-hidden="true"></a>
                            <h5 id="unisex-products-heading">UNISEX</h5>
                            <ul class="other-scroll" role="list" aria-labelledby="unisex-products-heading">
                                <input type="hidden" name="other_uni_count" id="uni_other_count" value="{{count($manuf_prods_uni)}}" />
                                @foreach($manuf_prods_uni as $key => $value)
                                    @if($key < 4)
                                        <div class="li_hide_other_unisex" style="display:block" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{ $value['brand_link'] }}" aria-label="View product {{ $value['brand_name']}}">{{ $value['brand_name']}}</a>
                                            </li>
                                        </div>
                                    @else
                                        <div style="display:none" class="li_show_other_unisex_{{$key}}" role="listitem">
                                            <li class="pt-1">
                                                <a href="{{ $value['brand_link'] }}" aria-label="View product {{ $value['brand_name']}}">{{ $value['brand_name']}}</a>
                                            </li>
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @if(count($manuf_prods_uni) > 4)
                            <div class="f-fix button_show_other_unisex" style="display:block">
                                <a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_unisex(this);" role="button" aria-label="Show more unisex products">See More </a>
                            </div>
                            <div class="f-fix button_hide_other_unisex" style="display:none">
                                <a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_unisex(this);" role="button" aria-label="Show less unisex products">See Less </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif --}}