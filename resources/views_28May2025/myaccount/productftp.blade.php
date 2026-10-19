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
				      <h2>Edit Product FTP To Receive Feed</h2>
				   </div>
				   <div class="p-2 edit_ftp_box">
				      <form action="" method="post" id="frmProductFtp">
						<input type="hidden" name="action" value="update"/>
						<input type="hidden" name="eusertype" id="eusertype" value="{{ Session::get('eusertype') }}"/>
						{{ csrf_field() }}
						
						<div class="frmsuccess" role="alert" id="frmsuccess"></div>
						<div class="frmerror" role="alert" id="frmerror"></div>
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
				            <div class="col-md-4 pt-3">
				               <div class="field required">
									<x-selectbox :attr="[
										'label' => 'Receive Feed in Email ?',
										'id' => 'send_email_feed', 'name' => 'send_email_feed',
										'firstopt' => '',
										'classname' => 'form-control',
										'data' => ['Yes' => 'Yes', 'No' => 'No'],
										'selected' => (old('send_email_feed')?old('send_email_feed'):$aCustomer->send_email_feed)]"
									/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_send_email_feed']"
									/>
									@if ($errors->has('send_email_feed'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('send_email_feed')]"
										/>
									@endif
				               </div>
				            </div>
				            <div class="col-md-4 pt-3">
				               <div class="field required">
                                <x-selectbox :attr="[
                                    'label' => 'Receive New Arrival Product Feed in Email?',
                                    'id' => 'is_newarrival_email', 'name' => 'is_newarrival_email',
                                    'firstopt' => '',
                                    'classname' => 'form-control',
                                    'data' => ['Yes' => 'Yes', 'No' => 'No'],
                                    'selected' => (old('is_newarrival_email')?old('is_newarrival_email'):$aCustomer->is_newarrival_email)]"
                                />
                                <x-message :attr="[
                                    'classname' => 'frmerror', 
                                    'message' => '',
                                    'mid' => 'error_is_newarrival_email']"
                                />
                                @if ($errors->has('is_newarrival_email'))
                                    <x-message :attr="[
                                                'classname' => 'frmerror frmerror_shw', 
                                                'message' => $errors->first('is_newarrival_email')]"
                                    />
                                @endif
				               </div>
				            </div>
							<div class="col-md-4 pt-3">
								<div class="field required">
								 <x-selectbox :attr="[
									 'label' => 'Receive Price List in Email?',
									 'id' => 'is_pricelist_email', 'name' => 'is_pricelist_email',
									 'firstopt' => '',
									 'classname' => 'form-control',
									 'data' => ['Yes' => 'Yes', 'No' => 'No'],
									 'selected' => (old('is_pricelist_email')?old('is_pricelist_email'):$aCustomer->is_pricelist_email)]"
								 />
								 <x-message :attr="[
									 'classname' => 'frmerror', 
									 'message' => '',
									 'mid' => 'error_is_pricelist_email']"
								 />
								 @if ($errors->has('is_pricelist_email'))
									 <x-message :attr="[
												 'classname' => 'frmerror frmerror_shw', 
												 'message' => $errors->first('is_pricelist_email')]"
									 />
								 @endif
								</div>
							 </div>
				         </div>
						 <div class="row">
							<div class="col-md-12 pt-3 text-center">
								<span class="errmsg">*If set 'Yes' then Maxaroma will send the feed files in email to your account email address</span>
							</div>
							<div class="col-md-12 pt-3 text-center">
								<div class="main-heading">
									<h3><span style="font-size: 12px;">Or set the FTP details below to receive feed files in your FTP server.</span></h3>
								</div>								
							</div>
						 </div>
				         <div class="row">				            
				            
							<div class="col-md-6 pt-3">
								<div class="field required">
								 <x-selectbox :attr="[
									 'label' => 'FTP Type:',
									 'id' => 'ftp_type', 'name' => 'ftp_type',
									 'firstopt' => '',
									 'classname' => 'form-control',
									 'data' => ['ftp' => 'FTP', 'sftp' => 'SFTP'],
									 'selected' => (old('ftp_type')?old('ftp_type'):$aCustomer->ftp_type)]"
								 />
								 <x-message :attr="[
									 'classname' => 'frmerror', 
									 'message' => '',
									 'mid' => 'error_ftp_type']"
								 />
								 @if ($errors->has('ftp_type'))
									 <x-message :attr="[
												 'classname' => 'frmerror frmerror_shw', 
												 'message' => $errors->first('ftp_type')]"
									 />
								 @endif
								</div>
							 </div>
							 <div class="col-md-6 pt-3">
								<div class="field required">
								   <label for="ftp_host">FTP Host :</label>
								   <input type="text" id="ftp_host" name="ftp_host" placeholder="FTP Host" class="form-control" value="{{ old('ftp_host')?old('ftp_host'):$aCustomer->ftp_host}}" />
									 <x-message :attr="[
										 'classname' => 'frmerror', 
										 'message' => '',
										 'mid' => 'error_ftp_host']"
									 />
									 @if ($errors->has('ftp_host'))
										 <x-message :attr="[
													 'classname' => 'frmerror frmerror_shw', 
													 'message' => $errors->first('ftp_host')]"
										 />
									 @endif
								</div>
							 </div>
				         </div>
				         <div class="row">
							<div class="col-md-6 pt-3">
                                <div class="field required">
                                   <label for="ftp_username">FTP Username</label>
                                   <input type="name" id="ftp_username" name="ftp_username" placeholder="FTP Username" class="form-control" value="{{ old('ftp_username')?old('ftp_username'):$aCustomer->ftp_username}}"/>
                                     <x-message :attr="[
                                         'classname' => 'frmerror', 
                                         'message' => '',
                                         'mid' => 'error_ftp_username']"
                                     />
                                     @if ($errors->has('ftp_username'))
                                         <x-message :attr="[
                                                     'classname' => 'frmerror frmerror_shw', 
                                                     'message' => $errors->first('ftp_username')]"
                                         />
                                     @endif
                                </div>
                             </div>
							 <div class="col-md-6 pt-3">
								<div class="field required">
								 <label for="ftp_password" >FTP Password</label>
								 <input type="text" id="ftp_password" name="ftp_password" placeholder="FTP Password" class="form-control" value="{{ old('ftp_password')?old('ftp_password'):$aCustomer->ftp_password}}"/>
								   <x-message :attr="[
									   'classname' => 'frmerror', 
									   'message' => '',
									   'mid' => 'error_ftp_password']"
								   />
								   @if ($errors->has('ftp_password'))
									   <x-message :attr="[
												   'classname' => 'frmerror frmerror_shw', 
												   'message' => $errors->first('ftp_password')]"
									   />
								   @endif
								</div>
							 </div>	
				         </div>
				         <div class="row">
							<div class="col-md-6 pt-3">
                                <div class="field required">
                                    <label for="ftp_port">FTP Port Number</label>
                                    <input type="number" id="ftp_port" name="ftp_port" placeholder="FTP Port Number" class="form-control" value="{{ old('ftp_port')?old('ftp_port'):$aCustomer->ftp_port}}" />
                                      <x-message :attr="[
                                          'classname' => 'frmerror', 
                                          'message' => '',
                                          'mid' => 'error_ftp_port']"
                                      />
                                      @if ($errors->has('ftp_port'))
                                          <x-message :attr="[
                                                      'classname' => 'frmerror frmerror_shw', 
                                                      'message' => $errors->first('ftp_port')]"
                                          />
                                      @endif
                                </div>
                             </div>	
							 <div class="col-md-6 pt-3">
								<div class="field required">
								 <label for="ftp_file_path" >FTP Path</label>
								   <input type="text" id="ftp_file_path" name="ftp_file_path" placeholder="File Path" class="form-control" value="{{ old('ftp_file_path')?old('ftp_file_path'):$aCustomer->ftp_file_path}}" />
									 <x-message :attr="[
										 'classname' => 'frmerror', 
										 'message' => '',
										 'mid' => 'error_ftp_file_path']"
									 />
									 @if ($errors->has('ftp_file_path'))
										 <x-message :attr="[
													 'classname' => 'frmerror frmerror_shw', 
													 'message' => $errors->first('ftp_file_path')]"
										 />
									 @endif
								</div>
							 </div>		            
				         </div>

                         <div class="row">
                            @php
							 $orderFeedLocation = [
				            						'once' => 'Once A Day',
				            						'twice' => 'Twice A Day',
				            						'4time' => 'Every 6 Hours'
				            					];
				            @endphp
				            <div class="col-md-6 pt-3">
				               <div class="field required">
				                  <x-selectbox :attr="[
											'label' => 'Product Feed Time',
											'id' => 'ftp_sendfeed', 'name' => 'ftp_sendfeed',
											'firstopt' => '',
											'classname' => 'form-control',
											'data' => $orderFeedLocation,
											'selected' => (old('ftp_sendfeed')?old('ftp_sendfeed'):$aCustomer->ftp_sendfeed)]"
										/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_ftp_sendfeed']"
									/>
									@if ($errors->has('ftp_sendfeed'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('ftp_sendfeed')]"
										/>
									@endif
				               </div>
				            </div>	
				            
							<div class="col-md-6 pt-3">
								<div class="field required">
								 <x-selectbox :attr="[
									 'label' => 'Enable FTP:',
									 'id' => 'ftp_flag', 'name' => 'ftp_flag',
									 'firstopt' => '',
									 'classname' => 'form-control',
									 'data' => ['Yes' => 'Yes', 'No' => 'No'],
									 'selected' => (old('ftp_flag')?old('ftp_flag'):$aCustomer->ftp_flag)]"
								 />
								 <x-message :attr="[
									 'classname' => 'frmerror', 
									 'message' => '',
									 'mid' => 'error_ftp_flag']"
								 />
								 @if ($errors->has('ftp_flag'))
									 <x-message :attr="[
												 'classname' => 'frmerror frmerror_shw', 
												 'message' => $errors->first('ftp_flag')]"
									 />
								 @endif
								</div>
							 </div>
										            
				         </div>
				         
				         <div class="text-right pt-3 row">
				         	<div class="col-12">
					         	<div class="float-right">
					         		<a href="javascript:void(0);" class="btn btn-primary" id="TestProductFTPVal">Test ftp connection</a> 
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
				            {{--<li><b>File Name Prefix:</b> This is the name of the file to be imported into your FTP folder without the extension (i.e. if the file name is “Newinvetory.csv”, there’s no need to put the extension “.csv”)(e.g. “Newinventory”).</li>
				             <li><b>Order Pick Time:</b> If you don’t want to upload your orders manually, use this option to control the time when you want your orders to be uploaded with a 24 hour interval (e.g: if you select 08 AM, it will upload your orders every day at 08:00 AM) <br><br><b>IMPORTANCE NOTICE:</b> Please note that all orders coming before 01:00 PM EST will be processed on the same business day. However, every order coming after 01:00 PM EST will be processed on the next business day.</li> --}}
							<li><b>Enable FTP:</b> Select “Yes” if you want to enable this feature so that Maxaroma can access your FTP cloud and import our inventory.</li>
				         </ol>
				      </div>
				   </div>
				</div>


			</div>
		</div>
	</div>
</main>	

@endsection
