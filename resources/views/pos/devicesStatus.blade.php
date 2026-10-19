@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-label="POS Page">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
            <a href="{{config('global.SITE_URL')}}" aria-label="Home">Home</a>
			<a href="{{config('global.SITE_URL')}}store/store-dashboard.html" aria-label="Home">POS</a>
            <span class="active" aria-current="page">Devices</span>
        </div>
    </section>
    <div class="container pt-lg-5 pt-2 pb-5 pos-new-order-main" role="region" aria-labelledby="pos-new-order-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12 pt-lg-0 pt-3" role="region" aria-label="POS New Order Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-new-order-title">Devices</h2>
                </div>
                <div class="row" role="list" aria-label="Dashboard Navigation">
                        @if(Session::has('deviceError'))
                            <p class="mb-3 alert alert-danger p-2" id="daily-open-section-title">{{ Session::get('deviceError') }}</p>
                        @endif
                        @if(Session::has('deviceSuccess'))
                            <p class="mb-3 alert alert-success p-2" id="daily-open-section-title">{{ Session::get('deviceSuccess') }}</p>
                        @endif
                        <div class="col-6 col-sm-4 col-md-4 mb-4" role="listitem">
                            <div class="dashboard-card">
                                <i class="bi bi-receipt" aria-hidden="true"></i>
                                <div class="dashboard-cardHd">Card Reader</div>
                                {!! $cardReaderStatus??'' !!}
                                <p id="barcode_scanner_name" class="mb-0 mt-2"></p>
                                <p id="reset_cardreader_div" class="mb-0" style="display:none;"><a href="javascript:location.reload();" class="posstatus-link">Reset</a></p>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4 col-md-4 mb-4" role="listitem">

                            <div class="dashboard-card">
                                <i class="bi bi-box-seam" aria-hidden="true"></i>
                                <div class="dashboard-cardHd">Barcode Scanner</div>
                                <p class="d-none bluetooth_status_div">Bluetooth: <span class="posstatus-span bluetooth_status" style="color:red;">OFF</span></p>
                                <p class="mb-0">Status: <span id="barcode_scanner_status" class="posstatus-span" style="color:red;">NOT CONNECTED</span>&nbsp;&nbsp;<span id="barcode_scanner_battery_level"></span></p>
                                <p id="barcode_scanner_name" class="mb-0 mt-2"></p>
                                <p id="reset_scanner_div" class="mb-0"><a href="javascript:void(0);" class="posstatus-link" onClick="onResetDeviceStatus();">Reset</a></p>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4 col-md-4 mb-4" role="listitem">
                            <div class="dashboard-card">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                <div class="dashboard-cardHd">Cash Drawer</div>
                                <p class="mb-0">Status: <span id="cashdrawer_status" class="posstatus-span" style="color:red;">NOT CONNECTED</span></p>
                                <p id="cashdrawer_conn_mode_div" class="d-none">Mode: <span id="cashdrawer_conn_mode" class="posstatus-span" style="color:green;"></span></p>
                                <p id="reset_cashdrawer_div" class="mb-0 d-none"><a href="javascript:resetCashDrawer();" class="posstatus-link">Reset Cash Drawer</a></p>
                            </div>
                        </div>
                    </div>
                <!-- Back Link -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group"
                    aria-label="Navigation and Actions">
                    <a href="{{config('global.SITE_URL')}}store/store-dashboard.html" class="back-link" aria-label="Back to POS Home">
                        <i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Dashboard
                    </a>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="region" aria-label="Hardware Status Details" >
					<textarea type="text" id="hardware_status" name="hardware_status" class="form-control" placeholder="Hardware Status" cols="30" rows="12"></textarea>
				</div>
                
            </div>
        </div>
    </div>
</main>
<div id="connect-card-reader" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-sm vertical-align-center">
            <div class="modal-content">
                <a class="close" type="button" data-dismiss="modal" aria-hidden="true">
                    <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                        <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                    </svg>
                </a>
                <div class="modal-body">
                    <div class="modal-space">
                        <form method="post" id="frmNewCardReader" name="frmNewCardReader">
                            <h3 class="sub-hd mb-2 mt-lg-4">Connect New Card Reader</h3>
                            <div class="error-cls d-none" id="errmsgm"></div>
                            <div class="form-group">
                                <label for="user code" class="col-form-label">Enter User Code</label>
                                <input id="user_code" type="text" name="user_code" value="" placeholder="User Code" class="form-control">
                            </div><?php //simulated-s700 ?>
                            <div class="form-group">
                                <label for="reader name" class="col-form-label">Enter Reader Name</label>
                                <input id="reader_name" type="text" name="reader_name" value="{{ $reader_store_name }} - Reader" placeholder="Reader Name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="location_label" class="col-form-label">Location Name</label>
                                <input id="location_label" type="text" placeholder="Location Label" value="<?=$location_label?>" class="form-control" disabled>
                            </div>
                            <button id="saveReader" class="btn btn-primary d-block w-100" title="Connect Card Reader">Connect Card Reader</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
/*
$(document).ready(function() {
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		alert("Star WebPRNT Browser detected");
        console.log("Star WebPRNT Browser detected");

        //setTimeout(onConnect(),3000);
		//setTimeout(onPrint(),2000);

		var manager1 = new StarWebPrintExtManager();
		var url = "http://localhost:8001/StarWebPRNT/SendExtMessage";
		if (manager1.connect({ url: url }) == true) {
			alert("Connected");
			
			if ($("#cashdrawer_status").length) {
				$("#cashdrawer_status").css("color","green");
				$("#cashdrawer_status").text("Connected");
			}

			if ($("#barcode_scanner_status").length) {
				$("#barcode_scanner_status").css("color","green");
				$("#barcode_scanner_status").text("Connected");
			}
        }
        else{
			alert("Not connected");
		}
	}
	else {
		alert("Star WebPRNT Browser NOT detected");
        console.log("Star WebPRNT Browser NOT detected");
	}
});

function onResetDeviceStatus() {
    let text = "Please click 'OK' if you want to reset your hardware";
     alert("hi...");
    if (confirm(text) == true) {
        //showNowCommunicating();
        
        setTimeout(onConnect(),3000);
        alert("Connecting...");
    }
}*/

</script>

@endsection
