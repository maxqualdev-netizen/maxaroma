(function( $ ) {
	$(document).ready(function() {
		var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		var yotpoAttempts = 0;
		var checkData = setInterval(function() {
			yotpoAttempts++;
			if ($('.yotpo-num-of-reviews').length) {
				setYotpoViewAllLink();
				clearInterval(checkData);
			} else if (yotpoAttempts >= 15) {
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
			autoplay: !prefersReducedMotion,
			autoplaySpeed: 12000,
			pauseOnHover: true,
			pauseOnFocus: true,
			pauseOnDotsHover: true,
			accessibility: true,
			responsive: [
				{ breakpoint: 767, settings: { slidesToShow: 1, slidesToScroll: 1, adaptiveHeight: true } }
			],
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
			accessibility: true,
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
	
		$('.find-slider').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: false,
			centerPadding: '0px',
			autoplay: !prefersReducedMotion,
			autoplaySpeed: 4000,
			pauseOnHover: true,
			pauseOnFocus: true,
			pauseOnDotsHover: true,
			arrows: false,
			speed: 300,
			slidesToShow: 3,
			slidesToScroll: 3,
			accessibility: true,
			responsive: [
				{ breakpoint: 600, settings: { slidesToShow: 2.3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 1.3, slidesToScroll: 1 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
		$('.excl-rail-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: false,
			centerPadding: '0px',
			autoplay: !prefersReducedMotion,
			autoplaySpeed: 4000,
			pauseOnHover: true,
			pauseOnFocus: true,
			pauseOnDotsHover: true,
			arrows: false,
			speed: 300,
			slidesToShow: 3,
			slidesToScroll: 3,
			accessibility: true,
			responsive: [
				{ breakpoint: 600, settings: { slidesToShow: 2.3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 1.3, slidesToScroll: 1 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
	

		$('.tiktok-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: false,
			centerPadding: '0px',
			autoplaySpeed: 2000,
			arrows: false,
			speed: 300,
			slidesToShow: 6,
			slidesToScroll: 6,
			accessibility: true,
			responsive: [
				{ breakpoint: 1199, settings: { slidesToShow: 4, slidesToScroll: 4 } },
				{ breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } },
				{ breakpoint: 600, settings: { slidesToShow: 2.3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 1.3, slidesToScroll: 1 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});

		$('.commit-sl').slick({
			lazyLoad: 'ondemand',
			dots: true,
			infinite: false,
			centerPadding: '0px',
			autoplaySpeed: 2000,
			arrows: false,
			speed: 300,
			slidesToShow: 3,
			slidesToScroll: 3,
			accessibility: true,
			responsive: [
				{ breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll: 2 } },
				{ breakpoint: 600, settings: { slidesToShow: 1, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1 } }
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
			autoplay: !prefersReducedMotion,
			autoplaySpeed: 4000,
			pauseOnHover: true,
			pauseOnFocus: true,
			pauseOnDotsHover: true,
			arrows: false,
			speed: 300,
			accessibility: true,
			nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
	});
	function closeHeroHotspots() {
		$('.home-main-sl .hotspot.is-open')
			.removeClass('is-open')
			.find('.hotspot-btn')
			.attr('aria-expanded', 'false');
	}

	$(document).on('click', '.home-main-sl .hotspot-btn', function(event) {
		event.preventDefault();
		event.stopPropagation();

		var $hotspot = $(this).closest('.hotspot');
		var shouldOpen = !$hotspot.hasClass('is-open');

		closeHeroHotspots();

		if (shouldOpen) {
			$hotspot.addClass('is-open');
			$(this).attr('aria-expanded', 'true');
		}
	});

	$(document).on('click', function(event) {
		if (!$(event.target).closest('.home-main-sl .hotspot').length) {
			closeHeroHotspots();
		}
	});

	$(document).on('keydown', function(event) {
		if (event.key === 'Escape') {
			closeHeroHotspots();
		}
	});
		function updateHeroCountdowns() {
		$('.hero-cd[data-countdown-end]').each(function() {
			var $countdown = $(this);
			var endTime = Date.parse($countdown.attr('data-countdown-end'));

			if (isNaN(endTime)) {
				return;
			}

			var remainingSeconds = Math.max(
				0,
				Math.floor((endTime - Date.now()) / 1000)
			);

			var days = Math.floor(remainingSeconds / 86400);
			var hours = Math.floor((remainingSeconds % 86400) / 3600);
			var minutes = Math.floor((remainingSeconds % 3600) / 60);
			var seconds = remainingSeconds % 60;

			$countdown.find('[data-countdown-part="days"]')
				.text(String(days).padStart(2, '0'));
			$countdown.find('[data-countdown-part="hours"]')
				.text(String(hours).padStart(2, '0'));
			$countdown.find('[data-countdown-part="minutes"]')
				.text(String(minutes).padStart(2, '0'));
			$countdown.find('[data-countdown-part="seconds"]')
				.text(String(seconds).padStart(2, '0'));
		});
	}

	updateHeroCountdowns();
	setInterval(updateHeroCountdowns, 1000);
	function parseCustomDate(str) {
		if (!str || typeof str !== 'string') return null;
		var parts = str.trim().split(/[\s-:]+/);
		if (parts.length >= 6) {
			var day = parseInt(parts[0], 10);
			var month = parseInt(parts[1], 10) - 1;
			var year = parseInt(parts[2], 10);
			var hour = parseInt(parts[3], 10);
			var min = parseInt(parts[4], 10);
			var sec = parseInt(parts[5], 10);
			var d = new Date(year, month, day, hour, min, sec);
			if (!isNaN(d.getTime())) return d.getTime();
		}
		var fallback = Date.parse(str);
		return isNaN(fallback) ? null : fallback;
	}

	var deal_end_date = $('#deal_end_date').val();
	var deal_start_date = $('#deal_start_date').val();
	if (deal_end_date) {
		var $clock = $('#counter_1');
		if ($clock.length) {
			var endTime = parseCustomDate(deal_end_date);
			var startTime = deal_start_date ? parseCustomDate(deal_start_date) : Date.now();
			if (endTime) {
				var diffSeconds = Math.max(0, Math.floor((endTime - (startTime || Date.now())) / 1000));
				if (diffSeconds > 0) {
					function updateClock() {
						if (diffSeconds <= 0) {
							clearInterval(timerInterval);
							return;
						}
						diffSeconds--;
						var d = Math.floor(diffSeconds / 86400);
						var h = Math.floor((diffSeconds % 86400) / 3600);
						var m = Math.floor((diffSeconds % 3600) / 60);
						var s = diffSeconds % 60;

						var dStr = d < 10 ? '0' + d : String(d);
						var hStr = h < 10 ? '0' + h : String(h);
						var mStr = m < 10 ? '0' + m : String(m);
						var sStr = s < 10 ? '0' + s : String(s);

						$clock.html(
							'<li><span class="count" id="days">' + dStr + '</span><span class="count_name">Days</span></li>' +
							'<li><span class="count" id="hrs">' + hStr + '</span><span class="count_name">Hours</span></li>' +
							'<li><span class="count" id="min">' + mStr + '</span><span class="count_name">Mins</span></li>' +
							'<li><span class="count" id="sec">' + sStr + '</span><span class="count_name">Secs</span></li>'
						);
					}
					var timerInterval = setInterval(updateClock, 1000);
					updateClock();
				}
			}
		}
	}
})( jQuery );
function setYotpoViewAllLink(){
	if ($('#yotpo_all_reviews').length) return;
	$('<span class="review-pipe" aria-hidden="true"> | </span><a target="_blank" rel="noopener noreferrer" id="yotpo_all_reviews" href="/reviews" aria-label="View all customer reviews on Yotpo">View All Reviews</a>').insertAfter( ".yotpo-num-of-reviews" );
}