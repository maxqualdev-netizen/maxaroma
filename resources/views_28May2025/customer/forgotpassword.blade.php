@extends('layouts.app')
@section('content')	
	<main>
	  <section class="smallcon">
	    <div class="container">
		  
		  <!--<div class="myact_hd"> <svg class="sv-forgotpass" aria-hidden="true" role="img">
            <use href="#sv-forgotpass" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-forgotpass"></use>
            </svg>
            <h2>Forgot Password</h2>
          </div>-->
		  <div class="row">
			<div class="col-12">
			  <form action="" method="post" id="formForgotPassword">
				{{ csrf_field() }}
				<input type="hidden" name="action" value="forgot_password"/>	
				<div class="row pt-4 pb-4">
				  <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12 col-xs-12">
				    <div class="row">
				      <div class="pb-3 col-12 text-right fw500"><span class="errmsg">*</span>Required Fields</div>		
					  <div class="col-md-12">
					    <div class="field required">
						  <label for="email_address">Email Address</label>
						  <input type="email" id="email" name="email" placeholder="Email Address" class="form-control">
							<x-message :attr="[
								'classname' => 'frmerror', 
								'message' => '',
								'mid' => 'error_email']"
							/>
							@if ($errors->has('NotExistEmail'))
								<x-message :attr="[
											'classname' => 'frmerror frmerror_shw', 
											'message' => $errors->first('NotExistEmail')]"
								/>
							@endif
							@if ($errors->has('email'))
								<x-message :attr="[
											'classname' => 'frmerror frmerror_shw', 
											'message' => $errors->first('email')]"
								/>
							@endif
							@if (Session::has('success'))
								<x-message :attr="[
											'classname' => 'frmsuccess', 
											'message' => Session::get('success')]"
								/>
							@endif
						</div>
					  </div>
					  <div class="col-12">
                            @include('googlecaptcha')
						</div>
						<div class="col-12 text-right pt-3">
							
							<a href="#" class="btn btn1-link mt-2 pt-1 float-left pl-0 back-link"><svg class="sv-back vam" aria-hidden="true" role="img" width="24" height="24"><use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use></svg>Back</a>

							<!-- <x-button btype="submit" btntext="Submit" name="btnsubmit" classname="btn btn-primary d-block"/> -->
							<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnForgotPassword"/>
						</div>
				    </div>
				  </div>
				</div>
	          </form>	    	  	
	    	</div>
		  </div>
	    </div>
	  </section>
	</main>
@endsection	