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
					   <svg class="sv_df_list" aria-hidden="true" role="img">
					      <use href="#sv_df_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_df_list"></use>
					   </svg>
					   <h2>Dropshipper Fund List</h2>
					</div>
					<div class="col-md-12 text-center">
						@if (Session::has('error'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('error')]"
							/>
						@endif
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
						@if (Session::has('fund_error'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('fund_error')]"
							/>
						@endif
						@if (Session::has('fund_success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('fund_success')]"
							/>
						@endif
					</div>
					<div class="myorder_dtl">
						<div class="row pb-3">
							@if(Session::get('eusertype')=='Wholesaler')
							<div class="col-md-4 col-sm-12 pt-md-3 pb-md-0 pb-2">
								<h5><strong>Available Fund : {{Price($available_funds)}} </strong></h5>
							</div>
							@endif
							@if(count($DropShipper_Customer) > 0)
							<div class="col-md-8 col-sm-12 btnserch">
								<div class="pr5 float-md-right">
									<x-button link="{{ url('/export-fund-history.html') }}" btntext="Download Report" classname="btn btn-primary mb-2" bid="btnDownloadReport"/>
                                    <x-button btype="button" btntext="Add Fund" classname="btn btn-primary mb-2" bid="btnDropshipAdddFund" />
								<!-- <h5 onclick="addFundPopUp();"><strong>Add Fund</strong></h5> -->
								</div>
							</div>
							@endif
						</div>
					   <div id="Rptable">
					      <table class="text-left">
					         <thead>
					            <tr>
					               <td>Order&nbsp;Number&nbsp;/&nbsp;Added&nbsp;by</td>
					               <td>Date</td>
					               <td>Used&nbsp;/&nbsp;Added&nbsp;Fund</td>
					               <td>Balance</td>
					               <td class="text-left">Note</td>
					            </tr>
					         </thead>
					         <tbody class="align-top">
								@if(count($DropShipper_Customer) > 0)
								@foreach($DropShipper_Customer as $dropsipper_customer_key => $dropsipper_customer_value)
									@if($dropsipper_customer_value['added_fund'] != '')
										@php 
											$status_bg="#509350"; 
											$birthday = \Carbon\Carbon::parse($dropsipper_customer_value['date_time'])->format('Y-m-d');
										@endphp
										<tr>
											<td data-title="Order Number / Added by">{{$dropsipper_customer_value['orders_no']}}</td>
											<td data-title="Date">{{$dropsipper_customer_value['date_time']}}</td>
											<td data-title="Used / Added Fund">{{$dropsipper_customer_value['added_fund']}}</td>
											<td data-title="Balance">
												@if($dropsipper_customer_value['oldnew1']=='')
													-
												@else
													{{$dropsipper_customer_value['oldnew1']}}
												@endif
											</td>
											<td data-title="Note" class="text-left">{{$dropsipper_customer_value['note']}}</td>
										</tr>
									@endif
								@endforeach
								@else
								<tr>
									<td colspan="5" align="center" class="errmsg">There are no record found</td>
								</tr>
								@endif
					         </tbody>
					      </table>
							<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
							   {!! $DropShipper_Customer->onEachSide(0)->links('myaccount.pagination') !!}
							</div>
					   </div>
					   {{-- @if($history != '') --}}
					   <div class="md_list mt-3 pt-4">
					      <h4>Note</h4>
					      <ul class="com_clist">
					      <li>Fund Update History :{{$history}}</li>
					      <ul>
					   </div>
					   {{-- @endif --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	
<input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>
@endsection
