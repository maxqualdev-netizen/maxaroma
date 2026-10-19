var getURL = document.URL;
var setPageText = '';
//let manager1;

if(getURL.includes('store/devices')){
	setPageText = ' - Devices Page';
}
else if(getURL.includes('store/store-dashboard.html')){
	setPageText = ' - Dashboard';
}
else if(getURL.includes('store/create-order.html')){
	setPageText = ' - POS New Order';
}
else if(getURL.includes('store/store-payment')){
	setPageText = ' - Store Payment Page';
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

let getStatus = '';

function connectDevices(){
	
	$("#page-spinner").show();
	
	//window.manager1 = new StarWebPrintExtManager();
	
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Star WebPRNT Browser detected");
		}
        
        storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected'+setPageText,'No');

		//var manager1 = new StarWebPrintExtManager();
		//manager1 = new StarWebPrintExtManager();
		window.manager1 = new StarWebPrintExtManager();
		
		manager1.onDataReceive = function (args) {
			// This is called when StarMicronics barcode scanner is connected with mPop and scans any barcode
			
			storeDeviceLog(1,'testdeviceName','Normal',decodeBase64ToString(args.data)+setPageText,'Yes');
		}

		manager1.onReceive = function (response) {
			if (response.managerSuccess == 'true' && response.managerClaim == 'true') {
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Connected');
				}
				//localStorage.setItem("manager_connected", "true");
				storeDeviceLog(1,'testdeviceName','Normal','Manager Connected'+setPageText,'Yes');
			}
			else {				
				if(response.managerCode == '2001' || response.managerCode == 2001){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))');
					}
					
					storeDeviceLog(1,'testdeviceName','Error','Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))'+setPageText,'Yes');
					//localStorage.setItem("manager_connected", "false");
					
					/*manager1.disconnect();
					setTimeout(function() {
						connectDevices();
					}, 5000);*/
					
					/*manager1.onPrinterImpossible();
					manager1.onPrinterOnline();
					manager1.onPrinterOffline();
					manager1.onCashDrawerOpen();*/
				}
				else if(response.managerCode == '1010' || response.managerCode == 1010){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Printer unconnected state');
					}
					//localStorage.setItem("manager_connected", "false");
					storeDeviceLog(1,'testdeviceName','Error','Printer unconnected state'+setPageText,'Yes');
				}
				else if(response.managerCode == '3000' || response.managerCode == 3000){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Claim non-acquired state');
					}
					//localStorage.setItem("manager_connected", "false");
					storeDeviceLog(1,'testdeviceName','Error','Claim non-acquired state'+setPageText,'Yes');
				}
				else if(response.managerCode == '3001' || response.managerCode == 3001){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Not supported');
					}
					
					storeDeviceLog(1,'testdeviceName','Error','Not supported'+setPageText,'Yes');
				}
				else{
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Not connected');
					}
					
					storeDeviceLog(1,'testdeviceName','Error','Manager Not connected'+setPageText,'Yes');
				}
				
				$("#page-spinner").hide();
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
			
			storeDeviceLog(1,'testdeviceName','Normal',msg+setPageText,'Yes');
		}

		manager1.onError = function (args) {
			
			var msg = 'onError {\n';
			msg += '\tStatus: ' + args.status + '\n';
			msg += '\tResponseText:' + args.responseText + '\n }';

			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + msg + " - in 'onError' function");
			}
			
			storeDeviceLog(1,'testdeviceName','Error',msg+setPageText,'Yes');
		}
		
		manager1.onBarcodeDataReceive = function (args) {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeDataReceive");
				//$("#hardware_status").val($("#hardware_status").val()+ '\n' + decodeBase64ToString(args.data));
				$("#hardware_status").val($("#hardware_status").val()+ '\n' +  atob(args.data));
				
				let productUPC = atob(args.data).trim();
				let lengthOfUPC = productUPC.length;
				let setUPC = '';
				
				if(lengthOfUPC == 28){
					setUPC = productUPC.slice(0, 14);
				}
				else if(lengthOfUPC == 26){
					setUPC = productUPC.slice(0, 13);
				}
				else if(lengthOfUPC == 24){
					setUPC = productUPC.slice(0, 12);
				}else{
					setUPC = productUPC;
				}
				
				setTimeout(function() {AddToCartStore(setUPC, 1);}, 500);
				//AddToCartStore(productUPC, 1);
			}
			storeDeviceLog(1,'testdeviceName','Normal','onBarcodeDataReceive'+' '+atob(args.data)+setPageText,'Yes');
		}
		manager1.onBarcodeReaderConnect = function () {
			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","green");
				$("#barcode_scanner_status").text("CONNECTED");
			}
			
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderConnected");
			}
			
			if ($("#tabContent_Barcode").length) {
				$("#tabContent_Barcode").addClass("text-success");
				$("#tabContent_Barcode").text("CONNECTED");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','event : onBarcodeReaderConnected'+setPageText,'Yes');
			
			if(document.URL.includes('store/devices')){
				manager1.disconnect();
			}
		}
		manager1.onBarcodeReaderDisconnect = function () {
			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","red");
				$("#barcode_scanner_status").text("NOT CONNECTED");
			}
			
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderDisconnect");
			}
			
			if ($("#tabContent_Barcode").length) {
				$("#tabContent_Barcode").addClass("text-danger");
				$("#tabContent_Barcode").text("NOT CONNECTED");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onBarcodeReaderDisconnect'+setPageText,'Yes');
		}
		manager1.onBarcodeReaderImpossible = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderImpossible");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onBarcodeReaderImpossible'+setPageText,'Yes');
		}
		manager1.onAccessoryDisconnect = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryDisconnect");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryDisconnect'+setPageText,'Yes');
		}
		manager1.onAccessoryConnectSuccess = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectSuccess");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','event : onAccessoryConnectSuccess'+setPageText,'Yes');
		}
		manager1.onAccessoryConnectFailure = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectFailure");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryConnectFailure'+setPageText,'Yes');
		}
		manager1.onPrinterImpossible = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onPrinterImpossible");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterImpossible'+setPageText,'Yes');
		}
		manager1.onPrinterOnline = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterOnline");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterOnline'+setPageText,'Yes');
		}
		manager1.onPrinterOffline = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterOffline");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterOffline'+setPageText,'Yes');
		}
		manager1.onPrinterPaperReady = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperReady");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperReady'+setPageText,'Yes');
		}
		manager1.onPrinterPaperNearEmpty = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperNearEmpty");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperNearEmpty'+setPageText,'Yes');
		}
		manager1.onPrinterPaperEmpty = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperEmpty");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterPaperEmpty'+setPageText,'Yes');
		}
		manager1.onPrinterCoverOpen = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterCoverOpen");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverOpen'+setPageText,'Yes');
		}
		manager1.onPrinterCoverClose = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterCoverClose");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverClose'+setPageText,'Yes');
		}
		manager1.onCashDrawerOpen = function () {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Cash Drawer Opened');
			}
			
		}
		manager1.onStatusUpdate = function (args) {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onStatusUpdate");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','event : onStatusUpdate'+setPageText,'Yes');
			
			getStatus = args.status;
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Printer Status -  "+args.status);
			}
			
			if ((getStatus === "00000000" || getStatus === 00000000 || getStatus === "0000" || getStatus === 0000 || getStatus === "0" || getStatus === 0) && $("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","green");
				$("#cashdrawer_status").text("CONNECTED");
				
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "CONNECTED");
				}
				
				$("#page-spinner").hide();
				
				if(document.URL.includes('store/devices')){
					manager1.disconnect();
				}
				
			}else{
				
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "NOT CONNECTED");
				}
				
				$("#cashdrawer_status").css("color","red");
				$("#cashdrawer_status").text("NOT CONNECTED");
				
				$("#page-spinner").hide();
			}
		}

		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";//THIS IS THE ONLY URL THAT WILL WORK TO CONNECT MANAGER INSTANCE
		
		try {
			if (manager1.connect({ url: url }) === false) {
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Already connected or Now communicating.");
				}
				
				storeDeviceLog(1,'testdeviceName','Normal','Already connected or Now communicating'+setPageText,'Yes');
			}
		} catch (ee) {
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Error: " + ee);
			}
			
			storeDeviceLog(1,'testdeviceName','Error','Error: ' + ee+setPageText,'Yes');
		}
	}
	else {        
         if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Star WebPRNT Browser NOT detected");
		}
        
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
	/*if(manager1.length){
		manager1.disconnect();
	}*/
	
	//alert(JSON.stringify(manager1));
	//if (manager1 instanceof StarWebPrintExtManager) {
	/*if (localStorage.getItem("manager_connected") === "true" && false) {
		//do nothing
		alert("Already cretaed object of manager");
	}
	else{*/
		//alert("Creating object of manager");
		//connectDevices();
		//printOrder();
		//alert(JSON.stringify(manager1));
		
		connectDevices();
		//receiptPrinting();
		//sendOrderToPrinter();
		//onPrint();// working
	/*}*/
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

/*function showDeviceStatus(){
	connectDevices();
}*/
