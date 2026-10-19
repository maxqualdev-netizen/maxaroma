@extends('layouts.app')
@section('content')
<div class="checkout-page" id="checkout-app">
  @include('checkout-new.checkout-header')
  <main class="checkout-main" role="main" id="main-content">

    <!-- ── LEFT COLUMN ──────────────────────────────────────── -->
    <div class="checkout-form-col">

      <!-- ══ 1. EXPRESS CHECKOUT ══════════════════════════════ -->
      <section class="express-section" aria-labelledby="express-heading">
        <div class="express-header">
          <p class="express-header-title" id="express-heading">Express checkout</p>
        </div>
        <div class="express-buttons" role="group" aria-label="Express payment options">
          <!-- 1. Afterpay -->
          <button class="express-btn express-btn-afterpay" type="button" aria-label="Pay with Afterpay">
            <img src="{{ url('images/checkout-new/afterpay.svg') }}" alt="">
          </button>
          <!-- 2. Amazon Pay -->
          <button class="express-btn express-btn-amazon" type="button" aria-label="Pay with Amazon Pay">
            <img src="{{ url('images/checkout-new/amazon-pay.svg') }}" alt="">
          </button>
          <!-- 3. Link (no brand SVG supplied — accessible wordmark) -->
          <button class="express-btn express-btn-link" type="button" aria-label="Pay with Link">
            Link
          </button>
          <!-- 4. PayPal -->
          <button class="express-btn express-btn-paypal" type="button" aria-label="Pay with PayPal">
            <img src="{{ url('images/checkout-new/paypal.svg') }}" alt="">
          </button>
          <!-- 5. Apple Pay -->
          <button class="express-btn express-btn-apple" type="button" aria-label="Pay with Apple Pay">
            <img src="{{ url('images/checkout-new/apple-pay.svg') }}" alt="">
          </button>
          <!-- 6. Google Pay -->
          <button class="express-btn express-btn-google" type="button" aria-label="Pay with Google Pay">
            <img src="{{ url('images/checkout-new/google-pay.svg') }}" alt="">
          </button>
        </div>

        <div class="divider-text" style="margin-top: var(--space-5); font-size: var(--font-size-xs);">
          <span>or continue with email</span>
        </div>
      </section>

      <!-- ══ 2. CONTACT INFORMATION ════════════════════════════ -->
      <section class="checkout-section" aria-labelledby="contact-heading" id="section-contact">
        <div class="checkout-section-head">
          <div class="checkout-section-head-left">
            <h2 class="step-title" id="contact-heading">Contact</h2>
          </div>
          <button class="section-edit-btn" aria-label="Edit contact information" onclick="editSection('contact')">Edit</button>
        </div>

        <!-- Completed summary state (shown after filling) -->
        <div class="section-summary" id="contact-summary" style="display:none;" aria-live="polite">
          <span id="contact-summary-email">jane.smith@email.com</span>
          <span id="contact-summary-phone">+1 (555) 012-3456</span>
        </div>

        <!-- Form body -->
        <div class="checkout-section-body" id="contact-form">

          <!-- Guest / Sign In toggle -->
          <div class="auth-options">
            <div class="auth-toggle" role="tablist" aria-label="Checkout as guest or sign in">
              <button class="auth-tab active" role="tab" aria-selected="true" id="tab-guest" aria-controls="panel-guest" onclick="switchAuthTab('guest')">Continue as Guest</button>
              <button class="auth-tab" role="tab" aria-selected="false" id="tab-signin" aria-controls="panel-signin" onclick="switchAuthTab('signin')">Sign In</button>
            </div>
          </div>

          <!-- Guest Panel -->
          <div role="tabpanel" id="panel-guest" aria-labelledby="tab-guest">
            <div style="display:flex; flex-direction:column; gap: var(--space-4);">
              <div class="fl-group">
                <input class="fl-input" type="email" id="email" name="email" placeholder="Email address" autocomplete="email" inputmode="email" required="" aria-required="true" aria-describedby="email-error">
                <label class="fl-label" for="email">Email address</label>
                <svg class="fl-check-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <span class="fl-error-msg" id="email-error" role="alert">
                  <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><circle cx="6" cy="6" r="5.5" stroke="currentColor"></circle><path d="M6 3.5V6.5" stroke="currentColor" stroke-linecap="round"></path><circle cx="6" cy="8.5" r="0.5" fill="currentColor"></circle></svg>
                  Please enter a valid email address
                </span>
              </div>

              <div class="fl-group">
                <div class="relative">
                  <input class="fl-input" type="tel" id="phone" name="phone" placeholder="Phone number" autocomplete="tel" inputmode="tel" aria-describedby="phone-hint">
                  <label class="fl-label" for="phone">Phone number (optional)</label>
                </div>
                <span class="fl-error-msg" id="phone-hint" style="display:flex; color: var(--color-text-tertiary);">For delivery updates via text</span>
              </div>

              <label class="field-check" style="margin-top: -8px;">
                <input type="checkbox" id="marketing" name="marketing" checked="">
                <div style="display:flex; flex-direction:column; gap:2px;">
                  <span style="font-size: var(--font-size-sm); font-weight: var(--font-weight-medium);">Stay in the loop</span>
                  <span style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">Receive exclusive deals and new arrivals from MaxAroma</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Sign In Panel (hidden by default) -->
          <div role="tabpanel" id="panel-signin" aria-labelledby="tab-signin" hidden="">
            <div style="display:flex; flex-direction:column; gap: var(--space-4);">
              <div class="fl-group">
                <input class="fl-input" type="email" id="signin-email" name="signin-email" placeholder="Email address" autocomplete="email" inputmode="email">
                <label class="fl-label" for="signin-email">Email address</label>
              </div>
              <div class="fl-group">
                <input class="fl-input" type="password" id="signin-password" name="signin-password" placeholder="Password" autocomplete="current-password">
                <label class="fl-label" for="signin-password">Password</label>
              </div>
              <div style="display:flex; justify-content:flex-end;">
                <button type="button" style="font-size:var(--font-size-sm); color:var(--color-text-secondary); text-decoration:underline; background:none; border:none; cursor:pointer;">Forgot password?</button>
              </div>
              <button type="button" class="btn btn-primary btn-full">Sign In</button>
            </div>
          </div>

        </div>
      </section>

      <!-- ══ 3. SHIPPING ADDRESS ══════════════════════════════ -->
      <section class="checkout-section" aria-labelledby="shipping-heading" id="section-shipping">
        <div class="checkout-section-head">
          <div class="checkout-section-head-left">
            <h2 class="step-title" id="shipping-heading">Shipping address</h2>
          </div>
          <button class="section-edit-btn" aria-label="Edit shipping address" onclick="editSection('shipping')">Edit</button>
        </div>

        <!-- Completed summary -->
        <div class="section-summary" id="shipping-summary" style="display:none;" aria-live="polite">
          <span>Jane Smith</span>
          <span>123 Main Street, Apt 4B</span>
          <span>New York, NY 10001</span>
        </div>

        <div class="checkout-section-body" id="shipping-form">
          <!-- Country -->
          <div style="display:flex; flex-direction:column; gap: var(--space-3);">
            <div class="fl-group">
              <select class="fl-input" id="country" name="country" autocomplete="country" aria-label="Country">
                <option value="" disabled="" selected=""></option>
                <option value="US" selected="">United States</option>
                <option value="CA">Canada</option>
                <option value="GB">United Kingdom</option>
                <option value="AU">Australia</option>
              </select>
              <label class="fl-label" for="country">Country / Region</label>
            </div>

            <div class="form-grid-2">
              <div class="fl-group">
                <input class="fl-input" type="text" id="first-name" name="first-name" placeholder="First name" autocomplete="given-name" aria-required="true">
                <label class="fl-label" for="first-name">First name</label>
              </div>
              <div class="fl-group">
                <input class="fl-input" type="text" id="last-name" name="last-name" placeholder="Last name" autocomplete="family-name" aria-required="true">
                <label class="fl-label" for="last-name">Last name</label>
              </div>
            </div>

            <div class="fl-group" style="position:relative;">
              <input class="fl-input" type="text" id="address1" name="address1" placeholder="Address" autocomplete="off" aria-required="true" role="combobox" aria-expanded="false" aria-autocomplete="list" aria-controls="address-suggestions" oninput="handleAddressInput(this)" onkeydown="handleAddressKeydown(event)">
              <label class="fl-label" for="address1">Address</label>
              <!-- Autocomplete icon -->
              <span style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color: var(--color-text-tertiary); pointer-events:none;" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"></circle><path d="M11 11L14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
              </span>
              <!-- DEMO address autocomplete — see ADDRESS_DEMO_DB / handleAddressInput
                   in <script>. No Google Places API key exists in this project yet;
                   swap the local filter for a real Places Autocomplete + Place
                   Details call once one is available — the UI/keyboard-nav/fill
                   logic here doesn't need to change, just the data source. -->
              <ul class="addr-suggestions" id="address-suggestions" role="listbox" aria-label="Address suggestions" hidden=""></ul>
            </div>

            <div class="fl-group">
              <input class="fl-input" type="text" id="address2" name="address2" placeholder="Apt, suite, etc. (optional)" autocomplete="address-line2">
              <label class="fl-label" for="address2">Apt, suite, etc. (optional)</label>
            </div>

            <div class="form-grid-3col">
              <div class="fl-group">
                <input class="fl-input" type="text" id="city" name="city" placeholder="City" autocomplete="address-level2" aria-required="true">
                <label class="fl-label" for="city">City</label>
              </div>
              <div class="fl-group">
                <select class="fl-input" id="state" name="state" autocomplete="address-level1" aria-label="State" aria-required="true">
                  <option value="" disabled="" selected=""></option>
                  <option value="NY" selected="">NY</option>
                  <option value="CA">CA</option>
                  <option value="TX">TX</option>
                  <option value="FL">FL</option>
                </select>
                <label class="fl-label" for="state">State</label>
              </div>
              <div class="fl-group">
                <input class="fl-input" type="text" id="zip" name="zip" placeholder="ZIP code" autocomplete="postal-code" inputmode="numeric" aria-required="true" maxlength="10">
                <label class="fl-label" for="zip">ZIP code</label>
              </div>
            </div>

            <!-- Save address option -->
            <label class="field-check">
              <input type="checkbox" id="save-address" name="save-address">
              <span style="font-size: var(--font-size-sm);">Save this address for faster checkout next time</span>
            </label>
          </div>
        </div>
      </section>

      <!-- ══ 4. SHIPPING METHOD ══════════════════════════════ -->
      <section class="checkout-section" aria-labelledby="delivery-heading" id="section-delivery">
        <div class="checkout-section-head">
          <div class="checkout-section-head-left">
            <h2 class="step-title" id="delivery-heading">Shipping method</h2>
          </div>
        </div>

        <div class="checkout-section-body">
          <!-- Order-by urgency line -->
          <p class="ship-urgency" role="status">
            Place your order in the next <strong id="ship-countdown">2 hours 12 minutes</strong> for delivery by the listed dates.
          </p>

          <fieldset style="border:none; padding:0; margin:0; display:flex; flex-direction:column; gap:var(--space-3);">
            <legend class="visually-hidden">Select shipping method</legend>

            <!-- 1. Standard Shipping -->
            <label class="shipping-option" for="shipping-standard">
              <input type="radio" id="shipping-standard" name="shipping" value="standard">
              <div class="shipping-option-info">
                <div class="shipping-option-name">Standard Shipping</div>
                <div class="shipping-option-eta">Arrives Tue, June 30 · 3–5 business days</div>
              </div>
              <div class="shipping-option-price free">FREE</div>
            </label>

            <!-- 2. Next Business Day Air -->
            <label class="shipping-option" for="shipping-nextday">
              <input type="radio" id="shipping-nextday" name="shipping" value="nextday">
              <div class="shipping-option-info">
                <div class="shipping-option-name">Next Business Day Air</div>
                <div class="shipping-option-eta">Arrives Mon, June 22 · Next business day</div>
              </div>
              <div class="shipping-option-price">$34.00</div>
            </label>

            <!-- 3. 2DAY AIR — FEDEX / UPS (recommended) -->
            <label class="shipping-option selected" for="shipping-2day">
              <input type="radio" id="shipping-2day" name="shipping" value="2day" checked="">
              <div class="shipping-option-info">
                <div class="shipping-option-name">2DAY AIR — FEDEX / UPS</div>
                <div class="shipping-option-eta">Arrives Tue, June 23 · 2 business days</div>
                <div class="shipping-option-confidence">
                  <svg width="13" height="13" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1.5L9 5.5H13L9.5 8L11 12L7 9.5L3 12L4.5 8L1 5.5H5L7 1.5Z" fill="currentColor"></path></svg>
                  98% on-time delivery rate
                </div>
              </div>
              <div class="shipping-option-price">$14.00</div>
            </label>

            <!-- 4. Expedited Mail -->
            <label class="shipping-option" for="shipping-expedited">
              <input type="radio" id="shipping-expedited" name="shipping" value="expedited">
              <div class="shipping-option-info">
                <div class="shipping-option-name">Expedited Mail</div>
                <div class="shipping-option-eta">Arrives Fri, June 26 · 4–6 business days</div>
              </div>
              <div class="shipping-option-price">$5.00</div>
            </label>

            <!-- 5. UPS-Ground -->
            <label class="shipping-option" for="shipping-ups">
              <input type="radio" id="shipping-ups" name="shipping" value="ups">
              <div class="shipping-option-info">
                <div class="shipping-option-name">UPS-Ground</div>
                <div class="shipping-option-eta">Arrives Mon, June 29 · 3–7 business days</div>
              </div>
              <div class="shipping-option-price">$5.50</div>
            </label>
          </fieldset>

          <!-- Order add-ons: Protect My Order (default ON) · Request Signature · Gift Wrap
               Compact redesign (client spec, 2026-08-06) — same 3 toggles/IDs/
               handlers/pricing, restyled into one bordered list of single-line
               rows instead of 3 tall cards. -->
          <div class="checkout-addons" style="margin-top: var(--space-5);">
            <div class="addon-list">

              <!-- Protect My Order -->
              <label class="addon-row active" aria-label="Add Protect My Order for $3.77">
                <span class="addon-icon" aria-hidden="true">
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M11 2L3 5.5V10.5C3 15.1 6.6 19.4 11 20.5C15.4 19.4 19 15.1 19 10.5V5.5L11 2Z" stroke="#000" stroke-width="1.5"></path><path d="M8 11L10 13L14 9" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
                <span class="addon-body">
                  <span class="addon-title">Protect My Order</span>
                  <span class="addon-desc">Covers damage, loss &amp; theft</span>
                </span>
                <span class="addon-price">+$3.77</span>
                <span class="toggle-switch">
                  <input type="checkbox" id="protection" name="protection" checked="" onchange="handleProtectionToggle(this)">
                  <span class="toggle-track"><span class="toggle-thumb"></span></span>
                </span>
              </label>

              <!-- Request Signature -->
              <label class="addon-row" aria-label="Add Request Signature for $2.50">
                <span class="addon-icon" aria-hidden="true">
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 16C5 13 7 12 8.5 13.5C9.5 14.5 8 16 6.5 15C5 14 6.5 9 8.5 7C10 5.5 11 6.5 10.5 8.5C10 11 8 14.5 10 16C11.5 17 13.5 15.5 15 13" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16 17H19" stroke="#000" stroke-width="1.5" stroke-linecap="round"></path></svg>
                </span>
                <span class="addon-body">
                  <span class="addon-title">Request Signature</span>
                  <span class="addon-desc">Signature required on delivery</span>
                </span>
                <span class="addon-price">+$2.50</span>
                <span class="toggle-switch">
                  <input type="checkbox" id="request-signature" name="request-signature" onchange="recalcTotals()">
                  <span class="toggle-track"><span class="toggle-thumb"></span></span>
                </span>
              </label>

              <!-- Add Gift Wrap -->
              <label class="addon-row" aria-label="Add Gift Wrap for $5.00">
                <span class="addon-icon" aria-hidden="true">
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 8H19V19H3V8Z" stroke="#000" stroke-width="1.5" stroke-linejoin="round"></path><path d="M2 8H20V11H2V8Z" stroke="#000" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8V19" stroke="#000" stroke-width="1.5"></path><path d="M11 8C11 8 9 8 7.5 6.5C6.5 5.5 7.5 3.5 9 4.5C10.5 5.5 11 8 11 8Z" stroke="#000" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8C11 8 13 8 14.5 6.5C15.5 5.5 14.5 3.5 13 4.5C11.5 5.5 11 8 11 8Z" stroke="#000" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                </span>
                <span class="addon-body">
                  <span class="addon-title">Add Gift Wrap</span>
                  <span class="addon-desc">Premium gift wrapping</span>
                </span>
                <span class="addon-price">+$5.00</span>
                <span class="toggle-switch">
                  <input type="checkbox" id="gift-wrap" name="gift-wrap" onchange="recalcTotals()">
                  <span class="toggle-track"><span class="toggle-thumb"></span></span>
                </span>
              </label>

            </div>
          </div>
        </div>
      </section>

      <!-- ══ COMPLETE YOUR ORDER (recommendations, above Payment) ══ -->
      <section class="upsell-section complete-your-order" aria-labelledby="upsell-heading" aria-label="Complete Your Order">
        <p class="upsell-title" id="upsell-heading">Complete Your Order</p>
        <div class="upsell-carousel" role="list" aria-label="Suggested add-on products, swipe to browse">

          <div class="upsell-card" role="listitem">
            <img class="upsell-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre" loading="lazy" width="120" height="92">
            <div class="upsell-info">
              <div class="upsell-brand">Chanel</div>
              <div class="upsell-name">Chance Eau Tendre EDP 1.7 oz</div>
              <div class="upsell-price-row">
                <span class="upsell-price">$115.00</span>
              </div>
            </div>
            <button class="upsell-add" aria-label="Add Chanel Chance Eau Tendre to cart" type="button"><span aria-hidden="true">＋</span> Add</button>
          </div>

          <div class="upsell-card" role="listitem">
            <img class="upsell-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre" loading="lazy" width="120" height="92">
            <div class="upsell-info">
              <div class="upsell-brand">Versace</div>
              <div class="upsell-name">Bright Crystal EDT 3.4 oz</div>
              <div class="upsell-price-row">
                <span class="upsell-price">$69.99</span>
                <span class="upsell-original">$85.00</span>
              </div>
            </div>
            <button class="upsell-add" aria-label="Add Versace Bright Crystal to cart" type="button"><span aria-hidden="true">＋</span> Add</button>
          </div>

          <div class="upsell-card" role="listitem">
            <img class="upsell-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre" loading="lazy" width="120" height="92">
            <div class="upsell-info">
              <div class="upsell-brand">MaxAroma</div>
              <div class="upsell-name">Travel Atomizer 10 ml</div>
              <div class="upsell-price-row">
                <span class="upsell-price">$14.99</span>
              </div>
            </div>
            <button class="upsell-add" aria-label="Add Travel Atomizer to cart" type="button"><span aria-hidden="true">＋</span> Add</button>
          </div>

          <div class="upsell-card" role="listitem">
            <img class="upsell-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre" loading="lazy" width="120" height="92">
            <div class="upsell-info">
              <div class="upsell-brand">MaxAroma</div>
              <div class="upsell-name">Signature Gift Box</div>
              <div class="upsell-price-row">
                <span class="upsell-price">$9.99</span>
              </div>
            </div>
            <button class="upsell-add" aria-label="Add Signature Gift Box to cart" type="button"><span aria-hidden="true">＋</span> Add</button>
          </div>

          <div class="upsell-card" role="listitem">
            <img class="upsell-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre" loading="lazy" width="120" height="92">
            <div class="upsell-info">
              <div class="upsell-brand">MaxAroma</div>
              <div class="upsell-name">Fragrance Sampler Set</div>
              <div class="upsell-price-row">
                <span class="upsell-price">$24.99</span>
                <span class="upsell-original">$32.00</span>
              </div>
            </div>
            <button class="upsell-add" aria-label="Add Fragrance Sampler Set to cart" type="button"><span aria-hidden="true">＋</span> Add</button>
          </div>

        </div>
      </section>

      <!-- ══ 5. PAYMENT ════════════════════════════════════════ -->
      <section class="checkout-section" aria-labelledby="payment-heading" id="section-payment">
        <div class="checkout-section-head">
          <div class="checkout-section-head-left">
            <h2 class="step-title" id="payment-heading">Payment</h2>
          </div>
          <!-- SSL badge inline -->
          <div style="display:flex; align-items:center; gap:4px; font-size:11px; color: var(--color-text-tertiary);">
            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" aria-hidden="true"><path d="M6 1L1 3.5V6.5C1 9.5 3.2 12.2 6 13C8.8 12.2 11 9.5 11 6.5V3.5L6 1Z" stroke="#16A34A" stroke-width="1.2"></path></svg>
            SSL Secure
          </div>
        </div>

        <div class="checkout-section-body">
          <!-- Payment method tabs -->
          <div class="payment-tabs" role="tablist" aria-label="Payment methods" style="margin-bottom: var(--space-5);">
            <button class="payment-tab active" role="tab" aria-selected="true" id="pay-tab-card" aria-controls="pay-panel-card" onclick="switchPayTab('card')">
              <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true"><rect x="1" y="1" width="14" height="10" rx="1.5" stroke="currentColor" stroke-width="1.3"></rect><path d="M1 5H15" stroke="currentColor" stroke-width="1.3"></path></svg>
              Card
            </button>
            <button class="payment-tab" role="tab" aria-selected="false" id="pay-tab-paypal" aria-controls="pay-panel-paypal" onclick="switchPayTab('paypal')">PayPal</button>
            <button class="payment-tab" role="tab" aria-selected="false" id="pay-tab-affirm" aria-controls="pay-panel-affirm" onclick="switchPayTab('affirm')">Affirm</button>
          </div>

          <!-- Card Panel -->
          <div role="tabpanel" id="pay-panel-card" aria-labelledby="pay-tab-card">
            <div style="display:flex; flex-direction:column; gap: var(--space-3);">
              <div class="fl-group">
                <input class="fl-input" type="text" id="card-number" name="card-number" placeholder="Card number" autocomplete="cc-number" inputmode="numeric" maxlength="19" aria-required="true">
                <label class="fl-label" for="card-number">Card number</label>
              </div>

              <div class="fl-group">
                <input class="fl-input" type="text" id="card-name" name="card-name" placeholder="Name on card" autocomplete="cc-name" aria-required="true">
                <label class="fl-label" for="card-name">Name on card</label>
              </div>

              <div class="form-grid-2">
                <div class="fl-group">
                  <input class="fl-input" type="text" id="card-expiry" name="card-expiry" placeholder="MM / YY" autocomplete="cc-exp" inputmode="numeric" maxlength="7" aria-required="true">
                  <label class="fl-label" for="card-expiry">Expiry date</label>
                </div>
                <div class="fl-group" style="position:relative;">
                  <input class="fl-input" type="text" id="card-cvv" name="card-cvv" placeholder="CVV" autocomplete="cc-csc" inputmode="numeric" maxlength="4" aria-required="true" aria-describedby="cvv-hint">
                  <label class="fl-label" for="card-cvv">CVV</label>
                  <button type="button" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color: var(--color-text-tertiary);" aria-label="What is CVV?" id="cvv-hint">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"></circle><path d="M8 7V11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path><circle cx="8" cy="5" r="0.7" fill="currentColor"></circle></svg>
                  </button>
                </div>
              </div>

              <!-- Accepted cards row -->
              <div class="accepted-cards" aria-label="Accepted payment cards">
                <img class="card-logo" src="{{ url('images/checkout-new/visa.svg') }}" alt="Visa">
                <img class="card-logo" src="{{ url('images/checkout-new/mastercard.svg') }}" alt="Mastercard">
                <img class="card-logo" src="{{ url('images/checkout-new/american-express.svg') }}" alt="American Express">
                <img class="card-logo" src="{{ url('images/checkout-new/discover.svg') }}" alt="Discover">
                <img class="card-logo" src="{{ url('images/checkout-new/paypal.svg') }}" alt="PayPal">
              </div>
            </div>
          </div>

          <!-- PayPal Panel (hidden) -->
          <div role="tabpanel" id="pay-panel-paypal" aria-labelledby="pay-tab-paypal" hidden="">
            <div style="text-align:center; padding: var(--space-8) var(--space-4);">
              <p style="color: var(--color-text-secondary); font-size: var(--font-size-sm); margin-bottom: var(--space-4);">You'll be redirected to PayPal to complete your payment securely.</p>
              <button type="button" class="btn btn-full" style="background:#003087; color:white; border-color:#003087; height:52px; font-size:var(--font-size-base); font-weight:700;">Continue with PayPal</button>
            </div>
          </div>

          <!-- Affirm Panel (hidden) -->
          <div role="tabpanel" id="pay-panel-affirm" aria-labelledby="pay-tab-affirm" hidden="">
            <div style="padding: var(--space-4);">
              <div class="savings-banner" style="margin-bottom: var(--space-4);">
                <svg class="savings-banner-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 2L12.5 7H18L13.5 10.5L15.5 16L10 12.5L4.5 16L6.5 10.5L2 7H7.5L10 2Z" fill="currentColor"></path></svg>
                <span>Pay as low as <strong>$28/month</strong> with Affirm. No hidden fees.</span>
              </div>
              <button type="button" class="btn btn-full" style="background:#000; color:white; height:52px;">Continue with Affirm</button>
            </div>
          </div>

          <!-- Security bar -->
          <div class="security-bar" style="margin-top: var(--space-5);">
            <div class="security-bar-item">
              <svg class="security-bar-icon" viewBox="0 0 14 16" fill="none" aria-hidden="true"><path d="M7 1L1 3.5V7.5C1 11.1 3.6 14.4 7 15.5C10.4 14.4 13 11.1 13 7.5V3.5L7 1Z" stroke="currentColor" stroke-width="1.2"></path><path d="M4.5 8L6 10L9.5 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path></svg>
              SSL Encrypted
            </div>
            <div class="security-bar-item">
              <svg class="security-bar-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true"><rect x="1" y="5" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.2"></rect><path d="M4 5V4C4 2.3 5.3 1 7 1C8.7 1 10 2.3 10 4V5" stroke="currentColor" stroke-width="1.2"></path></svg>
              256-bit Encryption
            </div>
            <div class="security-bar-item">
              <svg class="security-bar-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1L13 4V8C13 11 10.5 13.5 7 14C3.5 13.5 1 11 1 8V4L7 1Z" stroke="currentColor" stroke-width="1.2"></path></svg>
              PCI Compliant
            </div>
          </div>
        </div>
      </section>

      <!-- ══ 7. ORDER REVIEW + PLACE ORDER ════════════════════ -->
      <section class="place-order-section" aria-labelledby="review-heading">
        <h2 class="step-title" id="review-heading" style="margin-bottom: var(--space-5);">Review your order</h2>

        <div class="review-table" aria-label="Order review" style="margin-bottom: var(--space-5); padding-bottom: var(--space-5); border-bottom: 1px solid var(--color-border-default);">
          <div class="review-row">
            <span class="review-row-label">Contact</span>
            <span class="review-row-value">jane.smith@email.com</span>
            <button class="review-row-edit" onclick="editSection('contact')" aria-label="Edit contact information">Edit</button>
          </div>
          <div class="review-row">
            <span class="review-row-label">Ship to</span>
            <span class="review-row-value">123 Main St, Apt 4B, New York NY 10001</span>
            <button class="review-row-edit" onclick="editSection('shipping')" aria-label="Edit shipping address">Edit</button>
          </div>
          <div class="review-row">
            <span class="review-row-label">Method</span>
            <span class="review-row-value">Standard Shipping · Free · Arrives Jun 22–24</span>
            <button class="review-row-edit" onclick="editSection('delivery')" aria-label="Edit shipping method">Edit</button>
          </div>
          <div class="review-row">
            <span class="review-row-label">Payment</span>
            <span class="review-row-value">Visa ending in 4242</span>
            <button class="review-row-edit" onclick="editSection('payment')" aria-label="Edit payment method">Edit</button>
          </div>
        </div>

        <!-- Collapsible order summary (Shopify-style): the mobile sticky
             footer hides itself once this section is in view, so this
             keeps item thumbnails + the expandable breakdown reachable
             right where the customer actually places the order. -->
        <div class="review-summary-card" id="review-summary-card">
          <button type="button" class="review-summary-toggle" id="review-summary-toggle" aria-expanded="false" aria-controls="review-summary-panel" onclick="toggleReviewSummary()" aria-label="Show order summary">
            <div class="review-summary-thumb" aria-hidden="true">
              <img src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
              <span class="review-summary-qty" id="review-summary-qty">2</span>
            </div>
            <div class="review-summary-total">
              <span class="place-order-total-label">Total</span>
              <div class="review-summary-tax">Includes $14.17 tax</div>
            </div>
            <div class="review-summary-amount" id="place-order-total-amount" aria-label="Order total $171.74">$171.74</div>
            <svg class="review-summary-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </button>
          <div class="review-summary-panel" id="review-summary-panel">
            <div class="review-summary-clip">
              <div class="review-summary-inner">
                <div class="msum-item">
                  <div class="msum-item-thumb">
                    <img src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
                    <span class="msum-item-qty" aria-hidden="true">1</span>
                  </div>
                  <span class="msum-item-name">Coco Mademoiselle EDP</span>
                  <span class="msum-item-price">$112.99</span>
                </div>
                <div class="msum-item">
                  <div class="msum-item-thumb">
                    <img src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
                    <span class="msum-item-qty" aria-hidden="true">1</span>
                  </div>
                  <span class="msum-item-name">Sauvage EDT</span>
                  <span class="msum-item-price">$39.99</span>
                </div>
                <div class="msum-divider"></div>
                <div class="msum-row"><span class="msum-row-label">Discount</span><span class="msum-discount">−$47.03</span></div>
                <div class="msum-row" id="review-code-discount-row" hidden=""><span class="msum-row-label">Code <span id="review-code-name"></span></span><span class="msum-discount" id="review-code-discount-value">−$0.00</span></div>
                <div class="msum-row" id="review-protection-row"><span class="msum-row-label">Protect My Order</span><span>$3.77</span></div>
                <div class="msum-row" id="review-signature-row" hidden=""><span class="msum-row-label">Request Signature</span><span>$2.50</span></div>
                <div class="msum-row" id="review-giftwrap-row" hidden=""><span class="msum-row-label">Gift wrap</span><span>$5.00</span></div>
                <div class="msum-row"><span class="msum-row-label">Shipping</span><span class="msum-success">Free</span></div>
                <div class="msum-row"><span class="msum-row-label">Taxes</span><span>$14.17</span></div>
                <div class="msum-row msum-total"><span>Total</span><span id="review-summary-total-value">$171.74</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- CTA -->
        <button type="submit" class="btn btn-cta btn-full" aria-label="Place order for $167.97" id="place-order-btn" onclick="handlePlaceOrder()">
          <span class="btn-text" id="place-order-btn-text">Place Order · $171.74</span>
        </button>

        <p class="terms-note">
          By placing your order you agree to MaxAroma's
          <a href="/terms" target="_blank" rel="noopener">Terms of Service</a> and
          <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a>.
          Your payment is processed securely. We never store your card details.
        </p>
      </section>

    </div><!-- end .checkout-form-col -->

    <!-- ── RIGHT COLUMN — ORDER SUMMARY ───────────────────── -->
    <aside class="checkout-sidebar" aria-label="Order summary" id="order-summary-sidebar">

      <!-- Order Summary Card -->
      <div class="order-summary-card" id="order-summary-card">
        <button class="order-summary-toggle" aria-expanded="false" aria-controls="order-summary-body" onclick="toggleOrderSummary()" type="button">
          <div class="order-summary-toggle-left">
            <div class="summary-cart-icon-wrap" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 450.391 450.391"> <g> <g> <g> <path d="M143.673,350.322c-25.969,0-47.02,21.052-47.02,47.02c0,25.969,21.052,47.02,47.02,47.02 c25.969,0,47.02-21.052,47.02-47.02C190.694,371.374,169.642,350.322,143.673,350.322z M143.673,423.465 c-14.427,0-26.122-11.695-26.122-26.122c0-14.427,11.695-26.122,26.122-26.122c14.427,0,26.122,11.695,26.122,26.122 C169.796,411.77,158.1,423.465,143.673,423.465z"/> <path d="M342.204,350.322c-25.969,0-47.02,21.052-47.02,47.02c0,25.969,21.052,47.02,47.02,47.02s47.02-21.052,47.02-47.02 C389.224,371.374,368.173,350.322,342.204,350.322z M342.204,423.465c-14.427,0-26.122-11.695-26.122-26.122 c0-14.427,11.695-26.122,26.122-26.122s26.122,11.695,26.122,26.122C368.327,411.77,356.631,423.465,342.204,423.465z"/> <path d="M448.261,76.037c-2.176-2.377-5.153-3.865-8.359-4.18L99.788,67.155L90.384,38.42 C83.759,19.211,65.771,6.243,45.453,6.028H10.449C4.678,6.028,0,10.706,0,16.477s4.678,10.449,10.449,10.449h35.004 c11.361,0.251,21.365,7.546,25.078,18.286l66.351,200.098l-5.224,12.016c-5.827,15.026-4.077,31.938,4.702,45.453 c8.695,13.274,23.323,21.466,39.184,21.943h203.233c5.771,0,10.449-4.678,10.449-10.449c0-5.771-4.678-10.449-10.449-10.449 H175.543c-8.957-0.224-17.202-4.936-21.943-12.539c-4.688-7.51-5.651-16.762-2.612-25.078l4.18-9.404l219.951-22.988 c24.16-2.661,44.034-20.233,49.633-43.886l25.078-105.012C450.96,81.893,450.36,78.492,448.261,76.037z M404.376,185.228 c-3.392,15.226-16.319,26.457-31.869,27.69l-217.339,22.465L106.58,88.053l320.261,4.702L404.376,185.228z"/> </g> </g> </g></svg>
              <span class="summary-cart-count" aria-label="2 items">2</span>
            </div>
            <span class="order-summary-toggle-title">Order summary</span>
          </div>
          <div style="display:flex; align-items:center; gap: var(--space-2);">
            <span class="order-summary-toggle-amount" id="mobile-summary-amount" aria-label="Total $171.74">$171.74</span>
            <svg class="order-summary-toggle-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            </svg>
          </div>
        </button>

        <div class="order-summary-body" id="order-summary-body" role="region" aria-label="Order details">
          <div class="order-summary-clip">
          <div class="order-summary-inner">

            <!-- ══ DEMO ONLY — promo mode preview switcher ══
                 Lets the client preview all 3 promo states (flat threshold, GWP
                 site-wide, GWP restricted) live, without touching code. Remove
                 this block once PROMO_CONFIG is wired to the real backend. -->
            <div class="promo-demo-switcher compact" style="margin-bottom: var(--space-3);" aria-label="Promo mode preview, for review only">
              <span class="promo-demo-label">🔧 PREVIEW MODE (for review only):</span>
              <button type="button" class="promo-demo-btn active" data-preset="free_samples" aria-pressed="true" onclick="setPromoPreset('free_samples')">Free Samples</button>
              <button type="button" class="promo-demo-btn" data-preset="gwp_site_wide" aria-pressed="false" onclick="setPromoPreset('gwp_site_wide')">GWP · Site-wide</button>
              <button type="button" class="promo-demo-btn" data-preset="gwp_restricted" aria-pressed="false" onclick="setPromoPreset('gwp_restricted')">GWP · Restricted (Dior, Versace)</button>
            </div>

            <!-- ══ Purchase-threshold promo module (Free Samples OR Gift With Purchase) ══
                 One module, two mutually exclusive modes. The active mode, threshold,
                 eligibility scope and messaging are all driven by PROMO_CONFIG (backend). -->
            <div class="purchase-threshold-promo" style="margin-bottom: var(--space-4);">
              <div class="free-gift-card compact" id="promo-card" role="status" aria-live="polite">

                <!-- In-progress state -->
                <div class="free-gift-state free-gift-progress">
                  <div class="free-gift-head">
                    <div class="free-gift-icon" aria-hidden="true">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 8H19V19H3V8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M2 8H20V11H2V8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8V19" stroke="#16A34A" stroke-width="1.5"></path><path d="M11 8C11 8 9 8 7.5 6.5C6.5 5.5 7.5 3.5 9 4.5C10.5 5.5 11 8 11 8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8C11 8 13 8 14.5 6.5C15.5 5.5 14.5 3.5 13 4.5C11.5 5.5 11 8 11 8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                    </div>
                    <div class="free-gift-title" id="promo-heading"><span id="promo-title-text">Unlock your free samples</span></div>
                    <span class="promo-info-wrap">
                      <button type="button" class="promo-info-btn" id="promo-info-btn" hidden="" aria-label="Which products qualify for this offer?" aria-expanded="false" aria-controls="promo-tooltip" onclick="togglePromoTooltip(event)">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"></circle><path d="M8 7.2V11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path><circle cx="8" cy="5" r="0.9" fill="currentColor"></circle></svg>
                      </button>
                      <span class="promo-tooltip" id="promo-tooltip" role="tooltip"></span>
                    </span>
                  </div>
                  <div class="free-gift-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="98" id="promo-progressbar" aria-label="Progress toward your promotion">
                    <div class="free-gift-fill" id="promo-fill" style="width: 98.1%;"></div>
                  </div>
                  <p class="free-gift-remaining">
                    You're <strong id="promo-amount">$3.26 away</strong> <span id="promo-remaining-suffix">from free samples!</span>
                  </p>
                </div>

                <!-- Unlocked state -->
                <div class="free-gift-state free-gift-unlocked">
                  <div class="free-gift-unlocked-row">
                    <div class="free-gift-icon" aria-hidden="true">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 8H19V19H3V8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M2 8H20V11H2V8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8V19" stroke="#16A34A" stroke-width="1.5"></path><path d="M11 8C11 8 9 8 7.5 6.5C6.5 5.5 7.5 3.5 9 4.5C10.5 5.5 11 8 11 8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path><path d="M11 8C11 8 13 8 14.5 6.5C15.5 5.5 14.5 3.5 13 4.5C11.5 5.5 11 8 11 8Z" stroke="#16A34A" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                    </div>
                    <div>
                      <div class="free-gift-unlocked-title">🎉 Congratulations!</div>
                      <div class="free-gift-unlocked-desc" id="promo-success-desc">You've unlocked your free samples!</div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Savings banner — DISABLED for now, keep markup for future re-enable
            <div class="savings-banner">
              <svg class="savings-banner-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M10 2C5.6 2 2 5.6 2 10S5.6 18 10 18 18 14.4 18 10 14.4 2 10 2Z" stroke="currentColor" stroke-width="1.3"/>
                <path d="M7 10.5L9 12.5L13 8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
              </svg>
              <span>You're saving <strong>$47.03</strong> on this order!</span>
            </div>
            -->

            <!-- Products: first 2 always visible, remainder in scroll area -->
            <div class="order-items-list" aria-label="Items in cart">

              <!-- Item 1 -->
              <div class="order-item-row" data-brand="Chanel" data-category="Women's Fragrance">
                <div class="order-item-image">
                  <img class="order-item-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Coco Mademoiselle EDP">
                </div>
                <div class="order-item-info">
                  <div class="order-item-brand">Chanel</div>
                  <div class="order-item-name">Coco Mademoiselle EDP</div>
                  <div class="order-item-variant">3.4 oz / 100ml · Eau de Parfum</div>
                  <div class="order-item-sku">SKU: CH-COCO-100</div>
                  <div class="order-item-controls">
                    <div class="qty-stepper" role="group" aria-label="Quantity for Coco Mademoiselle EDP">
                      <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
                      <span class="qty-value" aria-live="polite">1</span>
                      <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
                    </div>
                    <button class="item-remove" type="button" aria-label="Remove Coco Mademoiselle EDP from cart" onclick="removeItem(this)">Remove</button>
                  </div>
                </div>
                <div class="order-item-price">
                  <div class="order-item-price-current">$112.99</div>
                  <div class="order-item-price-original">$145.00</div>
                </div>
              </div>

              <!-- Item 2 -->
              <div class="order-item-row" data-brand="Dior" data-category="Men's Fragrance">
                <div class="order-item-image">
                  <img class="order-item-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Dior Sauvage EDT">
                </div>
                <div class="order-item-info">
                  <div class="order-item-brand">Dior</div>
                  <div class="order-item-name">Sauvage EDT</div>
                  <div class="order-item-variant">3.4 oz / 100ml · Eau de Toilette</div>
                  <div class="order-item-sku">SKU: DI-SAUV-100</div>
                  <div class="order-item-controls">
                    <div class="qty-stepper" role="group" aria-label="Quantity for Sauvage EDT">
                      <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
                      <span class="qty-value" aria-live="polite">1</span>
                      <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
                    </div>
                    <button class="item-remove" type="button" aria-label="Remove Sauvage EDT from cart" onclick="removeItem(this)">Remove</button>
                  </div>
                </div>
                <div class="order-item-price">
                  <div class="order-item-price-current">$39.99</div>
                  <div class="order-item-price-original">$72.00</div>
                </div>
              </div>

              <!-- More items trigger (no nested scroll — opens drawer) -->
              <button type="button" class="view-all-items" onclick="openCartDrawer()" aria-haspopup="dialog" aria-controls="cart-drawer">
                <span class="view-all-stack" aria-hidden="true">
                  <span class="view-all-thumb" style="background:linear-gradient(135deg,#f5eef0 0%,#e8d5dc 100%);">
                    <img class="view-all-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
                  </span>
                  <img class="view-all-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
                </span>
                <span class="view-all-label"><strong id="more-items-count">+ 2 More Items</strong> in your order</span>
                <span class="view-all-cta">View All Items <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>
              </button>
            </div>

            <!-- Coupon Code / E-Gift Card / Shipping Rate Calculator
                 accordions — moved above Totals per client feedback
                 (2026-08-05), so they sit right under the item list
                 instead of below the Total row. -->
            <div class="summary-accordion-group" style="margin-top: var(--space-1);">
              <div class="summary-accordion">
                <button class="summary-accordion-head" onclick="toggleSummaryAccordion('coupon')" aria-expanded="false" aria-controls="coupon-panel" type="button" id="coupon-toggle">
                  <span class="summary-accordion-title">
                    <svg width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><circle cx="4" cy="4" r="1.5" stroke="currentColor" stroke-width="1.2"></circle><circle cx="10" cy="10" r="1.5" stroke="currentColor" stroke-width="1.2"></circle><path d="M2 12L12 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path></svg>
                    Have a Coupon Code?
                  </span>
                  <svg class="summary-accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
                </button>
                <div class="summary-accordion-body" id="coupon-panel" hidden="">
                  <div class="promo-field">
                    <input type="text" id="promo-code" name="promo-code" placeholder="Enter coupon code" aria-label="Coupon code" autocomplete="off" spellcheck="false">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="applyPromo()">Apply</button>
                  </div>
                  <div id="promo-result"></div>
                </div>
              </div>

              <!-- E-Gift Card accordion -->
              <div class="summary-accordion">
                <button class="summary-accordion-head" onclick="toggleSummaryAccordion('giftcard')" aria-expanded="false" aria-controls="giftcard-panel" type="button" id="giftcard-toggle">
                  <span class="summary-accordion-title">
                    <svg width="15" height="15" viewBox="0 0 16 14" fill="none" aria-hidden="true"><rect x="1" y="3" width="14" height="10" rx="1.5" stroke="currentColor" stroke-width="1.2"></rect><path d="M1 6H15" stroke="currentColor" stroke-width="1.2"></path><path d="M8 3V1.5M8 3C8 3 6.5 1 5 1.8C4 2.4 5 3 8 3ZM8 3C8 3 9.5 1 11 1.8C12 2.4 11 3 8 3Z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path></svg>
                    Have an E-Gift Card Code?
                  </span>
                  <svg class="summary-accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
                </button>
                <div class="summary-accordion-body" id="giftcard-panel" hidden="">
                  <div class="promo-field">
                    <input type="text" id="giftcard-code" name="giftcard-code" placeholder="Enter gift card code" aria-label="E-Gift card code" autocomplete="off" spellcheck="false">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="applyGiftCard()">Apply</button>
                  </div>
                  <div id="giftcard-result"></div>
                </div>
              </div>

              <!-- Shipping Rate Calculator accordion -->
              <div class="summary-accordion">
                <button class="summary-accordion-head" onclick="toggleSummaryAccordion('shipcalc')" aria-expanded="false" aria-controls="shipcalc-panel" type="button" id="shipcalc-toggle">
                  <span class="summary-accordion-title">
                    <svg width="16" height="14" viewBox="0 0 18 14" fill="none" aria-hidden="true"><path d="M1 3H11V11H1V3Z" stroke="currentColor" stroke-width="1.2"></path><path d="M11 6H14L17 9V11H11V6Z" stroke="currentColor" stroke-width="1.2"></path><circle cx="4" cy="11.5" r="1.3" stroke="currentColor" stroke-width="1.2"></circle><circle cx="13.5" cy="11.5" r="1.3" stroke="currentColor" stroke-width="1.2"></circle></svg>
                    Shipping Rate Calculator
                  </span>
                  <svg class="summary-accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path></svg>
                </button>
                <div class="summary-accordion-body" id="shipcalc-panel" hidden="">
                  <p class="shipcalc-hint">Estimate shipping to your destination.</p>
                  <div class="shipcalc-grid">
                    <div class="fl-group">
                      <select class="fl-input fl-input-sm" id="calc-country" aria-label="Country">
                        <option value="US" selected="">United States</option>
                        <option value="CA">Canada</option>
                        <option value="GB">United Kingdom</option>
                        <option value="AU">Australia</option>
                      </select>
                      <label class="fl-label" for="calc-country">Country</label>
                    </div>
                    <div class="fl-group">
                      <select class="fl-input fl-input-sm" id="calc-state" aria-label="State">
                        <option value="NY" selected="">New York</option>
                        <option value="CA">California</option>
                        <option value="TX">Texas</option>
                        <option value="FL">Florida</option>
                      </select>
                      <label class="fl-label" for="calc-state">State / Province</label>
                    </div>
                  </div>
                  <div class="fl-group" style="margin-top: var(--space-2);">
                    <input class="fl-input fl-input-sm" type="text" id="calc-zip" placeholder="ZIP / Postal code" inputmode="numeric" aria-label="ZIP or postal code">
                    <label class="fl-label" for="calc-zip">ZIP / Postal code</label>
                  </div>
                  <button type="button" class="btn btn-secondary btn-full" style="margin-top: var(--space-3);" onclick="recalcShipping()">Calculate Shipping</button>
                  <div id="shipcalc-result" class="shipcalc-result" aria-live="polite"></div>
                </div>
              </div>
            </div>

            <!-- Totals -->
            <div class="summary-totals" style="margin-top: var(--space-4);">
              <div class="summary-row">
                <span class="summary-row-label">Subtotal (2 items)</span>
                <span class="summary-row-value">$152.98</span>
              </div>
              <div class="summary-row savings">
                <span class="summary-row-label">Savings</span>
                <span class="summary-row-value">−$47.03</span>
              </div>
              <div class="summary-row">
                <span class="summary-row-label">Shipping</span>
                <span class="summary-row-value" style="color: var(--color-text-success);">Free</span>
              </div>
              <div class="summary-row">
                <span class="summary-row-label">Estimated tax</span>
                <span class="summary-row-value">$14.17</span>
              </div>
              <div class="summary-row" id="protection-row">
                <span class="summary-row-label">Protect My Order</span>
                <span class="summary-row-value">$3.77</span>
              </div>
              <div class="summary-row" id="signature-row" hidden="">
                <span class="summary-row-label">Request Signature</span>
                <span class="summary-row-value">$2.50</span>
              </div>
              <div class="summary-row" id="gift-wrap-row" hidden="">
                <span class="summary-row-label">Gift wrap</span>
                <span class="summary-row-value">$5.00</span>
              </div>
              <div class="summary-row total">
                <span class="summary-row-label">Total</span>
                <span class="summary-row-value" id="summary-total-value">$171.74</span>
              </div>
            </div>

            <!-- Policy strip -->
            <div class="policy-strip" aria-label="Shopping policies">
              <div class="policy-item">
                <svg class="policy-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1L13 4V8C13 11 10.5 13.5 7 14C3.5 13.5 1 11 1 8V4L7 1Z" stroke="currentColor" stroke-width="1.2"></path></svg>
                Secure checkout
              </div>
              <div class="policy-item">
                <svg class="policy-icon" viewBox="0 0 14 12" fill="none" aria-hidden="true"><path d="M1 9L3.5 11.5L13 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"></path></svg>
                100% Authentic
              </div>
              <div class="policy-item">
                <svg class="policy-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1C9.5 1 11.5 3 11.5 5.5C11.5 8 9.5 13 7 13C4.5 13 2.5 8 2.5 5.5C2.5 3 4.5 1 7 1Z" stroke="currentColor" stroke-width="1.2"></path></svg>
                Free Returns
              </div>
            </div>

          </div><!-- end .order-summary-inner -->
          </div><!-- end .order-summary-clip -->
        </div><!-- end .order-summary-body -->
      </div><!-- end .order-summary-card -->

      <!-- Aroma Club reward program -->
      <a class="aroma-club-card" href="https://www.maxaroma.com/reward-point-program.html" target="_blank" rel="noopener" aria-label="Join Aroma Club rewards program — opens in new tab">
        <div class="aroma-club-icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <path d="M11 2L13.5 7.8L19.8 8.4L15 12.6L16.5 19L11 15.6L5.5 19L7 12.6L2.2 8.4L8.5 7.8L11 2Z" fill="#000"></path>
          </svg>
        </div>
        <div class="aroma-club-info">
          <div class="aroma-club-title">Join Aroma Club</div>
          <div class="aroma-club-desc">Earn reward points on this and every future purchase.</div>
        </div>
        <span class="aroma-club-cta" aria-hidden="true">Learn More <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>
      </a>

      <!-- Reassurance sidebar block (desktop only; mobile copy lives near footer) -->
      <div class="sidebar-reassurance">
        <p class="sidebar-reassurance-title">Why shop with MaxAroma</p>
        <div class="reassurance-list">
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 11L7 7L10 10L15 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path><path d="M1 15H17" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path></svg>
            <span><strong style="font-weight:600;">Free Shipping</strong> on qualifying orders</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.3"></circle><path d="M5.5 9L8 11.5L12.5 6.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            <span><strong style="font-weight:600;">Free Returns</strong> — 30-day hassle-free policy</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1L16 4V9C16 13 13 16.5 9 17.5C5 16.5 2 13 2 9V4L9 1Z" stroke="currentColor" stroke-width="1.3"></path></svg>
            <span><strong style="font-weight:600;">Secure Checkout</strong> — SSL encrypted &amp; PCI compliant</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M1 3H11V11H1V3Z" stroke="currentColor" stroke-width="1.3"></path><path d="M11 6H14L17 9V11H11V6Z" stroke="currentColor" stroke-width="1.3"></path><circle cx="4" cy="11.5" r="1.4" stroke="currentColor" stroke-width="1.3"></circle><circle cx="13.5" cy="11.5" r="1.4" stroke="currentColor" stroke-width="1.3"></circle></svg>
            <span><strong style="font-weight:600;">Shipping Rate Calculator</strong> — estimate before you buy</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.5L11.5 6.5H17L12.5 10L14.5 15.5L9 12L3.5 15.5L5.5 10L1 6.5H6.5L9 1.5Z" stroke="currentColor" stroke-width="1.3"></path></svg>
            <span><strong style="font-weight:600;">100% Authentic Products</strong> — sourced from authorized distributors</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 2C5.1 2 2 5.1 2 9S5.1 16 9 16 16 12.9 16 9 12.9 2 9 2Z" stroke="currentColor" stroke-width="1.3"></path><path d="M9 6V9.5L11.5 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path></svg>
            <span><strong style="font-weight:600;">Customer Support Available</strong> — 7 days a week</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.5C6.5 1.5 4.5 3.5 4.5 6C4.5 9 9 16.5 9 16.5S13.5 9 13.5 6C13.5 3.5 11.5 1.5 9 1.5Z" stroke="currentColor" stroke-width="1.3"></path><circle cx="9" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"></circle></svg>
            <span><strong style="font-weight:600;">Free Samples Available</strong> with select orders</span>
          </div>
          <div class="reassurance-item">
            <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 2L11 7H16L12 10.5L13.5 15.5L9 12.5L4.5 15.5L6 10.5L2 7H7L9 2Z" stroke="currentColor" stroke-width="1.3"></path></svg>
            <span><strong style="font-weight:600;">Aroma Club Rewards</strong> — earn points on every order</span>
          </div>
        </div>
      </div>

    </aside>
  </main>

  <!-- Back to cart -->
  <div class="back-to-cart-wrap">
    <a href="https://www.maxaroma.com/checkout/cart" class="back-to-cart">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
        <path d="M9.5 3.5L5 8l4.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
      Back to Cart
    </a>
  </div>

  <!-- Why shop with MaxAroma — mobile only: placed near checkout completion,
       above the footer. On desktop this block lives in the sidebar instead. -->
  <div class="reassurance-bottom-section">
    <div class="sidebar-reassurance">
      <p class="sidebar-reassurance-title">Why shop with MaxAroma</p>
      <div class="reassurance-list">
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 11L7 7L10 10L15 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path><path d="M1 15H17" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path></svg>
          <span><strong style="font-weight:600;">Free Shipping</strong> on qualifying orders</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.3"></circle><path d="M5.5 9L8 11.5L12.5 6.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          <span><strong style="font-weight:600;">Free Returns</strong> — 30-day hassle-free policy</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1L16 4V9C16 13 13 16.5 9 17.5C5 16.5 2 13 2 9V4L9 1Z" stroke="currentColor" stroke-width="1.3"></path></svg>
          <span><strong style="font-weight:600;">Secure Checkout</strong> — SSL encrypted &amp; PCI compliant</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M1 3H11V11H1V3Z" stroke="currentColor" stroke-width="1.3"></path><path d="M11 6H14L17 9V11H11V6Z" stroke="currentColor" stroke-width="1.3"></path><circle cx="4" cy="11.5" r="1.4" stroke="currentColor" stroke-width="1.3"></circle><circle cx="13.5" cy="11.5" r="1.4" stroke="currentColor" stroke-width="1.3"></circle></svg>
          <span><strong style="font-weight:600;">Shipping Rate Calculator</strong> — estimate before you buy</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.5L11.5 6.5H17L12.5 10L14.5 15.5L9 12L3.5 15.5L5.5 10L1 6.5H6.5L9 1.5Z" stroke="currentColor" stroke-width="1.3"></path></svg>
          <span><strong style="font-weight:600;">100% Authentic Products</strong> — sourced from authorized distributors</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 2C5.1 2 2 5.1 2 9S5.1 16 9 16 16 12.9 16 9 12.9 2 9 2Z" stroke="currentColor" stroke-width="1.3"></path><path d="M9 6V9.5L11.5 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path></svg>
          <span><strong style="font-weight:600;">Customer Support Available</strong> — 7 days a week</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.5C6.5 1.5 4.5 3.5 4.5 6C4.5 9 9 16.5 9 16.5S13.5 9 13.5 6C13.5 3.5 11.5 1.5 9 1.5Z" stroke="currentColor" stroke-width="1.3"></path><circle cx="9" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"></circle></svg>
          <span><strong style="font-weight:600;">Free Samples Available</strong> with select orders</span>
        </div>
        <div class="reassurance-item">
          <svg class="reassurance-icon" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 2L11 7H16L12 10.5L13.5 15.5L9 12.5L4.5 15.5L6 10.5L2 7H7L9 2Z" stroke="currentColor" stroke-width="1.3"></path></svg>
          <span><strong style="font-weight:600;">Aroma Club Rewards</strong> — earn points on every order</span>
        </div>
      </div>
    </div>
  </div>

  @include("checkout-new.checkout-footer")

  <!-- ═══════════════════════════════════════════════════════════
       MOBILE STICKY FOOTER
  ═══════════════════════════════════════════════════════════ -->
  <div class="mobile-summary-overlay" id="mobile-summary-overlay" hidden="" onclick="toggleMobileSummary()"></div>
  <div class="mobile-sticky-footer" role="complementary" aria-label="Checkout action bar" id="mobile-footer" style="opacity: 1; pointer-events: auto;">

    <!-- Expandable order-summary breakdown (slides up) -->
    <div class="mobile-summary-panel" id="mobile-summary-panel">
      <div class="mobile-summary-clip">
        <div class="mobile-summary-content" role="region" aria-label="Order summary details">
          <div class="msum-label">Products</div>
          <div class="msum-item">
            <div class="msum-item-thumb">
              <img src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
              <span class="msum-item-qty" aria-hidden="true">1</span>
            </div>
            <span class="msum-item-name">Coco Mademoiselle EDP</span>
            <span class="msum-item-price">$112.99</span>
          </div>
          <div class="msum-item">
            <div class="msum-item-thumb">
              <img src="{{ url('/images/noimage-lrg.jpg') }}" alt="">
              <span class="msum-item-qty" aria-hidden="true">1</span>
            </div>
            <span class="msum-item-name">Sauvage EDT</span>
            <span class="msum-item-price">$39.99</span>
          </div>
          <div class="msum-divider"></div>

          <!-- Discount code -->
          <div class="msum-promo">
            <div class="msum-promo-field" id="msum-promo-field">
              <input type="text" id="msum-promo-input" class="msum-promo-input" placeholder="Discount code" aria-label="Discount code" autocomplete="off" spellcheck="false" enterkeyhint="done">
              <button type="button" class="btn btn-secondary btn-sm" onclick="applyMobileDiscount()">Apply</button>
            </div>
            <div class="msum-promo-applied" id="msum-promo-applied" hidden="">
              <span><strong id="msum-promo-code-label"></strong> applied</span>
              <button type="button" class="msum-promo-remove" onclick="removeMobileDiscount()" aria-label="Remove discount code">Remove</button>
            </div>
            <p class="msum-promo-msg" id="msum-promo-msg" role="status" aria-live="polite"></p>
          </div>

          <div class="msum-row"><span class="msum-row-label">Discount</span><span class="msum-discount">−$47.03</span></div>
          <div class="msum-row" id="msum-code-discount-row" hidden=""><span class="msum-row-label">Code <span id="msum-code-name"></span></span><span class="msum-discount" id="msum-code-discount-value">−$0.00</span></div>
          <div class="msum-row" id="msum-protection-row"><span class="msum-row-label">Protect My Order</span><span>$3.77</span></div>
          <div class="msum-row" id="msum-signature-row" hidden=""><span class="msum-row-label">Request Signature</span><span>$2.50</span></div>
          <div class="msum-row" id="msum-giftwrap-row" hidden=""><span class="msum-row-label">Gift wrap</span><span>$5.00</span></div>
          <div class="msum-row"><span class="msum-row-label">Shipping</span><span class="msum-success">Free</span></div>
          <div class="msum-row"><span class="msum-row-label">Taxes</span><span>$14.17</span></div>
          <div class="msum-row msum-total"><span>Total</span><span id="mobile-summary-total">$171.74</span></div>
        </div>
      </div>
    </div>

    <div class="mobile-footer-inner">
      <button type="button" class="mobile-summary-toggle" id="mobile-summary-toggle" aria-expanded="false" aria-controls="mobile-summary-panel" onclick="toggleMobileSummary()" aria-label="Show order summary">
        <div class="mobile-footer-total">
          <div class="mobile-footer-label">Order summary</div>
          <div class="mobile-footer-amount" id="mobile-footer-amount" aria-label="$171.74">$171.74</div>
        </div>
        <svg class="mobile-summary-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
          <path d="M5 12.5L10 7.5L15 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
      <button type="button" class="btn btn-cta mobile-footer-btn" onclick="handlePlaceOrder()" aria-label="Place order for $167.97">
        Place Order
      </button>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════
       "Remove Protect My Order?" confirmation (D1)
  ═══════════════════════════════════════════════════════════ -->
  <div class="protect-confirm-overlay" id="protect-confirm-overlay" hidden="" onclick="keepProtection()"></div>
  <div class="protect-confirm-modal" id="protect-confirm-modal" role="alertdialog" aria-modal="true" aria-labelledby="protect-confirm-title" aria-describedby="protect-confirm-body" hidden="">
    <div class="protect-confirm-icon" aria-hidden="true">
      <svg width="20" height="20" viewBox="0 0 22 22" fill="none"><path d="M11 2L3 5.5V10.5C3 15.1 6.6 19.4 11 20.5C15.4 19.4 19 15.1 19 10.5V5.5L11 2Z" stroke="#000" stroke-width="1.5"></path><path d="M11 8V12" stroke="#000" stroke-width="1.5" stroke-linecap="round"></path><circle cx="11" cy="15" r="1" fill="#000"></circle></svg>
    </div>
    <h2 class="protect-confirm-title" id="protect-confirm-title">Remove Protect My Order?</h2>
    <p class="protect-confirm-body" id="protect-confirm-body">Without this protection, you're responsible for replacing your order if it's lost, stolen, or damaged in transit.</p>
    <div class="protect-confirm-actions">
      <button type="button" class="btn btn-primary protect-confirm-keep" onclick="keepProtection()">Keep Protection · $3.77</button>
      <button type="button" class="protect-confirm-remove" onclick="removeProtection()">No thanks, remove it</button>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════
       CART DRAWER (right-side on desktop, bottom-sheet on mobile)
       Holds ALL items — no nested scroll in the summary itself.
  ═══════════════════════════════════════════════════════════ -->
  <div class="drawer-overlay" id="cart-drawer-overlay" hidden="" onclick="closeCartDrawer()"></div>
  <aside class="cart-drawer" id="cart-drawer" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title" hidden="">
    <div class="cart-drawer-handle" aria-hidden="true"></div>
    <header class="cart-drawer-head">
      <h2 class="cart-drawer-title" id="cart-drawer-title">Your Order <span class="cart-drawer-count">(4 items)</span></h2>
      <button class="cart-drawer-close" type="button" onclick="closeCartDrawer()" aria-label="Close order details">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 5L15 15M15 5L5 15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path></svg>
      </button>
    </header>

    <div class="cart-drawer-body">
      <!-- Item 1 -->
      <div class="order-item-row" data-brand="Chanel" data-category="Women's Fragrance">
        <div class="order-item-image"><img class="order-item-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Coco Mademoiselle EDP"></div>
        <div class="order-item-info">
          <div class="order-item-brand">Chanel</div>
          <div class="order-item-name">Coco Mademoiselle EDP</div>
          <div class="order-item-variant">3.4 oz / 100ml · Eau de Parfum</div>
          <div class="order-item-sku">SKU: CH-COCO-100</div>
          <div class="order-item-controls">
            <div class="qty-stepper" role="group" aria-label="Quantity for Coco Mademoiselle EDP">
              <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
              <span class="qty-value" aria-live="polite">1</span>
              <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
            </div>
            <button class="item-remove" type="button" aria-label="Remove Coco Mademoiselle EDP from cart" onclick="removeItem(this)">Remove</button>
          </div>
        </div>
        <div class="order-item-price"><div class="order-item-price-current">$112.99</div><div class="order-item-price-original">$145.00</div></div>
      </div>

      <!-- Item 2 -->
      <div class="order-item-row" data-brand="Dior" data-category="Men's Fragrance">
        <div class="order-item-image"><img class="order-item-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Dior Sauvage EDT"></div>
        <div class="order-item-info">
          <div class="order-item-brand">Dior</div>
          <div class="order-item-name">Sauvage EDT</div>
          <div class="order-item-variant">3.4 oz / 100ml · Eau de Toilette</div>
          <div class="order-item-sku">SKU: DI-SAUV-100</div>
          <div class="order-item-controls">
            <div class="qty-stepper" role="group" aria-label="Quantity for Sauvage EDT">
              <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
              <span class="qty-value" aria-live="polite">1</span>
              <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
            </div>
            <button class="item-remove" type="button" aria-label="Remove Sauvage EDT from cart" onclick="removeItem(this)">Remove</button>
          </div>
        </div>
        <div class="order-item-price"><div class="order-item-price-current">$39.99</div><div class="order-item-price-original">$72.00</div></div>
      </div>

      <!-- Item 3 -->
      <div class="order-item-row" data-brand="Versace" data-category="Women's Fragrance">
        <div class="order-item-image"><div class="order-item-thumb" style="background:linear-gradient(135deg,#f5eef0 0%,#e8d5dc 100%);" aria-hidden="true">🌸</div></div>
        <div class="order-item-info">
          <div class="order-item-brand">Versace</div>
          <div class="order-item-name">Bright Crystal EDT</div>
          <div class="order-item-variant">3.0 oz / 90ml · Eau de Toilette</div>
          <div class="order-item-sku">SKU: VE-BCRY-090</div>
          <div class="order-item-controls">
            <div class="qty-stepper" role="group" aria-label="Quantity for Bright Crystal EDT">
              <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
              <span class="qty-value" aria-live="polite">1</span>
              <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
            </div>
            <button class="item-remove" type="button" aria-label="Remove Bright Crystal EDT from cart" onclick="removeItem(this)">Remove</button>
          </div>
        </div>
        <div class="order-item-price"><div class="order-item-price-current">$69.99</div><div class="order-item-price-original">$85.00</div></div>
      </div>

      <!-- Item 4 -->
      <div class="order-item-row" data-brand="Chanel" data-category="Women's Fragrance">
        <div class="order-item-image"><img class="order-item-thumb" src="{{ url('/images/noimage-lrg.jpg') }}" alt="Chanel Chance Eau Tendre EDP"></div>
        <div class="order-item-info">
          <div class="order-item-brand">Chanel</div>
          <div class="order-item-name">Chance Eau Tendre EDP</div>
          <div class="order-item-variant">1.7 oz / 50ml · Eau de Parfum</div>
          <div class="order-item-sku">SKU: CH-CHTE-050</div>
          <div class="order-item-controls">
            <div class="qty-stepper" role="group" aria-label="Quantity for Chance Eau Tendre EDP">
              <button class="qty-btn" type="button" aria-label="Decrease quantity" onclick="updateQty(this,-1)">−</button>
              <span class="qty-value" aria-live="polite">1</span>
              <button class="qty-btn" type="button" aria-label="Increase quantity" onclick="updateQty(this,1)">+</button>
            </div>
            <button class="item-remove" type="button" aria-label="Remove Chance Eau Tendre EDP from cart" onclick="removeItem(this)">Remove</button>
          </div>
        </div>
        <div class="order-item-price"><div class="order-item-price-current">$115.00</div></div>
      </div>
    </div>

    <footer class="cart-drawer-foot">
      <div class="cart-drawer-foot-total">
        <span>Subtotal</span>
        <strong>$152.98</strong>
      </div>
      <button type="button" class="btn btn-primary btn-full" onclick="closeCartDrawer()">Done</button>
    </footer>
  </aside>

</div>

<script>
/* ============================================================
   MaxAroma Checkout — Interaction Layer
   ============================================================ */

'use strict';

// ── Auth Tabs ─────────────────────────────────────────────────
function switchAuthTab(tab) {
  const tabs = ['guest', 'signin'];
  tabs.forEach(t => {
    const btn = document.getElementById('tab-' + t);
    const panel = document.getElementById('panel-' + t);
    const isActive = t === tab;
    btn.classList.toggle('active', isActive);
    btn.setAttribute('aria-selected', isActive);
    if (isActive) {
      panel.removeAttribute('hidden');
    } else {
      panel.setAttribute('hidden', '');
    }
  });
}

// ── Payment Tabs ──────────────────────────────────────────────
function switchPayTab(tab) {
  const tabs = ['card', 'paypal', 'affirm'];
  tabs.forEach(t => {
    const btn = document.getElementById('pay-tab-' + t);
    const panel = document.getElementById('pay-panel-' + t);
    const isActive = t === tab;
    btn.classList.toggle('active', isActive);
    btn.setAttribute('aria-selected', isActive);
    if (isActive) {
      panel.removeAttribute('hidden');
    } else {
      panel.setAttribute('hidden', '');
    }
  });
}

// ── Order Summary Toggle (mobile) ─────────────────────────────
function toggleOrderSummary() {
  const card = document.getElementById('order-summary-card');
  const toggle = card.querySelector('.order-summary-toggle');
  const isOpen = card.classList.contains('open');

  card.classList.toggle('open', !isOpen);
  toggle.setAttribute('aria-expanded', String(!isOpen));
}

// ── Summary Accordions (coupon / gift card / shipping calc) ────
function toggleSummaryAccordion(key) {
  const toggle = document.getElementById(key + '-toggle');
  const panel = document.getElementById(key + '-panel');
  const accordion = toggle.closest('.summary-accordion');
  const isOpen = accordion.classList.contains('open');

  accordion.classList.toggle('open', !isOpen);
  toggle.setAttribute('aria-expanded', String(!isOpen));
  if (isOpen) {
    panel.setAttribute('hidden', '');
  } else {
    panel.removeAttribute('hidden');
    const input = panel.querySelector('input');
    if (input) setTimeout(() => input.focus(), 50);
  }
}

function applyPromo() {
  const input = document.getElementById('promo-code');
  const code = input.value.trim().toUpperCase();
  const result = document.getElementById('promo-result');
  if (!code) {
    result.innerHTML = `<p style="font-size:12px;color:var(--color-text-error);margin-top:8px;" role="alert">Please enter a code.</p>`;
    return;
  }
  input.closest('.promo-field').style.display = 'none';
  result.innerHTML = `
    <div class="coupon-applied animate-in" role="status" aria-live="polite" style="margin-top:12px;">
      <div><span class="coupon-applied-code">${code}</span>
        <span style="font-size:12px;color:var(--color-text-success);margin-left:8px;">Applied!</span></div>
      <button class="coupon-applied-remove" onclick="removeCode('coupon')" type="button" aria-label="Remove coupon ${code}">Remove</button>
    </div>`;
  announce('Coupon ' + code + ' applied.');
}

function applyGiftCard() {
  const input = document.getElementById('giftcard-code');
  const code = input.value.trim().toUpperCase();
  const result = document.getElementById('giftcard-result');
  if (!code) {
    result.innerHTML = `<p style="font-size:12px;color:var(--color-text-error);margin-top:8px;" role="alert">Please enter a gift card code.</p>`;
    return;
  }
  input.closest('.promo-field').style.display = 'none';
  result.innerHTML = `
    <div class="coupon-applied animate-in" role="status" aria-live="polite" style="margin-top:12px;">
      <div><span class="coupon-applied-code">${code}</span>
        <span style="font-size:12px;color:var(--color-text-success);margin-left:8px;">Gift card applied!</span></div>
      <button class="coupon-applied-remove" onclick="removeCode('giftcard')" type="button" aria-label="Remove gift card ${code}">Remove</button>
    </div>`;
  announce('Gift card ' + code + ' applied.');
}

function removeCode(key) {
  document.getElementById(key + '-result').innerHTML = '';
  const field = document.querySelector('#' + key + '-panel .promo-field');
  if (field) { field.style.display = 'flex'; field.querySelector('input').value = ''; field.querySelector('input').focus(); }
}

// ── Shipping Rate Calculator ──────────────────────────────────
function recalcShipping() {
  const country = document.getElementById('calc-country');
  const state = document.getElementById('calc-state');
  const zip = document.getElementById('calc-zip').value.trim();
  const result = document.getElementById('shipcalc-result');
  const loc = state.options[state.selectedIndex].text + (zip ? ' ' + zip : '');
  result.innerHTML = `
    <strong>Rates to ${loc}:</strong><br/>
    Standard (3–5 days): <strong>FREE</strong><br/>
    Express (2 days): <strong>$9.99</strong><br/>
    Overnight: <strong>$24.99</strong>`;
  announce('Shipping rates updated for ' + loc);
}

// ── Quantity stepper ──────────────────────────────────────────
function updateQty(btn, delta) {
  const stepper = btn.closest('.qty-stepper');
  const valEl = stepper.querySelector('.qty-value');
  let v = parseInt(valEl.textContent, 10) + delta;
  if (v < 1) v = 1;
  valEl.textContent = v;
}

// ── Remove item ───────────────────────────────────────────────
function removeItem(btn) {
  const row = btn.closest('.order-item-row');
  row.style.transition = 'opacity .2s ease';
  row.style.opacity = '0';
  setTimeout(() => row.remove(), 200);
  announce('Item removed from cart.');
}

// ── Order-by countdown timer ──────────────────────────────────
(function initCountdown() {
  const el = document.getElementById('ship-countdown');
  if (!el) return;
  let total = (2 * 60 + 17) * 60; // 2h 17m in seconds
  function render() {
    if (total <= 0) { el.textContent = 'a few minutes'; return; }
    const h = Math.floor(total / 3600);
    const m = Math.floor((total % 3600) / 60);
    const parts = [];
    if (h) parts.push(h + (h === 1 ? ' hour' : ' hours'));
    parts.push(m + (m === 1 ? ' minute' : ' minutes'));
    el.textContent = parts.join(' ');
  }
  render();
  setInterval(() => { total -= 60; render(); }, 60000);
})();

// ── Cart Drawer (right-side / bottom-sheet) ───────────────────
let _drawerLastFocus = null;

function openCartDrawer() {
  const drawer = document.getElementById('cart-drawer');
  const overlay = document.getElementById('cart-drawer-overlay');
  _drawerLastFocus = document.activeElement;

  overlay.hidden = false;
  drawer.hidden = false;
  document.body.style.overflow = 'hidden';

  // Move focus into the drawer (close button) for accessibility
  const closeBtn = drawer.querySelector('.cart-drawer-close');
  setTimeout(() => closeBtn && closeBtn.focus(), 50);

  document.addEventListener('keydown', _drawerKeydown);
}

function closeCartDrawer() {
  const drawer = document.getElementById('cart-drawer');
  const overlay = document.getElementById('cart-drawer-overlay');

  drawer.hidden = true;
  overlay.hidden = true;
  document.body.style.overflow = '';

  document.removeEventListener('keydown', _drawerKeydown);
  if (_drawerLastFocus) { _drawerLastFocus.focus(); _drawerLastFocus = null; }
}

function _drawerKeydown(e) {
  const drawer = document.getElementById('cart-drawer');
  if (e.key === 'Escape') { closeCartDrawer(); return; }
  if (e.key !== 'Tab') return;

  // Focus trap
  const focusable = drawer.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
  if (!focusable.length) return;
  const first = focusable[0];
  const last = focusable[focusable.length - 1];
  if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
  else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
}

// ── Edit Section ──────────────────────────────────────────────
function editSection(section) {
  const formId = 'section-' + section;
  const el = document.getElementById(formId);
  if (el) {
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(() => {
      const firstInput = el.querySelector('input, select, textarea');
      if (firstInput) firstInput.focus();
    }, 300);
  }
}

// ── Place Order ───────────────────────────────────────────────
function handlePlaceOrder() {
  const btn = document.getElementById('place-order-btn');
  if (!btn) return;

  btn.classList.add('btn-loading');
  btn.setAttribute('aria-busy', 'true');
  btn.setAttribute('aria-label', 'Processing your order…');
  btn.disabled = true;

  // Simulate processing
  setTimeout(() => {
    btn.classList.remove('btn-loading');
    btn.disabled = false;
    // In real implementation: redirect to confirmation page
  }, 2000);
}

// ── Floating Label Polyfill for selects ──────────────────────
document.querySelectorAll('.fl-input').forEach(input => {
  if (input.tagName === 'SELECT') {
    input.addEventListener('change', () => {
      input.closest('.fl-group').classList.toggle('has-value', input.value !== '');
    });
  }
});

// ── Shipping option selection ─────────────────────────────────
document.querySelectorAll('.shipping-option input[type="radio"]').forEach(radio => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('.shipping-option').forEach(opt => opt.classList.remove('selected'));
    radio.closest('.shipping-option').classList.add('selected');
  });
});

// ── Mobile bottom order summary (collapsible) ─────────────────
function toggleMobileSummary() {
  const footer = document.getElementById('mobile-footer');
  const toggle = document.getElementById('mobile-summary-toggle');
  const overlay = document.getElementById('mobile-summary-overlay');
  const open = footer.classList.toggle('summary-open');
  toggle.setAttribute('aria-expanded', String(open));
  toggle.setAttribute('aria-label', open ? 'Hide order summary' : 'Show order summary');
  if (overlay) overlay.hidden = !open;
}

function toggleReviewSummary() {
  // Desktop: static "Total / Includes tax" display only — the full
  // order-summary card is already always visible in the sidebar there,
  // so this row isn't interactive (matches window.innerWidth against
  // the same 960px breakpoint the CSS uses for mobile vs. desktop).
  if (window.innerWidth > 960) return;
  const card = document.getElementById('review-summary-card');
  const toggle = document.getElementById('review-summary-toggle');
  const open = card.classList.toggle('open');
  toggle.setAttribute('aria-expanded', String(open));
  toggle.setAttribute('aria-label', open ? 'Hide order summary' : 'Show order summary');
}

// ── Central order-state totals (single source of truth) ───────
// BASE_ORDER_TOTAL = subtotal − savings + shipping + tax, before any add-ons.
const BASE_ORDER_TOTAL = 167.97;
const ADDON_PRICES = {
  protection: 3.77,        // Protect My Order (default ON)
  'request-signature': 2.50, // Request Signature
  'gift-wrap': 5.00,       // Gift Wrap ($5 per order for this prototype)
};

// Discount code state (single source; drives every total via recalcTotals).
// Demo validation only — a real build validates server-side.
let appliedDiscount = 0;
let appliedCode = '';
const DISCOUNT_CODES = { 'WELCOME10': 10.00, 'SAVE5': 5.00 };

function applyMobileDiscount() {
  const input = document.getElementById('msum-promo-input');
  const msg = document.getElementById('msum-promo-msg');
  const code = (input.value || '').trim().toUpperCase();
  if (!code) { msg.textContent = 'Enter a discount code.'; msg.className = 'msum-promo-msg error'; return; }
  if (!(code in DISCOUNT_CODES)) {
    msg.textContent = 'That code isn’t valid.';
    msg.className = 'msum-promo-msg error';
    return;
  }
  appliedDiscount = DISCOUNT_CODES[code];
  appliedCode = code;
  document.getElementById('msum-promo-field').hidden = true;
  const applied = document.getElementById('msum-promo-applied');
  applied.hidden = false;
  document.getElementById('msum-promo-code-label').textContent = code;
  document.getElementById('msum-code-name').textContent = '(' + code + ')';
  msg.textContent = 'Discount applied.';
  msg.className = 'msum-promo-msg success';
  announce('Discount code ' + code + ' applied.');
  recalcTotals();
}

function removeMobileDiscount() {
  appliedDiscount = 0;
  appliedCode = '';
  document.getElementById('msum-promo-field').hidden = false;
  document.getElementById('msum-promo-applied').hidden = true;
  document.getElementById('msum-promo-input').value = '';
  const msg = document.getElementById('msum-promo-msg');
  msg.textContent = '';
  msg.className = 'msum-promo-msg';
  announce('Discount code removed.');
  recalcTotals();
}

// ── "Remove Protect My Order?" confirmation (D1) ───────────────
// Turning the toggle ON needs no confirmation — only turning it OFF
// (the checkbox is already unchecked by the time this fires; the
// popup decides whether that change sticks or gets reverted).
let _protectConfirmLastFocus = null;
function handleProtectionToggle(checkbox) {
  if (checkbox.checked) { recalcTotals(); return; }
  const overlay = document.getElementById('protect-confirm-overlay');
  const modal = document.getElementById('protect-confirm-modal');
  _protectConfirmLastFocus = checkbox;
  overlay.hidden = false;
  modal.hidden = false;
  document.body.style.overflow = 'hidden';
  setTimeout(() => { const b = modal.querySelector('.protect-confirm-keep'); if (b) b.focus(); }, 50);
  document.addEventListener('keydown', _protectConfirmKeydown);
}
function closeProtectConfirm() {
  const overlay = document.getElementById('protect-confirm-overlay');
  const modal = document.getElementById('protect-confirm-modal');
  overlay.hidden = true;
  modal.hidden = true;
  document.body.style.overflow = '';
  document.removeEventListener('keydown', _protectConfirmKeydown);
  if (_protectConfirmLastFocus) { _protectConfirmLastFocus.focus(); _protectConfirmLastFocus = null; }
}
function keepProtection() {
  document.getElementById('protection').checked = true;
  syncAddonCard('protection');
  recalcTotals();
  closeProtectConfirm();
}
function removeProtection() {
  // checkbox is already unchecked from the user's original click
  syncAddonCard('protection');
  recalcTotals();
  closeProtectConfirm();
  announce('Protect My Order removed.');
}
function _protectConfirmKeydown(e) {
  // Esc = safe default (keep the protection), same as dismissing without choosing
  if (e.key === 'Escape') { keepProtection(); return; }
  if (e.key !== 'Tab') return;
  const modal = document.getElementById('protect-confirm-modal');
  const focusable = modal.querySelectorAll('button:not(:disabled), [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
  if (!focusable.length) return;
  const first = focusable[0], last = focusable[focusable.length - 1];
  if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
  else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
}

// Highlight an add-on card border when its toggle is on
function syncAddonCard(id) {
  const input = document.getElementById(id);
  if (!input) return;
  const row = input.closest('.addon-row');
  if (row) row.classList.toggle('active', input.checked);
}

// Recompute the order total from base + selected add-ons, then push the
// same value to every UI surface (top summary, mobile summary, review,
// place-order button, promo progress). No component computes totals alone.
function recalcTotals() {
  let addons = 0;
  ['protection', 'request-signature', 'gift-wrap'].forEach(id => {
    const input = document.getElementById(id);
    const on = !!(input && input.checked);
    if (on) addons += ADDON_PRICES[id];
    syncAddonCard(id);
  });

  const total = Math.max(0, BASE_ORDER_TOTAL + addons - appliedDiscount);
  const money = '$' + total.toFixed(2);

  // Applied discount-code line (mobile bottom summary + review-step summary)
  ['msum-code-discount-row', 'review-code-discount-row'].forEach(id => {
    const row = document.getElementById(id);
    if (row) row.hidden = appliedDiscount <= 0;
  });
  ['msum-code-discount-value', 'review-code-discount-value'].forEach(id => {
    const val = document.getElementById(id);
    if (val) val.textContent = '−$' + appliedDiscount.toFixed(2);
  });
  const reviewCodeName = document.getElementById('review-code-name');
  if (reviewCodeName) reviewCodeName.textContent = appliedCode ? '(' + appliedCode + ')' : '';

  // Add-on line rows (top summary + mobile bottom summary + review-step summary)
  const rowMap = {
    'protection':        ['protection-row', 'msum-protection-row', 'review-protection-row'],
    'request-signature': ['signature-row',  'msum-signature-row',  'review-signature-row'],
    'gift-wrap':         ['gift-wrap-row',   'msum-giftwrap-row',  'review-giftwrap-row'],
  };
  Object.keys(rowMap).forEach(id => {
    const input = document.getElementById(id);
    const on = !!(input && input.checked);
    rowMap[id].forEach(rowId => {
      const row = document.getElementById(rowId);
      if (row) row.hidden = !on;
    });
  });

  // Every total display
  const set = (el, text, attr, attrVal) => {
    if (!el) return;
    el.textContent = text;
    if (attr) el.setAttribute(attr, attrVal);
  };
  set(document.getElementById('summary-total-value'), money);
  set(document.getElementById('mobile-summary-amount'), money, 'aria-label', 'Total ' + money);
  set(document.getElementById('place-order-total-amount'), money, 'aria-label', 'Order total ' + money);
  set(document.getElementById('place-order-btn-text'), 'Place Order · ' + money);
  set(document.getElementById('mobile-footer-amount'), money, 'aria-label', money);
  set(document.getElementById('mobile-summary-total'), money);
  set(document.getElementById('review-summary-total-value'), money);

  // Promo progress uses the same live total
  updatePromo(total);
}

// ── Purchase-threshold promo module (backend-configurable) ────
// Only ONE promotion is ever shown. The active promotion, threshold,
// eligibility scope and copy come from the backend. Nothing is hardcoded
// into the visible markup — swap PROMO_CONFIG for a server-provided object.
//
// Suggested backend shape (see spec §8 / §28 — kept configurable):
//   { type, threshold, eligibleSubtotal, eligibleBrands, eligibleCategories,
//     scope, eligibilityNote, status, priority, messages }
//   type             : 'free_samples' | 'gift_with_purchase'
//   scope            : 'site_wide'  → progress uses the full order total
//                      'restricted' → progress uses eligible items only,
//                                      and an info tooltip lists eligible brands/categories
//   eligibleSubtotal : number, sent by backend once it has computed the eligible
//                      total itself — wins over client-side computation when present.
//                      null/omitted → the front end computes it from eligibleBrands/
//                      eligibleCategories against the live cart (see computeEligibleSubtotal).
//   eligibleBrands   : string[] — brand names that qualify (case-insensitive match
//                      against each cart line's data-brand attribute)
//   eligibleCategories: string[] — category names that qualify (data-category attribute)
//   eligibilityNote  : optional explicit tooltip copy; auto-generated from
//                      eligibleBrands/eligibleCategories when omitted
//   priority         : which promo wins when several are eligible (client to confirm)
//
// NOTE: eligibility currently keys off data-brand/data-category attributes on each
// .order-item-row (see cart drawer markup). Client confirmed whether the real product
// catalog already carries this tagging is still an open question — see change list.
const PROMO_PRESETS = {
  free_samples: {
    type: 'free_samples',
    threshold: 175.00,
    scope: 'site_wide',
    eligibleSubtotal: null,
    eligibleBrands: [],
    eligibleCategories: [],
    eligibilityNote: null,
    status: 'active',
    priority: 1,
  },
  gwp_site_wide: {
    type: 'gift_with_purchase',
    threshold: 200.00,
    scope: 'site_wide',
    eligibleSubtotal: null,
    eligibleBrands: [],
    eligibleCategories: [],
    eligibilityNote: null,
    status: 'active',
    priority: 1,
  },
  gwp_restricted: {
    type: 'gift_with_purchase',
    threshold: 150.00,
    scope: 'restricted',
    eligibleSubtotal: null,          // null → compute from eligibleBrands/eligibleCategories below
    eligibleBrands: ['Dior', 'Versace'],
    eligibleCategories: [],
    eligibilityNote: null,           // null → auto-generate from eligibleBrands/eligibleCategories
    status: 'active',
    priority: 1,
  },
};
const PROMO_MESSAGES = {
  free_samples:       { title: 'Unlock your free samples',        away: 'from free samples!',            success: "You've unlocked your free samples!" },
  gift_with_purchase: { title: 'Unlock your complimentary gift',   away: 'from your complimentary gift.', success: 'Your complimentary gift has been unlocked.' }
};
// Backend swaps this object out for whichever promo is currently active.
let PROMO_CONFIG = PROMO_PRESETS.free_samples;

// Reads the live cart (the drawer holds the full item list; the sidebar only
// ever shows a preview of the first two) so eligibility math always reflects
// what's actually in the cart, not a hardcoded number.
function getCartLineItems() {
  const rows = document.querySelectorAll('#cart-drawer .order-item-row');
  return Array.from(rows).map(row => {
    const priceEl = row.querySelector('.order-item-price-current');
    const qtyEl = row.querySelector('.qty-value');
    return {
      brand: row.dataset.brand || '',
      category: row.dataset.category || '',
      price: priceEl ? parseFloat(priceEl.textContent.replace(/[^0-9.]/g, '')) || 0 : 0,
      qty: qtyEl ? parseInt(qtyEl.textContent, 10) || 0 : 0,
    };
  });
}

// Sums only the cart lines that match the promo's eligible brands/categories —
// this is the "restricted" branch the client asked for: ineligible items must
// not move the bar.
function computeEligibleSubtotal(cfg) {
  const brands = (cfg.eligibleBrands || []).map(b => b.toLowerCase());
  const categories = (cfg.eligibleCategories || []).map(c => c.toLowerCase());
  if (!brands.length && !categories.length) return 0;
  return getCartLineItems().reduce((sum, item) => {
    const eligible = brands.includes(item.brand.toLowerCase()) || categories.includes(item.category.toLowerCase());
    return eligible ? sum + item.price * item.qty : sum;
  }, 0);
}

function formatList(arr) {
  if (arr.length <= 1) return arr[0] || '';
  if (arr.length === 2) return arr[0] + ' and ' + arr[1];
  return arr.slice(0, -1).join(', ') + ', and ' + arr[arr.length - 1];
}

// Client asked for a small info icon that tells shoppers which brands/categories
// qualify. Auto-generated from config so it never drifts out of sync with the
// actual eligibility rule — unless the backend sends explicit copy.
function buildEligibilityNote(cfg) {
  if (cfg.eligibilityNote) return cfg.eligibilityNote;
  const bits = [];
  if (cfg.eligibleBrands && cfg.eligibleBrands.length) bits.push('brands: ' + formatList(cfg.eligibleBrands));
  if (cfg.eligibleCategories && cfg.eligibleCategories.length) bits.push('categories: ' + formatList(cfg.eligibleCategories));
  return bits.length ? 'Eligible on selected ' + bits.join(' · ') + '.' : 'Only select items in your cart qualify for this offer.';
}

function updatePromo(orderTotal) {
  const card = document.getElementById('promo-card');
  if (!card || PROMO_CONFIG.status !== 'active') { if (card) card.hidden = true; return; }
  card.hidden = false;

  const cfg = PROMO_CONFIG;
  const msg = PROMO_MESSAGES[cfg.type] || PROMO_MESSAGES.free_samples;
  const restricted = cfg.scope === 'restricted';

  // Site-wide promos track the full cart subtotal for every shopper.
  // Restricted promos count only eligible items — backend-provided
  // eligibleSubtotal wins when present, otherwise computed live from the cart.
  const qualifying = restricted
    ? (typeof cfg.eligibleSubtotal === 'number' ? cfg.eligibleSubtotal : computeEligibleSubtotal(cfg))
    : orderTotal;

  const remaining = Math.max(0, cfg.threshold - qualifying);
  const pct = Math.min(100, (qualifying / cfg.threshold) * 100); // capped at 100%
  const unlocked = remaining <= 0;

  const titleText = document.getElementById('promo-title-text');
  if (titleText) titleText.textContent = msg.title;

  const fill = document.getElementById('promo-fill');
  if (fill) fill.style.width = pct.toFixed(1) + '%';

  const bar = document.getElementById('promo-progressbar');
  if (bar) bar.setAttribute('aria-valuenow', Math.round(pct));

  const amount = document.getElementById('promo-amount');
  if (amount) amount.textContent = '$' + remaining.toFixed(2) + ' away';

  const suffix = document.getElementById('promo-remaining-suffix');
  if (suffix) suffix.textContent = msg.away;

  const successDesc = document.getElementById('promo-success-desc');
  if (successDesc) successDesc.textContent = msg.success;

  // Restricted promotions expose an accessible eligibility tooltip so shoppers
  // know what to add instead of guessing why the bar isn't moving.
  const infoBtn = document.getElementById('promo-info-btn');
  const tooltip = document.getElementById('promo-tooltip');
  if (infoBtn) infoBtn.hidden = !restricted;
  if (tooltip) tooltip.textContent = restricted ? buildEligibilityNote(cfg) : '';

  card.classList.toggle('unlocked', unlocked);
}

// ── DEMO ONLY: lets the client preview all 3 promo states live without
// touching code. Remove this function + its UI hook once PROMO_CONFIG is
// wired to the real backend/catalog data (see checkout-v2-feedback-changelist.md). ──
function setPromoPreset(name) {
  const preset = PROMO_PRESETS[name];
  if (!preset) return;
  PROMO_CONFIG = preset;
  document.querySelectorAll('.promo-demo-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.preset === name);
    b.setAttribute('aria-pressed', String(b.dataset.preset === name));
  });
  recalcTotals();
}

// Accessible tooltip: hover + keyboard focus handled in CSS; this adds
// tap/click toggling on mobile plus dismissal (outside click / Escape).
function togglePromoTooltip(e) {
  if (e) e.stopPropagation();
  const btn = document.getElementById('promo-info-btn');
  const tooltip = document.getElementById('promo-tooltip');
  if (!btn || !tooltip) return;
  const show = tooltip.classList.toggle('show');
  btn.setAttribute('aria-expanded', String(show));
}
document.addEventListener('click', function(e) {
  const btn = document.getElementById('promo-info-btn');
  const tooltip = document.getElementById('promo-tooltip');
  if (!btn || !tooltip || !tooltip.classList.contains('show')) return;
  if (!btn.contains(e.target) && !tooltip.contains(e.target)) {
    tooltip.classList.remove('show'); btn.setAttribute('aria-expanded', 'false');
  }
});
document.addEventListener('keydown', function(e) {
  if (e.key !== 'Escape') return;
  const btn = document.getElementById('promo-info-btn');
  const tooltip = document.getElementById('promo-tooltip');
  if (btn && tooltip && tooltip.classList.contains('show')) {
    tooltip.classList.remove('show'); btn.setAttribute('aria-expanded', 'false'); btn.focus();
  }
  const summaryFooter = document.getElementById('mobile-footer');
  if (summaryFooter && summaryFooter.classList.contains('summary-open')) {
    toggleMobileSummary();
    document.getElementById('mobile-summary-toggle').focus();
  }
});

// Reflect the default add-on state (Protect My Order ON) and animate the
// promo bar from 0 on first paint — single recalc drives every surface.
window.addEventListener('load', function() {
  requestAnimationFrame(recalcTotals);
});

// ── Address autocomplete (D4) — DEMO DATA ──────────────────────
// No Google Places API key exists in this project. This stands in the
// real UI/UX (dropdown, keyboard nav, autofill-on-select, debounce)
// against a small local address list so the interaction can be
// reviewed now. Swap ADDRESS_DEMO_DB + the filter in
// handleAddressInput() for a real Places Autocomplete + Place Details
// call later — nothing else here needs to change.
const ADDRESS_DEMO_DB = [
  { line1: '123 Main Street',       city: 'New York',      state: 'NY', zip: '10001' },
  { line1: '456 Madison Avenue',    city: 'New York',      state: 'NY', zip: '10022' },
  { line1: '789 Broadway',          city: 'New York',      state: 'NY', zip: '10003' },
  { line1: '12 Sunset Boulevard',   city: 'Los Angeles',   state: 'CA', zip: '90028' },
  { line1: '2200 Ocean Avenue',     city: 'Santa Monica',  state: 'CA', zip: '90405' },
  { line1: '350 Market Street',     city: 'San Francisco', state: 'CA', zip: '94105' },
  { line1: '88 Congress Avenue',    city: 'Austin',        state: 'TX', zip: '78701' },
  { line1: '500 Main Street',       city: 'Houston',       state: 'TX', zip: '77002' },
  { line1: '1200 Brickell Avenue',  city: 'Miami',         state: 'FL', zip: '33131' },
  { line1: '400 Beach Drive',       city: 'Orlando',       state: 'FL', zip: '32801' },
];

let _addrActiveIndex = -1;
let _addrDebounce = null;
const ADDR_PIN_SVG = '<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 14.5S3 9.5 3 6.2C3 3.6 5.2 1.5 8 1.5S13 3.6 13 6.2C13 9.5 8 14.5 8 14.5Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><circle cx="8" cy="6.2" r="1.8" stroke="currentColor" stroke-width="1.3"/></svg>';

function handleAddressInput(input) {
  clearTimeout(_addrDebounce);
  const query = input.value.trim();
  _addrDebounce = setTimeout(() => renderAddressSuggestions(query), 150);
}

function renderAddressSuggestions(query) {
  const list = document.getElementById('address-suggestions');
  const field = document.getElementById('address1');
  if (!list || !field) return;
  _addrActiveIndex = -1;

  if (query.length < 3) { closeAddressSuggestions(); return; }

  const q = query.toLowerCase();
  const matches = ADDRESS_DEMO_DB.filter(a => a.line1.toLowerCase().includes(q)).slice(0, 5);

  if (!matches.length) {
    list.innerHTML = '<li class="addr-suggestions-empty">No matching addresses</li>';
  } else {
    list.innerHTML = matches.map((a, i) =>
      '<li class="addr-suggestion" id="addr-opt-' + i + '" role="option" data-idx="' + i + '" onclick="selectAddressSuggestion(' + i + ')" onmouseenter="_addrSetActive(' + i + ')">' +
        ADDR_PIN_SVG +
        '<span><span class="addr-suggestion-line1">' + a.line1 + '</span><br><span class="addr-suggestion-line2">' + a.city + ', ' + a.state + ' ' + a.zip + '</span></span>' +
      '</li>'
    ).join('');
  }
  list.hidden = false;
  field.setAttribute('aria-expanded', 'true');
  window._addrCurrentMatches = matches;
}

function _addrSetActive(i) {
  _addrActiveIndex = i;
  document.querySelectorAll('.addr-suggestion').forEach((el, idx) => el.classList.toggle('active', idx === i));
  const field = document.getElementById('address1');
  const opt = document.getElementById('addr-opt-' + i);
  if (field && opt) field.setAttribute('aria-activedescendant', opt.id);
}

function selectAddressSuggestion(i) {
  const a = (window._addrCurrentMatches || [])[i];
  if (!a) return;
  document.getElementById('address1').value = a.line1;
  document.getElementById('city').value = a.city;
  const stateSelect = document.getElementById('state');
  if (stateSelect) stateSelect.value = a.state;
  document.getElementById('zip').value = a.zip;
  closeAddressSuggestions();
  const addr2 = document.getElementById('address2');
  if (addr2) addr2.focus();
  announce('Address filled: ' + a.line1 + ', ' + a.city + ', ' + a.state + ' ' + a.zip);
}

function closeAddressSuggestions() {
  const list = document.getElementById('address-suggestions');
  const field = document.getElementById('address1');
  if (list) { list.hidden = true; list.innerHTML = ''; }
  if (field) { field.setAttribute('aria-expanded', 'false'); field.removeAttribute('aria-activedescendant'); }
  _addrActiveIndex = -1;
}

function handleAddressKeydown(e) {
  const list = document.getElementById('address-suggestions');
  if (!list || list.hidden) return;
  const matches = window._addrCurrentMatches || [];
  if (e.key === 'ArrowDown') {
    e.preventDefault();
    if (matches.length) { _addrActiveIndex = (_addrActiveIndex + 1) % matches.length; _addrSetActive(_addrActiveIndex); }
  } else if (e.key === 'ArrowUp') {
    e.preventDefault();
    if (matches.length) { _addrActiveIndex = (_addrActiveIndex - 1 + matches.length) % matches.length; _addrSetActive(_addrActiveIndex); }
  } else if (e.key === 'Enter') {
    if (_addrActiveIndex >= 0) { e.preventDefault(); selectAddressSuggestion(_addrActiveIndex); }
  } else if (e.key === 'Escape') {
    closeAddressSuggestions();
  }
}

document.addEventListener('click', function(e) {
  const field = document.getElementById('address1');
  const list = document.getElementById('address-suggestions');
  if (!field || !list || list.hidden) return;
  if (!field.contains(e.target) && !list.contains(e.target)) closeAddressSuggestions();
});

// ── Card number formatting ────────────────────────────────────
const cardInput = document.getElementById('card-number');
if (cardInput) {
  cardInput.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '').substring(0, 16);
    e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
  });
}

const expiryInput = document.getElementById('card-expiry');
if (expiryInput) {
  expiryInput.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 2) v = v.substring(0, 2) + ' / ' + v.substring(2);
    e.target.value = v;
  });
}

// ── Hide mobile footer when near place order btn ──────────────
if ('IntersectionObserver' in window) {
  const placeOrderSection = document.querySelector('.place-order-section');
  const mobileFooter = document.getElementById('mobile-footer');
  if (placeOrderSection && mobileFooter) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        mobileFooter.style.opacity = entry.isIntersecting ? '0' : '1';
        mobileFooter.style.pointerEvents = entry.isIntersecting ? 'none' : 'auto';
      });
    }, { threshold: 0.3 });
    obs.observe(placeOrderSection);
  }
}

// ── Upsell carousel: mouse drag-to-scroll + wheel (touch is native) ──
document.querySelectorAll('.upsell-carousel').forEach(track => {
  let isDown = false, startX = 0, startScroll = 0, moved = false;

  track.addEventListener('pointerdown', e => {
    if (e.pointerType !== 'mouse') return; // let touch/pen use native swipe
    isDown = true;
    moved = false;
    startX = e.clientX;
    startScroll = track.scrollLeft;
  });

  track.addEventListener('pointermove', e => {
    if (!isDown) return;
    const dx = e.clientX - startX;
    if (!moved && Math.abs(dx) > 4) {
      moved = true;
      track.classList.add('dragging');
      try { track.setPointerCapture(e.pointerId); } catch (_) {}
    }
    if (moved) track.scrollLeft = startScroll - dx;
  });

  const endDrag = () => {
    if (!isDown) return;
    isDown = false;
    track.classList.remove('dragging');
  };
  track.addEventListener('pointerup', endDrag);
  track.addEventListener('pointercancel', endDrag);

  // Suppress the click that follows a drag so "Add" doesn't fire on release
  track.addEventListener('click', e => {
    if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
  }, true);

  // Vertical wheel → horizontal scroll when hovering the carousel
  track.addEventListener('wheel', e => {
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      track.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, { passive: false });
});

// ── Upsell add button ─────────────────────────────────────────
document.querySelectorAll('.upsell-add').forEach(btn => {
  btn.addEventListener('click', function() {
    const originalText = this.innerHTML;
    this.innerHTML = '✓';
    this.style.background = '#16A34A';
    this.setAttribute('aria-label', this.getAttribute('aria-label').replace('Add', 'Added'));
    setTimeout(() => {
      this.innerHTML = originalText;
      this.style.background = '';
    }, 1500);
  });
});

// ── Announce dynamic changes to screen readers ────────────────
function announce(message) {
  const el = document.createElement('div');
  el.setAttribute('role', 'status');
  el.setAttribute('aria-live', 'polite');
  el.className = 'visually-hidden';
  el.textContent = message;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 3000);
}
</script>
@endsection