@extends('layouts.app')
@section('content')
<main>
	<section class="breadcrumb">
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
	<section class="listing_mid">
		<div class="container">
			<div class="listing_left">
				<div class="fra_man">
					<!--
					<a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block">Category list</a>
					-->
					<div class="fra_link">
						<div class="pt-1">
							<h1>{{$PageTitle}} <span id="total">@if($TotalProducts > 0)({{$TotalProducts}})@endif</span></h1>
						</div>
						<div class="separator"></div>
					</div>
				</div>
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
						@if(count($Categories) > 0)
						<div class="filter_acrd filter_acrd_act">
							<div class="filter_acrd_hd">
								<span class="title">Category</span> 
								<a class="reset_link" id="reset-categories" data-filter="categories" >Reset</a>
							</div>
							<div class="filter_acrd_con">
								<div class="inner">
									<div class="scroll" id="selcat" data-cat="@if(isset($Category->category_id)) {{$Category->category_id}} @endif">
										<ul id="categories">
											@foreach($Categories as $Main => $SubCats)
												@foreach($SubCats as $Scat => $SubCat)
													@if($SubCat['Level'] == '0')
														<li class="maincat">{{$SubCat['category_name']}}</li>
													@endif
														
													@if($SubCat['Level'] == 1)
														@if(isset($SubCat['hasChild']) && $SubCat['hasChild'] == 'Yes')
															<li class="maincat" style="padding-left:5px;">- {{$SubCat['category_name']}}</li>
														@else
															<li class="subcat" style="padding-left:5px;"><a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}">- {{$SubCat['category_name']}}</a></li>
														@endif
													@endif
													@if($SubCat['Level'] == 2)
														<li class="subcat" style="padding-left:15px;"><a href="#" class="@if(in_array($SubCat['category_id'],$SelectedCat)) catactive @endif" id="cat-{{$SubCat['category_id']}}" data-id="{{$SubCat['category_id']}}">- {{$SubCat['category_name']}}</a></li>
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
						
						<div class="filter_acrd filter_acrd_act">
							<div class="filter_acrd_hd">
								<span class="title">Price Range</span> 
								<a class="reset_link" id="reset-price" data-filter="price">Reset</a>
							</div>
							<div class="filter_acrd_con filters-p">
								<div class="inner">
										<input type="hidden" id="pricechange" value="0"/>
										<input type="hidden" id="minprice" value="{{$MinPrice}}"/>
										<input type="hidden" id="maxprice" value="{{$MaxPrice}}"/>
										<input type="hidden" id="chgminprice" value="{{$MinPrice}}"/>
										<input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}"/>
										<div class="range_slider_controller"><div id="slider-range"></div></div>
										<input type="text" id="amount" readonly class="renge-price">
								</div>
							</div>
						</div>
					</div>
					<div class="filter_bottom d-lg-none"><a href="javascript:void(0);" id="btnfilter" class="btn btn-primary d-block">Apply</a></div>
				</div>
			</div>
			<div class="listing_right">
				<div class="pb-2 text-right"> 
					<a class="filter_btn d-block d-lg-none btn btn-primary float-left" href="javascript:void(0);">
						<svg class="filter_icon" aria-hidden="true" role="img" width="20" height="20">
							<use href="#filter_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filter_icon"></use>
						</svg>
						Filter
					</a>
					<div class="select-auto">
						<select class="select" id="selsort">
							<option>Sort By</option>
							<option value="priceLH">Price Low to High</option>
							<option value="priceHL">Price High to Low</option>
							<option value="priceAZ">A-Z</option>
							<option value="priceZA">Z-A</option>
						</select>
						<input type="hidden" id="sortopt" value=""/>
					</div>
				</div>
				<div class="pb-2">
					<ul class="listing_grid" data-load='0'>
						@foreach($Products as $key => $Product)
							<li><x-productbox :prodData="$Product"/></li>
						@endforeach	
					</ul>
					{{-- <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>--}}
				</div>
				
				<div class="pb-2" id="list-more" style="@if($TotalProducts <= 12) display:none @endif">
					<a class="list-more d-block" data-page='1'> Load More... </a>
				</div>
                
				<div class="pb-2 errmsg" id="noprod" style="@if($TotalProducts > 0) display:none @endif">Sorry, no records found</div>
				
			</div>
			<div class="clearfix" style="clear:both;"></div>
		</div>
	</section>
</main>
@endsection