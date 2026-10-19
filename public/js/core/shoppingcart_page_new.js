$(document).ready(function(){
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
	/*
	$(document).on('click','.btn-number',function(e) {
		e.preventDefault();
		fieldName = $(this).attr('data-field');
		type = $(this).attr('data-type');
		var input = $("input[name='" + fieldName + "']");
		var currentVal = parseInt(input.val());
		if (!isNaN(currentVal)) {
			if (type == 'minus') {
				if (currentVal > input.attr('min')) {
					input.val(currentVal - 1).change();
				}
				if (parseInt(input.val()) == input.attr('min')) {
					$(this).attr('disabled', true);
				}
			} else if (type == 'plus') {
				input.val(currentVal + 1).change();
				//if (currentVal < input.attr('max')) {
					input.val(currentVal + 1).change();
				//}
				if (parseInt(input.val()) == input.attr('max')) {
					$(this).attr('disabled', true);
				}
			}
		} else {
			input.val(0);
		}
	});
	*/	
});