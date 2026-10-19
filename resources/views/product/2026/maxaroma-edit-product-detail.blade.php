@extends('layouts.app', ['body_class' => 'prd-bot-space'])
@section('content')
  <link rel="stylesheet" href="{{ asset('css/maxaroma-edit26.css') }}?v=2.6.1">

  <!-- Schema.org Structured Data (JSON-LD) for SEO & Google Rich Results -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@graph": [
      {
        "@type": "Product",
        "@id": "{{ url()->current() }}#product",
        "name": "Ciel d'Orange Eau de Parfum",
        "image": [
          "https://placehold.co/768x768/EEE/31343C",
          "https://store.maxaroma.com/public/images/maxaroma-edit-2026/ojar-banner.jpg"
        ],
        "description": "Ciel d'Orange by OJAR is an oriental woody unisex fragrance featuring notes of absinthe, agarwood, agave, and warm amber accord.",
        "sku": "OJAR-CDO-100",
        "brand": {
          "@type": "Brand",
          "name": "OJAR"
        },
        "offers": {
          "@type": "Offer",
          "url": "{{ url()->current() }}",
          "priceCurrency": "USD",
          "price": "230.00",
          "priceValidUntil": "2027-12-31",
          "itemCondition": "https://schema.org/NewCondition",
          "availability": "https://schema.org/InStock",
          "seller": {
            "@type": "Organization",
            "name": "MaxAroma"
          }
        },
        "aggregateRating": {
          "@type": "AggregateRating",
          "ratingValue": "4.6",
          "reviewCount": "7",
          "bestRating": "5",
          "worstRating": "1"
        }
      },
      {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Fragrance",
            "item": "{{ url('/fragrance') }}"
          },
          {
            "@type": "ListItem",
            "position": 3,
            "name": "OJAR",
            "item": "{{ url('/brand/ojar') }}"
          },
          {
            "@type": "ListItem",
            "position": 4,
            "name": "Ciel d'Orange"
          }
        ]
      }
    ]
  }
  </script>

  <!-- Main PDP Container -->
  <main class="pdp-page" id="main-content" role="main" aria-label="Product Details">

    <!-- Breadcrumb Navigation -->
    <nav class="pdp-breadcrumbs" aria-label="Breadcrumb">
      <div class="container">
        <ol class="pdp-breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">
          <li class="pdp-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ url('/') }}" class="pdp-breadcrumbs-link" itemprop="item"><span itemprop="name">Home</span></a>
            <meta itemprop="position" content="1" />
            <span class="pdp-breadcrumbs-sep" aria-hidden="true">/</span>
          </li>
          <li class="pdp-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ url('/fragrance') }}" class="pdp-breadcrumbs-link" itemprop="item"><span itemprop="name">Fragrance</span></a>
            <meta itemprop="position" content="2" />
            <span class="pdp-breadcrumbs-sep" aria-hidden="true">/</span>
          </li>
          <li class="pdp-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ url('/brand/ojar') }}" class="pdp-breadcrumbs-link" itemprop="item"><span itemprop="name">OJAR</span></a>
            <meta itemprop="position" content="3" />
            <span class="pdp-breadcrumbs-sep" aria-hidden="true">/</span>
          </li>
          <li class="pdp-breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
            <span class="pdp-breadcrumbs-current" itemprop="name">Ciel d'Orange</span>
            <meta itemprop="position" content="4" />
          </li>
        </ol>
      </div>
    </nav>

    <!-- Product Hero Section -->
    <section class="pdp-hero" aria-labelledby="product-title">
      <div class="container">
        <div class="pdp-hero-inner">

          <!-- Gallery Column -->
            <div class="desktop-section">
              <div class="pdp-gallery-wrap">
            <div class="pdp-gallery" id="pdp-gallery" role="region" aria-label="Product Media Gallery">
              <!-- Primary Hero Card (Slide 1) -->
              <div class="pdp-hero-card" id="pdp-hero-view">
                <div class="pdp-hero-card-shadow" aria-hidden="true"></div>
                <div class="pdp-hero-badges">
                  <span class="pdp-badge-dark">Ships in 24h</span>
                  <span class="pdp-badge-outline">Unisex</span>
                </div>
                <div class="pdp-hero-card-placeholder">
                  <div class="pdp-hero-placeholder-label">Image placeholder</div>
                  <div class="pdp-hero-placeholder-title" id="pdp-gallery-active-label">Ciel d'Orange 100ml</div>
                </div>
                <div class="pdp-hero-card-caption">Photographed in Muscat, 2024</div>
              </div>

              <!-- Lifestyle Image Card (Slide 2) -->
              <div class="pdp-lifestyle-card">
                <span class="pdp-lifestyle-caption">Lifestyle — bottle on stone, evening light</span>
              </div>

              <!-- Mobile-only angle slides; desktop continues to use the thumbnail controls. -->
              <div class="pdp-mobile-gallery-card pdp-mobile-gallery-box">
                <span class="pdp-mobile-gallery-label">Box — Ciel d'Orange 100ml</span>
              </div>
              <div class="pdp-mobile-gallery-card pdp-mobile-gallery-texture">
                <span class="pdp-mobile-gallery-label">Texture — Ciel d'Orange 100ml</span>
              </div>
              <div class="pdp-mobile-gallery-card pdp-mobile-gallery-scale">
                <span class="pdp-mobile-gallery-label">Scale — Ciel d'Orange 100ml</span>
              </div>
            </div>

            <!-- Thumbnail Selector Buttons (Direct sibling of slider, perfectly positioned for both mobile & desktop) -->
            <div class="pdp-thumbs" role="group" aria-label="Product Gallery Angles">
              <button type="button" class="pdp-thumb-btn is-active" data-shot="0" data-label="Box — Ciel d'Orange 100ml" aria-label="View packaging box" aria-pressed="true">
                <span class="pdp-thumb-label">Box</span>
              </button>
              <button type="button" class="pdp-thumb-btn" data-shot="1" data-label="Texture — Ciel d'Orange 100ml" aria-label="View bottle texture" aria-pressed="false">
                <span class="pdp-thumb-label">Texture</span>
              </button>
              <button type="button" class="pdp-thumb-btn" data-shot="2" data-label="Scale — Ciel d'Orange 100ml" aria-label="View bottle scale" aria-pressed="false">
                <span class="pdp-thumb-label">Scale</span>
              </button>
            </div>
            </div>
              </div>

            <section class="mobile-section" aria-label="Product media gallery">
              <div class="pdp-hero-badges" aria-label="Product highlights">
                <span class="pdp-badge-dark">Ships in 24h</span>
                <span class="pdp-badge-outline">Unisex</span>
              </div>
              <div class="mobile-slick-slider slick-cls slick-dotted-nw" id="pdp-mobile-slick" role="region" aria-label="Product media gallery carousel" aria-roledescription="carousel">
                <div class="slide-cls" role="group" aria-roledescription="slide" aria-label="Slide 1 of 4: Bottle Front View">
                  <img src="https://placehold.co/768x768/EEE/31343C" width="768" height="768" alt="Ciel d'Orange Eau de Parfum 100ml luxury bottle front view" fetchpriority="high" decoding="async">
                </div>
                <div class="slide-cls" role="group" aria-roledescription="slide" aria-label="Slide 2 of 4: Packaging Box">
                  <img src="https://placehold.co/768x768/EEE/31343C" width="768" height="768" alt="Ciel d'Orange packaging box with OJAR signature branding" loading="lazy" decoding="async">
                </div>
                <div class="slide-cls" role="group" aria-roledescription="slide" aria-label="Slide 3 of 4: Bottle Texture">
                  <img src="https://placehold.co/768x768/EEE/31343C" width="768" height="768" alt="Detailed close-up of OJAR Ciel d'Orange bottle texture and cap" loading="lazy" decoding="async">
                </div>
                <div class="slide-cls" role="group" aria-roledescription="slide" aria-label="Slide 4 of 4: Scale in Hand">
                  <img src="https://placehold.co/768x768/EEE/31343C" width="768" height="768" alt="Ciel d'Orange 100ml bottle scale and proportions held in hand" loading="lazy" decoding="async">
                </div>
              </div>
            </section>

      <!-- Buy Rail / Product Details -->
      <div class="pdp-buy-rail">
        <!-- Brand Header -->
        <div class="pdp-brand-header">
          OJAR <span class="pdp-brand-origin">· Est. Oman</span>
        </div>

        <!-- Product Title -->
        <h1 class="pdp-product-title" id="product-title">Ciel d'Orange</h1>
        <div class="pdp-product-concentration-tag">Eau de Parfum</div>

        <!-- Rating Row -->
        <div class="pdp-rating-row" role="img" aria-label="Rated 4.6 out of 5 stars based on 7 customer reviews">
          <span class="pdp-stars" aria-hidden="true">★★★★<span class="pdp-stars-empty">★</span></span>
          <span class="pdp-rating-count">4.6 · 7 reviews</span>
        </div>

        <!-- Pricing Row -->
        <div class="pdp-price-row">
          <span class="pdp-price-amount" id="pdp-price-display">$230</span>
          <span class="pdp-installment-text" id="pdp-installment-display">or 4 payments of $58 with Afterpay</span>
        </div>

        <!-- Concentration Selector -->
        <div class="pdp-option-group">
          <div class="pdp-option-header">
            <span class="pdp-option-title" id="concentration-label">Concentration</span>
          </div>
          <div class="pdp-concs-group" role="radiogroup" aria-labelledby="concentration-label">
            <button type="button" class="pdp-conc-pill is-active" role="radio" aria-checked="true" tabindex="0" data-conc="0">
              Eau de Parfum
            </button>
            <button type="button" class="pdp-conc-pill" role="radio" aria-checked="false" tabindex="-1" data-conc="1">
              Eau de Toilette
            </button>
            <button type="button" class="pdp-conc-pill" role="radio" aria-checked="false" tabindex="-1" data-conc="2">
              Extrait de Parfum
            </button>
          </div>
        </div>

        <!-- Size Selector -->
        <div class="pdp-option-group">
          <div class="pdp-option-header">
            <span class="pdp-option-title" id="size-label">Size</span>
            <button type="button" class="pdp-option-link" id="pdp-oos-btn" aria-expanded="false" aria-controls="pdp-oos-notice">Show out of stock</button>
            <span id="pdp-oos-notice" class="sr-only" aria-live="polite">All sizes are currently in stock.</span>
          </div>
          <div class="pdp-sizes-grid" role="radiogroup" aria-labelledby="size-label">
            <button type="button" class="pdp-size-btn is-active" role="radio" aria-checked="true" tabindex="0" data-size="0" data-price="$230" data-installment="$58" data-size-label="3.4 oz / 100 ml">
              <span class="pdp-size-label">3.4 oz / 100 ml</span>
              <span class="pdp-size-price">$230</span>
            </button>
            <button type="button" class="pdp-size-btn" role="radio" aria-checked="false" tabindex="-1" data-size="1" data-price="$34" data-installment="$9" data-size-label="0.17 oz / 5 ml atomizer">
              <span class="pdp-size-label">0.17 oz / 5 ml atomizer</span>
              <span class="pdp-size-price">$34</span>
            </button>
          </div>
        </div>

        <!-- Add to Bag & Quantity Actions -->
        <div class="pdp-add-row">
          <!-- Quantity Stepper -->
          <div class="pdp-qty-stepper" role="group" aria-label="Product quantity selector">
            <button type="button" class="pdp-qty-btn" id="pdp-qty-dec" aria-label="Decrease quantity">−</button>
            <input type="number" class="pdp-qty-val" id="pdp-qty-input" name="quantity" value="1" min="1" max="99" aria-label="Quantity" autocomplete="off">
            <button type="button" class="pdp-qty-btn" id="pdp-qty-inc" aria-label="Increase quantity">+</button>
          </div>

          <!-- Primary CTA -->
          <button type="button" class="pdp-btn-add" id="pdp-add-btn" aria-label="Add Ciel d'Orange to bag">
            Add to cart
          </button>

          <!-- Wishlist Button -->
          <button type="button" class="pdp-btn-wishlist" id="pdp-wish-btn" aria-label="Add to wishlist" aria-pressed="false">
            <svg class="pdp-wish-icon" id="pdp-wish-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>
        </div>

        <!-- Accessible Live Region for Dynamic State Announcements (WCAG 4.1.3) -->
        <div id="pdp-a11y-status" class="sr-only" role="status" aria-live="polite" aria-atomic="true"></div>

        <!-- Inventory & Shipping Perks -->
        <div class="pdp-perks-list">
          <div class="pdp-perk-item">
            <span class="pdp-perk-dot" aria-hidden="true">●</span>
            <span class="pdp-perk-bold">In stock — only 3 left at this size</span>
          </div>
          <div class="pdp-perk-item">
            <span class="pdp-perk-dash" aria-hidden="true">—</span>
            <span><span class="pdp-perk-bold">Free 2-day express shipping</span> · arrives Sep 1</span>
          </div>
        </div>

        <!-- Aroma Club Points Banner -->
        <div class="pdp-points-banner">
          <div class="pdp-points-val">230</div>
          <div>
            <div class="pdp-points-title">Aroma Club points</div>
            <div class="pdp-points-desc">Earned on this purchase — redeemable against your next order</div>
          </div>
        </div>

        <!-- Authenticity Promise Link -->
        <div class="pdp-auth-row">
          <span class="pdp-perk-dash" aria-hidden="true">—</span>
          <span>100% authentic, sourced direct from the maison · <a href="#particulars" class="pdp-auth-link">our Authenticity Promise</a></span>
        </div>

        <!-- Questions / Specialist Quick Help -->
        <div class="pdp-qa-box">
          <div class="pdp-qa-header">
            <div class="pdp-qa-title">Questions about this product?</div>
            <div class="pdp-qa-subtitle">A specialist answers within a day</div>
          </div>
          <ul class="pdp-qa-chips" aria-label="Frequently asked questions">
            <li><button type="button" class="pdp-qa-chip">Does this fragrance have a strong trail?</button></li>
            <li><button type="button" class="pdp-qa-chip">Is this scent appropriate for a date night?</button></li>
            <li><button type="button" class="pdp-qa-chip">Is this scent suitable for evening wear?</button></li>
            <li><button type="button" class="pdp-qa-chip">Does this fragrance last all day?</button></li>
          </ul>
          <button type="button" class="pdp-btn-ask">
            Ask my question <span class="pdp-btn-ask-arrow" aria-hidden="true">→</span>
          </button>
        </div>
      </div>

        </div>
      </div>
    </section>

    <!-- The Perfumer Quote Section -->
    <section class="pdp-perfumer-sec" aria-labelledby="perfumer-title">
      <div class="container">
        <div class="pdp-perfumer-inner">
          <h2 class="sr-only" id="perfumer-title">The Perfumer's Inspiration</h2>
          <div class="pdp-perfumer-eyebrow" aria-hidden="true">The perfumer</div>
          <blockquote class="pdp-perfumer-quote">
            “I wanted the moment an orange tree is cut open at noon — the peel, the wood, the dust in the air. Not the fruit. The heat around it.”
          </blockquote>
          <div class="pdp-perfumer-attribution">
            Sheikha Al-Bakhawi · Founder, OJAR
          </div>
        </div>
      </div>
    </section>

    <!-- Note Architecture Section -->
    <section class="pdp-arch-sec" aria-labelledby="architecture-title">
      <div class="container">
        <div class="pdp-arch-header">
          <div>
            <div class="pdp-section-eyebrow">Note architecture</div>
            <h2 class="pdp-arch-title" id="architecture-title">Built in three movements</h2>
          </div>
          <div class="pdp-arch-desc">
            Absinthe and agarwood open cold and resinous. The middle turns green and aldehydic. The base is where the amber settles and stays.
          </div>
        </div>

        <!-- Staggered Note Composition Grid -->
        <div class="pdp-notes-grid" role="region" aria-label="Fragrance Note Breakdown">
          <!-- Top Notes Label -->
          <div class="pdp-note-top-label">
            <h3 class="pdp-note-level-title">Top</h3>
            <div class="pdp-note-level-items">Absinthe<br>Agarwood<br>Agave</div>
            <div class="pdp-note-level-time">0–30 min</div>
          </div>

          <!-- Image: Top Movement -->
          <figure class="pdp-note-img-card pdp-note-img-top">
            <img src="https://store.maxaroma.com/public/images/maxaroma-edit-2026/three-one.jpg" alt="Top notes visual: Absinthe, Agarwood, Agave" width="580" height="580" loading="lazy" decoding="async" />
          </figure>

          <!-- Image: Middle Movement -->
          <figure class="pdp-note-img-card pdp-note-img-mid">
            <img src="https://store.maxaroma.com/public/images/maxaroma-edit-2026/three-two.jpg" alt="Middle notes visual: Agave, Aldehydes, Algae" width="580" height="580" loading="lazy" decoding="async" />
          </figure>

          <!-- Image: Base Movement -->
          <figure class="pdp-note-img-card pdp-note-img-base">
            <img src="https://store.maxaroma.com/public/images/maxaroma-edit-2026/three-3.jpg" alt="Base notes visual: Agave, Amber Accord, Amber Musk" width="580" height="580" loading="lazy" decoding="async" />
          </figure>

          <!-- Middle Notes Label -->
          <div class="pdp-note-mid-label">
            <h3 class="pdp-note-level-title">Middle</h3>
            <div class="pdp-note-level-items">Agave<br>Aldehydes<br>Algae</div>
            <div class="pdp-note-level-time">1–4 hrs</div>
          </div>

          <!-- Base Notes Label -->
          <div class="pdp-note-base-label">
            <h3 class="pdp-note-level-title">Base</h3>
            <div class="pdp-note-level-items">Agave<br>Amber Accord<br>Amber Musk</div>
            <div class="pdp-note-level-time">6–12+ hrs</div>
          </div>
        </div>
      </div>
    </section>

    <!-- On Skin Evolution Timeline Section -->
    <section class="pdp-evolution-sec" aria-labelledby="evolution-title">
      <div class="container">
        <div class="pdp-evo-banner">
          <div class="pdp-section-eyebrow">What you'll actually smell</div>
          <div class="pdp-evo-divider" aria-hidden="true"></div>
        </div>

        <!-- Settling Intro -->
        <div class="pdp-skinintro">
          <figure class="pdp-skinintro-media">
            <img src="https://store.maxaroma.com/public/images/maxaroma-edit-2026/maxedit_sec2_banner.jpg" alt="Natural botanical perfume ingredients on stone" width="1050" height="530" loading="lazy" decoding="async">
            <div class="pdp-handrule" aria-hidden="true"></div>
          </figure>
          <div class="pdp-skinintro-text">
            <h2 class="pdp-skinintro-title" id="evolution-title">How it settles into skin</h2>
            <div class="pdp-skinintro-desc">
              Two sprays on the wrist. Below is what you'd actually notice as it moves from first spray to what stays behind.
            </div>
          </div>
        </div>

        <!-- 4-Column Phases & Gender Profile -->
        <div class="pdp-phases-grid">
          <!-- Phase 1 -->
          <article class="pdp-phase-card">
            <div class="pdp-phase-head">
              <h3 class="pdp-phase-name">Opening</h3>
              <span class="pdp-phase-time">0–30 min</span>
            </div>
            <div class="pdp-phase-line" aria-hidden="true"></div>
            <p class="pdp-phase-copy">
              Absinthe and agarwood, cold and dry. Bitter herb over cut wood — sharper than the name suggests.
            </p>
          </article>

          <!-- Phase 2 -->
          <article class="pdp-phase-card">
            <div class="pdp-phase-head">
              <h3 class="pdp-phase-name">Heart</h3>
              <span class="pdp-phase-time">1–4 hrs</span>
            </div>
            <div class="pdp-phase-line" aria-hidden="true"></div>
            <p class="pdp-phase-copy">
              Aldehydes open it up. Agave and algae bring something green and saline, like air off the coast at dusk.
            </p>
          </article>

          <!-- Phase 3 -->
          <article class="pdp-phase-card">
            <div class="pdp-phase-head">
              <h3 class="pdp-phase-name">Drydown</h3>
              <span class="pdp-phase-time">6–12+ hrs</span>
            </div>
            <div class="pdp-phase-line" aria-hidden="true"></div>
            <p class="pdp-phase-copy">
              Amber accord and amber musk, warm and close. Skin-adjacent rather than projecting. It stays until you wash it off.
            </p>
          </article>

          <!-- Gender Profile -->
          <div class="pdp-phase-card">
            <div class="pdp-phase-head">
              <h3 class="pdp-phase-name">Gender profile</h3>
              <span class="pdp-phase-time">Unisex</span>
            </div>
            <div class="pdp-phase-line" aria-hidden="true"></div>
            <div class="pdp-gender-list" role="list" aria-label="Gender profile classification">
              <div class="pdp-gender-item" role="listitem">Masculine</div>
              <div class="pdp-gender-item" role="listitem">Leans masc.</div>
              <div class="pdp-gender-item is-active" role="listitem" aria-current="true"><span class="sr-only">Selected: </span>Unisex</div>
              <div class="pdp-gender-item" role="listitem">Leans fem.</div>
              <div class="pdp-gender-item" role="listitem">Feminine</div>
            </div>
          </div>
        </div>

        <!-- Performance Meters -->
        <div class="pdp-meters-grid" role="region" aria-label="Fragrance Performance Metrics">
          <!-- Projection Meter -->
          <div class="pdp-meter-item">
            <div class="pdp-meter-head">
              <span class="pdp-meter-name">Projection</span>
              <span class="pdp-meter-val">Moderate</span>
            </div>
            <div class="pdp-meter-pips" role="meter" aria-valuenow="3" aria-valuemin="0" aria-valuemax="5" aria-label="Projection: Moderate (3 of 5)">
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip"></div>
              <div class="pdp-meter-pip"></div>
            </div>
          </div>

          <!-- Sillage Meter -->
          <div class="pdp-meter-item">
            <div class="pdp-meter-head">
              <span class="pdp-meter-name">Sillage</span>
              <span class="pdp-meter-val">Warm trail</span>
            </div>
            <div class="pdp-meter-pips" role="meter" aria-valuenow="4" aria-valuemin="0" aria-valuemax="5" aria-label="Sillage: Warm trail (4 of 5)">
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip"></div>
            </div>
          </div>

          <!-- Longevity Meter -->
          <div class="pdp-meter-item">
            <div class="pdp-meter-head">
              <span class="pdp-meter-name">Longevity</span>
              <span class="pdp-meter-val">8–12 hrs</span>
            </div>
            <div class="pdp-meter-pips" role="meter" aria-valuenow="4" aria-valuemin="0" aria-valuemax="5" aria-label="Longevity: 8–12 hrs (4 of 5)">
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip is-filled"></div>
              <div class="pdp-meter-pip"></div>
            </div>
          </div>
        </div>

        <!-- Season Suitability -->
        <div class="pdp-seasons-box">
          <div class="pdp-seasons-label">Wears best in</div>
          <div class="pdp-seasons-tags" role="list" aria-label="Recommended Seasons">
            <span class="pdp-season-tag is-active" role="listitem"><span class="sr-only">Recommended: </span>Spring</span>
            <span class="pdp-season-tag is-active" role="listitem"><span class="sr-only">Recommended: </span>Summer</span>
            <span class="pdp-season-tag is-active" role="listitem"><span class="sr-only">Recommended: </span>Fall</span>
            <span class="pdp-season-tag" role="listitem">Winter</span>
          </div>
        </div>
      </div>
    </section>

    <!-- The Maison Section -->
    <section class="pdp-maison-sec" aria-labelledby="maison-title">
      <div class="container">
        <div class="pdp-maison-inner">
          <div class="pdp-maison-text">
            <div class="pdp-section-eyebrow">The maison</div>
            <h2 class="pdp-maison-title" id="maison-title">OJAR</h2>
            <div class="pdp-maison-narrative">
              <p>
                OJAR has become a benchmark in luxury perfumery, blending the ancestral aridity of the East with the refinement of the West. Founded by Sheikha Al-Bakhawi, the brand captures the soul of Oman through fragrances that reflect its cultural richness and timeless traditions.
              </p>
              <p>
                Each OJAR fragrance is a masterpiece crafted by world-renowned perfumers, merging artisanal precision with modern elegance. The brand stands out for creating unisex perfumes that celebrate Eastern heritage while offering lasting sophistication.
              </p>
            </div>
            <div class="pdp-maison-stats">
              <div>
                <div class="pdp-stat-num">2018</div>
                <div class="pdp-stat-label">Founded</div>
              </div>
              <div>
                <div class="pdp-stat-num">Muscat</div>
                <div class="pdp-stat-label">Home</div>
              </div>
              <div>
                <div class="pdp-stat-num">Unisex</div>
                <div class="pdp-stat-label">Every scent</div>
              </div>
            </div>
          </div>
          <figure class="pdp-maison-img-card">
            <img src="https://store.maxaroma.com/public/images/maxaroma-edit-2026/ojar-banner.jpg" alt="OJAR luxury fragrance house heritage in Muscat, Oman" width="867" height="726" loading="lazy" decoding="async">
        </figure>
        </div>
      </div>
    </section>

    <!-- Compare with Confidence Section -->
    <section class="pdp-compare-sec" aria-labelledby="compare-title">
      <div class="container">
        <div class="pdp-compare-head">
          <div class="pdp-section-eyebrow">Compare with confidence</div>
          <h2 class="pdp-compare-title" id="compare-title">If you already love one of these</h2>
          <div class="pdp-compare-desc">Our fragrance team's honest take, side by side.</div>
        </div>

        <div class="pdp-compare-grid">
          <!-- Card 1 -->
          <article class="pdp-compare-card">
            <div class="pdp-compare-pre">If you like</div>
            <h3 class="pdp-compare-name">Kilian Angels' Share</h3>
            <div class="pdp-compare-sep" aria-hidden="true"></div>
            <blockquote class="pdp-compare-take">
              “This one is drier and less boozy. Where Angels' Share is cognac and cinnamon, Ciel d'Orange is bark and resin.”
            </blockquote>
          </article>

          <!-- Card 2 -->
          <article class="pdp-compare-card">
            <div class="pdp-compare-pre">If you like</div>
            <h3 class="pdp-compare-name">Tom Ford Oud Wood</h3>
            <div class="pdp-compare-sep" aria-hidden="true"></div>
            <blockquote class="pdp-compare-take">
              “Similar oud spine, but Ciel d'Orange is greener up top and finishes on amber rather than spice.”
            </blockquote>
          </article>

          <!-- Card 3 -->
          <article class="pdp-compare-card">
            <div class="pdp-compare-pre">If you like</div>
            <h3 class="pdp-compare-name">Le Labo Santal 33</h3>
            <div class="pdp-compare-sep" aria-hidden="true"></div>
            <blockquote class="pdp-compare-take">
              “If you wear Santal 33 as a signature, this is the evening version — same skin-close warmth, more shadow.”
            </blockquote>
          </article>
        </div>
      </div>
    </section>

    <!-- The Particulars / Accordions Section -->
    <section class="pdp-particulars-sec" id="particulars" aria-labelledby="particulars-title">
      <div class="container">
        <div class="pdp-particulars-grid">
          <div>
            <div class="pdp-section-eyebrow">The particulars</div>
            <h2 class="pdp-particulars-title" id="particulars-title">Everything else you might want to know</h2>
          </div>

          <!-- Accordions List -->
          <div class="pdp-accordion-group">
            <!-- Accordion 1: Details (Open by default) -->
            <details class="pdp-accordion" open>
              <summary class="pdp-accordion-summary">
                <span>Details</span>
                <span class="pdp-accordion-icon" aria-hidden="true">−</span>
              </summary>
              <div class="pdp-accordion-body">
                Ciel d'Orange is an oriental woody built on a contradiction: a citrus name over a resinous heart. The absinthe and agarwood arrive first and cold, almost medicinal, before agave and aldehydes lift the middle into something green and clean. What remains after six hours is amber — accord and musk together — sitting close to the skin rather than announcing itself.

                House: OJAR · Concentration: Eau de Parfum · Family: Oriental Woody · Gender: Unisex
                Top: Absinthe, Agarwood, Agave
                Middle: Agave, Aldehydes, Algae
                Base: Agave, Amber Accord, Amber Musk
              </div>
            </details>

            <!-- Accordion 2: Authenticity -->
            <details class="pdp-accordion">
              <summary class="pdp-accordion-summary">
                <span>Authenticity</span>
                <span class="pdp-accordion-icon" aria-hidden="true">+</span>
              </summary>
              <div class="pdp-accordion-body">
                Sourced direct from the maison. Every unit is verified on arrival at our New Jersey facility and sealed in its original cellophane. We publish our chain of custody — no grey-market stock, ever.

                If a bottle ever arrives without the OJAR batch code intact, we replace it and cover return shipping.
              </div>
            </details>

            <!-- Accordion 3: Shipping information -->
            <details class="pdp-accordion">
              <summary class="pdp-accordion-summary">
                <span>Shipping information</span>
                <span class="pdp-accordion-icon" aria-hidden="true">+</span>
              </summary>
              <div class="pdp-accordion-body">
                Free standard shipping on all orders. Ground delivery arrives in 2–5 business days; express in 1–2. Fragrance ships by ground only to some states due to air-freight restrictions on alcohol — we'll tell you at checkout, not after.

                Orders placed before 2pm ET ship the same day.
              </div>
            </details>

            <!-- Accordion 4: Return & exchange -->
            <details class="pdp-accordion">
              <summary class="pdp-accordion-summary">
                <span>Return &amp; exchange</span>
                <span class="pdp-accordion-icon" aria-hidden="true">+</span>
              </summary>
              <div class="pdp-accordion-body">
                Unopened and sealed: full refund within 30 days. Opened: store credit within 30 days, because fragrance can't be resold. Damaged or wrong item: we send a prepaid label and a replacement the same day you tell us.
              </div>
            </details>
          </div>
        </div>
      </div>
    </section>

    <!-- Continue the House / Related Scents Section -->
    <section class="pdp-related-sec" aria-labelledby="related-title">
      <div class="container">
        <div class="pdp-related-head">
          <div class="pdp-section-eyebrow">Continue the house</div>
          <h2 class="pdp-related-title" id="related-title">The other scents of OJAR</h2>
        </div>

        <div class="pdp-related-grid slick-cls slick-dotted-nw space-dots-top">
          <!-- Related Item 1 -->
          <article class="pdp-related-card slide-cls">
            <figure class="pdp-related-media">
              <img src="https://store.maxaroma.com/images/noimage-lrg.jpg" class="img1" loading="lazy" decoding="async" alt="OJAR Infusion Velours fragrance bottle" width="240" height="240">
            </figure>
            <div class="pdp-related-brand">OJAR</div>
            <h3 class="pdp-related-name">Infusion Velours</h3>
            <div class="pdp-related-notes">Vanilla · Tonka · Cedar</div>
            <div class="pdp-related-price">$70</div>
          </article>

          <!-- Related Item 2 -->
          <article class="pdp-related-card slide-cls">
            <figure class="pdp-related-media">
              <img src="https://store.maxaroma.com/images/noimage-lrg.jpg" class="img1" loading="lazy" decoding="async" alt="OJAR Bouées Fumantes Absolues fragrance bottle" width="240" height="240">
            </figure>
            <div class="pdp-related-brand">OJAR</div>
            <h3 class="pdp-related-name">Bouées Fumantes Absolues</h3>
            <div class="pdp-related-notes">Smoke · Incense · Leather</div>
            <div class="pdp-related-price">$175</div>
          </article>

          <!-- Related Item 3 -->
          <article class="pdp-related-card slide-cls">
            <figure class="pdp-related-media">
              <img src="https://store.maxaroma.com/images/noimage-lrg.jpg" class="img1" loading="lazy" decoding="async" alt="OJAR Rose Marmochanta fragrance bottle" width="240" height="240">
            </figure>
            <div class="pdp-related-brand">OJAR</div>
            <h3 class="pdp-related-name">Rose Marmochanta</h3>
            <div class="pdp-related-notes">Rose · Saffron · Oud</div>
            <div class="pdp-related-price">$60</div>
          </article>

          <!-- Related Item 4 -->
          <article class="pdp-related-card slide-cls">
            <figure class="pdp-related-media">
              <img src="https://store.maxaroma.com/images/noimage-lrg.jpg" class="img1" loading="lazy" decoding="async" alt="OJAR Papillon Seul fragrance bottle" width="240" height="240">
            </figure>
            <div class="pdp-related-brand">OJAR</div>
            <h3 class="pdp-related-name">Papillon Seul</h3>
            <div class="pdp-related-notes">Iris · Musk · Pear</div>
            <div class="pdp-related-price">$70</div>
          </article>

          <!-- Related Item 5 -->
          <article class="pdp-related-card slide-cls">
            <figure class="pdp-related-media">
              <img src="https://store.maxaroma.com/images/noimage-lrg.jpg" class="img1" loading="lazy" decoding="async" alt="OJAR Eagle Eyed Stranger fragrance bottle" width="240" height="240">
            </figure>
            <div class="pdp-related-brand">OJAR</div>
            <h3 class="pdp-related-name">Eagle Eyed Stranger</h3>
            <div class="pdp-related-notes">Frankincense · Bay Leaf · Woods</div>
            <div class="pdp-related-price">$160</div>
          </article>

        </div>
      </div>
    </section>

    <!-- Mobile Sticky Bottom Action Bar -->
    <aside class="pdp-sticky-bar" id="pdp-sticky-bar" aria-label="Quick Add to Cart">
      <div class="container">
        <div class="pdp-sticky-inner">
          <div class="pdp-sticky-info">
            <div class="pdp-sticky-title">Ciel d'Orange</div>
            <div class="pdp-sticky-meta" id="pdp-sticky-meta">$230 · 3.4 oz / 100 ml</div>
          </div>
          <button type="button" class="pdp-sticky-btn" id="pdp-sticky-cta">
            Add to cart
          </button>
        </div>
      </div>
    </aside>

  </main>

  <!-- Production-Ready Progressive Enhancement & Accessible Interactive Controller -->
  <script>
    (function () {
      'use strict';

      // -------------------------------------------------------------
      // 1. Accessibility State & Screen Reader Announcements (WCAG 4.1.3)
      // -------------------------------------------------------------
      var a11yStatus = document.getElementById('pdp-a11y-status');
      var a11yTimer = null;

      function announceA11y(message) {
        if (!a11yStatus) return;
        clearTimeout(a11yTimer);
        a11yTimer = setTimeout(function () {
          a11yStatus.textContent = message;
        }, 80);
      }

      // -------------------------------------------------------------
      // 2. Quantity Stepper & Direct Accessible Number Input
      // -------------------------------------------------------------
      var qtyInput = document.getElementById('pdp-qty-input');
      var qtyDec = document.getElementById('pdp-qty-dec');
      var qtyInc = document.getElementById('pdp-qty-inc');

      function updateQuantity(val, announce) {
        var num = parseInt(val, 10);
        if (isNaN(num) || num < 1) num = 1;
        if (num > 99) num = 99;
        if (qtyInput) {
          qtyInput.value = num;
        }
        if (announce) {
          announceA11y('Quantity set to ' + num);
        }
        return num;
      }

      if (qtyInc && qtyDec && qtyInput) {
        qtyInc.addEventListener('click', function () {
          updateQuantity(parseInt(qtyInput.value, 10) + 1, true);
        });

        qtyDec.addEventListener('click', function () {
          var cur = parseInt(qtyInput.value, 10);
          if (cur > 1) {
            updateQuantity(cur - 1, true);
          }
        });

        qtyInput.addEventListener('change', function () {
          updateQuantity(this.value, true);
        });

        qtyInput.addEventListener('keydown', function (e) {
          if (e.key === 'ArrowUp') {
            e.preventDefault();
            qtyInc.click();
          } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            qtyDec.click();
          }
        });
      }

      // -------------------------------------------------------------
      // 3. WAI-ARIA Radio Group Controller (Concentration & Size)
      //    Supports Click and Arrow Keys (ArrowLeft, ArrowRight, Up, Down, Home, End)
      // -------------------------------------------------------------
      function setupAccessibleRadiogroup(groupSelector, itemSelector, onSelect) {
        var group = document.querySelector(groupSelector);
        if (!group) return;
        var items = Array.prototype.slice.call(group.querySelectorAll(itemSelector));
        if (!items.length) return;

        function selectItem(index, shouldFocus, announceMsg) {
          if (index < 0) index = 0;
          if (index >= items.length) index = items.length - 1;

          items.forEach(function (btn, i) {
            var isCurrent = i === index;
            btn.classList.toggle('is-active', isCurrent);
            btn.setAttribute('aria-checked', isCurrent ? 'true' : 'false');
            btn.setAttribute('tabindex', isCurrent ? '0' : '-1');
            if (isCurrent && shouldFocus) {
              btn.focus();
            }
          });

          if (typeof onSelect === 'function') {
            onSelect(items[index]);
          }
          if (announceMsg) {
            announceA11y(announceMsg);
          }
        }

        items.forEach(function (btn, index) {
          btn.addEventListener('click', function () {
            selectItem(index, false, null);
          });

          btn.addEventListener('keydown', function (e) {
            var newIndex = -1;
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
              e.preventDefault();
              newIndex = (index + 1) % items.length;
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
              e.preventDefault();
              newIndex = (index - 1 + items.length) % items.length;
            } else if (e.key === 'Home') {
              e.preventDefault();
              newIndex = 0;
            } else if (e.key === 'End') {
              e.preventDefault();
              newIndex = items.length - 1;
            }

            if (newIndex !== -1) {
              selectItem(newIndex, true, null);
            }
          });
        });
      }

      var priceDisplay = document.getElementById('pdp-price-display');
      var installmentDisplay = document.getElementById('pdp-installment-display');
      var stickyMeta = document.getElementById('pdp-sticky-meta');

      // Size Radiogroup Setup
      setupAccessibleRadiogroup('.pdp-sizes-grid', '.pdp-size-btn', function (selectedBtn) {
        var price = selectedBtn.getAttribute('data-price');
        var installment = selectedBtn.getAttribute('data-installment');
        var sizeLabel = selectedBtn.getAttribute('data-size-label');

        if (priceDisplay && price) priceDisplay.textContent = price;
        if (installmentDisplay && installment) installmentDisplay.textContent = 'or 4 payments of ' + installment + ' with Afterpay';
        if (stickyMeta && price && sizeLabel) stickyMeta.textContent = price + ' · ' + sizeLabel;
        announceA11y('Selected size ' + sizeLabel + ' for ' + price);
      });

      // Concentration Radiogroup Setup
      setupAccessibleRadiogroup('.pdp-concs-group', '.pdp-conc-pill', function (selectedBtn) {
        var name = selectedBtn.textContent.trim();
        announceA11y('Selected concentration ' + name);
      });

      // -------------------------------------------------------------
      // 4. Desktop Thumbnail Angles
      // -------------------------------------------------------------
      var heroActiveLabel = document.getElementById('pdp-gallery-active-label');
      var thumbBtns = document.querySelectorAll('.pdp-thumb-btn');
      thumbBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          thumbBtns.forEach(function (b) {
            b.classList.remove('is-active');
            b.setAttribute('aria-pressed', 'false');
          });
          btn.classList.add('is-active');
          btn.setAttribute('aria-pressed', 'true');

          var label = btn.getAttribute('data-label');
          if (heroActiveLabel && label) {
            heroActiveLabel.textContent = label;
            announceA11y('Showing angle ' + label);
          }
        });
      });

      // -------------------------------------------------------------
      // 5. Wishlist & Add To Cart CTA
      // -------------------------------------------------------------
      var addBtn = document.getElementById('pdp-add-btn');
      var wishBtn = document.getElementById('pdp-wish-btn');

      if (addBtn) {
        addBtn.addEventListener('click', function () {
          var qty = qtyInput ? qtyInput.value : 1;
          announceA11y('Added ' + qty + ' Ciel d\'Orange to your bag');
        });
      }

      if (wishBtn) {
        wishBtn.addEventListener('click', function () {
          var isPressed = wishBtn.getAttribute('aria-pressed') === 'true';
          var nextState = !isPressed;
          wishBtn.setAttribute('aria-pressed', String(nextState));
          wishBtn.classList.toggle('is-active', nextState);
          announceA11y(nextState ? 'Saved to your wishlist' : 'Removed from your wishlist');
        });
      }

      // Out of Stock Toggle
      var oosBtn = document.getElementById('pdp-oos-btn');
      var oosNotice = document.getElementById('pdp-oos-notice');
      if (oosBtn) {
        oosBtn.addEventListener('click', function () {
          var isExpanded = oosBtn.getAttribute('aria-expanded') === 'true';
          var next = !isExpanded;
          oosBtn.setAttribute('aria-expanded', String(next));
          oosBtn.classList.toggle('is-active', next);
          if (oosNotice) {
            announceA11y(next ? 'Showing out of stock options: All sizes are currently in stock' : 'Hiding out of stock options');
          }
        });
      }

      // FAQ Question Chips
      var qaChips = document.querySelectorAll('.pdp-qa-chip');
      qaChips.forEach(function (chip) {
        chip.addEventListener('click', function () {
          var qText = chip.textContent.trim();
          announceA11y('Question selected: ' + qText + '. A specialist will answer shortly.');
        });
      });

      // Details / Accordion Sign Sync
      var accordions = document.querySelectorAll('.pdp-accordion');
      accordions.forEach(function (acc) {
        acc.addEventListener('toggle', function () {
          var icon = acc.querySelector('.pdp-accordion-icon');
          if (icon) {
            icon.textContent = acc.open ? '−' : '+';
          }
        });
      });

      // -------------------------------------------------------------
      // 6. Mobile Sticky Bar Observer & CTA
      // -------------------------------------------------------------
      var stickyBar = document.getElementById('pdp-sticky-bar');
      var stickyCta = document.getElementById('pdp-sticky-cta');

      if (stickyCta && addBtn) {
        stickyCta.addEventListener('click', function () {
          addBtn.click();
        });
      }

      if (addBtn && stickyBar && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
              stickyBar.classList.add('is-visible');
            } else {
              stickyBar.classList.remove('is-visible');
            }
          });
        }, { threshold: 0 });

        observer.observe(addBtn);
      }

      // -------------------------------------------------------------
      // 7. Robust Slick Slider Controller (Bounded retries & passive resize)
      // -------------------------------------------------------------
      var slickRetryTimer = null;
      var slickRetryCount = 0;
      var MAX_SLICK_RETRIES = 20; // 3 seconds maximum timeout to prevent CPU drain

      function initPDPSliders() {
        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.slick !== 'function') {
          if (!slickRetryTimer && slickRetryCount < MAX_SLICK_RETRIES) {
            slickRetryCount++;
            slickRetryTimer = window.setTimeout(function () {
              slickRetryTimer = null;
              initPDPSliders();
            }, 150);
          }
          return;
        }

        var $ = window.jQuery;

        // Related Products Slider (Desktop & Mobile)
        var $relatedGrid = $('.pdp-related-grid');
        if ($relatedGrid.length && !$relatedGrid.hasClass('slick-initialized')) {
          $relatedGrid.slick({
            lazyLoad: 'ondemand',
            dots: true,
            infinite: false,
            speed: 300,
            arrows: false,
            slidesToShow: 5,
            slidesToScroll: 1,
            accessibility: true,
            responsive: [
              { breakpoint: 1200, settings: { slidesToShow: 5, slidesToScroll: 1 } },
              { breakpoint: 992, settings: { slidesToShow: 4, slidesToScroll: 1 } },
              { breakpoint: 768, settings: { arrows: false, slidesToShow: 3, slidesToScroll: 1 } },
              { breakpoint: 480, settings: { arrows: false, slidesToShow: 2, slidesToScroll: 1 } }
            ],
            nextArrow: '<button type="button" class="slick-next round-btn-sl" aria-label="Next fragrance slide"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
            prevArrow: '<button type="button" class="slick-prev round-btn-sl" aria-label="Previous fragrance slide"><svg class="svg svg-slick-left" aria-hidden="true" width="24" height="24"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>'
          });
        }

        // Mobile Media Gallery Slider (<= 767px only)
        var $mobileGallery = $('.mobile-slick-slider');
        if (window.innerWidth <= 767) {
          if ($mobileGallery.length && !$mobileGallery.hasClass('slick-initialized')) {
            $mobileGallery.slick({
              dots: true,
              arrows: false,
              infinite: false,
              speed: 300,
              slidesToShow: 1,
              slidesToScroll: 1,
              accessibility: true,
              swipe: true,
              draggable: true,
              touchThreshold: 10,
              customPaging: function (slider, index) {
                return '<button type="button" aria-label="Go to slide ' + (index + 1) + '">' + (index + 1) + '</button>';
              }
            });
          }
          if ($mobileGallery.hasClass('slick-initialized')) {
            $mobileGallery.slick('setPosition');
          }
        } else if ($mobileGallery.hasClass('slick-initialized')) {
          $mobileGallery.slick('unslick');
        }
      }

      var mobileGalleryResizeTimer;
      window.addEventListener('resize', function () {
        clearTimeout(mobileGalleryResizeTimer);
        mobileGalleryResizeTimer = setTimeout(initPDPSliders, 150);
      }, { passive: true });

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPDPSliders);
      } else {
        initPDPSliders();
      }
      window.addEventListener('load', initPDPSliders, { once: true });
    })();
  </script>

@endsection
