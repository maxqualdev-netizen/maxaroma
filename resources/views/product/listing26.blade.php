@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="listing-page-title">
    <section class="breadcrumb" aria-label="Breadcrumb">
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
    <section class="listing_mid new-listing" aria-label="Product Listing Section">
        <div class="container">
            <div class="listing_left" role="region" aria-label="Product Filters">
                <div class="listing-top">
                    <div class="fra_man">
                        <!-- <a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block">Category list</a> -->
                        <div class="fra_link">
                            <h1 id="listing-page-title" tabindex="0">{{$PageTitle}} </h1>
                            <p>Independent and artisanal houses chosen for composition, not hype — from smoked woods to salted gourmands. Every bottle sourced direct and guaranteed authentic.</p>
                            <span id="total" class="total-results" aria-live="polite">@if($TotalProducts > 0) {{$TotalProducts}}@endif Results</span>
                        </div>
                    </div>
                    <div class="start-banner">
                        <span class="star">☆</span>
                        <strong>Not sure where to start?</strong>
                        <a href="#quiz">Take the quiz →</a><b></b><a href="#notes">Shop by note →</a>
                    </div>
                    
                </div>
                
                
                <div class="main-list">
                    <div class="filter" data-current="" data-id="" role="region" aria-label="Filter Panel">
                        <div id="left_filter">
                            <div class="filter_top">
                                {{-- <a href="javascript:void(0);" class="reset_link" data-filter="all">Reset all filters</a>--}}
                                <a href="javascript:void(0);" class="filter-close" aria-label="Close Filters">
                                    <svg class="sv-close" aria-hidden="true" role="img" width="16" height="16">
                                        <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                                    </svg>
                                </a>
                            </div>
                            
                            <div class="filter_mid">
                                <div class="hide-sold">
                                    <label class="switch active mb-2 input-fcs" id="insurance">
                                        <input type="checkbox" id="chktoprated" @if($chkTopRated=='Yes' ) checked @endif>
                                        <span class="slider round"></span>
                                        <div class="switch-label">Top Rated</div>
                                    </label>
                                    <label class="switch active mb-2 input-fcs" id="insurance">
                                        <input type="checkbox" id="chkinstock" @if($chkInStockItems=='Yes' ) checked @endif>
                                        <span class="slider round"></span>
                                        <div class="switch-label">In Stock</div>
                                    </label>
                                    <label class="switch active mb-2 input-fcs" id="insurance">
                                        <input type="checkbox" id="chkSaleDeal" @if($chkOnSaleDeal=='Yes' ) checked @endif>
                                        <span class="slider round"></span>
                                        <div class="switch-label">On Sale/Deal</div>
                                    </label>
                                </div>
                                <span class="title" id="filter-by-title">Filter By:</span>
                                <div class="cat-filter" role="region" aria-labelledby="filter-by-title">
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
                                    <ul class="cf-list" id="categories" role="list" aria-label="Categories">
                                        @foreach($Categories as $Main => $SubCats)
                                            @foreach($SubCats as $Scat => $SubCat)
                                                @if($SubCat['Level'] == '0')
                                                    <li role="listitem"><a href="#" data-name="{{$SubCat['category_name']}}" class="cf-link @if(in_array($SubCat['category_id'],$SelectedCat)) cf-active @endif " id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}" aria-label="Filter by {{$SubCat['category_name']}}">{{$SubCat['category_name']}}</a></li>
                                                @endif
                                            @endforeach
                                        @endforeach

                                        {{-- <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by Women">Women</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by Unisex">Unisex</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by Niche Fragrances">Niche Fragrances</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by 8 ML Mini">8 ML Mini</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by Gift Sets">Gift Sets</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by Best Sellers">Best Sellers</a></li>
                                        <li role="listitem"><a href="#" class="cf-link" aria-label="Filter by MAX 2DAY">MAX 2DAY</a></li> --}}
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
                                    <div class="filter_acrd filter_acrd_act" role="region" aria-label="Price Range Filter">
                                        <div class="filter_acrd_hd">
                                            <span class="title" id="price-range-title">Price Range</span>
                                            <span class="fdown-icon" aria-hidden="true">
                                                <svg class="sv_farrow" aria-hidden="true" role="img" width="20" height="20">
                                                    <use href="#sv_farrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_farrow"></use>
                                                </svg>
                                            </span>
                                            <a class="reset_link" id="reset-price" data-filter="price" aria-label="Reset Price Filter">Reset</a>
                                        </div>
                                        <div class="filter_acrd_con filters-p" aria-labelledby="price-range-title">
                                            <div class="inner">
                                                <input type="hidden" id="pricechange" value="0" />
                                                <input type="hidden" id="minprice" value="{{$MinPrice}}" />
                                                <input type="hidden" id="maxprice" value="{{$MaxPrice}}" />
                                                <input type="hidden" id="chgminprice" value="{{$MinPrice}}" />
                                                <input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}" />
                                                <div class="range_slider_controller" aria-label="Price Range Slider">
                                                    <div id="slider-range"></div>
                                                </div>
                                                <input type="text" id="amount" readonly class="renge-price" aria-label="Selected Price Range">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="filter_bottom">
                                <a href="javascript:void(0);" class="fclear-btn reset_link" id="clrAll" data-filter="all" style="display:none;" aria-label="Clear All Filters">Clear All</a>
                                <a href="javascript:void(0);" id="btnfilter" class="fapply-btn btn btn-primary" aria-label="Apply Filters">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="listing_right" role="region" aria-label="Product Results">
                    <div class="filter-sort-div" role="region" aria-label="Filter and Sort Options">
                            <a class="filter_btn fs-inner" href="javascript:void(0);" aria-label="Open Filters">
                                <span class="fcount" aria-hidden="true"></span>
                                <span class="ttu">Filter</span>
                                <span class="fplus-icon" aria-hidden="true">
                                    <!-- <svg class="sv_plus" aria-hidden="true" role="img" width="15" height="15">
                                        <use href="#sv_plus" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_plus"></use>
                                    </svg> -->
                                    <svg id="sv_plus" width="15" height="15" viewBox="0 0 15 15">
                                        <path d="M7.5 3.125V11.875" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M3.125 7.5H11.875" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                            <div class="filter-tag-div" role="region" aria-label="Active Filters">
                                <ul class="ft-list" role="list" aria-label="Active Filters List">
                                    <!-- <li role="listitem"><a href="javascript:void(0);" class="ft-clear-all" aria-label="Clear All Filters">Clear All</a></li> -->
                                </ul>
                            </div>
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
                    <div class="pt-1 pb-4 errmsg text-center lheight-18 fw500" id="noprod_temp" style="text-align:center; display:none;" aria-live="polite">Sorry, there are no products available for your selections. <br>You can view the products below or try to filter with other options.<hr></div>
                    <div class="pb-2">
                        <ul class="listing_grid newproduct-box" data-load='0' role="list" aria-label="Product List">
                            <?
                                $pagenm = 'listing';
                            ?>
                            @foreach($Products as $key => $Product)
                            <li id="{{$loop->index}}" role="listitem">
                                <x-productbox26 :prodData="$Product" :prodIndex="$loop->index" :page="$pagenm" />
                            </li>
                            @endforeach
                        </ul>
                        {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>--}}
                    </div>
                    <a class="note-banner" href="#tuberose">
                            <span>
                                <span class="eyebrow">NOTE COLLECTION</span>
                                <h2>Tuberose, Canonically</h2>
                                <p>One flower, twelve interpretations — from indolic and heady to creamy and restrained. Explore the canonical Tuberose collection.</p>
                            </span>
                            <strong style="">EXPLORE THE NOTE →</strong>
                    </a>
                    <div class="pb-2 text-center" id="list-more" style="@if($TotalProducts <= 25) display:none @endif">
                        <!-- <a class="list-more" data-page='1' aria-label="Load More Products"> Load More... </a> -->
                        <a class="btn btn-primary" href="javascript:void(0)" data-page='1' aria-label="Load More Products"> Load More... </a>
                    </div>
                    <div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif" aria-live="polite">Sorry, no records found</div>
                </div>
            </div>
            <div class="clearfix" style="clear:both;"></div>
            
        </div>
    </section>
</main>
@endsection