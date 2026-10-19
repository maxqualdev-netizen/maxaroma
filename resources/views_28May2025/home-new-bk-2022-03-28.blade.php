@extends('layouts.app')
@section('content')
<main>
	@if(count($HomeMainBanners) > 0)
	<section class="home-main-banner">
		<div class="container">
			<div class="home-main-sl">
				@foreach ($HomeMainBanners as $HomeMainBanner)
				<a href="{{$HomeMainBanner['link']}}" class="slick-slide">
					<img src="{{$HomeMainBanner['home_image']}}" width="1920" height="591" class="rounded d-none d-md-block" alt="">
					@if($HomeMainBanner['mobile_image'] != '')
					<img src="{{$HomeMainBanner['mobile_image']}}" width="767" height="859" class="rounded d-md-none" alt="">
					@endif
				</a>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	   
    @if(count($NewArrivals) > 0)
	<x-slider :attr="$NewArrivalsAttr" :sliderData="$NewArrivals" :ver="$NewHomeDesign" />
	@endif

	<div class="home-product home_nwbanner pt-lg-5 pt-3">
		<div class="container">
			<div class="row row10">
				<div class="col-md-4 pb-2 pt-2 text-center"><a href="{{config('global.SITE_URL').'dealofweek.html'}}"><img src="https://staging.maxaroma.com/public/images/topdeal-test.jpg" width="600" height="600" alt=""></a></div>
				<div class="col-md-4 pb-2 pt-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x600" width="600" height="600" alt=""></a></div>
				<div class="col-md-4 pb-2 pt-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x600" width="600" height="600" alt=""></a></div>
			</div>
		</div>
	</div>
	
	@if(count($BestSellers) > 0)
	<x-slider :attr="$BestSellersAttr" :sliderData="$BestSellers" :ver="$NewHomeDesign"/> 
	@endif
	
	<div class="home-product pt-lg-5 pt-3">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="pt-3 text-center pb-4">
						<div class="main-heading">
							<h3><span>Featured Brands</span></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="home_banner">
			<div class="container">
				<div class="row row10">
					<div class="col-md-6 pt-2 pb-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x800" width="600" height="800" alt=""></a></div>
					<div class="col-md-6 pt-2 pb-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x800" width="600" height="800" alt=""></a></div>
					<div class="col-md-6 pt-2 pb-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x400" width="600" height="400" alt=""></a></div>
					<div class="col-md-6 pt-2 pb-2 text-center"><a href="#"><img src="https://via.placeholder.com/600x400" width="600" height="400" alt=""></a></div>
				</div>
			</div>
		</div>
	</div>

	<div class="home_banner pt-lg-5 pt-3">
		<div class="container">
			<div class="row row10">
				@if(count($HomeBottomBanners) > 0)
				@for($i=0; $i<=1; $i++) 
					@if(isset($HomeBottomBanners[$i]))	
						<div class="col-md-12 pb14">
							<a href="{{$HomeBottomBanners[$i]['link']}}"><img src="{{$HomeBottomBanners[$i]['home_image']}}" width="1186" height="364" alt="" /></a>
						</div>
					@endif	
				@endfor
			@endif
		</div>
	</div>
	</div>

	<div class="home_banner pb-4 pt-4">
		<div class="container">
			<!-- TrustBox widget - Carousel -->
			<div class="trustpilot-widget" data-locale="en-US" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="56b63b590000ff00058894fd" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="en">
				<a href="https://www.trustpilot.com/review/maxaroma.com" target="_blank" rel="noopener">Trustpilot</a>
			</div>
			<!-- End TrustBox widget -->
		</div>
	</div>
	@if(count($HomeBottomBanners) > 2)
	<div class="home_banner">
		<div class="container">
			<a href="{{$HomeBottomBanners[2]['link']}}"><img src="{{$HomeBottomBanners[2]['home_image']}}" alt="{{$HomeBottomBanners[2]['image_alt']}}" title="{{$HomeBottomBanners[2]['title']}}" width="1186" height="364" /></a>
		</div>
	</div>
	@endif
	@if(count($HomeMiddleBottomBanners)>0)
	<section class="home-blog">
		<div class="container">
			<div class="row row10 pb-3 pb-md-5">
				<div class="col-md-6 col-12 pt-3">
					<a href="{{$HomeMiddleBottomBanners[0]['link']}}"><img src="{{$HomeMiddleBottomBanners[0]['home_image']}}" alt="{{$HomeMiddleBottomBanners[0]['image_alt']}}" width="798" height="358" ></a>
				</div>
				@if(isset($HomeMiddleBottomBanners[1]))
				<div class="col-md-6 col-12 pt-3">
					<a href="{{$HomeMiddleBottomBanners[1]['link']}}"><img src="{{$HomeMiddleBottomBanners[1]['home_image']}}" alt="{{$HomeMiddleBottomBanners[1]['image_alt']}}" width="798" height="358"></a>
				</div>
				@endif
			</div>
		</div>
	</section>
	@endif
</main>
@endsection
<!-- TrustBox script -->
<script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
<!-- End TrustBox script -->
