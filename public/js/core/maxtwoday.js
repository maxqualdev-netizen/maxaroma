
$("#page-spinner").show();	
$(document).ready(function(){
	$("#page-spinner").hide();
	$("#brands").selectbox({
		speed : 450,	
		onChange:function (val, inst) {
			if(val != 'Brands')
				$("#selbrands").val(val);
			else
				$("#selbrands").val('');
			GetMaxtwoday();
		}
	});
	$("#size").selectbox({
		speed : 450,
		onChange:function (val, inst) {
			if(val != 'Size')
				$("#selsize").val(val);
			else
				$("#selsize").val('');	
			GetMaxtwoday();
		}
	});
	$("#btnsearch").click(function(){
		if($("#searchkey").val() == '')
		{
			alert('Please Enter UPC.');
			return false;
		}
		GetMaxtwoday();	
	})
	$("#dealsort").selectbox({
		speed : 450,
		onChange:function (val, inst) {
			$("#sortopt").val(val);
			var Page = 1;
			$('.list-more').attr('data-page',Page);
			GetMaxtwoday();
		}
	});
	$("#chksold").click(function(){
		GetMaxtwoday();
	});
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetMaxtwoday();
	});
	$(".reset_link").click(function(){
		window.location.href = site_url+'maxtwoday.html';
	});
	$(".deal-filternw-t").click(function(){
		console.log('IN');
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
			GetMaxtwoday();
		}
	});
	$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
	
	
});
function GetMaxtwodayFilters()
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
			GetMaxtwoday();
		}
    }
});
*/
function GetMaxtwoday(autoload=false)
{
	var Filters = GetMaxtwodayFilters();
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	$("#dealofweek").attr('data-load','1');
	$.ajax({
	type:'POST',
	url:site_url+'getmaxtwoday',
	headers: {
		'X-CSRF-TOKEN': token
	},
	data:{
		filters : Filters,
		page : parseInt($('.list-more').attr('data-page')),
	},
	success:function(data) {
		var TotalProds = data.TotalProducts;
		
		$("#MaxTitle").html('('+TotalProds+')');
		$(".list-more").attr('data-total',TotalProds);
		var PageSize = parseInt($(".list-more").attr('data-page')) * 12;
		var BrandList = data.BrandList;
		var SizeList = data.Sizelist;
		$('#brands').html('<option>Brands</option>');
		$.each(BrandList,function(key,brand){
			$('#brands').append('<option value="'+key+'">'+brand+'</option>');
		});
		$('#brands').selectbox({
			refresh : true,
		});
		
		var MinPrice = parseFloat(data.MinPrice);
		var MaxPrice = parseFloat(data.MaxPrice);

		$('#slider-range').slider("option", "min", MinPrice);
		$('#slider-range').slider("option", "max", MaxPrice);
		$('#slider-range').slider("option", "values", [MinPrice, MaxPrice]);
		$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
		$("#noprod").hide();
		if(TotalProds > 0){
			if(PageSize > TotalProds){
				$("#loadmore").hide();
			}else{
				$("#loadmore").show();
			}
		} else {
			$("#noprod").show();
			$("#loadmore").hide();
		}
		
		var page = parseInt($('.list-more').attr('data-page'));
		if(page > 1 ){
			$('#dealofweek').hide().append(data.ProductHTML).fadeIn(1000);
		}else{
			$('#dealofweek').hide().html(data.ProductHTML).fadeIn(1000);
		}
		$("#page-spinner").hide();
		SetFilterLink();
	},
	complete:function(){
		$("#dealofweek").attr('data-load','0');
	},
});
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
