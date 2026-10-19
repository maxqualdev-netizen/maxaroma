@extends('layouts.app')
@section('content')
<style>
	.brands-banner.brands-bnrslider .brands-content.box-imagebg {
		background-image:url('{{ config(' global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->top_sec_image}}') !important;
	}
</style>
<main>
	<div class="brand-newpage">
		@if($BrandDetail[0]->PageSectionStatus['sec-1'] == '1' && (!empty($BrandDetail[0]->brand_logo) || !empty($BrandDetail[0]->top_sec_slider_image1) || !empty($BrandDetail[0]->top_sec_slider_image2) || !empty($BrandDetail[0]->top_sec_slider_image3)))
		<section class="brands-banner brands-bnrslider brandpage-space" role="region" aria-labelledby="explore-ojar-heading">
			<div class="container">
				<div class="brands-content box-imagebg">
					<div class="brands-text">
						@if(!empty($BrandDetail[0]->brand_logo))
						<div class="brands-logo">
							<h2 id="explore-ojar-heading" class="logo-main">
								<img src="{{ config('global.SPEED_SIZE_URL')}}{{ $BrandDetail[0]->brand_logo }}" alt="{{ $BrandDetail[0]->vmanufacture }}" loading="lazy" width="290" height="80">
							</h2>
						</div>
						@endif
					</div>
					@if(!empty($BrandDetail[0]->top_sec_slider_image1) || !empty($BrandDetail[0]->top_sec_slider_image2) || !empty($BrandDetail[0]->top_sec_slider_image3))
					<div class="brands-image-wrapper">
						<div class="brand-bnrslider" role="region" aria-roledescription="carousel" aria-label="{{ $BrandDetail[0]->vmanufacture }}" aria-live="polite">
							@if(!empty($BrandDetail[0]->top_sec_slider_image1))
							<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->top_sec_slider_image1}}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection showcase" class="brands-image" loading="lazy" width="830" height="880">
							@endif
							@if(!empty($BrandDetail[0]->top_sec_slider_image2))
							<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->top_sec_slider_image2}}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection showcase" class="brands-image" loading="lazy" width="830" height="880">
							@endif
							@if(!empty($BrandDetail[0]->top_sec_slider_image3))
							<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->top_sec_slider_image3}}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection showcase" class="brands-image" loading="lazy" width="830" height="880">
							@endif
						</div>
					</div>
					@endif
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-2'] == '1' && ((isset($ListSecProducts) && count($ListSecProducts)>0) || !empty($BrandDetail[0]->list_sec_image)))
		<section class="brand-expore-coll brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection">
			<div class="container">
				<div class="row ">
					<div class="col-12">
						<div class="row orderchange-mob">
							@if(!empty($BrandDetail[0]->list_sec_image))
							<div class="col-lg-3 col-md-5">
								<div class="prd-leftbanner">
									<a href="javascript:void(0);" aria-label="View full {{ $BrandDetail[0]->vmanufacture }} fragrance collection banner">
										<picture>
											<source
												media="(max-width: 767px)"
												srcset="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->list_sec_mob_image}}">
											<img
												src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->list_sec_image}}"
												loading="lazy"
												alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection promotional banner"
												width="380"
												height="470">
										</picture>
									</a>
								</div>
							</div>
							@endif
							@if(isset($ListSecProducts) && count($ListSecProducts)>0)
							<div class="@if(!empty($BrandDetail[0]->list_sec_image)) col-lg-9 col-md-7 @else col-12 @endif pb-md-0 pb-5">
								<div class="brand-ex-list">
									<div class="brand-slider" role="region" aria-roledescription="carousel" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance products" aria-live="polite">
										<div class="brand-page-listul @if(!empty($BrandDetail[0]->list_sec_image)) prd-grid-3 @else prd-grid-4 @endif brandProducts-sl" data-slider="banner-withslider">
											@foreach($ListSecProducts as $key => $Product)
											@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
											@endforeach
										</div>
									</div>
									<div class="brand-slider-progress show-767" aria-hidden="true">
										<div class="bar" style="width:0%"></div>
									</div>
								</div>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-3'] == '1' && (!empty($BrandDetail[0]->history_sec_image) || !empty($BrandDetail[0]->brand_history)))
		<section class="brands-banner brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance brand story">
			<div class="container">
				<div class="brands-content box-greybg">
					<div class="brands-image-wrapper image-rightbg">
						@if(!empty($BrandDetail[0]->history_sec_image))
						<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->history_sec_image}}" alt="{{ $BrandDetail[0]->vdetail }}" class="brands-image" loading="lazy" width="880" height="690">
						@endif
					</div>
					<div class="brands-text">
						<p class="brands-description m-f16">{!! $BrandDetail[0]->brand_history !!}</p>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-4'] == '1' && (!empty($BrandDetail[0]->brand_video) || !empty($BrandDetail[0]->brand_video_desc)))
		<section class="brands-banner brandpage-space brands-banner-video" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance brand story">
			<div class="container">
				<div class="brands-content box-greybg">
					@if(!empty($BrandDetail[0]->brand_video))
					<div class="brands-image-wrapper image-rightbg">
						@if($BrandDetail[0]->brand_video_type == 'youtube')
						<div class="fullscreen">
							<div class="video-full">
								<iframe width="100%" height="500" frameborder="0" allow="autoplay; encrypted-media" src="{{ $BrandDetail[0]->brand_video }}"></iframe>
							</div>
						</div>
						@else
						<div class="fullscreen">
							<div class="video-full">
								<video class="video-fullview" playsinline="" autoplay="" muted="" loop="" preload="auto" src="{{ config('global.SPEED_SIZE_URL')}}{{ $BrandDetail[0]->brand_video}}" aria-label="{{ $BrandDetail[0]->vmanufacture }} brand story video presentation"></video>
							</div>
						</div>
						@endif
					</div>
					@endif
					<div class="brands-text">
						<p class="brands-description m-f16">{{ remove_html_entities($BrandDetail[0]->brand_video_desc) }}</p>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-5'] == '1' && (!empty($BrandDetail[0]->sec5_information) || !empty($BrandDetail[0]->sec5_image_1) || !empty($BrandDetail[0]->sec5_image_2)))
		<section class="brands-banner  brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} Utilizing traditional">
			<div class="container">
				<div class="brands-content box-greybg">

					<div class="brands-text text-md-left text-center">
						<h2 class="brandcommom-hd d-md-none">Header Here</h2>
						<p class="brands-description m-f16">{{ remove_html_entities($BrandDetail[0]->sec5_information) }}</p>
						{{--<p class="brands-description m-f16">Utilizing traditional perfumery methods and the highest quality materials.</p>--}}
						<a href="#" class="btn btn-secondary brand-btn d-md-none fit-content mb-5" aria-label="Add to cart" tabindex="0">Add to cart</a>
					</div>
					@if(!empty($BrandDetail[0]->sec5_image_1) || !empty($BrandDetail[0]->sec5_image_2))
					<div class="brands-image-wrapper twoimg-col">
						@if(!empty($BrandDetail[0]->sec5_image_1))
						<img src="{{ config('global.SPEED_SIZE_URL')}}{{ $BrandDetail[0]->sec5_image_1 }}" alt="{{ $BrandDetail[0]->vmanufacture }}" class="brands-image" loading="lazy" width="600" height="840">
						@endif
						@if(!empty($BrandDetail[0]->sec5_image_2))
						<img src="{{ config('global.SPEED_SIZE_URL')}}{{ $BrandDetail[0]->sec5_image_2 }}" alt="{{ $BrandDetail[0]->vmanufacture }}" class="brands-image" loading="lazy" width="600" height="600">
						@endif
					</div>
					@endif
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-6'] == '1' && isset($MiddleSecProducts) && count($MiddleSecProducts)>0)
		<section class="brand-expore-coll brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="brand-ex-list">
							<div class="brand-slider">
								<div class="brand-page-listul prd-grid-4 brandProducts-sl" data-slider="brand-itemslider">
									@if(isset($MiddleSecProducts) && count($MiddleSecProducts)>0)
									@foreach($MiddleSecProducts as $key => $Product)
									@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
									@endforeach
									@endif
								</div>
							</div>
							<div class="brand-slider-progress show-767" aria-hidden="true">
								<div class="bar" style="width:0%"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-7'] == '1' && (isset($BottomSecProducts) && count($BottomSecProducts)>0 || !empty($BrandDetail[0]->bottom_sec_image)))
		<section class="brands-banner prd-brandsbanner-sec brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation">
			<div class="container">
				<div class="brands-content orderchange-mob">
					<div class="brands-text">
						@if(isset($BottomSecProducts) && count($BottomSecProducts)>0)
						@foreach($BottomSecProducts as $key => $Product)
						@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
						@endforeach
						@endif
					</div>
					<div class="brands-image-wrapper">
						<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->bottom_sec_image}}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation" class="brands-image" loading="lazy" width="880px" height="790">
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-8'] == '1' && (!empty($BrandDetail[0]->sec8_image) || !empty($BrandDetail[0]->sec8_information)))
		<section class="brands-banner brandpage-space" role="region" aria-labelledby="explore-ojar-heading">
			<div class="container">
				<div class="brands-content box-greybg">
					<div class="brands-text">
						@if(!empty($BrandDetail[0]->brand_logo))
						<div class="brands-logo">
							<h2 id="explore-ojar-heading" class="logo-main">
								<img src="{{ config('global.SPEED_SIZE_URL')}}{{ $BrandDetail[0]->brand_logo }}" alt="{{ $BrandDetail[0]->vmanufacture }}" loading="lazy" width="290" height="80">
							</h2>
						</div>
						@endif
						@if(!empty($BrandDetail[0]->sec8_information))
						<p class="brands-description">{{ remove_html_entities($BrandDetail[0]->sec8_information) }}</p>
						@endif
					</div>
					@if(!empty($BrandDetail[0]->sec8_image))
					<div class="brands-image-wrapper brands-image-wrapper pl-md-0 pr-md-0 pl-3 pr-3 pb-md-0 pb-4">
						<img src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->sec8_image}}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection showcase" class="brands-image" loading="lazy" width="830" height="880">
					</div>
					@endif
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-9'] == '1' && ((isset($Sec9Products) && count($Sec9Products)>0) || !empty($BrandDetail[0]->sec9_image)))
		<section class="brand-expore-coll brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="row  orderchange-mob">
							@if(isset($Sec9Products) && count($Sec9Products)>0)
							<div class="@if(!empty($BrandDetail[0]->sec9_image)) col-lg-9 col-md-7 @else col-12 @endif">
								<div class="brand-ex-list">
									<div class="brand-slider" role="region" aria-roledescription="carousel" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance products" aria-live="polite">
										<div class="brand-page-listul @if(!empty($BrandDetail[0]->sec9_image)) prd-grid-3 @else prd-grid-4 @endif brandProducts-sl" data-slider="banner-withslider">
											@foreach($Sec9Products as $key => $Product)
												@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
											@endforeach
										</div>
									</div>
									<div class="brand-slider-progress show-767" aria-hidden="true">
										<div class="bar" style="width:0%"></div>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($BrandDetail[0]->sec9_image))
							<div class="col-lg-3 col-md-5 pb-md-0 pb-5">
								<div class="prd-leftbanner 1234">
									<a href="javascript:void(0);" aria-label="View full {{ $BrandDetail[0]->vmanufacture }} fragrance collection banner">
										<picture>
											<source
												media="(max-width: 767px)"
												srcset="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->sec9_mob_image}}">
											<img
												src="{{ config('global.SPEED_SIZE_URL')}}{{$BrandDetail[0]->sec9_image}}"
												loading="lazy"
												alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection promotional banner"
												width="380"
												height="470">
										</picture>
									</a>
								</div>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-10'] == '1' && isset($Sec10Products) && count($Sec10Products)>0)
		<section class="brand-expore-coll brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="brand-ex-list">
							<div class="brand-slider">
								<div class="brand-page-listul prd-grid-4 brandProducts-sl" data-slider="brand-itemslider">
									@if(isset($Sec10Products) && count($Sec10Products)>0)
										@foreach($Sec10Products as $key => $Product)
											@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
										@endforeach
									@endif
								</div>
							</div>
							<div class="brand-slider-progress show-767" aria-hidden="true">
								<div class="bar" style="width:0%"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-11'] == '1' && ((isset($Sec11Products) && count($Sec11Products)>0) || !empty($BrandDetail[0]->sec11_image)))
		<section class="brands-banner prd-brandsbanner-sec brandpage-space" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation">
			<div class="container">
				<div class="row silder-content-box align-items-center">
					@if(!empty($BrandDetail[0]->sec11_image))
					<div class="col-lg-6 col-md-7">
						<div class="brands-image-wrapper">
							<img src="{{ $BrandDetail[0]->sec11_image }}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation" class="brands-image" loading="lazy" width="770" height="600">
						</div>
					</div>
					@endif
					@if(isset($Sec11Products) && count($Sec11Products)>0)
					<div class="@if(!empty($BrandDetail[0]->sec11_image)) col-lg-6 col-md-5 @else col-12 @endif">
						<div class="brand-ex-list">
							<div class="brand-page-listul prd-grid-2 brandProducts-sl" data-slider="brand-2itemslider">
								@if(isset($Sec11Products) && count($Sec11Products)>0)
									@foreach($Sec11Products as $key => $Product)
										@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
									@endforeach
								@endif
							</div>
							<div class="brand-slider-progress show-767" aria-hidden="true">
								<div class="bar" style="width:0%"></div>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</section>
		@endif

		@if($BrandDetail[0]->PageSectionStatus['sec-12'] == '1' && ((isset($Sec12Products) && count($Sec12Products)>0) || !empty($BrandDetail[0]->sec12_image)))
		<section class="brands-banner prd-brandsbanner-sec" aria-label="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation">
			<div class="container">
				<div class="row silder-content-box align-items-center">
					@if(!empty($BrandDetail[0]->sec12_image))
					<div class="col-lg-6 col-md-7 order-md-2">
						<div class="brands-image-wrapper">
							<img src="{{ $BrandDetail[0]->sec12_image }}" alt="{{ $BrandDetail[0]->vmanufacture }} fragrance collection presentation" class="brands-image" loading="lazy" width="770" height="600">
						</div>
					</div>
					@endif
					@if(isset($Sec12Products) && count($Sec12Products)>0)
					<div class="@if(!empty($BrandDetail[0]->sec12_image)) col-lg-6 col-md-5 @else col-12 @endif">
						<div class="brand-ex-list">
							<div class="brand-page-listul  prd-grid-2 brandProducts-sl" data-slider="brand-2itemslider">
									@if(isset($Sec12Products) && count($Sec12Products)>0)
										@foreach($Sec12Products as $key => $Product)
											@include('brand.maxaroma-brand-productbox', ['Product' => $Product])
										@endforeach
									@endif
							</div>
							<div class="brand-slider-progress show-767" aria-hidden="true">
								<div class="bar" style="width:0%"></div>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</section>
		@endif

	</div>
	<script>
		$('.brand-bnrslider').slick({
			lazyLoad: 'ondemand',
			dots: false,
			infinite: false,
			speed: 300,
			arrows: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			accessibility: true,
			nextArrow: '<button class="slick-next round-btn-sl"  aria-label="Next Slide"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl"  aria-label="Previous Slide" ><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
	</script>
<script>
    (function($) {
        $(function() {

            if (typeof $.fn.slick === 'undefined') {
                console.error('Slick.js not loaded.');
                return;
            }

            function initSlider($slider, options) {
                var $bar = $slider.closest('.brand-ex-list').find('.brand-slider-progress .bar');
                var $progressWrap = $slider.closest('.brand-ex-list').find('.brand-slider-progress');
                var rafId = null;

                function getTranslateX($track) {
                    var transform = $track.css('transform');
                    if (!transform || transform === 'none') return 0;
                    var m = transform.match(/matrix.*\((.+)\)/);
                    if (!m) return 0;
                    var vals = m[1].split(',');
                    return Math.abs(parseFloat(vals[4]) || 0);
                }

                function updateProgress() {
                    if (!$slider.hasClass('slick-initialized')) return;

                    var slickObj = $slider.slick('getSlick');
                    var $track = $slider.find('.slick-track');

                    var translateX = getTranslateX($track);
                    var slideWidth = slickObj.slideWidth || 0;
                    var slideCount = slickObj.slideCount || 0;
                    var slidesToShow = slickObj.options.slidesToShow || 1;

                    var totalWidth = slideWidth * slideCount;
                    var visibleWidth = slideWidth * slidesToShow;
                    var maxOffset = Math.max(0, totalWidth - visibleWidth);

                    var percent = maxOffset > 0 ? (translateX / maxOffset) * 100 : 0;
                    $bar.css({ width: percent + '%' });

                    rafId = requestAnimationFrame(updateProgress);
                }

                function startProgress() {
                    if (rafId) cancelAnimationFrame(rafId);

                    if (!$slider.hasClass('slick-initialized')) return;

                    var slickObj = $slider.slick('getSlick');
                    var slideCount = slickObj.slideCount || 0;
                    var slidesToShow = slickObj.options.slidesToShow || 1;

                    if (slideCount <= slidesToShow) {
                        $progressWrap.hide();
                        $bar.css({ width: '0%' });
                        return;
                    }

                    $progressWrap.show();
                    updateProgress();
                }

                function stopProgress() {
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }
                }

                function initOrDestroy() {
                    var isMobile = window.innerWidth <= 1024;

                    if (isMobile) {
                        if (!$slider.hasClass('slick-initialized')) {
                            $slider.slick(options);
                        }
                        startProgress();
                    } else {
                        stopProgress();
                        if ($slider.hasClass('slick-initialized')) {
                            $slider.slick('unslick');
                        }
                        $progressWrap.hide();
                        $bar.css({ width: '0%' });
                    }
                }

                // Default hide
                $progressWrap.hide();

                initOrDestroy();

                $(window).on('resize.slider' + $slider.index(), function() {
                    initOrDestroy();
                });

                $slider.on('destroy', function() {
                    stopProgress();
                    $progressWrap.hide();
                    $bar.css({ width: '0%' });
                });
            }

            var sliderOptionsMap = {
                'banner-withslider': {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: true,
                    dots: false,
                    accessibility: true,
                    autoplay: false,
                    responsive: [
                        { breakpoint: 767, settings: { slidesToShow: 2 } },
                        { breakpoint: 575, settings: { slidesToShow: 1 } }
                    ]
                },
                'brand-itemslider': {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: true,
                    dots: false,
                    accessibility: true,
                    autoplay: false,
                    responsive: [
                        { breakpoint: 767, settings: { slidesToShow: 2 } },
                        { breakpoint: 575, settings: { slidesToShow: 1 } }
                    ]
                },
                'brand-2itemslider': {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: true,
                    dots: false,
                    accessibility: true,
                    autoplay: false,
                    responsive: [
                        { breakpoint: 1024, settings: { slidesToShow: 1 } },
                        { breakpoint: 575, settings: { slidesToShow: 1 } }
                    ]
                }
            };

            $('.brandProducts-sl').each(function() {
                var $slider = $(this);
                var key = $slider.data('slider');
                var options = sliderOptionsMap[key];

                if (options) {
                    initSlider($slider, options);
                } else {
                    console.warn('No options found for slider key: ' + key);
                }
            });

        });
    })(jQuery);
</script>

</main>
@endsection