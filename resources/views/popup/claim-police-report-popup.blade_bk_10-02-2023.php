<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>CLAIM ORDER POLICE REPORT UPLOAD</h1>
                    </div>
                    <div class="modal-space">
                        @if (Session::has('successef'))
                        <x-message :attr="[
                            'classname' => 'frmsuccess', 
                            'message' => Session::get('successef')]" />
                        @endif
                        <form method="post" id="frmPoliceReportOrder" name="frmPoliceReportOrder" action="{{ url('claimed_order_police_report') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="isAction" value="claimorderpolicereport">
                            <input type="hidden" name="orders_id" value="{{$orders_id}}">
                            <input type="hidden" name="check_value" value="1" id="check_value">
			    
                            <div class="row">
								<div class="pb-md-3 col-12 text-right fw500 requd-feild"><span class="errmsg">*</span>Required Fields</div>
								
								<div class="col-md-12 pt-3">
									<div class="field required">
										<label for="fmail1" class="col-form-label">Upload Report :</label>
										<input type="file" placeholder="Attachment" name="attach_item_file" id="attach_item_file" value="" class="form-control" accept="image/*,.pdf" required>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="form-actions clearfix pt-0 pb-0">
										<div class="float-right">
											<a href="javascript:void(0);" id="claimReportclick" class="btn btn-primary return-btn">Upload</a>
											<a href="javascript:void(0)" onclick="resetform('frmPoliceReportOrder')" class="btn btn-secondary">Reset</a>
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
<script src="{{ asset('public/js/core/claimorder.js?ver=1') }}"></script>
