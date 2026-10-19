<div class="dtl_itemid" id="quickviewpsort_sku" role="region" aria-label="Product SKU">
    <span>Item#</span> {{ $productDetails->sku }}
</div>
@if($is_finalsale == 'Y')
<div class="dtl_itemid" role="alert" aria-live="polite">
    <strong style="font-size:12px;">FINAL SALE - NOT ELIGIBLE FOR RETURN</strong>
</div>
@endif
<div class="dtl_itemid" role="region" aria-label="Product Size">
    <span>Size:</span>
    @if ($productDetails->short_description != ''){{ $productDetails->short_description }} @endif
</div>

<div class="mob-dtl_rbtn pt-3" role="region" aria-label="Add to Cart Section">
    <div class="dtl_rbtn" id="quickviewaddtocart_sort_button">
        @if ($productDetails->stock != 'Out')
        @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
        <div class="pt10" role="alert" aria-live="polite">
            <span class="text-red pt-2">* <b>Retail Only.</b></span>
        </div>
        @else
        <div class="input-group qty-input" role="group" aria-label="Quantity Selector">
            <button type="button" class="left-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="minus" data-field="prodqty" aria-label="Decrease quantity">-</button>
            <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1" aria-label="Product quantity">
            <button type="button" class="right-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="plus" data-field="prodqty" aria-label="Increase quantity">+</button>
        </div>
        <div class="row row5">
            <div class="col-12">
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart" role="button" aria-label="Add to cart">Add to cart</a>
                
                @if ($productDetails->current_stock > 0)
                    @if (($productDetails->current_stock - $productDetails->minimum_stock) < 6)  
                        <span class="sms_text" role="status" aria-live="polite">Only {{ $productDetails->current_stock - $productDetails->minimum_stock }} left in stock </span>
                    @else
                        <span class="sms_text greentext clrgreen mt-2"><strong>In Stock</strong></span>
                    @endif
                @endif
                
                
            </div>
            @if ($productDetails->maxtwodaydelivery == 'Yes' && count($dealofdayRS)<=0)
                <div class="col-12 greentext text-center" role="status" aria-live="polite"><strong>FREE 2DAY SHIPPING</strong></div>
            @else
                <div class="col-12 greentext text-center" role="status" aria-live="polite"><strong>FREE STANDARD SHIPPING</strong></div>
            @endif
        </div>
        @endif
        @else
        <div class="soldout pb-2" role="alert" aria-live="polite">
            <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{ $productDetails->products_id }}','{{ $productDetails->sku }}');" class="btn btn-addtocart d-block" rel="nofollow" aria-label="Notify me when product is back in stock">Notify Me</a>
        </div>
        <span class="text-red" role="status" aria-live="polite">Out Of Stock</span>
        @endif
    </div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-xs-12" id="SpecialPriceSortQuickview" role="region" aria-label="Special Quantity Discount Price">
    @if($SpecialPriceDetails!='')
    <div class="sublist_prd">
        <h4 class="error pb5 text-red">Special Quantity Discount Price</h4>
        {!! $SpecialPriceDetails !!}
    </div>
    @endif
</div>

<div class="col-xs-12">
    <div id="quickview_sort_descrition" class="mt-0" role="region" aria-label="Product Description">
        @if($productDetails->product_url!="")
        <div class="dtl_addrview">
            <a href="{{ $productDetails->product_url }}" aria-label="View more product details">View More Details</a>
        </div>
        @endif
    </div>
</div>

<div id="Max2DayQuick" role="region" aria-label="Max 2 Day Shipping Information">
    @if ($productDetails->maxtwodaydelivery == 'Yes' && $Max2Day == 'Yes' && $productDetails->IsCosmo == 'No' && $productDetails->IsPCA == 'No' && $productDetails->IsNandansons == 'No' && count($dealofdayRS)<=0)
    <div class="dtl_exp_sip pb-2 dtl_exp_sip">
        <img class="maxday-ico svg-watch icon" src="{{config('global.SITE_URL')}}images/maxtwoday-dtl.png" width="36" height="17" alt="Max 2 Day Shipping Icon" role="img" aria-label="Max 2 Day Shipping Icon">
        <span>
            {{ $maxtwodaycontent }} Order within 
            <strong>
            @if($Maxhour > 0){{ $Maxhour }} hrs @endif
            @if($Maxmin > 0){{ $Maxmin }} minutes. @endif    
            </strong> and choose <strong>2-day Shipping!</strong> at checkout.
            <div class="max_qttable">
                <a href="#" aria-label="More information about guaranteed delivery"><u> i</u></a>
                <div class="tables" role="region" aria-label="Guaranteed Delivery Policy">
                    We offer guaranteed delivery on certain fast delivery speeds and select products. When guaranteed delivery is available on an order, it will be clearly marked on the checkout page with an associated delivery date and fee (if applicable).<br />
                    If we provide a guaranteed delivery date and a delivery attempt is not made by this date, we will provide a refund of any shipping fees associated with that order.<br />
                    Note: The order within countdown reflects Maxaroma's delivery capability at the moment the page was opened. The delivery day may become unavailable within that time frame, prior to completing your order, due to changes in inventory or delivery capacity.
                </div>
            </div>
        </span>
    </div>
    @endif
</div>