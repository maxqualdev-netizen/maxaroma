let getData;

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
		$('body').css('pointer-events', 'none');
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
					$('body').css('pointer-events', 'auto');
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
	/*$.ajax({
		url: site_url+'store/getReceiptData',
		type: "POST",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
		},
		data: {
			OrderID: $('#getOrderID').text(),
		},
		success: function(response) {
			if(response.success == "1")
			{
				getData = response;
				setTimeout(function(){
					onPrint(getData);
				}, 3000);
			}
			else{
			}
		}
	});*/
	
	/*var receiptStr = '';
	console.log("Test - "+getData);
	receiptStr += '<div id="receipt-container" style="width: 300px; font-family: monospace;"><div style="text-align: center;"><h2>MAXAROMA</h2><p>123 Street, City</p></div><hr><table>';
	
	for (let i = 0; i < getData.orderItems.length; i++) {
		receiptStr += '<tr><td>'+getData.orderItems.SKU+'</td><td style="text-align: right;">$'+getData.orderItems.ItemTotal+'</td></tr>';
	}
	receiptStr += '</table><hr><div style="text-align: right;"><strong>Total: $'+getData.orderTotal+'</strong></div></div>';
	
	document.body.innerHTML = receiptStr;*/
	
	
	
	//document.body.innerHTML += '<div id="receipt-container" style="position: absolute;left: -9999px;top: 0;display: block;"><style>body{width:302px;margin:0 auto;padding:10px;font-family:monospace;font-size:12px;color:#000;}.center{text-align:center;}.left{text-align:left;}.right{text-align:right;}.bold{font-weight:bold;}.line{border-top:1px dashed #000;margin:8px 0;}table{width:100%;border-collapse:collapse;}th, td{padding:2px 0;}th{text-align:left;}.total-box{font-size:20px;font-weight:bold;}.barcode{text-align:center;margin-top:10px;}.barcode div{font-size:28px;letter-spacing:3px;}@media print{body{width:302px;}@page{margin:0;}}</style><div class="center bold"><img src="https://store.maxaroma.com/public/images/logo.jpg" width="100"><br><br><br></div><div class="center">MAXAROMA<br>3117 38th Ave<br>Long Island City, NY 11101<br>United States</div><div class="line"></div><table><tbody><tr><td>Date:Feb 27, 2026</td><td class="right" style="display:none;">Time:HH:MM PM</td></tr></tbody></table><div class="line"></div><div class="bold">SALE</div><table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table><div class="line"></div><table><tbody><tr><td>Subtotal</td><td class="right">65.00</td></tr><tr><td>Auto Discount</td><td class="right">16.25</td></tr></tbody></table><div class="line"></div><table><tbody><tr class="total-box"><td>Total</td><td class="right">$48.75</td></tr></tbody></table><div class="line"></div><div>Charge &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 48.75<br>Visa XXXX-XXXX-XXXX-0123</div><div class="line"></div><div class="center bold">Refunds and Exchanges</div><div class="center">Within 30 days with receipt<br>And tags attached</div><div class="barcode"><div></div><div>MaxAroma</div></div></div>';


	/*loadScript();
	
	if ($('script[src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"]').length > 0) {
		console.log("Script tag is present on the page.");
		setTimeout(function(){
			onPrint();
		}, 3000);
		
	} else {
		console.log("Script tag not found.");
	}*/
});

/*
function loadScript() {
  var script = document.createElement('script');
  var lib = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
  script.type = 'text/javascript';
  script.setAttribute('src', lib);
  document.getElementsByTagName('head')[0].appendChild(script);
  document.head.appendChild(script);
  //return script;
}
*/


//var data1 = '<table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table>';

var data1 = '<html><head><title>MaxAroma Receipt</title></head><body><table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table></body</html>';

//var data1 = '<div id="receipt-container" style="width: 300px; font-family: monospace;"><div style="text-align: center;"><h2>Store Name</h2><p>123 Street, City</p></div><hr><table><tr><td>Item 1</td><td style="text-align: right;">$10.00</td></tr><tr><td>Item 2</td><td style="text-align: right;">$20.00</td></tr></table><hr><div style="text-align: right;"><strong>Total: $30.00</strong></div></div>';


function onPrint(getData) {
    //var builder = new StarWebPrintBuilder();
	//if ($("#hardware_status").length) {
		//$("#hardware_status").val($("#hardware_status").val()+ '\n' +JSON.stringify(builder));
	//}
	
    //var request = '';

    //request += builder.createInitializationElement();
    
    /*html2canvas(document.querySelector("#receipttestp")).then(canvas => {
		var builder = new StarWebPrintBuilder();
		var request = builder.createInitializationElement();
		
		// Convert canvas to bit image
		var context = canvas.getContext('2d');
		request += builder.createBitImageElement({context: context, x: 0, y: 0, width: canvas.width, height: canvas.height});
		
		request += builder.createCutPaperElement({feed: true});
		// Send via AJAX as shown in method 1
	});*/

    // Cashdrawer channel should be 1, on off in milliseconds
    //request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });
    /*request += builder.createTextElement({
		characterspace: 0,
		codepage: 'utf8',
		//font: 'fontA',
		//emphasis: true,
		height: 2,
		data: 'ORDER CONFIRMED\n'
	});*/
	//request += builder.createTextElement({ data: 'Item 1: $10.00\n' });
	//request += builder.createTextElement({ data: data1 });
	
	/*request += builder.createBitImageElement({
		canvas: document.getElementById('receipttestp'),
		anchor: 'center',
		type: 'bitimage'
	});*/

	/*request += builder.createRawDataElement({
		// This allows direct HTML string usage
		'type': 'html',
		//'html': data1
		characterspace: 0,
		codepage: 'utf8',
		//font: 'fontA',
		//emphasis: true,
		height: 2,
		data: data1
	});*/
	
	//request += builder.createCutPaperElement({ feed: true, type: 'partial' });
	
	/*html2canvas(document.querySelector("#receipt-container")).then(canvas => {
        
        // 2. Initialize Star WebPRNT Builder
        var builder = new StarWebPrintBuilder();
        request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });
        
        request += builder.createInitializationElement();
        // 3. Add the canvas image to the request
        request += builder.createBitImageElement({ context: canvas.getContext('2d'), x: 0, y: 0, width: canvas.width, height: canvas.height });
        request += builder.createCutPaperElement({ feed: true });
        
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' +request);
		}
    });*/
	
	var builder = new StarWebPrintBuilder();
	var request = '';
	
    // Initialize document
    request += builder.createInitializationElement();
    
    request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });

    // Header - Centered Logo (Index 1) and Store Info
    request += builder.createAlignmentElement({position: 'center'});
    request += builder.createLogoElement({number: 1}); // Assumes logo is stored in printer
    request += builder.createTextElement({data: 'MAXAROMA\n', emphasis: true});
    request += builder.createTextElement({data: '123 Tech Lane, San Jose, CA\n\n'});

    // Body - Left Aligned Items
    request += builder.createAlignmentElement({position: 'left'});
    
    //request += builder.createTextElement({data: 'Latte                 $4.50\n'});
    //request += builder.createTextElement({data: 'Muffin                $3.00\n'});
    for (let i = 0; i < getData.orderItems.length; i++) {
		request += builder.createTextElement({data: getData.orderItems[i].SKU+'                '+getData.orderItems[i].Qty+'                $'+getData.orderItems[i].ItemTotal+'\n'});
	}
    
    request += builder.createTextElement({data: '----------------------------\n'});

    // Footer - Totals
    request += builder.createAlignmentElement({position: 'right'});
    
    //request += builder.createTextElement({data: 'Total: $7.50\n', width: 2, height: 2}); // Double-sized text
    request += builder.createTextElement({data: 'Total: $'+getData.orderTotal+'\n', width: 2, height: 2}); // Double-sized text

    // Cut Paper
    request += builder.createCutPaperElement({feed: true});

	 if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' +request);
	}
	
	//console.log(atob("data - "+request.data));
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
