@extends('layouts.app')
@section('content')
<style>
    #reader img[alt="Info icon"] {
        display: none;
    }
    .dropzone.dz-clickable {
        border: 3px dashed #d3d3d3 !important;
    }
    #divimagereader{
        /* padding-top:5px; */
        display: none;
    }
    .optbuttons{
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        border: 1px solid #dee2e6;
        border-bottom: none;
    }
    .maincamdiv{
        display: inline-flex;
    }
    .divactive{
        background: #000;
    }
    .divactive a{
        color:#fff
    }
    .optbuttons a i{padding-right:5px;}
</style>
<script type="text/javascript" src="{{url("/public/js/core/html5-qrcode.min.js")}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<main class="pos-page" role="main" aria-label="POS Page">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
            <a href="{{url('store/store-dashboard.html')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">POS</span>
        </div>
    </section>
    <!-- New Order Page Content Page Start Here -->
    <div class="container pt-lg-5 pt-2 pb-5 pos-new-order-main" role="region" aria-labelledby="pos-new-order-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12 pt-lg-0 pt-3" role="region" aria-label="POS New Order Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-new-order-title">POS New Order</h2>
                </div>

                <div class="row">
					<div class="col-12" role="status" aria-live="polite" id="show_success_msg" style="display:none;">
						<!--<div class="alert alert-success p-2">Product added to cart.</div>-->
					</div>
                    <div class="col-12" role="status" aria-live="polite" id="show_error_msg" style="display:none;">
						<!--<div class="alert alert-success p-2">Product added to cart.</div>-->
					</div>
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
                                <!--<p>Status: <strong class="text-success">CONNECTED</strong></p>-->
                                <p>Status: <strong class="text-danger" id="tabContent_Barcode">NOT CONNECTED</strong></p>
                                <p class="mb-0">Please connect your barcode scanner to proceed. Ensure the device is properly configured and recognized by the system.</p>
                            </div>
                            <!-- Scan via Camera Tab -->
                            <div class="tab-pane fade status-card" id="tab-camera" role="tabpanel" aria-labelledby="tab-camera-tab">
                                <!-- <h2 class="font-weight-bold mb-3">Camera Scanner</h2> -->
                                <div class="maincamdiv">
                                    <div class="optbuttons divactive">
                                        <a href="javascript:void(0);" id="btncam"><i class="bi bi-camera-fill" area-hidden="true"></i> Camera</a>
                                    </div>
                                    <div class="optbuttons">
                                        <a href="javascript:void(0);" id="btnimg"><i class="bi bi-card-image" area-hidden="true"></i> Image File</a>
                                    </div>
                                </div>
                                <form action="" method="post" id="frmstoreAddToCartViaCameraScanner">
                                    <!-- <label class="">Recent Scan: <span id="recent_scan"></span></label> -->
                                    <label id="error_skuval" class="error-cls d-none"></label>
                                    <label id="success_skuval" class="success-cls d-none"></label>
                                    @if ($errors->has('skuval'))
                                    <x-message :attr="[ 'classname' => 'frmerror mt', 'message' => '', 'mid' => 'error_skuval']" />
                                    @endif
                                </form>
                                <div class="form-group">
                                    <div id="divreader">
                                        <div style="width:100%;" id="reader"></div>
                                    </div>
                                    <!-- <div style="padding-top:5px;"><hr></div> -->
                                    <div id="divimagereader">
                                        <form id="barcodeDropzone" class="dropzone border rounded p-4 text-center">
                                            <div class="dz-message">
                                                Drag & Drop Barcode Image<br>OR<br>Click to Upload
                                            </div>
                                        </form>
                                        {{--<input type="file" id="uploadBarcode" accept="image/*" class="form-control">--}}
                                    </div>
                                </div>
                                {{--
                                <p>Status: <strong class="text-warning">CAMERA NOT ENABLED</strong></p>
                                <p class="mb-0">Please allow camera access in your browser settings to scan using your device camera.</p>
                                --}}
                            </div>
                            <!-- Enter Manually Tab -->
                            <div class="tab-pane fade status-card" id="tab-manual" role="tabpanel" aria-labelledby="tab-manual-tab">
                                <form action="" method="post" id="frmstoreAddToCart">
                                    <h2 class="font-weight-bold mb-3">Enter Manually</h2>
                                    <label class="font-weight-medium" for="manual-product-id">Product Title / SKU / UPC Number <span class="text-danger">*</span></label>
                                    <div class="custom-input-flex input-clear-wrapper">
                                        <input type="text" id="skuval" name="skuval" class="form-control" placeholder="Enter product identifier" aria-required="true" aria-label="Product SKU, ERP SKU, or UPC Number">
                                        <div class="autocomplete_icons">
                                            <i class="bi bi-x clear-input"></i>
                                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                                        <span class="input-loader"></span></div>
                                        <button class="btn btn-dark-custom d-none" aria-label="Add to bag" id="AddToCartStore">
                                            <i class="bi bi-cart-plus mr-2" aria-hidden="true"></i>Add to bag
                                        </button>
                                    </div>
                                    <x-message :attr="[ 'classname' => 'frmerror mt', 'message' => '', 'mid' => 'error_skuval']" />
                                    <div class="note" id="sp_note"></div>
                                </form>
                            </div>
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
    <!-- New Order Page Content Page End Here -->
</main>

@endsection
