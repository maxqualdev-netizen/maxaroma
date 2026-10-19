<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Responsive Metatags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		@if(config('global.SITE_MODE') == 'Live')
			<meta name="p:domain_verify" content="798c033e248145d3c22b4ea5d09f56a9"/>
			<meta name="google-site-verification" content="htgE48PlMcKi9JNyU_bSnIw3-ZeWHYBOPDX_NgiAIhc" />
			<meta name="p:domain_verify" content="3432dcf2cb5d491bb31fd877350e4996" />
		@endif	
		<!-- Responsive Metatags -->
		<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
        <title>@if(isset($meta_title) && $meta_title!=''){!! $meta_title !!}@else{{config('global.META_TITLE')}}@endif</title>
		<meta name="Keywords" data-type="text" content="@if(isset($meta_keywords) && $meta_keywords!=''){{stripslashes($meta_keywords)}}@else{{config('global.META_KEYWORDS')}}@endif">
		<meta name="Description" data-type="text" content="@if(isset($meta_description) && $meta_description!=''){{stripslashes($meta_description)}}@else{{config('global.META_DESCRIPTION')}}@endif">
        <!--<script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0103/4351.js" async="async" ></script>-->
        @if(CanonicalURL() !='') 
			<link rel="canonical" href="{{CanonicalURL()}}" />
		@endif
        <!-- Favicon Icon -->
		<link rel="icon" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" type="image/x-icon" />
		<link rel="icon" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" type="ico" />
		<link rel="SHORTCUT ICON" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" />
		<!-- Favicon Icon -->
		
		<!-- Stylesheet File Start -->
		@if(config('global.ENV') != 'Dev')
		<link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE')}}all.css">
		@endif
		@php 
			$FilePath = config('global.SITE_URL').'public'; 
			$ConfigMsg = json_encode(config('message'));
            $CategorySize = config('Settings.CATEGORY_TITLE_SIZE');
		@endphp
		<script>
			var site_url = '{{config("global.SITE_URL")}}';
			function GetMessage(Module,Message)
			{
				var config = <?php echo $ConfigMsg?>;
				return config[Module][Message];
			}
		</script>
		@if($CurrentRoute == 'product-list3')
		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		@endif
		@if(false && ($CurrentRoute == 'product-list5' || $CurrentRoute == 'myaccount' || $CurrentRoute =='billing' || $CurrentRoute =='wholesaler-registration' || $CurrentRoute =='billing-shipping'))
		 <script async src="https://www.googletagmanager.com/gtag/js?id=GTM-NXK4PT"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'GTM-NXK4PT');
		</script>
		@endif

		@if($CurrentRoute != 'billing' && $CurrentRoute != 'billing-shipping' && $CurrentRoute != 'billing-payment' && $CurrentRoute != 'order-receipt' && $CurrentRoute != 'phoneorder_payment_receipt' && $CurrentRoute != 'AmazonBilling' && $CurrentRoute != 'AmazonBillingFund' && $CurrentRoute != 'AmazonPhoneOrderCheckout' && $CurrentRoute != 'billing' && $CurrentRoute != 'billing-shipping' && $CurrentRoute != 'billing-payment')

		<script type="text/javascript"> var yotpo_app_key = "MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8";
              (function e(){var e=document.createElement("script");e.type="text/javascript",e.async=true,e.src="//staticw2.yotpo.com/MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8/widget.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
              </script>
		@endif	  
		@if($CurrentRoute == 'home')
			<script src="https://cdn-widgetsrepository.yotpo.com/v1/loader/uIl5V6C_LVeCr5BT4bhDLQ" async></script>
		@endif
		@if($CurrentRoute == 'referral-program')
		<script>			
			document.addEventListener("DOMContentLoaded", function() {				
				var checkData = setInterval(function() {
					if ($('.yotpo-widget-referral-widget').length) {						
						$("#page-spinner").hide();						
						clearInterval(checkData);
					}
				}, 1000);
			});
		</script>
		@endif
		@include('css.indexcss',(isset($CSSFILES)?['CSSFILES' => $CSSFILES,'NewHomeDesign' => (isset($NewHomeDesign)?$NewHomeDesign:'')]:['CSSFILES' =>[]]))
    </head>
    <body class="{{ $body_class ?? ''}}" data-test="{{Session::get('new')}}">
		@if($CurrentRoute == 'referral-program')
			<div id="page-spinner" style="display: block;" ></div>
		@else
			<div id="page-spinner"></div>
		@endif
		
		@if($CurrentRoute == 'billing' || $CurrentRoute == 'billing-shipping' || $CurrentRoute == 'billing-payment' || $CurrentRoute == 'order-receipt' || $CurrentRoute == 'phoneorder_payment_receipt' || $CurrentRoute == 'AmazonBilling' || $CurrentRoute == 'AmazonBillingFund' || $CurrentRoute == 'AmazonPhoneOrderCheckout' || $CurrentRoute == 'billing' || $CurrentRoute == 'billing-shipping' || $CurrentRoute == 'billing-payment') 
			@include('layouts.blank')
		@else
			@include('layouts.header')
		@endif
		
		@yield('content')
		
		@if($CurrentRoute != 'billing' && $CurrentRoute != 'billing-shipping' && $CurrentRoute != 'billing-payment' && $CurrentRoute != 'order-receipt' && $CurrentRoute != 'phoneorder_payment_receipt' && $CurrentRoute != 'AmazonBilling' && $CurrentRoute != 'AmazonBillingFund' && $CurrentRoute != 'AmazonPhoneOrderCheckout' && $CurrentRoute !='billing' && $CurrentRoute != 'billing-shipping' && $CurrentRoute != 'billing-payment')
			@include('layouts.footer')
		@endif
		<input type="hidden" id="site_currency" value="{{Session::get('currency_code')}}"/>
		<input type="hidden" id="apminamt" value="{{Session::get('Afterpay.Min_AP_AMT')}}"/>
		<input type="hidden" id="apmaxamt" value="{{Session::get('Afterpay.Max_AP_AMT')}}"/>
		@if($CurrentRoute != 'product-list3')
		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		@endif
		@if(config('global.ENV') != 'Dev')
		<script src="{{config('global.SITE_JS')}}all.js"></script>
		@endif
		<script>
			var locationValue = window.location.hash;
			var CurrLocation = window.location.href;
			if(locationValue.indexOf("#") >= 0)
			{
				locationValue = locationValue.replace("#","");
				locationValues = locationValue.split("&");
				locationValues = locationValues.filter((v, i, a) => a.indexOf(v) === i);
				locationValues = locationValues.join("/");
				var PageName = '';

				if(CurrLocation.indexOf("dealofweek.html") >= 0)
				{
					PageName = site_url+'dealofweek.html/'+locationValues+"/view";
				}
				else if(CurrLocation.indexOf("maxtwoday.html") >= 0)
				{
					PageName = site_url+'maxtwoday.html/'+locationValues+"/view";
				} else {
					if(document.getElementById("catName") && document.getElementById("catName").value != ''){
						var Page = document.getElementById("catName").value;
						Page = Page.trim();
					} else {
						var Page = document.getElementById("pageTitle").value;
						Page = Page.trim();
					}
					if(locationValues.substring(0, 1) != '/')
						locationValues = '/'+locationValues;
					PageName = site_url+Page+"/p4u"+locationValues+"/view";

					//alert(locationValues);
				}
				window.location.href = PageName;
			}
		</script>
		{{-- @if(Session::has('Afterpay') && ($CurrentRoute == 'shoppingcart' || $CurrentRoute == 'billing' || $CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code' )) --}}
		
		@if(($CurrentRoute == 'shoppingcart' || $CurrentRoute == 'billing' || $CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code' ))
		   <script async src="https://js.afterpay.com/afterpay-1.x.js"></script>
            {{--
            @if(config('NEW') == 1)
                <script async src="https://js.afterpay.com/afterpay-1.x.js"></script>
            @else
                <script type="text/javascript" src="https://static-us.afterpay.com/javascript/present-afterpay.js"></script>
            @endif
            --}}
        @endif
		
		@if($CurrentRoute == 'phoneorder_payment_receipt')
		<script src="https://www.paypalobjects.com/api/checkout.js"></script>
		@endif
		
		@if(config('global.StripeButton') == 'Show')
			@include('layouts.stripe-button')
		@endif
		
		{{-- 
			@if($CurrentRoute == 'shoppingcart' || $CurrentRoute == 'billing' ||  $CurrentRoute == 'AmazonBilling' || $CurrentRoute == 'dropshipper-fund-summary' || $CurrentRoute == 'AmazonBillingFund' || $CurrentRoute == 'phoneorder_payment_receipt' || $CurrentRoute == 'AmazonPhoneOrderCheckout') 
		--}}
			<script type="text/javascript">
				window.onAmazonLoginReady = function() {
					amazon.Login.setClientId("{{config('CLIENT_ID')}}");
					amazon.Login.setUseCookie(true);
				};
				var call_url= "{{config('CALLBACK_CHECKOUT_URL')}}";
			</script>	
            @if(config('NEW') == 1)
                <script async src="https://js.afterpay.com/afterpay-1.x.js"></script>
            @else
			     <script type="text/javascript" src="https://static-us.afterpay.com/javascript/present-afterpay.js"></script>
            @endif
			<script type="text/javascript" src="{{config('JS_SERVER_URL')}}"></script>
			{{--
			<script type="text/javascript">
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
			--}}
			@if($CurrentRoute == 'shoppingcart' || $CurrentRoute == 'phoneorder_payment_receipt' )
			<script type="text/javascript">
				var authRequest;
					OffAmazonPayments.Button("AmazonPayButton", "{{config('MERCHANT_ID')}}", {
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
			@if($CurrentRoute == 'AmazonBilling' || $CurrentRoute == 'AmazonBillingFund')
				<script type="text/javascript">
					$("#page-spinner").show();
					new OffAmazonPayments.Widgets.AddressBook({
						sellerId: "{{config('MERCHANT_ID')}}",
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

					new OffAmazonPayments.Widgets.Wallet({
						sellerId: "{{config('MERCHANT_ID')}}",
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
				</script>
			@endif	
			
			@if($CurrentRoute == 'phoneorder_payment_receipt' || $CurrentRoute == 'AmazonPhoneOrderCheckout')
				<script type="text/javascript">
					$("#page-spinner").show();
					new OffAmazonPayments.Widgets.Wallet({
						sellerId: "{{config('MERCHANT_ID')}}",
						onOrderReferenceCreate: function (orderReference) {
						   orderReferenceId = orderReference.getAmazonOrderReferenceId();
						},
						onPaymentSelect: function () {
							GetAmazonDetails(orderReferenceId);
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
					
				</script>
			@endif	
			
		{{-- @endif --}}
		@include('layouts.svg')
		@include('js.indexjs',(isset($JSFILES)?['JSFILES' => $JSFILES]:['JSFILES' =>[]]))
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
		@include('layouts.thirdparty',['GTMDATA' => isset($GTMDATA)?$GTMDATA:[],'CurrentRoute' => $CurrentRoute])
					
		<div class="black-grbg fade in"></div>
		
    </body>
</html>	
