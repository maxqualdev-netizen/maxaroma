<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="email-friend-title" aria-describedby="email-friend-desc">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="email-friend-desc">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1 id="email-friend-title">Email a friend</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        @if (Session::has('failedef'))
                        <x-message :attr="[
                            'classname' => 'frmerror frmerror_shw', 
                            'message' => Session::get('failedef')]" />
                        @endif
                        <form method="post" id="frmFriend" name="frmFriend" action="javascript:void(0)" aria-labelledby="email-friend-title" aria-describedby="email-friend-desc">
                            <input type="hidden" name="isAction" value="EmailAfriend">
                            <input type="hidden" name="productId" value="{{$productId}}">
                            <div class="row" role="region" aria-labelledby="email-friend-title">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail1" class="col-form-label" id="label-fmail1">Friend's Email Address 1<span class="errmsg">*</span></label>
                                        <input id="fmail1" name="fmail1" type="text" placeholder="Friend's Email Address 1" class="form-control" aria-labelledby="label-fmail1" aria-required="true" aria-label="Friend's Email Address 1">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail1']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail2" class="col-form-label" id="label-fmail2">Friend's Email Address 2</label>
                                        <input id="fmail2" name="fmail2" type="text" placeholder="Friend's Email Address 2" class="form-control" aria-labelledby="label-fmail2" aria-label="Friend's Email Address 2">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail2']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail3" class="col-form-label" id="label-fmail3">Friend's Email Address 3</label>
                                        <input id="fmail3" name="fmail3" type="text" placeholder="Friend's Email Address 3" class="form-control" aria-labelledby="label-fmail3" aria-label="Friend's Email Address 3">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail3']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail4" class="col-form-label" id="label-fmail4">Friend's Email Address 4</label>
                                        <input id="fmail4" name="fmail4" type="text" placeholder="Friend's Email Address 4" class="form-control" aria-labelledby="label-fmail4" aria-label="Friend's Email Address 4">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail4']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail5" class="col-form-label" id="label-fmail5">Friend's Email Address 5</label>
                                        <input id="fmail5" name="fmail5" type="text" placeholder="Friend's Email Address 5" class="form-control" aria-labelledby="label-fmail5" aria-label="Friend's Email Address 5">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail5']" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="col-form-label" id="label-message">Comments</label>
                                        <textarea id="message" name="message" rows="4" cols="50" type="text" placeholder="Comments" class="form-control" aria-labelledby="label-message" aria-label="Comments"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="youremail" class="col-form-label" id="label-youremail">Your Email Address<span class="errmsg">*</span></label>
                                        <input id="youremail" name="youremail" type="text" placeholder="Your Email Address" class="form-control" aria-labelledby="label-youremail" aria-required="true" aria-label="Your Email Address">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_youremail']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="yourname" class="col-form-label" id="label-yourname">Your Name</label>
                                        <input id="yourname" name="yourname" type="text" placeholder="Your Name" class="form-control" aria-labelledby="label-yourname" aria-label="Your Name">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        @include('googlecaptcha')
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-actions clearfix pt-0 pb-0">
                                        <div class="float-right">
                                            <x-button btype="submit" btntext="Submit" classname="btn btn-primary join-btn" bid="btnjoin" aria-label="Submit Email to Friend" />
                                            <a href="#" class="btn btn-secondary" aria-label="Reset Form">Reset</a>
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
<script src="{{ asset('public/js/core/email_a_friend_validate.js') }}"></script>