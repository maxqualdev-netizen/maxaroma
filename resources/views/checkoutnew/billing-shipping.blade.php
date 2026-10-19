<div class="row">
	
	<div class="col-12" id="frmbilling_head">
		<h4 class="cart-hd-sub text-center">Billing Information</h4>
	</div>
	
	<div class="col-12">
		
		@if(Auth::user())
		<ul class="checkout-steps" id="EditInfo">
            <li>
                <div class="row">
                    @if(isset($Billing['first_name']) && isset($Billing['last_name']) && isset($Billing['address1']) && isset($Billing['zip']) && isset($Billing['city']) && isset($Billing['state']) && isset($Billing['country']))
                    <div class="col-8 pb-md-5">
                        <p class="mb-0 lheight-18">
                            {{$Billing['first_name']}} {{$Billing['last_name']}}<br>
                            {{$Billing['address1']}},
                            @if($Billing['address2'] != '')
                                {{$Billing['address2']}},
                            @endif
                            <br> 
                            {{$Billing['city']}}, {{$Billing['state']}} - {{$Billing['zip']}}, {{$Billing['country']}}<br> 
                            {{$Billing['email']}}
                        </p>
                    </div>
                    @endif
                    <div class="col-4" align="right">
                        <a href="javascript:void(0);" class="float-right edit-btn btn btn-secondary" data-index="2" id="EditBilling">Edit</a>
                    </div>
                    
                </div>    
            </li>
           
        </ul>
		@endif
		<form id="frmbilling" name="frmbilling" @if(Auth::user()) style="display:none;" @endif>
			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<label for="bill_fname" class="col-form-label">First Name<span class="errmsg">*</span></label>
						<input id="bill_fname" name="bill_fname" type="text" value="{{$Billing['first_name']}}" placeholder="First Name" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_fname']"/>
					</div>
				</div>
				<div class="col-12">
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
				<div class="col-12">
					<div class="form-group">
						<label for="bill_address1" class="col-form-label">Address1<span class="errmsg">*</span></label>
						<input id="bill_address1" name="bill_address1" type="text" value="{{$Billing['address1']}}" placeholder="Address" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_address1']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="bill_address2" class="col-form-label">Address2</label>
						<input id="bill_address2" name="bill_address2" type="text" value="{{$Billing['address2']}}" placeholder="Address" class="form-control">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="bill_city" class="col-form-label">City<span class="errmsg">*</span></label>
						<input id="bill_city" name="bill_city" type="text" value="{{$Billing['city']}}" placeholder="City" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_city']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group selectbox-form">
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
				<div class="col-12">
					<div class="form-group selectbox-form">
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
					</div>
					<div class="form-group" id="divotherstate" style="@if($Billing['country'] == 'US' || (Session::has('ShoppingCart.BillingAddress.country') && Session::get('ShoppingCart.BillingAddress.country') == 'US')) display:none; @else display:block; @endif">	
						
							<label class="col-form-label" for="oter_state">Other<span class="errmsg">*</span></label>
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
				<div class="col-12">
					<div class="form-group">
						<label for="bill_zip" class="col-form-label">Zip<span class="errmsg">*</span></label>
						<input id="bill_zip" name="bill_zip" type="text" value="{{$Billing['zip']}}" placeholder="Zip" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_zip']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="bill_phone" class="col-form-label">Phone<span class="errmsg">*</span></label>
						<input id="bill_phone" name="bill_phone" type="text" value="{{$Billing['phone']}}"  placeholder="e.g. +1 444 123 4567" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_phone']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="bill_email" class="col-form-label">Email<span class="errmsg">*</span></label>
						<input id="bill_email" name="bill_email" type="text" value="{{$Billing['email']}}" placeholder="e.g. jhone@gmail.com" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_bill_email']"/>
					</div>
				</div>
				@if(!Auth::user() && (isset($SelPayMethod) && $SelPayMethod != 'paypal'))
				<div class="col-md-12">
					<label class="checkbox-label f12 lheight-18">
						<div class="chebox">
							<input type="checkbox" id="chknews" name="chknews" checked=""><span class="checkmark"></span>
						</div>
						Join MAXAROMA today for exclusive access to special sales, new arrivals & more. Sign-up below is easy and free!
					</label>
				</div>
				@endif
			</div>
			@if(!Auth::user() && (isset($SelPayMethod) && $SelPayMethod != 'paypal'))
			<div class="row">	
				<div class="col-md-12 f12 pt-3 pb-3 fw500">
					To become a registered customer, enter password (optional)
				</div>	
				<div class="col-12">
					<input type="hidden" id="chkflag" value="guest"/>
					<div class="form-group">
						<label for="guest_password" class="col-form-label">Password<span class="errmsg">*</span></label>
						<input id="guest_password" name="guest_password" type="password" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_guest_password']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="guest_confirm_password" class="col-form-label">Confirm Password<span class="errmsg">*</span></label>
						<input id="guest_confirm_password" name="guest_confirm_password" type="password" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_guest_confirm_password']"/>
					</div>
				</div>
			</div>	
			@endif
		</form>
		<label class="checkbox-label f12">
			@if(isset($ISNewsletter) && !empty($ISNewsletter) && $ISNewsletter=="No") 
			<div class="chebox">
				<input type="checkbox" id="chknews" name="chknews" ><span class="checkmark"></span>
			</div>
			@endif
			@if(!Auth::user() && (isset($SelPayMethod) && $SelPayMethod != 'paypal'))
			By creating an account, you agree to MAXAROMA's <a href="{{config('global.SITE_URL')}}terms-and-conditions.html" target="_blank" class="ulink">Terms</a> and <a target="_blank" href="{{config('global.SITE_URL')}}privacy-policy.html" class="ulink" >Privacy Policy</a>.
			@elseif(isset($ISNewsletter)  && $ISNewsletter=="No") 
			Join MAXAROMA today for exclusive access to special sales, new arrivals & more.
			@endif
		</label>
	</div>
	
	<div class="col-12 d-md-block d-none" id="DeskView">
		<div class="border-md-top">&nbsp;</div>
	</div>
	<div class="col-12 d-md-none" id="MobileView">
		<div class="mobile-shadow mb-0">&nbsp;</div>
	</div>
	
	
	@if($CartAttr['onlyGCPurchased'] == 0)
	<div class="col-12">
		<h4 class="cart-hd-sub text-center pb-3 mb-1">Shipping Information</h4>
		<div class="pt-md-2 pb-md-4">
		<label class="checkbox-label f12">
		<div class="chebox">
			<input type="checkbox" name="chksamebill" checked="" id="chksamebill" @if(Session::get('ShoppingCart.BillingAsShipping') == 'Yes') checked @endif ><span class="checkmark"></span>
		</div>
		Same as Shipping Address
		</label>
	</div>
	</div>
	
	<div class="col-12">
		<form id="frmshipping" class="mb-0" name="frmshipping" method="post" action="{{config('global.SITE_URL')}}shipping" style='display:none' @if(Session::get('ShoppingCart.BillingAsShipping') == 'Yes') style='display:none' @endif  >
			{{ csrf_field() }}
            <input type="hidden" name="action" id="action" value="shippinginfo"/>
            <input type="hidden" name="OnlyHead" id="OnlyHead" value="0"/>
            <div class="row">
				<div class="col-12">
					<div class="form-group">
						<label for="ship_fname" class="col-form-label">First Name<span class="errmsg">*</span></label>
						<input id="ship_fname" name="ship_fname" type="text" placeholder="First Name" value="{{$Shipping['first_name']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_fname']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_lname" class="col-form-label">Last Name<span class="errmsg">*</span></label>
						<input id="ship_lname" name="ship_lname" type="text" placeholder="Last Name" value="{{$Shipping['last_name']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_lname']"/>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="ship_company" class="col-form-label">Company</label>
						<input id="ship_company" name="ship_company" type="text" placeholder="Company" value="{{$Shipping['company']}}" class="form-control">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_address1" class="col-form-label">Address1<span class="errmsg">*</span></label>
						<input id="ship_address1" name="ship_address1" type="text" placeholder="Address" value="{{$Shipping['address1']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_address1']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_address2" class="col-form-label">Address2</label>
						<input id="ship_address2" name="ship_address2" type="text" placeholder="Address" value="{{$Shipping['address2']}}" class="form-control">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_city" class="col-form-label">City<span class="errmsg">*</span></label>
						<input id="ship_city" name="ship_city" type="text" placeholder="City" value="{{$Shipping['city']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_city']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group selectbox-form">
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
				<div class="col-12">
					<div class="form-group selectbox-form">
						<div class="field required" id="divshipstate" style="@if($Shipping['country'] != 'US' || (Session::has('ShoppingCart.ShippingAddress.country') && Session::get('ShoppingCart.ShippingAddress.country') != 'US')) display:none; @else '' @endif">
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
						<div class="field required otherstate" id="divshipotherstate" style="@if($Shipping['country'] == 'US' || (Session::has('ShoppingCart.ShippingAddress.country') && Session::get('ShoppingCart.ShippingAddress.country') == 'US')) display:none; @else display:block; @endif">
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
				<div class="col-12">
					<div class="form-group">
						<label for="ship_zip" class="col-form-label">Zip<span class="errmsg">*</span></label>
						<input id="ship_zip" name="ship_zip" type="text" placeholder="Zip" value="{{$Shipping['zip']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_zip']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_phone" class="col-form-label">Phone<span class="errmsg">*</span></label>
						<input id="ship_phone" name="ship_phone" type="text" placeholder="e.g. +1 444 123 4567" value="{{$Shipping['phone']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_phone']"/>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<label for="ship_email" class="col-form-label">Email<span class="errmsg">*</span></label>
						<input id="ship_email" name="ship_email" type="text" placeholder="e.g. jhone@gmail.com" value="{{$Shipping['email']}}" class="form-control">
						<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_ship_email']"/>
					</div>
				</div>
			</div>
            <input type="hidden" name="amazon_callback" id="amazon_callback" value="{{config('CALLBACK_CHECKOUT_URL')}}"/>
			<input type="hidden" id="amazon_fund_callback" value="{{config('CALLBACK_URL')}}"/>
			<input type="hidden" name="SelPayMethod" id="SelPayMethod" value="{{$SelPayMethod}}"/>
			<input type="hidden" name="onlyGCPurchased" id="onlyGCPurchased" value="{{$CartAttr['onlyGCPurchased']}}"/>
			<input type="hidden" name="IsVenderItem" id="IsVenderItem" value="{{$CartAttr['IsVenderItem']}}"/>
			<input type="hidden" name="Afterpay_Checkout" id="Afterpay_Checkout" value="{{$CartAttr['Afterpay_Checkout']}}"/>
			<input type="hidden" name="IsCosmo" id="IsCosmo" value="{{$CartAttr['IsCosmo']}}"/>
			<input type="hidden" name="IsNandansons" id="IsNandansons" value="{{$CartAttr['IsNandansons']}}"/>
			<input type="hidden" name="IsPerfumePW" id="IsPerfumePW" value="{{$CartAttr['IsPerfumePW']}}"/>
			<input type="hidden" name="IsPCA" id="IsPCA" value="{{$CartAttr['IsPCA']}}"/>
			<input type="hidden" name="IsMaxaromaTwoDelivery" id="IsMaxaromaTwoDelivery" value="{{$CartAttr['IsMaxaromaTwoDelivery']}}"/>
			<input type="hidden" name="ISMaxTwoItem" id="ISMaxTwoItem" value="{{$CartAttr['ISMaxTwoItem']}}"/>
			<input type="hidden" name="ISMax2dayVal" id="ISMax2dayVal" value="No"/>
		</form>
	</div>
	@endif
	
	
	<div class="col-12 pb-md-5 pt-3">
		<div class="chekout-main-btn">
			<a class="max_continuesp_btn btn btn-primary d-block" id="btnbill_step1" href="javascript:void(0)">Continue to Shipping</a>
		</div>
	</div>
	<div class="col-12 d-md-none">
		<div class="mobile-shadow">&nbsp;</div>
	</div>
	<div class="col-12 f12 text-center">
		<p class="pb-3 lheight-18">By continuing, you acknowledge that Max Aroma will handle your information as set out in the <a href="{{config('global.SITE_URL')}}privacy-policy.html" class="ulink">Privacy Policy</a> including why we collect it, how we use it and your rights.</p>
		<a href="{{config('global.SITE_URL')}}shoppingcart/new" class="ulink"><strong>Back To Cart</strong></a>
	</div>
</div>
