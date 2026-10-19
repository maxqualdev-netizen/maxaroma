@if(config('global.cntOffer') == 1)
<div class="h-fixed d-flex" role="region" aria-label="Special Offer">
    <div class="parsent-hd" aria-hidden="true">%</div>
    <div class="parsent-box"> 
        <a href="https://www.maxaroma.com/offers.html" aria-label="View new and sale offers">New & Sale</a>
        <!-- <a href="#" onclick="DisplayPopupOffer();" data-toggle="modal" data-target="#sales-offers-popup">New & Sale</a> -->
    </div>
</div>
@endif
@include('layouts.popups')
@include('layouts.sidecart')
@include('layouts.mobile_menu')
<div class="sb-slidebar sb-left" data-sb-width="290px" role="navigation" aria-label="Mobile Navigation Sidebar">
    <div class="mobile-nav" id="mobiNav"></div>
</div>
<div id="sb-site">
    @if(config('show_notification') == 'Show')
    <div class="header-msg active" style="background: #{{ config('color_picker') }} " id="notificationtoggle" role="status" aria-live="polite" aria-atomic="true">
        <a href="javascript:void(0);" class="msg-close" id="topmsgclose" aria-label="Close notification">
            <svg class="sv-close vam" aria-hidden="true" role="img" width="14" height="14" style="fill: #fff;">
            <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
            </svg>
        </a>
        <p class="mb-0" style="color:#fff;" id="notificationText">{!! config('notification_text') !!}</p>
    </div>
    @endif
    @include('layouts.menu')
