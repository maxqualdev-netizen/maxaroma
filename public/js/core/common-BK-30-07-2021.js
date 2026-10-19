$(document).ready(function () {
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
    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

    $(".header_icon .cart-link").click(function () {
        $('#cart-open').animate({
            right: '0px'
        });
        $('body').toggleClass('slide-open');

    });
    $(".sidepanel > .close").click(function () {
        $('#cart-open').animate({
            right: '-320px'
        });
        $('body').removeClass('slide-open');

    });

    $(".menu_bar").click(function () {
        $('#navnw-open').animate({
            left: '0px'
        });
        $('body').toggleClass('navby-open');

    });
    $(".sidepanel-nw > .close-nv").click(function () {
        $('#navnw-open').animate({
            left: '-320px'
        });
        $('body').removeClass('navby-open');

    });
    $(".black-grbg").click(function () {
        $('#navnw-open').animate({
            left: '-320px'
        });
        $('body').removeClass('navby-open');

    });
    $(".h-fixed .parsent-hd").click(function () {
        $('.h-fixed').toggleClass('h-fixed-open');
    });
    $(".search-mob").click(function () {
        $('.header_search_mob').toggleClass('open');
    });

    $(function () {
        var w = $(document).width();
        $(function () {
            adjustMenu1();
        });
        $(window).bind('resize orientationchange', function () {
            w = document.body.clientWidth;
            adjustMenu1();
        });
        var adjustMenu1 = function () {
            if (w >= 1025) {
                $('.block .block-title').unbind('click');
                $('.block .block-content').show();
                $('.block .block-title').removeClass('active');
            } else {
                $('.block .block-content').hide();
                $('.block .block-title').removeClass('active');
                $('.block-subscriber .block-content').show();
                $('.block .block-title').unbind('click').bind('click', function (event) {
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

    $(function ($) {
        $('a[href="#top"]').click(function () {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            return false;
        });
        $(window).scroll(function () {
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

    // $(".outofstockhover").hover(function () {
    //     $('.outofstockproduct').show();
    // }, function () {
    //     $('.outofstockproduct').hide();
    // });
});

$(function () {
    $(".myact_nav").click(function () {
        $(".myact_nav_cot").slideToggle();
    });
    $(window).resize(function () {
        if ($(this).width() < 1025) {
            $(".myact_nav_cot").hide();
        } else {
            $(".myact_nav_cot").show();
        }

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
            console.log(data);
        }
    });
}

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
        keyword = keyword.split(" ").join("-");
        keyword = keyword.split("&").join("andd");

        if (keyword != '') {
            searchURL = site_url + "p4u/key-" + keyword + "/view";
            //alert(searchURL);
            window.location = searchURL;
        }
    }
}

if ($('#keyword').length) {

    var extraLIs = '<li class="search-pl"><ul><li class="search-pl-hd">Popular Searches</li><li><a href="#">Lorem Ipsum</a></li><li><a href="#">Lorem Ipsum</a></li><li><a href="#">Lorem Ipsum</a></li></ul></li>';

    var token = $('meta[name="csrf-token"]').attr('content');

    $('#keyword').autocomplete({
        minLength: 3,
        //source: site_url + "searchspring_autocomplete",

        source: function (request, response) {
            $.ajax({
                url: site_url + "searchspring_autocomplete",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: {
                    term: $('#keyword').val(),
                },
                dataType: "json",
                type: "POST",
                success: function (data) {
                    response(data);
                }
            })
        },

        response: function (event, ui) { },
        select: function (event, ui) {
            event.preventDefault();
            location.href = ui.item.url;
        },
        focus: function (event, ui) {
            event.preventDefault();
        },
    }).on('keyup', function (event) {
    }).data('ui-autocomplete')._renderItem = function (ul, item) {
        ul.addClass('user-search');
        ul.addClass('addscroll');

        return $('<li style="padding: 7px;"></li>').data("item.autocomplete", item).append('<a href="' + item.url + '"><img src="' + item.image + '" style="width: 60px;vertical-align: middle;"><span>' + item.name + '</span></a>').appendTo(ul);
    };
}

$("#zoomimageclose").click(function () {
    $('#imgmodal').css('display','none');
});