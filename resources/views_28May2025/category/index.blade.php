@extends('layouts.app')
@section('content')
<main>
	<section class="breadcrumb">
		<div class="container"> 
			<a href="{{config('global.SITE_URL')}}">Home</a>
			<span class="active">{{$Bredcrum}}</span> 
		</div>
	</section>
	@if(isset($BannerImage) && $BannerImage !='')
	<section class="cat_banner border-bottom">
		<div class="container">
			<div class="cat-banner-inner"> 
				@if(isset($BannerImage) && $BannerImage !='')
				<img src="{{$BannerImage}}" class="d-none d-md-block" width="1470" height="315" alt="Niche fragrances" /> 
				@endif	
				@if(isset($BannerImageMobile) && $BannerImageMobile != '')
				<img src="{{$BannerImageMobile}}" class="d-md-none" alt="Niche fragrances" />
				@endif
				<div class="catban-links">
					<ul>
						<li><a href="#">Men</a></li>
						<li><a href="#">Women</a></li>
						<li><a href="#">Unisex</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
  @else

    <section class="cat_banner_new">
      <div class="container">
         <div class="over">
            <h1>{{ $PageTitle }} {{'('.$TotalCatProds.')'}}</h1>
         </div>
      </div>
   </section>

	@endif
	

   @if(count($NewArrivals) > 0)
	<section class="niche-frag-slider">
    <div class="container">
      <div class="frag-slide-main">
        <div class="row align-items-center">
          <div class="col-12 text-center">
          	  <div class="pt-3 text-center pb-4">
          	  	<div class="main-heading">
          	  		<h3><span>New Arrivals</span></h3>
          	  		<a href="{{$NewArrivalsAttr['SeeMore']}}" class="viewall">See More</a>
          	  	</div>
          	  </div>
          </div>
          <div class="col-12"> 
            <div class="row">
              <div class="col-12">
                <div class="way_shop p-0 p-md-4">
                  @foreach($NewArrivals as $key => $Product)
                    <x-productbox :prodData="$Product" sliderClass="slick-slide" />
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

	@if(isset($CatProducts) && count($CatProducts) > 0)
	<section class="niche-frag-page grid_view">
		<div class="container">
			<div class="border-top pt-2 mt-2">
				<div class="row">
				<div class="col-md-12 mt-0 mt-lg-1 mb-3 mb-lg-4">
					<div class="filtoolbar clearfix">
						<div class="page_view float-left">
							<ul>
								<li>
									<a class="sortmenu d-none d-md-none" href="javascript:void(0);">
										<svg class="sv-filter-i mr-2" aria-hidden="true" role="img" width="20" height="20">
											<use href="#sv-filter-i" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-filter-i"></use>
										</svg>
										Filter 
									</a> 
								</li>
							</ul>
						</div>
						{{--
						<div class="float-right">
							<div class="d-inline-block">
								<div class="select-auto">
									<select class="select" id="selsort">
										<option>Sort By</option>
										<option>Price Low to High</option>
										<option>Price High to Low</option>
										<option>A-Z</option>
										<option>Z-A</option>
									</select>
								</div>
							</div>
						</div>
						--}}
					</div>
					<div class="mt-lg-4">
						<div class="row">
							@foreach($CatProducts as $Product)
							<div class="col-xl-2 col-lg-3 col-md-3 col-6">
								<x-productbox :prodData="$Product"/>
							</div>
							@endforeach
						</div>
						<div class="row">
							<div class="col-md-4 offset-md-4">
								<a href="{{$ViewMoreLink}}" class="view-more"> View All </a> 
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</section>
	@endif

  @if(count($BestSellers) > 0)
  <section class="niche-frag-slider">
    <div class="container">
      <div class="frag-slide-main">
        <div class="row align-items-center">
          <div class="col-12 text-center">
          	<div class="pt-3 text-center pb-4">
          	  	<div class="main-heading">
          	  		<h3><span>Bestsellers</span></h3>
            			<a href="{{$BestSellersAttr['SeeMore']}}" class="viewall">See More</a>
            		</div>
            	</div>
          </div>
          <div class="col-12 order-2">
            <div class="row">
              <div class="col-12">
                <div class="way_shop p-0 p-md-4">
                  @foreach($BestSellers as $key => $Product)
                    <x-productbox :prodData="$Product" sliderClass="slick-slide" />
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

</main>
@endsection
