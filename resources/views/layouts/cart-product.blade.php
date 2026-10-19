<li class="item" role="listitem" aria-label="Cart Item: {{$CartItem['ProductName']}}">
    <div class="product">
        <div class="product-thumb">
            <a href="{{$CartItem['Prod_URL']}}" tabindex="0" aria-label="View {{$CartItem['ProductName']}}">
                {!!$CartItem['image_forpopup']!!}
            </a>
        </div>
        <div class="product-info-right">
            <div class="product-name" style="min-height:60px;">
                <a href="{{$CartItem['Prod_URL']}}" tabindex="0" aria-label="Product: {{$CartItem['ProductName']}}">
                    <strong>{{$CartItem['ProductName']}}</strong>
                </a>
                @if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
                    <div>{!! $CartItem['short_description'] !!}</div>
                @endif
                @if(isset($CartItem['stock_left']) && $CartItem['stock_left'] > 0 && $CartItem['stock_left'] < 6)
                    <span class="stock_left">Only {{ $CartItem['stock_left'] }} left in stock</span>
                @endif
                @if(isset($CartItem['BogoDiscountMessage']) && !empty($CartItem['BogoDiscountMessage']))
                    {!! $CartItem['BogoDiscountMessage'] !!}
                @endif
            </div>
            @if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
                <div class="yotpo-product pb-2" style="font-size:11px;color:#000;">
                    Free Product Reward - <strong>Yotpo</strong>
                </div>
            @endif
            @if(isset($CartItem['FinalSale']) && $CartItem['FinalSale']!='' )
            <div class="yotpo-product pb-2" style="font-size:11px;color:#000;">
                {{$CartItem['FinalSale']}}
            </div>
            @endif

            @if(Auth::guard('store')->check())
                @if(isset($CartItem['OrderType']) && $CartItem['OrderType'] == 'Store')
                    <div class="itemType ml-0 f12 pb-2">Store Item</div>
                @else
                    <div class="itemType ml-0 f12 pb-2">Online Item</div>
                @endif
            @endif

            <div class="product-price">
                @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')  || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') )
                    <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price Free">{{$CartItem['Qty']}} X {{Price(0.00)}}</div>
                @else
                    @if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
                        <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price {{Price($CartItem['Price'])}}">{{$CartItem['Qty']}} X {{Price($CartItem['Price'])}}</div>
                    @else
                        <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price {{Price($CartItem['ItemPrice'])}}">{{$CartItem['Qty']}} X {{Price($CartItem['ItemPrice'])}}</div>
                    @endif
                @endif
                @if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
                @else
                <!-- <a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" > Remove </a> -->
                @endif
            </div>
        </div>
    </div>
    <div class="prd-cartbot">
        <a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" aria-label="Remove {{$CartItem['ProductName']}} from cart">Remove</a>
        <div class="qtybox product-cart-plusminus cart-table" role="group" aria-label="Change quantity for {{$CartItem['ProductName']}}">

            @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes') || (isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes') || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes'))
            <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="1" data-qty="1" min="1" max="3" style="display:none;" aria-label="Quantity 1">
            @else
                <button type="button" class="left-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="minus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Decrease quantity for {{$CartItem['ProductName']}}">-</button>
                <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="{{$CartItem['Qty']}}" data-qty="{{$CartItem['Qty']}}" min="1" style="width:30%;" aria-label="Quantity for {{$CartItem['ProductName']}}">
                <button type="button" class="right-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="plus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Increase quantity for {{$CartItem['ProductName']}}">+</button>
            @endif
        </div>
    </div>
</li>