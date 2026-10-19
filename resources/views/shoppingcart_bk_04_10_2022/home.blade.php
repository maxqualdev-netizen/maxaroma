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
	<section class="home-middle-top">
		<div class="container">
			<div class="home-whyshop-sl">
				<div class="whyshop-box"><img src="{{config('global.SITE_IMAGES')}}quality_product.png" width="75" height="75px" alt=""> <span>Quality Products</span>
					<p>100% Authentic</p>
				</div>
				<div class="whyshop-box"> <img src="{{config('global.SITE_IMAGES')}}stress_free.png"  width="75" height="75px" alt=""> <span>Stress-Free Shopping</span>
					<p>Secure Shopping Having Trouble? Live Chat support is here to help!</p>
				</div>
				<div class="whyshop-box"> <img src="{{config('global.SITE_IMAGES')}}premium_service.png" width="75" height="75px" alt=""> <span>Premium Customer Service</span>
					<p>Fast and Easy Returns</p>
				</div>
			</div>
		</div>
	</section>
	@if(count($NewArrivals) > 0)
	<x-slider :attr="$NewArrivalsAttr" :sliderData="$NewArrivals" />
	@endif

	@if(count($HomeWeeklyBanners)>0 && isset($Deal))
	<section class="home-weekly-sale">
		<div class="container">
			<div class="position-relative">
				<input type="hidden" id="deal_start_date" value="{{date('d-m-Y h:i:s')}}" />
				<input type="hidden" id="deal_end_date" value="{{optional($Deal)->deal_end_date}}" />
				<a href="{{config('global.SITE_URL').'dealofweek.html'}}" ><img src="{{$HomeWeeklyBanners[0]['home_image']}}" width="1178" height="356" class="d-none d-md-block" alt="" /></a>
				@if($HomeWeeklyBanners[0]['mobile_image'] != '')
				<a href="{{config('global.SITE_URL').'dealofweek.html'}}"><img src="{{$HomeWeeklyBanners[0]['mobile_image']}}" width="767" height="859" class="d-md-none" alt="" /></a>
				@endif
				<div class="over">
					<ul id="counter_1">
						<li><span class="count">00</span><span class="count_name">Days</span></li>
						<li><span class="count">00</span><span class="count_name">Hours</span></li>
						<li><span class="count">00</span><span class="count_name">Mins</span></li>
						<li><span class="count">00</span><span class="count_name">Secs</span></li>
					</ul>
					<div><a href="{{config('global.SITE_URL').'dealofweek.html'}}" class="btn btn-primary">START SHOP NOW</a></div>
				</div>
			</div>
		</div>
	</section>
	@endif
	@if(count($BestSellers) > 0)
	<x-slider :attr="$BestSellersAttr" :sliderData="$BestSellers" />
	@endif
	<div class="home_banner">
		<div class="container">
			<div class="row row10">
				@if(count($HomeCategoryBanners)>0)
				<div class="col-md-6">
					<div class="pb14">
						<a href="{{$HomeCategoryBanners[0]->main_link}}"><img src="{{$HomeCategoryBanners[0]->banner_image}}" width="860" height="1221" alt="" /></a>
					</div>
				</div>
				<div class="col-md-6">
					<div class="pb14"><a href="{{$HomeCategoryBanners[1]->main_link}}"><img src="{{$HomeCategoryBanners[1]->banner_image}}" width="755" height="525" alt="" /></a></div>
					<div class="pb14"><a href="{{$HomeCategoryBanners[2]->main_link}}"><img src="{{$HomeCategoryBanners[2]->banner_image}}" width="755" height="525" alt="" /></a></div>
				</div>
				@endif
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
	<section class="home_lets">
		<div class="container">
			<h3>Let's be friends</h3>
			<p><strong>Follow Us: <a target="_blank" href="https://www.instagram.com/maxaroma/">@Maxaroma</a></strong></p>
			<p><strong>Share Your Scents: <a target="_blank" href="https://www.instagram.com/maxaroma/">#Maxaroma</a></strong></p>
			<p>We obsessively watch those hashtags, share a picture &amp; you might be featured :)</p>
		</div>
	</section>
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
