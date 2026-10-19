@extends('layouts.app')
@section('content')

<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
				
				<div class="col-lg-9 col-md-12 col-xs-12">
					<div class="myact_hd myact_hd_flex">
						<div class="myact_hdleft">
							<svg class="sv_account_overview" aria-hidden="true" role="img">
								<use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
							</svg>
							<h2>Dashboard</h2>
						</div>
						<div class="myact_hdright">
							@if(Session::get('eusertype')=='Retailer')
								@if(config('global.YOTPO_PROG') == true)
									<b>You have {{ Session::get('YotpoRewardPoints') }} reward points in your account - <a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="ulink">Redeem Now</a></b>
								@else
									<b>You have {{ $reward_point }} reward points in your account - <a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="ulink">Redeem Now</a></b>
								@endif
							@endif
						</div>
					</div>
					
				   	<div class="pt-3 myact_re_cu">
				      	<h1 class="mb-3 mb-md-2">Recent Orders Summary</h1>
						<div id="Rptable">
							<table>
								<thead>
								<tr>
									<td>Order No</td>
									<td>Date</td>
									<td>Order Total</td>
									<td>Payment Status</td>
									<td>Order Status</td>
									<td>Action</td>
								</tr>
								</thead>
								<tbody class="align-top">
									@if(count($OrdResult) > 0)
										@foreach($OrdResult as $order_key => $order_value)
										
											@php
												$setPayStatusColor = '';
												if($order_value->pay_status == 'Paid'){
													$setPayStatusColor = '#00a100';
												}else if($order_value->pay_status == 'Unpaid'){
													$setPayStatusColor = '#cf1b15';
												}
												else if($order_value->pay_status == 'Inprocess'){
													$setPayStatusColor = '#dd8306';
												}
												
												$setStatusColor = '';
												if($order_value->status == 'Pending'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Completed'){
													$setStatusColor = '#00a100';
												}
												else if($order_value->status == 'Canceled'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Declined'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Back Ordered'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Closed'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Refund'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Sent To Stripe'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Return Requested'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Return Approved'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Return Rejected'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Item Received'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Request Cancellation'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Cancellation Approved'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Pending By Amazon'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Pending - PhoneOrder'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Sent To AfterPay'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Resolved'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Claim Approved'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Claim Rejected'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Claim In Review'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Claim Requested'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Claim In Review - Police Report Required'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Claim In Review - Pictures / Videos Required'){
													$setStatusColor = '#dd8306';
												}
												else if($order_value->status == 'Refund Initiated'){
													$setStatusColor = '#0892bd';
												}
												else if($order_value->status == 'Refund Declined'){
													$setStatusColor = '#cf1b15';
												}
												else if($order_value->status == 'Refund Partially'){
													$setStatusColor = '#dd8306';
												}
												
											@endphp
											
											<tr>
												<td data-title="Order No"><a href="{{config('global.SITE_URL')}}order-detail/{{$order_value->orders_id}}.html">{{ $order_value->orders_no }}</a></td>
												<td data-title="Date">{{ $order_value->datetime }}</td>
												<td data-title="Order Total">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_value->order_total }}</td>
												<td data-title="Payment Status"><span class="custlabel" style="background-color:{{$setPayStatusColor}};">{{ $order_value->pay_status }}</span></td>
												<td data-title="Order Status"><span class="custlabel" style="background-color:{{$setStatusColor}};">{{ $order_value->status }}</span></td>
												<td data-title="Action">
													<a href="{{config('global.SITE_URL')}}order-detail/{{$order_value->orders_id}}.html" class="custlabel action-link">View Order</a>
													&nbsp;|&nbsp;
													<a href="{{config('global.SITE_URL')}}re-order-detail/{{$order_value->orders_id}}.html" class="custlabel action-link">Reorder</a>
												</td>
											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
							
							<!-- <div class="text-left pb-5 pt-3 fadeInUp">&nbsp;</div> -->
							
							<!--<div class="text-left pb-5 pt-3 fadeInUp">
								@if(Session::get('eusertype')=='Retailer')
								<b>You have {{ $reward_point }} reward points in your account</b><br>
								<a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="btn btn-primary">Redeem Now</a>
								&nbsp;
								@endif
								<a href="{{config('global.SITE_URL')}}wish-category.html" class="btn btn-primary">View Wishlist</a>
								&nbsp;
								<a href="{{config('global.SITE_URL')}}" class="btn btn-primary">Exclusive Offers</a>
							</div>-->
							
							<div class="dashboard-row" role="list" aria-label="Dashboard Navigation">
								<div class="dashboard-col" role="listitem">
									<a href="{{config('global.SITE_URL')}}order-history.html" class="dashboard-card" aria-label="Order History">
										<div class="dashboard-cardicon">
											<svg class="sv_orders" aria-hidden="true" role="img">
												<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
											</svg>
										</div>
										<div class="dashboard-cardhd">Orders</div>
									</a>
								</div>
								<div class="dashboard-col" role="listitem">
									<a href="{{config('global.SITE_URL')}}return-orders.html" class="dashboard-card" aria-label="Order Return History">
										<div class="dashboard-cardicon">
											<svg class="sv_returns" aria-hidden="true" role="img">
												<use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
											</svg>
										</div>
										<div class="dashboard-cardhd">Returns</div>
									</a>
								</div>
								@if(Session::get('eusertype')=='Retailer')
									<div class="dashboard-col" role="listitem">
										<a href="{{config('global.SITE_URL')}}myrewardpoint.html" class="dashboard-card" aria-label="Reward Points">
											<div class="dashboard-cardicon">
												<svg class="sv_reward_points" aria-hidden="true" role="img">
													<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
												</svg>
											</div>
											<div class="dashboard-cardhd">Reward Points</div>
										</a>
									</div>
								@endif
								<div class="dashboard-col" role="listitem">
									<a href="{{config('global.SITE_URL')}}wish-category.html" class="dashboard-card" aria-label="Wishlist">
										<div class="dashboard-cardicon">
											<svg class="sv_wishlist" aria-hidden="true" role="img">
												<use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
											</svg>
										</div>
										<div class="dashboard-cardhd">Wishlist</div>
									</a>
								</div>
								<div class="dashboard-col" role="listitem">
									<a href="{{config('global.SITE_URL')}}cancel-orders.html" class="dashboard-card" aria-label="Cancellation">
										<div class="dashboard-cardicon">
											<svg class="sv_cancellation" aria-hidden="true" role="img">
												<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
											</svg>
										</div>
										<div class="dashboard-cardhd">Cancellation</div>
									</a>
								</div>
								@if(Session::get('is_dropshipper')!='Yes')
									<div class="dashboard-col" role="listitem">
										<a href="{{config('global.SITE_URL')}}credit-summary.html" class="dashboard-card" aria-label="Store Credit">
											<div class="dashboard-cardicon">
												<svg class="svg-Credit-report" aria-hidden="true" role="img">
													<use href="#svg-Credit-report" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-Credit-report"></use>
												</svg>
											</div>
											<div class="dashboard-cardhd">
												@if(Session::get('eusertype')=='Wholesaler')
													Credit Report
												@else
													Store Credit
												@endif
											</div>
										</a>
									</div>
								@endif
								@if($IsExclusiveCustomers == 'Yes')
									<div class="dashboard-col" role="listitem">
										{{-- @if($IsExclusiveCustomers == 'Yes') --}}
											<a href="{{config('global.SITE_URL')}}exclusive-offers.html" class="dashboard-card" aria-label="Exclusive Offers">
												<div class="dashboard-cardicon">
													<svg class="svg-points" aria-hidden="true" role="img">
														<use href="#svg-points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-points"></use>
													</svg>
												</div>
												<div class="dashboard-cardhd">Exclusive Offers</div>
											</a>
										{{-- @else
											<a href="{{config('global.SITE_URL')}}offers.html" class="dashboard-card" aria-label="Offers">
												<div class="dashboard-cardicon">
													<svg class="svg-points" aria-hidden="true" role="img">
														<use href="#svg-points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-points"></use>
													</svg>
												</div>
												<div class="dashboard-cardhd">Offers</div>
											</a>
										@endif --}}
									</div>
								@endif
							</div>
						</div>
				   	</div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
