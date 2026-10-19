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
	
	document.body.innerHTML += '<div id="receipt-container" style="width: 300px; font-family: monospace;"><div style="text-align: center;"><h2>Store Name</h2><p>123 Street, City</p></div><hr><table><tr><td>Item 1</td><td style="text-align: right;">$10.00</td></tr><tr><td>Item 2</td><td style="text-align: right;">$20.00</td></tr></table><hr><div style="text-align: right;"><strong>Total: $30.00</strong></div></div>';
	
	/*
	document.body.innerHTML += '<html><head><meta charset="utf-8"><title>MaxAroma Receipt</title><style>body{
    width:302px;margin:0 auto;padding:10px;font-family: "Courier New", monospace;font-size:12px;color:#000;}.center{text-align:center;}.left{text-align:left;}.right{text-align:right;}.bold{font-weight:bold;}.line{border-top:1px dashed #000;margin:8px 0;}table{width:100%;border-collapse:collapse;}th, td{padding:2px 0;}th{text-align:left;}.total-box{font-size:20px;font-weight:bold;}.barcode{text-align:center;margin-top:10px;}.barcode div{font-size:28px;letter-spacing:3px;}@media print{body{width:302px;}@page{margin:0;}}</style></head><body id="receipt-container"><div class="center bold"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAAA3CAYAAAAVFkZ2AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQ4IDc5LjE2NDAzNiwgMjAxOS8wOC8xMy0wMTowNjo1NyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkFEOUVFRjNBNjlGQTExRUI4NkRGQzNFOTM2QTc2QzkxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkFEOUVFRjNCNjlGQTExRUI4NkRGQzNFOTM2QTc2QzkxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QUQ5RUVGMzg2OUZBMTFFQjg2REZDM0U5MzZBNzZDOTEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QUQ5RUVGMzk2OUZBMTFFQjg2REZDM0U5MzZBNzZDOTEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7EasU6AAAcGElEQVR42uxdCVhV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" width="100"><br><br><br></div><div class="center">MAXAROMA<br>3117 38th Ave<br>Long Island City, NY 11101<br>United States</div><div class="line"></div><table><tbody><tr><td>Date:Feb 27, 2026</td><td class="right" style="display:none;">Time:HH:MM PM</td></tr></tbody></table><div class="line"></div><div class="bold">SALE</div><table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table><div class="line"></div><table><tbody><tr><td>Subtotal</td><td class="right">65.00</td></tr><tr><td>Auto Discount</td><td class="right">16.25</td></tr></tbody></table><div class="line"></div><table><tbody><tr class="total-box"><td>Total</td><td class="right">$48.75</td></tr></tbody></table><div class="line"></div><div>Charge &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 48.75<br>Visa XXXX-XXXX-XXXX-0123</div><div class="line"></div><div class="center bold">Refunds and Exchanges</div><div class="center">Within 30 days with receipt<br>And tags attached</div><div class="barcode"><div></div><div>MaxAroma</div></div></body></html>';
    */


	loadScript();
	
	if ($('script[src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"]').length > 0) {
		console.log("Script tag is present on the page.");
		//onPrint();
		
		/*setTimeout(function(){
			onPrint();
		}, 3000);*/
		
	} else {
		console.log("Script tag not found.");
	}
});


function loadScript() {
  var script = document.createElement('script');
  var lib = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
  script.type = 'text/javascript';
  script.setAttribute('src', lib);
  document.getElementsByTagName('head')[0].appendChild(script);
  document.head.appendChild(script);
  //return script;
}


//var data1 = '<table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table>';

var data1 = '<html><head><title>MaxAroma Receipt</title></head><body><table><tbody><tr class="bold"><th>SKU</th><th>Description</th><th>Qty.</th><th class="right">Total</th></tr><tr><td>737052351100</td><td>Hugo Boss Boss Bottled</td><td>1</td><td class="right">65.00</td></tr></tbody></table></body</html>';

//var data1 = '<div id="receipt-container" style="width: 300px; font-family: monospace;"><div style="text-align: center;"><h2>Store Name</h2><p>123 Street, City</p></div><hr><table><tr><td>Item 1</td><td style="text-align: right;">$10.00</td></tr><tr><td>Item 2</td><td style="text-align: right;">$20.00</td></tr></table><hr><div style="text-align: right;"><strong>Total: $30.00</strong></div></div>';


function onPrint() {
    //var builder = new StarWebPrintBuilder();
	if ($("#hardware_status").length) {
		//$("#hardware_status").val($("#hardware_status").val()+ '\n' +JSON.stringify(builder));
	}
	
    var request = '';

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
	
	html2canvas(document.querySelector("#receipt-container")).then(canvas => {
        
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
    });
	
	
	
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
