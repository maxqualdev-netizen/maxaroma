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
					<form action="" method="post" id="frmRegister">
						<input type="hidden" name="action" value="signup"/>
						{{ csrf_field() }}
						<div class="row">
							<div class="col-lg-10 offset-lg-1 col-md-12 myact">
								<p class="text-center"><strong>For wholesaler registration please <a href="{{ url('/wholesaleregister.html') }}" class="ulink">click here</a></strong></p>
								<div class="row row10">
									@php 
										$fsvg = [
											'title' => 'Login with Facebook',
											'symbol' => 'sv-facebook',
											'height' => '18',
											'width' => '18',
											'classname' => 'sv-facebook vam',
										];
										$gsvg = [
											'title' => 'Login with Gmail',
											'symbol' => 'sv-gmail-0',
											'height' => '26',
											'width' => '26',
											'classname' => 'sv-gmail-0 vam mr-1',
										];
									@endphp
									<div class="col-md-6 pb-2 mt-1">
										<x-button link="{{url('/redirect')}}" btntext='Facebook' classname="btn btn-primary btn-facebook d-block  no-hw-svg" :bsvg="$fsvg"/>
									</div>
									<div class="col-md-6 pb-2 mt-1">
										<x-button link="{{url('/redirectgoogle')}}" btntext='Gmail' classname="btn btn-primary btn-gmail d-block  no-hw-svg" :bsvg="$gsvg"/>
									</div>
								</div>
								<div class="divider-or"><span>or</span></div>
								<div class="text-right fw500"><span class="errmsg">*</span>Required Fields</div>
								<div class="row">
									@if (count($errors) > 0)
										@if($errors->has('existing_email'))
											<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ $errors->first('existing_email') }}</small></div> -->
											<div class="col-md-12">
												<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('existing_email')]"
												/>
											</div>
										@endif
										@if($errors->has('duplicate_ip'))
											<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ $errors->first('duplicate_ip') }}</small></div> -->
											<div class="col-md-12">
												<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('duplicate_ip')]"
												/>
											</div>
										@endif										
									@endif
									@if (Session::has('duplication_ip_error'))
                                            <div class="frmerror frmerror_shw" role="alert" style="">
                                                {!! Session::get('duplication_ip_error') !!}
                                            </div>    
                                        @endif
									@if (Session::has('success'))
										<!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ Session::get('success') }}</small></div> -->
										<div class="col-md-12">
											<x-message :attr="[
												'classname' => 'frmsuccess', 
												'message' => Session::get('success')]"
											/>
										</div>
									@endif
									<div class="col-md-6 pt-3">
										<div class="field required">
											<label for="email-address">Email Address</label>
											<input type="email" id="email" name="email" placeholder="Email Address" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
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
											<label for="password">Password</label>
											<input type="password" id="password" name="password" placeholder="Password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}">							
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
											<label for="confirm_Password">Confirm Password</label>
											<input type="password" id="confirm_Password" name="confirm_Password" placeholder="Confirm Password" class="form-control {{ $errors->has('confirm_Password') ? ' is-invalid' : '' }}">
											<x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_confirm_Password']"
											/>
											@if ($errors->has('confirm_Password'))
												<x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('confirm_Password')]"
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
													<input type="text" id="first_name" name="first_name" maxlength="10" value="{{ old('first_name') }}" placeholder="First Name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}">
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
													<input type="text" id="last_name" name="last_name" maxlength="10" value="{{ old('last_name') }}" placeholder="Last Name" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}">
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
										<div class="field">
											<label for="company_name">Company Name</label>
											<input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 pt-3">
										<div class="field required">
											<label for="address1">Street Address 1</label>
											<input type="text" id="address1" name="address1" rows="4" cols="50" placeholder="Street Address 1" class="form-control {{ $errors->has('address1') ? ' is-invalid' : '' }}">{{ old('address1') }}</input>
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
											<label for="address2">Street Address 2</label>
											<input type="text" id="address2" name="address2" rows="4" cols="50" value="{{ old('address2') }}" placeholder="Street Address 2" class="form-control"></input>
										</div>
										<input type="hidden" id="to" name="to">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="row row10">
											<div class="col-sm-6 pt-3">
												<div class="field required">
													<label for="city">City</label>
													<input type="text" id="city" name="city" maxlength="10" value="{{ old('city') }}" placeholder="City" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}">
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
														'selected' => (old('country')?old('country'):'US'),
														]"
													/>
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
															'selected' => old('state'),
															]"
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
														<input type="text" id="other_state" name="other_state" maxlength="10" value="{{ old('other_state') }}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}">
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
														<input type="text" id="zip" name="zip" value="{{ old('zip') }}" placeholder="zip" class="form-control {{ $errors->has('zip') ? ' is-invalid' : '' }}">
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
												<label for="phone">Phone</label>
												<input type="text" id="phone" name="phone" value="{{ old('phone') }}" onkeypress="return isNumber(event)" placeholder="Phone" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}">
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
												<label for="birthday">Birthday - ( MM-DD-YYYY )</label>
												<input type="date" id="birthday" data-date="" data-date-format="MM/DD/YYYY" name="birthday" value="{{ old('birthday') }}" placeholder="Birthday - ( MM-DD-YYYY )" class="form-control input-date">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 pt-3">
											<div class="field">
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
											</div>
										</div>
										<div class="col-md-6 pt-3">
											<div class="field required here_about_opt" id="divinstagram" style="@if(old('hear_about')) @if(old('hear_about') == 'Instagram') display:block; @else display:none; @endif @endif">
												<x-selectbox :attr="[
													'label' => 'Instagram',
													'id' => 'instagram', 'name' => 'instagram',
													'firstopt' => 'Select Instagram Value',
													'classname' => 'form-control',
													'data' => $InstagramAttrs,
													'selected' => old('instagram'),
													'datafield' => 'attributevalue'
													]"
												/>
												<x-message :attr="[
													'classname' => 'frmerror', 
													'message' => '',
													'mid' => 'error_instagram']"
												/>
												@if ($errors->has('instagram'))
													<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('instagram')]"
													/>
												@endif
											</div>
											<div class="field required here_about_opt" id="divyoutube" style="@if(old('hear_about')) @if(old('hear_about') == 'Youtube') display:block; @else display:none; @endif @endif">
												<x-selectbox :attr="[
													'label' => 'Youtube',
													'id' => 'youtube', 'name' => 'youtube',
													'firstopt' => 'Select Youtube Value',
													'classname' => 'form-control',
													'data' => $YoutubeAttrs,
													'selected' => old('youtube'),
													'datafield' => 'attributevalue'
													]"
												/>
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
												<input type="text" id="other" name="other" value="{{ old('other') }}" class="form-control {{ $errors->has('other') ? ' is-invalid' : '' }}">
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
										<div class="col-md-6 pt-3">
											<div class="field required">
												@include('googlecaptcha')
											</div>
										</div>
									</div>					  
									<div class="text-right pt-3">
										<!-- <x-button btype="reset" btntext="Reset" classname="btn btn-secondary" name="sbtreset"/> -->
										<!-- <x-button btype="submit" btntext="Submit" classname="btn btn-primary" name="sbtsubmit"/> -->
										<x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnRegisterReset"/>
										<x-button link="javascript:void(0);" btntext="Sign Up" classname="btn btn-primary" bid="btnRegister"/>
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
