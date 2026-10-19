var setFilterData = new Array();

(function ($) {
	$(window).on('load', function () {
		$(".scroll").mCustomScrollbar({});
	});
	$(".filter_btn").click(function () {
		$("body").toggleClass('open-filter');
	});
	$(".filter-close").click(function () {
		$("body").removeClass('open-filter');
	});

})(jQuery);


(function ($) {
	$(document).ready(function () {
		//$('.reset_link').hide();
		setTimeout(function(){applyFilters();},300);
		if($('#categories li a.catactive').length > 0)
			$('#reset-categories').show();
		else
			$('#reset-categories').hide();
		//GetProducts();
		$(document).on("click", ".filter_checkbox li a", function () {
			$(".filter").attr('data-current',$(this).text());
			$('.list-more').attr('data-page', '1');
			if ($(this).hasClass('active'))
				$(this).removeClass('active');
			else
				$(this).addClass('active');
			
			var filterParent = $(this).parent().parent();
			var filID = $(filterParent).attr('id');
			
			if($('#'+filID+' li a.active').length > 0)
				$('#reset-'+filID).show();
			else
				$('#reset-'+filID).hide();
			
			GetProducts();
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
		});
		$(document).on("click", ".list-more", function () {
		//$(".list-more").click(function () {
			var Page = parseInt($(this).attr('data-page'));
			Page = Page + 1;
			$(this).attr('data-page', Page);
			GetProducts();
		});
		$(document).on("change", "#selsort", function () {
			var Page = parseInt($('.list-more').attr('data-page'));
			Page = Page + 1;
			$('.list-more').attr('data-page', Page);
			GetProducts();
		});
		$(document).on("click", ".reset_link", function (e) {
			var Filter = $(this).attr('data-filter');
			var currentURL = window.location.href;
			if (Filter == 'all') {
				setFilterData.splice(0, setFilterData.length);
				$("#categories li a").removeClass('catactive');
				var SelCat = $("#selcat").attr('data-cat');
				$("#cat-" + SelCat).addClass('catactive');
				$(".filter_checkbox li a").removeClass('active');
				var SelBrand = $("#imanufacture_id").val();
				$("#brands li a[data-id='" + SelBrand + "']").addClass('active');
				$("#pricechange").val('0');
				var MinPrice = parseFloat($("#minprice").val());
				var MaxPrice = parseFloat($("#maxprice").val());
				$('#slider-range').slider("option", "min", MinPrice);
				$('#slider-range').slider("option", "max", MaxPrice);
				$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
				
				if (currentURL.indexOf('p4u/key-') > 0) {
					var MinPrice = MaxPrice = '';
					$("#pricechange").val('0');
				}
				$('.filter_acrd_hd .reset_link').hide();
				GetProducts();
			} else if (Filter == 'price') {
				var MinPrice = parseFloat($("#minprice").val());
				var MaxPrice = parseFloat($("#maxprice").val());
				$('#slider-range').slider("option", "min", MinPrice);
				$('#slider-range').slider("option", "max", MaxPrice);
				$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
				$("#reset-price").hide();
				$("#pricechange").val('0');
				if (currentURL.indexOf('p4u/key-') > 0) {
					
					$("#chgminprice").val(parseFloat($("#minprice").val()));
					$("#chgmaxprice").val(parseFloat($("#maxprice").val()));
					$("#amount").val("$" + parseFloat($("#minprice").val()) + " - $" + parseFloat($("#maxprice").val()));
					
					var MinPrice = MaxPrice = '';
					$("#pricechange").val('0');
					$('#reset-price').hide();
				}
				GetProducts();
			} else if (Filter == 'categories') {
				$("#categories li a").removeClass('catactive');
				$('#reset-categories').hide();
				GetProducts();
			} else {
				$("#" + Filter).find('li a').removeClass('active');
				$('#reset-'+Filter).hide();
				GetProducts();
			}
			window.location.hash = '';
		})
	})
})(jQuery);

function SetFilterLink() {
	var hashLink = '';
	var CatLink = '';
	if ($('#selcat').length > 0 && $('#selcat').attr('data-cat') != '')
		hashLink += 'cid-' + $('#selcat').attr('data-cat').trim();
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
	hashLink = '#' + hashLink;
	window.location.hash = hashLink;
	return false;
}

function GetLink(FilterID) {
	var hashLink = '';
	var hashID = '';
	if (FilterID == 'categories') {
		var CatLink = '';
		$("#categories li a").each(function (index, ele) {
			if ($(ele).hasClass('catactive'))
				CatLink += $(ele).attr('data-id') + ',';
		});
		if (CatLink != '')
			hashLink += '&cid-' + (CatLink.substr(0, CatLink.length - 1));
	} else if (FilterID == 'special') {
		var SpecialLink = '';
		$("#special li a").each(function (index, ele) {
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'top_seller')
				SpecialLink += 'ts,';
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'new_arrival')
				SpecialLink += 'na,';
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'featured')
				SpecialLink += 'fe,';
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'clearance')
				SpecialLink += 'cl,';
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'celebrity')
				SpecialLink += 'cp,';
			if ($(ele).hasClass('active') && $(ele).attr('data-id') == 'sale')
				SpecialLink += 'sl,';
		});
		if (SpecialLink != '')
			hashLink += '&special-' + (SpecialLink.substr(0, SpecialLink.length - 1));
	} else if (FilterID == 'price') {
		var MinPrice = '';
		var MaxPrice = '';
		if ($("#pricechange").val() == '1') {
			MinPrice = $("#slider-range").slider("values", 0);
			MaxPrice = $("#slider-range").slider("values", 1);
			hashLink += '&price-' + MinPrice + '-' + MaxPrice;
		}
	} else {
		var Filter = GetFilterByID(FilterID);
		var FilterKey = FilterID;
		if (FilterID == 'brands')
			FilterKey = 'mid';
		if (FilterID == 'vtype')
			FilterKey = 'type';
		if (FilterID == 'fragrance_family')
			FilterKey = 'family';
		if (FilterID == 'fragrance_personality')
			FilterKey = 'personality';
		if (FilterID == 'fragrance_seasons')
			FilterKey = 'seasons';
		if (FilterID == 'fragrance_occasion')
			FilterKey = 'occasion';
		if(FilterID == 'features')
			FilterKey = 'features';
		if (Filter.length > 0 && FilterID != 'stock')
			hashLink += '&' + FilterKey + '-' + Filter.join(',');
		if (Filter != '' && FilterID == 'stock')
			hashLink += '&' + FilterKey + '-' + Filter;
	}
	return hashLink;
}

function GetFilters() {
	var currentURL = window.location.href;

	if (currentURL.indexOf('p4u/key-') > 0) {
		var SelBrands = GetFilterByID('brand');
		var SelCategories = GetFilterByID('category');
		var SelSize = GetFilterByID('size');
		var SelSpecial = GetFilterByID('badges');
		var SelFormulation = GetFilterByID('formulation');
		var SelFeatures = GetFilterByID('by_feature');
		var SelFamily = GetFilterByID('fragrance_family');
		var SelType = GetFilterByID('type');
		var SelCoverage = GetFilterByID('coverage');
		var SelFinish = GetFilterByID('finish');
		var SelSkinType = GetFilterByID('skin_type');
		var SelSeasons = GetFilterByID('seasons');
		var SelOccasion = GetFilterByID('occasion');
		var SelPersonality = GetFilterByID('personality');
		var SortBy = $('#selsort').val();
	}
	else {
		var SelCategories = GetFilterByID('categories');
		var SelBrands = GetFilterByID('brands');
		var SelFamily = GetFilterByID('fragrance_family');
		var SelPersonality = GetFilterByID('fragrance_personality');
		var SelSeasons = GetFilterByID('fragrance_seasons');
		var SelOccasion = GetFilterByID('fragrance_occasion');
		var SelType = GetFilterByID('vtype');
		var SelFormulation = GetFilterByID('formulation');
		var SelSize = GetFilterByID('size');
		var SelFeatures = GetFilterByID('features');
		var SelStock = GetFilterByID('stock');
		var SelSpecial = GetFilterByID('special');
		var SelCoverage = GetFilterByID('coverage');
		var SelFinish = GetFilterByID('finish');
		var SelSkinType = GetFilterByID('skin_type');
		var SortBy = $("#sortopt").val();
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
			if (setFilterData.indexOf($(ele).attr('data-id')) <= 0 && $(ele).hasClass('active')) {
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
function GetProducts() {
	var Filters = GetFilters();
	var token = $('meta[name="csrf-token"]').attr('content');
	var category_id = $("#category_id").val();
	$("#page-spinner").show();

	var currentURL = window.location.href;
	
	if (currentURL.indexOf('p4u/key-') > 0) {
		var arr = currentURL.split('p4u/key-');
		var getKeyword = arr[1].split('/');
		var keyword = getKeyword[0];
	} else {
		var keyword = '';
	}

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
			currFilter : $(".filter").attr('data-current')
		},
		success: function (data) {
			var TotalProds = data.TotalProducts;
			var PageSize = parseInt($(".list-more").attr('data-page')) * 12;
			//$("#total").html('('+TotalProds+')');
			
			if (currentURL.indexOf('p4u/key-') > 0) {
				//$('.filter_mid').html('');
				//$('.filter_mid').html(data.Filters);
				
				$(".scroll").mCustomScrollbar({});
				
				$('#total').html('');
				$('#total').html('('+data.TotalProducts+')');
			}
			
			$("#noprod").hide();
			if (TotalProds > 0) {
				if (PageSize > TotalProds) {
					$("#loadmore").hide();
				} else {
					$("#loadmore").show();
				}
			} else {
				$("#noprod").show();
			}

			var page = parseInt($('.list-more').attr('data-page'));
			if (page > 1) {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
				}
				$('.listing_grid').hide().append(data.ProductHTML).fadeIn(1000);
			} else {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
				}
				$('.listing_grid').hide().html(data.ProductHTML).fadeIn(1000);
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

			if (currentURL.indexOf('p4u/key-') <= 0) {
				SetFilterLink();
			}
		}
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
	//i(".filter_acrd_hd").on("click", function () {
		o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())
	})
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
		$(".filter").show();
		$(".filter_acrd").addClass("filter_acrd_act");
		$(".filter_acrd_con").show();
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
			GetProducts();
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
});

function applyFilters() {
	var chkid = localStorage.getItem("chkId");
	
	if(chkid  !== '' && chkid !== null){
		$("#"+chkid).click();
		localStorage.removeItem("chkId");
	}
}
