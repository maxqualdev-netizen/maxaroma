
<div class="posleft-mobbar">
    <h2>POS Navigation</h2>
    <button class="pos-navbutton p-0" type="button" aria-label="Toggle POS Navigation"   aria-expanded="false"    aria-controls="pos-navigation-list">
        <i class="bi bi-filter" aria-hidden="true"></i>
        <i class="bi bi-x d-none" aria-hidden="true"></i>
    </button>
</div>
<ul class="myact_left_link">
    <li>
        <a href="{{config('global.SITE_URL').'store/store-dashboard.html'}}" aria-label="Manage Store User">
            <i class="bi bi-shop" aria-hidden="true"></i>
           <span>POS Dashboard</span>
        </a>
    </li>
    <li>
        <a @if(Session::get('isshow_dailyopen_pending') == '1') href="javascript:void(0);" onclick="javascript:showDailyOpenPendingPopup();" @else  href="{{config('global.SITE_URL').'store/create-order.html'}}" @endif aria-label="Create a New POS Order">
            <i class="bi bi-receipt" aria-hidden="true"></i>
            <span>POS New Order</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=store-inventory'}}" aria-label="POS Inventory">
            <i class="bi bi-box-seam" aria-hidden="true"></i>
            <span>POS Inventory</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=customer-list-store'}}" aria-label="Customers">
            <i class="bi bi-people" aria-hidden="true"></i>
            <span>Customers</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=order-list-store'}}" aria-label="Order History">
            <i class="bi bi-clock-history" aria-hidden="true"></i>
            <span>Order History</span>
        </a>
    </li>
    <!--<li>
        <a href="#" aria-label="Returns">
            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
            <span>Returns</span>
        </a>
    </li>
    <li>
        <a href="#" aria-label="Manage Repairs">
            <i class="bi bi-wrench" aria-hidden="true"></i>
            <span>Repairs</span>
        </a>
    </li>-->
    <li>
        <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=daily_sales_report'}}" aria-label="View Reports">
            <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
            <span>Reports</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'pu-panel/index.php?f=store-user-list'}}" aria-label="Manage Store User">
            <i class="bi bi-shop" aria-hidden="true"></i>
           <span>Store User</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'store/cash-drawer'}}" aria-label="Access Cash Drawer">
            <i class="bi bi-cash" aria-hidden="true"></i>
            <span>Cash Drawer</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'store/devices'}}" aria-label="Manage Devices">
            <i class="bi bi-pc-display-horizontal" aria-hidden="true"></i>
            <span>Devices</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'store/editprofile.html'}}" aria-label="Edit my profile">
            <i class="bi bi-pencil" aria-hidden="true"></i>
            <span>Edit My Profile</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'store/change-password.html'}}" aria-label="Edit my profile">
            <i class="bi bi-key" aria-hidden="true"></i>
            <span>Change Password</span>
        </a>
    </li>
    <li>
        <a href="{{config('global.SITE_URL').'store/logout'}}" aria-label="Logout">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
            <span>Logout</span>
        </a>
    </li>
</ul>

<script>

//var isshow_dailyopen_pending = {{ Session::has('isshow_dailyopen_pending') && Session::get('isshow_dailyopen_pending') != '' ? Session::get('isshow_dailyopen_pending') : 0 }}

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

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const navBtn = document.querySelector(".pos-navbutton");
  const targetDiv = document.querySelector(".myact_left_link");

  if (navBtn && targetDiv) {
    navBtn.addEventListener("click", function () {
      targetDiv.classList.toggle("active");
      navBtn.classList.toggle("active");
    });
  }
});
</script>
