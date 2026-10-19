function AfterpaySetup()
{
	var currency_code = $('#site_currency').val();
	var Min_AP_AMT = $('#apminamt').val();
	Min_AP_AMT =  parseInt(Number(Min_AP_AMT.replace(/[^0-9.-]+/g,"")));
	
	var Max_AP_AMT = $('#apmaxamt').val();
	Max_AP_AMT =  parseInt(Number(Max_AP_AMT.replace(/[^0-9.-]+/g,"")));
	
	if($('#shoptotal').length > 0)
	{
		var cmpval = $('#shoptotal').html();
		cmpval = parseFloat(cmpval * 100);
		$(".afterpay-paragraph").remove();
		currency_code = (currency_code == "EURO") ? "EUR" : currency_code;
		const apConfig = {
			priceSelector: '#shoptotal',
			locale: 'en_US',
			currency: currency_code,
			minMaxThreshold: {
				min: Min_AP_AMT,
				max: Max_AP_AMT,
			},
		};

		new presentAfterpay(apConfig).init();
		$(".afterpay-paragraph").addClass('mt-3');
		if($(".afterpay-link").length){
			$(".afterpay-link").empty().html("<i class='fa fa-info-circle'></i>");
			$(".afterpay-paragraph").on("mouseover",function(){this.style.cursor = "pointer";});
		}
		if(cmpval > Min_AP_AMT && cmpval < Max_AP_AMT){
			$(document).on('click',".afterpay-paragraph",function(){
				window.location= site_url+'billing';
			});
			$(document).on('click',".afterpay-paragraph .afterpay-link",function(e){
				 e.stopPropagation();
			});
			if($(".afterpay-text1").length){
				$(".afterpay-paragraph").addClass('afterpay-second');
				var txt1 = $(".afterpay-text1").html();
				if(txt1 != "" && txt1 != 'undefined'){
					var replacetxt = txt1.replace("or", "Or");
					$(".afterpay-text1").html(replacetxt);
				}
			}

			if($(".afterpay-link").length){
				//$(".afterpay-link").empty().html("<i class='fa fa-info-circle'></i>");
				$(".afterpay-paragraph").on("mouseover",function(){this.style.cursor = "pointer";});
			}
		}
	}
}