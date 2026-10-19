<div class="vertical-alignment-helper" role="presentation">
    <div class="modal-dialog modal-sm vertical-align-center" role="dialog" aria-modal="true" aria-labelledby="nicheFragranceModalTitle">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close popup">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd text-center pt-3" id="nicheFragranceModalTitle">
                        <img src="https://www.maxaroma.com/images/join-peraroma-logo.png" alt="MaxAroma Join Peraroma Logo">
                    </div>
                    <div class="modal-space">
                        <form method="post" name="FormNicheFragrances" id="FormNicheFragrances" action="javascript:void(0)" aria-labelledby="nicheFragranceModalTitle">
                            <input type="hidden" name="isAction" value="AddNicheFragrances">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="row">
                                                    <div class="col-12 col-md-9">
                                                        <div class="eheight">
                                                            @if (Session::has('failedfm'))
                                                            <x-message :attr="[
                                                                'classname' => 'frmerror frmerror_shw', 
                                                                'message' => Session::get('failedfm'),
                                                                'role' => 'alert',
                                                                'aria-live' => 'assertive'
                                                            ]" />
                                                            @endif
                                                            @if (Session::has('successfm'))
                                                            <x-message :attr="[
                                                                'classname' => 'frmsuccess', 
                                                                'message' => Session::get('successfm'),
                                                                'role' => 'status',
                                                                'aria-live' => 'polite'
                                                            ]" />
                                                            @endif
                                                            <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox" role="alert" aria-live="assertive"><span></span></div>
                                                            <div class="form-group">
                                                                <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg" aria-hidden="true">*</span></label>
                                                                <input type="email" class="form-control" placeholder="Email Address" id="email" name="email" aria-required="true" autocomplete="email">
                                                                <x-message :attr="[
                                                                    'classname' => 'frmerror', 
                                                                    'message' => '',
                                                                    'mid' => 'error_email',
                                                                    'role' => 'alert',
                                                                    'aria-live' => 'assertive'
                                                                ]" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="first_name" class="col-form-label pt-0 text-left">First Name<span class="errmsg" aria-hidden="true">*</span></label>
                                                                <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" aria-required="true" autocomplete="given-name">
                                                                <x-message :attr="[
                                                                    'classname' => 'frmerror', 
                                                                    'message' => '',
                                                                    'mid' => 'error_first_name',
                                                                    'role' => 'alert',
                                                                    'aria-live' => 'assertive'
                                                                ]" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="last_name" class="col-form-label pt-0 text-left">Last Name<span class="errmsg" aria-hidden="true">*</span></label>
                                                                <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" aria-required="true" autocomplete="family-name">
                                                                <x-message :attr="[
                                                                    'classname' => 'frmerror', 
                                                                    'message' => '',
                                                                    'mid' => 'error_last_name',
                                                                    'role' => 'alert',
                                                                    'aria-live' => 'assertive'
                                                                ]" />
                                                            </div>
                                                            <div class="form-group" role="radiogroup" aria-labelledby="gender">
                                                                <label for="gender" class="col-form-label pt-0 text-left">Gender<span class="errmsg">*</span></label>&nbsp;&nbsp;
                                                                <input type="radio" name="gender" id="gender" value="Male" checked="">&nbsp;Men &nbsp;&nbsp;
                                                                <input type="radio" name="gender" id="gender" value="Female" checked="">&nbsp;Women
                                                                <x-message :attr="[
                                                                    'classname' => 'frmerror', 
                                                                    'message' => '',
                                                                    'mid' => 'error_gender',
                                                                    'role' => 'alert',
                                                                    'aria-live' => 'assertive'
                                                                ]" />
                                                            </div>
                                                            <!-- <div class="form-group" role="radiogroup" aria-labelledby="genderLabel">
                                                                <label id="genderLabel" class="col-form-label pt-0 text-left">Gender<span class="errmsg" aria-hidden="true">*</span></label>&nbsp;&nbsp;
                                                                <input type="radio" name="gender" id="gender_male" value="Male" aria-checked="true" aria-label="Men">&nbsp;<label for="gender_male">Men</label> &nbsp;&nbsp;
                                                                <input type="radio" name="gender" id="gender_female" value="Female" aria-checked="false" aria-label="Women">&nbsp;<label for="gender_female">Women</label>
                                                                <x-message :attr="[
                                                                    'classname' => 'frmerror', 
                                                                    'message' => '',
                                                                    'mid' => 'error_gender',
                                                                    'role' => 'alert',
                                                                    'aria-live' => 'assertive'
                                                                ]" />
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-none d-md-block">
                                                        <img alt="Niche Fragrances Only" class="niche-logo" src="{{config('global.SITE_IMAGES')}}niche-fragrances-only.png" width="164" height="84">
                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Join Now" classname="btn btn-primary" aria-label="Join Now" />
                                            </div>
                                        </div>
                                        <br>
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
<script src="{{ asset('public/js/core/niche_fragrance_validate.js') }}"></script>