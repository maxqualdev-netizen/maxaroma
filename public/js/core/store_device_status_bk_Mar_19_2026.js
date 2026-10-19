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
	
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		
		//alert("Star WebPRNT Browser detected");
        //console.log("Star WebPRNT Browser detected");
        if ($("#hardware_status").length) {
			$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Star WebPRNT Browser detected");
		}
        
        storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected'+setPageText,'No');

		var manager1 = new StarWebPrintExtManager();
		
		/*
		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";
		
		if (manager1.connect({ url: url }) == true) {
			alert("CONNECTED");
			
			if ($("#scanner_drawer_status").length) {
				$("#scanner_drawer_status").val("ONLINE");
			}
			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","green");
				$("#cashdrawer_status").text("CONNECTED");
			}

			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","green");
				$("#barcode_scanner_status").text("CONNECTED");
			}
			
			if ($("#tabContent_Barcode").length) {
				$("#tabContent_Barcode").addClass("text-success");
				$("#tabContent_Barcode").text("CONNECTED");
			}
			
			$("#page-spinner").hide();
        }
        else{
			alert("NOT CONNECTED");
			
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
			
			storeDeviceLog(1,'testdeviceName','Normal',decodeBase64ToString(args.data)+setPageText,'Yes');
		}

		manager1.onReceive = function (response) {
			if (response.managerSuccess == 'true' && response.managerClaim == 'true') {
				//alert("Manager Connected");
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Manager Connected');
				}
				
				storeDeviceLog(1,'testdeviceName','Normal','Manager Connected'+setPageText,'Yes');
			}
			else {
				//alert("Manager Not connected");
				
				if(response.managerCode == '2001' || response.managerCode == 2001){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))');
					}
					
					storeDeviceLog(1,'testdeviceName','Error','Already connected or Now communicating (Printer busy(In use with another device: Need to resend data))'+setPageText,'Yes');
					
					/*setTimeout(function() {
						manager1.disconnect();
						connectDevices();
					}, 5000);*/
				}
				else if(response.managerCode == '1010' || response.managerCode == 1010){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Printer unconnected state');
					}
					
					storeDeviceLog(1,'testdeviceName','Error','Printer unconnected state'+setPageText,'Yes');
				}
				else if(response.managerCode == '3000' || response.managerCode == 3000){
					if ($("#hardware_status").length) {
						$("#hardware_status").val($("#hardware_status").val()+ '\n' + 'Claim non-acquired state');
					}
					
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

			//alert(msg);
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + msg);
			}
			
			storeDeviceLog(1,'testdeviceName','Normal',msg+setPageText,'Yes');
		}

		manager1.onError = function (args) {
			//alert("Manager Not connected - in 'onError' function");
			
			var msg = 'onError {\n';
			msg += '\tStatus: ' + args.status + '\n';
			msg += '\tResponseText:' + args.responseText + '\n }';

			//alert(msg + " - in 'onError' function");
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + msg + " - in 'onError' function");
			}
			
			storeDeviceLog(1,'testdeviceName','Error',msg+setPageText,'Yes');
		}
		
		manager1.onBarcodeDataReceive = function (args) {
			//alert('onBarcodeDataReceive');
			//alert(decodeBase64ToString(args.data));
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeDataReceive");
				//$("#hardware_status").val($("#hardware_status").val()+ '\n' + decodeBase64ToString(args.data));
				$("#hardware_status").val($("#hardware_status").val()+ '\n' +  atob(args.data));
				
				let productUPC = atob(args.data).trim();
				
				AddToCartStore(productUPC, 1);
			}
			storeDeviceLog(1,'testdeviceName','Normal','onBarcodeDataReceive'+' '+atob(args.data)+setPageText,'Yes');
		}
		manager1.onBarcodeReaderConnect = function () {
			//alert('event : onBarcodeReaderConnected');
			if ($("#barcode_scanner_status").length) {
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
		}
		manager1.onBarcodeReaderDisconnect = function () {
			//alert('event : onBarcodeReaderDisconnect');
			if ($("#barcode_scanner_status").length) {
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
			//alert('event : onBarcodeReaderImpossible');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onBarcodeReaderImpossible");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onBarcodeReaderImpossible'+setPageText,'Yes');
		}
		manager1.onAccessoryDisconnect = function () {
			//alert('event : onAccessoryDisconnect');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryDisconnect");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryDisconnect'+setPageText,'Yes');
		}
		manager1.onAccessoryConnectSuccess = function () {
			//alert('event : onAccessoryConnectSuccess');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectSuccess");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','event : onAccessoryConnectSuccess'+setPageText,'Yes');
		}
		manager1.onAccessoryConnectFailure = function () {
			//alert('event : onAccessoryConnectFailure');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onAccessoryConnectFailure");
			}
			
			storeDeviceLog(1,'testdeviceName','Error','event : onAccessoryConnectFailure'+setPageText,'Yes');
		}
		manager1.onPrinterImpossible = function () {
			//alert('event : onPrinterImpossible');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "event : onPrinterImpossible");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterImpossible'+setPageText,'Yes');
		}
		manager1.onPrinterOnline = function () {
			//alert('event : onPrinterOnline');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterOnline");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterOnline'+setPageText,'Yes');
		}
		manager1.onPrinterOffline = function () {
			//alert('event : onPrinterOffline');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterOffline");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterOffline'+setPageText,'Yes');
		}
		manager1.onPrinterPaperReady = function () {
			//alert('event : onPrinterPaperReady');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperReady");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperReady'+setPageText,'Yes');
		}
		manager1.onPrinterPaperNearEmpty = function () {
			//alert('event : onPrinterPaperNearEmpty');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperNearEmpty");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterPaperNearEmpty'+setPageText,'Yes');
		}
		manager1.onPrinterPaperEmpty = function () {
			//alert('event : onPrinterPaperEmpty');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterPaperEmpty");
			}
			storeDeviceLog(1,'testdeviceName','Error','event : onPrinterPaperEmpty'+setPageText,'Yes');
		}
		manager1.onPrinterCoverOpen = function () {
			//alert('event : onPrinterCoverOpen');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterCoverOpen");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverOpen'+setPageText,'Yes');
		}
		manager1.onPrinterCoverClose = function () {
			//alert('event : onPrinterCoverClose');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onPrinterCoverClose");
			}
			storeDeviceLog(1,'testdeviceName','Normal','event : onPrinterCoverClose'+setPageText,'Yes');
		}
		manager1.onStatusUpdate = function (args) {
			//alert('event : onStatusUpdate');
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "onStatusUpdate");
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','event : onStatusUpdate'+setPageText,'Yes');
			
			//alert("Printer Status -  "+args.status);
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
				
				 /*if ($("#tabContent_Barcode").length) {
					$("#tabContent_Barcode").addClass("text-success");
					$("#tabContent_Barcode").text("CONNECTED");
				}*/
				
				/*if ($("#showDeviceStatusDiv").length) {
					$("#notConnectedDevice").hide();
					$("#showDeviceStatusDiv").show();
					$("#connectedDevice").show();
				}*/
				
				//alert("Connected");
				
				$("#page-spinner").hide();
				
			}else{
				
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "NOT CONNECTED");
				}
				
				/*if ($("#tabContent_Barcode").length) {
					$("#tabContent_Barcode").addClass("text-danger");
					$("#tabContent_Barcode").text("NOT CONNECTED");
				}*/
				
				$("#cashdrawer_status").css("color","red");
				$("#cashdrawer_status").text("NOT CONNECTED");
				
				/*if ($("#showDeviceStatusDiv").length) {
					$("#connectedDevice").hide();
					$("#showDeviceStatusDiv").show();
					$("#notConnectedDevice").show();
				}*/
				
				$("#page-spinner").hide();
			}
		}

		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";//THIS IS THE ONLY URL THAT WILL WORK TO CONNECT MANAGER INSTANCE
		/*try {
			// alert(url);
			if (manager1.connect({ url: url }) == false) {
				//alert("Already connected or Now communicating.");
				
				//alert("Var - "+getStatus);
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Var - "+getStatus+" - CONNECTED  Testtt");
				}
				
				if (getStatus === "00000000" && $("#cashdrawer_status").length) {
					$("#cashdrawer_status").css("color","green");
					$("#cashdrawer_status").text("CONNECTED  Testtt");
					
					//alert("Connected Testtt");
				}else{
					$("#cashdrawer_status").css("color","red");
					$("#cashdrawer_status").text("NOT CONNECTED");
				}
			}
			else{
				$("#cashdrawer_status").css("color","red");
				$("#cashdrawer_status").text("NOT CONNECTED");
			}
		} catch (ee) {
			// alert(ee);
			alert("Error: " + ee);
			
			$("#cashdrawer_status").css("color","red");
			$("#cashdrawer_status").text("NOT CONNECTED");
		}*/
		
		try {
			// alert(url);
			if (manager1.connect({ url: url }) === false) {
				if ($("#hardware_status").length) {
					$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Already connected or Now communicating.");
				}
				
				storeDeviceLog(1,'testdeviceName','Normal','Already connected or Now communicating'+setPageText,'Yes');
			}
		} catch (ee) {
			// alert(ee);
			if ($("#hardware_status").length) {
				$("#hardware_status").val($("#hardware_status").val()+ '\n' + "Error: " + ee);
			}
			
			storeDeviceLog(1,'testdeviceName','Error','Error: ' + ee+setPageText,'Yes');
		}
	}
	else {
		//alert("Star WebPRNT Browser NOT detected");
        //console.log("Star WebPRNT Browser NOT detected");
        
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

/*function showDeviceStatus(){
	connectDevices();
}*/

