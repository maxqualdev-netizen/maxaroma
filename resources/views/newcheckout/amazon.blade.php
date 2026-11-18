@if($CartAttr["Amazon_pay_Checkout"] == 'Yes' && $onlyGCPurchased != '1')
    @php $JSSAmazonValVer = filemtime(config('global.SITE_JS_CORE_PATH').'amazon.js'); @endphp
    <script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}amazon.js?ver={{$JSSAmazonValVer}}"></script>
    <script type="text/javascript" src="{{ $JS_SERVER_URL }}" async></script>

    <script type="text/javascript">
        var address_veriffication = "{{config('global.address_verification')}}";
        /*window.onAmazonLoginReady = function() {
            amazon.Login.setClientId("{{$amazon_client_id}}");
            amazon.Login.setUseCookie(true);
        };
        //var call_url= "{{config('CALLBACK_CHECKOUT_URL')}}";
        var call_url = "{{ route('checkout.setup-amazon') }}";
        */
        $(document).ready(function(){
            if($("#addressBookWidgetDiv").length > 0)
            {
                setTimeout(() => {
                    new OffAmazonPayments.Widgets.AddressBook({
                        sellerId: "{{$amazon_merchant_id}}",
                        onOrderReferenceCreate: function (orderReference) {
                            orderReferenceId = orderReference.getAmazonOrderReferenceId();
                        },
                        onAddressSelect: function () {
                            GetAmazonDetails(orderReferenceId);
                        },
                        design: {
                            designMode: 'responsive'
                        },
                        onError: function (error) {
                            $("#btnAmazonOrder").hide();
                            // your error handling code
                        }
                    }).bind("addressBookWidgetDiv");
                }, 500);
            }

            if($("#walletWidgetDiv").length > 0)
            {
                setTimeout(() => {
                    new OffAmazonPayments.Widgets.Wallet({
                        sellerId: "{{$amazon_merchant_id}}",
                        onPaymentSelect: function () {
                            $("#btnAmazonOrder").show();
                            //console.log(orderReference);
                        },
                        design: {
                            designMode: 'responsive'
                        },
                        onError: function (error) {
                            $("#btnAmazonOrder").hide();
                            // your error handling code
                        }
                    }).bind("walletWidgetDiv");
                }, 500);
            }
        })

        function GetAmazonDetails(AmazonOrderID)
        {
            var token = $('meta[name="csrf-token"]').attr("content");
            var onlyGCPurchased = $("#onlyGCPurchased").val();
            $.ajax({
                type:'POST',
                //url:site_url+'amazon/order-details',
                url:site_url+'amazon-order-details',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                datatype: 'JSON',
                data:{
                    amazon_order_id: AmazonOrderID
                },
                success:function(data) {
                    //console.log(data);
                    if(data.success === true){
                        window.amazon.address = data.address;
                        window.MaxaromaOnePageCheckout.loadShippingMethods();
                        var address = "";
                        address+=data.address.first_name;
                        if($.trim(data.address.last_name) != '')
                        {
                            address+=" "+data.address.first_name;
                        }
                        address+=", "+data.address.address1;
                        if($.trim(data.address.address2) != '')
                        {
                            address+=", "+data.address.address2;
                        }
                        address+=", "+data.address.city;
                        address+=", "+data.address.state;
                        address+=", "+data.address.zip;
                        //console.log(address,$("#review-ship-to-value").length);

                        setTimeout(() => {
                            $("#review-contact-value").text(data.address.email);
                            $("#review-contact-value").parent().css('visibility','visible');
                            $("#review-ship-to-value").html(address);
                            //window.MaxaromaOnePageCheckout.updateCheckoutReviewAddress(data.address);
                        }, 1000);

                        //window.MaxaromaOnePageCheckout.updateCheckoutReviewAddress(data.address);
                        // if(onlyGCPurchased == '0')
                        // {
                        //     //GetShippingMethods(0,'amazon_billing');
                        //     loadShippingMethods();
                        // }
                    }
                }
            });
        }
    </script>
@endif