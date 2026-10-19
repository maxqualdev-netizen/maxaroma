@if(config('global.SITE_MODE') == 'Live')

	@if(
    config('Settings.ESTREAMLY_FLOATING') == "Yes" && isset($CurrentRoute) &&
    $CurrentRoute != 'billing' &&
    $CurrentRoute != 'billing-shipping' &&
    $CurrentRoute != 'billing-payment' &&
    $CurrentRoute != 'order-receipt' &&
    $CurrentRoute != 'phoneorder_payment_receipt' &&
    $CurrentRoute != 'AmazonBilling' &&
    $CurrentRoute != 'AmazonBillingFund' &&
    $CurrentRoute != 'AmazonPhoneOrderCheckout' &&
    config('Settings.ESTREAMLY_FLOATING_SCRIPT')!=''
	)
	 <script src="https://scripts.estreamly.com/estreamly-utils.js" onload="createEmbedScript('estreamly_iframe_container_1748433595055','estreamly_iframe_1748433595055','{{ config('Settings.ESTREAMLY_FLOATING_SCRIPT')}}','maxaromanew','PORTRAIT','','bleft');" defer="true"></script>  
	@endif
	 
	 @if(($CurrentRoute == 'gift-guide'))
		<script src='https://cas.zma.gs/644c2352b6fc7c5ba74a8d75/ssr/containers/fd519442-f278-4908-92dd-90756beffe06/init.js'></script>
	  @endif 
	 
	
	<noscript><iframe src="https://fbstape.maxaroma.com/ns.html?id=GTM-NXK4PT" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	
	<script type="text/javascript">
		window.dataLayer = window.dataLayer || [];
	</script>
	
	@if(isset($SchemaCodeDetailPage) && $SchemaCodeDetailPage!='')
			{!! $SchemaCodeDetailPage !!}		
	@endif
	
	@if(count($GTMDATA) > 0)
		{!! $GTMDATA['google_remarketing_codes'] !!}
	@endif
	
	@if(isset($AllFBView) && $AllFBView!='')
			
		<script type="text/javascript">
		{!! $AllFBView !!}
		</script>
	@endif
	
	
	
	@if(isset($GALogin4) && $GALogin4!='')
			
		<script type="text/javascript">
		{!! $GALogin4 !!}
		</script>
	@endif
	
	@if(isset($GARegister1) && $GARegister1!='')
			
		<script type="text/javascript">
		{!! $GARegister1 !!}
		</script>
	@endif
	
	
	
	@if(isset($GA4Amazon) && $GA4Amazon!='')
			
		<script type="text/javascript">
		{!! $GA4Amazon !!}
		</script>
	@endif
	
	@if(isset($GAShipping) && $GAShipping!='')
			
		<script type="text/javascript">
		{!! $GAShipping !!}
		</script>
	@endif

	@if(isset($GAPayment) && $GAPayment!='')
			
		<script type="text/javascript">
		{!! $GAPayment !!}
		</script>
	@endif
	
	<script>(function () {Grin = window.Grin || (window.Grin = []);var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'https://d38xvr37kwwhcm.cloudfront.net/js/grin-sdk.js';var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})();</script>
	<!-- Google Tag Manager -->
	<script>
		var load_gtm_script = function() {
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=true;j.src="https://fbstape.maxaroma.com/gtm.js?"+i;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','id=GTM-NXK4PT');
		}; 
		$(window).bind("load", function() { 
			load_gtm_script();
		});
	</script>
	<!-- End Google Tag Manager -->
	@if(Session::get('sess_icustomerid') > 0 && empty($GARegister1))
		<script type='text/javascript'>		
			async function hashEmail(email) {
				const encoder = new TextEncoder();
				const data = encoder.encode(email.trim().toLowerCase());
				const hashBuffer = await crypto.subtle.digest('SHA-256', data);
				const hashArray = Array.from(new Uint8Array(hashBuffer));
				return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
			  }

			  const userIdVal = "{{Session::get('sess_icustomerid')}}";
			  const emailAdd = @json(Session::has('sess_useremail') ? Session::get('sess_useremail') : null);

			  if (userIdVal) {
				if (emailAdd) {
				  hashEmail(emailAdd).then(function(hashedEmail) {
					dataLayer.push({
					  'userId': userIdVal,
					  'hashed_email': hashedEmail,
					  'emailadd' : emailAdd,
					  'external_id': '{{setExternalIDHash()}}'
					});
				  });
				} else {
				  dataLayer.push({
					'userId': userIdVal,
					'external_id': '{{setExternalIDHash()}}'
				  });
				}
			  }		
		</script>
	@elseif(empty($GARegister1))
		<script type='text/javascript'>dataLayer.push({'userId': 'auto', 'external_id': '{{setExternalIDHash()}}'});</script>
	@endif
	
	
	@if(isset($GA4) && $GA4!='')
			
		<script type="text/javascript">
		{!! $GA4 !!}
		</script>
	@endif
	
	@if($CurrentRoute == 'home')
	
        <!-- TrustBox script -->
        {{-- <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script> --}}
        <!-- End TrustBox script -->
	@endif	
	
	
	<!--@if($CurrentRoute != 'order-receipt')	
	<script type="text/javascript">var bizrate={small:"true"};</script>
	<script src="//medals.bizrate.com/medals/js/304245_medal.js" type="text/javascript"></script>
	@endif	
	@if(isset($criteoskutop3skus) && $criteoskutop3skus !='')
		<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
		<script type="text/javascript">
			window.criteo_q = window.criteo_q || [];
			window.criteo_q.push(
				{ event: "setAccount", account: 50896 },
				{ event: "setEmail", email: '{{Session::get("sess_useremail")}}' },
				{ event: "setSiteType", type: "{{config('typevalofcriteo')}}"},
				{ event: "viewList", item:[{!! $criteoskutop3skus !!}]}
			);
		</script>
	@endif-->

	@if($CurrentRoute == 'order-receipt')
       
		
		<!--START Pepperjam CODE--> <noscript> <iframe src="//nojscontainer.pepperjam.com/1193642088.html" width="1" height="1" frameborder="0"></iframe> </noscript> <script> (function(){ var a=document.createElement("script"); a.type="text/javascript", a.async=!0, a.src="//container.pepperjam.com/1193642088.js"; var b=document.getElementsByTagName("head")[0]; if(b) b.appendChild(a,b); else { var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b) } })(); </script> <!--END Pepperjam CODE-->
		
		@if(isset($pixel_html))
			{!! $pixel_html !!}	
		@endif	
		<script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
		<script>
			window.renderOptIn = function() { 
				window.gapi.load("surveyoptin", function() {
				window.gapi.surveyoptin.render(
					{
					// REQUIRED
					"merchant_id": "110911517",
					"order_id": "{{$MainOrder->orders_no}}",
					"email": "{{$MainOrder->bill_email}}",
					"delivery_country": "{{$MainOrder->bill_country}}",
					"estimated_delivery_date": "{{$Estimated_Ship_Date}}",
					// OPTIONAL
					"opt_in_style": "CENTER_DIALOG",
					"products": [{!! $trusted_code !!}]
				}); 
			 });
		  }
		</script>
		@if(isset($GOOGLE_ORDER_TRACKING_GTM))
			{!! $GOOGLE_ORDER_TRACKING_GTM !!}		
		@endif
		
		@if(isset($RIO_EBAY_COMMERCE_CODE))
			{!! $RIO_EBAY_COMMERCE_CODE !!}		
		@endif
		
		
		
		@if(isset($roktstr) && $roktstr != '')
		<script type='text/javascript'>
			 (function(r,o,k,t,n,e,w,a,_){r._ROKT_=n;r[n]=r[n]||{id:t,h:w,lc:[],it:new Date(),onLoaded:function(c){r[n].lc.push(c)}};a=o.createElement('script');a.type='text/javascript';a.async=!0;a.src=k;if(e){a.integrity=e;a.crossOrigin='anonymous'}_=o.getElementsByTagName('script')[0];_.parentNode.insertBefore(a,_)})(window,document,'https://apps.rokt.com/wsdk/integrations/snippet.js','2926714591093489435_c271bb7cd67245f99c88566f473209f9', 'rokt');

			 rokt.onLoaded(function(rokt) {
				  rokt.setAttributes({          
					   email: "{{$MainOrder->bill_email}}",
					   firstname: "{{$MainOrder->bill_first_name}}",
					   lastname: "{{$MainOrder->bill_last_name}}",
					   address1: "{{$MainOrder->bill_address1}}",
					   address2: "{{$MainOrder->bill_address2}}",
					   city: "{{$MainOrder->bill_city}}",
					   state: "{{$MainOrder->bill_state}}",
					   zipcode: "{{$MainOrder->bill_zip}}",
					   country: "{{$MainOrder->bill_country}}",
					   mobile: "{{$MainOrder->bill_phone}}",
					   language: '',
					   confirmationref: "{{$MainOrder->orders_no}}",
					   currency: 'USD',
					   amount: "{{$MainOrder->order_total}}",
					   paymenttype: "{{$MainOrder->payment_method}}",
					   ccbin: '',
					   cartItems: JSON.stringify
					   ([
							{!! $roktstr !!}
					   ])
				  });
			 });
		</script>
		@endif
		<!--@if($CurrentRoute!= 'order-receipt')
		<script type="text/javascript" src="//eval.bizrate.com/js/pos_304245.js"></script>
		@endif-->
		
		<script>
			// set the amount here
			var amount = '{{$MainOrder->order_total}}';
			// set an order number if it exists
			var order_number = '{{$MainOrder->orders_no}}';
			// send the conversion to Grin
			Grin = window.Grin || (window.Grin = []);Grin.push(['conversion', amount, {order_number: order_number}]);
		</script>
	@endif
    
    @if($CurrentRoute == 'brand-name-perfumes')
        <!-- TrustBox script -->
        {{-- <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script> --}}
        <!-- End TrustBox script -->
        <script>
            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (isMobile) {
                function animatebrand(typeval) {
                    $('html, body').animate({
                        scrollTop: $("#" + typeval).offset().top - 180
                    }, 1000);
                }
            } else {
                function animatebrand(typeval) {
                    $('html, body').animate({
                        scrollTop: $("#" + typeval).offset().top - 120
                    }, 1000);
                }
            }
        </script>
    @endif

	@if(isMobile() == 0)
		<script>
			window.renderBadge = function() {
				var ratingBadgeContainer = document.createElement("div");
				document.body.appendChild(ratingBadgeContainer);
				window.gapi.load('ratingbadge', function() {
				window.gapi.ratingbadge.render(
					ratingBadgeContainer, {
						"merchant_id": 110911517,
						"position": "BOTTOM_LEFT"
					});
				});
			}
		</script>	
	@endif
	<script>
		window.___gcfg = {
			lang: 'en_US'
		};
	</script>

	<script>
		$("#getHelpChat").click(function(){
			$('.helpButtonEnabled').trigger("click");
		});
	</script>
	

	@if($CurrentRoute != 'phoneorder_payment_receipt')
		<script>
		function loadSupportChat() {
			if (window.supportChatLoaded) return;
			$("#page-spinner").show();
			(window._xsq || function(x, s) {
			window._xsq = [];
			var c, b, a = document.createElement("iframe");
			a.src = "javascript:false";
			a.title = "";
			a.role = "presentation";
			(a.frameElement || a).style.cssText = "display: none";
			document.body.appendChild(a);
			try {
				b = a.contentWindow.document;
			} catch (f) {
				c = document.domain;
				a.src = "javascript:var d=document.open();d.domain='" + c + "';void(0);";
				b = a.contentWindow.document;
			}
			b.open()._l = function() {
				var a = this.createElement("script");
				c && (this.domain = c);
				a.id = "js-iframe-async";
				a.src = "https://" + x + s;
				this.body.appendChild(a);
			};
			b.write('<body onload="document._l();">');
			b.close();
			return _xsq;
			})("widgets.xsellco.com", "/js/widgets.js")
			.push(["load", "a3hs4e30b", document.scripts[document.scripts.length - 1]]);

			window.supportChatLoaded = true;

			var attempts = 0;
			var checkLoaded = setInterval(function () {
				var iframeList = document.querySelectorAll("iframe");
				var widgetReady = false;

				iframeList.forEach(function (frame) {
					try {
						var btn = frame.contentDocument.querySelector(".btn-edesk-chat");
						if (btn) widgetReady = true;
					} catch (e) {
						
					}
				});

				if (widgetReady || ++attempts > 20) {
					clearInterval(checkLoaded);
					$("#page-spinner").hide();					
				}
			}, 500);

		}

		["getHelpChat", "getHelpChatNew"].forEach(function (id) {
		var el = document.getElementById(id);
		if (el) {
			el.addEventListener("click", function () {								
				loadSupportChat();				
			});
		}
	});

</script>
	@endif
	@if(isset($OmniProdData))
		<script type="text/javascript">
			window.omnisend = window.omnisend || [];    
			omnisend.push(["accountID", "61a57424f7860b001f9ed49f"]);    
			omnisend.push(["track", "$productViewed",{!! $OmniProdData !!}]);    
			!function(){var e=document.createElement("script");
			e.type="text/javascript",e.async=!0,e.src="https://omnisnippet1.com/inshop/launcher-v2.js";
			var t=document.getElementsByTagName("script")[0];
			t.parentNode.insertBefore(e,t)}(); 
		</script>
	@else	
		<script type="text/javascript">
			window.omnisend = window.omnisend || [];    
			omnisend.push(["accountID", "61a57424f7860b001f9ed49f"]);    
			omnisend.push(["track", "{{SetPageview($CurrentRoute)}}"]);    
			!function(){var e=document.createElement("script");
			e.type="text/javascript",e.async=!0,e.src="https://omnisnippet1.com/inshop/launcher-v2.js";
			var t=document.getElementsByTagName("script")[0];
			t.parentNode.insertBefore(e,t)}(); 
		</script>
	@endif	

    
    @if(config('global.YOTPO_PROG') == true)
		@if($CurrentRoute == 'referral-program')
		<script src="https://cdn-widgetsrepository.yotpo.com/v1/loader/uIl5V6C_LVeCr5BT4bhDLQ" async></script>
		@endif
        @if(Session::has('sess_useremail') && Session::get('sess_useremail') != '')
            <div 
                id="swell-customer-identification"
                data-authenticated="true"
                data-email="{{Session::get('sess_useremail')}}"
                data-id="{{Session::get('sess_icustomerid')}}"
                style="display:none;">
            </div>
            {{Session::get('ShoppingCart.YotpoFreeGiftCoupon')}}
            <div 
                id="yotpo-loyalty-cart-data" 
                data-free-product-points="" 
                data-cart-currency="USD"
                data-applied-coupon-points="" 
                data-cart-id="{{Session::get('sess_icustomerid')}}" 
                @if(config('PaidItemInCart') == 1)
                    data-has-paid-product="true"
                @else
                    data-has-paid-product="false"
                @endif 
                @if(Session::get('ShoppingCart.YotpoFreeGiftCoupon') != '') 
                    data-has-free-product="true"
                @else 
                    data-has-free-product="false"
                @endif>
            </div>
            <script src="https://cdn-widgetsrepository.yotpo.com/v1/loader/uIl5V6C_LVeCr5BT4bhDLQ" async></script>
            <script>
                async function onProductRedemption(data) {
                    console.log(data);  
                    if(data.id)
                    {
                        if(data.costInPoints && data.costInPoints > 0)
                        {
                            $("#yotpo-loyalty-cart-data").attr('data-free-product-points',data.costInPoints);  
                            $("#yotpo-loyalty-cart-data").attr('data-applied-coupon-points',data.costInPoints);
                            var YotpoFreeProduct = AddToCartForYotpoFreeGift(data.variantId,data.rewardText);
                            $("#yotpo-loyalty-cart-data").attr('data-has-free-product',YotpoFreeProduct);
                            return YotpoFreeProduct;
                        }
                    }
                    //return true;
                }
            </script>
        @endif
    @endif
@endif	

@if($CurrentRoute == 'order-receipt')	
	<? //print_r($MainOrder);
	$total_discount = 0; 
	$payout = 0;
	if(isset($MainOrder->wire_discount) && $MainOrder->wire_discount > 0){
		$total_discount = $total_discount + $MainOrder->wire_discount;
	}
	if(isset($MainOrder->auto_discount) && $MainOrder->auto_discount > 0){
		$total_discount = $total_discount + $MainOrder->auto_discount;
	}	
	if(isset($MainOrder->quantity_discount) && $MainOrder->quantity_discount > 0){
		$total_discount = $total_discount + $MainOrder->quantity_discount;
	}
	if(isset($MainOrder->reward_discount) && $MainOrder->reward_discount > 0){
		$total_discount = $total_discount + $MainOrder->reward_discount;
	}
	if(isset($MainOrder->coupon_amount) && $MainOrder->coupon_amount > 0){
		$total_discount = $total_discount + $MainOrder->coupon_amount;
	}
	if(isset($MainOrder->bogo_discount) && $MainOrder->bogo_discount > 0){
		$total_discount = $total_discount + $MainOrder->bogo_discount;
	}
	$payout_s = $MainOrder->sub_total - $total_discount;
	if($payout_s != "" && $payout_s > 0){
		$payout = number_format($payout_s,2);
	}	
	?>
	<!-- Offer Conversions: Ambassadors-->
	<img src="https://aspireiq.go2cloud.org/aff_l?offer_id=13603&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	<!-- Offer Conversion: MAXAROMA YouTube -->
	<img src="https://aspireiq.go2cloud.org/aff_l?offer_id=14310&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	<!-- Offer Conversion: TikTok MAXAROMA -->
	<img src="https://aspireiq.go2cloud.org/aff_l?offer_id=16592&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	<!-- Offer Conversion: MAXAROMA Q1 Campaign -->
	<img src="https://get.aspr.app/aff_l?offer_id=20584&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	<!-- Offer Conversion: MAXAROMA Q1 Ambassodars -->
	<img src="https://get.aspr.app/aff_l?offer_id=24461&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	
	<!-- Offer Conversion: MAXAROMA Sales Tracking -->
	<img src="https://get.aspr.app/aff_lsr?offer_id=28550&amount={{ $MainOrder->order_total }}&transaction_id={{ $MainOrder->orders_no }}" width="1" height="1" />
	<!-- // End Offer Sales Tracking -->
	
	﻿﻿<!-- Offer Conversion: House Of Dastan -->
	<img src="https://get.aspr.app/aff_l?offer_id=215073&amount={{ $MainOrder->order_total }}" width="1" height="1" />
	<!-- // End Offer Conversion -->
	@endif
	

<!--Code for Searchspring IntelliSuggest Tracking Start-->
{{--@if($CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code' || $CurrentRoute == 'order-receipt' || $CurrentRoute == 'shoppingcart' )--}}
{{--@if($CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code' || $CurrentRoute == 'order-receipt' || $CurrentRoute == 'shoppingcart')--}}

<script type="text/javascript" src="//cdn.searchspring.net/intellisuggest/is.min.js"></script>
<script type="text/javascript">
var intialValues = {siteId:"faltym"};
try{ 
    @if(($CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code') && isset($productDetails) && $productDetails->sku != '')
        var product_code = "{{ $productDetails->sku }}";
        var product_ID = "{{ $productDetails->products_id }}";
        IntelliSuggest.init({siteId: 'faltym', context:'Product/' + product_code, seed:[product_code]});
        @if(Session::has("sess_icustomerid"))
			IntelliSuggest.setShopperId('{{Session::get("sess_icustomerid")}}');
		@endif
		IntelliSuggest.viewItem({ uid: product_ID, sku: product_code });
    @endif
	
	@if($CurrentRoute == 'shoppingcart')
		@if(Session::has('ShoppingCart') && Session::get('ShoppingCart') != '')
			@php
				$cart_arr = Session::get('ShoppingCart.Cart');
				$skus = '';

				if (!empty($cart_arr)) {
				$sku_array = array_map(function ($item) {
				return $item['SKU'];
				}, $cart_arr);

				//$skus = "'".implode("','", $sku_array)."'";
				$skus = implode(',', $sku_array);
				}
			@endphp
			var setSku = '{{ $skus }}';
			setSku = setSku.replace(",", "','");
			IntelliSuggest.init({siteId: 'faltym', context: 'Basket', seed: [setSku]});
			
			@if(Session::has("sess_icustomerid"))
				IntelliSuggest.setShopperId('{{Session::get("sess_icustomerid")}}');
			@endif
			
			@if(!empty($cart_arr))
				@foreach($cart_arr as $item)
					IntelliSuggest.haveItem({
					uid: '{{ $item["ProductID"] }}',
					childUid: '',
					sku: '{{ $item["SKU"] }}',
					childSku: '',
					price: '{{ $item["ItemPrice"] ?? 0 }}',
					qty: '{{ $item["Qty"] ?? 1 }}'
					});
				@endforeach
				IntelliSuggest.setCurrency({ code: 'USD' });
				IntelliSuggest.inBasket();
			@endif
		@endif	
	@endif
	
    @if( $CurrentRoute == 'order-receipt' && isset($OrderDetails) && isset($MainOrder))
        IntelliSuggest.init(intialValues);
		IntelliSuggest.init({siteId: 'faltym' });
		@if(Session::has("sess_icustomerid"))
			IntelliSuggest.setShopperId('{{Session::get("sess_icustomerid")}}');
		@endif
		
        @foreach($OrderDetails as $key => $Order)
            IntelliSuggest.haveItem({uid:"{{$Order->products_id}}", sku:"{{$Order->sku}}", qty:"{{$Order->quantity}}", price:"{{$Order->total}}"});
        @endforeach
        IntelliSuggest.inSale({
            orderId:'{{$MainOrder->orders_id}}',
            total:'{{$MainOrder->order_total}}',
            city:'{{$MainOrder->ship_city}}',
            state:'{{$MainOrder->ship_state}}',
            country:'{{$MainOrder->ship_country}}'
        });
    @endif
} catch(err) {}
</script>
{{--@endif--}}
<!--Code for Searchspring IntelliSuggest Tracking End-->

@if(!Session::has("sess_store_id"))
<script type='text/javascript'>
	dataLayer.push({'event': 'websiteallpages',});
</script>
@endif

