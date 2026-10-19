window.StripeWallet = {

    stripe: null,

    elements: null,

    expressCheckout: null,

    initialized: false,

    async init(options) {

        if (this.initialized) {
            return;
        }

        const container =
            document.querySelector(
                '#stripe-express-checkout'
            );

        if (!container) {
            return;
        }

        this.stripe =
            Stripe(
                options.publishableKey
            );

        /*
         * Create Stripe Elements instance.
         *
         * The amount MUST come from the
         * server-calculated checkout total.
         */
        this.elements =
            this.stripe.elements({
                mode: 'payment',

                amount: options.amount,

                currency: options.currency,

                appearance: {
                    theme: 'stripe'
                }
            });

        /*
         * Express Checkout supports wallets
         * such as Google Pay / Apple Pay.
         */
        this.expressCheckout =
            this.elements.create(
                'expressCheckout',
                {
                    wallets: {
                        googlePay: 'always',

                        /*
                         * Keep Apple Pay available
                         * for the future.
                         */
                        applePay: 'auto'
                    }
                }
            );

        this.expressCheckout.mount(
            '#stripe-express-checkout'
        );

        /*
         * Stripe tells us which wallets are
         * actually available on this device/browser.
         */
        this.expressCheckout.on(
            'ready',
            (event) => {

                console.log(
                    'Available wallets:',
                    event.availablePaymentMethods
                );
            }
        );

        /*
         * Google Pay / Apple Pay clicked.
         */
        this.expressCheckout.on(
            'confirm',
            async (event) => {

                await this.confirmPayment(
                    event
                );
            }
        );

        this.initialized = true;
    },

    async confirmPayment(event) {

        try {

            this.clearError();

            /*
             * IMPORTANT:
             *
             * First create the order on Laravel
             * according to our agreed checkout flow.
             */
            const order =
                await this.createOrder();

            /*
             * Now confirm the Stripe payment.
             *
             * The Express Checkout Element has
             * already collected the wallet payment
             * information securely.
             */
            const result =
                await this.stripe.confirmPayment({

                    elements:
                        this.elements,

                    clientSecret:
                        order.client_secret,

                    confirmParams: {
                        return_url:
                            window
                                .stripeWalletUrls
                                .return
                    },

                    redirect: 'if_required'
                });

            if (result.error) {

                this.showError(
                    result.error.message
                );

                return;
            }

            /*
             * Payment completed without redirect.
             */
            if (
                result.paymentIntent &&
                result.paymentIntent.status
                    === 'succeeded'
            ) {

                await this.paymentSuccess(
                    result.paymentIntent
                );
            }

        } catch (error) {

            console.error(
                error
            );

            this.showError(
                error.message ||
                'Google Pay payment failed.'
            );
        }
    },

    async createOrder() {

        const response =
            await fetch(
                window
                    .stripeWalletUrls
                    .createOrder,
                {
                    method: 'POST',

                    credentials:
                        'same-origin',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            this.csrfToken()
                    },

                    body: JSON.stringify({

                        payment_method:
                            'google_pay'

                        /*
                         * Your existing
                         * checkout data will
                         * eventually go here.
                         */
                    })
                }
            );

        const result =
            await response.json();

        if (!response.ok) {

            throw new Error(
                result.message ||
                'Unable to create order.'
            );
        }

        return result;
    },

    async paymentSuccess(
        paymentIntent
    ) {

        const response =
            await fetch(
                window
                    .stripeWalletUrls
                    .paymentSuccess,
                {
                    method: 'POST',

                    credentials:
                        'same-origin',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            this.csrfToken()
                    },

                    body: JSON.stringify({

                        payment_intent_id:
                            paymentIntent.id
                    })
                }
            );

        const result =
            await response.json();

        if (!response.ok) {

            throw new Error(
                result.message ||
                'Unable to complete order.'
            );
        }

        if (result.success) {

            window.location.href =
                result.redirect;
        }
    },

    showError(message) {

        const element =
            document.querySelector(
                '#stripe-wallet-error'
            );

        if (element) {
            element.textContent =
                message || '';
        }
    },

    clearError() {

        this.showError('');
    },

    csrfToken() {

        const element =
            document.querySelector(
                'meta[name="csrf-token"]'
            );

        return element
            ? element.content
            : '';
    }
};