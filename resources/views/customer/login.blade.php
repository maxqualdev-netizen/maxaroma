@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Login Main Content">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">
                <div class="login pb-3 pt-3">
                    <!--<div class="myact_hd">
                        <svg class="sv_logout" aria-hidden="true" role="img">
                            <use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use>
                        </svg>
                        <h2>Sign In</h2>
                    </div>-->
                    <form action="" method="post" id="formLogin" aria-labelledby="login-heading">
                        {{ csrf_field() }}
                        <input type="hidden" name="fromcheckout" value="{{$FromCheckout}}" />
                        <input type="hidden" name="action" value="signin" />
                        <div class="row">
                            <div class="col-md-6 pt-4">
                                <div class="eheight">
                                    <h2 id="login-heading" class="h3">Sign in</h2>
                                    @if (count($errors) > 0)
                                        @if($errors->has('login'))
                                            <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('login'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                        @endif
                                    @endif
                                    @if ($errors->has('Failed'))
                                        <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('Failed'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                    @endif
                                    <div class="field required mt-4">
                                        <label for="email">Email Address <span class="sr-only">(required)</span></label>
                                        <input type="email" id="email" name="email" placeholder="Email Address" class="form-control" aria-required="true" aria-describedby="error_email">
                                        <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email', 'role' => 'alert' ]" />
                                        @if ($errors->has('email'))
                                            <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                        @endif
                                    </div>
                                    <div class="field required mt-4">
                                        <label for="password">Password <span class="sr-only">(required)</span></label>
                                        <input type="password" id="password" name="password" placeholder="Password" class="form-control" aria-required="true" aria-describedby="error_password passwordHelp">
                                        <small id="passwordHelp" class="pt-2 d-block">Don't use space when entering your password.</small>
                                        <!-- <div id="error_password" class="frmerror"></div> -->
                                        <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_password', 'role' => 'alert' ]" />
                                        @if ($errors->has('password'))
                                            <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('password'), 'role' => 'alert', 'aria-live' => 'assertive' ]" />
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="field required">
                                            @include('googlecaptcha')
                                        </div>
                                    </div>
                                    <div class="pt-4" role="group" aria-label="Login Actions">
                                        <a class="btn_link float-right" href="{{url('/forgot-password.html')}}" aria-label="Forgot Password">Forgot Password?</a>
                                        <label class="checkbox-label" for="remember_me">
                                            <div class="chebox">
                                                <input type="checkbox" id="remember_me" name="remember_me" aria-checked="false"><span class="checkmark"></span>
                                            </div>Remember Me
                                        </label>
                                    </div>
                                </div>
                                <div class="pt-4" role="group" aria-label="Sign In Button">
                                    <!-- <x-button btype="submit" btntext="Login" classname="btn btn-primary d-block" name="sbtsubmit" bid="btnLogin"/> -->
                                    <x-button link="javascript:void(0);" btntext="Sign In" classname="btn btn-primary d-block" bid="btnLogin" aria-label="Sign In" />
                                </div>
                                <div class="dividerr-or" aria-hidden="true"><span>or</span></div>
                                <div class="row row10" role="group" aria-label="Social Login Options">
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
                                        <x-button link="{{url('/redirect')}}" btntext='Facebook' classname="btn btn-primary btn-facebook d-block  no-hw-svg" :bsvg="$fsvg" aria-label="Login with Facebook" />
                                    </div>
                                    <div class="col-md-6 pb-3">
                                        <x-button link="{{url('/redirectgoogle')}}" btntext='Gmail' classname="btn btn-primary btn-gmail d-block  no-hw-svg" :bsvg="$gsvg" aria-label="Login with Gmail" />
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
                                <div class="pt-4" role="group" aria-label="Sign Up Button">
                                    <x-button link="{{config('global.SITE_URL').'register.html'}}" btntext="Sign up & Get 150 Points" classname="btn btn-secondary d-block" aria-label="Sign up and get 150 points" />
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