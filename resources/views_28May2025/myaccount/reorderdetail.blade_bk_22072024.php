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
				      <svg class="sv_orders" aria-hidden="true" role="img">
				         <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
				      </svg>
				      <h2>Reorder Products</h2>
				   </div>
				   <div class="myorder_dtl">
				      <div class="order_info">
				         <div class="row row5">
				            <div class="col-md-8 pt-2">
				               <table>
				                  <tr>
				                     <td>Order Number :</td>
				                     <td><strong>{{ $OrderRs->orders_no }}</strong></td>
				                  </tr>
				                  <tr>
				                     <td>Order Date :</td>
				                     <td><strong>{{ $OrderRs->datetime }}</strong></td>
				                  </tr>
				               </table>
				            </div>
				         </div>
				      </div>
					<form id="frmReOrder" action="" method="post">
				      <input type="hidden" name="frmAction" value="" />
				      <input type="hidden" name="orders_id" value="{{$OrderRs->orders_id}}" />
				      <div id="Rptable">
				         <table class="text-center">
				            <thead>
				               <tr>
				                  <td></td>
				                  <td>Image</td>
				                  <td class="text-left">Item Description</td>
				                  <td>Current Unit Price</td>
				                  <td>Quantity</td>
				               </tr>
				            </thead>
				            <tbody class="align-top">
				            	@if(count($ProdRS) > 0)
				            	@foreach($ProdRS as $order_details_key => $order_details_value)
				               <tr>
				                  <td data-title="full center" class="text-left">
										@if($order_details_value['current_stock']=="In")
											<input type="checkbox" id="isChecked{{$order_details_value['products_id']}}" name="ch[]" value="{{$order_details_value['products_id']}}" class="noborder chkitem" />
										@else
											<input type="checkbox" id="isChecked{{$order_details_value['products_id']}}" name="ch[]" value="{{$order_details_value['products_id']}}" class="noborder form-control chkitem" style="display:none;"/>&nbsp;
										@endif
				                  </td>
				                  <td data-title="Image"><img src="{{$order_details_value['thumb_image']}}" alt=""></td>
				                  <td data-title="Item Description" class="text-left">
										<div class="pb5">{{$order_details_value['product_name']}}</div>
										<div class="pb5">{{$order_details_value['short_description']}}</div>			  
										<div class="pb5"><strong>Item SKU :</strong> {{$order_details_value['sku']}}</div>
										@if($order_details_value['product_type'] == 'retailer' && Session::get('eusertype') == 'Wholesaler')
											<strong>Note</strong> : <span style="color:#FF0000"><strong>This item is not available for wholesale at this time, please call to get the price and availability</strong></span>
										@endif
				                  </td>
				                  <td data-title="Current Unit Price">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$order_details_value['our_price']}}</td>
				                  <td data-title="Quantity">
										@if($order_details_value['current_stock']=="In")
										<div class="input-group qty-input">
											<button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$order_details_value['products_id']}}" data-product="{{$order_details_value['products_id']}}">-</button>
											<input type="text" name="prodqty_{{$order_details_value['products_id']}}" id="prodqty_{{$order_details_value['products_id']}}" class="form-control input-number" value="1" data-qty="1" min="1">
											<button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$order_details_value['products_id']}}" data-product="{{$order_details_value['products_id']}}">+</button>
										</div>	
										@else
											<input type="hidden" name="prodqty_{{$order_details_value['products_id']}}" id="prodqty_{{$order_details_value['products_id']}}" class="form-control input-number" value="1" data-qty="1" min="1">
										@endif

										@if($order_details_value['current_stock']=="Out")				  
											<a class="label-cart-outstock" data-target="#myModalPopUp" data-toggle="modal" href="javascript:void(0);" rel="nofollow" onclick="DisplayPopupBoxAlertMe('{{$order_details_value['products_id']}}','{{$order_details_value['sku']}}');">Notify Me</a>
											<br/>
											<span class="alert-declined">Out of Stock</span>
										@endif  

				                  </td>
				               </tr>
				               @endforeach
				               @else
				               No items found!
				               @endif
				            </tbody>
				         </table>
				      </div>
				  	</form>
				   </div>
					@if($ProdRS)
						<div class="right pt-3 text-right">
							<x-button link="javascript:void(0);" btntext="Add to cart" classname="btn btn-primary" bid="btnreorder"/>
						</div>
					@endif

				</div>

			</div>
		</div>
	</div>
</main>	

@endsection