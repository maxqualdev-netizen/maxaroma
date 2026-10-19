@if($Is_Afterpay_Checkout == "Yes")
<script>
	// ensure this function is defined before loading afterpay.js
	function initAfterpayCheckout() {
		setTimeout(function(){
			AfterPay.initializeForPopup({
				countryCode: 'US',
				onCommenceCheckout: function (actions) {
					/* retrieve afterpay token from your server */
					/* then call `actions.resolve(token)` */
					// actions.resolve('$Afterpay_Token');
					
					 $.ajax({
							type: "POST",
							url: site_url+"afterpay/placeorder_express",
							headers: {
								'X-CSRF-TOKEN': token
							},
							data: "get_token=1",
							dataType: "JSON",
							success: function(response){
								  if(response.success == 1 && response.token != ""){
									  actions.resolve(response.token);
								  }else{
									  alert(response.message);
									  // location.reload();
									  return false;
								  }
						}
					})
				},onShippingAddressChange: function (data, actions) {
					/* address in `data` */
					/* calc options, then call `actions.resolve(options)` */
					console.log(data);
					
				},
				onComplete: function (event) {
					$("#page-spinner").show()
					console.log(event);
					/* handle success/failure of checkout */
					if (event.data.status == "SUCCESS") {
					  // The consumer has confirmed the payment schedule.
					  // Call your server here to retrieve the order details
					  var order_token =  event.data.orderToken;
					  var merchant_reference =  event.data.merchantReference;
					  
					  location.href = site_url+"afterpay/billing_checkout_express/1/"+order_token;
					  return false;
					} else {
					  var order_token =  "undefined";
					   
					  // The consumer cancelled the payment or closed the popup window.
					  location.href = site_url+"afterpay/billing_checkout_express/0/"+order_token;
					  return false;
					}
				},
				target: '#afterpay-button',
				shippingOptionRequired: false,
			})
		},500);
	}
</script>
<!-- <script type="text/javascript" src="https://static-us.afterpay.com/javascript/present-afterpay.js"></script>-->
<script src="{!! $token_js_url !!}" async onload="initAfterpayCheckout()"></script>
@endif

<ul class="pt-md-2">
	<li>
		@if($Is_Afterpay_Checkout == "Yes")
			<a id="afterpay-button" data-afterpay-entry-point="cart" class="btn btn-primary d-block" href="javascript:void(0)">Pay with Afterpay</a>
		@endif	
	</li>
	
	<li>
		@if($CartAttr['IsPaypalExpressCheckout'] == 'Yes')
			<a class="paypal-btn btn btn-primary d-block" href="{{url('paypal/placeorder')}}"> 
				Pay with Paypal 
			</a> 
		@endif
	</li>
	<li>
		@if($CartAttr['Amazon_pay_Checkout'] == 'Yes')
			<a class="amazon-btn btn btn-primary d-block" href="javascript:void(0);"> 
				Pay with Amazon 
			</a> 
			<div id="AmazonPayButtonAll" class="d-none"></div>
			<script type="text/javascript">
				window.onAmazonLoginReady = function() {
					amazon.Login.setClientId("{{config('CLIENT_ID')}}");
					amazon.Login.setUseCookie(true);
				};
				var call_url= "{{config('CALLBACK_CHECKOUT_URL')}}";
			</script>
			<script type="text/javascript" src="{{config('JS_SERVER_URL')}}"></script>
			<script>
				var authRequest;
					OffAmazonPayments.Button("AmazonPayButtonAll", "{{config('MERCHANT_ID')}}", {
						type: "PwA",
						size: "large",
						authorization: function () {
							loginOptions = { scope: "profile postal_code payments:widget payments:shipping_address", popup: true };
							authRequest = amazon.Login.authorize(loginOptions, call_url);
					},
					onError: function (error) {
						// something bad happened
					}
				});
			</script>
		@endif
	</li>
</ul>
@if(config('global.StripeButton') == 'Show')
	<div class="row">
		<div class="col-12 mt-3 text-center" id="payment-request-button">
			<!-- A Stripe Element will be inserted here. -->
		</div>
		<form id="frmstripe" method="post" action="{{url('placeorder')}}">
			{{ csrf_field() }}
			<input type="hidden" name="PaymentMethod" value="PAYMENT_STRIPE_BUTTON"/>
		</form>
		<div id="shoptotal" style="display:none;">{{$NetTotal}}</div>
	</div>
	@endif	
