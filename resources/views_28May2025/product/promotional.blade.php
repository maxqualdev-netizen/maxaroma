@extends('layouts.app')
@section('content')
<main class="clearfix">
	<section>
		<div class="container">
	  		<div class="row">
	  			<div class="col-12 text-center">
					<a href="{{$PromotionalData[0]->banner_link}}">
						@if($PromotionalData[0]->banner != '' && file_exists(config('global.HOME_IMAGE_PATH').$PromotionalData[0]->banner))
							<img src="{{config('global.HOME_IMAGE_URL')}}{{$PromotionalData[0]->banner}}" width="1920" height="591" class="d-none d-md-block" alt="">
						@endif
						@if($PromotionalData[0]->banner_mobile != '' && file_exists(config('global.HOME_IMAGE_PATH').$PromotionalData[0]->banner_mobile))
							<img src="{{config('global.HOME_IMAGE_URL')}}{{$PromotionalData[0]->banner_mobile}}" width="767" height="859" class="d-md-none" alt="">
						@endif
					</a>
	  			</div>
	  		</div>
	  	</div>
	</section>
	
	<section class="deal-listing">
		<div class="container">
			
			@if($PromotionalData[0]->display_product == 'Yes')
			<div class="row">
				<div class="col-12">
					<div class="deal-filter d-sm-flex justify-content-between align-items-center">
						<div class="deal-select">
							<div class="select-deal">
								<select class="select" id="brands">
									<option>Brands</option>
									@foreach($BrandList as $key => $Brand)
									<option value="{{$key}}" @if($SelBrand == $key) selected @endif >{{$Brand}}</option>
									@endforeach
								</select>
								<input type="hidden" id="selbrands" value=""/>
							</div>
							<div class="select-deal">
								<select class="select" id="size">
									<option>Size</option>
									@foreach($SizeList as $key => $Size)
									<option value="{{$Size}}" @if($SelSize == $key) selected @endif >{{$Size}}</option>
									@endforeach
								</select>
								<input type="hidden" id="selsize" value=""/>
							</div>
							<div class="select-deal">
								<select class="select" id="dealsort">
									<option>Sort By</option>
									<option value="priceLH">Price Low to High</option>
									<option value="priceHL">Price High to Low</option>
									<option value="priceAZ">A-Z</option>
									<option value="priceZA">Z-A</option>
								</select>
								<input type="hidden" id="sortopt" value=""/>
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
							</div>
							<div class="deal-src-f">
								<input type="text" id="searchkey" name="searchkey" value="{{$SelKey}}" placeholder="Search By UPC" class="form-control">	
								<x-button link="javascript:void(0);" btntext="Search" classname="btn btn-primary" bid="btnsearch"/>
							</div>
						</div>
						<div class="hide-sold">
							<a href="#" class="reset_link" id="reset_filters">Reset Filters</a>
							<label class="checkbox-label">
								<div class="chebox">
									<input type="checkbox" id="chksold" {{$SelStock}} >
									<span class="checkmark"></span>
								</div>Hide sold out
							</label>
						</div>	
					</div>
				</div>
			</div>
			@endif
			
			@if($PromotionalData[0]->display_banner == 'Yes')
			<div class="row pt-3 pt-md-5">
				@foreach($PromotionalBannerData as $key => $Banner)
					@if($Banner->banner_image != "" or  $Banner->youtube_url != "")
						<div class="col-md-4 col-sm-6 col-12 p-3">
							@if($Banner->youtube_url == "")
								@if($Banner->banner_image_url != "")
								<a href="{{$Banner->banner_image_url}}">
								@endif
								<div class="promotion-block">
									<!-- Product Thumbnail -->
									<div class="thumbnail">
										<img src="{{config('global.HOME_IMAGE_URL')}}{{$Banner->banner_image}}" alt="{{$Banner->banner_title}}" title="{{$Banner->banner_title}}"  />
									</div>
									<!-- Product Thumbnail -->
									<!-- Product Name -->
										<div class="name">{{$Banner->banner_title}}</div>
									<!-- Product Name -->
								</div>
								@if($Banner->banner_image_url != "")
								</a>
								@endif
							@else
								<div class="promotion-block">
								<!-- <a href="javascript:void(0);" id="play-video" class="thumbnail">
								<picture>
									<img src="{{config('global.HOME_IMAGE_URL')}}{{$Banner->banner_image}}" alt="{{$Banner->banner_title}}" title="{{$Banner->banner_title}}" />
								</picture>
								</a> -->
								<div class="pro-video">
									<iframe id="video" src="{{$Banner->youtube_url}}" frameborder="0" allowfullscreen></iframe>
								</div>
								<!-- Product Name -->
								<div class="name">{{$Banner->banner_title}}</div>
								<!-- Product Name -->
								</div>
							@endif
						</div>
					@endif
				@endforeach
			</div>
			@endif
			
			<div id="cover-spin"></div>
			
			@if($PromotionalData[0]->display_product == 'Yes')
			<div class="row pt-3 pt-md-5" id="promotional">
				@foreach($PromotionalProds as $key => $Product)
					<div class="col-md-4 col-6">
						<x-productbox :prodData="$Product"/>
					</div>
				@endforeach
			</div>
			<div class="pb-2" id="loadmore" style="@if($TotalProducts <= 12) display:none @endif">
				<a class="list-more d-block" data-page='1'> Load More... </a>
			</div>
			<div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif" align="center">No Record Found</div>
			@endif
		</div>
	</section>
	
</main>
@endsection
