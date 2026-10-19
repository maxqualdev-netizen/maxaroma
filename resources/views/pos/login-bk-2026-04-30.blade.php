@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-labelledby="login-title">
    <div class="container pt-4 pb-4 pt-md-5 pb-md-5">
		<div class="login" role="region" aria-labelledby="login-title">
			<!--<div class="myact_hd">
				<svg class="sv_logout" aria-hidden="true" role="img"><use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use></svg>
				<h2>Sign In</h2>
			</div>-->
			<form action="" method="post" id="formLogin" aria-labelledby="login-title">
				{{ csrf_field() }}
				<input type="hidden" name="fromcheckout" value="{{$FromCheckout}}"/>
				<input type="hidden" name="action" value="signin"/>
				<div class="row">
					<div class="col-md-6 offset-md-3">
						<h2 class="posmain-hd mb-4" id="login-title">POS Store Login</h2>
						@if (count($errors) > 0)
							@if($errors->has('login'))
								<x-message :attr="[ 'classname' => 'frmerror frmerror_shw mt-0 mb-3', 'message' => $errors->first('login')]" />
							@endif
						@endif
						@if ($errors->has('Failed'))
							<x-message :attr="[ 'classname' => 'frmerror frmerror_shw mt-0 mb-3', 'message' => $errors->first('Failed')]" />
						@endif
						<div class="field required mb-3">
							<label for="email_address">Email Address</label>
							<input type="email" id="email_address" name="email" placeholder="Email Address" class="form-control" aria-required="true" aria-label="Email Address" autocomplete="username">
							<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email']" />
							@if ($errors->has('email'))
								<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email') ]" />
							@endif
						</div>
						<div class="field required mb-3">
							<label for="password">Password</label>
							<input type="password" id="password" name="password" placeholder="Password" class="form-control" aria-required="true" aria-label="Password" autocomplete="current-password">
							<small class="mt-2 d-block" id="password-instructions">Don't use space when entering your password.</small>
							<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_password' ]" />
							@if ($errors->has('password'))
								<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('password')]" />
							@endif
						</div>
						{{-- <div class="form-group">
							<div class="field required">
									@include('googlecaptcha')
							</div>
						</div> --}}
						<div class="d-flex justify-content-between align-items-center mb-3" role="group" aria-label="Login Options">
							<label class="checkbox-label mb-0" for="remember_me">
								<div class="chebox">
									<input type="checkbox" id="remember_me" name="remember_me" aria-checked="false" aria-label="Remember Me"><span class="checkmark"></span>
								</div>Remember Me
							</label>
							{{-- <a class="btn_link" href="{{url('/store/forgot-password.html')}}" aria-label="Forgot Password">Forgot Password?</a> --}}
						</div>
						<!-- <x-button btype="submit" btntext="Login" classname="btn btn-primary d-block" name="sbtsubmit" bid="btnLogin"/> -->
						<x-button link="javascript:void(0);" btntext="Sign In" classname="btn btn-primary d-block" bid="btnLogin" aria-label="Sign In"/>
					</div>
				</div>
			</form>
		</div>
	</div>
</main>
@endsection