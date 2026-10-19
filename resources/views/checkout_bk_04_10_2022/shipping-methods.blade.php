@if(isset($OnlyHead) && $OnlyHead == '1')
<h3 class="cart-hd d-none d-md-block">Shipping</h3>
<h3 class="cart-hd d-md-none">2. Shipping Method</h3>
@else 
<!--<script src="https://cdn.routeapp.io/route-widget/stable/route-widget-stable.min.js"></script>-->
<input type="hidden" name="max_hidsubtotal" id="max_hidsubtotal" value="{{Session::get('ShoppingCart.SubTotal')}}"/>
<h3 class="cart-hd d-none d-md-block">Shipping</h3>
<h3 class="cart-hd d-md-none">2. Shipping Method</h3>
<div class="pt-3">
	<div class="pb-3"><strong>Place your order in the next {{$datediff}} for delivery by the listed dates.</strong></div>
	@if(isset($ShippingMethods) && count($ShippingMethods) > 0)
		@if(count($ShippingMessage) > 0)
			@foreach($ShippingMessage as $SMSG)
			<div class="alert alert-danger">	
				{{$SMSG}}
			</div>
			@endforeach
		@endif
		@if(isset($ShipMethodError) && $ShipMethodError != '')
			<div class="alert alert-danger">	
				{{$ShipMethodError}}
			</div>
		@endif	
		<div class="row row5" id="divinsurance">		
			<div class="col-10 pb-3" >
				<div class="d-flex align-items-center">
					<div>
						<div class="shiphead">
							Shipping Protection
							<span class="cart-tooltip max_qttable" id="insure_info">
								<a href="#"><u>i</u></a>
								<span class="tables">Insure your package from Damage, Loss & Theft. please contact us to file a claim if there is an issue with your order. </span>
							</span>	
						</div>
						<div class="shipsubhead">From Damage, Loss, Theft 
							@if(Session::has('shipping_insurance_charge'))
							<span class="shipinsprice">{{Price(Session::get('shipping_insurance_charge'))}}</span>
							@endif
						</div>
					</div>	
					<div class="ship-sur ml-2">
						<label class="switch active mb-0" id="insurance">
							<input type="checkbox" id="shipinsurance" checked>
							<span class="slider round"></span>
						</label>
					</div>
				</div>
			</div>	
		</div>
		<div class="row" id="divshipcerty" data-amt="{{$InsureAmount}}" @if($InsureAmount < 200) style="display:none;" @endif >		
			<div class="col-12 pb-3">
				<div class="d-flex align-items-center">
					<span>Request Signature <span>@if($InsureAmount >= 200) (It's Free) @else {{(Price(2.5))}} @endif</span></span>
					<div class="ml-2">
						<label class="mb-0 switch @if($InsureAmount >= 200) active @endif" id="insurance">
						<input type="checkbox" value="Yes" data-value="@if($InsureAmount >= 200)0 @else 2.5 @endif" @if($InsureAmount >= 200) checked @endif name="shipping_signature" id="shipping_signature">
						<span class="slider round"></span>
						</label>
						<span class="cart-tooltip max_qttable" id="shipcerty_info" @if($InsureAmount > 200) style="display:content;" @else style="display:none;" @endif>
						<a href="#"><u>i</u></a>
						<span class="tables">For this order, we automatically add signature requirements free of charge. Opting out of signature requests will void any additional reassurances should your package show as delivered according to tracking.</span>
						</span>
					</div>
				</div>
			</div>	
		</div>	
	<!--
	{!!$RouteWidget!!}
	
	@foreach($ShippingSignatureInfo as $skey => $ShippingSignature)
		<div class="row">
			<div class="col-12">
				{!!$ShippingSignature!!}
			</div>
		</div>		
	@endforeach	
	-->
		@foreach($ShippingMethods as $key => $ShippingMethod)
			<label id="method-{{$key}}" class="comcheck radio d-inline-block w-100 checkbox-label clsship @if($key == 0) active @endif">
				<div class="chebox">
					<input type="radio" class="shipmethod" name="shippingModeId" data-key="{{$key}}" id="shippingModeId{{$key}}" value="{{$ShippingMethod['shipping_mode_id']}}" data-estdate="{{$ShippingMethod['estdate']}}" @if($key == 0) checked @endif >
					<span class="checkmark"></span>
					<span class="float-left w-75">
						<div>
							<strong>{!!$ShippingMethod['display_date']!!} - {{$ShippingMethod['charge_str']}}</strong>
							<span class="cart-tooltip max_qttable">	
								<a href="#">
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
			<!--
			<label class="comcheck radio d-inline-block w-100 checkbox-label">
				<div class="chebox">
					<input type="radio" class="shipmethod" name="shippingModeId" id="shippingModeId{{$key}}" value="{{$ShippingMethod['shipping_mode_id']}}" {{$ShippingMethod['checked']}} >
					<span class="checkmark"></span>
					<span class="float-left w-75">
						{!!$ShippingMethod['method_name']!!} 
						@if($ShippingMethod['display_date'] != '') ({!!$ShippingMethod['display_date']!!}) @endif
						
						<span class="cart-tooltip max_qttable">	
							<a href="#">
								<u>i</u>
							</a>
							@if($ShippingMethod['shipping_mode_id'] == '33' || $ShippingMethod['shipping_mode_id'] == '34')
								<span class="tables">Guaranteed</span>
							@else	
								<span class="tables">This is an estimated delivery date, and it is not guaranteed.</span>
							@endif
						</span>
					</span>	
					<span class="float-right">{{$ShippingMethod['charge_str']}}</span>
				</div>
			</label>
			-->
		@endforeach
	@else
		<div class="alert alert-danger">There is no shipping method available to your destination. Please fill a different shipping address.</div>	
	@endif
	<input type="hidden" id="vendor-popup" value="{{$VendorPopup}}"/>	
</div>
@if(isset($ShippingMethods) && count($ShippingMethods) > 0 && $PageFrom == '' )
<div class="mt-3 mb-3">
	<a class="max_continuesp_btn btn btn-primary d-block" id="btnbill_step2" href="javascript:void(0);">CONTINUE TO PAYMENT</a>
</div>
@endif
@endif