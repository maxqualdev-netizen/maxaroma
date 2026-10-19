@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Reset Password Main Content">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">
                <div class="login pb-3 pt-3">
                    <form action="" method="post" id="formreset" aria-labelledby="reset-password-heading">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="reset" />
                        <input type="hidden" name="code" value="{{$code}}" />
                        <div class="row">
                            <div class="col-md-6 pt-4">
                                <h2 id="reset-password-heading" class="sr-only">Reset Password</h2>
                                <h3 aria-hidden="true">Reset Password</h3>
                                <div class="row" role="alert" aria-live="polite">
                                    <div class="col-md-12 mt-4">
                                        @if (Session::has('failed'))
                                        <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => Session::get('failed'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                        @endif
                                        @if (Session::has('success'))
                                        <x-message :attr="[ 'classname' => 'frmsuccess frmerror_shw', 'message' => Session::get('success'), 'role' => 'status', 'aria-live' => 'polite' ]" />
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div class="field required">
                                            <label for="password">Password <span class="sr-only">(required)</span></label>
                                            <input type="password" id="password" name="password" placeholder="Password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" aria-required="true" aria-describedby="error_password passwordHelp">
                                            <small id="passwordHelp" class="pt-1 d-block">Don't use space when entering your password.</small>
                                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_password', 'role' => 'alert' ]" />
                                            @if ($errors->has('password'))
                                            <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('password'), 'role' => 'alert' ]" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div class="field required">
                                            <label for="confirm_Password">Confirm Password <span class="sr-only">(required)</span></label>
                                            <input type="password" id="confirm_Password" name="confirm_Password" placeholder="Confirm Password" class="form-control {{ $errors->has('confirm_Password') ? ' is-invalid' : '' }}" aria-required="true" aria-describedby="error_confirm_Password">
                                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_confirm_Password', 'role' => 'alert' ]" />
                                            @if ($errors->has('confirm_Password'))
                                            <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('confirm_Password'), 'role' => 'alert' ]" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div role="group" aria-label="Form Actions">
                                            <x-button link="javascript:void(0);" btntext="Reset Password" classname="btn btn-primary d-block" bid="btnReset" aria-label="Reset Password" />
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
</main>
@endsection