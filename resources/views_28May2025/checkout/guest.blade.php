<div id="guest1">
	<div class="row">
		<div class="col-12" id="divguest">
			<form action="{{config('global.SITE_URL')}}login.html" class="mb-0" method="post" id="formguest">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-12">
						<div class="form-group required">
						<input type="email" name="guest_email" id="guest_email" class="form-control" required="">
						<label>Email Address</label>
						<x-message :attr="[
							'classname' => 'frmerror', 
							'message' => '',
							'mid' => 'error_guest_email']"
						/>
						@if ($errors->has('email'))
							<x-message :attr="[
								'classname' => 'frmerror frmerror_shw', 
								'message' => $errors->first('guest_email')
								]"
							/>
						@endif
						</div>
					</div>
					<div class="col-12">
						<label class="checkbox-label f12 lheight-18 mb-0">
							<div class="chebox">
								<input type="checkbox" id="chknews1" name="chknews1" checked=""><span class="checkmark"></span>
							</div>
							Keep me up to date with Max Aroma news, promotions, and exclusive offers by email and SMS. I can update my preferences at any time by contacting Max Aroma or using unsubscribe links.
						</label>
					</div>
                    <div class="col-12">
                    	<div class="chekout-main-btn">
	                        <div class="mt-md-3 pt-md-1 mb-md-3">
	                            <a class="max_continuesp_btn btn btn-primary w-100" id="btnguest" href="javascript:void(0)">Continue</a>
	                            <a class="max_continuesp_btn btn btn-primary w-100" id="btnasguest" href="javascript:void(0)" style="display:none;">Continue as Guest</a>
	                        </div>
                    	</div>
                    </div>
					<div class="col-12 d-md-none">
						<div class="mobile-shadow mb-0">&nbsp;</div>
					</div>
				</div>
			</form>

		</div>
	</div>	
</div>
