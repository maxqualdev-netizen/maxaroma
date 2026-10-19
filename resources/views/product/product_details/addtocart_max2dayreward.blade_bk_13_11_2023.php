<div class="mob-dtl_rbtn">
    <div class="dtl_rbtn @if($productDetails->stock == 'Out') Notify-rbtn @endif">
        @if ($productDetails->stock != 'Out')
        @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
        <div class="pt10"><span class="text-red pt-2">* <b>Retail Only.</b></span></div>
        @else
        @if ($productDetails->current_stock > 0)
        <div class="input-group qty-input page-qty">
            <button type="button" class="left-qty btn-number" disabled="disabled" data-type="minus" data-field="prodqty">-</button>
            <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1">
            <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
        </div>
        @endif
       
        <div class="row row5">
            <div class="col-12 col-md-12">
                @if ($productDetails->current_stock > 0)
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart">Add to cart</a>
                @if ($productDetails->current_stock - $productDetails->minimum_stock < 30) <span class="sms_text">Hurry, only {{ $productDetails->current_stock - $productDetails->minimum_stock }} left! </span>@endif
                {{-- @if ($productDetails->current_stock < 30) <span class="sms_text">Hurry, only {{ $productDetails->current_stock }} left! </span>@endif --}}
                    @endif
            </div>
            @if(isset($DealOfferTime))
            <div class="col-12">
                <span class="dtl-offermob">Offer ending in {{$DealOfferTime}}</span> 
            </div>
            @endif
            @if ($productDetails->maxtwodaydelivery == 'Yes')
                <div class="col-12 greentext text-center"><strong>FREE 2DAY SHIPPING</strong></div>
            @else
                <div class="col-12 greentext text-center"><strong>FREE STANDARD SHIPPING</strong></div>
            @endif
            <div class="col-12">
                <span id="sort_website_stockcheck" class="reg_error">
                    @if($productDetails->WebsiteStock=="Out" && $productDetails->stock=="In" && ($productDetails->IsCosmo=="Yes" || $productDetails->IsPCA=="Yes" || $productDetails->IsNandansons=="Yes" ))
                    <div class="text-red pt-2"><b>In Stock --- 2 Business Days Handling Time.</b></div>
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
