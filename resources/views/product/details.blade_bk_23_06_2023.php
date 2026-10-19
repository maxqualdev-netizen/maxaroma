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
                    <div class="dtl_addrview">
                        <span id="prd_review_rating" onclick="changetab('Read')">
                            <x-ratingstar :starRating="$avg_rate" />
                        </span>
                        <span id="prd_review">
                            ({{ count($arr_product_review) }})
                            <!--<a href="javascript:void(0)" onclick="changetab('Read')">Write a Review</a>&nbsp; |-->
                            <a href="javascript:void(0)" onclick="changetab('Read')">Write a Review</a>&nbsp; |
                        </span>
                        <span>
                            <a href="<?=config('global.BASE_URL')?>/contact-us.html" target="_blank">Ask A Question</a>
                        </span>
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
                    <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJ" referencedProductevent="change_product" new=1  />
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

                <div class="dtl_exp_sip-main">
                    <div class="row">
                        @if ($productDetails->maxtwodaydelivery == 'Yes' && $Max2Day == 'Yes' && $productDetails->IsCosmo == 'No' && $productDetails->IsPCA == 'No' && $productDetails->IsNandansons == 'No')
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="dtl_exp_sip">
                                    <img class="maxday-ico svg-watch icon" src="{{config('global.SITE_URL')}}images/maxtwoday-dtl.png"  width="36" height="17">
                                    <span>
                                        {{ $maxtwodaycontent }}, Order within 
                                        <strong>
                                        @if($Maxhour > 0){{ $Maxhour }} hrs @endif
                                        @if($Maxmin > 0){{ $Maxmin }} minutes. @endif 
                                        </strong> and choose <strong>2-day Shipping!</strong> at checkout.
                                    </span>
                                    <div class="max_qttable">
                                        <a href="javascript:void(0);"><u>i</u></a>
                                        <div class="tables">
                                            We offer guaranteed delivery on certain fast delivery speeds and select products. When guaranteed delivery is available on an order, it will be clearly marked on the checkout page with an associated delivery date and fee (if applicable).<br />
                                            If we provide a guaranteed delivery date and a delivery attempt is not made by this date, we will provide a refund of any shipping fees associated with that order.<br />
                                            Note: The order within countdown reflects Maxaroma's delivery capability at the moment the page was opened. The delivery day may become unavailable within that time frame, prior to completing your order, due to changes in inventory or delivery capacity.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="dtl_exp_sip">
                                    <svg class="svg-newship icon" aria-hidden="true" role="img" width="35" height="20">
                                        <use href="#svg-newship" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newship"></use>
                                    </svg>
                                    <span>
                                        <strong>Get it as soon as {{ $totalEstimate }}</strong><br />
                                        When you choose <strong>Expedited shipping</strong> at checkout.
                                    </span>
                                    <div class="max_qttable">
                                        <a href="javascript:void(0);"><u>i</u></a>
                                        <div class="tables">This is an estimated delivery date, and it is not guaranteed.</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($reward_points_text)
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="dtl_exp_sip">
                                    <svg class="svg-points icon" aria-hidden="true" role="img" width="25" height="25">
                                        <use href="#svg-points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-points"></use>
                                    </svg>
                                    <span>
                                        Members Get More <strong>{{$productDetails->point_multiplier}}x Points</strong> for this item {!! $reward_points_text !!}
                                        after purchase.<br>
                                        <a class="ulink" href="<?=config('global.SITE_URL')?>reward-point-program.html" target="_blank">
                                            <span>View Reward Program</span> 
                                        </a>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
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
    
    <section class="container"><div class="dtl_gallery"></div></section>
    
    @if($productDetails->story_video != '' && file_exists(config('global.PRODUCT_STORY_VIDEO_PATH') . $productDetails->story_video))
    <section class="prd-dtl-video">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4 pb-md-4">
					<div class="fullscreen">
                        <div class="video-full">
                            <div class="wrapper-f">
								<video width="100%" height="100%" controls poster="{{config('global.SITE_URL')}}images/video.jpg" class="imgposter">
									<div class="overlaybg">
										<a href="#" class="vdicon" title="Perfume">
											<svg class="svg-newvideo" aria-hidden="true" role="img" width="75" height="75">
												<use href="#svg-newvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newvideo"></use>
											</svg>
										</a>
									</div>
									<source src="{{config('global.PRODUCT_STORY_VIDEO_URL')}}{{$productDetails->story_video}}" type="video/mp4">
								</video>
							</div>
			            </div>
			        </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @if($productDetails->story_youtubelink != '')
    <section class="prd-dtl-video">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4 pb-md-4">
                    <div class="fullscreen">
                        <div class="video-full">
                            <div class="wrapper-f">
								<iframe width="100%" height="500" src="{{$productDetails->story_youtubelink}}"></iframe>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    @if($productDetails->product_story != '' || $productDetails->story_image_one != '' || $productDetails->story_image_two != '' || $productDetails->story_image_three != '' || $productDetails->story_image_four != '')
        <section class="prd-dtl-story">
            <div class="container">
                <div class="row">
                    <div class="col-12 pt-4 text-center pb-4">
                        <div class="main-heading">
                            <h3><span>Brand Stories</span></h3>
                        </div>
                    </div>
                    @if($productDetails->product_story != '')
                        <div class="col-md-12">
                            <p class="text-center f12">{{$productDetails->product_story}}</p>
                        </div>
                    @endif
                    @if($productDetails->story_image_one != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_one))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#"><img src="{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_one}}"  width="696" height="484"></a>
                            @if(trim($productDetails->story_image_one_desc) != '')
                            <div class="prd-story-text text-center pt-4">
                                <p class="mb-3 f12" id="imgtextdisplay1">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_one_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide1" style="display:none;">{{ remove_html_entities($productDetails->story_image_one_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow1" class="ulink f12" onclick="moredescshow('1')">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide1" style="display:none;" class="ulink f12" onclick="moredeschide('1')">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_two != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_two))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#"><img src="{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_two}}"  width="696" height="484"></a>
                            @if(trim($productDetails->story_image_two_desc) != '')
                            <div class="prd-story-text text-center pt-4">
                                <p class="mb-3 f12" id="imgtextdisplay2">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_two_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide2" style="display:none;">{{ remove_html_entities($productDetails->story_image_two_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow2" class="ulink f12" onclick="moredescshow('2')">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide2" style="display:none;" class="ulink f12" onclick="moredeschide('2')">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_three != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_three))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#"><img src="{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_three}}"  width="696" height="484"></a>
                            @if(trim($productDetails->story_image_three_desc) != '')
                            <div class="prd-story-text text-center pt-4">
                                <p class="mb-3 f12" id="imgtextdisplay3">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_three_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide3" style="display:none;">{{ remove_html_entities($productDetails->story_image_three_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow3" class="ulink f12" onclick="moredescshow('3')">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide3" style="display:none;" class="ulink f12" onclick="moredeschide('3')">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_four != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_four))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#"><img src="{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_four}}"  width="696" height="484"></a>
                            @if(trim($productDetails->story_image_four_desc) != '')
                            <div class="prd-story-text text-center pt-4">
                                <p class="mb-3 f12" id="imgtextdisplay4">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_four_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide4" style="display:none;">{{ remove_html_entities($productDetails->story_image_four_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow4" class="ulink f12" onclick="moredescshow('4')">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide4" style="display:none;" class="ulink f12" onclick="moredeschide('4')">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>    
    @endif

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
