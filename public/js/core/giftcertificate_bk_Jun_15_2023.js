$(document).ready(function () {
	//~ $('.gift-main-sl').slick({
	//~ dots: true,
	//~ arrows: true,	
	//~ infinite: true,
	//~ autoplay: true,
	//~ autoplaySpeed: 2000,
	//~ speed: 300,
	//~ nextArrow: '<button class="slick-next"><svg class="sv-slick-arrow" aria-hidden="true" width="48px" height="" role="img"><use href="#sv-slick-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-slick-arrow"></use></svg></button>',
	//~ prevArrow: '<button class="slick-prev"><svg class="sv-slick-arrow  roted180deg" width="48px" height="" aria-hidden="true" role="img"><use href="#sv-slick-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-slick-arrow"></use></svg></button>',
	//~ });

	$('.gift-main-sl').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: false,
		arrows: true,
		infinite: false,
		autoplay: false,
		autoplaySpeed: 2000,
		speed: 300,
		fade: false,
		nextArrow: '<button class="slick-next"><svg class="sv-slick-arrow" aria-hidden="true" width="48px" height="48px" role="img"><use href="#sv-slick-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-slick-arrow"></use></svg></button>',
		prevArrow: '<button class="slick-prev"><svg class="sv-slick-arrow  roted180deg" width="48px" height="48px" aria-hidden="true" role="img"><use href="#sv-slick-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-slick-arrow"></use></svg></button>',
		asNavFor: '.gift-nav-sl',
	});

	$('.gift-main-sl').on('beforeChange', function(event, slick, currentSlide, nextSlide){
		var gift_name = $("#maingift"+nextSlide).attr('data-id');
		$("#GiftImage").val(gift_name);
	});
	
	$('.gift-nav-sl').slick({
		centerMode: true,
		centerPadding: '0px',
		slidesToShow: 5,
		slidesToScroll: 1,
		asNavFor: '.gift-main-sl',
		dots: false,
		responsive: [
				{ breakpoint: 992, settings: { slidesToShow: 5, slidesToScroll: 1 } },
				{ breakpoint: 767, settings: { slidesToShow: 6, slidesToScroll: 1 } },
				{ breakpoint: 479, settings: { slidesToShow: 4, slidesToScroll: 1 } }
		],
		focusOnSelect: true
	});
	
	$('.gift-nav-sl').on('beforeChange', function(event, slick, currentSlide, nextSlide){
		var gift_name = $("#mobgift"+nextSlide).attr('data-id');
		$("#GiftImage").val(gift_name);
	});

	//~ $('.gift-main-sl').on('afterChange', function() {
	//~ //var dataId = $('.gift-main-sl').slick('slickCurrentSlide');   
	//~ var dataId = $('.slick-current .item').attr("data-id");    
	//~ console.log(dataId);
	//~ });

	$('#frmGiftCerti').validate({
		ignore: ":hidden:not(.validateGRecaptcha)",
		rules: {
			gc_value: { required: true, digits: true, range: [$("#minimumorder").val(), $("#maximumorder").val()] },
			cardamount: { required: "div#CustomeStr:visible", digits: true, range: [$("#minimumorder").val(), $("#maximumorder").val()] },
			recname: { required: true },
			recemail: { required: { depends: function () { $(this).val($.trim($(this).val())); return true; } }, email: true },
			recconfemail: { required: { depends: function () { $(this).val($.trim($(this).val())); return true; } }, email: true, equalTo: "#recemail" },
			yourname: { required: true },
			youremail: { required: { depends: function () { $(this).val($.trim($(this).val())); return true; } }, email: true },
			hiddenRecaptcha: {
				required: function () {
					if (grecaptcha.getResponse() == '') {
						return true;
					} else {
						return false;
					}
				}
			}
		},
		messages: {
			gc_value: { required: GetMessage('Gift_Certi', 'GiftAmount'), digits: GetMessage('Gift_Certi', 'WrongGiftAmount'), },
			cardamount: { required: "Please enter custom amount between " + $("#minimumordermsg").val() + " and " + $("#maximumordermsg").val() + "", digits: GetMessage('Gift_Certi', 'WrongGiftAmount'), range: "Please enter custom amount between " + $("#minimumordermsg").val() + " and " + $("#maximumordermsg").val() + "" },
			recname: GetMessage('Gift_Certi', 'RecName'),
			recemail: { required: GetMessage('Gift_Certi', 'RecEmail'), email: GetMessage('Gift_Certi', 'ValidEmail') },
			recconfemail: { required: GetMessage('Gift_Certi', 'ReRecEmail'), email: GetMessage('Gift_Certi', 'ValidEmail'), equalTo: GetMessage('Gift_Certi', 'ReRecEmail') },
			yourname: GetMessage('Gift_Certi', 'Name'),
			youremail: { required: GetMessage('Gift_Certi', 'Email'), email: GetMessage('Gift_Certi', 'ValidEmail') },
			hiddenRecaptcha: 	{ required: "Please select I'm not a robot." },
		},
		onsubmit: false,
		invalidHandler: function (form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				for (var i = 0; i < errors; i++) {
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmGiftCerti #error_" + id).html(message);
					$("#frmGiftCerti #error_" + id).show();
				}
			}
			else {
				$("#frmGiftCerti .frmerror").html('');
			}
		},
		errorPlacement: function (error, element) {
			if(element.attr('name') == 'hiddenRecaptcha'){
				error.appendTo($('#error_captcha_js')); 
				$('#error_captcha_js').html("Please select I'm not a robot.");
				$('#error_captcha_js').show();
			}
		}

	});
});

function GiftChangeAmount(GiftAmount, obj) {
	$("#CustomeStr").hide();
	$("#cardamount").val('');
	$("#customflag").val('');
	$('#giftamountcss a').removeClass('active');
	$(obj).addClass('active');
	if (GiftAmount == 'Custom') {
		$("#CustomeStr").show();
		$("#customflag").val('Custom');
	}
	else {
		$("#gc_value").val(GiftAmount)
	}
}

function GiftValAssign(GiftValue) {
	$("#gc_value").val(GiftValue)
}
function ChangeDeliveryDate(DateStringVal, obj) {
	//$("#deliverydate").val('');
	$("#FutureDateStr").hide();
	$("#dateflag").val('');
	$('#giftdatecss a').removeClass('active');
	$(obj).addClass('active');
	if (DateStringVal == 'Instantly') {
	}
	else {
		$("#FutureDateStr").show();
		//$("#deliverydate").val($("#filterDate").val());
		$("#dateflag").val('FutureDate');
	}
}
let file = "public/css/core/datepicker.css";
$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
$("#deliverydate").attr("data-date", "mm/dd/yyyy");

$("#deliverydate").on("change", function() {
	let file = "public/css/core/datepicker.css";
	$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
});

function submitform() {
	var dataId = $('.slick-current .item').attr("data-id");
	$("#GiftImage").val(dataId);

	//alert($('#frmGiftCerti').valid());return false;

	//$('#frmGiftCerti').submit();return false;
	if ($('#frmGiftCerti').valid()) {
		$('#frmGiftCerti').submit();
	}

}

$(document).ready(function(){
    $(this).scrollTop(0);
});
