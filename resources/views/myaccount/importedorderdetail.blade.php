@extends('layouts.app')
@section('content')
@php
@endphp
<style type="text/css">
</style>
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
            	@auth
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
            	@endauth
				<div class="col-lg-9 col-md-12 col-xs-12">
            		@auth
				   <div class="myact_hd">
				      <svg class="sv_orders" aria-hidden="true" role="img">
				         <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
				      </svg>
				      <h2>Dropshipper Order Detail</h2>
				   </div>
            		@endauth
					@if (Session::has('success'))
				      <div class="col-md-12 text-center pt-3" id="success-message">
						<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => Session::get('success')]"
						/>
					  </div>
					@endif
					@if (Session::has('error'))
				    <div class="col-md-12 text-center pt-3">
						<x-message :attr="[
									'classname' => 'frmerror frmerror_shw', 
									'message' => Session::get('error')]"
						/>
					</div>
					@endif
					@if($errmsg != '')
				    <div class="col-md-12 text-center pt-3">
						<x-message :attr="[
									'classname' => 'frmerror frmerror_shw', 
									'message' => $errmsg]"
						/>
					</div>
					@endif
					@if($SKUUPDATESUCCESS != '')
				    <div class="col-md-12 text-center pt-3">
						<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => $SKUUPDATESUCCESS]"
						/>
					</div>
					@endif
				   <div class="myorder_dtl">
				      <!-- <div class="myact_subhd">Order Details</div> -->
				      <div class="order_info">
				         <div class="row row5">
				            <div class="col-md-6 pt-2">
				               <table>
				                  <tr>
				                     <td>Order Number :</td>
				                     <td><strong>{{ $OrderRs->orders_no }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Order Date :</td>
				                     <td><strong>{{ $OrderRs->datetime }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Order Currency :</td>
				                     <td><strong>{{ $OrderRs->currency_info }}</strong></td>
				                  </tr>
				               </table>
				            </div>
				            <div class="col-md-6 pt-2">
			                  	<strong class="ttu">Billing to:</strong><br />
	                     		{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}<br />
	                     		{{$OrderRs->bill_company}}<br />
							  	{{$OrderRs->bill_address1}}, {{$OrderRs->bill_address2}}<br />
							  	{{$OrderRs->bill_city}} - {{$OrderRs->bill_zip}}<br />
							  	{{$OrderRs->bill_state}}, {{$OrderRs->bill_country}}<br />
							  	{{$OrderRs->bill_phone}}, {{$OrderRs->bill_email}}
				            </div>
				         </div>
				      </div>

					  <form action="" method="post" id="frmImportedOrder" name="frmImportedOrder" >
					   <div class="">
					      <div class="myact_subhd">SHIPPING ADDRESS</div>
					      <!-- <form action="" method="post" id="frmImportedOrder" name="frmImportedOrder" > -->
							<input type="hidden" name="orders_id" value="{{$OrderRs->orders_id}}">
							<input type="hidden" name="pageAction" value="UpdateOrder">
							<input type="hidden" name="TotalQuantity" id="TotalQuantity" value="{{$TotalQuantity}}">
							<input type="hidden" name="shippingId" id="shippingId" value="{{$OrderRs->shippingModeId}}">
							<input type="hidden" name="shipping_sign" id="shipping_sign" value="{{$OrderRs->shipping_signature}}">
							<input type="hidden" name="orders_detail_id" id="orders_detail_id" value="">
							 <input type="hidden" name="action" value="Update Shipping Address"/>
							 {{ csrf_field() }}
					         <div class="row">
					            <div class="col-md-6 pt-3">
					               <div class="field required">
					                  <label for="ship_first_name" >Shipping First Name</label>
					                  <input type="text" id="ship_first_name" name="ship_first_name" placeholder="First Name" class="form-control" value="{{ old('ship_first_name')?old('ship_first_name'):$OrderRs->ship_first_name }}"/>
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_ship_first_name']"
										/>
										@if ($errors->has('ship_first_name'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_first_name')]"
											/>
										@endif
					               </div>
					            </div>
					            <div class="col-md-6 pt-3">
					               <div class="field required">
					                  <label for="ship_last_name" >Shipping Last Name</label>
					                  <input type="text" id="ship_last_name" name="ship_last_name" placeholder="Last Name" class="form-control" value="{{ old('ship_last_name')?old('ship_last_name'):$OrderRs->ship_last_name }}"/>
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_ship_last_name']"
										/>
										@if ($errors->has('ship_last_name'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_last_name')]"
											/>
										@endif
					               </div>
					            </div>
					         </div>
					         <div class="row">
					            <div class="col-md-6 pt-3">
					               <div class="field required">
					                  <label for="ship_address1" >Shipping Address 1</label>
					                  <textarea id="ship_address1" name="ship_address1" placeholder="Street Address 1" class="form-control">{{ old('ship_address1')?old('ship_address1'):$OrderRs->ship_address1 }}</textarea>
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_ship_address1']"
										/>
										@if ($errors->has('ship_address1'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_address1')]"
											/>
										@endif
					               </div>
					            </div>
					            <div class="col-md-6 pt-3">
					               <div class="field">
					                  <label for="ship_address2" >Shipping Address 2</label>
					                  <textarea id="ship_address2" name="ship_address2" placeholder="Street Address 2" class="form-control">{{ old('ship_address2')?old('ship_address2'):$OrderRs->ship_address2 }}</textarea>
					               </div>
					            </div>
					         </div>
					         <div class="row">
					            <div class="col-md-6">
					               <div class="row row10">
					                  <div class="col-sm-6 pt-3">
					                     <div class="field required">
					                        <label for="ship_city" >Shipping City</label>
					                        <input type="text" id="ship_city" name="ship_city" placeholder="City" class="form-control" value="{{ old('ship_city')?old('ship_city'):$OrderRs->ship_city }}" />
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_city']"
											/>
											@if ($errors->has('ship_city'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('ship_city')]"
												/>
											@endif
					                     </div>
					                  </div>
					                  <div class="col-sm-6 pt-3">
					                     <div class="field required">
											<x-selectbox :attr="[
												'label' => 'Shipping Country',
												'id' => 'ship_country', 'name' => 'ship_country',
												'firstopt' => '',
												'classname' => 'form-control',
												'data' => $Countries,
												'selected' => (old('ship_country')?old('ship_country'):$OrderRs->ship_country) ]"
											/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_country']"
											/>
											@if ($errors->has('ship_country'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('ship_country')]"
												/>
											@endif
					                     </div>
					                  </div>
					               </div>
					            </div>
		                     @php
		                     if(old('ship_country')) {
			                     if(old('ship_country') && old('ship_country') != 'US') {
			                     	$dstateclass='style="display:none;"'; 
			                     	$dotherstateclass="";
			                     } else { 
			                     	$dstateclass="";
			                     	$dotherstateclass='style="display:none;"'; 
			                 	 }
		                     } else {
			                     if($OrderRs->ship_country && $OrderRs->ship_country != 'US') {
			                     	$dstateclass='style="display:none;"'; 
			                     	$dotherstateclass="";
			                     }else{ 
			                     	$dstateclass="";
			                     	$dotherstateclass='style="display:none;"'; 
			                     }
		                     }
		                     @endphp
					            <div class="col-md-6">
					               <div class="row row10">
					                  <div class="col-sm-6 pt-3">
										<div class="field required" id="divstate" {!! $dstateclass !!}>
											<x-selectbox :attr="[
												'label' => 'State',
												'id' => 'ship_state', 'name' => 'ship_state',
												'firstopt' => '',
												'classname' => 'form-control',
												'data' => $States,
												'selected' => old('ship_state')?old('ship_state'):$OrderRs->ship_state,
												'error' => $errors->has('ship_state')]"
											/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_state']"
											/>
											@if ($errors->has('ship_state'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('ship_state')]"
												/>
											@endif
										</div>
										<div class="field required otherstate" id="divotherstate" {!! $dotherstateclass !!}>
											<label for="oter_state">Other</label>
											<input type="text" id="other_state" name="other_state" value="{{ old('other_state')?old('other_state'):$OrderRs->ship_state}}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}">
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_other_state']"
											/>
											@if ($errors->has('other_state'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('other_state')]"
												/>
											@endif
										</div>
					                  </div>
					                  <div class="col-sm-6 pt-3">
					                     <div class="field required">
					                        <label for="Zip" >Zip</label>
					                        <input type="text" id="ship_zip" name="ship_zip" placeholder="Zip" class="form-control" value="{{ old('ship_zip')?old('ship_zip'):$OrderRs->ship_zip }}"/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_zip']"
											/>
											@if ($errors->has('ship_zip'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('ship_zip')]"
												/>
											@endif
					                     </div>
					                  </div>
					               </div>
					            </div>
					         </div>
					         <div class="row">
					            <div class="col-md-6 pt-3">
					               <div class="field required">
					                  <label for="ship_phone" >Phone</label>
					                  <input type="text" id="ship_phone" name="ship_phone" placeholder="Phone" class="form-control" value="{{ old('ship_phone')?old('ship_phone'):$OrderRs->ship_phone }}"/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_phone']"
											/>
										@if ($errors->has('ship_phone'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_phone')]"
											/>
										@endif
					               </div>
					            </div>
					            <div class="col-md-6 pt-3">
					               <div class="field required">
					                  <label for="ship_email" >Email</label>
					                  <input type="email" id="ship_email" name="ship_email" placeholder="Email" class="form-control" value="{{ old('ship_email')?old('ship_email'):$OrderRs->ship_email }}"/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_email']"
											/>
										@if ($errors->has('ship_email'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_email')]"
											/>
										@endif
					               </div>
					            </div>
					         </div>
					         <div class="row">
					            <div class="col-md-6 pt-3">
					               <div class="field">
					                  <label for="ship_company" >Shipping Company</label>
					                  <input type="text" id="ship_company" name="ship_company" placeholder="Shipping Company" class="form-control" value="{{ old('ship_company')?old('ship_company'):$OrderRs->ship_company }}"/>
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_ship_company']"
											/>
										@if ($errors->has('ship_company'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('ship_company')]"
											/>
										@endif
					               </div>
					            </div>
					         </div>
					         <div class="text-right pt-3">
								<x-button link="javascript:void(0);" btntext="Update Shipping Address" classname="btn btn-primary" bid="btnEditShippingAddress"/>
					         </div>
					      <!-- </form> -->
					   </div>

						<div class="row pt-2 pb-2">
							<div class="col-md-12 col-sm-12 col-xs-12" id="show_shipping_method_box">
								<img src="{{ config('global.SITE_IMAGES') }}loading.gif">
							<br>
							<span class="msg_error"> Please wait while shipping methods are loading....<br>
							if it takes more than 10-15 seconds then please refresh the page.</span>
							</div>
						</div>

				      <div id="Rptable">
				         <table class="text-center">
				            <thead>
				               <tr>
				                  <td class="text-left">Item Description</td>
				                  <td>Unit price</td>
				                  <td>Quantity</td>
				                  <td class="text-right">Total Price</td>
				               </tr>
				            </thead>
				            <tbody class="align-top">
				            	@if(count($OrderDetailRs) > 0)
				            	@php $i = 1; @endphp
				            	@foreach($OrderDetailRs as $order_details_key => $order_details_value)
								@if($order_details_value->errmsgOutOfStock != '')
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $order_details_value->errmsgOutOfStock]"
									/>
								@endif
				  				<input type="hidden" name="orders_detail_id{{$i}}" id="orders_detail_id{{$i}}" value="{{$order_details_value->orders_detail_id}}">
				                <tr> 
				                 <td data-title="full center" class="text-left">
				                     <div class="row row5">
				                        <div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left"><a href="#"><img src="{{$order_details_value->Image}}" alt=""></a></div>
				                        <div class="col-lg-8 col-md-7 col-sm-6 text-center text-sm-left pt-2 pt-sm-0"> 
											<a href="#"><strong>{!! $order_details_value->product_name !!}</strong></a><br>
											Item SKU : 
											<input type="text" name="vtmpsku{{$i}}" id="vtmpsku{{$i}}" placeholder="SKU" value="{{$order_details_value->sku}}"  class="input form-control mt-2" />
											<a href="javascript:void(0);" onclick="javascript:RemoveItem({{$order_details_value->orders_detail_id}}, {{$order_details_value->orders_id}});" title="Remove Item" class="pt-3 d-inline-block ulink">Remove</a>
				                        </div>
				                     </div>
				                  </td>
				                  <td data-title="Unit price"><input type="text" name="unit_price{{$i}}" id="unit_price{{$i}}" value="{{$order_details_value->price}}" class="input form-control" readonly></td>
				                  <td data-title="Quantity"><input type="text" name="quantity{{$i}}" id="quantity{{$i}}" value="{{$order_details_value->quantity}}" class="input form-control" placeholder="Quantity" onchange="Calculate();" style="width:50%;text-align:center;" ></td>
				                  <td data-title="Total Price" class="text-md-right text-left"><input type="text" name="total_price{{$i}}" id="total_price{{$i}}" value="{{$order_details_value->total}}" class="input form-control" readonly></td>
				                </tr>
				            	@php $i++; @endphp
				                @endforeach
								  <input type="hidden" name="loopvalue" id="loopvalue" value="{{count($OrderDetailRs)}}"/>
								  <input type="hidden" name="total_items" id="total_items" value="{{count($OrderDetailRs)}}">
								  {!! $htmlstr !!}
				                @else
				                <tr>
				                  <td data-title="full center" colspan="4">No items found</td>
				                </tr>
				                @endif
				            </tbody>
				         </table>
				         <div class="text-left pt-3">
							<x-button link="javascript:void(0);" btntext="Add New Items" classname="btn btn-primary" bid="btnAddNewItems"/>
							<x-button link="javascript:void(0);" btntext="Update SKU" classname="btn btn-primary" bid="btnUpdateSku"/>
				         </div>
				      </div>
				      <div class="deliv_dtl">
				         <div class="row row5">
				            <div class="col-md-4 order-md-2 pt-3" id="show_order_summary_data">
				               <table class="st_table">
				                  <tr>
				                     <td width="48%">Subtotal:</td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}<span id="sub_total_span">{{$OrderRs->sub_total}}</span><input name="fsub_total" id="fsub_total" value="{{$OrderRs->sub_total}}"  type="hidden" /></strong></td>
				                  </tr>
								  @if($OrderRs->shipping_amt > 0)
				                  <tr>
				                     <td>Shipping:</td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->shipping_amt}}</strong></td>
				                  </tr>
				                  @endif
								  <input type="hidden" value="{{$OrderRs->shipping_amt}}" name="shipping_amt" id="shipping_amt" class="admin-input" size="10">
				                  @if($OrderRs->shipping_signature > 0)
				                  <tr>
				                     <td>Shipping Signature: </td>
				                     <td class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->shipping_signature}}</strong></td>
				                  </tr>
				                  @endif
								  <input type="hidden" value="{{$OrderRs->shipping_signature}}" name="shipping_signature" id="shipping_signature" class="admin-input" size="10">
				                  <tr>
				                     <th>Order Total</th>
				                     <th class="text-left text-md-right"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->order_total}}</strong></th>
								  	 <input type="hidden" value="{{$OrderRs->order_total}}" name="order_total" id="order_total" class="admin-input" size="10">
				                  </tr>
				               </table>
				            </div>
				            <div class="col-md-8 order-md-1 pt-3">
				            </div>
				         </div>
				         <div class="row row5">
				            <div class="col-md-4 pt-4 offset-md-8">
					         <div class="text-right pt-3">
								<a href="javascript:void(0);" class="btn btn-primary" id="updateSku" onclick="return UpdateSKU()">Update Orders</a>
					         </div>
				            </div>
				         </div>
				      </div>
				  	</form>
				   </div>
				</div>

			</div>
		</div>
	</div>
</main>	

@endsection
