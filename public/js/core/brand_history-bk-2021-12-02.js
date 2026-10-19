$(document).ready(function(){
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetBrandProducts();
	});
});
function GetBrandProducts() {
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#cover-spin").show();
	$.ajax({
		type:'POST',
		url:site_url+'getbrandproducts',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data:{
			page : parseInt($('.list-more').attr('data-page')),
			brand_id : parseInt($('.list-more').attr('data-brand-id')),
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
				$('#brandProducts').hide().append(data.ProductHTML).fadeIn(1000);
			}else{
				$('#brandProducts').hide().html(data.ProductHTML).fadeIn(1000);
			}
			$("#cover-spin").hide();
		}
	});
}