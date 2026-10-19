$(document).ready(function() {
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
    $(function() {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
    // SidePanel Sliding Effect
    $(".header_icon .cart-link").click(function() {
        $('#cart-open').animate({
            right: '0px'
        });
        $('body').toggleClass('slide-open');

    });
    $(".sidepanel > .close").click(function() {
        $('#cart-open').animate({
            right: '-320px'
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
    });
    /////// Footer Toggle Section
    $(function() {
        var w = $(document).width();
        $(function() {
            adjustMenu1();
        });
        $(window).bind('resize orientationchange', function() {
            w = document.body.clientWidth;
            adjustMenu1();
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

});
/////// My account Toggle Section
$(function() {
    $(".myact_nav").click(function() {
        $(".myact_nav_cot").slideToggle();
    });
    $(window).resize(function() {
        if ($(this).width() < 1025) {
            $(".myact_nav_cot").hide();
        } else {
            $(".myact_nav_cot").show();
        }

    });
});
