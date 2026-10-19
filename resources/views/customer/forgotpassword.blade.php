@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Forgot Password Main Content">
    <section class="smallcon" aria-labelledby="forgot-password-heading">
        <div class="container">
            <!--<div class="myact_hd">
                <svg class="sv-forgotpass" aria-hidden="true" role="img">
                    <use href="#sv-forgotpass" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-forgotpass"></use>
                </svg>
                <h2>Forgot Password</h2>
            </div>-->
            <div class="row">
                <div class="col-12">
                    <form action="" method="post" id="formForgotPassword" aria-labelledby="forgot-password-heading">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="forgot_password" />
                        <div class="row pt-4 pb-4">
                            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12 col-xs-12">
                                <h2 id="forgot-password-heading" class="sr-only">Forgot Password</h2>
                                <div class="row">
                                    <div class="pb-3 col-12 text-right fw500"><span class="errmsg" aria-hidden="true">*</span>Required Fields</div>
                                    <div class="col-md-12">
                                        <div class="field required">
                                            <label for="email">Email Address <span class="sr-only">(required)</span></label>
                                            <input type="email" id="email" name="email" placeholder="Email Address" class="form-control" aria-required="true" aria-describedby="error_email">
                                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email', 'role' => 'alert' ]" />
                                            @if ($errors->has('NotExistEmail'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('NotExistEmail'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                            @endif
                                            @if ($errors->has('email'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                            @endif
                                            @if (Session::has('success'))
                                                <x-message :attr="[ 'classname' => 'frmsuccess', 'message' => Session::get('success'), 'role' => 'status', 'aria-live' => 'polite' ]" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        @include('googlecaptcha')
                                    </div>
                                    <div class="col-12 text-right pt-3" role="group" aria-label="Form Actions">
                                        <a href="#" class="btn btn1-link mt-2 pt-1 float-left pl-0 back-link" aria-label="Go back">
                                            <svg class="sv-back vam" aria-hidden="true" role="img" width="24" height="24">
                                                <use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use>
                                            </svg>Back
                                        </a>
                                        <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnForgotPassword" aria-label="Submit Forgot Password Form" />
                                    </div>
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