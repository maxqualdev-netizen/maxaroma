<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>Ratings & Reviews</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successpr'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successpr')]" />
                        @endif
                        <br>
                        <form method="post" id="frmRatingsReview" name="frmRatingsReview" action="javascript:void(0)">
                            <input type="hidden" name="isAction" value="ProductReview">
                            <input type="hidden" name="productId" value="{{$productId}}">
                            <div class="row">
                                <div class="col-12 pb20">
                                    <div class="pt15"> <strong>How do you rate this item?</strong> <br><br>
                                        <div class="reg_error" id="errorBox" style="display:none;"></div>
                                        <div class=" ratting-input pt15">
                                            <input type="radio" class="noborder" value="1" name="star_rate">
                                            <span class="rating">
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star active"></span>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class=" ratting-input pt5">
                                            <input type="radio" class="noborder" value="2" name="star_rate">
                                            <span class="rating">
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class=" ratting-input pt5">
                                            <input type="radio" class="noborder" value="3" name="star_rate">
                                            <span class="rating">
                                                <span class="star"></span>
                                                <span class="star"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class=" ratting-input pt5">
                                            <input type="radio" class="noborder" value="4" name="star_rate">
                                            <span class="rating">
                                                <span class="star"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class=" ratting-input pt5">
                                            <input type="radio" class="noborder" value="5" name="star_rate" checked>
                                            <span class="rating">
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                                <span class="star active"></span>
                                            </span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="col-12 pb20">
                                    <div class="row">
                                        <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="FirstName" class="col-form-label">Your Name<span class="errmsg">*</span></label>
                                                <input id="FirstName" name="FirstName" type="text" placeholder="Your Name" value="{{ $name ?? '' }}" class="form-control">
                                                <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_FirstName']" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city" class="col-form-label">City</label>
                                                <input id="city" type="text" name="city" placeholder="Enter City Name" value="{{ $city ?? '' }}" class="form-control">
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
    											'selected' => ($country?$country:'US') ]" />
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
    											'error' => $errors->has('state')]" />
                                            </div>
                                            <div class="form-group otherstate" id="divotherstate" {!! $dotherstateclass !!}>
                                                <label for="oter_state">Other</label>
                                                <input type="text" id="other_state" name="other_state" value="{{ old('other_state') }}" placeholder="Other State" class="form-control {{ $errors->has('other_state') ? ' is-invalid' : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="user_review" class="col-form-label">Your Review<span class="errmsg">*</span></label>
                                                <textarea id="user_review" name="user_review" rows="4" cols="50" type="text" placeholder="Enter Your Review" class="form-control"></textarea>
                                                <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_user_review']" />
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