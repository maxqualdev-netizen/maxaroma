<input type="hidden" name="max_hidsubtotal" id="max_hidsubtotal" value="{{Session::get('ShoppingCart.SubTotal')}}"/>
<div class="row">
    <div class="col-12 d-md-none">
    <div class="mobile-shadow mb-0">&nbsp;</div>
	</div>
    <div class="col-12">
		<h4 class="cart-hd-sub text-center pb-4">Shipping Method</h4>
	</div>
	<div class="col-12">
		<div class="pb-4">Place your order in the next {{$datediff}} for delivery by the listed dates.</div>
		@if(isset($ShippingMethods) && count($ShippingMethods) > 0)
			@if(count($ShippingMessage) > 0)
				@foreach($ShippingMessage as $SMSG)
				<div class=@if(isset($MsgSucess) && $MsgSucess!=1) "alert alert-danger" @else "alert alert-success" @endif>	
					{!! $SMSG !!}
				</div>
				@endforeach
			@endif
			@if(isset($ShipMethodError) && $ShipMethodError != '')
				<div class="alert alert-danger">	
					{{$ShipMethodError}}
				</div>
			@endif	
            <form id="frmpayment" class="mb-0" name="frmpayment" method="post" action="{{config('global.SITE_URL')}}payment">
                {{ csrf_field() }}
                <input type="hidden" name="action" id="action" value="paymentinfo"/>
                <input type="hidden" name="OnlyHead" id="OnlyHead" value="0"/>
                <input type="hidden" name="SelPayMethod" id="SelPayMethod" value="{{$SelPayMethod}}"/>
                <div class="row row5 pb-2" id="divinsurance">
                    <div class="col-9">
                        <div>Shipping Protection</div>
                        <div>From Damage, Loss, Theft 
                            @if(Session::has('shipping_insurance_charge'))
                            <span>{{Price(Session::get('shipping_insurance_charge'))}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-3" align="right">
                        <label class="switch active mb-0" id="insurance">
                            <input type="checkbox" id="shipinsurance" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="cart-tooltip max_qttable" id="insure_info">
                            <a href="javascript:void(0);"><u>i</u></a>
                            <span class="tables">Please note by turning Shipping Insurance OFF, you are agreeing to taking full responsibility for any loss, damages or theft. Maxaroma cannot take claim if the insurance is off, however you can submit a claim to the carrier directly. </span>
                        </span>
                    </div>
                </div>
               <div class="row row5 pb-2" id="divshipcerty" data-amt="{{$InsureAmount}}"  >		
                    <div class="col-9">
						@if($InsureAmount <= 199)
                        <div>Request Signature ($2.5)</div>
                        @else
                        <div>Request Signature (It's Free)</div>
                        @endif
                    </div>
                    <div class="col-3" align="right">
                        <label class="mb-0 switch @if($InsureAmount >= 200) active @endif" id="insurance">
                            <input type="checkbox" value="Yes" data-value="@if($InsureAmount >= 200)0 @else 2.5 @endif" @if($InsureAmount >= 200) checked @endif name="shipping_signature" id="shipping_signature">
                            <span class="slider round"></span>
                        </label>
                       <span class="cart-tooltip max_qttable" id="shipcerty_info" >
                            <a href="javascript:void(0);"><u>i</u></a>
                           
                           @if($InsureAmount < 200)
                           <span class="tables">For this order, there is an option to add signature requirements at $2.5. Opting out of signature requests will void any additional reassurances should your package show as delivered according to tracking..</span>
                           @else
                            <span class="tables">For this order, we automatically add signature requirements free of charge. Opting out of signature requests will void any additional reassurances should your package show as delivered according to tracking.</span>
                         @endif
                        </span>
                    </div>	
                </div>
                <div class="pt-3"> 	
                    @foreach($ShippingMethods as $key => $ShippingMethod)
                        <label id="method-{{$key}}" class="comcheck radio d-inline-block w-100 checkbox-label clsship @if(Session::has('ShoppingCart.Shipping.ShippingMethodID') == $ShippingMethod['shipping_mode_id']) active @endif">
                            <div class="chebox">
                                <input type="radio" class="shipmethod" name="shippingModeId" data-key="{{$key}}" id="shippingModeId{{$key}}" value="{{$ShippingMethod['shipping_mode_id']}}" data-estdate="{{$ShippingMethod['estdate']}}" @if(Session::get('ShoppingCart.Shipping.ShippingMethodID') == $ShippingMethod['shipping_mode_id']) checked @endif >
                                <span class="checkmark"></span>
                                <span class="float-left w-75">
                                    <div>
                                        {!!$ShippingMethod['display_date']!!} - {{$ShippingMethod['charge_str']}}
                                        <span class="cart-tooltip max_qttable">	
                                            <a href="javascript:void(0);">
                                                <u>i</u>
                                            </a>
                                            @if($ShippingMethod['shipping_mode_id'] == '33' || $ShippingMethod['shipping_mode_id'] == '34')
                                                <span class="tables">Guaranteed</span>
                                            @else	
                                                <span class="tables">This is an estimated delivery date, and it is not guaranteed.</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div>{!!$ShippingMethod['method_name']!!}</div>	
                                </span>	
                            </div>
                        </label>
                    @endforeach
                    @else
                        <div class="alert alert-danger">There is no shipping method available to your destination. Please fill a different shipping address.</div>	
                    @endif
                <input type="hidden" id="vendor-popup" value="{{$VendorPopup}}"/>
                </div>
        </form>
	</div>
    @if(isset($ShippingMethods) && count($ShippingMethods) > 0 && $PageFrom == '' )
        <div class="col-12 pb-md-5 pt-md-4 mt-md-2">
            <div class="mobile-shadow d-md-none">&nbsp;</div>
            <div class="chekout-main-btn">
                <a class="max_continuesp_btn btn btn-primary d-block" id="btnbill_step2" href="javascript:void(0);">Continue to Payment</a>
            </div>
        </div>
        <div class="col-12 f12 text-center">
            <a href="{{config('global.SITE_URL')}}checkout{{$payment_method_url}}" class="ulink"><strong>Return to Information</strong></a>
        </div>
    @endif
</div>

