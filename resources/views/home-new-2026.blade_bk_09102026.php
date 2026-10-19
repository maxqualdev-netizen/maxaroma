{{--
@if($_SERVER['REMOTE_ADDR'] == '2402:a00:409:1e8a:54fc:5b7:f894:379e')
    @extends('layouts.app-updated')
@else
    @extends('layouts.app')
@endif
--}}
@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Homepage Main Content">
  <h1 class="sr-only">MAXAROMA fragrance, skincare, and beauty</h1>
  @if(count($HomeMainBanners) > 0)
  <section class="home-main-banner" aria-label="Homepage banners">
    <div class="container">
      <div class="home-main-sl" role="region" aria-label="Main Banner Slider">

        {{-- @if($Homepage_Video_Text != '' || $Homepage_Mobile_Video_Text != '')
                <div class="item" role="group" aria-label="Homepage Video Banner">
                    <!-- Desktop Video Here -->
                    @if($Homepage_Video_Text != '')
                    <div class="d-md-block d-none" role="region" aria-label="Desktop Video">
                        <div class="fullscreen">
                            <div class="video-full">
                                <div class="wrapper-f">
                                    {!! $Homepage_Video_Text !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Mobile Video Here -->
                    @if($Homepage_Mobile_Video_Text != '')
                    <div class="d-md-none" role="region" aria-label="Mobile Video">
                        <div class="fullscreen">
                             <div class="video-full">
                                 <div class="wrapper-f">
                                    {!! $Homepage_Mobile_Video_Text !!}
                                    <!--<iframe src="https://player.vimeo.com/video/743149663?h=e18c3c74d9" width="768" height="768" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif --}}

        @foreach ($HomeMainBannerVideos as $HomeMainBannerVideo)
        @if($HomeMainBannerVideo['detail_html'] != '' || $HomeMainBannerVideo['mobile_detail_html'] != '')
        <div class="item" role="group" aria-label="Homepage Video Banner">
          <!-- Desktop Video Here -->
          @if($HomeMainBannerVideo['detail_html'] != '')
          <div class="d-md-block d-none" role="region" aria-label="Desktop Video">
            <div class="fullscreen">
              <div class="video-full">
                <div class="wrapper-f">
                  {!! $HomeMainBannerVideo['detail_html'] !!}
                </div>
              </div>
            </div>
          </div>
          @endif
          <!-- Mobile Video Here -->
          @if($HomeMainBannerVideo['mobile_detail_html'] != '')
          <div class="d-md-none" role="region" aria-label="Mobile Video">
            <div class="fullscreen">
              <div class="video-full">
                <div class="wrapper-f">
                  {!! $HomeMainBannerVideo['mobile_detail_html'] !!}
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
        @endif
        @endforeach

        @foreach ($HomeMainBanners as $bannerIndex => $HomeMainBanner)
        <a href="{{$HomeMainBanner['link']}}" class="home-hero-banner-{{ strtolower(str_replace(' ', '-', str_replace('\\', '', $HomeMainBanner['title']))) }}" role="group" aria-label="Main Banner Slide {{ $bannerIndex + 1 }}">
          <img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMainBanner['home_image']}}" width="1920" height="591" class="rounded d-none d-md-block" alt="{{ $HomeMainBanner['image_alt'] ?? 'Main Banner Image' }}" @if($loop->first) fetchpriority="high" loading="eager" decoding="async" @else loading="lazy" decoding="async" @endif>
          @if($HomeMainBanner['mobile_image'] != '')
          <img src="{{ config('global.SPEED_SIZE_URL')}}{{$HomeMainBanner['mobile_image']}}" width="767" height="859" class="rounded d-md-none" alt="{{ $HomeMainBanner['image_alt'] ?? 'Main Banner Mobile Image' }}" @if($loop->first) fetchpriority="high" loading="eager" decoding="async" @else loading="lazy" decoding="async" @endif>
          @endif
        </a>
        @endforeach
      </div>
      <div id="load_hero_banner" class="cover-spin" style="height: 526px;padding-top: 220px;" role="status" aria-live="polite"></div>
    </div>
  </section>
  @endif

  <section class="home-main-banner" aria-label="Featured homepage banners">
    <div class="container">
      <div class="home-main-sl slick-dotted-nw slick-cls" role="region" aria-label="Main Banner Slider">
        <div class="item slide-cls" role="group" aria-label="Homepage Banner">
          <div class="hero-frame">
            <a href="Category.dc.html" aria-label="Shop New Arrivals - The Gourmand Season Begins">
              <picture>
                <source media="(max-width: 767px)"
                  srcset="{{config('global.Site_URL')}}public/images/newhome-2026/gourmand-season-mobbanner.jpg">
                <img src="{{config('global.Site_URL')}}public/images/newhome-2026/gourmand-season-banner.jpg"
                  alt="The Gourmand Season Begins - Fall 2026 Edit"
                  fetchpriority="high"
                  loading="eager"
                  decoding="async"
                  width="1920"
                  height="800">
              </picture>
            </a>
            <div class="hero-copy">
              <div class="hero-kicker">FALL 2026 EDIT</div>
              <div class="hero-title">The Gourmand Season Begins</div>
              <a href="Category.dc.html" class="btn btn-secondary">SHOP NEW ARRIVALS</a>
            </div>
          </div>
        </div>
        <div class="item slide-cls is-shoppable" role="group" aria-label="Homepage Banner">
          <div class="hero-frame">
            <a href="Category.dc.html" aria-label="Shop The Maison - House of Dastan">
              <picture>
                <source media="(max-width: 767px)"
                  srcset="{{config('global.Site_URL')}}public/images/newhome-2026/saffron-mobbanner.jpg">
                <img src="{{config('global.Site_URL')}}public/images/newhome-2026/saffron-banner.jpg"
                  alt="Saffron, Rose, Smoked Oud"
                  loading="lazy"
                  decoding="async"
                  width="1920"
                  height="800">
              </picture>
            </a>

            <div class="hero-hotspots">
              <div class="hotspot hotspot-1">
                <button class="hotspot-btn" type="button" aria-label="Aurum Saffron Extrait — $245"><span class="hotspot-dot"></span></button>
                <a href="PDP.dc.html" class="hotspot-card"><span class="hotspot-brand">HOUSE OF DASTAN</span><span class="hotspot-name">Aurum Saffron Extrait</span><span class="hotspot-price"><span class="hotspot-now">$245</span><span class="hotspot-was">$310</span></span></a>
              </div>
              <div class="hotspot hotspot-2">
                <button class="hotspot-btn" type="button" aria-label="Rose Zaher Extrait — $225"><span class="hotspot-dot"></span></button>
                <a href="PDP.dc.html" class="hotspot-card"><span class="hotspot-brand">HOUSE OF DASTAN</span><span class="hotspot-name">Rose Zaher Extrait</span><span class="hotspot-price"><span class="hotspot-now">$225</span><span class="hotspot-was">$285</span></span></a>
              </div>
              <div class="hotspot hotspot-3">
                <button class="hotspot-btn" type="button" aria-label="Oud Kandahar Extrait — $265"><span class="hotspot-dot"></span></button>
                <a href="PDP.dc.html" class="hotspot-card"><span class="hotspot-brand">HOUSE OF DASTAN</span><span class="hotspot-name">Oud Kandahar Extrait</span><span class="hotspot-price"><span class="hotspot-now">$265</span><span class="hotspot-was">$340</span></span></a>
              </div>
            </div>
            <div class="hero-copy">
              <div class="hero-kicker">THE EXCLUSIVES · HOUSE OF DASTAN</div>
              <div class="hero-title">Saffron, Rose, Smoked Oud</div>
              <a href="Category.dc.html" class="btn btn-secondary">SHOP THE MAISON</a>
            </div>
          </div>
        </div>
        <div class="item slide-cls is-countdown" role="group" aria-label="Homepage Banner">
          <div class="hero-frame">
            <picture>
              <source media="(max-width: 767px)"
                srcset="{{config('global.Site_URL')}}public/images/newhome-2026/weekend-event-mobbanner.jpg">
              <img src="{{config('global.Site_URL')}}public/images/newhome-2026/weekend-event-banner.jpg"
                alt="WEEKEND EVENT ENDS SUNDAY"
                loading="lazy"
                decoding="async"
                width="1920"
                height="800">
            </picture>
            <div class="hero-copy">
              <div class="hero-kicker">WEEKEND EVENT · ENDS SUNDAY</div>
              <div class="hero-title">Fall Preview: Up to 33% Off Icons</div>
              <div class="hero-cd">
                <span class="hero-cd-box"><span class="hero-cd-num">00</span><span class="hero-cd-lbl">DAYS</span></span>
                <span class="hero-cd-box"><span class="hero-cd-num">00</span><span class="hero-cd-lbl">HRS</span></span>
                <span class="hero-cd-box"><span class="hero-cd-num">00</span><span class="hero-cd-lbl">MIN</span></span>
                <span class="hero-cd-box"><span class="hero-cd-num">00</span><span class="hero-cd-lbl">SEC</span></span>
              </div>
              <a href="Offers.dc.html" class="btn btn-secondary">VIEW OFFERS</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @if(count($NewArrivals) > 0)
  <x-slider :attr="$NewArrivalsAttr" :sliderData="$NewArrivals" :ver="$NewHomeDesign" type="NewArrival" sliderLink="new-arrivals/p4u/special-na/view" aria-label="New Arrivals Slider" />
  @endif


  <section class="discovery topspacing">
    <div class="discovery-inner container">
      <div class="pt-3 text-center pb-4">
        <div class="main-heading">
          <h2><span>Find your next fragrance</span></h2>
          <p class="discovery-sub">Three ways in — no expertise required.</p>
        </div>
      </div>
      <div class="find-slider slick-cls slick-dotted-nw space-dots-top sl-mrl">
        <div class="pl-2 pr-2 slide-cls">
          <a href="#quiz" class="dcard">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
              <circle cx="12" cy="12" r="9"></circle>
              <path d="M9.5 9.5a2.5 2.5 0 115 0c0 1.5-2.5 2-2.5 3.5"></path>
              <circle cx="12" cy="16.8" r=".4" fill="#000"></circle>
            </svg>
            <span class="dcard-title">Take the Quiz</span>
            <span class="dcard-blurb">Five questions. A shortlist built around how you actually wear fragrance.</span>
            <span class="dcard-cta line-hover">START </span>
          </a>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <a href="Category.dc.html" class="dcard">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
              <path d="M12 3c3 4 6 6.5 6 10a6 6 0 11-12 0c0-3.5 3-6 6-10z"></path>
            </svg>
            <span class="dcard-title">Shop by Notes</span>
            <span class="dcard-blurb">Vanilla, oud, tuberose, salt — start from the ingredients you already love.</span>
            <span class="dcard-cta line-hover">BROWSE NOTES </span>
          </a>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <a href="Category.dc.html" class="dcard">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
              <circle cx="12" cy="12" r="9"></circle>
              <path d="M12 7v5l3.5 2"></path>
            </svg>
            <span class="dcard-title">Shop by Context</span>
            <span class="dcard-blurb">Date night, office safe, cold weather — matched to the moment, not the marketing.</span>
            <span class="dcard-cta line-hover">EXPLORE CONTEXTS </span>
          </a>
        </div>
      </div>
    </div>
  </section>

  @if(count($HomeFeatureBrands2026 ?? []) > 0)
  <section class="exclusives-wrap topspacing">
    <div class="exclusives">
      <div class="exclusives-inner container">
        <div class="pt-3 text-center pb-4">
          <div class="main-heading">
            <h2><span>The Exclusives</span></h2>
            <p class="discovery-sub">Three maisons carried in the U.S. only at MAXAROMA.</p>
          </div>
        </div>
        <div class="excl-rail excl-rail-sl slick-cls sl-mrl  slick-dotted-nw space-dots-top">          
            @foreach($HomeFeatureBrands2026 as $FeaturedBrand)
              <div class="pl-2 pr-2 slide-cls">
                <a href="{{ $FeaturedBrand['link'] ?? '#' }}"
                  class="excl-card"
                  aria-label="Explore {{ $FeaturedBrand['title'] ?? 'exclusive brand' }}">
                  <span class="excl-art">
                    <img src="{{ config('global.SPEED_SIZE_URL') }}{{ $FeaturedBrand['home_image'] ?? '' }}"
                        alt="{{ $FeaturedBrand['image_alt'] ?? ($FeaturedBrand['title'] ?? 'Exclusive fragrance brand') }}"
                        loading="lazy"
                        decoding="async"
                        width="700"
                        height="960">
                  </span>
                  <span class="excl-panel">
                    <span class="excl-name">{{ $FeaturedBrand['title'] ?? '' }}</span>
                    <span class="excl-line">Discover an exclusive fragrance collection at MAXAROMA.</span>
                    <span class="excl-cta line-hover">EXPLORE {{ $FeaturedBrand['title'] ?? '' }}</span>
                  </span>
                </a>
              </div>
            @endforeach          
        </div>
      </div>
    </div>
  </section>
  @endif

  @if(count($BestSellers) > 0)
  <x-slider :attr="$BestSellersAttr" :sliderData="$BestSellers" :ver="$NewHomeDesign" aria-label="Best Sellers Slider" />
  @endif

  <section class="ugc-section container topspacing">
    <div class="pt-3 text-center pb-4">
      <div class="main-heading">
        <h2><span>Seen on #PerfumeTok</span></h2>
        <p class="discovery-sub">Real wear tests from creators we trust — tap to watch and shop.</p>
      </div>
    </div>
    <div class="ugc-rail sl-mrl">
      <div class="tiktok-sl slick-cls space-dots-top slick-dotted slick-dotted-nw">
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="0" aria-label="Play video review: Mes Bisous Crumble Me Softly by @scentedsofia">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/8EETZnsVG1k/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/8EETZnsVG1k/hqdefault.jpg" class="ugc-img" alt="Mes Bisous Crumble Me Softly video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@scentedsofia</span>
              <span class="ugc-platform">TIKTOK</span>
            </span>
            <span class="ugc-label">Mes Bisous · Crumble Me Softly Extrait de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="2" aria-label="Play video review: Baccarat Rouge 540 by @fragfiles">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/Rgem4sJGd7E/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/Rgem4sJGd7E/hqdefault.jpg" class="ugc-img" alt="Maison Francis Kurkdjian Baccarat Rouge 540 video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@fragfiles</span>
              <span class="ugc-platform">TIKTOK</span>
            </span>
            <span class="ugc-label">Maison Francis Kurkdjian · Baccarat Rouge 540 Eau de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="1" aria-label="Play video review: Creed Aventus by @noseknowsnyc">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/Rgem4sJGd7E/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/Rgem4sJGd7E/hqdefault.jpg" class="ugc-img" alt="Creed Aventus video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@noseknowsnyc</span>
              <span class="ugc-platform">INSTAGRAM</span>
            </span>
            <span class="ugc-label">Creed · Aventus Eau de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="3" aria-label="Play video review: Parfums de Marly Layton by @wearitwithmarco">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/8EETZnsVG1k/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/8EETZnsVG1k/hqdefault.jpg" class="ugc-img" alt="Parfums de Marly Layton video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@wearitwithmarco</span>
              <span class="ugc-platform">INSTAGRAM</span>
            </span>
            <span class="ugc-label">Parfums de Marly · Layton Eau de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="4" aria-label="Play video review: House of Dastan Discovery Set by @maxaroma">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/Rgem4sJGd7E/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/Rgem4sJGd7E/hqdefault.jpg" class="ugc-img" alt="House of Dastan Discovery Set video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@maxaroma</span>
              <span class="ugc-platform">OURS</span>
            </span>
            <span class="ugc-label">House of Dastan · Discovery Set — Six Extraits</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="5" aria-label="Play video review: Kilian Paris Angels' Share by @gourmandghost">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/Rgem4sJGd7E/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/Rgem4sJGd7E/hqdefault.jpg" class="ugc-img" alt="Kilian Paris Angels' Share video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@gourmandghost</span>
              <span class="ugc-platform">TIKTOK</span>
            </span>
            <span class="ugc-label">Kilian Paris · Angels' Share Eau de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="6" aria-label="Play video review: Xerjoff Naxos by @maxaroma">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/Rgem4sJGd7E/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/Rgem4sJGd7E/hqdefault.jpg" class="ugc-img" alt="Xerjoff Naxos video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@maxaroma</span>
              <span class="ugc-platform">OURS</span>
            </span>
            <span class="ugc-label">Xerjoff · Naxos Eau de Parfum</span>
          </button>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <button class="ugc-tile" type="button" data-index="7" aria-label="Play video review: Nishane Hacivat by @softspraysam">
            <span class="ugc-thumb">
              <picture class="ugc-picture">
                <source srcset="https://i.ytimg.com/vi_webp/8EETZnsVG1k/hqdefault.webp" type="image/webp">
                <img src="https://i.ytimg.com/vi/8EETZnsVG1k/hqdefault.jpg" class="ugc-img" alt="Nishane Hacivat video preview" loading="lazy" decoding="async" width="280" height="500">
              </picture>
              <span class="ugc-play" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#1A1A1A" focusable="false"><path d="M8 5v14l11-7z"></path></svg>
              </span>
              <span class="ugc-handle">@softspraysam</span>
              <span class="ugc-platform">INSTAGRAM</span>
            </span>
            <span class="ugc-label">Nishane · Hacivat Extrait de Parfum</span>
          </button>
        </div>
      </div>
    </div>
  </section>

  <div class="ugc-modal" role="dialog" aria-modal="true" aria-labelledby="ugc-modal-title" aria-hidden="true" inert>
    <div class="ugc-modal-backdrop" aria-hidden="true"></div>
    <div class="ugc-modal-wrapper">
      <button class="ugc-modal-prev" type="button" aria-label="Previous video">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
      </button>

      <div class="ugc-modal-card">
        <div class="ugc-modal-header">
          <div class="ugc-modal-creator">
            <span class="ugc-modal-platform-badge">TIKTOK</span>
            <span class="ugc-modal-handle">@scentedsofia</span>
          </div>
          <button class="ugc-modal-close" type="button" aria-label="Close video dialog">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>

        <div class="ugc-modal-stage"></div>

        <div class="ugc-modal-footer">
          <div class="ugc-modal-product">
            <span class="ugc-modal-mark">MB</span>
            <div class="ugc-modal-copy">
              <span class="ugc-modal-brand">MES BISOUS</span>
              <a href="PDP.dc.html" class="ugc-modal-name" id="ugc-modal-title">Crumble Me Softly Extrait de Parfum</a>
              <div class="ugc-modal-price-row">
                <span class="ugc-modal-price">$275</span>
                <span class="ugc-modal-list">$78</span>
              </div>
            </div>
            <button class="ugc-modal-atc" type="button">ADD TO CART</button>
          </div>

          <div class="ugc-modal-actions">
            <a href="PDP.dc.html" class="ugc-shop btn btn-primary">SHOP MES BISOUS</a>
            <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="ugc-watch btn btn-secondary">WATCH ON TIKTOK</a>
          </div>
        </div>
      </div>

      <button class="ugc-modal-next" type="button" aria-label="Next video">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>
    </div>
  </div>

  <section class="container topspacing">
    <div class="promises">
      <div class="promises-grid">
        <div class="promise"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
            <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
            <path d="M9 12l2 2 4-4"></path>
          </svg><span class="promise-title">Authenticity Promise</span><span class="promise-sub">Sourced direct. Every bottle guaranteed.</span></div>
        <div class="promise"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
            <rect x="4" y="9" width="16" height="11" rx="1.5"></rect>
            <path d="M4 12h16M12 9v11M12 9c-3 0-4.5-1.5-4.5-3A1.8 1.8 0 0110 4.5c1.5 0 2 2.5 2 4.5zm0 0c3 0 4.5-1.5 4.5-3A1.8 1.8 0 0014 4.5c-1.5 0-2 2.5-2 4.5z"></path>
          </svg><span class="promise-title">Free Samples on $180+</span><span class="promise-sub">Try before it becomes a signature.</span></div>
        <div class="promise"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
            <path d="M4 9l1.5-5h13L20 9M4 9v11h16V9M4 9h16M9.5 20v-6h5v6"></path>
          </svg><span class="promise-title">SoHo Boutique</span><span class="promise-sub">Visit MAXAROMA The Edit in person.</span></div>
        <div class="promise"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5">
            <path d="M12 3.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L3.5 9.7l5.9-.9L12 3.5z"></path>
          </svg><span class="promise-title">40K Reviews · 4.8★</span><span class="promise-sub">Rated by shoppers on Google.</span></div>
      </div>
    </div>
  </section>

  <section class="sets-section container topspacing">
    <div class="pt-3 text-center pb-4">
      <div class="main-heading">
        <h2 class="sliderHeading" id="slider-heading-best-sellers"><span>Try Before You Commit</span></h2>
        <a href="Category.dc.html" class="viewall line-hover" aria-label="View all discovery sets">All Discovery Sets</a>
      </div>
    </div>
    <div class="sets-grid sl-mrl">
      <div class="commit-sl slick-cls space-dots-top slick-dotted slick-dotted-nw">
        <div class="pl-2 pr-2 slide-cls">
          <div class="set-card">
            <a href="PDP.dc.html" class="set-media" aria-label="House of Dastan Discovery Set">
              <img src="{{config('global.Site_URL')}}public/images/newhome-2026/house-of-dastan-bottom.jpg?asa" alt="House of Dastan Discovery Set Six Extraits" loading="lazy" decoding="async" width="430" height="320">
            </a>
            <span class="set-kicker">HOUSE OF DASTAN · EXCLUSIVE</span>
            <a href="PDP.dc.html" class="set-title">Discovery Set — Six Extraits</a>
            <p class="set-blurb">Aurum Saffron, Rose Zaher, Oud Kandahar, Ambre Bagh, Musk Safid and Neroli Shahi — the full extrait range, 2 ml each.</p>
            <span class="set-price-row"><span class="set-price">$60</span><span class="set-was">$78</span><span class="set-size">6 × 2 ml</span></span>
            <span class="set-credit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.7" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M8 12.5l2.6 2.5L16 9.5"></path>
              </svg><span>$60 credited back toward any Dastan full bottle.</span></span>
            <a href="PDP.dc.html" class="btn btn-primary" aria-label="Add House of Dastan Discovery Set to cart for $60">ADD SET · $60</a>
          </div>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <div class="set-card">
            <a href="Category.dc.html" class="set-media" aria-label="Warm and Woody Five to Try">
              <img src="{{config('global.Site_URL')}}public/images/newhome-2026/seasional-edit-bottom.jpg" alt="Warm and Woody Seasonal Edit Fall 2026" loading="lazy" decoding="async" width="430" height="320">
            </a>
            <span class="set-kicker">SEASONAL EDIT · FALL 2026</span>
            <a href="Category.dc.html" class="set-title">Warm &amp; Woody: Five to Try</a>
            <p class="set-blurb">Parfums de Marly Althaïr, Xerjoff Naxos, Initio Oud for Greatness, Kilian Angels’ Share, Nishane Ani.</p>
            <span class="set-price-row"><span class="set-price">$32</span><span class="set-was">$40</span><span class="set-size">5 × 2 ml</span></span>
            <span class="set-credit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.7" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M8 12.5l2.6 2.5L16 9.5"></path>
              </svg><span>$32 credited back toward any bottle in the edit.</span></span>
            <a href="Category.dc.html" class="btn btn-primary" aria-label="Add Warm and Woody set to cart for $32">ADD SET · $32</a>
          </div>
        </div>
        <div class="pl-2 pr-2 slide-cls">
          <div class="set-card">
            <a href="Category.dc.html" class="set-media" aria-label="Choose Any Five Samples">
              <img src="{{config('global.Site_URL')}}public/images/newhome-2026/build-bottom-bnr.jpg" alt="Build your own sample set of any five fragrances" loading="lazy" decoding="async" width="430" height="320">
            </a>
            <span class="set-kicker">BUILD YOUR OWN · ANY FIVE</span>
            <a href="Category.dc.html" class="set-title">Choose Any Five Samples</a>
            <p class="set-blurb">Pick from 4,000 fragrances across 300 brands — Dior and Creed to Xerjoff and Parfums de Marly.</p>
            <span class="set-price-row"><span class="set-price">From $24</span><span class="set-size">5 × 2 ml</span></span>
            <span class="set-credit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.7" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M8 12.5l2.6 2.5L16 9.5"></path>
              </svg><span>Your full sample spend comes back as bottle credit.</span></span>
            <a href="Category.dc.html" class="btn btn-primary" aria-label="Build your custom set starting from $24">BUILD YOUR SET</a>
          </div>
        </div>
      </div>
    </div>
    <div class="sets-note">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1E6B3A" stroke-width="1.6" aria-hidden="true" focusable="false">
        <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
        <path d="M9 12l2 2 4-4"></path>
      </svg>
      <span>100% of your sample spend comes back as credit toward your first full bottle.</span>
    </div>
  </section>

  <section class="gifty-section topspacing" aria-labelledby="gifty-section-title">
    <div class="container">
      <h2 id="gifty-section-title" class="sr-only">Featured Offers and Membership</h2>
      <div class="gifty-grid"> <!-- Aroma Club -->
        <article class="gifty-card"> <a href="/aroma-club" aria-label="Learn more about Aroma Club"> <img src="{{ config('global.Site_URL') }}public/images/newhome-2026/aroma-club-bnr.jpg" alt="Aroma Club membership promotion" loading="lazy" decoding="async" width="800" height="420"> </a>
          <div class="gifty-content">
            <div class="gifty-icon" aria-hidden="true">♛</div>
            <h3 class="gifty-title">Aroma Club</h3>
            <p class="gifty-description"> Earn points on every bottle. <span class="description-break" aria-hidden="true"><br></span> Redeem on anything. </p> <a href="/aroma-club" class="gifty-link line-hover" aria-label="Join Aroma Club Free"> Join Free </a>
          </div>
        </article> <!-- This Week's Offers -->
        <article class="gifty-card"> <a href="/offers" aria-label="View this week's offers"> <img src="{{ config('global.Site_URL') }}public/images/newhome-2026/week-offer-bnr.jpg" alt="This week's promotional fragrance offers" loading="lazy" decoding="async" width="800" height="420"> </a>
          <div class="gifty-content">
            <div class="gifty-icon" aria-hidden="true">%</div>
            <h3 class="gifty-title"> This Week's<br aria-hidden="true"> Offers </h3>
            <p class="gifty-description"> Real markdowns on icons — <span class="description-break" aria-hidden="true"><br></span> every deal stated in plain text. </p> <a href="/offers" class="gifty-link line-hover" aria-label="View this week's offers"> View Offers </a>
          </div>
        </article>
      </div>
    </div>
  </section>
  <section class="home_review topspacing" role="region" aria-label="Customer Reviews">
        <div class="container">
            <div class="yotpo yotpo-reviews-carousel"
                data-background-color="transparent"
                data-mode="most_recent"
                data-type="product"
                data-count="9"
                data-show-bottomline="1"
                data-autoplay-enabled="1"
                data-autoplay-speed="6000"
                data-show-navigation="1"
                role="region"
                aria-label="Product Reviews Carousel"
            >&nbsp;</div>
            <script>
            (function() {
              /* Luxury Reviews Carousel Card Enhancer */
              var knownBrands = [
                'Maison Francis Kurkdjian', 'House of Dastan', 'Parfums de Marly', 'Roja Parfums',
                'John Varvatos', 'Christian Dior', 'Dior', 'Amouage', 'Xerjoff', 'Creed',
                'Tom Ford', 'Byredo', 'Initio', 'Diptyque', 'Kilian', 'Montale', 'Mancera',
                'Atelier Cologne', 'Tiziana Terenzi', 'Rasasi', 'Yves Saint Laurent', 'YSL',
                'Giorgio Armani', 'Armani', 'Chanel', 'Prada', 'Versace', 'Gucci', 'Bvlgari',
                'Hermes', 'Burberry', 'Dolce & Gabbana', 'Givenchy', 'Valentino', 'Jo Malone',
                'Le Labo', 'Floraiku', 'Memo Paris', 'Nishane', 'Vilhelm Parfumerie', 'Bond No. 9',
                'Penhaligon\'s', 'Clive Christian', 'Acqua di Parma'
              ];

              function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
              }

              function formatLuxDate(dStr) {
                if (!dStr) return '';
                var parts = dStr.match(/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/);
                if (parts) {
                  var mNum = parseInt(parts[1], 10);
                  var yNum = parseInt(parts[3], 10);
                  if (yNum < 100) yNum += 2000;
                  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                  if (mNum >= 1 && mNum <= 12) return months[mNum - 1] + ' ' + yNum;
                }
                return dStr;
              }

              function getMonogram(brand) {
                if (!brand) return 'MA';
                if (/parfums de marly/i.test(brand)) return 'PdM';
                if (/maison francis kurkdjian/i.test(brand)) return 'MFK';
                if (/house of dastan/i.test(brand)) return 'HD';
                if (/xerjoff/i.test(brand)) return 'X';
                if (/creed/i.test(brand)) return 'CR';
                if (/floraiku/i.test(brand)) return 'FL';
                if (/roja/i.test(brand)) return 'RP';
                if (/tom ford/i.test(brand)) return 'TF';
                if (/initio/i.test(brand)) return 'IP';
                if (/byredo/i.test(brand)) return 'BY';
                if (/kilian/i.test(brand)) return 'KL';
                var words = brand.trim().split(/\s+/);
                if (words.length >= 2) return (words[0][0] + words[1][0]).toUpperCase();
                return brand.substring(0, 2).toUpperCase();
              }

              function getBrandAndProduct(rawText, card) {
                if (!rawText) return { brand: 'FRAGRANCE', prod: 'Luxury Scent' };
                var cleaned = rawText.replace(/^\d{4}\s+/, '').replace(/^Product\s*:\s*/i, '').trim();
                var brand = '';
                var prod = cleaned;

                /* Prefer Yotpo's own brand metadata whenever it is available. */
                var brandEl = card && card.querySelector('[data-brand], [class*="product-brand"], [class*="brand-name"]');
                if (brandEl) {
                  brand = (brandEl.getAttribute('data-brand') || brandEl.textContent || '').trim().toUpperCase();
                  if (brand && cleaned.toLowerCase().indexOf(brand.toLowerCase()) === 0) {
                    prod = cleaned.substring(brand.length).replace(/^[\s\-–—:]+/, '').trim() || cleaned;
                  }
                }

                for (var i = 0; !brand && i < knownBrands.length; i++) {
                  var b = knownBrands[i];
                  if (cleaned.toLowerCase().indexOf(b.toLowerCase()) === 0) {
                    brand = b.toUpperCase();
                    prod = cleaned.substring(b.length).replace(/^[\s\-–—:]+/, '').trim();
                    break;
                  }
                }
                return {
                  brand: brand || 'FRAGRANCE',
                  prod: prod || cleaned || 'Luxury Scent'
                };
              }

              function formatCard(card) {
                if (card.getAttribute('data-lux-done') === 'true' && card.querySelector('.lux-card-inner')) return;

                var bodyEl = card.querySelector('.carousel-review-body, [class*="review-body"]');
                var starsEl = card.querySelector('.stars-container, .yotpo-review-stars, .yotpo-stars, [class*="stars"]');
                var authorEl = card.querySelector('.carousel-review-author, .yotpo-reviewer-name, [class*="review-author"]');

                if (!bodyEl && !starsEl && !authorEl) return;

                // 1. Stars
                var starsHtml = starsEl ? starsEl.innerHTML : '';
                if (!starsHtml || starsHtml.trim() === '') {
                  starsHtml = '<span class="yotpo-icon yotpo-icon-star">★</span>'.repeat(5);
                }

                // 2. Title & Body
                var titleEl = card.querySelector('.carousel-review-title, [class*="review-title"]');
                var titleText = titleEl ? titleEl.textContent.trim() : '';
                var bodyText = bodyEl ? bodyEl.textContent.trim() : '';

                var fullText = '';
                if (titleText && bodyText && bodyText.indexOf(titleText) === -1) {
                  var punct = /[.!?]$/.test(titleText) ? ' ' : '. ';
                  fullText = titleText + punct + bodyText;
                } else {
                  fullText = bodyText || titleText || 'Outstanding fragrance and authentic quality.';
                }

                // 3. Date & Author
                var dateEl = card.querySelector('.carousel-review-date, .yotpo-review-date, [class*="review-date"]');
                var dateText = dateEl ? dateEl.textContent.trim() : '';

                var authorRaw = authorEl ? authorEl.textContent.trim() : 'Customer';
                if (!dateText) {
                  var dMatch = authorRaw.match(/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4}|[A-Za-z]{3,9}\s+\d{4}|[A-Za-z]{3,9}\s+\d{1,2},\s*\d{4})/);
                  if (dMatch) {
                    dateText = dMatch[1];
                    authorRaw = authorRaw.replace(dMatch[0], '').trim();
                  }
                }

                var formattedDate = formatLuxDate(dateText);
                var authorName = authorRaw
                  .replace(/✓/g, '')
                  .replace(/Verified\s*(Buyer|Reviewer|Customer)/gi, '')
                  .replace(/\s+/g, ' ')
                  .trim() || 'Customer';

                // 4. Product Image
                var imgEl = card.querySelector('img');
                var imgSrc = '';
                if (imgEl) {
                  imgSrc = imgEl.getAttribute('src') || imgEl.getAttribute('data-src') || imgEl.getAttribute('data-original') || imgEl.src || '';
                }
                if (!imgSrc) {
                  var bgEl = card.querySelector('[style*="background-image"]');
                  if (bgEl) {
                    var m = bgEl.style.backgroundImage.match(/url\(["']?([^"']+)["']?\)/);
                    if (m) imgSrc = m[1];
                  }
                }

                // 5. Product Brand, Title, Link
                var prodTextEl = card.querySelector('.carousel-review-product-text, .product-text, [class*="product-text"]');
                var linkEl = card.querySelector('.carousel-review-product-text a, a.product-link, a[href*="/product"], [class*="product"] a') || card.querySelector('a');
                var href = (linkEl && linkEl.getAttribute('href')) ? linkEl.getAttribute('href') : '#';
                var rawProdText = prodTextEl ? prodTextEl.textContent.trim() : (linkEl ? linkEl.textContent.trim() : '');

                var bp = getBrandAndProduct(rawProdText, card);
                var monogram = getMonogram(bp.brand);

                // Build Clean Structure
                card.innerHTML = [
                  '<div class="lux-card-inner">',
                  '  <div class="lux-card-top">',
                  '    <div class="lux-stars">' + starsHtml + '</div>',
                  '    <div class="lux-quote" aria-hidden="true">”</div>',
                  '  </div>',
                  '  <div class="lux-card-body">',
                  '    <p class="lux-body-text">' + escapeHtml(fullText) + '</p>',
                  '  </div>',
                  '  <div class="lux-card-author-row">',
                  '    <div class="lux-author-info">',
                  '      <span class="lux-author-name">' + escapeHtml(authorName) + '</span>',
                  '      <span class="lux-verified-badge">',
                  '        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#8C6D28" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
                  '        Verified Buyer',
                  '      </span>',
                  '    </div>',
                  (formattedDate ? '    <span class="lux-review-date">' + escapeHtml(formattedDate) + '</span>' : ''),
                  '  </div>',
                  '  <div class="lux-card-divider"></div>',
                  '  <div class="lux-card-product-row">',
                  '    <div class="lux-product-thumb">',
                  (imgSrc
                    ? '      <img src="' + imgSrc + '" alt="' + escapeHtml(bp.prod) + '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';"><span class="lux-monogram" style="display:none;">' + monogram + '</span>'
                    : '      <span class="lux-monogram">' + monogram + '</span>'),
                  '    </div>',
                  '    <div class="lux-product-meta">',
                  '      <span class="lux-brand-name">' + escapeHtml(bp.brand) + '</span>',
                  '      <a href="' + href + '" class="lux-product-title">' + escapeHtml(bp.prod) + '</a>',
                  '    </div>',
                  '  </div>',
                  '</div>'
                ].join('\n');

                card.setAttribute('data-lux-done', 'true');
              }

              function enhanceYotpoReviews() {
                var cards = document.querySelectorAll('.home_review .single-carousel');
                if (!cards.length) return;
                for (var i = 0; i < cards.length; i++) {
                  formatCard(cards[i]);
                }
              }

              // Observer for dynamic Yotpo carousel changes & pagination
              var reviewSection = document.querySelector('.home_review');
              if (reviewSection) {
                var observer = new MutationObserver(function() {
                  enhanceYotpoReviews();
                });
                observer.observe(reviewSection, { childList: true, subtree: true });
              }

              // Periodic polling to catch initial Yotpo asynchronous load
              var attempts = 0;
              var pollInterval = setInterval(function() {
                enhanceYotpoReviews();
                attempts++;
                if (attempts > 40) clearInterval(pollInterval);
              }, 250);

              document.addEventListener('DOMContentLoaded', enhanceYotpoReviews);
              window.addEventListener('load', enhanceYotpoReviews);

            })();
            </script>
            <!-- TrustBox widget - Carousel -->
            {{-- <div class="trustpilot-widget" data-locale="en-US" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="56b63b590000ff00058894fd" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="en">
                <a href="https://www.trustpilot.com/review/maxaroma.com" target="_blank" rel="noopener">Trustpilot</a>
            </div> --}}
            <!-- End TrustBox widget -->
        </div>
  </section>
  <section class="reassurance-sec topspacing">
    <div class="container">
      <div class="reassurance-badges-grid">
        <div class="reassurance-badge-card">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.4" style="flex:none">
            <path d="M12 3a5 5 0 100 10 5 5 0 000-10zM9 13l-1.5 8L12 18l4.5 3L15 13"></path>
          </svg>
          <div style="display:flex;flex-direction:column;line-height:1.3">
            <span class="reassurance-badge-title">Best Online Shops</span>
            <span class="reassurance-badge-sub">Newsweek &amp; Statista · 2026</span>
          </div>
        </div>
        <div class="reassurance-badge-card">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.4" style="flex:none">
            <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
            <path d="M9 12l2 2 4-4"></path>
          </svg>
          <div style="display:flex;flex-direction:column;line-height:1.3">
            <span class="reassurance-badge-title">41,800 certified reviews</span>
            <span class="reassurance-badge-sub">Yotpo Verified Reviews</span>
          </div>
        </div>
        <div class="reassurance-badge-card">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.4" style="flex:none">
            <path d="M12 3.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8-5.3-2.8-5.3 2.8 1-5.8L3.5 9.7l5.9-.9L12 3.5z"></path>
          </svg>
          <div style="display:flex;flex-direction:column;line-height:1.3">
            <span class="reassurance-badge-title">4.8★ average rating</span>
            <span class="reassurance-badge-sub">Across all verified reviews</span>
          </div>
        </div>
        <div class="reassurance-badge-card">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.4" style="flex:none">
            <path d="M12 2l2.4 1.8 3 .1 1 2.8 2.4 1.7-.9 2.8.9 2.8-2.4 1.7-1 2.8-3 .1L12 22l-2.4-1.8-3-.1-1-2.8L3.2 15.7l.9-2.8-.9-2.8 2.4-1.7 1-2.8 3-.1L12 2zM8.5 12l2.5 2.5 4.5-5"></path>
          </svg>
          <div style="display:flex;flex-direction:column;line-height:1.3">
            <span class="reassurance-badge-title">100% Authentic</span>
            <span class="reassurance-badge-sub">Sourced direct, guaranteed</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>


<script>
  (function() {
    var LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

    var UGC = [{
        type: 'creator',
        platform: 'TIKTOK',
        youtubeId: '8EETZnsVG1k',
        postUrl: 'https://www.tiktok.com/',
        handle: '@scentedsofia',
        mark: 'MB',
        brand: 'Mes Bisous',
        product: 'Crumble Me Softly Extrait de Parfum',
        price: 275,
        listPrice: 320,
        href: 'PDP.dc.html'
      },
      {
        type: 'creator',
        platform: 'INSTAGRAM',
        youtubeId: 'Rgem4sJGd7E',
        postUrl: 'https://www.instagram.com/',
        handle: '@noseknowsnyc',
        mark: 'C',
        brand: 'Creed',
        product: 'Aventus Eau de Parfum',
        price: 340,
        listPrice: null,
        href: 'PDP.dc.html'
      },
      {
        type: 'creator',
        platform: 'TIKTOK',
        youtubeId: 'Rgem4sJGd7E',
        postUrl: 'https://www.tiktok.com/',
        handle: '@fragfiles',
        mark: 'MFK',
        brand: 'Maison Francis Kurkdjian',
        product: 'Baccarat Rouge 540 Eau de Parfum',
        price: 325,
        listPrice: null,
        href: 'PDP.dc.html'
      },
      {
        type: 'creator',
        platform: 'INSTAGRAM',
        youtubeId: '8EETZnsVG1k',
        postUrl: 'https://www.instagram.com/',
        handle: '@wearitwithmarco',
        mark: 'PdM',
        brand: 'Parfums de Marly',
        product: 'Layton Eau de Parfum',
        price: 365,
        listPrice: null,
        href: 'PDP.dc.html'
      },
      {
        type: 'own',
        platform: 'MAXAROMA',
        youtubeId: 'Rgem4sJGd7E',
        postUrl: 'https://www.youtube.com/',
        handle: '@maxaroma',
        mark: 'HD',
        brand: 'House of Dastan',
        product: 'Discovery Set — Six Extraits',
        price: 60,
        listPrice: 78,
        href: 'PDP.dc.html'
      },
      {
        type: 'creator',
        platform: 'TIKTOK',
        youtubeId: 'Rgem4sJGd7E',
        postUrl: 'https://www.tiktok.com/',
        handle: '@gourmandghost',
        mark: 'K',
        brand: 'Kilian Paris',
        product: "Angels' Share Eau de Parfum",
        price: 320,
        listPrice: null,
        href: 'PDP.dc.html'
      },
      {
        type: 'own',
        platform: 'MAXAROMA',
        youtubeId: 'Rgem4sJGd7E',
        postUrl: 'https://www.youtube.com/',
        handle: '@maxaroma',
        mark: 'X',
        brand: 'Xerjoff',
        product: 'Naxos Eau de Parfum',
        price: 255,
        listPrice: null,
        href: 'PDP.dc.html'
      },
      {
        type: 'creator',
        platform: 'INSTAGRAM',
        youtubeId: '8EETZnsVG1k',
        postUrl: 'https://www.instagram.com/',
        handle: '@softspraysam',
        mark: 'N',
        brand: 'Nishane',
        product: 'Hacivat Extrait de Parfum',
        price: 215,
        listPrice: null,
        href: 'PDP.dc.html'
      }
    ];

    var PLATFORM_NAMES = {
      TIKTOK: 'TikTok',
      INSTAGRAM: 'Instagram',
      YOUTUBE: 'YouTube',
      MAXAROMA: 'YouTube'
    };
    var TARGET = new Date('2026-08-30T23:59:59').getTime();

    function pad(n) {
      return String(n).padStart(2, '0');
    }

    function money(n) {
      return '$' + n;
    }

    /* ── UGC modal ── */
    var modal = document.querySelector('.ugc-modal');
    var ugcIndex = 0;
    var ugcAdded = false;
    var ugcTrigger;

    function renderUgc() {
      var cur = UGC[ugcIndex];
      var hasList = cur.listPrice != null && cur.listPrice > cur.price;

      var handleEl = modal.querySelector('.ugc-modal-handle');
      if (handleEl) handleEl.textContent = cur.handle;

      var badgeEl = modal.querySelector('.ugc-modal-platform-badge');
      if (badgeEl) badgeEl.textContent = PLATFORM_NAMES[cur.platform] || cur.platform;

      var markEl = modal.querySelector('.ugc-modal-mark');
      if (markEl) markEl.textContent = cur.mark;

      var brandEl = modal.querySelector('.ugc-modal-brand');
      if (brandEl) brandEl.textContent = cur.brand.toUpperCase();

      var nameEl = modal.querySelector('.ugc-modal-name');
      if (nameEl) {
        nameEl.textContent = cur.product;
        nameEl.setAttribute('href', cur.href);
      }

      var priceEl = modal.querySelector('.ugc-modal-price');
      if (priceEl) priceEl.textContent = money(cur.price);

      var listEl = modal.querySelector('.ugc-modal-list');
      if (listEl) {
        listEl.textContent = hasList ? money(cur.listPrice) : '';
        listEl.classList.toggle('is-on', hasList);
      }

      var atcBtn = modal.querySelector('.ugc-modal-atc');
      if (atcBtn) atcBtn.textContent = ugcAdded ? 'ADDED ✓' : 'ADD TO CART';

      var shopLink = modal.querySelector('.ugc-shop');
      if (shopLink) {
        shopLink.setAttribute('href', cur.href);
        shopLink.textContent = 'SHOP ' + cur.brand.toUpperCase();
      }

      var watchLink = modal.querySelector('.ugc-watch');
      if (watchLink) {
        watchLink.setAttribute('href', cur.postUrl);
        watchLink.textContent = 'WATCH ON ' + (cur.type === 'own' ? 'YOUTUBE' : (PLATFORM_NAMES[cur.platform] || cur.platform));
      }

      var stageEl = modal.querySelector('.ugc-modal-stage');
      if (stageEl) {
        var ytId = cur.youtubeId || '8EETZnsVG1k';
        stageEl.innerHTML = '<iframe class="ugc-modal-iframe" src="https://www.youtube.com/embed/' + ytId + '?autoplay=1&mute=1&playsinline=1&rel=0" title="' + cur.product.replace(/"/g, '') + ' video review" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
      }
    }

    function openUgc(i) {
      ugcTrigger = document.activeElement;
      ugcIndex = i;
      ugcAdded = false;
      document.body.style.overflow = 'hidden';
      renderUgc();
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      modal.inert = false;
      modal.querySelector('.ugc-modal-close').focus();
    }

    function closeUgc() {
      document.body.style.overflow = '';
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      modal.inert = true;
      var stageEl = modal.querySelector('.ugc-modal-stage');
      if (stageEl) stageEl.innerHTML = '';
      if (ugcTrigger) ugcTrigger.focus();
    }

    document.querySelectorAll('.ugc-tile').forEach(function(btn) {
      btn.addEventListener('click', function() {
        openUgc(Number(btn.getAttribute('data-index')));
      });
    });

    modal.querySelector('.ugc-modal-backdrop').addEventListener('click', closeUgc);
    modal.querySelector('.ugc-modal-card').addEventListener('click', function(e) {
      e.stopPropagation();
    });
    modal.querySelector('.ugc-modal-close').addEventListener('click', function(e) {
      e.stopPropagation();
      closeUgc();
    });
    modal.querySelector('.ugc-modal-prev').addEventListener('click', function(e) {
      e.stopPropagation();
      ugcIndex = (ugcIndex + UGC.length - 1) % UGC.length;
      ugcAdded = false;
      renderUgc();
    });
    modal.querySelector('.ugc-modal-next').addEventListener('click', function(e) {
      e.stopPropagation();
      ugcIndex = (ugcIndex + 1) % UGC.length;
      ugcAdded = false;
      renderUgc();
    });
    modal.querySelector('.ugc-modal-atc').addEventListener('click', function() {
      ugcAdded = true;
      renderUgc();
      setTimeout(function() {
        ugcAdded = false;
        if (modal.classList.contains('is-open')) renderUgc();
      }, 1400);
    });

    window.addEventListener('keydown', function(e) {
      if (!modal.classList.contains('is-open')) return;
      if (e.key === 'Escape') {
        closeUgc();
        return;
      }
      if (e.key === 'Tab') {
        var focusable = modal.querySelectorAll('button:not([disabled]), [href]:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
        if (!focusable.length) return;
        var first = focusable[0];
        var last = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    });

    /* Fill A–Z letter rows if empty placeholders exist */
    document.querySelectorAll('[data-letters]').forEach(function(wrap) {
      if (wrap.children.length) return;
      LETTERS.forEach(function(l) {
        var a = document.createElement('a');
        a.href = '#brands';
        a.textContent = l;
        a.className = wrap.getAttribute('data-letter-class');
        wrap.appendChild(a);
      });
    });
  })();
</script>

@endsection
