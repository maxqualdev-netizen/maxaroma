$(window).resize(function() {
    responsiveChanges();
});
function responsiveChanges(){
    if($('#sizeoption option').length <= 1){
        $('#sizeoption').hide();
        $('#mobshipping_info').hide();
    }

    var wdth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    if(wdth < 768){                                
        $(".productratingsreview:contains('Ask A Question')").hide();       
        // var prodprice = $(".prodprice").text();                        
        // if(prodprice!=''){
        //     var prodpricearr = prodprice.split(" ");
        //     var disp_price = "";
        //     if(prodpricearr[0]!=''){
        //         disp_price = prodpricearr[0];
        //     } else if(prodpricearr[1] != ''){
        //         disp_price = prodpricearr[1];
        //     }                
        //     var prod_price = " | "+disp_price;
        // } else {
        //     var prod_price = "";
        // }
        var disp_price = $("#prod_pro_price").val();
        var prod_price = " | "+disp_price;
        $(".addtocart").text("Add to cart"+prod_price);            
    } else {            
        $(".productratingsreview:contains('Ask A Question')").show();            
        $(".addtocart").text("Add to cart");
    }
}

function iAdvizeInterval(){
    var checkData = setInterval(function() {
        if ($('#yotpo_testimonials_btn').length && $("#iadvize-container").length && $("#iadvize-container").html().trim() !== '') {
            // console.log(123);
            //console.log($("#iadvize-container").html());
            //var isOpened = $("iframe[name='iAdvize copilot opened'][title='Chatbox opened']").length > 0;
            var isOpened = $("iframe[name='iAdvize copilot opened']:visible");
            var isDocked = $("iframe[name='iAdvize copilot docked']:visible");
            if (isOpened.length) {
                $("#yotpo_testimonials_btn").hide();
                clearInterval(checkData);
            } else if (isDocked.length) {
                $("#yotpo_testimonials_btn").show();
                return 'docked';
            }  /*else {
                $("#yotpo_testimonials_btn").show();
                clearInterval(checkData);
            }    */        
        }
    }, 1000);
}

$(document).ready(function () {
    responsiveChanges();
    var checkWriteReview = setInterval(function() {
        if ($('.write-review-btn-hidden').length) {
            $('.write-review-btn-hidden').hide();
            clearInterval(checkWriteReview);
        }
      }, 1000);

    iAdvizeInterval();  
      

    // const iframeName = "iAdvize copilot opened";
    // const buttonSelector = ".ReduceButton-module__ReduceButton__sB22x";
    
    // function onReduceButtonClick() {
    //     $("#yotpo_testimonials_btn").show();        
    // }

    // const interval = setInterval(() => {
    //     const iframe = document.querySelector(`iframe[name="${iframeName}"]`);

    //     if (iframe && iframe.contentWindow) {
    //         try {
    //             const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    //             const button = iframeDoc.querySelector(buttonSelector);

    //             if (button) {                    
    //                 if (!button._listenerAttached) {
    //                     button.addEventListener("click", onReduceButtonClick);
    //                     button._listenerAttached = true;                        
    //                 }
    //                 clearInterval(interval);
    //             }
    //         } catch (err) {
    //             console.error("Error : ", err);
    //             clearInterval(interval);
    //         }
    //     }
    // }, 1000);


    // var checkiadvize = setInterval(function() {
    //     if($("#iadvize-container").length > 0){
    //         $("#testimonials").hide();
    //         clearInterval(checkiadvize);
    //     } 
    // }, 1000);
  
    
    setTimeout(function(){
        var locationValue = window.location.hash;
        if(locationValue.indexOf("#") >= 0)
        {
            var Exp = locationValue.split("#");
            if(Exp[1] && Exp[1] == 'productreview')
            {
                $('html, body').animate({scrollTop: $("#productreview").offset().top - 80}, 1000);
            }
        }    
    },1000);
    
    $('.dtl_large').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        dots: false,
        asNavFor: '.dtl_extra',
        accessibility: true,
        responsive: [
            { breakpoint: 768, settings: { dots: true } }
        ],
    });
    $('.dtl_extra').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.dtl_large',
        dots: false,
        arrows: true,
        centerMode: true,
        focusOnSelect: true,
        accessibility: true,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 5, slidesToScroll: 5 } },
            { breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 4 } },
            { breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3} },
            { breakpoint: 768, settings: { slidesToShow: 3, slidesToScroll: 3 } }
        ],
    });
    $('.dtl_gallery_slider').slick({
        dots: false,
        infinite: true,
        centerMode: true,
        speed: 300,
        arrows: true,
        slidesToShow: 5,
        centerPadding: '0px',
        slidesToScroll: 1,
        accessibility: true,
        nextArrow: '<button class="slick-next round-btn-sl slick-arrow" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
        prevArrow: '<button class="slick-prev round-btn-sl slick-arrow" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
        responsive: [
            { breakpoint: 1200, settings: { slidesToShow: 5, slidesToScroll: 5 } },
            { breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
            { breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll: 2} },
            { breakpoint: 479, settings: { slidesToShow: 1, slidesToScroll: 1 } }
        ],
    });

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        accessibility: true,
        asNavFor: '.slider-nav'
    });
    $(".cover-spin").show();
    $('.slider-nav').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    vertical:true,
    accessibility: true,
    nextArrow: '<button class="slick-next round-btn-sl slick-arrow" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
    prevArrow: '<button class="slick-prev round-btn-sl slick-arrow" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
    asNavFor: '.slider-for',
    dots: false,
    focusOnSelect: true,
    verticalSwiping:true,
    responsive: [
     {
      breakpoint: 992,
      settings: {
        vertical: false,
        slidesToShow: 1,
        dots: true,
        arrows: true
      }
    }   
    ]	    
});


    $('#dtl_tab').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion           
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        // closed: 'accordion', // Start closed if in accordion view
        activate: function (event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
            //tabSlider;
        }
    });
    $("ul.resp-tabs-list > li").removeClass("resp-tab-active"); 
    $("div.resp-tabs-container > h2").removeClass("resp-tab-active"); 
    $("div.resp-tabs-container > div").removeClass("resp-tab-content-active");
    $("div.resp-tabs-container > div").hide();
});

$('.slider-nav').on('init', function(event, slick){
    $(".cover-spin").hide();
}); 

$(document).on('click', '#addtocart_max2dayreward .btn-number, .sticky-qty .btn-number', function (e) {
    
    e.preventDefault();
	
    var userType = $("#proddetails").attr('data-type');
    if(userType == ""){
        userType = "retailer";
    }
    var $button = $(this);
    var type = $button.attr('data-type');
    var fieldName = $button.attr('data-field');

    // Target both input fields
    var $pageInput = $(".page-qty input[name='" + fieldName + "']");
    var $stickyInput = $(".sticky-qty input[name='" + fieldName + "'], .sticky-qty input[id='prodqty-sticky']");

    var currentVal = parseInt($pageInput.val(), 10);
    var min = parseInt($pageInput.attr('min'), 10) || 1;

    // Detect user type
    //var max = (userType === 'retailer') ? 20 : 9999; // apply limit only for retailer
    if (userType === 'retailer' ||  userType === 'Retailer') {
        max = (available_stock < 20) ? available_stock : 20;
    } else if (userType === 'wholesaler' || userType === 'Wholesaler') {
        max = available_stock;
    } else {
        max = 9999;
    }

    if (!isNaN(currentVal)) {
        var newVal = currentVal;

        if (type === 'minus' && currentVal > min) {
            newVal = currentVal - 1;
        } else if (type === 'plus') {
            if (currentVal < max) {
                newVal = currentVal + 1;
            } else if (userType === 'retailer' || userType === 'wholesaler' ||  userType === 'Retailer' || userType === 'Wholesaler') {
                alert("The maximum quantity you can add is "+max+" pieces.");
                return;
            }
        }

        // Update both inputs and trigger change
        $pageInput.val(newVal).change();
        $stickyInput.val(newVal).change();
    } else {
        $pageInput.val(min);
        $stickyInput.val(min);
    }

    GetWholesalePrice($pageInput.val());
});

// $(document).on('click','.page-qty .btn-number',function (e) {
// 	e.preventDefault();
// 	fieldName = $(this).attr('data-field');
// 	type = $(this).attr('data-type');
// 	var input = $(".page-qty input[name='" + fieldName + "']");
// 	var currentVal = parseInt(input.val());
// 	if (!isNaN(currentVal)) {
// 		if (type == 'minus') {
// 			if (currentVal > input.attr('min')) {
// 				input.val(currentVal - 1).change();
// 				$('.sticky-qty #prodqty').val(currentVal - 1);
// 			}
// 			if (parseInt(input.val()) == input.attr('min')) {
// 				$(this).attr('disabled', true);
// 			}
// 		} else if (type == 'plus') {
// 			//if (currentVal < input.attr('max')) {
// 			input.val(currentVal + 1).change();
// 			$('.sticky-qty #prodqty').val(currentVal + 1);
// 			//}
// 			/*if (parseInt(input.val()) == input.attr('max')) {
// 				$(this).attr('disabled', true);
// 			}*/
// 		}
// 	} else {
// 		input.val(0);
// 	}
// 	GetWholesalePrice(input.val());
// });

// $(document).on('click','.sticky-qty .btn-number',function (e) {
// 	e.preventDefault();
// 	fieldName = $(this).attr('data-field');
// 	type = $(this).attr('data-type');
// 	var input = $(".sticky-qty input[name='" + fieldName + "']");
// 	var currentVal = parseInt(input.val());
// 	if (!isNaN(currentVal)) {
// 		if (type == 'minus') {
// 			if (currentVal > input.attr('min')) {
// 				input.val(currentVal - 1).change();
// 				$('.page-qty #prodqty').val(currentVal - 1);
// 			}
// 			if (parseInt(input.val()) == input.attr('min')) {
// 				$(this).attr('disabled', true);
// 			}
// 		} else if (type == 'plus') {
// 			//if (currentVal < input.attr('max')) {
// 			input.val(currentVal + 1).change();
// 			$('.page-qty #prodqty').val(currentVal + 1);
// 			//}
// 			/*if (parseInt(input.val()) == input.attr('max')) {
// 				$(this).attr('disabled', true);
// 			}*/
// 		}
// 	} else {
// 		input.val(0);
// 	}
// 	GetWholesalePrice(input.val());
// });

$('.input-number').focusin(function () {
	$(this).data('oldValue', $(this).val());
});

// $(document).on('change','.input-number',function () {
// 	minValue = parseInt($(this).attr('min'));
// 	maxValue = parseInt($(this).attr('max'));
// 	valueCurrent = parseInt($(this).val());
// 	name = $(this).attr('name');
// 	if (valueCurrent >= minValue) {
// 		$(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
// 	} else {
// 		alert('Sorry, the minimum value was reached');
// 		$(this).val($(this).data('oldValue'));
// 	}
// 	/*if (valueCurrent <= maxValue) {
// 		$(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
// 	} else {
// 		alert('Sorry, the maximum value was reached');
// 		$(this).val($(this).data('oldValue'));
// 	}*/
// });

$(document).on('change', '.input-number', function () {
    var userType = $("#proddetails").attr('data-type');
    if(userType == ""){
        userType = "retailer";
    }
    var $this = $(this);
    var minValue = parseInt($this.attr('min'), 10) || 1;

    var maxValue = (userType === 'retailer') ? 20 : 9999;

    if (userType === 'retailer' ||  userType === 'Retailer') {
        maxValue = (available_stock < 20) ? available_stock : 20;
    } else if (userType === 'wholesaler' || userType === 'Wholesaler') {
        maxValue = available_stock;
    } else {
        maxValue = 9999;
    }

    var valueCurrent = parseInt($this.val(), 10);
    var name = $this.attr('name');

    // Update both inputs by name
    var $allInputs = $("input[name='" + name + "']");

    if (isNaN(valueCurrent)) {
        alert('Quantity cannot be blank or non-numeric. Resetting to 1.');
        $allInputs.val(minValue);
        return;
    }

    if (valueCurrent > maxValue) {
        if (userType === 'retailer') {
            alert('The maximum quantity you can add is ' + maxValue + ' pieces.');
        }
        var oldVal = $this.data('oldValue') || maxValue;
        $allInputs.val(oldVal); // reset both
        return;
    }

    if (valueCurrent < minValue) {
        alert('Minimum quantity is ' + minValue);
        var oldVal = $this.data('oldValue') || minValue;
        $allInputs.val(oldVal); // reset both
    } else {
        $allInputs.val(valueCurrent); // sync both inputs
    }
});

$(".input-number").keydown(function (e) {
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
		return;
	}
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
});

$(document).on('input propertychange paste', '.page-qty .input-number', function () {
	var Qty = $(this).val();
	$('.sticky-qty #prodqty').val(Qty);
	GetWholesalePrice(Qty);
})

$(document).on('input propertychange paste', '.sticky-qty .input-number', function () {
	var Qty = $(this).val();
	$('.page-qty #prodqty').val(Qty);
	GetWholesalePrice(Qty);
})
/*
$(document).on('click','.thumbnail-image',function(){
    //var imgnam11 = $('.slider-for .slick-active').find('img').attr('src');
    var imgnam11 = $(this).find('img').attr('src');
    $(".slider-for .slider-banner-image img").attr('src',imgnam11);
    $('.slider-for > div:gt(3)').remove();
    //initialise1();
    //$('.slider-for div').not('.slick-list').remove();
    $('.zoom_img').jzoom({ bgColor: "#fff", suffixName: imgnam11 });
    console.log($('.slider-for .slick-active').find('img').attr('src'));
})
*/

function setSize(obj){
    var prod_catid = $(obj).attr('data-catid');
    var prod_id = $(obj).val();
    if(prod_id!=''){
        change_product(prod_id,prod_catid);
    }
}

function GetWholesalePrice(quantity=1)
{
	var usertype = $("#proddetails").attr('data-type');
	if(usertype == 'Wholesaler')
	{
		$("#page-spinner").show();
		var products_id = $(".btn-addtocart").attr('data-product');
		$.ajax({
			type: 'POST',
			url: site_url + 'get-wholesale-price',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			datatype: 'JSON',
			data: {
				products_id: products_id,
				quantity: quantity
			},
			success: function (data) {
				$("#page-spinner").hide();
				if(data.Price)
				{
					if($(".prodprice").attr('data-flag') == 'deal_price')
					{
						$(".prodprice").html('Deal Price: '+data.Price);
					} else {
						$(".prodprice").html(data.Price);
					}
                    $("#prod_pro_price").val(data.Price);
                    var wdth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                        if(wdth < 768){                                                
                            var disp_price = $("#prod_pro_price").val();
                            var prod_price = " | "+disp_price;
                            $(".addtocart").text("Add to cart"+prod_price);            
                        } else {                    
                            $(".addtocart").text("Add to cart");
                        }
				}
			}
		});
	}
    return true;
}
function SeeMoreReview() {
    $(".hideReviewCs").show();
    $("#SeeReviewMore").hide();
}

function morereviewshow(i) {
    $("#reviewtextdisplay" + i).hide();
    $("#reviewshow" + i).hide();
    $("#reviewhide" + i).show();
    $("#reviewtexthide" + i).show();
}

function moredescshow(i) {
    $("#imgtextdisplay" + i).hide();
    $("#imgdescshow" + i).hide();
    $("#imgdeschide" + i).show();
    $("#imgtexthide" + i).show();
}
function moredeschide(i) {
    $("#imgtextdisplay" + i).show();
    $("#imgtexthide" + i).hide();
    $("#imgdescshow" + i).show();
    $("#imgdeschide" + i).hide();

}

function morereviewhide(i) {
    $("#reviewtextdisplay" + i).show();
    $("#reviewtexthide" + i).hide();
    $("#reviewshow" + i).show();
    $("#reviewhide" + i).hide();

}

function moreStoryshow(i) {
    $("#storytextdisplay" + i).hide();
    $("#storyshow" + i).hide();
    $("#storyhide" + i).show();
    $("#storytexthide" + i).show();
}

function moreStoryhide(i) {
    $("#storytextdisplay" + i).show();
    $("#storytexthide" + i).hide();
    $("#storyshow" + i).show();
    $("#storyhide" + i).hide();
}

function setReferenceProduct(){    
    //var selectHtml = $('#addtocart_max2dayreward select').prop('outerHTML');
    return $(".qtyse").html();
    //return selectHtml;
}

function change_product(product_id,cid=0) {
    $("#page-spinner").show();
    $.ajax({
        type: 'POST',
        url: site_url + 'getrefproduct',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        datatype: 'JSON',
        data: {
            products_id: product_id,
            new:1,
            category_id : cid
        },
        success: function (data) {
            var refProductDropdown = setReferenceProduct();
            $("#page-spinner").hide();
            $(".detailboxJ").removeClass("active");
            $("." + data.psort_sku).addClass("active");
            
            if (data.psort_sku != "") {
                $("#psort_sku").html("<span>Item #:</span> " + data.psort_sku);
            }
            $("#product_descriptionpart").html('');
            $("#product_descriptionpart").hide();
            if (data.product_descriptionpart != '') {
                $("#product_descriptionpart").show();
                $("#product_descriptionpart").html(data.product_descriptionpart);
            }
            
            $("#product_descriptionmobilepart").html('');
            $("#product_descriptionmobilepart").hide();
            
            if (data.product_descriptionmobilepart != '') {
                $("#product_descriptionmobilepart").show();
                $("#product_descriptionmobilepart").html(data.product_descriptionmobilepart);
            }

            $("#maxtwoandreward").html('');
            $("#maxtwoandreward").hide();
            
            if (data.maxtwoandreward != '') {
                $("#maxtwoandreward").show();
                $("#maxtwoandreward").html(data.maxtwoandreward);
            }

            $("#productstorydetails").html('').hide();

            if(data.productstorydetails != ''){
                $("#productstorydetails").html(data.productstorydetails).show();
            }
            
            if (data.psort_descriptn != "") {
                $("#psort_descriptn").html("<span>Size:</span> " + data.psort_descriptn);
            }
            
            $("#productBottomsticky").html('');
            $("#productBottomsticky").hide();
            if (data.product_descriptionpart != '') {
                $("#productBottomsticky").show();
                $("#productBottomsticky").html(data.product_bottomsticky);
            }

            if (data.addtocart_max2dayreward != '') {
                $("#addtocart_max2dayreward").show();
                $("#addtocart_max2dayreward").html(data.addtocart_max2dayreward);

                var htm = "<div class='qtyse'>"+refProductDropdown+"</div>";
                $('#mobshipping_info').after(htm);
                var wdth = (window.innerWidth > 0) ? window.innerWidth : screen.width;

                var checkWriteReview = setInterval(function() {
                    if ($('.write-review-btn-hidden').length) {
                        $('.write-review-btn-hidden').hide();
                        clearInterval(checkWriteReview);
                    }
                }, 1000);

                if(wdth < 768){
                    $(".qtyse").html(refProductDropdown);
                    $("#sizeoption").val(product_id);
                    $(".productratingsreview:contains('Ask A Question')").hide();
                } else {
                    $(".productratingsreview:contains('Ask A Question')").show();
                }
                responsiveChanges();
            }

            $("#prodetail").html('');
            $("#prodetail").removeClass("active");

            if (data.product_detail != '') {
                $("#prodetail").addClass("active");
                $("#prodetail").html(data.product_detail);
            }

            if (data.product_review != "") {
                $("#productreview").html(data.product_review);
            }

            $("#SortRelatedProducts").html("");
            if (data.you_may_also_like != "") {
                $("#SortRelatedProducts").html(data.you_may_also_like);
                $(".dtl_gallery_slider").not('.slick-initialized').slick({
                    dots: false,
                    infinite: true,
                    centerMode: true,
                    centerPadding: '0',
                    speed: 300,
                    arrows: true,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    accessibility: true,
                    nextArrow: '<button class="slick-next round-btn-sl slick-arrow" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
                    prevArrow: '<button class="slick-prev round-btn-sl slick-arrow" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 5, slidesToScroll: 5 } },
                        { breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                        { breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll: 2 } }
                    ],
                });
            }

            $("#SpecialPriceSort").html('');
            if (data.SpecialPriceSort != '') {
                $("#SpecialPriceSort").html(data.SpecialPriceSort);
            }
            $("#sort_remaining_days").hide();
            $("#sort_remaining_days").html('');
            if (data.sort_remaining_days != '') {

                $("#sort_remaining_days").show();
                $("#sort_remaining_days").html(data.sort_remaining_days);
                /*
                $(document).ready(function (e) {

                    var $clock = $('#counter_1'),
                        eventTime = moment(data.DealEndDate, 'DD-MM-YYYY HH:mm:ss').unix(),
                        currentTime = moment(data.DealStartDate, 'DD-MM-YYYY HH:mm:ss').unix(),
                        diffTime = eventTime - currentTime,
                        duration = moment.duration(diffTime * 1000, 'milliseconds'),
                        interval = 1000;
                    if (diffTime > 0) {
                        var $d = $('<i style="font-style: normal;"></i>').appendTo($clock),
                            $h = $('<i style="font-style: normal;"></i>').appendTo($clock),
                            $m = $('<i style="font-style: normal;"></i>').appendTo($clock),
                            $s = $('<i style="font-style: normal;"></i>').appendTo($clock);

                        setInterval(function () {

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
                            $d.html('<span class="countdown-section"><span class="countdown-amount" id="days">' + d + '</span><span class="countdown-period">Days</span></span>');
                            $h.html('<span class="countdown-section"><span class="countdown-amount" id="hrs">' + h + '</span><span class="countdown-period">Hours</span></span>');
                            $m.html('<span class="countdown-section"><span class="countdown-amount" id="min">' + m + '</span><span class="countdown-period">Mins</span></span>');
                            $s.html('<span class="countdown-section"><span class="countdown-amount" id="sec">' + s + '</span><span class="countdown-period">Secs</span></span>');

                        }, interval);

                    }

                });
                */
            }

            if (data.productimgicon_manufacture_detail) {
                $("#productimgicon_manufacture_detail").html(data.productimgicon_manufacture_detail);
            }

            if (data.add_towishlist) {
                $("#add_towishlist").html(data.add_towishlist);
            }

            if (data.product_story_notes) {
                $("#product_story_notes").html(data.product_story_notes);
            }
            
            $(".dtl_extra").not('.slick-initialized').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                asNavFor: '.dtl_large',
                dots: false,
                arrows: true,
                centerMode: true,
                accessibility: true,
                focusOnSelect: true
            });

            $(".dtl_large").not('.slick-initialized').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                dots: false,
                asNavFor: '.dtl_extra',
                accessibility: true,
                responsive: [
                    { breakpoint: 768, settings: { dots: true } }
                ],
            });
            $(".cover-spin").show();     
            $('.slider-for').not('.slick-initialized').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                accessibility: true,
                asNavFor: '.slider-nav'
            });
            $('.slider-for').slick('refresh');

            $('.slider-nav').not('.slick-initialized').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                vertical:true,
                accessibility: true,
                nextArrow: '<button class="slick-next round-btn-sl slick-arrow" aria-label="Next slide" role="button"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
                prevArrow: '<button class="slick-prev round-btn-sl slick-arrow" aria-label="Previous slide" role="button"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
                asNavFor: '.slider-for',
                dots: false,
                focusOnSelect: true,
                verticalSwiping:true,
                responsive: [
                 {
                  breakpoint: 992,
                  settings: {
                    vertical: false,
                    slidesToShow: 1,
                    dots: true,
                    arrows: true
                  }
                }   
                ]
            });
            $('.slider-nav').slick('refresh');
            
            $('.dtl_extra').slick('refresh');
            $('.dtl_large').slick('refresh');

            //initialise();
            initialise1();

            if (data.product_url != "") {
                history.pushState({}, null, data.product_url);
            }
            yotpo.refreshWidgets();
            setPayPal();
        },
        complete: function(data){
            $(".cover-spin").hide(); 
            //AfterpaySetup();
            //window.location.href = window.location.href+'?new=1';
        }
    });
    iAdvizeInterval();
    return true;
}

/*
if (typeof currency_code !== 'undefined' && parseInt(Min_AP_AMT) > 0) {
    currency_code = (currency_code == "EURO") ? "EUR" : currency_code;
    const apConfig = {
        priceSelector: '#pricediv',
        locale: 'en_US',
        currency: currency_code,
        minMaxThreshold: {
            min: Min_AP_AMT,
            max: Max_AP_AMT,
        },
        afterpayLogoColor: 'black'
    };
    window.onload = function () {
        var val = $("#pricediv").html();
        var priceval = Number(val.replace(/[^0-9.-]+/g, ""));
        $("#pricediv").html(priceval.toFixed(2));
        var cmpval = parseFloat(priceval * 100);

        new presentAfterpay(apConfig).init();
        if (cmpval > Min_AP_AMT && cmpval < Max_AP_AMT) {
            $("#afterpay_div").show();

            $(".afterpay-paragraph").click(function () {
            });

            $(".afterpay-paragraph .afterpay-link").click(function (e) {
                e.stopPropagation();
            });
        } else {
        }
        //remove "or" from text
        $(".afterpay-text1").css('text-transform', "capitalize");

        if ($(".afterpay-link").length) {
            $(".afterpay-link").empty().html("<i class='fa fa-info-circle'></i>");
            $(".afterpay-paragraph").on("mouseover", function () { this.style.cursor = "pointer"; });
        }
    }
}*/

function initialise1() {
    var imgnam11 = $('.zoom_img img').attr('src');
    
    if ($('.zoom_img img').attr('data-zoom-img') != "") {
        imgnam11 = $('.zoom_img img').attr('data-zoom-img');
    }
    
    imgnam11 = $('.slider-for .slick-active').find('img').attr('src');
     
    $('.zoom_img').jzoom({ bgColor: "#fff", suffixName: imgnam11 });
    //console.log($('.slider-for .slick-active').find('img').attr('src'));
    // Get the modal
    var modal = document.getElementById("imgmodal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("ExtraImages");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    $(document).on("click", '#ExtraImages', function () {
        var bigImg = $('.slider-for .slick-active').find('img').attr('src');
        modal.style.display = "block";
        modalImg.src = bigImg;
        captionText.innerHTML = this.alt;
    });

    // img.onclick = function () {
    //     console.log('modal',modal);
    //     modal.style.display = "block";
    //     modalImg.src = this.src;
    //     captionText.innerHTML = this.alt;
    //     console.log('modal',modal);

    // }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal

    $(span).on("click", span, function () {
        $(".modal-content-zoom-image").css({ "animation-name": "zoomout", "animation-duration": "0.6s" });
        $(".zoom-modal").fadeOut("500");
        setTimeout(function () { $(".modal-content-zoom-image").css({ "animation-name": "zoom", "animation-duration": "0.6s" }); }, "600");	//modal.style.display = "none";
    });

    // span.onclick = function () {
    //     $(".modal-content-zoom-image").css({ "animation-name": "zoomout!important", "animation-duration": "0.6s" });
    //     $(".modal").fadeOut("500");
    //     setTimeout(function () { $(".modal-content-zoom-image").css({ "animation-name": "zoom!important", "animation-duration": "0.6s" }); }, "600");	//modal.style.display = "none";
    // }

    $(document).on("click", '#imgmodal', function (e) {
        var clsclik = e.target.className;
        if (clsclik != "modal-content-zoom-image" && clsclik != "close") {
            $(".close").trigger("click");
        }
    });

    $("#imgmodal").click(function (e) {
        var clsclik = e.target.className;
        if (clsclik != "modal-content-zoom-image" && clsclik != "close") {
            $(".close").trigger("click");
        }
    });
}

$(document).ready(function () {
    initialise1();
    var Total = $(".ul_col li").length;
    var TotalInactive = $('.ul_col li[style*="display: none"]').length;
    
    if(Total == TotalInactive)
    {
        $(".titles").hide();
    } else {
        $(".titles").show();
    }
    $("#togglestockitem").click(function () {
        var msg = $(this).text();
        if (msg == 'Show out of stock items') {
            $('#togglestockitem').html('<strong>Hide out of stock items</strong>');
        } else {
            $('#togglestockitem').html('<strong>Show out of stock items</strong>');
        }
        $(".outofstockitem").toggle();
        
        var Total = $(".ul_col li").length;
        var TotalInactive = $('.ul_col li[style*="display: none"]').length;

        if(Total == TotalInactive)
        {
            $(".titles").hide();
        } else {
            $(".titles").show();
        }
    });


    const options = document.querySelectorAll('.ul_col [role="option"]');
    options.forEach(option => {
        option.querySelector('a').addEventListener('click', e => {
            e.preventDefault();
            // AJAX already handling update
            options.forEach(o => {
                o.setAttribute('aria-selected', 'false');
            });
            option.setAttribute('aria-selected', 'true');
        });
    });
});

$(document).on("click",".detailtab",function(){
    var tabid = $(this).attr('data-id');
    $("#"+tabid).click();
    $('html, body').animate({scrollTop: $("#dtl_tab").offset().top - 80}, 1000);
});

/*
$(document).ready(function (e) {
    var $clock = $('#counter_1'),
        eventTime = moment(DealEndDate, 'DD-MM-YYYY HH:mm:ss').unix(),
        currentTime = moment(DealStartDate, 'DD-MM-YYYY HH:mm:ss').unix(),
        diffTime = eventTime - currentTime,
        duration = moment.duration(diffTime * 1000, 'milliseconds'),
        interval = 1000;
    if (diffTime > 0) {
        var $d = $('<i style="font-style: normal;"></i>').appendTo($clock),
            $h = $('<i style="font-style: normal;"></i>').appendTo($clock),
            $m = $('<i style="font-style: normal;"></i>').appendTo($clock),
            $s = $('<i style="font-style: normal;"></i>').appendTo($clock);

        setInterval(function () {

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

            $d.html('<span class="countdown-section"><span class="countdown-amount" id="days">' + d + '</span><span class="countdown-period">Days</span></span>');
            $h.html('<span class="countdown-section"><span class="countdown-amount" id="hrs">' + h + '</span><span class="countdown-period">Hours</span></span>');
            $m.html('<span class="countdown-section"><span class="countdown-amount" id="min">' + m + '</span><span class="countdown-period">Mins</span></span>');
            $s.html('<span class="countdown-section"><span class="countdown-amount" id="sec">' + s + '</span><span class="countdown-period">Secs</span></span>');

        }, interval);
    }
});
*/

function VideoPlayerNew(imagename, largeimage, type) {
    if (imagename != '' && type == "image") {
        $(".slider-for").show();
        $("#video").hide();
        $('#ExtraImages').attr('src', imagename);
        $('#ExtraImages').parents( ".slick-slide" ).css( "opacity", "1" );
        $('#ExtraImages').attr('data-other-src', imagename);
        
        if (largeimage.indexOf("/medium/") >= 0){
			largeimage = largeimage.replace('/medium/', '/large/');
		}else{
			largeimage = largeimage;
		}
        
        $('#ExtraImages').attr('data-zoom-img', largeimage);
        $(".zoomImg").attr('src', largeimage);
        
        $(".setDiv").find('img').attr('src',largeimage);
    }
    else if (imagename != '' && type == "video") {
        $(".slider-for").hide();
        $("#video").show();
        $("#iframe#video").attr("src", $("iframe#video").attr("src").replace("autoplay=0", "autoplay=1"));
        //$('#ExtraImages').hide();
        //$("#video").show();
        //$("iframe#video").attr("src", $("iframe#video").attr("src").replace("autoplay=0", "autoplay=1"));
    }
}

function VideoPlayer(imagename, largeimage, type) {
    if (imagename != '' && type == "image") {
        $('#ExtraImages').show();
        $("#video").hide();
        $('#ExtraImages').attr('src', imagename);
        $('#ExtraImages').parents( ".slick-slide" ).css( "opacity", "1" );
        $('#ExtraImages').attr('data-other-src', imagename);
        
        if (largeimage.indexOf("/medium/") >= 0){
			largeimage = largeimage.replace('/medium/', '/large/');
		}else{
			largeimage = largeimage;
		}
        
        $('#ExtraImages').attr('data-zoom-img', largeimage);
        $(".zoomImg").attr('src', largeimage);
        
        $(".setDiv").find('img').attr('src',largeimage);
    }
    else if (imagename != '' && type == "video") {
        $('#ExtraImages').hide();
        $("#video").show();
        $("iframe#video").attr("src", $("iframe#video").attr("src").replace("autoplay=0", "autoplay=1"));
    }
}


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

function changetab(typeval)
{
	$('html, body').animate({scrollTop: $("#productreview").offset().top - 80}, 1000);
}

function changestickoption(typeval,tabname)
{
	//$('html, body').animate({scrollTop: $("#"+typeval).offset().top - 80}, 1000);
    //$('#dtl_tab').easyResponsiveTabs({});
    //$('ul.resp-tabs-list li:nth-child('+tabname+')').trigger('click');
}

if($(this).scrollTop() == 0){
    //console.log($(this).scrollTop());
    $('.dtl_sticky').removeClass('d-md-block');
}

$(window).scroll(function(){
    if ($(this).scrollTop() > 200) {
        //console.log($(this).scrollTop());
        $('.dtl_sticky').addClass('d-md-block');
    } else {
       $('.dtl_sticky').removeClass('d-md-block');
    }
});

/*(function ($) {
    $(document).ready(function() {

        //Integration with "Magnific Popup" plugin
        $(".xzoom, .xzoom-gallery").xzoom({zoomWidth:250,
            zoomHeight:250,
            lensCollision:false,
            defaultScale:-1, //-100%
            smoothScale:6,
            Xoffset: 50,
            smoothZoomMove:6
        });

        $('.xzoom:first').bind('click', function() {
            var xzoom = $(this).data('xzoom');
            xzoom.closezoom();
            var gallery = xzoom.gallery().cgallery;
            var i, images = new Array();
            for (i in gallery) {
                images[i] = {src: gallery[i]};
            }
            $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
            event.preventDefault();
        });
    });
})(jQuery);*/

$(document).on("click", '#reinitialiseimg', function () {

  
});

