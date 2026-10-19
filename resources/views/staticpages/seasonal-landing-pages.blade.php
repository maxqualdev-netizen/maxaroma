@extends('layouts.app')
@section('content')
<style>
  :root {
    --gold: #000000;
    --gold-light: #1a1a1a;
    --gold-pale: #ffffff;
    --cream: #F7F5F5;
    --warm-white: #ffffff;
    --charcoal: #111111;
    --ink: #222222;
    --mid: #4d4d4d;
    --muted: #777777;
    --light-border: #dddddd;
    --hairline: #eeeeee;
    --rose: #333333;
    --blush: #e9e9e9;
    --plum: #111111;
  }

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
<main role="main" id="main-content" tabindex="-1" aria-labelledby="pageTitle">
  <!-- Editorial Hero -->
  <section class="hero">
    <div class="hero-text">
      <div class="hero-eyebrow-row">
        <span class="rule"></span>
        <span class="eyebrow">The Mother's Day Edit · Vol. 04</span>
      </div>
      <h1 class="hero-heading">For the one<br>who taught you<br><em>what beauty</em> means.</h1>
      <p class="hero-sub">A curated collection of rare and celebrated fragrances, chosen by our perfumers for the most important gift of the year.</p>
      <div class="hero-cta-row">
        <a href="#gifts" class="btn btn-primary">Shop the edit </a>
        <a href="#finder" class="hero-cta-link">Take the scent quiz</a>
      </div>
      <div class="hero-meta">
        <div class="hero-meta-item">
          <span class="hero-meta-label">Pieces</span>
          <span class="hero-meta-val">42</span>
        </div>
        <div class="hero-meta-item">
          <span class="hero-meta-label">Houses</span>
          <span class="hero-meta-val">18</span>
        </div>
        <div class="hero-meta-item">
          <span class="hero-meta-label">Delivered by</span>
          <span class="hero-meta-val serif">May 10</span>
        </div>
      </div>
    </div>
    <div class="hero-image">
      <div class="hero-collage">
        <div class="hero-tile hero-tile-1" style="background: url('https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/1036_maxedit_top_sec_slider_image2.jpg?ver=1785278671');     background-size: cover;
    background-repeat: no-repeat;">
          <span class="hero-tile-label">Vol. 04 — Mother's Day</span>
        </div>
        <div class="hero-tile hero-tile-2" style="background: url('https://www.maxaroma.com/images/maxaroma-edit/872_maxedit_sec12_image.jpg?ver=1780564212'); background-size: cover;
    background-repeat: no-repeat;">
          <span class="hero-tile-brand">Amouage</span>
        </div>
        <div class="hero-tile hero-tile-3" style="background: url('https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/986_maxedit_bottom_sec_image.jpg?ver=1780564215');     background-size: cover;
    background-repeat: no-repeat;">
          <span class="hero-tile-brand">Creed</span>
        </div>
      </div>
      <div class="hero-date-card">
        <div class="hero-date-main"><small>SUNDAY</small>May 11</div>
        <div class="hero-date-label">Mother's Day</div>
      </div>
    </div>
  </section>

  <!-- Brand strip -->
  <section class="brand-strip topspacing">
    <div class="container">
        <div class="section-header">
          <h2 class="eyebrow">As featured From the world's finest houses</h2>
        </div>
        <div class="brand-logos">
          <div class="brand_box">
            <ul class="inner_newbrand" role="list" aria-label="Popular Brands">

              <li role="listitem">
                <a href="https://www.maxaroma.com/creed/smid-14" aria-label="Creed">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_14.jpg" alt="Creed" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/house-of-dastan/smid-1031" aria-label="House Of Dastan">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1031.png" alt="House Of Dastan" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/initio-parfums/smid-534" aria-label="Initio Parfums">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_534.png" alt="Initio Parfums" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/mmicallef/smid-775" aria-label="M.MICALLEF">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_775.png" alt="M.MICALLEF" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/mes-bisous/smid-987" aria-label="Mes Bisous">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_987.jpg" alt="Mes Bisous" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/montale/smid-425" aria-label="Montale">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_425.jpg" alt="Montale" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/nishane/smid-812" aria-label="Nishane">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_812.png" alt="Nishane" width="108" height="73">
                </a>
              </li>
              <li role="listitem">
                <a href="https://www.maxaroma.com/ojar/smid-1001" aria-label="OJAR">
                  <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1001.jpg" alt="OJAR" width="108" height="73">
                </a>
              </li>
          </div>
          </ul>
        </div>
    </div>
  </section>

  <!-- Value strip -->
  <section class="topspacing">
    <div class="container">
      <div class="value-strip">
        <div class="value-item">
          <div class="value-icon">✦</div>
          <div class="value-text">
            <div class="value-head">100% Authentic</div>
            <div class="value-sub">Direct from the houses</div>
          </div>
        </div>
        <div class="value-item">
          <div class="value-icon">◆</div>
          <div class="value-text">
            <div class="value-head">Free 2-Day Ship</div>
            <div class="value-sub">Orders <span id="vs-ship">$220</span>+ (May 3–6)</div>
          </div>
        </div>
        <div class="value-item">
          <div class="value-icon">❀</div>
          <div class="value-text">
            <div class="value-head">Complimentary Wrap</div>
            <div class="value-sub">On every Mother's Day order</div>
          </div>
        </div>
        <div class="value-item">
          <div class="value-icon">✧</div>
          <div class="value-text">
            <div class="value-head">Expert Concierge</div>
            <div class="value-sub">Personal gift advice, free</div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <!-- Shop by Category -->
  <section id="gifts" class="topspacing">
    <div class="container">
      <div class="section-header-row">
        <div>
          <span class="eyebrow">Shop by Moment</span>
          <h2>How will you <em>tell her</em>?</h2>
        </div>
        <a href="#">View all categories</a>
      </div>
      <div class="category-nav">
        <div class="category-grid">
          <a href="#" class="cat-card">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_6_14.jpg?ver=1780564427" alt="MERCHANT OF VENICE" class="brand-image" loading="lazy" width="440" height="530">
            <div class="cat-content">
              <div class="cat-eyebrow">Tier One</div>
              <div class="cat-title">Thoughtful <em>under</em> <span>$150</span></div>
              <div class="cat-meta">12 curated pieces</div>
            </div>
          </a>
          <a href="#" class="cat-card">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_6_15.jpg?ver=1780564428" alt="PRECIOUS LIQUID" class="brand-image" loading="lazy" width="440" height="530">
            <div class="cat-content">
              <div class="cat-eyebrow">Tier Two</div>
              <div class="cat-title">Elevated <em>under</em> <span>$250</span></div>
              <div class="cat-meta">14 curated pieces</div>
            </div>
          </a>
          <a href="#" class="cat-card">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_6_16.jpg?ver=1780564429" alt="SPIRIT OF KINGS" class="brand-image" loading="lazy" width="440" height="530">
            <div class="cat-content">
              <div class="cat-eyebrow">Tier Three</div>
              <div class="cat-title">Extraordinary <span>$400+</span></div>
              <div class="cat-meta">10 rare selections</div>
            </div>
          </a>
          <a href="#" class="cat-card">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/images/maxaroma-edit/maxaroma_edit_banner_6_9.jpg?ver=1780564431" alt="MERCHANT OF VENICE" class="brand-image" loading="lazy" width="440" height="530">
            <div class="cat-content">
              <div class="cat-eyebrow">Curated</div>
              <div class="cat-title">Limited <em>gift sets</em></div>
              <div class="cat-meta">3 exclusive bundles</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Filterable tier tabs + products -->
  <section class="gift-tier topspacing" id="the-edit">
    <div class="container">
      <div class="tier-tabs" id="tier-tabs" role="group" aria-label="Price tier">
        <button class="tier-tab active" data-tier="all">All the Edit</button>
        <button class="tier-tab" data-tier="150">Under <span class="tier-tab-price">$150</span></button>
        <button class="tier-tab" data-tier="250">Under <span class="tier-tab-price">$250</span></button>
        <button class="tier-tab" data-tier="400">Above <span class="tier-tab-price">$400</span></button>
        <button class="tier-tab" data-tier="new">New Arrivals</button>
      </div>
      <div class="facets" id="facets">
        <div class="facet"><span class="facet-label">Recipient</span>
          <select id="f-recipient">
            <option value="all">Anyone</option>
            <option value="her">For Her</option>
            <option value="him">For Him</option>
            <option value="unisex">Unisex</option>
          </select>
        </div>
        <div class="facet"><span class="facet-label">Budget</span>
          <select id="f-budget">
            <option value="all">Any price</option>
            <option value="0-150">Under $150</option>
            <option value="150-300">$150 – $300</option>
            <option value="300-9999">$300 and above</option>
          </select>
        </div>
        <div class="facet"><span class="facet-label">Notes &amp; family</span>
          <select id="f-family">
            <option value="all">All families</option>
            <option value="floral">Floral</option>
            <option value="amber">Amber &amp; oriental</option>
            <option value="woody">Woody</option>
            <option value="fresh">Fresh &amp; citrus</option>
            <option value="gourmand">Gourmand</option>
          </select>
        </div>
        <div class="facet"><span class="facet-label">House tier</span>
          <select id="f-house">
            <option value="all">All houses</option>
            <option value="maison">Grand maison</option>
            <option value="niche">Niche</option>
            <option value="indie">Independent</option>
          </select>
        </div>
        <div class="facet"><span class="facet-label">Sort</span>
          <select id="f-sort">
            <option value="featured">Featured</option>
            <option value="best">Best sellers</option>
            <option value="new">New arrivals</option>
            <option value="low">Price: low to high</option>
            <option value="high">Price: high to low</option>
          </select>
        </div>
        <div class="facet-toggles">
          <button type="button" class="facet-chip on" id="f-stock" aria-pressed="true"><span class="box" aria-hidden="true"></span>In stock only</button>
          <button type="button" class="facet-chip" id="f-sets" aria-pressed="false"><span class="box" aria-hidden="true"></span>Gift sets</button>
          <span class="facet-count" id="f-count" role="status" aria-live="polite">8 fragrances</span>
          <button class="facet-reset" id="f-reset">Clear</button>
        </div>
      </div>
      <div class="products-grid" id="products-grid">
        <a href="#" class="product-card" data-price="149" data-recipient="her" data-family="floral" data-house="indie" data-stock="ok" data-new="0" data-best="1" data-set="0" data-sku="ANF-SAL-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UPT8055773549128.jpg?ver=1784044008" alt="Viktor &amp; Rolf SpiceBomb Extreme bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge gold">Best Seller</span><span class="product-badge value">$30 bottle credit</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Anfas</div>
          <div class="product-name">Salam Eau de Parfum</div>
          <div class="stock-line stock-ok"><span class="stock-dot"></span>In stock — ships today</div>
          <div class="product-notes">Rose · saffron · amber</div>
          <div class="product-price-row">
            <div class="product-price">$149</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.9 (212)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="152" data-recipient="her" data-family="gourmand" data-house="niche" data-stock="low" data-new="1" data-best="0" data-set="0" data-sku="OJR-HLW-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UP3665032006795.jpg?ver=1754629919" alt="Creed Aventus bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge">Mother's Day Pick</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Ojar</div>
          <div class="product-name">Halwa Kiss EDP</div>
          <div class="stock-line stock-low"><span class="stock-dot"></span>Low stock — 9 remaining</div>
          <div class="product-notes">Pistachio · rose · honey</div>
          <div class="product-price-row">
            <div class="product-price">$152</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.8 (87)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="249" data-recipient="unisex" data-family="fresh" data-house="maison" data-stock="ok" data-new="0" data-best="1" data-set="1" data-sku="CRD-SWM-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UP3508441001145.jpg?ver=1783615479" alt="Xerjoff Erba Pura bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge gold">Top Rated</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Creed</div>
          <div class="product-name">Silver Water Millesime</div>
          <div class="stock-line stock-ok"><span class="stock-dot"></span>In stock — ships today</div>
          <div class="gwp-line"><strong>$107 value</strong> free — 3-pc discovery set</div>
          <div class="product-notes">Bergamot · mandarin · musk</div>
          <div class="product-price-row">
            <div class="product-price"><span class="orig">$285</span>$249</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.9 (340)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="450" data-recipient="her" data-family="floral" data-house="maison" data-stock="crit" data-new="0" data-best="0" data-set="0" data-sku="ROJ-ELX-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/productimages/large/UP3605521932105.jpg" alt="Maison Margiela Replica Jazz Club bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge">Rare</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">ROJA Parfums</div>
          <div class="product-name">Elixir Essence de Parfum</div>
          <div class="stock-line stock-crit"><span class="stock-dot"></span>Only 3 left — allocated stock</div>
          <div class="product-notes">Jasmine · tuberose · oud</div>
          <div class="product-price-row">
            <div class="product-price">$450</div>
            <div class="product-rating"><span class="stars">★★★★★</span>5.0 (56)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="240" data-recipient="unisex" data-family="woody" data-house="indie" data-stock="ok" data-new="0" data-best="0" data-set="0" data-sku="ALG-KOF-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UP8681619761036.jpg?ver=1770863068" alt="Xerjoff Casamorati 1888 bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge value">$40 bottle credit</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Alghabra</div>
          <div class="product-name">King of Flowers EDP</div>
          <div class="stock-line stock-ok"><span class="stock-dot"></span>In stock — ships today</div>
          <div class="product-notes">Magnolia · iris · sandalwood</div>
          <div class="product-price-row">
            <div class="product-price">$240</div>
            <div class="product-rating"><span class="stars">★★★★☆</span>4.7 (124)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="148" data-recipient="unisex" data-family="woody" data-house="niche" data-stock="ok" data-new="0" data-best="1" data-set="0" data-sku="BDK-GRC-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UP3760035451761.jpg?ver=1770758140" alt="MaxAroma fragrance bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge">Limited</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">BDK Parfums</div>
          <div class="product-name">Gris Charnel EDP</div>
          <div class="stock-line stock-ok"><span class="stock-dot"></span>In stock — ships today</div>
          <div class="product-notes">Fig · tea · sandalwood</div>
          <div class="product-price-row">
            <div class="product-price">$148</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.8 (198)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="415" data-recipient="her" data-family="amber" data-house="maison" data-stock="low" data-new="0" data-best="1" data-set="1" data-sku="AMG-INT-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/P701666315124.jpg?ver=1729742308" alt="MaxAroma fragrance bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge gold">Bestseller</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Amouage</div>
          <div class="product-name">Interlude Woman EDP</div>
          <div class="stock-line stock-low"><span class="stock-dot"></span>Low stock — 6 remaining</div>
          <div class="gwp-line"><strong>$107 value</strong> free — 3-pc discovery set</div>
          <div class="product-notes">Opoponax · amber · kiwi</div>
          <div class="product-price-row">
            <div class="product-price"><span class="orig">$490</span>$415</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.9 (176)</div>
          </div>
        </a>
        <a href="#" class="product-card" data-price="480" data-recipient="him" data-family="woody" data-house="indie" data-stock="ok" data-new="1" data-best="0" data-set="0" data-sku="R15-PSY-100" data-ga="product_click">
          <div class="product-image">
            <div class="product-image-inner"><img src="https://www.maxaroma.com/productimages/large/UP3683080649740.jpg?ver=1765383931" alt="MaxAroma fragrance bottle" loading="lazy"></div>
            <div class="product-badges"><span class="product-badge gold">New</span></div>
            <button type="button" class="product-wishlist" aria-label="Add to wishlist"><svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
              </svg></button>
            <div class="product-quick">+ Quick Add</div>
          </div>
          <div class="product-brand">Room 1015</div>
          <div class="product-name">Psychedélique EDP</div>
          <div class="stock-line stock-ok"><span class="stock-dot"></span>In stock — ships today</div>
          <div class="product-notes">Violet · leather · patchouli</div>
          <div class="product-price-row">
            <div class="product-price">$480</div>
            <div class="product-rating"><span class="stars">★★★★★</span>4.8 (42)</div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Promotional banner: double the rewards -->
  <section class="topspacing">
    <div class="container">
      <div class="promo-dual">
          <div class="promo-dual-card promo-dual-a">
            <span class="eyebrow">Member Perk</span>
            <h3>Earn <em>2&times; points</em><br>on every Mother's Day order.</h3>
            <p>Maxaroma Rewards members automatically earn double points April 24 – May 10. Redeem toward your next luxury fragrance.</p>
            <a href="#" class="btn btn-secondary">Join rewards — it's free </a>
          </div>
          <div class="promo-dual-card promo-dual-b">
            <span class="eyebrow">Stackable Offer</span>
            <h3 style="color:var(--charcoal);">Spend <em>$400</em>,<br>choose a <em>free deluxe sample.</em></h3>
            <p style="color:var(--mid);">Add any qualifying Mother's Day purchase of $400+ and select one of six complimentary 7.5ml atomizers at checkout.</p>
            <a href="#" class="btn btn-secondary">See eligible samples </a>
          </div>
      </div>
    </div>
  </section>

  <!-- Fragrance Finder / Concierge -->
  <section class="finder topspacing" id="finder">
    <div class="container">
      <div class="finder-inner">
        <div>
          <span class="eyebrow">The Maxaroma Concierge</span>
          <h2>Not sure where<br>to begin? <em>Let us help.</em></h2>
          <p>Answer five short questions about the woman you're gifting — her style, her memories, the scents she's always loved — and our perfumers will personally curate three fragrances chosen just for her.</p>
          <div class="finder-bullets">
            <div class="finder-bullet">Answered by a human perfumer, never a bot</div>
            <div class="finder-bullet">Complimentary 30-min video consultation available</div>
            <div class="finder-bullet">Free samples shipped before you commit</div>
          </div>
          <div class="finder-cta-row">
            <a href="#" class="btn btn-secondary">Start the 2-min quiz </a>
            <a href="#" class="hero-cta-link-white">Book a concierge call</a>
          </div>
        </div>
        <div class="finder-visual">
          <div class="finder-steps">
            <div class="finder-step">
              <div class="finder-step-num">01</div>
              <div>
                <div class="finder-step-title">Her story</div>
                <div class="finder-step-desc">Share a memory, a signature, a place she loves</div>
              </div>
            </div>
            <div class="finder-step">
              <div class="finder-step-num">02</div>
              <div>
                <div class="finder-step-title">Scent family</div>
                <div class="finder-step-desc">Florals, orientals, woods, fresh</div>
              </div>
            </div>
            <div class="finder-step">
              <div class="finder-step-num">03</div>
              <div>
                <div class="finder-step-title">Occasion</div>
                <div class="finder-step-desc">Daily wear, evenings, special moments</div>
              </div>
            </div>
            <div class="finder-step">
              <div class="finder-step-num">04</div>
              <div>
                <div class="finder-step-title">The reveal</div>
                <div class="finder-step-desc">Three curated picks, with notes from our team</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bundles -->
  <section id="bundles" class="bundles-section">
    <div class="container">
      <div class="section-header">
        <span class="eyebrow">Limited Edition</span>
        <h2>Gift sets, <em>considered</em></h2>
        <p class="section-desc">Three bundles hand-assembled for Mother's Day — complete with our signature keepsake box, silk ribbon, and handwritten card.</p>
        <div class="rule"></div>
      </div>
      <div class="bundles-grid">
        <div class="bundle-card">
          <div class="bundle-image">
            <img src="https://www.maxaroma.com/productimages/large/UP381519190315.jpg?ver=1714660778" width="280" height="280" alt="Viktor &amp; Rolf SpiceBomb Extreme bottle" loading="lazy">
          </div>
          <div class="bundle-name">The Essentials</div>
          <div class="bundle-desc">A duo of day-to-evening fragrances — effortless, versatile, unmistakably hers.</div>
          <ul class="bundle-includes">
            <li>2 full-size EDPs (30ml each)</li>
            <li>Branded keepsake box + silk ribbon</li>
            <li>Handwritten gift card included</li>
          </ul>
          <div class="bundle-price-row">
            <span class="bundle-price">$185</span>
            <span class="bundle-was">$220</span>
            <span class="bundle-save">Save $35</span>
          </div>
          <button class="btn btn-secondary">Add to Bag</button>
        </div>
        <div class="bundle-card featured">
          <div class="bundle-featured-tag">Best Seller</div>
          <div class="bundle-image">
            <img src="https://www.maxaroma.com/productimages/large/UP3616305277114.jpg?ver=1765823864" width="280" height="280" alt="MaxAroma fragrance bottle" loading="lazy">
          </div>
          <div class="bundle-name">The Luxe Trio</div>
          <div class="bundle-desc">Three most-gifted luxury fragrances in our signature presentation — the ultimate Mother's Day statement.</div>
          <ul class="bundle-includes">
            <li>3 full-size EDPs (50ml each)</li>
            <li>Luxury keepsake box</li>
            <li>Free 2-day shipping included</li>
            <li>Complimentary gift wrapping</li>
          </ul>
          <div class="bundle-price-row">
            <span class="bundle-price">$365</span>
            <span class="bundle-was">$435</span>
            <span class="bundle-save">Save $70</span>
          </div>
          <button class="btn btn-primary">Add to Bag</button>
        </div>
        <div class="bundle-card">
          <div class="bundle-image">
            <img src="https://www.maxaroma.com/productimages/large/SOK-KIT-2-11.jpg?ver=1675190659" width="280" height="280" alt="Xerjoff Casamorati 1888 bottle" loading="lazy">
          </div>
          <div class="bundle-name">The Discovery</div>
          <div class="bundle-desc">Five travel-size luxury fragrances — for the mother who loves to discover her next signature.</div>
          <ul class="bundle-includes">
            <li>5 travel atomizers (10ml each)</li>
            <li>Personalized fragrance guide card</li>
            <li>Elegant canvas pouch included</li>
          </ul>
          <div class="bundle-price-row">
            <span class="bundle-price">$145</span>
            <span class="bundle-was">$175</span>
            <span class="bundle-save">Save $30</span>
          </div>
          <button class="btn btn-secondary">Add to Bag</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Brands on sale strip -->
  <section class="brand-sale topspacing">
    <div class="container">
        <div class="section-header-row">
          <div>
            <span class="eyebrow">Brands on Sale</span>
            <h2>Up to <em>40% off</em> your favorite houses</h2>
          </div>
          <a href="#">Shop all brands</a>
        </div>
        <div class="brand-sale-grid">
          <a href="#" class="bs-tile">
            <div class="bs-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_14.jpg" alt="Creed" width="108" height="73"></div>
            <div class="bs-off">Up to 25% off</div>
          </a>
          <a href="#" class="bs-tile">
            <div class="bs-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1031.png" alt="House Of Dastan" width="108" height="73"></div>
            <div class="bs-off">Up to 20% off</div>
          </a>
          <a href="#" class="bs-tile">
            <div class="bs-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_534.png" alt="Initio Parfums" width="108" height="73"></div>
            <div class="bs-off">Up to 30% off</div>
          </a>
          <a href="#" class="bs-tile">
            <div class="bs-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_812.png" alt="Nishane" width="108" height="73"></div>
            <div class="bs-off">Up to 35% off</div>
          </a>
          <a href="#" class="bs-tile">
            <div class="bs-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1001.jpg" alt="OJAR" width="108" height="73"></div>
            <div class="bs-off">Up to 25% off</div>
          </a>
          <a href="#" class="bs-tile">
            <div class="bs-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_984.png" alt="Pantheon Roma" width="108" height="73"></div>
            <div class="bs-off">Up to 40% off</div>
          </a>
        </div>
    </div>
  </section>

  <!-- As featured from the world's finest houses -->
  <section class="houses-section topspacing">
    <div class="container">
      <div class="section-header">
        <span class="eyebrow">Our House Portfolio</span>
        <h2>As featured from the <em>world's finest houses</em></h2>
        <p class="section-desc">Maxaroma is an authorized retailer of over seventy luxury and niche fragrance houses, sourced direct from the ateliers and distillers who create them.</p>
        <div class="rule"></div>
      </div>
      <div class="houses-grid">
        <a href="#" class="house-tile">
          <div class="house-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_14.jpg" alt="Creed" width="108" height="73"></div>
          <div class="house-origin">Oman · Est. 1983</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1031.png" alt="House Of Dastan" width="108" height="73"></div>
          <div class="house-origin">France · Est. 1760</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_534.png" alt="Initio Parfums" width="108" height="73"></div>
          <div class="house-origin">France · Est. 2009</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo script"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_812.png" alt="Nishane" width="108" height="73"></div>
          <div class="house-origin">United Kingdom</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_1001.jpg" alt="OJAR" width="108" height="73"></div>
          <div class="house-origin">Italy · Est. 2004</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo serif italic"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_984.png" alt="Pantheon Roma" width="108" height="73"></div>
          <div class="house-origin">France · Grasse</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo serif"><img src="https://store.maxaroma.com/manufacture_logo/manufacture_logo_671.png" alt="Roja Parfums" width="108" height="73"></div>
          <div class="house-origin">United Kingdom</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_282.png" alt="Parfums De Marly" width="108" height="73"></div>
          <div class="house-origin">France · Paris</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo serif italic"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_14.jpg" alt="Creed" width="108" height="73"></div>
          <div class="house-origin">Oman · Muscat</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo sans"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_939.png" alt="The Merchant of Venice" width="108" height="73"></div>
          <div class="house-origin">France · Paris</div>
        </a>
        <a href="#" class="house-tile">
          <div class="house-logo serif"><img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/manufacture_logo/manufacture_logo_425.jpg" alt="Montale" width="108" height="73"></div>
          <div class="house-origin">United Arab Emirates</div>
        </a>
        <a href="#" class="house-tile view-all">
          <div class="house-logo serif">View all<br><em>70+ houses</em></div>
        </a>
      </div>
    </div>
  </section>

  <!-- Shipping / Delivery timeline -->
  <section class="shipping-banner-sec topspacing" id="ending-soon">
    <div class="container">
      <div class="shipping-banner">
        <div class="shipping-content">
          <span class="eyebrow">The Mother's Day Promise</span>
          <h2 class="shipping-headline">Guaranteed <strong>in time</strong>,<br>on the house.</h2>
          <p class="shipping-detail">Order by <span>May 6th at 2PM EST</span> on purchases of <span><span id="ship-tl">$220</span> or more</span> and we'll deliver in 2 business days — no code required, applied automatically at checkout. We also offer <span>overnight shipping</span> through May 9th.</p>
          <div class="shipping-timeline">
            <div class="ship-step">
              <span class="ship-step-date">Apr 24</span>
              <span class="ship-step-label">Sale begins</span>
            </div>
            <div class="ship-step">
              <span class="ship-step-date">May 3–6</span>
              <span class="ship-step-label">Free 2-day window</span>
            </div>
            <div class="ship-step">
              <span class="ship-step-date">May 9</span>
              <span class="ship-step-label">Last overnight</span>
            </div>
            <div class="ship-step">
              <span class="ship-step-date">May 11</span>
              <span class="ship-step-label">Mother's Day</span>
            </div>
          </div>
        </div>
        <div class="shipping-badge">
          <div class="shipping-badge-main"><span id="ship-badge">$220</span>+</div>
          <div class="shipping-badge-sub">Free 2-Day Shipping</div>
          <div class="shipping-badge-rule"></div>
          <div class="shipping-badge-mini">Auto-applied · No code</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Concierge / experience / gifting services -->
  <section class="experience topspacing">
    <div class="container">
        <div class="section-header">
          <span class="eyebrow">The Maxaroma Experience</span>
          <h2>Gifting, <em>done beautifully</em></h2>
          <div class="rule"></div>
        </div>
        <div class="experience-grid">
          <div class="exp-card">
            <div class="exp-num">No. 01</div>
            <div class="exp-title">Complimentary Wrapping</div>
            <div class="exp-desc">Every Mother's Day order arrives in our signature keepsake box, finished with a silk ribbon and sealed with wax.</div>
            <ul class="exp-list">
              <li>Three box finishes available</li>
              <li>Handwritten message card</li>
              <li>Scent-free tissue paper</li>
            </ul>
            <a href="#" class="exp-link">Add to cart</a>
          </div>
          <div class="exp-card">
            <div class="exp-num">No. 02</div>
            <div class="exp-title">Scheduled Delivery</div>
            <div class="exp-desc">Choose exactly when the gift arrives — ship to her directly, or schedule for the morning of May 11.</div>
            <ul class="exp-list">
              <li>Ship to her, bill to you</li>
              <li>Scheduled morning delivery</li>
              <li>Optional delivery photo</li>
            </ul>
            <a href="#" class="exp-link">Schedule a gift</a>
          </div>
          <div class="exp-card">
            <div class="exp-num">No. 03</div>
            <div class="exp-title">30-Day Worry-Free Returns</div>
            <div class="exp-desc">If the fragrance isn't right, we'll help her exchange it — unopened or opened — for thirty days after Mother's Day.</div>
            <ul class="exp-list">
              <li>Free return shipping label</li>
              <li>No questions asked</li>
              <li>Exchange concierge available</li>
            </ul>
            <a href="#" class="exp-link">Return policy</a>
          </div>
        </div>
    </div>
  </section>



  <!-- Testimonial + press -->
  <section class="testimonial-section topspacing">
    <div class="container md-container">
      <div class="testimonial-slider slick-dotted">
        <div class="slide">
          <div class="testimonial-inner">
            <div class="test-stars">★ ★ ★ ★ ★</div>
            <div class="test-quote">The packaging alone made her cry before she even opened the bottle. It felt like receiving a gift from a Parisian parfumerie — which, in a way, it was.</div>
            <div class="test-author">Maria C. · Verified Buyer</div>
            <div class="test-source">Mother's Day 2025</div>
          </div>
        </div>
        <div class="slide">
          <div class="testimonial-inner">
            <div class="test-stars">★ ★ ★ ★ ★</div>
            <div class="test-quote">The packaging alone made her cry before she even opened the bottle. It felt like receiving a gift from a Parisian parfumerie — which, in a way, it was.</div>
            <div class="test-author">Maria C. · Verified Buyer</div>
            <div class="test-source">Mother's Day 2025</div>
          </div>
        </div>
        <div class="slide">
          <div class="testimonial-inner">
            <div class="test-stars">★ ★ ★ ★ ★</div>
            <div class="test-quote">The packaging alone made her cry before she even opened the bottle. It felt like receiving a gift from a Parisian parfumerie — which, in a way, it was.</div>
            <div class="test-author">Maria C. · Verified Buyer</div>
            <div class="test-source">Mother's Day 2025</div>
          </div>
        </div>
      </div>
      <div class="press-logos">
        <span class="press-logo italic">Vogue</span>
        <span class="press-logo bold">Harper's Bazaar</span>
        <span class="press-logo italic">Elle</span>
        <span class="press-logo bold">Robb Report</span>
        <span class="press-logo italic">Town &amp; Country</span>
      </div>
    </div>
  </section>

  <!-- Gift card -->
  <section class=" topspacing">
    <div class="container md-container">
      <div class="giftcard">
          <div class="giftcard-visual">
            <img src="https://mediafiles.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://www.maxaroma.com/productimages/large/UP0850065695198_2.jpg?ver=1782145403" alt="Scents Of Wood fragrance collection presentation" class="brands-image" loading="lazy" width="1620" height="1620">
            <div class="giftcard-top">
              <div class="giftcard-logo">MAXAROMA</div>
            </div>
            <div class="giftcard-bottom">
              <div class="giftcard-label">Digital Gift Card</div>
              <div class="giftcard-amount">$250</div>
            </div>
          </div>
          <div class="giftcard-content">
            <span class="eyebrow">Running Out of Time?</span>
            <h3>The gift of <em>choice</em>.<br>Delivered instantly.</h3>
            <p>Missed the shipping window? Digital gift cards are emailed immediately — let her choose her own luxury fragrance, the most personal gift of all.</p>
            <div class="giftcard-amounts">
              <div class="gc-amount">$100</div>
              <div class="gc-amount active">$250</div>
              <div class="gc-amount">$500</div>
              <div class="gc-amount">$1,000</div>
              <div class="gc-amount">Custom</div>
            </div>
            <a href="#" class="btn btn-primary">Send a gift card </a>
          </div>
      </div>
    </div>
  </section>

  <!-- Newsletter -->
  <section class="newsletter topspacing">
    <div class="container">
      <div class="newsletter-inner">
        <span class="eyebrow">Join the List</span>
        <h2>Early access, <em>private sales,</em><br>and our perfumer's notes.</h2>
        <p>Subscribers receive Mother's Day offers 48 hours before the public and get an exclusive $25 welcome credit on their first order.</p>
        <form class="newsletter-form" onsubmit="event.preventDefault();">
          <input type="email" placeholder="Your email address">
          <button class="btn btn-secondary" type="submit">Subscribe</button>
        </form>
        <div class="newsletter-incentive">
          <div class="ni-item">
            <div class="ni-icon">$25</div>
            <div class="ni-text">Welcome credit</div>
          </div>
          <div class="ni-item">
            <div class="ni-icon">48h</div>
            <div class="ni-text">Early access</div>
          </div>
          <div class="ni-item">
            <div class="ni-icon">✎</div>
            <div class="ni-text">Perfumer's letter</div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- FAQ -->
  <section class="faq-section topspacing" id="faq">
    <div class="container md-container">
          <div class="section-header" style="padding:0 0 40px;">
            <span class="eyebrow">Thoughtful Details</span>
            <h2>Mother's Day, <em>answered</em></h2>
            <div class="rule"></div>
          </div>
          <div class="faq-item open">
            <button type="button" class="faq-q" aria-expanded="false" aria-controls="faq-a-1"><span>When should I order to guarantee delivery by May 11?</span><span class="faq-plus" aria-hidden="true">+</span></button>
            <div class="faq-a" id="faq-a-1">Order by May 6th at 2PM EST with free 2-day shipping (<span id="faq-ship">$220</span>+), or by May 9th with our overnight upgrade. Digital gift cards are delivered instantly up through May 11.</div>
          </div>
          <div class="faq-item">
            <button type="button" class="faq-q" aria-expanded="false" aria-controls="faq-a-2"><span>Can I ship directly to her, with a gift message?</span><span class="faq-plus" aria-hidden="true">+</span></button>
            <div class="faq-a" id="faq-a-2">Absolutely. At checkout, toggle "Ship as a gift" to add a handwritten message card, hide pricing on the packing slip, and optionally schedule the delivery for the morning of May 11.</div>
          </div>
          <div class="faq-item">
            <button type="button" class="faq-q" aria-expanded="false" aria-controls="faq-a-3"><span>What if the fragrance isn't right for her?</span><span class="faq-plus" aria-hidden="true">+</span></button>
            <div class="faq-a" id="faq-a-3">Our 30-day Worry-Free Returns apply to every Mother's Day order — opened or unopened. Contact our concierge and we'll help her exchange for something she loves, with free return shipping.</div>
          </div>
          <div class="faq-item">
            <button type="button" class="faq-q" aria-expanded="false" aria-controls="faq-a-4"><span>Are all fragrances authentic?</span><span class="faq-plus" aria-hidden="true">+</span></button>
            <div class="faq-a" id="faq-a-4">Every bottle at Maxaroma is sourced directly from the house or an authorized distributor. We do not sell grey-market, decants, or repackaged product — ever.</div>
          </div>
          <div class="faq-item">
            <button type="button" class="faq-q" aria-expanded="false" aria-controls="faq-a-5"><span>Can I buy a Mother's Day gift in-store?</span><span class="faq-plus" aria-hidden="true">+</span></button>
            <div class="faq-a" id="faq-a-5">Yes. Our flagship boutiques will honor Mother's Day pricing through May 10. Our concierge team can also assemble a custom bundle for in-store pickup or same-day local delivery.</div>
          </div>
    </div>
  </section>

  <div class="faq-bottotext"  id="disclaimer"></div>

  <script>
    // fade up
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) e.target.classList.add('visible');
      });
    }, {
      threshold: 0.1
    });
    document.querySelectorAll('.fade-up, section').forEach(el => obs.observe(el));

    // Tier tabs active state (filtering handled below)
    document.querySelectorAll('.tier-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.tier-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
      });
    });

    /* ══ CAMPAIGN OBJECT — the single editable record per campaign.
       Field names mirror the Monday brief zones so marketing dev is copy-paste.
       BLUE = campaign meta · GREEN = offer · GREY = merchandising · YELLOW = legal. ══ */
    const CAMPAIGN = {
      blue: {
        name: "Mother's Day Edit",
        starts: '2026-04-24'
      },
      green: {
        code: 'MAMAN15',
        tierLabel: 'Up to 15% + 2× points',
        freeShipThreshold: 220,
        gwpValue: 107,
        gwpLabel: '3-pc discovery set'
      },
      grey: {
        collections: ['best-sellers', 'new-arrivals', 'under-150', 'gift-sets', 'editors-picks'],
        stockTierFilter: ['ok', 'low']
      },
      yellow: {
        disclaimer: 'Offer valid 4/24–5/10/26. Excludes allocated and Tier-C indie houses. Cannot combine with other codes.'
      }
    };
    // Render campaign fields (nothing here is hardcoded in markup)
    const setTxt = (id, v) => {
      const n = document.getElementById(id);
      if (n) n.textContent = v;
    };
    ['vs-ship', 'ship-tl', 'ship-badge', 'faq-ship'].forEach(id => setTxt(id, '$' + CAMPAIGN.green.freeShipThreshold));

    /* ══ GA4 events on every CTA, filter and collection click ══ */
    function ga(event, params) {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push(Object.assign({
        event: event,
        campaign: CAMPAIGN.blue.name,
        code: CAMPAIGN.green.code
      }, params || {}));
    }
    document.addEventListener('click', e => {
      const t = e.target.closest('[data-ga]');
      if (t) ga(t.dataset.ga, {
        label: (t.textContent || '').trim().slice(0, 48),
        sku: t.dataset.sku || null
      });
    });

    /* ══ Returning visitors default to best sellers ══ */
    const returning = localStorage.getItem('mx_seen') === '1';
    localStorage.setItem('mx_seen', '1');

    /* ══ Faceted filtering + sort ══ */
    const grid = document.getElementById('products-grid');
    const cards = [...grid.querySelectorAll('.product-card')];
    const F = {
      recipient: 'all',
      budget: 'all',
      family: 'all',
      house: 'all',
      sort: returning ? 'best' : 'new',
      tier: 'all',
      stock: true,
      sets: false
    };
    const selSort = document.getElementById('f-sort');
    selSort.value = F.sort;
    const empty = document.createElement('div');
    empty.className = 'empty-state';
    empty.textContent = 'No fragrances match these filters — try widening your budget.';
    grid.appendChild(empty);

    function apply() {
      let shown = 0;
      cards.forEach(c => {
        const p = +c.dataset.price;
        let ok = true;
        if (F.recipient !== 'all' && c.dataset.recipient !== F.recipient) ok = false;
        if (F.family !== 'all' && c.dataset.family !== F.family) ok = false;
        if (F.house !== 'all' && c.dataset.house !== F.house) ok = false;
        if (F.budget !== 'all') {
          const [lo, hi] = F.budget.split('-').map(Number);
          if (p < lo || p > hi) ok = false;
        }
        if (F.stock && !CAMPAIGN.grey.stockTierFilter.includes(c.dataset.stock)) ok = false;
        if (F.sets && c.dataset.set !== '1') ok = false;
        if (F.tier === 'new' && c.dataset.new !== '1') ok = false;
        else if (F.tier === '150' && p >= 150) ok = false;
        else if (F.tier === '250' && p >= 250) ok = false;
        else if (F.tier === '400' && p < 400) ok = false;
        c.classList.toggle('hidden', !ok);
        if (ok) shown++;
      });
      const order = [...cards].sort((a, b) => {
        if (F.sort === 'low') return a.dataset.price - b.dataset.price;
        if (F.sort === 'high') return b.dataset.price - a.dataset.price;
        if (F.sort === 'new') return b.dataset.new - a.dataset.new;
        if (F.sort === 'best') return b.dataset.best - a.dataset.best;
        return 0;
      });
      order.forEach(c => grid.appendChild(c));
      grid.appendChild(empty);
      empty.style.display = shown ? 'none' : 'block';
      setTxt('f-count', shown + (shown === 1 ? ' fragrance' : ' fragrances'));
      if (typeof syncPressed === 'function') syncPressed();
    }
    [
      ['f-recipient', 'recipient'],
      ['f-budget', 'budget'],
      ['f-family', 'family'],
      ['f-house', 'house'],
      ['f-sort', 'sort']
    ].forEach(([id, key]) => {
      document.getElementById(id).addEventListener('change', e => {
        F[key] = e.target.value;
        ga('filter_change', {
          facet: key,
          value: e.target.value
        });
        apply();
      });
    });
    [
      ['f-stock', 'stock'],
      ['f-sets', 'sets']
    ].forEach(([id, key]) => {
      document.getElementById(id).addEventListener('click', e => {
        F[key] = !F[key];
        e.currentTarget.classList.toggle('on', F[key]);
        ga('filter_change', {
          facet: key,
          value: F[key]
        });
        apply();
      });
    });
    document.getElementById('f-reset').addEventListener('click', () => {
      Object.assign(F, {
        recipient: 'all',
        budget: 'all',
        family: 'all',
        house: 'all',
        tier: 'all',
        stock: true,
        sets: false
      });
      ['f-recipient', 'f-budget', 'f-family', 'f-house'].forEach(id => document.getElementById(id).value = 'all');
      document.getElementById('f-stock').classList.add('on');
      document.getElementById('f-sets').classList.remove('on');
      document.querySelectorAll('.tier-tab').forEach((t, i) => t.classList.toggle('active', i === 0));
      apply();
    });
    document.querySelectorAll('.tier-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        F.tier = tab.dataset.tier;
        ga('collection_click', {
          collection: tab.dataset.tier
        });
        apply();
      });
    });
    apply();

    setTxt('disclaimer', CAMPAIGN.yellow.disclaimer);

    /* ══ FAQ accessible disclosure ══ */
    document.querySelectorAll('.faq-q').forEach(btn => btn.addEventListener('click', () => {
      const open = btn.closest('.faq-item').classList.toggle('open');
      btn.setAttribute('aria-expanded', String(open));
    }));
    document.querySelectorAll('.faq-item.open .faq-q').forEach(b => b.setAttribute('aria-expanded', 'true'));

    /* ══ aria-pressed — single global sync, driven by rendered state ══ */
    function syncPressed() {
      document.querySelectorAll('.facet-chip, .tier-tab').forEach(b =>
        b.setAttribute('aria-pressed', String(b.classList.contains('on') || b.classList.contains('active'))));
    }
    syncPressed();
  </script>

  <script>
    $(document).ready(function() {
      $('.testimonial-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: true,
        infinite: true
      });
    });
  </script>

</main>

@endsection