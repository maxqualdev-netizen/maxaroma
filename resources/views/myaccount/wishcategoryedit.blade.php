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
									'classname' => 'frmerror frmerror_shw', 
									'message' => Session::get('error')]"
						/>
					  </div>
					@endif
					<div class="myact_cpword">
              			<form id="frmWishCategoryEdit" method="post" action="">
		                <input type="hidden" name="action" value="EditCat" />
		                <input type="hidden" name="wishcatid" value="{{$WishCat->wishlist_category_id}}" class="input" />
						 {{ csrf_field() }}
					      <div class="row">
					         <div class="col-md-7 col-md-offset-2">
					            <div class="field required mt-3">
					               <label for="name" >Category Name</label>
					               <input type="text" id="name" name="name" placeholder="Category Name" class="form-control" value="{{ old('name') ? old('name') : $WishCat->name }}"/>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_name']"
									/>
									@if ($errors->has('name'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('name')]"
										/>
									@endif
					            </div>
					            <div class="field required mt-3">
					               <label for="description" >Description</label>
					               <textarea id="description" name="description" placeholder="Description" class="form-control" >{{ old('description') ? old('description') : $WishCat->description }}</textarea>
									<x-message :attr="[
										'classname' => 'frmerror', 
										'message' => '',
										'mid' => 'error_description']"
									/>
									@if ($errors->has('description'))
										<x-message :attr="[
													'classname' => 'frmerror frmerror_shw', 
													'message' => $errors->first('description')]"
										/>
									@endif
					            </div>
					            <div class="pt-3">
					            	<!-- <a href="#" class="btn btn-primary btn-block">Submit</a> -->
									<x-button link="javascript:void(0);" btntext="Submit" classname="btn btn-primary btn-block" bid="btnEditWishCategory"/>
								</div>
					         </div>
					      </div>
					   	</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection