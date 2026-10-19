<div class="vertical-alignment-helper" role="presentation">
    <div class="modal-dialog vertical-align-center" role="dialog" aria-modal="true" aria-labelledby="cancelOrderModalTitle">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close popup">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd" id="cancelOrderModalTitle">
                        <h1>Reason for order cancel</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess',
                            'message' => Session::get('successef'),
                            'role' => 'status',
                            'aria-live' => 'polite'
                        ]" />
                        @endif
                        <form method="post" id="frmCancelOrder" name="frmCancelOrder" action="{{ url('cancel_order') }}" aria-labelledby="cancelOrderModalTitle">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="cancelorder">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
                            <input type="hidden" name="action" value="{{$trackorder}}">
                            <div class="row">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild">
                                    <span class="errmsg" aria-hidden="true">*</span>Required Fields
                                </div>
                                @php $returnReason = GetReturnReasons(); @endphp
                                @if(count($returnReason) > 0)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="customerCancelReason" class="col-form-label">Select Reason<span class="errmsg" aria-hidden="true">*</span></label>
                                        <select class="form-control" name="customerCancelReason" id="customerCancelReason" onchange="return otherField(this.value)" aria-required="true" aria-label="Select Reason for Cancellation">
                                            <option value="">Select Reason</option>
                                            @foreach($returnReason as $return_reason_key => $return_reason_value)
                                            <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
                                            @endforeach
                                        </select>
                                        <x-message :attr="[
                                            'classname' => 'frmerror',
                                            'message' => '',
                                            'mid' => 'error_customerCancelReason',
                                            'role' => 'alert',
                                            'aria-live' => 'assertive'
                                        ]" />
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12" id="OtherInput" style="display:none;">
                                    <div class="pb5">
                                        <div class="form-group">
                                            <label for="otherreason" class="col-form-label">Other Reason :<span class="errmsg" aria-hidden="true">*</span></label>
                                            <input type="text" placeholder="Other" name="otherreason" id="otherreason" value="" class="form-control" aria-required="true" aria-label="Other Reason" />
                                            <x-message :attr="[
                                                'classname' => 'frmerror',
                                                'message' => '',
                                                'mid' => 'error_otherreason',
                                                'role' => 'alert',
                                                'aria-live' => 'assertive'
                                            ]" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-actions clearfix pt-0 pb-0">
                                        <div class="float-right">
                                            <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnCancelOrder" aria-label="Submit Cancel Order Reason"/>
                                            <a href="javascript:void(0)" onclick="resetform('frmCancelOrder')" class="btn btn-secondary" aria-label="Reset Form">Reset</a>
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
<script src="{{ asset('public/js/core/cancelorder.js') }}"></script>