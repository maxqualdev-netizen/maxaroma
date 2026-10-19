var sw = $(window).width();
var sh = $(window).height();

function reinitializeSearchspring() {
  // This is where you put your specific Searchspring re-initialization code.
  // For example, trigger an input event on the search box.
  const searchInput = document.getElementById('keyword');
  if (searchInput) {
    //searchInput.value = ''; // Clear the input
    const inputEvent = new Event('input', { bubbles: true });
    searchInput.dispatchEvent(inputEvent);
    console.log('Searchspring autocomplete was re-initialized after idle.');
  }
}

$("#guestmember").click(function(){
	$("#guest-to-member").toggle();
});

$('#frmguestmember').submit(function(){
	if(!$("#termsprivacy").prop('checked'))
	{
		alert('Please indicate that you have read and agree to the Terms and Conditions');
		return false;
	}
	$("#frmguestmember .frmerror").html('').hide();
	var password = $("#guest_password").val();
	var confirmPassword = $("#guest_confirm_password").val();
	var hasError = false;
	if(!password)
	{
		$("#frmguestmember #error_guest_password").html(GetMessage('Register','Password')).show();
		hasError = true;
	}
	if(!confirmPassword || confirmPassword !== password)
	{
		$("#frmguestmember #error_guest_confirm_password").html(GetMessage('Validate','ValidConfirmPassword')).show();
		hasError = true;
	}
	if(hasError)
	{
		return false;
	}
	$.ajax({
		url: site_url+"setguestmember",
		type: "POST",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
		},
		data: {
			password: password,
			action: 'guest_to_member'
		},
		success: function(response) {
			if(response.success == "1")
			{
				$("#maingmdiv").hide();
				$("#messages").html("<div class='alert alert-success'>"+response.message+"</div>");
			} else {
				$("#messages").html("<div class='alert alert-danger'>"+response.message+"</div>")
			}
		}
	});
	return false;
});
$(document).ready(function() {

	// Function to fetch and update the CSRF token
    function refreshCsrfToken() {
        $.ajax({
            url: site_url + 'refresh-csrf', // The route you defined
            method: 'GET',
            success: function(response) {
                // Update the meta tag with the new token
                $('meta[name="csrf-token"]').attr('content', response.token);
                // Optionally, update any other elements that might be using the token
                $('input[name="_token"]').val(response.token);
                console.log('CSRF token refreshed.'+response.token);
            },
            error: function(xhr, status, error) {
                console.error('Error refreshing CSRF token:', error);
            }
        });
    }

    // Set an interval to refresh the token every hour (3600000 milliseconds)
    setInterval(refreshCsrfToken, 3600000);
    //setInterval(refreshCsrfToken, 60000);//1 minute

    // Initial refresh on page load (optional, but good practice)
    //refreshCsrfToken();

	var token = $('meta[name="csrf-token"]').attr('content');

	// Custom search autocomplete: debounce + abort stale Meili requests
	var AUTOCOMPLETE_DEBOUNCE_MS = 80;
	var AUTOCOMPLETE_LOADER_DELAY_MS = 120;
	var autocompleteTimer = null;
	var autocompleteLoaderTimer = null;
	var autocompleteXhr = null;
	var autocompleteReqId = 0;

	function abortAutocompleteRequest() {
		if (autocompleteXhr !== null) {
			try { autocompleteXhr.abort(); } catch (e) {}
			autocompleteXhr = null;
		}
	}

	function clearAutocompleteTimer() {
		if (autocompleteTimer) {
			clearTimeout(autocompleteTimer);
			autocompleteTimer = null;
		}
		if (autocompleteLoaderTimer) {
			clearTimeout(autocompleteLoaderTimer);
			autocompleteLoaderTimer = null;
		}
	}

	function showAutocompleteLoaderSoon() {
		if (autocompleteLoaderTimer) {
			clearTimeout(autocompleteLoaderTimer);
		}
		autocompleteLoaderTimer = setTimeout(function () {
			autocompleteLoaderTimer = null;
			$('div.showLoader').show();
		}, AUTOCOMPLETE_LOADER_DELAY_MS);
	}

	function hideAutocompleteLoader() {
		if (autocompleteLoaderTimer) {
			clearTimeout(autocompleteLoaderTimer);
			autocompleteLoaderTimer = null;
		}
		$('div.showLoader').hide();
	}

	function runHeaderAutocomplete() {
		var setKeyword = $('#keyword').val();
		setKeyword = setKeyword.replace("&","andd");
		setKeyword = setKeyword.replace("/","backslash");

		if ($('#keyword').val().length <= 2) {
			hideAutocompleteLoader();
			$("#autoSuggesstion").html('');
			$("#autoSuggesstion").hide();
			return;
		}

		showAutocompleteLoaderSoon();

		abortAutocompleteRequest();
		var reqId = ++autocompleteReqId;
		autocompleteXhr = $.ajax({
			type: 'POST',
			url: site_url + "searchspring_autocomplete",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: { keyword: setKeyword },
			success: function(data){
				if (reqId !== autocompleteReqId) {
					return;
				}
				hideAutocompleteLoader();
				$('#searchResults').removeClass('show');
				$("#autoSuggesstion").html(data).show().css({
					'display': 'block',
					'z-index': 10050,
					'position': 'relative'
				});
				$("#serach-dropdownauto").css("overflow","hidden");
				$('.src-sec').slimscroll({
					height: '550px'
				});
				initAutocompletePriceSlider();

				if (window.yotpo && typeof window.yotpo.initWidgets === 'function') {
					window.yotpo.initWidgets();
				}

				try {
					if (window.MaxSearchAnalytics && typeof window.MaxSearchAnalytics.trackSearch === 'function') {
						var acCount = $("#autoSuggesstion").find('[data-product-id], .product-box, .prodbox').length;
						var $nlpMeta = $("#autoSuggesstion").find("#max-analytics-nlp");
						var nlpBrand = ($nlpMeta.attr("data-brand") || "").trim() || undefined;
						var isNlp = $nlpMeta.attr("data-nlp") === "1";
						window.MaxSearchAnalytics.trackSearch($('#keyword').val() || setKeyword, {
							resultCount: acCount,
							isSearch: 1,
							isNlp: isNlp,
							brand: nlpBrand,
							newQueryId: false,
							domRoot: document.getElementById("autoSuggesstion")
						});
					}
				} catch (e) {}
			},
			error: function (xhr) {
				if (xhr && xhr.statusText === 'abort') {
					return;
				}
				if (reqId !== autocompleteReqId) {
					return;
				}
				hideAutocompleteLoader();
			},
			complete: function () {
				if (reqId === autocompleteReqId) {
					autocompleteXhr = null;
				}
			}
		});
	}

    $("#keyword").keyup(function(){
		clearAutocompleteTimer();
		abortAutocompleteRequest();

		if ($('#keyword').val() === '') {
			hideAutocompleteLoader();
			$("#autoSuggesstion").html('');
			$("#autoSuggesstion").hide();
			return;
		}

		if ($('#keyword').val().length <= 2) {
			hideAutocompleteLoader();
			$("#autoSuggesstion").html('');
			$("#autoSuggesstion").hide();
			return;
		}

		autocompleteTimer = setTimeout(function () {
			autocompleteTimer = null;
			runHeaderAutocomplete();
		}, AUTOCOMPLETE_DEBOUNCE_MS);
	});

	$("#accordion .accordion_title").eq(0).addClass("active");
	$("#accordion .accordion_content").eq(0).show();
	$("#accordion .accordion_title").click(function(){
		$(this).next(".accordion_content").slideToggle("slow");
		$(this).toggleClass("active");
	});

	if($("#SocialBtnVal").length > 0 && $("#SocialBtnVal").attr("id")=='SocialBtnVal')
   {
	  $(".review-bottom-hidec").show();
   }
   if(newroutenm!='' &&   newroutenm=="home")
  {
	  $(".clrC").hide();
  }

  if(newroutenm!="billing" && newroutenm!="billing-shipping" && newroutenm!="billing-payment" && newroutenm!="order-receipt"){
    if (sw < 768) {
        $('.head-mobile').on('init', function(event, slick){
            $("#load_head_mobile").hide();
            $(this).parent().find(".cover-spin").hide();
        });
        $('.head-mobile').slick({
            lazyLoad: 'ondemand',
            dots: false,
            arrows: false,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed:3000,
            accessibility: true,
        });
    }
}

	//var $input = $('input, textarea');
	$( "input, textarea" ).each(function( index ){
		//console.log($(this).val());
		if($(this).val() != '')
		{
			$(this).parent().addClass('input-fcs');
		}
	});
	$(document).on('focus blur', 'input, textarea', function(e) {
		//console.log(e.type);
		if( e.type == 'focusin' ){
			$(this).parent().addClass('input-fcs');
		}else{
			if($(this).val() == '')
				$(this).parent().removeClass('input-fcs');
		}
	});

	function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#header-sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('#header-sticky').addClass('header-fixed');
            $('#header-sticky-anchor').height($('#header-sticky').outerHeight());
        } else {
            $('#header-sticky').removeClass('header-fixed');
            $('#header-sticky-anchor').height(0);
        }
    }

    function sticky_relocate_mag() {
        var window_top = $(window).scrollTop();
        var div_top = 0;
        if($('#header-sticky-anchormag').length > 0){
            div_top = $('#header-sticky-anchormag').offset().top;
        }
        if (window_top > div_top) {
            $('#header-sticky-mag').addClass('header-fixed-mag');
            if($('#header-sticky-anchormag').length > 0){
                $('#header-sticky-anchormag').height($('#header-sticky-mag').outerHeight());
            }
        } else {
            $('#header-sticky-mag').removeClass('header-fixed-mag');
            if($('#header-sticky-anchormag').length > 0){
                $('#header-sticky-anchormag').height(0);
            }
        }
    }

    function sticky_relocate_mag_bkp() {
        var window_top = $(window).scrollTop();
        var div_top = $('#header-sticky-anchormag').offset().top;
        if (window_top > div_top) {
            $('#header-sticky-mag').addClass('header-fixed-mag');
            $('#header-sticky-anchormag').height($('#header-sticky-mag').outerHeight());
        } else {
            $('#header-sticky-mag').removeClass('header-fixed-mag');
             $('#header-sticky-anchormag').height(0);
        }
    }

    $(window).scroll(function() {
        var scrollDistance = $(window).scrollTop();
        $('.magpage-sec').each(function(i) {
                if ($(this).position().top <= scrollDistance) {
                        $('.mag-step a.active').removeClass('active');
                        $('.mag-step a').eq(i).addClass('active');
                }
        });
    }).scroll();

  	$(document).on('click','#bookmark',function(){
		alert('Please Press Ctrl+D');
		return false;
	});
    $(function() {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

    $(function() {
        $(window).scroll(sticky_relocate_mag);
        sticky_relocate_mag();
    });

      $(".h-fixed").hover(function() {
        $('.h-fixed').toggleClass('h-fixed-open');
    });

    $(".footer-fixed").hover(function() {
        $('.footer-fixed').toggleClass('f-fixed-open');
    });

    // SidePanel Sliding Effect
    $(".header_icon .cart-link").click(function() {
        $('#cart-open').animate({
            right: '0px'
        });
        $('body').toggleClass('slide-open');
         var token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			type: 'POST',
			url: site_url + 'GetSlideCartData',
			headers: {
				'X-CSRF-TOKEN': token
			},
			success: function (data) {
					if(data!='' )
					{
					$("#ViewCartEventCode").html(data);
				//	DataPrintGA(data);
					}
			}
		});

    });
    function DataPrintGA(data)
    {
		var p = data;
	}
    $(".sidepanel > .close").click(function() {
        $('#cart-open').animate({
            right: '-480px'
        });
        $('body').removeClass('slide-open');

    });
    // SidePanel Sliding Effect
    $(".menu_bar").click(function() {
        $('#navnw-open').animate({
            left: '0px'
        });
        $('body').toggleClass('navby-open');

    });
    $(".sidepanel-nw > .close-nv").click(function() {
        $('#navnw-open').animate({
            left: '-320px'
        });
        $('body').removeClass('navby-open');

    });
    $(".black-grbg").click(function() {
        $('#navnw-open').animate({
            left: '-320px'
        });
        $('body').removeClass('navby-open');

    });
    $(".h-fixed .parsent-hd").click(function() {
        $('.h-fixed').toggleClass('h-fixed-open');
    });
    $(".search-mob").click(function() {
        $('.header_search_mob').toggleClass('open');
        $('#keyword').focus();
    });

    /* header search outside click hide / show code start */
    // $(document).on('click','main:not(.header_search)',function(){
    //     $(".header_search").hide();
    // });
    // $(document).on('click','header',function(event){
    //     if(event.target.id != 'mainsearch' && event.target.id != 'keyword' && event.target.id != 'sidesearch' && $(event.target).closest('.serach-dropdown').attr('id') != 'serach-dropdownauto'){
    //         $('.header_search').hide();
    //     }
    // });
    // $(document).on('click','li .src-nwicon',function(){
    //     $(".header_search").toggle();
    //     $('#keyword').focus();
    // });
     $(document).on('click','li .src-nwicon',function(){
        $(".header_mid").toggleClass('sticky-src');
    });
    /* header search outside click hide / show code end */

    $(document).on('click','#topmsgclose',function(){
        $("#notificationtoggle").hide();
    });
    /////// Footer Toggle Section
    $(function() {
        var w = $(document).width();
        $(function() {
            adjustMenu1();
        });
        $(window).bind('resize orientationchange', function() {
            w = document.body.clientWidth;
			if(sw!=$(window).width() && sh!=$(window).height()){
				sw = $(window).width();
				sh = $(window).height();
				adjustMenu1();
			}
        });
        var adjustMenu1 = function() {
            if (w >= 1025) {
                $('.block .block-title').unbind('click');
                $('.block .block-content').show();
                $('.block .block-title').removeClass('active');
            } else {
                $('.block .block-content').hide();
                $('.block .block-title').removeClass('active');
                $('.block-subscriber .block-content').show();
                $('.block .block-title').unbind('click').bind('click', function(event) {
                    if ($(this).next("div.block-content").css("display") == "none") {
                        $('.block .block-content').slideUp();
                        $('.block .block-title').removeClass('active');
                        $(this).addClass('active');
                        $(this).next("div.block-content").slideDown();
                    } else {
                        $(this).next("div.block-content").slideUp();
                        $(this).removeClass('active');
                    }
                });
            }
        };
    });
    /////// Footer Toggle Section

    /////// Header Navigation Section
    $(function() {
        var w = $(document).width();
        $(function() {
            adjustMenu2();
        });
        $(window).bind('resize orientationchange', function() {
            w = document.body.clientWidth;
			if(sw!=$(window).width() && sh!=$(window).height()){
				sw = $(window).width();
				sh = $(window).height();
				adjustMenu2();
			}
        });
        var adjustMenu2 = function() {
            if (w >= 1025) {
                $('.mainNav.accordion-nav .menu>li .navsub-arrow').unbind('click');
                $('.drop_inner .nav-dropdown h5').unbind('click');
                $('.mainNav.accordion-nav .menu .nav-dropdown').show();
                $('.drop_inner .nav-dropdown .mm-sub').show();
                $('.mainNav.accordion-nav .menu>li .navsub-arrow').removeClass('active');
                $('.drop_inner .nav-dropdown h5').removeClass('active');
            } else {
                $('.mainNav.accordion-nav .menu .nav-dropdown').hide('slow');
                $('.drop_inner .nav-dropdown .mm-sub').hide('slow');
                $('.mainNav.accordion-nav .menu>li .navsub-arrow').removeClass('active');
                $('.drop_inner .nav-dropdown h5').removeClass('active');
                $('.mainNav.accordion-nav .menu>li .navsub-arrow').unbind('click').bind('click', function(event) {
                    if ($(this).next("div.nav-dropdown.nav-sub-mm, div.drop_inner>.mm-sub").css("display") == "none") {
                        $('div.drop_inner .mm-sub').hide('slow');
                        $('.mainNav.accordion-nav .menu .nav-dropdown').slideUp('slow');
                        $('.mainNav.accordion-nav .menu>li .navsub-arrow').removeClass('active');
                        $(this).addClass('active');
                        $(this).next("div.nav-dropdown.nav-sub-mm").slideDown('slow');
                        $('.drop_inner .nav-dropdown h5').removeClass('active');
                        $(this).addClass('active');
                        $(this).next("div.drop_inner .mm-sub").slideDown('slow');

                    } else {

                        $(this).next("div.nav-dropdown.nav-sub-mm").slideUp('slow');
                        $(this).removeClass('active');
                        $(this).next("div.drop_inner .mm-sub").hide('slow');
                    }

                });

                $('.nav-dropdown .drop_inner h5').unbind('click').bind('click', function(event) {
                    if ($(this).next("div.drop_inner>.mm-sub").css("display") == "none") {
                        $('.nav-dropdown .drop_inner h5').removeClass('active');
                        $('div.drop_inner .mm-sub').hide('slow');
                        $(this).addClass('active');
                        $(this).next("div.drop_inner .mm-sub").slideDown('slow');
                    } else {

                        $(this).next("div.drop_inner .mm-sub").slideUp('slow');
                        $(this).removeClass('active');
                        $('.nav-dropdown .drop_inner h5').removeClass('active');
                        $('div.drop_inner .mm-sub').hide('slow');
                    }

                });

            }

        };
    });
    /////// Header Navigation Section

    $(function($) {
        $('a[href="#top"]').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            return false;
        });
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('a[href="#top"]').css({
                    'opacity': 100
                });
            } else {
                $('a[href="#top"]').css({
                    'opacity': 0
                });
            }
        });
    });

    /////// Currency
    $("#selcurrency").change(function () {
        var selcurrency = $('#selcurrency').val();
        $("#page-spinner").show();
        $.ajax({
            type: 'POST',
            url: site_url + 'changecurrency',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: {
                currency: selcurrency,
            },
            success: function (data) {
                //alert("done");
                location.reload();
            }
        });
    });

    /////// Currency
    $("#selcurrencymob").change(function () {
        /* var selcurrency = $('#selcurrencymob').val(); */
        var sb = $('#selcurrencymob').attr('sb');
        var selcurrency = $("#sbOptions_" + sb + " li a.sbFocus").attr('rel');

        $("#page-spinner").show();
        $.ajax({
            type: 'POST',
            url: site_url + 'changecurrency',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: {
                currency: selcurrency,
            },
            success: function (data) {
                //alert("done");
                location.reload();
            }
        });
    });

});
/////// My account Toggle Section
$(function() {
    $(".myact_nav").click(function() {
        $(".myact_nav_cot").slideToggle();
        $(".myact_left_link").toggleClass('active');
    });
    $(".posleft-mobbar, .pos-navbutton").click(function() {
        //$(".myact_nav_cot").slideToggle();
        $("body .myact_left_link").toggleClass('active');
        if($("body .myact_left_link").hasClass('active'))
        {
            $("body .myact_left_link").css('display','block');
        } else {
            $("body .myact_left_link").css('display','none');
        }
    });
    // $(window).resize(function() {
    //     if ($(this).width() < 1025) {
    //         $(".myact_nav_cot").hide();
    //     } else {
    //         $(".myact_nav_cot").show();
    //     }

    // });
});

/////// Brands Menu Left Section
$(function(){
  $('#topbrands_scoller').slimscroll({
    height: '450px',
    animate: true
  });
});

$(function(){
  $('.other-scroll').slimscroll({
    animate: true
  });
});

/* Dev JS starts here */

function submitform(frmname) {
    $('#' + frmname).submit();
}

function resetform(frmname) {
    $('#' + frmname)[0].reset();
}
/* Dev JS ends here */

$("#brand1 a,#brand2 a").click(function () {
    var brandchar = $(this).attr('data-id');
    var ID = $(this).parent().attr('id');
    $('#' + ID + ' a').removeClass('active');
    $(this).addClass('active');
    FindBrands(brandchar, ID);
})
function FindBrands(Char, ID) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: site_url + 'get_brands',
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: {
            Char: Char,
        },
        success: function (data) {
            if (ID == 'brand1')
                $('#alpha1').html(data.Brands);
            else
                $('#alpha2').html(data.Brands);
            //console.log(data);
        }
    });
}

$(document).on("click", '.sv-search', function () {
    Valid_Search_Keyword();
});

$('#keyword').keypress(function (event) {
    if (event.keyCode == 13) {
        Valid_Search_Keyword();
    }
});

function Valid_Search_Keyword() {
    if ($('#keyword').val().replace(/^\s+|\s+$/g, "") == "" || $('#keyword').val() == 'Enter Your Keyword Here' || $('#keyword').val() == 'Search') {
        alert("Please Enter Search Keyword.");
        var keyword = $('#keyword').val();
        $('#keyword').val(keyword.replace(/^\s+|\s+$/g, ""));
        $('#keyword').focus();
        return false;
    }
    else {
        var keyword = $('#keyword').val();
        keyword = keyword.replace(/^\s+|\s+$/g, "");
        keyword = keyword.replace(/  +/g, ' ');
        keyword = keyword.replace("&","andd");
        keyword = keyword.replace("/","backslash");
        keyword = keyword.split(" ").join("-");

        if (keyword != '') {
            searchURL = site_url + "p4u/key-" + encodeURIComponent(keyword) + "/view";
            //alert(searchURL);
            window.location = searchURL;
        }
    }
}

    (function e(){
        var e = document.createElement("script");
        e.type = "text/javascript";
        e.async = true;
        e.src = "//staticw2.yotpo.com/MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8/widget.js"; // <-- Check if 'tetsddd' is correct
        var t = document.getElementsByTagName("script")[0];
        t.parentNode.insertBefore(e, t);
    })();

$(function() {
    $(document).on('click touch', '#autoSuggesstion h2 span', function(){
         $("#autoSuggesstion .src-left-filter .src-fbox").toggle();

          $("#closeSearchAutoComplete").hide();
          if ($('.src-left-filter .src-fbox').is(":visible"))
          {
             $("#closeSearchAutoComplete").show();
          }
    });
    $("#closeSearchAutoComplete").click(function () {
        $("#autoSuggesstion .src-left-filter .src-fbox").hide();
    });

    //$(document).on('mouseover touch', '#autoSuggesstion .changeTab', function(){
    $(document).on('click touch', '.changeTab', function(){
        var tabKeyword = $('a', this).html();
        var getElement = $('a', this);

        var token = $('meta[name="csrf-token"]').attr('content');

        $('#autoSuggesstion .changeTab a').removeClass("serch-ltab-active");
        $('a', this).addClass("serch-ltab-active");

        var setKeyword = tabKeyword;
        setKeyword = setKeyword.replace("&","andd");
        setKeyword = setKeyword.replace("/","backslash");

        var passData = '';
        passData = 'type=changeTabData&keyword='+setKeyword;

        $.ajax({
            type: "POST",
            url: site_url + "searchspring_autocomplete",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:passData,
            success: function(data){
                var splitData = data.split("@@@");
                if(splitData[1] == '' && splitData[2] == ''){
					$(".headingLink").html('');
					$(".src-left-filter").html('');
					$(".src-plist").html('<div style="display: block;"><strong>Sorry, No records found.</strong></div>');
				}else{
					$(".headingLink").html('<a href="'+site_url+'p4u/key-'+splitData[0]+'/view"><strong>'+splitData[0]+'</strong></a>');
					$(".src-left-filter").html(splitData[1]);
					$(".src-plist").html(splitData[2]);
					initAutocompletePriceSlider();

					if (window.yotpo && typeof window.yotpo.initWidgets === 'function') {
					  window.yotpo.initWidgets();
					}
				}
                //$(".src-prdlist").html(splitData[2]);
            }
        });
    });
});

$(document).on("click touch", function(event){
    if(!$(event.target).closest("#autoSuggesstion").length && !$(event.target).closest("#keyword").length && !$(event.target).closest("#searchResults").length && !$(event.target).closest(".search-voice-btn").length){
        $("#autoSuggesstion").html('');
        $("#autoSuggesstion").hide();
    }
});

var list = $('#autoSuggesstion'),
    timeout,

    getSomeDetail = function (passData) {
        return function () {

            var token = $('meta[name="csrf-token"]').attr('content');

           $.ajax({
                type: "POST",
                url: site_url + "searchspring_autocomplete",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:passData,
                success: function(data){
                    var splitData = data.split("@@@");
                    $(".headingLink").html('<a href="'+site_url+'p4u/key-'+splitData[0]+'/view"><strong>'+splitData[0]+'</strong></a>');
                    $(".src-left-filter").html(splitData[1]);
                    $(".src-plist").html(splitData[2]);
					initAutocompletePriceSlider();

                    if (window.yotpo && typeof window.yotpo.initWidgets === 'function') {
					  window.yotpo.initWidgets();
					}
                }
            });
        };
    };
  var ID;
  var applyFilter = 1;

/** Collect every active autosuggest refine chip (multi-filter) + price slider. */
function collectAutocompleteActiveFilters() {
	var activeFilters = [];
	$("#autoSuggesstion .setChk a.active").each(function () {
		var $a = $(this);
		var n = $a.data("name");
		var v = $a.attr("value");
		if (!n) {
			return;
		}
		if (n === "special") {
			v = String($a.attr("id") || "").replace(/^special/, "");
		}
		if (n === "price") {
			return; // price comes from the slider
		}
		if (v === undefined || v === null || v === "") {
			return;
		}
		activeFilters.push({ name: String(n), value: String(v) });
	});
	var $slider = $("#auto-slider-range");
	if ($slider.length && $("#auto-pricechange").val() === "1" && typeof $slider.slider === "function") {
		try {
			var vals = $slider.slider("values");
			if (vals && vals.length >= 2) {
				activeFilters.push({
					name: "price",
					value: String(vals[0]).replace(/\./g, "dot") + "dash" + String(vals[1]).replace(/\./g, "dot")
				});
			}
		} catch (e) {
			var cmin = $("#auto-chgminprice").val();
			var cmax = $("#auto-chgmaxprice").val();
			if (cmin !== undefined && cmax !== undefined && cmin !== "" && cmax !== "") {
				activeFilters.push({
					name: "price",
					value: String(cmin).replace(/\./g, "dot") + "dash" + String(cmax).replace(/\./g, "dot")
				});
			}
		}
	}
	return activeFilters;
}

/**
 * jQuery UI price slider inside autosuggest (bounds = current result min/max).
 * Matches PLP black circular handles; keeps a grab-gap when min===max.
 */
function initAutocompletePriceSlider() {
	var $slider = $("#auto-slider-range");
	var $wrap = $slider.closest(".auto-price-slider");
	if (!$slider.length || typeof $slider.slider !== "function") {
		return;
	}
	if ($slider.data("ui-slider")) {
		try { $slider.slider("destroy"); } catch (e) {}
	}
	var minPrice = parseFloat($("#auto-minprice").val());
	var maxPrice = parseFloat($("#auto-maxprice").val());
	var chgMin = parseFloat($("#auto-chgminprice").val());
	var chgMax = parseFloat($("#auto-chgmaxprice").val());
	if (isNaN(minPrice) || isNaN(maxPrice)) {
		return;
	}
	if (maxPrice < minPrice) {
		maxPrice = minPrice;
	}
	if ((maxPrice - minPrice) < 1) {
		maxPrice = minPrice + Math.max(10, Math.round(minPrice * 0.25 * 100) / 100);
		$("#auto-maxprice").val(maxPrice);
	}
	if (isNaN(chgMin)) chgMin = minPrice;
	if (isNaN(chgMax)) chgMax = maxPrice;
	chgMin = Math.max(minPrice, Math.min(chgMin, maxPrice));
	chgMax = Math.max(minPrice, Math.min(chgMax, maxPrice));
	if (chgMax < chgMin) chgMax = chgMin;

	var step = (maxPrice - minPrice) > 100 ? 1 : 0.1;
	var minGap = step;

	var fmt = function (n) {
		var x = Math.round(Number(n) * 100) / 100;
		return String(x);
	};

	var syncEqualClass = function (v0, v1) {
		if (!$wrap.length) return;
		$wrap.toggleClass("handles-equal", Math.abs(Number(v0) - Number(v1)) < 0.0001);
	};

	$slider.slider({
		range: true,
		min: minPrice,
		max: maxPrice,
		step: step,
		values: [chgMin, chgMax],
		slide: function (event, ui) {
			if (ui.values[1] - ui.values[0] < minGap) {
				if (ui.handleIndex === 0) {
					ui.values[0] = Math.max(minPrice, ui.values[1] - minGap);
					$(this).slider("values", 0, ui.values[0]);
				} else {
					ui.values[1] = Math.min(maxPrice, ui.values[0] + minGap);
					$(this).slider("values", 1, ui.values[1]);
				}
			}
			syncEqualClass(ui.values[0], ui.values[1]);
			$("#auto-amount").val("$" + fmt(ui.values[0]) + " - $" + fmt(ui.values[1]));
			return true;
		},
		stop: function (event, ui) {
			if (ui.values[1] - ui.values[0] < minGap) {
				ui.values[1] = Math.min(maxPrice, ui.values[0] + minGap);
				$(this).slider("values", 1, ui.values[1]);
			}
			syncEqualClass(ui.values[0], ui.values[1]);
			$("#auto-amount").val("$" + fmt(ui.values[0]) + " - $" + fmt(ui.values[1]));
			$("#auto-pricechange").val("1");
			$("#auto-chgminprice").val(ui.values[0]);
			$("#auto-chgmaxprice").val(ui.values[1]);
			refreshAutocompleteWithFilters();
		}
	});
	syncEqualClass(chgMin, chgMax);
	$("#auto-amount").val("$" + fmt(chgMin) + " - $" + fmt(chgMax));
}

/** Re-run autosuggest AJAX with all active filters (used by price slider stop). */
function refreshAutocompleteWithFilters() {
	var setKeyword = "";
	var setType = "";
	$("#autoSuggesstion .changeTab a").each(function () {
		if ($(this).hasClass("serch-ltab-active") == true) {
			setKeyword = $(this).html();
			setType = "&type=changeTabData";
		}
	});
	if (setKeyword === "") {
		setKeyword = $("#keyword").val() || "";
		setType = "";
	}
	setKeyword = String(setKeyword).replace(/&/g, "andd").replace(/\//g, "backslash");
	var activeFilters = collectAutocompleteActiveFilters();
	var passData = "keyword=" + encodeURIComponent(setKeyword)
		+ "&extraFilters=" + encodeURIComponent(JSON.stringify(activeFilters))
		+ setType;

	$.ajax({
		type: "POST",
		url: site_url + "searchspring_autocomplete",
		headers: {
			"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
		},
		data: passData,
		success: function (data) {
			var splitData = String(data).split("@@@");
			if (splitData.length >= 3) {
				$(".headingLink").html('<a href="' + site_url + "p4u/key-" + splitData[0] + '/view"><strong>' + splitData[0] + "</strong></a>");
				$(".src-left-filter").html(splitData[1]);
				$(".src-plist").html(splitData[2]);
				initAutocompletePriceSlider();
			} else {
				$("#autoSuggesstion").html(data);
				initAutocompletePriceSlider();
			}
			if (window.yotpo && typeof window.yotpo.initWidgets === "function") {
				window.yotpo.initWidgets();
			}
		}
	});
}

//list.on('mouseover', '.setChk', function (e) {
list.on('click touch', '.setChk', function (e) {

	var $link = $('a', this);
	var name = $link.data("name");

	if ($link.hasClass("active") == true) {
		$link.removeClass("active");
		applyFilter = 0;
	} else {
		$link.addClass("active");
		applyFilter = 1;
		// Star rating: one chip at a time within that group.
		if (name === "rating_avg") {
			$('#autoSuggesstion .setChk a[data-name="' + name + '"]').not($link).removeClass("active");
		}
	}

	ID = $link.attr('id');
	var setKeyword = setType = '';

	$("#autoSuggesstion .changeTab a").each(function () {
		if ($(this).hasClass("serch-ltab-active") == true) {
			setKeyword = $(this).html();
			setType = '&type=changeTabData';
		}
	});

	if (setKeyword == '') {
		setKeyword = $('#keyword').val();
		setType = '';
	}

	setKeyword = setKeyword.replace("&", "andd");
	setKeyword = setKeyword.replace("/", "backslash");

	var activeFilters = collectAutocompleteActiveFilters();
	var passData = 'keyword=' + encodeURIComponent(setKeyword)
		+ '&extraFilters=' + encodeURIComponent(JSON.stringify(activeFilters))
		+ setType;

	if (timeout) clearTimeout(timeout);

	timeout = setTimeout(getSomeDetail(passData), 0);

})/*.on('mouseleave', function () {
  clearTimeout(timeout);
})*/;

function setFilterValue(){

    var setKeyword = setKeyword1 = url = '';

    $("#autoSuggesstion .changeTab a").each(function() {
        if($(this).hasClass("serch-ltab-active") == true){
            setKeyword = $(this).html();
        }
    });

    if(setKeyword == ''){
        setKeyword = $('#keyword').val();
    }

    setKeyword = setKeyword.replace("&","andd");
    setKeyword = setKeyword.replace("/","backslash");

    setKeyword1 = setKeyword.replace("/","backslash");
    url = site_url + "p4u/key-" + encodeURIComponent(setKeyword1) + "/view";

    var qParts = [];
    var lastChk = '';
    $("#autoSuggesstion .setChk a.active").each(function () {
        var name = $(this).data("name");
        var val = $(this).attr("value");
        if (name === "special") {
            val = String($(this).attr("id") || "").replace(/^special/, "");
        }
        if (!name || name === "price" || val === undefined || val === null || val === "") {
            return;
        }
        lastChk = name + val;
        var decoded = String(val).replace(/_/g, " ").replace(/dash/g, "-").replace(/andd/g, "&").replace(/dot/g, ".").replace(/doubledot/g, ":").replace(/singlecomma/g, "'");
        if (name === "cid") {
            qParts.push("category=" + encodeURIComponent(decoded));
        } else if (name === "mid") {
            qParts.push("brand=" + encodeURIComponent(decoded));
        } else if (name === "size") {
            qParts.push("size=" + encodeURIComponent(decoded));
        } else if (name === "special") {
            qParts.push("badges=" + encodeURIComponent(decoded));
        } else if (name === "rating_avg") {
            qParts.push("rating_avg=" + encodeURIComponent(String(val).replace(/dash/g, "-")));
        }
    });

    if ($("#auto-pricechange").val() === "1") {
        var pmin = $("#auto-chgminprice").val();
        var pmax = $("#auto-chgmaxprice").val();
        if (pmin !== undefined && pmax !== undefined && pmin !== "" && pmax !== "") {
            qParts.push("price=" + encodeURIComponent(pmin + "-" + pmax));
            lastChk = "price" + pmin + "-" + pmax;
        }
    }

    if (lastChk !== '') {
        localStorage.setItem("chkId", lastChk);
    }
    if (qParts.length) {
        url += (url.indexOf("?") >= 0 ? "&" : "?") + qParts.join("&");
    }
    location.href = url;
}

$("#zoomimageclose").click(function () {
    $('#imgmodal').css('display','none');
});

var w = document.body.clientWidth;
    $(function () {
        adjustMenu();
    });
    $(window).bind('resize orientationchange', function () {
        w = document.body.clientWidth;
		if(sw!=$(window).width() && sh!=$(window).height()){
			sw = $(window).width();
			sh = $(window).height();
			adjustMenu();
		}
    });
var $leftmenu = $('.menu-mob');
var adjustMenu = function () {
    if (w >= 1026) {
        $($leftmenu).empty();
    }
    else {
        $($leftmenu).empty();
        $leftmenu.html($(".menu_des").html());
        //$($(".menu_des").html()).appendTo($leftmenu);
    }
}

function move(id) {
    $('#topbrands_scoller').animate({
        scrollTop: $("#"+id+"_top").position().top - $('#topbrands_scoller li:first').position().top
    }, 2000);
}

$(function () {
    $("#search_brands").keyup(function () {
        var searchText = $(this).val();
        searchText = searchText.toUpperCase();
       var test =  $('#topbrands_scoller > li').each(function(){
            var currentLiText = $(this).text().toUpperCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });

        //console.log('sagar', test);
    });
});

$("#getHelpChat, .HelpChat, #getHelpChatNew").click(function(){
	$('.helpButtonEnabled').trigger("click");
    $('.embeddedServiceHelpButton .helpButton').show();
});

$(".setdwnLink").click(function () {
	$("#page-spinner").show();

	if (!$(window).is(":focus")) {
		$(".showDwnMessage").show();
	}else{
		$(".showDwnMessage").hide();
	}

	$.ajax({
		type: 'GET',
		url: $(this).attr('href'),
		success: function (data) {
			$("#page-spinner").hide();
			$(".showDwnMessage").hide();
		}
	});
});

function intellisuggestTrackClick(element, data, signature) {
	var escapeFn = encodeURIComponent || escape;

	if(document.images){
		if ('https:' == location.protocol) {
			var api_url = 'https://faltym.a.searchspring.io/api/';
		}else{
			var api_url = 'http://faltym.a.searchspring.io/api/';
		}

		var imgTag = new Image;
		imgTag.src= api_url+'track/track.json?d='+data+'&s='+signature+'&u='+escapeFn(element.href)+'&r='+escapeFn(document.referrer);
	}

	return true;
}

$('.ga4c').on('click', function(e) {

  dataLayer.push({
            'event': 'top_navigation_click',
            ecommerce: {
            'link_url': $(this).attr('href'),
            'link_text': $(this).text()
			}});

});

function GAEventVal(sku,productname,brand)
{
	dataLayer.push({ ecommerce: null });
		dataLayer.push({
		  event: "select_item",
		  ecommerce: {
			item_list_id: "related_products",
			item_list_name: "Related products",
			items: [
			{
			  item_id: sku,
			  item_name: productname,
			  affiliation: "Maxraoma",
			  item_brand: brand
			 }
			]
		}
	});
}
function offervalid(productid,productname)
{
	dataLayer.push({ ecommerce: null });
		dataLayer.push({
		  event: "select_promotion",
		  ecommerce: {
			items: [
			{
			  item_id: productid,
			  item_name: productname,
			  affiliation: "Maxraoma",
			  index: 0
			 }
			]
		}
	});
}

// 04-Jun-2025 JS for currency dropdown in header start
// Initialize for both select elements
initSelectBoxWithTitles('#selcurrency');
initSelectBoxWithTitles('#selcurrencymob');
initSelectBoxWithTitles('#selsort');
initSelectBoxWithTitles('#sort_by');

// Master function to initialize and bind behavior for a given select element
function initSelectBoxWithTitles(selector) {
    const $select = $(selector);

    // Step 1: Set title attributes on options
    $select.find('option').each(function () {
        $(this).attr('title', $(this).text());
    });

    // Step 2: Initialize selectbox
    $select.selectbox();

    // Step 3: Apply titles after rendering
    setTimeout(function () {
        setTitleFromSelected($select);
        setTitleOnDropdownOptions($select);
        bindToggleTitleHandler($select);
    }, 0);

    // Step 4: Update title on change
    $select.change(function () {
        setTitleFromSelected($select);
    });
}

// Set title on selected item and toggle
function setTitleFromSelected($select) {
    const selectedOption = $select.find('option:selected');
    const title = selectedOption.attr('title');

    const $holder = $select.next('.sbHolder');
    $holder.find('.sbSelector').attr('title', title);
    $holder.find('.sbToggle').attr('title', 'Click to open dropdown');
}

// Set title on each .sbOptions a
function setTitleOnDropdownOptions($select) {
    const $options = $select.find('option');
    const $dropdownLinks = $select.next('.sbHolder').find('.sbOptions a');

    $dropdownLinks.each(function (i) {
        const optionTitle = $options.eq(i).attr('title');
        $(this).attr('title', optionTitle);
    });
}

// Watch open/close and set toggle title dynamically
function bindToggleTitleHandler($select) {
    const $holder = $select.next('.sbHolder');
    const $toggle = $holder.find('.sbToggle');

    $toggle.on('click', function () {
        setTimeout(function () {
            if ($holder.hasClass('sbToggleOpen')) {
                $toggle.attr('title', 'Click to close dropdown');
            } else {
                $toggle.attr('title', 'Click to open dropdown');
            }
        }, 10);
    });
}
// 04-Jun-2025 JS for currency dropdown in header end

// 13-Jun-2025 JS for menu open on tab in header start
document.addEventListener("DOMContentLoaded", function () {
    const parentMenus = document.querySelectorAll(".ga4c");

    parentMenus.forEach((menu, index) => {
        const submenu = menu.closest("li").querySelector(".nav-dropdown");
        if (submenu) {
            submenu.setAttribute("role", "menuitem");
            submenu.classList.remove("open");
        }

        // On focus, open the menu
        menu.addEventListener("focus", () => {
            closeAllMenus();
            if (submenu) {
                submenu.classList.add("open");
            }
        });

        // Close menus when mouse leaves or tab moves
        menu.addEventListener("blur", (e) => {
            setTimeout(() => {
                if (
                !menu.contains(document.activeElement) &&
                !submenu.contains(document.activeElement)
                ) {
                submenu.classList.remove("open");
                }
            }, 100);
        });

        // Keyboard navigation
        menu.addEventListener("keydown", (e) => {
            if (e.key === "ArrowRight") {
                e.preventDefault();
                closeAllMenus();
                const next = parentMenus[index + 1] || parentMenus[0];
                next.focus();
                const nextSub = next.closest("li").querySelector(".nav-dropdown");
                if (nextSub) {
                    nextSub.classList.add("open");
                }
            }

            if (e.key === "ArrowLeft") {
                e.preventDefault();
                closeAllMenus();
                const prev =
                parentMenus[index - 1] || parentMenus[parentMenus.length - 1];
                prev.focus();
                const prevSub = prev.closest("li").querySelector(".nav-dropdown");
                if (prevSub) {
                prevSub.classList.add("open");
                }
            }

            if (e.key === "Escape") {
                submenu.classList.remove("open");
                menu.blur();
            }
        });
    });

    // Function to close all submenus
    function closeAllMenus() {
        parentMenus.forEach((menu) => {
            const sub = menu.closest("li").querySelector(".nav-dropdown");
            if (sub) {
                sub.classList.remove("open");
            }
        });
    }

    var $progressBar = $('.progressbar-sticky');
    if ($progressBar.length > 0) {
        var elementTop = $progressBar.offset().top; // Save original position

        $(window).on("scroll", function () {
            var scrollTop = $(window).scrollTop();

            if (scrollTop >= elementTop - 50) {
                $progressBar.addClass('checkout-progress-sticky');
            } else {
                $progressBar.removeClass('checkout-progress-sticky');
            }
        });
    }
});

// 13-Jun-2025 JS for menu open on tab in header end

// info icon click tooltip show/hide 29-dec-2025 JS Start
$(document).on("click", ".infoBtn", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let $table = $(this).siblings(".infoTables");

    // Pehle sab close
    $(".infoTables").not($table).hide();

    // Current toggle
    $table.toggle();
});

// Outer click pe close
$(document).on("click", function () {
    $(".infoTables").hide();
});

// Table ke andar click pe close na ho
$(document).on("click", ".infoTables", function (e) {
    e.stopPropagation();
});
// info icon click tooltip show/hide 29-dec-2025 JS End
