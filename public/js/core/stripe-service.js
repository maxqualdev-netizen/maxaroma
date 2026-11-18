var AppleGPay = '';
function GetShippingOptions(state = '', zip = '', country = '', city = '')
{
    var shippingmodes = [];
    $.ajax({
        type: 'POST',
        url: site_url + 'get-shipping-methods',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        data: {
            'OnlyHead': '0',
            'action': 'shippinginfo',
            'subaction': 'stripecart',
            'state': state,
            'zip': zip,
            'country': country,
            'city': city,
            'Gpay': 'Yes',
            'FirstStepGpay': 'FirstStep'
        },
        datatype: 'JSON',
        success: function(data) {
            //alert(data);
            //alert("shippingmodes==" + data[0].id);
            shippingmodes = data;
            SetStripeShippingMethod(data[0].id, state, zip, country, city);
            //alert("shippingmodes=="+JSON.stringify(data));
        }
    });

    return shippingmodes;
}

function GetStripeCart() {
    $("#page-spinner").show();
    var items = [];
    var NetTotal = 0;
    $("#ShippingSignInsu").modal('hide');

    $.ajax({
        type: 'POST',
        url: site_url + 'get-stipe-cart',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        data: {
            'action': 'getcart',
        },
        datatype: 'JSON',
        success: function(data) {

            if (data == "OutOfStock") {
                window.location = site_url + 'shoppingcart/view';
                return false;
            }

            if (data == "Zero") {
                window.location = site_url + 'shoppingcart/view';
                return false;
            }

            items = data.items;
            NetTotal = data.NetTotal;
            $("#page-spinner").hide();
            console.log("GetStripeCart", data);
        }
    });
    var CartData = {
        'items': items,
        'NetTotal': NetTotal
    };
    return CartData;
}

function CreateUpdatePaymentIntent()
{
    $("#page-spinner").show();
    var items = [];
    var NetTotal = 0;
    $("#ShippingSignInsu").modal('hide');

    return $.ajax({
        type: 'POST',
        url: site_url + 'payment/stripe/wallet/create-update-intent',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        data: {
            'action': 'createUpdateIntent',
            'orderTotal': parseFloat($("#shoptotal").text())
        },
        datatype: 'JSON',
        success: function(data) {
            $("#page-spinner").hide();
            console.log("CreateUpdateIntent: ", data);
        }
    });
}
function SetStripeShippingMethod(ShipMethodID, state = '', zip = '', country = '', city = '') {

    $.ajax({
        type: 'POST',
        url: site_url + 'set-shipping-method',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        datatype: 'JSON',
        data: {
            action: 'stripecart',
            ShipMethodID: ShipMethodID,
            state: state,
            zip: zip,
            country: country,
            city: city
        },
        success: function(data) {
            //alert("SetStripeShippingMethod=="+data.status.status);
        },
    });
}

function GetClientSecret(AppleGPay, allDetails) {
    var clientSecret = "";
    $.ajax({
        type: 'POST',
        url: site_url + 'getclientsecret',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        //async: false,
        datatype: 'JSON',
        data: {
            action: 'clientsecret',
            AppleGPay: AppleGPay,
            allDetailsVal: allDetails,
            stepfrom: 'firststep',
        },
        success: function(data) {
            if (data == "OutOfStock") {
                //alert("Sorry some of your cart items are out of stock. Please update your cart and then proceed for payment");
                return false;
            } else if (data == "Close") {
                //alert("Sorry please try again");
                return false;
            } else if (data == "Guest") {
                //alert("Error while processing your order, Please try again");
                return false;
            } else if (data == "SHMethod") {
                //alert("PlaceOrderError: Shipping Method Not Selected");
                return false;
            } else if (data == "Zero") {
                //alert("Please change payment type");
                return false;
            } else {
                clientSecret = data.clientSecret;
            }
        },
    });
    return clientSecret;
}

function addApplePayLog(logaction,data,actions,routenmnew)	{
    var res = "";
    $.ajax({
        type:'POST',
        url:site_url+'applepaylogupdate',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            logaction : logaction,
            log_data : JSON.stringify(data),
            log_actions : JSON.stringify(actions),
            rtnm : routenmnew
        },
        async:false,
        datatype: 'JSON',
        success:function(data) {
            res= data
        },

    });
}
async function checkServer() {
    try {
        const res = await $.ajax({
            type: 'POST',
            url: site_url + 'servercheck',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            timeout: 4000
        });
        return res && res.status === 'ok';
    } catch (e) {
        return false;
    }
}
/*
let stripe = null;
let stripeElements = null;

let expressCheckoutElement = null;

let stripeClientSecret = null;

let stripePaymentIntentId = null;

const csrfToken = $('meta[name="csrf-token"]').attr("content");

async function initStripe() {
    stripe = Stripe(window.stripeinfo.stripekey);
    const result = await $.ajax({
        url: window.stripePaymentUrls.stripePaymentIntent,
        method: 'POST',
        dataType: 'json',
        data: {
            _token: csrfToken
        }
    });

    console.log(
        'Stripe PaymentIntent response:',
        result
    );

    if (!result.success) {
        throw new Error(
            result.message ||
            'Unable to initialize Stripe payment.'
        );
    }

    stripeClientSecret =
            result.client_secret;

    stripePaymentIntentId =
        result.payment_intent_id;

    if (
        !stripeClientSecret ||
        !stripeClientSecret.includes('_secret_')
    ) {

        console.error(
            'Invalid Stripe client secret:',
            stripeClientSecret
        );

        throw new Error(
            'Invalid Stripe client secret received from server.'
        );
    }

    stripeElements = stripe.elements({
        clientSecret: stripeClientSecret,
        appearance: {
            theme: 'stripe'
        }
    });

    InitStripeExpress();
}

$(document).ready(function () {
    initStripe();
});

function InitStripeCard() {
    const cardStyle = {
        base: {
            fontSize: '12px',
            color: '#32325d',
            fontFamily: 'Arial, sans-serif',

            '::placeholder': {
                color: '#aab7c4'
            }
        },

        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    cardNumberElement =
        stripeCardElements.create(
            'cardNumber',
            {
                style: cardStyle
            }
        );

    cardExpiryElement =
        stripeCardElements.create(
            'cardExpiry',
            {
                style: cardStyle
            }
        );

    cardCvcElement =
        stripeCardElements.create(
            'cardCvc',
            {
                style: cardStyle
            }
        );

    cardNumberElement.mount(
        '#stripe-card-number'
    );

    cardExpiryElement.mount(
        '#stripe-card-expiry'
    );

    cardCvcElement.mount(
        '#stripe-card-cvc'
    );

    cardNumberElement.on(
        'change',
        function(event) {

            if (event.error) {

                $('#stripe-card-error')
                    .text(event.error.message);

            } else {

                $('#stripe-card-error')
                    .text('');
            }
        }
    );

    console.log(
        'Stripe Card Elements initialized'
    );
}

function initAppleGooglePay() {
    expressCheckoutElement = stripeElements.create('expressCheckout', {
        buttonType: {
            applePay: 'buy',
            googlePay: 'buy'
        },
        buttonHeight: 45
    });

    expressCheckoutElement.mount('#express-checkout-element');

    expressCheckoutElement.on('ready', function(event) {

        console.log('Express Checkout ready:', event);

    });
}

function InitStripeExpress() {

    stripeExpress = stripeElements.create('expressCheckout', {
        buttonType: {
            applePay: 'buy',
            googlePay: 'buy'
        },
        buttonHeight: 45
    });

    stripeExpress.mount('#express-checkout-element');

    stripeExpress.on('ready', function(event) {

        console.log(
            'Available express wallets:',
            event.availablePaymentMethods
        );

    });
}

function initializeStripeElements() {

    if (!stripeClientSecret) {

        throw new Error(
            'Stripe client secret is missing.'
        );
    }

    stripeElements =
        stripe.elements({
            clientSecret:
                stripeClientSecret
        });

    const cardStyle = {

        base: {

            fontSize: '16px',

            color: '#32325d',

            '::placeholder': {
                color: '#aab7c4'
            }
        },

        invalid: {
            color: '#fa755a'
        }
    };

    cardNumberElement =
        stripeElements.create(
            'cardNumber',
            {
                style: cardStyle
            }
        );

    cardExpiryElement =
        stripeElements.create(
            'cardExpiry',
            {
                style: cardStyle
            }
        );

    cardCvcElement =
        stripeElements.create(
            'cardCvc',
            {
                style: cardStyle
            }
        );

    cardNumberElement.mount(
        '#stripe-card-number'
    );

    cardExpiryElement.mount(
        '#stripe-card-expiry'
    );

    cardCvcElement.mount(
        '#stripe-card-cvc'
    );

    expressCheckoutElement =
        stripeElements.create(
            'expressCheckout',
            {
                buttonHeight: 48,

                emailRequired: true
            }
        );

    expressCheckoutElement.mount(
        '#stripe-express-checkout'
    );

    expressCheckoutElement.on(
        'ready',
        function (event) {

            console.log(
                'Stripe wallet availability:',
                event.availablePaymentMethods
            );

            if (
                event.availablePaymentMethods
            ) {

                $('#stripe-wallet-section')
                    .show();

            } else {

                $('#stripe-wallet-section')
                    .hide();
            }
        }
    );

    expressCheckoutElement.on(
        'confirm',
        function (event) {

            confirmStripeWallet();
        }
    );
}

async function confirmStripeWallet() {

    try {

        showStripeLoading(true);

        const submitResult =
            await stripeElements.submit();

        if (
            submitResult.error
        ) {

            throw new Error(
                submitResult
                    .error
                    .message
            );
        }

        const result =
            await stripe.confirmPayment({

                elements:
                    stripeElements,

                clientSecret:
                    stripeClientSecret,

                confirmParams: {

                    return_url:
                        window.checkoutUrls
                            .stripeReturn
                }
            });

        if (result.error) {

            throw new Error(
                result.error.message
            );
        }

    } catch (error) {

        console.error(
            'Stripe wallet error:',
            error
        );

        showStripeError(
            error.message
        );

    } finally {

        showStripeLoading(false);
    }
}
*/