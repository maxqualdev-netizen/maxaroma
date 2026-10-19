<?php $email = (isset($email)?$email:'') ?>
@if ($isAction == 'sign_in')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center user-login-modal">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="modal-space">
                    <form action="login_pdetail_page" id="formSignIn">
                        <input type="hidden" name="isAction" value="sign_in">
                         <input type="hidden" name="checkloginPopval" id="checkloginPopval" value="{{$checkloginPopval}}">
                        <input type="hidden" name="check_value" value="1" id="check_value">
                        {{ csrf_field() }}
                        <div class="login">
                            <div class="row">
                                <div class="col-12">
                                    <div class="spg-panel">
                                        <h3>Sign In</h3>
						@if (Session::has('failed'))
                                                    <x-message :attr="[
                                                                'classname' => 'pl-3 frmerror frmerror_shw pb-3', 
                                                                'message' => Session::get('failed')]"
                                                    />
                                                @endif
                                        @if (count($errors) > 0)
                                        @if($errors->has('login'))
                                        <x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('login')]" />
                                        @endif
                                        @endif
                                        @if ($errors->has('Failed'))
                                        <x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('Failed')]" />
                                        @endif
                                        <div class="spg-body loginbody pb-sm-0">
                                            <div class="eheight">
                                                <div class="form-group">
                                                    <label for="lname" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter Your Email Address" autofocus />
                                                    <x-message :attr="[
                                                        'classname' => 'frmerror', 
                                                        'message' => '',
                                                        'mid' => 'error_email']" />
                                                    @if ($errors->has('email'))
                                                    <x-message :attr="[
                                                            'classname' => 'frmerror frmerror_shw', 
                                                            'message' => $errors->first('email')
                                                            ]" />
                                                    @endif
                                                </div>
                                                <div class="pass-div form-group">
                                                    
                                                    <!-- <a href="#" class="sml-text float-right
                                                         ">Forgot your password?</a> -->
                                                        <label for="lname" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
                                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_password'
                                                            ]" />
                                                        @if ($errors->has('password'))
                                                        <x-message :attr="[
                                                                'classname' => 'frmerror frmerror_shw', 
                                                                'message' => $errors->first('password')]" />
                                                        @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="checkbox-label">
                                                        <div class="chebox">
                                                            <input type="checkbox" name="rememberme" id="rememberme" checked=""><span class="checkmark"></span>
                                                        </div>Remember Me<span class="errmsg">*</span>
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_rememberme'
                                                            ]" />
                                                    </label>
                                                </div>
						
                                            </div>
                                            <x-button btype="submit" btntext="Sign In" classname="btn btn-primary w-100" bid="btnsubmit" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 pt-3">
                                    <div class="spg-panel spg-panel1">
                                        <h3>Create An Account</h3>
                                        <div class="spg-body loginbody pb-sm-0">
                                            <div class="eheight creating_text">
                                                <ul class="dots">
                                                    <li>Giveaway Alerts</li>
                                                    <li>Easily redeem rewards</li>
                                                    <li>Brand Drops</li>
                                                    <li>Presale and exclusive access</li>
                                                    <li>Easily Presale track orders and view order history</li>
                                                </ul>
                                            </div>
                                            <div class="">
                                                <a href="javascript:void(0);" data-action="sign_upfirstpart" class="btn btn-secondary w-100 signinsignup">Sign up</a>
                                            </div>
                                        </div>
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
@endif

@if ($isAction == 'sign_upfirstpart')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center user-login-modal">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="modal-space">
                    <form action="login_pdetail_page" id="frmSignUpFirstPart">
                        <input type="hidden" name="isAction" value="sign_upsecondpart">
                        <input type="hidden" name="check_value" value="0" id="check_value">
                        {{ csrf_field() }}
                        <div class="login">
                            <div class="row">
                                <div class="col-12">
                                    <div class="spg-panel">
                                        <h3 class="text-center">Create an Account</h3>
                                        <hr>
                                        @if (count($errors) > 0)
                                        @if($errors->has('login'))
                                        <x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('login')]" />
                                        @endif
                                        @endif
                                        @if ($errors->has('Failed'))
                                        <x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('Failed')]" />
                                        @endif
                                        
                                        <div class="spg-body loginbody pb-sm-0">
                                            <div class="eheight">
                                                <div class="form-group">
                                                    <label for="lname" class="col-form-label pt-0 text-left">Email<span class="errmsg">*</span></label>
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter Your Email Address" autofocus />
                                                    <x-message :attr="[
                                                        'classname' => 'frmerror', 
                                                        'message' => '',
                                                        'mid' => 'error_email']" />
                                                    @if ($errors->has('email'))
                                                    <x-message :attr="[
                                                            'classname' => 'frmerror frmerror_shw', 
                                                            'message' => $errors->first('email')
                                                            ]" />
                                                    @endif
                                                    @if (Session::has('error'))
                                                    <x-message :attr="[
                                                        'classname' => 'frmerror frmerror_shw', 
                                                        'message' => Session::get('error')]"
                                                    />
                                                    @endif
                                                </div>
                                                <div class="pass-div form-group">
                                                   
                                                    <!-- <a href="#" class="sml-text float-right
                                                         ">Forgot your password?</a> -->
                                                    
                                                         <label for="lname" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
                                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_password'
                                                            ]" />
                                                        @if ($errors->has('password'))
                                                        <x-message :attr="[
                                                                'classname' => 'frmerror frmerror_shw', 
                                                                'message' => $errors->first('password')]" />
                                                        @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="checkbox-label mt-2">
                                                    <div class="chebox">
                                                        <input type="checkbox" id="termsprivacy" name="termsprivacy" checked=""><span class="checkmark"></span>
                                                    </div>
                                                    By creating an account, you agree to receive MAXAROMA's marketing emails to get first dibs on new arrivals, sales, exclusive content, and more! You may unsubscribe at any time.<br> By creating an account, you agree to MAXAROMA's <a href="{{config('global.SITE_URL')}}terms-and-conditions.html" target="_blank">Terms</a> of use and <a target="_blank" href="{{config('global.SITE_URL')}}privacy-policy.html">Privacy Policy</a>.<span class="errmsg">*</span>
                                                    <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_termsprivacy'
                                                            ]" />
                                                </label>
                                                </div>
                                            </div>
                                            <x-button btype="submit" btntext="CREATE AN ACCOUNT" classname="btn btn-primary w-100" bid="btnsubmit" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 pt-3">
                                    <div class="spg-panel spg-panel1">
                                        <h3 class="text-center">Already Have an Account?</h3>
                                        <div class="spg-body loginbody pb-sm-0">
                                            <div class="">
                                                <a href="javascript:void(0);" data-action="sign_in" class="btn btn-secondary w-100 signinsignup">SIGN IN</a>
                                            </div>
                                        </div>
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
@endif

@if ($isAction == 'sign_upsecondpart')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body signup-popup">
                <div class="friend-mail-modal">
                    <div class="modal-hd text-center">
                        <h3>Join MAXAROMA today for exclusive access to specials sales, new arrivals & more. Sign-up below is easy and free</h3>
                    </div>
                    <div class="modal-space pt-3">
                        <form action="" method="post" id="frmSignUpSecondPart">
                            <input type="hidden" name="check_value" value="1" id="check_value">
                            <input type="hidden" name="isAction" value="sign_upsecondpart">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 myact pl-3 pr-3 pl-lg-5 pr-lg-5 pt-0">
                                    <p class="text-center"><strong>For wholesaler registration please <a href="{{ url('/wholesaleregister.html') }}" class="ulink">click here</a></strong></p>
                                    <div class="divider-or"><span>or</span></div>
                                    <div class="text-right fw500"><span class="errmsg">*</span>Required Fields</div>
                                    <div class="row">
                                        @if (count($errors) > 0)
                                        @if($errors->has('existing_email'))
                                        <!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ $errors->first('existing_email') }}</small></div> -->
                                        <div class="col-md-12">
                                            <x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('existing_email')]" />
                                        </div>
                                        @endif
                                        @if($errors->has('duplicate_ip'))
                                        <!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ $errors->first('duplicate_ip') }}</small></div> -->
                                        <div class="col-md-12">
                                            <x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('duplicate_ip')]" />
                                        </div>
                                        @endif
                                        <div class="col-md-12">
                                            @if (Session::has('error'))
                                            <x-message :attr="[
                                                'classname' => 'frmerror frmerror_shw', 
                                                'message' => Session::get('error')]"
                                            />
                                            @endif
                                        </div>

                                        @endif
                                        @if (Session::has('success'))
                                        <!-- <div class="col-md-12"><small class="pt-1 d-block frmerror frmerror_shw">{{ Session::get('success') }}</small></div> -->
                                        <div class="col-md-12">
                                            <x-message :attr="[
												'classname' => 'frmsuccess', 
												'message' => Session::get('success')]" />
                                        </div>
                                        @endif
                                        <div class="col-md-6 pt-3">
                                            <div class="field">
                                                <label for="email-address">Email Address<span class="errmsg">*</span></label>
                                                <input type="email" id="email" name="email" placeholder="Email Address" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ ($email?$email:old('email')) }}">
                                                <x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_email']" />
                                                @if ($errors->has('email'))
                                                <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('email')]" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 pt-3 signup-ge">
                                            <label for="email-address">Gender</label>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="comcheck radio checkbox-label clsship mr-1">
                                                        <div class="chebox">
                                                            <input type="radio" class="gendermalefemale" name="gender" value="female" />
                                                            <span class="checkmark"></span>
                                                            <span class="float-left w-75">
                                                                <div>
                                                                    <span class="tables">Female</span>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </label>
                                                    <label class="comcheck radio checkbox-label clsship">
                                                        <div class="chebox">
                                                            <input type="radio" class="gendermalefemale" name="gender" value="male" />
                                                            <span class="checkmark"></span>
                                                            <span class="float-left w-75">
                                                                <div>
                                                                    <span class="tables">Male</span>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-12 nichedesignerspray" style="display: none;">
                                                    <label class="checkbox-label mr-1">
                                                        <div class="chebox">
                                                            <input type="checkbox" id="niche" name="niche" ><span class="checkmark"></span>
                                                        </div>
                                                        Niche
                                                    </label>
                                                    <label class="checkbox-label mr-1">
                                                        <div class="chebox">
                                                            <input type="checkbox" id="designer" name="designer" ><span class="checkmark"></span>
                                                        </div>
                                                        Designer
                                                    </label>
                                                    <label class="checkbox-label">
                                                        <div class="chebox">
                                                            <input type="checkbox" id="traveler_spray" name="traveler_spray" ><span class="checkmark"></span>
                                                        </div>
                                                        Traveler Spray
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 pt-3">
                                            <div class="field">
                                                <label for="password">Password<span class="errmsg">*</span></label>
                                                <input type="password" id="password" name="password" placeholder="Password" value="{{ ($password?$password:old('password')) }}" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}">
                                                <small class="pt-1 d-block">Don't use space when entering your password.</small>
                                                <x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_password']" />
                                                @if ($errors->has('password'))
                                                <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('password')]" />
                                                @endif
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-6 pt-3">
                                            <div class="field">
                                                <label for="confirm_Password">Confirm Password<span class="errmsg">*</span></label>
                                                <input type="password" id="confirm_Password" name="confirm_Password" value="{{ $password }}" placeholder="Confirm Password" class="form-control {{ $errors->has('confirm_Password') ? ' is-invalid' : '' }}">
                                                <x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_confirm_Password']" />
                                                @if ($errors->has('confirm_Password'))
                                                <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('confirm_Password')]" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 pt-1 check-small">
                                            <label class="checkbox-label mt-2">
                                                    <div class="chebox">
                                                        <input type="checkbox" id="agreepolicy" name="agreepolicy" checked="" ><span class="checkmark"></span>
                                                    </div>
                                                    By creating an account, you agree to receive MAXAROMA marketing emails to get first dibs on new arrivals, sales, exclusive content, and more! You may unsubscribe at any time.<span class="errmsg">*</span>
                                                    <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_agreepolicy'
                                                            ]" />
                                                </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row row10">
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field">
                                                        <label for="first_name">First Name<span class="errmsg">*</span></label>
                                                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}">
                                                        <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_first_name']" />
                                                        @if ($errors->has('first_name'))
                                                        <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('first_name')]" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field">
                                                        <label for="last_name">Last Name<span class="errmsg">*</span></label>
                                                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}">
                                                        <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_last_name']" />
                                                        @if ($errors->has('last_name'))
                                                        <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('last_name')]" />
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
                                            <div class="field">
                                                <label for="address1">Street Address 1<span class="errmsg">*</span></label>
                                                <input type="text" id="address1" name="address1" rows="4" cols="50" placeholder="Street Address 1" class="form-control {{ $errors->has('address1') ? ' is-invalid' : '' }}">{{ old('address1') }}</input>
                                                <x-message :attr="[
												'classname' => 'frmerror', 
												'message' => '',
												'mid' => 'error_address1']" />
                                                @if ($errors->has('address1'))
                                                <x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('address1')]" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 pt-3">
                                            <div class="field">
                                                <label for="address2">Street Address 2</label>
                                                <input type="text" id="address2" name="address2" rows="4" cols="50" value="{{ old('address2') }}" placeholder="Street Address 2" class="form-control"></input>
                                            </div>
                                        </div>
                                        <div class="col-12 pt-1 input-lsmall">
                                              <label class="checkbox-label mt-2">
                                                    <div class="chebox">
                                                        <input type="checkbox" id="termsprivacy" name="termsprivacy" checked=""><span class="checkmark"></span>
                                                    </div>
                                                    By creating an account, you agree to MAXAROMA's <a href="{{config('global.SITE_URL')}}terms-and-conditions.html" target="_blank">Terms</a> and <a target="_blank" href="{{config('global.SITE_URL')}}privacy-policy.html">Privacy Policy</a>. <span class="errmsg">*</span>
                                                    <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_termsprivacy'
                                                            ]" />
                                                </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row row10">
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field">
                                                        <label for="city">City<span class="errmsg">*</span></label>
                                                        <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="City" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}">
                                                        <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_city']" />
                                                        @if ($errors->has('city'))
                                                        <x-message :attr="[
															'classname' => 'frmerror frmerror_shw', 
															'message' => $errors->first('city')]" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field">
                                                        <x-selectbox :attr="[
														'label' => 'Country',
														'id' => 'country', 'name' => 'country',
														'firstopt' => '',
														'classname' => 'form-control',
														'data' => $Countries,
														'selected' => (old('country')?old('country'):'US'),
														]" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row row10">
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field" id="divstate" style="@if(old('country') && old('country') != 'US') display:none; @else '' @endif">
                                                        <x-selectbox :attr="[
															'label' => 'State',
															'id' => 'state', 'name' => 'state',
															'firstopt' => '',
															'classname' => 'form-control',
															'data' => $States,
															'selected' => old('state'),
															]" />
                                                        <x-message :attr="[
															'classname' => 'frmerror', 
															'message' => '',
															'mid' => 'error_state']" />
                                                        @if ($errors->has('state'))
                                                        <x-message :attr="[
																'classname' => 'frmerror frmerror_shw', 
																'message' => $errors->first('state')]" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 pt-3">
                                                    <div class="field">
                                                        <label for="zip">Zip<span class="errmsg">*</span></label>
                                                        <input type="text" id="zip" name="zip" value="{{ old('zip') }}" placeholder="zip" class="form-control {{ $errors->has('zip') ? ' is-invalid' : '' }}">
                                                        <x-message :attr="[
															'classname' => 'frmerror', 
															'message' => '',
															'mid' => 'error_zip']" />
                                                        @if ($errors->has('zip'))
                                                        <x-message :attr="[
																'classname' => 'frmerror frmerror_shw', 
																'message' => $errors->first('zip')]" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 pt-3">
                                            <div class="field">
                                                <label for="phone">Phone<span class="errmsg">*</span></label>
                                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}">
                                                <x-message :attr="[
													'classname' => 'frmerror', 
													'message' => '',
													'mid' => 'error_phone']" />
                                                @if ($errors->has('phone'))
                                                <x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('phone')]" />
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
													]" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 pt-3">
                                            <div class="field required">
                                                @include('googlecaptcha')
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="text-right pt-3">
                                        <!-- <x-button btype="reset" btntext="Reset" classname="btn btn-secondary" name="sbtreset"/> -->
                                        <!-- <x-button btype="submit" btntext="Submit" classname="btn btn-primary" name="sbtsubmit"/> -->
                                       <!--  <x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnSignUpReset" /> -->
                                        <x-button btype="submit" btntext="Sign Up" classname="btn btn-primary w-100" bid="btnsubmit" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endif

@if ($isAction == 'wish_forget')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-hidden="true">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>FORGOT PASSWORD</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmForgotPwd" id="frmForgotPwd" action="javascript:void(0)">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox"><span>{{$var_msg}}</span></div>
                                                    @if (Session::has('failedfp'))
                                                    <x-message :attr="[
                        										'classname' => 'frmerror frmerror_shw', 
                        										'message' => Session::get('failedfp')]" />
                                                    @endif
                                                    @if (Session::has('successfp'))
                                                    <x-message :attr="[
                        										'classname' => 'frmsuccess pb-3', 
                        										'message' => Session::get('successfp')]" />
                                                    @endif

                                                    <div class="form-group">
                                                        <label for="lname" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" autofocus="">
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Submit" classname="btn btn-primary" bid="btnsubmit" />
                                            </div>
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
</div>
@endif

<script src="{{ asset('public/js/core/signin_popup_validate.js?var=6') }}"></script>
