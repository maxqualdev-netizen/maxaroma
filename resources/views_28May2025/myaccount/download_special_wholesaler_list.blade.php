@extends('layouts.app')
@section('content')

<main id="wholesaleproducts">
   <div class="myact">
      <div class="container">
         <div class="row row10">
            <div class="col-lg-3 col-md-12 col-xs-12">
               @include('myaccount.myaccountmenu')
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12">
               <div class="myact_hd">
                  <svg class="sv_spp_list" aria-hidden="true" role="img">
                     <use href="#sv_spp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_spp_list"></use>
                  </svg>
                  <h2>Download Special Wholesaler Product Pricelist</h2>           
               </div>
			   
			   <div>
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
			   </div>
               
				<div class="row">
					
					<div class="showDwnMessage" style="display:none;">Please wait.. Your data file is being downloading..</div>
					
				   <div class="col-md-12 pt-3">
						<div class="text-center">
							<x-button link="{{config('global.SITE_URL')}}download_specialwholesaleproductlist" btntext="Download Price List" classname="btn btn-primary setdwnLink" bid="btnwholesaleproduct11"/>
						</div>     
				   </div>  
				</div>
            </div>
         </div>
      </div>
   </div>
</main>
<div id="alertmodalpopup" href="#" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true">
	</div>
@endsection

