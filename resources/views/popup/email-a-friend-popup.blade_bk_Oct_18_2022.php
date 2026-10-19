<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>Email a friend</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="psot" id="frmFriend" name="frmFriend" action="javascript:void(0)">
                            <input type="hidden" name="isAction" value="EmailAfriend">
                            <input type="hidden" name="productId" value="{{$productId}}">
                            <div class="row">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail1" class="col-form-label">Friend's Email Address 1<span class="errmsg">*</span></label>
                                        <input id="fmail1" name="fmail1" type="text" placeholder="Friend's Email Address 1" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail1']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail2" class="col-form-label">Friend's Email Address 2</label>
                                        <input id="fmail2" name="fmail2" type="text" placeholder="Friend's Email Address 2" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail2']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail3" class="col-form-label">Friend's Email Address 3</label>
                                        <input id="fmail3" name="fmail3" type="text" placeholder="Friend's Email Address 3" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail3']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail4" class="col-form-label">Friend's Email Address 4</label>
                                        <input id="fmail4" name="fmail4" type="text" placeholder="Friend's Email Address 4" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail4']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fmail5" class="col-form-label">Friend's Email Address 5</label>
                                        <input id="fmail5" name="fmail5" type="text" placeholder="Friend's Email Address 5" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_fmail5']" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="comt" class="col-form-label">Comments</label>
                                        <textarea id="message" name="message" rows="4" cols="50" type="text" placeholder="Comments" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Your Email Address<span class="errmsg">*</span></label>
                                        <input id="youremail" name="youremail" type="text" placeholder="Your Email Address" class="form-control">
                                        <x-message :attr="[
                                            'classname' => 'frmerror', 
                                            'message' => '',
                                            'mid' => 'error_youremail']" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="yourname" class="col-form-label">Your Name</label>
                                        <input id="yourname" name="yourname" type="text" placeholder="Your Name" class="form-control">
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
                                            <x-button btype="submit" btntext="Submit" classname="btn btn-primary join-btn" bid="btnjoin" />
                                            <a href="#" class="btn btn-secondary">Reset</a>
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