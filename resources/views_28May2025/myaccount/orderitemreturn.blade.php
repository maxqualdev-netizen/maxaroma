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
					   <svg class="sv_returns" aria-hidden="true" role="img">
					      <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
					   </svg>
					   <h2>My Returns</h2>
					</div>
					@if (count($errors) > 0)
						@if($errors->has('existing_email'))
							<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror">{{ $errors->first('existing_email') }}</small></div> -->
							<div class="col-md-12">
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('existing_email')]"
							/>
							</div>
						@endif
						@if($errors->has('duplicate_ip'))
							<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror">{{ $errors->first('duplicate_ip') }}</small></div> -->
							<div class="col-md-12">
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('duplicate_ip')]"
							/>
							</div>
						@endif
						@if($errors->has('order_not_returned'))
							<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror">{{ $errors->first('order_not_returned') }}</small></div> -->
							<div class="col-md-12">
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => $errors->first('order_not_returned')]"
							/>
							</div>
						@endif
					@endif
					@if (Session::has('success'))
						<!-- <div class="col-md-12"><small class="pt-1 d-block frmsuccess">{{ Session::get('success') }}</small></div> -->
						<div class="col-md-12">
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						</div>
					@endif
					@if(count($OrdResult) > 0)
					@foreach($OrdResult as $order_result_key => $order_result_value)
									@php 
										$k = $order_result_key;
									@endphp
		               <div class="myact_obox myact_returns2 list-head">
							<ul class="thead">
							 <li class="col_1"><strong>order no</strong><span>{{ $order_result_value->orders_no }}</span></li>
							 <li class="col_2"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
							 <li class="col_3"><strong>tOTAL aMOUNT</strong><span>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</span> </li>
							 <li class="col_4"><strong>order status</strong><span class="{{ GetOrderStatusClass($order_result_value->status) }}">{{ $order_result_value->status }}
									@php 
									$singlereq = "No";
									@endphp
									@if($order_result_value->status=="Return Approved" && isset($Data_Return[$k]['COrderNo']) && $Data_Return[$k]['COrderNo'] != '') {
										@php $singlereq = "Yes"; @endphp
										<div class="pb5"><a  href="{{config('global.Site_URL')}}returnord/{{$Data_Return[$k]['COrderNo']}}" target="_blank" rel="nofollow" class="button btn-1 btn-small">Print Return Label</a></div>';
										if($Data_Return[$k]['return_shipping_label']!='') {
											<div class="pb5">{{$Data_Return[$k]['return_shipping_label']}}</div>
										}
									}
									@endif
								</span></li>
								<li class="col_5">
							         <a href="javascript:;" onclick="submitformreturn('{{$order_result_value->orders_id}}');" class="btn btn-primary return-btn">Return</a>
								</li>
							</ul>
							<form name="frmReturn{{$order_result_value->orders_id}}" id="frmReturn{{$order_result_value->orders_id}}" action="" method="post">
							{{ csrf_field() }}
								<input type="hidden" name="orderno" value="{{$order_result_value->orders_id}}" />
								<input type="hidden" name="action" value="return" />
								@php $allowform = 'No'; @endphp
								@if(!empty($order_result_value['order_items']) && count($order_result_value['order_items']) > 0)
								@foreach($order_result_value['order_items'] as $order_item_key => $order_item_value)
								<ul class="body">
								 <li class="col_1"> 
								 		{{-- @if($order_item_value->ostatus=="Pending" || $order_item_value->ostatus=="Completed")
												@php $allowform = 'Yes'; @endphp
											<input type="checkbox" name="chk[]" value="{{$order_item_value->orders_detail_id}}" class="cls_{{$order_result_value->orders_id}}"> 
										@endif
										--}}
										<img src="{{$order_item_value->Image}}" alt="" /> </li>
								 <li class="col_2">
								    <div class="info"> <a href="javascript:void(0);"><strong>{!! $order_item_value->product_name !!}</strong></a><br /> <span class="edu d-block pb-2">Eau de Parfum 1.7 oz 50 ml Spray</span> Item SKU : {{$order_item_value->sku}}<br />Unit Price: {{config('global.SITE_CURRENCY_SYMBOL')}}{{$order_item_value->price}} <br />Quantity : @if($order_item_value->ostatus == "Pending" || $order_item_value->ostatus == "Completed")
								          <input type="hidden" name="oldquantity{{$order_item_value->orders_detail_id}}" id="oldquantity{{$order_item_value->orders_detail_id}}"  value="{{$order_item_value->quantity}}" class="form-control">
										  <input type="text" name="quantity{{$order_item_value->orders_detail_id}}" id="quantity{{$order_item_value->orders_detail_id}}" class="form-control" value="{{$order_item_value->quantity}}" maxlength="4" size="3">
								          @else
								          {{$order_item_value->quantity}}
								          @endif
								    </div>
									<input type="hidden" name="sku{{$order_item_value->orders_detail_id}}" value="{{$order_item_value->sku}}">
								 </li>
								 <li class="col_3">
										<div class="pb5"><strong>Order Item Status :</strong>
											@if($order_item_value->ostatus=="Pending" || $order_item_value->ostatus=="Completed")
												Completed
											@else
												{{ $order_item_value->ostatus }}
											@endif
										</div>

										@if($order_item_value->Reason!='')
								        	<div class="info">Reason: {{ $order_item_value->Reason }}</div>
								        @endif
										@if($order_item_value->ostatus=="Pending" || $order_item_value->ostatus=="Completed")
										@php $returnReason = GetReturnReasons(); @endphp
											@if(count($returnReason) > 0)
								            <div class="field required">
								               <label>Select Reason</label>
								               <select class="form-control" name="customerReturnReason{{$order_item_value->orders_detail_id}}" onchange="return otherFieldnew(this.value,{{$order_item_value->orders_detail_id}})">
								                  <option value="">Select Reason</option>
								                  @foreach($returnReason as $return_reason_key => $return_reason_value)
								                  <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
								                  @endforeach
								               </select>
								            </div>
								            @endif
											<div class="pb5" id="OtherInput{{$order_item_value->orders_detail_id}}" style="display:none;">
												<strong>Other Reason :</strong>
												<input type="text" placeholder="Other" name="otherreason{{$order_item_value->orders_detail_id}}" value="" class="form-control" />
											</div>

											@if($order_item_value->ostatus=="Return Approved" && $singlereq == "No")
												<div class="pb5"><a  href="{{config('global.Site_URL')}}returnord/{{$Data_Return[$k]['COrderNo']}}" target="_blank" rel="nofollow" class="button btn-1 btn-small">Print Return Label</a></div>
												@if($order_item_value->return_shipping_label!='')
													<div class="pb5">{{$order_item_value->return_shipping_label}}</div>
												@endif
											@endif
										@endif
								 </li>
								 <!-- <li class="col_4"> <a href="#" class="btn btn-primary">sUBMIT rEASON</a> </li> -->
								 <li class="col_4">
								 	@if($order_item_value->ostatus=="Pending" || $order_item_value->ostatus=="Completed")
										@php $allowform = 'Yes'; @endphp
										<input type="checkbox" name="chk[]" value="{{$order_item_value->orders_detail_id}}" class="cls_{{$order_result_value->orders_id}}"> 
									@else 
										<input type="checkbox" name="chkcheked" value="" disabled checked>
									@endif

								 </li>
								</ul>
								@endforeach
								@endif
							</form>
		               </div>
	                @endforeach
					
					<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
					   {!! $OrdResult->onEachSide(0)->links('myaccount.pagination') !!}
					</div>
	                
					@else
					<div class="myre_points myreturns">
					   <div class="you_points text-center">
					      <img src="{{config('global.SITE_IMAGES')}}re_perfume.svg" width="78" height="135" alt="" />
					      <!-- <h2>You currenlty have no returns</h2> -->
					      <h2>There are no eligible orders for returns</h2>
					      <!-- <p>You can create a returns from My orders...</p> -->
					      <a class="btn btn-primary d-block mt-4" href="{{ config('global.SITE_URL') }}order-history.html">View my Orders</a>
					   </div>
					   <div class="help_op">
					      <h4>Need help?</h4>
					      <ul>
					         <li>
					            <a href="{{config('global.SITE_URL').'faq.html'}}">
					               Order Isssues FAQ					   
					               <svg class="sv_re_file" aria-hidden="true" role="img">
					                  <use href="#sv_re_file" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_re_file"></use>
					               </svg>
					            </a>
					         </li>
					         <li>
					            <a href="{{config('global.SITE_URL').'faq.html'}}">
					               Return FAQ					   
					               <svg class="sv_re_file" aria-hidden="true" role="img">
					                  <use href="#sv_re_file" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_re_file"></use>
					               </svg>
					            </a>
					         </li>
					      </ul>
					   </div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection