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
				      <svg class="sv_address_book" aria-hidden="true" role="img">
				         <use href="#sv_address_book" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_address_book"></use>
				      </svg>
				      <h2>Address Book</h2>
				   </div>
				   <div class="p-2">
				      <div class="myact_subhd spac_tpbt">Add Address</div>
				      <form action="{{config('global.SITE_URL').'addressbook/add.html'}}" method="post" id="frmAddAddressBook">
						 <input type="hidden" name="action" value="submit"/>
						 {{ csrf_field() }}
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="first_name" >First Name</label>
				                  <input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name') }}"/>
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
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="last_name" >Last Name</label>
				                  <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}"/>
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
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="address1" >Street Address 1</label>
				                  <textarea id="address1" name="address1" placeholder="Street Address 1" class="form-control">{{ old('address1') }}</textarea>
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
				                  <textarea id="address2" name="address2" placeholder="Street Address 2" class="form-control">{{ old('address2') }}</textarea>
				               </div>
				            </div>
				         </div>
				         <div class="row">
				            <div class="col-md-6">
				               <div class="row row10">
				                  <div class="col-sm-6 pt-3">
				                     <div class="field required">
				                        <label for="city" >City</label>
				                        <input type="text" id="city" name="city" placeholder="City" class="form-control" value="{{ old('city') }}"/>
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
										<x-selectbox :attr="[
											'label' => 'Country',
											'id' => 'country', 'name' => 'country',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $Countries,
											'selected' => (old('country')?old('country'):'US') ]"
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
		                     	$dstateclass="";
		                     	$dotherstateclass='style="display:none;"'; 
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
											'selected' => old('state'),
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
										<label for="oter_state">Other</label>
										<input type="text" id="other_state" name="other_state" value="{{ old('other_state') }}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}">
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
				                        <input type="text" id="zip" name="zip" placeholder="Zip" class="form-control" value="{{ old('zip') }}"/>
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
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="phone" >Phone</label>
				                  <input type="text" id="phone" name="phone" placeholder="Phone" class="form-control" value="{{ old('phone') }}"/>
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
				               <div class="field required">
				                  <label for="ein_number" >Email</label>
				                  <input type="email" id="email" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}"/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_email']"
									/>
									@if ($errors->has('email'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('email')]"
										/>
									@endif
				               </div>
				            </div>
				         </div>
				         <div class="text-right pt-3">
							<!-- <x-button btype="submit" btntext="Submit" name="btnsubmit" classname="btn btn-primary"/> -->
							<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnAddAddressBook"/>
				         </div>
				      </form>
				   </div>
				</div>



			</div>
		</div>
	</div>
</main>	

@endsection