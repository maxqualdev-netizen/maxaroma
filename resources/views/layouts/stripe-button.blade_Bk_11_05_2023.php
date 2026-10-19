@if($CurrentRoute == 'billing' )
	<script src="https://js.stripe.com/v3/"></script>	
	<script>
	
		function GetShippingOptions(state='',zip='',country=''){
			//alert(state);
			//alert(zip);
			//alert(country);
			var shippingmodes = [];
			$.ajax({
				type:'POST',
				url:site_url+'shipping',
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
					'Gpay'	  : 'Yes',	
				},
				datatype: 'JSON',
				success:function(data) {
					//alert(data);
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
					amount: {{round($NetTotal*100)}},
				},
				requestShipping: true,
				requestPayerName: true,
				requestPayerEmail: true,
				/*shippingOptions: [
					// The first shipping option in this list appears as the default
					// option in the browser payment interface.
					{
					  id: 'free-shipping',
					  label: 'Free shipping',
					  detail: 'Arrives in 5 to 7 days',
					  amount: 0,
					},
				  ],*/
			});
			
			
			var elements = stripe.elements();
			
			var prButton = elements.create('paymentRequestButton', {
				paymentRequest: paymentRequest,
				 style: {
					paymentRequestButton: {
					  type: 'default',	// One of 'default', 'book', 'buy', or 'donate'
					  theme: 'dark',	// One of 'dark', 'light', or 'light-outline'
					  // height: '64px'	// Defaults to '40px'. The width is always '100%'.
					},
				  },
			});

			prButton.on('click',function(ev){
				var CartData = GetStripeCart();
                  
				paymentRequest.update({
					total: {
						label: 'Order Total',
						amount: CartData.NetTotal,
					},
					displayItems : CartData.items,
					// shippingOptions: GetShippingOptions(),
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
				//var state = event.shippingAddress.region;
			//	var zip = event.shippingAddress.postalCode;
				//var country = event.shippingAddress.country;
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

			paymentRequest.on('shippingaddresschange', async (ev) => {
			  if (ev.shippingAddress.country !== 'US') {
				ev.updateWith({status: 'invalid_shipping_address'});
			  } else {
				var state = ev.shippingAddress.region;
				var zip = ev.shippingAddress.postalCode;
				var country = ev.shippingAddress.country;  
				var datas = GetShippingOptions(state,zip,country);
				//alert(datas);
				ev.updateWith({
				  status: 'success',
				  shippingOptions: datas,
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
					$("#dd123").val(confirmResult+"\n\n\n"+ev);
										
					if (confirmResult.error) {
					alert('Your payment has failed. Please try again or use different payment option.');
					console.log('Could not confirm result: ', confirmResult.error);
					ev.complete('fail');

					//alert('fail');
					
				} else {
					// Report to the browser that the confirmation was successful, prompting
					// it to close the browser payment method collection interface.
					console.log("confirmResult=="+confirmResult);
					//console.log(ev.paymentMethod.id);
					
					ev.complete('success');
					//alert("success");
					// Check if the PaymentIntent requires any actions and if so let Stripe.js
					// handle the flow. If using an API version older than "2019-02-11"
					// instead check for: `paymentIntent.status === "requires_source_action"`.
					if (confirmResult.paymentIntent.status === "requires_action") {
						// Let Stripe.js handle the rest of the payment flow.
						stripe.confirmCardPayment(clientSecret).then(function(result) {
						if (result.error) {
							 alert('Your payment has failed. Please try again or use different payment option.');
							 ev.complete('fail');
						} else {

							 $.ajax({
						type:'POST',
						url:site_url+'stripebtnres',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						datatype: 'JSON',
						
						data:{	
							'methodName':ev.paymentMethod.payment_method_types,	
							'payerEmail':ev.payerEmail,
							'payerName':ev.payerName,
							'paymentMethod':ev.paymentMethod.id,
							'shippingOption':ev.shippingOption,
							'shippingAddress':ev.shippingAddress,
								
						},
						success:function(data) {
							
							if(data.status == 'success')
							{
                              // alert('success');
                               $("#is_stripe_wallet").val("google_pay");
                               $("#is_stripe_applepay").val("apple_pay");
                               $("#frmstripe").submit();
							}else{
								 alert('Your payment has failed. Please try again or use different payment option.');
							}
						}
					}); 
						}
					});
				} else {
					 $.ajax({
						type:'POST',
						url:site_url+'stripebtnres',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						datatype: 'JSON',
						
						data:{	
							'methodName':ev.paymentMethod.payment_method_types,	
							'payerEmail':ev.payerEmail,
							'payerName':ev.payerName,
							'paymentMethod':ev.paymentMethod.id,
							'shippingOption':ev.shippingOption,
							'shippingAddress':ev.shippingAddress,
								
						},
						success:function(data) {
							
							if(data.status == 'success')
							{
                              // alert('success');
                               $("#is_stripe_wallet").val("google_pay");
                               $("#is_stripe_applepay").val("apple_pay");
                               $("#frmstripe").submit();
							}else{
								 alert('Your payment has failed. Please try again or use different payment option.');
							}
						}
					}); 
				}
			}
		});
	});	
});
				
	</script>	
@endif
@if($CurrentRoute == 'billing-payment')
	<script src="https://js.stripe.com/v3/"></script>	
	<script>
	
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
					isLastNew : 'Yes',
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
					'isLast' : 'Yes',
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
		
		$(document).ready(function(){
			var stripe = Stripe('{{env("STRIPE_SECRET")}}', {
				apiVersion: "2019-05-16",
				
			});
			
			
			var paymentRequest = stripe.paymentRequest({
				country: 'US',
				currency: 'usd',
				total: {
					label: 'Order Total',
					amount: {{round($NetTotal*100)}},
				},
				//requestShipping: true,
				requestPayerName: true,
				requestPayerEmail: true,
			});
			
			
			var elements = stripe.elements();
			
			var prButton = elements.create('paymentRequestButton', {
				paymentRequest: paymentRequest,
				 style: {
					paymentRequestButton: {
					  type: 'default',	// One of 'default', 'book', 'buy', or 'donate'
					  theme: 'dark',	// One of 'dark', 'light', or 'light-outline'
					  // height: '64px'	// Defaults to '40px'. The width is always '100%'.
					},
				  },
			});

			prButton.on('click',function(ev){
				var CartData = GetStripeCart();
                  
				paymentRequest.update({
					total: {
						label: 'Order Total',
						amount: CartData.NetTotal,
					},
					displayItems : CartData.items,
					// shippingOptions: GetShippingOptions(),
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
						//shippingOptions: GetShippingOptions(state,zip,country),
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
					$("#dd123").val(confirmResult+"\n\n\n"+ev);
										
					if (confirmResult.error) {
					alert('Your payment has failed. Please try again or use different payment option.');
					console.log('Could not confirm result: ', confirmResult.error);
					ev.complete('fail');

					//alert('fail');
					
				} else {
					// Report to the browser that the confirmation was successful, prompting
					// it to close the browser payment method collection interface.
					console.log("confirmResult=="+confirmResult);
					//console.log(ev.paymentMethod.id);
					 
					
					ev.complete('success');
					
					// Check if the PaymentIntent requires any actions and if so let Stripe.js
					// handle the flow. If using an API version older than "2019-02-11"
					// instead check for: `paymentIntent.status === "requires_source_action"`.
					if (confirmResult.paymentIntent.status === "requires_action") {
						// Let Stripe.js handle the rest of the payment flow.
						stripe.confirmCardPayment(clientSecret).then(function(result) {
						if (result.error) {
							
							 alert('Your payment has failed. Please try again or use different payment option.');
							 ev.complete('fail');
						} else {

							$.ajax({
						type:'POST',
						url:site_url+'stripebtnres',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						datatype: 'JSON',
						
						data:{	
							'methodName':ev.paymentMethod.payment_method_types,	
							'payerEmail':ev.payerEmail,
							'payerName':ev.payerName,
							'paymentMethod':ev.paymentMethod.id,
							'shippingOption':ev.shippingOption,
							'shippingAddress':ev.shippingAddress,
							'stepfrom' : 'laststep',
								
						},
						success:function(data) {
							
							if(data.status == 'success')
							{
                               alert('success');
                               $("#is_stripe_wallet").val("google_pay");
                               $("#is_stripe_applepay").val("apple_pay");
                               $("#order_process").submit();
							}else{
								 alert('Your payment has failed. Please try again or use different payment option.');
							}
						}
					}); 
						}
					});
				} else {
					$.ajax({
						type:'POST',
						url:site_url+'stripebtnres',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						datatype: 'JSON',
						
						data:{	
							'methodName':ev.paymentMethod.payment_method_types,	
							'payerEmail':ev.payerEmail,
							'payerName':ev.payerName,
							'paymentMethod':ev.paymentMethod.id,
							'shippingOption':ev.shippingOption,
							'shippingAddress':ev.shippingAddress,
							'stepfrom' : 'laststep',
								
						},
						success:function(data) {
							
							if(data.status == 'success')
							{
                               alert('success');
                               $("#is_stripe_wallet").val("google_pay");
                               $("#is_stripe_applepay").val("apple_pay");
                               $("#order_process").submit();
							}else{
								 alert('Your payment has failed. Please try again or use different payment option.');
							}
						}
					}); 
				}
			}
		});
	});	
});
				
	</script>	
@endif

