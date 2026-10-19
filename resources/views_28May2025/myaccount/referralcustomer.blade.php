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
				      <h2>Referral Customer</h2>
				   </div>
		          @if($reward_point > 0 && strtolower(Session::get('eusertype')) == 'retailer')
		          <div class="pt-3"> <strong style="text-transform:uppercase;"> You have earned {{$reward_point}}</font> reward points</strong> </div>
		          @endif
				   <div class="pt-3 myact_re_cu">
				      <div id="Rptable">
				         <table>
				            <thead>
				               <tr>
				                  <td>Referral&nbsp;Customer&nbsp;Email</td>
				                  <td>Comment</td>
				                  <td>Referral&nbsp;Accepted</td>
				                  <td>Date</td>
				               </tr>
				            </thead>
				            <tbody class="align-top">
				            	@if(count($referral_customer) > 0)
					            	@foreach($referral_customer as $referral_customer_key => $referral_customer_value)
					               <tr>
					                  <td data-title="Email">
					                     <a href="mailto:{{ $referral_customer_value->receiver }}">
					                        <span class="d-none d-sm-inline-block">{{ $referral_customer_value->receiver }}</span> 
					                        <span class="d-sm-none">
					                           <svg class="sv-email" aria-hidden="true" role="img">
					                              <use href="#sv-email" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-email"></use>
					                           </svg>
					                        </span>
					                     </a>
					                  </td>
					                  <td data-title="Comment">{{ $referral_customer_value->refer_comment }}</td>
					                  <td data-title="Referral Status">
						            	@if($referral_customer_value->is_sender_notified == 'Y')
						            	<span class="text_green">Yes</span>
					            		@else
					            		<span class="text_red">No</span>
					            		@endif
					                  </td>
					                  <td data-title="Date">{{ $referral_customer_value->datetime }}</td>
					               </tr>
					               @endforeach
				               @else
					              <tr align="center">
					                <td colspan="4" class="errmsg tac">No Record Found</td>
					              </tr>
				               @endif
				            </tbody>
				         </table>
						  <div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
						  	{!! $referral_customer->onEachSide(0)->links('myaccount.pagination') !!}
						  </div>
				      </div>
				   </div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection