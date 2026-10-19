<style>
    :root {
      --bg-surface: #f9f9f9;
      --bg-surface-low: #f3f3f3;
      --bg-surface-high: #e8e8e8;
      --bg-surface-highest: #e2e2e2;
      --bg-white: #ffffff;
      --bg-primary: #000000;
      --bg-secondary: #fed488;
      --text-primary: #000000;
      --text-on-primary: #ffffff;
      --text-secondary: #775a19;
      --text-on-surface: #1a1c1c;
      --text-on-surface-variant: #4c4546;
      --text-on-primary-container: #848484;
      --border-light: #e2e2e2;
      --container-max: 1440px;
      --gap-lg: 32px;
      --gap-md: 16px;
      --gap-sm: 8px;
      --section-gap: 80px;
      --gutter: 24px;
    }

    html {
      scroll-behavior: smooth;
    }
    
    .hero {
      position: relative;
      overflow: hidden;
    }

    .hero__content {
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      min-height: 600px;
    }

    .hero__copy {
      width: 100%;
      background: #ffffff;
      padding: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 32px;
      z-index: 1;
    }

    .hero__copy>span {
      text-transform: uppercase;
      letter-spacing: 0.2em;
      color: #775a19;
      font-size: 12px;
      font-weight: 600;
    }

    .hero__title {
      margin: 0;
      font-size: 48px;
      line-height: 1.1;
      font-weight: 700;
    }

    .hero__title .hero__subtitle {
      display: block;
      font-style: italic;
      font-weight: 400;
      color: #4c4546;
      letter-spacing: -0.02em;
    }

    .hero__paragraph {
      max-width: 520px;
      margin: 0;
      color: #4c4546;
      line-height: 25px;
      font-size: 16px;
    }

    .hero__cta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 1rem 2.5rem;
      border: none;
      background: var(--bg-primary);
      color: var(--text-on-primary);
      text-transform: uppercase;
      letter-spacing: 0.15em;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.5s ease;
    }

    .hero__cta:hover {
      background: var(--text-secondary);
    }

    .hero__image {
      width: 100%;
      position: relative;
    }

    .hero__image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero__image::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.05);
    }

    @media (min-width: 768px) {
      .hero__content {
        flex-direction: row;
      }

      .hero__copy,
      .hero__image {
        width: 50%;
      }
    }

    .offer-nav {
      padding: var(--gap-md) 0;
      background: var(--bg-white);
      border-bottom: 1px solid var(--border-light);
      position: sticky;
      top: 80px;
      z-index: 40;
      overflow-y: hidden;
      overflow-x: auto;
    }

    .offer-nav__inner {
      margin: 0 auto;
      padding: 0 15px;
      display: flex;
      gap: var(--gutter);
      overflow-x: auto;
      white-space: nowrap;
      justify-self: center;
    }

    .offer-nav__inner a {
      text-decoration: none;
      color: var(--text-on-surface-variant);
      font-size: 12px;
      letter-spacing: 0.15em;
      font-weight: 600;
      text-transform: uppercase;
      padding-bottom: 4px;
      transition: color 0.3s ease;
    }

    .offer-nav__inner a.active {
      color: var(--text-primary);
      border-bottom: 2px solid var(--text-primary);
    }

    .offer-nav__inner a:hover {
      color: var(--text-primary);
    }
    .section {
      padding: var(--section-gap) 0;
      max-width: var(--container-max);
      margin: 0 auto;
    }

    .section-header {
      margin-bottom: var(--gap-lg);
    }

    .section-title {
      margin: 0;
      font-size: 32px;
      font-weight: 600;
    }

    .section-decor-bar {
      margin-top: 8px;
      width: 64px;
      height: 4px;
      background: var(--text-secondary);
    }

    @media (min-width: 768px) {
      .section-header {
        text-align: left;
      }
    }

    .featured-grid {
      display: grid;
      gap: var(--gutter);
      grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
      .featured-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    .feature-card {
      position: relative;
      overflow: hidden;
      background: var(--bg-surface-high);
      border-radius: 4px;
    }

    .feature-card__image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.7s ease;
    }

    .feature-card:hover .feature-card__image {
      transform: scale(1.05);
    }

    .feature-card__overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.6),
        rgba(0, 0, 0, 0)
      );
    }

    .feature-card__content {
      position: absolute;
      left: 0;
      bottom: 0;
      padding: var(--gap-lg);
      color: var(--bg-white);
    }

    .feature-card__label {
      display: inline-block;
      margin-bottom: 12px;
      background: var(--bg-secondary);
      color: var(--text-secondary);
      padding: 4px 8px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
      font-size: 10px;
      font-weight: 600;
    }

    .feature-card__content h3 {
      margin: 0 0 12px;
      font-size: 32px;
    }

    .feature-card__content p {
      margin: 0 0 16px;
      color: rgba(255, 255, 255, 0.9);
    }

    .feature-card__content a {
      display: inline-block;
      color: var(--bg-white);
      text-transform: uppercase;
      letter-spacing: 0.15em;
      text-decoration: none;
      border-bottom: 1px solid var(--bg-white);
      padding-bottom: 4px;
    }

    .small-cards {
      display: flex;
      flex-direction: column;
      gap: var(--gutter);
    }

    .card {
      position: relative;
      overflow: hidden;
      border-radius: 4px;
      background: var(--bg-surface-low);
    }

    .card__image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.7s ease;
    }

    .card:hover .card__image {
      transform: scale(1.05);
    }

    .card__overlay {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.2);
      transition: background 0.3s ease;
    }

    .card:hover .card__overlay {
      background: rgba(0, 0, 0, 0.3);
    }

    .card__panel {
      width: 75%;
      margin: 0 auto;
      padding: var(--gap-lg);
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-light);
      text-align: center;
    }

    .card__panel h4 {
      margin: 0;
      font-size: 20px;
      color: var(--text-primary);
    }

    .card__panel p {
      margin: 8px 0 0;
      color: var(--text-secondary);
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
    }

    .value-section {
      background: var(--bg-primary);
      color: var(--text-on-primary);
      padding: var(--section-gap) 0px;
    }

    .value-intro p {
      margin: 8px 0 0;
      color: var(--text-on-primary-container);
      text-transform: uppercase;
      letter-spacing: 0.15em;
      font-size: 12px;
    }

    .value-grid {
      display: grid;
      gap: var(--gap-md);
      grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
      .value-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .value-card {
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 24px;
      text-align: center;
      transition: border-color 0.3s ease;
    }

    .value-card:hover {
      border-color: var(--text-secondary);
    }

    .value-card--featured::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(
        90deg,
        #775a19 0%,
        #fed488 50%,
        #775a19 100%
      );
      background-size: 200% auto;
      animation: shimmer 4s linear infinite;
    }

    .value-card span {
      display: block;
      margin-bottom: 24px;
      font-size: 48px;
      color: var(--bg-white);
      transition: color 0.3s ease;
    }

    .value-card p {
      margin: 0 0 24px;
      color: var(--text-on-primary-container);
    }

    /* .value-card button {
      border: 1px solid var(--bg-white);
      background: transparent;
      color: var(--bg-white);
      padding: 12px 32px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
      cursor: pointer;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .value-card button:hover {
      background: var(--bg-white);
      color: var(--text-primary);
    } */
    .new-arrivals {
      padding: var(--section-gap) 0;
    }

    .new-arrivals__header {
      max-width: var(--container-max);
      margin: 0 auto 32px;
      display: flex;
      flex-direction: column;
      gap: var(--gap-sm);
    }

    .new-arrivals__header h2 {
      margin: 0;
      font-size: 32px;
    }

    .new-arrivals__header p {
      margin: 0;
      color: var(--text-on-surface-variant);
    }

    .carousel-controls {
      display: flex;
      gap: var(--gap-sm);
    }

    .control-button {
      width: 40px;
      height: 40px;
      border: 1px solid var(--border-light);
      background: var(--bg-white);
      color: var(--text-primary);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .control-button:hover {
      background: var(--bg-surface-high);
    }

    .new-arrivals__header.desktop {
      display: none;
    }

    .luxurys-slider .slick-track {
      display: flex;
      gap: var(--gutter);
      overflow-x: auto;
      padding: 0 2% 16px;
    }

    .product-card {
      flex-shrink: 0;
    }

    @media (min-width: 768px) {
      .new-arrivals__header {
        flex-direction: row;
        align-items: flex-end;
      }

    }
    .luxurys-slider .slick-prev{
        top: -58px;
        right: 17%;
        left: auto;
    }
    .luxurys-slider .slick-next{
        top: -58px;
        right: 13%;
        left: auto;
    }
    .product-card__image {
      position: relative;
      overflow:hidden;
      border-radius: 4px;
      background: var(--bg-surface-low);
      margin-bottom: 8px;
    }

    .product-card__image img {
      width: 100%;
      transition: transform 0.5s ease;
    }

    .product-card:hover .product-card__image img {
      transform: scale(1.05);
    }

    .quick-add {
      position: absolute;
      left: 50%;
      bottom: 16px;
      transform: translateX(-50%);
      padding: 12px 24px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
      opacity: 0;
      transition: opacity 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .product-card:hover .quick-add {
      opacity: 1;
    }

    .product-card__meta {
      text-align: center;
    }

    .product-card__meta .brand {
      margin: 0;
      color: var(--text-secondary);
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
    }

    .product-card__meta h4 {
      margin: 8px 0 0;
      font-size: 20px;
    }

    .product-card__meta .price {
      margin: 8px 0 0;
      font-size: 18px;
      font-weight: 700;
    }

     .curated {
      background: var(--bg-surface-highest);
      padding: var(--section-gap) 40px;
    }

    .curated-grid {
      max-width: var(--container-max);
      margin: 0 auto;
      display: grid;
      gap: var(--section-gap);
      grid-template-columns: 1fr;
      align-items: center;
    }

    @media (min-width: 768px) {
      .curated-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    .curated-image {
      position: relative;
    }

    .curated-image__decor {
      display: none;
    }

    .curated-image img {
      width: 100%;
      height: 600px;
      object-fit: cover;
      border: 4px solid var(--bg-white);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
    }
    .curated-quote p{
      margin-bottom:0;
    }
    .curated-quote {
      position: absolute;
      right: -24px;
      bottom: -24px;
      background: var(--bg-primary);
      color: var(--text-on-primary);
      padding: 24px;
      max-width: 200px;
    }

    @media (min-width: 992px) {
      .curated-image__decor {
        display: block;
        position: absolute;
        top: -40px;
        left: -40px;
        width: 160px;
        height: 160px;
        border: 2px solid rgba(119, 90, 25, 0.2);
      }
    }

    .curated-copy {
      display: flex;
      flex-direction: column;
      gap: var(--gap-lg);
    }

    .curated-copy span {
      text-transform: uppercase;
      letter-spacing: 0.15em;
      font-size: 12px;
      color: var(--text-secondary);
    }

    .curated-copy h2 {
      margin: 0;
      font-size: 48px;
    }

    .curated-copy p {
      margin: 0;
      color: var(--text-on-surface-variant);
      max-width: 620px;
    }

    .curated-list {
      list-style: none;
      margin: 0;
      padding:0 0;
      border-top: 1px solid var(--border-light);
      display: grid;
      gap: 16px;
    }

    .curated-list li {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 16px;
      border-top: 1px solid var(--border-light);
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .curated-list li:hover {
      color: var(--text-secondary);
    }

    .curated-list li span {
      font-size: 20px;
    }

    .trust-section {
      background: var(--bg-white);
      padding: var(--section-gap) 0px;
    }

    .trust-grid {
      max-width: var(--container-max);
      margin: 0 auto;
      display: flex;
      justify-content: center;
      gap: var(--gutter);
      text-align: center;
      
    }
    
    .trust-card {
      padding: 24px;
      flex-basis: 33.333%;
      max-width: 33.333%;
    }
    .trust-card span{
      margin-bottom: 10px;
      display: block;
    }

    .trust-card h3 {
      margin: 0 0 8px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.15em;
    }

    .trust-card p {
      margin: 0;
      color: var(--text-on-surface-variant);
    }

     @media (max-width: 1200px) {
      .hero__title,.value-card span,.curated-copy h2{font-size: 36px;}
     }

     @media (max-width: 991px) {
      .curated-copy span{letter-spacing: 0;}
      .curated-grid{gap:40px;}
      .value-section,.curated,.new-arrivals,.trust-section{padding:40px 0;}
      .hero__title, .value-card span,.curated-copy h2{font-size: 26px;}
      .feature-card__content{padding: 15px;}
      .feature-card__content h3{font-size: 24px}
      .section{padding:40px 0;}
      .hero__copy{padding: 25px 0px;}
      .luxurys-slider .slick-track{gap:15px;}
     }
     @media (max-width: 767px) {
      .luxurys-slider .slick-track{padding: 0 15px 15px;}
      .product-card__meta h4,.product-card__meta .price{font-size: 14px;}
      .trust-grid{flex-wrap:wrap;gap: 15px;}
      .trust-card{padding:0;flex-basis: 47%;max-width: 47%;}
      .curated-quote{right: 0px;}
      .hero__content{flex-direction: column-reverse;}
     }
	
	/*
	seasonal landing page css
	*/
	.slick-dots>li>button:before {
    width: 5px;
    height: 5px;
  }

  .product-wishlist,
  .facet-chip,
  .faq-q {
    background: none;
    border: none;
    font-family: inherit;
    cursor: pointer;
  }

  .facet-chip {
    border: 1px solid var(--light-border);
  }


  /* Reusable atoms */
  .rule {
    width: 40px;
    height: 1px;
    background: var(--gold);
  }

  /* ── EDITORIAL HERO ── */
  .hero {
    min-height: 92vh;
    display: grid;
    grid-template-columns: 5fr 7fr;
    background: var(--cream);
  }

  .hero-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 80px 64px 80px 80px;
    position: relative;
  }

  .hero-eyebrow-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 32px;
  }

  .hero-eyebrow-row .rule {
    width: 28px;
  }

  .hero-heading em {
    font-style: italic;
    color: var(--plum);
  }

  .hero-sub {
    line-height: 18px;
    margin-bottom: 30px;
    max-width: 420px;
  }

  .hero-cta-row {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
  }

  .hero-cta-link {
    text-transform: uppercase;
    text-decoration: none;
    border-bottom: 1px solid var(--gold);
    padding-bottom: 4px;
  }

  .hero-cta-link-white {
    text-transform: uppercase;
    color: #fff;
    text-decoration: none;
    border-bottom: 1px solid #fff;
    display: flex;
    align-items: center;
  }
  
  .hero-cta-link-white:hover {
    color: #fff;
  }

  .hero-meta {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 24px;
    padding-top: 32px;
    border-top: 1px solid var(--light-border);
  }

  .hero-meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .hero-meta-label {
    font-size: 9px;
    text-transform: uppercase;
  }

  .hero-meta-val {
    font-size: 20px;
    font-weight: 400;
    color: var(--charcoal);
  }

  .hero-image {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #3D2B24 0%, #1C1410 60%, #2A1F1A 100%);
  }

  .hero-collage {
    position: absolute;
    inset: 0;
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 2px;
  }

  .hero-tile {
    position: relative;
    overflow: hidden;
    padding: 30px;
  }

  .hero-tile-1 {
    grid-row: 1 / span 2;
    background: linear-gradient(160deg, #4A3428 0%, #1E1410 100%);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .hero-tile-2 {
    background: linear-gradient(160deg, #6B4A3A 0%, #2A1B14 100%);
    display: flex;
    align-items: flex-end;
  }

  .hero-tile-3 {
    background: linear-gradient(160deg, #8A6B4A 0%, #3A2818 100%);
    display: flex;
    align-items: flex-end;
  }

  .hero-tile-label {
    position: absolute;
    top: 20px;
    left: 24px;
    font-style: italic;
    font-size: 13px;
    color: #fff;
  }

  .hero-tile-brand {
    font-size: 9px;
    text-transform: uppercase;
    color: var(--gold-light);
    font-weight: 500;
    position: absolute;
    bottom: 20px;
    right: 24px;
  }

  .hero-date-card {
    position: absolute;
    top: 40px;
    right: 40px;
    background: rgba(17, 17, 17, 0.75);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(184, 150, 90, 0.3);
    padding: 20px 24px;
    text-align: center;
    z-index: 5;
  }

  .hero-date-main {
    font-size: 32px;
    font-weight: 300;
    color: #fff;
    line-height: 1;
  }

  .hero-date-main small {
    display: block;
    font-size: 11px;
    color: rgb(255 255 255 / 55%);
    margin-bottom: 4px;
  }

  .hero-date-label {
    font-size: 9px;
    text-transform: uppercase;
    color: rgb(255 255 255 / 55%);
    margin-top: 6px;
  }

  /* ── BRAND LOGO STRIP ── */
  .brand-strip-label {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 24px;
  }

  .brand-logos .brand_box .inner_newbrand {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 50px;
    flex-wrap: wrap;
    row-gap: 30px;
}

  .brand-logo {
    font-size: 22px;
    font-weight: 400;
    color: var(--mid);
    opacity: 0.72;
    transition: opacity .2s, color .2s;
  }

  .brand-logo:hover {
    opacity: 1;
    color: var(--gold);
  }

  .brand-logo.italic {
    font-style: italic;
  }

  .brand-logo.sans {
    font-family: 'Jost', sans-serif;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 14px;
  }

  /* ── PROMO VALUE STRIP ── */
  .value-strip {
    background: var(--gold);
    display: grid;
    grid-template-columns: repeat(4, 1fr);
  }

  .value-item {
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-right: 1px solid rgba(28, 26, 24, 0.15);
    color:#fff;
    border-color: #ddd;
    justify-content: center;
  }

  .value-item:last-child {
    border-right: none;
  }

  .value-icon {
    width: 32px;
    height: 32px;
    border: 1px solid var(--charcoal);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
  }

  .value-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    line-height: 1.2;
  }

  .value-head {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 600;
  }

  .value-sub {
    font-size: 11px;
    color: rgb(255 255 255 / 55%);
  }

  /* ── STORY / EDITORIAL INTRO ── */
  .editorial-intro {
    padding: 120px 80px;
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 48px;
    max-width: 1400px;
    margin: 0 auto;
    text-align: center;
  }

  .editorial-intro-content {
    grid-column: 2;
  }

  .editorial-intro .eyebrow {
    margin-bottom: 24px;
    display: inline-block;
  }

  .editorial-intro h2 {
    font-size: clamp(36px, 4.2vw, 56px);
    font-weight: 300;
    line-height: 1.15;
    color: var(--charcoal);
    margin-bottom: 28px;
  }

  .editorial-intro h2 em {
    font-style: italic;
    color: var(--plum);
  }

  .editorial-intro p {
    font-size: 15px;
    line-height: 1.9;
    color: var(--mid);
    max-width: 620px;
    margin: 0 auto;
  }

  .editorial-intro .rule {
    margin: 40px auto 0;
  }

  /* ── SHOP BY CATEGORY (Gift Finder entry) ── */
  .category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  .cat-card {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    text-decoration: none;
    cursor: pointer;
    transition: transform .3s;
  }

  .cat-card:after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 40%;
      background: linear-gradient(to top,rgba(0,0,0,.35),transparent);
      z-index: 1;
    }

  .cat-card:hover {
    transform: translateY(-4px);
  }

  .cat-content {
    color: var(--cream);
    position: absolute;
    left: 20px;
    bottom: 20px;
    z-index: 9;
  }

  .cat-eyebrow {
    font-size: 9px;
    text-transform: uppercase;
    font-weight: 500;
    margin-bottom: 8px;
  }

  .cat-title span {
    display: block;
  }


  /* ── SECTION HEADER ── */
  .section-header {
    text-align: center;
    max-width: 900px;
    margin: 0 auto;
    padding-bottom: 30px;
  }

  .section-header .eyebrow {
    margin-bottom: 15px;
    display: inline-block;
  }

  .section-header h2 {
    font-size: 24px;
  }

  .section-header h2 em {
    font-style: italic;
    color: var(--plum);
  }

  .section-header .section-desc {
    font-size: 12px;
    margin: 10px auto 0;
    max-width: 480px;
  }

  .section-header .rule {
    margin: 24px auto 0;
  }

  .section-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding-bottom:30px;
  }

  .section-header-row h2 {
    font-size: 24px;
    font-weight: 500;
  }

  .section-header-row h2 em {
    font-style: italic;
    color: var(--plum);
  }

  .section-header-row .eyebrow {
    margin-bottom: 12px;
    display: block;
  }

  .section-header-row a {
    text-transform: uppercase;
    text-decoration: none;
    border-bottom: 1px solid var(--gold);
    padding-bottom: 4px;
  }

  /* ── GIFT TIER ── */
  .tier-tabs {
    display: flex;
    justify-content: center;
    gap: 0;
    margin-bottom: 48px;
    border-bottom: 1px solid var(--light-border);
  }

  .tier-tab {
    padding: 18px 32px;
    text-transform: uppercase;
    color: var(--muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: all .2s;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
    font-family: inherit;
    font-weight: 500;
  }

  .tier-tab.active {
    color: var(--charcoal);
    border-bottom-color: var(--gold);
    font-weight: 600;
  }

  .tier-tab:hover {
    color: var(--charcoal);
  }

  .tier-tab-price {
    font-size: 15px;
    font-style: italic;
    color: var(--gold);
    margin-left: 6px;
    text-transform: none;
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    column-gap: 20px;
    row-gap: 30px;
  }

  .product-card {
    display: flex;
    flex-direction: column;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
  }

  .product-image {
    aspect-ratio: 3/4;
    background: var(--cream);
    border: 1px solid var(--light-border);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 16px;
    transition: border-color .3s;
  }

  .product-card:hover .product-image {
    border-color: var(--gold);
  }

  .product-image-inner {
    width: 60%;
    height: 80%;
    position: relative;
    display: flex;
    align-items: flex-end;
    justify-content: center;
  }

  .product-image-inner img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform .3s ease;
  }

  .product-card:hover .product-image-inner img {
    transform: scale(1.05);
  }

  .product-bottle {
    width: 52px;
    height: 120px;
    border-radius: 3px 3px 2px 2px;
    position: relative;
  }

  .product-bottle::before {
    content: '';
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    width: 14px;
    height: 14px;
    border-radius: 50% 50% 0 0;
    background: inherit;
    opacity: 0.8;
  }


  .product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .product-badge {
    background: var(--charcoal);
    color: var(--gold-pale);
    font-size: 9px;
    text-transform: uppercase;
    padding: 5px 9px;
    font-weight: 500;
  }

  .product-badge.rose {
    background: var(--rose);
    color: #fff;
  }

  .product-wishlist {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 32px;
    height: 32px;
    border: 1px solid var(--light-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--mid);
    font-size: 14px;
    cursor: pointer;
    transition: all .2s;
  }

  .product-quick {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #000;
    color: #fff;
    text-align: center;
    padding: 12px;
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 500;
    transform: translateY(100%);
    transition: transform .3s;
    cursor: pointer;
  }

  .product-card:hover .product-quick {
    transform: translateY(0);
  }

  .product-brand {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 500;
    margin-bottom: 4px;
  }

  .product-name {
    font-size: 17px;
    font-weight: 400;
    color: var(--charcoal);
    line-height: 1.3;
    margin-bottom: 6px;
  }

  .product-notes {
    font-size: 11px;
    color: var(--muted);
    font-style: italic;
    margin-bottom: 10px;
  }

  .product-price-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
  }

  .product-price {
    font-weight: 600;
  }

  .product-price .orig {
    text-decoration: line-through;
    margin-right: 6px;
    font-weight: 400;
  }

  .product-rating {
    font-size: 10px;
    color: var(--muted);
  }

  .product-rating .stars {
    color: var(--gold);
    margin-right: 4px;
  }

  /* ── EDITORIAL SPLIT (story block) ── */
  .editorial-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 560px;
    max-width: 1400px;
    margin: 80px auto;
    padding: 0 48px;
    gap: 48px;
  }

  .editorial-split.reverse .ed-image {
    order: 2;
  }

  .ed-image {
    background: linear-gradient(160deg, #3A2B24 0%, #1C1410 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px;
  }

  .ed-image.blush {
    background: linear-gradient(160deg, #E8D5C8 0%, #C9826B 100%);
  }

  .ed-image-stamp {
    position: absolute;
    top: 24px;
    left: 24px;
    font-style: italic;
    font-size: 14px;
    color: rgba(240, 230, 211, 0.6);
  }

  .ed-bottle {
    width: 150px;
    height: 280px;
    background: linear-gradient(170deg, #E8C99A 0%, #B8965A 40%, #7A5C38 100%);
    border-radius: 6px 6px 3px 3px;
    position: relative;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
  }

  .ed-bottle::after {
    content: '';
    position: absolute;
    top: -26px;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 30px;
    background: #D4AF6A;
    border-radius: 50% 50% 4px 4px;
  }

  .ed-image.blush .ed-bottle {
    background: linear-gradient(170deg, #F5E0D0 0%, #D8A090 40%, #8A4A38 100%);
  }

  .ed-image.blush .ed-bottle::after {
    background: #8A4A38;
  }

  .ed-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px 0;
  }

  .ed-content .eyebrow {
    margin-bottom: 18px;
    display: inline-block;
  }

  .ed-content h3 {
    font-size: clamp(32px, 3.6vw, 48px);
    font-weight: 300;
    line-height: 1.15;
    margin-bottom: 20px;
  }

  .ed-content h3 em {
    font-style: italic;
    color: var(--plum);
  }

  .ed-content p {
    font-size: 14px;
    line-height: 1.9;
    color: var(--mid);
    margin-bottom: 16px;
    max-width: 480px;
  }

  .ed-content-pullquote {
    font-size: 22px;
    font-style: italic;
    color: var(--gold);
    line-height: 1.4;
    margin: 24px 0;
    padding-left: 20px;
    border-left: 1px solid var(--gold);
    max-width: 440px;
  }

  .ed-credit {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--muted);
    margin-top: 28px;
  }

  .ed-credit span {
    color: var(--gold);
  }

  /* ── FRAGRANCE FINDER (Quiz/Concierge CTA) ── */
  .finder {
    background: linear-gradient(135deg, #111111 0%, #111111 100%);
    padding: 70px 0;
    position: relative;
    overflow: hidden;
  }

  .finder::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    border: 1px solid rgba(184, 150, 90, 0.15);
    border-radius: 50%;
  }

  .finder::after {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -150px;
    width: 500px;
    height: 500px;
    border: 1px solid rgba(184, 150, 90, 0.1);
    border-radius: 50%;
  }

  .finder-inner {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
    position: relative;
    z-index: 2;
  }

  .finder-inner .eyebrow {
    margin-bottom: 20px;
    display: inline-block;
    color: #fff;
  }

  .finder h2 {
    font-size: 36px;
    font-weight: 400;
    color: var(--gold-pale);
    line-height: 1.12;
    margin-bottom: 20px;
  }

  .finder h2 em {
    font-style: italic;
  }

  .finder p {
    color: #fff;
    line-height: 18px;
    margin-bottom: 32px;
    max-width: 440px;
  }

  .finder-bullets {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 40px;
  }

  .finder-bullet {
    color: #fff;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .finder-bullet::before {
    content: '';
    width: 24px;
    height: 1px;
    background: #fff;
  }

  .finder-cta-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
  }

  .finder-visual {
    position: relative;
    aspect-ratio: 1;
    background: rgba(240, 230, 211, 0.04);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .finder-steps {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 100%;
    height: 100%;
  }

  .finder-step {
    background: rgba(17, 17, 17, 0.4);
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: space-between;
    border: solid 1px rgba(240, 230, 211, 0.04);
  }

  .finder-step-num {
    font-size: 42px;
    font-weight: 300;
    color: #fff;
    line-height: 1;
    font-style: italic;
  }

  .finder-step-title {
    font-size: 18px;
    font-weight: 400;
    color: var(--gold-pale);
  }

  .finder-step-desc {
    font-size: 11px;
    color: rgb(255 255 255 / 55%);
    line-height: 1.6;
  }

  /* ── BUNDLES ── */
  .bundles-section {
    background: #F7F5F5;
    padding: 70px 0;
    border-top: 1px solid var(--light-border);
    border-bottom: 1px solid var(--light-border);
  }

  .bundles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }

  .bundle-card {
    background: var(--warm-white);
    border: 1px solid var(--light-border);
    padding: 32px 28px;
    position: relative;
    transition: all .3s;
    cursor: pointer;
    display: flex;
    flex-direction: column;
  }

  .bundle-card:hover {
    border-color: var(--gold);
    box-shadow: 0 8px 40px rgba(184, 150, 90, 0.12);
    transform: translateY(-2px);
  }

  .bundle-card.featured {
    border-color: var(--gold);
    background: var(--warm-white);
  }

  .bundle-featured-tag {
    position: absolute;
    top: -1px;
    right: 28px;
    background: var(--gold);
    color: var(--warm-white);
    font-size: 9px;
    text-transform: uppercase;
    padding: 6px 12px;
    font-weight: 600;
  }

  .bundle-image {
    background: var(--warm-white);
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--light-border);
    padding: 24px;
    margin-bottom: 30px;
  }

  .bundle-name {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 8px;
  }

  .bundle-desc {
    line-height: 18px;
    margin-bottom: 20px;
  }

  .bundle-includes {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 24px;
    flex: 1;
  }

  .bundle-includes li {
    font-size: 12px;
    color: var(--charcoal);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .bundle-includes li::before {
    content: '—';
    color: var(--gold);
  }

  .bundle-price-row {
    display: flex;
    align-items: baseline;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .bundle-price {
    font-size: 24px;
    font-weight: 400;
  }

  .bundle-was {
    font-size: 13px;
    color: var(--muted);
    text-decoration: line-through;
  }

  .bundle-save {
    font-size: 11px;
    color: var(--rose);
    font-weight: 500;
    background: rgba(201, 130, 107, 0.1);
    padding: 3px 8px;
  }

  /* ── GIFTING EXPERIENCE (concierge) ── */
  .experience-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }

  .exp-card {
    border: 1px solid var(--light-border);
    padding: 36px 28px;
    background: var(--warm-white);
    transition: all .3s;
  }

  .exp-card:hover {
    border-color: var(--gold);
    transform: translateY(-2px);
  }

  .exp-num {
    font-size: 14px;
    font-style: italic;
    color: var(--gold);
    margin-bottom: 20px;
  }

  .exp-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 14px;
    line-height: 1.2;
  }

  .exp-desc {
    color: var(--mid);
    line-height: 18px;
    margin-bottom: 20px;
  }

  .exp-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
  }

  .exp-list li {
    font-size: 12px;
    color: var(--charcoal);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .exp-list li::before {
    content: '◇';
    color: var(--gold);
    font-size: 8px;
  }

  .exp-link {
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 500;
    color: var(--charcoal);
    text-decoration: none;
    border-bottom: 1px solid var(--gold);
    padding-bottom: 3px;
  }

  .exp-link:hover {
    color: var(--gold);
  }

  /* ── SHIPPING CTA BANNER ── */
  .shipping-banner-sec{
    background: var(--charcoal);
    padding: 80px 0;
    position: relative;
        overflow: hidden;


  }
  .shipping-banner {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 60px;
  }

  .shipping-banner::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 300px;
    height: 300px;
    border: 1px solid rgba(184, 150, 90, 0.12);
    border-radius: 50%;
  }

  .shipping-content {
    position: relative;
    z-index: 2;
  }

  .shipping-banner .eyebrow {
    margin-bottom: 18px;
    display: inline-block;
    color: #fff;
  }

  .shipping-headline {
    font-size: 36px;
    font-weight: 400;
    color: #fff;
    margin-bottom: 20px;
  }

  .shipping-headline strong {
    font-weight: 400;
  }

  .shipping-detail {
    color: #fff;
    line-height: 18px;
    max-width: 520px;
    margin-bottom: 24px;
  }

  .shipping-detail span {
    font-weight: 600;
  }

  .shipping-timeline {
    display: flex;
    gap: 32px;
    padding-top: 24px;
    border-top: 1px solid rgba(240, 230, 211, 0.1);
  }

  .ship-step {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .ship-step-date {
    font-size: 18px;
    font-weight: 600;
    color: #fff;
    font-style: italic;
  }

  .ship-step-label {
    color: rgb(255 255 255 / 55%);
  }

  .shipping-badge {
    flex-shrink: 0;
    border: 1px solid rgba(184, 150, 90, 0.35);
    padding: 40px 32px;
    text-align: center;
    min-width: 380px;
    position: relative;
    z-index: 2;
    background: rgba(14, 13, 12, 0.3);
  }

  .shipping-badge-main {
    font-size: 48px;
    font-weight: 300;
    color: #fff;
    line-height: 1;
  }

  .shipping-badge-sub {
    font-size: 10px;
    text-transform: uppercase;
    color: rgb(255 255 255 / 55%);
    margin-top: 10px;
  }

  .shipping-badge-rule {
    width: 30px;
    height: 1px;
    background: var(--gold);
    margin: 12px auto;
  }

  .shipping-badge-mini {
    font-size: 10px;
    color: rgb(255 255 255 / 55%);
  }

  /* ── FROM THE JOURNAL (editorial blog) ── */
  .journal-section {
    padding: 0 80px 100px;
    max-width: 1400px;
    margin: 0 auto;
  }

  .journal-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }

  .journal-card {
    text-decoration: none;
    color: inherit;
    cursor: pointer;
  }

  .journal-img {
    aspect-ratio: 4/5;
    background: linear-gradient(160deg, #3A2B24 0%, #1C1410 100%);
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
  }

  .journal-card:nth-child(2) .journal-img {
    background: linear-gradient(160deg, #E8D5C8 0%, #C9826B 100%);
  }

  .journal-card:nth-child(3) .journal-img {
    background: linear-gradient(160deg, #8A6B4A 0%, #3A2818 100%);
  }

  .journal-cat {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(14, 13, 12, 0.7);
    color: var(--gold-light);
    font-size: 9px;
    text-transform: uppercase;
    padding: 6px 12px;
    backdrop-filter: blur(6px);
  }

  .journal-read {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 500;
    margin-bottom: 8px;
  }

  .journal-title {
    font-size: 24px;
    font-weight: 400;
    line-height: 1.25;
    margin-bottom: 10px;
  }

  .journal-excerpt {
    font-size: 13px;
    color: var(--mid);
    line-height: 1.8;
  }

  .journal-meta {
    font-size: 11px;
    color: var(--muted);
    margin-top: 14px;
  }

  /* ── TESTIMONIAL / PRESS ── */
  .testimonial-section {
    background: var(--cream);
    padding: 70px 0;
    border-top: 1px solid var(--light-border);
  }

  .testimonial-inner {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
  }

  .test-stars {
    color: var(--gold);
    font-size: 16px;
    margin-bottom: 28px;
  }

  .test-quote {
    font-size: 24px;
    font-weight: 400;
    font-style: italic;
    line-height: 32px;
    margin-bottom: 32px;
}

  .test-quote::before,
  .test-quote::after {
    content: '"';
    color: var(--gold);
    margin: 0 4px;
  }

  .test-author {
    font-size: 11px;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 500;
    margin-bottom: 6px;
  }

  .test-source {
    font-size: 12px;
    color: var(--muted);
    font-style: italic;
  }

  .testimonial-slider .slick-dots {
    padding-top: 30px;
  }

  .press-logos {
    display: flex;
    justify-content: center;
    gap: 48px;
    align-items: center;
    padding-top: 60px;
    margin-top: 60px;
    border-top: 1px solid var(--light-border);
    flex-wrap: wrap;
  }

  .press-logo {
    font-size: 18px;
    color: var(--muted);
    font-style: italic;
    opacity: 0.7;
  }

  .press-logo.bold {
    font-family: 'Jost', sans-serif;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    font-style: normal;
  }


  /* ── GIFT CARD ── */
  .giftcard {
    padding: 30px 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }

  .giftcard-visual {
    position: relative;
    overflow: hidden;
  }

  .giftcard-top {
    z-index: 2;
    position: absolute;
    top: 30px;
    left: 30px;
}

  .giftcard-logo {
    font-size: 24px;
    color: #fff;
  }

  .giftcard-bottom {
    position: absolute;
    bottom: 30px;
    left: 30px;
    z-index: 2;
  }

  .giftcard-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #fff;
    margin-bottom: 10px;
  }

  .giftcard-amount {
    font-size: 42px;
    font-weight: 300;
    color: #fff;
    font-style: italic;
  }

  .giftcard-content h3 {
    font-size: 32px;
    font-weight: 400;
    line-height: 1.15;
    margin: 15px 0;

  }

  .giftcard-content h3 em {
    font-style: italic;
    color: var(--plum);
  }

  .giftcard-amounts {
    display: flex;
    gap: 10px;
    margin-bottom: 40px;
    flex-wrap: wrap;
  }

  .giftcard p{line-height: 18px; margin-bottom: 30px;}

  .gc-amount {
    padding: 12px 22px;
    border: 1px solid var(--light-border);
    font-size: 18px;
    font-weight: 400;
    cursor: pointer;
    transition: all .2s;
  }

  .gc-amount.active,
  .gc-amount:hover {
    border-color: #000;
    background: #000;
    color: #fff;
  }

  /* ── NEWSLETTER ── */
  .newsletter {
    background: linear-gradient(135deg, #111 0%, #111 100%);
    padding: 70px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .newsletter::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 600px;
    height: 600px;
    border: 1px solid rgba(184, 150, 90, 0.06);
    border-radius: 50%;
  }

  .newsletter::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 900px;
    height: 900px;
    border: 1px solid rgba(184, 150, 90, 0.04);
    border-radius: 50%;
  }

  .newsletter-inner {
    max-width: 540px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
  }

  .newsletter .eyebrow {
    color: #fff;
    margin-bottom: 20px;
    display: inline-block;
  }

  .newsletter h2 {
    font-size: 36px;
    font-weight: 400;
    color: var(--gold-pale);
    margin-bottom: 20px;
  }

  .newsletter p {
    color: #fff;
    line-height: 18px;
    margin-bottom: 36px;
  }

  .newsletter-form {
    display: flex;
    max-width: 550px;
    margin: 0 auto;
    border-bottom: 1px solid rgba(255, 255, 255, 0.4);
  }

  .newsletter-form input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 16px 4px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: var(--gold-pale);
    outline: none;
  }

  .newsletter-form input::placeholder {
    color: rgb(255 255 255 / 55%);
  }

  .newsletter-form button {
    border-radius: 0;
  }

  .newsletter-incentive {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 48px;
    flex-wrap: wrap;
  }

  .ni-item {
    display: flex;
    color: #fff;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }

  .ni-icon {
    font-size: 28px;
    font-style: italic;
  }

  .ni-text {
    font-size: 11px;
    text-transform: uppercase;
    color: rgb(255 255 255 / 55%);
  }

  /* ── FAQ ── */

  .faq-item {
    border-bottom: 1px solid var(--light-border);
    padding: 20px 0;
    cursor: pointer;
    max-width: 900px;
    margin: auto;
  }

  .faq-item button:focus {
    outline: none;
    border: none;
  }

  .faq-q {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    width: 100%;
    text-align: left;
    padding: 0;
    color: inherit;
  }

  .faq-q span {
    font-size: 14px;
    font-weight: 500;
    color: #000;
  }

  .faq-plus {
    color: var(--gold);
    font-size: 18px;
    flex-shrink: 0;
    transition: transform .2s;
  }

  .faq-a {
    font-size: 13px;
    color: var(--mid);
    line-height: 1.9;
    margin-top: 0;
    max-height: 0;
    overflow: hidden;
    transition: all .3s;
  }

  .faq-item.open .faq-a {
    max-height: 300px;
    font-size: 12px;
    margin-top: 16px;
  }

  .faq-item.open .faq-plus {
    transform: rotate(45deg);
  }

  .fade-up {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity .7s ease, transform .7s ease;
  }

  .fade-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* ── PROMO DUAL (stackable offers) ── */
  .promo-dual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }

  .promo-dual-card {
    padding: 56px 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 340px;
    position: relative;
    overflow: hidden;
  }

  .promo-dual-a {
    background: linear-gradient(135deg, #111 0%, #111 100%);
    color: var(--gold-pale);
  }

  .promo-dual a {
    width: fit-content;
  }

  .promo-dual-a::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 260px;
    height: 260px;
    border: 1px solid rgba(184, 150, 90, 0.18);
    border-radius: 50%;
  }

  .promo-dual-b {
    background: var(--cream);
    border: 1px solid var(--light-border);
    color: var(--charcoal);
  }

  .promo-dual-card.promo-dual-b .eyebrow{
    color:#000;
  }

  .promo-dual-card .eyebrow {
    margin-bottom: 18px;
    display: inline-block;
    color: #fff;
  }

  .promo-dual-card h3 {
    font-size: 32px;
    font-weight: 400;
    line-height: 1.15;
    margin-bottom: 18px;
    color: inherit;
    position: relative;
    z-index: 2;
  }

  .promo-dual-card h3 em {
    font-style: italic;
  }

  .promo-dual-b h3 em {
    color: var(--plum);
  }

  .promo-dual-card p {
    line-height: 18px;
    color: #fff;
    margin-bottom: 28px;
    max-width: 440px;
    position: relative;
    z-index: 2;
  }

  /* ── HOUSES PORTFOLIO ── */
  .houses-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1px;
    background: var(--light-border);
    border: 1px solid var(--light-border);
  }

  .house-tile {
    background: var(--warm-white);
    padding: 40px 24px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: background .25s;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

.house-logo {
    font-size: 20px;
    letter-spacing: 0.05em;
    color: var(--charcoal);
    line-height: 1.1;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 10px;
}

  .house-logo.serif {
    font-weight: 400;
  }

  .house-logo.serif.italic {
    font-style: italic;
  }

  .house-origin {
    text-transform: uppercase;
    color: var(--muted);
    font-weight: 400;
  }

  .house-tile.view-all {
    background: var(--charcoal);
    color: var(--gold-pale);
  }

.house-tile.view-all .house-logo {
    color: var(--gold-pale);
    font-size: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.house-tile.view-all .house-logo em {
    color: #fff;
    font-style: normal;
    font-size: 18px;
    font-weight: 500;
}

  .house-tile.view-all .house-origin {
    color: var(--gold);
    font-size: 14px;
    letter-spacing: 0;
  }

  .house-tile.view-all:hover {
    background: var(--ink);
  }

  @media (max-width: 900px) {
    .houses-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  /* ── BRAND SALE GRID ── */

  .brand-sale-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 15px;
    border: 1px solid var(--light-border);
    padding: 20px;
  }

  .bs-tile {
    background: var(--warm-white);
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: background .2s;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .bs-logo {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 10px;
  }

  .bs-off {
    text-transform: uppercase;
    font-weight: 500;
    color: var(--muted);
  }

  @media (max-width: 1100px) {
    .promo-dual-card{
      padding: 30px;
    }

    .brand-sale-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      row-gap: 15px;
    }
    .gc-amount {
      padding: 10px 15px;
      font-size: 16px;
    }
  }

  @media (max-width: 767px) {
    .brand-sale-grid{
      padding: 15px;
    }
   .bs-tile{
    min-height: 100px;
   }
   .bs-logo{
    height: 50px;
   }
    .promo-dual {
      grid-template-columns: 1fr;
    }
    .promo-dual-card {
      padding: 40px 28px;
    }

  }

  /* Review note marker */
  .note-marker {
    background: #FFF4D6;
    border-left: 3px solid var(--gold);
    padding: 14px 20px;
    margin: 0 auto;
    max-width: 1200px;
    font-size: 12px;
    color: #7A5C28;
    font-family: 'Jost', sans-serif;
    letter-spacing: 0.02em;
  }

  .note-marker strong {
    color: var(--gold);
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-size: 10px;
  }

  @media (max-width: 1100px) {
    .hero {
      grid-template-columns: 1fr;
    }

    .hero-image {
      min-height: 500px;
    }

    .category-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .editorial-split {
      grid-template-columns: 1fr;
      padding: 0 24px;
    }

    .giftcard {
      gap: 30px;
    }
  }

   @media (max-width: 1024px) {
      .products-grid {
        grid-template-columns: repeat(3, 1fr);
        column-gap: 15px;
      }
      .finder-inner {
        gap: 30px;
      }
  }

  @media (max-width: 767px) {
 
       .products-grid,
    .journal-grid {
      grid-template-columns:repeat(2, 1fr);
    }

    .bundles-grid,.experience-grid{
      grid-template-columns:repeat(1, 1fr);
    }

    .finder,.testimonial-section{
      padding: 40px 0;
    }
    .test-quote{
      font-size: 18px;
      line-height: 24px;
    }

    .press-logos{
      padding-top: 30px;
      margin-top: 30px;
    }

    .giftcard{
      padding: 0;
    }

    .value-strip { 
      grid-template-columns: 1fr 1fr;
    }

    .giftcard {
      grid-template-columns: 1fr;
    }

    .value-item:nth-child(2) {
      border-right: none;
    }

    .shipping-banner {
      grid-template-columns: 1fr;
      gap: 30px;
    }

    .shipping-timeline {
      gap: 5px;
    }
    .ship-step-date{
      font-size: 14px;
    }

    .brand-logos {
      gap: 20px;
    }

    .brand-logo {
      font-size: 16px;
    }

    .section-header-row {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }

    .bundles-section {
      padding: 50px 0;
    }
    .finder-inner {
        grid-template-columns: 1fr;
    }
  }

  /* ── FACETED FILTERS ── */
  .facets {
    border-top: 1px solid var(--hairline);
    border-bottom: 1px solid var(--hairline);
    padding: 20px 0;
    margin-bottom: 36px;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 28px
  }

  .facet {
    display: flex;
    flex-direction: column;
    gap: 7px
  }

  .facet-label {
    font-size: 9px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--muted)
  }

  .facet select {
    font-family: inherit;
    font-size: 12px;
    font-weight: 400;
    color: var(--charcoal);
    background: var(--warm-white);
    border: 1px solid var(--light-border);
    padding: 9px 30px 9px 12px;
    letter-spacing: .06em;
    appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='8' height='6'><path d='M0 0h8L4 6z' fill='%238A847C'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    cursor: pointer
  }

  .facet select:focus {
    outline: none;
    border-color: var(--gold)
  }

  .facet-toggles {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-left: auto
  }

  .facet-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--mid);
    border: 1px solid var(--light-border);
    padding: 9px 14px;
    cursor: pointer;
    user-select: none
  }

  .facet-chip .box {
    width: 11px;
    height: 11px;
    border: 1px solid var(--muted);
    display: inline-block;
    position: relative
  }

  .facet-chip.on {
    border-color: var(--charcoal);
    color: var(--charcoal)
  }

  .facet-chip.on .box {
    background: var(--gold);
    border-color: var(--gold)
  }

  .facet-count {
    font-size: 10px;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--muted)
  }

  .facet-reset {
    font-size: 10px;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--muted);
    background: none;
    border: none;
    font-family: inherit;
    cursor: pointer;
    text-decoration: underline;
    text-underline-offset: 3px
  }

  .facet-reset:hover {
    color: var(--charcoal)
  }

  .product-card.hidden {
    display: none
  }

  .empty-state {
    grid-column: 1/-1;
    text-align: center;
    padding: 70px 20px;
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-style: italic;
    color: var(--muted)
  }

  /* ── STOCK + VALUE BADGES / GWP ── */
  .stock-line {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 10px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted);
    margin-top: 9px
  }

  .stock-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0
  }

  .stock-ok .stock-dot {
    background: #5A8A5C
  }

  .stock-low .stock-dot {
    background: #C9A227
  }

  .stock-crit .stock-dot {
    background: #C2703F
  }

  .stock-low {
    color: #96772A
  }

  .stock-crit {
    color: #A85B30
  }

  .product-badge.value {
    background: var(--plum);
    color: var(--gold-pale);
    letter-spacing: .1em;
    white-space: nowrap
  }

 .gwp-line {
    margin-top: 9px;
    font-size: 10px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--charcoal);
    background: var(--gold-pale);
    display: inline-block;
    padding: 5px 9px
  }

  .gwp-line strong {
    font-weight: 600
  }

  @media (hover: none) {
    .product-quick {
      position: static;
      transform: none;
      margin-top: 8px
    }
  }

  /* Maxaroma storefront alignment: compact monochrome commerce UI */
  .hero {
    min-height: 78vh;
    background: #fff;
  }

  .hero-text {
    padding: 64px 56px;
  }

  .hero-heading {
    font-size: 32px;
    line-height: 40px;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .hero-heading em,
  .editorial-intro h2 em,
  .section-header h2 em,
  .section-header-row h2 em {
    font-style: normal;
    color: inherit;
  }


  .eyebrow {
    font-size: 10px;
    color: #000;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .brand-logo {
    font-size: 15px;
    font-weight: 600;
    letter-spacing: .04em;
    color: #333;
  }

  .value-strip {
    background: #000;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
  }

  .value-icon {
    border-color: #aaa;
    width: 28px;
    height: 28px;
  }

  .value-head {
    font-size: 10px;
    letter-spacing: .08em;
  }


  .category-grid {
    gap: 16px;
  }

  .cat-title {
    font-size: 18px;
    font-weight: 600;
    line-height: 24px;
    margin-bottom: 10px;
  }

  .product-image {
    background: #f7f7f7;
    margin-bottom: 12px;
  }

  .product-brand {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .05em;
  }

  .product-name {
    font-size: 12px;
    line-height: 1.45;
    font-weight: 500;
  }


  .product-notes,
  .stock-line {
    font-size: 10px;
  }

  .facets {
    gap: 16px;
    padding: 16px 0;
    margin-bottom: 28px;
  }

  .facet select {
    font-size: 11px;
    padding: 8px 28px 8px 10px;
  }

  .tier-tab {
    padding: 14px 22px;
    font-size: 10px;
  }

  @media (max-width: 1024px) {
    .hero{
      display: flex;
      flex-direction: column-reverse;
    }
    .hero-text {
      padding: 30px 15px 10px;
    }

  }

  .facet-chip,
  .tier-tab,
  .facet-reset,
  .gc-amount {
    border-radius: 0;
    text-transform: none;
  }


 .product-brand {
    color: #000;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: none;
  }

  .product-name {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 5px;
  }

  .product-notes {
    font-size: 11px;
    font-style: normal;
  }

  .product-rating,
  .stock-line {
    font-size: 10px;
  }

  .product-image {
    background: #fff;
    border-color: #e3e3e3;
  }

  .product-card:hover .product-image {
    border-color: #000;
  }

  .facets {
    background: #f7f7f7;
    border: 1px solid #e3e3e3;
    padding: 16px;
  }

  .facet-label {
    color: #000;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .08em;
  }

  .facet select {
    background-color: #fff;
    border-color: #ddd;
    color: #000;
  }

  .facet-chip {
    background: #fff;
    border-color: #000;
    color: #000;
    font-size: 11px;
    letter-spacing: 0;
  }

  .facet-chip.on {
    background: #000;
    border-color: #000;
    color: #fff;
  }

  .facet-chip.on .box {
    background: #fff;
    border-color: #fff;
  }

  .gift-tier{
    padding-bottom: 50px;
  }

  .tier-tabs {
    margin-bottom: 28px;
  }

  .tier-tab {
    color: #555;
    font-size: 12px;
    letter-spacing: 0;
  }

  .tier-tab-price {
    font-family: inherit;
    font-size: inherit;
    font-style: normal;
    color: inherit;
  }

  .brand-strip {
    background: #fff;
  }

  .brand-strip h2.eyebrow{
    font-size: 10px;
    margin-bottom: 0;
  }

  .value-strip {
    border-color: #ddd;
  }

  @media (max-width: 767px) {
   
    .hero-heading {
      font-size: 24px;
      line-height: 28px;
    }

  
    .facets {
      padding: 14px;
      gap: 12px;
    }

    .facet-toggles {
      margin-left: 0;
      flex-wrap: wrap;
    }
  }

  .container.md-container{max-width: 1208px;}

  .topspacing{margin-top: 70px;}

  .faq-bottotext{
    max-width: 900px;
    margin: 0 auto 72px;
    padding: 50px 48px;
    text-align: center;
    font-size: 11px;
    letter-spacing: .06em;
    line-height: 1.9;
    color: var(--muted);
  }

  @media (max-width: 1024px) {
    .hero-tile{ padding: 10px; }
    .hero-date-card { top: 15px; right: 15px; padding: 10px; }
    .hero-date-main { font-size: 24px; font-weight: 400;}
    .product-quick{display: none;}
    .section-header h2{ font-size: 18px; }
    .promo-dual-card h3,.finder h2,.shipping-headline,.giftcard-content h3,.newsletter h2{font-size: 24px;}
    .finder-step{padding: 20px;}
    .finder-step-title{font-size: 14px;}
    .finder-step-num{font-size: 32px;}
    .shipping-badge{min-width: inherit;}
  }
  
    @media (max-width: 767px) {
      .topspacing{ margin-top: 40px; }
      .section-header-row .eyebrow{margin-bottom: 5px;}
      .cat-title{ font-size: 16px; }
      .cat-content{ left: 10px; bottom: 10px; }
      .cat-eyebrow,.cat-title{ margin-bottom: 3px; }
      .category-grid{ gap:15px;}
      .products-grid{ gap:15px; row-gap: 40px; }
      .brand-strip .brand-logos .brand_box .inner_newbrand { display: flex; flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none; gap: 15px; justify-content: start;}
      .brand-strip .brand-logos .brand_box .inner_newbrand li{flex: 0 0 auto; width: 80px;}
      .brand-strip .section-header{padding-bottom: 20px;}
      .product-badges { top: 8px; left: 8px; gap: 5px; }
      .product-badge { font-size: 8px; padding: 5px; }
      .product-wishlist { top: 8px; right: 8px; width: 24px; height: 24px; }
      .gift-tier{ padding-bottom: 20px; } 
      .shipping-banner-sec,.newsletter{ padding: 40px 0; } 
      .shipping-badge{ padding: 20px; min-width: inherit;}
      .shipping-badge-main{ font-size: 36px; }
      .newsletter-incentive{ gap: 10px; }
      .ni-icon { font-size: 24px; }
      .faq-bottotext{ margin-bottom: 0; } 
      .faq-q span{ font-size: 12px;}

      .house-tile{padding: 15px;}

      .tier-tabs { display: flex; flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none; gap: 15px; justify-content: start;}
      .tier-tabs .tier-tab{flex: 0 0 auto; padding: 15px 5px;}      

      .house-origin{font-size: 10px;}

      .ship-step{text-align: center;}
}
	
</style>

@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="pageTitle">
    <section class="breadcrumb">
        <div class="container">
            <a href="{{config('global.SITE_URL')}}">Home</a>
            <span class="active">{{$pageName}}</span>
        </div>
    </section>
    
    <!-- Content Part Start Here -->
    @if(isset($LandingPageDetails) && $LandingPageDetails->count() > 0)
		@foreach($LandingPageDetails as $sectionName => $Details)
			
			@if($Details[0]->section_name == 'Hero Section')
			@foreach($Details as $detail)
			<section class="hero">
			  <div class="container">
				<div class="hero__content">
					<div class="hero__copy">
					  <span>{{$detail->banner_short_info}}</span>
					  <h1 class="hero__title">
						  {{$detail->banner_title}}
						  <span class="hero__subtitle">{{$detail->banner_subtitle}}</span>
					  </h1>
					  <p class="hero__paragraph">
						  {!!$detail->banner_desc!!}
					  </p>
					  <div class="">
						  <button class="btn btn-primary" type="button">
							  {{$detail->banner_link_title}}
						  </button>
					  </div>
					</div>

					<div class="hero__image">
					  <img
						  src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner}}"
						  alt="{{$detail->banner_title}}"
					  />
					</div>
				  </div>
				</div>
			</section>
			@endforeach
			@endif
			
			@if($Details[0]->section_name == 'EXCEPTIONAL OFFERS')
			<section class="section featured-section" id="featured">
			  <div class="container">
				  <div class="section-header">
					<h2 class="section-title">{{$Details[0]->section_name}}</h2>
					<div class="section-decor-bar"></div>
				  </div>
				  
				  <div class="featured-grid">
					@foreach($Details as $detail)
					@if($loop->iteration == 1)
					<div class="feature-card">
					  <img class="feature-card__image" src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner}}" alt="{{$detail->banner_title}}">
					  <div class="feature-card__overlay"></div>
					  <div class="feature-card__content">
						<span class="feature-card__label">{{$detail->banner_short_info}}</span>
						<h3>{{$detail->banner_title}}</h3>
						<p>{{$detail->banner_subtitle}}</p>
						<a href="{{$detail->banner_link}}">{{$detail->banner_link_title}}</a>
					  </div>
					</div>
					@endif
					@endforeach
					
					<div class="small-cards">
					  @foreach($Details as $detail)
					  @if($loop->iteration == 2  || $loop->iteration == 3)
					  <div class="card">
						<img class="card__image" src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner}}" alt="{{$detail->banner_title}}">
						<div class="feature-card__overlay"></div>
						<div class="feature-card__content">
							<!-- <span class="feature-card__label">MOST WANTED</span> -->
							<h3>{{$detail->banner_title}}</h3>
							<p>{{$detail->banner_subtitle}}</p>
							<a href="{{$detail->banner_link}}">{{$detail->banner_link_title}}</a>
						</div>
					  </div>
					  @endif
					  @endforeach
					</div>
				  </div>
				</div>
			</section>
			@endif
			
			@if($Details[0]->section_name == 'DISCOVER BY VALUE')
			<section class="value-section" id="budget">
			  <div class="container">
				<div class="section-header">
				  <h2 class="section-title">{{$Details[0]->section_name}}</h2>
				  <p class="value-intro">{{$Details[0]->description}}</p>
				</div>

				<div class="value-grid">
				  @foreach($Details as $detail)
				  @if($loop->iteration == 2)
				  <div class="value-card value-card--featured">
				  @else
				  <div class="value-card">
				  @endif
					<span>{{$detail->banner_title}}</span>
					<p>{!!$detail->banner_desc!!}</p>
					<button class="btn btn-secondary" type="button">{{$detail->banner_link_title}}</button>
				  </div>
				  @endforeach
				</div>
			  </div>
			</section>
			@endif
			
			@if($Details[0]->section_name == 'THE CURATOR SECTION')
			@foreach($Details as $detail)
			<section class="curated" id="curated">
			  <div class="container">
				<div class="curated-grid">
				  <div class="curated-image">
					<div class="curated-image__decor"></div>
					<img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner}}" alt="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner_title}}">
					<div class="curated-quote">
					  <p>{{$detail->banner_subtitle}}</p>
					</div>
				  </div>

				  <div class="curated-copy">
					<span>{{$detail->banner_short_info}}</span>
					<h2>{{$detail->banner_title}}</h2>
					{!!$detail->banner_desc!!}
				  </div>
				</div>
			  </div>
			</section>
			@endforeach
			@endif
			
			@if($Details[0]->section_name == 'Shop by Moment')
			<section id="gifts" class="topspacing">
				<div class="container">
				  <div class="section-header-row">
					<div>
					  <span class="eyebrow">{{$Details[0]->section_name}}</span>
					  <h2>{{$Details[0]->description}}</h2>
					</div>
					<a href="#">View all categories</a>
				  </div>
				  <div class="category-nav">
					<div class="category-grid">
						@foreach($Details as $detail)
						<a href="#" class="cat-card">
							<img src="{{ config('global.SPEED_SIZE_URL')}}{{config('global.SITE_URL')}}images/landing/advanced_landing_pages/{{$detail->banner}}" alt="{{$detail->banner_title}}" class="brand-image" loading="lazy" width="440" height="530">
							<div class="cat-content">
								<div class="cat-eyebrow">{{$detail->banner_short_info}}</div>
								<div class="cat-title">{{$detail->banner_title}}</div>
								<div class="cat-meta">{{$detail->banner_subtitle}}</div>
							</div>
						</a>
						@endforeach
					</div>
				  </div>
				</div>
			</section>
			@endif
			
			@if($Details[0]->section_name == '2 Blocks')
			<section class="topspacing">
				<div class="container">
				  <div class="promo-dual">
					  @foreach($Details as $detail)
					  @if($loop->iteration == 1)
					  <div class="promo-dual-card promo-dual-a">
					  @else
					  <div class="promo-dual-card promo-dual-b">
					  @endif
						<span class="eyebrow">{{$detail->banner_short_info}}</span>
						<h3>{{$detail->banner_title}}</h3>
						<p>{!!$detail->banner_desc!!}</p>
						<a href="{{$detail->banner_link}}" class="btn btn-secondary">{{$detail->banner_link_title}} </a>
					  </div>
					  @endforeach
				  </div>
				</div>
			</section>
			@endif
			
			@if($Details[0]->section_name == 'Gifting, done beautifully')
			<section class="experience topspacing">
				<div class="container">
					<div class="section-header">
						<span class="eyebrow">{{$Details[0]->description}}</span>
						<h2>{{$Details[0]->section_name}}</h2>
						<div class="rule"></div>
					</div>
					<div class="experience-grid">
						@foreach($Details as $detail)
						<div class="exp-card">
							<div class="exp-num">{{$detail->banner_short_info}}</div>
							<div class="exp-title">{{$detail->banner_title}}</div>
							{!!$detail->banner_desc!!}
							<a href="{{$detail->banner_link}}" class="exp-link">{{$detail->banner_link_title}}</a>
						</div>
						@endforeach
					</div>
				</div>
			</section>
			@endif
			
		@endforeach
    @endif
    
    
    <?php /* ?>
    <section class="offer-nav">
      <div class="offer-nav__inner no-scrollbar">
        <a class="active" href="#featured">FEATURED OFFERS</a>
        <a href="#budget">SHOP BY BUDGET</a>
        <a href="#new">NEW ARRIVALS</a>
        <a href="#curated">CURATOR'S CHOICE</a>
        <a href="#club">AROMA CLUB</a>
      </div>
    </section>
    <section class="section featured-section" id="featured">
      <div class="container">
          <div class="section-header">
            <h2 class="section-title">EXCEPTIONAL OFFERS</h2>
            <div class="section-decor-bar"></div>
          </div>

          <div class="featured-grid">
            <div class="feature-card">
              <img class="feature-card__image" src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com//images/landing/banner_27.jpg?ver=1744141732" alt="Close-up artistic shot of premium fragrance ingredients arranged elegantly on a textured linen surface.">
              <div class="feature-card__overlay"></div>
              <div class="feature-card__content">
                <span class="feature-card__label">MOST WANTED</span>
                <h3>NICHE ESSENTIALS</h3>
                <p>Up to 40% Off Select Artisanal Brands</p>
                <a href="#">SHOP NOW</a>
              </div>
            </div>

            <div class="small-cards">
              <div class="card">
                <img class="card__image" src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com//images/landing/banner_32.jpg?ver=1744141753" alt="A minimalist arrangement of three sleek perfume bottles on a geometric concrete pedestal.">
                <div class="feature-card__overlay"></div>
                <div class="feature-card__content">
                    <!-- <span class="feature-card__label">MOST WANTED</span> -->
                    <h3>GIFT SETS</h3>
                    <p>THE PERFECT GESTURE</p>
                    <a href="#">SHOP NOW</a>
                </div>
              </div>

              <div class="card">
                <img class="card__image" src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com//images/landing/banner_33.png?ver=1755796443" alt="An atmospheric macro shot of gold leaf floating in dark perfume oil.">
                <div class="feature-card__overlay"></div>
                <div class="feature-card__content">
                    <!-- <span class="feature-card__label">MOST WANTED</span> -->
                    <h3>COLLECTOR PIECES</h3>
                    <p>LIMITED STOCK</p>
                    <a href="#">SHOP NOW</a>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
    <section class="value-section" id="budget">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">DISCOVER BY VALUE</h2>
          <p class="value-intro">LUXURY AT EVERY PRICE POINT</p>
        </div>

        <div class="value-grid">
          <div class="value-card">
            <span>UNDER $100</span>
            <p>Exceptional quality from top designer houses.</p>
            <button class="btn btn-secondary" type="button">SHOP SELECTION</button>
          </div>

          <div class="value-card value-card--featured">
            <span>$100 - $250</span>
            <p>Signature scents and prestigious best-sellers.</p>
            <button class="btn btn-secondary" type="button">SHOP SELECTION</button>
          </div>

          <div class="value-card">
            <span>$250+ LUXE</span>
            <p>Ultra-niche extrait de parfums and rare finds.</p>
            <button class="btn btn-secondary" type="button">SHOP SELECTION</button>
          </div>
        </div>
      </div>
    </section>
    <section class="new-arrivals" id="new">
      <div class="new-arrivals__header container">
        <div>
          <h2>NEW ARRIVALS</h2>
          <p>The latest olfactory discoveries.</p>
        </div>
      </div>

      <div class="luxurys-slider carousel no-scrollbar">
        <div class="product-card">
          <div class="product-card__image">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_2_3.jpg?ver=1784563995" alt="HOUSE Of DASTAN" class="brand-image" loading="lazy" width="440" height="530">
            <button class="quick-add btn btn-secondary" type="button">QUICK ADD</button>
          </div>
          <div class="product-card__meta">
            <p class="brand">AMOUAGE</p>
            <h4>Guidance Eau De Parfum</h4>
            <p class="price">$360.00</p>
          </div>
        </div>

        <div class="product-card">
          <div class="product-card__image">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_2_4.jpg?ver=1784564146" alt="OMANLUXURY" class="brand-image" loading="lazy" width="440" height="530">
            <button class="quick-add btn btn-secondary" type="button">QUICK ADD</button>
          </div>
          <div class="product-card__meta">
            <p class="brand">PARFUMS DE MARLY</p>
            <h4>Delina Exclusif</h4>
            <p class="price">$375.00</p>
          </div>
        </div>

        <div class="product-card">
          <div class="product-card__image">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_2_5.jpg?ver=1784564007" alt="MES BISOUS" class="brand-image" loading="lazy" width="440" height="530">
            <button class="quick-add btn btn-secondary" type="button">QUICK ADD</button>
          </div>
          <div class="product-card__meta">
            <p class="brand">BYREDO</p>
            <h4>Bal d'Afrique</h4>
            <p class="price">$205.00</p>
          </div>
        </div>

        <div class="product-card">
          <div class="product-card__image">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_4_6.jpg?ver=1780564424" alt="SPIRIT OF DUBAI" class="brand-image" loading="lazy" width="440" height="530">
            <button class="quick-add btn btn-secondary" type="button">QUICK ADD</button>
          </div>
          <div class="product-card__meta">
            <p class="brand">XERJOFF</p>
            <h4>Erba Pura</h4>
            <p class="price">$310.00</p>
          </div>
        </div>

        <div class="product-card">
          <div class="product-card__image">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_4_7.jpg?ver=1780564425" alt="PANTHEON ROMA" class="brand-image" loading="lazy" width="440" height="530">
            <button class="quick-add btn btn-secondary" type="button">QUICK ADD</button>
          </div>
          <div class="product-card__meta">
            <p class="brand">XERJOFF</p>
            <h4>Erba Pura</h4>
            <p class="price">$310.00</p>
          </div>
        </div>

      </div>
    </section>

    <section class="curated" id="curated">
      <div class="container">
        <div class="curated-grid">
          <div class="curated-image">
            <div class="curated-image__decor"></div>
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuByJPZlvJTLkMKpZsnN_JkSbnEMnStKvahc13nKanHX-M_5aLUXDTXpkf18EO3BomxoOUN9IIHK_k2rk-ojw-jw8_QZQHjHXKUhUMnsaCLqLIFEmpTpwWy_hXNpUXehDnMeMjTpDPjiHe_ExqX2vHm9zQ6eXqg8B3sWNE5KdUwzSbedxVs2w_Y8E7glVdwpPFD4nh-aUwoO0LfquIVKYTX9ZSVRroieojYhN4B5wTwCivH8mtEgBX3e" alt="An artistic photograph of a perfume curator's desk with amber vials and a leather-bound notebook.">
            <div class="curated-quote">
              <p>"Scent is the most intense form of memory."</p>
            </div>
          </div>

          <div class="curated-copy">
            <span>EDITORIAL</span>
            <h2>THE CURATOR'S SELECTION</h2>
            <p>
              Our lead curator, Elena Vance, explores the delicate balance of oud and amber
              in this season's most compelling releases. Each selection represents a pinnacle
              of technical skill and creative vision.
            </p>
            <ul class="curated-list">
              <li>
                <span>01. The Art of Layering</span>
                <span class="material-symbols-outlined"><svg class="svg svg-slick-right" width="18" height="18"><use href="#sv-right-arrow"></use></svg></span>
              </li>
              <li>
                <span>02. Sustainable Luxury</span>
                <span class="material-symbols-outlined"><svg class="svg svg-slick-right" width="18" height="18"><use href="#sv-right-arrow"></use></svg></span>
              </li>
              <li>
                <span>03. Rare Ingredients</span>
                <span class="material-symbols-outlined"><svg class="svg svg-slick-right" width="18" height="18"><use href="#sv-right-arrow"></use></svg></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section class="trust-section" id="club">
        <div class="container">
          <div class="trust-grid">
              <div class="trust-card">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#8B6B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Starburst Badge -->
                    <path d="M12 2l2.3 1.8 2.9-.6 1.1 2.7 2.8 1.1-.6 2.9 1.8 2.3-1.8 2.3.6 2.9-2.8 1.1-1.1 2.7-2.9-.6L12 22l-2.3-1.8-2.9.6-1.1-2.7-2.8-1.1.6-2.9L1.7 12l1.8-2.3-.6-2.9 2.8-1.1 1.1-2.7 2.9.6L12 2z" />
                    <!-- Inner Checkmark -->
                    <path d="M9 12l2 2 4-4" />
                  </svg>

                </span>
                <h3>100% AUTHENTIC</h3>
                <p>Every bottle is sourced directly from authorized brand distributors.</p>
              </div>

              <div class="trust-card">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#8B6B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Truck Body & Cabin -->
                    <path d="M2 5h11v11H2z" />
                    <path d="M13 8h4l3 3v5h-7V8z" />
                    <!-- Left Wheel -->
                    <circle cx="5.5" cy="17.5" r="2" fill="none" />
                    <!-- Right Wheel -->
                    <circle cx="16.5" cy="17.5" r="2" fill="none" />
                  </svg>

                </span>
                <h3>PRIORITY SHIPPING</h3>
                <p>Complimentary express shipping on all orders over $150.</p>
              </div>

              <div class="trust-card">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#8B6B18" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Headset band over head -->
                    <path d="M4 14a8 8 0 0 1 16 0" />
                    
                    <!-- Head outline / hair -->
                    <path d="M6.5 13.5c0-3 2.5-5.5 5.5-5.5s5.5 2.5 5.5 5.5" />
                    
                    <!-- Left earpad -->
                    <rect x="3" y="12" width="2" height="4" rx="1" fill="#8B6B18" />
                    
                    <!-- Right earpad -->
                    <rect x="19" y="12" width="2" height="4" rx="1" fill="#8B6B18" />
                    
                    <!-- Eyes -->
                    <circle cx="9.5" cy="14" r="0.75" fill="#8B6B18" stroke="none" />
                    
                    <circle cx="14.5" cy="14" r="0.75" fill="#8B6B18" stroke="none" />
                    
                    <!-- Smile -->
                    <path d="M10.5 16.5a1.5 1.5 0 0 0 3 0" />
                    
                    <!-- Microphone arm -->
                    <path d="M19 15v2a2 2 0 0 1-2 2h-3" />
                  </svg>
                </span>
                <h3>CONCIERGE SERVICE</h3>
                <p>Our fragrance experts are available for private consultations.</p>
              </div>
            </div>
          </div>
    </section>
	<? */ ?>



    <!-- Content Part End Here -->
	<script>
		$(document).ready(function () {
			$('.luxurys-slider').slick({
				lazyLoad: 'ondemand',
				dots: false,
				infinite: false,
				speed: 300,
				arrows: true,
				slidesToShow: 4,
				slidesToScroll: 2,
				accessibility: true,
				responsive: [
				{
					breakpoint: 1200,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						arrows: false,
						dots: true
					}
				}
				],
				nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next slide"><svg class="svg svg-slick-right" width="24" height="24"><use href="#sv-right-arrow"></use></svg></button>',
				prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous slide"><svg class="svg svg-slick-left" width="24" height="24"><use href="#sv-left-arrow"></use></svg></button>'
			});
		});
	</script>  
</main>
       
@endsection
