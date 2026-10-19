@extends('layouts.app')
@section('content')
<div id="myModalPopUpCancelOrder" class=" modal fade login_popup" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="myModalPopUpReturnOrder" class=" modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
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
				      <h2>My Orders</h2>
                      <div class="row p-3">
						
						@if (Session::has('error'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('error')]"
							/>
			            @endif
						@if($errors->has('order_not_returned'))
							<div class="col-md-12">
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('order_not_returned')]"
							/>
							</div>
						@endif
				   	</div>
				      <div class="myact_hd_search">
				      	<form action="" id="frmsearchorder" method="post">
				      	{{ csrf_field() }}
					<!-- 	<x-selectbox :attr="[
							'label' => '',
							'id' => 'FilterByOrderNo', 'name' => 'FilterByOrderNo',
							'firstopt' => '',
							'classname' => 'form-control',
							'data' => ['MaxaromaOrderNo' => 'Maxaroma Order No.', 'YourOrderNo' => 'Your Order No.'],
							'selected' => $FilterByOrderNo ]"
						/> -->
				         <input type="text" placeholder="Search By Order No" class="form-control" name="orders_no" id="orders_no" value="{{ $orders_no }}" />
				         <a href="javascript:void(0);" class="search_icon" id="search_order">
				            <svg class="sv_search" aria-hidden="true" role="img">
				               <use href="#sv_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_search"></use>
				            </svg>
				         </a>
				     	</form>
				      </div>
				   </div>
				   <div class="row">
                         @if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess pl-3', 
										'message' => Session::get('success')]"
							/>
			            @endif
			            @if($OutOfStockMsg!='')
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $OutOfStockMsg]"
							/>
			            @endif
			            @if($OrderProcessMsg!='')
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $OrderProcessMsg]"
							/>
			            @endif
				   </div>
				   	<div class="order-history-grid" id="order-history-grid">
				   		@include('myaccount.orderhistorytable')
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection