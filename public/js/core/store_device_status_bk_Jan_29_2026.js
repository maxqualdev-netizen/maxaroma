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
