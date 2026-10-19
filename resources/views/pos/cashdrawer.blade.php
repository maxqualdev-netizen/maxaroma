@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-label="POS Page">
	<div class="container pt-3 pb-md-5 pb-3 pos-cash-drawer-main" role="region" aria-labelledby="pos-cash-drawer-title">
		<div class="row">
			<div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
			<div class="col-lg-9 col-md-12 col-xs-12  pt-3 pt-lg-3" role="region" aria-label="POS Cash Drawer Management">
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
					<h2 class="posmain-hd" id="pos-cash-drawer-title">Cash Drawer</h2>
				</div>

				<div class="row">
					@if (count($errors) > 0)
						<div class="col-12" role="alert" aria-live="assertive">
							<div class="alert alert-danger p-2">
								<ul class="list-unstyled" aria-label="Error messages">
									@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif
					@if (Session::has('success'))
						<div class="col-12" role="status" aria-live="polite">
							<div class="alert alert-success p-2">{{ Session::get('success') }}</div>
						</div>
					@endif
					@if ($shared_isshow_dailyopen_pending == '1')
						<div class="col-12" role="alert" aria-live="assertive">
							<div class="alert alert-danger p-2">Please click the "Daily Open" button to verify your cash amounts and open your store.</div>
						</div>
					@endif
				</div>

				<div class="row" role="region" aria-labelledby="store-balance-title">
					<!-- Left section -->
					<div class="col-lg-8 mb-lg-0 mb-4">
						<div class="cash-drawer-card" role="region" aria-labelledby="store-info-title">
							<h2 class="font-weight-semibold mb-3" id="store-info-title">{{$store_name}}</h2>
							<div class="row label-row mb-2" role="group" aria-label="Store Open Balance">
								<div class="col" id="store-open-balance-label">Store Open Balance</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="store-open-balance-label" aria-live="polite">{{ $store_open_balance }}</div>
							</div>
							<div class="row label-row mb-2" role="group" aria-label="Manual Open Balance">
								<div class="col" id="store-open-balance-label">Manual Open Balance</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="store-open-balance-label" aria-live="polite">{{ $set_store_cash_manual }}</div>
							</div>
							<div class="row label-row mb-2" role="group" aria-label="Cash Sales">
								<div class="col" id="cash-sales-label">Cash Sales</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="cash-sales-label" aria-live="polite">{{ $store_cash_sales }}</div>
							</div>
							<div class="row label-row mb-2" role="group" aria-label="Cash Refunds">
								<div class="col" id="cash-refunds-label">Cash Refunds</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="cash-refunds-label" aria-live="polite">{{ $store_cash_refunds }}</div>
							</div>
							<!-- <div class="row label-row mb-2" role="group" aria-label="Manual Credit/Debit">
								<div class="col" id="manual-credit-debit-label">Manual Credit/Debit</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="manual-credit-debit-label" aria-live="polite">{{ $store_cash_manual }}</div>
							</div> -->
							<div class="row label-row mb-2" role="group" aria-label="Bank Deposit">
								<div class="col" id="bank-deposit-label">Bank Deposit</div>
								<div class="col-auto font-weight-semibold" aria-labelledby="bank-deposit-label" aria-live="polite">{{ $store_bank_deposite }}</div>
							</div>
							<div class="row label-row pt-2" role="group" aria-label="Current Balance">
								<div class="col font-weight-bold" id="current-balance-label">Current Balance</div>
								<div class="col-auto font-weight-bold" aria-labelledby="current-balance-label" aria-live="polite">{{ $current_balance }}</div>
							</div>
						</div>
					</div>
					<!-- Right section -->
					<div class="col-lg-4">
						<div class="drawer-actions-card" role="region" aria-labelledby="drawer-actions-title">
							<span id="drawer-actions-title" class="sr-only visually-hidden">Cash Drawer Actions</span>
							<div class="d-flex flex-column" role="group" aria-label="Cash Drawer Actions">
								@if($isshow_dailyopen_btn == '1')
								<a href="{{config('global.SITE_URL')}}store/cash-drawer-daily-open" class="btn btn-primary mb-2 action-btn" aria-label="Open store for the day" role="button">DAILY OPEN</a>
								@endif
								@if($isshow_dailyclose_btn == '1')
								<a href="{{config('global.SITE_URL')}}store/cash-drawer-daily-close" class="btn btn-primary mb-2 action-btn" aria-label="Close store for the day" role="button">DAILY CLOSE</a>
								@endif
								@if($isshow_manualopen_btn == '1')
								<a href="{{config('global.SITE_URL')}}store/cash-drawer-manual-open" class="btn btn-primary mb-2 action-btn" aria-label="Manually open cash drawer" role="button">MANUAL OPEN</a>
								@endif
								<a target="_blank" href="{{config('global.SITE_URL')}}pu-panel/store_user_login.php?f=cashdrawer_log&store_id={{$store_id}}&suid={{$store_user_id}}" class="btn btn-primary action-btn" aria-label="View cash drawer log" role="button">CASH DRAWER LOG</a>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Back Link -->
				<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" role="group" aria-label="Navigation and Actions">
					<a href="{{config('global.SITE_URL')}}store/store-dashboard.html" class="back-link mb-2 mb-md-0" aria-label="Back to POS Home" role="link">
						<i class="bi bi-arrow-left mr-1" aria-hidden="true"></i> Back to POS Home
					</a>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection
