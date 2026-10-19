<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>CLAIM FOR INSURANCE</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="post" id="frmClaimOrder" name="frmClaimOrder" action="{{ url('claim_order') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="claimorder">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
                            <input type="hidden" name="order_details_id" id="order_details_id" value="{{$order_details_id}}">
                            <input type="hidden" name="check_value" value="1" id="check_value">
                            <input type="hidden" name="action" value="{{$trackorder}}">
                            <input type="hidden" name="billemail" value="{{$billemail}}">
			    
                            <div class="row">
                                <div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
								
								<div class="col-md-6 pt-3">
									<div class="field required">
											<label>Order number:</label>
											<input type="text" name="orders_no" id="orders_no" class="form-control" value="{{$orders_no}}">
									</div>
                                </div>
                            
                                <div class="col-md-6 pt-3">
									<div class="field required">
									<label>Customer Name:&nbsp;</label>
										<input type="text" name="cust_name" id="cust_name" class="form-control" value="{{$billfname}} {{$billlname}}" >
									</div>
                                </div>
								
                                <div class="col-md-6 pt-3">
									<div class="field required">
										<label>Reason For Claim</label>
										<select class="form-control" name="customerClaimReason" id="customerClaimReason" onchange="return otherFieldnew(this.value)">
											<option value="">Select Reason</option>
											<option value="Lost Package">Lost Package</option>
											<option value="Stolen package">Stolen package</option>
											<option value="Damage Item">Damage Item</option>
											
										</select>
									</div>
								</div>
								
								<div class="col-md-6 pt-3">
									<div class="form-group">
										<label for="fmail1" class="col-form-label">Attachment :</label>
										<input type="file" placeholder="Attachment" name="attach_item_image" id="attach_item_image" value="" class="form-control">
									</div>
								</div>
								<div class="col-md-12">
									<div class="field required">
										<label for="comment">Comment:</label>
										<textarea id="comment" name="comment" placeholder="Comment" class="form-control"></textarea>
									</div>
								</div>
                                <div class="col-md-12 pt-3">
									<div class="static-inner"> 
										<label class="checkbox-label">
										  <div class="chebox">
											<input type="checkbox" id="agreepolicy" name="agreepolicy"><span class="checkmark"></span>
										  </div>
										  <p class="pt-10" align="justify">﻿By Checking this box, I hereby certify that the information contained in this form is true and complete. I understand that if I misrepresent and/or withhold information, MaxAroma may exercise any rights it may have.</p>
										</label>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="form-actions clearfix pt-0 pb-0">
										<div class="float-right">
											<a href="javascript:;" id="orderClaimclick" class="btn btn-primary return-btn">Claim</a>
											<a href="javascript:void(0)" onclick="resetform('frmClaimOrder')" class="btn btn-secondary">Reset</a>
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
<script src="{{ asset('public/js/core/claimorder.js?ver=1.5') }}"></script> 
