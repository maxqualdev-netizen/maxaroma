<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>PLEASE SIGN IN TO MAXAROMA</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmLogin" id="frmLogin" action="javascript:void(0)">
                            <input type="hidden" name="check_value" value="1" id="check_value">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <h3>Member Login</h3>
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    @if (Session::has('failed'))
                                                    <x-message :attr="[
                        										'classname' => 'frmerror frmerror_shw', 
                        										'message' => Session::get('failed')]" />
                                                    @endif
                                                    @if (Session::has('success'))
                                                    <x-message :attr="[
                        										'classname' => 'frmsuccess', 
                        										'message' => Session::get('success')]" />
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" autofocus />
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_password']" />
                                                        <small class="pt-2 d-block">Don't use space when entering your password.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <p class="float-right w-100"><a class="btn_link pt-0 pb-0 mb-0" href="javascript:void(0);" onclick="ShowForgetPassword();">Forgot Password?</a></p>
                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Join" classname="btn btn-primary" bid="btnsubmit" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel spg-panel1">
                                            <h3>New Member</h3>
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    <p>Creating an account is simple and fast. Just click the button below. Once it's been set up, you can take advantage of all the benefits of MaxAroma.com</p>
                                                </div>
                                                <div class=""><a href="{{$SITE_URL}}register.html" class="btn btn-secondary">Sign up</a></div>
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
<script src="{{ asset('public/js/core/login_validate.js') }}"></script>