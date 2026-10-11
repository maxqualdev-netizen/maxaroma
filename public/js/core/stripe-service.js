let stripe = null;
let stripeElements = null;

let expressCheckoutElement = null;

let stripeClientSecret = null;

let stripePaymentIntentId = null;

const csrfToken = $('meta[name="csrf-token"]').attr("content");

async function initStripe() {
    stripe = Stripe(window.stripeinfo.stripekey);
    console.log(window.stripePaymentUrls.stripePaymentIntent);
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

    /*
     * Card Number
     */
    cardNumberElement =
        stripeCardElements.create(
            'cardNumber',
            {
                style: cardStyle
            }
        );

    /*
     * Card Expiry
     */
    cardExpiryElement =
        stripeCardElements.create(
            'cardExpiry',
            {
                style: cardStyle
            }
        );

    /*
     * Card CVC
     */
    cardCvcElement =
        stripeCardElements.create(
            'cardCvc',
            {
                style: cardStyle
            }
        );

    /*
     * Mount
     */
    cardNumberElement.mount(
        '#stripe-card-number'
    );

    cardExpiryElement.mount(
        '#stripe-card-expiry'
    );

    cardCvcElement.mount(
        '#stripe-card-cvc'
    );

    /*
     * Element errors
     */
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

    /*
     * =========================
     * Credit Card
     * =========================
     */

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

    /*
     * =========================
     * Apple Pay / Google Pay
     * =========================
     */

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

    /*
     * Wallet availability
     */

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

    /*
     * Wallet confirmation
     */

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

        /*
         * Validate Elements before confirmation.
         */
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

        /*
         * Confirm the PaymentIntent.
         */
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