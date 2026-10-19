@if($CurrentRoute == 'shoppingcart')
	<script src="https://js.stripe.com/v3/"></script>	
	<script>
		function GetShippingOptions(state='',zip='',country=''){
			var shippingmodes = [];
			$.ajax({
				type:'POST',
				url:site_url+'shippinginfo',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				async:false,
				data:{
					'OnlyHead':'0',
					'action':'shippinginfo',
					'subaction' : 'stripecart',
					'state' : state,
					'zip' : zip,
					'country' : country,
				},
				datatype: 'JSON',
				success:function(data) {
					shippingmodes = data;
					SetStripeShippingMethod(data[0].id,state,zip,country);
				}
			});
			return shippingmodes;
		}
		function GetStripeCart()
		{
			$("#page-spinner").show();
			var items = [];
			var NetTotal = 0;
			$.ajax({
				type:'POST',
				url:site_url+'getstripecart',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				async:false,
				data:{
					'action' : 'getcart',
				},
				datatype: 'JSON',
				success:function(data) {
					items = data.items;
					NetTotal = data.NetTotal;
					$("#page-spinner").hide();
				}
			});
			var CartData = {'items':items,'NetTotal' : NetTotal};
			return CartData;
		}
		function SetStripeShippingMethod(ShipMethodID,state='',zip='',country='')
		{
			$.ajax({
				type:'POST',
				url:site_url+'setshipmethod',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				async:false,
				datatype: 'JSON',
				data:{
					action : 'stripecart',	
					ShipMethodID: ShipMethodID,
					state:state,
					zip:zip,
					country:country
				},
				success:function(data) {	
				},
			});
		}
		function GetClientSecret()
		{
			var clientSecret = "";
			$.ajax({
				type:'POST',
				url:site_url+'getclientsecret',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				async:false,
				datatype: 'JSON',
				data:{
					action : 'clientsecret',
				},
				success:function(data) {	
					clientSecret = data.clientSecret;
				},
			});
			return clientSecret;
		}
		$(document).ready(function(){
			var stripe = Stripe('{{env("STRIPE_SECRET")}}', {
				apiVersion: "2019-05-16",
			});
			var paymentRequest = stripe.paymentRequest({
				country: 'US',
				currency: 'usd',
				total: {
					label: 'Order Total',
					amount: {{(config('ShopTotal')*100)}},
				},
				requestShipping: true,
				requestPayerName: true,
				requestPayerEmail: true,
			});
			var elements = stripe.elements();
			var prButton = elements.create('paymentRequestButton', {
				paymentRequest: paymentRequest,
			});
			prButton.on('click',function(ev){
				var CartData = GetStripeCart();
				paymentRequest.update({
					total: {
						label: 'Order Total',
						amount: CartData.NetTotal,
					},
					displayItems : CartData.items,
					shippingOptions: GetShippingOptions(),
				});
			})
			// Check the availability of the Payment Request API first.
			paymentRequest.canMakePayment().then(function(result) {
				if (result) {
					prButton.mount('#payment-request-button');
				} else {
					document.getElementById('payment-request-button').style.display = 'none';
				}
			});
			
			paymentRequest.on('shippingoptionchange', function(event) {
				console.log(event);
				var updateWith = event.updateWith;
				var ShipMethodID = event.shippingOption.id;
				SetStripeShippingMethod(ShipMethodID);
				var CartData = GetStripeCart();
				updateWith({
					status: 'success',
					total: {
						label: 'Order Total',
						amount: CartData.NetTotal,
					},
					displayItems : CartData.items,
				});	
			});
			paymentRequest.on('shippingaddresschange', function(ev) {
				var updateWith = ev.updateWith;
				if (ev.shippingAddress.country !== 'US') {
					ev.updateWith({status: 'invalid_shipping_address'});
				} else {
					var state = ev.shippingAddress.region;
					var zip = ev.shippingAddress.postalCode;
					var country = ev.shippingAddress.country;
					updateWith({
						status: 'success',
						shippingOptions: GetShippingOptions(state,zip,country),
					});
				}
			});

			paymentRequest.on('paymentmethod', function(ev) {
				var clientSecret = GetClientSecret();
				// Confirm the PaymentIntent without handling potential next actions (yet).
				stripe.confirmCardPayment(
					clientSecret,
					{payment_method: ev.paymentMethod.id},
					{handleActions: false}
				).then(function(confirmResult) {
					if (confirmResult.error) {
					// Report to the browser that the payment failed, prompting it to
					// re-show the payment interface, or show an error message and close
					// the payment interface.
					ev.complete('fail');
				} else {
					// Report to the browser that the confirmation was successful, prompting
					// it to close the browser payment method collection interface.
					console.log(confirmResult);
					console.log(ev);
					$.ajax({
						type:'POST',
						url:site_url+'stripebtnres',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						datatype: 'JSON',
						data:{	
							'methodName':ev.methodName,	
							'payerEmail':ev.payerEmail,
							'payerName':ev.payerName,
							'paymentMethod':ev.paymentMethod.id,
							'shippingOption':ev.shippingOption,
							'shippingAddress':ev.shippingAddress,	
						},
						success:function(data) {
							if(data.status == 'success')
							{
								$("#frmstripe").submit();
							}
						}
					});
					ev.complete('success');
					// Check if the PaymentIntent requires any actions and if so let Stripe.js
					// handle the flow. If using an API version older than "2019-02-11"
					// instead check for: `paymentIntent.status === "requires_source_action"`.
					if (confirmResult.paymentIntent.status === "requires_action") {
						// Let Stripe.js handle the rest of the payment flow.
						stripe.confirmCardPayment(clientSecret).then(function(result) {
						if (result.error) {
							// The payment failed -- ask your customer for a new payment method.
						} else {
							// The payment has succeeded.
						}
					});
				} else {
					// The payment has succeeded.
				}
			}
		});
	});	
});
				
	</script>	
@endif