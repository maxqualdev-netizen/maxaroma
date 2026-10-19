@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-label="POS Page">
    <div class="container pt-3 pb-md-5 pb-3 ppos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-manual-open-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12 pt-3" role="region" aria-label="POS Cash Drawer Manual Open Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="cash-drawer-manual-open-title">Cash Drawer - Manual Open</h2>
                </div>
                    
                @php
                $readonly_tfield = 'readonly';
                $readonly_ttype_sbox = 'style=pointer-events:none;';
                if($act_type == 'add')
                {
                    $readonly_tfield = '';
                    $readonly_ttype_sbox = '';
                }
                @endphp
                <div id="cd-dailyopen-container">
                    <div class="manual-open-card-section" role="region" aria-labelledby="manual-open-section-title">
                        <div class="card-header mb-4">
                            <h2 class="font-weight-bold mb-1" id="manual-open-section-title">{{$store_name}}</h2>
                            @if (count($errors) > 0)
                                <div class="alert alert-danger p-2" role="alert" aria-live="assertive">
                                    <ul class="list-unstyled" aria-label="Error messages">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (Session::has('success'))
                                <div class="alert alert-success p-2" role="status" aria-live="polite">{{ Session::get('success') }}</div>
                            @endif

                            @if (isset($success_msg) && $success_msg != '')
                                <div class="alert alert-success p-2" role="status" aria-live="polite">{{ $success_msg }}</div>
                            @endif

                            @if($act_type == 'add')
                                <p class="mb-0" id="manual-open-desc">Please fill out the details below to open cash drawer.</p>
                            @endif
                        </div>
                        <form method="post" name="frmCashDrawerManualOpen" id="frmCashDrawerManualOpen" action="{{ url('store/save-cash-drawer-manual-open') }}" class="manual-remarks-form" aria-labelledby="cash-drawer-manual-open-title manual-open-section-title manual-open-desc">
                            {{ csrf_field() }}
                            <input type="hidden" name="act_type" id="act_type" value="{{ $act_type }}">
                            <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">

                            <div class="form-group mb-4">
                                <label for="transaction_remark" class="font-weight-semibold">Remarks:</label>
                                <textarea name="transaction_remark" class="form-control" id="transaction_remark" placeholder="Enter Remarks" aria-label="Remarks" {{ $readonly_tfield }}>{{ $transaction_remark != '' ? $transaction_remark : '' }}</textarea>
                            </div>

                            <!-- Back Link and Submit Button -->
                            @if($act_type == 'add')
                                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                                    <a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer" role="link">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" onclick="submitform('frmCashDrawerManualOpen');" aria-label="Submit form to manually open cash drawer">SUBMIT</button>
                                </div>
                            @else
                                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                                    <a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link" aria-label="Back to POS Cash Drawer" role="link">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<span id="cashdrawer_status" class="posstatus-span d-none" style="color:red;" aria-live="polite"></span>
@if($act_type == 'view')
	<input type="hidden" id="qd_pos_load_source" value="">
	<input type="hidden" id="qd_cash_drawer_receive_event" value="1">
	<script>
		{{--<!--var transaction_type = "{{ $transaction_type }}";-->--}}
		{{--<!--var transaction_amount = "{{ $transaction_amount }}";-->--}}
		var transaction_remark = "{{ $transaction_remark }}";
		
		var actionVal = 'Manual';
		var hasCashPayment		= '1';
		var cd_amount		= 0; 
		//var actionNotes		= '';
		//var actionNotes		= '{"cd_amount":'+(transaction_type == "credit" ? parseFloat(transaction_amount) : (parseFloat(transaction_amount) * -1))+', "remark":"'+transaction_remark+'"}';
		var actionNotes		= '{"remark": "'+transaction_remark+'", "action": "Manual - Open"}';
		onPrint();
	</script>
@endif

@endsection
