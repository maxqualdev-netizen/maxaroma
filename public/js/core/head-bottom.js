(function ($) {
        /*$(".select").selectbox({
            speed: 450,
			onChange: function (val, ele) {
				if($(ele).attr('id') == 'selsort')
				{
					$("#sortopt").val(val);
					GetProducts();
				}
			},
        });*/
		$("#selcurrency, #mcurrency, #selcurrencymob").selectbox({
            speed: 450,
        });
		$("#selsort").selectbox({
            speed: 450,
			onChange: function (val, ele) {
				$("#sortopt").val(val);
				var Page = 1;
				$('.list-more').attr('data-page', Page);
				GetProducts();
			}
        });
		$("#sort_by").selectbox({
            speed: 450,
			onChange: function (val, ele) {
				$("#sortselect").val(val);
				GetMainCategoryProducts();
			}
        });
})(jQuery);
(function($){
	$(document).ready(function(){
		//Accordion Nav
		$('.mainNav').navAccordion({
			expandButtonText: '+',  //Text inside of buttons can be HTML
			collapseButtonText: '-'
		}, 
		function(){
			//console.log('Callback')
		});
	});
})(jQuery);