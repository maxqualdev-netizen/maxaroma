@extends('layouts.app')
@section('content')
<main>   
	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1 col-md-12">
				<div class="login pb-3 pt-3">
					<form action="" method="post" id="formreset">
						{{ csrf_field() }}
						<input type="hidden" name="action" value="reset"/>
                        <input type="hidden" name="code" value="{{$code}}"/>
						<div class="row">
							<div class="col-md-6 pt-4">
                                <h3>Reset Password</h3>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        @if (Session::has('failed'))
                                            <x-message :attr="[
                                                        'classname' => 'frmerror frmerror_shw', 
                                                        'message' => Session::get('failed')]"
                                            />
                                        @endif
                                        @if (Session::has('success'))
                                            <x-message :attr="[
                                                        'classname' => 'frmsuccess frmerror_shw', 
                                                        'message' => Session::get('success')]"
                                            />
                                        @endif
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div class="field required">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" name="password" placeholder="Password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}">							
                                            <small class="pt-1 d-block">Don't use space when entering your password.</small>
                                            <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_password']"
                                            />
                                            @if ($errors->has('password'))
                                                <x-message :attr="[
                                                            'classname' => 'frmerror frmerror_shw', 
                                                            'message' => $errors->first('password')]"
                                                />
                                            @endif
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div class="field required">
                                            <label for="confirm_Password">Confirm Password</label>
                                            <input type="password" id="confirm_Password" name="confirm_Password" placeholder="Confirm Password" class="form-control {{ $errors->has('confirm_Password') ? ' is-invalid' : '' }}">
                                            <x-message :attr="[
                                                'classname' => 'frmerror', 
                                                'message' => '',
                                                'mid' => 'error_confirm_Password']"
                                            />
                                            @if ($errors->has('confirm_Password'))
                                                <x-message :attr="[
                                                            'classname' => 'frmerror frmerror_shw', 
                                                            'message' => $errors->first('confirm_Password')]"
                                                />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div>
                                            <x-button link="javascript:void(0);" btntext="Reset Password" classname="btn btn-primary d-block" bid="btnReset"/>
                                        </div>
                                    </div>
                                </div>    
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection