@if($productDetails->story_youtubelink!= "" || $productDetails->story_video != "")

@if($productDetails->story_youtubelink!= "")
<div class="video-wrapper" style="height:550px!important" role="region" aria-label="Product Story Video">
    <iframe id="video" width="100%" height="100%" src="{{ $productDetails->story_youtubelink }}" frameborder="0" allowfullscreen class="product-img" title="Product Story YouTube Video"></iframe>
</div>
@else

    @if($productDetails->story_video!= "")
    <div class="video-wrapper1" role="region" aria-label="Product Story Video">
        <video width="100%" height="100%" controls poster="{{config('global.SITE_URL')}}images/video.jpg" class="imgposter" title="Product Story Video">
            <div class="overlaybg">
                <a href="#" class="vdicon" title="Perfume" aria-label="Perfume Video Icon">
                    <svg class="svg-newvideo" aria-hidden="true" role="img" width="75" height="75">
                        <use href="#svg-newvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newvideo">
                        </use>
                    </svg>
                </a>
            </div>
            <source src="{{config('global.PRODUCT_STORY_VIDEO_URL')}}{{$productDetails->story_video}}" type="video/mp4">
        </video>
    </div>
    @endif
@endif

@endif
@if($productDetails->product_story != "" || $productDetails->story_image_one != "" || $productDetails->story_image_two != "" || $productDetails->story_image_three != "" || $productDetails->story_image_four != "")
<div class="p-story-sec @if($productDetails->product_story =='') story-sec-no @endif" role="region" aria-label="Product Story Section">
<div class="prstory">
    @if($productDetails->product_story != "")
    <h4 id="product-story-title">Product Story</h4>

    <p id="storytextdisplay{{ $productDetails->products_id }}" aria-labelledby="product-story-title">
        {{ \Illuminate\Support\Str::limit($productDetails->product_story, 700, $end = '...') }}
    </p>
    <p id="storytexthide{{ $productDetails->products_id }}" style="display:none;" aria-labelledby="product-story-title">{{ $productDetails->product_story }}</p>
    @if (strlen($productDetails->product_story) > 700)
    <span id="storyshow{{ $productDetails->products_id }}"><a href="javascript:void(0);" class="story-more" onclick="moreStoryshow('{{ $productDetails->products_id }}');" aria-label="Read More Product Story">Read More</a>
    </span>
    <span id="storyhide{{ $productDetails->products_id }}" style="display:none;"><a class="story-more story-minus" href="javascript:void(0);" onclick="moreStoryhide('{{ $productDetails->products_id }}');" aria-label="See Less Product Story">See Less</a>
    </span>
    @endif

    <!-- <a href="">See All Products</a> -->
    @endif
    <div class="storyimgsec" role="region" aria-label="Product Story Images">
        <div class="row">
            @if($productDetails->story_image_one != "" || $productDetails->story_image_two != "" )
            <div class="col-6">
                @if($productDetails->story_image_one != "")
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_one}}" class="mb-3 mb-md-4" width="215" height="139" alt="Product Story Image 1" />
                @endif
                @if($productDetails->story_image_two != "")
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_two}}" class="d-block" width="215" height="139" alt="Product Story Image 2" />
                @endif
            </div>
            @endif
            @if($productDetails->story_image_three != "")
            <div class="col-6">
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_three}}" width="185" height="260" alt="Product Story Image 3" />
            </div>
            @endif
            @if($productDetails->story_image_four != "")
            <div class="col-12 mt-4">
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_four}}" alt="Product Story Image 4" />
            </div>
            @endif
        </div>
    </div>
</div>
@endif