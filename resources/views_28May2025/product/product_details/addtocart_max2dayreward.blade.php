<div class="mob-dtl_rbtn">
    <div id="dtlbbtn" class="dtl_rbtn @if($productDetails->stock == 'Out') Notify-rbtn @endif">
        @php $pro_price = 0; @endphp
        @if($productDetails->stock=='In')
            @if(count($dealofdayRS)>0)            
                @if(isset($productDetails->dealprice))                
                    @php $pro_price = $productDetails->dealprice; @endphp
                @else
                    @php $pro_price = $productDetails->retail_price; @endphp
                @endif
            @else 
                @if($productDetails->product_price!='')                
                    @php $pro_price = $productDetails->product_price; @endphp
                @endif
            @endif    
        @endif
        @if ($productDetails->maxtwodaydelivery == 'Yes' && @count($dealofdayRS)<=0)
            <div id="mobshipping_info" class="col-12 greentext text-center d-md-none"><strong>FREE 2DAY SHIPPING</strong></div>
        @else
            <div id="mobshipping_info" class="col-12 greentext text-center d-md-none"><strong>FREE STANDARD SHIPPING</strong></div>
        @endif

        @if (isset($productDetails->referenced_products))
        <div class="qtyse">
            <div class="d-md-none">
                <select name="sizeoption" id="sizeoption" class="form-control" data-catid="{{$produ_categoryid}}" onchange="setSize(this);">
                    <option value="">Select Size</option>
                    @if (isset($productDetails->referenced_products['Standard Size']) && count($productDetails->referenced_products['Standard Size']) > 0)
                        @for ($s = 0; $s < count($productDetails->referenced_products['Standard Size']); $s++)                    
                            <option value="{{$productDetails->referenced_products['Standard Size'][$s]['products_id']}}" {{ $productDetails->products_id == $productDetails->referenced_products['Standard Size'][$s]['products_id'] ? 'selected' : '' }} >{{$productDetails->referenced_products['Standard Size'][$s]['short_description']}}</option>
                        @endfor
                    @endif        
                    @if (isset($productDetails->referenced_products['Pocket Perfume']) && count($productDetails->referenced_products['Pocket Perfume']) > 0)
                        @for ($p = 0; $p < count($productDetails->referenced_products['Pocket Perfume']); $p++)
                            <option value="{{$productDetails->referenced_products['Pocket Perfume'][$p]['products_id']}}" {{ $productDetails->products_id == $productDetails->referenced_products['Pocket Perfume'][$p]['products_id'] ? 'selected' : '' }} >{{$productDetails->referenced_products['Pocket Perfume'][$p]['short_description']}}</option>
                        @endfor
                    @endif
                </select>
            </div>    
            @if ($productDetails->current_stock > 0)
                <div class="input-group qty-input page-qty">
                    <button type="button" class="left-qty btn-number" disabled="disabled" data-type="minus" data-field="prodqty">-</button>
                    <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1">
                    <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
                </div>
            @endif
        </div>
        @endif

        @if ($productDetails->stock != 'Out')
        @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
        <div class="pt10"><span class="text-red pt-2">* <b>Retail Only.</b></span></div>
        @else
        
       
        <div class="row row5">
            <div class="col-12 col-md-12">
                @if ($productDetails->current_stock > 0)
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart">Add to cart</a>
                @if ($productDetails->current_stock - $productDetails->minimum_stock < 30) <span class="sms_text">Hurry, only {{ $productDetails->current_stock - $productDetails->minimum_stock }} left! </span>@endif
                {{-- @if ($productDetails->current_stock < 30) <span class="sms_text">Hurry, only {{ $productDetails->current_stock }} left! </span>@endif --}}
                    @endif

                    <div class="mobile_paypal" 
                        data-pp-message
                        data-pp-style-layout="text"
                        data-pp-style-logo-type="inline"
                        data-pp-style-text-color="black"
                        data-pp-style-text-size="12"
                        data-pp-amount="{{$pro_price}}"
                        data-pp-placement=product>
                    </div>
                    <div id="paypal-button-container" class="mobile_paypal" style="position: relative; z-index: 1;"></div>                    
            </div>
            @if(isset($DealOfferTime))
            <div class="col-12">
                <span class="dtl-offermob">Offer ending in {{$DealOfferTime}}</span> 
            </div>
            @endif
            @if ($productDetails->maxtwodaydelivery == 'Yes' && @count($dealofdayRS)<=0)
                <div id="shipping_info" class="col-12 greentext text-center d-md-block d-none"><strong>FREE 2DAY SHIPPING</strong></div>
            @else
                <div id="shipping_info" class="col-12 greentext text-center d-md-block d-none"><strong>FREE STANDARD SHIPPING</strong></div>
            @endif
            <div class="col-12">
                <span id="sort_website_stockcheck" class="reg_error">
                    @if($productDetails->WebsiteStock=="Out" && $productDetails->stock=="In" && ($productDetails->IsCosmo=="Yes" || $productDetails->IsPCA=="Yes" || $productDetails->IsNandansons=="Yes" ))
                    <div class="text-red pt-2"><b>In Stock --- 3 Business Days Handling Time.</b></div>
                    @endif
                    @if($productDetails->WebsiteStock=="Out" && $productDetails->stock=="In" && $productDetails->IsPerfumePW=="Yes")
                    <div class="text-red pt-2"><b>In Stock --- 3 Business Days Handling Time.</b></div>
                    @endif
                </span>
            </div>
        </div>
        @endif
        @else
        <div class="soldout pb-2">
            <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{ $productDetails->products_id }}','{{ $productDetails->sku }}');" class="btn btn-addtocart d-block" rel="nofollow">Notify Me</a>
        </div>
        <span class="text-red">Out Of Stock</span>
        @endif
    </div>
</div>
