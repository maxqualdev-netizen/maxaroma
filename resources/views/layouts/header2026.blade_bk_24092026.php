

{{--
@if(config('global.cntOffer') == 1)
<div class="h-fixed d-flex" role="region" aria-label="Special Offer">
    <div class="parsent-hd" aria-hidden="true">%</div>
    <div class="parsent-box"> 
        <a href="https://www.maxaroma.com/offers.html" aria-label="View new and sale offers">
            New & Sale
        </a>
    </div>
</div>
@endif
@include('layouts.sidecart')
@include('layouts.popups')
@include('layouts.mobile_menu')

<div class="sb-slidebar sb-left" data-sb-width="290px" role="navigation" aria-label="Mobile Navigation Sidebar">
    <div class="mobile-nav" id="mobiNav"></div>
</div>

<div id="sb-site">
    @if(config('show_notification') == 'Show')
    <div 
        class="header-msg active" 
        style="background: #{{ config('color_picker') }}" 
        id="notificationtoggle" 
        role="status" 
        aria-live="polite" 
        aria-atomic="true"
    >
        <a 
            href="javascript:void(0);" 
            class="msg-close" 
            id="topmsgclose" 
            aria-label="Close notification"
        >
            <svg 
                class="sv-close vam" 
                aria-hidden="true" 
                role="img" 
                width="14" 
                height="14" 
                style="fill: #fff;"
            >
                <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
            </svg>
        </a>

        <p class="mb-0" style="color:#fff;" id="notificationText">
            {!! config('notification_text') !!}
        </p>
    </div>
    @endif

    @include('layouts.menu')
--}}
@include('layouts.sidecart')

 <!-- ================= Left Sidebar & Overlay ================= -->
  <!-- Backdrop overlay for outside-click to close -->
  <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>
  <!-- Slide-in Sidebar from Left -->
  <aside class="sidebar-drawer" id="sidebar-drawer" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title" aria-hidden="true" inert tabindex="-1">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
      <h3 class="sidebar-title" id="auth-modal-title">Sign In</h3>
      <button type="button" class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Close sidebar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M18 6L6 18M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <!-- Sidebar Content / Sign In Form -->
    <div class="sidebar-body">
      <div class="auth-modal-stage" data-auth-stage>
        <div class="spg-panel auth-modal-panel is-active" data-auth-panel="signin" aria-labelledby="auth-modal-title" aria-hidden="false">
          <div class="spg-body loginbody pb-sm-0">
              <div class="eheight">
                  <div class="form-group">
                      <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email Address" required aria-required="true" autocomplete="email">
                      <div class="frmerror" role="alert" id="error_email">

                      </div>
                  </div>
                  <div class="pass-div form-group">
                      <!-- <a href="#" class="sml-text float-right" aria-label="Forgot your password?">Forgot your password?</a> -->
                      <label for="password" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
                      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required aria-required="true" autocomplete="current-password">
                      <div class="frmerror" role="alert" id="error_password">

                      </div>
                  </div>
                  <input type="hidden" id="is_for_store" name="is_for_store" value="N">
                  <input type="hidden" id="login_with_otp" name="login_with_otp" value="N">
                  <div class="form-group">
                      <div class="field required">
                          <div class="d-block mt-3">
                              <div data-sitekey="6Lf9tMcUAAAAAHdQKDrYsXKq0YtNu9c6tpLsZ1fm" class="g-recaptcha" id="auth-g-recaptcha"></div>
                          </div>
                          <div class="frmerror" role="alert" id="error_captcha_js">

                          </div>
                          <input id="hiddenRecaptcha" name="hiddenRecaptcha" type="text" class="form-control validateGRecaptcha" style="display:none;" required aria-label="reCAPTCHA verification">
                      </div>
                  </div>
                  <div class="mb-3">
                      <button class="btn_link float-right auth-modal-trigger" type="button" data-auth-target="forgot" aria-label="Forgot Password?">Forgot Password?</button>
                      <label class="checkbox-label">
                          <div class="chebox">
                              <input type="checkbox" name="rememberme" id="rememberme" checked="" aria-label="Remember Me"><span class="checkmark"></span>
                          </div>
                          Remember Me<span class="errmsg">*</span>
                          <div class="frmerror" role="alert" id="error_rememberme">

                          </div>
                      </label>
                  </div>
              </div>
              <button type="submit" name="" class="btn btn-primary w-100" id="btnsubmit">
                  Sign In
              </button>
          </div>
          <div class="eheight creating_text pt-3">
            <ul class="dots">
                <li>Giveaway Alerts</li>
                <li>Easily redeem rewards</li>
                <li>Brand Drops</li>
                <li>Presale and exclusive access</li>
                <li>Easily Presale track orders and view order history</li>
            </ul>
        </div>
        <div class="">
            <button type="button" class="btn btn-secondary w-100 signinsignup auth-modal-trigger" data-auth-target="signup" aria-label="Sign up">Sign up</button>
        </div>
        </div>
        <section class="auth-modal-panel" data-auth-panel="forgot" aria-labelledby="auth-modal-title" aria-hidden="true" inert>
          <div class="form-group">
            <label for="forgot-password-email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
            <input type="email" id="forgot-password-email" name="forgot_password_email" class="form-control" placeholder="Email Address" autocomplete="email" required>
          </div>
          <button type="button" class="btn btn-primary w-100" data-auth-reset-submit>Submit</button>
          <button type="button" class="btn_link auth-modal-back" data-auth-target="signin">Back to Sign In</button>
        </section>
        <section class="auth-modal-panel" data-auth-panel="signup" aria-labelledby="auth-modal-title" aria-hidden="true" inert>
          <div class="form-group">
            <label for="signup-email" class="col-form-label pt-0 text-left">Email<span class="errmsg">*</span></label>
            <input type="email" id="signup-email" name="signup_email" class="form-control" placeholder="Enter Your Email Address" autocomplete="email" required>
          </div>
          <div class="form-group">
            <label for="signup-password" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
            <input type="password" id="signup-password" name="signup_password" class="form-control" placeholder="Password" autocomplete="new-password" required>
          </div>
          <div class="auth-signup-consent-wrap">
            <label class="checkbox-label auth-signup-consent" for="signup-consent">
              <div class="chebox"><input type="checkbox" id="signup-consent" required aria-required="true"><span class="checkmark"></span></div>
              <span>By creating an account, you agree to receive MAXAROMA's marketing emails to get first dibs on new arrivals, sales, exclusive content, and more! You may unsubscribe at any time.<span class="errmsg">*</span></span>
            </label>
            <div class="sml-text mt-1 text-muted">
              By creating an account, you agree to MAXAROMA's <a href="https://www.maxaroma.com/terms-and-conditions.html" class="ulink" target="_blank" rel="noopener noreferrer">Terms</a> of use and <a class="ulink" target="_blank" rel="noopener noreferrer" href="https://www.maxaroma.com/privacy-policy.html">Privacy Policy</a>.
            </div>
          </div>
          <button type="button" class="btn btn-primary w-100 auth-signup-submit">CREATE AN ACCOUNT</button>
          <button type="button" class="btn_link auth-modal-back" data-auth-target="signin">Back to Sign In</button>
        </section>
      </div>
    </div>
  </aside>

<header role="banner" aria-label="Site Header">
    <div class="site-nav">
  <div class="nav-desktop">
    <div class="container">
      <div class="nav-desktop-row">
        <a href="Home.dc.html" class="logo"><img src="https://store.maxaroma.com/public/images/logo.jpg" width="224" height="55" alt="MaxAroma Logo" decoding="async" fetchpriority="high"></a>
        <div class="nav-search-wrap">
          <div class="nav-inner">
          <input class="nav-search" id="desktop-search" name="q" type="search" placeholder="Search fragrances, brands, notes…" aria-label="Search fragrances, brands, and notes">
          <button
              type="button"
              class="nav-search-btn"
              id="desktop-search-button"
              aria-label="Submit search"
          >
              <svg
                  class="sv-search vam"
                  aria-hidden="true"
                  focusable="false"
                  width="17"
                  height="17"
                  role="img"
              >
                  <use
                      href="#sv-search"
                      xlink:href="#sv-search"
                  ></use>
              </svg>
          </button>
          </div>
        </div>
        <div class="nav-icons header_icon">
          <a href="#account" class="nav-icon" data-account-trigger role="button" aria-haspopup="dialog" aria-expanded="false" aria-label="Sign In or View Account">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="1.5" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4 3.5-6.5 8-6.5s8 2.5 8 6.5"></path></svg>
          </a>
          <a href="javascript:void(0)" class="nav-cart cart-link" role="button" title="View your cart" aria-label="Shopping Cart, 2 items"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M6 8h12l-1 12H7L6 8z"></path><path d="M9 8V6a3 3 0 016 0v2"></path></svg><span class="nav-cart-count" aria-hidden="true">2</span></a>
        </div>
      </div>
      <nav class="nav-links" aria-label="Primary Navigation">
        <a href="Category.dc.html" class="nav-link" data-menu="fragrance">FRAGRANCE</a>
        <a href="#pocket" class="nav-link">TRAVEL &amp; POCKET</a>
        <a href="#skincare" class="nav-link">SKINCARE</a>
        <a href="#brands" class="nav-link" data-menu="brands">BRANDS</a>
        <a href="#edit" class="nav-link">THE EDIT</a>
        <a href="Offers.dc.html" class="nav-link">OFFERS</a>
        <a href="#club" class="nav-link">AROMA CLUB</a>
      </nav>
    </div>
  </div>

  <div class="nav-mobile">
    <div class="nav-mobile-row">
      <button class="nav-burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-drawer"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"></path></svg></button>
      <a href="Home.dc.html" class="logo-mobile">
        <img src="https://store.maxaroma.com/public/images/logo.jpg" width="140" height="34" alt="MaxAroma Logo" decoding="async" fetchpriority="high">
      </a>
      <div class="nav-mobile-spacer"></div>
      <div class="nav-mobile-icons  header_icon">
        <a href="#account" class="nav-icon" data-account-trigger role="button" aria-haspopup="dialog" aria-expanded="false" aria-label="Sign In or View Account"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="1.5" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c0-4 3.5-6.5 8-6.5s8 2.5 8 6.5"></path></svg></a>
        <a href="javascript:void(0)" class="nav-cart cart-link" role="button" title="View your cart" aria-label="Shopping Cart, 2 items"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M6 8h12l-1 12H7L6 8z"></path><path d="M9 8V6a3 3 0 016 0v2"></path></svg><span class="nav-cart-count" aria-hidden="true">2</span></a>
      </div>
    </div>
    <div class="nav-mobile-search">
      <input id="mobile-search" name="q" type="search" placeholder="Search fragrances, brands, notes…" aria-label="Search fragrances, brands, and notes">
      <button type="button" class="nav-search-btn" id="mobile-search-button" aria-label="Search">
          <svg class="sv-search vam" aria-hidden="true" focusable="false" width="16" height="16" role="img">
              <use href="#sv-search" xlink:href="#sv-search"></use>
          </svg>
      </button>
    </div>
  </div>

  <div class="mega" data-menu="fragrance">
    <div class="mega-inner">
      <div class="mega-main">
        <div class="mega-az">
          <span class="mega-az-label">BROWSE A–Z</span>
          <div class="mega-letters">
            <a href="#brands" class="mega-letter">A</a><a href="#brands" class="mega-letter">B</a><a href="#brands" class="mega-letter">C</a><a href="#brands" class="mega-letter">D</a><a href="#brands" class="mega-letter">E</a><a href="#brands" class="mega-letter">F</a><a href="#brands" class="mega-letter">G</a><a href="#brands" class="mega-letter">H</a><a href="#brands" class="mega-letter">I</a><a href="#brands" class="mega-letter">J</a><a href="#brands" class="mega-letter">K</a><a href="#brands" class="mega-letter">L</a><a href="#brands" class="mega-letter">M</a><a href="#brands" class="mega-letter">N</a><a href="#brands" class="mega-letter">O</a><a href="#brands" class="mega-letter">P</a><a href="#brands" class="mega-letter">Q</a><a href="#brands" class="mega-letter">R</a><a href="#brands" class="mega-letter">S</a><a href="#brands" class="mega-letter">T</a><a href="#brands" class="mega-letter">U</a><a href="#brands" class="mega-letter">V</a><a href="#brands" class="mega-letter">W</a><a href="#brands" class="mega-letter">X</a><a href="#brands" class="mega-letter">Y</a><a href="#brands" class="mega-letter">Z</a>
          </div>
        </div>
        <div class="mega-cols">
          <div class="mega-fragrance">
            <div class="mega-col"><span class="mega-col-title">BY GENDER</span><a href="Category.dc.html">Women</a><a href="Category.dc.html">Men</a><a href="Category.dc.html">Unisex</a><a href="Category.dc.html">Gift Sets</a></div>
            <div class="mega-col"><span class="mega-col-title">BY TYPE</span><a href="Category.dc.html">Eau de Parfum</a><a href="Category.dc.html">Extrait de Parfum</a><a href="Category.dc.html">Travel &amp; Pocket</a><a href="Category.dc.html">Discovery Sets</a></div>
            <div class="mega-col"><span class="mega-col-title">BY NOTE</span><a href="Category.dc.html">Vanilla</a><a href="Category.dc.html">Oud</a><a href="Category.dc.html">Rose</a><a href="Category.dc.html">Citrus</a><a href="Category.dc.html">Amber</a><a href="Category.dc.html">Musk</a></div>
            <div class="mega-col"><span class="mega-col-title">BY OCCASION</span><a href="Category.dc.html">Date Night</a><a href="Category.dc.html">Office Safe</a><a href="Category.dc.html">Cold Weather</a><a href="Category.dc.html">Summer Heat</a></div>
          </div>
          <div class="mega-brands">
            <div class="mega-col"><span class="mega-col-title">FEATURED HOUSES</span><a href="#dastan">House of Dastan</a><a href="PDP.dc.html">Mes Bisous</a><a href="#crivelli">Maison Crivelli</a><a href="Category.dc.html">Parfums de Marly</a></div>
            <div class="mega-col"><span class="mega-col-title">DESIGNER</span><a href="Category.dc.html">Dior</a><a href="Category.dc.html">Creed</a><a href="Category.dc.html">Tom Ford</a><a href="Category.dc.html">Chanel</a></div>
            <div class="mega-col"><span class="mega-col-title">NICHE</span><a href="Category.dc.html">Xerjoff</a><a href="Category.dc.html">Nishane</a><a href="Category.dc.html">Initio</a><a href="Category.dc.html">Amouage</a></div>
          </div>
        </div>
      </div>
      <div class="mega-rule"></div>
      <div class="mega-trend">
        <span class="mega-trend-label">TRENDING NOW</span>
        <a href="PDP.dc.html" class="mega-trend-item"><span class="mega-trend-mark">C</span><span class="mega-trend-copy"><span class="mega-trend-brand">CREED</span><span class="mega-trend-name">Aventus Eau de Parfum</span><span class="mega-trend-price"><span class="mega-trend-now">$340</span><span class="mega-trend-was">$510</span></span></span></a>
        <a href="PDP.dc.html" class="mega-trend-item"><span class="mega-trend-mark">PdM</span><span class="mega-trend-copy"><span class="mega-trend-brand">PARFUMS DE MARLY</span><span class="mega-trend-name">Layton Eau de Parfum</span><span class="mega-trend-price"><span class="mega-trend-now">$365</span><span class="mega-trend-was">$420</span></span></span></a>
        <a href="PDP.dc.html" class="mega-trend-item"><span class="mega-trend-mark">X</span><span class="mega-trend-copy"><span class="mega-trend-brand">XERJOFF</span><span class="mega-trend-name">Naxos Eau de Parfum</span><span class="mega-trend-price"><span class="mega-trend-now">$255</span><span class="mega-trend-was">$310</span></span></span></a>
        <a href="Category.dc.html" class="mega-all line-hover">ALL BESTSELLERS</a>
      </div>
    </div>
  </div>

  <div class="drawer-overlay" data-drawer-close aria-hidden="true"></div>
  <div class="drawer nvscroll" id="mobile-drawer" role="navigation" aria-label="Mobile navigation" aria-hidden="true" inert>
    <div class="drawer-head">
      <a href="Home.dc.html" class="drawer-logo" aria-label="MaxAroma Homepage">
        <img src="https://store.maxaroma.com/public/images/logo.jpg" width="140" height="34" alt="MaxAroma Logo" loading="lazy" decoding="async">
      </a>
      <button class="drawer-close" type="button" data-drawer-close aria-label="Close menu">✕</button>
    </div>
    <nav class="drawer-nav">
      <div class="drawer-item" data-key="fragrance">
        <button class="drawer-item-row drawer-toggle" type="button" data-label="FRAGRANCE" aria-label="Expand FRAGRANCE" aria-expanded="false" aria-controls="drawer-panel-fragrance"><span class="drawer-item-label">FRAGRANCE</span><span class="drawer-toggle-icon" aria-hidden="true">+</span></button>
        <div class="drawer-panel" id="drawer-panel-fragrance">
          <a href="Category.dc.html" class="ulink">VIEW ALL FRAGRANCE</a>
          <div class="drawer-group"><span class="drawer-group-title">BY GENDER</span><a href="Category.dc.html">Women</a><a href="Category.dc.html">Men</a><a href="Category.dc.html">Unisex</a><a href="Category.dc.html">Gift Sets</a></div>
          <div class="drawer-group"><span class="drawer-group-title">BY TYPE</span><a href="Category.dc.html">Eau de Parfum</a><a href="Category.dc.html">Extrait de Parfum</a><a href="Category.dc.html">Travel &amp; Pocket</a><a href="Category.dc.html">Discovery Sets</a></div>
          <div class="drawer-group"><span class="drawer-group-title">BY NOTE</span><a href="Category.dc.html">Vanilla</a><a href="Category.dc.html">Oud</a><a href="Category.dc.html">Rose</a><a href="Category.dc.html">Citrus</a><a href="Category.dc.html">Amber</a><a href="Category.dc.html">Musk</a></div>
          <div class="drawer-group"><span class="drawer-group-title">BY OCCASION</span><a href="Category.dc.html">Date Night</a><a href="Category.dc.html">Office Safe</a><a href="Category.dc.html">Cold Weather</a><a href="Category.dc.html">Summer Heat</a></div>
        </div>
      </div>
      <div class="drawer-item">
        <div class="drawer-item-row"><a href="#pocket">TRAVEL &amp; POCKET</a></div>
      </div>
      <div class="drawer-item">
        <div class="drawer-item-row"><a href="#skincare">SKINCARE</a></div>
      </div>
      <div class="drawer-item" data-key="brands">
        <button class="drawer-item-row drawer-toggle" type="button" data-label="BRANDS" aria-label="Expand BRANDS" aria-expanded="false" aria-controls="drawer-panel-brands"><span class="drawer-item-label">BRANDS</span><span class="drawer-toggle-icon" aria-hidden="true">+</span></button>
        <div class="drawer-panel" id="drawer-panel-brands">
          <a href="#brands" class="ulink">VIEW ALL BRANDS</a>
          <div class="drawer-letters">
            <a href="#brands" class="drawer-letter">A</a><a href="#brands" class="drawer-letter">B</a><a href="#brands" class="drawer-letter">C</a><a href="#brands" class="drawer-letter">D</a><a href="#brands" class="drawer-letter">E</a><a href="#brands" class="drawer-letter">F</a><a href="#brands" class="drawer-letter">G</a><a href="#brands" class="drawer-letter">H</a><a href="#brands" class="drawer-letter">I</a><a href="#brands" class="drawer-letter">J</a><a href="#brands" class="drawer-letter">K</a><a href="#brands" class="drawer-letter">L</a><a href="#brands" class="drawer-letter">M</a><a href="#brands" class="drawer-letter">N</a><a href="#brands" class="drawer-letter">O</a><a href="#brands" class="drawer-letter">P</a><a href="#brands" class="drawer-letter">Q</a><a href="#brands" class="drawer-letter">R</a><a href="#brands" class="drawer-letter">S</a><a href="#brands" class="drawer-letter">T</a><a href="#brands" class="drawer-letter">U</a><a href="#brands" class="drawer-letter">V</a><a href="#brands" class="drawer-letter">W</a><a href="#brands" class="drawer-letter">X</a><a href="#brands" class="drawer-letter">Y</a><a href="#brands" class="drawer-letter">Z</a>
          </div>
          <div class="drawer-group"><span class="drawer-group-title">FEATURED HOUSES</span><a href="#dastan">House of Dastan</a><a href="PDP.dc.html">Mes Bisous</a><a href="#crivelli">Maison Crivelli</a><a href="Category.dc.html">Parfums de Marly</a></div>
          <div class="drawer-group"><span class="drawer-group-title">DESIGNER</span><a href="Category.dc.html">Dior</a><a href="Category.dc.html">Creed</a><a href="Category.dc.html">Tom Ford</a><a href="Category.dc.html">Chanel</a></div>
          <div class="drawer-group"><span class="drawer-group-title">NICHE</span><a href="Category.dc.html">Xerjoff</a><a href="Category.dc.html">Nishane</a><a href="Category.dc.html">Initio</a><a href="Category.dc.html">Amouage</a></div>
        </div>
      </div>
      <div class="drawer-item">
        <div class="drawer-item-row"><a href="#edit">THE EDIT</a></div>
      </div>
      <div class="drawer-item">
        <div class="drawer-item-row"><a href="Offers.dc.html">OFFERS</a></div>
      </div>
      <div class="drawer-item">
        <div class="drawer-item-row"><a href="#club">AROMA CLUB</a></div>
      </div>
    </nav>
    <div class="drawer-account"><a href="#account">Sign In / Account</a><a href="#orders">Track Order</a><a href="#wishlist">Wishlist</a></div>
    <div class="drawer-foot">40,000+ Google Reviews · 4.8★<br>Free Samples on $180+ · 100% Authentic</div>
  </div>
</div>

<div class="nav-spacer"></div>

<div class="trust-a">
  <div class="trust-a-inner tbscroll">
    <span class="trust-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><circle cx="12" cy="12" r="8.5"></circle><path d="M16.2 9.2A5 5 0 1017.2 14h-5.2"></path></svg>
      <span class="trust-stars">★★★★★</span>
      <span class="trust-anchor-label">40,000+ GOOGLE REVIEWS · 4.8</span>
    </span>
    <span class="trust-div"></span>
    <span class="trust-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M4.5 9.5h15v10.5h-15z"></path><path d="M4.5 13.5h15M12 9.5V20"></path><path d="M12 9.5C10.8 6.6 9.2 5.6 8 6.3S7.4 9.5 9.6 9.5zM12 9.5c1.2-2.9 2.8-3.9 4-3.2s.6 3.2-1.6 3.2z"></path></svg>
      <span class="trust-item-label">FREE SAMPLES ON $180+</span>
    </span>
    <span class="trust-div"></span>
    <span class="trust-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M4.5 9h11a5 5 0 010 10H8.5"></path><path d="M8.5 5L4.5 9l4 4"></path></svg>
      <span class="trust-item-label">FREE RETURNS</span>
    </span>
    <span class="trust-div"></span>
    <span class="trust-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M12 3.2l7 3v5c0 4.9-3 7.8-7 9.6-4-1.8-7-4.7-7-9.6v-5l7-3z"></path><path d="M9.2 12.2l2 2 3.6-3.8"></path></svg>
      <span class="trust-item-label">100% AUTHENTIC</span>
    </span>
  </div>
</div>

<div class="trust-b">
  <div class="trust-b-inner">
    <span class="trust-b-anchor">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><circle cx="12" cy="12" r="8.5"></circle><path d="M16.2 9.2A5 5 0 1017.2 14h-5.2"></path></svg>
      <span class="trust-b-stars">★★★★★</span>
      <span class="trust-b-anchor-label">40,000+ REVIEWS · 4.8</span>
    </span>
    <span class="trust-b-secondary">
      <span class="trust-b-chip">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M4.5 9.5h15v10.5h-15z"></path><path d="M4.5 13.5h15M12 9.5V20"></path><path d="M12 9.5C10.8 6.6 9.2 5.6 8 6.3S7.4 9.5 9.6 9.5zM12 9.5c1.2-2.9 2.8-3.9 4-3.2s.6 3.2-1.6 3.2z"></path></svg>
        <span class="trust-b-chip-label">FREE SAMPLES $180+</span>
      </span>
      <span class="trust-b-div"></span>
      <span class="trust-b-chip">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M4.5 9h11a5 5 0 010 10H8.5"></path><path d="M8.5 5L4.5 9l4 4"></path></svg>
        <span class="trust-b-chip-label">FREE RETURNS</span>
      </span>
      <span class="trust-b-div"></span>
      <span class="trust-b-chip">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4"><path d="M12 3.2l7 3v5c0 4.9-3 7.8-7 9.6-4-1.8-7-4.7-7-9.6v-5l7-3z"></path><path d="M9.2 12.2l2 2 3.6-3.8"></path></svg>
        <span class="trust-b-chip-label">AUTHENTIC</span>
      </span>
    </span>
  </div>
  <div class="trust-mobile-carousel" role="region" aria-label="Store highlights" aria-live="off">
    <span class="trust-mobile-slide is-active">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4" aria-hidden="true"><circle cx="12" cy="12" r="8.5"></circle><path d="M16.2 9.2A5 5 0 1017.2 14h-5.2"></path></svg>
      <span class="trust-b-stars">★★★★★</span>
      <span class="trust-b-anchor-label">40,000+ REVIEWS · 4.8</span>
    </span>
    <span class="trust-mobile-slide">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4" aria-hidden="true"><path d="M4.5 9.5h15v10.5h-15z"></path><path d="M4.5 13.5h15M12 9.5V20"></path><path d="M12 9.5C10.8 6.6 9.2 5.6 8 6.3S7.4 9.5 9.6 9.5zM12 9.5c1.2-2.9 2.8-3.9 4-3.2s.6 3.2-1.6 3.2z"></path></svg>
      <span class="trust-b-chip-label">FREE SAMPLES $180+</span>
    </span>
    <span class="trust-mobile-slide">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4" aria-hidden="true"><path d="M4.5 9h11a5 5 0 010 10H8.5"></path><path d="M8.5 5L4.5 9l4 4"></path></svg>
      <span class="trust-b-chip-label">FREE RETURNS</span>
    </span>
    <span class="trust-mobile-slide">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.4" aria-hidden="true"><path d="M12 3.2l7 3v5c0 4.9-3 7.8-7 9.6-4-1.8-7-4.7-7-9.6v-5l7-3z"></path><path d="M9.2 12.2l2 2 3.6-3.8"></path></svg>
      <span class="trust-b-chip-label">AUTHENTIC</span>
    </span>
  </div>
</div>
</header>
<script>
  document.addEventListener('DOMContentLoaded', () => {
  const accountButtons = document.querySelectorAll('[data-account-trigger]');
  const sidebarDrawer = document.getElementById('sidebar-drawer');
  const sidebarOverlay = document.getElementById('sidebar-overlay');
  const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
  let accountTrigger;
  let recaptchaLoaded = false;

  function loadRecaptcha() {
    if (recaptchaLoaded) return;
    recaptchaLoaded = true;
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  }

  // Function to open the sidebar
  function openSidebar(e) {
    if (e) e.preventDefault();
    loadRecaptcha();
    sidebarDrawer.classList.add('is-active');
    sidebarOverlay.classList.add('is-active');
    document.body.classList.add('sidebar-open');
    sidebarDrawer.setAttribute('aria-hidden', 'false');
    sidebarDrawer.inert = false;
    sidebarOverlay.setAttribute('aria-hidden', 'false');
    accountTrigger = e && e.currentTarget;
    accountButtons.forEach(btn => btn.setAttribute('aria-expanded', 'true'));
    sidebarCloseBtn.focus();
  }

  // Function to close the sidebar
  function closeSidebar() {
    sidebarDrawer.classList.remove('is-active');
    sidebarOverlay.classList.remove('is-active');
    document.body.classList.remove('sidebar-open');
    sidebarDrawer.setAttribute('aria-hidden', 'true');
    sidebarDrawer.inert = true;
    sidebarOverlay.setAttribute('aria-hidden', 'true');
    accountButtons.forEach(btn => btn.setAttribute('aria-expanded', 'false'));
    if (accountTrigger) accountTrigger.focus();
  }

  // Event Listeners
  accountButtons.forEach((accountButton) => accountButton.addEventListener('click', openSidebar));
  sidebarCloseBtn.addEventListener('click', closeSidebar);
  sidebarOverlay.addEventListener('click', closeSidebar);

  // Focus trap for sidebar drawer
  sidebarDrawer.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
      const focusables = sidebarDrawer.querySelectorAll('button:not([disabled]), input:not([disabled]):not([type="hidden"]), a[href], select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
      if (!focusables.length) return;
      const first = focusables[0];
      const last = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  // Close when pressing the ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebarDrawer.classList.contains('is-active')) {
      closeSidebar();
    }
  });

  const mobileTrustCarousel = document.querySelector('.trust-mobile-carousel');
  const mobileTrustSlides = mobileTrustCarousel ? mobileTrustCarousel.querySelectorAll('.trust-mobile-slide') : [];
  const mobileQuery = window.matchMedia('(max-width: 767px)');
  const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
  let trustTimer;
  let trustIndex = 0;

  function stopTrustCarousel() {
    window.clearTimeout(trustTimer);
    mobileTrustSlides.forEach((slide, index) => {
      slide.classList.toggle('is-active', index === 0);
      slide.classList.remove('is-fading');
    });
    trustIndex = 0;
  }

  function runTrustCarousel() {
    if (!mobileTrustSlides.length || !mobileQuery.matches || reducedMotionQuery.matches || document.hidden) return;
    trustTimer = window.setTimeout(() => {
      const current = mobileTrustSlides[trustIndex];
      current.classList.add('is-fading');
      trustTimer = window.setTimeout(() => {
        current.classList.remove('is-active', 'is-fading');
        trustIndex = (trustIndex + 1) % mobileTrustSlides.length;
        mobileTrustSlides[trustIndex].classList.add('is-active');
        runTrustCarousel();
      }, 600);
    }, 2400);
  }

  function updateTrustCarousel() {
    window.clearTimeout(trustTimer);
    stopTrustCarousel();
    runTrustCarousel();
  }

  if (mobileTrustSlides.length) {
    mobileQuery.addEventListener('change', updateTrustCarousel);
    reducedMotionQuery.addEventListener('change', updateTrustCarousel);
    document.addEventListener('visibilitychange', updateTrustCarousel);
    updateTrustCarousel();
  }

  const authStage = document.querySelector('[data-auth-stage]');
  const authPanels = authStage ? authStage.querySelectorAll('[data-auth-panel]') : [];
  const authTitle = document.getElementById('auth-modal-title');
  const authTitles = { signin: 'Sign In', forgot: 'Forgot Password', signup: 'Create an Account' };
  let authState = 'signin';
  let authFocusTimer;

  function setAuthState(nextState) {
    const nextPanel = authStage && authStage.querySelector('[data-auth-panel="' + nextState + '"]');
    const currentPanel = authStage && authStage.querySelector('[data-auth-panel="' + authState + '"]');
    if (!nextPanel || !currentPanel || nextState === authState) return;

    window.clearTimeout(authFocusTimer);
    const isReturningToSignIn = nextState === 'signin';
    currentPanel.classList.remove('is-before', 'is-after');
    currentPanel.classList.add(isReturningToSignIn ? 'is-after' : 'is-before');
    currentPanel.setAttribute('aria-hidden', 'true');
    currentPanel.inert = true;
    nextPanel.classList.remove('is-before', 'is-after');
    if (isReturningToSignIn) {
      nextPanel.classList.add('is-before');
      void nextPanel.offsetWidth;
    }
    nextPanel.classList.remove('is-before');
    nextPanel.classList.add('is-active');
    nextPanel.setAttribute('aria-hidden', 'false');
    nextPanel.inert = false;
    authTitle.textContent = authTitles[nextState];
    authState = nextState;

    authFocusTimer = window.setTimeout(() => {
      currentPanel.classList.remove('is-active', 'is-before', 'is-after');
      const focusTarget = nextPanel.querySelector('input, button, [tabindex="-1"]');
      if (focusTarget) focusTarget.focus();
    }, reducedMotionQuery.matches ? 0 : 460);
  }

  document.querySelectorAll('[data-auth-target]').forEach((trigger) => {
    trigger.addEventListener('click', () => setAuthState(trigger.getAttribute('data-auth-target')));
  });

});
</script>

<script>
  /* ── Nav ── */
  var nav = document.querySelector('.site-nav');
  var spacer = document.querySelector('.nav-spacer');
  var mega = document.querySelector('.mega');
  var navLinks = document.querySelectorAll('.nav-link[data-menu]');
  var fullH = 0;
  var pinned = false;
  var menu = null;

  function measureNav() {
    if (!nav || pinned) return;
    var h = Math.round(nav.getBoundingClientRect().height);
    if (h && h !== fullH) {
      fullH = h;
      if (spacer) spacer.style.height = h + 'px';
    }
  }

  function onScroll() {
    var p = window.scrollY > 70;
    if (p !== pinned) {
      pinned = p;
      nav.classList.toggle('is-condensed', pinned);
    }
  }

  function closeMenu() {
    if (!menu) return;
    menu = null;
    mega.classList.remove('is-open');
    navLinks.forEach(function (el) { el.classList.remove('is-active'); });
  }

  function openMenu(key) {
    if (!key) {
      closeMenu();
      return;
    }
    menu = key;
    mega.classList.add('is-open');
    mega.setAttribute('data-menu', key);
    navLinks.forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-menu') === key);
    });
  }

  if (nav) {
    nav.addEventListener('mouseleave', closeMenu);
    document.querySelectorAll('.nav-link').forEach(function (el) {
      var key = el.getAttribute('data-menu');
      el.addEventListener('mouseenter', function () { openMenu(key); });
      el.addEventListener('focus', function () { openMenu(key); });
    });
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () { measureNav(); });
    onScroll();
    measureNav();
    if (typeof ResizeObserver !== 'undefined') {
      var ro = new ResizeObserver(measureNav);
      ro.observe(nav);
    }
  }

  /* ── Drawer ── */
  var burger = document.querySelector('.nav-burger');
  var drawerClose = document.querySelectorAll('[data-drawer-close]');
  var drawerToggles = document.querySelectorAll('.drawer-toggle');
  var drawer = document.querySelector('.drawer');
  var drawerOverlay = document.querySelector('.drawer-overlay');
  var drawerTrigger;

  function setDrawer(open) {
    nav.classList.toggle('is-drawer-open', open);
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    drawer.setAttribute('aria-hidden', String(!open));
    drawer.inert = !open;
    drawerOverlay.setAttribute('aria-hidden', String(!open));
    if (open) {
      drawerTrigger = document.activeElement;
      var firstDrawerControl = drawer.querySelector('a, button');
      if (firstDrawerControl) firstDrawerControl.focus();
    } else if (drawerTrigger) {
      drawerTrigger.focus();
    }
    if (!open) {
      document.querySelectorAll('.drawer-item.is-open').forEach(function (el) {
        el.classList.remove('is-open');
        var btn = el.querySelector('.drawer-toggle');
        if (btn) {
        btn.querySelector('.drawer-toggle-icon').textContent = '+';
          var label = btn.getAttribute('data-label');
          btn.setAttribute('aria-label', 'Expand ' + label);
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  }

  if (burger) {
    burger.addEventListener('click', function () {
      setDrawer(!nav.classList.contains('is-drawer-open'));
    });
  }
  drawerClose.forEach(function (el) {
    el.addEventListener('click', function () { setDrawer(false); });
  });
  function toggleDrawerItem(btn) {
    var item = btn.closest('.drawer-item');
    var wasOpen = item.classList.contains('is-open');
    document.querySelectorAll('.drawer-item.is-open').forEach(function (el) {
      el.classList.remove('is-open');
      var b = el.querySelector('.drawer-toggle');
      if (b) {
        b.querySelector('.drawer-toggle-icon').textContent = '+';
        b.setAttribute('aria-label', 'Expand ' + b.getAttribute('data-label'));
        b.setAttribute('aria-expanded', 'false');
      }
    });
    if (!wasOpen) {
      item.classList.add('is-open');
      btn.querySelector('.drawer-toggle-icon').textContent = '−';
      btn.setAttribute('aria-label', 'Collapse ' + btn.getAttribute('data-label'));
    }
    btn.setAttribute('aria-expanded', String(!wasOpen));
  }

  drawerToggles.forEach(function (btn) {
    btn.addEventListener('click', function () {
      toggleDrawerItem(btn);
    });
  });

  if (drawer) {
    drawer.addEventListener('keydown', function(e) {
      if (e.key === 'Tab') {
        var focusables = drawer.querySelectorAll('a[href], button:not([disabled]), input:not([disabled])');
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    });
  }

  window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && nav && nav.classList.contains('is-drawer-open')) {
      setDrawer(false);
    }
  });
</script>
