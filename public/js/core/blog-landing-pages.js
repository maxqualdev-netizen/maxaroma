$(document).ready(function(){
	$(document).on("click", ".list-more", function () {
		var Page = parseInt($(this).attr('data-page'));
		Page = Page + 1;
		$(this).attr('data-page', Page);
		GetBlogProducts();
	});
});

function GetBlogProducts(autoload=false)
{
	var Filters = '';
    
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	
	$.ajax({
		type: 'POST',
		url: site_url + 'getBlogProducts',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			page: parseInt($('.list-more').attr('data-page')),
			sectionID : 8,
			main_blogid : $('#main_blogid').val()
		},
		success: function (data) {
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
				$('#setBlogProducts').hide().append(data.ProductHTML).fadeIn(1000);
			}else{
				$('#setBlogProducts').hide().html(data.ProductHTML).fadeIn(1000);
			}
			$("#page-spinner").hide();
		}
	});
}

$('.setText').click(function () {
	
	var val = $(this).html();
	var ID = $(this).attr("data-setid");
	
	if(val == 'Read Less')
	{
		$("#"+ID).hide();
		$(this).attr("title",'Read More');
		$(this).html('Read More');
	}else{
		$("#"+ID).show();
		$(this).attr("title",'Read Less');
		$(this).html('Read Less');
	}
});
