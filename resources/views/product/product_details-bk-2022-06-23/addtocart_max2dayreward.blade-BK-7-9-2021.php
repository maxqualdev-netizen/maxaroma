<div class="mob-dtl_rbtn">
    <div class="dtl_rbtn">
        @if ($productDetails->stock != 'Out')
        @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
        <div class="pt10"><span class="text-red pt-2">* <b>Retail Only.</b></span></div>
        @else
        @if ($productDetails->current_stock > 0)
        <div class="input-group qty-input">
            <button type="button" class="left-qty btn-number" disabled="disabled" data-type="minus" data-field="prodqty">-</button>
            <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1">
            <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
        </div>
        @endif
       
        <div class="row row5">
            <div class="col-6 col-md-12">
                @if ($productDetails->current_stock > 0)
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart">Add to cart</a>
                @if ($productDetails->current_stock < 30) <span class="sms_text">Hurry, only {{ $productDetails->current_stock }} left! </span>
                    @endif
                    @endif
            </div>
            <div class="col-6 col-md-12">
                <div class="dtl_cart pt-md-3" id="buyItNow_sort_button">
                    @if($productDetails->current_stock > 0)
                    <a class="btn btn-addtocart d-block" id="buyitnow" data-product="{{ $productDetails->products_id }}" href="javascript:void(0);">Buy It Now</a>
                    @endif
                </div>
            </div>
        </div>
         <div class="dtl_wish hide-767 pt-md-3">               
            <a href="javascript:void(0);" id="add_to_wishlist" class="dtl-wish-link" onclick="DisplayPopupBoxWishlist('{{$productDetails->products_id}}','yes');"><!-- <img src="{{config('global.SITE_URL')}}public/images/m_icon.jpg" class="mr-1 vam" alt="" /> -->
            <svg class="sv-heart mr-1" aria-hidden="true" role="img" width="14" height="13">
                    <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
                </svg> Add to Wishlist</a>
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
<div class="dtl_exp_sip-main">
    <div class="dtl_wish show-767 pt-md-3">               
        <a href="javascript:void(0);" id="add_to_wishlist" class="dtl-wish-link" onclick="DisplayPopupBoxWishlist('{{$productDetails->products_id}}','yes');"><!-- <img src="{{config('global.SITE_URL')}}public/images/m_icon.jpg" class="mr-1 vam" alt="" /> -->
        <svg class="sv-heart mr-1" aria-hidden="true" role="img" width="16" height="15">
            <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
        </svg> Add to Wishlist</a>
    </div>
    @if ($productDetails->maxtwodaydelivery == 'Yes' && $Max2Day == 'Yes' && $productDetails->IsCosmo == 'No' && $productDetails->IsPCA == 'No' && $productDetails->IsNandansons == 'No')
    <div class="dtl_exp_sip">
        <img class="maxday-ico svg-watch icon" src="{{config('global.SITE_URL')}}images/maxtwoday.png" width="50">
        <span>
            {{ $maxtwodaycontent }} Order within <strong>{{ $Maxhour }} hrs
                {{ $Maxmin }} mins</strong> and choose <strong>Two Day Shipping</strong>
            at checkout. 
        </span>
        <div class="max_qttable">
            <a href="javascript:void(0);"><u>DETAILS</u></a>
            <div class="tables">We offer guaranteed delivery on certain fast delivery speeds and select products. When guaranteed delivery is available on an order, it will be clearly marked on the checkout page with an associated delivery date and fee (if applicable).<br />

                If we provide a guaranteed delivery date and a delivery attempt is not made by this date, we will provide a refund of any shipping fees associated with that order.<br />

                Note: The order within countdown reflects Maxaroma's delivery capability at the moment the page was opened. The delivery day may become unavailable within that time frame, prior to completing your order, due to changes in inventory or delivery capacity.</div>
        </div>
    </div>
    @else
    <div class="dtl_exp_sip">
        <svg class="svg-newship icon" aria-hidden="true" role="img" width="42" height="27">
            <use href="#svg-newship" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newship"></use>
        </svg>
        <span>
            <strong>Get it as soon as {{ $totalEstimate }}</strong><br />
            When you choose <strong>Expedited shipping</strong> at checkout.
        </span>
    </div>
    @endif
    @if ($reward_points_text)
    <div class="dtl_exp_sip">
        <svg class="svg-points icon" aria-hidden="true" role="img" width="37" height="37">
            <use href="#svg-points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-points"></use>
        </svg>
        <span>
            Members Get More <strong>3x Points</strong> for this item {!! $reward_points_text !!}
            after purchase.
        </span>
    </div>
    @endif
</div>