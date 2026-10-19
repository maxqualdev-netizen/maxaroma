var setFilterData = new Array();
var PageFilters = '';

/** Keep search/PLP filter accordions collapsed (SS behavior). */
function collapseFilterAccordions() {
	$(".filter_acrd").removeClass("filter_acrd_act");
	$(".filter_acrd_con").hide();
}

/**
 * Each filter list needs a fixed height or mCustomScrollbar grows with content (no bar).
 * Call on load, after AJAX filter HTML replace, and when an accordion opens.
 */
function initFilterScrollbars($root) {
	var $scopes = $root && $root.length
		? $root.find('.filter_acrd_con .scroll')
		: $('.filter_acrd_con .scroll');
	if (!$scopes.length || typeof $.fn.mCustomScrollbar !== 'function') {
		return;
	}
	$scopes.each(function () {
		var $el = $(this);
		try {
			if ($el.hasClass('mCustomScrollbar')) {
				$el.mCustomScrollbar('destroy');
			}
		} catch (err) {}
		$el.mCustomScrollbar({
			setHeight: 320,
			theme: 'dark',
			scrollInertia: 300,
			advanced: { updateOnContentResize: true, autoScrollOnFocus: false }
		});
	});
}

function refreshFilterScrollbar($panel) {
	if (!$panel || !$panel.length || typeof $.fn.mCustomScrollbar !== 'function') {
		return;
	}
	var $scroll = $panel.find('.scroll').first();
	if (!$scroll.length) {
		return;
	}
	try {
		if ($scroll.hasClass('mCustomScrollbar')) {
			$scroll.mCustomScrollbar('update');
		} else {
			initFilterScrollbars($panel);
		}
	} catch (err) {
		initFilterScrollbars($panel);
	}
}

$(document).on("click", "#chktoprated", function () {
	var Page = 1;
	$('.list-more').attr('data-page', Page);
	GetProducts();
});

$(document).on("click", "#chkinstock", function () {
	var Page = 1;
	$('.list-more').attr('data-page', Page);
	GetProducts();
});

$(document).on("click", "#chkSaleDeal", function () {
	var Page = 1;
	$('.list-more').attr('data-page', Page);
	GetProducts();
});

(function ($) {
	$(window).on('load', function () {
		collapseFilterAccordions();
		initFilterScrollbars();
		// Capture SSR/NLP sort so Clear all / Reset all restore the same baseline.
		if ($('#sortopt').length && $('#sortopt').attr('data-baseline-sort') === undefined) {
			var baselineSort = $('#sortopt').val() || '';
			if (!baselineSort || baselineSort === 'Sort By') {
				baselineSort = '';
			}
			$('#sortopt').attr('data-baseline-sort', baselineSort);
		}
		// Capture first-load toggle state (NLP may have checked In Stock / Top Rated / Sale).
		['#chktoprated', '#chkinstock', '#chkSaleDeal'].forEach(function (sel) {
			var $el = $(sel);
			if ($el.length && $el.attr('data-baseline-checked') === undefined) {
				$el.attr('data-baseline-checked', $el.is(':checked') ? '1' : '0');
			}
		});
	});
	$(".filter_btn").click(function () {
		$("body").toggleClass('open-filter');
		
		if($("body").hasClass('open-filter')){
			$("#yotpo_testimonials_btn").hide();
		}else{
			$("#yotpo_testimonials_btn").show();
		}
	});
	$(".filter-close").click(function () {
		$("body").removeClass('open-filter');
		$("#yotpo_testimonials_btn").show();
	});

})(jQuery);
/*
$(window).scroll(function(e) {
	if(($(window).scrollTop() + 250) >= $('.listing_grid').height()) 
	{
		var TotalProds = parseInt($('.list-more').attr('data-total'));
		var CurrProdsCount = $(".product").length;
		if($(".listing_grid").attr('data-load') == '0' && CurrProdsCount < TotalProds)
		{
			var Page = parseInt($('.list-more').attr('data-page'));
			Page = Page + 1;
			$('.list-more').attr('data-page', Page);
			GetProducts(true);
		}
    }
});
*/
(function ($) {
	$(document).ready(function () {
		collapseFilterAccordions();
		//$('.reset_link').hide();
		setTimeout(function(){applyFilters();},300);
		if($('#categories li a.catactive').length > 0)
			$('#reset-categories').show();
		else
			$('#reset-categories').hide();
		
		PageFilters = GetFilters();
		//GetProducts();
		$(document).on("click", ".filter_checkbox li a", function () {
			$(".filter").attr('data-current',$(this).text());
			$('.list-more').attr('data-page', '1');
			$('.listing_grid').attr('data-load', '0');

			var filterParent = $(this).parent().parent();
			var filID = $(filterParent).attr('id');
			var $link = $(this);

			// Star Rating: one bucket only (5 / 4+ / 3+) — radio, not multi-select.
			if (filID === 'rating_avg') {
				if ($link.hasClass('active')) {
					$link.removeClass('active').attr('aria-pressed', 'false');
				} else {
					$('#' + filID + ' li a.active').removeClass('active').attr('aria-pressed', 'false');
					$link.addClass('active').attr('aria-pressed', 'true');
				}
			} else if ($link.hasClass('active')) {
				$link.removeClass('active');
			} else {
				$link.addClass('active');
			}
			
			if($('#'+filID+' li a.active').length > 0)
				$('#reset-'+filID).show();
			else
				$('#reset-'+filID).hide();
			
			if(!mobilecheck())
			{			
				GetProducts();
			}
		});
		
		$(document).on("click", "#categories li a", function () {
			$(".filter").attr('data-current',$(this).text());
			$('.list-more').attr('data-page', '1');
			if ($(this).hasClass('catactive'))
				$(this).removeClass('catactive');
			else
				$(this).addClass('catactive');
			
			if($('#categories li a.catactive').length > 0)
				$('#reset-categories').show();
			else
				$('#reset-categories').hide();
			GetProducts();
			if(mobilecheck())
			{
				$(".filter-close").click();
			}
		});
		$(document).on("click", ".list-more", function () {
		//$(".list-more").click(function () {
			var Page = parseInt($(this).attr('data-page'));
			Page = Page + 1;
			$(this).attr('data-page', Page);
			GetProducts();
		});
		// Sort changes are handled once in head-bottom.js (#selsort selectbox onChange).
		$(document).on("click", ".reset_link", function (e) {
			var Filter = $(this).attr('data-filter');
			var currentURL = window.location.href;
			if (Filter == 'all') {
				ClearAllListingFilters();
				return;
			} else if (Filter == 'price') {
				var MinPrice = parseFloat($("#minprice").val());
				var MaxPrice = parseFloat($("#maxprice").val());
				$('#slider-range').slider("option", "min", MinPrice);
				$('#slider-range').slider("option", "max", MaxPrice);
				$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
				$("#reset-price").hide();
				if (currentURL.indexOf('p4u/key-') > 0) {
					
					$("#chgminprice").val(parseFloat($("#minprice").val()));
					$("#chgmaxprice").val(parseFloat($("#maxprice").val()));
					$("#amount").val("$" + parseFloat($("#minprice").val()) + " - $" + parseFloat($("#maxprice").val()));
					
					var MinPrice = MaxPrice = '';
					$("#pricechange").val('0');
					$('#reset-price').hide();
				}
				GetProducts(true);
			} else if (Filter == 'categories') {
				$("#categories li a").removeClass('catactive');
				$('#reset-categories').hide();
				GetProducts(true);
			} else {
				$("#" + Filter).find('li a').removeClass('active');
				$('#reset-'+Filter).hide();
				GetProducts(true);
			}
			// GetProducts → SetFilterLink refreshes shareable URL; don't force-clear hash on search PLP.
			if (currentURL.indexOf('p4u/key-') <= 0) {
				window.location.hash = '';
			}
		})
	})
})(jQuery);

function mobilecheck() 
{
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
}

$("#btnfilter").click(function(){
	GetProducts();
	$(".filter-close").click();
});
function ResetFilters()
{
	var CurrFilters = JSON.parse(PageFilters);
	$.each(CurrFilters,function(key,valarray){
		if(key != 'minprice' && key != 'maxprice' && key != 'ominprice' && key != 'omaxprice' && key != 'ochangeprice' && key != 'sortby')
		{
			if(key == 'categories')
			{
				$("#"+key+" li a").each(function (index, ele) {
					var DataID = $(ele).attr('data-id');
					$(ele).removeClass('catactive');
					if($.inArray(DataID, valarray) >= 0)
					{
						$(ele).addClass('catactive');
					}
				});
			} else {
				$("#"+key+" li a").each(function (index, ele) {
					var DataID = $(ele).attr('data-id');
					$(ele).removeClass('active');
					if($.inArray(DataID, valarray) >= 0)
					{
						$(ele).addClass('active');
					}
				});
			}
		}
	})
}
function SetFilterLink() {
	var hashLink = '';
	var isSearchPlp = window.location.href.indexOf('p4u/key-') > 0;

	if (isSearchPlp) {
		// Search facet UL ids (Meili/ss_field): brand, category, seasons, type, …
		hashLink += GetLink('category');
		hashLink += GetLink('brand');
		hashLink += GetLink('fragrance_family');
		hashLink += GetLink('personality');
		hashLink += GetLink('seasons');
		hashLink += GetLink('occasion');
		hashLink += GetLink('type');
		hashLink += GetLink('formulation');
		hashLink += GetLink('size');
		hashLink += GetLink('by_feature');
		hashLink += GetLink('badges');
		hashLink += GetLink('coverage');
		hashLink += GetLink('finish');
		hashLink += GetLink('skin_type');
		hashLink += GetLink('price');
		hashLink += GetLink('rating_avg');
		hashLink += GetLink('OnTopRated');
		hashLink += GetLink('InStockItems');
		hashLink += GetLink('OnSaleDeal');
		hashLink += GetLink('top_note');
		hashLink += GetLink('middle_note');
		hashLink += GetLink('base_note');
		hashLink += GetLink('fragrance_notes');

		// Query string only (no hash) so each filter appears once and paste restores via PHP.
		var parts = hashLink.replace(/^&+/, '').split('&').filter(Boolean);
		var seen = {};
		var qsParts = [];
		parts.forEach(function (part) {
			var i = part.indexOf('-');
			if (i <= 0) {
				return;
			}
			var key = part.substring(0, i);
			var val = part.substring(i + 1);
			if (!key || seen[key]) {
				return;
			}
			seen[key] = true;
			qsParts.push(encodeURIComponent(key) + '=' + encodeURIComponent(val));
		});
		if (window.history && window.history.replaceState) {
			var next = window.location.pathname + (qsParts.length ? ('?' + qsParts.join('&')) : '');
			window.history.replaceState(null, '', next);
		}
		return false;
	}

	if ($('#selcat').length > 0 && $('#selcat').attr('data-cat') != '') {
		hashLink += 'cid-' + $('#selcat').attr('data-cat').trim();
	}
	hashLink += GetLink('categories');
	hashLink += GetLink('brands');
	hashLink += GetLink('fragrance_family');
	hashLink += GetLink('fragrance_personality');
	hashLink += GetLink('fragrance_seasons');
	hashLink += GetLink('fragrance_occasion');
	hashLink += GetLink('vtype');
	hashLink += GetLink('formulation');
	hashLink += GetLink('size');
	hashLink += GetLink('features');
	hashLink += GetLink('stock');
	hashLink += GetLink('coverage');
	hashLink += GetLink('special');
	hashLink += GetLink('finish');
	hashLink += GetLink('skin_type');
	hashLink += GetLink('price');
	hashLink += GetLink('rating_avg');
	hashLink += GetLink('OnTopRated');
	hashLink += GetLink('InStockItems');
	hashLink += GetLink('OnSaleDeal');
	hashLink += GetLink('top_note');
	hashLink += GetLink('middle_note');
	hashLink += GetLink('base_note');
	hashLink += GetLink('fragrance_notes');

	if (hashLink != '') {
		hashLink = '#' + hashLink;
		window.location.hash = hashLink;
	} else if (window.location.hash) {
		window.location.hash = '';
	}
	return false;
}

function GetLink(FilterID) {
	var hashLink = '';
	var isSearchPlp = window.location.href.indexOf('p4u/key-') > 0;

	// Toolbar toggles — read checkboxes, not facet UL links.
	if (FilterID === 'OnTopRated' || FilterID === 'InStockItems' || FilterID === 'OnSaleDeal') {
		var $chk = FilterID === 'OnTopRated' ? $('#chktoprated')
			: (FilterID === 'InStockItems' ? $('#chkinstock') : $('#chkSaleDeal'));
		if ($chk.length && $chk.is(':checked')) {
			hashLink += '&' + FilterID + '-yes';
		}
		return hashLink;
	}

	if (FilterID == 'categories' || FilterID == 'category') {
		var CatLink = '';
		var $catUl = $('#' + FilterID);
		if (!$catUl.length && FilterID === 'category') {
			$catUl = $('#categories');
		}
		$catUl.find('li a').each(function (index, ele) {
			if ($(ele).hasClass('catactive') || $(ele).hasClass('active')) {
				CatLink += $(ele).attr('data-id') + ',';
			}
		});
		if (CatLink != '') {
			hashLink += '&' + (isSearchPlp ? 'category' : 'cid') + '-' + (CatLink.substr(0, CatLink.length - 1));
		}
		return hashLink;
	}

	if (FilterID == 'special' || FilterID == 'badges') {
		var SpecialLink = '';
		var $spec = $('#' + FilterID);
		if (!$spec.length && FilterID === 'badges') {
			$spec = $('#special');
		}
		$spec.find('li a').each(function (index, ele) {
			if (!$(ele).hasClass('active')) {
				return;
			}
			var id = $(ele).attr('data-id');
			if (id == 'top_seller') SpecialLink += 'ts,';
			if (id == 'new_arrival') SpecialLink += 'na,';
			if (id == 'featured') SpecialLink += 'fe,';
			if (id == 'clearance') SpecialLink += 'cl,';
			if (id == 'celebrity') SpecialLink += 'cp,';
			if (id == 'sale' || id == 'sale_price') SpecialLink += 'sl,';
			// Search PLP may use full badge labels as data-id.
			if (isSearchPlp && id && SpecialLink.indexOf(id) < 0 && ['ts','na','fe','cl','cp','sl'].indexOf(id) < 0) {
				SpecialLink += id + ',';
			}
		});
		if (SpecialLink != '') {
			hashLink += '&' + (isSearchPlp ? 'badges' : 'special') + '-' + (SpecialLink.substr(0, SpecialLink.length - 1));
		}
		return hashLink;
	}

	if (FilterID == 'price') {
		if ($("#pricechange").val() == '1') {
			var MinPrice = $("#slider-range").slider("values", 0);
			var MaxPrice = $("#slider-range").slider("values", 1);
			hashLink += '&price-' + MinPrice + '-' + MaxPrice;
		}
		return hashLink;
	}

	var Filter = GetFilterByID(FilterID);
	var FilterKey = FilterID;
	if (FilterID == 'brands' || FilterID == 'brand') {
		FilterKey = isSearchPlp ? 'brand' : 'mid';
	}
	if (FilterID == 'vtype' || FilterID == 'type') {
		FilterKey = 'type';
	}
	if (FilterID == 'fragrance_family') {
		FilterKey = 'family';
	}
	if (FilterID == 'fragrance_personality' || FilterID == 'personality') {
		FilterKey = 'personality';
	}
	if (FilterID == 'fragrance_seasons' || FilterID == 'seasons') {
		FilterKey = 'seasons';
	}
	if (FilterID == 'fragrance_occasion' || FilterID == 'occasion') {
		FilterKey = 'occasion';
	}
	if (FilterID == 'features' || FilterID == 'by_feature') {
		FilterKey = isSearchPlp ? 'by_feature' : 'features';
	}
	if (FilterID == 'rating_avg') FilterKey = 'rating_avg';
	if (FilterID == 'top_note') FilterKey = 'top_note';
	if (FilterID == 'middle_note') FilterKey = 'middle_note';
	if (FilterID == 'base_note') FilterKey = 'base_note';
	if (FilterID == 'fragrance_notes') FilterKey = 'fragrance_notes';

	if (Filter.length > 0 && FilterID != 'stock') {
		hashLink += '&' + FilterKey + '-' + Filter.join(',');
	}
	if (Filter != '' && FilterID == 'stock') {
		hashLink += '&' + FilterKey + '-' + Filter;
	}
	return hashLink;
}

function GetFilters() {
	var currentURL = window.location.href;

	// Defaults so JSON.stringify never drops keys (undefined is omitted).
	var SelCategories = [];
	var SelBrands = [];
	var SelFamily = [];
	var SelPersonality = [];
	var SelSeasons = [];
	var SelOccasion = [];
	var SelType = [];
	var SelFormulation = [];
	var SelSize = [];
	var SelFeatures = [];
	var SelStock = [];
	var SelSpecial = [];
	var SelCoverage = [];
	var SelFinish = [];
	var SelSkinType = [];
	var SelRatingAvg = [];
	var SelTopNote = [];
	var SelMiddleNote = [];
	var SelBaseNote = [];
	var SelFragranceNotes = [];
	var SortBy = 'Sort By';
	var SelOnTopRated = false;
	var SelInStockItems = false;
	var SelOnSaleDeal = false;

	if (currentURL.indexOf('p4u/key-') > 0) {
		// Search PLP facet UL ids match Meili/ss_field names (brand, category, top_note, …).
		SelBrands = GetFilterByID('brand');
		SelCategories = GetFilterByID('category');
		SelSize = GetFilterByID('size');
		SelSpecial = GetFilterByID('badges');
		SelFormulation = GetFilterByID('formulation');
		SelFeatures = GetFilterByID('by_feature');
		SelFamily = GetFilterByID('fragrance_family');
		SelType = GetFilterByID('type');
		SelCoverage = GetFilterByID('coverage');
		SelFinish = GetFilterByID('finish');
		SelSkinType = GetFilterByID('skin_type');
		SelSeasons = GetFilterByID('seasons');
		SelOccasion = GetFilterByID('occasion');
		SelPersonality = GetFilterByID('personality');
		SelRatingAvg = GetFilterByID('rating_avg');
		SelTopNote = GetFilterByID('top_note');
		SelMiddleNote = GetFilterByID('middle_note');
		SelBaseNote = GetFilterByID('base_note');
		SelFragranceNotes = GetFilterByID('fragrance_notes');
		
		// selectbox plugin syncs into #sortopt; prefer that when set.
		SortBy = $('#sortopt').val() || $('#selsort').val();
		if (!SortBy || SortBy === 'Sort By') {
			SortBy = '';
		}
		
		SelOnTopRated = $('#chktoprated').is(':checked');
		SelInStockItems = $('#chkinstock').is(':checked');
		SelOnSaleDeal =  $('#chkSaleDeal').is(':checked');
	}
	else {
		SelCategories = GetFilterByID('categories');
		SelBrands = GetFilterByID('brands');
		SelFamily = GetFilterByID('fragrance_family');
		SelPersonality = GetFilterByID('fragrance_personality');
		SelSeasons = GetFilterByID('fragrance_seasons');
		SelOccasion = GetFilterByID('fragrance_occasion');
		SelType = GetFilterByID('vtype');
		SelFormulation = GetFilterByID('formulation');
		SelSize = GetFilterByID('size');
		SelFeatures = GetFilterByID('features');
		SelStock = GetFilterByID('stock');
		SelSpecial = GetFilterByID('special');
		SelCoverage = GetFilterByID('coverage');
		SelFinish = GetFilterByID('finish');
		SelSkinType = GetFilterByID('skin_type');
		SelRatingAvg = GetFilterByID('rating_avg');
		SelTopNote = GetFilterByID('top_note');
		SelMiddleNote = GetFilterByID('middle_note');
		SelBaseNote = GetFilterByID('base_note');
		SelFragranceNotes = GetFilterByID('fragrance_notes');
		
		SortBy = $("#sortopt").val() || $('#selsort').val();
		if (!SortBy || SortBy === 'Sort By') {
			SortBy = '';
		}
		
		SelOnTopRated = $('#chktoprated').is(':checked');
		SelInStockItems = $('#chkinstock').is(':checked');
		SelOnSaleDeal =  $('#chkSaleDeal').is(':checked');
	}

	var MinPrice = MaxPrice = OMinPrice = OMaxPrice = OChangePrice = '';
	
	if ($("#pricechange").val() == '1') {
		MinPrice = $("#chgminprice").val();
		MaxPrice = $("#chgmaxprice").val();
		OChangePrice = 1;
	}
	
	OMinPrice = $("#minprice").val();
	OMaxPrice = $("#maxprice").val();

	var Filters = new Array();
	
	Filters = {
		'categories': SelCategories,
		'brands': SelBrands,
		'fragrance_family': SelFamily,
		'fragrance_personality': SelPersonality,
		'fragrance_occasion': SelOccasion,
		'fragrance_seasons': SelSeasons,
		'vtype': SelType,
		'formulation': SelFormulation,
		'size': SelSize,
		'features' : SelFeatures,
		'stock': SelStock,
		'special': SelSpecial,
		'coverage': SelCoverage,
		'finish': SelFinish,
		'skin_type': SelSkinType,
		'rating_avg': SelRatingAvg,
		'top_note': SelTopNote,
		'middle_note': SelMiddleNote,
		'base_note': SelBaseNote,
		'fragrance_notes': SelFragranceNotes,
		'OnTopRated': SelOnTopRated,
		'InStockItems': SelInStockItems,
		'OnSaleDeal': SelOnSaleDeal,
		'minprice': MinPrice,
		'maxprice': MaxPrice,
		'ominprice': OMinPrice,
		'omaxprice': OMaxPrice,
		'ochangeprice': OChangePrice,
		'sortby': SortBy
	};
	return JSON.stringify(Filters);
}

function GetFilterByID(FilterID) {
	var FilterData = new Array();
	var currentURL = window.location.href;
	
	
	if (currentURL.indexOf('p4u/key-') > 0) {
		$("#" + FilterID + " li a").each(function (index, ele) {
			if (setFilterData.indexOf($(ele).attr('data-id')) < 0 && $(ele).hasClass('active')) {
				setFilterData.unshift($(ele).attr('data-id'));
			}
			
			if (setFilterData.indexOf($(ele).attr('data-id')) >= 0 && !$(ele).hasClass('active')) {
				for( var i = 0; i < setFilterData.length; i++){ 
					if ( setFilterData[i] === $(ele).attr('data-id')) { 
						setFilterData.splice(i, 1); 
						i--; 
					}
				}
			}
		});
		
		setTimeout(function(){
			
			$("#" + FilterID + " li a").each(function (index, ele) {
				for( var i = 0; i < setFilterData.length; i++){ 
					
					if (setFilterData.indexOf(setFilterData[i]) > -1) {
						
						//var getID = $(this).attr('id');
						
						//console.log(setFilterData[i]);
						//if(getID.includes(setFilterData[i]) == 'true' && (!$('#'+getID).hasClass('active') || !$('#'+getID).length)){
						if(!$("#" + FilterID + " li a").hasClass('active') || !$("#" + FilterID + " li a").length){
							const indexOfEle = setFilterData.indexOf(setFilterData[i]);
							if (indexOfEle > -1) {
								setFilterData.splice(indexOfEle, 1);
								i--;
							}
						}
					}
				}
			});
		},300);
	}
	
	if (FilterID == 'categories') {
		$("#" + FilterID + " li a").each(function (index, ele) {
			if ($(ele).hasClass('catactive')) {
				FilterData.push($(ele).attr('data-id'));
			}
		});
	} else if (FilterID == 'stock') {
		if ($("#stock li a").hasClass('active'))
			return $("#stock li a").attr('data-id');
	} else {
		$("#" + FilterID + " li a").each(function (index, ele) {
			if ($(ele).hasClass('active')) {
				if (currentURL.indexOf('p4u/key-') > 0) {
					FilterData.unshift($(ele).attr('data-id'));
				}else{
					FilterData.push($(ele).attr('data-id'));
				}
			}
		});
	}
	return FilterData;
}
function GetProducts(autoload=false) {
	var Filters = GetFilters();
    //console.log(Filters);
	var token = $('meta[name="csrf-token"]').attr('content');
	var category_id = $("#category_id").val();
	$("#page-spinner").show();

	var currentURL = window.location.href;
	
	if (currentURL.indexOf('p4u/key-') > 0) {
		var arr = currentURL.split('p4u/key-');
		var getKeyword = arr[1].split('/');
		var keyword = getKeyword[0];
		try { keyword = decodeURIComponent(keyword); } catch (e) {}
	} else {
		var keyword = '';
	}
	$(".listing_grid").attr('data-load','1');
	$.ajax({
		type: 'POST',
		url: site_url + 'get_products',
		headers: {
			'X-CSRF-TOKEN': token
		},
		data: {
			category_id: category_id,
			filters: Filters,
			keyword: keyword,
			page: parseInt($('.list-more').attr('data-page')),
			setArrBreadCrumb: setFilterData,
			currFilter : $(".filter").attr('data-current'),
			fromClearAll: window._listingClearAll ? '1' : '0'
		},
		success: function (data) {
			window._listingClearAll = false;
			var TotalProds = data.TotalProducts;
			$(".list-more").attr('data-total',TotalProds);
			var PageSize = parseInt($(".list-more").attr('data-page')) * 24;
			//console.log(PageSize);
			//$("#total").html('('+TotalProds+')');
			
			if (currentURL.indexOf('p4u/key-') > 0) {
				//$('.filter_mid').html('');
				//$('.filter_mid').html(data.Filters);	
				initFilterScrollbars($('.filter_mid'));
			}
			$('#total').html('');
			$('#total').html('('+data.TotalProducts+')');
			$("#noprod").hide();

			// Search analytics — track when filters refresh page 1 results
			try {
				if (
					window.MaxSearchAnalytics &&
					parseInt($('.list-more').attr('data-page'), 10) <= 1 &&
					typeof window.MaxSearchAnalytics.trackListingSearch === 'function'
				) {
					var k = (typeof keyword !== 'undefined' && keyword) ? keyword : ($('#keyword').val() || '');
					if (!k && typeof org_keyword !== 'undefined') k = org_keyword;
					if (k) {
						window.MaxSearchAnalytics.trackListingSearch(k, data.TotalProducts || 0, false);
					}
				}
			} catch (e) {}

			if (TotalProds > 0) {
				if (PageSize > TotalProds) {
					$("#list-more").hide();
				} else {
					$("#list-more").show();
				}
			} else {
                $("#list-more").hide();
				$("#noprod").show();
			}

			var page = parseInt($('.list-more').attr('data-page'));
			if (page > 1) {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
					collapseFilterAccordions();
					initFilterScrollbars($('.filter_mid'));
				}
				$('.listing_grid').hide().append(data.ProductHTML).fadeIn(1000);
				if (window.yotpo && typeof window.yotpo.initWidgets === 'function') {
					window.yotpo.initWidgets();
				}
			} else {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
					collapseFilterAccordions();
					initFilterScrollbars($('.filter_mid'));
				}
				$('.listing_grid').hide().html(data.ProductHTML).fadeIn(1000);
				if (window.yotpo && typeof window.yotpo.initWidgets === 'function') {
					window.yotpo.initWidgets();
				}
			}
			
			if (currentURL.indexOf('p4u/key-') > 0) {
				$(".filter_checkbox li a").each(function (index, ele) {
					var filterParent = $(this).parent().parent();
					var filID = $(filterParent).attr('id');
					
					if($('#'+filID+' li a.active').length > 0){
						$('#reset-'+filID).show();
					}
					else{
						$('#reset-'+filID).hide();
					}
				});
				
				if ($("#pricechange").val() == '1') {
					$('#reset-price').show();
				}else{
					$('#reset-price').hide();
				}
			}
			
			if(data.BredcrumHTML)
			{
				$('.breadcrumb .container').html(data.BredcrumHTML.BredLink);
			}
			$("#page-spinner").hide();

			// Always sync shareable URL to current UI (including chip/breadcrumb remove + Clear/Reset).
			// Load-more is fine too — same filters rewrite the same query.
			SetFilterLink();
			RenderAppliedFilters();
		},
		complete:function(){
            window._listingClearAll = false;
            /*
			if(!autoload){
				$('html, body').animate({
					scrollTop: $(".listing_right").offset().top - 150
				}, 2000);
			}
            */
			$(".listing_grid").attr('data-load','0');
		},
	});
}

function openNav() {
	document.getElementById("myFilter").style.width = "100%";
}

function closeNav() {
	document.getElementById("myFilter").style.width = "0%";
} ! function (i) {
	var o, c;
	$(document).on("click", ".filter_acrd_hd", function () {
		o = i(this).parents(".filter_acrd");
		c = o.find(".filter_acrd_con");
		if (o.hasClass("filter_acrd_act")) {
			o.removeClass("filter_acrd_act");
			c.slideUp();
		} else {
			o.addClass("filter_acrd_act");
			c.stop(true, true).slideDown(function () {
				// Height is known only after open — refresh scrollbar then.
				refreshFilterScrollbar(c);
			});
		}
	});
}(jQuery);
$(window).bind('resize orientationchange', function () {
	w = document.body.clientWidth;
	if (w < 1025) {
		$(".filter_acrd").removeClass("filter_acrd_act");
		$(".filter_acrd_con").hide();
		$(".filter").hide();
		$(document).on('click', '.sortmenu', function () {
			o = $(".sortmenu");
			c = $(".filter");
			o.hasClass("sortactive") ? (o.removeClass("sortactive"), c.slideUp()) : (o.addClass("sortactive"), c.stop(!0, !0).slideDown())
		})
	} else {
		// Desktop: keep filter sidebar visible, but do NOT force-open every accordion
		// (that was making all Meilisearch search filters appear open by default).
		$(".filter").show();
	}
});

$(function () {
	var MinPrice = parseFloat($("#minprice").val());
	var MaxPrice = parseFloat($("#maxprice").val());
	
	var currentURL = window.location.href;
	
	$("#slider-range").slider({
		range: true,
		min: MinPrice,
		max: MaxPrice,
		step: 0.1,
		values: [MinPrice, MaxPrice],
		slide: function(event, ui) {
			$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			$("#chgminprice").val(ui.values[0]);
			$("#chgmaxprice").val(ui.values[1]);
			$('.list-more').attr('data-page', 1);
			$('#reset-price').show();
			$("#pricechange").val('1');
		},
		change: function (event, ui) {
			$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			$("#pricechange").val('1');
			$("#chgminprice").val(ui.values[0]);
			$("#chgmaxprice").val(ui.values[1]);
			$('.list-more').attr('data-page', 1);
		},
		stop:function(event,ui){
			if (currentURL.indexOf('p4u/key-') > 0) {
				//$('#slider-range').trigger('change');
				$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
				$("#pricechange").val('1');
				$("#chgminprice").val(ui.values[0]);
				$("#chgmaxprice").val(ui.values[1]);
				$('.list-more').attr('data-page', 1);
			}
			if(!mobilecheck())
			{
				GetProducts();
			}
		}
	});
	$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
});
$(document).ready(function () {
	$(".fra_toggle").click(function () {
		$(".fra_link").slideToggle();
	});
	$(window).resize(function () {
		if ($(this).width() < 1025) {
			$(".fra_link").hide();
		} else {
			$(".fra_link").show();
		}
	});
	$(document).on('click', '#applied-filters-clear', function (e) {
		e.preventDefault();
		ClearAllListingFilters();
	});
	$(document).on('click', '.af-remove', function (e) {
		e.preventDefault();
		var $btn = $(this);
		var filter = $btn.attr('data-filter') || '';
		var id = $btn.attr('data-id') || '';
		var type = $btn.attr('data-type') || '';
		if (type === 'toggle' && filter) {
			$('#' + filter).prop('checked', false);
		} else if (type === 'price') {
			var MinPrice = parseFloat($('#minprice').val());
			var MaxPrice = parseFloat($('#maxprice').val());
			if (!isNaN(MinPrice) && !isNaN(MaxPrice) && $('#slider-range').length) {
				try {
					$('#slider-range').slider('option', 'values', [MinPrice, MaxPrice]);
				} catch (err) {}
			}
			$('#chgminprice').val(MinPrice);
			$('#chgmaxprice').val(MaxPrice);
			$('#amount').val('$' + MinPrice + ' - $' + MaxPrice);
			$('#pricechange').val('0');
			$('#reset-price').hide();
		} else if (type === 'category' && id) {
			$('#categories li a[data-id="' + id + '"], #category li a[data-id="' + id + '"]').removeClass('catactive active');
		} else if (filter && id) {
			$('#' + filter + ' li a[data-id="' + id + '"]').removeClass('active').attr('aria-pressed', 'false');
		}
		$('.list-more').attr('data-page', '1');
		GetProducts(true);
		// Ensure URL drops the removed filter even if AJAX is slow/skipped.
		SetFilterLink();
	});
	RenderAppliedFilters();
});

function applyFilters() {
	var chkid = localStorage.getItem("chkId");
	
	if(chkid  !== '' && chkid !== null){
		$("#"+chkid).click();
		localStorage.removeItem("chkId");
	}
	// Old shared links may have both ?… and #… — keep query only on search PLP.
	if (window.location.href.indexOf('p4u/key-') > 0
		&& (window.location.search || '') !== ''
		&& (window.location.hash || '') !== '') {
		if (window.history && window.history.replaceState) {
			window.history.replaceState(null, '', window.location.pathname + window.location.search);
		}
	}
	RenderAppliedFilters();
}

function escapeAppliedFilterText(str) {
	return String(str == null ? '' : str)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#39;');
}

function getAppliedFilterOptionLabel($a) {
	if ($a.find('.filter-star-rating').length) {
		var starId = $.trim($a.attr('data-id') || '');
		if (!starId) {
			return $.trim($a.text());
		}
		var n = parseInt(starId, 10);
		if (n === 5) {
			return '5';
		}
		if (n === 4 || n === 3) {
			return n + '+';
		}
		return starId;
	}
	var $span = $a.children('span').not('.filter-star-rating').first();
	if ($span.length) {
		return $.trim($span.text());
	}
	return $.trim($a.text());
}

/**
 * Clear every applied facet/toggle/price on listing/search PLP, then refresh via AJAX.
 * Clears shareable URL defaults (e.g. ?OnTopRated=yes&OnSaleDeal=yes) as well as
 * filters the shopper added after load.
 */
function ClearAllListingFilters() {
	setFilterData.splice(0, setFilterData.length);

	$('.filter_checkbox li a.active').removeClass('active').attr('aria-pressed', 'false');
	$('#categories li a.catactive, #category li a.catactive, #category li a.active').removeClass('catactive active');

	// Fully clear toolbar toggles — do not restore first-load / URL baseline.
	$('#chktoprated, #chkinstock, #chkSaleDeal').each(function () {
		$(this).prop('checked', false).attr('data-baseline-checked', '0');
	});
	$('.filter_acrd_hd .reset_link').hide();

	// Sort: reset to "Sort By" (not first-load NLP/URL sort) so Clear all is a full clear.
	$('#sortopt').val('').attr('data-baseline-sort', '');
	if ($('#selsort').length) {
		$('#selsort').val('');
		try {
			var $sb = $('#selsort').next('.sbHolder');
			if ($sb.length) {
				$sb.find('.sbSelector').text($('#selsort option:first').text() || 'Sort By');
			}
		} catch (err) {}
	}

	if ($('#pricechange').length) {
		var MinPrice = parseFloat($('#minprice').val());
		var MaxPrice = parseFloat($('#maxprice').val());
		if (!isNaN(MinPrice) && !isNaN(MaxPrice) && $('#slider-range').length && typeof $('#slider-range').slider === 'function') {
			try {
				$('#slider-range').slider('option', 'min', MinPrice);
				$('#slider-range').slider('option', 'max', MaxPrice);
				$('#slider-range').slider('option', 'values', [MinPrice, MaxPrice]);
			} catch (err) {}
		}
		$('#chgminprice').val(MinPrice);
		$('#chgmaxprice').val(MaxPrice);
		$('#amount').val('$' + MinPrice + ' - $' + MaxPrice);
		$('#pricechange').val('0');
		$('#reset-price').hide();
	}

	var SelCat = $('#selcat').attr('data-cat');
	if (SelCat) {
		$('#cat-' + SelCat).addClass('catactive');
	}

	$('.list-more').attr('data-page', '1');
	$('.listing_grid').attr('data-load', '0');

	if (window.location.href.indexOf('p4u/key-') > 0) {
		// Drop shareable ?filters from the URL without leaving the page.
		if (window.history && window.history.replaceState) {
			window.history.replaceState(null, '', window.location.pathname);
		}
	} else {
		window.location.hash = '';
	}

	window._listingClearAll = true;
	RenderAppliedFilters();
	GetProducts(true);
}

/** Show active filters as removable chips above the product grid. */
function RenderAppliedFilters() {
	var $wrap = $('#applied-filters');
	var $list = $('#applied-filters-list');
	if (!$wrap.length || !$list.length) {
		return;
	}

	var chips = [];

	$('.filter_checkbox').each(function () {
		var $ul = $(this);
		var filID = $ul.attr('id') || '';
		if (!filID) {
			return;
		}
		// NLP may select many note tokens in the sidebar; keep them there but do not
		// flood the applied-filter breadcrumb chips with every note.
		if (filID === 'fragrance_notes') {
			return;
		}
		var title = $.trim($ul.closest('.filter_acrd').find('.filter_acrd_hd .title').first().text()) || filID;
		$ul.find('li a.active').each(function () {
			var $a = $(this);
			var value = getAppliedFilterOptionLabel($a);
			var dataId = $a.attr('data-id') || '';
			if (!value) {
				return;
			}
			chips.push({
				label: title + ': ' + value,
				filter: filID,
				id: dataId,
				type: 'facet'
			});
		});
	});

	$('#categories li a.catactive').each(function () {
		var $a = $(this);
		var value = $.trim($a.text()).replace(/^[\-\s]+/, '');
		chips.push({
			label: 'Category: ' + value,
			filter: 'categories',
			id: $a.attr('data-id') || '',
			type: 'category'
		});
	});

	if ($('#pricechange').val() == '1') {
		var minP = $('#chgminprice').val() || $('#minprice').val();
		var maxP = $('#chgmaxprice').val() || $('#maxprice').val();
		chips.push({
			label: 'Price: $' + minP + ' - $' + maxP,
			filter: 'price',
			id: '',
			type: 'price'
		});
	}

	if ($('#chktoprated').is(':checked')) {
		chips.push({ label: 'Top Rated', filter: 'chktoprated', id: '', type: 'toggle' });
	}
	if ($('#chkinstock').is(':checked')) {
		chips.push({ label: 'In Stock', filter: 'chkinstock', id: '', type: 'toggle' });
	}
	if ($('#chkSaleDeal').is(':checked')) {
		chips.push({ label: 'On Sale/Deal', filter: 'chkSaleDeal', id: '', type: 'toggle' });
	}

	if (!chips.length) {
		$list.empty();
		$wrap.hide();
		return;
	}

	var html = '';
	chips.forEach(function (chip) {
		html += '<li class="af-tag">'
			+ '<span class="af-label">' + escapeAppliedFilterText(chip.label) + '</span>'
			+ '<button type="button" class="af-remove" aria-label="Remove filter"'
			+ ' data-filter="' + escapeAppliedFilterText(chip.filter) + '"'
			+ ' data-id="' + escapeAppliedFilterText(chip.id) + '"'
			+ ' data-type="' + escapeAppliedFilterText(chip.type) + '">&times;</button>'
			+ '</li>';
	});
	$list.html(html);
	$wrap.show();
}
