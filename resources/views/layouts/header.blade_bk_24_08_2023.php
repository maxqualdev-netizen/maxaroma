@if(config('global.cntOffer') == 1)
<div class="h-fixed d-flex">
	<div class="parsent-hd">%</div>
	<div class="parsent-box"> 
	<a href="https://www.maxaroma.com/offers.html">New & Sale</a> </div>
		<!-- <a href="#" onclick="DisplayPopupOffer();" data-toggle="modal" data-target="#sales-offers-popup">New & Sale</a> </div> -->
</div>
@endif
@include('layouts.popups')
@include('layouts.sidecart')
@include('layouts.mobile_menu')
<div class="sb-slidebar sb-left" data-sb-width="290px">
	<div class="mobile-nav" id="mobiNav"></div>
</div>
<div id="sb-site">
	@if(config('show_notification') == 'Show')
	<div class="header-msg active" style="background: #{{ config('color_picker') }} " id="notificationtoggle">
		<a href="javascript:void(0);" class="msg-close" id="topmsgclose">
			<svg class="sv-close vam" aria-hidden="true" role="img" width="14" height="14" style="fill: #fff;">
			<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
			</svg>
		</a>
		<p class="mb-0" style="color:#fff;">{!! config('notification_text') !!}</p>
	</div>
	@endif
	@include('layouts.menu')
