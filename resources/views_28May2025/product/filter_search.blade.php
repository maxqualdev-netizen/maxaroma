@if(count($Filters) > 0)
	@foreach($Filters as $FilterArray)
		@foreach($FilterArray as $Filter)
			<x-filtersearch :filterAttr="$Filter['Attr']" :filterData="$Filter['Data']" :filterSelected="$Filter['Selected']"/>
		@endforeach
	@endforeach
@endif
@if(count($Products) > 0)
<div class="filter_acrd filter_acrd_act">
	<div class="filter_acrd_hd">
		<span class="title">Price Range</span> 
		<a class="reset_link" id="reset-price" data-filter="price">Reset</a>
	</div>
	<div class="filter_acrd_con filters-p">
		<div class="inner">
			<!--<div class="scroll">-->
				<input type="hidden" id="pricechange" value="{{$OChangePrice}}"/>
				<input type="hidden" id="minprice" value="{{$OMinPrice}}"/>
				<input type="hidden" id="maxprice" value="{{$OMaxPrice}}"/>
				<input type="hidden" id="chgminprice" value="{{$MinPrice}}"/>
				<input type="hidden" id="chgmaxprice" value="{{$MaxPrice}}"/>
				<div class="range_slider_controller"><div id="slider-range"></div></div>
				<input type="text" id="amount" readonly class="renge-price">
			<!--</div>-->
		</div>
	</div>
</div>
@endif

<script>

$(document).ready(function(){
	var MinPrice = parseFloat($("#minprice").val());
	var MaxPrice = parseFloat($("#maxprice").val());
	
	$("#slider-range").slider({
		range: true,
		min: MinPrice,
		max: MaxPrice,
		step: 0.1,
		values: [$("#chgminprice").val(), $("#chgmaxprice").val()],
		//slide: function(event, ui) {
		change: function (event, ui) {
			$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			$("#pricechange").val('1');
			$("#chgminprice").val($("#chgminprice").val());
			$("#chgmaxprice").val($("#chgmaxprice").val());
			$('.list-more').attr('data-page', 1);
			$('#reset-price').show();
		},
		stop:function(event,ui){
			/*$("#chgminprice").val($("#chgminprice").val());
			$("#chgmaxprice").val($("#chgmaxprice").val());
			$("#pricechange").val('1');*/
			
			$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			$("#pricechange").val('1');
			$("#chgminprice").val(ui.values[0]);
			$("#chgmaxprice").val(ui.values[1]);
			$('.list-more').attr('data-page', 1);
			
			GetProducts();
		}
	});
	$("#amount").val("$" + $("#chgminprice").val() + " - $" + $("#chgmaxprice").val());
});
</script>
