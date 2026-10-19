@extends('layouts.app')
@section('content')
<style>
	.pin-input {
		width: 60px;
		height: 60px;
		font-size: 24px;
		text-align: center;
		margin: 0 10px;
	}
	@media (max-width: 576px) {
		.pin-input {
			width: 50px;
			height: 50px;
			font-size: 20px;
		}
	}
</style>
<main class="pos-page" role="main" aria-labelledby="login-title">
    <div class="container pt-4 pb-4 pt-md-5 pb-md-5">
		<div class="login" role="region" aria-labelledby="login-title">
			<!--<div class="myact_hd">
				<svg class="sv_logout" aria-hidden="true" role="img"><use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use></svg>
				<h2>Sign In</h2>
			</div>-->

			<form action="" method="post" id="formLoginPin" aria-labelledby="login-title">
				{{ csrf_field() }}
				<input type="hidden" name="fromcheckout" value="{{$FromCheckout}}"/>
				<!-- <input type="hidden" name="action" value="signin"/> -->
				<input type="hidden" name="action" value="signin_with_pin"/>
				<div class="row justify-content-center">
					<!-- <div class="col-md-6 offset-md-3"> -->
					<div class="col-10 col-sm-8 col-md-5 col-lg-3 pb-4">
						<h2 class="posmain-hd mb-3 text-center" id="login-title">POS Store Login</h2>
						@if (count($errors) > 0)
							@if($errors->has('login'))
								<x-message :attr="[ 'classname' => 'frmerror frmerror_shw mt-0 mb-3', 'message' => $errors->first('login')]" />
							@endif
						@endif
						@if ($errors->has('Failed'))
							<x-message :attr="[ 'classname' => 'frmerror frmerror_shw mt-0 mb-3', 'message' => $errors->first('Failed')]" />
						@endif
						@if(Session::has('InacativeUser'))
							<x-message :attr="[ 'classname' => 'frmerror frmerror_shw mt-0 mb-3', 'message' => Session::get('InacativeUser')]" />
						@endif
						<div class="pt-4 text-center">
							<label for="store_pin" class="form-label text-start w-100 pb-3">Enter Your PIN</label>
							<div class="d-flex justify-content-center gap-2 mb-2">
								<input type="number" maxlength="1" class="form-control pin-input text-center" pattern="[0-9]*" required>
								<input type="number" maxlength="1" class="form-control pin-input text-center" pattern="[0-9]*" required>
								<input type="number" maxlength="1" class="form-control pin-input text-center" pattern="[0-9]*" required>
								<input type="number" maxlength="1" class="form-control pin-input text-center" pattern="[0-9]*" required>
							</div>
						</div>
						@if ($errors->has('store_pin'))
							<x-message :attr="[ 'classname' => 'frmerror frmerror_shw text-center w-100 mb-2 pt-2', 'message' => $errors->first('store_pin') ]" />
						@endif
						<div class="frmerror frmerror_shw mb-2 text-start w-100" role="alert" style="" id="pin_error"></div>
						<input type="hidden" id="store_pin" name="store_pin" placeholder="Enter Your PIN" class="form-control" aria-required="true" aria-label="Store PIN" autocomplete="off">
						{{--
						<div class="field required mb-3">
							<label for="store_pin">Enter Your Store PIN</label>
							<input type="number" id="store_pin" name="store_pin" placeholder="Enter Your PIN" class="form-control" aria-required="true" aria-label="Store PIN" autocomplete="off">
							<!-- <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email']" /> -->
							@if ($errors->has('store_pin'))
								<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('store_pin') ]" />
							@endif
						</div>
						--}}
						{{--
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
						--}}
						{{-- <div class="form-group">
							<div class="field required">
									@include('googlecaptcha')
							</div>
						</div> --}}
						{{--
						<div class="d-flex justify-content-between align-items-center mb-3" role="group" aria-label="Login Options">
							<label class="checkbox-label mb-0" for="remember_me">
								<div class="chebox">
									<input type="checkbox" id="remember_me" name="remember_me" aria-checked="false" aria-label="Remember Me"><span class="checkmark"></span>
								</div>Remember Me
							</label>
						</div>
						<!-- <x-button btype="submit" btntext="Login" classname="btn btn-primary d-block" name="sbtsubmit" bid="btnLogin"/> -->
						<x-button link="javascript:void(0);" btntext="Sign In" classname="btn btn-primary d-block" bid="btnLoginPin" aria-label="Sign In"/>
						--}}
					</div>
				</div>
			</form>
		</div>
	</div>
</main>
<script>
$(document).ready(function () {
    const $inputs = $('.pin-input');
	let submitted = false;

	let isReady = false;
	// $(window).on('load', function () {
	// 	isReady = true;
	// 	$('.pin-input').prop('disabled', false);
	// 	$('.pin-input').first().focus();
	// });
	$('.pin-input').first().focus();
    // Allow only digits + auto move
    $inputs.on('input', function () {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);

        if (value !== '') {
            $(this).next('.pin-input').focus();
        }
    });

    // Backspace handling
    $inputs.on('keydown', function (e) {
		// if (!isReady) {
		// 	e.preventDefault();
		// }
        if (e.key === 'Backspace') {
            if ($(this).val() === '') {
                $(this).prev('.pin-input').focus().val('');
            }
        }
    });

    // Paste handling
    $inputs.on('paste', function (e) {
        e.preventDefault();
        let pasteData = (e.originalEvent.clipboardData.getData('text') || '')
                        .replace(/[^0-9]/g, '')
                        .split('');

        $inputs.each(function (i) {
            $(this).val(pasteData[i] || '');
        });

        let focusIndex = pasteData.length >= $inputs.length
                        ? $inputs.length - 1
                        : pasteData.length;

        $inputs.eq(focusIndex).focus();
    });

	$('.pin-input').on('input', function () {

		let value = $(this).val().replace(/[^0-9]/g, '');
		// If user entered multiple digits (mobile/autofill case)
		if (value.length > 1) {
			let digits = value.split('');
			let index = $('.pin-input').index(this);

			$('.pin-input').each(function (i) {
				if (digits[i - index]) {
					$(this).val(digits[i - index]);
				}
			});

			// focus last filled box
			let nextIndex = Math.min(index + digits.length, $('.pin-input').length - 1);
			$('.pin-input').eq(nextIndex).focus();

		} else {
			$(this).val(value);

			// move to next
			if (value !== '') {
				$(this).next('.pin-input').focus();
			}
		}

		if (submitted) return;

		let allFilled = true;

		$('.pin-input').each(function () {
			if ($(this).val() === '') {
				allFilled = false;
				return false;
			}
		});
		if(allFilled)
		{
			let pin = '';
			$inputs.each(function () {
				pin += $.trim($(this).val());
			});
			submitted = true;
			$("#store_pin").val(pin);
			$("#formLoginPin").submit();
		}
	});

    // Submit
    $('#btnLoginPin').on('click', function (e) {
        let pin = '';
        $inputs.each(function () {
            pin += $.trim($(this).val());
        });
		$('#pin_error').text('');
		if(pin === '')
		{
			 $('#pin_error').text('Please enter your PIN.').show();
			// focus first empty input
			$('.pin-input').filter(function () {
				return $(this).val() === '';
			}).first().focus();
			return;
		}

		$("#store_pin").val(pin);
		$("#formLoginPin").submit();
    });

	$('.pin-input').on('keypress', function (e) {
		if ($(this).val().length >= 1) {
			e.preventDefault();
		}
	});
});
</script>
@endsection