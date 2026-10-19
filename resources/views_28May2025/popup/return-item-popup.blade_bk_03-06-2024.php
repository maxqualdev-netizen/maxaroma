<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content">
			<a class="close" type="button" data-dismiss="modal" aria-hidden="true"><svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16"><use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use></svg> </a>
			<div class="modal-body">
                <div class="friend-mail-modal">
					<div class="modal-hd">
                        <h1>RETURN ITEMS</h1>
                    </div>

					<div class="container">
						<div class="row row10">
							@php
								$class = 'class="col-lg-12 col-md-12 col-xs-12"';
								$returnReason = GetReturnReasons();
								$selectDisableStr = ($is_order_returnable == 'no') ? 'disabled="disabled"' : '';
								$i = 0;
							@endphp
							<form method="post" id="frmReturnItemPopup" name="frmReturnItemPopup" action="{{ url('return_order_item') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<input type="hidden" name="action" value="{{$requestedPage}}">
								<input type="hidden" name="orders_id" value="{{ $OrderRs->orders_id }}">
								<div {!! $class !!}>
									<div class="">
										<div id="Rptable">
											<table class="text-center">
												<thead>
													<tr>
														<td width="5%"><input type="checkbox" id="return-select-all" {!! $selectDisableStr !!}></td>
														<td width="50%" class="text-left">Item Description</td>
														<td width="25%">Reason</td>
														<td width="20%">Quantity</td>
													</tr>
												</thead>
												<tbody class="align-top">
													@if(count($OrderDetailRs) > 0)
													@foreach($OrderDetailRs as $order_details_key => $order_details_value)
													@if($order_details_value->is_returnable == 'yes')
													<tr class="return-tr">
														<!--<td><input type="checkbox" name="return_item[]" data-index="{{ $loop->index }}" class="return-checkbox" value="{{$order_details_value->orders_detail_id}}"></td>-->
														<input type="hidden" name="order_details_id[]" value="{{$order_details_value->orders_detail_id}}">
														@php
														$disableStr = ($order_details_value->is_returnable == 'no') ? 'disabled="disabled"' : '';
														@endphp
														<td data-title="full center" class="return-tcheckbox"><input type="checkbox" name="return_item[]" data-index="{{ $i }}" class="return-checkbox" value="{{ $i }}" {!! $disableStr !!}></td>
														<td data-title="full center" class="text-left">
															<div class="row row5 retun-pbox">
																<div class="col-lg-4 col-md-5 col-sm-6 text-center text-sm-left retun-pimg"><a href="#"><img src="{{$order_details_value->Image}}" alt=""></a></div>
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
																</div>
															</div>
														</td>
														@if(count($returnReason) > 0)
														<td data-title="full" data-title="Reason">
															<select class="form-control" name="customerReturnReason[]" id="return-item-{{ $i }}" onchange="return popupOtherFieldnew(this.value, {{ $i }})">
																<option value="">Select Reason</option>
																@foreach($returnReason as $return_reason_key => $return_reason_value)
																<option value="{{ $return_reason_value }}">{{ $return_reason_value }}</option>
																@endforeach
															</select>
															<div class="field pt-3" id="OtherInput-{{ $i }}" style="display:none;">
																<div class="pb5">
																	<div class="form-group">
																		<label for="fmail1" class="col-form-label">Other Reason :<span class="errmsg">*</span></label>
																		<input type="text" placeholder="Other" name="otherreason[]" id="otherreason-{{ $i }}" value="" class="form-control" />
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
																		<label for="fmail1" id="lblReturnImageUpload" class="col-form-label">Image for damaged item :<span class="errmsg">*</span></label>
																		<input type="file" placeholder="Image for damaged item" id="damaged_item_image_{{ $i }}" name="damaged_item_image_{{ $i }}" value="" class="form-control" />
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
															<input type="number" min="1" id="return_qty_{{ $i }}" max="{{$order_details_value->quantity}}" name="return_qty[]" value="1">
															@else
															<input type="hidden" id="return_qty_{{ $i }}" name="return_qty[]" value="{{$order_details_value->quantity}}">
															{{$order_details_value->quantity}}
															@endif
														</td>
													</tr>
													@php $i++;@endphp
													@endif
													@endforeach
													<!--<tr>
														<td class="text-right"><a href="javascript:;" id="orderItemReturnclick" class="btn btn-primary return-btn">Return</a></td>
													</tr>-->
													@else
													No items found!
													@endif
												</tbody>
											</table>
										</div>
										<div class="col-12 pt-3">
											<div class="form-actions clearfix pt-0 pb-10">
												<div class="float-right">
													<a href="javascript:;" id="orderItemReturnclick" class="btn btn-primary return-btn">Return</a>
													<a href="javascript:void(0)" onclick="resetform('frmReturnItemPopup')" class="btn btn-secondary">Reset</a>
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
