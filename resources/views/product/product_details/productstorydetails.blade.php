@if($productDetails->story_video != '' && file_exists(config('global.PRODUCT_STORY_VIDEO_PATH') . $productDetails->story_video))
    <section class="prd-dtl-video" role="region" aria-label="Product Story Video Section">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4 pb-md-4">
                    <div class="fullscreen">
                        <div class="video-full">
                            <div class="wrapper-f">
                                <video width="100%" height="100%" controls poster="{{config('global.SITE_URL')}}images/video.jpg" class="imgposter" title="Product Story Video">
                                    <div class="overlaybg">
                                        <a href="#" class="vdicon" title="Perfume" aria-label="Perfume Video Icon">
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
    <section class="prd-dtl-video" role="region" aria-label="Product Story YouTube Video Section">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4 pb-md-4">
                    <div class="fullscreen">
                        <div class="video-full">
                            <div class="wrapper-f">
                                <iframe width="100%" height="500" src="{{$productDetails->story_youtubelink}}" title="Product Story YouTube Video"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    @if($productDetails->product_story != '' || $productDetails->story_image_one != '' || $productDetails->story_image_two != '' || $productDetails->story_image_three != '' || $productDetails->story_image_four != '')
        <section class="prd-dtl-story" role="region" aria-label="Brand Stories Section">
            <div class="container">
                <div class="row">
                    <div class="col-12 pt-4 text-center pb-4" style="min-height:82px;">
                        <div class="main-heading">
                            <h3 id="brand-stories-title"><span>Brand Stories</span></h3>
                        </div>
                    </div>
                    @if($productDetails->product_story != '')
                        <div class="col-md-12">
                            <p class="text-center f12" id="brand-story-text" aria-labelledby="brand-stories-title">{{$productDetails->product_story}}</p>
                        </div>
                    @endif
                    @if($productDetails->story_image_one != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_one))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#">
                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_one}}?ver={{ filemtime(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_one);  }}" loading="lazy" width="696" height="484" alt="Brand Story Image 1">
                            </a>
                            @if(trim($productDetails->story_image_one_desc) != '')
                            <div class="prd-story-text text-center pt-4" role="region" aria-label="Brand Story Image 1 Description">
                                <p class="mb-3 f12" id="imgtextdisplay1" aria-labelledby="brand-stories-title">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_one_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide1" style="display:none;" aria-labelledby="brand-stories-title">{{ remove_html_entities($productDetails->story_image_one_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow1" class="ulink f12" onclick="moredescshow('1')" aria-label="Read More Brand Story Image 1 Description">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide1" style="display:none;" class="ulink f12" onclick="moredeschide('1')" aria-label="Read Less Brand Story Image 1 Description">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_two != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_two))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#">
                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_two}}?ver={{ filemtime(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_two);  }}" loading="lazy" width="696" height="484" alt="Brand Story Image 2">
                            </a>
                            @if(trim($productDetails->story_image_two_desc) != '')
                            <div class="prd-story-text text-center pt-4" role="region" aria-label="Brand Story Image 2 Description">
                                <p class="mb-3 f12" id="imgtextdisplay2" aria-labelledby="brand-stories-title">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_two_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide2" style="display:none;" aria-labelledby="brand-stories-title">{{ remove_html_entities($productDetails->story_image_two_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow2" class="ulink f12" onclick="moredescshow('2')" aria-label="Read More Brand Story Image 2 Description">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide2" style="display:none;" class="ulink f12" onclick="moredeschide('2')" aria-label="Read Less Brand Story Image 2 Description">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_three != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_three))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#">
                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_three}}?ver={{ filemtime(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_three);  }}" loading="lazy"  width="696" height="484" alt="Brand Story Image 3">
                            </a>
                            @if(trim($productDetails->story_image_three_desc) != '')
                            <div class="prd-story-text text-center pt-4" role="region" aria-label="Brand Story Image 3 Description">
                                <p class="mb-3 f12" id="imgtextdisplay3" aria-labelledby="brand-stories-title">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_three_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide3" style="display:none;" aria-labelledby="brand-stories-title">{{ remove_html_entities($productDetails->story_image_three_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow3" class="ulink f12" onclick="moredescshow('3')" aria-label="Read More Brand Story Image 3 Description">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide3" style="display:none;" class="ulink f12" onclick="moredeschide('3')" aria-label="Read Less Brand Story Image 3 Description">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($productDetails->story_image_four != '' && file_exists(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_four))
                        <div class="col-sm-6 pt-4 pb-4">
                            <a href="#">
                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.PRODUCT_STORY_IMAGE_URL').$productDetails->story_image_four}}?ver={{ filemtime(config('global.PRODUCT_STORY_IMAGE_PATH').$productDetails->story_image_four);  }}" loading="lazy" width="696" height="484" alt="Brand Story Image 4">
                            </a>
                            @if(trim($productDetails->story_image_four_desc) != '')
                            <div class="prd-story-text text-center pt-4" role="region" aria-label="Brand Story Image 4 Description">
                                <p class="mb-3 f12" id="imgtextdisplay4" aria-labelledby="brand-stories-title">{{ \Illuminate\Support\Str::limit(remove_html_entities($productDetails->story_image_four_desc), 160, $end = '...') }}</p>
                                <p class="mb-3 f12" id="imgtexthide4" style="display:none;" aria-labelledby="brand-stories-title">{{ remove_html_entities($productDetails->story_image_four_desc) }}</p>
                                <a href="javascript:void(0);" id="imgdescshow4" class="ulink f12" onclick="moredescshow('4')" aria-label="Read More Brand Story Image 4 Description">Read More</a>
                                <a href="javascript:void(0);" id="imgdeschide4" style="display:none;" class="ulink f12" onclick="moredeschide('4')" aria-label="Read Less Brand Story Image 4 Description">Read Less</a>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>    
    @endif