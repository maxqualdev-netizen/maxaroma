@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Sales Tax Exemption Main Content">
    <section class="smallcon" aria-labelledby="sales-tax-exemption-heading">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="" method="post" name="frmsalesTaxExemption" id="frmsalesTaxExemption" enctype="multipart/form-data" aria-labelledby="sales-tax-exemption-heading" >
                        <input type="hidden" name="action" value="submitExemptionForm" />
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1 col-md-12 myact">
                                <h2 id="sales-tax-exemption-heading" class="sr-only">Sales Tax Exemption</h2>
                                <h1>
									<strong>Buyer Exemption program for sales tax</strong>
								</h1>
								
                                <p class="pt-3">
									If you are a tax-exempt buyer, we have a buyer exemption system that allows you to submit sales tax exemption certificates to eBay and make purchases without paying sales tax.
								</p>
								<p class="pt-3">
									Note: The documentation required for tax exemption can vary from jurisdiction to jurisdiction.
								</p>
								<p class="pt-3">
									To upload your documentation, use the <strong>Upload documents section</strong> below.
								</p>
								
                                <div class="pt-3 text-right fw500"><span class="errmsg" aria-hidden="true">*</span>Required Fields</div>
                                <div class="row" role="alert" aria-live="polite">
                                    @if (count($errors) > 0)
                                    @endif
                                    @if (Session::has('success'))
                                        <div class="col-md-12">
                                            <x-message :attr="[ 'classname' => 'frmsuccess', 'message' => Session::get('success'), 'role' => 'status', 'aria-live' => 'polite' ]" />
                                        </div>
                                    @endif
                                </div>
                                
                                
                                <div class="row">
                                    <div class="col-md-6 pt-3">
										<div class="field required">
											<label for="first_name">First Name <span class="sr-only">(required)</span></label>
											<input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" value="{{ old('first_name') }}" aria-required="true" aria-describedby="error_first_name">
											<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_first_name', 'role' => 'alert' ]" />
											@if ($errors->has('first_name'))
												<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('first_name'), 'role' => 'alert' ]" />
											@endif
										</div>
									</div>
									<div class="col-md-6 pt-3">
										<div class="field required">
											<label for="last_name">Last Name <span class="sr-only">(required)</span></label>
											<input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" value="{{ old('last_name') }}" aria-required="true" aria-describedby="error_last_name">
											<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_last_name', 'role' => 'alert' ]" />
											@if ($errors->has('last_name'))
												<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('last_name'), 'role' => 'alert' ]" />
											@endif
										</div>
									</div>
								</div>
								
                                <div class="row">
                                    <div class="col-md-6 pt-3">
                                        <div class="field required">
                                            <label for="email_id">Email Address <span class="sr-only">(required)</span></label>
                                            <input type="email" id="email_id" name="email_id" placeholder="Email Address" class="form-control" value="{{ old('email_id') }}" aria-required="true" aria-describedby="error_email_id">
                                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_email_id', 'role' => 'alert' ]" />
                                            @if ($errors->has('email_id'))
                                                <x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('email_id'), 'role' => 'alert' ]" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 pt-3">
										<div class="field required">
											<label for="state_for_exemption">State for exemption <span class="sr-only">(required)</span></label>
											<input type="text" id="state_for_exemption" name="state_for_exemption" placeholder="State for exemption" class="form-control" value="{{ old('state_for_exemption') }}" aria-required="true" aria-describedby="error_state_for_exemption">
											<x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_state_for_exemption', 'role' => 'alert' ]" />
											@if ($errors->has('state_for_exemption'))
												<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('state_for_exemption'), 'role' => 'alert' ]" />
											@endif
										</div>
									</div>
                                </div>
                                
                                <div class="row">
									<div class="col-md-12 pt-3">
                                        <div class="field required">
                                            <label for="upload_sales_tax">Upload Sales Tax Exemption Form</label>
                                            <input type="file" id="upload_sales_tax" name="upload_sales_tax" class="browinput form-control" aria-describedby="upload_sales_tax_help" />
                                            <span id="upload_sales_tax_help" class="sr-only">Upload your sales tax exemption form here</span>
                                            <x-message :attr="[ 'classname' => 'frmerror', 'message' => '', 'mid' => 'error_upload_sales_tax', 'role' => 'alert' ]" />
											@if ($errors->has('state_for_exemption'))
												<x-message :attr="[ 'classname' => 'frmerror frmerror_shw', 'message' => $errors->first('upload_sales_tax'), 'role' => 'alert' ]" />
											@endif
                                        </div>
                                    </div>
								</div>
                                
                                <div class="text-right pt-3" role="group" aria-label="Form Actions">
                                   
                                    <x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnSalesTaxExemptionReset" aria-label="Reset Form" />
                                    <x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnSalesTaxExemption" aria-label="Submit Form" />
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
