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
				      <svg class="sv_referral_customer" aria-hidden="true" role="img">
				         <use href="#sv_referral_customer" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_referral_customer"></use>
				      </svg>
				      <h2>View Credit Report</h2>
				   </div>
				   <div class="pt-3 myact_re_cu">
				      <div id="Rptable">
				         <table>
				            <thead>
				               <tr>
				                  <td>Order Number/Added By</td>
				                  <td>Date</td>
				                  <td>Used Amount/Credited Amount</td>
				                  <td>Applied Credit</td>
				                  <td>Old Amount/New Amount</td>
				               </tr>
				            </thead>
				            <tbody class="align-top">
				            	@if(count($credit_customer) > 0)
					            	@foreach($credit_customer as $credit_customer_key => $credit_customer_value)
					            		@php
					            			if($credit_customer_value->apply_credit != '') {
					            				$apply_credit = config('global.SITE_CURRENCY_SYMBOL').$credit_customer_value->apply_credit;
					            			} else {
					            				$apply_credit = $credit_customer_value->apply_credit;
					            			}
					            		@endphp
					               <tr>
					                  <td data-title="Order No">{{ $credit_customer_value->orders_no }}</td>
					                  <td data-title="Date">{{ $credit_customer_value->date_time }}</td>
					                  <td data-title="Current Credit Limit">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $credit_customer_value->cust_current_credit_limit }}</td>
					                  <td data-title="Applied Credit">{{ $apply_credit }}</td>
					                  <td data-title="old New Amount">
						            	@if($credit_customer_value->oldnew == '' && $credit_customer_value->oldnew1 == '')
						            	-
					            		@else
							            	@php
							            		$oldnew = config('global.SITE_CURRENCY_SYMBOL').$credit_customer_value->oldnew;
							            		$oldnew1 = config('global.SITE_CURRENCY_SYMBOL').$credit_customer_value->oldnew1;
							            	@endphp
					            		{{ $oldnew }} | {{ $oldnew1 }}
					            		@endif
					                  </td>
					               </tr>
					               @endforeach
				               @else
					              <tr align="center">
					                <td colspan="5" class="errmsg tac">No Record Found</td>
					              </tr>
				               @endif
				            </tbody>
				         </table>
						  <div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
						  	{!! $credit_customer->onEachSide(0)->links('myaccount.pagination') !!}
						  </div>
				      </div>
				   </div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection