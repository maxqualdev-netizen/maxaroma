<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>REASON FOR ITEM RETURN</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="post" id="frmReturnOrder" name="frmReturnOrder" action="{{ url('return_order') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="returnorder">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
                            <input type="hidden" name="order_details_id" id="order_details_id" value="{{$order_details_id}}">
                            <input type="hidden" name="oldquantity" id="oldquantity"  value="{{$qty}}" class="form-control">
			    <input type="hidden" name="action" value="{{$trackorder}}">
                            <div class="row">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                @php $returnReason = GetReturnReasons(); @endphp
                                @if(count($returnReason) > 0)
                                    <div class="field required">
                                        <label>Select Reason</label>
                                        <select class="form-control" name="customerReturnReason" id="customerReturnReason" onchange="return otherFieldnew(this.value)">
                                            <option value="">Select Reason</option>
                                            @foreach($returnReason as $return_reason_key => $return_reason_value)
                                            <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            
                                <div class="col-6">
                                <div class="field required">
                                <label>Quantity:&nbsp;</label>
                                    <input type="text" name="quantity" id="quantity" class="form-control" value="{{$qty}}" maxlength="4" size="3">
                                </div>
                                </div>
                                   
                                <div class="field pt-3" id="OtherInput" style="display:none;">
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
                                <div class="field pt-3" id="DamageItemImage" style="display:none;">
                                    <div class="pb5">
                                        <div class="form-group">
                                        <label for="fmail1" class="col-form-label">Image for damaged item :<span class="errmsg">*</span></label>
                                        <input type="file" placeholder="Image for damaged item" name="damaged_item_image" id="damaged_item_image" value="" class="form-control" />
                                        <x-message :attr="[
                                        'classname' => 'frmerror', 
                                        'message' => '',
                                        'mid' => 'error_damaged_item_image']" />
                                        </div>
                                    </div>
                                </div>
                             
                                <div class="col-12 pt-3">
                                    <div class="form-actions clearfix pt-0 pb-0">
                                        <div class="float-right">
                                            <a href="javascript:;" id="orderReturnclick" class="btn btn-primary return-btn">Return</a>
                                            <a href="javascript:void(0)" onclick="resetform('frmReturnOrder')" class="btn btn-secondary">Reset</a>
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
<script src="{{ asset('public/js/core/returnorder.js?ver=1') }}"></script>
