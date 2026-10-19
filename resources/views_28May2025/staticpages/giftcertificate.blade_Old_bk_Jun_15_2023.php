@extends('layouts.app')
@section('content')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
	<main>
		<!-- Content Part Start Here -->
		<section class="breadcrumb">
	        <div class="container">
	            <a href="{{config('global.SITE_URL')}}">Home</a>
	            <span class="active">Give The Perfect Gift</span>
	        </div>
    	</section>
		<div class="container">
			<div class="static-inner">
				<div class="row">
					<div class="col-12">
						<div class="mainhd">
							<h1>Give The Perfect Gift</h1>
						</div>
					</div>
					<div class="col-12">
						<p class="text-center"><strong class="color-blk">Send a gift instantly in any amount you choose</strong></p>
					</div>
					<div class="col-lg-5 col-md-7" style="margin: auto">
						<section class="gift-main-banner">
						    <div class="gift-main-sl">
						        <a href="javascript:void(0)" data-hash="zero" data-id="GiftCard1.png" class="item" id="maingift0">
						            <img src="{{config('global.SITE_IMAGES')}}GiftCard1.png" width="525" height="300" alt="">
						        </a>
						        <a href="javascript:void(0)" data-hash="one" data-id="GiftCard2.png" class="item" id="maingift1">
						            <img src="{{config('global.SITE_IMAGES')}}GiftCard2.png" width="525" height="300" alt="">
						        </a>
						    </div>
						    
							<div class="gift-nav-sl">
								<a href="javascript:void(0)" data-id="GiftCard1.png" class="item" id="mobgift0">
									<img src="{{config('global.SITE_IMAGES')}}GiftCard1.png" width="112" height="64" alt="">
								</a>
								<a href="javascript:void(0)" data-id="GiftCard2.png" class="item" id="mobgift1">
									<img src="{{config('global.SITE_IMAGES')}}GiftCard2.png" width="112" height="64" alt="">
								</a>
							</div>
						</section>
					</div>
				</div>
			    <div class="row">
			    	<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 pt-4">
						@if(Session::has('gift_success'))
							<x-message :attr="[ 'classname' => 'alert alert-success', 'message' => Session::get('gift_success'), 'mid' => 'message_gift']"/>
						@endif
						@if(Session::has('gift_error'))
							<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('gift_error'), 'mid' => 'error_gift']"/>
						@endif
			    		<form action="" method="post" name="frmGiftCerti" id="frmGiftCerti">
							<input type="hidden" name="action" value="add_gift_certificate"/>
							{{ csrf_field() }}
							<input name="GiftImage" id="GiftImage" value="GiftCard1.png" type="hidden">
							<input name="customflag" id="customflag" value="" type="hidden">
							<input name="dateflag" id="dateflag" value="{{ old('dateflag') }}" type="hidden">
							<input name="minimumorder" id="minimumorder" value="{{$Min_Amt}}" type="hidden">
							<input name="maximumorder" id="maximumorder" value="{{$Max_Amt}}" type="hidden">
							<input name="minimumordermsg" id="minimumordermsg" value="{{$Min_Amt_Msg}}" type="hidden">
							<input name="maximumordermsg" id="maximumordermsg" value="{{$Max_Amt_Msg}}" type="hidden">
					        <div class="row">
					            <div class="pb-3 col-12 text-right fw500"><span class="errmsg">*</span>Required Fields</div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="cardamount" class="col-form-label">EGift Card Amount<span class="errmsg">*</span></label>
					                    <input name="gc_value" id="gc_value" value="{{$Min_Amt}}" type="hidden">
					                    <div class="giftcate_amont" id="giftamountcss">
								        	<!--<a href="javascript:void();" class="active">$10.00</a>
								        	<a href="javascript:void();">$20.00</a>
								        	<a href="javascript:void();">$30.00</a>
								        	<a href="javascript:void();">$40.00</a>
								        	<a href="javascript:void();">Custom</a> -->
								        	{!!$TabStr!!}
								        </div>
								        <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_gc_value']"
													/>
													@if ($errors->has('gc_value'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('gc_value')]"
														/>
													@endif

								        <div id="CustomeStr" style="display:none">
								            <div class="pt-3">
								                <input id="cardamount" type="text" placeholder="EGift Card Amount" name="cardamount" class="form-control {{ $errors->has('cardamount') ? ' is-invalid' : '' }}" onblur="return GiftValAssign(this.value)" value="{{ old('cardamount') }}">
								                <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_cardamount']"
													/>
													@if ($errors->has('cardamount'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('cardamount')]"
														/>
													@endif
								            </div>
								        </div>
								        <span class="pt-2 d-block">
								        	<samll><strong>Must be between {{$Min_Amt_Msg}} and {{$Max_Amt_Msg}}!</strong></samll>
								        </span>
					                </div>
					            </div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="recname" class="col-form-label">Recipient Name<span class="errmsg">*</span></label>
					                    <input id="recname" name="recname" type="text" placeholder="Recipient Name" class="form-control {{ $errors->has('recname') ? ' is-invalid' : '' }}" autofocus="" value="{{ old('recname') }}">
					                    <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_recname']"
													/>
													@if ($errors->has('recname'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('recname')]"
														/>
													@endif
					                </div>
					            </div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="recemail" class="col-form-label">Recipient Email<span class="errmsg">*</span></label>
					                    <input id="recemail" name="recemail" type="text" placeholder="Recipient Email" class="form-control {{ $errors->has('recemail') ? ' is-invalid' : '' }}" value="{{ old('recemail') }}">
					                      <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_recemail']"
													/>
													@if ($errors->has('recemail'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('recemail')]"
														/>
													@endif
					                </div>
					            </div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="recconfemail" class="col-form-label">Re-Enter Recipient's email<span class="errmsg">*</span></label>
					                    <input id="recconfemail" name="recconfemail" type="text" placeholder="Re-Enter Recipient's email" class="form-control {{ $errors->has('recconfemail') ? ' is-invalid' : '' }}" value="{{ old('recemail') }}">
					                     <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_recconfemail']"
													/>
													@if ($errors->has('recconfemail'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('recconfemail')]"
														/>
													@endif
					                </div>
					            </div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="yourname" class="col-form-label">Your Name<span class="errmsg">*</span></label>
					                    <input id="yourname" name="yourname" type="text" placeholder="Your Name" class="form-control {{ $errors->has('yourname') ? ' is-invalid' : '' }}" value="{{ old('yourname') }}">
					                    <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_yourname']"
													/>
													@if ($errors->has('yourname'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('yourname')]"
														/>
													@endif
					                </div>
					            </div>

					            <div class="col-md-12">
					                <div class="form-group">
					                    <label for="email" class="col-form-label">Email Address<span class="errmsg">*</span></label>
					                    <input id="youremail" name="youremail" type="text" placeholder="Email Address" class="form-control {{ $errors->has('youremail') ? ' is-invalid' : '' }}" value="{{ old('youremail') }}">
					                    <x-message :attr="[
														'classname' => 'frmerror', 
														'message' => '',
														'mid' => 'error_youremail']"
													/>
													@if ($errors->has('youremail'))
														<x-message :attr="[
															'classname' => 'frmerror', 
															'message' => $errors->first('youremail')]"
														/>
													@endif
					                </div>
					            </div>

					            <div class="col-12">
					            	<p class="color-blk"><small>If you want to send a custom greeting along with this Gift Card, please fill in the fields below.</small></p>
					            </div>

					            <div class="col-12">
					            	<div class="form-group">
					                    <label for="subject" class="col-form-label">Subject<span class="errmsg">*</span></label>
					                    <input id="subject" name="subject" type="text" placeholder="Subject" class="form-control">
					                </div>
					            </div>

					            <div class="col-12">
					                <div class="form-group">
					                    <label for="permassage" class="col-form-label">Personal Message<span class="errmsg">*</span></label>
					                    <textarea id="permassage" name="permassage" rows="4" cols="50" type="text" placeholder="Personal Message" class="form-control"></textarea>
					                </div>
					            </div>

					            <div class="col-12">
					            	<div class="giftcate_delivery">
				            		 	<div class="form-group">
						                    <label for="deliverydate" class="col-form-label">Delivery<span class="errmsg">*</span></label>

						                    <div class="giftcate_amont" id="giftdatecss"> 
									        	<a href="javascript:void();" class="{{old('dateflag')  != 'FutureDate' ? 'active' : ''}}" onclick="ChangeDeliveryDate('Instantly',this);">Send Instantly</a> 
									        	<a href="javascript:void();" class="{{old('dateflag')  == 'FutureDate' ? 'active' : ''}}"  onclick="ChangeDeliveryDate('Future Date',this);">Send On A Future Date</a> 
									        </div>

									        <div id="FutureDateStr" style="{{old('dateflag')  == 'FutureDate' ? '' : 'display:none'}}">
											    <div class="pt-3" id="">
											    	<input id="deliverydate" name="d_start_date"  data-date="" data-date-format="MM/DD/YYYY" type="Date" placeholder="MM-DD-YYYY" class="form-control input-date" value="">
										    	</div>
											</div>
						                </div>
					            	</div>
					            </div>
								<div class="col-12">
									<div class="form-group">
										@include('googlecaptcha')
									</div>
								</div>
					            <div class="col-12">
					                <div class="form-actions clearfix pt-0">					           
					                    <div class="float-right">
											<input type="reset" class="btn btn-secondary" value="Reset"> 
					                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitform();">Buy Now</a>
					                    </div>
					                </div>
					            </div>
					        </div>
						</form>
			    	</div>
			    </div>
			</div>
		</div>
		<!-- Content Part End Here -->
	</main>
@endsection
