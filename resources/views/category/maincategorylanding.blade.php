@extends('layouts.app')
@section('content')
<style type="text/css">
    .scroll-filter-div{max-height:150px;overflow-y:scroll;}
</style>
<main role="main" id="main-content" tabindex="-1" aria-label="Category Main Content">
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{config('global.SITE_URL')}}">Home</a>
            <span class="active">{{$Bredcrum}}</span>
        </div>
    </section>
    <section class="cat_banner_new" role="region" aria-label="Category Banner">
        <div class="container">
            <div class="over">
                <h1 id="main-category-title">{{$PageTitle}}</h1>
            </div>
        </div>
    </section>
    <section class="listing_mid" role="region" aria-labelledby="main-category-title">
        <div class="container">
            <div class="listing_left" role="navigation" aria-label="Category List">
                <div class="fra_man">
                    <a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block" aria-label="Toggle category list">Category list</a>
                    <div class="fra_link">
                        <div>
                            <a href="{{config('global.SITE_URL')}}top-sellers/p4u/special-ts/view" class="arrow filter_main" aria-label="View Bestsellers"><strong>Bestsellers</strong></a>
                        </div>
                        <div class="pt-2">
                            <a href="{{config('global.SITE_URL')}}new-arrivals/p4u/special-na/view" class="arrow filter_main" aria-label="View Just Arrived"><strong>Just Arrived</strong></a>
                        </div>
                        @if(count($Categories) > 0)
                        <div class="pt-2">
                            <a href="javascript:void(0);" class="arrow filter_main" data-filter-key="category_id" data-filter-value="{{ $Categories[0]['category_id'] }}" aria-label="Filter by {{ $Categories[0]['category_name'] }}"><strong>{{$Categories[0]['category_name']}}</strong></a>
                            <div class="pt-1">
                                @foreach($Categories as $key => $Cat)
                                @if($key != 0)
                                <a href="{{ $Cat['link'] }}" class="arrow" data-filter-key="category_id" data-filter-value="{{ $Cat['category_id'] }}" aria-label="Filter by {{ $Cat['category_name'] }}">{{$Cat['category_name']}}</a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="listing_right">
                <div class="pb-2 text-right">
                    <div class="select-auto" role="region" aria-label="Sort Products">
                        <label for="sort_by" class="sr-only">Sort By</label>
                        <select class="select" id="sort_by" aria-label="Sort products">
                            <option>Sort By</option>
                            <option value="priceLH">Price Low to High</option>
                            <option value="priceHL">Price High to Low</option>
                            <option value="priceAZ">A-Z</option>
                            <option value="priceZA">Z-A</option>
                        </select>
                        <input type="hidden" id="sortselect" value="" />
                    </div>
                    <!-- <div class="select-auto">
                        <select class="select" id="sort_by">
                            <option>Sort By</option>
                            <option>Price Low to High</option>
                            <option>Price High to Low</option>
                            <option>A-Z</option>
                            <option>Z-A</option>
                        </select>
                    </div> -->
                </div>
                <div class="pb-2">
                    <ul class="listing_grid" data-load='0' role="list" aria-label="Product List">
                        @foreach($Products as $key => $Product)
                            <li role="listitem"><x-productbox :prodData="$Product" /></li>
                        @endforeach
                    </ul>
                    {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>--}}
                </div>

                <div class="pb-4 mb-2" id="loadmore" style="@if($TotalProducts <= 12) display:none @endif">
                    <a class="list-more d-block" data-page='1' aria-label="Load more products"> Load More... </a>
                </div>
                <div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif" role="status" aria-live="polite">No Record Found</div>
                <!-- <div class="pb-2"><a class="list-more d-block"> Load More... </a></div> -->
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
</main>
@endsection