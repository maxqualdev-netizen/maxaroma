@extends('layouts.app')
@section('content')
<main>
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
	
	<section class="breadcrumb">
		<div class="container">
			{!!$Bredcrum!!}
		</div>
	</section>
		{{-- <section class="cat_banner_new">
			<div class="container">
			<div class="over">
				<h1>{{ $PageTitle }}</h1>
			</div>
			</div>
		</section>  --}}
	<section class="listing_mid">
		<div class="container">
			<div class="listing_left">
				<div class="fra_man">
					<div class="fra_link">
						<div class="pt-1">
							<h1>{{--{{$PageTitle}}--}}<?=str_replace('backslash', '/', $PageTitle)?> <span id="total">@if($TotalProducts > 0)({{$TotalProducts}})@endif</span></h1>
						</div>
						<div class="separator"></div>
					</div>
				</div>
				@if(count($Products) > 0)
				<div class="filter" data-current="" data-id="">
					<div class="filter_top">
						<span class="title">Filter by:</span> 
						<a href="javascript:void(0);" class="reset_link" data-filter="all">Reset all filters</a> 
						<a href="#" class="filter-close d-lg-none">
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
						<div class="filter_acrd filter_acrd_act">
							<div class="filter_acrd_hd">
								<span class="title">Price Range</span>
								<a class="reset_link" id="reset-price" data-filter="price">Reset</a>
							</div>
							<div class="filter_acrd_con filters-p">
								<div class="inner">
									<!--<div class="scroll">-->
										<input type="hidden" id="pricechange" value="0"/>
										<input type="hidden" id="minprice" value="{{$MinPrice}}"/>
										<input type="hidden" id="maxprice" value="{{$MaxPrice}}"/>
										<input type="hidden" id="chgminprice" value="{{$MinPrice}}"/>
										<input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}"/>
										<div class="range_slider_controller"><div id="slider-range"></div></div>
										<input type="text" id="amount" readonly class="renge-price">
									<!--</div>-->
								</div>
							</div>
						</div>
						@endif
					</div>
					
				</div>
				@endif
			</div>
			<div class="listing_right">
				<div class="pb-2 text-right"> 
					<a class="filter_btn d-block d-lg-none btn btn-primary float-left" href="javascript:void(0);">
						<!--<svg class="filter_icon" aria-hidden="true" role="img" width="20" height="20">
							<use href="#filter_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filter_icon"></use>
						</svg>-->
						<i class="fa fa-filter"></i>
						Filter
					</a>
					@if(count($Products) > 0)
					<div class="select-auto">
						<select class="select" id="selsort">
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
					<ul class="listing_grid" data-load='0'>
						@foreach($Products as $key => $Product)
							<li><x-productboxsearch :prodData="$Product"/></li>
						@endforeach	
					</ul>
					{{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span> --}}
				</div>
				
				<div class="pb-2" id="list-more" style="@if($TotalProducts <= 12) display:none @endif">
					<a class="list-more d-block" data-page='1'> Load More... </a>
				</div>
				<div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif" align="center">Sorry, no records found</div>
				
			</div>
			<div class="clearfix" style="clear:both"></div>
		</div>
	</section>
</main>
@endsection
