$(document).ready(function () {
    $(".fra_toggle").click(function () {
        $(".fra_link").slideToggle();
    });
    $(window).resize(function () {
        if ($(this).width() < 1025) {
            $(".fra_link").hide();
        }
        else {
            $(".fra_link").show();
        }
    });
});

(function($) {
    $(window).on('load', function(){
        $(".scroll").mCustomScrollbar({});
    });
    $(".filter_btn").click(function() {
        $("body").toggleClass('open-filter');
    });
    $(".filter-close").click(function() {
        $("body").removeClass('open-filter');
    });
    
})(jQuery);
/*
$(window).scroll(function(e) {
	if(($(window).scrollTop()+250) >= $('.listing_grid').height()) 
	{
		var TotalProds = parseInt($('.list-more').attr('data-total'));
		var CurrProdsCount = $(".product").length;
		if($(".listing_grid").attr('data-load') == '0' && CurrProdsCount < TotalProds)
		{
			var Page = parseInt($('.list-more').attr('data-page'));
			Page = Page + 1;
			$('.list-more').attr('data-page', Page);
			GetMainCategoryProducts();
		}
    }
});
*/
$(document).ready(function(){
    //GetMainCategoryProducts();
    $(".filter_main").click(function(){
        var filter_key = $(this).attr('data-filter-key');
        var filter_value = $(this).attr('data-filter-value');
        var currentURL = window.location.href;
        var newURL = currentURL;
        if(currentURL.indexOf('cid/') > 0) {
            var arr = currentURL.split('cid/');
            newURL = arr[0];
            var params = arr[1].split('/');
            var catID = params[0];
        }
        if(filter_key !== 'category_id') {
            newURL = newURL+'cid/'+catID+'/'+filter_key+'-'+filter_value;
        } else {
            newURL = newURL+'cid/'+filter_value;
        }
        window.location.href = newURL;
        // console.log(newURL);
        // GetMainCategoryProducts();
    });

    $(".list-more").click(function(){
        var Page = parseInt($(this).attr('data-page'));
        Page = Page+1;
        $(this).attr('data-page',Page);
        GetMainCategoryProducts();
    });
    $(document).on("change","#sort_by",function(){
        var Page = parseInt($('.list-more').attr('data-page'));
        Page = Page+1;
        $('.list-more').attr('data-page',Page);
        var SortBy = $('#sort_by').val();
        GetMainCategoryProducts();
    });
});

function openNav() {
    document.getElementById("myFilter").style.width = "100%";
}

function closeNav() {
    document.getElementById("myFilter").style.width = "0%";
}! function(i) {
    var o, c;
    i(".filter_acrd_hd").on("click", function() {
        o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())
    })
}(jQuery);
$(window).bind('resize orientationchange', function() {
    w = document.body.clientWidth;
    if (w < 1025) {
        $(".filter_acrd").removeClass("filter_acrd_act");
        $(".filter_acrd_con").hide();
        $(".filter").hide();
        $(document).on('click', '.sortmenu', function() {
            o = $(".sortmenu");
            c = $(".filter");
            o.hasClass("sortactive") ? (o.removeClass("sortactive"), c.slideUp()) : (o.addClass("sortactive"), c.stop(!0, !0).slideDown())
        })
    } else {
        $(".filter").show();
        $(".filter_acrd").addClass("filter_acrd_act");
        $(".filter_acrd_con").show();
    }
});



function GetMainCategoryProducts() {
    var token = $('meta[name="csrf-token"]').attr('content');
    $("#page-spinner").show();
    var sortby = $('#sortselect').val();
    var currentURL = window.location.href;
	$(".listing_grid").attr('data-load','1');
    $.ajax({
        type:'POST',
        url:currentURL,
        headers: {
            'X-CSRF-TOKEN': token
        },
        data:{
            page : parseInt($('.list-more').attr('data-page')),
            sortby : sortby
        },
        success:function(data) {
            var TotalProds = data.TotalProducts;
            var PageSize = parseInt($(".list-more").attr('data-page')) * 12;
			$(".list-more").attr('data-total',TotalProds);
            $("#total").html('('+TotalProds+')');
            
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
                $('.listing_grid').hide().append(data.ProductHTML).fadeIn(1000);
            }else{
                $('.listing_grid').hide().html(data.ProductHTML).fadeIn(1000);
            }
            $("#page-spinner").hide();
        },
		complete:function(){
			$(".listing_grid").attr('data-load','0');
		},
    });
 }