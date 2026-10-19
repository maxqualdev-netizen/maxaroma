<div id="guest1" role="region" aria-label="Guest Checkout Section">
    <div class="row">
        <div class="col-12" id="divguest">
            <form action="{{config('global.SITE_URL')}}login.html" class="mb-0" method="post" id="formguest" aria-labelledby="guest-checkout-heading">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12">
                        <h2 id="guest-checkout-heading" class="sr-only">Guest Checkout</h2>
                        <div class="form-group required">
                            <label for="guest_email">Email Address</label>
                            <input type="email" name="guest_email" id="guest_email" class="form-control" required="" aria-required="true" aria-label="Email Address">
                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_guest_email']" />
                            @if ($errors->has('email'))
                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('guest_email') ]" />
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="checkbox-label f12 lheight-18 mb-0" for="chknews1" aria-label="Subscribe to Max Aroma news and offers">
                            <div class="chebox">
                                <input type="checkbox" id="chknews1" name="chknews1" checked="" aria-checked="true"><span class="checkmark"></span>
                            </div>
                            Keep me up to date with Max Aroma news, promotions, and exclusive offers by email and SMS. I
                            can update my preferences at any time by contacting Max Aroma or using unsubscribe links.
                        </label>
                    </div>
                    <div class="col-12">
                        <div class="chekout-main-btn" role="region" aria-label="Continue as Guest Button Section">
                            <div class="mt-md-3 pt-md-1 mb-md-3">
                                <a class="max_continuesp_btn btn btn-primary w-100" id="btnguest" href="javascript:void(0)" role="button" aria-label="Continue">Continue</a>
                                <a class="max_continuesp_btn btn btn-primary w-100" id="btnasguest" href="javascript:void(0)" style="display:none;" role="button" aria-label="Continue as Guest">Continue as Guest</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-md-none">
                        <div class="mobile-shadow mb-0" aria-hidden="true">&nbsp;</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>