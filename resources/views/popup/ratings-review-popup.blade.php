<div class="vertical-alignment-helper" role="presentation">
    <div class="modal-dialog vertical-align-center" role="dialog" aria-modal="true" aria-labelledby="ratingsReviewModalTitle">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close popup">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd" id="ratingsReviewModalTitle">
                        <h1>Ratings & Reviews</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successpr'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successpr'),
                            'role' => 'status',
                            'aria-live' => 'polite'
                        ]" />
                        @endif
                        <br>
                        <form method="post" id="frmRatingsReview" name="frmRatingsReview" action="javascript:void(0)" aria-labelledby="ratingsReviewModalTitle">
                            <input type="hidden" name="isAction" value="ProductReview">
                            <input type="hidden" name="productId" value="{{$productId}}">
                            <div class="row">
                                <div class="col-12 pb20">
                                    <div class="pt15">
                                        <strong id="rateItemLabel">How do you rate this item?</strong>
                                        <br><br>
                                        <div class="reg_error" id="errorBox" style="display:none;" role="alert" aria-live="assertive"></div>
                                        <fieldset class="pt15" role="radiogroup" aria-labelledby="rateItemLabel">
                                            <legend class="sr-only">Star Rating</legend>
                                            <div class="ratting-input">
                                                <input type="radio" class="noborder" value="1" name="star_rate" id="star_rate_1" aria-label="1 star">
                                                <span class="rating" aria-hidden="true">
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star active"></span>
                                                </span>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="ratting-input pt5">
                                                <input type="radio" class="noborder" value="2" name="star_rate" id="star_rate_2" aria-label="2 stars">
                                                <span class="rating" aria-hidden="true">
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                </span>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="ratting-input pt5">
                                                <input type="radio" class="noborder" value="3" name="star_rate" id="star_rate_3" aria-label="3 stars">
                                                <span class="rating" aria-hidden="true">
                                                    <span class="star"></span>
                                                    <span class="star"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                </span>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="ratting-input pt5">
                                                <input type="radio" class="noborder" value="4" name="star_rate" id="star_rate_4" aria-label="4 stars">
                                                <span class="rating" aria-hidden="true">
                                                    <span class="star"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                </span>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="ratting-input pt5">
                                                <input type="radio" class="noborder" value="5" name="star_rate" id="star_rate_5" aria-label="5 stars" checked>
                                                <span class="rating" aria-hidden="true">
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                    <span class="star active"></span>
                                                </span>
                                            </div>
                                            <div class="clear"></div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-12 pb20">
                                    <div class="row">
                                        <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg" aria-hidden="true">*</span>Required Fields</div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="FirstName" class="col-form-label">Your Name<span class="errmsg" aria-hidden="true">*</span></label>
                                                <input id="FirstName" name="FirstName" type="text" placeholder="Your Name" value="{{ $name ?? '' }}" class="form-control" aria-required="true" autocomplete="given-name">
                                                <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_FirstName',
                                                'role' => 'alert',
                                                'aria-live' => 'assertive'
                                                ]" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city" class="col-form-label">City</label>
                                                <input id="city" type="text" name="city" placeholder="Enter City Name" value="{{ $city ?? '' }}" class="form-control" autocomplete="address-level2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-selectbox :attr="[
                                                'label' => 'Country',
                                                'id' => 'country', 'name' => 'country',
                                                'firstopt' => '',
                                                'classname' => 'form-control',
                                                'data' => $Countries,
                                                'selected' => ($country?$country:'US'),
                                                'aria-required' => 'true'
                                                ]" />
                                            </div>
                                        </div>
                                        @php
                                        if(old('country')) {
                                        if(old('country') && old('country') != 'US') {
                                        $dstateclass='style="display:none;"';
                                        $dotherstateclass="";
                                        } else {
                                        $dstateclass="";
                                        $dotherstateclass='style="display:none;"';
                                        }
                                        } else {
                                        $dstateclass="";
                                        $dotherstateclass='style="display:none;"';
                                        }
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="form-group" id="divstate" {!! $dstateclass !!}>
                                                <x-selectbox :attr="[
                                                'label' => 'State',
                                                'id' => 'state', 'name' => 'state',
                                                'firstopt' => '',
                                                'classname' => 'form-control',
                                                'data' => $States,
                                                'selected' => $state,
                                                'error' => $errors->has('state'),
                                                'aria-required' => 'true'
                                                ]" />
                                            </div>
                                            <div class="form-group otherstate" id="divotherstate" {!! $dotherstateclass !!}>
                                                <label for="other_state">Other</label>
                                                <input type="text" id="other_state" name="other_state" value="{{ old('other_state') }}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}" autocomplete="address-level1">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="user_review" class="col-form-label">Your Review<span class="errmsg" aria-hidden="true">*</span></label>
                                                <textarea id="user_review" name="user_review" rows="4" cols="50" type="text" placeholder="Enter Your Review" class="form-control" aria-required="true"></textarea>
                                                <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_user_review',
                                                'role' => 'alert',
                                                'aria-live' => 'assertive'
                                                ]" />
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
                                                    <x-button btype="submit" btntext="Submit" classname="btn btn-primary join-btn" bid="btnjoin" aria-label="Submit Ratings and Review" />
                                                </div>
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
<script src="{{ asset('public/js/core/ratings_review_validate.js') }}"></script>