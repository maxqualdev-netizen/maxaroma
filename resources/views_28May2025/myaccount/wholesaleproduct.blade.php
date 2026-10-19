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
								<td> <strong>{{Price($prodata->$wprice_obj,true)}}</strong> </td>
							</tr>
						@endif
					@endforeach
			   </table>
			   @endif
			</div>
			
			<div class="col-md-2 text-center text-md-right pt-md-0 pt-2">
				  @if($prodata->stock == 'In')
					  <div class="input-group qty-input cartitem">
							<button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$prodata->products_id}}" data-product="{{$prodata->products_id}}">-</button>
							<input type="text" name="prodqty_{{$prodata->products_id}}" id="prodqty_{{$prodata->products_id}}" class="form-control input-number wprodqty" value="1" data-qty="1" min="1">
							<button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$prodata->products_id}}" data-product="{{$prodata->products_id}}">+</button>
					  </div>				  
				  @else
					 <input type="hidden" name="Qty_{{$prodata->products_id}}" id="Qty_{{$prodata->products_id}}" class="form-control input-number"  value="">
					  <input id="SKU_{{$prodata->products_id}}" type="hidden" value="{{$prodata->sku}}" name="SKU_{{$prodata->products_id}}">
					  <input id="Spid_{{$prodata->products_id}}" type="hidden" value="{{$prodata->products_id}}" name="Spid[]">
					  <input type="hidden" value="{{$prodata->current_stock}}" name="avt_{{$prodata->products_id}}" id="avt_{{$prodata->products_id}}">
					  
					  <a class="notify-link red" data-target="#myModalPopUp" data-toggle="modal" href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodata->products_id}}','{{$prodata->sku}}');"> Notify Me</a> 
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
