@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-labelledby="forgot-password-title">
	<div class="container pt-md-5 pb-md-5 pt-4 pb-3">
		<!--<div class="myact_hd">
			<svg class="sv-forgotpass" aria-hidden="true" role="img">
				<use href="#sv-forgotpass" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-forgotpass"></use>
			</svg>
			<h2>Forgot Password</h2>
		</div>-->
		<form action="" method="post" id="formForgotPassword" aria-labelledby="forgot-password-title">
			{{ csrf_field() }}
			<input type="hidden" name="action" value="forgot_password" />
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h2 id="forgot-password-title" class="posmain-hd">Forgot Password</h2>
						<div class="fw500"><span class="errmsg">*</span>Required Fields</div>
					</div>
					<div class="row mb-3">
						<div class="col-12">
							<div class="field required">
								<label for="email" class="form-label">Email Address</label>
								<input type="email" id="email" name="email" placeholder="Email Address" class="form-control" aria-required="true" aria-label="Email Address" autocomplete="username">
								<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email']" />
								@if ($errors->has('NotExistEmail'))
									<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('NotExistEmail')]" />
								@endif
								@if ($errors->has('email'))
									<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email')]" />
								@endif
								@if (Session::has('success'))
									<x-message :attr="[ 'classname' => 'frmsuccess', 'message' => Session::get('success')]" />
								@endif
							</div>
						</div>
						{{-- <div class="col-12">
							@include('googlecaptcha')
						</div>--}}
					</div>
					<div class="d-flex justify-content-between align-items-center" role="group" aria-label="Forgot Password Actions">
						<a href="{{ url('store/login.html') }}" class="back-link" aria-label="Back to Login">
							<svg class="sv-back vam" aria-hidden="true" role="img" width="16" height="16">
								<use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use>
							</svg>Back
						</a>
						<!-- <x-button btype="submit" btntext="Submit" name="btnsubmit" classname="btn btn-primary d-block"/> -->
						<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnForgotPassword" aria-label="Submit Forgot Password" />
					</div>
				</div>
			</div>
		</form>
	</div>
</main>
@endsection