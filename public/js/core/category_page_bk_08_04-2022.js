$(document).ready(function() {
	/* MORE WAY TO SHOP Slider */
	$('.way_shop').slick({
		dots: false,
		infinite: true,
		autoplay: true,
		autoplaySpeed: 2000,
		arrows: true,
		speed: 300,
		slidesToShow: 2,
		slidesToScroll: 2,
		responsive: [
			{ breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll: 2 } },
			{ breakpoint: 600, settings: { slidesToShow: 2, slidesToScroll: 2 } },
			{ breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 2 } }
		],
		nextArrow: '<button class="slick-next"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
		prevArrow: '<button class="slick-prev"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
	});
	$("#catsort").selectbox({
		speed : 450,
	})
});