@extends('layouts.app')
@section('content')
<main>
	<div class="brand-newpage box-blackbg">
		@if(isset($AllSections[1]))
		<section class="brands-banner banner-textwhite brandpage-space" role="region" aria-labelledby="brands-heading">
			<div class="container">
				<div class="brands-content">
					<div class="brands-text">
						<div class="brands-logo">
							<h2 id="brands-heading" class="logo-main">
								@if(isset($AllSections[1]['logo']) && $AllSections[1]['logo'] != '')
								<img
									src="{{$AllSections[1]['logo']}}"
									alt="MaxAroma Edit Logo"
									loading="lazy"
								width="394"
								height="70">
								@endif
							</h2>
						</div>
						<p class="brands-description">{!! nl2br($AllSections[1]['short_description']) !!}</p>
					</div>
					<div class="brands-image-wrapper">
						@if(isset($AllSections[1]['header_image']) && $AllSections[1]['header_image'] != '')
						<img
							src="{{$AllSections[1]['header_image']}}"
							alt="Featured MaxAroma fragrance collection display"
							class="brands-image"
							loading="lazy"
							width="880"
							height="840">
						@endif
					</div>
				</div>
			</div>
		</section>
		@endif

		<!-- Introduction to Brands Section -->
		<section class="introduction-section" role="region" aria-labelledby="introduction-heading">
			<div class="container">
				<!-- <h2 id="introduction-heading" class="sr-only">Brand Collections</h2> -->
				<div class="brands-grid">
					@if(isset($AllSections[2]) && count($AllSections[2]) > 0)
						@foreach($AllSections[2] as $brandData)
							@if(!empty($brandData['brand_image']))
								<article class="brand-card " itemscope="" itemtype="https://schema.org/Brand">
									<div class="brand-image-wrapper">
										<a class="brand-link" href="{{ $brandData['link']??'#' }}" aria-label="View {{ $brandData['brand_title'] ?? 'brand' }} collection">
											<img src="{{$brandData['brand_image']}}" alt="{{ $brandData['brand_title'] ?? 'Brand image' }}" class="brand-image" loading="lazy" width="440" height="530">
										</a>
									</div>
									<div class="brand-info">
										<h3 class="brand-name" itemprop="name">
											<span>NEW ARRIVAL</span>
											{{ $brandData['brand_title'] }}
										</h3>
									</div>
								</article>
							@endif
						@endforeach
					@endif

					@if(isset($AllSections[3]))
					<article class="brand-card brand-banner" itemscope="" itemtype="https://schema.org/Brand">
						@if(!empty($AllSections[3]['header_image']))
						<div class="brand-image-wrapper">
							<a class="brand-link" href="{{$AllSections[3]['link']??'#'}}" aria-label="View brand collection">
								<picture>
									<source media="(max-width: 767px)" srcset="{{ $AllSections[3]['header_image_mobile'] }}" alt="MaxAroma brand banner mobile version">

									<img
										src="{{ $AllSections[3]['header_image'] }}"
										alt="{{ $AllSections[3]['brand_title'] ?? 'Brand image' }}"
									class="brand-image"
									loading="lazy"
									width="1920"
									height="390">
								</picture>
							</a>
						</div>
						@endif
						<div class="brand-info">
							@if(!empty($AllSections[3]['sec_label']) || !empty($AllSections[3]['title']))
							<h3 class="brand-name" itemprop="name">
								@if(!empty($AllSections[3]['sec_label']))
								<span>{{ $AllSections[3]['sec_label'] }}</span>
								@endif
								@if(!empty($AllSections[3]['title']))
								{{$AllSections[3]['title']}}
								@endif
							</h3>
							@endif
							<p>{!! $AllSections[3]['introduction'] !!}</p>
						</div>
					</article>
					@endif

					@if(isset($AllSections[4]) && count($AllSections[4]) > 0)
						@foreach($AllSections[4] as $brandData)
							@if(!empty($brandData['brand_image']))
								<article class="brand-card " itemscope="" itemtype="https://schema.org/Brand">
									<div class="brand-image-wrapper">
										<a class="brand-link" href="{{ $brandData['link']??'#' }}" aria-label="View {{ $brandData['brand_title'] ?? 'brand' }} collection">
											<img src="{{$brandData['brand_image']}}" alt="{{ $brandData['brand_title'] ?? 'Brand image' }}" class="brand-image" loading="lazy" width="440" height="530">
										</a>
									</div>
									<div class="brand-info">
										<h3 class="brand-name" itemprop="name">
											<span>NEW ARRIVAL</span>
											{{ $brandData['brand_title'] }}
										</h3>
									</div>
								</article>
							@endif
						@endforeach
					@endif

					@if(isset($AllSections[5]))
					<article class="brand-card brand-banner" itemscope="" itemtype="https://schema.org/Brand">
						@if(!empty($AllSections[5]['header_image']))
						<div class="brand-image-wrapper">
							<a class="brand-link" href="{{ $AllSections[5]['link']??'#' }}" aria-label="View OJAR collection">
								<picture>
									<source media="(max-width: 767px)" srcset="{{ $AllSections[5]['header_image_mobile'] }}" alt="MaxAroma brand banner mobile version">

									<img
										src="{{ $AllSections[5]['header_image'] }}"
										alt="Maxaroma Edit"
									class="brand-image"
									loading="lazy"
									width="1920"
									height="390">
								</picture>
							</a>
						</div>
						@endif
						<div class="brand-info tdnone">
							<h3 class="brand-name" itemprop="name">{{ $AllSections[5]['title'] }}</h3>
						</div>
					</article>
					@endif
				</div>
				<div class="brands-grid brands-grid4">
					@if(isset($AllSections[6]) && count($AllSections[6]) > 0)
						@foreach($AllSections[6] as $brandData)
							@if(!empty($brandData['brand_image']))
								<article class="brand-card " itemscope="" itemtype="https://schema.org/Brand">
									<div class="brand-image-wrapper">
										<a class="brand-link" href="{{ $brandData['link']??'#' }}" aria-label="View {{ $brandData['brand_title'] ?? 'brand' }} collection">
											<img src="{{$brandData['brand_image']}}" alt="{{ $brandData['brand_title'] ?? 'Brand image' }}" class="brand-image" loading="lazy" width="440" height="530">
										</a>
									</div>
									<div class="brand-info">
										<h3 class="brand-name" itemprop="name">
											<span>NEW ARRIVAL</span>
											{{ $brandData['brand_title'] }}
										</h3>
									</div>
								</article>
							@endif
						@endforeach
					@endif
				</div>
			</div>
		</section>

</div>
</main>
@endsection