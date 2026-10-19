@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="trackOrderTitle">
    <!-- Content Part Start Here -->
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="#" aria-label="Home">Home</a>
            <span class="active" aria-current="page">Track Your Order</span>
        </div>
    </section>
    <div class="container">
        <div class="static-inner">
            <div class="row">
                <div class="col-12">
                    <div class="mainhd">
                        <h2 class="h1" id="trackOrderTitle">Track Your Order</h2>
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center">Please enter your order number below to track your order. You can also sign in to track your order, see your complete order history and much more.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12 pt-4">
                    <div class="pb-3 col-12 text-right fw500"><span class="errmsg">*</span>Required Fields</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="spg-panel" role="region" aria-labelledby="checkOrderStatusTitle">
                                <h2 id="checkOrderStatusTitle" class="h3">Check Order Status</h2>
                                <div class="spg-body loginbody">
                                    <form method="post" id="formTrackOrder" action="" role="form" aria-labelledby="checkOrderStatusTitle">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="action" value="submit" />
                                        <input type="hidden" name="optionname" value="check_order_zip" />
                                        @if (count($errors) > 0)
                                        @if($errors->has('incorrect'))
                                        <x-message :attr="[ 'classname' => 'frmerror frmerror_shw pb-3', 'message' => $errors->first('incorrect')]" />
                                        @endif
                                        @endif
                                        <div class="eheight">
                                            <div class="form-group">
                                                <label for="bill_email" class="col-form-label">Email Address<span class="errmsg">*</span></label>
                                                <!-- <input id="email_track" type="text" placeholder="Email Address" class="form-control" autofocus=""> -->
                                                <input type="email" id="bill_email" name="bill_email" placeholder="Email Address" class="form-control" aria-required="true">
                                                <x-message :attr="[ 'classname' => 'frmerror ', 'message' => '', 'mid' => 'error_bill_email']" />
                                                @if ($errors->has('bill_email'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('bill_email') ]" />
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="orders_id" class="col-form-label">Order Number<span class="errmsg">*</span></label>
                                                <!-- <input id="ordernumber" type="text" placeholder="Order Number" class="form-control"> -->
                                                <input type="text" id="orders_id" name="orders_id" placeholder="Order Number" class="form-control" aria-required="true">
                                                <x-message :attr="[ 'classname' => 'frmerror ', 'message' => '', 'mid' => 'error_orders_id' ]" />
                                                @if ($errors->has('orders_id'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('orders_id')]" />
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @include('googlecaptcha')
                                            </div>
                                            <div class="form-actions clearfix pt-0">
                                                <div class="float-right">
                                                    <!-- <a href="#" class="btn btn-primary">Submit</a> -->
                                                    <x-button link="javascript:void(0);" btntext="Track" classname="btn btn-primary" bid="btnTrackOrder" aria-label="Track Order" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="spg-panel" role="region" aria-labelledby="memberLoginTitle">
                                <h2 id="memberLoginTitle" class="h3">Member Login</h2>
                                <div class="spg-body loginbody">
                                    <form action="{{ url('/login.html') }}" method="post" id="formLogin" role="form" aria-labelledby="memberLoginTitle">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="action" value="signin" />
                                        @if (count($errors) > 0)
                                        @if($errors->has('login'))
                                        <x-message :attr="[ 'classname' => 'frmerror frmerror_shw pb-3', 'message' => $errors->first('login')]" />
                                        @endif
                                        @endif
                                        @if ($errors->has('Failed'))
                                        <x-message :attr="[ 'classname' => 'frmerror frmerror_shw pb-3', 'message' => $errors->first('Failed')]" />
                                        @endif
                                        <div class="eheight">
                                            <div class="form-group">
                                                <label for="email" class="col-form-label d-none d-md-block pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                <!-- <input type="text" class="form-control" placeholder="Email Address"> -->
                                                <input type="email" id="email" name="email" placeholder="Email Address" class="form-control" aria-required="true">
                                                <x-message :attr="[ 'classname' => 'frmerror ', 'message' => '', 'mid' => 'error_email']" />
                                                @if ($errors->has('email'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email') ]" />
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="col-form-label d-none d-md-block pt-0 text-left">Password<span class="errmsg">*</span></label>
                                                <!-- <input type="password" class="form-control" placeholder="Password"> -->
                                                <input type="password" id="password" name="password" placeholder="Password" class="form-control" aria-required="true">
                                                <x-message :attr="[ 'classname' => 'frmerror ', 'message' => '', 'mid' => 'error_password' ]" />
                                                @if ($errors->has('password'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('password')]" />
                                                @endif
                                            </div>
                                            <div class="form-group" id="track_login_captcha">
                                                @include('googlecaptcha')
                                            </div>
                                            <div class="form-actions clearfix pt-0">
                                                <div class="float-right">
                                                    <!-- <a href="#" class="btn btn-primary">Log In</a> -->
                                                    <x-button link="javascript:void(0);" btntext="Log in" classname="btn btn-primary" bid="btnLogin" aria-label="Log In" />
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
    </div>
    <!-- Content Part End Here -->
</main>
@endsection