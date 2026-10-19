$(document).ready(function(){
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetLandingPageProducts();
	});
});

(function( $ ) {
		$('.home-new-sl').on('init', function(event, slick){
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
			responsive: [
				{ breakpoint: 1600, settings: { slidesToShow: 5, slidesToScroll: 5 } },
				{ breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
				{ breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
				{ breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } }
			],
			nextArrow: '<button class="slick-next round-btn-sl"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
			prevArrow: '<button class="slick-prev round-btn-sl"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
		});
})( jQuery );

function GetLandingPageProducts(){
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	$.ajax({
		type:'POST',
		url:site_url+'getgiftproductspreview',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data:{
			page : parseInt($('.list-more').attr('data-page')),
		},
		success:function(data) {
			var TotalProds = data.TotalProducts;
			var PageSize = parseInt($(".list-more").attr('data-page')) * 10;
			
			$("#noprod").hide();
			if(TotalProds > 0){
				if(PageSize > TotalProds){
					$("#loadmore").hide();
				}else{
					$("#loadmore").show();
				}
			} else {
				$("#noprod").show();
			}
			
			var page = parseInt($('.list-more').attr('data-page'));
			if(page > 1 ){
				$('#selectedProducts').hide().append(data.ProductHTML).fadeIn(1000);
			}else{
				$('#selectedProducts').hide().html(data.ProductHTML).fadeIn(1000);
			}
			$("#page-spinner").hide();
		}
	});
}
