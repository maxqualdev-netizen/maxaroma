// $(document).ready(function(){
// 	$(".list-more").click(function(){
// 		var Page = parseInt($(this).attr('data-page'));
// 		Page = Page+1;
// 		$(this).attr('data-page',Page);
// 		//GetBrandHistoryBundleProducts();
// 	});
// });
$(window).scroll(function(e) {
	if($(window).scrollTop() >= $('#brand-history-grid').height()) 
	{
		var TotalProds = parseInt($('.list-more').attr('data-total'));
		var CurrProdsCount = $(".product").length;
		if($("#brand-history-grid").attr('data-load') == '0' && CurrProdsCount < TotalProds)
		{
			var Page = parseInt($('.list-more').attr('data-page'));
			Page = Page + 1;
			$('.list-more').attr('data-page', Page);
			//GetBrandHistoryBundleProducts();
		}
    }
});
$("#loadmore").on("click", function(){
	//GetBrandHistoryBundleProducts();
	var TotalProds = parseInt($('.list-more').attr('data-total'));
	var CurrProdsCount = $(".product").length;
	//console.log(TotalProds+"---"+CurrProdsCount);
	// if($("#brand-history-grid").attr('data-load') == '0' && CurrProdsCount < TotalProds)
	// {
		var Page = parseInt($('.list-more').attr('data-page'));
		Page = Page + 1;
		$('.list-more').attr('data-page', Page);
		GetBrandHistoryBundleProducts();
	//}
})
function GetBrandHistoryBundleProducts() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	$("#brand-history-grid").attr('data-load','1');
	$.ajax({
		type:'POST',
		url:site_url+'getbrandhistorybundleproducts',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data:{
			page : parseInt($('.list-more').attr('data-page')),
			brand_id : parseInt($('.list-more').attr('data-brand-id')),
		},
		cache: false,
		success:function(data) {
			var TotalProds = data.TotalProducts;
			var PageSize = parseInt($(".list-more").attr('data-page')) * 15;
			$(".list-more").attr('data-total',TotalProds);
			
			$("#noprod").hide();
			if(TotalProds > 0){
				if(PageSize > TotalProds){
					$("#loadmore").hide();
				}else{
					$("#loadmore").show();
				}
			} else {
				$("#noprod").show();
				$("#loadmore").hide();
			}
			
			var page = parseInt($('.list-more').attr('data-page'));
			
			if(page > 1 && TotalProds > 0){
				$('#brand-history-grid').hide().append(data.ProductHTML).fadeIn(1000);
			}else{
				$('#brand-history-grid').hide().html(data.ProductHTML).fadeIn(1000);
			}
			$("#page-spinner").hide();
		},
		complete:function(){
			$("#brand-history-grid").attr('data-load','0');
		},
	});
}
