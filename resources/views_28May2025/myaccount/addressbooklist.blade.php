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
				      <svg class="sv_address_book" aria-hidden="true" role="img">
				         <use href="#sv_address_book" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_address_book"></use>
				      </svg>
				      <h2>Address Book</h2>
				      <div class="myact_hd_search text-right"><a href="{{config('global.SITE_URL')}}addressbook/add.html" class="btn btn-secondary d-md-inline-block d-block">Add new address</a></div>
				   </div>

					@if (Session::has('success'))
				      <div class="col-md-12 text-center pt-3" id="success-message">
						<x-message :attr="[
									'classname' => 'frmsuccess', 
									'message' => Session::get('success')]"
						/>
					  </div>
					@endif
				      <div class="col-md-12 text-center pt-3" id="delete-success-message" style="display: none;">
						<!-- <div class="pt-1 d-block frmsuccess">{{ config('message.Addressbook.DeleteSuccess') }}</div> -->
						<x-message :attr="[
									'classname' => 'pt-1 d-block frmsuccess', 
									'message' => config('message.Addressbook.DeleteSuccess')]"
						/>
					  </div>
				   	  @if(count($address_book) > 0)
					      <div class="row pt-3">
						   	  @foreach($address_book as $address_book_key => $address_book_value)
						      <div class="col-md-6 pt-3" id="addressbook_block_{{$address_book_value->address_book_id}}">
						         <div class="myact_abbox">
						            <div class="add_box">
						               {{$address_book_value->first_name}} {{$address_book_value->last_name}}<br />
						               {{$address_book_value->address1}}<br />
						               @if($address_book_value->address2 !== '')
						               {{$address_book_value->address2}}<br />
						               @endif
						               {{$address_book_value->city}}<br />
						               {{$address_book_value->state}}<br />
						               {{$address_book_value->zip}}<br />
						               {{$address_book_value->country}}<br />
						               {{$address_book_value->phone}}
						               <div class="remove-div">
						                  <a href="{{config('global.SITE_URL')}}addressbook/edit/{{$address_book_value->address_book_id}}.html" class="">
						                     Edit
						                     <svg class="sv_new_edit vam" aria-hidden="true" role="img" width="20" height="20">
						                        <use href="#sv_new_edit" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_new_edit"></use>
						                     </svg>
						                  </a>
						                  <a href="javascript:void(0);" class="grcolor remove_address" data-id="{{$address_book_value->address_book_id}}">
						                     Delete
						                     <svg class="sv-trash vam" aria-hidden="true" role="img" width="20" height="20">
						                        <use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
						                     </svg>
						                  </a>
						               </div>
						            </div>
						         </div>
						      </div>
						      @endforeach
					      </div>
						  <div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
						  	{!! $address_book->onEachSide(0)->links('myaccount.pagination') !!}
						  </div>
				      @else
					   	  <div class="row pt-3">
						      <div class="col-md-12 text-center">
							      <span class="errmsg tac">No Record Found</span>
							  </div>
					      </div>
				      @endif
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection