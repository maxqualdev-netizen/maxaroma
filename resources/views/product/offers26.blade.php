@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/offers26.css') }}?v=2026.1">
<style>
  /* ==========================================================================
     ADA / Accessibility & Performance Enhancements
     ========================================================================== */
  /* Focus Visible styling for all interactive elements */
  .filter-pill:focus-visible,
  .sort-select:focus-visible,
  button.lable-quickview:focus-visible,
  .clear-filters-btn:focus-visible,
  .viewall:focus-visible,
  .brand-name:focus-visible,
  .product-tital a:focus-visible,
  button.avl-round-btn:focus-visible,
  .slick-dots button:focus-visible {
    outline: 2px solid #000000 !important;
    outline-offset: 2px !important;
  }

  /* Accessible Button Reset for Quick Add */
  button.lable-quickview {
    display: block;
    width: 100%;
    border: 1px solid #000000;
    background-color: #ffffff;
    color: #000000;
    font-size: 11.5px;
    font-family: inherit;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 8px 0;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    margin: 10px 0 0;
    text-decoration: none;
    box-sizing: border-box;
    line-height: normal;
  }

  button.lable-quickview:hover {
    background-color: #333333;
    border-color: #333333;
    color: #ffffff;
  }

  /* Accessible Options Tooltip Button */
  button.avl-round-btn {
    background: none;
    border: none;
    padding: 0;
    margin: 0 0 0 4px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    vertical-align: middle;
  }

  /* Product Title Heading Reset inside Product Cards */
  h3.product-tital {
    font-size: inherit;
    font-weight: inherit;
    margin: 0;
    line-height: inherit;
  }

  /* Accessible Was/Now Price Formatting */
  del.price-was {
    text-decoration: line-through;
    color: #595959;
  }

  ins.price-now {
    text-decoration: none;
    font-weight: 700;
  }

  /* Contrast Improvements (WCAG AA 4.5:1+) */
  .hero-cd-lbl {
    color: #595959 !important;
  }

  /* Accessible Screen-Reader-Only Utility */
  .visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
  }

  /* Prefers Reduced Motion support */
  @media (prefers-reduced-motion: reduce) {
    .hero-countdown-pulse {
      animation: none !important;
    }
    .filter-pill,
    button.lable-quickview,
    .sort-select {
      transition: none !important;
    }
  }
</style>

<main class="clearfix" role="main" id="main-content" tabindex="-1" aria-label="Offers and Promotions">
  <!-- Headline Hero Banner -->
  <section class="hero-section" aria-labelledby="hero-title">
    <div class="container hero-inner">
      <div class="hero-copy">
        <div class="hero-kicker">THIS WEEK'S HEADLINE OFFER</div>
        <h1 class="hero-title" id="hero-title">Fall Preview Event — up to 33% off select icons</h1>
        <p class="hero-desc">Real markdowns, stated in plain text. Every saving equals list price minus sale price.</p>
      </div>
      <div class="hero-countdown-box" role="timer" aria-label="Offer countdown timer">
        <div class="hero-countdown-header">
          <span class="hero-countdown-pulse" aria-hidden="true"></span>
          <span class="hero-countdown-label">ENDS IN</span>
        </div>
        <div class="hero-cd" aria-hidden="true">
          <div class="hero-cd-box">
            <span class="hero-cd-num" id="cd-days">02</span>
            <span class="hero-cd-lbl">DAYS</span>
          </div>
          <span class="hero-cd-sep">:</span>
          <div class="hero-cd-box">
            <span class="hero-cd-num" id="cd-hours">13</span>
            <span class="hero-cd-lbl">HRS</span>
          </div>
          <span class="hero-cd-sep">:</span>
          <div class="hero-cd-box">
            <span class="hero-cd-num" id="cd-mins">11</span>
            <span class="hero-cd-lbl">MIN</span>
          </div>
          <span class="hero-cd-sep">:</span>
          <div class="hero-cd-box">
            <span class="hero-cd-num" id="cd-secs">45</span>
            <span class="hero-cd-lbl">SEC</span>
          </div>
        </div>
        <span class="visually-hidden" id="countdown-val">2 days 13 hours 11 minutes left</span>
      </div>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="deals-section" aria-labelledby="deals-catalog-heading">
    <div class="container filter-bar-container">
      <h2 class="visually-hidden" id="deals-catalog-heading">Offers and Promotions Catalog</h2>
      <div class="filter-bar-row" role="region" aria-label="Filter and sort offers">
        <!-- Discount Depth Filter -->
        <div class="filter-group filter-group-discount" role="group" aria-labelledby="discount-label">
          <span class="filter-group-label" id="discount-label">DISCOUNT</span>
          <div class="filter-pills-wrap">
            <button type="button" class="filter-pill" data-filter="depth" data-val="10 – 25%" aria-pressed="false"
              aria-label="Filter by 10 to 25 percent discount">10 – 25%</button>
            <button type="button" class="filter-pill" data-filter="depth" data-val="25 – 50%" aria-pressed="false"
              aria-label="Filter by 25 to 50 percent discount">25 – 50%</button>
            <button type="button" class="filter-pill" data-filter="depth" data-val="50%+" aria-pressed="false"
              aria-label="Filter by 50 percent and above discount">50%+</button>
          </div>
        </div>

        <span class="filter-divider dsk" aria-hidden="true"></span>

        <!-- Offer Type Filter -->
        <div class="filter-group filter-group-type" role="group" aria-labelledby="type-label">
          <span class="filter-group-label" id="type-label">OFFER TYPE</span>
          <div class="filter-pills-wrap">
            <button type="button" class="filter-pill" data-filter="type" data-val="Price Cut" aria-pressed="false"
              aria-label="Filter by offer type: Price Cut">Price Cut</button>
            <button type="button" class="filter-pill" data-filter="type" data-val="Free Gift" aria-pressed="false"
              aria-label="Filter by offer type: Free Gift">Free Gift</button>
            <button type="button" class="filter-pill" data-filter="type" data-val="Limited Quantities" aria-pressed="false"
              aria-label="Filter by offer type: Limited Quantities">Limited Quantities</button>
            <button type="button" class="filter-pill" data-filter="type" data-val="New Drop" aria-pressed="false"
              aria-label="Filter by offer type: New Drop">New Drop</button>
          </div>
        </div>

        <!-- Sort Select -->
        <div class="sort-container">
          <label for="sort-select" class="sort-label">Sort</label>
          <select id="sort-select" class="sort-select" aria-label="Sort offers by">
            <option value="Ending Soonest">Ending Soonest</option>
            <option value="Biggest Saving">Biggest Saving</option>
            <option value="New">New</option>
          </select>
        </div>
      </div>

      <!-- Live region for screen readers -->
      <div class="visually-hidden" aria-live="polite" aria-atomic="true" id="filter-status">Showing 8 offers</div>

      <!-- Deals Catalog Grid (Pre-sorted by default Ending Soonest for zero CLS on initial paint) -->
      <div class="deals-grid newproduct-box" id="deals-grid" aria-label="Offers List">
        <!-- 1. Angels' Share (18h) -->
        <div class="product" sku="UP0616943549089" productname="Angels' Share Eau de Parfum" brand="Kilian Paris" role="group" aria-label="Product: Angels' Share Eau de Parfum by Kilian Paris">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Angels' Share Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="eager" fetchpriority="high" decoding="async" alt="Angels' Share Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">Low Stock</span>
              <span class="cornar discount">−20%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">KILIAN PARIS</a>
            <h3 class="product-tital">
              <a href="index.html">Angels' Share Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.7 out of 5 stars from 812 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:94%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.7 (812)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$320</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$256</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="pcard-ends product-ends-label">Ends in 18h 12m</div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Angels' Share Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 2. Lost Cherry (31h) -->
        <div class="product" sku="UP0616943549089" productname="Lost Cherry Eau de Parfum" brand="Tom Ford" role="group" aria-label="Product: Lost Cherry Eau de Parfum by Tom Ford">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Lost Cherry Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="eager" fetchpriority="high" decoding="async" alt="Lost Cherry Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">−20%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">TOM FORD</a>
            <h3 class="product-tital">
              <a href="index.html">Lost Cherry Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.5 out of 5 stars from 690 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:90%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.5 (690)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$395</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$316</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl no-dot">Samples from $42</div>
              <div class="pcard-ends product-ends-label">Ends in 31h 12m</div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Lost Cherry Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 3. Reflection Man (47h) -->
        <div class="product" sku="UP0616943549089" productname="Reflection Man Eau de Parfum" brand="Amouage" role="group" aria-label="Product: Reflection Man Eau de Parfum by Amouage">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Reflection Man Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="eager" decoding="async" alt="Reflection Man Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">−30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">AMOUAGE</a>
            <h3 class="product-tital">
              <a href="index.html">Reflection Man Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.7 out of 5 stars from 421 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:94%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.7 (421)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$365</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$254</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="pcard-ends product-ends-label">Ends in 47h 12m</div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Reflection Man Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 4. Aventus (61h) -->
        <div class="product" sku="UP0616943549089" productname="Aventus Eau de Parfum" brand="Creed" role="group" aria-label="Product: Aventus Eau de Parfum by Creed">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Aventus Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="eager" decoding="async" alt="Aventus Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">−33%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">CREED</a>
            <h3 class="product-tital">
              <a href="index.html">Aventus Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 1204 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (1204)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$510</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$340</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl no-dot">Samples from $58</div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Aventus Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 5. Layton (88h) -->
        <div class="product" sku="UP0616943549089" productname="Layton Eau de Parfum" brand="Parfums de Marly" role="group" aria-label="Product: Layton Eau de Parfum by Parfums de Marly">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Layton Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Layton Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">PARFUMS DE MARLY</a>
            <h3 class="product-tital">
              <a href="index.html">Layton Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 1450 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (1450)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$365</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl sc-interp">Free 10ml travel spray — $107 value</div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Layton Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 6. Hacivat (95h) -->
        <div class="product" sku="UP0616943549089" productname="Hacivat Extrait de Parfum" brand="Nishane" role="group" aria-label="Product: Hacivat Extrait de Parfum by Nishane">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Hacivat Extrait de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Hacivat Extrait de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">−20%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">NISHANE</a>
            <h3 class="product-tital">
              <a href="index.html">Hacivat Extrait de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 733 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (733)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$215</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$172</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap"></div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Hacivat Extrait de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 7. Cedrat Boise (120h) -->
        <div class="product" sku="UP0616943549089" productname="Cedrat Boise Eau de Parfum" brand="Mancera" role="group" aria-label="Product: Cedrat Boise Eau de Parfum by Mancera">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Cedrat Boise Eau de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Cedrat Boise Eau de Parfum bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">−30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">MANCERA</a>
            <h3 class="product-tital">
              <a href="index.html">Cedrat Boise Eau de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.6 out of 5 stars from 1102 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:92%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.6 (1102)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <del class="price-was"><span class="visually-hidden">Original price: </span>$180</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$126</ins>
              </div>
            </div>
            <div class="pcard-meta-wrap"></div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Cedrat Boise Eau de Parfum to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 8. Gris Charnel (200h) -->
        <div class="product" sku="UP0616943549089" productname="Gris Charnel Extrait de Parfum" brand="BDK Parfums" role="group" aria-label="Product: Gris Charnel Extrait de Parfum by BDK Parfums">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Gris Charnel Extrait de Parfum" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Gris Charnel Extrait de Parfum bottle" width="185" height="185">
              <span class="cornar">New</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">BDK PARFUMS</a>
            <h3 class="product-tital">
              <a href="index.html">Gris Charnel Extrait de Parfum</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 89 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (89)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$230</span>
              </div>
            </div>
            <div class="pcard-meta-wrap"></div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Gris Charnel Extrait de Parfum to cart">QUICK ADD</button>
          </div>
        </div>
      </div>

      <div class="no-deals-notice" id="no-results-box" style="display: none;" aria-live="polite">
        No current offers match these filters. <button type="button" class="clear-filters-btn"
          id="clear-filters-btn">Clear filters</button>
      </div>
    </div>
  </section>

  <!-- Xerjoff Brand Spotlight -->
  <section class="spotlight-section" aria-labelledby="xerjoff-heading">
    <div class="container spotlight-inner">
      <div class="spotlight-card">
        <div class="pt-3 text-center pb-4">
          <div class="main-heading">
            <h2 class="sliderHeading" id="xerjoff-heading"><span>XERJOFF</span></h2>
            <p>Up to 40% off Xerjoff · Ends Aug 10</p>
            <a href="category.html" class="viewall line-hover" aria-label="View all Xerjoff fragrances">View All</a>
          </div>
        </div>
        <div class="spotlight-rail xerjoff-slider newproduct-box slick-cls slick-dotted-nw space-dots-top" role="region" aria-label="Xerjoff Offers Carousel" id="xerjoff-rail">
          <!-- 1. Naxos -->
          <div class="product" sku="UP0616943549089" productname="Naxos Eau de Parfum" brand="Xerjoff" role="group" aria-label="Product: Naxos Eau de Parfum by Xerjoff" style="width: 100%; display: inline-block;">
            <div class="product-image-wrapper">
              <a href="category.html" class="p-thumb" title="Naxos Eau de Parfum" tabindex="-1" aria-hidden="true">
                <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Naxos Eau de Parfum bottle" width="185" height="185">
                <span class="cornar">MAX2DAY</span>
                <span class="cornar discount">−25%</span>
              </a>
            </div>
            <div class="product-name">
              <a class="brand-name" href="category.html">XERJOFF</a>
              <h3 class="product-tital">
                <a href="category.html">Naxos Eau de Parfum</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.7 out of 5 stars from 412 reviews">
                <div class="pcard-rating-inner">
                  <span class="pcard-stars" aria-hidden="true">
                    <span class="pcard-stars-bg">★★★★★</span>
                    <span class="pcard-stars-fill" style="width:94%">★★★★★</span>
                  </span>
                  <span class="pcard-rating-count" aria-hidden="true">4.7 (412)</span>
                </div>
              </div>
              <div class="pb-2 product-price">
                <div class="price">
                  <del class="price-was"><span class="visually-hidden">Original price: </span>$255</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$191</ins>
                </div>
              </div>
              <div class="pcard-meta-wrap">
                <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Naxos Eau de Parfum to cart">QUICK ADD</button>
            </div>
          </div>

          <!-- 2. Erba Pura -->
          <div class="product" sku="UP0616943549089" productname="Erba Pura Eau de Parfum" brand="Xerjoff" role="group" aria-label="Product: Erba Pura Eau de Parfum by Xerjoff" style="width: 100%; display: inline-block;">
            <div class="product-image-wrapper">
              <a href="category.html" class="p-thumb" title="Erba Pura Eau de Parfum" tabindex="-1" aria-hidden="true">
                <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Erba Pura Eau de Parfum bottle" width="185" height="185">
                <span class="cornar">MAX2DAY</span>
                <span class="cornar discount">−30%</span>
              </a>
            </div>
            <div class="product-name">
              <a class="brand-name" href="category.html">XERJOFF</a>
              <h3 class="product-tital">
                <a href="category.html">Erba Pura Eau de Parfum</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.6 out of 5 stars from 388 reviews">
                <div class="pcard-rating-inner">
                  <span class="pcard-stars" aria-hidden="true">
                    <span class="pcard-stars-bg">★★★★★</span>
                    <span class="pcard-stars-fill" style="width:92%">★★★★★</span>
                  </span>
                  <span class="pcard-rating-count" aria-hidden="true">4.6 (388)</span>
                </div>
              </div>
              <div class="pb-2 product-price">
                <div class="price">
                  <del class="price-was"><span class="visually-hidden">Original price: </span>$260</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$182</ins>
                </div>
              </div>
              <div class="pcard-meta-wrap">
                <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Erba Pura Eau de Parfum to cart">QUICK ADD</button>
            </div>
          </div>

          <!-- 3. Amber Star -->
          <div class="product" sku="UP0616943549089" productname="Amber Star Eau de Parfum" brand="Xerjoff" role="group" aria-label="Product: Amber Star Eau de Parfum by Xerjoff" style="width: 100%; display: inline-block;">
            <div class="product-image-wrapper">
              <a href="category.html" class="p-thumb" title="Amber Star Eau de Parfum" tabindex="-1" aria-hidden="true">
                <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Amber Star Eau de Parfum bottle" width="185" height="185">
                <span class="cornar">MAX2DAY</span>
                <span class="cornar discount">−40%</span>
              </a>
            </div>
            <div class="product-name">
              <a class="brand-name" href="category.html">XERJOFF</a>
              <h3 class="product-tital">
                <a href="category.html">Amber Star Eau de Parfum</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.5 out of 5 stars from 143 reviews">
                <div class="pcard-rating-inner">
                  <span class="pcard-stars" aria-hidden="true">
                    <span class="pcard-stars-bg">★★★★★</span>
                    <span class="pcard-stars-fill" style="width:90%">★★★★★</span>
                  </span>
                  <span class="pcard-rating-count" aria-hidden="true">4.5 (143)</span>
                </div>
              </div>
              <div class="pb-2 product-price">
                <div class="price">
                  <del class="price-was"><span class="visually-hidden">Original price: </span>$350</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$210</ins>
                </div>
              </div>
              <div class="pcard-meta-wrap">
                <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Amber Star Eau de Parfum to cart">QUICK ADD</button>
            </div>
          </div>

          <!-- 4. Accento -->
          <div class="product" sku="UP0616943549089" productname="Accento Eau de Parfum" brand="Xerjoff" role="group" aria-label="Product: Accento Eau de Parfum by Xerjoff" style="width: 100%; display: inline-block;">
            <div class="product-image-wrapper">
              <a href="category.html" class="p-thumb" title="Accento Eau de Parfum" tabindex="-1" aria-hidden="true">
                <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Accento Eau de Parfum bottle" width="185" height="185">
                <span class="cornar">MAX2DAY</span>
                <span class="cornar discount">−30%</span>
              </a>
            </div>
            <div class="product-name">
              <a class="brand-name" href="category.html">XERJOFF</a>
              <h3 class="product-tital">
                <a href="category.html">Accento Eau de Parfum</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.6 out of 5 stars from 205 reviews">
                <div class="pcard-rating-inner">
                  <span class="pcard-stars" aria-hidden="true">
                    <span class="pcard-stars-bg">★★★★★</span>
                    <span class="pcard-stars-fill" style="width:92%">★★★★★</span>
                  </span>
                  <span class="pcard-rating-count" aria-hidden="true">4.6 (205)</span>
                </div>
              </div>
              <div class="pb-2 product-price">
                <div class="price">
                  <del class="price-was"><span class="visually-hidden">Original price: </span>$250</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$175</ins>
                </div>
              </div>
              <div class="pcard-meta-wrap">
                <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Accento Eau de Parfum to cart">QUICK ADD</button>
            </div>
          </div>

          <!-- 5. Torino21 (Distinct 5th Fragrance, avoiding redundant duplicate) -->
          <div class="product" sku="UP0616943549089" productname="Torino21 Eau de Parfum" brand="Xerjoff" role="group" aria-label="Product: Torino21 Eau de Parfum by Xerjoff" style="width: 100%; display: inline-block;">
            <div class="product-image-wrapper">
              <a href="category.html" class="p-thumb" title="Torino21 Eau de Parfum" tabindex="-1" aria-hidden="true">
                <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Torino21 Eau de Parfum bottle" width="185" height="185">
                <span class="cornar">MAX2DAY</span>
                <span class="cornar discount">−25%</span>
              </a>
            </div>
            <div class="product-name">
              <a class="brand-name" href="category.html">XERJOFF</a>
              <h3 class="product-tital">
                <a href="category.html">Torino21 Eau de Parfum</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 295 reviews">
                <div class="pcard-rating-inner">
                  <span class="pcard-stars" aria-hidden="true">
                    <span class="pcard-stars-bg">★★★★★</span>
                    <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                  </span>
                  <span class="pcard-rating-count" aria-hidden="true">4.8 (295)</span>
                </div>
              </div>
              <div class="pb-2 product-price">
                <div class="price">
                  <del class="price-was"><span class="visually-hidden">Original price: </span>$260</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>$195</ins>
                </div>
              </div>
              <div class="pcard-meta-wrap">
                <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Torino21 Eau de Parfum to cart">QUICK ADD</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Discovery Sets & Sampling -->
  <section class="discovery-section topspacing" aria-labelledby="discovery-heading">
    <div class="container discovery-inner">
      <div class="pt-3 text-center pb-4">
        <div class="main-heading">
          <h2 id="discovery-heading">Discovery Sets &amp; Sampling</h2>
          <p>Our exclusive maisons are never discounted — they're discovered. Each set includes a $30 sample credit toward a full bottle.</p>
          <a href="category.html" class="viewall line-hover" aria-label="View all discovery sets">All Discovery Sets</a>
        </div>
      </div>
      <div class="discovery-rail discovery-sampling-slider newproduct-box slick-cls slick-dotted-nw space-dots-top" role="region" aria-label="Discovery Sets Carousel" id="discovery-rail">
        <!-- 1. House of Dastan -->
        <div class="product" sku="UP0616943549089" productname="Discovery Set — Six Extraits, 2ml each" brand="House of Dastan" role="group" aria-label="Product: Discovery Set — Six Extraits, 2ml each by House of Dastan" style="width: 100%; display: inline-block;">
          <div class="product-image-wrapper">
            <a href="category.html" class="p-thumb" title="Discovery Set — Six Extraits, 2ml each" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Discovery Set — Six Extraits, 2ml each bottle" width="185" height="185">
              <span class="cornar">Exclusive</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="category.html">HOUSE OF DASTAN</a>
            <h3 class="product-tital">
              <a href="category.html">Discovery Set — Six Extraits, 2ml each</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.9 out of 5 stars from 41 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:98%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.9 (41)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$65</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Discovery Set — Six Extraits, 2ml each to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 2. Mes Bisous -->
        <div class="product" sku="UP0616943549089" productname="Discovery Set — Four Gourmands, 2ml each" brand="Mes Bisous" role="group" aria-label="Product: Discovery Set — Four Gourmands, 2ml each by Mes Bisous" style="width: 100%; display: inline-block;">
          <div class="product-image-wrapper">
            <a href="index.html" class="p-thumb" title="Discovery Set — Four Gourmands, 2ml each" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Discovery Set — Four Gourmands, 2ml each bottle" width="185" height="185">
              <span class="cornar">Exclusive</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="index.html">MES BISOUS</a>
            <h3 class="product-tital">
              <a href="index.html">Discovery Set — Four Gourmands, 2ml each</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.7 out of 5 stars from 19 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:94%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.7 (19)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$48</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Discovery Set — Four Gourmands, 2ml each to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 3. Maison Crivelli -->
        <div class="product" sku="UP0616943549089" productname="Discovery Collection — Ten Extraits" brand="Maison Crivelli" role="group" aria-label="Product: Discovery Collection — Ten Extraits by Maison Crivelli" style="width: 100%; display: inline-block;">
          <div class="product-image-wrapper">
            <a href="category.html" class="p-thumb" title="Discovery Collection — Ten Extraits" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Discovery Collection — Ten Extraits bottle" width="185" height="185">
              <span class="cornar">Exclusive</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="category.html">MAISON CRIVELLI</a>
            <h3 class="product-tital">
              <a href="category.html">Discovery Collection — Ten Extraits</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 66 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (66)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$85</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Discovery Collection — Ten Extraits to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 4. MAXAROMA Best of Niche Sampler -->
        <div class="product" sku="UP0616943549089" productname="Best of Niche Sampler — Eight Icons, 1.5ml" brand="MAXAROMA" role="group" aria-label="Product: Best of Niche Sampler — Eight Icons, 1.5ml by MAXAROMA" style="width: 100%; display: inline-block;">
          <div class="product-image-wrapper">
            <a href="category.html" class="p-thumb" title="Best of Niche Sampler — Eight Icons, 1.5ml" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Best of Niche Sampler — Eight Icons, 1.5ml bottle" width="185" height="185">
              <span class="cornar">MAX2DAY</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="category.html">MAXAROMA</a>
            <h3 class="product-tital">
              <a href="category.html">Best of Niche Sampler — Eight Icons, 1.5ml</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.8 out of 5 stars from 312 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:96%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.8 (312)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$55</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Best of Niche Sampler — Eight Icons, 1.5ml to cart">QUICK ADD</button>
          </div>
        </div>

        <!-- 5. Initio Parfums Privés (Distinct 5th Discovery Set, avoiding redundant duplicate) -->
        <div class="product" sku="UP0616943549089" productname="Discovery Set — Six Extraits, 2ml each" brand="Initio Parfums Privés" role="group" aria-label="Product: Discovery Set — Six Extraits, 2ml each by Initio Parfums Privés" style="width: 100%; display: inline-block;">
          <div class="product-image-wrapper">
            <a href="category.html" class="p-thumb" title="Discovery Set — Six Extraits, 2ml each" tabindex="-1" aria-hidden="true">
              <img class="img1" src="https://store.maxaroma.com/images/noimage-lrg.jpg" loading="lazy" decoding="async" alt="Discovery Set — Six Extraits, 2ml each bottle" width="185" height="185">
              <span class="cornar">Exclusive</span>
              <span class="cornar discount">-30%</span>
            </a>
          </div>
          <div class="product-name">
            <a class="brand-name" href="category.html">INITIO PARFUMS PRIVÉS</a>
            <h3 class="product-tital">
              <a href="category.html">Discovery Set — Six Extraits, 2ml each</a>
            </h3>
          </div>
          <div class="minheightprodbox">
            <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated 4.9 out of 5 stars from 58 reviews">
              <div class="pcard-rating-inner">
                <span class="pcard-stars" aria-hidden="true">
                  <span class="pcard-stars-bg">★★★★★</span>
                  <span class="pcard-stars-fill" style="width:98%">★★★★★</span>
                </span>
                <span class="pcard-rating-count" aria-hidden="true">4.9 (58)</span>
              </div>
            </div>
            <div class="pb-2 product-price">
              <div class="price">
                <span class="price-now"><span class="visually-hidden">Price: </span>$75</span>
              </div>
            </div>
            <div class="pcard-meta-wrap">
              <div class="opstion-avl">3 Options Available <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button></div>
            </div>
            <button type="button" onclick="if(typeof productquickview==='function'){productquickview('38528','5');}" class="lable-quickview" data-pid="38528" aria-label="Quick Add Discovery Set — Six Extraits, 2ml each to cart">QUICK ADD</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    (function () {
      'use strict';

      // 1. Data Definitions
      const deals = [
        { brand: 'Kilian Paris', name: "Angels' Share Eau de Parfum", price: 256, listPrice: 320, rating: 4.7, reviews: 812, badges: ['Low Stock'], offerType: 'Limited Quantities', endsH: 18, isNew: false, href: 'index.html' },
        { brand: 'Tom Ford', name: 'Lost Cherry Eau de Parfum', price: 316, listPrice: 395, rating: 4.5, reviews: 690, samplesFrom: 42, offerType: 'Price Cut', endsH: 31, isNew: false, href: 'index.html', badges: [] },
        { brand: 'Amouage', name: 'Reflection Man Eau de Parfum', price: 254, listPrice: 365, rating: 4.7, reviews: 421, offerType: 'Price Cut', endsH: 47, isNew: false, href: 'index.html', badges: [] },
        { brand: 'Creed', name: 'Aventus Eau de Parfum', price: 340, listPrice: 510, rating: 4.8, reviews: 1204, samplesFrom: 58, offerType: 'Price Cut', endsH: 61, isNew: false, href: 'index.html', badges: [] },
        { brand: 'Parfums de Marly', name: 'Layton Eau de Parfum', price: 365, listPrice: null, rating: 4.8, reviews: 1450, gwp: 'Free 10ml travel spray — $107 value', offerType: 'Free Gift', endsH: 88, isNew: false, href: 'index.html', badges: [] },
        { brand: 'Nishane', name: 'Hacivat Extrait de Parfum', price: 172, listPrice: 215, rating: 4.8, reviews: 733, offerType: 'Price Cut', endsH: 95, isNew: false, href: 'index.html', badges: [] },
        { brand: 'Mancera', name: 'Cedrat Boise Eau de Parfum', price: 126, listPrice: 180, rating: 4.6, reviews: 1102, offerType: 'Price Cut', endsH: 120, isNew: false, href: 'index.html', badges: [] },
        { brand: 'BDK Parfums', name: 'Gris Charnel Extrait de Parfum', price: 230, listPrice: null, rating: 4.8, reviews: 89, badges: ['New'], offerType: 'New Drop', endsH: 200, isNew: true, href: 'index.html' }
      ];

      const xerjoff = [
        { brand: 'Xerjoff', name: 'Naxos Eau de Parfum', price: 191, listPrice: 255, rating: 4.7, reviews: 412, href: 'category.html' },
        { brand: 'Xerjoff', name: 'Erba Pura Eau de Parfum', price: 182, listPrice: 260, rating: 4.6, reviews: 388, href: 'category.html' },
        { brand: 'Xerjoff', name: 'Amber Star Eau de Parfum', price: 210, listPrice: 350, rating: 4.5, reviews: 143, href: 'category.html' },
        { brand: 'Xerjoff', name: 'Accento Eau de Parfum', price: 175, listPrice: 250, rating: 4.6, reviews: 205, href: 'category.html' },
        { brand: 'Xerjoff', name: 'Torino21 Eau de Parfum', price: 195, listPrice: 260, rating: 4.8, reviews: 295, href: 'category.html' }
      ];

      const discovery = [
        { brand: 'House of Dastan', name: 'Discovery Set — Six Extraits, 2ml each', price: 65, rating: 4.9, reviews: 41, badges: ['Exclusive'], href: 'category.html' },
        { brand: 'Mes Bisous', name: 'Discovery Set — Four Gourmands, 2ml each', price: 48, rating: 4.7, reviews: 19, badges: ['Exclusive'], href: 'index.html' },
        { brand: 'Maison Crivelli', name: 'Discovery Collection — Ten Extraits', price: 85, rating: 4.8, reviews: 66, badges: ['Exclusive'], href: 'category.html' },
        { brand: 'MAXAROMA', name: 'Best of Niche Sampler — Eight Icons, 1.5ml', price: 55, rating: 4.8, reviews: 312, href: 'category.html' },
        { brand: 'Initio Parfums Privés', name: 'Discovery Set — Six Extraits, 2ml each', price: 75, rating: 4.9, reviews: 58, badges: ['Exclusive'], href: 'category.html' }
      ];

      // State Management
      let selectedDepth = null;
      let selectedType = null;
      let selectedSort = 'Ending Soonest';

      const depthRanges = {
        '10 – 25%': [10, 25],
        '25 – 50%': [25, 50],
        '50%+': [50, 101]
      };

      // Helper function to format currency
      function formatMoney(n) {
        if (n == null) return null;
        return '$' + (Number.isInteger(n) ? n : n.toFixed(2));
      }

      // 2. Component Renderer: Product Card with Full Semantic Accessibility
      function renderProductCard(p) {
        const price = p.price || 0;
        const list = p.listPrice;
        const hasSale = !!list && list > price;
        const discountText = hasSale ? ('−' + Math.round((1 - price / list) * 100) + '%') : (p.discount || '-30%');
        const badgeLeft = (p.badges && p.badges.length > 0) ? p.badges[0] : (p.badge || 'MAX2DAY');
        const imgUrl = p.image || 'https://store.maxaroma.com/images/noimage-lrg.jpg';
        const href = p.href || 'javascript:void(0);';
        const brandHref = p.brandHref || href;
        const pid = p.pid || '38528';

        // 1. Offer Label / Badge when an Offer Type is selected
        const offerLabelText = selectedType ? selectedType : '';
        const offerLabelHtml = offerLabelText ? `<span class="pcard-offer-type product-offer-type">${escapeHtml(offerLabelText.toUpperCase())}</span>` : '';

        // 2. Rating Row
        const ratingVal = p.rating ? Number(p.rating).toFixed(1) : null;
        const ratingPct = p.rating ? (Math.round((p.rating / 5) * 100) + '%') : '0%';
        const reviewsCnt = p.reviews || 0;
        const ratingHtml = ratingVal ? `
          <div class="product-rating pcard-rating-wrap" role="img" aria-label="Rated ${ratingVal} out of 5 stars from ${reviewsCnt} reviews">
            <div class="pcard-rating-inner">
              <span class="pcard-stars" aria-hidden="true">
                <span class="pcard-stars-bg">★★★★★</span>
                <span class="pcard-stars-fill" style="width:${ratingPct}">★★★★★</span>
              </span>
              <span class="pcard-rating-count" aria-hidden="true">${ratingVal} (${reviewsCnt})</span>
            </div>
          </div>
        ` : '';

        // 3. Price HTML with accessible labeling
        let priceHtml = '';
        if (hasSale) {
          priceHtml = `<del class="price-was"><span class="visually-hidden">Original price: </span>${formatMoney(list)}</del> <ins class="price-now"><span class="visually-hidden">Sale price: </span>${formatMoney(price)}</ins>`;
        } else if (p.priceRange) {
          priceHtml = `<span class="price-now"><span class="visually-hidden">Price: </span>${escapeHtml(p.priceRange)}</span>`;
        } else {
          priceHtml = `<span class="price-now"><span class="visually-hidden">Price: </span>${formatMoney(price)}</span>`;
        }

        // 4. Options / Samples / GWP
        const optionsText = p.options || (p.samplesFrom ? ('Samples from ' + formatMoney(p.samplesFrom)) : (p.gwp || null));
        let optionsHtml = '';
        if (optionsText) {
          const isGwp = Boolean(p.gwp || (p.offerType === 'Free Gift') || (optionsText && optionsText.toLowerCase().includes('free')));
          const isSamples = Boolean(p.samplesFrom || (optionsText && optionsText.toLowerCase().includes('sample')));
          if (isGwp) {
            optionsHtml = `<div class="opstion-avl sc-interp">${escapeHtml(optionsText)}</div>`;
          } else if (isSamples) {
            optionsHtml = `<div class="opstion-avl no-dot">${escapeHtml(optionsText)}</div>`;
          } else {
            optionsHtml = `<div class="opstion-avl">${escapeHtml(optionsText)}
                <button type="button" class="avl-round-btn" onclick="NicheFragrances()" aria-label="Niche fragrance options info"><span class="avl-round" aria-hidden="true"></span></button>
              </div>`;
          }
        }

        // 5. Countdown
        const endsLabel = (p.endsH != null && p.endsH < 48)
          ? ('Ends in ' + Math.floor(p.endsH) + 'h ' + (Math.round((p.endsH % 1) * 60) || 12) + 'm')
          : null;
        const endsLabelHtml = endsLabel ? `<div class="pcard-ends product-ends-label">${escapeHtml(endsLabel)}</div>` : '';

        return `
          <div class="product" sku="${escapeHtml(p.sku || 'UP0616943549089')}" productname="${escapeHtml(p.name)}" brand="${escapeHtml(p.brand)}" role="group" aria-label="Product: ${escapeHtml(p.name)} by ${escapeHtml(p.brand)}">
            <div class="product-image-wrapper">
              <a href="${escapeHtml(href)}" class="p-thumb" title="${escapeHtml(p.name)}" tabindex="-1" aria-hidden="true">
                <img class="img1" src="${escapeHtml(imgUrl)}" loading="lazy" decoding="async" alt="${escapeHtml(p.name)} bottle" width="185" height="185">
                <span class="cornar">${escapeHtml(badgeLeft)}</span>
                ${discountText ? `<span class="cornar discount">${escapeHtml(discountText)}</span>` : ''}
              </a>
            </div>
            <div class="product-name">
              ${offerLabelHtml}
              <a class="brand-name" href="${escapeHtml(brandHref)}">${escapeHtml(p.brand.toUpperCase())}</a>
              <h3 class="product-tital">
                <a href="${escapeHtml(href)}">${escapeHtml(p.name)}</a>
              </h3>
            </div>
            <div class="minheightprodbox">
              ${ratingHtml}
              <div class="pb-2 product-price">
                <div class="price">
                  ${priceHtml}
                </div>
              </div>
              <div class="pcard-meta-wrap">
                ${optionsHtml}
                ${endsLabelHtml}
              </div>
              <button type="button" onclick="if(typeof productquickview==='function'){productquickview('${escapeHtml(pid)}','5');}" class="lable-quickview" data-pid="${escapeHtml(pid)}" aria-label="Quick Add ${escapeHtml(p.name)} to cart">QUICK ADD</button>
            </div>
          </div>
        `;
      }

      function escapeHtml(str) {
        if (!str) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      // 3. Render Deals Catalog with Filtering & Sorting
      function updateDeals() {
        let list = deals.map(d => {
          const pct = d.listPrice ? Math.round((1 - d.price / d.listPrice) * 100) : 0;
          return { ...d, pct };
        });

        // Filter by discount depth
        if (selectedDepth && depthRanges[selectedDepth]) {
          const [lo, hi] = depthRanges[selectedDepth];
          list = list.filter(d => d.pct >= lo && d.pct < hi);
        }

        // Filter by offer type
        if (selectedType) {
          list = list.filter(d => d.offerType === selectedType);
        }

        // Sort
        if (selectedSort === 'Ending Soonest') {
          list.sort((a, b) => a.endsH - b.endsH);
        } else if (selectedSort === 'Biggest Saving') {
          list.sort((a, b) => {
            const saveA = (a.listPrice || a.price) - a.price;
            const saveB = (b.listPrice || b.price) - b.price;
            return saveB - saveA;
          });
        } else if (selectedSort === 'New') {
          list.sort((a, b) => (b.isNew ? 1 : 0) - (a.isNew ? 1 : 0));
        }

        const grid = document.getElementById('deals-grid');
        const noResults = document.getElementById('no-results-box');
        const status = document.getElementById('filter-status');

        if (list.length === 0) {
          grid.innerHTML = '';
          noResults.style.display = 'block';
          if (status) status.textContent = 'No offers match the selected filters.';
        } else {
          noResults.style.display = 'none';
          grid.innerHTML = list.map(renderProductCard).join('');
          if (status) status.textContent = 'Showing ' + list.length + ' offers.';
        }

        attachQuickAddHandlers();
      }

      // 4. Quick Add Button Feedback with Accessible Audio Announcement
      function attachQuickAddHandlers() {
        document.querySelectorAll('.lable-quickview, .pcard-add').forEach(btn => {
          btn.onclick = function (e) {
            if (typeof productquickview !== 'function') {
              e.preventDefault();
              const originalText = btn.textContent;
              const originalAriaLabel = btn.getAttribute('aria-label') || '';
              const productName = btn.closest('.product')?.getAttribute('productname') || 'Item';

              btn.textContent = 'ADDED ✓';
              btn.setAttribute('aria-label', 'Added ' + productName + ' to cart');
              btn.style.backgroundColor = '#1E6B3A';
              btn.style.color = '#FFFFFF';
              btn.style.borderColor = '#1E6B3A';

              const status = document.getElementById('filter-status');
              if (status) {
                status.textContent = 'Added ' + productName + ' to cart.';
              }

              setTimeout(() => {
                btn.textContent = originalText;
                btn.setAttribute('aria-label', originalAriaLabel);
                btn.style.backgroundColor = '';
                btn.style.color = '';
                btn.style.borderColor = '';
              }, 1400);
            }
          };
        });
      }

      // 5. Setup Filter Buttons Event Listeners
      function setupFilters() {
        document.querySelectorAll('.filter-pill').forEach(btn => {
          btn.addEventListener('click', function () {
            const filterKind = btn.getAttribute('data-filter');
            const val = btn.getAttribute('data-val');

            if (filterKind === 'depth') {
              if (selectedDepth === val) {
                selectedDepth = null;
              } else {
                selectedDepth = val;
              }
            } else if (filterKind === 'type') {
              if (selectedType === val) {
                selectedType = null;
              } else {
                selectedType = val;
              }
            }

            // Update active pill UI classes and ARIA attributes
            document.querySelectorAll('.filter-pill').forEach(b => {
              const bKind = b.getAttribute('data-filter');
              const bVal = b.getAttribute('data-val');
              const isActive = (bKind === 'depth' && selectedDepth === bVal) || (bKind === 'type' && selectedType === bVal);
              b.classList.toggle('on-pill', isActive);
              b.setAttribute('aria-pressed', String(isActive));
            });

            updateDeals();
          });
        });

        // Sort Select Listener
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
          sortSelect.addEventListener('change', function () {
            selectedSort = this.value;
            updateDeals();
          });
        }

        // Clear Filters Listener
        const clearBtn = document.getElementById('clear-filters-btn');
        if (clearBtn) {
          clearBtn.addEventListener('click', function () {
            selectedDepth = null;
            selectedType = null;
            document.querySelectorAll('.filter-pill').forEach(b => {
              b.classList.remove('on-pill');
              b.setAttribute('aria-pressed', 'false');
            });
            updateDeals();
            const status = document.getElementById('filter-status');
            if (status) {
              status.textContent = 'Showing ' + deals.length + ' offers. Filters cleared.';
            }
          });
        }
      }

      // 6. Live Countdown Timer (WCAG: No repetitive aria-live interrupts)
      function setupCountdown() {
        let heroEnd = null;
        try {
          heroEnd = localStorage.getItem('ma_offer_end');
        } catch (e) {}

        if (!heroEnd || isNaN(heroEnd) || Number(heroEnd) < Date.now()) {
          heroEnd = Date.now() + (2 * 24 + 13) * 3600e3 + 11 * 60e3 + 45 * 1e3;
          try {
            localStorage.setItem('ma_offer_end', heroEnd);
          } catch (e) {}
        } else {
          heroEnd = parseInt(heroEnd, 10);
        }

        const daysEl = document.getElementById('cd-days');
        const hoursEl = document.getElementById('cd-hours');
        const minsEl = document.getElementById('cd-mins');
        const secsEl = document.getElementById('cd-secs');
        const countdownEl = document.getElementById('countdown-val');

        function pad(n) {
          return String(Math.max(0, n)).padStart(2, '0');
        }

        function updateTimer() {
          const remain = Math.max(0, heroEnd - Date.now());
          const dd = Math.floor(remain / 864e5);
          const hh = Math.floor((remain % 864e5) / 36e5);
          const mm = Math.floor((remain % 36e5) / 6e4);
          const ss = Math.floor((remain % 6e4) / 1e3);

          if (daysEl) daysEl.textContent = pad(dd);
          if (hoursEl) hoursEl.textContent = pad(hh);
          if (minsEl) minsEl.textContent = pad(mm);
          if (secsEl) secsEl.textContent = pad(ss);
          if (countdownEl) {
            countdownEl.textContent = `${dd} days ${hh} hours ${mm} minutes ${ss} seconds left`;
          }
        }

        updateTimer();
        setInterval(updateTimer, 1000);
      }

      // Initialize Application on DOM Ready
      // Page speed optimization: Avoid unnecessary re-rendering on initial load
      document.addEventListener('DOMContentLoaded', function () {
        setupCountdown();
        setupFilters();
        attachQuickAddHandlers();
      });
    })();
  </script>

  <!-- Slider Initialization with Deferral Guard and WCAG Pagination Support -->
  <script>
    (function () {
      function initSliders() {
        if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.slick === 'function') {
          var $ = window.jQuery;

          // Xerjoff Brand Spotlight Slider (Offers Page)
          if ($('.xerjoff-slider').length && !$('.xerjoff-slider').hasClass('slick-initialized')) {
            $('.xerjoff-slider').slick({
              lazyLoad: 'ondemand',
              dots: true,
              infinite: false,
              speed: 300,
              arrows: false,
              slidesToShow: 5,
              slidesToScroll: 1,
              accessibility: true,
              customPaging: function (slider, i) {
                return '<button type="button" aria-label="Go to slide ' + (i + 1) + '">' + (i + 1) + '</button>';
              },
              responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 1 } },
                { breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 1 } },
                { breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 1 } },
                { breakpoint: 480, settings: { slidesToShow: 1.3, slidesToScroll: 1 } }
              ]
            });
          }

          // Discovery Sets & Sampling Slider (Offers Page)
          if ($('.discovery-sampling-slider').length && !$('.discovery-sampling-slider').hasClass('slick-initialized')) {
            $('.discovery-sampling-slider').slick({
              lazyLoad: 'ondemand',
              dots: true,
              infinite: false,
              speed: 300,
              arrows: false,
              slidesToShow: 5,
              slidesToScroll: 1,
              accessibility: true,
              customPaging: function (slider, i) {
                return '<button type="button" aria-label="Go to slide ' + (i + 1) + '">' + (i + 1) + '</button>';
              },
              responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 4, slidesToScroll: 1 } },
                { breakpoint: 992, settings: { slidesToShow: 3, slidesToScroll: 1 } },
                { breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 1 } },
                { breakpoint: 480, settings: { slidesToShow: 1.3, slidesToScroll: 1 } }
              ]
            });
          }
        } else {
          setTimeout(initSliders, 150);
        }
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSliders);
      } else {
        initSliders();
      }
    })();
  </script>
</main>
@endsection