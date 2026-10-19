@extends('layouts.app')
@section('content')
<main>
	<section class="smallcon">
		<div class="container">
			<!--<div class="myact_hd">
				<svg class="sv-registerb" aria-hidden="true" role="img"><use href="#sv-registerb" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-registerb"></use></svg>
				<h2>Registration</h2>
			</div>-->
			<div class="row">
			   <div class="col-12">
			      <form action="" method="post" id="frmWholesaleRegister" enctype="multipart/form-data">
					<input type="hidden" name="action" value="signup"/>
					{{ csrf_field() }}
			         <div class="row">
			            <div class="col-lg-10 offset-lg-1 col-md-12 myact">
			               <p class="text-center"><strong>Not a Wholesaler ? <a href="{{ url('/register.html') }}" class="ulink">click here</a></strong></p>
			               <div class="pt-3 text-right fw500"><span class="errmsg">*</span>Required Fields</div>
			               <div class="row">
								@if (count($errors) > 0)
									@if($errors->has('existing_email'))
										<div class="col-md-12">
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('existing_email')]"
											/>
										</div>
									@endif
									@if($errors->has('duplicate_ip'))
										<div class="col-md-12">
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('duplicate_ip')]"
											/>
										</div>
									@endif
									
								@endif
								@if (Session::has('success'))
									<div class="col-md-12">
										<x-message :attr="[
											'classname' => 'frmsuccess', 
											'message' => Session::get('success')]"
										/>
									</div>
								@endif
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
			                        <label for="company_name">Company Name</label>
			                        <input type="text" id="company_name" name="company_name" placeholder="Company Name" class="form-control" value="{{ old('company_name') }}">
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_company_name']"
									/>
									@if ($errors->has('company_name'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('company_name')]"
										/>
									@endif
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
			                        <label for="password">Password</label>
			                        <input type="password" id="password" name="password" placeholder="Password" class="form-control">							
			                        <small class="pt-1 d-block">Don't use space when entering your password.</small>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_password']"
									/>
									@if ($errors->has('password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('password')]"
										/>
									@endif
			                     </div>
			                  </div>
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
			                        <label for="confirm_password">Confirm Password</label>
			                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control">
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_confirm_password']"
									/>
									@if ($errors->has('confirm_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('confirm_password')]"
										/>
									@endif
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6">
			                     <div class="row row10">
			                        <div class="col-sm-6 pt-3">
			                           <div class="field required">
			                              <label for="first_name">First Name</label>
			                              <input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name') }}">
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
			                              <label for="last_name">Last Name</label>
			                              <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}">
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
			                        <label for="phone">Phone</label>
			                        <input type="tel" id="phone" name="phone" placeholder="Phone" class="form-control" value="{{ old('phone') }}">
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
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field">
			                        <label for="salestax_id">Sales Tax ID</label>
			                        <input type="text" id="salestax_id" name="salestax_id" placeholder="Sales Tax ID" class="form-control" value="{{ old('salestax_id') }}">
			                        <small class="pt-1 d-block"><strong>Note: NYS Resale Certificate *</strong></small>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_salestax_id']"
									/>
									@if ($errors->has('salestax_id'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('salestax_id')]"
										/>
									@endif
			                     </div>
			                  </div>
			                  <div class="col-md-6 pt-3">
			                     <div class="field">
			                        <label for="upload_sales_tax">Upload Sales Tax Exemption Form</label>
			                        <input type="file" id="upload_sales_tax" name="upload_sales_tax" placeholder="Upload Sales Tax Exemption Form" class="browinput form-control" placeholder="Upload Sales Tax Exemption Form" />
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
			                        <label for="email_address">Email Address</label>
			                        <input type="email" id="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email') }}">
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
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
			                        <label for="address1">Address Line 1</label>
			                        <textarea type="text" id="address1" rows="4" cols="50" name="address1" placeholder="Address Line 1" class="form-control">{{ old('address1') }}</textarea>
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
			                        <label for="address2">Address Line 2</label>
			                        <textarea type="text" id="address2" rows="4" cols="50" name="address2" placeholder="Address Line 2" class="form-control">{{ old('address2') }}</textarea>
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6">
			                     <div class="row row10">
			                        <div class="col-sm-6 pt-3">
			                           <div class="field required">
			                              <label for="city">City</label>
			                              <input type="text" id="city" name="city" placeholder="City" class="form-control" value="{{ old('city') }}">
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
			                  <div class="col-md-6">
			                     <div class="row row10">
			                        <div class="col-sm-6 pt-3">
										<div class="field required" id="divstate" style="@if(old('country') && old('country') != 'US') display:none; @else '' @endif">
											<x-selectbox :attr="[
												'label' => 'State',
												'id' => 'state', 'name' => 'state',
												'firstopt' => '',
												'classname' => 'form-control',
												'data' => $States,
												'selected' => old('state')]"
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
										<div class="field required otherstate" id="divotherstate" style="@if(old('country')) @if(old('country') == 'US') display:none; @else display:block; @endif @endif">
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
			                              <label for="zip">Zip</label>
			                              <input type="text" id="zip" name="zip" placeholder="Zip" class="form-control" value="{{ old('zip') }}">
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
			                     <div class="field">
			                        <label for="company_website">Company Website</label>
			                        <input type="text" id="company_website" name="company_website" placeholder="Company Website" class="form-control" value="{{ old('company_website') }}">
			                     </div>
			                  </div>
			                  <div class="col-md-6 pt-3">
			                     <div class="field">
			                        <label for="stores">How Many Stores Do You Have?</label>
			                        <input type="text" id="stores" name="stores" placeholder="How Many Stores Do You Have?" class="form-control" value="{{ old('stores') }}">
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
									<x-selectbox :attr="[
										'label' => 'Do You Sell Online?',
										'id' => 'sell_online', 'name' => 'sell_online',
										'firstopt' => '',
										'classname' => 'form-control',
										'data' => ['No' => 'No', 'Yes' => 'Yes'],
										'selected' => (old('sell_online')?old('sell_online'):'No')]"
									/>
			                     </div>
			                  </div>
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
									<x-selectbox :attr="[
										'label' => 'Do You Sell On Any Third Party Websites?',
										'id' => 'website', 'name' => 'website',
										'firstopt' => '',
										'classname' => 'form-control',
										'data' => ['No' => 'No', 'Yes' => 'Yes'],
										'selected' => (old('website')?old('website'):'No')]"
									/>
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field">
			                        <label for="webiste_url">Third Party Websites URL?</label>
			                        <input type="text" id="webiste_url" name="webiste_url" placeholder="Third Party Websites URL?" class="form-control" value="{{ old('webiste_url') }}">
			                     </div>
			                  </div>
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
									@php 
										$HearAbout = [
											"Facebook" => "Facebook",
											"Pinterest" => "Pinterest",
											"Instagram" => "Instagram",
											"Twitter" => "Twitter",
											"Friends" => "Friends",
											"Our Weblog" => "Our Weblog",
											"Youtube" => "Youtube",
											"Email" => "Email",
											"Other" => "Other"];
									@endphp
									<x-selectbox :attr="[
										'label' => 'How Did You Hear About US?',
										'id' => 'hear_about', 'name' => 'hear_about',
										'firstopt' => 'How did you hear about us?',
										'classname' => 'form-control',
										'data' => $HearAbout,
										'selected' => old('hear_about'),
										]"
									/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_hear_about']"
									/>
									@if ($errors->has('hear_about'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('hear_about')]"
										/>
									@endif
			                     </div>
			                  </div>
			               </div>
			               <div class="row">
			                  <div class="col-md-6 pt-3">
			                     <div class="field required">
									<x-selectbox :attr="[
										'label' => 'Customer Type',
										'id' => 'is_dropshipper', 'name' => 'is_dropshipper',
										'firstopt' => '',
										'classname' => 'form-control',
										'data' => ['No' => 'Wholesaler', 'Yes' => 'Dropshipper'],
										'selected' => (old('is_dropshipper')?old('is_dropshipper'):'No')]"
									/>
			                     </div>
			                  </div>
								<div class="col-md-6 pt-3">
				                    <div class="field" id="diveinnumber" style="@if(old('state')) @if(old('state') == 'NY') display:block; @else display:none; @endif @endif">
				                        <label for="einnumber">EIN Number</label>
				                        <input type="text" id="einnumber" name="einnumber" placeholder="EIN Number" class="form-control" value="{{ old('einnumber') }}">
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
									<div class="field required here_about_opt" id="divyoutube" style="@if(old('hear_about')) @if(old('hear_about') == 'Youtube') display:block; @else display:none; @endif @endif">
										<label for="youtube">Which Youtuber</label>
										<input type="text" id="youtube" name="youtube" placeholder="Which Youtuber?" class="form-control" value="{{ old('youtube') }}">
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_youtube']"
										/>
										@if ($errors->has('youtube'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('youtube')]"
											/>
										@endif
									</div>
									<div class="field required here_about_opt" id="divother" style="@if(old('hear_about')) @if(old('hear_about') == 'Other') display:block; @else display:none; @endif @endif">
										<label for="other">Other</label>
										<input type="text" id="other" name="other" value="{{ old('other') }}" placeholder="Other" class="form-control">
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_other']"
										/>
										@if ($errors->has('other'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('other')]"
											/>
										@endif
									</div>
								</div>
			               </div>
			               <div class="row">
			                  <div class="col-md-12 pt-3">
			                     <div class="field required">
			                        <div class="d-block">
			                           <strong>How the drop shipping and wholesale works. Please click <a href="javascript:void(0);" class="ulink" onclick="DisplayShippingServicePopUp();">here</a></strong>
			                        </div>
			                        <div class="d-block pt-3">
										@include('googlecaptcha')
			                        </div>
			                        <div class="d-block pt-3">
			                           <label class="checkbox-label">
			                              <div class="chebox"><input type="checkbox" name="policy" id="policy" value="Yes"><span class="checkmark"></span></div>
			                              I have read and understood Maxaroma's <a href="javascript:void(0);" class="ulink" onclick="DisplayWholesalerShippingPolicyPopUp();">policy.</a>
			                           </label>
			                        </div>
			                        <div class="d-block pt-2">
			                           <label class="checkbox-label">
			                              <div class="chebox"><input type="checkbox" name="knowledge" id="knowledge" value="Yes"><span class="checkmark"></span></div>
			                              The information above is true and accurate to the best of my knowledge.
			                           </label>
			                        </div>
			                        <div class="d-block pt-2">
			                           <strong>It may take 24 to 48 hours for wholesale account approval.</strong>
			                        </div>
			                     </div>
			                  </div>
			               </div>
			               <div class="text-right pt-3">
			               	<!-- <a href="#" class="btn btn-secondary">Reset</a>
			               	<a href="#" class="btn btn-primary">Submit</a> -->
								<!-- <x-button btype="reset" btntext="Reset" name="sbtreset" classname="btn btn-secondary"/>
								<x-button btype="submit" btntext="Submit" name="sbtsubmit" classname="btn btn-primary"/> -->
								<x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnWholesaleRegisterReset"/>
								<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnWholesaleRegister"/>
			               </div>
			            </div>
			         </div>
			      </form>
			   </div>
			</div>
		</div>
	</section>
</main>
@endsection