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
					   <svg class="sv_id_orders" aria-hidden="true" role="img">
					      <use href="#sv_id_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_id_orders"></use>
					   </svg>
					   <h2>Import Dropshipper Orders</h2>
					</div>
				   <div class="row col-md-12">
						@if (Session::has('success'))
							<div class="col-md-12"><x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/></div>
						@endif
						@if (Session::has('error'))
							<!-- <x-message :attr="[
										'classname' => 'frmerror', 
										'message' => Session::get('error')]"
							/> -->
							<div class="pt-1 d-block frmerror frmerror_shw col-md-12">{!! Session::get('error') !!}</div>
						@endif
						@if($OutOfStockMsg!='')
							<div class="pt-1 d-block frmerror frmerror_shw col-md-12">{!! $OutOfStockMsg !!} </div>
						@endif
						@if($HalfOutOfStockMsg!='')
							<div class="pt-1 d-block frmerror frmerror_shw col-md-12">{!! $HalfOutOfStockMsg !!} </div>
						@endif
				   </div>
					<div class="myre_points import_drop_order">
						@if($under_maintanance == '1')
						<div class="you_points text-center">
							<h1>Under Maintenance</h1>
						</div>
						@else
					   <div class="you_points text-center">
					      <svg class="sv_id_orders" aria-hidden="true" role="img">
					         <use href="#sv_id_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_id_orders"></use>
					      </svg>
					      <h2>Browse Order CSV File</h2>
					      <form action="{{ url('import-order-csv.html') }}" method="post" id="frmImportOrder" enctype="multipart/form-data">
								<input type="hidden" name="action" value="submit"/>
								{{ csrf_field() }}
								<input type="file" class="form-control" placeholder="No file Chosen" name="fileupload" id="fileupload" />
								<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => '',
									'mid' => 'error_fileupload']"
								/>
								@if ($errors->has('fileupload'))
									<x-message :attr="[
												'classname' => 'frmerror frmerror_shw', 
												'message' => $errors->first('fileupload')]"
									/>
								@endif
								<!-- <a href="#" class="dcsv_file">Download Sample CSV File</a>
								<a href="#" class="btn btn-primary d-block">Submit</a> -->
								<!-- <x-button link="{{ config('global.SITE_URL') }}sample_order_csv.csv" btntext="Download Sample CSV File" classname="dcsv_file" bid="btnDownloadCSV"/> -->
								<x-button link="{{config('global.SITE_URL')}}public/csv/sample_order_csv.csv" btntext="Download Sample CSV File" classname="dcsv_file" bid="btnDownloadCSV"/>
								<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary d-block" bid="btnImportOrder"/>
								<x-button link="javascript:void(0);" btntext="Reset" classname="btn btn-secondary d-block mt-3" bid="btnImportOrderReset"/>
					      </form>
					      <div class="md_list mt-2 pt-4 text-left">
					         <h4>Note</h4>
					         <ol class="com_clist">
					            <li>This section will allow you to upload bulk Order data using .CSV file only.</li>
					            <li>CSV file format must be same as sample CSV format</li>
					         </ol>
					      </div>
					   </div>
					   @endif
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
