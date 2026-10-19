@extends('layouts.app')
@section('content')
<main>
	@if(count($HomeMainBanners) > 0)
	<section class="home-main-banner">
		<div class="container">
			<div class="home-main-sl">
				
				@if($Homepage_Video_Text != '' || $Homepage_Mobile_Video_Text != '')
				<div class="item">
					<!-- Desktop Video Here -->
					@if($Homepage_Video_Text != '')
					<div class="d-md-block d-none">
						<div class="fullscreen">
							<div class="video-full">
								<div class="wrapper-f">
									{!! $Homepage_Video_Text !!}
								</div>
							</div>
						</div>
					</div>
					@endif
					<!-- Mobile Video Here -->
					@if($Homepage_Mobile_Video_Text != '')
					<div class="d-md-none">
						<div class="fullscreen">
							 <div class="video-full">
								 <div class="wrapper-f">
									{!! $Homepage_Mobile_Video_Text !!}
									<!--<iframe src="https://player.vimeo.com/video/743149663?h=e18c3c74d9" width="768" height="768" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>-->
								</div>
							</div>
						</div>
					</div>
					@endif
				</div>
				@endif
				
				@foreach ($HomeMainBanners as $HomeMainBanner)
				<a href="{{$HomeMainBanner['link']}}" class="slick-slide">
					<img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMainBanner['home_image']}}" width="1920" height="591" class="rounded d-none d-md-block" alt="">
					@if($HomeMainBanner['mobile_image'] != '')
					<img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMainBanner['mobile_image']}}" width="767" height="859" class="rounded d-md-none" alt="">
					@endif
				</a>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	   
    @if(count($NewArrivals) > 0)
	<x-slider :attr="$NewArrivalsAttr" :sliderData="$NewArrivals" :ver="$NewHomeDesign" type="NewArrival" sliderLink="new-arrivals/p4u/special-na/view" />
	@endif

	<div class="home-product home_nwbanner pt-lg-5 pt-3">
		<div class="container">
			<div class="row row10 d-flex flex-wrap justify-content-center">
				@if(count($HomeWeeklyBanners)>0)
                    <div class="col-md-4 pb-2 pt-2 text-center"><a href="{{config('global.SITE_URL').'dealofweek.html'}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeWeeklyBanners[0]['home_image']}}" width="600" height="600" alt="{{$HomeWeeklyBanners[0]['image_alt']}}"></a></div>
                @endif	
                @if(count($HomeTopThree)>0)
                    @foreach($HomeTopThree as $key => $TopThree)
                        @if($key < 2)
                            <div class="col-md-4 pb-2 pt-2 text-center"><a href="{{$TopThree['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$TopThree['home_image']}}" width="600" height="600" alt="{{$TopThree['image_alt']}}"></a></div>         
                        @endif             
                    @endforeach
                @endif
			</div>
		</div>
	</div>
	
	@if(count($BestSellers) > 0)
	<x-slider :attr="$BestSellersAttr" :sliderData="$BestSellers" :ver="$NewHomeDesign"/> 
	@endif
	
    @if(isset($HomeFeatureBrands) && count($HomeFeatureBrands) > 0)
        <div class="home-product pt-lg-5 pt-3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="pt-3 text-center pb-4">
                            <div class="main-heading">
                                <h3><span>{{config('Settings.FEATURE_BRAND_TITLE')}}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home_banner">
                <div class="container">
                    <div class="row row10">
                        @foreach($HomeFeatureBrands as $key => $FeaturedBrands )
                            <div class="col-md-6 pt-2 pb-2 text-center"><a href="{{$FeaturedBrands['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$FeaturedBrands['home_image']}}" width="600" height="800" alt="{{$FeaturedBrands['image_alt']}}"></a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(count($HomeBottomBanners) > 0)
	<div class="home_banner pt-lg-5 pt-3">
		<div class="container">
			<div class="row row10">
                @for($i=0; $i<1; $i++) 
                    @if(isset($HomeBottomBanners[$i]))	
                        <div class="col-md-12 pb14">
                            <a href="{{$HomeBottomBanners[$i]['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeBottomBanners[$i]['home_image']}}" width="1186" height="364" alt="" /></a>
                        </div>
                    @endif	
                @endfor
		  </div>
	   </div>
	</div>
    @endif

	<div class="home_banner pb-4 pt-4">
		<div class="container">
			<div class="yotpo yotpo-reviews-carousel" 
 data-background-color="transparent" 
 data-mode="most_recent" 
 data-type="product" 
 data-count="9" 
 data-show-bottomline="1" 
 data-autoplay-enabled="1" 
 data-autoplay-speed="3000" 
 data-show-navigation="1">&nbsp;</div>
			<!-- TrustBox widget - Carousel -->
			{{-- <div class="trustpilot-widget" data-locale="en-US" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="56b63b590000ff00058894fd" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="en">
				<a href="https://www.trustpilot.com/review/maxaroma.com" target="_blank" rel="noopener">Trustpilot</a>
			</div> --}}
			<!-- End TrustBox widget -->
		</div>
	</div>
	@if(count($HomeBottomBanners) > 2)
	<div class="home_banner">
		<div class="container">
			<a href="{{$HomeBottomBanners[2]['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeBottomBanners[2]['home_image']}}" alt="{{$HomeBottomBanners[2]['image_alt']}}" title="{{$HomeBottomBanners[2]['title']}}" width="1186" height="364" /></a>
		</div>
	</div>
	@endif
	@if(count($HomeMiddleBottomBanners)>0)
	<section class="home-blog">
		<div class="container">
			<div class="row row10 pb-3 pb-md-5">
				<div class="col-md-6 col-12 pt-3">
					<a href="{{$HomeMiddleBottomBanners[0]['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMiddleBottomBanners[0]['home_image']}}" alt="{{$HomeMiddleBottomBanners[0]['image_alt']}}" width="798" height="358" ></a>
				</div>
				@if(isset($HomeMiddleBottomBanners[1]))
				<div class="col-md-6 col-12 pt-3">
					<a href="{{$HomeMiddleBottomBanners[1]['link']}}"><img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMiddleBottomBanners[1]['home_image']}}" alt="{{$HomeMiddleBottomBanners[1]['image_alt']}}" width="798" height="358"></a>
				</div>
				@endif
			</div>
		</div>
	</section>
	@endif
</main>
@endsection

