@if($CartAttr["Amazon_pay_Checkout"] == 'Yes' && $onlyGCPurchased != '1')
    @php $JSSAmazonValVer = filemtime(config('global.SITE_JS_CORE_PATH').'amazon.js'); @endphp
    <script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}amazon.js?ver={{$JSSAmazonValVer}}"></script>
    <script type="text/javascript" src="{{ config('JS_SERVER_URL') }}" async></script>

    <script type="text/javascript">
        var address_veriffication = "{{config('global.address_verification')}}";
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId("{{config('CLIENT_ID')}}");
            amazon.Login.setUseCookie(true);
        };
        //var call_url= "{{config('CALLBACK_CHECKOUT_URL')}}";
        var call_url = "{{ route('checkout.setup-amazon') }}";
    </script>
@endif