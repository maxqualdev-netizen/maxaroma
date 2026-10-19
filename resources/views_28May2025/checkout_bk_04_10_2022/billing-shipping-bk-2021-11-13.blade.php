<div class="mb-0 mb-md-4">
	<h3 class="cart-hd d-none d-md-block">Billing Information</h3>
	<h3 class="cart-hd d-md-none">1. Shipping Information</h3>
	<form id="frmbilling" name="frmbilling">
		<div class="row pt-3">
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_fname" class="col-form-label">First Name<span class="errmsg">*</span></label>
					<input id="bill_fname" name="bill_fname" type="text" value="{{$Billing['first_name']}}" placeholder="First Name" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_fname']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_lname" class="col-form-label">Last Name<span class="errmsg">*</span></label>
					<input id="bill_lname" name="bill_lname" type="text" value="{{$Billing['last_name']}}" placeholder="Last Name" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_lname']"/>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="bill_company" class="col-form-label">Company</label>
					<input id="bill_company" name="bill_company" type="text" value="{{$Billing['company']}}" placeholder="Company" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_address1" class="col-form-label">Address1<span class="errmsg">*</span></label>
					<input id="bill_address1" name="bill_address1" type="text" value="{{$Billing['address1']}}" placeholder="Address" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_address1']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_address2" class="col-form-label">Address2</label>
					<input id="bill_address2" name="bill_address2" type="text" value="{{$Billing['address2']}}" placeholder="Address" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_city" class="col-form-label">City<span class="errmsg">*</span></label>
					<input id="bill_city" name="bill_city" type="text" value="{{$Billing['city']}}" placeholder="City" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_city']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<x-selectbox :attr="[
						'label' => 'Country',
						'id' => 'bill_country', 'name' => 'bill_country',
						'firstopt' => '',
						'classname' => 'form-control',
						'data' => $Countries,
						'selected' => ($Billing['country'])?$Billing['country']:'US',
						]"
					/>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="field required" id="divstate" style="@if($Billing['country'] != 'US' || (Session::has('ShoppingCart.BillingAddress.country') && Session::get('ShoppingCart.BillingAddress.country') != 'US')) display:none; @else '' @endif">
						<x-selectbox :attr="[
							'label' => 'State',
							'id' => 'bill_state', 'name' => 'bill_state',
							'firstopt' => '',
							'classname' => 'form-control',
							'data' => $States,
							'selected' => $Billing['state'],
							]"
						/>
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_bill_state']"
						/>
						@if ($errors->has('state'))
							<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => $errors->first('bill_state')]"
							/>
						@endif
					</div>
					<div class="field required otherstate" id="divotherstate" style="@if($Billing['country'] == 'US' || (Session::has('ShoppingCart.BillingAddress.country') && Session::get('ShoppingCart.BillingAddress.country') == 'US')) display:none; @else display:block; @endif">
						<label for="oter_state">Other</label>
						<input type="text" id="bill_other_state" name="bill_other_state" value="{{$Billing['state']}}" placeholder="Other State" class="form-control">
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_bill_other_state']"
						/>
						@if ($errors->has('other_state'))
							<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => $errors->first('bill_other_state')]"
							/>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="bill_zip" class="col-form-label">Zip<span class="errmsg">*</span></label>
					<input id="bill_zip" name="bill_zip" type="text" value="{{$Billing['zip']}}" placeholder="Zip" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_zip']"/>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="bill_phone" class="col-form-label">Phone<span class="errmsg">*</span></label>
					<input id="bill_phone" name="bill_phone" type="text" value="{{$Billing['phone']}}"  placeholder="e.g. +1 444 123 4567" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_phone']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="bill_email" class="col-form-label">Email<span class="errmsg">*</span></label>
					<input id="bill_email" name="bill_email" type="text" value="{{$Billing['email']}}" placeholder="e.g. jhone@gmail.com" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_email']"/>
				</div>
			</div>
			@if(!Auth::user())
			<div class="col-md-12">
				<label class="checkbox-label">
					<div class="chebox">
						<input type="checkbox" id="chknews" name="chknews" checked=""><span class="checkmark"></span>
					</div>
					Join MAXAROMA today for exclusive access to special sales, new arrivals & more. Sign-up below is easy and free!
				</label>
			</div>
			@endif
		</div>
		@if(!Auth::user())
		<div class="row pt-3">	
			<div class="col-md-12">
				<p><strong>To become a registered customer, enter password (optional)</strong></p>
			</div>	
			<div class="col-md-6">
				<input type="hidden" id="chkflag" value="guest"/>
				<div class="form-group">
					<label for="guest_password" class="col-form-label">Password<span class="errmsg">*</span></label>
					<input id="guest_password" name="guest_password" type="password" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_guest_password']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="guest_confirm_password" class="col-form-label">Confirm Password<span class="errmsg">*</span></label>
					<input id="guest_confirm_password" name="guest_confirm_password" type="password" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_guest_confirm_password']"/>
				</div>
			</div>
		</div>	
		@endif
	</form>
	<label class="checkbox-label">
		<div class="chebox">
			<input type="checkbox" id="chknews" name="chknews" ><span class="checkmark"></span>
		</div>
		@if(!Auth::user())
		By creating an account, you agree to MAXAROMA's <a href="{{config('global.SITE_URL')}}terms-and-conditions.html" target="_blank" class="ulink">Terms</a> and <a target="_blank" href="{{config('global.SITE_URL')}}privacy-policy.html" class="ulink" >Privacy Policy</a>.
		@else
		Join MAXAROMA today for exclusive access to special sales, new arrivals & more.
		@endif
	</label>
</div>
<div class="border-top pt-4">
	@if($CartAttr['onlyGCPurchased'] == 0)
	<h3 class="cart-hd mb-3">Shipping Information</h3>
	<label class="checkbox-label">
		<div class="chebox">
			<input type="checkbox" name="chksamebill" checked="" id="chksamebill" @if(Session::get('ShoppingCart.BillingAsShipping') == 'Yes') checked @endif ><span class="checkmark"></span>
		</div>
		<strong>SHIP TO THIS ADDRESS</strong>
	</label>
	<form id="frmshipping" name="frmshipping" style='display:none' @if(Session::get('ShoppingCart.BillingAsShipping') == 'Yes') style='display:none' @endif >
		<div class="row pt-3">
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_fname" class="col-form-label">First Name<span class="errmsg">*</span></label>
					<input id="ship_fname" name="ship_fname" type="text" placeholder="First Name" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_fname']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_lname" class="col-form-label">Last Name<span class="errmsg">*</span></label>
					<input id="ship_lname" name="ship_lname" type="text" placeholder="Last Name" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_lname']"/>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="ship_company" class="col-form-label">Company</label>
					<input id="ship_company" name="ship_company" type="text" placeholder="Company" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_address1" class="col-form-label">Address1<span class="errmsg">*</span></label>
					<input id="ship_address1" name="ship_address1" type="text" placeholder="Address" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_address1']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_address2" class="col-form-label">Address2</label>
					<input id="ship_address2" name="ship_address2" type="text" placeholder="Address" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_city" class="col-form-label">City<span class="errmsg">*</span></label>
					<input id="ship_city" name="ship_city" type="text" placeholder="City" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_city']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<x-selectbox :attr="[
						'label' => 'Country',
						'id' => 'ship_country', 'name' => 'ship_country',
						'firstopt' => '',
						'classname' => 'form-control',
						'data' => $Countries,
						'selected' => ($Shipping['country'])?$Shipping['country']:'US',
						]"
					/>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="field required" id="divshipstate" style="@if($Shipping['country'] != 'US' || (Session::has('ShoppingCart.BillingAddress.country') && Session::get('ShoppingCart.ShippingAddress.country') != 'US')) display:none; @else '' @endif">
						<x-selectbox :attr="[
							'label' => 'State',
							'id' => 'ship_state', 'name' => 'ship_state',
							'firstopt' => '',
							'classname' => 'form-control',
							'data' => $States,
							'selected' => $Shipping['state'],
							]"
						/>
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_ship_state']"
						/>
						@if ($errors->has('state'))
							<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => $errors->first('ship_state')]"
							/>
						@endif
					</div>
					<div class="field required otherstate" id="divshipotherstate" style="@if($Shipping['country'] == 'US' || (Session::has('ShoppingCart.BillingAddress.country') && Session::get('ShoppingCart.ShippingAddress.country') == 'US')) display:none; @else display:block; @endif">
						<label for="oter_state">Other</label>
						<input type="text" id="ship_other_state" name="ship_other_state" value="{{$Shipping['state']}}" placeholder="Other State" class="form-control">
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_ship_other_state']"
						/>
						@if ($errors->has('other_state'))
							<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => $errors->first('ship_other_state')]"
							/>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="ship_zip" class="col-form-label">Zip<span class="errmsg">*</span></label>
					<input id="ship_zip" name="ship_zip" type="text" placeholder="Zip" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_zip']"/>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="ship_phone" class="col-form-label">Phone<span class="errmsg">*</span></label>
					<input id="ship_phone" name="ship_phone" type="text" placeholder="e.g. +1 444 123 4567" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_phone']"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="ship_email" class="col-form-label">Email<span class="errmsg">*</span></label>
					<input id="ship_email" name="ship_email" type="text" placeholder="e.g. jhone@gmail.com" class="form-control">
					<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_email']"/>
				</div>
			</div>
		</div>
	</form>
	@endif
	<div class="mt-2 mb-3">
		<a class="max_continuesp_btn btn btn-primary d-block" id="btnbill_step1" href="javascript:void(0)">SHIP TO THIS ADDRESS</a>
	</div>
</div>