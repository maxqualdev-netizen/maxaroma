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
                        <!--<form method="post" name="frmCashDrawerManualOpen" id="frmCashDrawerManualOpen" action="{{ url('store/save-cash-drawer-manual-open') }}" class="manual-remarks-form" aria-labelledby="cash-drawer-manual-open-title manual-open-section-title manual-open-desc">-->
                        <form method="post" name="frmCashDrawerOB" id="frmCashDrawerOB" action="{{ url('store/save-cash-drawer-manual-open') }}" class="manual-remarks-form" aria-labelledby="cash-drawer-manual-open-title manual-open-section-title manual-open-desc">
                            {{ csrf_field() }}
                            <input type="hidden" name="act_type" id="act_type" value="{{ $act_type }}">
                            <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
                            <input type="hidden" name="store_cash_drawer_id" id="store_cash_drawer_id" value="{{ $store_cash_drawer_id }}">
                            <input type="hidden" name="expected_cash_drawer_fund" id="expected_cash_drawer_fund" value="{{ $expected_cash_drawer_fund }}">
                            <input type="hidden" name="cd_opening_balance" id="cd_opening_balance" value="{{ $total_balance }}">
                            <input type="hidden" name="store_manual_open_cash" id="store_manual_open_cash" value="{{ $store_cash_manual }}">
                            <input type="hidden" name="current_balance" id="current_balance" value="{{ $current_balance }}">
							
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
											@if(count($us_currencies) > 0)
												@foreach($us_currencies as $key => $value)
													<tr>
														<td id="label-{{strtolower($value['label'])}}">{{ $value['label'] }}</td>
														<td><input name="{{ $key }}" type="number" class="form-control" value="{{ $value['count'] }}" aria-labelledby="label-{{strtolower($value['label'])}}" min="0" inputmode="numeric"  data-conversion-value="{{ $value['conv_value'] }}" autocomplete="off" /></td>
														<td class="text-right">{{ (isset($value['conv_value']) && $value['conv_value'] != '') ? '$'.$value['conv_value'] : '' }}</td>
														<td class="row_total text-right">{{ (isset($value['row_total']) && $value['row_total'] != '') ? Price($value['row_total'],'yes','yes') : '' }}</td>
													</tr>
												@endforeach
											@endif
											
											<!--<tr class="opening-balance" role="row">
												<td colspan="3" id="label-cash-drawer-opening-balance" class="text-right font-weight-bold" scope="row">Cash Drawer Opening Balance:</td>
												<td class="text-right font-weight-bold" aria-labelledby="label-cash-drawer-opening-balance" aria-live="polite">
													{{ Price($store_open_balance,'yes','yes') }}
												</td>
											</tr>-->
											<tr class="current-balance" role="row">
												<td colspan="3" id="label-current-balance" class="text-right font-weight-bold" scope="row">
													Current Balance:
												</td>
												<td class="text-right font-weight-bold" aria-labelledby="label-current-balance" aria-live="polite">
													{{--{{ Price($total_balance,'yes','yes') }}--}}
													{{ Price($current_balance,'yes','yes') }}
												</td>
											</tr>
											<tr class="manual-opening-balance" role="row">
												<td colspan="3" id="label-cash-drawer-manual-opening-balance" class="text-right font-weight-bold" scope="row">
													Manual Opening Balance:
												</td>
												<td class="text-right font-weight-bold manual_total" aria-labelledby="label-cash-drawer-manual-opening-balance" aria-live="polite">
													{{ $set_store_cash_manual }}
												</td>
											</tr>
											<tr class="updated-manual-opening-balance" role="row">
												<td colspan="3" id="label-cash-drawer-updated-manual-opening-balance" class="text-right font-weight-bold" scope="row">Updated Balance:</td>
												
												<td class="text-right font-weight-bold updated_manual_total" aria-labelledby="label-cash-drawer-updated-manual-total" aria-live="polite">
													{{ Price($current_balance,'yes','yes') }}
												</td>
											</tr>
											<!--<tr class="previous-balance" role="row">
												<td colspan="3" id="label-previous-closing-balance" class="text-right font-weight-bold" scope="row">Previous Closing Balance:</td>
												<td class="text-right font-weight-bold" aria-labelledby="label-previous-closing-balance" aria-live="polite">	
													{{ Price($expected_cash_drawer_fund,'yes','yes') }}
												</td>
											</tr>-->
										</tbody>
									</table>
								</div>
							
                            <div class="form-group mb-4">
                                <label for="transaction_remark" class="font-weight-semibold">Remarks:</label>
                                <textarea name="transaction_remark" class="form-control" id="transaction_remark" placeholder="Enter Remarks" aria-label="Remarks">{{ $transaction_remark != '' ? $transaction_remark : '' }}</textarea>
                            </div>

                            <!-- Back Link and Submit Button -->
                            @if($act_type == 'add')
                                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                                    <a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer" role="link">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                                    </a>
                                    <!--<button type="submit" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" onclick="submitform('frmCashDrawerManualOpen');" aria-label="Submit form to manually open cash drawer">SUBMIT</button>-->
                                    <button type="button" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" onclick="submitformcash('frmCashDrawerOB');" aria-label="Submit form to manually open cash drawer">SUBMIT</button>
                                </div>
                            @else
                                <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
                                    <a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link" aria-label="Back to POS Cash Drawer" role="link">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
                                    </a>
                                </div>
                            @endif
                        </form>
                        <div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="region" aria-label="Hardware Status Details" >
							<textarea type="text" id="hardware_status" name="hardware_status" class="form-control" placeholder="Hardware Status" cols="30" rows="2"></textarea>
						</div>
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
		//onPrint();
	</script>
@endif

<script>
if ($("#hardware_status").length) {
	var curValue1 = $("#hardware_status").val();
}else{
	var curValue1 = '';
}
	
window.onload = function () {
	let userAgent = navigator.userAgent;
	if (userAgent.includes('StarWebPRNTBrowser')) {
		
		if ($("#hardware_status").length) {
			$("#hardware_status").val(curValue1+ '\n' + 'Star WebPRNT Browser detected');
		}
		
		//alert("Star WebPRNT Browser detected");
		storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Manual Open Page','No');
		
		setTimeout(onConnect(),5000);
		setTimeout(onPrint(),10000);
	} else {
		if ($("#hardware_status").length) {
			$("#hardware_status").val(curValue1+ '\n' + 'Star WebPRNT Browser NOT detected');
		}
		
		//alert("Star WebPRNT Browser NOT detected");
		storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Manual Open Page','No');
	}
	//console.log("inCreatingMnager");
	
	/*if(isset(manager)){
		alert("already manager is set");
	}else{
		alert("setting manager now");*/
		//var manager = new StarWebPrintExtManager();
	//}
}
	
</script>

@endsection
