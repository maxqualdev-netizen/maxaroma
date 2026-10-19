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
					   <svg class="sv_wishlist" aria-hidden="true" role="img">
					      <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
					   </svg>
					   <h2>My Wishlist</h2>
					</div>
					@if (Session::has('error'))
				      <div class="col-md-12 text-center pt-3">
						<x-message :attr="[
									'classname' => 'frmerror', 
									'message' => Session::get('error')]"
						/>
					  </div>
					@endif
					@if (Session::has('success'))
				      <div class="col-md-12 text-center pt-3">
						<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => Session::get('success')]"
						/>
					  </div>
					@endif
					<div class="pt-3">
					   @if($WishCatRS)
					   <div class="myact_subhd">{{ $WishCatRS->name }}</div>
					   @endif
		               <div class="myorder_dtl">
		                  <div id="Rptable">
	            			<form name="frmwish" id="frmwish" method="delete" action="">
							<input type="hidden" name="action" value="" />
							<!-- <input type="hidden" name="wishlist_category_id" value="{{$wishlist_category_id}}" /> -->
		                     <table class="text-left">
		                        <thead>
		                           <tr>
	                           		  @if(count($WishProdRS) > 0)
		                              <td>
									    <label class="checkbox-label">
										  <div class="chebox">
										  	<input type="checkbox" name="chk" value="1" id="checkAll">
										  	<span class="checkmark"></span>
										  </div>
									    </label>
									  </td>
									  @endif
									  <td>IMAGE</td>
		                              <td>PRODUCT NAME</td>
									  <td>DESCRIPTION</td>
		                           </tr>
		                        </thead>
		                        <tbody class="align-top">
	                       		   @if(count($WishProdRS) > 0)
		                       		   @foreach($WishProdRS as $wish_cate_key => $wish_cat_value)
			                           <tr>
			                              <td data-title="Select">
										    <label class="checkbox-label">
											  <div class="chebox">
											  	<input type="checkbox" id="WishId_{{$wish_cat_value['wishlist_id']}}" name="ch[]" value="{{$wish_cat_value['wishlist_id']}}" class="noborder">
											  	<span class="checkmark"></span>
											  </div>
										    </label>
										  </td>
										  <td data-title="IMAGE">
										  	<a href="{{$wish_cat_value['p_link']}}"  title="{{$wish_cat_value['product_name']}}"> <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$wish_cat_value['thumb_image']}}" alt="{{$wish_cat_value['product_name']}}" title="{{$wish_cat_value['product_name']}}" width="90" /></a>
										  </td>
			                              <td data-title="PRODUCT NAME">
			                              	<div class="pb5">
			                              		<a href="{{$wish_cat_value['p_link']}}"  title="{{$wish_cat_value['product_name']}}">{{$wish_cat_value['product_name']}}</a>
			                              	</div>
						                    <div class="pb5">
						                    	<strong>Item SKU :</strong> {{$wish_cat_value['sku']}}
						                    </div>
						                  </td>
			                              <td data-title="DESCRIPTION">{{remove_html_entities($wish_cat_value['short_description'])}}</td>
			                           </tr>
			                           @endforeach
		                           @else
			                           <tr class="text-center">
			                           		<td colspan="5" class="errmsg tac">No Record Found</td>
			                           </tr>
		                           @endif						   
								</tbody>
		                     </table>
		                 	</form>
		                 	
							@if(count($WishProdRS) > 0)
								<div class="right pt-3">
									<x-button link="javascript:void(0);" btntext="Delete" classname="btn btn-primary" bid="btnDeleteWishProduct"/>
								</div>
							@endif
		                  </div>
		               </div>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
