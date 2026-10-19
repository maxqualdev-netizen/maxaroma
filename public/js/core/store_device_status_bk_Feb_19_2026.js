var getURL = document.URL;
var setPageText = '';

if(getURL.includes('store/devices')){
	setPageText = ' - Devices Page';
}
else if(getURL.includes('store/store-dashboard.html')){
	setPageText = ' - Dashboard';
}
else if(getURL.includes('store/create-order.html')){
	setPageText = ' - POS New Order';
}
else{
	setPageText = '';
}

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

function connectDevices(){
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		alert("Star WebPRNT Browser detected");
        console.log("Star WebPRNT Browser detected");
        
        storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected'+setPageText,'No');

		var manager1 = new StarWebPrintExtManager();
		
		/*
		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";
		
		if (manager1.connect({ url: url }) == true) {
			alert("Connected");
			
			if ($("#scanner_drawer_status").length) {
				$("#scanner_drawer_status").val("ONLINE");
			}
			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","green");
				$("#cashdrawer_status").text("Connected");
			}

			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","green");
				$("#barcode_scanner_status").text("Connected");
			}
			
			if ($("#tabContent_Barcode").length) {
				$("#tabContent_Barcode").addClass("text-success");
				$("#tabContent_Barcode").text("CONNECTED");
			}
			
			$("#page-spinner").hide();
        }
        else{
			alert("Not connected");
			
			if ($("#scanner_drawer_status").length) {
				$("#scanner_drawer_status").val("OFFLINE");
			}
			
			if ($("#tabContent_Barcode").length) {
				$("#tabContent_Barcode").addClass("text-danger");
				$("#tabContent_Barcode").text("NOT CONNECTED");
			}
			
			$("#page-spinner").hide();
		}
		*/
		
		manager1.onDataReceive = function (args) {
			// This is called when StarMicronics barcode scanner is connected with mPop and scans any barcode
			// console.log("args: ", decodeBase64ToString(args.data));
		}

		manager1.onReceive = function (response) {
			if (response.managerSuccess == 'true' && response.managerClaim == 'true') {
				alert("Manager Connected");
			}
			else {
				alert("Manager Not connected");
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

			alert(msg);
		}

		manager1.onError = function (args) {
			alert("Manager Not connected - in 'onError' function");
			
			var msg = 'onError {\n';
			msg += '\tStatus: ' + args.status + '\n';
			msg += '\tResponseText:' + args.responseText + '\n }';

			alert(msg + " - in 'onError' function");
		}
		
		manager1.onPrinterImpossible = function () {
			alert('event : onPrinterImpossible');
		}
		manager1.onPrinterOnline = function () {
			alert('event : onPrinterOnline');
		}
		manager1.onPrinterOffline = function () {
			alert('event : onPrinterOffline');
		}
		manager1.onPrinterPaperReady = function () {
			alert('event : onPrinterPaperReady');
		}
		manager1.onPrinterPaperNearEmpty = function () {
			alert('event : onPrinterPaperNearEmpty');
		}
		manager1.onPrinterPaperEmpty = function () {
			alert('event : onPrinterPaperEmpty');
		}
		manager1.onPrinterCoverOpen = function () {
			alert('event : onPrinterCoverOpen');
		}
		manager1.onPrinterCoverClose = function () {
			alert('event : onPrinterCoverClose');
		}
		manager1.onStatusUpdate = function (args) {
			alert('event : onStatusUpdate');

			alert("Printer Status -  "+args.status);
		}

		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";//THIS IS THE ONLY URL THAT WILL WORK TO CONNECT MANAGER INSTANCE
		try {
			// alert(url);
			if (manager1.connect({ url: url }) == false) {
				alert("Already connected or Now communicating.");
			}
		} catch (ee) {
			// alert(ee);
			alert("Error: " + ee);
		}
	}
	else {
		alert("Star WebPRNT Browser NOT detected");
        console.log("Star WebPRNT Browser NOT detected");
        
        storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected'+setPageText,'No');
        
        if ($("#scanner_drawer_status").length) {
			$("#scanner_drawer_status").val("OFFLINE");
		}
        
        if ($("#tabContent_Barcode").length) {
			$("#tabContent_Barcode").addClass("text-danger");
			$("#tabContent_Barcode").text("NOT CONNECTED");
		}
        
        $("#page-spinner").hide();
	}
}

$(document).ready(function() {
	connectDevices();
});

function onResetDeviceStatus() {
    let text = "Please click 'OK' if you want to reset your hardware";
    
    if (confirm(text) == true) {
		alert("Connecting...");
		$("#page-spinner").show();
        connectDevices();
    }
}

function storeDeviceLog(deviceID,deviceName,logType,message,isHardwareLog)
{
	$.ajax({
		type: 'GET',
		url: site_url + 'store/storeDeviceLog',
		//data:'deviceID='+deviceID+'&deviceName='+deviceName+'&logType='+logType+'&message='+message+'&isHardwareLog='+isHardwareLog+'&metadata='+metadata,
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
