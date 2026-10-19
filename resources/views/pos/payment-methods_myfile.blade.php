


<form method="post" action="{{route('placeorder')}}" name="order_process" id="order_process">
	<input type="hidden" name="shipping_signature" id="shipping_signature" value="{{$shipping_signature}}"/>
	
<div class="row">
    <div class="col-12 d-md-none">
        <div class="mobile-shadow mb-0">&nbsp;</div>
    </div>
	<div class="col-12">
		<h4 class="cart-hd-sub text-center">Payment</h4>
	</div>
	<div class="col-12">    
            <div>
            @if(count($PaymentMethodList) > 0)
               
                        <input type="hidden" name="shipsignatureflag" id="shipsignatureflag" value=""/>
                        <input type="hidden" name="ap_psChecksum" id="ap_psChecksum" value="">
						
                        {{ csrf_field() }}
                       
                        <div class="pb-2 mb-1">All transactions are secure and encrypted.</div>
                        @foreach($PaymentMethodList as $pkey => $PMethod)				
                           
                                <label class="comcheck radio checkbox-label pay-radio card-pay credit-methods w-100">
                                    <div class="chebox">
                                        <input type="radio" name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}"
                                       onclick="ShowPaymentMethod()"  @if($PMethod['pm_group_name'] == 'PAYMENT_SPLIT')class="dynamic-textbox" @endif>
                                        <span class="checkmark"></span>
                                        <span class="fw500">{{$PMethod['pm_name']}}
                                            
                                        </span>
                                    </div>
                                   
                                </label>

                            
                        @endforeach


                  <div id="extraField" style="display:none;" class="row pt-2">
                    <div class="col-4">
                        <div class="form-group mb-0">
                        <label for="CashPayment" class="col-form-label">Cash</label>
                        <input id="CashPayment" name="CashPayment" type="text"  class="form-control">
						</div>
                </div>
				
				<div class="col-4">
                    <div id="textbox-container">
                        <div class="textbox-wrapper form-group mb-0">
                            <label for="txtcreditcard" class="col-form-label">CreditCard</label>
                            <input id="txtcreditcard" name="txtcreditcard[]" type="text"  class="form-control dynamic-textbox">
                            <span class="confirm-msg"></span> </div>
                        </div>
                </div>
                <div class="col-4" id="confirm-container">
                    <a href="javascript:void(0)" class="confirm-link btn btn-secondary">Confirm</a>
                </div>
				
				<div id="error-msg" style="color:red;"></div>
				<div id="remaining-msg" class="col-12 text-center pt-3 fw500 f14" style="color:green;">Amount of order $<span id="remaining-amount">{{ $NetTotal }}</span></div>
				</div>   
				
				<!--Store Payment Design Start-->
				
				<!--Store Payment Design End-->
				
				  	
				  
            @endif
 
           
        </div>
        @if(count($PaymentMethodList) > 0)
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

            <div class="row">
				 <div class="col-12">
                    <div class="form-group">
                        <h4 class="cart-hd-sub text-center pt-4 mt-md-3 mb-md-2 pb-4 fw500">Special Request</h4>
                        <textarea id="customer_comment" class="form-control" name="customer_comment" placeholder="Special Request" ></textarea>
                    </div>	
                </div>
                <div class="col-12 pt-1 pb-md-3">
                    <label class="checkbox-label che-box">
                        <div class="chebox">
                            <input type="checkbox" checked id="chkagree"><span class="checkmark"></span>
                            <span>By placing and order on MaxAroma.com you agree with the <a href="{{url('/terms-and-conditions.html')}}" target="_blank">terms and conditions</a>.</span>
                        </div>
                    </label>
                </div>
                <div class="col-12 d-md-none">
                    <div class="mobile-shadow">&nbsp;</div>
                </div>
                <div class="col-12 pt-md-3 pb-md-5">
                    <div class="chekout-main-btn" id="btnPlaceOrderVal" style="display:none;">	
                        <a href="javascript:void(0);" class="btn btn-primary d-block checkout-btn" id="btnPlaceOrder" >Place Order</a>
                    </div>
                </div>
                <div class="col-12 f12 text-center">
                     <a href="{{config('global.SITE_URL')}}shipping" class="ulink"><strong>Return to Shipping</strong></a>
                </div>
            </div>
		@endif
	
</div>

</form>
<script type="text/javascript">
const maxTotal = {{ $NetTotal }};
</script>
