$("#cover-spin").show();	
$(document).ready(function(){
	$("#cover-spin").hide();
	$("#brands").selectbox({
		speed : 450,	
		onChange:function (val, inst) {
			$("#selbrands").val(val);
			GetPromotional();
		}
	});
	$("#size").selectbox({
		speed : 450,
		onChange:function (val, inst) {
			$("#selsize").val(val);
			GetPromotional();
		}
	});
	$("#btnsearch").click(function(){
		if($("#searchkey").val() == '')
		{
			alert('Please Enter UPC.');
			return false;
		}
		GetPromotional();	
	})
	$("#dealsort").selectbox({
		speed : 450,
		onChange:function (val, inst) {
			$("#sortopt").val(val);
			var Page = 1;
			$('.list-more').attr('data-page',Page);
			GetPromotional();
		}
	});
	$("#chksold").click(function(){
		GetPromotional();
	});
	$(".list-more").click(function(){
		var Page = parseInt($(this).attr('data-page'));
		Page = Page+1;
		$(this).attr('data-page',Page);
		GetPromotional();
	});
	$(".reset_link").click(function(){
		window.location.href = site_url+'promotional.html';
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
			GetPromotional();
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
function GetDealFilters()
{
	var SelBrands = new Array();
	if($("#selbrands").val() != '' && $("#selbrands").val() != 'Brands')
		SelBrands = [$("#selbrands").val()];
	var SelSize = new Array();
	if($("#selsize").val() != '' && $("#selsize").val() != 'Size')
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
function GetPromotional()
{
	var Filters = GetDealFilters();
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#cover-spin").show();
	$.ajax({
	type:'POST',
	url:site_url+'getpromotional',
	headers: {
		'X-CSRF-TOKEN': token
	},
	data:{
		filters : Filters,
		page : parseInt($('.list-more').attr('data-page')),
	},
	success:function(data) {
		var TotalProds = data.TotalProducts;
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
		
		$("#noprod").hide();
		if(TotalProds > 0){
			if(PageSize > TotalProds){
				$("#loadmore").hide();
			}else{
				$("#loadmore").show();
			}
		} else {
			$("#noprod").show();
		}
		
		var page = parseInt($('.list-more').attr('data-page'));
		if(page > 1 ){
			$('#promotional').hide().append(data.ProductHTML).fadeIn(1000);
		}else{
			$('#promotional').hide().html(data.ProductHTML).fadeIn(1000);
		}
		$("#cover-spin").hide();
		SetFilterLink();
	}
});
}

function SetFilterLink()
{
	var hashLink = '';
	var hasarray = new Array();
	if($("#selbrands").val() != ''&& $("#selsize").val() != 'Brands')
		hasarray.push('mid-'+$("#selbrands").val());
	if($("#selsize").val() != '' && $("#selsize").val() != 'Size')
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
