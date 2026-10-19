<?php
for($i=0;$i<count($allSectionRecommendedPrds);$i++){ 
if(count($allSectionRecommendedPrds[$i]['slider_products']) > 0){ ?>
	<section class="home-product slider-arrow-space sectionMainCart">
		<div class="container">
			<div class="pt-3 text-center pb-4">
				<div class="main-heading">
					<h3><span><?=$allSectionRecommendedPrds[$i]['title'];?></span></h3>
					<!--<a href="javascript:void(0);" class="viewall">See More</a>-->
				</div>
			</div>
			<div class="cover-spin"></div>
			<div class="products-slider">
				@foreach($allSectionRecommendedPrds[$i]['slider_products'] as $key => $Product)
					<x-productbox :prodData="$Product" sliderClass="slick-slide" />
				@endforeach
			</div>
		</div>
	</section>
<?php
}
} ?>


<script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}jquery-3.4.1.min.js?ver=1111112"></script> 
<script src="{{ config('global.SITE_JS_CORE')}}slick.js?ver=2222"></script>
<script>

$('.products-slider').on('init', function(event, slick){
	$('.sectionMainCart').find(".cover-spin").hide();
});

$('.products-slider').slick({
	lazyLoad: 'ondemand',
	dots: true,
	infinite: false,
	speed: 300,
	arrows: false,
	slidesToShow: 6,
	slidesToScroll: 6,
	responsive: [
		{ breakpoint: 1600, settings: { slidesToShow: 5, slidesToScroll: 5 } },
		{ breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
		{ breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
		{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } }
	],
	nextArrow: '<button class="slick-next round-btn-sl"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
	prevArrow: '<button class="slick-prev round-btn-sl"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
});
</script>
