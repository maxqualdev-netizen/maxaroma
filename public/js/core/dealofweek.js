
$("#page-spinner").show();	
var PageFilters = '';
var setFilterData = new Array();
$(document).mouseup(function(e) 
{
	if($("#is_test").val() == "1"){
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
	}
});
$("#chktoprated").on("click", function() {
    $("#li-OnTopRated a")[0].click();
    GetDealOfWeek();
});
$(document).ready(function(){

	if($("#is_test").val() == "1"){
		var size_frm_url = "";
		var brand_id = "";
		var price_frm_url = "";
		var fragrance_type = "";
		var fragrance_occasion = "";
		var fragrance_season = "";
		var upc_key = "";
		var v_href = window.location.href;
		var v_hrefarr = v_href.split("/");
		$(".filter_btn").click(function () {			
			$("body").toggleClass('open-filter');
			isFilterOpen = 1;        
		});
	
		$(".filter-close").click(function () {
			$("body").removeClass('open-filter');
			isFilterOpen = 0;        
		});

		for(var i = 0; i < v_hrefarr.length; i++){
			if(v_hrefarr[i].indexOf("size") > -1){			
				size_frm_url = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("mid") > -1){			
				brand_id = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("price") > -1){			
				price_frm_url = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("ftype") > -1){			
				fragrance_type = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("fragrance_occasion") > -1){			
				fragrance_occasion = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("fragrance_season") > -1){			
				fragrance_season = v_hrefarr[i];
			}
			if(v_hrefarr[i].indexOf("key") > -1){			
				upc_key = v_hrefarr[i];
			}
		}

		if(upc_key != ''){
			var upc_arr = upc_key.split("-");
			$("#searchkey").val(upc_arr[1]);
			GetDealOfWeek();
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
				GetDealOfWeek();
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
				$("#clrAll").show();
				GetDealOfWeek();									
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
				GetDealOfWeek();
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
				GetDealOfWeek();
			}
		}
		if(fragrance_occasion != ''){
			var fragrance_occasion_arr = fragrance_occasion.split("fragrance_occasion-");		
			if(fragrance_occasion_arr.length > 1){
				var fragrance_occasion_arr = fragrance_occasion_arr[1].split(",");
				$("#fragrance_occasion li a").each(function(index){			
					if($.inArray($(this).attr("data-id"),fragrance_occasion_arr) !== -1){				
						$(this).addClass('active');				
					}
				})
				$("#reset-fragrance_occasion").show();
				$("#clrAll").show();
				GetDealOfWeek();
			}
		}
		if(fragrance_season != ''){
			var fragrance_season_arr = fragrance_season.split("fragrance_season-");		
			if(fragrance_season_arr.length > 1){
				var fragrance_season_arr = fragrance_season_arr[1].split(",");
				$("#fragrance_season li a").each(function(index){			
					if($.inArray($(this).attr("data-id"),fragrance_season_arr) !== -1){				
						$(this).addClass('active');				
					}
				})
				$("#reset-fragrance_season").show();
				$("#clrAll").show();
				GetDealOfWeek();
			}
		}
	}

	$("#page-spinner").hide();
	if($("#is_test").val() == "1"){
		$(".reset_link").hide();
		$(".filter_acrd_con").hide();

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

	}	
	if($("#is_test").val() != "1"){
		$("#brands").selectbox({
			speed : 450,	
			onChange:function (val, inst) {
				if(val != 'Brands')
					$("#selbrands").val(val);
				else
					$("#selbrands").val('');
				GetDealOfWeek();
			}
		});
		$("#size").selectbox({
			speed : 450,
			onChange:function (val, inst) {
				if(val != 'Size')
					$("#selsize").val(val);
				else
					$("#selsize").val('');	
				GetDealOfWeek();
			}
		});
	}
	$("#btnsearch").click(function(){
		if($("#searchkey").val() == '')
		{
			alert('Please Enter UPC.');
			return false;
		}
		GetDealOfWeek();	
	})
	$("#dealsort").selectbox({
		speed : 450,
		onChange:function (val, inst) {
			$("#sortopt").val(val);
			var Page = 1;
			$('.list-more').attr('data-page',Page);
			GetDealOfWeek();
		}
	});
	$("#chksold").click(function(){
		GetDealOfWeek();
	});
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetDealOfWeek();
	});
	if($("#is_test").val() != "1"){
		$(".reset_link").click(function(){
			window.location.href = site_url+'dealofweek.html';
		});
	}	
	$(".deal-filternw-t").click(function(){
		//console.log('IN');
	  $(".deal-filtersec").toggle();
	});
	
	var MinPrice = parseFloat($("#minprice").val());
	var MaxPrice = parseFloat($("#maxprice").val());	
	$("#slider-range").slider({
		range: true,
		min: MinPrice,
		max: MaxPrice,
		step: 0.1,
		values: [MinPrice, MaxPrice],
		slide: function(event, ui) {
			$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			$("#pricechange").val('1');
			$("#chgminprice").val(ui.values[0]);
			$("#chgmaxprice").val(ui.values[1]);
		},
		stop:function(event,ui){
			GetDealOfWeek();
		}
	});
	$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
	
	var deal_end_date = $('#deal_end_date').val();
	//var deal_end_date = '20-05-2021 23:59:59';
	var deal_start_date = $('#deal_start_date').val();
	if(deal_end_date != '')
	{
		var $clock = $('#counter_1'),
		eventTime = moment(deal_end_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		currentTime = moment(deal_start_date, 'DD-MM-YYYY HH:mm:ss').unix(),
		diffTime = eventTime - currentTime,
		duration = moment.duration(diffTime * 1000, 'milliseconds'),
		interval = 1000;
		if(diffTime > 0) {
			/*var $d = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$h = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$m = $('<i style="font-style: normal;"></i>').appendTo($clock),
				$s = $('<i style="font-style: normal;"></i>').appendTo($clock);
			*/
			setInterval(function(){

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
				
				var clockdata = '';
				clockdata+='<li><span class="count" id="days">'+d+'</span><br/>Days</li>';
				clockdata+='<li><span class="count" id="hrs">'+h+'</span><br/>Hours</li>';
				clockdata+='<li><span class="count" id="min">'+m+'</span><br/>Mins</li>';
				clockdata+='<li><span class="count" id="sec">'+s+'</span><br/>Secs</li>';
				$clock.html(clockdata);

			}, interval);
		}
	}
	
});
PageFilters = GetFilters();

function chkClearAll(){
	var make_clear = true;
	$("#categories li a").each(function(){		
		if($(this).hasClass("cf-active")){
			make_clear = false;
		}
	})

	if($("#reset-brands, #reset-size, #reset-price, #reset-fragrance_type, #reset-fragrance_occasion, #reset-fragrance_season").is(":visible")){
		make_clear = false;
	}	
	if(make_clear == true){
		$("#clrAll").hide();
	}	
}

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

function openNav() {
	document.getElementById("myFilter").style.width = "100%";
}

function closeNav() {
	document.getElementById("myFilter").style.width = "0%";
} ! function (i) {
	var o, c;
	$(document).on("click", ".filter_acrd_hd", function () {
		o = i(this).parents(".filter_acrd"), c = o.find(".filter_acrd_con"), o.hasClass("filter_acrd_act") ? (o.removeClass("filter_acrd_act"), c.slideUp()) : (o.addClass("filter_acrd_act"), c.stop(!0, !0).slideDown())				
    })
}(jQuery);
if($("#is_test").val() == "1"){
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

	$('#filterbrand').keyup(function(){
		var searchText = $(this).val().toLowerCase();
		$('#brands > li').each(function(){
			var currentLiText = $(this).text().toLowerCase(),
				showCurrentLi = currentLiText.indexOf(searchText) !== -1;
			$(this).toggle(showCurrentLi);
		});     
	});
	$("#btnfilter").click(function(){
		GetDealOfWeek();
		$(".filter-close").click();
	});
}

$(document).on("click", ".reset_link", function (e) {
	if($("#is_test").val() == "1"){
		var Filter = $(this).attr('data-filter');
		var currentURL = window.location.href;
	
		if (Filter == 'all') {
			$(".filter-close").click();	
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
				GetDealOfWeek(true);
				//GetProducts(true);
			}
		}else if (Filter == 'categories') {
			$("#categories li a").removeClass('cf-active');
			$('#reset-categories').hide();
			chkClearAll();			
			//GetProducts(true);
			GetDealOfWeek(true);
		} else {
			$("#" + Filter).find('li a').removeClass('active');
			$('#reset-'+Filter).hide();
			$(".filter-close").click();
			chkClearAll();
			//GetProducts(true);
			GetDealOfWeek(true);
		}
		window.location.hash = '';
		$(this).hide();
	}
})



function GetDealFilters()
{
	var SelBrands = new Array();
	if($("#selbrands").val() != '' && $("#selbrands").val() != 'Brands' )
		SelBrands = [$("#selbrands").val()];
	var SelSize = new Array();
	if($("#selsize").val() != '')
		SelSize = [$("#selsize").val()];
	var SortBy = $("#sortopt").val();
	var SoldOut = "";
	if($("#chksold").prop('checked'))
		SoldOut = "Out";
	
	var MinPrice = '';
	var MaxPrice = '';
	
	if($("#pricechange").val() == '1')
	{
		MinPrice = $("#chgminprice").val();
		MaxPrice = $("#chgmaxprice").val();
	}
	
	var Filters = {
		'brands' : SelBrands,
		'size'	: SelSize,
		'sortby' : SortBy,
		'minprice' 	: MinPrice,
		'maxprice' 	: MaxPrice,
	};
	if(SoldOut != '')
		Filters['stock'] = SoldOut;
	if($("#searchkey").val() != '')
		Filters['key'] = $("#searchkey").val();
	
	return JSON.stringify(Filters);
}
/*
$(window).scroll(function(e) {
	if(($(window).scrollTop()+250) >= $('#dealofweek').height()) 
	{
		var TotalProds = parseInt($('.list-more').attr('data-total'));
		var CurrProdsCount = $(".product").length;
		if($("#dealofweek").attr('data-load') == '0' && CurrProdsCount < TotalProds)
		{
			var Page = parseInt($('.list-more').attr('data-page'));
			Page = Page + 1;
			$('.list-more').attr('data-page', Page);
			GetDealOfWeek();
		}
    }
});
*/

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

$(document).on('click','.ft-clear',function(){
	var Filter = $(this).attr('data-filter');
	var ID = $(this).attr('data-id');		
	if(Filter == 'categories')
	{
		$('#'+Filter).find('[data-id = "'+ID+'"]').removeClass('cf-active');
	}else {			
		$('#'+Filter).find('[data-id = "'+ID+'"]').removeClass('active');			
	}
	//here			
	//GetProducts();		
	GetDealOfWeek();
	checkReset(Filter);
});

$("#btnupcsearch").on("click", function (){
	if($("#searchkey").val() != ''){
		GetDealOfWeek();
		$("body").toggleClass('open-filter');
	}
})

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
		var SelFragranceOccasion = GetFilterByID('fragrance_occasion');
		var SelFragranceSeason = GetFilterByID('fragrance_season');
		var SortBy = $('#sortopt').val();
	} else {			
		var SelCategories = GetFilterByID('categories');
		var SelBrands = GetFilterByID('brands');
		var SelFamily = GetFilterByID('fragrance_family');
		var SelSize = GetFilterByID('size');
        var SelPrice = GetFilterByID('price');
		var SelFragranceType = GetFilterByID('fragrance_type');
		var SelFragranceOccasion = GetFilterByID('fragrance_occasion');
		var SelFragranceSeason = GetFilterByID('fragrance_season');
        var SortBy = $('#sortopt').val(); //$("#sortopt").val();
	}
	var Filters = new Array();
	Filters = {
        'new_filter' : 1,
		'categories': SelCategories,
		'brands': SelBrands,
		'fragrance_family': SelFamily,
		'fragrance_type': SelFragranceType,
		'fragrance_occasion' : SelFragranceOccasion,
		'fragrance_season' : SelFragranceSeason,
		'size': SelSize,
		'price': SelPrice,
		'sortby': SortBy
	};
	var SoldOut = "";
	var topRated = "";
	if($("#chksold").prop('checked'))
		SoldOut = "Out";
	if($("#searchkey").val() != '')
		Filters['key'] = $("#searchkey").val();

	if(SoldOut != '')
		Filters['stock'] = SoldOut;	

	if($("#chktoprated").prop('checked'))
		topRated = 'Y';

	if(topRated == 'Y')
		Filters['onTopRated'] = topRated;
	return JSON.stringify(Filters);
}

function GetDealOfWeek(autoload=false)
{
	var Filters = GetDealFilters();
	if($("#is_test").val() == "1"){
		Filters = GetFilters();
		console.log(Filters);		
	}
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	$("#dealofweek").attr('data-load','1');
	$.ajax({
	type:'POST',
	url:site_url+'getdealofweek',
	headers: {
		'X-CSRF-TOKEN': token
	},
	data:{
		filters : Filters,
		is_test : $("#is_test").val(),
		page : parseInt($('.list-more').attr('data-page')),
	},
	success:function(data) {
		//console.log(data);
		var TotalProds = data.TotalProducts;
		$(".list-more").attr('data-total',TotalProds);
		var PageSize = parseInt($(".list-more").attr('data-page')) * 12;
		var BrandList = data.BrandList;
		var SizeList = data.Sizelist;
		if($("#is_test").val() != "1"){
			$('#brands').html('<option>Brands</option>');
			$.each(BrandList,function(key,brand){
				$('#brands').append('<option value="'+key+'">'+brand+'</option>');
			});
			$('#brands').selectbox({
				refresh : true,
			});		
		}
		var MinPrice = parseFloat(data.MinPrice);
		var MaxPrice = parseFloat(data.MaxPrice);

		$('#slider-range').slider("option", "min", MinPrice);
		$('#slider-range').slider("option", "max", MaxPrice);
		$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
		$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
		$("#noprod").hide();
		if(TotalProds > 0){
			if(PageSize >= TotalProds){
				//$("#loadmore").hide();				
				$("#list-more").hide();
				$(".list-more").attr('data-page','1').removeAttr('data-total');
			}else{				
				//$("#loadmore").show();
				$("#list-more").show();
			}
		} else {
			$("#noprod").show();
			//$("#loadmore").hide();
			$("#list-more").hide();
		}
		
		var page = parseInt($('.list-more').attr('data-page'));
		if(page > 1 ){			
			$('#dealofweek').hide().append(data.ProductHTML).fadeIn(1000);
		}else{			
			$('#dealofweek').hide().html(data.ProductHTML).fadeIn(1000);
		}
		$("#page-spinner").hide();
		if($("#is_test").val() == "1"){			
			SetDealFilterLink(data.FilterCategories);
		} else {
			SetFilterLink();
		}	
		if (typeof yotpo !== 'undefined' && yotpo.initWidgets) {
			yotpo.initWidgets();
		}
	},
	complete:function(){
		$("#dealofweek").attr('data-load','0');
	},
});
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
		//here			
		var Filter = GetFilterByID(FilterID);
					
		var FilterKey = FilterID;
		if (FilterID == 'brands')
			FilterKey = 'mid';
		if (FilterID == 'fragrance_family')
			FilterKey = 'family';
		if (FilterID == 'fragrance_type')
			FilterKey = 'ftype';
		if (FilterID == 'fragrance_occasion')
			FilterKey = 'fragrance_occasion';		
		if (FilterID == 'fragrance_season')
			FilterKey = 'fragrance_season';	
		if (FilterID == 'price')
			FilterKey = 'price';
		if (Filter.length > 0)
			hashLink += '&' + FilterKey + '-' + Filter.join(',');
	}	
	return hashLink;
}

function SetDealFilterLink(Categories='') {		
	var hashLink = '';
	var CatLink = '';
    if(Categories != '0' && Categories != '' && Categories.length > 0)
    {
        hashLink += 'cid-' + Categories.join(",");
    }
	
    hashLink += GetLink('categories');
	hashLink += GetLink('brands');
    hashLink += GetLink('gender');
    hashLink += GetLink('special');
    hashLink += GetLink('max2day');	
	hashLink += GetLink('fragrance_family');
	hashLink += GetLink('fragrance_type');
	hashLink += GetLink('fragrance_occasion');
	hashLink += GetLink('fragrance_season');
	hashLink += GetLink('size');
	hashLink += GetLink('price');
	
	if(hashLink != '')
	{
		hashLink = '#' + hashLink;
		window.location.hash = hashLink;
	}
	return false;
}

function SetFilterLink()
{
	var hashLink = '';
	var hasarray = new Array();
	if($("#selbrands").val() != '' && $("#selbrands").val() != 'Brands')
		hasarray.push('mid-'+$("#selbrands").val());
	if($("#selsize").val() != '')
		hasarray.push('size-'+$("#selsize").val());
	if($("#chksold").prop('checked'))
		hasarray.push('stock-In');
	var MinPrice = '';
	var MaxPrice = '';
	if($("#pricechange").val() == '1')
	{
		MinPrice = $("#slider-range").slider("values", 0);
		MaxPrice = $("#slider-range").slider("values", 1);
		hasarray.push('price-' + MinPrice + '-' + MaxPrice);
	}
	if($("#searchkey").val() != '')
		hasarray.push('key-' + $("#searchkey").val());
	
	if(hasarray.length > 0)
		hashLink+= hasarray.join("&");
	
	window.location.hash = hashLink;
	return false;
}
