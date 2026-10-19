var IsCashDrawerOpen = 0;

function showDailyOpenPendingPopup()
{
	if($('#daily-open-pending-popup').length == 0)
	{
		$.ajax({
		  type: 'GET',
		  url: site_url + 'store/get-daily-open-pending-popup',
		  cache: true,
		  async: false,
		  dataType: "json",
		  success: (function(data, status) {
			  $('#daily-open-pending-popup').remove();
			  $("body").append(data.html);
			  $('#daily-open-pending-popup').modal('show');
		  }),
		  error: (function(resXhr){
		  })
		});
	}
	else
	{
		$('#daily-open-pending-popup').modal('show');
	}
}

if(typeof isshow_dailyopen_pending != 'undefined' && isshow_dailyopen_pending == '1')
{
	showDailyOpenPendingPopup();
}

$('#frmCashDrawerOB input[type=number], #frmCashDrawerManualOpen input[type=number]').keydown(function (event) {

	if ((event.keyCode >= 48 && event.keyCode <= 57) || 
		(event.keyCode >= 96 && event.keyCode <= 105) || 
		event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
		event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

	} else {
		event.preventDefault();
	}

	if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
		event.preventDefault();
});

$('#frmCashDrawerOB input[type=number]').on('change keyup paste input', function(){
	if($(this).attr('id') != 'disp_bank_deposit')
	{
	  calculate_open_balance();
	}
});


$('#frmCashDrawerOB input[id=disp_bank_deposit]').on('blur', function(){
	var disp_bank_deposit = $('#frmCashDrawerOB #disp_bank_deposit').val();
	disp_bank_deposit = disp_bank_deposit.replaceAll(/[^0-9.]/g, '');
	if(disp_bank_deposit == '') {
		disp_bank_deposit = 0;
	}
	if (disp_bank_deposit !== '' && !isNaN(disp_bank_deposit)) {
		disp_bank_deposit = Number(disp_bank_deposit).toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	}
	$('#frmCashDrawerOB #disp_bank_deposit').val('$' + disp_bank_deposit);
});

$('#frmCashDrawerOB input[id=disp_bank_deposit]').on('change keydown paste input', function(){
	
	var bank_deposit = $('#frmCashDrawerOB #disp_bank_deposit').val();
	bank_deposit = bank_deposit.replaceAll(/[^0-9.]/g, '');
	$('#frmCashDrawerOB #bank_deposit').val(bank_deposit);
	if($('#frmCashDrawerOB #bank_deposit').val() == '')
	{
		$('#frmCashDrawerOB #bank_deposit').val(0);
	}
	
	var grand_total = parseFloat($('#frmCashDrawerOB #grand_total').val());
	var bank_deposit = parseFloat($('#frmCashDrawerOB #bank_deposit').val());
	
	
	if(isNaN(bank_deposit))
	{
		bank_deposit = 0;
	}

	
	if(bank_deposit < grand_total)
	{
		var diff_val = parseFloat(grand_total - bank_deposit);
		if(diff_val > 0)
		{
			$('#frmCashDrawerOB #cash_drawer_fund').val(diff_val.toFixed(2));
			
			diff_val = diff_val.toFixed(2);
			if (diff_val !== '' && !isNaN(diff_val)) {
				diff_val = Number(diff_val).toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}
			$('#frmCashDrawerOB .cash_drawer_fund').text('$' + diff_val);
			return;
		}
	}

	$('#frmCashDrawerOB .cash_drawer_fund').text('$0.00');
	$('#frmCashDrawerOB #cash_drawer_fund').val(0);
	return;
});

const calculate_open_balance = function(e) {
	var grand_total = 0;
	var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
	$('#frmCashDrawerOB input[type=number]').each(function() {
		var conv_value = parseFloat($(this).attr('data-conversion-value'));
		var input_val = parseFloat($(this).val());

		if(input_val != undefined && !isNaN(input_val) && conv_value != undefined && !isNaN(conv_value) && numberRegex.test(conv_value)) {
			var row_total = input_val * conv_value;
			row_total = parseFloat(row_total.toFixed(2));
			grand_total = parseFloat(grand_total + row_total);
			
			row_total = row_total.toFixed(2);
			if (row_total !== '' && !isNaN(row_total)) {
				row_total = Number(row_total).toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}
			
			$(this).closest('tr').find('.row_total').text('$'+row_total);
			
		} else {
			$(this).closest('tr').find('.row_total').text('');
		}
	});
	
	
	var org_grand_total = grand_total;
	var set_manual_balance = set_updated_manual_balance  = 0;
	grand_total = grand_total.toFixed(2);
	var set_grand_total = grand_total;
	
	if (grand_total !== '' && !isNaN(grand_total)) {
		grand_total = Number(grand_total).toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	}
	$('#frmCashDrawerOB .grand_total').text('$' + grand_total);
	$('#frmCashDrawerOB #grand_total').val(org_grand_total.toFixed(2));
	
	if ($(".manual_total").length) {		
		if(parseFloat(set_grand_total) > parseFloat($("#cd_opening_balance").val())){
			set_manual_balance = set_grand_total - parseFloat($("#cd_opening_balance").val());
			
			set_manual_balance = parseFloat(set_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .manual_total').text('$' + set_manual_balance);
			
			set_updated_manual_balance = parseFloat($("#current_balance").val()) + parseFloat(set_manual_balance);
			set_updated_manual_balance = parseFloat(set_updated_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .updated_manual_total').text('$' + set_updated_manual_balance);
		}
		else if(parseFloat(set_grand_total) < parseFloat($("#cd_opening_balance").val())){
			set_manual_balance = parseFloat($("#cd_opening_balance").val()) - set_grand_total;
			
			set_manual_balance = parseFloat(set_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .manual_total').text('-$' + set_manual_balance);
			
			set_updated_manual_balance = parseFloat($("#current_balance").val()) - parseFloat(set_manual_balance);
			set_updated_manual_balance = parseFloat(set_updated_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .updated_manual_total').text('$' + set_updated_manual_balance);
		}else{
			set_manual_balance = 0.00;
			
			set_manual_balance = parseFloat(set_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .manual_total').text('$' + set_manual_balance);
			
			set_updated_manual_balance = parseFloat($("#current_balance").val()) + parseFloat(set_manual_balance);
			set_updated_manual_balance = parseFloat(set_updated_manual_balance).toFixed(2);
			$('#frmCashDrawerOB .updated_manual_total').text('$' + set_updated_manual_balance);
		}
	}
	
	
	var type = $('#frmCashDrawerOB #type').val();
	if(type == 'close')
	{		
		var store_cash_drawer_fund = parseFloat($('#frmCashDrawerOB #store_cash_drawer_fund').val());
		if(org_grand_total > store_cash_drawer_fund)
		{
			var diff_val = parseFloat(org_grand_total - store_cash_drawer_fund);
			
			$('#frmCashDrawerOB #cash_drawer_fund').val(store_cash_drawer_fund.toFixed(2));
			
			store_cash_drawer_fund = store_cash_drawer_fund.toFixed(2);
			if (store_cash_drawer_fund !== '' && !isNaN(store_cash_drawer_fund)) {
				store_cash_drawer_fund = Number(store_cash_drawer_fund).toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}
			$('#frmCashDrawerOB .cash_drawer_fund').text('$' + store_cash_drawer_fund);
			
			diff_val = diff_val.toFixed(2);
			$('#frmCashDrawerOB #bank_deposit').val(diff_val);
			
			if (diff_val !== '' && !isNaN(diff_val)) {
				diff_val = Number(diff_val).toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}
			$('#frmCashDrawerOB #disp_bank_deposit').val('$' + diff_val);
		}
		else
		{
			$('#frmCashDrawerOB .cash_drawer_fund').text('$' + grand_total);
			$('#frmCashDrawerOB #cash_drawer_fund').val(org_grand_total);
			$('#frmCashDrawerOB #bank_deposit').val(0);
			$('#frmCashDrawerOB #disp_bank_deposit').val('$0.00');
		}
	}
}

function Change_Manual_Adjust(){
	var is_deposit_adjusted = $("#is_deposit_adjusted").val();
	if(is_deposit_adjusted == 'yes')
	{
		var adjustment_type = $("#adjustment_type_tr .ring-size-dropdown").attr('data-adjustment-type');
		$("#adjustment_type_tr .ring-size-dropdown").html('<select class="select" name="adjustment_type" id="adjustment_type"><option value="">Select</option><option value="increase" ' + (adjustment_type == 'increase' ? ' selected ' : '') + '>Increase</option><option value="decrease" ' + (adjustment_type == 'decrease' ? ' selected ' : '') + '>Decrease</option></select>');
		$("#adjustment_type_tr").removeClass('d-none');
		$("#adjustment_reason_tr").removeClass('d-none');
	}
	else
	{
		$("#adjustment_type_tr").addClass('d-none');
		$("#adjustment_reason_tr").addClass('d-none');
		$("#adjustment_type").val('').change();
		$("#adjustment_reason").val('');
	}
}


$(document).ready(function()
{
	$('a').click(function(e) {
        if(IsCashDrawerOpen == 1) {
            e.preventDefault();
            alert("Please close the cash drawer first");
			return 0;
        }
    });
	
	$("#frmCashDrawerOB").submit(function()
	{	
		if ($("#transaction_remark").length) {
			var transaction_remark = $("#transaction_remark").val();
			if(transaction_remark.trim() == '')
			{
				alert('Please enter remarks.');
				$("#transaction_remark").focus();
				return false;
			}
		}
		
		var type = $('#frmCashDrawerOB #type').val();
		if(type == 'close')
		{
			var grand_total = parseFloat($('#frmCashDrawerOB #grand_total').val());
			var cash_drawer_fund = parseFloat($('#frmCashDrawerOB #cash_drawer_fund').val());
			var bank_deposit = parseFloat($('#frmCashDrawerOB #bank_deposit').val());

			if(grand_total != (cash_drawer_fund + bank_deposit))
			{
				alert('Invalid bank deposit amount. Cash drawer closing balance does not match with cash drawer fund and bank deposit.');
				$('#frmCashDrawerOB #disp_bank_deposit').focus();
				return false;
			}
		}
	});
	
	
	$("#frmCashDrawerManuallyAdjust").submit(function()
	{
		var adjustment_reason = $("#adjustment_reason").val();
		if(adjustment_reason.trim() == '')
		{
			alert('Please enter reason for manual adjustment.');
			$("#adjustment_reason").focus();
			return false;
		}
    });
    
    $("#frmCashDrawerManualOpen").submit(function()
	{
		var transaction_remark = $("#transaction_remark").val();
		if(transaction_remark.trim() == '')
		{
			alert('Please enter remarks.');
			$("#transaction_remark").focus();
			return false;
		}
	});
});

function submitformcash(frmname) {
	if(IsCashDrawerOpen == 1) {
		alert("Please close the cash drawer first");
		return 0;
	}
	else {
		//onDisconnect();
		$('#'+frmname).submit();
	}
}


function onConnect() {
    if ($("#hardware_status").length) {
		var curValue = $("#hardware_status").val();
	}else{
		var curValue = '';
	}
    
    if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Connecting...');
	}
    storeDeviceLog(1,'testdeviceName','Normal','Connecting...','Yes');
    
    window.manager = new StarWebPrintExtManager();

    manager.onDataReceive = function (args) {
        // This is called when StarMicronics barcode scanner is connected with mPop and scans any barcode
		
        storeDeviceLog(1,'testdeviceName','Normal',decodeBase64ToString(args.data),'Yes');
    }
	
    manager.onReceive = function (response) {
        if (response.managerSuccess == 'true' && response.managerClaim == 'true') {
			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","green");
				$("#cashdrawer_status").text("CONNECTED");
			}
			
			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","green");
				$("#barcode_scanner_status").text("CONNECTED");
			}
			
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Connected');
			}
			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").val($("#cashdrawer_status").val()+ '\n' + 'Manager Connected');
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','Manager Connected','Yes');
        }
        else {			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","red");
				$("#cashdrawer_status").text("NOT CONNECTED");
			}
			
			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","red");
				$("#barcode_scanner_status").text("NOT CONNECTED");
			}
			
            if(response.managerCode == '2001' || response.managerCode == 2001){
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))');
				}
				
				storeDeviceLog(1,'testdeviceName','Error','Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))','Yes');
			}
			else if(response.managerCode == '1010' || response.managerCode == 1010){
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Printer unconnected state');
				}
				
				storeDeviceLog(1,'testdeviceName','Error','Printer unconnected state','Yes');
			}
			else if(response.managerCode == '3000' || response.managerCode == 3000){
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Claim non-acquired state');
				}
				
				storeDeviceLog(1,'testdeviceName','Error','Claim non-acquired state','Yes');
			}
			else if(response.managerCode == '3001' || response.managerCode == 3001){
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Not supported');
				}
				
				storeDeviceLog(1,'testdeviceName','Error','Not supported','Yes');
			}
			else{
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Not connected');
				}
				
				storeDeviceLog(1,'testdeviceName','Error','Manager Not connected','Yes');
			}
        }

        var msg = 'onReceive {\n';
        msg += '\tManagerSuccess : [ ' + response.managerSuccess + ' ]\n';
        msg += '\tManagerCode : [ ' + response.managerCode + ' ]\n }';
        // manager will be detecting cash drawer events if and only if:-
        // 1. response.managerCode = 0
        // 2. response.managerSuccess = 'true'
        // 3. response.managerClaim == 'true'
        /**
         * All Manager codes possible values (https://www.star-m.jp/products/s_print/sdk/webprnt/manual/en/_StarWebPrintExtManager-js.htm#onReceive)
            0 … Success
            1010 … Printer unconnected state
            2001 … Printer busy(In use with another device: Need to resend data)
            3000 … Claim non-acquired state
            3001 … Not supported
         * 
         */
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + msg);
		}
		
		storeDeviceLog(1,'testdeviceName','Normal',msg,'Yes');
    }

    manager.onError = function (args) {
        var msg = 'onError {\n';
        msg += '\tStatus: ' + args.status + '\n';
        msg += '\tResponseText:' + args.responseText + '\n }';
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + ' onError '+msg);
		}
		
		storeDeviceLog(1,'testdeviceName','Error',msg,'Yes');
    }
	
    manager.onBarcodeDataReceive = function (args) {
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeDataReceive"+decodeBase64ToString(args.data));
		}
		storeDeviceLog(1,'testdeviceName','Normal','onBarcodeDataReceive'+' '+decodeBase64ToString(args.data),'Yes');
        
    }
    manager.onBarcodeReaderConnect = function () {
		if ($("#barcode_scanner_status").length) {
			$("#barcode_scanner_status").text("Barcode Reader Connect");
		}
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderConnected");
		}
		
		storeDeviceLog(1,'testdeviceName','Normal','event : onBarcodeReaderConnected','Yes');
    }
    
    manager.onBarcodeReaderDisconnect = function () {
		if ($("#barcode_scanner_status").length) {
			$("#barcode_scanner_status").text("Barcode Reader Disconnected");
		}
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderDisconnect");
		}
		
		storeDeviceLog(1,'testdeviceName','Error','event : onBarcodeReaderDisconnect','Yes');
    }
    
    manager.onBarcodeReaderImpossible = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderImpossible");
		}
		
		storeDeviceLog(1,'testdeviceName','Error','event : onBarcodeReaderImpossible','Yes');
    }
    manager.onAccessoryDisconnect = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryDisconnect");
		}
		
		storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryDisconnect','Yes');
	}
    manager.onAccessoryConnectSuccess = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectSuccess");
		}
		
		storeDeviceLog(1,'testdeviceName','Normal','event : onAccessoryConnectSuccess','Yes');
    }
    manager.onAccessoryConnectFailure = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectFailure");
		}
		
		storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryConnectFailure','Yes');
    }
    
    manager.onPrinterImpossible = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterImpossible');
		}
		storeDeviceLog(1,'testdeviceName','Error','event : onPrinterImpossible','Yes');
    }
    manager.onPrinterOnline = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterOnline');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterOnline','Yes');
    }
    manager.onPrinterOffline = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterOffline');
		}
		storeDeviceLog(1,'testdeviceName','Error','event : onPrinterOffline','Yes');
    }
    manager.onPrinterPaperReady = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterPaperReady');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperReady','Yes');
    }
    manager.onPrinterPaperNearEmpty = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterPaperNearEmpty');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperNearEmpty','Yes');
    }
    manager.onPrinterPaperEmpty = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterPaperEmpty');
		}
		storeDeviceLog(1,'testdeviceName','Error','event : onPrinterPaperEmpty','Yes');
    }
    manager.onPrinterCoverOpen = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterCoverOpen');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverOpen','Yes');
    }
    manager.onPrinterCoverClose = function () {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onPrinterCoverClose');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverClose','Yes');
    }
    manager.onCashDrawerOpen = function (event1) {
		IsCashDrawerOpen = IsCashDrawerOpen + parseInt(1);
		
		if(IsCashDrawerOpen == 1) {
            event1.preventDefault();
            alert("Please close the cash drawer first");
			return 0;
        }
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Cash Drawer Opened');
		}
		
		storeDeviceLog(1,'testdeviceName','Normal','event : onCashDrawerOpen','Yes');
    }
    manager.onCashDrawerClose = function () {
		IsCashDrawerOpen = 0;
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Cash Drawer Closed');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onCashDrawerClose','Yes');
		
		setTimeout(manager.disconnect(),2000);
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Disconnected');
		}
    }
    manager.onStatusUpdate = function (args) {
		if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'event : onStatusUpdate');
		}
		storeDeviceLog(1,'testdeviceName','Normal','event : onStatusUpdate','Yes');
    }

    var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";//THIS IS THE ONLY URL THAT WILL WORK TO CONNECT MANAGER INSTANCE
    try {
        if (manager.connect({ url: url }) == false) {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Already connected or Now communicating.");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','Already connected or Now communicating.','Yes');
        }
    } catch (ee) {
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Error: " + ee);
		}
        
        storeDeviceLog(1,'testdeviceName','Error','Error: ' + ee,'Yes');
    }


}

function onDisconnect() {
    if (manager.disconnect() == true) {
		alert("Disconnected");
		storeDeviceLog(1,'testdeviceName','Normal','Disconnected','Yes');
    }
    else {
		storeDeviceLog(1,'testdeviceName','Normal','Already disconnected','Yes');
    }
}

function disconnectDevices() {
	if (manager instanceof StarWebPrintExtManager) {
		manager.disconnect();
		window.location.href = site_url+'store/store-dashboard.html';
	}
	else{
		window.location.href = site_url+'store/store-dashboard.html';
	}
}

function onPrint() {
    var builder = new StarWebPrintBuilder();

    var request = '';

    request += builder.createInitializationElement();

    // Cashdrawer channel should be 1, on off in milliseconds
    request += builder.createPeripheralElement({ channel: 1, on: 100, off: 100 });
	
	if ($("#hardware_status").length) {
		$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Opening Cash Drawer');
	}
	
    sendMessage(request);
}

function sendMessage(request) {
    var url = "http://localhost:8001/StarWebPRNT/SendMessage";////THIS IS THE ONLY URL THAT WILL WORK TO SEND THE CASHDRAWER OPEN MESSAGE TO THE mPOP.

    // var trader = new StarWebPrintTrader({ url: url, papertype: papertype });
    var trader = new StarWebPrintTrader({ url: url });

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
        
		storeDeviceLog(1,'testdeviceName','Normal',msg,'Yes');
    }

    trader.onError = function (response) {
        var msg = 'onError {\n';

        msg += '\tStatus:' + response.status + '\n';

        msg += '\tResponseText:' + response.responseText + '\n';

        msg += ' }';
		
		storeDeviceLog(1,'testdeviceName','Error',msg,'Yes');
    }

    trader.sendMessage({ request: request, url });

    // TO CONFIRM IF THE CASHDRAWER REQUEST WE SEND HERE IS PROPERLY RECEIVED BY mPOP, WE NEED TO CHECK THE RESPONSE RECEIVED IN onReceive CALLBACK FUNCTION. SEARCH FOR 'response.traderStatus' in the trader.onReceive CALLBACK FUNCTION.
}

function onReset() {
    let text = "Please click 'OK' if you want to reset your hardware";
    if (confirm(text) == true) {
        var builder = new StarWebPrintBuilder();
        var request = '';
        request += builder.createInitializationElement({ reset: true, print: false });

        sendMessage(request);
    }
}

function storeDeviceLog(deviceID,deviceName,logType,message,isHardwareLog)
{
	$.ajax({
		type: 'GET',
		url: site_url + 'store/storeDeviceLog',
		data:'deviceID='+deviceID+'&deviceName='+deviceName+'&logType='+logType+'&message='+message+'&isHardwareLog='+isHardwareLog,
		cache: true,
		async: false,
		dataType: "json",
		success: (function(data, status) {
			
		}),
		error: (function(resXhr){
			
		})
	});
}

