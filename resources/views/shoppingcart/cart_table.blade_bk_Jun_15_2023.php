
@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
                          <div class="col-12 mb-md-4 mb-3" id="alldata">
							  <div>
	@if(Session::has('CartError'))
		<x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('CartError'), 'mid' => 'error_cart']"/>
	@elseif(Session::has('CartSuccess'))
		<x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => Session::get('CartSuccess'), 'mid' => 'success_cart']"/>
	@elseif(Session::has('fund_success'))
		<x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => Session::get('fund_success'), 'mid' => 'message_cart']"/>
	@elseif(Session::has('fund_error'))
		<x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('fund_error'), 'mid' => 'error_cart']"/>
	@elseif(Session::has('PlaceOrderError'))
		<x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_cart']"/>
	@else
	<x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => '', 'mid' => 'error_cart', 'style' => 'display:none']"/>
	<x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => '', 'mid' => 'cart_msg', 'style' => 'display:none']"/>
	@endif
</div>
                                <div class="cart-top text-center pb-md-5 pt-md-5">
                                    <h3 class="cart-hd">My Cart</h3>
                                    <p class="d-none d-md-inline-block mb-0">Free Shipping on all U.S orders.</p>
                                </div>
                                <div class="cart-table">
                                    <div class="cart-table-header">
                                        <div class="d-none d-md-block">
                                            <div class="row row10">
                                                <div class="col-md-7">
                                                    <div class="cart-table-head">PRODUCT</div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="cart-table-head">QUANTITY <span class="flot-right">PRICE</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" id="clear-bag" class="ulink f12">Clear Your Cart</a> 
                                    </div>
                                   
                           
                            
	@foreach($CartDetails['Cart'] as $key => $CartItem)
		<div class="cart-row">
			<div class="row row10">
				<div class="col-md-7">
					<div class="cart-table-decsec">
					@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
						<a class="cart-prd" href="javascript:void(0);">{!!$CartItem['Image']!!}</a>
					@else
						<a class="cart-prd" href="{{$CartItem['Prod_URL']}}">{!!$CartItem['Image']!!}</a>
					@endif

					<div class="cart-table-des">	
							<div class="cart-product f12 pb-md-3 pb-2"> 
								@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
									{{$CartItem['ProductName']}}
								@else 	
									<a href="{{$CartItem['Prod_URL']}}">{{$CartItem['ProductName']}}</a> 
								@endif

								@if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
									<div class="pt-1">{{$CartItem['short_description']}}</div>
								@endif
							</div>
							<div class="SKU"> <span>Item SKU: {{$CartItem['SKU']}}</span> </div>
							
							
							
							@if(isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')
								<div class="pb-2 freegift">This one is on US!</div>
							@endif	
							
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
							
							<div class="unit-price pt-1"> 
								<span>Unit Price : {{Price($CartItem['Price'])}}</span>
							</div>
                             @if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
                                <div class="yotpo-product pt-2"> 
                                    <span>Free Product Reward - <strong>Yotpo</strong></span>
                                </div>
                            @endif
                            @if(count($skrSKU) > 0 && in_array($CartItem['SKU'],$skrSKU))
								<div class="pb5 pt-1"><span class="fsbold error sale-menu">
									Out Of Stock</span>
								</div>
							@endif
							@if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
								@else
								<div class="fav-remove pt-md-4 pt-2 mt-md-0 mt-1">
									<div class="add-fav-div"> 
										<a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$CartItem['ProductID']}}','yes');" class="svg-h">
											<svg class="sv-heart vam sv-bor mr-1" aria-hidden="true" role="img" width="18" height="16">
												<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
											</svg>
											<svg class="sv-heart-fill vam sv-fill mr-1" aria-hidden="true" role="img" width="18" height="16">
												<use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
											</svg>
											Move to favorites 
										</a> 
									</div>
								</div>
							@endif
					</div>
					@if(!isset($CartItem['IS_Free_Gift']) || $CartItem['IS_Free_Gift'] == 'No')
					<div class="remove-div"> 
						<a href="javascript:void(0);" class="shopcartItemDel" data-index="{{$key}}" >
							<svg class="sv-trash vam" aria-hidden="true" role="img" width="18" height="18">
								<use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
							</svg>
						</a> 
					</div>
					@endif
				  </div>
				</div>
				<div class="col-md-5">
					<div class="cart-table-right">
						<div class="input-group qty-input cartitem">
							@if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes') || (isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes'))
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
						@if(isset($CartItem['ShowGiftChkOpt']) && $CartItem['ShowGiftChkOpt'] == 'Yes' && $CartItem['ProductID'] != '0' )
							<label class="checkbox-label d-none d-md-inline-block mb-0 f12">
								<div class="chebox">
									<input type="checkbox" @if(isset($CartItem['gift_wrap']) && $CartItem['gift_wrap'] == 'Yes') checked @endif class="clsgiftbox" name="giftbox_{{$CartItem['ProductID']}}" id="giftbox_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}">
									<span class="checkmark"></span> 
								</div>
								Gift Wrap
							</label>
						@endif
						<div class="cart-price">{{Price($CartItem['TotPrice'])}}</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
		     </div>
                            </div>
@endif
{{--
<!--Code for Searchspring IntelliSuggest Tracking Start-->
<script type="text/javascript" src="//cdn.searchspring.net/intellisuggest/is.min.js"></script>
<script type="text/javascript">

var allItems;

try{
    allItems = '{{$allCartItems}}';
    allItems = allItems.replace(",", "','");
    allItems = "'"+allItems+"'";
    
    IntelliSuggest.init({siteId:'faltym', context:'Basket', seed:[allItems]});
    @if(Session::has("sess_icustomerid"))
		IntelliSuggest.setShopperId('{{Session::get("sess_icustomerid")}}');
	@endif
    
    @foreach($CartDetails['Cart'] as $key => $CartItem)
		IntelliSuggest.haveItem({sku:"{{$CartItem['SKU']}}", qty:"{{$CartItem['Qty']}}", price:"{{$CartItem['TotPrice']}}"});
    @endforeach
    IntelliSuggest.inBasket({});
} catch(err) {}
</script>
<!--Code for Searchspring IntelliSuggest Tracking End-->
--}}
