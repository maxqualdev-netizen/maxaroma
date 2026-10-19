@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-label="POS Page">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
            <a href="{{url('/')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">POS</span>
        </div>
    </section>
    <!-- Dashboard Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 pos-dashboard-main" role="region" aria-labelledby="pos-dashboard-title">

        <h2 id="pos-dashboard-title" class="posmain-hd mb-4 text-center">POS Dashboard</h2>

		@if(isset($posPaymentMsg) && $posPaymentMsg!='')
		<x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => $posPaymentMsg, 'mid' => 'error_cart']" />
		@endif

        <div class="row" role="list" aria-label="Dashboard Navigation">
            <!-- POS Dashboard Card -->
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <!--<a href="{{config('global.SITE_URL').'store/create-order.html'}}" class="dashboard-card" aria-label="Create a new POS order">-->
                <a @if($shared_isshow_dailyopen_pending == '1') href="javascript:void(0);" onclick="javascript:showDailyOpenPendingPopup();" @else  href="{{config('global.SITE_URL').'store/create-order.html'}}" @endif class="dashboard-card" aria-label="Create a new POS order">

                    <i class="bi bi-receipt" aria-hidden="true"></i>
                    <div>POS New Order</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=store-inventory'}}" class="dashboard-card" aria-label="View POS inventory">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    <div>POS Inventory</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=customer-list-store'}}" class="dashboard-card" aria-label="Manage customers">
                    <i class="bi bi-people" aria-hidden="true"></i>
                    <div>Customers</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=order-list-store'}}" class="dashboard-card" aria-label="View order history">
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    <div>Order History</div>
                </a>
            </div>

            <!--<div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#returns" class="dashboard-card" aria-label="Process returns">
                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                    <div>Returns</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#repairs" class="dashboard-card" aria-label="Manage repairs">
                    <i class="bi bi-wrench" aria-hidden="true"></i>
                    <div>Repairs</div>
                </a>
            </div>-->

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=daily_sales_report'}}" class="dashboard-card" aria-label="View reports">
                    <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    <div>Reports</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=store-user-list'}}" class="dashboard-card" aria-label="Manage store users">
                    <i class="bi bi-shop" aria-hidden="true"></i>
                    <div>Store User</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'store/cash-drawer'}}" class="dashboard-card" aria-label="Access cash drawer">
                    <i class="bi bi-cash" aria-hidden="true"></i>
                    <div>Cash Drawer</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
				<input type="hidden" id="scanner_drawer_status" name="scanner_drawer_status" value="">
                <input type="hidden" id="card_reader_status" name="card_reader_status" value="{{ $cardreaderStatus }}">
                <a href="{{config('global.SITE_URL').'store/devices'}}" class="dashboard-card" aria-label="Manage devices">
                    <i class="bi bi-pc-display-horizontal" aria-hidden="true"></i>
                    <div>Devices</div>
                    <!--<strong id="deviceStatus"><div style='color:red'>OFFLINE</div></strong>-->
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'store/editprofile.html'}}" class="dashboard-card" aria-label="Edit my profile">
                    <i class="bi bi-pencil" aria-hidden="true"></i>
                    <div>Edit My Profile</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'store/change-password.html'}}" class="dashboard-card" aria-label="Change Password">
                    <i class="bi bi-key" aria-hidden="true"></i>
                    <div>Change Password</div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="{{config('global.SITE_URL').'store/logout'}}" class="dashboard-card" aria-label="Logout">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    <div>Logout</div>
                </a>
            </div>
        </div>
    </div>
    <!-- Dashboard Page Content Page End Here -->

</main>

<script>

var isshow_dailyopen_pending = {{ isset($shared_isshow_dailyopen_pending) && $shared_isshow_dailyopen_pending != '' ? $shared_isshow_dailyopen_pending : 0 }}

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

$(document).ready(function() {

	if(typeof isshow_dailyopen_pending != 'undefined' && isshow_dailyopen_pending == '1')
	{
		showDailyOpenPendingPopup();
	}

    var cashdrawer_status = $("#scanner_drawer_status").val();
    var cardreader_status = $("#card_reader_status").val();
    if(cashdrawer_status == 'ONLINE' && cardreader_status == 'ONLINE')
    {
        $("#deviceStatus").html("<div style='color:green'>ONLINE</div>");
    }
    else
    {
        $("#deviceStatus").html("<div style='color:red'>OFFLINE</div>");
    }
});
/*$(document).ready(function() {
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		alert("Star WebPRNT Browser detected");
        console.log("Star WebPRNT Browser detected");

        //setTimeout(onConnect(),3000);
		//setTimeout(onPrint(),2000);
	}
	else {
		alert("Star WebPRNT Browser NOT detected");
        console.log("Star WebPRNT Browser NOT detected");
	}
});*/

</script>

@endsection
<script>
const url = new URL(window.location.href);
if (url.searchParams.get('posmsg') === '1' || url.searchParams.get('posmsg') === '2' || url.searchParams.get('posmsg') === '3') {
	let newUrl = url.origin + url.pathname;
	window.history.replaceState({}, '', newUrl);
}
</script>
