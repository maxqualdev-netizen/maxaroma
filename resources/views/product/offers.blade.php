@extends('layouts.app')
@section('content')
<main class="clearfix" role="main" id="main-content" tabindex="-1" aria-labelledby="sales-offers-heading">
    <section aria-label="Sales and Offers Section">
        <div class="container pb-5">
            <div class="row">
                <div class="col-12 pt-lg-5 pt-4">
                    <div class="mainhd">
                        <h1 id="sales-offers-heading" tabindex="0">SALES & OFFERS</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                @if($siteOffer)
                    @foreach($siteOffer as $key => $value)
						@if($value['show_to_customers'] == 'All Customers')
							<div class="col-6 col-sm-4 col-lg-3 p-lg-3 p-2" aria-labelledby="offer-title-{{$value['site_offers_id']}}" tabindex="0" onclick="offervalid('{{$value['site_offers_id']}}','{{$value['offer_title']}}')">
								<div class="sale-of" role="region" aria-label="Offer: {{$value['offer_title']}}">
									<a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}" aria-labelledby="offer-title-{{$value['site_offers_id']}}">
										<img src="{{config('global.SPEED_SIZE_URL')}}{{$value['offer_banner']}}" loading="lazy" width="400" height="600" alt="Banner for {{$value['offer_title']}}">
									</a>
									<div class="sale-over">
										<div class="sale-over-text">
											<span id="offer-expiry-{{$value['site_offers_id']}}" aria-label="Offer ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}">
												Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}
											</span>
											<h4>
												<span class="d-block" id="offer-title-{{$value['site_offers_id']}}">{{$value['offer_title']}}</span>
											</h4>
											<a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}" aria-label="Shop now for {{$value['offer_title']}}">Shop Now</a>
										</div>
									</div>
								</div>
							</div>
						@endif
                    @endforeach
                @endif
            </div>	
        </div>
    </section>
</main>
@endsection
