$(document).ready(function(){
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetBrandHistoryBundleProducts();
	});
});
function GetBrandHistoryBundleProducts() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
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
		}
	});
}
