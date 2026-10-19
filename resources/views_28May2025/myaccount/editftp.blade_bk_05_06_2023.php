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
				      <svg class="sv_edit_ftp" aria-hidden="true" role="img">
				         <use href="#sv_edit_ftp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_edit_ftp"></use>
				      </svg>
				      <h2>Edit FTP</h2>
				   </div>
				   <div class="p-2 edit_ftp_box">
				      <form action="" method="post" id="frmEditFtp">
						<input type="hidden" name="action" value="update"/>
						<input type="hidden" name="eusertype" id="eusertype" value="{{ Session::get('eusertype') }}"/>
						{{ csrf_field() }}
						@if (Session::has('success'))
							<x-message :attr="[
										'classname' => 'frmsuccess', 
										'message' => Session::get('success')]"
							/>
						@endif
						@if (Session::has('error'))
							<x-message :attr="[
										'classname' => 'frmerror frmerror_shw', 
										'message' => Session::get('error')]"
							/>
						@endif
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
									<x-selectbox :attr="[
										'label' => 'FTP Type',
										'id' => 'dropshipper_ftp_type', 'name' => 'dropshipper_ftp_type',
										'firstopt' => '',
										'classname' => 'form-control',
										'data' => ['ftp' => 'FTP', 'sftp' => 'SFTP'],
										'selected' => (old('dropshipper_ftp_type')?old('dropshipper_ftp_type'):$aCustomer->dropshipper_ftp_type)]"
									/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_type']"
									/>
									@if ($errors->has('dropshipper_ftp_type'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_type')]"
										/>
									@endif
				               </div>
				            </div>
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_host" >FTP Host</label>
				                  <input type="text" id="dropshipper_ftp_host" name="dropshipper_ftp_host" placeholder="FTP Host" class="form-control" value="{{ old('dropshipper_ftp_host')?old('dropshipper_ftp_host'):$aCustomer->dropshipper_ftp_host}}"/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_host']"
									/>
									@if ($errors->has('dropshipper_ftp_host'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_host')]"
										/>
									@endif
				               </div>
				            </div>
				         </div>
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_username" >FTP Username</label>
				                  <input type="name" id="dropshipper_ftp_username" name="dropshipper_ftp_username" placeholder="FTP Username" class="form-control" value="{{ old('dropshipper_ftp_username')?old('dropshipper_ftp_username'):$aCustomer->dropshipper_ftp_username}}"/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_username']"
									/>
									@if ($errors->has('dropshipper_ftp_username'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_username')]"
										/>
									@endif
				               </div>
				            </div>
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_password" >FTP Password</label>
				                  <input type="password" id="dropshipper_ftp_password" name="dropshipper_ftp_password" placeholder="FTP Password" class="form-control" value="{{ old('dropshipper_ftp_password')?old('dropshipper_ftp_password'):$aCustomer->dropshipper_ftp_password}}"/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_password']"
									/>
									@if ($errors->has('dropshipper_ftp_password'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_password')]"
										/>
									@endif
				               </div>
				            </div>
				         </div>
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_port" >FTP Port Number</label>
				                  <input type="number" id="dropshipper_ftp_port" name="dropshipper_ftp_port" placeholder="FTP Port Number" class="form-control" value="{{ old('dropshipper_ftp_port')?old('dropshipper_ftp_port'):$aCustomer->dropshipper_ftp_port}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_port']"
									/>
									@if ($errors->has('dropshipper_ftp_port'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_port')]"
										/>
									@endif
				               </div>
				            </div>
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_file_path" >File Path</label>
				                  <input type="text" id="dropshipper_ftp_file_path" name="dropshipper_ftp_file_path" placeholder="File Path" class="form-control" value="{{ old('dropshipper_ftp_file_path')?old('dropshipper_ftp_file_path'):$aCustomer->dropshipper_ftp_file_path}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_file_path']"
									/>
									@if ($errors->has('dropshipper_ftp_file_path'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_file_path')]"
										/>
									@endif
				               </div>
				            </div>
				         </div>
				         <div class="row">
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <label for="dropshipper_ftp_file_name" >File Name Prefix</label>
				                  <input type="text" id="dropshipper_ftp_file_name" name="dropshipper_ftp_file_name" placeholder="File Name Prefix" class="form-control" value="{{ old('dropshipper_ftp_file_name')?old('dropshipper_ftp_file_name'):$aCustomer->dropshipper_ftp_file_name}}" />
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_file_name']"
									/>
									@if ($errors->has('dropshipper_ftp_file_name'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_file_name')]"
										/>
									@endif
				               </div>
				            </div>
				            @php
				            	$orderPickTime = [
				            						'00' => '12 AM',
				            						'01' => '01 AM',
				            						'02' => '02 AM',
				            						'03' => '03 AM',
				            						'04' => '04 AM',
				            						'05' => '05 AM',
				            						'06' => '06 AM',
				            						'07' => '07 AM',
				            						'08' => '08 AM',
				            						'09' => '09 AM',
				            						'10' => '10 AM',
				            						'11' => '11 AM',
				            						'12' => '12 PM',
				            						'13' => '01 PM',
				            						'14' => '02 PM',
				            						'15' => '03 PM',
				            						'16' => '04 PM',
				            						'17' => '05 PM',
				            						'18' => '06 PM',
				            						'19' => '07 PM',
				            						'20' => '08 PM',
				            						'21' => '09 PM',
				            						'22' => '10 PM',
				            						'23' => '11 PM'
				            					];
				            @endphp
				            <div class="col-md-6">
				               <div class="row row10">
				                  <div class="col-sm-6 pt-3">
				                     <div class="field required">
										<x-selectbox :attr="[
											'label' => 'Order Pick Time',
											'id' => 'dropshipper_ftp_picktime_order', 'name' => 'dropshipper_ftp_picktime_order',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $orderPickTime,
											'selected' => (old('dropshipper_ftp_picktime_order')?old('dropshipper_ftp_picktime_order'):$aCustomer->dropshipper_ftp_picktime_order)]"
										/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_picktime_order']"
									/>
									@if ($errors->has('dropshipper_ftp_picktime_order'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_picktime_order')]"
										/>
									@endif
				                     </div>
				                  </div>
				                  <div class="col-sm-6 pt-3">
				                     <div class="field required">
										<x-selectbox :attr="[
											'label' => 'Enable FTP',
											'id' => 'dropshipper_ftp_flag', 'name' => 'dropshipper_ftp_flag',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => ['No' => 'No', 'Yes' => 'Yes'],
											'selected' => (old('dropshipper_ftp_flag')?old('dropshipper_ftp_flag'):$aCustomer->dropshipper_ftp_flag)]"
										/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_dropshipper_ftp_flag']"
									/>
									@if ($errors->has('dropshipper_ftp_flag'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('dropshipper_ftp_flag')]"
										/>
									@endif
				                     </div>
				                  </div>
				               </div>
				            </div>
				         </div>
				         <div class="text-right pt-3">
				         	<!-- <a href="#" class="btn btn-primary">Submit</a> -->
							<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnEditFtp"/>
				         </div>
				      </form>
				      <div class="md_list mt-2 pt-4 text-left">
				         <h4>Note</h4>
				         <ol class="com_clist">
				            <li>FTP file path should always follow trailing slash (e.g. /var/www/tmp/csv_path/)</li>
				            <li>File name should contain the prefix name of csv file without its extension i.e.importorder.</li>
				            <li>File must be in CSV format. Format for the CSV file will be same as format of import functionality file. Please check sample file from here.</li>
				            <li>FTP port number - for FTP (21) and for SFTP (22)</li>
				            <li>Pick time is the clock time in 12 hour format. You need to set time on the base of 12 hour format. If you have selected 1 PM as a pick time than our scheduled job will take file on 1 PM so, you will need to upload your file on the server before 1.00 PM</li>
				            <li>Enable FTP must be set to yes if you want to use this functionality</li>
				            <li>Upload order related file on FTP server at the given path</li>
				            <li>If any errors in csv file or data in it, you will receive emails regarding errors. So you need to change CSV based on errors. Next time on the same hours, which you have set as a pick hour the file will be processed.</li>
				            <li>If you want to upload if before next day, you can manually upload the file from the import dropshipper orders functionality.</li>
				         </ol>
				      </div>
				   </div>
				</div>


			</div>
		</div>
	</div>
</main>	

@endsection