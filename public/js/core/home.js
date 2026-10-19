(function( $ ) {
	$(document).ready(function() {
		var checkData = setInterval(function() {
			if ($('.yotpo-num-of-reviews').length) {
			   // Set color code
			   setYotpoViewAllLink();
			   clearInterval(checkData);
			}
		  }, 1000);

		$('.home-main-sl').on('init', function(event, slick){
			$("#load_hero_banner").hide();
			$(this).parent().find(".cover-spin").hide();
		});
		$('.home-main-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			arrows: false,
			speed: 300,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed:16000,
			accessibility: true,
		});

		$('.home-new-sl').on('init', function(event, slick){
			$("#load_hero_banner").hide();
			$(this).parent().find(".cover-spin").hide();
		});
		$('.home-new-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: false,
			speed: 300,
			arrows: false,
			slidesToShow: 6,
			slidesToScroll: 6,
			accessibility: false,
			responsive: [
				{ breakpoint: 1600, settings: { slidesToShow: 5, slidesToScroll: 5 } },
				{ breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
				{ breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
				{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
		$('.home-brand-sl').on('init', function(event, slick){
			$(this).parent().find(".cover-spin").hide();
		});
		$('.home-brand-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: true,
			centerMode:true,
			centerPadding: '0px',
			autoplay: true,
			autoplaySpeed: 2000,
			arrows: false,
			speed: 300,
			slidesToShow: 7,
			slidesToScroll: 7,
			accessibility: true,
			responsive: [
				{ breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4 } },
				{ breakpoint: 600, settings: { slidesToShow: 3, slidesToScroll: 3 } },
				{ breakpoint: 480, settings: { slidesToShow: 3, slidesToScroll: 3 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
		$('.home-review-sl').on('init', function(event, slick){
			$(this).parent().find(".cover-spin").hide();
		});
		$('.home-review-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: true,
			autoplay: true,
			autoplaySpeed: 2000,
			arrows: false,
			speed: 300,
			accessibility: true,
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
			// responsive: [
			//     {
			//       breakpoint: 768,
			//       settings: {
			//         adaptiveHeight: false
			//       }
			//     },

			//     {
			//       breakpoint: 767,
			//       settings: {
			//        adaptiveHeight: true
			//       }
			//     }
			//   ]
		});
	});
	var deal_end_date = $('#deal_end_date').val();
	//var deal_end_date = '20-05-2021 23:59:59';
	var deal_start_date = $('#deal_start_date').val();
	if(deal_end_date != '')
	{
		var $clock = $('#counter_1'),
		eventTime = moment(deal_end_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		currentTime = moment(deal_start_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		diffTime = eventTime - currentTime,
		duration = moment.duration(diffTime * 1000, 'milliseconds'),
		interval = 1000;
		if(diffTime > 0) {
			/*var $d = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$h = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$m = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$s = $('<i style="font-style: normal;"></i>').appendTo($clock);
			*/
			setInterval(function(){

				duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
				var d = moment.duration(duration).days(),
					h = moment.duration(duration).hours(),
					m = moment.duration(duration).minutes(),
					s = moment.duration(duration).seconds();

				d = $.trim(d).length === 1 ? '0' + d : d;
				h = $.trim(h).length === 1 ? '0' + h : h;
				m = $.trim(m).length === 1 ? '0' + m : m;
				s = $.trim(s).length === 1 ? '0' + s : s;

				// show how many hours, minutes and seconds are left

				var clockdata = '';
				clockdata+='<li><span class="count" id="days">'+d+'</span><span class="count_name">Days</span></li>';
				clockdata+='<li><span class="count" id="hrs">'+h+'</span><span class="count_name">Hours</span></li>';
				clockdata+='<li><span class="count" id="min">'+m+'</span><span class="count_name">Mins</span></li>';
				clockdata+='<li><span class="count" id="sec">'+s+'</span><span class="count_name">Secs</span></li>';
				$clock.html(clockdata);
				//$d.html('<span class="countdown-section"><span class="countdown-amount" id="days">'+d+'</span><span class="countdown-period">Days</span></span>');
				//$h.html('<span class="countdown-section"><span class="countdown-amount" id="hrs">'+h+'</span><span class="countdown-period">Hours</span></span>');
				//$m.html('<span class="countdown-section"><span class="countdown-amount" id="min">'+m+'</span><span class="countdown-period">Mins</span></span>');
				//$s.html('<span class="countdown-section"><span class="countdown-amount" id="sec">'+s+'</span><span class="countdown-period">Secs</span></span>');

			}, interval);
		}
	}
})( jQuery );
function setYotpoViewAllLink(){
	$('<span class="review-pipe"> | </span><a target="_blank" id="yotpo_all_reviews" href="/reviews">View All Reviews</a>').insertAfter( ".yotpo-num-of-reviews" );
}