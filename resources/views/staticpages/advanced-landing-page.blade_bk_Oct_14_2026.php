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
						  {{$detail->banner_desc}}
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
					<p>{{$detail->banner_desc}}</p>
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
