<style>
	.stock_left{font-weight: 600;display: block;padding-bottom: 5px;color: #ec040f;font-size: 11px;line-height: 18px;}
</style>
@if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
<div class="col-12 mb-md-4 mb-3" id="alldata" role="region" aria-labelledby="cart-table-heading">
    <h2 id="cart-table-heading" class="sr-only">Shopping Cart Items</h2>
    <div>
        @if(Session::has('CartError'))
        <x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('CartError'), 'mid' => 'error_cart']" />
        @elseif(Session::has('CartSuccess'))
        <x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => Session::get('CartSuccess'), 'mid' => 'success_cart']" />
        @elseif(Session::has('fund_success'))
        <x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => Session::get('fund_success'), 'mid' => 'message_cart']" />
        @elseif(Session::has('fund_error'))
        <x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('fund_error'), 'mid' => 'error_cart']" />
        @elseif(Session::has('PlaceOrderError'))
        <x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_cart']" />
        @else
        <x-message :attr="[ 'classname' => 'alert alert-danger mt-3 text-center', 'message' => '', 'mid' => 'error_cart', 'style' => 'display:none']" />
        <x-message :attr="[ 'classname' => 'alert alert-success mt-3 text-center', 'message' => '', 'mid' => 'cart_msg', 'style' => 'display:none']" />
        @endif
    </div>
    <div class="cart-top text-center pb-md-5 pt-md-5">
        <h3 class="cart-hd" id="cart-heading">My Cart</h3>
        <p class="d-none d-md-inline-block mb-0">Free Shipping on all U.S orders.</p>
    </div>
    <div class="cart-table" role="region" aria-label="Shopping Cart Table">
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
            <a href="javascript:void(0);" id="clear-bag" class="ulink f12" role="button" aria-label="Clear Your Cart">Clear Your Cart</a>
        </div>
        @foreach($CartDetails['Cart'] as $key => $CartItem)
        <div class="cart-row" aria-labelledby="cart-item-{{$key}}">
            <div class="row row10">
                <div class="col-md-7">
                    <div class="cart-table-decsec">
                        @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')  || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') )
                        <a class="cart-prd" href="javascript:void(0);" aria-label="Free Gift Image">{!!$CartItem['Image']!!}</a>
                        @else
                        <a class="cart-prd" href="{{$CartItem['Prod_URL']}}" aria-label="Product Image: {{$CartItem['ProductName']}}">{!!$CartItem['Image']!!}</a>
                        @endif
                        <div class="cart-table-des" id="cart-item-{{$key}}">
                            <div class="cart-product f12 pb-md-3 pb-2">
                                @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')  || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') )
                                <span aria-label="Product Name">{{$CartItem['ProductName']}}</span>
                                @else
                                <a href="{{$CartItem['Prod_URL']}}" aria-label="Product: {{$CartItem['ProductName']}}">
                                    {{$CartItem['ProductName']}}
                                </a>
                                @endif
                                @if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
                                <div class="pt-1" aria-label="Short Description">{{$CartItem['short_description']}}</div>
                                @endif
                            </div>
                            <div class="SKU"> <span aria-label="SKU">Item SKU: {{$CartItem['SKU']}}</span> </div>
                            @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')  || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') )
                            <div class="pb-2 freegift" aria-label="Free Gift Notice">This one is on US!</div>
                            @endif
                            @if(isset($CartItem['IsGiftCertificateItem']) && $CartItem['IsGiftCertificateItem'] == 'Yes')
                            <div class="pb5" aria-label="Sender Name">Sender Name : @if(isset($CartItem['YourName']) && $CartItem['YourName'] != '') {{$CartItem['YourName']}} @endif</div>
                            <div class="pb5" aria-label="Sender Email">Sender Email : @if(isset($CartItem['YourEmail']) && $CartItem['YourEmail'] != '') {{$CartItem['YourEmail']}} @endif</div>
                            <div class="pb5" aria-label="Recipient Name">Recipient Name : @if(isset($CartItem['RecipientName']) && $CartItem['RecipientName'] != '') {{$CartItem['RecipientName']}} @endif</div>
                            <div class="pb5" aria-label="Recipient Email">Recipient Email : @if(isset($CartItem['RecipientEmail']) && $CartItem['RecipientEmail'] != ''){{$CartItem['RecipientEmail']}} @endif</div>
                            @endif
                            @if(isset($CartItem['HandlingTimeStr']) && $CartItem['HandlingTimeStr']!='')
                            <div class="pb5"><span class="fsbold error" aria-label="Handling Time">
                                    {{$CartItem['HandlingTimeStr']}}</span>
                            </div>
                            @endif
                            <div class="unit-price pt-1">
                                <span aria-label="Unit Price">Unit Price : {{Price($CartItem['Price'])}}</span>
                            </div>
                            @if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
                            <div class="yotpo-product pt-2">
                                <span aria-label="Free Product Reward">Free Product Reward - <strong>Yotpo</strong></span>
                            </div>
                            @endif
                            @if(isset($CartItem['FinalSale']) && $CartItem['FinalSale']!='' )
                            <div class="yotpo-product pt-2">
                                <span aria-label="Final Sale">{{ $CartItem['FinalSale']}}</span>
                            </div>
                            @endif
                            @if(count($skrSKU) > 0 && in_array($CartItem['SKU'],$skrSKU))
                            <div class="pb5 pt-1"><span class="fsbold error sale-menu" aria-label="Out Of Stock">
                                    Out Of Stock</span>
                            </div>
                            @elseif(isset($CartItem['stock_left']) && $CartItem['stock_left'] > 0 && $CartItem['stock_left'] < 6)
                                <span class="stock_left pt-1">Only {{ $CartItem['stock_left'] }} left in stock</span>
							@endif
                            @if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
                            @else
                            <div class="fav-remove pt-md-4 pt-2 mt-md-0 mt-1">
                                <div class="add-fav-div">
                                    <a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$CartItem['ProductID']}}','yes');" class="svg-h" role="button" aria-label="Move to favorites">
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
                        <div class="remove-div">
                            <a href="javascript:void(0);" class="shopcartItemDel" data-index="{{$key}}" role="button" aria-label="Remove item from cart">
                                <svg class="sv-trash vam" aria-hidden="true" role="img" width="18" height="18">
                                    <use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="cart-table-right">
                        <div class="input-group qty-input cartitem" role="group" aria-label="Quantity Selector">
                            @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes') || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') || (isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes'))
                            <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="1" data-qty="1" min="1" max="3" style="display:none;" aria-label="Quantity" aria-readonly="true">
                            @else
                            <button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Decrease quantity">-</button>
                            <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="{{$CartItem['Qty']}}" data-qty="{{$CartItem['Qty']}}" min="1" aria-label="Quantity">
                            <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Increase quantity">+</button>
                            @endif
                            @if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && isset($CartItem['SpecialPriceDetails']) && $CartItem['SpecialPriceDetails'] !='')
                            <div class="cart-tooltip max_qttable">
                                <a href="#" aria-label="Special Price Details">
                                    <u>i</u>
                                </a>
                                <div class="tables">{!!$CartItem['SpecialPriceDetails']!!}</div>
                            </div>
                            @endif
                        </div>
                        @if(isset($CartItem['ShowGiftChkOpt']) && $CartItem['ShowGiftChkOpt'] == 'Yes' && $CartItem['ProductID'] != '0' )
                        <label class="checkbox-label d-none d-md-inline-block mb-0 f12" for="giftbox_{{$CartItem['ProductID']}}">
                            <div class="chebox">
                                <input type="checkbox" @if(isset($CartItem['gift_wrap']) && $CartItem['gift_wrap']=='Yes' ) checked @endif class="clsgiftbox" name="giftbox_{{$CartItem['ProductID']}}" id="giftbox_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-checked="{{ isset($CartItem['gift_wrap']) && $CartItem['gift_wrap'] == 'Yes' ? 'true' : 'false' }}">
                                <span class="checkmark"></span>
                            </div>
                            Gift Wrap
                        </label>
                        @endif
                        <div class="cart-price" aria-label="Total Price">{{Price($CartItem['TotPrice'])}}</div>
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