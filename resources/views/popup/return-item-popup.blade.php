<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="return-items-title" aria-describedby="return-items-desc">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="return-items-desc">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1 id="return-items-title">RETURN ITEMS</h1>
                    </div>
                    <div class="container">
                        <div class="row row10">
                            @php
                                $class = 'class="col-lg-12 col-md-12 col-xs-12"';
                                $returnReason = GetReturnReasons();
                                $selectDisableStr = ($is_order_returnable == 'no') ? 'disabled="disabled"' : '';
                                $i = 0;
                            @endphp
                            <form method="post" id="frmReturnItemPopup" name="frmReturnItemPopup" action="{{ url('return_order_item') }}" enctype="multipart/form-data" aria-labelledby="return-items-title" aria-describedby="return-items-desc">
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="{{$requestedPage}}">
                                <input type="hidden" name="orders_id" value="{{ $OrderRs->orders_id }}">
                                <div {!! $class !!}>
                                    <div class="">
                                        <div id="Rptable" role="region" aria-label="Return Items Table">
                                            <table class="text-center" aria-describedby="return-items-table-desc">
                                                <caption id="return-items-table-desc" class="sr-only">Select items to return, specify reason and quantity.</caption>
                                                <thead>
                                                    <tr>
                                                        <td width="5%"><input type="checkbox" id="return-select-all" {!! $selectDisableStr !!} aria-label="Select all items to return"></td>
                                                        <td width="50%" class="text-left">Item Description</td>
                                                        <td width="25%">Reason</td>
                                                        <td width="20%">Quantity</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="align-top">
                                                    @if(count($OrderDetailRs) > 0)
                                                    @foreach($OrderDetailRs as $order_details_key => $order_details_value)
                                                    @if($order_details_value->is_returnable == 'yes' && $order_details_value->excluded_flag != 'Final Sale')
                                                    <tr class="return-tr">
                                                        <input type="hidden" name="order_details_id[]" value="{{$order_details_value->orders_detail_id}}">
                                                        @php
                                                        $disableStr = ($order_details_value->is_returnable == 'no' || ($order_details_value->excluded_flag !='' && trim($order_details_value->excluded_flag) =='Final Sale')) ? 'disabled="disabled"' : '';
                                                        @endphp
                                                        <td data-title="full center" class="return-tcheckbox">
                                                            <input type="checkbox" name="return_item[]" data-index="{{ $i }}" class="return-checkbox" value="{{ $i }}" {!! $disableStr !!} aria-label="Select item {{ $order_details_value->product_name }} for return">
                                                        </td>
                                                        <td data-title="full center" class="text-left">
                                                            <div class="row row5 retun-pbox">
                                                                <div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left retun-pimg">
                                                                    <a href="#"><img src="{{$order_details_value->Image}}" alt="Image of {{ $order_details_value->product_name }}"></a>
                                                                </div>
                                                                <div class="col-lg-8 col-md-7 col-sm-6 pt-md-2 pt-sm-0 retun-ptext">
                                                                    <a href="#"><strong>{!! $order_details_value->product_name !!}</strong></a><br>
                                                                    <input type="hidden" name="return_item_sku[]" value="{{ $order_details_value->sku }}">
                                                                    Item SKU: {{ $order_details_value->sku }}
                                                                    @if($order_details_value->IsGiftCertificateItem == 'Yes')
                                                                        <br>Sender Name: {{$order_details_value->SenderName}}
                                                                        <br>Sender Email: {{$order_details_value->SenderEmail}}
                                                                        <br>Recipient Name : {{$order_details_value->RecipientName}}
                                                                        <br>Recipient Email : {{$order_details_value->RecipientEmail}}
                                                                    @endif
                                                                    @if($order_details_value->excluded_flag == 'Final Sale')
                                                                        <br>
                                                                        <strong>{{config('global.FINALSALE');}}</strong>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @if(count($returnReason) > 0)
                                                        <td data-title="full" data-title="Reason">
                                                            <label for="return-item-{{ $i }}" class="sr-only">Select reason for returning {{ $order_details_value->product_name }}</label>
                                                            <select class="form-control" name="customerReturnReason[]" id="return-item-{{ $i }}" onchange="return popupOtherFieldnew(this.value, {{ $i }})" aria-label="Select reason for return">
                                                                <option value="">Select Reason</option>
                                                                @foreach($returnReason as $return_reason_key => $return_reason_value)
                                                                <option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="field pt-3" id="OtherInput-{{ $i }}" style="display:none;">
                                                                <div class="pb5">
                                                                    <div class="form-group">
                                                                        <label for="otherreason-{{ $i }}" class="col-form-label">Other Reason :<span class="errmsg">*</span></label>
                                                                        <input type="text" placeholder="Other" name="otherreason[]" id="otherreason-{{ $i }}" value="" class="form-control" aria-label="Other reason for return" />
                                                                        <x-message :attr="[
                                                                            'classname' => 'frmerror', 
                                                                            'message' => '',
                                                                            'mid' => 'error_otherreason']" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="field pt-3" id="DamageItemImage-{{ $i }}" style="display:none;">
                                                                <div class="pb5">
                                                                    <div class="form-group">
                                                                        <label for="damaged_item_image_{{ $i }}" id="lblReturnImageUpload" class="col-form-label">Image for damaged item :<span class="errmsg">*</span></label>
                                                                        <input type="file" placeholder="Image for damaged item" id="damaged_item_image_{{ $i }}" name="damaged_item_image_{{ $i }}" value="" class="form-control" aria-label="Upload image for damaged item" />
                                                                        <x-message :attr="[
                                                                        'classname' => 'frmerror', 
                                                                        'message' => '',
                                                                        'mid' => 'error_damaged_item_image']" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        <td class="return-qty" data-title="Qty">
                                                            <input type="hidden" id="old-qty-{{ $i }}" value="{{$order_details_value->quantity}}">
                                                            @if($order_details_value->quantity > 1)
                                                            <label for="return_qty_{{ $i }}" class="sr-only">Quantity to return for {{ $order_details_value->product_name }}</label>
                                                            <input type="number" min="1" id="return_qty_{{ $i }}" max="{{$order_details_value->quantity}}" name="return_qty[]" value="1" aria-label="Quantity to return">
                                                            @else
                                                            <input type="hidden" id="return_qty_{{ $i }}" name="return_qty[]" value="{{$order_details_value->quantity}}">
                                                            {{$order_details_value->quantity}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php $i++;@endphp
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="4">No items found!</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 pt-3">
                                            <div class="form-actions clearfix pt-0 pb-10">
                                                <div class="float-right">
                                                    <a href="javascript:;" id="orderItemReturnclick" class="btn btn-primary return-btn" role="button" aria-label="Submit return request">Return</a>
                                                    <a href="javascript:void(0)" onclick="resetform('frmReturnItemPopup')" class="btn btn-secondary" role="button" aria-label="Reset form">Reset</a>
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
</div>
<script src="{{ asset('public/js/core/returnorder.js?ver=3') }}"></script>