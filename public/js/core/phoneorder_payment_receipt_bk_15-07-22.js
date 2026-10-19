$(document).ready(function() {
	$("input[name='pay_method']").on("click",function(){
		var sel_val = $(this).val();
		
		if(sel_val == "paypal"){
			$(".stripe").hide();
			$(".amazon").hide();
			$(".paypal").show();
			$(".hddiv.paypal").css("height","auto");
			$(".paypal").css("visibility","visible");
			$(".afterpay-paragraph").hide();
		}else if(sel_val == "amazon"){
			$(".stripe").hide();
			$(".amazon").show();
			$(".paypal").hide();
			$(".afterpay-paragraph").hide();
		}else if(sel_val == "afterpay"){
			$(".stripe").hide();
			$(".amazon").hide();
			$(".paypal").hide();
			$(".afterpay-paragraph").show();
		}else{
			$(".stripe").show();
			$(".amazon").hide();
			$(".paypal").hide();
			$(".afterpay-paragraph").hide();
		}
	})
	
})

window.onload = function(){
	$("input[name='pay_method']:checked").trigger("click");
	
	$("#page-spinner").hide();
	/* paypal.Button.render({
			env: PaypalActionMode,
			locale: 'en_US',

			// Specify the style of the button
			style: {
				label: 'checkout',
				size:  'responsive',    // small | medium | large | responsive
				shape: 'rect',     // pill | rect
				color: 'gold'      // gold | blue | silver | black
			},
			funding: {
				allowed: [ paypal.FUNDING.CREDIT ]
			},
			payment: function(resolve) {
				$.post(CREATE_PAYMENT_URL,formdata,function(data) {
					dataArr = data.split("#####");
					var status = dataArr[0];
					if(status == 'Success')
					{
						token =  dataArr[1];
						console.log("Displaying data here: " + token);
						resolve(token);
					}
					else
					{
						if(status == 'Error')
						{
							err_msg = dataArr[1];
							alert(err_msg);
							window.location = "{{config('global.SITE_URL')}}index.php";
							return;
						}
					}
				});
			},
			onAuthorize: function(data, actions) {
					return actions.redirect();
			},
			onCancel: function(data, actions) {
				return actions.redirect();
			}
		}, '#paypal-button-container'); */
}


if (typeof currency_code !== 'undefined'  && parseInt(Min_AP_AMT) > 0) {
	currency_code = (currency_code == "EURO") ? "EUR" : currency_code;
	const apConfig = {
		priceSelector: '#pricediv',
		locale: 'en_US',
		currency: currency_code,
		minMaxThreshold: {
		min: Min_AP_AMT,
		max: Max_AP_AMT,
		},
	};
	
	$(document).ready(function(){
		//alert("sasa");
		var val = $("#pricediv").html();
		var priceval = Number(val.replace(/[^0-9.-]+/g,""));
		$("#pricediv").html(priceval.toFixed(2));
		var cmpval = parseFloat(priceval * 100);

		new presentAfterpay(apConfig).init();
		if(cmpval > Min_AP_AMT && cmpval < Max_AP_AMT){

			$("#afterpay_div").show();
			$(".afterpay-paragraph").click(function(){
				window.location= SITE_URL+"afterpay/phoneorder";
			});

			$(".afterpay-paragraph .afterpay-link").click(function(e){
				 e.stopPropagation();
			});
		}else{
			//$("#afterpay_div").hide();
		}
		//remove "or" from text
		
		$(".afterpay-paragraph").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12 pt10 tac fsbold");
		
		if($(".afterpay-text1").length){
			var txt1 = $(".afterpay-text1").html();
			if(txt1 != "" && txt1 != 'undefined'){
				var replacetxt = txt1.replace("or", "");
				$(".afterpay-text1").html(replacetxt);
			}
		}

		if($(".afterpay-link").length){
			$(".afterpay-link").empty().html("<i class='fa fa-info-circle'></i>");
			$(".afterpay-paragraph").on("mouseover",function(){this.style.cursor = "pointer";});
		}
		//remove "or" from text
	});
	
}
$(document).on('click',"#btnAmazonOrder",function(){
	$('#frmamazon').submit();
});

function GetAmazonDetails(AmazonOrderID)
{
	$.ajax({
		type:'POST',
		url:site_url+'amazon/phoneorder-details',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			amazon_order_id: AmazonOrderID
		},
		success:function(data) {
			//success
		}
	});
}
