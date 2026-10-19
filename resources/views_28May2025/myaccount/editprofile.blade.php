@extends('layouts.app')
@section('content')
@php
if($Userdata[0]->birthday != null && $Userdata[0]->birthday != '0000-00-00') {
	$birthday = \Carbon\Carbon::parse($Userdata[0]->birthday)->format('Y-m-d');
} else {
	$birthday = '';
}
@endphp
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>
	            <div class="col-lg-9 col-md-12 col-xs-12">
	               <div class="myact_hd"> <svg class="sv_details" aria-hidden="true" role="img">
	                  <use href="#sv_details" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_details"></use>
	                  </svg>
	                  <h2>My Details</h2>
	               </div>
	               <div class="p-2">
	                <form action="" method="post" id="frmEditProfile">
						<input type="hidden" name="action" value="update"/>
						<input type="hidden" name="eusertype" id="eusertype" value="{{ Session::get('eusertype') }}"/>
						{{ csrf_field() }}
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
	                  <div class="row">
	                     <div class="col-md-6">
	                        <div class="row row10">
	                           <div class="col-sm-6 pt-3">
	                              <div class="field required">
	                                 <label for="first_name" >First Name</label>
	                                 <input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name')?old('first_name'):$Userdata[0]->first_name}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_first_name']"
									/>
									@if ($errors->has('first_name'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('first_name')]"
										/>
									@endif
	                              </div>
	                           </div>
	                           <div class="col-sm-6 pt-3">
	                              <div class="field required">
	                                 <label for="last_name" >Last Name</label>
	                                 <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name')?old('last_name'):$Userdata[0]->last_name}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_last_name']"
									/>
									@if ($errors->has('last_name'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('last_name')]"
										/>
									@endif
	                              </div>
	                           </div>
	                        </div>
	                     </div>
	                     <div class="col-md-6 pt-3">
	                        <div class="field required">
	                           <label for="email" >Email Address</label>
	                           <input type="email" id="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email')?old('email'):$Userdata[0]->email}}" readonly="readonly" />
								@if ($errors->has('email'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('email')]"
									/>
								@endif
	                        </div>
	                     </div>
	                  </div>
	                  <div class="row">
	                     <div class="col-md-6 pt-3">
	                        <div class="field required">
	                           <label for="phone" >Phone</label>
	                           <input type="text" id="phone" name="phone" placeholder="phone" class="form-control" value="{{old('phone')?old('phone'):$Userdata[0]->phone}}" />
								<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => '',
									'mid' => 'error_phone']"
								/>
								@if ($errors->has('phone'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('phone')]"
									/>
								@endif
	                        </div>
	                     </div>
	                     <div class="col-md-6 pt-3">
	                        <div class="field">
	                           <label for="company_name" >Company Name</label>
	                           <input type="text" id="company_name" name="company_name" placeholder="Company Name" class="form-control" value="{{ old('company_name')?old('company_name'):$Userdata[0]->company_name}}" />
	                        </div>
	                     </div>
	                  </div>
	                  <div class="row">
	                     <div class="col-md-6 pt-3">
	                        <div class="field required">
	                           <label for="address1" >Street Address 1</label>
	                           <textarea id="address1" name="address1" placeholder="Street Address 1" class="form-control">{{ old('address1')?old('address1'):$Userdata[0]->address1}}</textarea>
								<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => '',
									'mid' => 'error_address1']"
								/>
								@if ($errors->has('address1'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('address1')]"
									/>
								@endif
	                        </div>
	                     </div>
	                     <div class="col-md-6 pt-3">
	                        <div class="field">
	                           <label for="address2" >Street Address 2</label>
	                           <textarea id="address2" name="address2" placeholder="Street Address 2" class="form-control">{{old('address2')?old('address2'):$Userdata[0]->address2}}</textarea>
	                        </div>
	                     </div>
	                  </div>
	                  <div class="row">
	                     <div class="col-md-6">
	                        <div class="row row10">
	                           <div class="col-sm-6 pt-3">
	                              <div class="field required">
	                                 <label for="city" >City</label>
	                                 <input type="text" id="city" name="city" placeholder="City" class="form-control" value="{{old('city')?old('city'):$Userdata[0]->city}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_city']"
									/>
									@if ($errors->has('city'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('city')]"
										/>
									@endif
	                              </div>
	                           </div>
	                           <div class="col-sm-6 pt-3">
									<div class="field required">
										<!-- <x-selectbox :attr="[
											'label' => 'Country',
											'id' => 'country', 'name' => 'country',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $Countries,
											'selected' => ($Userdata[0]->country?$Userdata[0]->country:'US') ]"
										/> -->
										<x-selectbox :attr="[
											'label' => 'Country',
											'id' => 'country', 'name' => 'country',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $Countries,
											'selected' => (old('country')?old('country'):$Userdata[0]->country) ]"
										/>
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_country']"
										/>
										@if ($errors->has('country'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('country')]"
											/>
										@endif
									</div>
	                           </div>
	                        </div>
	                     </div>
	                     @php
	                     if(old('country')) {
		                     if(old('country') && old('country') != 'US') {
		                     	$dstateclass='style="display:none;"'; 
		                     	$dotherstateclass="";
		                     } else { 
		                     	$dstateclass="";
		                     	$dotherstateclass='style="display:none;"'; 
		                 	 }
	                     } else {
		                     if($Userdata[0]->country && $Userdata[0]->country != 'US') {
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
											'id' => 'state', 'name' => 'state',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $States,
											'selected' => old('state')?old('state'):$Userdata[0]->state,
											'error' => $errors->has('state')]"
										/>
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_state']"
										/>
										@if ($errors->has('state'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('state')]"
											/>
										@endif
									</div>
									<div class="field required otherstate" id="divotherstate" {!! $dotherstateclass !!}>
										<label for="other_state">Other</label>
										<input type="text" id="other_state" name="other_state" value="{{ old('other_state')?old('other_state'):$Userdata[0]->state}}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}">
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
	                                 <input type="text" id="zip" name="zip" placeholder="Zip" class="form-control" value="{{old('zip')?old('zip'):$Userdata[0]->zip}}" />
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_zip']"
										/>
										@if ($errors->has('zip'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('zip')]"
											/>
										@endif
	                              </div>
	                           </div>
	                        </div>
	                     </div>
	                  </div>
	                  <div class="row">
						 @if($Userdata[0]->eusertype=="Retailer")
						 <!-- <div class="col-md-6 pt-3">
	                        <div class="field">
	                           	<label for="birthday" >Ship Date</label>
						 		<input type="date" name="ship_date" id="ship_date" data-date="7/28/21" data-date-format="M/D/YY" value="2021-07-28" class="form-control">
								<input type="date" data-date="" data-date-format="MM/DD/YYYY" value="2015-08-09" class="form-control input-date" id="testDate">
						 	</div>
						 </div> -->
	                     <div class="col-md-6 pt-3">
	                        <div class="field">
	                           <label for="birthday" >Birthday</label>
	                           <!-- <input type="date" id="birthday" name="birthday" placeholder="Birthday" class="form-control" value="{{ old('birthday')?old('birthday'):$birthday}}" data-date-format="mm/dd/yy"/> -->
	                           <input type="date" id="birthday" name="birthday" placeholder="Birthday" class="form-control input-date" data-date="" data-date-format="MM/DD/YYYY" value="{{ old('birthday')?old('birthday'):$birthday}}"/>
	                        </div>
	                     </div>
	                     @endif
	                     @php
	                     	if(Session::get('eusertype') == 'Wholesaler') {
			                     if(old('state')) {
				                     if(old('state') && old('state') == 'NY') {
	                     				$deinclass = '';
				                     } else { 
	                     				$deinclass = 'style="display: none;"';
				                 	 }
			                     } else {
				                     if($Userdata[0]->state && $Userdata[0]->state == 'NY') {
	                     				$deinclass = '';
				                     }else{ 
	                     				$deinclass = 'style="display: none;"';
				                     }
			                     }
	                     	} else {
                     			$deinclass = 'style="display: none;"';
	                     	}
	                     @endphp
	                     <div class="col-md-6">
	                        <div class="row row10">
								<div class="col-md-6 pt-3" id="diveinnumber" {!! $deinclass !!}>
									<div class="field required">
									   <label for="einnumber" >EIN Number</label>
									   <input type="text" id="einnumber" name="einnumber" placeholder="EIN Number" class="form-control" value="{{ $Userdata[0]->einnumber}}" />
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_einnumber']"
										/>
										@if ($errors->has('einnumber'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('einnumber')]"
											/>
										@endif
									</div>
								</div>
								<div class="col-md-6 pt-3" style="display: none;">
									<div class="field required">
									   <label for="salestax_id" >Sales Tax ID</label>
									   <input type="text" id="salestax_id" name="salestax_id" placeholder="Sales Tax ID" class="form-control" value="{{ $Userdata[0]->salestax_id}}"/>
									</div>
								</div>
	                        </div>
	                     </div>
	                  </div>
	                  <div class="text-right pt-3">
						<x-button link="javascript:void(0);" btntext="Save Changes" classname="btn btn-primary" bid="btnEditProfile"/>
	                  </div>
	               </form>
	               </div>
	            </div>
			</div>
		</div>
	</div>
</main>	
@endsection
