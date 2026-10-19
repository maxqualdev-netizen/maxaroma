@extends('layouts.app')
@section('content')
<main>
   <section class="breadcrumb">
      <div class="container">
         <a href="#">Home</a>
         <span class="active">Contact Us</span>
      </div>
   </section>
   <!-- Content Part Start Here -->
   <div class="container">
      <div class="static-inner contact-us">
         <div class="row">
            <div class="col-12">
               <div class="mainhd">
                  <h1>Contact Us</h1>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 col-sm-6">
               <div class="float-left w-100 pr-3 border-right cont-main">
                  {!! $Content !!}
               </div>
            </div>
            <div class="col-md-4 col-sm-6">            	
				@if (Session::has('success'))
					<div class="col-md-12 pb-4 text-center"><small class="pt-1 d-block frmsuccess"><strong>{{ Session::get('success') }}</strong></small></div>
				@endif
               <form action="" method="post" id="frmContactUs">
               	<input type="hidden" name="action" value="submit"/>
				        {{ csrf_field() }}
                  <div class="row">
                     <div class="pb-3 col-12 text-right fw500 f14"><span class="errmsg">*</span>Required Fields</div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="your_name" class="col-form-label">Your Name<span class="errmsg">*</span></label>
                           <input id="your_name" name="your_name" type="text" placeholder="Your Name" class="form-control" autofocus="" value="{{ old('your_name') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror', 
                              'message' => '',
                              'mid' => 'error_your_name']"
                           />
      							@if ($errors->has('your_name'))
      								<x-message :attr="[
      											'classname' => 'frmerror', 
      											'message' => $errors->first('your_name')]"
      								/>
      							@endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="your_email" class="col-form-label">Email Address<span class="errmsg">*</span></label>
                           <input id="your_email" type="email" placeholder="Email Address" class="form-control" name="your_email" value="{{ old('your_email') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror', 
                              'message' => '',
                              'mid' => 'error_your_email']"
                           />
      							@if ($errors->has('your_email'))
      								<x-message :attr="[
      											'classname' => 'frmerror', 
      											'message' => $errors->first('your_email')]"
      								/>
      							@endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="your_subject" class="col-form-label">Subject<span class="errmsg">*</span></label>
                           <input id="your_subject" type="text" placeholder="Subject" class="form-control" name="your_subject" value="{{ old('your_subject') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror', 
                              'message' => '',
                              'mid' => 'error_your_subject']"
                           />
      							@if ($errors->has('your_subject'))
      								<x-message :attr="[
      											'classname' => 'frmerror', 
      											'message' => $errors->first('your_subject')]"
      								/>
      							@endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="your_comment" class="col-form-label">Questions<span class="errmsg">*</span></label>
                           <textarea id="your_comment" rows="4" cols="50" type="text" placeholder="Questions" class="form-control" name="your_comment">{{ old('your_comment') }}</textarea>
                           <x-message :attr="[
                              'classname' => 'frmerror', 
                              'message' => '',
                              'mid' => 'error_your_comment']"
                           />
      							@if ($errors->has('your_comment'))
      								<x-message :attr="[
      											'classname' => 'frmerror', 
      											'message' => $errors->first('your_comment')]"
      								/>
      							@endif
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
							  <x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnContactUsReset"/>	
                              <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnContactUs"/>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-4 col-sm-12">
               <div class="contact-frame">
                  <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.3807549476637!2d-73.93344028494103!3d40.75364957932731!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25f2c1426ce29%3A0x7bbfc35d1cbb2ea5!2s31-17%2038th%20Ave%2C%20Long%20Island%20City%2C%20NY%2011101%2C%20USA!5e0!3m2!1sen!2sin!4v1595478297611!5m2!1sen!2sin"></iframe> 
                  <br>
                  <small>
                  <a href="https://www.google.com/maps/place/31-17+38th+Ave,+Long+Island+City,+NY+11101,+USA/@40.75365,-73.931252,16z/data=!4m5!3m4!1s0x89c25f2c1426ce29:0x7bbfc35d1cbb2ea5!8m2!3d40.7536496!4d-73.9312516?hl=en" target="_blank">View Larger Map</a>
                  </small>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Content Part End Here -->
</main>
@endsection
