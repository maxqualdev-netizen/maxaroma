<div class="sidepanel-nw" id="navnw-open" role="dialog" aria-modal="true" aria-label="Mobile Menu"> 
    <a href="javascript:void(0);" class="close-nv" aria-label="Close menu">
        <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
            <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
        </svg>
    </a>
    <div class="nav-header" id="mobileMenuHeader">Menu</div>
    <nav class="mainNav" role="navigation" aria-labelledby="mobileMenuHeader">
        <div class="menu-mob"></div>
        <div class="myact_link">
            <a href="{{config('global.SITE_URL')}}track-order.html" aria-label="Track your order">
                <svg class="sv_location" aria-hidden="true" role="img" width="23" height="27">
                    <use href="#sv_location" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_location"></use>
                </svg>&nbsp; Track Order
            </a>
        </div>
        <div class="myact_link">
            @auth
                <a href="{{config('global.SITE_URL')}}myaccount.html" aria-label="Go to My Account">
                    <svg class="sv-user vam" aria-hidden="true" role="img" width="23" height="27">
                        <use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
                    </svg> &nbsp; My Account
                </a>
            @endauth
            @guest
                <a href="{{config('global.SITE_URL')}}login.html" aria-label="Login to your account">
                    <svg class="sv-user vam" aria-hidden="true" role="img" width="23" height="27">
                        <use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
                    </svg> &nbsp; My Account
                </a>
            @endguest
        </div>
        <div class="header_currency">
            <div class="select-auto">
                <label for="selcurrencymob" class="sr-only">Select currency</label>
                <select class="select" sb="5982666" id="selcurrencymob" aria-label="Select currency">
                    @foreach(config('Currencies') as $MobileCurrency)
                        <option value="{{$MobileCurrency['currency_id']}}" @if(Session::get('currency_code') == $MobileCurrency['currency_code']) selected @endif >{{$MobileCurrency['currency_code']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
    </nav>
</div>