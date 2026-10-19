let getData;
let orderIDArr = [];
//let orderIDVal;

$(document).ready(function() {
	
	if(typeof hasCashPayment != 'undefined' && hasCashPayment == '1')
	{
		let userAgent = navigator.userAgent;
		if (userAgent.includes('StarWebPRNTBrowser')) {
			
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Star WebPRNT Browser detected");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Order Receipt Page','No');

			setTimeout(onConnect(),1000);
			setTimeout(onPrint(),2000);
		}
		else {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Star WebPRNT Browser NOT detected");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Order Receipt Page','No');
		}
		setTimeout(function(){openCashDrawerPopup();}, 500);
		setupNumberFormatting('pos_received_amount');
	}
	
	if(typeof hasCashPayment == 'undefined' || hasCashPayment != '1' || hasCashPayment == '0'){
		if (($('#getOrderID').text()).includes(",")) {
		orderIDArr = $('#getOrderID').text().split(",");
		}else{
			orderIDArr[0] = $('#getOrderID').text();
		}
		
		let orderIDVal = '';
		for(var o=0;o<orderIDArr.length;o++){
			orderIDVal = orderIDArr[o];
			if(o > 0){
				setTimeout(function(){
					getAllReceiptData(orderIDVal);
				}, 1000, orderIDVal);
			}else{
				getAllReceiptData(orderIDVal);
			}
		}
	}
});

$("#guestmember").click(function(){
	$("#guest-to-member").toggle();
});

$('#frmguestmember').submit(function(){
	if(!$("#termsprivacy").prop('checked'))
	{
		alert('Please indicate that you have read and agree to the Terms and Conditions');
		return false;
	}
	if($('#frmguestmember').valid())
	{
		var password = $("#guest_password").val();
		$.ajax({
			url: site_url+"setguestmember",
			type: "POST",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
			},
			data: {
				password: password,
				action: 'guest_to_member'
			},
			success: function(response) {
				if(response.success == "1")
				{
					$("#maingmdiv").hide();
					$("#messages").html("<div class='alert alert-success'>"+response.message+"</div>");
				} else {
					$("#messages").html("<div class='alert alert-danger'>"+response.message+"</div>")
				}
			}
		});
	}
	return false;
});
$('#frmguestmember').validate({
	rules: {
		guest_password	: { required: true },
		guest_confirm_password : { required: true, equalTo: '#guest_password' }
	},
	message: {
		guest_password	: { required: GetMessage('Register','Password') },
		guest_confirm_password : { required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword')},
	},
	onsubmit: false,
	ignore : '',
	invalidHandler: function(form, validator)
	{
		var errors = validator.numberOfInvalids();
		if (errors)
		{
			for(var i=0;i<errors;i++)
			{
				var message = validator.errorList[i].message;
				var id = $(validator.errorList[i].element).attr('name');
				$("#frmguestmember #error_"+id).html(message);
				$("#frmguestmember #error_"+id).show();
			}
		} else {
			$("#frmguestmember .frmerror").html('');
		}
	},
	errorPlacement: function(error, element)
	{
		// Override error placement to not show error messages beside elements //
	}
});

function openCashDrawerPopup() {
	$('#pos-cashdrawer-popup').modal('show');
	$('body').css('pointer-events', 'none');
}

function cashDrawerChangeSubmit() {
		
	if(IsCashDrawerOpen == 1){
		alert("Please close the cash drawer first");
		return false;
	}
	else{
		if($('#frmCashDrawerChange input[id=pos_received_amount]').val() == '' || $('#frmCashDrawerChange input[id=pos_received_amount]').val() == '$')
		{
			$('#frmCashDrawerChange #success_msg').hide();
			$('#frmCashDrawerChange #error_msg').show();
			$('#frmCashDrawerChange #error_msg').html('Please enter received amount.');
			$('#frmCashDrawerChange input[id=pos_received_amount]').focus();
			return false;
		}

		$('#frmCashDrawerChange #submitCashChangeBtn').prop('disabled', true);

		var data 	= $('#frmCashDrawerChange').serialize();

		var URL_AJAX 		= site_url+'store/save-pos-order-cash-change';
		var STR_POST_VAR 	= data+"&_token=" + $('meta[name="csrf-token"]').attr('content');

		$.ajax({
		  type: 'POST',
		  url: URL_AJAX,
		  data: STR_POST_VAR,
		  cache: false,
		  async: false,
		  dataType: "json",
		  beforeSend: function(){

		  },
		  success: (function(data, textStatus, jqXHR) {

			if(textStatus == 'success' && jqXHR.status == 200)
			{
				$('#frmCashDrawerChange input[name=store_order_cashchange_log_id]').val(data.store_order_cashchange_log_id);
				$('#frmCashDrawerChange #act_type').val('update');
				$('#frmCashDrawerChange #error_msg').hide();
				$('#frmCashDrawerChange #success_msg').show();
				$('#frmCashDrawerChange #success_msg').html('Cash change data submitted successfully.');
				$('body').css('pointer-events', 'auto');
				setTimeout(function(){$("#pos-cashdrawer-popup").modal('hide');},1500);
				
				if (($('#getOrderID').text()).includes(",")) {
					orderIDArr = $('#getOrderID').text().split(",");
				}else{
					orderIDArr[0] = $('#getOrderID').text();
				}
				
				let orderIDVal = '';
				for(var o=0;o<orderIDArr.length;o++){
					orderIDVal = orderIDArr[o];
					if(o > 0){
						setTimeout(function(){
							getAllReceiptData(orderIDVal);
						}, 1000, orderIDVal);
					}else{
						getAllReceiptData(orderIDVal);
					}
				}
				
			}
			else
			{
				$('#frmCashDrawerChange #success_msg').hide();
				$('#frmCashDrawerChange #error_msg').show();
				$('#frmCashDrawerChange #error_msg').html('Sorry, something went wrong, please try again.');
				$('#frmCashDrawerChange #submitCashChangeBtn').prop('disabled', false);
				console.log('Sorry, something went wrong, please try again.');
				return false;
			}

		  }),
		  error: (function(jqXHR, error, errorThrown)
		  {
				console.log(jqXHR, error, errorThrown);
				$('#frmCashDrawerChange #success_msg').hide();
				$('#frmCashDrawerChange #error_msg').show();
				$('#frmCashDrawerChange #error_msg').html('Sorry, something went wrong, please try again.');
				console.log('Sorry, something went wrong, please try again.');
				$('#frmCashDrawerChange #submitCashChangeBtn').prop('disabled', false);
				return false;

		  })
		});
	}
}

if(typeof hasCashPayment != 'undefined' && hasCashPayment == '1')
{
	$('#pos-cashdrawer-popup').on('hidden.bs.modal', function () {
		$('#frmCashDrawerChange #success_msg').html('').addClass('d-none');
		$('#frmCashDrawerChange #error_msg').html('').addClass('d-none');
		$('#frmCashDrawerChange #submitCashChangeBtn').prop('disabled', false);
	});

	function setupNumberFormatting(inputId) {
		const input = document.getElementById(inputId);

		input.addEventListener('input', function(e) {
			let value = e.target.value.replace(/[^0-9.]/g, '');

			const parts = value.split('.');
			if (parts.length > 2) {
				value = parts[0] + '.' + parts.slice(1).join('');
			}

			if (value !== '' && !isNaN(value)) {
				const [integerPart, decimalPart] = value.split('.');
				const formattedInteger = Number(integerPart).toLocaleString('en-US');

				if (decimalPart !== undefined) {
					value = `${formattedInteger}.${decimalPart.slice(0, 2)}`;
				} else {
					value = formattedInteger;
				}
			}

			e.target.value = "$"+value;
		});

		input.addEventListener('blur', function(e) {
			let value = e.target.value.replaceAll(/[^0-9.]/g, '');

			if (value !== '' && !isNaN(value)) {
				value = Number(value).toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}

			e.target.value = "$"+value || '$0.00';
		});
	}

	$('#frmCashDrawerChange input[id=pos_received_amount]').on('change keydown paste input', function(){

		var pos_payable_cash_amount = $('#frmCashDrawerChange #pos_payable_cash_amount').attr('data-amount-value');
		var pos_received_amount = $('#frmCashDrawerChange #pos_received_amount').val();
		pos_received_amount = pos_received_amount.replaceAll(",", "");
		pos_received_amount = pos_received_amount.replaceAll("$", "");

		var diff_val = parseFloat(pos_received_amount - pos_payable_cash_amount);
		diff_val = diff_val.toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
		
		if(diff_val == 'NaN' || diff_val == 'nan' || isNaN(diff_val)){
			diff_val = 0.00;
		}
		
		$('#frmCashDrawerChange #pos_change_amount').text('$' + diff_val);
		$('#frmCashDrawerChange input[name=pos_change_amount]').val(diff_val);
		return;

	});
}

function getAllReceiptData(orderIDVal){
	$.ajax({
		url: site_url+'store/getReceiptData',
		type: "POST",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
		},
		data: {
			//OrderID: $('#getOrderID').text(),
			OrderID: orderIDVal,
		},
		success: function(response) {
			if(response.success == "1")
			{
				getData = response;
				setTimeout(function(){
					onReceiptPrint(getData);
				}, 1000);
			}
			else{
			}
		}
	});
}


function onReceiptPrint(getData) {
	
	var builder = new StarWebPrintBuilder();
	var request = '';
	
    // Initialize document
    request += builder.createInitializationElement();
    
    //request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });

    // Header - Centered Logo (Index 1) and Store Info
    request += builder.createAlignmentElement({position: 'center'});
    request += builder.createLogoElement({number: 1}); // Assumes logo is stored in printer
    request += builder.createTextElement({data: 'MAXAROMA\n', emphasis: true, width: 2, height: 2});
    request += builder.createTextElement({data: 'THE EDIT BOUTIQUE\n', emphasis: true, width: 1, height: 1});
    request += builder.createTextElement({data: getData.storeAddress1+' . '+getData.storePrefix+'\n', width: 1, height: 1});
    request += builder.createTextElement({data: getData.storeAddress2+'\n'});
    //request += builder.createTextElement({data: 'Order #'+getData.orderID+'\n\n', emphasis: true});
    
    request += builder.createTextElement({data: 'maxaroma.com . @maxaroma\n'});
    
    request += builder.createAlignmentElement({position: 'left'});
    request += builder.createTextElement({data: '--------------------------------\n'});
    
	const getDate = new Date();

	// Format the date part: May 15, 2026
	const datePart = getDate.toLocaleDateString('en-US', {
		month: 'long',
		day: 'numeric',
		year: 'numeric'
	});

	// Format the time part: 4:30 PM
	const timePart = getDate.toLocaleTimeString('en-US', {
		hour: 'numeric',
		minute: '2-digit',
		hour12: true
	});

	// Combine with the specific separator
	const formattedDateTime = `${datePart} . ${timePart}`;
	request += builder.createTextElement({data: 'DATE\n'});
	request += builder.createTextElement({data: formattedDateTime+'\n', emphasis: true});
	
	
	request += builder.createTextElement({data: 'Order #'+getData.orderID+'\n', emphasis: true});
	if(getData.storeUserName != ''){
		request += builder.createTextElement({data: 'Assisted by: '+getData.storeUserName+'\n'});
	}
	request += builder.createTextElement({data: '--------------------------------\n'});
	
    
    for (let i = 0; i < getData.orderItems.length; i++) {
		
		request += builder.createAlignmentElement({position: 'left'});
		//request += builder.createTextElement({data: getData.orderItems[i].SKU+' X '+getData.orderItems[i].Qty+'             $'+getData.orderItems[i].ItemTotal+'\n'});
		request += builder.createTextElement({data: getData.orderItems[i].SKU+' X '+getData.orderItems[i].Qty+'\n'});
		
		request += builder.createAlignmentElement({position: 'left'});
		request += builder.createTextElement({data: getData.orderItems[i].PName+'\n'});
		
		request += builder.createAlignmentElement({position: 'right'});
		request += builder.createTextElement({data: '$'+getData.orderItems[i].ItemTotal+'\n'});
	}
    
    request += builder.createAlignmentElement({position: 'left'});
    request += builder.createTextElement({data: '--------------------------------\n'});

    // Footer - Totals
	
    request += builder.createAlignmentElement({position: 'right'});
    
    request += builder.createTextElement({data: 'Sub Total: $'+getData.subTotal+'\n'});
    
    if(getData.shippingAmt > 0){
		request += builder.createTextElement({data: 'Shipping Charge: $'+getData.shippingAmt+'\n'});
	}
	if(getData.tax > 0){
		request += builder.createTextElement({data: 'Sales Tax: $'+getData.tax+'\n'});
	}
	if(getData.giftCharge > 0){
		request += builder.createTextElement({data: 'Gift Wrapping Charge: $'+getData.tax+'\n'});
	}
	if(getData.routeShippingInsuranceCharge > 0){
		request += builder.createTextElement({data: 'Shipping Insurance Charge: $'+getData.routeShippingInsuranceCharge+'\n'});
	}
	if(getData.shippingSignature > 0){
		request += builder.createTextElement({data: 'Shipping Signature: $'+getData.shippingSignature+'\n'});
	}
	
	if(getData.autoDiscount > 0){
		request += builder.createTextElement({data: AutoDiscountLabel+': $'+getData.autoDiscount+'\n'});
	}
	if(getData.quantityDiscount > 0){
		request += builder.createTextElement({data: QuantityDiscountLabel+': $'+getData.quantityDiscount+'\n'});
	}
	if(getData.couponAmount > 0){
		request += builder.createTextElement({data: 'Coupon Discount: $'+getData.couponAmount+'\n'});
	}
	if(getData.gcAmount > 0){
		request += builder.createTextElement({data: 'Gift Certificate Discount: $'+getData.gcAmount+'\n'});
	}
	if(getData.referAmount > 0){
		request += builder.createTextElement({data: 'Refer Discount: $'+getData.referAmount+'\n'});
	}
	if(getData.bogoDiscount > 0){
		request += builder.createTextElement({data: BogoDiscountLabel+': $'+getData.bogoDiscount+'\n'});
	}
	if(getData.applyCredit > 0){
		request += builder.createTextElement({data: 'Credit Discount: $'+getData.applyCredit+'\n'});
	}
	if(getData.rewardDiscount > 0){
		request += builder.createTextElement({data: 'Yotpo Reward Discount: $'+getData.rewardDiscount+'\n'});
	}
	
    request += builder.createTextElement({data: 'Total: $'+getData.orderTotal+'\n\n', width: 2, height: 2, emphasis: true}); // Double-sized text
    
    request += builder.createTextElement({data: '--------------------------------\n', width: 1, height: 1});
	request += builder.createAlignmentElement({position: 'center'});
	request += builder.createTextElement({data: 'Discover the world\'s most \n', width: 1, height: 1, emphasis: true});
	request += builder.createTextElement({data: 'extraordinary fragrances. \n\n', width: 1, height: 1, emphasis: true});
	request += builder.createTextElement({data: 'Thank you for visiting the Edit.\n', width: 1, height: 1});
	
    // Cut Paper
    request += builder.createCutPaperElement({feed: true});

	 if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' +request);
	}
	
    sendReceiptMessage(request);
}

function sendReceiptMessage(request) {
    var url = "http://localhost:8001/StarWebPRNT/SendMessage";////THIS IS THE ONLY URL THAT WILL WORK TO SEND THE CASHDRAWER OPEN MESSAGE TO THE mPOP.

    // var trader = new StarWebPrintTrader({ url: url, papertype: papertype });
    var trader = new StarWebPrintTrader({ url: url });
    if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' +JSON.stringify(trader));
	}

    trader.onReceive = function (response) {
        var msg = 'onReceive {\n';

        msg += '\tRequestSuccess : [ ' + response.traderSuccess + ' ]\n';

        //      msg += 'TraderCode : [ ' + response.traderCode + ' ]\n';

        msg += '\tRequestStatus : [ ' + response.traderStatus + ',\n';

        // Any Cashdrawer or Print request will be successfully received by Printer if and only if response.traderStatus = 0 and response.traderSuccess = 'true'

        // response.traderStatus possible values:- (https://www.star-m.jp/products/s_print/sdk/webprnt/manual/en/_StarWebPrintTrader-js.htm#onReceive)
        /**
         * 
            0 … Success
            1100 … Printer off line(On error)
            2001 … Printer busy(In use with another device: Need to resend data)
         */

        if (trader.isCoverOpen({ traderStatus: response.traderStatus })) { msg += '\t\tCoverOpen,\n'; }
        if (trader.isOffLine({ traderStatus: response.traderStatus })) { msg += '\t\tOffLine,\n'; }
        if (trader.isCompulsionSwitchClose({ traderStatus: response.traderStatus })) { msg += '\t\tCompulsionSwitchClose,\n'; }
        if (trader.isEtbCommandExecute({ traderStatus: response.traderStatus })) { msg += '\t\tEtbCommandExecute,\n'; }
        if (trader.isHighTemperatureStop({ traderStatus: response.traderStatus })) { msg += '\t\tHighTemperatureStop,\n'; }
        if (trader.isNonRecoverableError({ traderStatus: response.traderStatus })) { msg += '\t\tNonRecoverableError,\n'; }
        if (trader.isAutoCutterError({ traderStatus: response.traderStatus })) { msg += '\t\tAutoCutterError,\n'; }
        if (trader.isBlackMarkError({ traderStatus: response.traderStatus })) { msg += '\t\tBlackMarkError,\n'; }
        if (trader.isPaperEnd({ traderStatus: response.traderStatus })) { msg += '\t\tPaperEnd,\n'; }
        if (trader.isPaperNearEnd({ traderStatus: response.traderStatus })) { msg += '\t\tPaperNearEnd,\n'; }
        if (trader.isPaperPresent({ traderStatus: response.traderStatus })) { msg += '\t\tPaperPresent,\n'; }
        if (trader.isRollPositionError({ traderStatus: response.traderStatus })) { msg += '\t\tRollPositionError,\n'; }
        msg += '\t\tEtbCounter = ' + trader.extractionEtbCounter({ traderStatus: response.traderStatus }).toString() + ' ]\n';

        msg += ' }';
        
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' +msg);
		}
    }

    trader.onError = function (response) {
        var msg = 'onError {\n';

        msg += '\tStatus:' + response.status + '\n';

        msg += '\tResponseText:' + response.responseText + '\n';

        msg += ' }';
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' +msg);
		}
    }

    trader.sendMessage({ request: request, url });

    // TO CONFIRM IF THE CASHDRAWER REQUEST WE SEND HERE IS PROPERLY RECEIVED BY mPOP, WE NEED TO CHECK THE RESPONSE RECEIVED IN onReceive CALLBACK FUNCTION. SEARCH FOR 'response.traderStatus' in the trader.onReceive CALLBACK FUNCTION.
}
