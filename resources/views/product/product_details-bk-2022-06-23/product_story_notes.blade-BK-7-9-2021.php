@if($productDetails->story_youtubelink!= "" || $productDetails->story_video != "")

@if($productDetails->story_youtubelink!= "")
<div class="video-wrapper" style="height:550px!important">
    <iframe id="video" width="100%" height="100%" src="{{ $productDetails->story_youtubelink }}" frameborder="0" allowfullscreen class="product-img"></iframe>
</div>
@else
<div class="video-wrapper">
    <video src="VfE_html5.mp4" poster="images/video.jpg" class="imgposter">
        <img src="images/video.jpg" alt="" class="imgposter" />
        <div class="overlaybg">
            <a href="#" class="vdicon" title="Perfume">
                <svg class="svg-newvideo" aria-hidden="true" role="img" width="75" height="75">
                    <use href="#svg-newvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newvideo">
                    </use>
                </svg>
            </a>
        </div>
</div>
@endif

@endif
@if($productDetails->product_story != "" || $productDetails->story_image_one != "" || $productDetails->story_image_two != "" || $productDetails->story_image_three != "" || $productDetails->story_image_four != "")
<div class="prstory">
    @if($productDetails->product_story != "")
    <h4>Product Story</h4>

    <p id="storytextdisplay{{ $productDetails->products_id }}">
        {{ \Illuminate\Support\Str::limit($productDetails->product_story, 700, $end = '...') }}
    </p>
    <p id="storytexthide{{ $productDetails->products_id }}" style="display:none;">{{ $productDetails->product_story }}</p>
    @if (strlen($productDetails->product_story) > 700)
    <span id="storyshow{{ $productDetails->products_id }}"><a href="javascript:void(0);" onclick="moreStoryshow('{{ $productDetails->products_id }}');"><u>Read More+</u></a>
    </span>
    <span id="storyhide{{ $productDetails->products_id }}" style="display:none;"><a href="javascript:void(0);" onclick="moreStoryhide('{{ $productDetails->products_id }}');"><u>See Less</u></a>
    </span>
    @endif

    <!-- <a href="">See All Products</a> -->
    @endif
    <div class="storyimgsec">
        <div class="row">
            @if($productDetails->story_image_one != "" || $productDetails->story_image_two != "" )
            <div class="col-6">
                @if($productDetails->story_image_one != "")
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_one}}" class="mb-3 mb-md-4" alt="" />
                @endif
                @if($productDetails->story_image_two != "")
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_two}}" class="d-block" alt="" />
                @endif
            </div>
            @endif
            @if($productDetails->story_image_three != "")
            <div class="col-6">
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_three}}" alt="" />
            </div>
            @endif
            @if($productDetails->story_image_four != "")
            <div class="col-12 mt-4">
                <img src="{{ config('global.SITE_URL') }}images/product-story/story-images/{{$productDetails->story_image_four}}" alt="" />
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@if($productDetails->fragrance_family != '' || $productDetails->formulation != '' || $productDetails->coverage != '' || $productDetails->finish != '' || $productDetails->fragrance_occasion != '' || $productDetails->fragrance_personality != '' || $productDetails->fragrance_seasons != '')
<div class="prstorynote"  @if($productDetails->story_image_four == "") style="min-height: 0px!important;" @endif>
    <h4>Product Notes</h4>
    <div class="">
        <table class="table">
            <tbody>
                @if ($productDetails->fragrance_family != '')
                <tr>
                    <th><strong>Fragrance Family: </strong></th>
                    <td>
                        <div data-swiftype-name="Fragrance Family" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_family }}
                        </div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->formulation != '')
                <tr>
                    <th><strong>Formulation: </strong></th>
                    <td>
                        <div data-swiftype-name="Formulation" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->formulation }}</div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->coverage != '')
                <tr>
                    <th><strong>Coverage: </strong></th>
                    <td>
                        <div data-swiftype-name="Coverage" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->coverage }}</div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->finish != '')
                <tr>
                    <th><strong>Finish: </strong></th>
                    <td>
                        <div data-swiftype-name="Finish" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->finish }}</div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->fragrance_occasion != '')
                <tr>
                    <th><strong>Fragrance Occasion: </strong></th>
                    <td>
                        <div data-swiftype-name="Fragrance Occasion" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_occasion }}
                        </div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->fragrance_personality != '')
                <tr>
                    <th><strong>Fragrance Personality: </strong></th>
                    <td>
                        <div data-swiftype-name="fragrance personality" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_personality }}
                        </div>
                    </td>
                </tr>
                @endif

                @if ($productDetails->fragrance_seasons != '')
                <tr>
                    <th><strong>Fragrance Seasons: </strong></th>
                    <td>
                        <div data-swiftype-name="Fragrance Seasons" data-swiftype-type="string" @if ($productDetails->current_stock == 'Out') data-swiftype-index='false' @else data-swiftype-index='true' @endif>{{ $productDetails->fragrance_seasons }}
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endif