@extends('layouts.app')
@section('content')
<main class="clearfix" role="main" id="main-content" tabindex="-1" aria-label="Max Two Day Shipping Page">
    
    @if(count($MaxtwoProds) > 0)
    <section class="deal-listing" role="region" aria-label="Max Two Day Product Listing">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="w-100 float-left pb-md-3 pt-md-3">
                        <div class="deal-filternw maxtwo-f pt-3" role="region" aria-label="Max Two Day Filters and Sort">
                            <div class="max2-logo">
                                <img width="100" height="74" src="{{config('global.SITE_IMAGES')}}2max-shipping.jpg" alt="Max Two Day Shipping Logo">
                            </div>
                            <a class="deal-filternw-t" aria-label="Open Filters">
                                Filter <span class="filter-i filter-plus" aria-hidden="true">&nbsp;</span>
                            </a>
                            <label class="mb-0" for="dealsort">
                                <select id="dealsort" aria-label="Sort Max Two Day Products">
                                    <option value="priceLH">Price Low to High</option>
                                    <option value="priceHL">Price High to Low</option>
                                    <option value="priceAZ">A-Z</option>
                                    <option value="priceZA">Z-A</option>
                                </select>
                                <input type="hidden" id="sortopt" value="" />	
                                Sort <span class="filter-i filter-down" aria-hidden="true">&nbsp;</span>
                          	</label>
                        </div>
                        <div class="deal-filtersec" role="region" aria-label="Max Two Day Filter Panel">
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
                    </div>		
                </div>
            </div>
            <div id="cover-spin" aria-hidden="true"></div>
            <div class="row pt-3 pt-md-5" id="dealofweek" data-load='0' role="region" aria-label="Max Two Day Products">
                @foreach($MaxtwoProds as $key => $Product)
                <div class="col-md-4 col-6" role="region" aria-label="Max Two Day Product {{ $loop->iteration }}">
                    <x-productbox :prodData="$Product" page="maxtwoday"/>
                </div>
                @endforeach
            </div>
            {{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
            
            <div class="pb-2" id="list-more" style="@if($TotalProducts <= 12) display:none @endif">
                <a class="list-more d-block" data-page='1' aria-label="Load More Max Two Day Products"> Load More... </a>
            </div>
            <div class="errmsg  pb-5 text-center" id="noprod" style="@if($TotalProducts > 0) display:none @endif" aria-live="polite">No Record Found</div>
        </div>
    </section>
    @else
    <section class="deal-time" role="region" aria-label="No Max Two Day Deals Found">
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