@extends('layouts.app')
@section('content')
	<main>
		<section class="brnd-h-bnr" @if($BrandDetail[0]->header_image != '') style="background-image: url(https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$BrandDetail[0]->header_image}}); background-repeat: no-repeat;" @endif >
			<div class="brnd-h-over text-center">
				@if($BrandDetail[0]->overlap_brand_logo != '')
				<div class="brand-h-logo">
					<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$BrandDetail[0]->overlap_brand_logo}}" alt="">
				</div>
				@endif
				@if($BrandDetail[0]->is_brand_show == 'Yes')<h1>{{$BrandDetail[0]->vmanufacture}}</h1>@endif
			</div>
		</section>
		@if($BrandDetail[0]->authorized_dealer_logo!='')
		<section class="auth-logo-sec">
			<div class="container position-relative">
				<div class="auth-logo-b">
					<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$BrandDetail[0]->authorized_dealer_logo}}" alt="">
				</div>
			</div>
		</section>
		@endif
		<section class="brand-h-sec">
			<div class="container">
				<div class="row">
				@if($BrandDetail[0]->brand_history != '')	
				<div class="col-12 text-center pt-4 pt-md-0">
					<div class="brand-phd">
						@if($BrandDetail[0]->is_brand_show == 'Yes')
							<h3>About {{$BrandDetail[0]->vmanufacture}}</h3>
						@else 
							<h1>About {{$BrandDetail[0]->vmanufacture}}</h1>
						@endif 
						<p>{!!$BrandDetail[0]->brand_history!!}</p>
					</div>
				</div>
				@endif
				@if($historyImgs && count($historyImgs) > 0)
				<div class="col-12">
				   <div class="row row0">
					   @for($i = 0; $i < count($historyImgs); $i++)
					   <div class="col-sm-4 pt-sm-3 pb-sm-3 pt-2 pb-2 text-center"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$historyImgs[$i]}}" alt=""></div>
					   @endfor
				   </div>
				</div>
				@endif
				</div>
			</div>
		</section>
		<section class="brand-expore-coll">
			<div class="container">
				<div class="row">
				<div class="col-12 text-center">
					<div class="brand-phd brand-phd2">
						<h3>Explore the Collection 
							<!-- <span><a href="#" class="viewall">See More</a></span> -->
						</h3>
					</div>
				</div>
				<div class="col-12 pt-3 pb-3">
				   <div class="brand-ex-list">
					   <ul class="brand-page-listul" id="brandProducts">
							@foreach($Products as $key => $Product)
								<li class="brand-page-list">
									<x-productbox :prodData="$Product"/>
								</li>
							@endforeach
					   </ul>
				   </div>
				</div>
				</div>
				<div class="pb-2" id="loadmore" style="@if($TotalProducts <= 10) display:none @endif">
					<a class="list-more d-block" data-page='1' data-brand-id="{{$BrandDetail[0]->imanufactureid}}"> Load More... </a>
				</div>
				<div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>
			</div>
		</section>
		@if($BrandDetail[0]->video_show=='Yes')     
		<section class="brand-video-sec pb-md-5 pt-md-5 pb-3 pt-3">
			<div class="container">
				<div class="row row10">
				@if($BrandDetail[0]->youtubelink1 != "")
					<div class="col-sm-6 pb-2 pt-2">
						<iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink1}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
				@endif
				@if($BrandDetail[0]->youtubelink2 != "")
					<div class="col-sm-6 pb-2 pt-2">
						<iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink2}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
				@endif
				</div>
			</div>
		</section>
		@endif
	<!-- Content Part End Here -->
	</main>
@endsection
