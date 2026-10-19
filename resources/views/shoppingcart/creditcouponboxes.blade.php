@if($CartAttr['CreditLimitFlag'] != 0)
    <div class="col-12 f12 pt-md-1 pb-4" role="region" aria-label="Credit Balance Information">
        <span id="credit-balance-label">Your available credit Balance is :</span>
        @if($CreditDiscount > 0 && $CartAttr['CreditLimitFlag'] == 2)
            <span aria-label="Remaining Credit Limit">{{Session::get('currency_symbol').$CartAttr['RemainCreditLimit']}}</span>
        @else
            <span aria-label="Credit Limit">{{Session::get('currency_symbol').$CartAttr['CreditLimit']}}</span>
        @endif
        @if($CreditDiscount > 0)
            <a href="javascript:void(0);" class="ulink d-inline-block ml-2" id="btnremovecred" role="button" aria-label="Remove Credit"><strong>Remove</strong></a>
        @else
            <a href="javascript:void(0);" id="btncredit" class="ulink d-inline-block ml-2" role="button" aria-label="Apply Credit"><strong>Apply</strong></a> 
        @endif
    </div>
@endif
@if($CartAttr['FundFlag'] == 1)
    <div class="col-12 f12 pt-md-1 pb-4" role="region" aria-label="Available Fund Information">
        <span id="available-fund-label">Available Fund :</span> 
        <span aria-label="Available Fund Amount">{{Price($CartAttr['available_funds'])}}</span>
        <a href="javascript:void(0);" class="ulink d-inline-block ml-2" id="btnAdddFund" role="button" aria-label="Add New Fund"><strong>Add New Fund</strong></a>
    </div>
@endif
<div class="col-12">
    <div class="row">
        @if($CartAttr['isCouponsAvailable'] == 1 )
        <div class="col-12 mb-2 pb-1">
            <div class="max_coupon_box" role="form" aria-labelledby="coupon-code-label">
                <div class="coupan_boxhd plus-minus">
                    <h6 class="f12" id="coupon-code-label">Promotional/Coupon Code</h6>
                </div>
                <div class="cart-discount">
                    <div class="form-group mb-0">
                        <input type="text" name="coupon_number" id="coupon_number" placeholder="Enter Coupon Code" class="form-control mb-0" aria-label="Enter Coupon Code" aria-describedby="coupon-code-label">
                        <label for="coupon_number" class="col-form-label">Enter Coupon Code</label>
                        <a href="javascript:void(0);" id="btncouponapply" class="btn btn-secondary" role="button" aria-label="Apply Coupon Code">Apply</a> 
                    </div>
                </div>
            </div>
        </div>
        @endif
            

        @if($CartAttr['allow_gift'] == 1 && $CartAttr['onlyGCPurchased']==0)
        <div class="col-12 mb-2 pb-1">
            <div class="max_coupon_box" role="form" aria-labelledby="gift-card-label">
                <div class="coupan_boxhd plus-minus">
                    <h6 class="f12" id="gift-card-label">E-Gift Card Code</h6>
                </div>
                <div class="cart-discount">
                    <div class="form-group mb-0">
                        <input type="text" id="txtgiftcard" value="{{Session::get('ShoppingCart.GiftCoupon.Code')}}" class="form-control mb-0" aria-label="Enter E-Gift Card Code" aria-describedby="gift-card-label">
                        <label for="txtgiftcard" class="col-form-label">Enter E-Gift Card Code</label>
                        <a href="javascript:void(0);" id="btngiftcard" class="btn btn-secondary" role="button" aria-label="Apply E-Gift Card Code">Apply</a> 
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>