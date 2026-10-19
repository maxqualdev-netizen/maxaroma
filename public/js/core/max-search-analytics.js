/**
 * Maxaroma search analytics (search + click only).
 * Sends events to the shared analytics server with project: "max"
 * → stored in search_events_max / click_events_max.
 *
 * Dedupes: autocomplete popup → search page with the same query does not
 * POST a second search (keeps the same QueryId for click attribution).
 */
(function (window, document) {
  "use strict";

  var ENDPOINT =
    (window.MAX_ANALYTICS_ENDPOINT ||
      "https://lianne-barefaced-soberly.ngrok-free.dev").replace(/\/$/, "");
  // Same-origin proxy (UAT-safe). Set window.MAX_ANALYTICS_DIRECT = true to hit ngrok from the browser.
  var PROXY_BASE = (function () {
    if (window.MAX_ANALYTICS_DIRECT) return "";
    if (window.MAX_ANALYTICS_PROXY) {
      return String(window.MAX_ANALYTICS_PROXY).replace(/\/$/, "");
    }
    if (typeof site_url !== "undefined" && site_url) {
      return String(site_url).replace(/\/?$/, "/") + "max_analytics";
    }
    return "/max_analytics";
  })();
  var PROJECT = "max";
  var STORAGE_USER = "max_analytics_user";
  var STORAGE_QID = "max_analytics_qid";
  var STORAGE_LAST = "max_analytics_last_track";
  /** Skip re-posting the same query within this window (popup → PLP). */
  var DEDUPE_MS = 2 * 60 * 1000;

  function uuid() {
    if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
      var r = (Math.random() * 16) | 0;
      var v = c === "x" ? r : (r & 0x3) | 0x8;
      return v.toString(16);
    });
  }

  function normalizeQueryKey(q) {
    return String(q || "")
      .trim()
      .toLowerCase()
      .replace(/[-_]+/g, " ")
      .replace(/\s+/g, " ");
  }

  function getLastTrack() {
    try {
      var raw = sessionStorage.getItem(STORAGE_LAST);
      if (!raw) return null;
      var o = JSON.parse(raw);
      if (!o || !o.q || !o.ts) return null;
      return o;
    } catch (e) {
      return null;
    }
  }

  function setLastTrack(q, brand) {
    try {
      sessionStorage.setItem(
        STORAGE_LAST,
        JSON.stringify({
          q: normalizeQueryKey(q),
          ts: Date.now(),
          hasBrand: !!(brand && String(brand).trim()),
        })
      );
    } catch (e) {}
  }

  /** True if this exact query was already sent to analytics recently. */
  function wasTrackedRecently(q) {
    var last = getLastTrack();
    if (!last) return false;
    if (Date.now() - last.ts > DEDUPE_MS) return false;
    return last.q === normalizeQueryKey(q);
  }

  function getUserToken() {
    try {
      var t = localStorage.getItem(STORAGE_USER);
      if (!t) {
        t = "max_" + uuid();
        localStorage.setItem(STORAGE_USER, t);
      }
      return t;
    } catch (e) {
      return "max_anon_" + uuid();
    }
  }

  function getQueryId(forceNew) {
    try {
      if (!forceNew) {
        var existing = sessionStorage.getItem(STORAGE_QID);
        if (existing) return existing;
      }
      var id = "mq_" + uuid().replace(/-/g, "").slice(0, 24);
      sessionStorage.setItem(STORAGE_QID, id);
      return id;
    } catch (e) {
      return "mq_" + Date.now();
    }
  }

  function resolvePostUrl(path) {
    return PROXY_BASE
      ? PROXY_BASE + String(path || "").replace(/^\/analytics/, "")
      : ENDPOINT + path;
  }

  function post(path, body) {
    try {
      if (typeof IS_CRAWLER_BOT !== "undefined" && String(IS_CRAWLER_BOT) === "1") {
        return;
      }
      var payload = Object.assign({ project: PROJECT }, body || {});
      var json = JSON.stringify(payload);
      var url = resolvePostUrl(path);

      // sendBeacon survives PDP navigation better than fetch (UAT listing → detail).
      if (typeof navigator !== "undefined" && typeof navigator.sendBeacon === "function") {
        try {
          var blob = new Blob([json], { type: "application/json" });
          if (navigator.sendBeacon(url, blob)) {
            if (window.MAX_ANALYTICS_DEBUG) {
              console.log("[MaxAnalytics] beacon", path, payload);
            }
            return;
          }
        } catch (beaconErr) {}
      }

      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "ngrok-skip-browser-warning": "1",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: json,
        keepalive: true,
        mode: "cors",
        credentials: "same-origin",
      })
        .then(function (r) {
          if (window.MAX_ANALYTICS_DEBUG) {
            console.log("[MaxAnalytics] fetch", path, r.status);
          }
        })
        .catch(function (err) {
          console.log("Max analytics error:", err);
        });
    } catch (e) {
      console.log("Max analytics error:", e);
    }
  }

  function detectDevice() {
    try {
      var ua = navigator.userAgent || "";
      if (/iPad|Tablet/i.test(ua)) return "tablet";
      if (/Mobi|Android.*Mobile|iPhone|iPod/i.test(ua)) return "mobile";
      return "desktop";
    } catch (e) {
      return "desktop";
    }
  }

  function detectLanding() {
    try {
      return (location.pathname || "/") + (location.search || "");
    } catch (e) {
      return null;
    }
  }

  /**
   * Brand from Maxaroma NLP / Meili catalog (injected into autocomplete or PLP).
   * No client-side house list — catalog is the source of truth.
   */
  function readNlpBrandFromDom(root) {
    try {
      var scope = root && root.querySelector ? root : document;
      var el =
        scope.querySelector("#max-analytics-nlp") ||
        document.getElementById("max-analytics-nlp") ||
        document.getElementById("nlp-tracked-brand");
      if (!el) return { brand: null, isNlp: false };
      var brand = (el.getAttribute("data-brand") || el.value || "").trim();
      var isNlp =
        el.getAttribute("data-nlp") === "1" ||
        el.getAttribute("data-nlp") === "true";
      return { brand: brand || null, isNlp: isNlp };
    } catch (e) {
      return { brand: null, isNlp: false };
    }
  }

  /**
   * Track a search.
   * @param {string} query
   * @param {object} opts  { resultCount, articleCount, isNlp, isSearch, newQueryId, force, brand }
   */
  function trackSearch(query, opts) {
    opts = opts || {};
    // Slug/URL hyphens ("Bond-No.9-…") → spaces for stable query + brand matching
    var q = String(query || "")
      .trim()
      .replace(/[-_]+/g, " ")
      .replace(/\s+/g, " ")
      .trim();
    if (!q) return;

    var fromDom = readNlpBrandFromDom(
      opts.domRoot || document.getElementById("autoSuggesstion") || document
    );
    var brand = opts.brand || fromDom.brand || null;
    var isNlp = opts.isNlp != null ? !!opts.isNlp : fromDom.isNlp;

    // Popup often tracks before catalog brand is known; PLP must be allowed to fill brand.
    var last = getLastTrack();
    if (!opts.force && wasTrackedRecently(q)) {
      if (!brand || (last && last.hasBrand)) {
        return getQueryId(false);
      }
    }

    var qid = getQueryId(!!opts.newQueryId);
    post("/analytics/search", {
      UserToken: getUserToken(),
      AuthToken: getUserToken(),
      query: q,
      ResultCount: opts.resultCount != null ? opts.resultCount : 0,
      ArticleCount: opts.articleCount != null ? opts.articleCount : 0,
      QueryId: qid,
      is_search: opts.isSearch != null ? opts.isSearch : 1,
      is_nlp: isNlp ? 1 : 0,
      device: detectDevice(),
      landing_page: detectLanding(),
      brand: brand || undefined,
    });
    setLastTrack(q, brand);
    return qid;
  }

  /**
   * Track a product click from search.
   * @param {string|number} objectId  sku / uid
   * @param {number} position  1-based
   * @param {string} [queryId]
   */
  function trackClick(objectId, position, queryId) {
    var oid = objectId != null ? String(objectId).trim() : "";
    if (!oid) return;
    if (window.MAX_ANALYTICS_DEBUG) {
      console.log("[MaxAnalytics] trackClick", oid, position);
    }
    post("/analytics/click", {
      QueryId: queryId || getQueryId(false),
      ObjectID: oid,
      Position: position != null ? position : 0,
    });
  }

  /**
   * Called from product card onmousedown (inline) — most reliable on UAT before PDP nav.
   * @param {Element} el
   */
  function trackProductClickFromEl(el) {
    try {
      if (!el || !el.getAttribute) return;
      var card = el.closest
        ? el.closest(".product, .product-box, [data-analytics-sku], [data-product-id]")
        : null;
      var sku = (
        el.getAttribute("data-analytics-sku") ||
        el.getAttribute("data-sku") ||
        (card && (card.getAttribute("data-analytics-sku") || card.getAttribute("data-sku"))) ||
        el.getAttribute("data-product-id") ||
        (card && card.getAttribute("data-product-id")) ||
        ""
      ).trim();
      if (!sku) return;
      var pos = parseInt(
        el.getAttribute("data-analytics-position") ||
          (card && card.getAttribute("data-analytics-position")) ||
          "0",
        10
      );
      if (!pos || pos < 1) {
        var list = card && card.parentElement ? card.parentElement.children : [];
        for (var i = 0; i < list.length; i++) {
          if (list[i] === card) {
            pos = i + 1;
            break;
          }
        }
      }
      trackClick(sku, pos || 1);
    } catch (e) {
      console.log("Max analytics trackProductClickFromEl error:", e);
    }
  }

  /** Bind clicks on PLP / autocomplete product links (mousedown so PDP nav doesn't cancel). */
  function bindProductClicks(root) {
    var scope = root || document;
    var lastSent = { sku: "", ts: 0 };

    function handleProductNav(ev) {
      try {
        var t = ev.target;
        if (!t || !t.closest) return;
        var a = t.closest("a[href], .prodaddcart, [data-analytics-sku]");
        if (!a) return;

        var href = (a.getAttribute("href") || "").trim().toLowerCase();
        var isJs = !href || href === "#" || href.indexOf("javascript:") === 0;
        var isQuick =
          a.classList.contains("lable-quickview") ||
          a.classList.contains("label-cart-outstock") ||
          a.classList.contains("svg-h");
        if (isJs && isQuick) return;
        if (isJs && !a.classList.contains("prodaddcart") && !a.getAttribute("data-analytics-sku")) {
          return;
        }

        var card = a.closest(
          ".product, .product-box, .prodbox, .productbox, [data-analytics-sku], [data-product-id], li"
        );
        var sku = (
          (card && (card.getAttribute("data-analytics-sku") || card.getAttribute("data-sku"))) ||
          a.getAttribute("data-analytics-sku") ||
          a.getAttribute("data-sku") ||
          a.getAttribute("data-product") ||
          a.getAttribute("data-product-id") ||
          (card && card.getAttribute("data-product-id")) ||
          ""
        ).trim();
        if (!sku) return;

        var now = Date.now();
        if (lastSent.sku === sku && now - lastSent.ts < 1000) return;
        lastSent = { sku: sku, ts: now };

        var pos = parseInt(
          a.getAttribute("data-analytics-position") ||
            (card && card.getAttribute("data-analytics-position")) ||
            "0",
          10
        );
        if (!pos || pos < 1) {
          var list = card && card.parentElement ? card.parentElement.children : [];
          for (var i = 0; i < list.length; i++) {
            if (list[i] === card) {
              pos = i + 1;
              break;
            }
          }
        }
        trackClick(sku, pos || 1);
      } catch (e) {
        console.log("Max analytics click handler error:", e);
      }
    }

    scope.addEventListener("mousedown", handleProductNav, true);
    scope.addEventListener("click", handleProductNav, true);
  }

  /**
   * Call after PLP loads with keyword + total.
   * Skips POST when this query was just tracked from the autocomplete popup.
   * @param {string} [brand]  Catalog brand from NLP (optional)
   */
  function trackListingSearch(query, total, isNlp, brand) {
    var sameAsRecent = wasTrackedRecently(query);
    var fromDom = readNlpBrandFromDom(document);
    return trackSearch(query, {
      resultCount: total || 0,
      isNlp: isNlp != null ? !!isNlp : fromDom.isNlp,
      brand: brand || fromDom.brand || undefined,
      // Keep popup QueryId when this is the same query; new id only for a fresh listing search
      newQueryId: !sameAsRecent,
    });
  }

  window.MaxSearchAnalytics = {
    endpoint: ENDPOINT,
    proxy: PROXY_BASE,
    project: PROJECT,
    trackSearch: trackSearch,
    trackClick: trackClick,
    trackListingSearch: trackListingSearch,
    trackProductClickFromEl: trackProductClickFromEl,
    bindProductClicks: bindProductClicks,
    getQueryId: getQueryId,
    getUserToken: getUserToken,
  };
  // Global alias for inline onmousedown on product cards
  window.maxAnalyticsTrackProductClick = trackProductClickFromEl;

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      bindProductClicks(document);
      if (window.MAX_ANALYTICS_DEBUG) {
        console.log("[MaxAnalytics] ready", { proxy: PROXY_BASE, endpoint: ENDPOINT });
      }
    });
  } else {
    bindProductClicks(document);
    if (window.MAX_ANALYTICS_DEBUG) {
      console.log("[MaxAnalytics] ready", { proxy: PROXY_BASE, endpoint: ENDPOINT });
    }
  }
})(window, document);
