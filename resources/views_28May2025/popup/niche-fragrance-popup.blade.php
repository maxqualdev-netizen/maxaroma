<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd text-center pt-3">
                        <img src="https://www.maxaroma.com/images/join-peraroma-logo.png" alt="">
                    </div>
                    <div class="modal-space">
                        <form method="post" name="FormNicheFragrances" id="FormNicheFragrances" action="javascript:void(0)">
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
                                                    'message' => Session::get('failedfm')]" />
                                                    @endif
                                                    @if (Session::has('successfm'))
                                                    <x-message :attr="[
                                                    'classname' => 'frmsuccess', 
                                                    'message' => Session::get('successfm')]" />
                                                    @endif
                                                    <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox"><span></span></div>
                                                    <div class="form-group">
                                                        <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" placeholder="Email Address" id="email" name="email">
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_email']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="First Name" class="col-form-label pt-0 text-left">First Name<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name">
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_first_name']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Last Name" class="col-form-label pt-0 text-left">Last Name<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name">
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_last_name']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gender" class="col-form-label pt-0 text-left">Gender<span class="errmsg">*</span></label>&nbsp;&nbsp;
                                                        <input type="radio" name="gender" id="gender" value="Male" checked="">&nbsp;Men &nbsp;&nbsp;
                                                        <input type="radio" name="gender" id="gender" value="Female" checked="">&nbsp;Women
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_gender']" />
                                                    </div>
                                                </div>
                                                    </div>
                                                    <div class="col-md-3 d-none d-md-block">
                                                                                                        <img alt="" class="niche-logo" src="{{config('global.SITE_IMAGES')}}niche-fragrances-only.png" width="164" height="84">

                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Join Now" classname="btn btn-primary" />
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