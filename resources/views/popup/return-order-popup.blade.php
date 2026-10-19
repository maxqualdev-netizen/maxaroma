<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="return-title" aria-describedby="return-desc">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="return-desc">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1 id="return-title">REASON FOR ITEM RETURN</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess',
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="post" id="frmReturnOrder" name="frmReturnOrder" action="{{ url('return_order') }}" enctype="multipart/form-data" aria-labelledby="return-title" aria-describedby="return-desc">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="returnorder">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
                            <input type="hidden" name="order_details_id" id="order_details_id" value="{{$order_details_id}}">
                            <input type="hidden" name="oldquantity" id="oldquantity"  value="{{$qty}}" class="form-control">
                            <input type="hidden" name="action" value="{{$trackorder}}">
                            <div class="row" role="region" aria-labelledby="return-title">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
                                @php $returnReason = GetReturnReasons(); @endphp
                                @if(count($returnReason) > 0)
                                    <div class="field required">
                                        <label for="customerReturnReason" id="label-customerReturnReason">Select Reason</label>
                                        <select class="form-control" name="customerReturnReason" id="customerReturnReason" onchange="return otherFieldnew(this.value)" aria-labelledby="label-customerReturnReason" aria-required="true" aria-label="Select Reason">
                                            <option value="">Select Reason</option>
                                            @foreach($returnReason as $return_reason_key => $return_reason_value)
                                            <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-6">
                                    <div class="field required">
                                        <label for="quantity" id="label-quantity">Quantity:&nbsp;</label>
                                        <input type="text" name="quantity" id="quantity" class="form-control" value="{{$qty}}" maxlength="4" size="3" aria-labelledby="label-quantity" aria-required="true" aria-label="Quantity">
                                    </div>
                                </div>

                                <div class="field pt-3" id="OtherInput" style="display:none;">
                                    <div class="pb5">
                                        <div class="form-group">
                                            <label for="otherreason" class="col-form-label" id="label-otherreason">Other Reason :<span class="errmsg">*</span></label>
                                            <input type="text" placeholder="Other" name="otherreason" id="otherreason" value="" class="form-control" aria-labelledby="label-otherreason" aria-label="Other Reason" />
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
                                            <label for="damaged_item_image" class="col-form-label" id="label-damaged-item-image">Image for damaged item :<span class="errmsg">*</span></label>
                                            <input type="file" placeholder="Image for damaged item" name="damaged_item_image" id="damaged_item_image" value="" class="form-control" aria-labelledby="label-damaged-item-image" aria-label="Image for damaged item" />
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
                                            <a href="javascript:;" id="orderReturnclick" class="btn btn-primary return-btn" aria-label="Submit Return">Return</a>
                                            <a href="javascript:void(0)" onclick="resetform('frmReturnOrder')" class="btn btn-secondary" aria-label="Reset Form">Reset</a>
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