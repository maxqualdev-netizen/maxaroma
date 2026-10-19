@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="listing-page-title">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            {!!$Bredcrum!!}
        </div>
    </section>
    <input type="hidden" id="token" value="{{csrf_token()}}"/>
    <input type="hidden" id="pageTitle" value="{{$PageName}}" />
    @php
        $category_name = isset($Category->category_name) ? remove_special_chars($Category->category_name) : $PageName;
    @endphp 

    @if($ListFrom == 'Category')
    <input type="hidden" id="category_id" value="{{$SelCat}}"/>
    <input type="hidden" id="catName" value="{{$category_name}}"/>
    @endif
    @if($ListFrom == 'Brand')
    <input type="hidden" id="imanufacture_id" value="{{$SelImanufacturer}}"/>
    <input type="hidden" id="brandName" value="{{$category_name}}"/>
    @endif
    <section class="listing_mid" aria-label="Product Listing Section">
        <div class="container">
            <div class="listing_left" role="region" aria-label="Product Filters">
                <div class="fra_man">
                    <!--
                    <a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block">Category list</a>
                    -->
                    <div class="fra_link">
                        <div class="pt-1">
                            <h1 id="listing-page-title" tabindex="0">{{$PageTitle}} <span id="total" aria-live="polite">@if($TotalProducts > 0)({{$TotalProducts}})@endif</span></h1>
                        </div>
                        <div class="separator"></div>
                    </div>
                </div>
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
                        @if(count($Categories) > 0)
                        <div class="filter_acrd filter_acrd_act" role="region" aria-label="Category Filter">
                            <div class="filter_acrd_hd">
                                <span class="title" id="category-filter-title">Category</span> 
                                <a class="reset_link" id="reset-categories" data-filter="categories" aria-label="Reset Category Filter">Reset</a>
                            </div>
                            <div class="filter_acrd_con" aria-labelledby="category-filter-title">
                                <div class="inner">
                                    <div class="scroll" id="selcat" data-cat="@if(isset($Category->category_id)) {{$Category->category_id}} @endif">
                                        <ul id="categories" role="list" aria-label="Categories">
                                            @foreach($Categories as $Main => $SubCats)
                                                @foreach($SubCats as $Scat => $SubCat)
                                                    @if($SubCat['Level'] == '0')
                                                        <li class="maincat" role="listitem" aria-label="{{$SubCat['category_name']}}">{{$SubCat['category_name']}}</li>
                                                    @endif
                                                        
                                                    @if($SubCat['Level'] == 1)
                                                        @if(isset($SubCat['hasChild']) && $SubCat['hasChild'] == 'Yes')
                                                            <li class="maincat" style="padding-left:5px;" role="listitem" aria-label="{{$SubCat['category_name']}}">- {{$SubCat['category_name']}}</li>
                                                        @else
                                                            <li class="subcat" style="padding-left:5px;" role="listitem" aria-label="{{$SubCat['category_name']}}">
                                                                <a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" aria-label="Filter by {{$SubCat['category_name']}}">- {{$SubCat['category_name']}}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if($SubCat['Level'] == 2)
                                                        <li class="subcat" style="padding-left:15px;" role="listitem" aria-label="{{$SubCat['category_name']}}">
                                                            <a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" aria-label="Filter by {{$SubCat['category_name']}}">- {{$SubCat['category_name']}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach	
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(count($Filters) > 0)
                            @foreach($Filters as $FilterArray)
                                @foreach($FilterArray as $Filter)
                                    <x-filter :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']"/>			
                                @endforeach
                            @endforeach
                        @endif	
                        
                        <div class="filter_acrd filter_acrd_act" role="region" aria-label="Price Range Filter">
                            <div class="filter_acrd_hd">
                                <span class="title" id="price-range-title">Price Range</span> 
                                <a class="reset_link" id="reset-price" data-filter="price" aria-label="Reset Price Filter">Reset</a>
                            </div>
                            <div class="filter_acrd_con filters-p" aria-labelledby="price-range-title">
                                <div class="inner">
                                        <input type="hidden" id="pricechange" value="0"/>
                                        <input type="hidden" id="minprice" value="{{$MinPrice}}"/>
                                        <input type="hidden" id="maxprice" value="{{$MaxPrice}}"/>
                                        <input type="hidden" id="chgminprice" value="{{$MinPrice}}"/>
                                        <input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}"/>
                                        <div class="range_slider_controller" aria-label="Price Range Slider"><div id="slider-range"></div></div>
                                        <input type="text" id="amount" readonly class="renge-price" aria-label="Selected Price Range">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filter_bottom d-lg-none"><a href="javascript:void(0);" id="btnfilter" class="btn btn-primary d-block" aria-label="Apply Filters">Apply</a></div>
                </div>
            </div>
            <div class="listing_right" role="region" aria-label="Product Results">
                <div class="pb-2 text-right"> 
                    <a class="filter_btn d-block d-lg-none btn btn-primary float-left" href="javascript:void(0);" aria-label="Open Filters">
                        <svg class="filter_icon" aria-hidden="true" role="img" width="20" height="20">
                            <use href="#filter_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filter_icon"></use>
                        </svg>
                        Filter
                    </a>
                    <div class="select-auto">
                        <label for="selsort" class="visually-hidden">Sort Products</label>
                        <select class="select" id="selsort" aria-label="Sort Products">
                            <option>Sort By</option>
                            <option value="priceLH" @if(isset($sortSel) && $sortSel == 'priceLH') selected @endif>Price Low to High</option>
                            <option value="priceHL" @if(isset($sortSel) && $sortSel == 'priceHL') selected @endif>Price High to Low</option>
                            <option value="priceAZ" @if(isset($sortSel) && $sortSel == 'priceAZ') selected @endif>A-Z</option>
                            <option value="priceZA" @if(isset($sortSel) && $sortSel == 'priceZA') selected @endif>Z-A</option>
                        </select>
                        <input type="hidden" id="sortopt" value=""/>
                    </div>
                </div>
                <div class="pb-2">
                    <ul class="listing_grid" data-load='0' role="list" aria-label="Product List">
                        @foreach($Products as $key => $Product)
                            <li role="listitem" aria-label="Product {{ $loop->iteration }}"><x-productbox :prodData="$Product"/></li>
                        @endforeach	
                    </ul>
                    {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>--}}
                </div>
                
                <div class="pb-2" id="list-more" style="@if($TotalProducts <= 24) display:none @endif">
                    <a class="list-more d-block" data-page='1' aria-label="Load More Products"> Load More... </a>
                </div>
                
                <div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif" aria-live="polite">Sorry, no records found</div>
                
            </div>
            <div class="clearfix" style="clear:both;"></div>
        </div>
    </section>
</main>
@endsection