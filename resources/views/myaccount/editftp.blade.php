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
				       <h2>Edit Order FTP</h2>
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
				                  <input type="text" id="dropshipper_ftp_password" name="dropshipper_ftp_password" placeholder="FTP Password" class="form-control" value="{{ old('dropshipper_ftp_password')?old('dropshipper_ftp_password'):$aCustomer->dropshipper_ftp_password}}"/>
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
				         <div class="text-right pt-3 row">
				         	<div class="col-12">
					         	<div class="float-right">
					         		<a href="javascript:void(0);" class="btn btn-primary" id="TestFTPVal">Test ftp connection</a> 
					         		<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary" bid="btnEditFtp"/>
					         		<div class="text-left pt-3">
						    			<div class="frmsuccess" role="alert" id="frmsuccess1"></div>
										<div class="frmerror" role="alert" id="frmerror1"></div>
									</div>
					         	</div>
					         </div>
				         </div>
				      </form>
				      <div class="md_list mt-5 text-left">
				         <h4><u>Notes:</u></h4>
				         <ol class="com_clist">
				            <li><b>FTP Type:</b> Choose whether your are using FTP or SFTP from the dropdown menu.<br>
								1.a.- FTP: implies <b>non-encrypted</b> transfer of files.<br>
								2.b.- SFTP: implies <b>encrypted</b> transfer of files.</li>
				            <li><b>FTP Host:</b> This is the direct link to access your FTP provider’s cloud.</li>
				            <li><b>FTP Username:</b> This is the email used to register your FTP account using your provider’s cloud.</li>
				            <li><b>FTP Password:</b> This is the password used to be granted access to your FTP account.</li>
				            <li><b> FTP Port Number:</b> Please select the corresponding channel according to the service you would like to use.<br>
								5.a.- For FTP, select "21". <br>
								5.b.- For SFTP, select "22".</li>
				            <li><b>File Path:</b> This is the direct link to access your folder inside the cloud of your FTP provider.</li>
				            <li><b>File Name Prefix:</b> This is the name of the file to be imported into your FTP folder without the extension (i.e. if the file name is “Neworders.csv”, there’s no need to put the extension “.csv”)(e.g. “Neworders”).</li>
				            <li><b>Order Pick Time:</b> If you don’t want to upload your orders manually, use this option to control the time when you want your orders to be uploaded with a 24 hour interval (e.g: if you select 08 AM, it will upload your orders every day at 08:00 AM) <br><br><b>IMPORTANCE NOTICE:</b> Please note that all orders coming before 01:00 PM EST will be processed on the same business day. However, every order coming after 01:00 PM EST will be processed on the next business day.
								<br><br><b>Order Pick up Folder : </b>Our system will pick up your orders everyday at a time that you specify - Make sure the file that you are uploading has the correct format. <a href="{{config('global.SITE_URL').'public/csv/sample_order_csv.csv'}}" style="text-decoration: underline !important"> Download template Here</a></li>
							<li><b>Enable FTP:</b> Select “Yes” if you want to enable this feature so that Maxaroma can access your FTP cloud and import orders.</li>
				         </ol>
				      </div>
				   </div>
				</div>


			</div>
		</div>
	</div>
</main>	

@endsection
