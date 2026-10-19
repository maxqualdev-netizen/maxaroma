 @extends('layouts.app')
@section('content')
    <main role="main" id="main-content" tabindex="-1" aria-label="Brand Page Main Content">
        <!-- Content Part Start Here -->
        <div class="barnd-banner" role="banner" aria-label="Brand Banner">
            @if($BrandDetail[0]->header_image != '')
                <img src="{{$BrandDetail[0]->header_image}}" alt="Brand Banner Image">
            @endif
        </div>
        <div class="bdk-perfume" role="region" aria-label="Brand Perfume Section">
            <div class="container">
                <div class="bdk-main">
                    @if($BrandDetail[0]->imglogo != '')
                    <div class="bdk-logo">
                        <img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$BrandDetail[0]->imglogo}}" alt="Brand Logo">
                    </div>
                    @endif
                    @if($BrandDetail[0]->header_desc != '')
                    <p>{{$BrandDetail[0]->header_desc}}</p>
                    @endif
                    <a href="{{$BrandDetail[0]->vlink}}" class="btn btn-secondary" role="button" aria-label="See all products for this brand">See All Prodcuts</a>    
                </div>
            </div>
        </div>    
        <div class="perfume-detail" role="region" aria-label="Perfume Details">    
            @if($Submanufacture && count($Submanufacture) > 0)
                @foreach($Submanufacture as $key => $SubManu)	
                    @if(($key+1) % 2 != 0)
                    <div class="loop-sec common-specing" role="region" aria-label="Perfume Detail Section {{ $SubManu->title }}">
                        <div class="img-sec">
                            @if($SubManu->image != '')
                            <a href="{{$SubManu->button_link}}">
                                <img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$SubManu->image}}" alt="{{ $SubManu->title }} Image">
                            </a>
                            @endif
                        </div>
                        <div class="text-sec">
                            <h2 class="lending-hd">
                                <a href="{{$SubManu->button_link}}" aria-label="View {{ $SubManu->title }}">
                                    <span class="big-text">{{substr($SubManu->title,0,1)}}</span>{{substr($SubManu->title,1,strlen($SubManu->title))}}
                                </a>
                            </h2>
                            {!! stripcslashes($SubManu->left_description) !!}
                            <div class="shop-btn">
                                <a href="{{$SubManu->button_link}}" class="btn btn-primary" role="button" aria-label="Shop {{ $SubManu->title }}">Shop Now</a>
                            </div>
                        </div>
                    </div>    
                    @else 
                    <div class="loop-sec img-right-loop common-specing" role="region" aria-label="Perfume Detail Section {{ $SubManu->title }}">
                        <div class="img-sec">
                            @if($SubManu->image != '')
                            <a href="{{$SubManu->button_link}}">
                                <img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$SubManu->image}}" alt="{{ $SubManu->title }} Image">
                            </a>
                            @endif
                        </div>
                        <div class="text-sec">
                            <h2 class="lending-hd">
                                <a href="{{$SubManu->button_link}}" aria-label="View {{ $SubManu->title }}">
                                    <span class="big-text">{{substr($SubManu->title,0,1)}}</span>{{substr($SubManu->title,1,strlen($SubManu->title))}}
                                </a>
                            </h2>
                            @if($SubManu->right_desciption != '')
                                {!! stripcslashes($SubManu->right_desciption) !!}
                            @endif
                            <div class="shop-btn">
                                <a href="{{$SubManu->button_link}}" class="btn btn-primary" role="button" aria-label="Shop {{ $SubManu->title }}">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach	
            @endif	
        </div>  
        @if($BrandDetail[0]->video_show=='Yes')     
            @if($BrandDetail[0]->youtubelink1 != "")	
                <div class="vedio-sec common-specing" role="region" aria-label="Brand Video 1">
                    <iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink1}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="Brand Video 1"></iframe>
                </div>     
            @endif
            @if($BrandDetail[0]->youtubelink2 != "")	
                <div class="vedio-sec common-specing" role="region" aria-label="Brand Video 2">
                    <iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink2}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="Brand Video 2"></iframe>
                </div>     
            @endif	
        @endif
        <!-- Content Part End Here -->
    </main>
@endsection