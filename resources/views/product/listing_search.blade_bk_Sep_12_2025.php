@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="search-listing-page-title">
    <input type="hidden" id="token" value="{{csrf_token()}}"/>
    <input type="hidden" id="pageTitle" value="Search" />

    <!--<section class="cat_banner_new">
		<div class="container">
			<div class="position-relative"> 
				<img src="{{config('global.SITE_IMAGES')}}cat_banner_new.jpg" width="1920" height="350" />
			</div>
			<div class="over">
				<h1>{{--{{$PageTitle}}--}}<?=str_replace('backslash', '/', $PageTitle)?></h1>
			</div>
		</div>
	</section>-->
    
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            {!!$Bredcrum!!}
        </div>
    </section>
    <section class="listing_mid" aria-label="Search Product Listing Section">
        <div class="container">
            <div class="listing_left" role="region" aria-label="Product Filters">
                <div class="fra_man">
                    <div class="fra_link">
                        <div class="pt-1">
                            <h1 id="search-listing-page-title" tabindex="0">
                                {{--{{$PageTitle}}--}}<?=str_replace('backslash', '/', $PageTitle)?> 
                                <span id="total" aria-live="polite">@if($TotalProducts > 0)({{$TotalProducts}})@endif</span>
                            </h1>
                        </div>
                        <div class="separator"></div>
                    </div>
                </div>
                @if(count($Products) > 0)
                <div class="filter" data-current="" data-id="" role="region" aria-label="Filter Panel">
                    <div class="filter_top">
                        <span class="title" id="filter-by-title">Filter by:</span> 
                        <a href="javascript:void(0);" class="reset_link" data-filter="all" aria-label="Reset all filters">Reset all filters</a> 
                        <a href="#" class="filter-close d-lg-none" aria-label="Close Filters">
                            <svg class="sv-close" aria-hidden="true" role="img" width="16" height="16">
                                <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                            </svg>
                        </a> 
                    </div>
                    <div class="filter_mid">
                        @if(count($Filters) > 0)
                            @foreach($Filters as $FilterArray)
                                @foreach($FilterArray as $Filter)
                                    <x-filtersearch :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']"/>
                                @endforeach
                            @endforeach
                        @endif	
                        
                        @if(count($Products) > 0)
                        <div class="filter_acrd filter_acrd_act" role="region" aria-label="Price Range Filter">
                            <div class="filter_acrd_hd">
                                <span class="title" id="price-range-title">Price Range</span>
                                <a class="reset_link" id="reset-price" data-filter="price" aria-label="Reset Price Filter">Reset</a>
                            </div>
                            <div class="filter_acrd_con filters-p" aria-labelledby="price-range-title">
                                <div class="inner">
                                    <!--<div class="scroll">-->
                                    <input type="hidden" id="pricechange" value="0"/>
                                    <input type="hidden" id="minprice" value="{{$MinPrice}}"/>
                                    <input type="hidden" id="maxprice" value="{{$MaxPrice}}"/>
                                    <input type="hidden" id="chgminprice" value="{{$MinPrice}}"/>
                                    <input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}"/>
                                    <div class="range_slider_controller" aria-label="Price Range Slider"><div id="slider-range"></div></div>
                                    <input type="text" id="amount" readonly class="renge-price" aria-label="Selected Price Range">
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="listing_right" role="region" aria-label="Product Results">
                <div class="pb-2 text-right"> 
                    <a class="filter_btn d-block d-lg-none btn btn-primary float-left" href="javascript:void(0);" aria-label="Open Filters">
                        <!-- <svg class="filter_icon" aria-hidden="true" role="img" width="20" height="20">
							<use href="#filter_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filter_icon"></use>
						</svg> -->    
                        <i class="fa fa-filter"></i>
                        Filter
                    </a>
                    @if(count($Products) > 0)
                    <div class="select-auto">
                        <label for="selsort" class="visually-hidden">Sort Products</label>
                        <select class="select" id="selsort" aria-label="Sort Products">
                            <option>Sort By</option>
                            <option value="priceLH">Price Low to High</option>
                            <option value="priceHL">Price High to Low</option>
                            <option value="ATZ">A-Z</option>
                            <option value="ZTA">Z-A</option>
                        </select>
                        <input type="hidden" id="sortopt" value=""/>
                    </div>
                    @endif
                </div>
                <div class="pb-2">
                    <ul class="listing_grid" data-load='0' role="list" aria-label="Search Product List">
                        @foreach($Products as $key => $Product)
                            <li role="listitem" aria-label="Search Result Product {{ $loop->iteration }}"><x-productboxsearch :prodData="$Product"/></li>
                        @endforeach	
                    </ul>
                    {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
                </div>
                
                <div class="pb-2" id="list-more" style="@if($TotalProducts <= 24) display:none @endif">
                    <a class="list-more d-block" data-page='1' aria-label="Load More Search Results"> Load More... </a>
                </div>
                <div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif" align="center" aria-live="polite">Sorry, no records found</div>
            </div>
            <div class="clearfix" style="clear:both"></div>
        </div>
    </section>
</main>
@endsection