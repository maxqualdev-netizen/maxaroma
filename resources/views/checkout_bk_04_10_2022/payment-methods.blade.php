<form method="post" action="{{route('placeorder')}}" name="order_process" id="order_process">
@if(isset($OnlyHead) && $OnlyHead == '1')
	<h3 class="cart-hd d-none d-md-block">Payment Info</h3>
	<h3 class="cart-hd d-md-none">3. Payment</h3>
@else
	<h3 class="cart-hd d-none d-md-block">Payment Info</h3>
	<h3 class="cart-hd d-md-none">3. Payment</h3>
	@if($show=="Yes" || $MethodNoShow=="Yes")
		<div class="pt-3">
			<div class="alert alert-danger">
				To complete the order, Please use 'Checkout with Paypal' OR 'Pay with Amazon' payment option on shopping cart page.  <a href="{{url('/shoppingcart')}}">click here </a> to go shopping cart.
			</div>
		</div>	
	@else
		<div class="pt-3">
		@if(count($PaymentMethodList) > 0)
			@if($OnlyWT == 1 && Session::get('eusertype') == "Retailer")
				<div class="alert alert-danger">
					Checkout is temporarily unavailable at this time, Please contact administrator.
				</div>	
			@else
				@if($onlyAmazonPaypal != 1)
						<input type="hidden" name="shipsignatureflag" id="shipsignatureflag" value=""/>
						{{ csrf_field() }}
						@if($Is_Afterpay_Checkout == 'No')
							<div style="display:none;">
								<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
							</div>	
						@endif	
						@foreach($PaymentMethodList as $pkey => $PMethod)				
							@if((Session::get('eusertype') == "" || Session::get('eusertype') == "Retailer") && $PMethod['pm_name'] != "Wire Transfer")
								<label @if($NetTotal <= 0 ) style="display:none !important;" @endif class="comcheck radio d-inline-block w-100 checkbox-label pay-radio card-pay credit-methods">
									<div class="chebox">
										<input type="radio" name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}" @if($NetTotal > 0  && ((isset($arrPaymentDetail) && $arrPaymentDetail['Payment_Type'] == $PMethod['pm_group_name']) || $SelMethod == $PMethod['pm_group_name'] || $pkey==0 || $ptype == 'AP')) checked @endif >
										<span class="checkmark"></span>
										<span class="float-left w-100">{{$PMethod['pm_name']}}
											@if($PMethod['pm_group_name'] == 'PAYMENT_STRIPE')
												<img src="{{config('global.SITE_IMAGES')}}/all-card.png" alt="" class="img-fluid all-card">
											@endif
										</span>
									</div>
									@if($PMethod['pm_group_name'] == 'PAYMENT_PAYWITHAFTERPAY')
										<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
									@endif
								</label>
							@endif
							
							@if(Session::get('eusertype') == "Wholesaler")
								<label @if($NetTotal <= 0 ) style="display:none !important;" @endif class="comcheck radio d-inline-block w-100 checkbox-label pay-radio card-pay credit-methods">
									<div class="chebox">
										<input type="radio" checked name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}" @if($NetTotal > 0  && ((isset($arrPaymentDetail) && $arrPaymentDetail['Payment_Type'] == $PMethod['pm_group_name']) || $SelMethod == $PMethod['pm_group_name'] || $pkey==0 || $ptype == 'AP')) checked @endif >
										<span class="checkmark"></span>
										<span class="float-left w-100">{{$PMethod['pm_name']}}
											@if($PMethod['pm_group_name'] == 'PAYMENT_STRIPE')
												<img src="{{config('global.SITE_IMAGES')}}/all-card.png" alt="" class="img-fluid all-card">
											@endif
										</span>
									</div>
									@if($PMethod['pm_group_name'] == 'PAYMENT_PAYWITHAFTERPAY')
										<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
									@endif
								</label>
							@endif	
						@endforeach
						<label id="PaymentDetailsStoreView" @if($NetTotal != 0) style="display:none !important;" @endif class="comcheck radio d-inline-block w-100 checkbox-label pay-radio card-pay">
							<div class="chebox">
								<input type="radio" name="PaymentMethod" id="paytypeIDCL1" value="PAYMENT_CL" >
								<span class="checkmark"></span>
								<span class="float-left w-100">Credit Limit</span>
							</div>
						</label>	
						<input type="hidden" name="is_paypal" id="is_paypal" value="{{$is_paypal}}"/>	
				@endif
			@endif	 
		@else
			<div class="alert alert-danger">
				@if(Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') =='Wholesaler')
					You do not have sufficient balance in your dropship account. To complete your order, please <a href="javascript:void(0)" class="add-fund" >Add Fund</a> to your account.
				@else	 
					Checkout is temporarily unavailable at this time, Please contact administrator.
				@endif
			</div>	
		@endif
		@if(Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') =='Wholesaler')
		<div class="row">	
			<div class="col-lg-12 mt-0 mt-md-4">
				<div class="max_coupon_box">
					<div class="coupan_boxhd">
						<div class="col-12 text-center">
							<div>Dropshipper Information</div>
							@if($DropshipperAccountDetails['fund_msg'] != "")
								<div class="alert alert-danger">
									{{$DropshipperAccountDetails['fund_msg']}}
								</div>
							@endif	
							<table width="100%" border="0">
								<tr>
									<th>Total Fund</th>
									<th>Required Amount</th>
									<th>
										@if($DropshipperAccountDetails['fund_available'] == 'Yes')
											Remaining Fund
										@else
											Required Fund
										@endif
									</th>
								</tr>
								<tr>
									<td align="center">{{Price($DropshipperAccountDetails['total_fund'])}}</td>
									<td align="center">{{Price($DropshipperAccountDetails['total_payment'])}}</td>
									<td align="center">
										@if($DropshipperAccountDetails['fund_available'] == 'Yes')
											{{Price($DropshipperAccountDetails['remaining_fund'])}}
										@else
											{{Price($DropshipperAccountDetails['required_fund'])}} | <a href="javascript:void(0);" class="add-fund" >Add Fund</a>
										@endif
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>	
		@endif	
		@if(count($PaymentMethodList) > 0 && $onlyAmazonPaypal != 1 && $CartAttr['CreditLimitFlag'] != 0)
		<label class="checkbox-label che-box w-100">
			<div class="chebox">
				@if($CreditDiscount > 0 && $CartAttr['CreditLimitFlag'] == 2)
					<input type="checkbox" checked name="chkcreditlimit" id="chkcreditlimit" value="{{$CartAttr['RemainCreditLimit']}}" @if($CartAttr['onlyGCPurchased'] == '1') disabled @endif />
					<span class="checkmark"></span>
					<span class="float-left w-100" id="credamt">
						@if($CartAttr['RemainCreditLimit'] > 0)
							Use your account balance, Your account balance is : {{Price($CartAttr['RemainCreditLimit'])}}
						@else 
							Your account balance has been applied.
						@endif
					</span>
				@else
					<input type="checkbox" name="chkcreditlimit" id="chkcreditlimit" value="{{$CartAttr['CreditLimit']}}" />
					<span class="checkmark"></span>
					<span class="float-left w-100" id="credamt">
						Use your account balance, Your account balance is : {{Price($CartAttr['CreditLimit'])}}
					</span>	
				@endif
			</div>
		</label>
		@endif	
	</div>
	@if(count($PaymentMethodList) > 0 && $onlyAmazonPaypal != 1)
		@if($giftflag == 1)
			<div class="gift-card">
				<div class="actmenuhd">
					<svg class="svg-newgift vam" aria-hidden="true" role="img" width="24" height="24">
						<use href="#svg-newgift" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newgift"></use>
					</svg>
					<span>Add gift card</span>
					<svg id="giftcard-down-arraw" class="sv-down-arrow vam float-right" aria-hidden="true" role="img" width="20" height="20">
						<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
					</svg>
				</div>
				<div class="actmenu-inner mt-4" id="divgiftcard">
					<form name="frmgiftcard" id="frmgiftcard">
						<div class="row pt-3">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-form-label">From<span class="errmsg">*</span></label>
									<input id="gift_from" name="gift_from" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftFrom')}}" >
									<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_from']"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-form-label">To<span class="errmsg">*</span></label>
									<input id="gift_to" name="gift_to" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftTo')}}">
									<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_to']"/>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="phone" class="col-form-label">Gift Message</label>
									<textarea id="gift_message_customer" name="gift_message_customer" rows="4" cols="50" type="text" class="form-control">{{Session::get('ShoppingCart.GiftMessageCustomer')}}</textarea>
									<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_message_customer']"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-form-label">Select Gift<span class="errmsg">*</span></label>
									{!! $freegiftcombo !!}
									<x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_freegiftvalue']"/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-actions clearfix pt-0 pb-0">                       
									<div class="float-left">
										<a href="javascript:void(0);" id="btnappygiftcard" class="btn btn-primary">Apply</a>
										<a href="#" class="ulink cancel-btn">Cancel</a> 
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		@endif
		@if($OnlyWT == 1 && Session::get('eusertype') == "Retailer")
		@else
		<div class="pt-3">
			@if($dropshipFundSection == 'No')
				<div>
					<div class="form-group">
						<label for="customer_comment">Special Request</label>
						<textarea id="customer_comment" class="form-control" name="customer_comment" placeholder="Special Request"></textarea>
					</div>	
				</div>	
			@endif	
			<label class="checkbox-label che-box w-100">
				<div class="chebox">
					<input type="checkbox" checked id="chkagree"><span class="checkmark"></span>
					<span class="float-left w-100">By placing and order on MaxAroma.com you agree with the <a href="{{url('/terms-and-conditions.html')}}" target="_blank">terms and conditions</a>.</span>
				</div>
			</label>
			<a href="javascript:void(0);" class="btn btn-primary d-block checkout-btn" id="btnPlaceOrder">Place Order</a>
		</div>
		@endif
		@endif
	@endif	
@endif
</form>
