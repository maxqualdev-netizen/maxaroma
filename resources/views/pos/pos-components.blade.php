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
        <h2 id="pos-dashboard-title" class="posmain-hd mb-4 text-center">POS Home</h2>
        <div class="row" role="list" aria-label="Dashboard Navigation">
            <!-- POS Dashboard Card -->
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="pos-new-order.php" class="dashboard-card" aria-label="Create a new POS order">
                    <i class="bi bi-receipt" aria-hidden="true"></i>
                    <div>POS New Order</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#pos-inventory" class="dashboard-card" aria-label="View POS inventory">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    <div>POS Inventory</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#customers" class="dashboard-card" aria-label="Manage customers">
                    <i class="bi bi-people" aria-hidden="true"></i>
                    <div>Customers</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#order-history" class="dashboard-card" aria-label="View order history">
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    <div>Order History</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
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
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#reports" class="dashboard-card" aria-label="View reports">
                    <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
                    <div>Reports</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#store-user" class="dashboard-card" aria-label="Manage store users">
                    <i class="bi bi-shop" aria-hidden="true"></i>
                    <div>Store User</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="pos-cash-drawer.php" class="dashboard-card" aria-label="Access cash drawer">
                    <i class="bi bi-cash" aria-hidden="true"></i>
                    <div>Cash Drawer</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="pos-devices.php" class="dashboard-card" aria-label="Manage devices">
                    <i class="bi bi-pc-display-horizontal" aria-hidden="true"></i>
                    <div>Devices</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#edit-profile" class="dashboard-card" aria-label="Edit my profile">
                    <i class="bi bi-pencil" aria-hidden="true"></i>
                    <div>Edit My Profile</div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 mb-4" role="listitem">
                <a href="#logout" class="dashboard-card" aria-label="Logout">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    <div>Logout</div>
                </a>
            </div>
        </div>
    </div>
    <!-- Dashboard Page Content Page End Here -->


    <!-- Devices Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 pos-devices-main" role="region" aria-labelledby="pos-devices-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12 pos-devices-content" role="region" aria-label="POS Devices Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-devices-title">Devices</h2>
                </div>
                <div class="row" role="list" aria-label="Connected POS Devices">
                    <!-- Card Reader -->
                    <div class="col-md-4 mb-md-0 mb-4" role="listitem">
                        <div class="device-card" role="region" aria-labelledby="card-reader-title">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-credit-card device-card-icon mr-2" aria-hidden="true"></i>
                                <span class="device-title font-weight-bold" id="card-reader-title">CARD READER</span>
                            </div>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-success" aria-label="Connected" aria-live="polite">CONNECTED</strong>
                            </p>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-danger" aria-label="Not Connected">NOT CONNECTED</strong>
                            </p>
                            <a href="#" class="device-connect-link" aria-label="Connect New Card Reader">Connect New Card Reader</a>
                        </div>
                    </div>
                    <!-- Barcode Scanner -->
                    <div class="col-md-4 mb-md-0 mb-4" role="listitem">
                        <div class="device-card" role="region" aria-labelledby="barcode-scanner-title">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-upc-scan device-card-icon mr-2" aria-hidden="true"></i>
                                <span class="device-title font-weight-bold" id="barcode-scanner-title">BARCODE SCANNER</span>
                            </div>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-success" aria-label="Connected" aria-live="polite">CONNECTED</strong>
                            </p>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-danger" aria-label="Not Connected">NOT CONNECTED</strong>
                            </p>
                            <a href="#" class="device-connect-link" aria-label="Reset Barcode Scanner">Reset</a>
                        </div>
                    </div>
                    <!-- Cash Drawer -->
                    <div class="col-md-4" role="listitem">
                        <div class="device-card" role="region" aria-labelledby="cash-drawer-title">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-cash-stack device-card-icon mr-2" aria-hidden="true"></i>
                                <span class="device-title font-weight-bold" id="cash-drawer-title">CASH DRAWER</span>
                            </div>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-success" aria-label="Connected" aria-live="polite">CONNECTED</strong>
                            </p>
                            <p class="mb-1" aria-live="polite">Status:
                                <strong class="text-danger" aria-label="Not Connected">NOT CONNECTED</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Back Link -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group"
                    aria-label="Navigation and Actions">
                    <a href="#" class="back-link" aria-label="Back to POS Home">
                        <i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Devices Page Content Page End Here -->
    
    
    <!-- New Order Page Content Page Start Here -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const tabLinks = document.querySelectorAll('#tabOptions .nav-link');
            const tabPanes = document.querySelectorAll('#tabContent .tab-pane');

            tabLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Remove 'active' from all tabs and tab panes
                    tabLinks.forEach(link => link.classList.remove('active'));
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    // Activate clicked tab
                    this.classList.add('active');

                    // Get the associated pane via href
                    const targetId = this.getAttribute('href');
                    const targetPane = document.querySelector(targetId);
                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>
    <div class="container pt-3 pb-md-5 pb-3 pos-new-order-main" role="region" aria-labelledby="pos-new-order-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12" role="region" aria-label="POS New Order Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-new-order-title">POS New Order</h2>
                </div>
                <div class="row">
                    <!-- Left: Options (Tabs) -->
                    <nav class="col-12" aria-label="Order input methods">
                        <div class="nav nav-pills custom-tabnav" id="tabOptions" role="tablist" aria-orientation="vertical">
                            <a class="btn-option nav-link active" id="tab-barcode-tab" data-toggle="pill" href="#tab-barcode" role="tab" aria-controls="tab-barcode" aria-selected="true" aria-label="Barcode Scanner Tab">
                                <i class="bi bi-upc-scan" aria-hidden="true"></i> Barcode Scanner
                                <!-- <i class="bi bi-code-slash" aria-hidden="true"></i> Barcode Scanner -->
                            </a>
                            <a class="btn-option nav-link" id="tab-camera-tab" data-toggle="pill" href="#tab-camera" role="tab" aria-controls="tab-camera" aria-selected="false" aria-label="Scan via Camera Tab">
                                <i class="bi bi-qr-code-scan" aria-hidden="true"></i> Scan via Camera
                            </a>
                            <a class="btn-option nav-link" id="tab-manual-tab" data-toggle="pill" href="#tab-manual" role="tab" aria-controls="tab-manual" aria-selected="false" aria-label="Enter Manually Tab">
                                <i class="bi bi-keyboard" aria-hidden="true"></i> Enter Manually
                            </a>
                        </div>
                    </nav>
                    <!-- Right: Tab Content -->
                    <div class="col-12" role="region" aria-labelledby="tabContent">
                        <div class="tab-content" id="tabContent">
                            <!-- Barcode Scanner Tab -->
                            <div class="tab-pane fade show active status-card" id="tab-barcode" role="tabpanel"
                                aria-labelledby="tab-barcode-tab">
                                <h2 class="font-weight-bold mb-3">Barcode Scanner</h2>
                                <p>Status: <strong class="text-success">CONNECTED</strong></p>
                                <p>Status: <strong class="text-danger">NOT CONNECTED</strong></p>
                                <p class="mb-0">Please connect your barcode scanner to proceed. Ensure the device is properly configured and recognized by the system.</p>
                            </div>
                            <!-- Scan via Camera Tab -->
                            <div class="tab-pane fade status-card" id="tab-camera" role="tabpanel" aria-labelledby="tab-camera-tab">
                                <h2 class="font-weight-bold mb-3">Camera Scanner</h2>
                                <p>Status: <strong class="text-warning">CAMERA NOT ENABLED</strong></p>
                                <p class="mb-0">Please allow camera access in your browser settings to scan using your device camera.</p>
                            </div>
                            <!-- Enter Manually Tab -->
                            <div class="tab-pane fade status-card" id="tab-manual" role="tabpanel" aria-labelledby="tab-manual-tab">
                                <h2 class="font-weight-bold mb-3">Enter Manually</h2>
                                <label class="font-weight-medium" for="manual-product-id">Product SKU / ERP SKU / UPC Number <span class="text-danger">*</span></label>
                                <div class="custom-input-flex">
                                    <input type="text" id="manual-product-id" class="form-control" placeholder="Enter product identifier" aria-required="true" aria-label="Product SKU, ERP SKU, or UPC Number">
                                    <button class="btn btn-dark-custom" aria-label="Add to bag">
                                        <i class="bi bi-cart-plus mr-2" aria-hidden="true"></i>Add to bag
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Back Link -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group"
                    aria-label="Navigation and Actions">
                    <a href="index.php" class="back-link" aria-label="Back to POS Home">
                        <i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- New Order Page Content Page End Here -->


    <!-- Cash Drawer Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="pos-cash-drawer-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12" role="region" aria-label="POS Cash Drawer Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-cash-drawer-title">Cash Drawer</h2>
                </div>
                <div class="row" role="region" aria-labelledby="store-balance-title">
                    <!-- Left section -->
                    <div class="col-lg-8 mb-md-0 mb-4">
                        <div class="cash-drawer-card" role="region" aria-labelledby="store-info-title">
                            <h2 class="font-weight-semibold mb-3" id="store-info-title">LGROWNB-007 - Texas - Houston Galleria</h2>
                            <div class="row label-row mb-2" role="group" aria-label="Store Open Balance">
                                <div class="col" id="store-open-balance-label">Store Open Balance</div>
                                <div class="col-auto font-weight-semibold" aria-labelledby="store-open-balance-label" aria-live="polite">$0.00</div>
                            </div>
                            <div class="row label-row mb-2" role="group" aria-label="Cash Sales">
                                <div class="col" id="cash-sales-label">Cash Sales</div>
                                <div class="col-auto font-weight-semibold" aria-labelledby="cash-sales-label" aria-live="polite">$0.00</div>
                            </div>
                            <div class="row label-row mb-2" role="group" aria-label="Cash Refunds">
                                <div class="col" id="cash-refunds-label">Cash Refunds</div>
                                <div class="col-auto font-weight-semibold" aria-labelledby="cash-refunds-label" aria-live="polite">$0.00</div>
                            </div>
                            <div class="row label-row mb-2" role="group" aria-label="Bank Deposit">
                                <div class="col" id="bank-deposit-label">Bank Deposit</div>
                                <div class="col-auto font-weight-semibold" aria-labelledby="bank-deposit-label" aria-live="polite">$0.00</div>
                            </div>
                            <div class="row label-row pt-2" role="group" aria-label="Current Balance">
                                <div class="col font-weight-bold" id="current-balance-label">Current Balance</div>
                                <div class="col-auto font-weight-bold" aria-labelledby="current-balance-label" aria-live="polite">$0.00</div>
                            </div>
                        </div>
                    </div>
                    <!-- Right section -->
                    <div class="col-lg-4">
                        <div class="drawer-actions-card" role="region" aria-labelledby="drawer-actions-title">
                            <span id="drawer-actions-title" class="sr-only">Cash Drawer Actions</span>
                            <div class="d-flex flex-column">
                                <a href="pos-cash-drawer-daily-open.php" class="btn btn-primary mb-2 action-btn" aria-label="Open store for the day">DAILY OPEN</a>
                                <a href="pos-cash-drawer-daily-close.php" class="btn btn-primary mb-2 action-btn" aria-label="Close store for the day">DAILY CLOSE</a>
                                <a href="pos-cash-drawer-manual-open.php" class="btn btn-primary mb-2 action-btn" aria-label="Manually open cash drawer">MANUAL OPEN</a>
                                <a href="#" class="btn btn-primary action-btn" aria-label="View cash drawer log">CASH DRAWER LOG</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Back Link -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                    <a href="index.php" class="back-link" aria-label="Back to POS Home">
                        <i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Cash Drawer Page Content Page End Here -->


    <!-- Cash Drawer Daily Open Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-daily-open-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12" role="region" aria-label="POS Cash Drawer Daily Open Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="cash-drawer-daily-open-title">Cash Drawer - Daily Open</h2>
                </div>
                <div class="daily-card-section" role="region" aria-labelledby="daily-open-section-title">
                    <p class="mb-3" id="daily-open-section-title">Enter your daily starting cash amounts to open your store.</p>
                    <!-- Currency Table -->
                    <div class="table-responsive" role="region" aria-labelledby="currency-table-title">
                        <table class="table currency-table text-left" aria-labelledby="currency-table-title">
                            <caption id="currency-table-title" class="sr-only">Cash Denominations Entry Table</caption>
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Denominations</th>
                                    <th scope="col">Enter Number</th>
                                    <th scope="col" class="value-col text-right">Value</th>
                                    <th scope="col" class="total-col text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Denomination Rows -->
                                <tr>
                                    <td id="label-pennies">Pennies</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-pennies" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.01</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-nickels">Nickels</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-nickels" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.05</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-dimes">Dimes</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-dimes" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.10</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-quarters">Quarters</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-quarters" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.25</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-ones">Ones</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-ones" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$1.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-fives">Fives</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-fives" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$5.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-tens">Tens</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-tens" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$10.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-twenties">Twenties</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-twenties" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$20.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-fifties">Fifties</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-fifties" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$50.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-hundreds">Hundreds</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-hundreds" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$100.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>

                                <!-- Totals -->
                                <tr class="opening-balance" role="row">
                                    <td colspan="3" id="label-cash-drawer-opening-balance" class="text-right font-weight-bold" scope="row">Cash Drawer Opening Balance:</td>
                                    <td class="text-right font-weight-bold" aria-labelledby="label-cash-drawer-opening-balance" aria-live="polite">$0.00</td>
                                </tr>
                                <tr class="previous-balance" role="row">
                                    <td colspan="3" id="label-previous-closing-balance" class="text-right font-weight-bold" scope="row">Previous Closing Balance:</td>
                                    <td class="text-right font-weight-bold" aria-labelledby="label-previous-closing-balance" aria-live="polite">$0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Notes -->
                    <p class="note mt-3 mb-0" id="drawer-note" aria-live="polite">
                        Please confirm your opening balance for your cash drawer. <span>Double check it.</span> Once entered, this cannot be adjusted.
                    </p>
                </div>

                <!-- Back Link and Submit Button -->
                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                    <a href="pos-cash-drawer.php" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                    </a>
                    <button type="submit" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit opening balance for cash drawer">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cash Drawer Daily Open Page Content Page End Here -->


    <!-- Cash Drawer Daily Close Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-daily-close-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12" role="region" aria-label="POS Cash Drawer Daily Close Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="cash-drawer-daily-close-title">Cash Drawer - Daily Close</h2>
                </div>

                <div class="daily-card-section" role="region" aria-labelledby="daily-close-section-title">
                    <h2 class="font-weight-semibold mb-3" id="daily-close-section-title">LGROWNB-007 - Texas - Houston Galleria</h2>
                    <!-- Currency Table -->
                    <div class="table-responsive" role="region" aria-labelledby="currency-table-title">
                        <table class="table currency-table text-left" aria-labelledby="currency-table-title">
                            <caption id="currency-table-title" class="sr-only">Cash Denominations Entry Table</caption>
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Denominations</th>
                                    <th scope="col">Enter Number</th>
                                    <th scope="col" class="value-col text-right">Value</th>
                                    <th scope="col" class="total-col text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Denomination Rows -->
                                <tr>
                                    <td id="label-pennies">Pennies</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-pennies" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.01</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-nickels">Nickels</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-nickels" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.05</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-dimes">Dimes</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-dimes" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.10</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-quarters">Quarters</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-quarters" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$0.25</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-ones">Ones</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-ones" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$1.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-fives">Fives</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-fives" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$5.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-tens">Tens</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-tens" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$10.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-twenties">Twenties</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-twenties" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$20.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-fifties">Fifties</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-fifties" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$50.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr>
                                    <td id="label-hundreds">Hundreds</td>
                                    <td><input type="number" class="form-control" value="0" aria-labelledby="label-hundreds" min="0" inputmode="numeric" /></td>
                                    <td class="text-right">$100.00</td>
                                    <td class="text-right">$0.00</td>
                                </tr>

                                <!-- Totals -->
                                <tr class="closing-balance" role="row">
                                    <td colspan="3" id="label-cash-drawer-closing-balance" class="text-right font-weight-bold" scope="row">Cash Drawer Closing Balance:</td>
                                    <td class="text-right font-weight-bold" aria-labelledby="label-cash-drawer-closing-balance" aria-live="polite">$0.00</td>
                                </tr>
                                <tr class="expected-balance" role="row">
                                    <td colspan="3" id="label-expected-current-balance" class="text-right font-weight-bold" scope="row">Expected Current Balance:</td>
                                    <td class="text-right font-weight-bold" aria-labelledby="label-expected-current-balance" aria-live="polite">$0.00</td>
                                </tr>
                                <tr class="fund-balance" role="row">
                                    <td colspan="3" id="label-cash-drawer-fund" class="text-right font-weight-bold" scope="row">Cash Drawer Fund:</td>
                                    <td class="text-right font-weight-bold" aria-labelledby="label-cash-drawer-fund" aria-live="polite">$0.00</td>
                                </tr>
                                <tr class="bank-deposit-balance" role="row">
                                    <td colspan="3" id="label-your-daily-bank-deposit" class="text-right font-weight-bold" scope="row">Your Daily Bank Deposit:</td>
                                    <td class="text-right font-weight-bold">
                                        <input type="number" class="form-control text-right font-weight-bold" value="0" aria-labelledby="label-your-daily-bank-deposit" min="0" inputmode="numeric" aria-required="true" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-warning d-flex align-items-start mt-3 mb-0" role="alert" aria-live="polite" aria-atomic="true">
                        <span class="mr-2" aria-hidden="true"><i class="bi bi-exclamation-triangle"></i></span>
                        <div class="alert-content">
                            <h2 class="h3 font-weight-semibold mb-1" id="manual-override-title">Manual Override</h2>
                            <span id="manual-override-desc">Are you sure to adjust daily deposit?</span>
                        </div>
                    </div>
                </div>

                <!-- Back Link and Submit Button -->
                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                    <a href="pos-cash-drawer.php" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                    </a>
                    <button type="submit" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit opening balance for cash drawer">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cash Drawer Daily Close Page Content Page End Here -->


    <!-- Cash Drawer Manual Open Page Content Page Start Here -->
    <div class="container pt-3 pb-md-5 pb-3 ppos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-manual-open-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                <div class="myact_box">
                    <div class="myact_user_name" aria-label="User Profile">
                        <span class="circle" aria-hidden="true">J</span>
                        <div class="text">
                            Hi,<span id="userName">John Doe</span>
                        </div>
                    </div>
                </div>
                <div class="myact_nav d-lg-none" role="button" aria-label="Toggle My Account Links" tabindex="0">
                    <svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
                        <use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
                    </svg>
                    <span>My Account Links</span>
                </div>
                <div class="myact_nav_cot">
                    <div class="myact_box">
                        <ul class="myact_left_link" role="list" aria-labelledby="userName">
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myaccount.html'}}" class="active" aria-current="page">
                                    <svg class="sv_account_overview" aria-hidden="true" role="img">
                                        <use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
                                    </svg>
                                    <span>Account Overview</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'order-history.html'}}">
                                    <svg class="sv_orders" aria-hidden="true" role="img">
                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'return-orders.html'}}">
                                    <svg class="sv_returns" aria-hidden="true" role="img">
                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                    </svg>
                                    <span>My Returns</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'myrewardpoint.html'}}">
                                    <svg class="sv_reward_points" aria-hidden="true" role="img">
                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                    </svg>
                                    <span>My Reward Points</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'wish-category.html'}}">
                                    <svg class="sv_wishlist" aria-hidden="true" role="img">
                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                    </svg>
                                    <span>My Wishlist</span>
                                </a>
                            </li>
                            <li role="listitem">
                                <a href="{{config('global.SITE_URL').'cancel-orders.html'}}">
                                    <svg class="sv_cancellation" aria-hidden="true" role="img">
                                        <use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
                                    </svg>
                                    <span>My Cancellation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12" role="region" aria-label="POS Cash Drawer Manual Open Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="cash-drawer-manual-open-title">Cash Drawer - Manual Open</h2>
                </div>
                <div class="manual-open-card-section" role="region" aria-labelledby="manual-open-section-title">
                    <div class="card-header mb-4">
                        <h2 class="font-weight-bold mb-1" id="manual-open-section-title">LGROWNB-007 - Texas - Houston Galleria</h2>
                        <p class="mb-0" id="manual-open-desc">Please fill out the details below to open cash drawer.</p>
                    </div>

                    <form class="manual-remarks-form" aria-labelledby="cash-drawer-manual-open-title manual-open-section-title manual-open-desc">
                        <div class="mb-4">
                            <label for="remarks" class="font-weight-semibold">Remarks:</label>
                            <textarea class="form-control" id="remarks" placeholder="Enter Remarks" aria-label="Remarks"></textarea>
                        </div>

                        <!-- Back Link and Submit Button -->
                        <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                            <a href="pos-cash-drawer.php" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
                                <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit form to manually open cash drawer">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Cash Drawer Manual Open Page Content Page End Here -->
</main>
@endsection