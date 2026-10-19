@extends('layouts.app')
@section('content')

<div class="landing-nw">
	<div class="container">
		@if(count($LandingPages) > 0)
			@foreach($LandingPages as $key => $LandingPage)
				@if($LandingPage->section_id == 1)
					<div class="row row10">
						<div class="col-12 pt-4">
							<a href="{{$LandingPage->PageData[0]->banner_link}}">
								<img class="d-md-block d-none" src="{{config('global.SITE_URL').'/images/landing/'.$LandingPage->PageData[0]->banner}}" width="1450" height="250">
								<img class="d-md-none" src="{{config('global.SITE_URL').'/images/landing/'.$LandingPage->PageData[0]->banner}}" width="767" height="250">
							</a>
						</div>
					</div>
				@endif

				@if($LandingPage->section_id == 2)
					<div class="row row10">
						<div class="col-12 pt-lg-5 pt-4"><h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3></div>
						<div class="col-12">
							<div class="row row10">
								@foreach($LandingPage->PageData as $key => $PData)
								<div class="col-sm-6 pb-4">
									<div class="giftbox-nw text-center">
										<a href="{{$PData->banner_link}}">
											<img src="{{config('global.SITE_URL').'/images/landing/'.$PData->banner}}" width="700" height="500">
										</a>
										<div class="gifttext-nw pt-3 pb-2">
											<h4 class="pb-2">{{$PData->banner_title}}</h4>
											<p>{{$PData->banner_desc}}</p>
											<a href="{{$PData->banner_link}}">{{$PData->banner_link_title}}</a>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>	
				@endif
				
				@if($LandingPage->section_id == 3)
					<div class="row row10">
						<div class="col-12 pt-5"><h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3></div>
						<div class="col-12">
							<div class="row row10">
								@foreach($LandingPage->PageData as $key => $PData)
								<div class="col-md-3 col-6 pb-3">
									<a href="{{$PData->banner_link}}">
										<img src="{{config('global.SITE_URL').'/images/landing/'.$PData->banner}}" width="350" height="200">
									</a>
								</div>
								@endforeach
							</div>
						</div>
					</div>	
				@endif	
				
				@if($TotalProducts > 0 && $LandingPage->section_id == 4)
				<div class="container">
					<div class="row">
						<div class="col-12 pt-5">
							<h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3>
						</div>
					
					<?php /*@if(count($BestSellers) > 0)
						<x-slider :attr="$BestSellersAttr" :sliderData="$BestSellers" />
					@endif*/ ?>
					
						<div class="col-12 pt-3 pb-3">
							<div class="brand-ex-list">
								<ul class="brand-page-listul" id="selectedProducts">
									@foreach($BestSellers as $key => $Product)
										<li class="brand-page-list">
											<x-productbox :prodData="$Product"/>
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					<div class="pb-2" id="loadmore" style="@if($TotalProducts <= 10) display:none @endif">
						<a class="list-more d-block" data-page='1'> Load More... </a>
					</div>
					<div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>
				</div>
					
				@endif

				@if($LandingPage->section_id == 5)
					<div class="row row10">
						<div class="col-12 pt-5"><h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3></div>
						<div class="col-12">
							<div class="row row10">
								@foreach($LandingPage->PageData as $key => $PData)
								<div class="col-md-3 col-sm-6 pb-4">
									<div class="giftbox-nw text-center">
										<a href="{{$PData->banner_link}}">
											<img src="{{config('global.SITE_URL').'/images/landing/'.$PData->banner}}" width="400" height="400">
										</a>
										<div class="gifttext-nw pt-3 pb-2">
											<h4 class="pb-2">{{$PData->banner_title}}</h4>
											<p>{{$PData->banner_desc}}</p>
											<a href="{{$PData->banner_link}}">{{$PData->banner_link_title}}</a>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>		
				@endif	

				@if($LandingPage->section_id == 6)
					<div class="row row10">
						<div class="col-12 pt-5"><h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3></div>
						<div class="col-12">
							<div class="row row10">
								@foreach($LandingPage->PageData as $key => $PData)
								<div class="col-md-4 pb-4">
									<div class="giftbox-nw text-center">
										<a href="{{$PData->banner_link}}">
											<img src="{{config('global.SITE_URL').'/images/landing/'.$PData->banner}}" width="500" height="400">
										</a>
										<div class="gifttext-nw pt-3 pb-2">
											<h4 class="pb-2">{{$PData->banner_title}}</h4>
											<p>{{$PData->banner_desc}}</p>
											<a href="{{$PData->banner_link}}">{{$PData->banner_link_title}}</a>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>		
				@endif	

				@if($LandingPage->section_id == 7)
					<div class="row row10">
						<div class="col-12 pt-4 text-center">
							<a href="{{$LandingPage->PageData[0]->banner_link}}">
								<img class="d-md-block d-none" src="{{config('global.SITE_URL').'/images/landing/'.$LandingPage->PageData[0]->banner}}" width="1450" height="150">
								<img class="d-md-none" src="{{config('global.SITE_URL').'/images/landing/'.$LandingPage->PageData[0]->banner}}" width="767" height="150">
							</a>
							<div class="gifttext-nw pt-4 pb-2 lh24">
								<p>{{$LandingPage->PageData[0]->banner_desc}}</p>
								<a href="{{$LandingPage->PageData[0]->banner_link}}">{{$LandingPage->PageData[0]->banner_link_title}}</a>
							</div>
						</div>
					</div>
				@endif		
				
				@if($LandingPage->section_id == 8)
					<div class="row row10">
						<div class="col-12 pt-5">
							<h3 class="gifthd-nw border-bottom pb-lg-4 pb-3 mb-4 text-center">{{$LandingPage->section_name}}</h3>
						</div>
						<div class="col-12">
							<div class="border-bottom pb-2 mb-2">
								<div class="row row10">
									@foreach($LandingPage->PageData as $key => $PData)
									<div class="col-md-3 col-sm-6 pb-4">
										<div class="giftbox-nw text-center round-product">
											<a href="{{$PData->banner_link}}">
												<img src="{{config('global.SITE_URL').'/images/landing/'.$PData->banner}}" width="200" height="200">
											</a>
											<div class="gifttext-nw pt-3 pb-2">
												<h4 class="pb-2">{{$PData->banner_title}}</h4>
												<p>{{$PData->banner_desc}}</p>
												<a href="{{$PData->banner_link}}">{{$PData->banner_link_title}}</a>
											</div>
										</div>
									</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>	
				@endif
				
				@if($LandingPage->section_id == 9)
					<div class="row row10">
						<div class="col-12 pb-3">
							<div class="gifttext-nw pt-3 pb-2 lh24">
								<p>{{$LandingPage->PageData[0]->banner_desc}}</p>
								<p><a href="{{$LandingPage->PageData[0]->banner_link}}">{{$LandingPage->PageData[0]->banner_link_title}}</a></p>
								</p>
							</div>
						</div>
					</div>
				@endif
			
			@endforeach
		@endif	
	</div>
</div>

@endsection
