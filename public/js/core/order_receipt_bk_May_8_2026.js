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
if(typeof hasCashPayment != 'undefined' && hasCashPayment == '1')
{
	function openCashDrawerPopup() {
		$('#pos-cashdrawer-popup').modal('show');
	}

	setTimeout(function(){openCashDrawerPopup();}, 500);

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
    setupNumberFormatting('pos_received_amount');

	function cashDrawerChangeSubmit() {
		
		if(IsCashDrawerOpen == 1){
			alert("Please close the cash drawer first");
			return false;
		}
		else{
			if($('#frmCashDrawerChange input[id=pos_received_amount]').val() == '' || $('#frmCashDrawerChange input[id=pos_received_amount]').val() == '$')
			{
				//$('#frmCashDrawerChange #success_msg').html('').addClass('d-none');
				//$('#frmCashDrawerChange #error_msg').html('Please enter received amount.').removeClass('d-none');
				
				$('#frmCashDrawerChange #success_msg').hide();
				$('#frmCashDrawerChange #error_msg').show();
				$('#frmCashDrawerChange #error_msg').html('Please enter received amount.');
				$('#frmCashDrawerChange input[id=pos_received_amount]').focus();
				return false;
			}

			//$('#frmCashDrawerChange #success_msg').html('').addClass('d-none');
			//$('#frmCashDrawerChange #error_msg').html('').addClass('d-none');
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

				//console.log(data, textStatus, jqXHR);

				if(textStatus == 'success' && jqXHR.status == 200)
				{
					$('#frmCashDrawerChange input[name=store_order_cashchange_log_id]').val(data.store_order_cashchange_log_id);
					$('#frmCashDrawerChange #act_type').val('update');
					$('#frmCashDrawerChange #error_msg').hide();
					$('#frmCashDrawerChange #success_msg').show();
					//$('#frmCashDrawerChange #success_msg').html('Cash change data submitted successfully.').removeClass('d-none');
					$('#frmCashDrawerChange #success_msg').html('Cash change data submitted successfully.');
					//settime$('#pos-cashdrawer-popup').modal('hide');
					setTimeout(function(){$("#pos-cashdrawer-popup").modal('hide');},1500);
				}
				else
				{
					$('#frmCashDrawerChange #success_msg').hide();
					$('#frmCashDrawerChange #error_msg').show();
					//$('#frmCashDrawerChange #error_msg').html('Sorry, something went wrong, please try again.').removeClass('d-none');
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
					//$('#frmCashDrawerChange #error_msg').html('Sorry, something went wrong, please try again.').removeClass('d-none');
					$('#frmCashDrawerChange #error_msg').html('Sorry, something went wrong, please try again.');
					console.log('Sorry, something went wrong, please try again.');
					$('#frmCashDrawerChange #submitCashChangeBtn').prop('disabled', false);
					return false;

			  })
			});

			//valid_cartform('addcart'); return false;
		}
	}

}

$(document).ready(function() {
	//onPrint();
});

function onPrint() {
    var builder = new StarWebPrintBuilder();
	if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' +JSON.stringify(builder));
	}
	
    var request = '';

    request += builder.createInitializationElement();

    // Cashdrawer channel should be 1, on off in milliseconds
    request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });
    request += builder.createTextElement({
		characterspace: 0,
		codepage: 'utf8',
		//font: 'fontA',
		//emphasis: true,
		height: 2,
		data: 'ORDER CONFIRMED\n'
	});
	request += builder.createTextElement({ data: 'Item 1: $10.00\n' });
	request += builder.createCutPaperElement({ feed: true, type: 'partial' });
	if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' +request);
	}
	
    sendMessage(request);
}

function sendMessage(request) {
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
