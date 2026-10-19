@extends('layouts.app')
@section('content')
<main>
    <section class="breadcrumb">
        <div class="container">
            {!!$Bredcrum!!}
        </div>
    </section>
    <input type="hidden" id="token" value="{{csrf_token()}}" />
    <input type="hidden" id="pageTitle" value="{{$PageName}}" />
    @php
    $category_name = isset($Category->category_name) ? remove_special_chars($Category->category_name) : $PageName;
    @endphp
    @if($ListFrom == 'Category')
    <input type="hidden" id="category_id" value="{{$SelCat}}" />
    <input type="hidden" id="catName" value="{{$category_name}}" />
    @endif
    @if($ListFrom == 'Brand')
    <input type="hidden" id="imanufacture_id" value="{{$SelImanufacturer}}" />
    <input type="hidden" id="brandName" value="{{$category_name}}" />
    @endif
    <section class="listing_mid">
        <div class="container">
            <div class="listing_left">
                <div class="listing-top">
                    <div class="fra_man">
                        <!--
                        <a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block">Category list</a>
                        -->
                        <div class="fra_link">
                            <h1>{{$PageTitle}} <span id="total" class="total-results">@if($TotalProducts > 0) {{$TotalProducts}}@endif Results</span></h1>
                        </div>
                    </div>
                    <div class="filter-sort-div">
                        <a class="filter_btn fs-inner" href="javascript:void(0);">
                            <span class="fcount"></span>
                            <span class="ttu">Filter</span>
                            <span class="fplus-icon">
                                <svg class="sv_plus" aria-hidden="true" role="img" width="15" height="15">
                                    <use href="#sv_plus" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_plus"></use>
                                </svg>
                            </span>
                        </a>
                        <div class="select-auto fs-inner">
                            <select class="select" id="selsort">
                                <option>Sort By</option>                                
                                <option value="priceLH" @if(isset($sortSel) && $sortSel == 'priceLH') selected @endif>Price Low to High</option>
                                <option value="priceHL" @if(isset($sortSel) && $sortSel == 'priceHL') selected @endif>Price High to Low</option>
                                <option value="priceAZ" @if(isset($sortSel) && $sortSel == 'priceAZ') selected @endif>A-Z</option>
                                <option value="priceZA" @if(isset($sortSel) && $sortSel == 'priceZA') selected @endif>Z-A</option>
                                <option value="latest" @if(isset($sortSel) && $sortSel == 'latest') selected @endif>Newest</option>
                            </select>
                            <input type="hidden" id="sortopt" value="" />
                        </div>
                    </div>
                    <div class="filter-tag-div mb-4">
                        <ul class="ft-list">
                            <!--<li><a href="javascript:void(0);" class="ft-clear-all">Clear All</a></li>-->
                        </ul>
                    </div>
                </div>
                
                <div class="filter" data-current="" data-id="">
                    <div id="left_filter">
                        <div class="filter_top">
                            {{-- <a href="javascript:void(0);" class="reset_link" data-filter="all">Reset all filters</a>--}}
                            <a href="javascript:void(0);" class="filter-close">
                                <svg class="sv-close" aria-hidden="true" role="img" width="16" height="16">
                                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                                </svg>
                            </a>
                        </div>
                        <div class="filter_mid">
                            <span class="title">Filter By:</span>
                            <div class="cat-filter">
                            <!-- NEW STATIC FILTERS -->
                            @if(isset($test) && $test == '1')                                
                                @if(count($NewFilters) > 0)                                    
                                    @foreach($NewFilters as $ProdFilters)                                        
                                        @foreach($ProdFilters as $ProdFilter)                                        
                                            <x-filter :filterAttr="$ProdFilter['Attr']" :filterData="$ProdFilter['Data']" :filterSelected="$ProdFilter['Selected']" :test="$test" />
                                        @endforeach
                                    @endforeach
                                @endif
                            @endif
                            <!-- NEW STATIC FILTERS -->
                            
                            @if(!isset($test) || (isset($test) && $test == '0'))    
                                <ul class="cf-list" id="categories">
                                    @foreach($Categories as $Main => $SubCats)
                                        @foreach($SubCats as $Scat => $SubCat)
                                            @if($SubCat['Level'] == '0')
                                                <li><a href="#" data-name="{{$SubCat['category_name']}}" class="cf-link @if(in_array($SubCat['category_id'],$SelectedCat)) cf-active @endif " id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}">{{$SubCat['category_name']}}</a></li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    {{--
                                    <li><a href="#" class="cf-link " alt="">Women</a></li>
                                    <li><a href="#" class="cf-link " alt="">Unisex</a></li>
                                    <li><a href="#" class="cf-link " alt="">Niche Fragrances</a></li>
                                    <li><a href="#" class="cf-link " alt="">8 ML Mini</a></li>
                                    <li><a href="#" class="cf-link " alt="">Gift Sets</a></li>
                                    <li><a href="#" class="cf-link " alt="">Best Sellers</a></li>
                                    <li><a href="#" class="cf-link " alt="">MAX 2DAY</a></li>
                                    --}}
                                </ul>
                            </div>
                                @if(count($Filters) > 0)
                                @foreach($Filters as $FilterArray)
                                @foreach($FilterArray as $Filter)
                                    @if(isset($test))
                                        <x-filter :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']" :test="$test" />
                                    @else
                                        <x-filter :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']" :test="0" />
                                    @endif
                                @endforeach
                                @endforeach
                                @endif
                                <div class="filter_acrd filter_acrd_act">
                                    <div class="filter_acrd_hd">
                                        <span class="title">Price Range</span>
                                        <span class="fdown-icon">
                                            <svg class="sv_farrow" aria-hidden="true" role="img" width="20" height="20">
                                                <use href="#sv_farrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_farrow"></use>
                                            </svg>
                                        </span>
                                        <a class="reset_link" id="reset-price" data-filter="price">Reset</a>
                                    </div>
                                    <div class="filter_acrd_con filters-p">
                                        <div class="inner">
                                            <input type="hidden" id="pricechange" value="0" />
                                            <input type="hidden" id="minprice" value="{{$MinPrice}}" />
                                            <input type="hidden" id="maxprice" value="{{$MaxPrice}}" />
                                            <input type="hidden" id="chgminprice" value="{{$MinPrice}}" />
                                            <input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}" />
                                            <div class="range_slider_controller">
                                                <div id="slider-range"></div>
                                            </div>
                                            <input type="text" id="amount" readonly class="renge-price">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="filter_bottom">
                            <a href="javascript:void(0);" class="fclear-btn reset_link" id="clrAll" data-filter="all" style="display:none;" >Clear All</a>
                            <a href="javascript:void(0);" id="btnfilter" class="fapply-btn btn btn-primary">Apply</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="listing_right">
            <div class="pt-1 pb-4 errmsg text-center lheight-18 fw500" id="noprod_temp" style="text-align:center; display:none;">Sorry, there are no products available for your selections. <br>You can view the products below or try to filter with other options.<hr></div>
                <div class="pb-2">
                    <ul class="listing_grid" data-load='0'>
                        <? 
                            $pagenm = 'listing';
                        ?> 
                        @foreach($Products as $key => $Product)
                        <li id="{{$loop->index}}">
                            <x-productbox :prodData="$Product" :prodIndex="$loop->index" :page="$pagenm" />
                        </li>
                        @endforeach
                    </ul>
                    {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>--}}
                </div>
                <div class="pb-2" id="list-more" style="@if($TotalProducts <= 25) display:none @endif">
                    <a class="list-more d-block" data-page='1'> Load More... </a>
                </div>
                <div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif">Sorry, no records found</div>
            </div>
            <div class="clearfix" style="clear:both;"></div>
        </div>
    </section>
</main>
@endsection
