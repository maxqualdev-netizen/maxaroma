<div id="coupon-modal-popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center">
            <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                        <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                    </svg> </a>
                <div class="modal-body">
                    <div class="modal-space">
                        <div class="subscribe-modal">
                            <div class="row align-items-center">
                                <div class="col-md-5 pr-md-0">
                                    <div class="half-img">
                                        @if ($InstantCouponText[0]['instant_image'])
                                        <img src="{{ $FilePath}}/images/{!! $InstantCouponText[0]['instant_image'] !!}" alt="" class="d-none d-md-block w-100" width="360" height="554" />
                                        <img src="{{ $FilePath}}/images/join-img-mob.png" alt="" class="d-md-none w-100" width="600" height="300"/>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="modal-half-div pr-md-5">
                                        <div class="join-hd">
                                            @if ($InstantCouponText[0]['detail_html'])
                                            {!! $InstantCouponText[0]['detail_html'] !!}
                                            @endif
                                        </div>
                                        <div class="join-input">
                                            <form method="post" name="frmPopUpNewsletter" id="frmPopUpNewsletter" action="javascript:void(0)">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Email Address" id="newsletter_email" name="newsletter_email">
                                                    <x-message :attr="[
                                                        'classname' => 'frmerror', 
                                                        'message' => '',
                                                        'mid' => 'error_newsletter_email']" />
                                                    <x-button btype="submit" btntext="Join" classname="btn btn-primary join-btn" bid="btnjoin" />
                                                </div>
                                            </form>
                                        </div>
                                        <p class="text-center"> <a href="javascript:void();" data-dismiss="modal" aria-hidden="true" class="closing">No Thanks</a> </p>
                                        <p class="mb-0">By Clicking Join, You Agree To Our Terms And Acknowledge You Have Read Our <a target="_blank"  href="privacy-policy.html">Privacy Policy</a>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="coupon-modal-popup-msg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-sm vertical-align-center">
            <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                        <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                    </svg> </a>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="modal-space">
                            <div id="newsPopUpLetter">

                                @if ($InstantCouponText[0]['success_image'])
                                <img src="{{ $FilePath}}/images/{!! $InstantCouponText[0]['success_image'] !!}" alt="1st order Extra 10%" class="w-100">
                                @endif

                                <div class="sumsg p-4">
                                    @if ($InstantCouponText[0]['sucessmsg'])
                                    {!! $InstantCouponText[0]['sucessmsg'] !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/js/core/instant_coupon_validate.js') }}"></script>