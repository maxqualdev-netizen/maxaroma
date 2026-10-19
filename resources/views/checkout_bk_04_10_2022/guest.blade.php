<div class="mb-0 mb-md-4" id="guest2" style="display:none;">
	<h3 class="cart-hd d-none d-md-block">
		<span>Email Address</span> 
		<a href="javascript:void(0);" class="float-right edit-btn btn btn-primary guestback" data-index="1">Edit</a>
	</h3>
	<div class="pt-3">
		<p class="mb-0" id="gemail"></p>
	</div>
</div>
<div class="col-lg-12" id="guest1">
	<div class="row">
		<div class="col-12">
			<div class="row" id="guesthead">
				<div class="col-6 chkhead chkactive" id="returning">Returning Member</div>
				<div class="col-6 chkhead" id="guest">Guest Customer</div>
			</div>
			<div class="row">
				<div class="col-12" id="divreturning">
					<form action="{{config('global.SITE_URL')}}login.html" method="post" id="formLogin">
						{{ csrf_field() }}
						<input type="hidden" name="action" value="signin"/>
						<input type="hidden" id="page" name="page" value="{{$PageMethod}}"/>
						<div class="row">
							<div class="col-md-12">
								<div class="eheight row">
									@if (count($errors) > 0)
										@if($errors->has('login'))
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw col-12 pt-4', 
												'message' => $errors->first('login')]"
											/>
										@endif	
									@endif
									@if ($errors->has('Failed'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw frmerror_shw col-12 pt-4', 
													'message' => $errors->first('Failed')]"
										/>
									@endif
									<div class="field required mt-4 col-md-8">
										<label for="email_address">Email Address</label>
										<input type="email" id="email" name="email" placeholder="Email Address" class="form-control">
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_email']"
										/>
										@if ($errors->has('email'))
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('email')
												]"
											/>
										@endif
									</div>
									<div class="field required mt-4 col-md-8">
										<label for="password">Password</label>
										<input type="password" id="password" name="password" placeholder="Password" class="form-control">
										<small class="pt-2 d-block">Don't use space when entering your password.</small>
										<!-- <div id="error_password" class="frmerror"></div> -->
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_password'
											]"
										/>
										@if ($errors->has('password'))
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('password')]"
											/>
										@endif
									</div>	
									<div class="pt-4 col-md-8">
										<a class="btn_link float-right" href="{{url('/forgot-password.html')}}">Forgot Password?</a>
										<label class="checkbox-label">
											<div class="chebox">
												<input type="checkbox"><span class="checkmark"></span>
											</div>Remember Me
										</label>
									</div>
								</div>
							</div>	
							<div class="col-md-8">	
								<div class="pt-4">
									<!-- <x-button btype="submit" btntext="Login" classname="btn btn-primary d-block" name="sbtsubmit" bid="btnLogin"/> -->
									<x-button link="javascript:void(0);" btntext="Sign in" classname="btn btn-primary d-block" bid="btnLogin"/>
								</div>
								<div class="dividerr-or"><span>or</span></div>
								<div class="row row10">
									@php 
										$fsvg = [
											'title' => 'Login with Facebook',
											'symbol' => 'sv-facebook',
											'height' => '22',
											'width' => '22',
											'classname' => 'sv-facebook vam',
										];
										$gsvg = [
											'title' => 'Login with Gmail',
											'symbol' => 'sv-gmail-0',
											'height' => '22',
											'width' => '22',
											'classname' => 'sv-gmail-0 vam mr-1',
										];
									@endphp
									<div class="col-md-6 pb-3">
										<x-button link="{{url('/redirect')}}" btntext='Facebook' classname="btn btn-primary btn-facebook d-block  no-hw-svg" :bsvg="$fsvg"/>
									</div>
									<div class="col-md-6 pb-3">
										<x-button link="{{url('/redirectgoogle')}}" btntext='Gmail' classname="btn btn-primary btn-gmail d-block  no-hw-svg" :bsvg="$gsvg"/>
									</div>
								</div>
								</div>
							</div>
						</form>
				</div>
				<div class="col-12" id="divguest" style="display:none;">
					<form action="{{config('global.SITE_URL')}}login.html" method="post" id="formguest">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12 pt-4">
								<div class="eheight row">
									<div class="field required mt-4 col-md-8">
										<label for="email_address">Email Address</label>
										<input type="email" id="guest_email" name="guest_email" placeholder="Email Address" class="form-control">
										<x-message :attr="[
											'classname' => 'frmerror', 
											'message' => '',
											'mid' => 'error_guest_email']"
										/>
										@if ($errors->has('email'))
											<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('guest_email')
												]"
											/>
										@endif
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="mt-3 pt-1 mb-3">
						<a class="max_continuesp_btn btn btn-primary" id="btnguest" href="javascript:void(0)">Continue</a>
						<a class="max_continuesp_btn btn btn-primary" id="btnasguest" href="javascript:void(0)" style="display:none;">Continue as Guest</a>
					</div>	
				</div>
			</div>
		</div>		
	</div>	
</div>
