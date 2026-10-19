   <div class="vertical-alignment-helper">
	  <div class="modal-dialog vertical-align-center">
		 <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
			<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
			</svg> </a>
			<div class="modal-body">
			   <div class="friend-mail-modal">
				  <div class="modal-space">
					 <form name="form1" id="form1" method="post" action="javascript:void(0)">
						<input name="alert" value="yes" type="hidden">
						<input name="iproductid" value="{{$prod_detail[0]->products_id}}" type="hidden">
						<input name="imanufactureid" value="" type="hidden">
						<input name="vsku" value="{{$prod_detail[0]->sku}}" type="hidden">
						
						<div class="login">
						   <div class="row">
							  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								 <div class="spg-panel">
									<h3 class="alrtme">Alert Me</h3>
									<div class="row spg-body loginbody pb-sm-0 alertdiv">
										<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
											<div class="quickview-thumb">
												<img alt="{{$prod_detail[0]->product_name}}" title="{{$prod_detail[0]->product_name}}" src="{{$prod_detail[0]->image}}" id="QuickviewExtraImages">
											</div>
										</div>
										<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
											<h4 id="quickviewprd_nm" class="prname">{{$prod_detail[0]->product_name}}</h4>
											<div id="quickview_sort_descrition">
												 
											<div class="dscn">
												<strong>Description:</strong>
												<p>{{$prod_detail[0]->short_description}}</p>
												<p>{{$prod_detail[0]->product_description}}</p>
											</div>
										</div>
									</div>
									</div>
									
									 <div class="form-group row alrtmsgfrm">
										  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rqdfld"><span class="errmsg">*</span>Required Fields</div>
										  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 alrtemail">
											  <label for="lname">Email Address<span class="errmsg">*</span></label>
										  </div>
										  <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
											  <input name="alert_email" id="alert_email" class="form-control" placeholder="Email Address" autofocus="" type="text">
											  
										  </div>
										  
									  </div>
									  
									  <div class="row form-group alrtmsgfrm">
										<label class="col-lg-3 col-md-4 col-sm-4 col-xs-12">&nbsp;</label>
										<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 pt10">
											<a href="javascript:void(0);" id="sbmitalert" onclick="return submitform_alert();" class="btn btn-primary ttu btn-medium">Submit</a>
										</div>
									  </div>
									  <div class="row form-group alrtmsg" id="alrtmsg" style="display:none"></div>
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
   </div>
