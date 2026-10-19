@if($CartAttr['CreditLimitFlag'] != 0)
<div class="row mb-4 mb-md-0">	
	<div class="col-lg-12 pr-lg-3 mt-0 mt-md-4">
		<div class="max_coupon_box p-3">
			<div class="coupan_boxhd credit-p mb-0">
				<div class="text-left">
					<p class="f14 mb-0">
						<strong>
						Your available credit Balance is : 
						@if($CreditDiscount > 0 && $CartAttr['CreditLimitFlag'] == 2)
							{{Session::get('currency_symbol').$CartAttr['RemainCreditLimit']}}
						@else
							{{Session::get('currency_symbol').$CartAttr['CreditLimit']}}
						@endif	
						</strong>
					</p>
				</div>
				<div class="ml-3"> 
					@if($CreditDiscount > 0)
						<a href="javascript:void(0);" class="btn btn-primary" id="btnremovecred" >Remove</a>
					@else
						<a href="javascript:void(0);" id="btncredit" class="btn btn-primary">Apply</a> 
					@endif
				</div>
			</div>
		</div>
	</div>
</div>	
@endif
<div class="row">
	@if($CartAttr['isCouponsAvailable'] == 1)
	<div class="col-lg-6 pr-lg-2 mt-0 mt-md-4">
		<div class="max_coupon_box">
			<div class="coupan_boxhd">
				<div class="float-left text-left">
					<h6 class="">Promotional/Coupon Code</h6>
				</div>
			</div>
			<div class="pb-3 pb-md-4">
				<div class="cart-discount">
					<input type="text" name="coupon_number" id="coupon_number" placeholder="Enter Coupon Code" class="form-control">
					<a href="javascript:void(0);" id="btncouponapply" class="btn btn-primary">Apply</a> 
				</div>
			</div>
		</div>
	</div>
	@endif
	
	@if(($CartAttr['isCouponsAvailable'] == 1 && $CartAttr['coupon_number'] !='') || ($CartAttr['isSecondCouponAvalilabe'] == 1 && $CartAttr['second_coupon_number'] !=''))
	<!--<div class="col-lg-6 pr-lg-2 mt-0 mt-md-4">
		<div class="max_coupon_box">
			<div class="coupan_boxhd">
				<div class="float-left text-left">
					@if($CartAttr['coupon_number'] !='')
						<!--<h6 class="">
							Applied Coupon Code : {{$CartAttr['coupon_number']}} 
							<a href="javascript:void(0);" class="remdis" title="Remove" data-id="CouponDiscount">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
									<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
								</svg>
							</a>
						</h6>-->
					<!--@endif
					@if($CartAttr['second_coupon_number'] !='')
						<h6 class="">
							Applied Coupon Code : {{$CartAttr['second_coupon_number']}} 
						</h6>
					@endif	
				</div>
			</div>
		</div>
	</div>-->
	@endif		
	
	@if($CartAttr['allow_gift'] == 1)
	<div class="col-lg-6 pl-lg-2 mt-4">
		<div class="max_coupon_box">
			<div class="coupan_boxhd">
				<div class="float-left">
					<h6 class="">E-Gift Card Code</h6>
				</div>
			</div>
			<div class="pb-3 pb-md-4">
				<div class="cart-discount">
					<input type="text" id="txtgiftcard" value="{{Session::get('ShoppingCart.GiftCoupon.Code')}}" placeholder="Enter E-Gift Card Code" class="form-control">
					<a href="javascript:void(0);" id="btngiftcard" class="btn btn-primary">Apply</a> 
				</div>
			</div>
		</div>
	</div>
	@endif
	
	@if($CartAttr['FundFlag'] == 1)
	<div class="col-lg-6 pl-lg-2 mt-4">
		<div class="max_coupon_box">
			<div class="coupan_boxhd">
				<div class="float-left">
					<h6 class="">Available Fund : {{Price($CartAttr['available_funds'])}}</h6><br>
                    <x-button btype="button" btntext="Add New Fund" classname="btn btn-primary" bid="btnAdddFund" />
				</div>
			</div>
		</div>
	</div>
	@endif
	<div class="col-12 mt-4 d-none d-md-block"> 
		<x-button link="{{config('global.SITE_URL')}}" btype="button" btntext="Continue Shopping" classname="btn btn-primary" bid="btnContinue" />
	</div>
	<div class="col-12 mt-4 mb-4 d-none d-md-block"> 
		<strong>Please note:</strong> Items are not reserved until checkout is complete. Prices are subject to change based on the price in effect the day you checkout.
	</div>
</div>
