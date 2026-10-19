@extends('layouts.app')
@section('content')
<main>   
	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1 col-md-12">
				<div class="login pb-3 pt-3">
					<!--<div class="myact_hd">
						<svg class="sv_logout" aria-hidden="true" role="img"><use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use></svg>
						<h2>Sign In</h2>
					</div>-->
					<form action="" method="post" id="formLogin">
						
						{{ csrf_field() }}
						<input type="hidden" name="fromcheckout" value="{{$FromCheckout}}"/>
						<input type="hidden" name="action" value="signin"/>
						<div class="row">
							<div class="col-md-6 pt-4">
								<div class="eheight">
									<h3>Sign in</h3>
										@if (count($errors) > 0)
											@if($errors->has('login'))
												<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('login')]"
												/>
											@endif	
										@endif
										@if ($errors->has('Failed'))
											<x-message :attr="[
														'classname' => 'frmerror frmerror_shw', 
														'message' => $errors->first('Failed')]"
											/>
										@endif
										<div class="field required mt-4">
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
										<div class="field required mt-4">
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
										<div class="form-group">                                                    
											<div class="field required">
												   @include('googlecaptcha')
											</div>                                                     
										</div>
										<div class="pt-4">
											<a class="btn_link float-right" href="{{url('/forgot-password.html')}}">Forgot Password?</a>
											<label class="checkbox-label">
												<div class="chebox">
													<input type="checkbox"><span class="checkmark"></span>
												</div>Remember Me
											</label>
										</div>
									</div>
									<div class="pt-4">
										<!-- <x-button btype="submit" btntext="Login" classname="btn btn-primary d-block" name="sbtsubmit" bid="btnLogin"/> -->
										<x-button link="javascript:void(0);" btntext="Sign In" classname="btn btn-primary d-block" bid="btnLogin"/>
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
								<div class="col-md-6 pt-4">
									<div class="eheight creating_text">
										<h3>Create an account</h3>
										<ul class="dots">
											<li>Giveaway Alerts</li>
											<li>Easily redeem rewards</li>
											<li>Brand Drops</li>
											<li>Presale and exclusive access</li>
											<li>Easily Presale track orders and view order history</li>
										</ul>
									</div>
									<div class="pt-4">
										<x-button link="{{config('global.SITE_URL').'register.html'}}" btntext="Sign up & Get 150 Points" classname="btn btn-secondary d-block" />
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
