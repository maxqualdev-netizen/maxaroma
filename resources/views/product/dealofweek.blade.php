@extends('layouts.app')
@section('content')
<main class="clearfix" role="main" id="main-content" tabindex="-1" aria-label="Deal of the Week Page">
    @if(@isset($Deal->deal_end_date) && !empty($Deal))
    <section class="deal-time" role="region" aria-label="Deal Countdown Timer">
        <div class="container" @if($dealOfTheWeekBanner != '') style="background-image:url({{$dealOfTheWeekBanner}});" @endif>
            <div class="row">
                <div class="col-12 text-center">
                    @if($dealOfTheWeekBanner == '')
                        <h1 id="deal-of-week-title">{{config('Settings.DEAL_PAGE_TITLE')}}</h1>
                    @endif
                    <div class="deal-countdown" role="region" aria-label="Deal Countdown">
                        <p>Deals End</p>
                        <input type="hidden" id="deal_start_date" value="{{date('d-m-Y h:i:s')}}" />
                        @isset($Deal->deal_end_date)
                        <input type="hidden" id="deal_end_date" value="{{$Deal->deal_end_date}}" />
                        @endisset

                        <ul id="counter_1" role="list" aria-label="Deal Countdown Timer">
                            <li role="listitem"><span id="days"></span><br />day</li>
                            <li role="listitem"><span id="hrs"></span><br />Hours</li>
                            <li role="listitem"><span id="min"></span><br />Mins</li>
                            <li role="listitem"><span id="sec"></span><br />Secs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @if(count($DealProds) > 0)
    <section class="deal-listing" role="region" aria-label="Deal of the Week Product Listing">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="w-100 float-left pb-md-3 pt-md-3">
                        <div class="maxtwo-f deal-filternw pt-3" role="region" aria-label="Deal Filters and Sort">
                            @if($test == 1)
                            <a class="deal-filternw-t filter_btn" aria-label="Open Filters">
                                Filter <span class="filter-i filter-plus" aria-hidden="true">&nbsp;</span>
                          	</a>
                            @else 
                            <a class="deal-filternw-t" aria-label="Filter">
                                Filter <span class="filter-i filter-plus" aria-hidden="true">&nbsp;</span>
                          	</a>
                            @endif
                          	<label class="mb-0" for="dealsort">
                                <select id="dealsort" aria-label="Sort Deals">
                                    <option value="priceLH">Price Low to High</option>
                                    <option value="priceHL">Price High to Low</option>
                                    <option value="priceAZ">A-Z</option>
                                    <option value="priceZA">Z-A</option>
                                </select>
                                <input type="hidden" id="sortopt" value="" />	
                                Sort <span class="filter-i filter-down" aria-hidden="true">&nbsp;</span>
                          	</label>
                        </div>
                        <input type="hidden" id="is_test" value="{{$test}}">
                        <div class="hide-sold">
                            <label class="switch active mb-2 input-fcs" id="insurance">
								<input type="checkbox" id="chktoprated">
								<span class="slider round"></span>
								</label>
                                Top Rated
								<b class="separator">|</b>
                        	<label class="switch active mb-2 input-fcs" id="insurance">
								<input type="checkbox" id="chksold">
								<span class="slider round"></span>
								</label>Hide sold out
                    	</div>
                        @if($test == 1)
                        <div class="filter-tag-div mb-4" role="region" aria-label="Active Filters">
                            <ul class="ft-list" role="list" aria-label="Active Filters List">
                                <!--<li role="listitem"><a href="javascript:void(0);" class="ft-clear-all" aria-label="Clear All Filters">Clear All</a></li>-->
                            </ul>
                        </div>
                        <!-- NEW STATIC FILTERS -->							
                        @if(count($NewFilters) > 0)
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
                                <span class="title" id="filter-by-title">Filter By:</span>
                                <div class="cat-filter" role="region" aria-labelledby="filter-by-title">
                                @foreach($NewFilters as $ProdFilters)
                                    @foreach($ProdFilters as $ProdFilter)
                                        <x-filter :filterAttr="$ProdFilter['Attr']" :filterData="$ProdFilter['Data']" :filterSelected="$ProdFilter['Selected']" :test="$test" />
                                    @endforeach
                                @endforeach
                                </div>
                            </div>
                            <div class="filter_bottom">
                                <a href="javascript:void(0);" class="fclear-btn reset_link" id="clrAll" data-filter="all" style="display:none;" aria-label="Clear All Filters">Clear All</a>
                                <a href="javascript:void(0);" id="btnfilter" class="fapply-btn btn btn-primary" aria-label="Apply Filters">Apply</a>
                            </div>
                            </div>
                        </div>	
                        @endif
                        <!-- NEW STATIC FILTERS -->
                        @else
                        <div class="deal-filtersec" role="region" aria-label="Deal Filters">
                            <div class="deal-filter d-flex justify-content-between align-items-center">
                                <div class="deal-select" role="region" aria-label="Brand and Size Filters">
                                    <div class="select-deal">
                                        <label for="brands" class="visually-hidden">Select Brand</label>
                                        <select class="select" id="brands" aria-label="Select Brand">
                                            <option>Brands</option>
                                            @foreach($BrandList as $key => $Brand)
                                            <option value="{{$key}}" @if($SelBrand==$key) selected @endif>{{$Brand}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="selbrands" value="{{$SelBrand}}" />
                                    </div>
                                    <div class="select-deal">
                                        <label for="size" class="visually-hidden">Select Size</label>
                                        <select class="select" id="size" aria-label="Select Size">
                                            <option>Size</option>
                                            @foreach($SizeList as $key => $Size)
                                            <option value="{{$key}}" @if($SelSize==$key) selected @endif>{{$Size}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="selsize" value="{{$SelSize}}" />
                                    </div>
                                </div>
                                <div class="deal-src-sec" role="region" aria-label="Price Range and Search">
                                    <div class="select-auto mr-2 price-rang-del">
                                        <div class="filter_acrd filter_acrd_act" role="region" aria-label="Price Range Filter">
                                            <div class="filter_acrd_hd">
                                                <span class="title" id="price-range-title"></span>
                                            </div>
                                            <div class="filter_acrd_con" aria-labelledby="price-range-title">
                                                <div class="inner">
                                                    <div class="scroll">
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
                                        </div>
                                    </div>
                                    <div class="deal-src-f">
                                        <label for="searchkey" class="visually-hidden">Search By UPC</label>
                                        <input type="text" id="searchkey" name="searchkey" value="{{$SelKey}}" placeholder="Search By UPC" class="form-control" aria-label="Search By UPC">
                                        <x-button link="javascript:void(0);" btntext="Search" classname="btn btn-primary" bid="btnsearch" />
                                    </div>
                                </div>
                                <div class="hide-sold" role="region" aria-label="Hide Sold Out and Reset Filters">
                                    <a href="#" class="reset_link" id="reset_filters" aria-label="Reset Filters">Reset Filters</a>
                                    <label class="checkbox-label" for="chksold">
                                        <div class="chebox">
                                            <input type="checkbox" id="chksold" {{$SelStock}} aria-checked="{{ $SelStock ? 'true' : 'false' }}">
                                            <span class="checkmark"></span>
                                        </div>Hide sold out
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>		
                </div>
            </div>
            <div id="cover-spin" aria-hidden="true"></div>
            <div class="row pt-3 pt-md-5" id="dealofweek" data-load='0' role="region" aria-label="Deal of the Week Products">
                @foreach($DealProds as $key => $Product)
                <div class="col-md-4 col-6" role="region" aria-label="Deal of the Week Product {{ $loop->iteration }}">
                    <x-productbox :prodData="$Product" page="dealofweek"/>
                </div>
                @endforeach
            </div>
            <span style="display:none;">{{$TotalProducts}}</span>
            {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
            
            <div class="pb-2" id="list-more" style="@if($TotalProducts <= 12) display:none @endif">
                <a class="list-more d-block" data-page='1' aria-label="Load More Deal of the Week Products"> Load More... </a>
            </div>
            
            <div class="errmsg  pb-5 text-center" id="noprod" style="@if($TotalProducts > 0) display:none @endif" aria-live="polite">No Record Found</div>
        </div>
    </section>
    @else
    <section class="deal-time" role="region" aria-label="No Deals Found">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="text-center errmsg" aria-live="polite">Sorry, no records found</div>
                </div>
            </div>
        </div>
    </section>
    @endif
</main>
@endsection