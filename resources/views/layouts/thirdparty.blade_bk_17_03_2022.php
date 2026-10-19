@if(config('global.SITE_MODE') == 'Live')
	
	<script type="text/javascript">
		window.dataLayer = window.dataLayer || [];
	</script>
	
	@if(count($GTMDATA) > 0)
		{!! $GTMDATA['google_remarketing_codes'] !!}
	@endif

	<script>(function () {Grin = window.Grin || (window.Grin = []);var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'https://d38xvr37kwwhcm.cloudfront.net/js/grin-sdk.js';var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})();</script>
	<!-- Google Tag Manager -->
	<script>
		var load_gtm_script = function() {
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NXK4PT');
		}; 
		$(window).bind("load", function() { 
			load_gtm_script(); 
		});
	</script>
	<!-- End Google Tag Manager -->
	@if(Session::get('sess_icustomerid') > 0)
		<script type='text/javascript'>
			dataLayer.push({'userId': "{{Session::get('sess_icustomerid')}}" });
		</script>
	@else
		<script type='text/javascript'>dataLayer.push({'userId': 'auto'});</script>
	@endif
	
	@if($CurrentRoute == 'home')
		<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
		<script type="text/javascript">
			window.criteo_q = window.criteo_q || [];
			window.criteo_q.push(
				{ event: "setAccount", account: 50896 },
				{ event: "setEmail", email: "{{Session::get('sess_useremail')}}" },
				{ event: "setSiteType", type: "{{config('typevalofcriteo')}}"},
				{ event: "viewHome" }
			);
		</script>
	@endif	
	
	@if($CurrentRoute == 'proddetails' || $CurrentRoute == 'proddetails_size' || $CurrentRoute == 'proddetails_code' )
		<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
		<script type="text/javascript">
			window.criteo_q = window.criteo_q || [];
			window.criteo_q.push(
				{ event: "setAccount", account: 50896 },
				{ event: "setEmail", email: "{{Session::get('sess_useremail')}}" },
				{ event: "setSiteType", type: "{{config('typevalofcriteo')}}"},
				{ event: "viewItem", item: "{{config('ProdSKU')}}" }
			);
		</script>
	@endif
	@if($CurrentRoute != 'order-receipt')	
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
	@endif
	@if($CurrentRoute == 'order-receipt')
		
		<script>(function() {
		  var _fbq = window._fbq || (window._fbq = []);
		  if (!_fbq.loaded) {
			var fbds = document.createElement('script');
			fbds.async = true;
			fbds.src = '//connect.facebook.net/en_US/fbds.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(fbds, s);
			_fbq.loaded = true;
		  }
		})();
		window._fbq = window._fbq || [];
		</script>
		
		@if(config('Settings.IS_WEB_SITE_ORDER') == 'Yes')
			<script>window._fbq.push(['track', '6028524510376', {'value':'{{$MainOrder->order_total}}','currency':'{{Session::get('currency_code')}}']);</script>
		@endif
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6028524510376&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
		
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
		@if($CurrentRoute!= 'order-receipt')
		<script type="text/javascript" src="//eval.bizrate.com/js/pos_304245.js"></script>
		@endif
		@if($critieostr!='')
			<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
			<script type="text/javascript">
				window.criteo_q = window.criteo_q || [];
				window.criteo_q.push(
					{ event: "setAccount", account: 50896 },
					{ event: "setEmail", email: '{{Session::get("sess_useremail")}}' },
					{ event: "setSiteType", type: "{{config('typevalofcriteo')}}"},
					{ event: "trackTransaction", id: "{{$MainOrder->orders_no}}", item: [{!! $critieostr !!}]}
				);
			</script>
		@endif
		<script>
			// set the amount here
			var amount = '{{$MainOrder->order_total}}';
			// set an order number if it exists
			var order_number = '{{$MainOrder->orders_no}}';
			// send the conversion to Grin
			Grin = window.Grin || (window.Grin = []);Grin.push(['conversion', amount, {order_number: order_number}]);
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
	<script type="text/javascript">
		adroll_adv_id = "QDBJ3WSJW5EALCLESMTLFL";
		adroll_pix_id = "AV3NOP3K35H65NYWTYBNLV";
		adroll_version = "2.0";

		(function(w, d, e, o, a) {
			w.__adroll_loaded = true;
			w.adroll = w.adroll || [];
			w.adroll.f = [ 'setProperties', 'identify', 'track' ];
			var roundtripUrl = "https://s.adroll.com/j/" + adroll_adv_id
					+ "/roundtrip.js";
			for (a = 0; a < w.adroll.f.length; a++) {
				w.adroll[w.adroll.f[a]] = w.adroll[w.adroll.f[a]] || (function(n) {
					return function() {
						w.adroll.push([ n, arguments ])
					}
				})(w.adroll.f[a])
			}

			e = d.createElement('script');
			o = d.getElementsByTagName('script')[0];
			e.async = 1;
			e.src = roundtripUrl;
			o.parentNode.insertBefore(e, o);
		})(window, document);
		adroll.track("pageView");
	</script>

	@if($CurrentRoute != 'phoneorder_payment_receipt')
		<style type='text/css'>
            .embeddedServiceHelpButton .helpButton{display: none;}
			.embeddedServiceHelpButton .helpButton .uiButton {
				background-color: #565656;
				font-family: "Arial", sans-serif;
			}
			.embeddedServiceHelpButton .helpButton .uiButton:focus {
				outline: 1px solid #565656;
			}
		</style>
		<script type='text/javascript' src='https://service.force.com/embeddedservice/5.0/esw.min.js'></script>
		<script type='text/javascript'>
                var initESW = function(gslbBaseURL) {
					embedded_svc.settings.displayHelpButton = true; //Or false
					embedded_svc.settings.language = ''; //For example, enter 'en' or 'en-US'
				   
					embedded_svc.settings.enabledFeatures = ['LiveAgent'];
					embedded_svc.settings.entryFeature = 'LiveAgent';
					embedded_svc.init(
						'https://maxaroma.my.salesforce.com',
						'https://chat1.secure.force.com/liveAgentSetupFlow',
						gslbBaseURL,
						'00D6g000005gu5P',
						'Chat_Agents',
						{
							baseLiveAgentContentURL: 'https://c.la1-c2-ia5.salesforceliveagent.com/content',
							deploymentId: '5726g0000001WvE',
							buttonId: '5736g0000001XuT',
							baseLiveAgentURL: 'https://d.la1-c2-ia5.salesforceliveagent.com/chat',
							eswLiveAgentDevName: 'EmbeddedServiceLiveAgent_Parent04I6g0000005H76EAE_170e48f9eef',
							isOfflineSupportEnabled: true
						}
					);
                };
 
                if (!window.embedded_svc) {
					var s = document.createElement('script');
					s.setAttribute('src', 'https://maxaroma.my.salesforce.com/embeddedservice/5.0/esw.min.js');
					s.onload = function() {
									initESW(null);
					};
					document.body.appendChild(s);
				} else {
					initESW('https://service.force.com');
                }
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
	@endif
	{{--
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
	--}}
@endif	
	
