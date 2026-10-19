@if($CartAttr['CreditLimitFlag'] != 0)
    <div class="col-12 f12 pt-md-1 pb-4">
        Your available credit Balance is : 
        @if($CreditDiscount > 0 && $CartAttr['CreditLimitFlag'] == 2)
            {{Session::get('currency_symbol').$CartAttr['RemainCreditLimit']}}
        @else
            {{Session::get('currency_symbol').$CartAttr['CreditLimit']}}
        @endif
        @if($CreditDiscount > 0)
            <a href="javascript:void(0);" class="ulink d-inline-block ml-2" id="btnremovecred" ><strong>Remove</strong></a>
        @else
            <a href="javascript:void(0);" id="btncredit" class="ulink d-inline-block ml-2"><strong>Apply</strong></a> 
        @endif
    </div>
@endif
@if($CartAttr['FundFlag'] == 1)
    <div class="col-12 f12 pt-md-1 pb-4">
        Available Fund : {{Price($CartAttr['available_funds'])}}
        <a href="javascript:void(0);" class="ulink d-inline-block ml-2" id="btnAdddFund" ><strong>Add New Fund</strong></a>
    </div>
@endif
<div class="col-12">
    <div class="row">
        @if($CartAttr['isCouponsAvailable'] == 1 )
        <div class="col-12 mb-2 pb-1">
            <div class="max_coupon_box">
                <div class="coupan_boxhd plus-minus">
                    <h6 class="f12">Promotional/Coupon Code</h6>
                </div>
                <div class="cart-discount">
                    <div class="form-group mb-0">
                        <input type="text" name="coupon_number" id="coupon_number" placeholder="Enter Coupon Code" class="form-control mb-0">
                        <label for="guest_confirm_password" class="col-form-label">Enter Coupon Code</label>
                        <a href="javascript:void(0);" id="btncouponapply" class="btn btn-secondary">Apply</a> 
                    </div>
                </div>
            </div>
        </div>
        @endif
        	

        @if($CartAttr['allow_gift'] == 1)
        <div class="col-12 mb-2 pb-1">
            <div class="max_coupon_box">
                <div class="coupan_boxhd plus-minus">
                    <h6 class="f12">E-Gift Card Code</h6>
                </div>
                <div class="cart-discount">
                        <div class="form-group mb-0">
                            <input type="text" id="txtgiftcard" value="{{Session::get('ShoppingCart.GiftCoupon.Code')}}" class="form-control mb-0">
                            <label for="guest_confirm_password" class="col-form-label">Enter E-Gift Card Code</label>
                            <a href="javascript:void(0);" id="btngiftcard" class="btn btn-secondary">Apply</a> 
                        </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>    
