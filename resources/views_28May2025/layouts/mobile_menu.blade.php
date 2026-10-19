<div class="sidepanel-nw" id="navnw-open"> 
	<a href="javascript:void(0);" class="close-nv">
    <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
    </svg>
    </a>
    <div class="nav-header">Menu</div>
    <nav class="mainNav">
	  <div class="menu-mob"></div>
    <div class="myact_link"><a href="{{config('global.SITE_URL')}}track-order.html"><svg class="sv_location" aria-hidden="true" role="img" width="23" height="27">
			<use href="#sv_location" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_location"></use>
			</svg>&nbsp; Track Order</a></div>
    <div class="myact_link">
		@auth
			<a href="{{config('global.SITE_URL')}}myaccount.html" ><svg class="sv-user vam" aria-hidden="true" role="img" width="23" height="27"><use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use></svg> &nbsp; My Account</a>
		@endauth
		@guest
			<a href="{{config('global.SITE_URL')}}login.html" ><svg class="sv-user vam" aria-hidden="true" role="img" width="23" height="27"><use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use></svg> &nbsp; My Account</a>
		@endguest
    </div>
    <div class="header_currency">
            <div class="select-auto">
              <select class="select" sb="5982666" id="selcurrencymob">
				@foreach(config('Currencies') as $MobileCurrency)
					<option value="{{$MobileCurrency['currency_id']}}" @if(Session::get('currency_code') == $MobileCurrency['currency_code']) selected @endif >{{$MobileCurrency['currency_code']}}</option>
				@endforeach
              </select>
            </div><div class="clearfix"></div>
          </div>
  </nav>
    
</div>