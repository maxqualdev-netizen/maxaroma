@extends('layouts.app')
@section('content')

<main class="pos-page" role="main" aria-label="Cash Drawer Daily C Page">
	<section class="breadcrumb" aria-label="Breadcrumb">
		<div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
			<a href="{{config('global.SITE_URL')}}" aria-label="Home">Home</a>
			<a href="{{config('global.SITE_URL')}}store/store-dashboard.html" aria-label="Home">POS</a>
			<a href="{{config('global.SITE_URL')}}store/cash-drawer" aria-label="Home">Cash Drawer</a>
			<span class="active" aria-current="page">Cash Drawer Daily Close</span>
		</div>
	</section>
	
	<div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-daily-close-title">
		<div class="row">
			
			<div class="col-lg-3 col-md-12 col-xs-12 " role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
			
			<div class="col-lg-9 col-md-12 col-xs-12 pt-3" role="region" aria-label="POS Cash Drawer Daily Open Management">
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="cash-drawer-daily-close-title">Cash Drawer - Daily Close</h2>
                </div>
                
                <div class="daily-card-section" role="region" aria-labelledby="daily-close-section-title">
					@php
					$readonly_dopen = 'readonly';
					if($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null))
					{
						$readonly_dopen = '';
					}
					@endphp
					
					@if($is_balance_matched == 'yes')
						<p class="mb-3 alert alert-success p-2" id="cash-drawer-daily-close-title">Your closing balance matches the system cash total, and this amount has been recorded for your store closing. Store has been successfully closed.</p>
					@elseif($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null))
						<p class="mb-3" id="daily-open-section-title">Enter your daily ending cash amounts to close your store.</p>
					@endif
					  
					@if (count($errors) > 0)
						@foreach ($errors->all() as $error)
							<p class="mb-3 alert alert-danger p-2">{{ $error }}</p>
						@endforeach
					@endif
					
					@if (Session::has('success'))
						<p class="mb-3 alert alert-success p-2">{{ Session::get('success') }}</p>
					@endif
					
					
					@if($act_type != 'manually_adjust')
					<form method="post" name="frmCashDrawerOB" id="frmCashDrawerOB" action="{{ url('store/save-cash-drawer-daily-close') }}">
						{{ csrf_field() }}
						<input type="hidden" name="act_type" id="act_type" value="{{ $act_type }}">
						<input type="hidden" name="type" id="type" value="close">
						<input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
						<input type="hidden" name="store_cash_drawer_id" id="store_cash_drawer_id" value="{{ $store_cash_drawer_id }}">
						<input type="hidden" name="cash_drawer_fund" id="cash_drawer_fund" value="{{ (isset($cash_drawer_fund) && $cash_drawer_fund != '')  ? $cash_drawer_fund : '0.00' }}">
						<input type="hidden" name="grand_total" id="grand_total" value="{{ $total_balance }}">
						<input type="hidden" name="store_cash_drawer_fund" id="store_cash_drawer_fund" value="{{ $store_cash_drawer_fund }}">
						<input type="hidden" name="bank_deposit" id="bank_deposit" value="{{ isset($bank_deposit)  ? $bank_deposit : '' }}">
						
						{{--<!--<h5 class="mt-4 mb-4">{{Auth::user()->store->store_code}} - {{Auth::user()->store->store_name}}</h5>-->--}}
						
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
											<td><input type="number" name="{{ $key }}" class="form-control" value="{{ $value['count'] }}" aria-labelledby="label-{{strtolower($value['label'])}}" data-conversion-value="{{ $value['conv_value'] }}" min="0" inputmode="numeric" autocomplete="off" {{ $readonly_dopen }} /></td>
											<td class="text-right">{{ (isset($value['conv_value']) && $value['conv_value'] != '') ? '$'.$value['conv_value'] : '' }}</td>
											<td class="row_total text-right">{{ (isset($value['row_total']) && $value['row_total'] != '') ? Price($value['row_total'],'yes','yes') : '' }}</td>
										</tr>
										@endforeach
									@endif
									
									<tr class="closing-balance" role="row">
										<td colspan="3" id="label-cash-drawer-closing-balance" class="text-right font-weight-bold" scope="row">Cash Drawer Closing Balance:</td>
										<td class="grand_total text-right font-weight-bold" aria-labelledby="label-cash-drawer-closing-balance" aria-live="polite">{{ Price($total_balance,'yes','yes') }}</td>
									</tr>
									<tr class="expected-balance" role="row">
										<td colspan="3" id="label-expected-current-balance" class="text-right font-weight-bold" scope="row">Expected Current Balance:</td>
										<td class="text-right font-weight-bold" aria-labelledby="label-expected-current-balance" aria-live="polite">{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
									</tr>
									
									<tr class="fund-balance" role="row">
										<!--<td colspan="2" rowspan="2" class="text-left font-weight-bold" scope="row">@if($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null)) Please confirm your bank deposit and balance for your cash drawer. Double check it. Once entered, this cannot be adjusted. @endif</td>-->
										<td colspan="3" id="label-cash-drawer-fund" class="text-right font-weight-bold" scope="row">Cash Drawer Fund:</td>
										<td class="cash_drawer_fund text-right font-weight-bold" aria-labelledby="label-cash-drawer-fund" aria-live="polite">{{ (isset($cash_drawer_fund) && $cash_drawer_fund != '')  ? Price($cash_drawer_fund,'yes','yes') : '$0.00' }}</td>
									</tr>
									<tr class="bank-deposit-balance" role="row">
										<td colspan="3" id="label-your-daily-bank-deposit" class="text-right font-weight-bold" scope="row">Your Daily Bank Deposit:</td>
										<td class="text-right font-weight-bold">
											<input name="disp_bank_deposit" id="disp_bank_deposit" type="text" class="form-control text-right font-weight-bold" value="{{ isset($bank_deposit)  ? Price($bank_deposit,'yes','yes') : '' }}" autocomplete="off" {{ $readonly_dopen }} aria-labelledby="label-your-daily-bank-deposit" min="0" inputmode="numeric" aria-required="true" />
										</td>
									</tr>
									
								</tbody>
							</table>
						</div>
						
						@if($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null)) 
						<div class="alert alert-warning d-flex align-items-start mt-3 mb-0" role="alert" aria-live="polite" aria-atomic="true">
							<span class="mr-2" aria-hidden="true"><i class="bi bi-exclamation-triangle"></i></span>
							<div class="alert-content">
								<h2 class="h3 font-weight-semibold mb-1" id="manual-override-title">Bank Deposit</h2>
								<span id="manual-override-desc">Please confirm your bank deposit and balance for your cash drawer. Double check it. Once entered, this cannot be adjusted.</span>
							</div>
						</div>
						@endif
						
						@if(($is_balance_matched == null || $is_balance_matched != 'yes') && $is_deposit_adjusted == null)
						<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
							<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
								<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
							</a>
							<a href="javascript:void(0);" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit closing balance for cash drawer" onclick="submitformcash('frmCashDrawerOB');">SUBMIT</a>
						</div>
						@endif
						
					</form>
					@endif
					
					@php
					$readonly_madjust = 'readonly';
					$readonly_madjust_sbox = 'style=pointer-events:none;';
					if($is_balance_matched == 'no' && $is_deposit_adjusted == null)
					{
					$readonly_madjust = '';
					$readonly_madjust_sbox = '';
					}
					@endphp

					@if($is_balance_matched == 'no')
					@if($act_type == 'manually_adjust' || $act_type == 'view')
					<form method="post" name="frmCashDrawerManuallyAdjust" id="frmCashDrawerManuallyAdjust" action="{{ url('store/save-cash-drawer-daily-close') }}">
						{{ csrf_field() }}
						<input type="hidden" name="act_type" id="act_type" value="{{ $act_type }}">
						<input type="hidden" name="type" id="type" value="close">
						<input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
						<input type="hidden" name="store_cash_drawer_id" id="store_cash_drawer_id" value="{{ $store_cash_drawer_id }}">
						<input type="hidden" name="adjustment_amount" id="adjustment_amount" value="{{ $adjustment_amount }}">
						
						@if($is_deposit_adjusted == null)
							{{--<!--<h3 class="mt-4 mb-4">{{Auth::user()->store->store_code}} - {{Auth::user()->store->store_name}}</h3>-->--}}
							<p class="mb-3 alert alert-danger p-2">Your closing balance does NOT MATCH to the system cash total. Please <a href="{{config('global.SITE_URL')}}store/cash-drawer-daily-close?act_type=update"><strong>re-check</strong></a> your count and then submit.</p>
						@endif
						
						<div class="table-responsive" role="region" aria-labelledby="manual-override-table-title">
							
							<!--<table class="text-center" role="presentation" width="100%" border="1" cellspacing="0" cellpadding="0">
								<tr height="40">
									<th class="text-center pl-4 pr-4" colspan="2">Manual Override</td>
								</tr>
								<tr class="text-center" height="50">
									<td class="text-left pl-2 pr-2" colspan="2">Are you sure to adjust daily deposit?</td>
								</tr>
								<tr class="text-center" height="50">
									<td class="text-left pl-2 pr-2" width="50%">Expected Current Balance:</td>
									<td class="" width="50%">{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
								</tr>
								<tr class="text-center" height="50">
									<td class="text-left pl-2 pr-2" width="50%">Added Amount:</td>
									<td class="" width="50%">
										{{ Price($total_balance,'yes','yes') }}
									</td>
								</tr>
								<tr class="text-center" height="50">
									<td class="text-left pl-2 pr-2" width="50%">Adjust Amount{{ $adjustment_type != '' ? ' ('.ucfirst($adjustment_type).')' : '' }}:</td>
									<td class="" width="50%">{{ Price($adjustment_amount,'yes','yes') }}</td>
								</tr>
								<tr class="text-center" height="50" id="adjustment_reason_tr">
									<td class="text-left pl-2 pr-2" width="50%">Reason for Manual Adjustment:</td>
									<td class="text-left" width="50%">
										<!--Two twenties must have been stuck together.-->
										<!--<div class="form-group mb-0">
											<textarea type="text" id="adjustment_reason" name="adjustment_reason" class="form-control" placeholder="Enter Reason for Manual Adjustment" cols="30" rows="2" {{ $readonly_madjust }}>{{ $adjustment_reason }}</textarea>
										</div>
									</td>
								</tr>-->
								
							
							<table class="table currency-table text-left" aria-labelledby="manual-override-table-title">
								<thead class="thead-light">
									<tr>
										<th scope="col" colspan="2">Manual Override</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="2">Are you sure to adjust daily deposit?</td>
									</tr>
									<tr>
										<td>Expected Current Balance:</td>
										<td>{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
									</tr>
									<tr>
										<td>Added Amount:</td>
										<td>{{ Price($total_balance,'yes','yes') }}</td>
									</tr>
									<tr>
										<td>Adjust Amount{{ $adjustment_type != '' ? ' ('.ucfirst($adjustment_type).')' : '' }}:</td>
										<td>{{ Price($adjustment_amount,'yes','yes') }}</td>
									</tr>
									<tr id="adjustment_reason_tr">
										<td>Reason for Manual Adjustment:</td>
										<td>
											<!--Two twenties must have been stuck together.-->
											<div class="form-group mb-0">
												<textarea type="text" id="adjustment_reason" name="adjustment_reason" class="form-control" placeholder="Enter Reason for Manual Adjustment" cols="30" rows="2" {{ $readonly_madjust }}>{{ $adjustment_reason }}</textarea>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						@if($is_deposit_adjusted == null)
							@if($is_balance_matched != 'yes')
							<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
								<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
									<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
								</a>
								<a href="javascript:void(0);" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit closing balance for cash drawer" onclick="submitformcash('frmCashDrawerManuallyAdjust');">SUBMIT</a>
							</div>
							@endif
						@else
							@if($is_balance_matched != 'yes')
							<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
								<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
									<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
								</a>
							</div>
							@endif
						@endif
					
					</form>
					@endif
					@endif
					
					@if($is_balance_matched == 'yes')
					<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
						<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to POS Cash Drawer">
							<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
						</a>
					</div>
					@endif
					
					<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="region" aria-label="Hardware Status Details" >
						<textarea type="text" id="hardware_status" name="hardware_status" class="form-control" placeholder="Hardware Status" cols="30" rows="2"></textarea>
					</div>
					
				</div>
                
			</div>
		</div>
	</div>
</main>


<span id="cashdrawer_status" class="posstatus-span d-none" style="color:red;"></span>

<!--<script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}pos_cashdrawer.js"></script>-->

<script>
	var cashDrawerOpen = 0;
</script>

@if($act_type == 'add' || $act_type == 'update')
<!--<input type="hidden" id="qd_pos_load_source" value="">
<input type="hidden" id="qd_cash_drawer_receive_event" value="1">-->

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
			
			alert("Star WebPRNT Browser detected");
			storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Daily Close Page','No');
			
			setTimeout(onConnect(),5000);
			setTimeout(onPrint(),10000);
		} else {
			if ($("#hardware_status").length) {
				$("#hardware_status").val(curValue1+ '\n' + 'Star WebPRNT Browser NOT detected');
			}
			
			alert("Star WebPRNT Browser NOT detected");
			storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Daily Close Page','No');
		}
	}
	
	var actionVal = "{{$act_type=='add'?'Daily Closeout':'Daily Closeout Re-check'}}";
	/*var hasCashPayment		= '1';
	var actionNotes		= '{}';
	var cd_amount		= 0;*/
	
</script>
@endif

@endsection
