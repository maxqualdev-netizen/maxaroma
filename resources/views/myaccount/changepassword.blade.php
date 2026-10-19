@extends('layouts.app')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	

				<div class="col-lg-9 col-md-12 col-xs-12">
				   <div class="myact_hd">
				      <svg class="sv_change_password" aria-hidden="true" role="img">
				         <use href="#sv_change_password" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_change_password"></use>
				      </svg>
				      <h2>Change Password</h2>
				   </div>
				   <div class="myact_cpword">
				      <form action="" method="post" id="frmChangePassword">
	    				{{ csrf_field() }}
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
				         <div class="row">
				            <div class="col-md-7">
				               <div class="field required mt-3">
				                  <label for="old_password" >Old Password</label>
				                  <input type="password" id="old_password" name="old_password" placeholder="Old Password" class="form-control" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_old_password']"
									/>
									@if ($errors->has('old_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('old_password')]"
										/>
									@endif
									@if ($errors->has('wrong_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('wrong_password')]"
										/>
									@endif
				               </div>
				               <div class="field required mt-3">
				                  <label for="new_password" >New Password</label>
				                  <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_new_password']"
									/>
									@if ($errors->has('new_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('new_password')]"
										/>
									@endif
									@if ($errors->has('uppercase_number'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('uppercase_number')]"
										/>
									@endif
				               </div>
				               <div class="field required mt-3">
				                  <label for="re_type_new_password" >Re-Type New Password</label>
				                  <input type="password" id="re_type_new_password" name="re_type_new_password" placeholder="Re-Type New Password" class="form-control" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_re_type_new_password']"
									/>
									@if ($errors->has('re_type_new_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('re_type_new_password')]"
										/>
									@endif
				               </div>
				               <div class="pt-3">
				               	<!-- <x-button btype="submit" btntext="Change my password" name="sbtsubmit" classname="btn btn-primary btn-block"/> -->
								<x-button link="javascript:void(0);" btntext="Change my password" classname="btn btn-primary btn-block" bid="btnChangePassword"/>
				               </div>
				            </div>
				            <div class="col-md-5 pt-3">
				               <div class="cptext">
				                  <strong>Password must contain:</strong><br />At least 1 upper case letter (A-Z)<br />At least 1 number (0-9)<br />At least 6 characters
				                  <div class="text-right">
				                     <svg class="sv_change_password" aria-hidden="true" role="img">
				                        <use href="#sv_change_password" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_change_password"></use>
				                     </svg>
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
</main>	

@endsection
