@extends('layouts.app')
@section('content')
<main class="clearfix">
	@if(@isset($Deal->deal_end_date) && !empty($Deal))
	<section class="deal-time">
		<div class="container" @if($dealOfTheWeekBanner != '') style="background-image:url({{$dealOfTheWeekBanner}});" @endif  >
			<div class="row">
				<div class="col-12 text-center">
					@if($dealOfTheWeekBanner == '')
						<h1>{{config('Settings.DEAL_PAGE_TITLE')}}</h1>
					@endif
					<div class="deal-countdown">
						<p>Deals End</p>
						<input type="hidden" id="deal_start_date" value="{{date('d-m-Y h:i:s')}}" />
						@isset($Deal->deal_end_date)
						<input type="hidden" id="deal_end_date" value="{{$Deal->deal_end_date}}" />
						@endisset

						<ul id="counter_1">
							<li><span id="days"></span><br />day</li>
							<li><span id="hrs"></span><br />Hours</li>
							<li><span id="min"></span><br />Mins</li>
							<li><span id="sec"></span><br />Secs</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	@endif
	@if(count($DealProds) > 0)
	<section class="deal-listing">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="w-100 float-left pb-md-3 pt-md-3">
						<div class="maxtwo-f deal-filternw pt-3">
							@if($test == 1)
							<a class="deal-filternw-t filter_btn">
				                Filter <span class="filter-i filter-plus">&nbsp;</span>
			              	</a>
							@else 
							<a class="deal-filternw-t">
				                Filter <span class="filter-i filter-plus">&nbsp;</span>
			              	</a>
							@endif
			              	<label class="mb-0">
				                <select id="dealsort">
									<option value="priceLH">Price Low to High</option>
									<option value="priceHL">Price High to Low</option>
									<option value="priceAZ">A-Z</option>
									<option value="priceZA">Z-A</option></select>
								<input type="hidden" id="sortopt" value="" />	
				                Sort <span class="filter-i filter-down">&nbsp;</span>
			              	</label>
						</div>
						<input type="hidden" id="is_test" value="{{$test}}">
						@if($test == 1)
						<div class="filter-tag-div mb-4">
							<ul class="ft-list">
								<!--<li><a href="javascript:void(0);" class="ft-clear-all">Clear All</a></li>-->
							</ul>
						</div>
						<!-- NEW STATIC FILTERS -->							
						@if(count($NewFilters) > 0)
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
								@foreach($NewFilters as $ProdFilters)
									@foreach($ProdFilters as $ProdFilter)
										<x-filter :filterAttr="$ProdFilter['Attr']" :filterData="$ProdFilter['Data']" :filterSelected="$ProdFilter['Selected']" :test="$test" />
									@endforeach
								@endforeach
								</div>
							</div>
							<div class="filter_bottom">
								<a href="javascript:void(0);" class="fclear-btn reset_link" id="clrAll" data-filter="all" style="display:none;" >Clear All</a>
								<a href="javascript:void(0);" id="btnfilter" class="fapply-btn btn btn-primary">Apply</a>
							</div>
							</div>
						</div>	
						@endif
						
						<!-- NEW STATIC FILTERS -->
						@else
						<div class="deal-filtersec">
							<div class="deal-filter d-flex justify-content-between align-items-center">
								<div class="deal-select">
									<div class="select-deal">
										<select class="select" id="brands">
											<option>Brands</option>
											@foreach($BrandList as $key => $Brand)
											<option value="{{$key}}" @if($SelBrand==$key) selected @endif>{{$Brand}}</option>
											@endforeach
										</select>
										<input type="hidden" id="selbrands" value="{{$SelBrand}}" />
									</div>
									<div class="select-deal">
										<select class="select" id="size">
											<option>Size</option>
											@foreach($SizeList as $key => $Size)
											<option value="{{$key}}" @if($SelSize==$key) selected @endif>{{$Size}}</option>
											@endforeach
										</select>
										<input type="hidden" id="selsize" value="{{$SelSize}}" />
									</div>
								</div>
								<div class="deal-src-sec">
									<div class="select-auto mr-2 price-rang-del">
										<div class="filter_acrd filter_acrd_act">
											<div class="filter_acrd_hd">
												<span class="title"></span>
											</div>
											<div class="filter_acrd_con">
												<div class="inner">
													<div class="scroll">
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
										</div>
									</div>
									<div class="deal-src-f">
										<input type="text" id="searchkey" name="searchkey" value="{{$SelKey}}" placeholder="Search By UPC" class="form-control">
										<x-button link="javascript:void(0);" btntext="Search" classname="btn btn-primary" bid="btnsearch" />
									</div>
								</div>
								<div class="hide-sold">
									<a href="#" class="reset_link" id="reset_filters">Reset Filters</a>
									<label class="checkbox-label">
										<div class="chebox">
											<input type="checkbox" id="chksold" {{$SelStock}}>
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
			<div id="cover-spin"></div>
			<div class="row pt-3 pt-md-5" id="dealofweek" data-load='0'>
				@foreach($DealProds as $key => $Product)
				<div class="col-md-4 col-6">
					<x-productbox :prodData="$Product" page="dealofweek"/>
				</div>
				@endforeach
			</div>
			{{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
			
            <div class="pb-2" id="list-more" style="@if($TotalProducts <= 12) display:none @endif">
				<a class="list-more d-block" data-page='1'> Load More... </a>
			</div>
            
			<div class="errmsg  pb-5 text-center" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>
		</div>
	</section>
	@else
	<section class="deal-time">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					<div class="text-center errmsg">Sorry, no records found</div>
				</div>
			</div>
		</div>
	</section>
	@endif
</main>
@endsection
