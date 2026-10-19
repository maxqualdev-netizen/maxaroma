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
	               <div class="myact_hd"> <svg class="sv_details" aria-hidden="true" role="img">
	                  <use href="#sv_details" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_details"></use>
	                  </svg>
	                  <h2>Download Tracking Numbers</h2>
	               </div>
					<div class="col-md-12 text-center">
						@if (Session::has('error'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('error')]"
							/>
						@endif
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
					</div>
	               <div class="p-2">
					<form name="frmExportOrder" action="{{ url('export-order-csv.html') }}" method="post" id="frmExportOrder" enctype="multipart/form-data">
						<input type="hidden" name="action" value="search"/>
						{{ csrf_field() }}
	                  <div class="row">
	                     <div class="col-md-6 pt-3">
	                        <div class="field">
	                           <label for="From" >From</label>
	                           <input type="date" id="d_start_date" name="d_start_date" placeholder="From" class="form-control" value="{{ $d_start_date_disp }}" />
								<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => '',
									'mid' => 'error_d_start_date']"
								/>
								@if($errors->has('d_start_date'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('d_start_date')]"
									/>
								@endif
	                        </div>
	                     </div>
	                     <div class="col-md-6 pt-3">
	                        <div class="field">
	                           <label for="To" >To</label>
	                           <input type="date" id="d_end_date" name="d_end_date" placeholder="To" class="form-control" value="{{ $d_end_date_disp }}" />
								<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => '',
									'mid' => 'error_d_end_date']"
								/>
								@if($errors->has('d_end_date'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('d_end_date')]"
									/>
								@endif
	                        </div>
	                     </div>
	                  </div>
	                  <div class="text-right pt-3">
						<x-button link="javascript:void(0);" btntext="Search" classname="btn btn-primary" bid="btnSearch"/>
						<x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary" bid="btnExportOrderReset"/>
	                  </div>
	               </form>
	               </div>
	            </div>
			</div>
		</div>
	</div>
</main>	

@endsection