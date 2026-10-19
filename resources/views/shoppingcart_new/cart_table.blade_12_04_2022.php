<div>
	@if(Session::has('CartError'))
		<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('CartError'), 'mid' => 'error_cart']"/>
	@endif
	@if(Session::has('CartSuccess'))
		<x-message :attr="[ 'classname' => 'alert alert-success', 'message' => Session::get('CartSuccess'), 'mid' => 'success_cart']"/>
	@endif
	@if(Session::has('fund_success'))
		<x-message :attr="[ 'classname' => 'alert alert-success', 'message' => Session::get('fund_success'), 'mid' => 'message_cart']"/>
	@endif
	@if(Session::has('fund_error'))
		<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('fund_error'), 'mid' => 'error_cart']"/>
	@endif
	<x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => '', 'mid' => 'error_cart', 'style' => 'display:none']"/>
	<x-message :attr="[ 'classname' => 'alert alert-success', 'message' => '', 'mid' => 'cart_msg', 'style' => 'display:none']"/>
</div>
<h6 class="top-cont">{{$CartDetails['TotalItemInCart']}} items in cart</h6>
@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
	@foreach($CartDetails['Cart'] as $key => $CartItem)
		<div class="cart-row">
			<div class="row">
				<div class="col-3 pr-0 pr-sm-3"> 
					@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
						<a href="javascript:void(0);">{!!$CartItem['Image']!!}</a>
					@else
						<a href="{{$CartItem['Prod_URL']}}">{!!$CartItem['Image']!!}</a>
					@endif	
				</div>
				<div class="col-9">
					<div class="row">
						<div class="col-md-6 col-12">
							<div class="cart-product"> 
								@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
									{{$CartItem['ProductName']}}
								@else 	
									<a href="{{$CartItem['Prod_URL']}}">{{$CartItem['ProductName']}}</a> 
								@endif
							</div>
							<div class="pb-2 SKU"> <span>Item SKU: {{$CartItem['SKU']}}</span> </div>
							
							@if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
								<div class="pb-2 DESC">{{$CartItem['short_description']}}</div>
							@endif
							
							@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
								<div class="pb-2 freegift">This one is on US!</div>
							@endif	
							{{--
							@if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
								@if(isset($CartItem['Markup_Percent']) && $CartItem['Markup_Percent']!='' && isset($CartItem['Markup_Value']) && $CartItem['Markup_Value'] !='' )
								<div class="pb5">
									<span>Actual Price :</span> {{Price($CartItem['ActualWholesalePrice'])}}
								</div>
								@endif
							@endif
							--}}
							@if($CartItem['SKU'] == config('global.GIFT_CERTIFICATE_SKU'))
								<div class="pb5">Sender Name : {{$CartItem['YourName']}}</div>
								<div class="pb5">Sender Email : {{$CartItem['YourEmail']}}</div>
								<div class="pb5">Recipient Name : {{$CartItem['RecipientName']}}</div>
								<div class="pb5">Recipient Email : {{$CartItem['RecipientEmail']}}</div>
							@elseif($CartItem['SKU'] == config('global.GIFT_CERTIFICATE_SKU1'))
								<div class="pb5">Sender Name : {{$CartItem['YourName']}}</div>
								<div class="pb5">Sender Email : {{$CartItem['YourEmail']}}</div>
								<div class="pb5">Recipient Name : {{$CartItem['RecipientName']}}</div>
								<div class="pb5">Recipient Email : {{$CartItem['RecipientEmail']}}</div>
							@endif
							
							@if(isset($CartItem['HandlingTimeStr']) && $CartItem['HandlingTimeStr']!='')
								<div class="pb5"><span class="fsbold error">
									{{$CartItem['HandlingTimeStr']}}</span>
								</div>
							@endif
							
							<div class="unit-price"> 
								<span>Unit Price : {{Price($CartItem['Price'])}}</span>
							</div>
							@if(isset($CartItem['ShowGiftChkOpt']) && $CartItem['ShowGiftChkOpt'] == 'Yes' && $CartItem['ProductID'] != '0' )
							<label class="checkbox-label d-block d-md-none mt-3">
								<div class="chebox">
									<input type="checkbox" @if(isset($CartItem['gift_wrap']) && $CartItem['gift_wrap'] == 'Yes') checked @endif class="clsgiftbox" name="giftbox_{{$CartItem['ProductID']}}" id="giftbox_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" >
									<span class="checkmark"></span> 
								</div>
								Gift Wrap
							</label>
							@endif
						</div>
						<div class="col-md-3 col-6 mt-2 mt-md-0">
							<div class="input-group qty-input cartitem">
								@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
									1	
									<input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="1" data-qty="1" min="1" max="3" style="display:none;">
								@else 	
									<button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">-</button>
									<input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="{{$CartItem['Qty']}}" data-qty="{{$CartItem['Qty']}}" min="1">
									<button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">+</button>
								@endif
								@if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && isset($CartItem['SpecialPriceDetails']) && $CartItem['SpecialPriceDetails'] !='')
									<div class="cart-tooltip max_qttable">	
										<a href="#">
											<u>i</u>
										</a>
										<div class="tables">{!!$CartItem['SpecialPriceDetails']!!}</div>
									</div>	
								@endif	
							</div>
						</div>
						<div class="col-md-3 col-6 mt-4 mt-md-0">
							<div class="cart-price">{{Price($CartItem['TotPrice'])}}</div>
						</div>
						<div class="col-12 mt-3 mt-md-4">
							@if(isset($CartItem['ShowGiftChkOpt']) && $CartItem['ShowGiftChkOpt'] == 'Yes' && $CartItem['ProductID'] != '0' )
							<label class="checkbox-label d-none d-md-inline-block">
								<div class="chebox">
									<input type="checkbox" @if(isset($CartItem['gift_wrap']) && $CartItem['gift_wrap'] == 'Yes') checked @endif class="clsgiftbox" name="giftbox_{{$CartItem['ProductID']}}" id="giftbox_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">
									<span class="checkmark"></span> 
								</div>
								Gift Wrap
							</label>
							@endif
							@if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
							@else
							<div class="fav-remove">
								<div class="add-fav-div"> 
									<a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$CartItem['ProductID']}}','yes');" class="svg-h">
										<svg class="sv-heart vam sv-bor" aria-hidden="true" role="img" width="20" height="20">
											<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
										</svg>
										<svg class="sv-heart-fill vam sv-fill" aria-hidden="true" role="img" width="20" height="20">
											<use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
										</svg>
										Move to favorites 
									</a> 
								</div>
								<div class="remove-div"> 
									<a href="javascript:void(0);" class="shopcartItemDel" data-index="{{$key}}" >
										<svg class="sv-trash vam" aria-hidden="true" role="img" width="20" height="20">
											<use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
										</svg>
										Remove 
									</a> 
								</div>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@endif

