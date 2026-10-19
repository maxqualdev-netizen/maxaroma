$(document).ready(function () {
    // initialise1();
    $("#togglestockitem").click(function () {
        var msg = $(this).find("strong").html();
        if (msg == 'Show out of stock items') {
            $('#togglestockitem').html('<strong>Hide out of stock items</strong>');
        } else {
            $('#togglestockitem').html('<strong>Show out of stock items</strong>');
        }
        $(".outofstockitem").toggle();
    });

    $('svg #sv-topdeal').css('width', '0px');
});

function more_other_women()
{
	var m = document.getElementById('women_other_count').value;
	$(".button_show_other_women").click(function() 
	{
		for(var k=1;k<=m;k++)
		{
  			$('.li_show_other_women_'+k).show();
			$(".button_show_other_women").hide();
			$(".button_hide_other_women").show();
		}
  	});

	$(".button_hide_other_women").click(function() 
	{
		for(var l=1;l<=m;l++)
		{
			$('.li_show_other_women_'+l).hide();
			$(".button_hide_other_women").hide();
			$(".button_show_other_women").show();
		}
	});

}

function more_other_unisex()
{
	var m = document.getElementById('uni_other_count').value;
	
	$(".button_show_other_unisex").click(function() 
	{
		for(var k=1;k<=m;k++)
		{
  			$('.li_show_other_unisex_'+k).show();
			$(".button_show_other_unisex").hide();
			$(".button_hide_other_unisex").show();
		}
  	});

	$(".button_hide_other_unisex").click(function() 
	{
		for(var l=1;l<=m;l++)
		{
			$('.li_show_other_unisex_'+l).hide();
			$(".button_hide_other_unisex").hide();
			$(".button_show_other_unisex").show();
		}
	});

}


function more_other_men()
{
	var n = document.getElementById('man_other_count').value;
	$(".button_show_other_men").click(function() 
	{
		for(var i=1;i<=n;i++)
		{
  			$('.li_show_other_men_'+i).show();
			$(".button_show_other_men").hide();
			$(".button_hide_other_men").show();
		}
  	});

	$(".button_hide_other_men").click(function() 
	{
		for(var j=1;j<=n;j++)
		{
			$('.li_show_other_men_'+j).hide();
			$(".button_hide_other_men").hide();
			$(".button_show_other_men").show();
		}
	});	
}


function change_product_quickview(product_id,cid = 0) {
    $("#page-spinner").show();
    $.ajax({
        type: 'POST',
        url: site_url + 'getquickviewrefproduct',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        datatype: 'JSON',
        data: {
            products_id: product_id,
			category_id : cid
        },

        success: function (data) {
            $("#page-spinner").hide();
            $("#quickview_price_details").html("");
            $("#quickview_addtocart_description").html("");
	    	$("#quickviewprodimg").html("");
            if (data.quickview_price_details != "") {
                $("#quickview_price_details").html(data.quickview_price_details);
            }

            $(".detailboxJquickview").removeClass("active");
            $("." + data.sku).addClass("active");

	    	if (data.quickviewprodimg != "") {
                $("#quickviewprodimg").html(data.quickviewprodimg);
            }

            if (data.productIcons != "") {
                $("#productIcons").html(data.productIcons);
            }

            if (data.quickview_addtocart_description != "") {
                $("#quickview_addtocart_description").html(data.quickview_addtocart_description);
            }

			checkReviewRef(product_id);
			setTimeout(() => {            
				if (!$('.yotpo.bottomLine').find('.yotpo-stars').length) {
					$('.yotpo.bottomLine').html(
						'<div class="yotpo-bottomline pull-left  star-clickable">' +
							'<span class="yotpo-stars">' +
							'<span class="yotpo-icon yotpo-icon-empty-star pull-left"></span>' +
							'<span class="yotpo-icon yotpo-icon-empty-star pull-left"></span>' +
							'<span class="yotpo-icon yotpo-icon-empty-star pull-left"></span>' +
							'<span class="yotpo-icon yotpo-icon-empty-star pull-left"></span>' +
							'<span class="yotpo-icon yotpo-icon-empty-star pull-left"></span></span>'+
							'<div class="yotpo-clr"></div>'+
						'</div>'
					);
				}

			}, 1500);
        }

    });
}
function checkReviewRef(productId){
    var i = 1;
    var checkData = setInterval(function() {
        if ($('#ProductQuickViewPopup').find("a.text-m").length == 0) {
            //if(i == 2){
                //console.log("not found"+i);
                //$("#star_rating").show();
				$("#star_rating").hide();
                $("#prd_review").show();
                $("#qk_pipe").show();
                $("#ask_question").show();
                clearInterval(checkData);
                $('#ProductQuickViewPopup').modal('show');
            //}    
        } else {
            //console.log("found");
            //$("#star_rating_"+productId).hide();
            clearInterval(checkData);
            $("#yotpo_write_review").show();
            $("#yotpo_qk_pipe").show();
            $("#yotpo_ask_question").show();
            $('#ProductQuickViewPopup').modal('show');
        }
        i++;
      }, 1000);
}
$(function(){
	if($('.other-scroll').length > 0){
		$('.other-scroll').slimscroll({
		animate: true
		});
  	}
});
