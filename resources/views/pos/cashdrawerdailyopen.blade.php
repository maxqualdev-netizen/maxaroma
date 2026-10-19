@extends('layouts.app')
@section('content')

<main class="pos-page" role="main" aria-label="Cash Drawer Daily Open Page">
	<section class="breadcrumb" aria-label="Breadcrumb">
		<div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
			<a href="{{config('global.SITE_URL')}}" aria-label="Home">Home</a>
			<a href="{{config('global.SITE_URL')}}store/store-dashboard.html" aria-label="Home">POS</a>
			<a href="{{config('global.SITE_URL')}}store/cash-drawer" aria-label="Home">Cash Drawer</a>
			<span class="active" aria-current="page">Cash Drawer Daily Open</span>
		</div>
	</section>
	
	<div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="cash-drawer-daily-open-title">
		<div class="row">
			
			<div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
			
			<div class="col-lg-9 col-md-12 col-xs-12  pt-3 pt-lg-3" role="region" aria-label="POS Cash Drawer Daily Open Management">
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
					<h2 class="posmain-hd" id="cash-drawer-daily-open-title">Cash Drawer - Daily Open</h2>
				</div>
				<div class="daily-card-section" role="region" aria-labelledby="daily-open-section-title">
					
					@php
					$readonly_dopen = 'readonly';
					if($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null))
					{
						$readonly_dopen = '';
					}
					@endphp
					
					@if($is_balance_matched == 'yes')
						<p class="mb-3 alert alert-success p-2" id="daily-open-section-title">Your opening balance matches your previous closing balance, and this amount has been recorded for your store opening.</p>
					@elseif($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null))
						<p class="mb-3" id="daily-open-section-title">Enter your daily starting cash amounts to open your store.</p>
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
				
					   <form method="post" name="frmCashDrawerOB" id="frmCashDrawerOB" action="{{ url('store/save-cash-drawer-daily-open') }}">
					   {{ csrf_field() }}
					   <input type="hidden" name="act_type" id="act_type" value="{{ $act_type }}">
					   <input type="hidden" name="type" id="type" value="open">
					   <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
					   <input type="hidden" name="store_cash_drawer_id" id="store_cash_drawer_id" value="{{ $store_cash_drawer_id }}">
					  
							  
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
														<td><input name="{{ $key }}" type="number" class="form-control" value="{{ $value['count'] }}" aria-labelledby="label-{{strtolower($value['label'])}}" min="0" inputmode="numeric"  data-conversion-value="{{ $value['conv_value'] }}" autocomplete="off" {{ $readonly_dopen }} /></td>
														<td class="text-right">{{ (isset($value['conv_value']) && $value['conv_value'] != '') ? '$'.$value['conv_value'] : '' }}</td>
														<td class="row_total text-right">{{ (isset($value['row_total']) && $value['row_total'] != '') ? Price($value['row_total'],'yes','yes') : '' }}</td>
													</tr>
												@endforeach
											@endif
											
											<tr class="opening-balance" role="row">
												<td colspan="3" id="label-cash-drawer-opening-balance" class="text-right font-weight-bold" scope="row">Cash Drawer Opening Balance:</td>
												<td class="text-right font-weight-bold grand_total" aria-labelledby="label-cash-drawer-opening-balance" aria-live="polite">{{ Price($total_balance,'yes','yes') }}</td>
											</tr>
											<tr class="previous-balance" role="row">
												<td colspan="3" id="label-previous-closing-balance" class="text-right font-weight-bold" scope="row">Previous Closing Balance:</td>
												<td class="text-right font-weight-bold" aria-labelledby="label-previous-closing-balance" aria-live="polite">{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
											</tr>
											
										</tbody>
									</table>
								</div>
								@if($is_balance_matched == null || ($is_balance_matched == 'no' && $is_deposit_adjusted == null))
								<p class="note mt-3 mb-0" id="drawer-note" aria-live="polite">
								Please confirm your opening balance for your cash drawer. <span>Double check it.</span> Once entered, this cannot be adjusted.
								</p>
								@endif
							
							@if(($is_balance_matched == null || $is_balance_matched != 'yes') && $is_deposit_adjusted == null)
							<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4 {{ (($is_balance_matched == null || $is_balance_matched != 'yes') && $is_deposit_adjusted == null) ? '' : 'd-none' }}" role="group" aria-label="Navigation and Actions" >
								<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to Cash Drawer">
									<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
								</a>
								<a href="javascript:void(0);" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit opening balance for cash drawer" onclick="submitformcash('frmCashDrawerOB');">SUBMIT</a>
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
					<form method="post" name="frmCashDrawerManuallyAdjust" id="frmCashDrawerManuallyAdjust" action="{{ url('store/save-cash-drawer-daily-open') }}">
					   {{ csrf_field() }}
					   <input type="hidden" name="act_type" id="act_type" value="manually_adjust">
					   <input type="hidden" name="type" id="type" value="open">
					   <input type="hidden" name="store_id" id="store_id" value="{{ $store_id }}">
					   <input type="hidden" name="store_cash_drawer_id" id="store_cash_drawer_id" value="{{ $store_cash_drawer_id }}">
					   <input type="hidden" name="adjustment_amount" id="adjustment_amount" value="{{ $adjustment_amount }}">
					   
						@if($is_deposit_adjusted == null)
							{{--<!--<h5 class="mt-4 mb-4">{{Auth::user()->store->store_code}} - {{Auth::user()->store->store_name}}</h5>-->--}}
							<p class="mb-3 alert alert-danger p-2">Your opening balance does NOT MATCH your previous closing balance. Please <a href="{{config('global.SITE_URL')}}store/cash-drawer-daily-open?act_type=update"><strong>re-check</strong></a> your count and then submit.</p>
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
								<td class="text-left pl-2 pr-2" width="50%">Previous Closing Balance:</td>
								<td class="" width="50%">{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
							</tr>
							<tr class="text-center" height="50">
								<td class="text-left pl-2 pr-2" width="50%">Added Amount:</td>
								<td class="" width="50%">
									{{ Price($total_balance,'yes','yes') }}
								</td>
							</tr>
							<tr class="text-center" height="50">
								<td class="text-left pl-2 pr-2" width="50%"><strong>Adjust Amount{{ $adjustment_type != '' ? ' ('.ucfirst($adjustment_type).')' : '' }}:</strong></td>
								<td class="" width="50%"><strong>{{ Price($adjustment_amount,'yes','yes') }}</strong></td>
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
										<td>Previous Closing Balance:</td>
										<td>{{ Price($expected_cash_drawer_fund,'yes','yes') }}</td>
									</tr>
									<tr>
										<td>Added Amount:</td>
										<td>{{ Price($total_balance,'yes','yes') }}</td>										
									</tr>
									<tr>
										<td><strong>Adjust Amount{{ $adjustment_type != '' ? ' ('.ucfirst($adjustment_type).')' : '' }}:</strong></td>
										<td><strong>{{ Price($adjustment_amount,'yes','yes') }}</strong></td>
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
								<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions" >
									<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to Cash Drawer">
									<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
									</a>
									<a href="javascript:void(0);" class="btn btn-primary btn-submit mb-4 mb-md-0 order-1 order-md-2" aria-label="Submit opening balance for cash drawer" onclick="submitformcash('frmCashDrawerManuallyAdjust');">SUBMIT</a>
								</div>
							@endif
						@else
							@if($is_balance_matched != 'yes')
							<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions" >
								<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to Cash Drawer">
									<i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Cash Drawer
								</a>
							</div>
							@endif
						@endif	
							
							
						  
					</form>
				@endif
				@endif
				
				@if($is_balance_matched == 'yes')
				<div class="d-flex flex-md-row flex-column justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions" >
					<a href="{{config('global.SITE_URL')}}store/cash-drawer" class="back-link order-2 order-md-1" aria-label="Back to Cash Drawer">
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

 <!--<style>
 .lightbox {
    display: none;
    position: fixed;
    z-index: 1;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.box {
    position: absolute;
    padding: 10px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #333;
    color: #fff;
}

h2, .box p {
  margin: 0 20px;
}

.close {
    position: absolute;
    left: 100%;
    top: 0;
    margin: -10px 0 0 -10px;
    width: 20px;
    height: 20px;
    color: #fff;
    font-size: 13px;
    font-weight: bold;
    text-align: center;
    border-radius: 50%;
    background-color: #5c5c5c;
    cursor: pointer;
    text-decoration: none;
}

</style>-->
<!--<div class="lightbox">
  <div class="box">
      <h2>Please, don't leave us! </h2>
        <p>This is a simple Exit Intent Popup example ;) <a href="#" class="close">X</a></p>
  </div>
</div>-->

  
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
	
	$(document).ready(function() {
		let userAgent = navigator.userAgent;
		if (userAgent.includes('StarWebPRNTBrowser')) {
			
			if ($("#hardware_status").length) {
				$("#hardware_status").val(curValue1+ '\n' + 'Star WebPRNT Browser detected');
			}
			
			storeDeviceLog(1,'testdeviceName','Normal','Star WebPRNT Browser detected - Daily Open Page','No');
			
			setTimeout(onConnect(),1000);
			setTimeout(onPrint(),2000);
		} else {
			if ($("#hardware_status").length) {
				$("#hardware_status").val(curValue1+ '\n' + 'Star WebPRNT Browser NOT detected');
			}
			
			storeDeviceLog(1,'testdeviceName','Error','Star WebPRNT Browser NOT detected - Daily Open Page','No');
		}
		
	});
	
	var actionVal = "{{$act_type=='add'?'Daily Open':'Daily Open Re-check'}}";
	/*var hasCashPayment		= '1';
	var actionNotes		= '';
	var cd_amount		= 0;*/
	
</script>
@endif

@endsection
