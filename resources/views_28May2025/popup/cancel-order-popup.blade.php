<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>Reason for order cancel</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="post" id="frmCancelOrder" name="frmCancelOrder" action="{{ url('cancel_order') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="cancelorder">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
			    <input type="hidden" name="action" value="{{$trackorder}}">
                            <div class="row">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                @php $returnReason = GetReturnReasons(); @endphp
                                @if(count($returnReason) > 0)
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="fmail1" class="col-form-label">Select Reason<span class="errmsg">*</span></label>
                                        <select class="form-control" name="customerCancelReason" id="customerCancelReason" onchange="return otherField(this.value)">
                                            <option value="">Select Reason</option>
                                            @foreach($returnReason as $return_reason_key => $return_reason_value)
                                            <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
                                            @endforeach
                                        </select>
                                        <x-message :attr="[
                                        'classname' => 'frmerror', 
                                        'message' => '',
                                        'mid' => 'error_customerCancelReason']" />
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12" id="OtherInput" style="display:none;">
                                    <div class="pb5">
                                        <div class="form-group">
                                        <label for="fmail1" class="col-form-label">Other Reason :<span class="errmsg">*</span></label>
                                        <input type="text" placeholder="Other" name="otherreason" id="otherreason" value="" class="form-control" />
                                        <x-message :attr="[
                                        'classname' => 'frmerror', 
                                        'message' => '',
                                        'mid' => 'error_otherreason']" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-actions clearfix pt-0 pb-0">
                                        <div class="float-right">
                                            <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnCancelOrder"/>
                                            <a href="javascript:void(0)" onclick="resetform('frmCancelOrder')" class="btn btn-secondary">Reset</a>
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