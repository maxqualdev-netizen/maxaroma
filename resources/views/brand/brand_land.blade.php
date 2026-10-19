@extends('layouts.app')
@section('content')
    <main role="main" id="main-content" tabindex="-1" aria-label="Brand Landing Main Content">
        <section class="brnd-h-bnr" role="banner" aria-label="Brand Banner">
            @if($BrandDetail[0]->header_image != '')
                <img src="{{$BrandDetail[0]->header_image}}" width="1920" height="591" class="d-none d-md-block" alt="Brand Banner Image">
            @endif
            @if($BrandDetail[0]->mobile_header_image != '')
                <img src="{{$BrandDetail[0]->mobile_header_image}}" width="767" height="859" class="d-md-none" alt="Brand Mobile Banner Image">
            @endif
            <div class="brnd-h-over text-center">
                @if($BrandDetail[0]->overlap_brand_logo != '')
                <div class="brand-h-logo">
                    <img src="{{config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->overlap_brand_logo}}" alt="Brand Logo">
                </div>
                @endif
                @if($BrandDetail[0]->is_brand_show == 'Yes')
                    <h1 id="brand-main-title">{{$BrandDetail[0]->vmanufacture}}</h1>
                @endif
            </div>
        </section>
        @if($BrandDetail[0]->authorized_dealer_logo!='')
        <section class="auth-logo-sec" role="region" aria-label="Authorized Dealer Logo">
            <div class="container position-relative">
                <div class="auth-logo-b">
                    <img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->authorized_dealer_logo}}" alt="Authorized Dealer Logo">
                </div>
            </div>
        </section>
        @endif
        <section class="brand-h-sec" role="region" aria-label="Brand History Section">
            <div class="container">
                <div class="row">
                @if($BrandDetail[0]->brand_history != '')	
                <div class="col-12 text-center pt-4 pt-md-0">
                    <div class="brand-phd">
                        @if($BrandDetail[0]->is_brand_show == 'Yes')
                            <h3 id="about-brand-title">About {{$BrandDetail[0]->vmanufacture}}</h3>
                        @else 
                            <h1 id="about-brand-title">About {{$BrandDetail[0]->vmanufacture}}</h1>
                        @endif 
                        <p>{!!$BrandDetail[0]->brand_history!!}</p>
                    </div>
                </div>
                @endif
                @if($historyImgs && count($historyImgs) > 0)
                <div class="col-12">
                   <div class="row row0">
                       @for($i = 0; $i < count($historyImgs); $i++)
                       <div class="col-sm-4 pt-sm-3 pb-sm-3 pt-2 pb-2 text-center">
                           <img src="{{ config('global.SPEED_SIZE_URL')}}{{$historyImgs[$i]}}" alt="Brand History Image {{ $i+1 }}">
                       </div>
                       @endfor
                   </div>
                </div>
                @endif
                </div>
            </div>
        </section>
        <section class="brand-expore-coll" role="region" aria-label="Explore the Collection">
            <div class="container">
                <div class="row">
                <div class="col-12 text-center">
                    <div class="brand-phd brand-phd2">
                        <h3 id="explore-collection-title">Explore the Collection 
                            <!-- <span><a href="#" class="viewall">See More</a></span> -->
                        </h3>
                    </div>
                </div>
                <div class="col-12 pt-3 pb-3">
                   <div class="brand-ex-list">
                       <ul class="brand-page-listul" id="brandProducts" role="list" aria-labelledby="explore-collection-title">
                            @foreach($Products as $key => $Product)
                                <li class="brand-page-list" role="listitem" aria-label="Product {{ $Product->product_name ?? $Product->name ?? 'Item' }}">
                                    <x-productbox :prodData="$Product"/>
                                </li>
                            @endforeach
                       </ul>
                   </div>
                </div>
                </div>
                <div class="pb-2" id="loadmore" style="@if($TotalProducts <= 10) display:none @endif">
                    <a class="list-more d-block" data-page='1' data-brand-id="{{$BrandDetail[0]->imanufactureid}}" role="button" aria-label="Load more products"> Load More... </a>
                </div>
                <div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif" role="alert" aria-live="polite">No Record Found</div>
            </div>
        </section>
        @if($BrandDetail[0]->video_show=='Yes')     
        <section class="brand-video-sec pb-md-5 pt-md-5 pb-3 pt-3" role="region" aria-label="Brand Videos">
            <div class="container">
                <div class="row row10">
                @if($BrandDetail[0]->youtubelink1 != "")
                    <div class="col-sm-6 pb-2 pt-2">
                        <iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink1}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="Brand Video 1"></iframe>
                    </div>
                @endif
                @if($BrandDetail[0]->youtubelink2 != "")
                    <div class="col-sm-6 pb-2 pt-2">
                        <iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink2}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="Brand Video 2"></iframe>
                    </div>
                @endif
                </div>
            </div>
        </section>
        @endif
    <!-- Content Part End Here -->
    </main>
@endsection