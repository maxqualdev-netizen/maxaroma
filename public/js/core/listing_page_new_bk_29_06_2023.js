var setFilterData = new Array();
var PageFilters = '';
var isFilterOpen = 0; 
var fromClearAll = 'N';
$(document).mouseup(function(e) 
{
    var container = $(".filter");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        //container.hide();
		if($("body").hasClass('open-filter')){
			$("body").removeClass('open-filter')
		}
		
		//$("body").toggleClass('open-filter');
    }
});
(function ($) {	
	$(".filter_btn").click(function () {
		$("body").toggleClass('open-filter');
		isFilterOpen = 1;
        //$(".scroll").mCustomScrollbar('destroy');
        //$(".scroll").mCustomScrollbar({});

		/*$('.scroll').slimscroll({
			animate: false
		  });*/
	});
	$(".filter-close").click(function () {
		$("body").removeClass('open-filter');
		isFilterOpen = 0;
        //$(".scroll").mCustomScrollbar('destroy');
        //$(".scroll").mCustomScrollbar({});
		
		/*$('.scroll').slimscroll({
			animate: false
		  });*/
	});

	if($(window).width() >= 1025){
		$(document).on('click','.filter_btn',function(){			
			//alert(456456);
			//$.noConflict();
			$('.filter').css("overflow","hidden");
			$('#left_filter').slimscroll({
				height: '630px',			
				animate: true
			});
			$('#brands').slimscroll({			
				height: '150px',	
				animate: true
			});
			
		})
	}
    
    $(document).on('click','.filter_acrd',function(){			
        //$(".scroll").mCustomScrollbar('destroy');
        //$(".scroll").mCustomScrollbar({});
		
		/*$('.filter').slimscroll({
			animate: false
		});*/

		/*$('.scroll').slimscroll({
			animate: false
		  });*/

    })

	/*$('.other-scroll').slimscroll({
		animate: true
	  });*/

	var fragrance_type = "";
	var brand_id = "";
	var price_frm_url = "";
	var size_frm_url = "";
	var v_href = window.location.href;
	var ml_mini_frm_url = '';
	var special_frm_url = '';
	var discovery_set_from_url = '';
	var fpocket_from_url = '';
	var v_hrefarr = v_href.split("/");
	//alert(v_href);
	//console.log(v_hrefarr);
	for(var i = 0; i < v_hrefarr.length; i++){
		if(v_hrefarr[i].indexOf("ftype") > -1){			
			fragrance_type = v_hrefarr[i];
		}
		if(v_hrefarr[i].indexOf("mid") > -1){			
			brand_id = v_hrefarr[i];
		}		
		if(v_hrefarr[i].indexOf("price") > -1){			
			price_frm_url = v_hrefarr[i];
		}		
		if(v_hrefarr[i].indexOf("size") > -1){			
			size_frm_url = v_hrefarr[i];
		}		
		if(v_hrefarr[i].indexOf("8-ml-mini") > -1){
			ml_mini_frm_url = v_hrefarr[i];
		}
		if(v_hrefarr[i].indexOf("na") > -1){
			special_frm_url = v_hrefarr[i];
		}
		if(v_hrefarr[i].indexOf("discovery-sets") > -1){
			discovery_set_from_url = v_hrefarr[i];
		}
		if(v_hrefarr[i].indexOf("f-pocket-perfume") > -1){
			fpocket_from_url = v_hrefarr[i];
		}		
	}
	//alert(new_arr_frm_url);
	//console.log(fragrance_type);
	//console.log(discovery_set_from_url);		
	

	if(special_frm_url == 'special-na' || special_frm_url == 'special-ts,na' || special_frm_url == 'special-na,ts'){
		var special_arr = special_frm_url.split("special-");
		if(special_arr.length > 1){
			var spec_arr = special_arr[1].split(",");			
			$("#categories li a").each(function(index){
				if($.inArray($(this).attr("data-id"),spec_arr) !== -1){
					$(this).addClass('cf-active');
					$("#clrAll").show();
				} else {
					$(this).removeClass('cf-active');
					chkClearAll();
				}
			})			
		}		
	}

	if(discovery_set_from_url == 'discovery-sets' || fpocket_from_url == 'f-pocket-perfume'){		
		$("#categories li a").each(function(index){
			if($(this).attr("data-id") == discovery_set_from_url){				
				$(this).addClass('cf-active');
				$("#clrAll").show();
			}
			if($(this).attr("data-id") == fpocket_from_url){				
				$(this).addClass('cf-active');
				$("#clrAll").show();
			}
		})
	}

	if(ml_mini_frm_url == '8-ml-mini'){
		$("#categories li a").each(function(index){
			if($(this).attr("data-id") == ml_mini_frm_url){
				$(this).addClass('cf-active');
				$("#clrAll").show();
			}
		})
	}
	if(size_frm_url != ''){
		var size_arr = size_frm_url.split("size-");
		if(size_arr.length > 1){
			var sizes_arr = size_arr[1].split(",");
			$("#size li a").each(function(index){
				if($.inArray($(this).attr("data-id"),sizes_arr) !== -1){				
					$(this).addClass('active');
				}
			})
			$("#reset-size").show();
			$("#clrAll").show();
		}
	}
	if(price_frm_url != ''){
		var price_arr = price_frm_url.split("price-");
		if(price_arr.length > 1){
			var prices_arr = price_arr[1].split(",");
			$("#price li a").each(function(index){
				if($.inArray($(this).attr("data-id"),prices_arr) !== -1){				
					$(this).addClass('active');				
				}
			})
			$("#reset-price").show();
			$("#clrAll").show();
		}
	}
	if(brand_id != ''){
		var brand_arr = brand_id.split("mid-");
		if(brand_arr.length > 1){
			var brands_arr = brand_arr[1].split(",");			
			$("#brands li a").each(function(index){
				if($.inArray($(this).attr("data-id"),brands_arr) !== -1){
					$(this).addClass('active');				
				}
			})
			$("#reset-brands").show();		
			if($("#category_id").length > 0){
				$("#clrAll").show();
			}	
		}
	}
	if(fragrance_type != ''){
		var ftype_arr = fragrance_type.split("ftype-");		
		if(ftype_arr.length > 1){
			var ftypes_arr = ftype_arr[1].split(",");
			$("#fragrance_type li a").each(function(index){			
				if($.inArray($(this).attr("data-id"),ftypes_arr) !== -1){				
					$(this).addClass('active');				
				}
			})
			$("#reset-fragrance_type").show();
			$("#clrAll").show();
		}
	}
	
    
    $('#filterbrand').keyup(function(){
        var searchText = $(this).val().toLowerCase();
        $('#brands > li').each(function(){
            var currentLiText = $(this).text().toLowerCase(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });     
    });
    
    $(document).on("click", ".list-more", function () {
    //$(".list-more").click(function () {
        var Page = parseInt($(this).attr('data-page'));
        Page = Page + 1;
        $(this).attr('data-page', Page);		
        GetProducts();
    });
    $(document).on("change", "#selsort", function () {
        var Page = 1;
        $('.list-more').attr('data-page', Page);		
        GetProducts();
    });
    
    $(document).on('click','.ft-clear',function(){
        var Filter = $(this).attr('data-filter');
        var ID = $(this).attr('data-id');		
        if(Filter == 'categories')
        {
            $('#'+Filter).find('[data-id = "'+ID+'"]').removeClass('cf-active');
        }else {			
            $('#'+Filter).find('[data-id = "'+ID+'"]').removeClass('active');			
        }
		$(".list-more").attr('data-page','1').removeAttr('data-total');
		//here			
        GetProducts();		
		checkReset(Filter);
    });
    
    $(document).on("click", ".filter_checkbox li a", function () {
        $(".filter").attr('data-current',$(this).text());
        $('.list-more').attr('data-page', '1');
        $('.listing_grid').attr('data-load', '0');
        if ($(this).hasClass('active'))
            $(this).removeClass('active');
        else
            $(this).addClass('active');

        var filterParent = $(this).parent().parent();
        var filID = $(filterParent).attr('id');

        if($('#'+filID+' li a.active').length > 0){
            $('#reset-'+filID).show();
			$("#clrAll").show();
		} else {
            $('#reset-'+filID).hide();
			chkClearAll();
		}	
			
    });
    
    $("#btnfilter").click(function(){		
        GetProducts();
        $(".filter-close").click();
    });
    
    PageFilters = GetFilters();

	
    
    $(document).on("click", "#categories li a", function () {
        $(".filter").attr('data-current',$(this).text());
        $('.list-more').attr('data-page', '1');
        if ($(this).hasClass('cf-active')){
            $(this).removeClass('cf-active');
			chkClearAll();
		} else {
            $(this).addClass('cf-active');
			$("#clrAll").show();
		}	

        if($('#categories li a.cf-active').length > 0){
            $('#reset-categories').show();
			$("#clrAll").show();
		} else {
            $('#reset-categories').hide();
			chkClearAll();
		}	
        //GetProducts();
        if(mobilecheck())
        {	
            //$(".filter-close").click();
			/*if ($(this).hasClass('cf-active')){
				alert(112233);
			}*/
        }
    });
    
    
    	$(document).on("click", ".reset_link", function (e) {
			var Filter = $(this).attr('data-filter');
			var currentURL = window.location.href;
			if (Filter == 'all') {	
				//alert(1111);
				ResetFilters();
				setFilterData.splice(0, setFilterData.length);
				//$("#categories li a").removeClass('cf-active');
				var SelCat = $("#selcat").attr('data-cat');
				$("#cat-" + SelCat).addClass('cf-active');
				//$(".filter_checkbox li a").removeClass('active');
				var SelBrand = $("#imanufacture_id").val();
				//$("#brands li a[data-id='" + SelBrand + "']").addClass('active');
				if($("#pricechange").val() == '1')
				{
					var MinPrice = parseFloat($("#minprice").val());
					var MaxPrice = parseFloat($("#maxprice").val());
					$('#slider-range').slider("option", "min", MinPrice);
					$('#slider-range').slider("option", "max", MaxPrice);
					$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
					
					if (currentURL.indexOf('p4u/key-') > 0) {
						var MinPrice = MaxPrice = '';
						$("#pricechange").val('0');
					}
					$("#pricechange").val('0');
				}
				$('.filter_acrd_hd .reset_link').hide();
				if($(".ft-list").text()!=""){	
					fromClearAll = 'Y';					
					GetProducts(true);
					$(".filter-close").click();
				}
			} else if (Filter == 'price') {						
				ResetFilters();			
				var MinPrice = parseFloat($("#minprice").val());
				var MaxPrice = parseFloat($("#maxprice").val());
				$('#slider-range').slider("option", "min", MinPrice);
				$('#slider-range').slider("option", "max", MaxPrice);
				$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
				$("#reset-price").hide();				
				chkClearAll();
				if (currentURL.indexOf('p4u/key-') > 0) {					
					$("#chgminprice").val(parseFloat($("#minprice").val()));
					$("#chgmaxprice").val(parseFloat($("#maxprice").val()));
					$("#amount").val("$" + parseFloat($("#minprice").val()) + " - $" + parseFloat($("#maxprice").val()));
					
					var MinPrice = MaxPrice = '';
					$("#pricechange").val('0');
					$('#reset-price').hide();
					chkClearAll();
				}
				return false;
				//GetProducts(true);
			} else if (Filter == 'categories') {
				$("#categories li a").removeClass('cf-active');
				$('#reset-categories').hide();
				chkClearAll();
				//GetProducts(true);
			} else {
				$("#" + Filter).find('li a').removeClass('active');
				$('#reset-'+Filter).hide();
				chkClearAll();
				//GetProducts(true);
				return false;
			}
			window.location.hash = '';
			$(this).hide();
		})
})(jQuery);

$(window).bind('resize orientationchange', function () {
	w = document.body.clientWidth;
	$(".filter_acrd").removeClass("filter_acrd_act");
	var brand_search_hasfocus = 'N'
	if(!$("#filter_brand_search").hasClass("input-fcs")){
		brand_search_hasfocus = 'Y';
	}
	if(brand_search_hasfocus == 'Y'){
		$(".filter_acrd_con").hide();
	}
	$(".filter").hide();
	$(document).on('click', '.sortmenu', function () {
		o = $(".sortmenu");
		c = $(".filter");
		o.hasClass("sortactive") ? (o.removeClass("sortactive"), c.slideUp()) : (o.addClass("sortactive"), c.stop(!0, !0).slideDown())
	})
	// if (w < 1025) {
	// } else {
	// 	$(".filter").show();
	// 	$(".filter_acrd").addClass("filter_acrd_act");
	// 	$(".filter_acrd_con").show();
	// }
});

function chkClearAll(){
	var make_clear = true;
	$("#categories li a").each(function(){		
		if($(this).hasClass("cf-active")){
			make_clear = false;
		}
	})

	if($("#reset-brands, #reset-size, #reset-price, #reset-fragrance_type").is(":visible")){
		make_clear = false;
	}	
	if(make_clear == true){
		$("#clrAll").hide();
	}	
}

function openNav() {
	document.getElementById("myFilter").style.width = "100%";
}

function closeNav() {
	document.getElementById("myFilter").style.width = "0%";
} ! function (i) {
	var o, c;
	$(document).on("click", ".filter_acrd_hd", function () {
		o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())	
		
		//var is_brand = "N";		
		//var is_brand_found = 'N'
		/*if($(this).parent().find('input[type=search]').attr('id')=='filterbrand'){
			is_brand = "Y";
		}*/	
		//if($(this).attr('id') == 'brand_filter'){			
			/*is_brand = "Y";			
			if($("#filter_brand_search").hasClass("input-fcs")){
				is_brand_found = 'Y';
				alert("focused");	
			}*/
			/*if($("#filterbrand").val() != ''){
				$("#brands li").each(function(){										
					if(typeof $(this).attr('style') === "undefined" || $(this).attr('style') == ''){
						is_brand_found = 'Y';
					}
				})
			}*/			
		//}		
		
		/*if(is_brand_found == 'N'){
			o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())
		} else {
			o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())
		}*/		
    })
}(jQuery);


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
					$(ele).removeClass('cf-active');
					if($.inArray(DataID, valarray) >= 0)
					{
						$(ele).addClass('cf-active');
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

var chkZeroResult = false;

function GetProducts(autoload=false) {	
	var Filters = GetFilters();	
	var from_brand_page = 'N';
	var brand_name = '';
	var brand_id = '';
	var cat_name = '';
	
	if($("#category_id").length > 0){
		from_brand_page = 'N';
		cat_name = $("#catName").val();
	} else {
		from_brand_page = 'Y';
		brand_name = $("#brandName").val();
		brand_id = $("#imanufacture_id").val();
	}	
	if(chkZeroResult == true){
		//alert(5555);
		//ResetFilters();
		//Filters = GetFilters(); //'{"new_filter":1,"categories":[],"brands":[],"fragrance_family":[],"fragrance_type":[],"size":[],"price":[],"sortby":""}';
		Filters = '{"new_filter":1,"categories":[],"brands":[],"fragrance_family":[],"fragrance_type":[],"size":[],"price":[],"sortby":""}';
		if(from_brand_page == 'Y'){
			Filters = '{"new_filter":1,"categories":[],"brands":["'+brand_id+'"],"fragrance_family":[],"fragrance_type":[],"size":[],"price":[],"sortby":""}';
		}
		//$("#page-spinner").removeAttr("style").attr("style","display:block");
		$("#page-spinner").show();				
	}
    //console.log("Filters-:-"+Filters);	
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
			fromClearAll:fromClearAll,
			keyword: keyword,
			page: parseInt($('.list-more').attr('data-page')),
			setArrBreadCrumb: setFilterData,
			currFilter : $(".filter").attr('data-current'),
            new_filter : 1,
			from_brand_page:from_brand_page,
			brand_name : brand_name,
			brand_id : brand_id,
			cat_name:cat_name
		},
		success: function (data) {
			var TotalProds = data.TotalProducts;
			$(".list-more").attr('data-total',TotalProds);
			//var PageSize = parseInt($(".list-more").attr('data-page')) * 12;
			var PageSize = parseInt($(".list-more").attr('data-page')) * 25;
			//$("#total").html('('+TotalProds+')');
			console.log(PageSize);
			if (currentURL.indexOf('p4u/key-') > 0) {
				//$('.filter_mid').html('');
				//$('.filter_mid').html(data.Filters);	
				//$(".scroll").mCustomScrollbar({});	
			}
			$('#total').html('');
			$('#total').html(data.TotalProducts+' Results');
			//$("#noprod").hide();	
			if(chkZeroResult == false){
				$("#noprod_temp").hide();
			}			
			if (TotalProds > 0) {
				chkZeroResult = false;
				if (PageSize > TotalProds) {
					$("#list-more").hide();					
				} else {					
					$("#list-more").show();
				}
                $('.total-results').show();
			} else {
				if(chkZeroResult == false){
					chkZeroResult = true;					
					GetProducts();
				}				
                $("#list-more").hide();
				//$("#noprod").show();
				$("#noprod_temp").show();			
                $('.total-results').hide();
			}
			if(data.from_cache == 'Y'){
				$("#noprod_temp").show();			
			}

			var page = parseInt($('.list-more').attr('data-page'));
			if (page > 1) {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
				}
				$('.listing_grid').hide().append(data.ProductHTML).fadeIn(1000);	
				$("#page-spinner").hide();
				
			} else {
				if (currentURL.indexOf('p4u/key-') > 0) {
					$('.filter_mid').hide().html(data.Filters).fadeIn(1000);
				}
				$('.listing_grid').hide().html(data.ProductHTML).fadeIn(1000);				
				$("#page-spinner").hide();
			}
			
			if (currentURL.indexOf('p4u/key-') > 0) {
				$(".filter_checkbox li a").each(function (index, ele) {
					var filterParent = $(this).parent().parent();
					var filID = $(filterParent).attr('id');
					
					if($('#'+filID+' li a.active').length > 0){
						$('#reset-'+filID).show();
						$("#clrAll").show();
					}
					else{
						$('#reset-'+filID).hide();
						chkClearAll();
					}
				});
				
				if ($("#pricechange").val() == '1') {
					$('#reset-price').show();
					$("#clrAll").show();
				}else{
					$('#reset-price').hide();
					chkClearAll();
				}
			}
			
			if(data.BredcrumHTML)
			{
				$('.breadcrumb .container').html(data.BredcrumHTML.BredLink);
			}
			//if(data.from_cache == 'N' && data.BredcrumHTML.PageTitle){
			if(data.BredcrumHTML.PageTitle){	
				$("#total").parent().html(data.BredcrumHTML.PageTitle+' <span id="total" class="total-results">'+data.TotalProducts+' Results</span>');
					
				//$('#total').html('('+data.TotalProducts+')');
			}			
					
			if (currentURL.indexOf('p4u/key-') <= 0 && !autoload) {
				SetFilterLink(data.FilterCategories);
			}						
		},
		complete:function(){
			$(".listing_grid").attr('data-load','0');
		},
	});
}

function checkReset(filter){		
	var j = 1;
	$("#"+filter+" li a").each(function(index){					
		if($(this).attr("class") == 'active'){	
			j++;
		}		
	})	
	if(j == 1){		
		$("#reset-"+filter).hide();
		chkClearAll();
	}	
}

function GetFilters() {
	var currentURL = window.location.href;
    GetSelectedFilters();
	if (currentURL.indexOf('p4u/key-') > 0) {		
		var SelBrands = GetFilterByID('brand');
		var SelCategories = GetFilterByID('category');
		var SelSize = GetFilterByID('size');
        var SelPrice = GetFilterByID('price');
		var SelFamily = GetFilterByID('fragrance_family');
		var SelFragranceType = GetFilterByID('fragrance_type');
		var SortBy = $('#selsort').val();
	} else {		
		var SelCategories = GetFilterByID('categories');
		var SelBrands = GetFilterByID('brands');
		var SelFamily = GetFilterByID('fragrance_family');
		var SelSize = GetFilterByID('size');
        var SelPrice = GetFilterByID('price');
		var SelFragranceType = GetFilterByID('fragrance_type');
        var SortBy = $("#sortopt").val();
	}	
	var Filters = new Array();
	
	Filters = {
        'new_filter' : 1,
		'categories': SelCategories,
		'brands': SelBrands,
		'fragrance_family': SelFamily,
		'fragrance_type': SelFragranceType,
		'size': SelSize,
		'price': SelPrice,
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
			if ($(ele).hasClass('cf-active')) {
				FilterData.push($(ele).attr('data-id'));
			}
		});
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

function SetFilterLink(Categories='') {
	var hashLink = '';
	var CatLink = '';
    if(Categories != '' && Categories.length > 0)
    {
        hashLink += 'cid-' + Categories.join(",");
    }
	if($("#category_id").length > 0){
		if($("#category_id").val()!=''){
			hashLink += 'cid-' + $("#category_id").val();
		}
		hashLink += GetLink('categories');
		hashLink += GetLink('brands');
	} else {
		hashLink += GetLink('brands');
		hashLink += GetLink('categories');		
	}
    hashLink += GetLink('gender');
    hashLink += GetLink('special');
    hashLink += GetLink('max2day');	
	hashLink += GetLink('fragrance_family');
	hashLink += GetLink('fragrance_type');
	hashLink += GetLink('size');
	hashLink += GetLink('price');
	if(hashLink != '')
	{
		hashLink = '#' + hashLink;
		window.location.hash = hashLink;
	}
	return false;
}

function GetSelectedFilters()
{
    var FilterData = new Array();
    $("#categories li a.cf-active").each(function (index, ele) {
        var SelID = $(ele).attr('data-id');
        var li = '<li class="ft-tag">'+$(ele).attr('data-name');
                li+='<a href="javascript:void(0);" class="ft-clear" data-filter="categories" data-id="'+SelID+'">';
                li+='<svg class="sv-close" aria-hidden="true" role="img" width="10" height="10"><use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use></svg>';
                li+='</a></li>';
        FilterData.push(li);  
    });
    $('.filter_checkbox').each(function(index,filterEle){
        var FilID = $(filterEle).attr('id');
        $(this).find('.active').each(function(actindex,actele){
            var SelID = $(actele).attr('data-id');
            var li = '<li class="ft-tag">'+$(actele).text();
                li+='<a href="javascript:void(0);" class="ft-clear" data-filter="'+FilID+'" data-id="'+SelID+'">';
                li+='<svg class="sv-close" aria-hidden="true" role="img" width="10" height="10"><use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use></svg>';
                li+='</a></li>';
            FilterData.push(li);    
        })
    });
    
    if ($("#pricechange").val() == '1') {
		MinPrice = $("#chgminprice").val();
		MaxPrice = $("#chgmaxprice").val();
        var li = '<li class="ft-tag">$'+MinPrice+'-$'+MaxPrice;
                li+='<a href="javascript:void(0);" class="ft-clear" data-filter="price">';
                li+='<svg class="sv-close" aria-hidden="true" role="img" width="10" height="10"><use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use></svg>';
                li+='</a></li>';
            FilterData.push(li);
	}
    if(FilterData.length > 0)
    {
        $('.fcount').show();
        $('.fcount').html(FilterData.length);
        var impData = FilterData.join(" ");
		
        $(".ft-list").html(impData);
    } else {
        $('.fcount').hide();
        $('.fcount').html('');
        $(".ft-list").html('');
    }
}

function GetLink(FilterID) {
	var hashLink = '';
	var hashID = '';	
	
	if (FilterID == 'categories') {		
		var CatLink = '';
		var tempLink = ''; //using for ml mini left side filter only
		var newarrivallink = '';
		var discovery_set_link = '';
		var fpocket_perfumelink = '';
		$("#categories li a").each(function (index, ele) {			
			if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'Category')
				CatLink += $(ele).attr('data-id') + ',';
			
			if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == '8-ml-mini')
				tempLink += $(ele).attr('data-id') + ',';	

			if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'Specialna')
				newarrivallink += $(ele).attr('data-id') + ',';		
			
			if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'DiscoverySet')
				discovery_set_link += $(ele).attr('data-id') + ',';			

			if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'f-pocket-perfume')
				fpocket_perfumelink += $(ele).attr('data-id') + ',';				
		});
		
		if (CatLink != '')
			hashLink += ',' + (CatLink.substr(0, CatLink.length - 1));
		if(tempLink != '')
			hashLink += '&' + (tempLink.substr(0, tempLink.length - 1));
		if(discovery_set_link != '')
			hashLink += '&' + (discovery_set_link.substr(0, discovery_set_link.length - 1));
		if(fpocket_perfumelink != '')
			hashLink += '&' + (fpocket_perfumelink.substr(0, fpocket_perfumelink.length - 1));
		/*if(newarrivallink != '')
			hashLink += '&' + (newarrivallink.substr(0, newarrivallink.length - 1));	*/
		
	} else if (FilterID == 'gender') {
        var GenderLink = '';
        $("#categories li a").each(function (index, ele) {
            if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'Gender')
				GenderLink += $(ele).attr('data-id') + ',';
		});
        if (GenderLink != '')
			hashLink += '&gen-' + (GenderLink.substr(0, GenderLink.length - 1));
    }else if (FilterID == 'special') {
        var SpecialLink = '';
        $("#categories li a").each(function (index, ele) {
            if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'Special')
				SpecialLink += $(ele).attr('data-id') + ',';
		});
        if (SpecialLink != '')
			hashLink += '&special-' + (SpecialLink.substr(0, SpecialLink.length - 1));
    }else if (FilterID == 'max2day') {
        var Max2dayLink = '';
        $("#categories li a").each(function (index, ele) {
            if ($(ele).hasClass('cf-active') && $(ele).attr('data-type') == 'Max2day')
				Max2dayLink += 'yes';
		});
        if (Max2dayLink != '')
			hashLink += '&max2day-' + Max2dayLink;
    }else {
		var Filter = GetFilterByID(FilterID);
		var FilterKey = FilterID;
		if (FilterID == 'brands')
			FilterKey = 'mid';
		if (FilterID == 'fragrance_family')
			FilterKey = 'family';
		if (FilterID == 'fragrance_type')
			FilterKey = 'ftype';	
		if (FilterID == 'price')
			FilterKey = 'price';
		if (Filter.length > 0)
			hashLink += '&' + FilterKey + '-' + Filter.join(',');
	}
	return hashLink;
}
function mobilecheck() 
{
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
}

