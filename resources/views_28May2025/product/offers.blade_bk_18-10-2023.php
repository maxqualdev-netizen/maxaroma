@extends('layouts.app')
@section('content')
<main class="clearfix">
	<section>
		<div class="container pb-5">
			<div class="row">
				<div class="col-12 pt-lg-5 pt-4">
					<div class="mainhd">
						<h1>SALES & OFFERS</h1>
					</div>
				</div>
			</div>
			<div class="row">
				@if($siteOffer)
					@foreach($siteOffer as $key => $value)
						<div class="col-6 col-sm-4 col-lg-3 p-lg-3 p-2" onclick="offervalid('{{$value['site_offers_id']}}','{{$value['offer_title']}}')">
							<div class="sale-of">
								<a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}">
									<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$value['offer_banner']}}" width="400" height="600">
								</a>
								<div class="sale-over">
									<div class="sale-over-text">
											Ends by {{ date('F', strtotime($value['expiry_date'])) }} {{ date('d', strtotime($value['expiry_date'])) }}
										<h4><span class="d-block">{{$value['offer_title']}}</span></h4>
										<a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $value['offer_link']) }}">Shop Now</a>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>	
		</div>
	</section>
</main>
@endsection
