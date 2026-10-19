$(document).ready(function(){
	function sticky_relocate() {
		var window_top = $(window).scrollTop();
		var div_top = $('#alp-sticky-anchor').offset().top;
		if (window_top > div_top) {
			$('#alphabets_brands').addClass('alph-fixed');
			$('#alp-sticky-anchor').height($('#alphabets_brands').outerHeight());
		} else {
			$('#alphabets_brands').removeClass('alph-fixed');
			$('#alp-sticky-anchor').height(0);
		}
	}

	$(function() {
		$(window).scroll(sticky_relocate);
		sticky_relocate();
	});
			
	$("#alphabets_brands a").on('click', function(event) {
		$("#alphabets_brands a").removeClass('active');
		$(this).addClass('active');
		var headHeight = $("#header-sticky").outerHeight();
		var AlphaHeight = $("#alphabets_brands").outerHeight() - 150;
		var stickyHeight = headHeight + AlphaHeight;
		if (this.hash !== "") {
			event.preventDefault();
			var hash = this.hash;
			var scollpos = $(hash).position().top - $('.alpha-bot-box .alpha-type a:first').position().top;
		}
	});
});
var isMobileb = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
	if (isMobileb) {
		function animatebrand(typeval) {
			$('html, body').animate({
				scrollTop: $("#" + typeval).offset().top - 180
			}, 1000);
		}
	} else {
		function animatebrand(typeval) {
			$('html, body').animate({
				scrollTop: $("#" + typeval).offset().top - 120
			}, 1000);
		}
	}