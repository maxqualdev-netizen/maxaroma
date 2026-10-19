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
	                  	<h2>Wishlist</h2>
	               </div>
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
	               <div class="myorder_dtl">
	                  <div id="Rptable">
            			<form name="frmwish" id="frmwish" method="delete" action="">
		                <input type="hidden" name="action" value="DeleteCat" />
	                     <table class="text-left">
	                        <thead>
	                           <tr>
                           		  @if(count($WishCatRS) > 0)
	                              <td class="hide-td">
								    <label class="checkbox-label">
									  <div class="chebox">
									  	<input type="checkbox" name="chk" value="1" id="checkAll">
									  	<span class="checkmark"></span>
									  </div>
								    </label>
								  </td>
								  @endif
								  <td>Category Name</td>
	                              <td>Description</td>
								  <td>Edit</td>
	                              <td>View Products</td>
	                           </tr>
	                        </thead>
	                        <tbody class="align-top">
                       		   @if(count($WishCatRS) > 0)
	                       		   @foreach($WishCatRS as $wish_cate_key => $wish_cat_value)
		                           <tr>
		                              <td data-title="Select">
									    <label class="checkbox-label">
										  <div class="chebox">
										  	<!-- <input type="checkbox"> -->
										  	<input type="checkbox" id="WishId_{{$wish_cat_value->wishlist_category_id}}" name="ch[]" value="{{$wish_cat_value->wishlist_category_id}}" class="noborder">
										  	<span class="checkmark"></span>
										  </div>
									    </label>
									  </td>
									  <td data-title="Category Name">{{remove_html_entities($wish_cat_value->name)}}</td>
		                              <td data-title="Description">{{remove_html_entities($wish_cat_value->description)}}</td>
		                              <td data-title="Edit"><a href="{{ config('global.SITE_URL') }}wish-category/{{$wish_cat_value->wishlist_category_id}}.html" class="">Edit</a></td>
									  <td data-title="View Products"><a href="{{ config('global.SITE_URL') }}wish-product/{{$wish_cat_value->wishlist_category_id}}.html" class="">View</a></td>
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

	                 	@php $paginationStyle = (count($WishCatRS) <= 10) ? 'style="display:none;"' : '' @endphp
						<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination" {!! $paginationStyle !!}>
							{!! $WishCatRS->onEachSide(0)->links('myaccount.pagination') !!}
						</div>

						@if(count($WishCatRS) > 0)
							<div class="right pt-3">
								<x-button link="javascript:void(0);" btntext="Delete" classname="btn btn-primary" bid="btnDeleteWishCategory"/>
							</div>
						@endif
	                  </div>
	               </div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
