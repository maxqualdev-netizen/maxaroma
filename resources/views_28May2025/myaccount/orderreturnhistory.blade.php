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
					<div class="myact_hd">
						<svg class="sv_orders" aria-hidden="true" role="img">
							<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
						</svg>
						<h2>My Returns Orders</h2>
						<!-- <div class="myact_hd_search">
							<form action="" id="frmsearchorder" method="post">
								{{ csrf_field() }}
								<input type="text" placeholder="Search By Order No" class="form-control" name="orders_no" id="orders_no" value="" />
								<a href="javascript:void(0);" class="search_icon" id="search_order">
									<svg class="sv_search" aria-hidden="true" role="img">
										<use href="#sv_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_search"></use>
									</svg>
								</a>
							</form>
						</div> -->
					</div>

					<div class="order-history-grid" id="order-history-grid">
					@if(count($OrdReturnHistory) > 0)
						@foreach($OrdReturnHistory as $as_key => $as_value)
							<div class="myact_obox myact_returns2">
								<ul class="thead">
									<li class="col_1"><strong>order no</strong><span>{{ $as_value->orders_no}}</span></li>
									<li class="col_2"><strong>order DATE</strong><span>{{date('m/d/Y H:i',strtotime($as_value->order_datetime))}}</span></li>
									<li class="col_3"><strong>tOTAL aMOUNT</strong><span>{{ Price($as_value->total)}}</span> </li>
									<li class="col_4"><strong>order status</strong><span class="text_green">{{ $as_value->status}}</span></li>
								</ul>
								<ul class="body">
									<li class="col_1"> <img src="{{$as_value->Image}}" alt="" /> </li>
									<li class="col_2">
										<div class="info"> 
											<a href="#"><strong>{!! $as_value->product_name !!}</strong></a><br />
										<span class="edu d-block pb-2"> </span> Item SKU : {{$as_value->sku}}<br />
											Unit Price: {{Price($as_value->unitprice)}} <br />
											Quantity : {{$as_value->quantity}} </div>
									</li>
									<li class="col_3">
										<div class="field">
											<label><strong>Return Reason</strong> : {{ $as_value->Reason }}</label>
											<label><strong>Status</strong> : <span class="text_green">{{ $as_value->status}}</span></label>
										
										</div>
									</li>
								</ul>
							</div>
						@endforeach
						<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
					   		{!! $OrdReturnHistory->onEachSide(0)->links('myaccount.pagination') !!}
						</div>
				   @else
				    <div class="row pt-3">
					   <div class="col-md-12 text-center">
					      <span class="errmsg tac">Sorry, No return orders found!</span>
					   </div>
					</div>
				   @endif

					</div>
				</div>
			</div>
		</div>
	</div>
</main>

@endsection