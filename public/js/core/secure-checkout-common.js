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
      const result = document.getElementById('promo-result');
      const code = input ? input.value.trim().toUpperCase() : '';

      if (!input || !result || !code) {
        if (result) {
          result.innerHTML = '<p style="font-size:12px;color:var(--color-text-error);margin-top:8px;">Please enter a code.</p>';
        }
        return;
      }

      if (
        window.MaxaromaCheckoutDiscount &&
        typeof window.MaxaromaCheckoutDiscount.apply === 'function'
      ) {
        result.insertAdjacentHTML(
          'beforeend',
          '<p class="promo-applying-message" style="font-size:12px;margin-top:8px;">Applying coupon...</p>'
        );

        window.MaxaromaCheckoutDiscount.apply(code)
          .done(function(response) {
            const messages = result.querySelectorAll('.promo-applying-message');
            messages.forEach(function(message) {
              message.remove();
            });

            if (
              response &&
              (
                response.status === 'error' ||
                response.error === 1 ||
                response.error === '1'
              )
            ) {
              result.insertAdjacentHTML(
                'beforeend',
                '<p style="font-size:12px;color:var(--color-text-error);margin-top:8px;">' +
                (response.message || 'This coupon could not be applied.') +
                '</p>'
              );
              return;
            }

            if (typeof window.addAppliedCheckoutDiscount === 'function') {
              window.addAppliedCheckoutDiscount(code, response);
            }

            input.value = '';
          })
          .fail(function(xhr) {
            const messages = result.querySelectorAll('.promo-applying-message');
            messages.forEach(function(message) {
              message.remove();
            });

            const response = xhr.responseJSON || {};

            result.insertAdjacentHTML(
              'beforeend',
              '<p style="font-size:12px;color:var(--color-text-error);margin-top:8px;">' +
              (response.message || 'Unable to apply coupon. Please try again.') +
              '</p>'
            );
          });
      }
    }

    function removeCode(key) {
      document.getElementById(key + '-result').innerHTML = '';
      const field = document.querySelector('#' + key + '-panel .promo-field');
      if (field) {
        field.style.display = 'flex';
        field.querySelector('input').value = '';
        field.querySelector('input').focus();
      }
    }

    // ── Shipping Rate Calculator ──────────────────────────────────
    // No separate calculator API exists in the current One Page contract.
    // Do not display fabricated rates. The actual shipping methods are loaded
    // from the checkout shipping endpoint using the checkout address above.
    function recalcShipping() {
      const result = document.getElementById('shipcalc-result');
      if (result) {
        result.innerHTML =
          'Shipping rates are calculated from your checkout address and selected shipping method.';
      }
    }

    // ── Quantity stepper ──────────────────────────────────────────

    // ── Remove item ───────────────────────────────────────────────

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
      if (_drawerLastFocus) {
        _drawerLastFocus.focus();
        _drawerLastFocus = null;
      }
    }

    function _drawerKeydown(e) {
      const drawer = document.getElementById('cart-drawer');
      if (e.key === 'Escape') {
        closeCartDrawer();
        return;
      }
      if (e.key !== 'Tab') return;

      // Focus trap
      const focusable = drawer.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      if (!focusable.length) return;
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }

    // ── Edit Section ──────────────────────────────────────────────
    function editSection(section) {
      const formId = 'section-' + section;
      const el = document.getElementById(formId);
      if (el) {
        el.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
        setTimeout(() => {
          const firstInput = el.querySelector('input, select, textarea');
          if (firstInput) firstInput.focus();
        }, 300);
      }
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

    // ── Desktop Sticky Place Order Dock (>= 992px) ───────────────
    (function initDesktopStickyPlaceOrder() {
      function setupStickyObserver() {
        const ctaWrap = document.getElementById('place-order-cta-wrap');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const dock = document.getElementById('order-summary-sticky-dock');
        const dockInner = document.getElementById('order-summary-sticky-inner');
        const summaryCard = document.getElementById('order-summary-card') || document.getElementById('order-summary-sidebar');
        if (!ctaWrap || !placeOrderBtn || !dock || !dockInner) return;

        const DESKTOP_MIN_WIDTH = 992;
        let isSticky = false;

        function isDesktop() {
          return window.innerWidth >= DESKTOP_MIN_WIDTH;
        }

        function syncStickyDockPosition() {
          if (!summaryCard || !dock) return;
          const rect = summaryCard.getBoundingClientRect();
          dock.style.left = rect.left + 'px';
          dock.style.width = rect.width + 'px';
        }

        function setSticky(active) {
          if (!isDesktop()) active = false;
          if (active === isSticky) {
            if (active) syncStickyDockPosition();
            return;
          }
          isSticky = active;
          if (active) {
            syncStickyDockPosition();
            if (!dockInner.contains(placeOrderBtn)) {
              dockInner.appendChild(placeOrderBtn);
            }
            document.body.classList.add('place-order-sticky');
          } else {
            document.body.classList.remove('place-order-sticky');
            if (!ctaWrap.contains(placeOrderBtn)) {
              ctaWrap.appendChild(placeOrderBtn);
            }
          }
        }

        // Keep dock position perfectly aligned with right Order Summary panel
        window.addEventListener('resize', function() {
          if (!isDesktop()) {
            setSticky(false);
          } else {
            syncStickyDockPosition();
            const rect = ctaWrap.getBoundingClientRect();
            const inViewport = (rect.top < window.innerHeight && rect.bottom > 0);
            setSticky(!inViewport);
          }
        }, {
          passive: true
        });

        window.addEventListener('scroll', function() {
          if (isSticky) {
            syncStickyDockPosition();
          }
        }, {
          passive: true
        });

        if ('ResizeObserver' in window && summaryCard) {
          const ro = new ResizeObserver(function() {
            if (isSticky) syncStickyDockPosition();
          });
          ro.observe(summaryCard);
        }

        if ('IntersectionObserver' in window) {
          const observer = new IntersectionObserver(
            function(entries) {
              if (!isDesktop()) {
                setSticky(false);
                return;
              }
              const entry = entries[0];
              // Hide sticky when original button area is visible; show when outside viewport
              if (entry.isIntersecting) {
                setSticky(false);
              } else {
                setSticky(true);
              }
            }, {
              root: null,
              threshold: 0
            }
          );
          observer.observe(ctaWrap);
        } else {
          function checkScroll() {
            if (!isDesktop()) {
              setSticky(false);
              return;
            }
            const rect = ctaWrap.getBoundingClientRect();
            const inViewport = (rect.top < window.innerHeight && rect.bottom > 0);
            setSticky(!inViewport);
          }
          window.addEventListener('scroll', checkScroll, {
            passive: true
          });
          checkScroll();
        }
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupStickyObserver);
      } else {
        setupStickyObserver();
      }
    })();

    // ── Backend-driven checkout state ─────────────────────────────
    // Do not calculate checkout totals in the Blade. checkout.js receives the
    // backend totals response and updates the common UI. These handlers only
    // control confirmation dialogs and lightweight UI state.

    let _protectConfirmLastFocus = null;
    let _signatureConfirmLastFocus = null;

    function syncAddonCard(id) {
      const input = document.getElementById(id);
      if (!input) return;
      const row = input.closest('.addon-row');
      if (row) row.classList.toggle('active', input.checked);
    }

    function applyMobileDiscount() {
      const input = document.getElementById('msum-promo-input');
      const msg = document.getElementById('msum-promo-msg');
      const code = input ? input.value.trim().toUpperCase() : '';

      if (!input || !msg || !code) {
        if (msg) {
          msg.textContent = 'Enter a discount code.';
          msg.className = 'msum-promo-msg error';
        }
        return;
      }

      if (
        window.MaxaromaCheckoutDiscount &&
        typeof window.MaxaromaCheckoutDiscount.apply === 'function'
      ) {
        window.MaxaromaCheckoutDiscount.apply(code)
          .done(function(response) {
            if (response && (response.status === 'error' || response.error === 1 || response.error === '1')) {
              msg.textContent = response.message || 'That code isn’t valid.';
              msg.className = 'msum-promo-msg error';
              return;
            }

            const field = document.getElementById('msum-promo-field');
            const applied = document.getElementById('msum-promo-applied');
            const label = document.getElementById('msum-promo-code-label');

            if (field) field.hidden = true;
            if (applied) applied.hidden = false;
            if (label) label.textContent = code;

            msg.textContent = 'Discount applied.';
            msg.className = 'msum-promo-msg success';
          })
          .fail(function(xhr) {
            const response = xhr.responseJSON || {};
            msg.textContent = response.message || 'Unable to apply discount.';
            msg.className = 'msum-promo-msg error';
          });
      }
    }

    function removeMobileDiscount() {
      if (
        window.MaxaromaCheckoutDiscount &&
        typeof window.MaxaromaCheckoutDiscount.removeCoupon === 'function'
      ) {
        window.MaxaromaCheckoutDiscount.removeCoupon();
      }

      const field = document.getElementById('msum-promo-field');
      const applied = document.getElementById('msum-promo-applied');
      const input = document.getElementById('msum-promo-input');
      const msg = document.getElementById('msum-promo-msg');

      if (field) field.hidden = false;
      if (applied) applied.hidden = true;
      if (input) input.value = '';
      if (msg) {
        msg.textContent = '';
        msg.className = 'msum-promo-msg';
      }
    }

    function handleProtectionToggle(checkbox) {

      /*
       * PROTECTION ON:
       * Apply insurance directly.
       * Do not open the confirmation popup.
       */
      if (checkbox.checked) {

        if (
          window.MaxaromaOnePageCheckout &&
          typeof window.MaxaromaOnePageCheckout
          .setShippingInsurance === 'function'
        ) {
          window.MaxaromaOnePageCheckout
            .setShippingInsurance('add');
        }

        syncAddonCard('protection');

        return;
      }

      /*
       * PROTECTION OFF:
       * Keep the existing confirmation popup behavior.
       */
      const overlay = document.getElementById(
        'protect-confirm-overlay'
      );

      const modal = document.getElementById(
        'protect-confirm-modal'
      );

      if (!overlay || !modal) {

        // If the confirmation UI is unavailable,
        // restore the checkbox to ON safely.
        checkbox.checked = true;

        syncAddonCard('protection');

        return;
      }

      /*
       * Read the current insurance amount before
       * opening the confirmation popup.
       */
      const $checkoutInsurance = $('#checkout-insurance');
      const $keepProtectionPrice = $(
        '#protect-confirm-keep-price'
      );

      if (
        $checkoutInsurance.length &&
        $keepProtectionPrice.length
      ) {
        const currentInsurance =
          $checkoutInsurance.text().trim();

        if (currentInsurance !== '') {
          $keepProtectionPrice.text(currentInsurance);
        }
      }

      /*
       * Keep Protection ON until the customer confirms
       * removal through the existing popup.
       */
      checkbox.checked = true;

      syncAddonCard('protection');

      _protectConfirmLastFocus = checkbox;

      overlay.hidden = false;
      modal.hidden = false;

      document.body.style.overflow = 'hidden';

      setTimeout(() => {
        const button = modal.querySelector(
          '.protect-confirm-keep'
        );

        if (button) {
          button.focus();
        }
      }, 50);

      document.addEventListener(
        'keydown',
        _protectConfirmKeydown
      );
    }

    function closeProtectConfirm() {
      const overlay = document.getElementById('protect-confirm-overlay');
      const modal = document.getElementById('protect-confirm-modal');

      if (overlay) overlay.hidden = true;
      if (modal) modal.hidden = true;

      document.body.style.overflow = '';
      document.removeEventListener('keydown', _protectConfirmKeydown);

      if (_protectConfirmLastFocus) {
        _protectConfirmLastFocus.focus();
        _protectConfirmLastFocus = null;
      }
    }

    function keepProtection() {
      const checkbox =
        document.getElementById('protection');

      /*
       * Keep the checkbox ON.
       */
      if (checkbox) {

        checkbox.checked = true;
      }

      /*
       * Keep existing addon UI behavior.
       */
      syncAddonCard('protection');

      /*
       * =========================================================
       * KEEP CURRENT INSURANCE AMOUNT
       * =========================================================
       *
       * Always use the latest Insurance amount currently shown
       * in checkout.
       *
       * Do NOT use the old Blade-rendered modal value.
       */
      const $checkoutInsurance =
        $('#checkout-insurance');

      const $keepProtectionPrice =
        $('#protect-confirm-keep-price');

      if (
        $checkoutInsurance.length &&
        $keepProtectionPrice.length
      ) {

        const currentInsurance =
          $checkoutInsurance
          .text()
          .trim();

        if (
          currentInsurance !== ''
        ) {

          $keepProtectionPrice.text(
            currentInsurance
          );
        }
      }

      /*
       * =========================================================
       * EXISTING BACKEND FLOW
       * =========================================================
       *
       * Keep Protection must restore Insurance
       * in backend/session.
       */
      if (
        window.MaxaromaOnePageCheckout &&
        typeof window.MaxaromaOnePageCheckout
        .setShippingInsurance ===
        'function'
      ) {

        window.MaxaromaOnePageCheckout
          .setShippingInsurance('add');
      }
      //window.MaxaromaOnePageCheckout.refreshAfterpayWidget();
      syncAddonCard('protection');
      /*
       * Existing modal close behavior.
       */
      closeProtectConfirm();
    }

    function removeProtection() {
      if (
        window.MaxaromaOnePageCheckout &&
        typeof window.MaxaromaOnePageCheckout.setShippingInsurance === 'function'
      ) {
        window.MaxaromaOnePageCheckout.setShippingInsurance('remove');
      }
      syncAddonCard('protection');
      closeProtectConfirm();
    }

    function _protectConfirmKeydown(e) {
      if (e.key === 'Escape') {
        //keepProtection();
        return;
      }

      if (e.key !== 'Tab') return;

      const modal = document.getElementById('protect-confirm-modal');
      if (!modal) return;

      const focusable = modal.querySelectorAll(
        'button:not(:disabled), [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );

      if (!focusable.length) return;

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }

    function handleSignatureToggle(checkbox) {
      if (checkbox.checked) {
        if (
          window.MaxaromaOnePageCheckout &&
          typeof window.MaxaromaOnePageCheckout.setShippingSignature === 'function'
        ) {
          window.MaxaromaOnePageCheckout.setShippingSignature('add');
        }

        syncAddonCard('request-signature');
        return;
      }

      // OFF request: keep the checkbox ON until the customer confirms.
      const overlay = document.getElementById('signature-confirm-overlay');
      const modal = document.getElementById('signature-confirm-modal');

      if (!overlay || !modal) {
        checkbox.checked = true;
        syncAddonCard('request-signature');
        return;
      }

      checkbox.checked = true;
      syncAddonCard('request-signature');

      const state = window.MaxaromaCheckout?.totalsState || {};
      const messagePrice = document.getElementById('signature-confirm-message-price');
      const keepPrice = document.getElementById('signature-confirm-keep-price');
      const addonPrice = document.getElementById('signature-addon-price');

      // Use the existing checkout signature display state.
      let displayCharge = Number(state.signatureDisplayCharge);
      let isFree = state.signatureIsFree;

      // Fallback to the currently displayed add-on price if state is unavailable.
      if (!Number.isFinite(displayCharge) || displayCharge < 0) {
        const visiblePrice = addonPrice?.textContent?.trim() || '';
        const match = visiblePrice.match(/(\d+(?:\.\d{1,2})?)/);

        displayCharge = match ? Number(match[1]) : 0;
      }

      if (isFree === undefined || isFree === null) {
        isFree = addonPrice ?
          addonPrice.textContent.trim().toLowerCase() === 'free' :
          displayCharge <= 0;
      } else {
        isFree =
          isFree === true ||
          String(isFree).toLowerCase() === 'true' ||
          String(isFree).toLowerCase() === 'yes' ||
          String(isFree) === '1';
      }

      const formattedPrice = isFree ?
        'Free' :
        '$' + displayCharge.toFixed(2);

      if (messagePrice) {
        messagePrice.textContent = isFree ?
          'free of charge' :
          formattedPrice;
      }

      if (keepPrice) {
        keepPrice.textContent = formattedPrice;
      }

      // Preserve the existing signature state while the confirmation is open.
      const checkoutState = window.MaxaromaCheckout?.totalsState;

      if (checkoutState) {
        checkoutState.signatureApplied = true;
      }

      // Open the existing confirmation modal.
      _signatureConfirmLastFocus = checkbox;

      overlay.hidden = false;
      modal.hidden = false;
      document.body.style.overflow = 'hidden';

      setTimeout(function() {
        const button = modal.querySelector('.protect-confirm-keep');

        if (button) {
          button.focus();
        }
      }, 50);

      document.addEventListener('keydown', _signatureConfirmKeydown);
    }

    function handleGiftWrapToggle(checkbox) {
      if (checkbox.checked) {
        if (
          window.MaxaromaOnePageCheckout &&
          typeof window.MaxaromaOnePageCheckout.setGiftWrappingCharge === 'function'
        ) {
          window.MaxaromaOnePageCheckout.setGiftWrappingCharge();
        }
        syncAddonCard('request-gift-wrap');
        return;
      }
    }

    function closeSignatureConfirm() {
      const overlay = document.getElementById('signature-confirm-overlay');
      const modal = document.getElementById('signature-confirm-modal');

      if (overlay) overlay.hidden = true;
      if (modal) modal.hidden = true;

      document.body.style.overflow = '';
      document.removeEventListener('keydown', _signatureConfirmKeydown);

      if (_signatureConfirmLastFocus) {
        _signatureConfirmLastFocus.focus();
        _signatureConfirmLastFocus = null;
      }
    }

    function keepSignature() {
      const checkbox = document.getElementById('request-signature');
      if (checkbox) checkbox.checked = true;
      syncAddonCard('request-signature');
      closeSignatureConfirm();
    }

    function removeSignature() {
      if (
        window.MaxaromaOnePageCheckout &&
        typeof window.MaxaromaOnePageCheckout.setShippingSignature === 'function'
      ) {
        window.MaxaromaOnePageCheckout.setShippingSignature('remove');
      }
      syncAddonCard('request-signature');
      closeSignatureConfirm();
    }

    function _signatureConfirmKeydown(e) {
      if (e.key === 'Escape') {
        keepSignature();
        return;
      }

      if (e.key !== 'Tab') return;

      const modal = document.getElementById('signature-confirm-modal');
      if (!modal) return;

      const focusable = modal.querySelectorAll(
        'button:not(:disabled), [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );

      if (!focusable.length) return;

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }

    /*
     * Signature summary rows are part of the new Blade design. checkout.js
     * remains responsible for the AJAX request and backend totals; this listener
     * only maps that response onto the three existing summary surfaces.
     */
    document.addEventListener(
      'maxaroma:checkout-totals-updated',
      function(event) {

        const detail =
          event.detail || {};

        const response =
          detail.response || {};

        const totals =
          detail.totals || {};

        const signature =
          response.shippingSignature ||
          response.shipping_signature ||
          response.ShippingSignature || {};

        /*
         * =========================================================
         * SIGNATURE CHARGE
         * =========================================================
         *
         * Priority:
         *
         * 1. Current checkout state
         * 2. Event detail
         * 3. Dedicated Signature response
         *
         * Do not let an unrelated totals refresh replace an
         * already selected $3 Signature with Free.
         */

        const checkoutState =
          window.MaxaromaCheckout?.totalsState || {};

        let charge =
          parseFloat(
            checkoutState.signatureCharge ??
            detail.signatureCharge ??
            signature.charge ??
            signature.shipping_signature_charge ??
            signature.amount ??
            0
          );

        if (
          Number.isNaN(charge)
        ) {

          charge = 0;
        }

        /*
         * =========================================================
         * SIGNATURE APPLIED
         * =========================================================
         */

        const applied =
          checkoutState.signatureApplied === true ||
          detail.signatureApplied === true ||
          String(
            signature.applied || ''
          ).toLowerCase() === 'yes';

        const signatureText =
          charge > 0 ?
          '$' + charge.toFixed(2) :
          'Free';

        /*
         * =========================================================
         * SUMMARY ROWS
         * =========================================================
         */

        [
          'signature-row',
          'msum-signature-row',
          'review-signature-row'
        ].forEach(function(id) {

          const row =
            document.getElementById(id);

          if (!row) {
            return;
          }

          row.hidden = !applied;

          if (applied) {

            const value =
              row.querySelector(
                '.summary-row-value, span:last-child'
              );

            if (value) {

              value.textContent =
                signatureText;
            }
          }
        });

        /*
         * =========================================================
         * ADDON PRICE
         * =========================================================
         */

        const addonPrice =
          document.getElementById(
            'signature-addon-price'
          );

        if (addonPrice) {

          const displayChargeRaw =
            checkoutState.signatureDisplayCharge ??
            detail.signatureDisplayCharge ??
            signature.display_charge ??
            signature.displayCharge ??
            charge;

          let displayCharge =
            parseFloat(displayChargeRaw);

          if (
            Number.isNaN(displayCharge) ||
            displayCharge < 0
          ) {
            displayCharge = 0;
          }

          const displayFreeRaw =
            checkoutState.signatureIsFree ??
            detail.signatureDisplayIsFree ??
            signature.is_free ??
            signature.isFree;

          const isGenuinelyFree =
            displayFreeRaw !== undefined &&
            displayFreeRaw !== null ?
            (
              displayFreeRaw === true ||
              String(displayFreeRaw).toLowerCase() === 'true' ||
              String(displayFreeRaw).toLowerCase() === 'yes' ||
              String(displayFreeRaw) === '1'
            ) :
            displayCharge <= 0;

          addonPrice.textContent =
            isGenuinelyFree ?
            'Free' :
            '+$' + displayCharge.toFixed(2);
        }

        /*
         * =========================================================
         * CONFIRMATION MODAL
         * =========================================================
         *
         * IMPORTANT:
         *
         * Once the Remove Signature modal is OPEN, do NOT allow
         * a background checkout-totals event to overwrite its
         * current Keep Signature price.
         *
         * This is the actual fix for:
         *
         *     Keep Signature · $3.00
         *              ↓
         *     Keep Signature · Free
         */

        const confirmModal =
          document.getElementById(
            'signature-confirm-modal'
          );

        const confirmPrice =
          document.getElementById(
            'signature-confirm-keep-price'
          );

        const modalIsOpen =
          confirmModal &&
          !confirmModal.hidden;

        if (
          confirmPrice &&
          !modalIsOpen
        ) {

          confirmPrice.textContent =
            applied && charge > 0 ?
            signatureText :
            'Free';
        }

        /*
         * =========================================================
         * CHECKBOX
         * =========================================================
         *
         * Do not change the checkbox while the confirmation
         * modal is open.
         */

        const checkbox =
          document.getElementById(
            'request-signature'
          );

        if (
          checkbox &&
          !modalIsOpen
        ) {

          checkbox.checked =
            applied;

          syncAddonCard(
            'request-signature'
          );
        }

        /*
         * =========================================================
         * OTHER TOTALS
         * =========================================================
         */

        const subtotal =
          parseFloat(
            totals.SubTotal ?? 0
          );

        const discount =
          parseFloat(
            totals.TotalDiscount ?? 0
          );

        const shipping =
          parseFloat(
            detail.shipping ?? 0
          );

        const tax =
          parseFloat(
            detail.tax ?? 0
          );

        const netTotal =
          detail.netTotal != null ?
          parseFloat(
            detail.netTotal
          ) :
          parseFloat(
            totals.NetTotal ?? 0
          );

        const subtotalEl =
          document.getElementById(
            'summary-subtotal-value'
          );

        if (
          subtotalEl &&
          !Number.isNaN(subtotal)
        ) {

          subtotalEl.textContent =
            '$' +
            subtotal.toFixed(2);
        }

        const savingsEl =
          document.getElementById(
            'summary-savings-value'
          );

        if (
          savingsEl &&
          !Number.isNaN(discount)
        ) {

          savingsEl.textContent =
            '-$' +
            discount.toFixed(2);
        }

        const shippingEl =
          document.getElementById(
            'summary-shipping-value'
          );

        if (shippingEl) {

          shippingEl.textContent =
            shipping > 0 ?
            '$' +
            shipping.toFixed(2) :
            'Free';
        }

        const taxEl =
          document.getElementById(
            'summary-tax-value'
          );

        if (
          taxEl &&
          !Number.isNaN(tax)
        ) {

          taxEl.textContent =
            '$' +
            tax.toFixed(2);
        }

        const mobileShipping =
          document.getElementById(
            'msum-shipping-value'
          );

        if (mobileShipping) {

          mobileShipping.textContent =
            shipping > 0 ?
            '$' +
            shipping.toFixed(2) :
            'Free';
        }

        const mobileTax =
          document.getElementById(
            'msum-tax-value'
          );

        if (
          mobileTax &&
          !Number.isNaN(tax)
        ) {

          mobileTax.textContent =
            '$' +
            tax.toFixed(2);
        }

        const mobileDiscount =
          document.getElementById(
            'msum-discount-value'
          );

        if (
          mobileDiscount &&
          !Number.isNaN(discount)
        ) {

          mobileDiscount.textContent =
            '-$' +
            discount.toFixed(2);
        }
      }
    );
    // ── Address autocomplete intentionally disabled ───────────────
    // No verified Places/Address Suggestion API is part of the current checkout
    // contract. The shipping address fields themselves are already dynamic and
    // are consumed by checkout.js for shipping-method calculation.

    /* =========================================================
     * SHIPPING COUNTRY / STATE
     * Cached helper data; no extra country/state request.
     * ========================================================= */
    (function() {

      const states = {};

      function escapeHtml(value) {
        return $('<div>').text(
          value == null ? '' : value
        ).html();
      }

      function renderShippingState(country, currentValue) {

        const container =
          document.getElementById(
            'shipping-state-container'
          );

        if (!container) {
          return;
        }

        const value = currentValue || '';

        if (country === 'US') {

          let options = '';

          Object.keys(states).forEach(function(code) {

            options +=
              '<option value="' +
              escapeHtml(code) +
              '"' +
              (code === value ? ' selected' : '') +
              '>' +
              escapeHtml(states[code]) +
              '</option>';
          });

          container.innerHTML =
            '<div class="input-inner">' +
            '<select class="fl-input" ' +
            'id="shipping_state" ' +
            'name="shipping_state" ' +
            'autocomplete="shipping address-level1" ' +
            'aria-label="State" ' +
            'aria-required="true">' +
            options +
            '</select>' +
            '<label class="fl-label" ' +
            'for="shipping_state">State</label>' +
            '</div>';

          return;
        }

        container.innerHTML =
          '<div class="input-inner">' +
          '<input class="fl-input" ' +
          'type="text" ' +
          'id="shipping_state" ' +
          'name="shipping_state" ' +
          'value="' + escapeHtml(value) + '" ' +
          'placeholder="State / Province" ' +
          'autocomplete="shipping address-level1" ' +
          'aria-required="true">' +
          '<label class="fl-label" ' +
          'for="shipping_state">State / Province</label>' +
          '</div>';
      }

      function renderBillingState(country, currentValue) {

        const container =
          document.getElementById(
            'billing-state-container'
          );

        if (!container) {
          return;
        }

        const value = currentValue || '';

        if (country === 'US') {

          let options = '';

          Object.keys(states).forEach(function(code) {

            options +=
              '<option value="' +
              escapeHtml(code) +
              '"' +
              (code === value ? ' selected' : '') +
              '>' +
              escapeHtml(states[code]) +
              '</option>';
          });

          container.innerHTML =
            '<div class="input-inner">' +
            '<select class="fl-input" ' +
            'id="billing_state" ' +
            'name="billing_state" ' +
            'autocomplete="billing address-level1" ' +
            'aria-label="State" ' +
            'aria-required="true">' +
            options +
            '</select>' +
            '<label class="fl-label" ' +
            'for="billing_state">State</label>' +
            '</div>';

          return;
        }

        container.innerHTML =
          '<div class="input-inner">' +
          '<input class="fl-input" ' +
          'type="text" ' +
          'id="billing_state" ' +
          'name="billing_state" ' +
          'value="' + escapeHtml(value) + '" ' +
          'placeholder="State / Province" ' +
          'autocomplete="shipping address-level1" ' +
          'aria-required="true">' +
          '<label class="fl-label" ' +
          'for="billing_state">State / Province</label>' +
          '</div>';
      }

      document.addEventListener(
        'DOMContentLoaded',
        function() {

          const country =
            document.getElementById(
              'shipping_country'
            );

          if (!country) {
            return;
          }

          country.addEventListener(
            'change',
            function() {

              const state =
                document.getElementById(
                  'shipping_state'
                );

              renderShippingState(
                this.value,
                state ? state.value : ''
              );
            }
          );

          const billing_country =
            document.getElementById(
              'billing_country'
            );

          if (!billing_country) {
            return;
          }

          billing_country.addEventListener(
            'change',
            function() {

              const state =
                document.getElementById(
                  'billing_state'
                );

              renderBillingState(
                this.value,
                state ? state.value : ''
              );
            }
          );
        }
      );

    })();