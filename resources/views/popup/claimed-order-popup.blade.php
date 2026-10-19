<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="claim-title" aria-describedby="claim-desc">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="claim-desc">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1 id="claim-title">CLAIM FOR INSURANCE</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <div class="row" role="region" aria-labelledby="claim-title">
                            <div class="pb-md-3 col-12 fw500 requd-feild">You have already requested for Claim, please find your claim details below:</div>
                            <div class="col-md-12 pt-3">
                                <div class="field">
                                    <label id="label-requested-date"><span>Requested Date:&nbsp;</span><span aria-labelledby="label-requested-date">{{$submit_date}}</span></label>
                                </div>
                            </div>
                            <div class="col-md-12 pt-3">
                                <div class="field">
                                    <label id="label-claim-reason"><span>Claim Reason:&nbsp;</span><span aria-labelledby="label-claim-reason">{{$claim_reason}}</span></label>
                                </div>
                            </div>
                            <div class="col-md-12 pt-3">
                                <div class="field">
                                    <label id="label-claim-status"><span>Claim Status:&nbsp;</span><span aria-labelledby="label-claim-status">{{$status}}</span></label>
                                </div>
                            </div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>