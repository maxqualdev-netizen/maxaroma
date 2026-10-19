$("#page-spinner").show();
$(document).ready(function(){
	$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 500);
	$("#page-spinner").hide();
})
$(document).on('click','#btnbill_step1',function(){
	if(FrmValidate())
	{
		SetBilling();
		$(".checkout-page").attr('data-section',0);
	} else {
		$('html, body').animate({
			scrollTop: $(".checkout-steps").offset().top - 300
		}, 2000);
	}
});
$("#down-arrow").click(function(){
	$("#cart_items").toggle();
	if($("#down-arrow").hasClass('cart-toggle'))
		$("#down-arrow").removeClass('cart-toggle');
	else
		$("#down-arrow").addClass('cart-toggle');
});
$(document).on('click',"#giftcard-down-arraw",function(){
	$("#divgiftcard").toggle();
	if($("#giftcard-down-arraw").hasClass('cart-toggle'))
		$("#giftcard-down-arraw").removeClass('cart-toggle');
	else
		$("#giftcard-down-arraw").addClass('cart-toggle');
})
$(document).on('click','.shipmethod',function(){
	//SetShippingMethod();
});
$(document).on('change','.shipmethod',function(){
	SetShippingMethod();
});
$(document).on('click','#btnappygiftcard',function(){
	ApplyFreeGift();
})
$(document).on('click','#btnPlaceOrder',function(){
	
	if($('input[name=PaymentMethod]:checked').length == 0)
	{
		alert('Please select payment method.');
		return false;
	}
	if(!$("#chkagree").prop('checked'))
	{
		alert('Please indicate that you have read and agree to the Terms and Conditions');
		return false;
	} else {
		$("#order_process").submit();
	}
});

$(document).on('click',"#btnAmazonOrder",function(){
	var page_from = "";
	if($("#page_from").length > 0 && $("#page_from").val() == 'fund')
		page_from = "fund";
	
	if($('.shipmethod:checked').length == 0 && page_from == '')
	{
		alert('Please select shipping method.');
		return false;
	}
	$('#frmamazon').submit();
});

$(document).on('change',"#bill_country",function(){
	if($(this).val() != 'US') {
		$("#divstate").hide();
		$("#divotherstate").show();
	} else {
		$("#divotherstate").hide();
		$("#divstate").show();
	}
});
$(document).on('click',"#chksamebill",function(){
	if($(this).prop('checked'))
		$("#frmshipping").hide();
	else
		$("#frmshipping").show();
});
$(document).on('click','#btnbill_step2',function(){
	if($("#vendor-popup").val() != '')
	{
		$("#VendorText").html($("#vendor-popup").val());
		$('#VendorItemPopup').modal('show');
	}
	$(".progress li").removeClass('active');
	$("#step3").addClass('active');
	$("#shipinfo").show();
	$(".shipping-method").hide();
	$(".billtab").removeClass('active');
	$("#shipinfo").removeClass('active');
	$(".payment-method").addClass('active');
	GetPaymentMethods();
	$(".checkout-page").attr('data-section',0);
})
$(document).on('click','.billback',function(){
	var CurrentSection = parseInt($(".checkout-page").attr('data-section'));
	if (CurrentSection == 0 || CurrentSection > 1)
	{
		$(".progress li").removeClass('active');
		$("#step1").addClass('active');
		$(".bill-info").show();
		$("#billshipinfo").hide();
		$(".billtab").removeClass('active');
		$(".bill-info").addClass('active');
		$("#shipping-method").hide();
		$("#payment-method").hide();
		GetShippingMethods(1);
		GetPaymentMethods(1);
		$(".checkout-page").attr('data-section',2);
	}
});
$(document).on('click','.shipback',function(){
	var CurrentSection = parseInt($(".checkout-page").attr('data-section'));
	if (CurrentSection == 0 || CurrentSection >= 2)
	{
		$(".progress li").removeClass('active');
		$("#step2").addClass('active');
		$(".shipping-method").show();
		$("#shipinfo").hide();
		$(".billtab").removeClass('active');
		$(".shipping-method").addClass('active');
		GetShippingMethods();
		GetPaymentMethods(1);
	}
});
$(document).on('click',".guestback",function(){
	$("#guest2").hide();
	$("#guest1").show();
	$(".billtab").removeClass('active');
	$("#billshipinfo").show();
	$(".bill-info").hide();
	$("#shipping-method").hide();
	$("#payment-method").hide()
	GetShippingMethods(1);
	GetPaymentMethods(1);
	$(".checkout-page").attr('data-section',1);
});
$(document).on('click','#shipping_signature',function(){
	//route_shipping_insurance();
	SetShippingInsuranceCharge();
});
$(document).on('change',"#shipinsurance", function(){
	SetShippingInsuranceCharge();
})

$(document).on('click','#btnguest',function(){
	CheckMember();
	$(".checkout-page").attr('data-section',0);
});
$(document).on('click','#chkcreditlimit',function(){
	ApplyCreditLimit();
	
});
$(document).on('click',"#btnasguest",function(){
	$('#formguest').validate({
		rules: {
			guest_email: { required: true,email: true  },
		},
		messages: {
			guest_email: { required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') }
		},
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#formguest #error_"+id).html(message);
					$("#formguest #error_"+id).show();
				}
			} else {
				   $("#formguest .frmerror").html('');
			}
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //
		}
	});
	
	if($('#formguest').valid())
	{
		var email = $("#guest_email").val();
		$("#gemail").html(email);
		$("#bill_email").val(email);
		$("#guest1").hide();
		$("#guest2").show();
		$("#billshipinfo").hide();
		$(".bill-info").addClass('active');
		$(".bill-info").show();
		$(".checkout-page").attr('data-section',2);
	}
})

$(document).on('click','.chkhead',function(){
	$(".chkhead").removeClass('chkactive');
	if($(this).attr('id') == 'returning')
	{
		$(this).addClass('chkactive');
		$("#divguest").hide();
		$("#divreturning").show();
	} else {
		$(this).addClass('chkactive');
		$("#divreturning").hide();
		$("#divguest").show();
	}
})

function ApplyCreditLimit()
{
	var token = $('meta[name="csrf-token"]').attr('content');
	var check = 0;
	var onlyGCPurchased = $("#onlyGCPurchased").val();
	
	var ShipInsCharge = 'no';
	if($("#shipinsurance").prop('checked'))
		ShipInsCharge = 'yes';
	
	$("#page-spinner").show();
	if($("#chkcreditlimit").prop('checked'))
		check = 1;
	$.ajax({
		type:'POST',
		url:site_url+'paymentinfo',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			action : 'setcreditlimit',
			check : check,
			onlyGCPurchased : onlyGCPurchased,
			ShipInsCharge : ShipInsCharge,
		},
		success:function(data) {
			if(data)
			{ 
				$("#cartsubtotal").html(data.CheckoutBoxHTML);
				$("#credit-limit").html(data.CreditLimitBoxHTML);
				$("#credamt").html(data.RemainCreditLimit);
				if(data.NetTotal == 0)
				{
					$("#PaymentDetailsStoreView").attr('style','display:inline-block !important');
					$(".credit-methods").attr('style','display:none !important');
					$("input[name=PaymentMethod]").prop('checked',false);
					$("#paytypeIDCL1").prop('checked',true);
				} else {
					$("#PaymentDetailsStoreView").attr('style','display:none !important');
					$(".credit-methods").attr('style','display:inline-block !important');
					$("#paytypeIDCL1").prop('checked',false);
				}
				$("#page-spinner").hide();
			}
		}
	});
}
function CheckMember()
{
	$('#formguest').validate({
		rules: {
			guest_email: { required: true,email: true  },
		},
		messages: {
			guest_email: { required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') }
		},
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#formguest #error_"+id).html(message);
					$("#formguest #error_"+id).show();
				}
			} else {
				   $("#formguest .frmerror").html('');
			}
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //
		}
	});
	
	if($('#formguest').valid())
	{
		var token = $('meta[name="csrf-token"]').attr('content');
		var email = $("#guest_email").val();
		$.ajax({
			type:'POST',
			url:site_url+'checkmember',
			headers: {
				'X-CSRF-TOKEN': token
			},
			datatype: 'JSON',
			data:{
				action : 'chkmember',
				email : email
			},
			success:function(data) {
				if(data)
				{
					//$("#error_guest_email").html('You already have an account with us, please <a href="'+site_url+'login.html">login</a> or continue as guest.');
					$("#error_guest_email").html('You have an account, <a href="'+site_url+'login.html">Sign in HERE</a>.');
					$("#error_guest_email").show();
					$("#btnguest").hide();
					$("#btnasguest").show();
				} else {
					$("#btnasguest").click();
				}
			}
		});
	}
}
function route_shipping_insurance(){
	//Live-2b3983e3-6b7d-45e4-bc21-a4ee1db24609	
	routeapp.get_quote('2b3983e3-6b7d-45e4-bc21-a4ee1db24609', $("#max_hidsubtotal").val(), 'USD', function(data){
		update_route_shipping_action(data);
	});	
	routeapp.on_insured_change(function (data){
		update_route_shipping_action(data);
	})	
}
function update_route_shipping_action(data){	
	if(data.insurance_selected == true){		
		$(".route-widget .rw-contents .rw-right .rw-checkbox-span.rw-checked").css("background-color","#000 !important");
		$("#shipping_insurance").prop("checked","checked");
		$("#shipping_insurance_charge").val(data.insurance_price);
		$("#shipping_signature").removeAttr("checked");
		$("#shipping_signature_div").hide();		
		SetShippingInsuranceCharge();
	} else {		
		$(".route-widget .rw-contents .rw-right .rw-checkbox-span.rw-unchecked").css("background-color","#f3f3f3 !important");
		$("#shipping_insurance").removeAttr("checked");
		$("#shipping_insurance_charge").val("");
		$("#shipping_signature_div").show();
		SetShippingInsuranceCharge();
	}
}

function GetShippingMethods(OnlyHead=0,PageFrom='')
{	
	var IsVenderItem = $("#IsVenderItem").val();
	var IsCosmo = $("#IsCosmo").val();
	var IsNandansons = $("#IsNandansons").val();
	var IsPerfumePW = $("#IsPerfumePW").val();
	var IsPCA = $("#IsPCA").val();
	var IsMaxaromaTwoDelivery = $("#IsMaxaromaTwoDelivery").val();
	var onlyGCPurchased = $("#onlyGCPurchased").val();
	var ISMaxTwoItem = $("#ISMaxTwoItem").val();
	var token = $('meta[name="csrf-token"]').attr('content');
	$("#page-spinner").show();
	$.ajax({
		type:'POST',
		url:site_url+'shippinginfo',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			action : 'shippinginfo',
			OnlyHead : OnlyHead,
			PageFrom : PageFrom,
			IsVenderItem: IsVenderItem,
			IsCosmo: IsCosmo,
			IsNandansons: IsNandansons,
			IsPerfumePW: IsPerfumePW,
			IsPCA: IsPCA,
			IsMaxaromaTwoDelivery: IsMaxaromaTwoDelivery,
			ISMaxTwoItem : ISMaxTwoItem,
			onlyGCPurchased: onlyGCPurchased,
		},
		success:function(data) {
			$(".shipping-method").html(data.ShipMethodsHtml);	
			$("#cartsubtotal").html(data.CheckoutBoxHTML);	
			$("#shipinfo").hide();
			$(".shipping-method").show();
			$("#page-spinner").hide();
			//SetShippingMethod();
			$('html, body').animate({
				scrollTop: $(".shipping-method").offset().top - 100
			}, 2000);
			/*setTimeout(function(){
				route_shipping_insurance();
			},1000);*/
		}
	});
}

function GetAmazonDetails(AmazonOrderID)
{
	$.ajax({
		type:'POST',
		url:site_url+'amazon/order-details',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			amazon_order_id: AmazonOrderID
		},
		success:function(data) {
			if(data){
				GetShippingMethods(0,'amazon_billing');
			}
		}
	});
}

function SetShippingMethod()
{
	var token = $('meta[name="csrf-token"]').attr('content');
	var ShipMethodID = $('.shipmethod:checked').val();
	var IsVenderItem = $("#IsVenderItem").val();
	var IsCosmo = $("#IsCosmo").val();
	var IsNandansons = $("#IsNandansons").val();
	var IsPerfumePW = $("#IsPerfumePW").val();
	var IsPCA = $("#IsPCA").val();
	var onlyGCPurchased = $("#onlyGCPurchased").val();
	$("#page-spinner").show();
	$(".clsship").removeClass('active');
	var SelShip = $('.shipmethod:checked').attr('data-key');
	$("#method-"+SelShip).addClass('active');
	$.ajax({
		type:'POST',
		url:site_url+'setshipmethod',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			ShipMethodID: ShipMethodID,
			IsVenderItem: IsVenderItem,
			IsCosmo: IsCosmo,
			IsNandansons: IsNandansons,
			IsPerfumePW: IsPerfumePW,
			IsPCA: IsPCA,
			onlyGCPurchased: onlyGCPurchased,
		},
		success:function(data) {
			$("#cartsubtotal").html(data.SubTotalBox);	
			$(".shipinsprice").html(data.ShipInsCharge);
			$("#vendor-popup").val(data.VendorPopup);
			$("#page-spinner").hide();
		},
	});
}

function SetShippingInsuranceCharge()
{
	/*
	var token = $('meta[name="csrf-token"]').attr('content');
	
	if($("#shipping_signature").is(":checked")==true){
		$("#slider_round").text("On").removeClass("text_off").addClass("text_on");	
	} else {
		$("#slider_round").text("Off").removeClass("text_on").addClass("text_off");	
	}
	
	var shipping_insurance = "N";
	var shipping_insurance_charge = "";
	if($("#shipping_insurance").is(":checked")==true){
		shipping_insurance = "Y";	
		shipping_insurance_charge = $("#shipping_insurance_charge").val();
	}
	
	$(".rdate").removeClass("active");
	var  shipping_mode_id = $('input[name=shippingModeId]:checked').val();

	$("#rdateSel"+shipping_mode_id).addClass("active");
	
	var est_date = $("#rdateSel"+shipping_mode_id).next("input[name='shippingModeId']").attr("data-estidate");
	$("#EstimatedDeliveryDate").val(est_date);
	
	var   shipping_signature = "";
	if($("#shipping_signature").prop("checked")== true)
	{
		shipping_signature = $('input[name=shipping_signature]:checked').val();
	}*/
	$("#page-spinner").show();
	var insuramt = $("#divshipcerty").attr('data-amt');
	insuramt = parseFloat(insuramt);
	if(insuramt >= 200)
		$("#shipcerty_info").show();
	else
		$("#shipcerty_info").hide();
	
	/*
	if($("#shipping_signature").is(":checked")==true){
		$("#slider_round").text("On").removeClass("text_off").addClass("text_on");	
	} else {
		$("#slider_round").text("Off").removeClass("text_on").addClass("text_off");	
	}
	*/
	var subaction = 'add';
	if($("#shipinsurance").prop('checked')){
		$("#shipinsurance").parent().addClass('active');
		subaction = 'add';
		if(insuramt < 200){
			$("#divshipcerty").hide();
			$("#shipping_signature").prop("checked",false);
		}
		//$("#shipping_signature").removeAttr("checked");
		//$("#shipping_signature_div").hide();
	}else{
		$("#shipinsurance").parent().removeClass('active');
		subaction = 'remove';
		if(insuramt < 200)
			$("#divshipcerty").show();
		//$("#shipping_insurance_charge").val("");
		//$("#shipping_signature_div").show();
	}
	var   shipping_signature = "";
	if($("#shipping_signature").prop("checked")== true)
	{
		//shipping_signature = $('input[name=shipping_signature]:checked').val();
		shipping_signature = $('#shipping_signature').attr('data-value');
		shipping_signature = parseFloat(shipping_signature.trim());
		$("#shipping_signature").parent().addClass('active');
	} else{
		$("#shipping_signature").parent().removeClass('active');
	}
	
	$.ajax({
		type:'POST',
		url:site_url+'shippinginfo',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			action : 'setshippinginsurance',
			subaction : subaction,
			shipping_signature: shipping_signature,
			/*ShippingInsuranceMethod : shipping_insurance,
			ShippingInsuranceCharge : shipping_insurance_charge,*/
		},
		success:function(data) {
			$("#cartsubtotal").html(data);	
			$("#page-spinner").hide();
		}
	});
}
function GetPaymentMethods(OnlyHead=0)
{
	var token = $('meta[name="csrf-token"]').attr('content');
	var SelPayMethod = $("#SelPayMethod").val();
	
	var ShipInsCharge = 'no';
	if($("#shipinsurance").prop('checked'))
		ShipInsCharge = 'yes';
	
	$("#page-spinner").show();
	$.ajax({
		type:'POST',
		url:site_url+'paymentinfo',
		headers: {
			'X-CSRF-TOKEN': token
		},
		datatype: 'JSON',
		data:{
			action : 'paymentinfo',
			OnlyHead : OnlyHead,
			SelPayMethod : SelPayMethod,
			ShipInsCharge : ShipInsCharge,
		},
		success:function(data) {
			if(data.ShipInfo)
				$("#shipinfo").html(data.ShipInfo);	
			$(".payment-method").html(data.PayMethods);	
			AfterpaySetup();
			$("#page-spinner").hide();
			$('html, body').animate({
				scrollTop: $(".payment-method").offset().top - 100
			}, 2000);
		}
	});
}

function FrmValidate()
{
	if(!$('#chksamebill').prop('checked'))
	{
		var Bill = ValidBilling();
		var Ship = ValidShipping();
		if(Bill && Ship)
			return true;
		else 
			return false;
	} else {
		return ValidBilling();
	}
	
}
function ValidBilling()
{
	$("#frmbilling .frmerror").hide();
	var Rules = {
			bill_fname: { required: true  },
			bill_lname: { required: true  },
			bill_address1: { required: true  },
			bill_city: { required: true  },
			bill_zip: { required: true  },
			bill_phone: { required: true  },
			bill_email: { required: true,email: true },
			//bill_cemail: { required: true, equalTo: '#bill_email'},
			bill_state: { required: function() { return $("#bill_country").val() == "US"}},
			bill_other_state: { required:function() { return $("#bill_country").val() != "US" }}
		};
	var Messages = {
			bill_fname: 	{ required: GetMessage('Validate','FirstName') },
			bill_lname: 	{ required: GetMessage('Validate','LastName') },
			bill_address1: 	{ required: GetMessage('Validate','Address') },
			bill_city: 		{ required: GetMessage('Validate','City') },
			bill_zip: 		{ required: GetMessage('Validate','ZipCode') },
			bill_phone: 	{ required: GetMessage('Validate','Phone') },
			bill_email: 	{ required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
			//bill_cemail: 	{ required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword') },
			bill_state: 	{ required: GetMessage('Validate','State') },
			bill_other_state: 	{ required: GetMessage('Validate','OtherState') },
			guest_password	: { required: GetMessage('Register','Password') },
			guest_confirm_password : { required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword')},
		};
	
	$('#frmbilling').validate({
		rules: Rules,
		messages: Messages,
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmbilling #error_"+id).html(message);
					$("#frmbilling #error_"+id).show();
				}
			} else {
				   $("#frmbilling .frmerror").html('');
		   }
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //
		}	
	});
	$('#frmbilling').validate();
	if($("#chkflag").length > 0 && $("#chkflag").val() == 'guest')
	{
		var guest_password = $("#guest_password").val();
		guest_password = guest_password.trim();
		var guest_confirm_password = $("#guest_confirm_password").val();
		guest_confirm_password = guest_confirm_password.trim();
		if(guest_password != '' || guest_confirm_password != '')
		{
			$("#guest_password").rules('add',{ required: true  });
			//$("#guest_password").messages('add',{ required: GetMessage('Register','Password') });
			
			$("#guest_confirm_password").rules('add',{ required: true, equalTo: '#guest_password' });
			//$("#guest_confirm_password").messages('add',{ required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword')});
			/*Rules.guest_password = { required: true  };
			Rules.guest_confirm_password = { required: true, equalTo: '#guest_password' };
			Messages.guest_password = { required: GetMessage('Register','Password') };
			Messages.guest_confirm_password = { required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword')};
			*/
		} else {
			$("#guest_password").rules('remove', 'required');
			$("#guest_confirm_password").rules('remove', 'required equalTo' );
			
			/*delete Rules['guest_password'];
			delete Rules['guest_confirm_password'];
			delete Messages['guest_password'];
			delete Messages['guest_confirm_password'];
			*/
		}
	}
	return $('#frmbilling').valid();
}

function ValidShipping()
{
	$('#frmshipping').validate({
		rules: {
			ship_fname: { required: true  },
			ship_lname: { required: true  },
			ship_address1: { required: true  },
			ship_city: { required: true  },
			ship_zip: { required: true  },
			ship_phone: { required: true  },
			ship_email: { required: true,email: true },
			ship_state: { required: function() { return $("#ship_country").val() == "US"}},
			ship_other_state: { required:function() { return $("#ship_country").val() != "US" }}
		},
		messages:{
			ship_fname: 	{ required: GetMessage('Validate','FirstName') },
			ship_lname: 	{ required: GetMessage('Validate','LastName') },
			ship_address1: 	{ required: GetMessage('Validate','Address') },
			ship_city: 		{ required: GetMessage('Validate','City') },
			ship_zip: 		{ required: GetMessage('Validate','ZipCode') },
			ship_phone: 	{ required: GetMessage('Validate','Phone') },
			ship_email: 	{ required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
			ship_state: 	{ required: GetMessage('Validate','State') },
			ship_other_state: 	{ required: GetMessage('Validate','OtherState') }
		},
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmshipping #error_"+id).html(message);
					$("#frmshipping #error_"+id).show();
				}
			} else {
				   $("#frmshipping .frmerror").html('');
		   }
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //
		}	
	});
	return $('#frmshipping').valid();
}
function SetBilling()
{
	$("#page-spinner").show();
	var token = $('meta[name="csrf-token"]').attr('content');
	var frmdata = Array();
	if($('#chksamebill').prop('checked'))
		frmdata = $("#frmbilling").serialize() + '&sameasbill=Yes';
	else 
		frmdata = $("#frmbilling, #frmshipping").serialize() + '&sameasbill=No';
	
	if($("#chknews").prop('checked'))
		frmdata+='&newsletter=Yes'; 
	frmdata+= '&action=setbilling';
	$.ajax({
		type:'POST',
		url:site_url+'setbilling',
		headers: {
			'X-CSRF-TOKEN': token
		},
		contentType: 'application/x-www-form-urlencoded',
		dataType: 'text',
		data:frmdata,
		success:function(data) {
			$(".progress li").removeClass('active');
			$("#step2").addClass('active');
			$("#billshipinfo").show();
			$(".bill-info").hide();
			$(".billtab").removeClass('active');
			$(".shipping-method").addClass('active');
			$("#billshipinfo").html(data);
			GetShippingMethods();
		}
	});
}

function ApplyFreeGift()
{
	var token = $('meta[name="csrf-token"]').attr('content');
	$('#frmgiftcard').validate({
		rules: {
			gift_from: {required: true},
			gift_to: {required: true},
			gift_message_customer: {required: true},
			freegiftvalue: { required: true },
		},
		messages: {
			gift_from: "Please enter from value.",
			gift_to: "Please enter to value.",
			gift_message_customer: "Please enter gift value.",
			freegiftvalue: "Please select free gift.",
		},
		onsubmit: false,
		invalidHandler: function(form, validator) 
		{
			var errors = validator.numberOfInvalids();
			if (errors) 
			{
				for(var i=0;i<errors;i++)
				{
					var message = validator.errorList[i].message;
					var id = $(validator.errorList[i].element).attr('name');
					$("#frmgiftcard #error_"+id).html(message);
					$("#frmgiftcard #error_"+id).show();
				}
			} else {
				   $("#frmgiftcard .frmerror").html('');
		   }
		},
		errorPlacement: function(error, element) 
		{
			// Override error placement to not show error messages beside elements //
		}
		
	 });
	if($('#frmgiftcard').valid()) {
		var GiftFrom = $("#gift_from").val();
		var GiftTo = $("#gift_to").val();
		var GiftMessage = $("#gift_message_customer").val();
		var GiftValue = $("#freegiftvalue").val();
		
		$.ajax({
			type: 'POST',
			url: site_url + 'cart',
			headers: {
				'X-CSRF-TOKEN': token
			},
			datatype: 'JSON',
			data: {
				action: 'apply_free_gift',
				GiftFrom: GiftFrom,
				GiftTo: GiftTo,
				GiftMessage: GiftMessage,
				GiftValue: GiftValue,
			},
			success: function (data) {
				if(data.success == '1')
					alert("Free Gift is applied successfully");
				else
					alert("Free Gift is not applied");
			}
		});
	}
}