@extends('layouts.app')
@section('content')
<main>
   <!-- Content Part Start Here -->
   <section class="breadcrumb">
      <div class="container">
         <a href="#">Home</a>
         <span class="active">Don't See it Request It</span>
      </div>
   </section>
   <div class="container">
      <div class="static-inner">
         <div class="row">
            <div class="col-12">
               <div class="mainhd">
                  <h1>Don't See It! Request It!</h1>
               </div>
            </div>
            <div class="col-12">
               <p class="text-center">If you don't see what you are looking for, fill this form and we will get it for you. We will inform you once it become available.</p>
            </div>
         </div>
         <div class="row">
            @if (Session::has('success'))
               <div class="col-md-12 text-center pt-3">
               <x-message :attr="[
                        'classname' => 'frmsuccess', 
                        'message' => Session::get('success')]"
               />
              </div>
            @endif
            @if (Session::has('error'))
               <div class="col-md-12 text-center pt-3">
               <x-message :attr="[
                        'classname' => 'frmerror frmerror_shw', 
                        'message' => Session::get('error')]"
               />
              </div>
            @endif
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 pt-4">
               <form action="" id="frmDontSeeIt">
                  {{ csrf_field() }}
                  <input type="hidden" name="action" value="submit"/>
                  <div class="row">
                     <div class="pb-3 col-12 text-right fw500"><span class="errmsg">*</span>Required Fields</div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="custname" class="col-form-label">Name<span class="errmsg">*</span></label>
                           <input id="custname" name="custname" type="text" placeholder="Name" class="form-control" autofocus="" value="{{ old('custname') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_custname']"
                           />
                           @if ($errors->has('custname'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('custname')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="email" class="col-form-label">Your Email Address<span class="errmsg">*</span></label>
                           <input id="email" name="email" type="email" placeholder="Your Email Address" class="form-control" value="{{ old('email') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_email']"
                           />
                           @if ($errors->has('email'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('email')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="product_price" class="col-form-label">Price<span class="errmsg">*</span></label>
                           <input id="product_price" name="product_price" type="text" placeholder="Price" class="form-control" autofocus="" value="{{ old('product_price') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_product_price']"
                           />
                           @if ($errors->has('product_price'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('product_price')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="product_qty" class="col-form-label">Quantity<span class="errmsg">*</span></label>
                           <input id="product_qty" name="product_qty" type="text" placeholder="Quantity" class="form-control" value="{{ old('product_qty') }}">
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_product_qty']"
                           />
                           @if ($errors->has('product_qty'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('product_qty')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="comments" class="col-form-label">Product<span class="errmsg">*</span></label>
                           <textarea id="comments" name="comments" rows="4" cols="50" placeholder="Product" class="form-control">{{ old('comments') }}</textarea>
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_comments']"
                           />
                           @if ($errors->has('comments'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('comments')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="delivery_date" class="col-form-label">Delivery Date<span class="errmsg">*</span></label>
                           <input id="delivery_date" name="delivery_date"  data-date="" data-date-format="MM/DD/YYYY" type="Date" placeholder="MM-DD-YYYY" class="form-control input-date" value="">
                           <x-message :attr="[
                              'classname' => 'frmerror ', 
                              'message' => '',
                              'mid' => 'error_delivery_date']"
                           />
                           @if ($errors->has('delivery_date'))
                              <x-message :attr="[
                                 'classname' => 'frmerror frmerror_shw', 
                                 'message' => $errors->first('delivery_date')
                                 ]"
                              />
                           @endif
                        </div>
                     </div>
                     <!-- <div class="col-12">
                        <div class="form-group">
                           <label for="productname" class="col-form-label">Product<span class="errmsg">*</span></label>
                           <textarea id="productname" rows="4" cols="50" type="text" placeholder="Product" class="form-control"></textarea>
                        </div>
                     </div> -->
                     <div class="col-md-6">
                        <div class="form-group">
                              @include('googlecaptcha')
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-actions clearfix pt-0">
                           <div class="float-right">
                              <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnSubmitDontSeeIt"/>
                              <x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnResetDontSeeIt"/>
                              <!-- <a href="#" class="btn btn-primary">Submit</a>
                              <a href="#" class="btn btn-secondary">Reset</a>  -->
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