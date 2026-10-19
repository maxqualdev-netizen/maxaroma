@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Brand History Main Content">
    <section class="brand-expore-coll" role="region" aria-label="Brand Explore Collection">
        <div class="container-fluid">
            <div class="brand_banner d-flex justify-content-center" role="banner" aria-label="Brand Banner">
                @if($res->banner_image!='')
                <img class="d-none d-md-block" src="{{ config('global.SPEED_SIZE_URL')}}{{$res->banner_image}}" width="1667" height="220" alt="Brand Banner Image" />
                @endif
                @if($res->mob_image!='')
                <img class="d-md-none" src="{{ config('global.SPEED_SIZE_URL')}}{{$res->mob_image}}" width="1920" height="650" alt="Brand Mobile Banner Image" />
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="brand-phd brand-phd2">
                        <h1 id="brand-history-title">{{$res->title}}</h1>
                    </div>
                </div>
                <div class="col-12 pt-3 pb-3">
                    <div class="brand-ex-list">
                        <ul class="brand-page-listul" id="brand-history-grid" data-load='0' role="list" aria-labelledby="brand-history-title">
                            @if(count($Products) > 0)
                            @foreach($Products as $key => $Product)
                            <li class="brand-page-list" role="listitem" aria-label="Product {{ $Product->product_name ?? $Product->name ?? 'Item' }}">
                                <x-productbox :prodData="$Product" />
                            </li>
                            @endforeach
                            @endif
                        </ul>
                        {{-- <span class="list-more" data-brand-id="{{ $res->id }}" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
                    </div>
                </div>

                <div class="col-12 pb-2" id="loadmore" style="@if($TotalProducts <= 15)display:none @endif">
                    <a class="list-more d-block" data-page="1" data-brand-id="{{ $res->id }}" data-total="{{$TotalProducts}}" role="button" aria-label="Load more products"> Load More... </a>
                </div>

                <div class="errmsg text-center col-12 pb-5 mb-3" id="noprod" style="@if($TotalProducts > 0) display:none @endif" role="alert" aria-live="polite">Sorry, no products found.</div>
            </div>
        </div>
    </section>
    @if($res->video_show=='Yes')
        <section class="brand-video-sec pb-md-5 pt-md-5 pb-3 pt-3" role="region" aria-label="Brand Videos">
            <div class="container">
                <div class="row row10">
                    @if($cntYoutubeLink == 1)
                    <div class="col-sm-12 pb-2 pt-2">
                        <iframe class="embed-responsive-item" src="{{$youtubeLinks[0]}}" height="100%" width="100%" title="Brand Video 1" allowfullscreen></iframe>
                    </div>
                    @else
                    @if(isset($youtubeLinks[0]) && $youtubeLinks[0] != '')
                    <div class="col-sm-6 pb-2 pt-2">
                        <iframe class="embed-responsive-item" src="{{$youtubeLinks[0]}}" height="100%" width="100%" title="Brand Video 1" allowfullscreen></iframe>
                    </div>
                    @endif
                    @if(isset($youtubeLinks[1]) && $youtubeLinks[1] != '')
                    <div class="col-sm-6 pb-2 pt-2">
                        <iframe class="embed-responsive-item" src="{{$youtubeLinks[1]}}" height="100%" width="100%" title="Brand Video 2" allowfullscreen></iframe>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </section>
    @endif
    <!-- Content Part End Here -->
</main>
@endsection