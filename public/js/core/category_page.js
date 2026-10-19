$(document).ready(function() {
	/* MORE WAY TO SHOP Slider */
	$('.way_shop').slick({
		dots: false,
		infinite: true,
		autoplay: false,
		autoplaySpeed: 2000,
		arrows: true,
		speed: 300,
		slidesToShow: 6,
		slidesToScroll: 6,
		accessibility: true,
		responsive: [
			{ breakpoint: 1600, settings: { slidesToShow: 5, slidesToScroll: 5 } },
			{ breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
			{ breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
			{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } }
		],
		nextArrow: '<button class="slick-next" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
		prevArrow: '<button class="slick-prev" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
	});
	$("#catsort").selectbox({
		speed : 450,
	})
});
