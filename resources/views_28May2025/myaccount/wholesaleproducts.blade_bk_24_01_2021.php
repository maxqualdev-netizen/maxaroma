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
                  <h2>Special Product Price List</h2>
                  <div class="myact_hd_search">
                     <input type="search" placeholder="Search In-Stock Items by (UPC/SKU/Product Name)" class="form-control" name="search_keyword" id="search_keyword" value="{{$Search_Keyword}}">
                     <input type="hidden" name="prev_search_keyword" id="prev_search_keyword" value="{{$Search_Keyword}}">
                     <a href="#" class="search_icon">
                        <svg class="sv_search" aria-hidden="true" role="img">
                           <use href="#sv_search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_search"></use>
                        </svg>
                     </a>
					 <label class="checkbox-label">
						<div class="chebox">
							<input type="checkbox" id="all_items" value="yes" @if($SearchAll == 'Yes') checked @endif >Show all Items
							<span class="checkmark"></span>
						</div>
					</label>
					@if($Search_Keyword != '') 
						<div class="text-right pt-1">
							<x-button link="{{config('global.SITE_URL').'special-product-list'}}" btntext="Reset" classname="belue-link"/>
						</div> 
					@endif	
                  </div>
                  
               </div>
               
               
               <div class="pt-3">
				  @if(count($ProductArr) > 0)
				  {{--	  
                  <div class="text-right">
					<x-button link="javascript:void(0)" btntext="Add To Cart" classname="btn btn-primary btnwproduct" bid="btnwholesaleproduct"/>
				  </div>--}}         
                  @foreach($ProductArr as $prokey => $prodata)
					  <div class="special_ppl_box">
						 <div class="row row5">
							<div class="col-md-2 text-center"><img src="{{$prodata->image}}" alt="{{$prodata->product_name}}" title="{{$prodata->product_name}}"></div>
							<div class="col-md-4 pt-md-0 pt-2"> <a href="#"><strong>{{$prodata->product_name}}</strong></a><br>
							   <span class="edu d-block pb-2">{{$prodata->short_description}}</span> Item SKU : {{$prodata->sku}}<br>
							   UPC : {{$prodata->UPC}}<br>
							   <!-- Unit Price: {{$prodata->sku}} -->
							</div>
							<div class="col-md-2 pt-md-0 pt-2">
								
								@if(count($MarkupArr) > 0)
							    <table>
									@foreach($MarkupArr as $mkey => $mval)
										@php
											$wprice_obj = "wholesale_price_".$mkey;
										@endphp
										
										@if($prodata->$wprice_obj != "")
											<tr>
												<td>{{$mval->markup_lable}} :</td>
												<td> <strong>{{Price($prodata->$wprice_obj)}}</strong> </td>
											</tr>
										@endif
									@endforeach
									
									<tr>
										<td>Qty < 5 :</td>
										<td> <strong>{{Price($prodata->wholesale_price,true)}}</strong> </td>
									</tr>
							   </table>
							   @endif
							</div>
							
							<div class="col-md-2 text-center text-md-right pt-md-0 pt-2">
							   
								@if($prodata->stock == 'In')
								  <div class="input-group qty-input">
										<button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$prodata->products_id}}" data-product="{{$prodata->products_id}}">-</button>
										<input type="text" name="prodqty_{{$prodata->products_id}}" id="prodqty_{{$prodata->products_id}}" class="form-control input-number wprodqty" value="1" data-qty="1" min="1">
										<button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$prodata->products_id}}" data-product="{{$prodata->products_id}}">+</button>
								  </div>
								@else
								 <input type="hidden" name="Qty_{{$prodata->products_id}}" id="Qty_{{$prodata->products_id}}" class="form-control input-number"  value="">
								  <input id="SKU_{{$prodata->products_id}}" type="hidden" value="{{$prodata->sku}}" name="SKU_{{$prodata->products_id}}">
								  <input id="Spid_{{$prodata->products_id}}" type="hidden" value="{{$prodata->products_id}}" name="Spid[]">
								  <input type="hidden" value="{{$prodata->current_stock}}" name="avt_{{$prodata->products_id}}" id="avt_{{$prodata->products_id}}">
								  <a class="notify-link red" data-target="#alertmodalpopup" data-toggle="modal" href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodata->products_id}}','{{$prodata->sku}}');"> Notify Me</a> 
								@endif
							</div>
							<div class="col-md-2">
								@if($prodata->stock == 'In')
									<x-button link="javascript:void(0)" btntext="Add To Cart" classname="btn btn-primary wproduct" bid="{{$prodata->products_id}}"/>
								@endif
							</div>
						 </div>
					  </div>
					@endforeach
					<div id="page-spinner"></div>
					<div class="listing_grid"></div>
                   <div id="loadmore" @if($TotalProducts < $PerPage)style="display:none"@endif><a class="list-more d-block mt-4" data-page="1"> Load More... </a></div>
				   {{--<div class="text-right pt-3">
					<x-button link="javascript:void(0)" btntext="Add To Cart" classname="btn btn-primary btnwproduct" bid="btnwholesaleproduct"/>
				   </div>--}}
				   @else
					<div class="col-md-12 text-center">
					      <span class="errmsg tac">Sorry, No records found!</span>
					   </div>
				   @endif
               </div>  
            </div>
         </div>
      </div>
   </div>
</main>
<div id="alertmodalpopup" href="#" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true">
	</div>
@endsection

