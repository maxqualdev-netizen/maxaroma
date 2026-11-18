<script>

(function ($) {
    "use strict";

    let stripe = null;
    let stripeElements = null;
    let expressCheckoutElement = null;

    let initialAmount = 0;
    let walletInitialized = false;

    function getCheckoutAmount() {
        const amount = parseFloat({{ round($NetTotal * 100) }});
        //const amount = parseFloat($("#shoptotal").text()) * 100;
        return Math.round(amount * 100);
    }

    function showWalletError(message) {
        $("#stripe-wallet-error")
            .text(message || "Unable to process payment.")
            .show();
    }

    function hideWalletError() {
        $("#stripe-wallet-error").hide().empty();
    }

    function initializeExpressCheckout() {

        if (walletInitialized) {
            return;
        }

        const publishableKey = window.stripeinfo?.stripekey;

        if (!publishableKey) {
            console.error("Stripe publishable key is missing.");
            return;
        }

        initialAmount = getCheckoutAmount();

        if (initialAmount <= 0) {
            console.error("Invalid Stripe checkout amount.");
            return;
        }

        stripe = Stripe(publishableKey);

        window.stripe = stripe;

        stripeElements = stripe.elements({
            mode: "payment",
            amount: initialAmount,
            currency: "usd",
        });

        window.stripeElements = stripeElements;

        expressCheckoutElement = stripeElements.create(
            "expressCheckout",
            {
                emailRequired: true,
                phoneNumberRequired: true,
                shippingAddressRequired: true,

                buttonHeight: 45,

                layout: {
                    maxColumns: 2,
                    maxRows: 2,
                    overflow: "auto",
                },
                paymentMethods: {
                    applePay: "always",
                    googlePay: "always",
                    link: "auto"
                },
                buttonType: {
                    applePay: "buy",
                    googlePay: "buy",
                },

                buttonTheme: {
                    applePay: "black",
                    googlePay: "black",
                },
            }
        );

        expressCheckoutElement.on(
            "availablepaymentmethodschange",
            function (event) {

                console.log(
                    "Stripe available payment methods:",
                    event.paymentMethods
                );
                $(".express-btn-apple, .express-btn-google, .express-btn-link").hide();
                if (event.paymentMethods) {
                    if (event.paymentMethods.applePay.available) {
                        $("#GpayBtn").show();
                        $("#GpayBtn").html("Apple Pay");
                        AppleGPay = 'A';
                        $(".express-btn-apple").show();
                        // $("#payment-request-button-checkout").addClass("express-btn-apple");
                        // $("#payment-request-button-checkout").html('<img src="/images/checkout-new/apple-pay.svg" alt="">');
                    }
                    if(event.paymentMethods.googlePay.available){
                        $("#GpayBtn").show();
                        $("#GpayBtn").html("Google Pay");
                        AppleGPay = 'G';
                        $(".express-btn-google").show();
                        // $("#payment-request-button-checkout").addClass("express-btn-google");
                        // $("#payment-request-button-checkout").html('<img src="/images/checkout-new/google-pay.svg" alt="">');
                    }
                    if(event.paymentMethods.link.available){
                        $(".express-btn-link").show();
                        // $("#payment-request-button-checkout").addClass("express-btn-link");
                        // $("#payment-request-button-checkout").text("Link");
                    }
                    $("#payment-request-button").show();
                } else {
                    $("#payment-request-button").hide();
                }
            }
        );

        expressCheckoutElement.on(
            "confirm",
            async function (event) {

                hideWalletError();
                console.log(event);

                try {

                    if (
                        !window.MaxaromaOnePageCheckout ||
                        typeof window.MaxaromaOnePageCheckout
                            .processStripeWalletPayment !== "function"
                    ) {
                        throw new Error(
                            "Stripe checkout handler is unavailable."
                        );
                    }

                    var clientSecret = await window.MaxaromaOnePageCheckout.processStripeWalletPayment(event);

                } catch (error) {

                    console.error(
                        "Stripe Express Checkout error:",
                        error
                    );

                    showWalletError(
                        error.message ||
                        "Payment failed. Please try again."
                    );
                }
            }
        );

        expressCheckoutElement.on('click', async (event) => {
            // Must be called within 1 second of the click event.
            var IntentUpdate = await CreateUpdatePaymentIntent();
            await stripeElements.update({
                amount: Number(IntentUpdate.amount),
            });
            var CartData = await GetStripeCart();
            var items = CartData.items;
            const total = items.reduce((sum, item) => sum + item.amount, 0);
            console.log(total);
            var LineItems = new Array();

            //LineItems.push({name: 'Order Total', amount:  CartData.NetTotal});
            if(items.length > 0)
            {
                for(var i=0;i<items.length;i++)
                {
                    LineItems.push({name: items[i].label, amount: items[i].amount});
                }
            }
            console.log(LineItems);
            const linetotal = LineItems.reduce((sum, item) => sum + item.amount, 0);
            console.log(linetotal);
            event.resolve({
                lineItems: LineItems
            });
        });

        expressCheckoutElement.on("shippingaddresschange", async function (event) {
            var state = event.address.state;
            var zip = event.address.postal_code;
            var country = event.address.country;
            var city = event.address.city;
            var datas = GetShippingOptions(state, zip, country, city);
            var shippingRates = new Array();
            console.log(datas);
            $.each(datas, function(index,data){
                shippingRates.push({id: data.id, displayName: data.label, amount: data.amount});
            })
            console.log(shippingRates);
            event.resolve({
                shippingRates: shippingRates,
                //lineItems: LineItems
            });
            /*
            try {
                const response = await fetch(window.checkoutUrls.getShippingMethods, {
                    method: "POST",
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        state: event.address.state,
                        zip: event.address.postal_code,
                        country: event.address.country,
                        city: event.address.city
                    })
                });

                const result = await response.json();

                if (!response.ok || result.status === "Error") {
                    event.reject();
                    return;
                }

                event.resolve({
                    shippingRates: result.shippingRates,
                    lineItems: result.lineItems
                });

            } catch (error) {
                console.error("Shipping address error:", error);
                event.reject();
            }
            */
        });

        expressCheckoutElement.on("shippingratechange", async function (event) {
            try {
                // 1. Update selected shipping method in Laravel
                await SetStripeShippingMethod(event.shippingRate.id);

                // 2. Fetch the recalculated cart
                const CartData = await GetStripeCart();

                const LineItems = [];

                if (CartData.items?.length) {
                    CartData.items.forEach(item => {
                        LineItems.push({
                            name: item.label,
                            amount: Number(item.amount)
                        });
                    });
                }

                var IntentUpdate = await CreateUpdatePaymentIntent();
                console.log(IntentUpdate.amount);
                await stripeElements.update({
                    amount: IntentUpdate.amount,
                });
                // 3. Update the Elements amount (minor currency units)
                /*
                const totalAmount = Math.round(Number(CartData.NetTotal) * 100);

                await stripeElements.update({
                    amount: totalAmount
                });
                */
                // 4. Update the wallet's displayed breakdown
                event.resolve({
                    lineItems: LineItems
                });

            } catch (error) {
                console.error("Shipping rate update failed:", error);
                event.reject();
            }
        });

        expressCheckoutElement.mount(
            "#payment-request-button"
        );

        walletInitialized = true;
    }

    /*
     * Called whenever Maxaroma recalculates checkout totals.
     */
    window.updateStripeExpressAmount = function (amount) {

        if (!stripeElements) {
            return;
        }

        const amountInCents = Math.round(
            Number(amount || 0) * 100
        );

        if (amountInCents <= 0) {
            return;
        }

        stripeElements.update({
            amount: amountInCents,
        });
    };

    $(document).ready(function () {
        initializeExpressCheckout();

        $(document).on('click', '.express-btn-link, .express-btn-google, .express-btn-apple', function() {
            $("#ShippingSignInsu").modal('show');
            $("#gpay_applepay_paypal").text("GooglePay/ApplePay");
            $("#paypal-button-container-checkout").hide();
            $("#payment-request-button").show();
        });
    });

})(jQuery);

</script>
{{--
<script>
    var AppleGPay = '';
    $(document).ready(function() {
        var publishable_key = window.stripeinfo.stripekey;
        window.stripe = Stripe(publishable_key, {
            apiVersion: "2019-05-16",
        });

        var paymentRequest = window.stripe.paymentRequest({
            country: 'US',
            currency: 'usd',
            total: {
                label: 'Order Total',
                amount: {{ round($NetTotal * 100) }},
            },
            requestShipping: true,
            requestPayerName: true,
            requestPayerEmail: true,
            requestPayerPhone: true,
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
                    type: 'default', // One of 'default', 'book', 'buy', or 'donate'
                    theme: 'dark', // One of 'dark', 'light', or 'light-outline'
                    height: '45px' // Defaults to '40px'. The width is always '100%'.
                },
            },
        });

        prButton.on('click', function(ev) {
            var CartData = GetStripeCart();
            paymentRequest.update({
                total: {
                    label: 'Order Total',
                    amount: CartData.NetTotal,
                },
                displayItems: CartData.items,
                // shippingOptions: GetShippingOptions(),
            });
        })

        // Check the availability of the Payment Request API first.
        paymentRequest.canMakePayment().then(function(result) {
            if (result) {
                // $("#GpayBtn").show();
                console.log(result);
                if (result.applePay) {
                    $("#GpayBtn").show();
                    $("#GpayBtn").html("Apple Pay");
                    AppleGPay = 'A';
                    $("#payment-request-button-checkout").addClass("express-btn-apple");
                    $("#payment-request-button-checkout").html('<img src="/images/checkout-new/apple-pay.svg" alt="">');
                }else if(result.googlePay){
                    $("#GpayBtn").show();
                    $("#GpayBtn").html("Google Pay");
                    AppleGPay = 'G';
                    $("#payment-request-button-checkout").addClass("express-btn-google");
                    $("#payment-request-button-checkout").html('<img src="/images/checkout-new/google-pay.svg" alt="">');
                }else if(result.link){
                    $("#payment-request-button-checkout").addClass("express-btn-link");
                    $("#payment-request-button-checkout").text("Link");
                }
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
                displayItems: CartData.items,
            });
        });

        paymentRequest.on('shippingaddresschange', async (ev) => {
            if (ev.shippingAddress.country !== 'US') {
                ev.updateWith({
                    status: 'invalid_shipping_address'
                });
            } else {
                var state = ev.shippingAddress.region;
                var zip = ev.shippingAddress.postalCode;
                var country = ev.shippingAddress.country;
                var city = ev.shippingAddress.city;
                var datas = GetShippingOptions(state, zip, country, city);
                //alert("shippingaddresschange=="+JSON.stringify(datas));
                //alert(datas);
                ev.updateWith({
                    status: 'success',
                    shippingOptions: datas,
                });
            }
        });

        let paymentController;
        paymentRequest.on('paymentmethod', async function(ev) {

            paymentController = new AbortController();
            const signal = paymentController.signal;
            var clientSecret = window.MaxaromaOnePageCheckout.processStripeWalletPayment(JSON.stringify(ev), AppleGPay);
            //alert("paymentmethod==", AppleGPay);
            /*
            var clientSecret = GetClientSecret(AppleGPay, JSON.stringify(ev));

            if (!clientSecret) {
                window.location = site_url + 'shoppingcart/view';
                return false;
            }

            const alive = await checkServer();
            if (!alive) {
                alert("Server unavailable. Please try again later.");
                try {
                    ev.complete('fail');
                } catch (e) {}
                window.location = site_url + 'shoppingcart/view';
                return false;
            }

            try {
                stripe.confirmCardPayment(
                    clientSecret, {
                        payment_method: ev.paymentMethod.id
                    }, {
                        handleActions: false
                    }
                ).then(function(confirmResult) {
                    $("#dd123").val(confirmResult + "\n\n\n" + ev);
                    addApplePayLog("confirm_result_payment_method", confirmResult, "", '<?= $CurrentRoute ?>');
                    if (confirmResult.error) {
                        alert('Your payment has failed. Please try again or use different payment option.');
                        console.log('Could not confirm result: ', confirmResult.error);
                        ev.complete('fail');
                        window.location = site_url + 'shoppingcart/view';
                        return false;
                    } else {
                        addApplePayLog("confirm_result_payment_method_2", confirmResult, "", '<?= $CurrentRoute ?>');
                        console.log("confirmResult==", confirmResult);
                        ev.complete('success');

                        if (confirmResult.paymentIntent.status === "requires_action") {
                            addApplePayLog("confirm_result_payment_require_action", confirmResult, "", '<?= $CurrentRoute ?>');
                            stripe.confirmCardPayment(clientSecret).then(function(result) {
                                if (result.error) {
                                    addApplePayLog("confirm_result_payment_require_action_error", result, "", '<?= $CurrentRoute ?>');
                                    alert('Your payment has failed. Please try again or use different payment option.');
                                    ev.complete('fail');
                                    window.location = site_url + 'shoppingcart/view';
                                    return false;
                                } else {
                                    $.ajax({
                                        type: 'POST',
                                        url: site_url + 'stripebtnres',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        datatype: 'JSON',
                                        data: {
                                            'methodName': ev.paymentMethod.payment_method_types,
                                            'payerEmail': ev.payerEmail,
                                            'payerName': ev.payerName,
                                            'paymentMethod': ev.paymentMethod.id,
                                            'shippingOption': ev.shippingOption,
                                            'shippingAddress': ev.shippingAddress,
                                            'stepfrom': 'firststep',
                                            'payerPhone': ev.payerPhone
                                        },
                                        signal: signal,
                                        success: function(data) {
                                            if (data.status == 'success') {
                                                if ($("#shipping_signature").prop('checked'))
                                                    $("#shipsignatureflag").val('Yes');
                                                else
                                                    $("#shipsignatureflag").val('No');

                                                $("#is_stripe_wallet").val("google_pay");
                                                $("#is_stripe_applepay").val("apple_pay");
                                                window.location.href = site_url + 'order-receipt';
                                            } else {
                                                addApplePayLog("confirm_result_payment_failed", data, "", '<?= $CurrentRoute ?>');
                                                alert('Your payment has failed. Please try again or use different payment option.');
                                                ev.complete('fail');
                                                window.location = site_url + 'shoppingcart/view';
                                                return false;
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("AJAX error:", error);
                                            ev.complete('fail');
                                            window.location = site_url + 'shoppingcart/view';
                                        }
                                    });
                                }
                            });
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: site_url + 'stripebtnres',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                datatype: 'JSON',
                                data: {
                                    'methodName': ev.paymentMethod.payment_method_types,
                                    'payerEmail': ev.payerEmail,
                                    'payerName': ev.payerName,
                                    'paymentMethod': ev.paymentMethod.id,
                                    'shippingOption': ev.shippingOption,
                                    'shippingAddress': ev.shippingAddress,
                                    'stepfrom': 'firststep',
                                    'payerPhone': ev.payerPhone
                                },
                                signal: signal,
                                success: function(data) {
                                    if (data.status == 'success') {
                                        if ($("#shipping_signature").prop('checked'))
                                            $("#shipsignatureflag").val('Yes');
                                        else
                                            $("#shipsignatureflag").val('No');

                                        $("#is_stripe_wallet").val("google_pay");
                                        $("#is_stripe_applepay").val("apple_pay");
                                        window.location.href = site_url + 'order-receipt';
                                    } else {
                                        addApplePayLog("confirm_result_payment_failed_2", data, "", '<?= $CurrentRoute ?>');
                                        alert('Your payment has failed. Please try again or use different payment option.');
                                        ev.complete('fail');
                                        window.location = site_url + 'shoppingcart/view';
                                        return false;
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("AJAX error:", error);
                                    ev.complete('fail');
                                    window.location = site_url + 'shoppingcart/view';
                                }
                            });
                        }
                    }
                });
            } catch (err) {
                $.ajax({
                    type: 'POST',
                    url: site_url + 'stripebtnres',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    datatype: 'JSON',
                    data: {
                        'methodName': ev.paymentMethod.payment_method_types,
                        'payerEmail': ev.payerEmail,
                        'payerName': ev.payerName,
                        'paymentMethod': ev.paymentMethod.id,
                        'shippingOption': ev.shippingOption,
                        'shippingAddress': ev.shippingAddress,
                        'stepfrom': 'firststep',
                        'payerPhone': ev.payerPhone,
                        'isAbort': 'Yes'
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            if ($("#shipping_signature").prop('checked'))
                                $("#shipsignatureflag").val('Yes');
                            else
                                $("#shipsignatureflag").val('No');

                            $("#is_stripe_wallet").val("google_pay");
                            $("#is_stripe_applepay").val("apple_pay");
                            window.location.href = site_url + 'order-receipt';
                        } else {
                            addApplePayLog("confirm_result_payment_failed", data, "", '<?= $CurrentRoute ?>');
                            alert('Your payment has failed. Please try again or use different payment option.');
                            ev.complete('fail');
                            window.location = site_url + 'shoppingcart/view';
                            return false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        ev.complete('fail');
                        window.location = site_url + 'shoppingcart/view';
                    }
                });
            }*/
        });

        $(document).on('click', '.express-btn-link, .express-btn-google, .express-btn-apple', function() {
            $("#ShippingSignInsu").modal('show');
            $("#gpay_applepay_paypal").text("GooglePay/ApplePay");
            $("#paypal-button-container-checkout").hide();
            $("#payment-request-button").show();
        });
    });
</script>
--}}
@php $StripeServiceVer = filemtime(config('global.SITE_JS_CORE_PATH').'stripe-service.js'); @endphp
<script src="{{config('global.SITE_JS_CORE')}}stripe-service.js?ver={{$StripeServiceVer}}"></script>