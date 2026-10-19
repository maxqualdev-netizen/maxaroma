var getURL = document.URL;
var setPageText = '';
let manager1 = null;
let isConnecting = false;
let isStarConnected = false;

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

/***********************
 * CONNECT DEVICES (FIXED)
 ***********************/
function connectDevices(){

	if (isConnecting) return;
	if (isStarConnected) {
		alert("Already connected → skipping");
		return;
	}

	$("#page-spinner").show();
	isConnecting = true;

	let userAgent = navigator.userAgent;

	if (userAgent.includes('StarWebPRNTBrowser')) {

		if (!manager1) {
			manager1 = new StarWebPrintExtManager();
		}

		/***************
		 * EVENTS
		 ***************/
		manager1.onReceive = function (response) {

			if (response.managerSuccess == 'true' && response.managerClaim == 'true') {

				isStarConnected = true;

				$("#cashdrawer_status").css("color","green").text("CONNECTED");

				storeDeviceLog(1,'testdeviceName','Normal','Manager Connected'+setPageText,'Yes');
			}
			else {

				// 🔥 FIX: treat 2001 as already connected
				if (response.managerCode == 2001) {

					console.log("Already connected (reuse)");
					isStarConnected = true;

					storeDeviceLog(1,'testdeviceName','Normal','Already connected (reuse)'+setPageText,'Yes');

					$("#page-spinner").hide();
					isConnecting = false;
					return;
				}

				$("#cashdrawer_status").css("color","red").text("NOT CONNECTED");

				storeDeviceLog(1,'testdeviceName','Error','Manager Not connected'+setPageText,'Yes');
			}

			$("#page-spinner").hide();
			isConnecting = false;
		};

		manager1.onError = function (args) {
			console.error("onError", args);

			storeDeviceLog(1,'testdeviceName','Error','onError'+setPageText,'Yes');

			$("#page-spinner").hide();
			isConnecting = false;
		};

		manager1.onBarcodeDataReceive = function (args) {

			let productUPC = atob(args.data).trim();

			storeDeviceLog(1,'testdeviceName','Normal','Scan '+productUPC+setPageText,'Yes');

			if (typeof AddToCartStore === "function") {
				AddToCartStore(productUPC, 1);
			}
		};

		manager1.onStatusUpdate = function (args) {

			let status = args.status;

			if (status === "0" || status === "00000000") {
				$("#cashdrawer_status").css("color","green").text("CONNECTED");
			} else {
				$("#cashdrawer_status").css("color","red").text("NOT CONNECTED");
			}
		};

		let url = "http://localhost:8001/StarWebPRNT/SendExtMessage";

		try {
			let result = manager1.connect({ url: url });

			if (result === false) {
				console.log("Busy / already connected");

				// treat as connected
				isStarConnected = true;

				storeDeviceLog(1,'testdeviceName','Normal','Busy / reuse connection'+setPageText,'Yes');

				$("#page-spinner").hide();
				isConnecting = false;
				return;
			}

		} catch (e) {
			console.error(e);

			storeDeviceLog(1,'testdeviceName','Error','Connect error'+setPageText,'Yes');

			$("#page-spinner").hide();
			isConnecting = false;
		}
	}
	else {

		storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected'+setPageText,'No');

		$("#cashdrawer_status").css("color","red").text("NOT CONNECTED");
		$("#page-spinner").hide();
	}
}

/***********************
 * SAFE INIT (FIXED)
 ***********************/
$(document).ready(async function () {

	// important delay to avoid reload conflict
	await new Promise(r => setTimeout(r, 800));

	connectDevices();
});

/***********************
 * RESET BUTTON
 ***********************/
function onResetDeviceStatus() {
	let text = "Please click 'OK' if you want to reset your hardware";

	if (confirm(text) == true) {
		isStarConnected = false;
		manager1 = null;
		connectDevices();
	}
}

/***********************
 * LOGGING (UNCHANGED)
 ***********************/
function storeDeviceLog(deviceID,deviceName,logType,message,isHardwareLog)
{
	$.ajax({
		type: 'GET',
		url: site_url + 'store/storeDeviceLog',
		data:'deviceID='+deviceID+'&deviceName='+deviceName+'&logType='+logType+'&message='+message+'&isHardwareLog='+isHardwareLog,
		cache: true,
		async: false,
		dataType: "json"
	});
}

/***********************
 * PRINT FUNCTIONS (UNCHANGED)
 ***********************/
function buildReceipt() {
	var builder = new StarWebPrintBuilder();
	var request = '';
	request += builder.createInitializationElement();
	request += builder.createTextElement({data: 'ORDER #12345\n'});
	request += builder.createTextElement({data: 'Item 1 - $10.00\n'});
	request += builder.createFeedElement({count: 2});
	request += builder.createCutPaperElement({type: 'partial'});
	return request;
}

function onPrint() {
	var builder = new StarWebPrintBuilder();
	var request = '';

	request += builder.createInitializationElement();
	request += builder.createTextElement({ data: 'ORDER CONFIRMED\n' });
	request += builder.createCutPaperElement({ feed: true, type: 'partial' });

	var trader = new StarWebPrintTrader({
		url: 'http://localhost:8001/StarWebPRNT/PrintRequest',
		timeout: 10000
	});

	trader.onReceive = function (response) {
		alert(response.result === 'success' ? 'Printed' : 'Failed');
	};

	trader.onError = function (error) {
		alert('Error: ' + error);
	};

	trader.sendMessage({ request: request });
}