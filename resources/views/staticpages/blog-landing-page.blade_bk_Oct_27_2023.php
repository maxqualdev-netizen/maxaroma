@extends('layouts.app')
@section('content')


<section class="breadcrumb">
		<div class="container">
			<a href="{{url('/')}}">Home</a>
			<span class="active">{{str_replace('-', ' ', ucwords(request()->segment(1)))}}</span>
		</div>
	</section>

		<div class="pt-md-4 pt-3">
			<div class="mainhd"><h1>{{ucwords(str_replace('-', ' ', ucwords($main_blog_title)))}}</h1></div>			
		</div>	

	<div class="landing-dtlblog pb-4">
		<div class="container">
			@if(count($BlogSection) > 0)
			@foreach($BlogSection as $key => $LandingPage)
			 @if($LandingPage->blog_section_id == 1)
			<div class="row">
				<div class="col-12 text-center pb-4">
					@if($LandingPage->main_banner_img!='')
								<img class="d-md-block d-none" src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="1600" height="500" loading="lazy" alt="{{$LandingPage->banner_title}}"  title="{{$LandingPage->banner_title}}">

								<!-- <img class="d-md-block d-none" src="https://via.placeholder.com/1600x500" width="1600" height="500"> -->
								@endif

								@if($LandingPage->mobile_banner_img!='')
					          <img class="d-md-none" src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->mobile_banner_img}}" width="768" height="768" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
					          @else
					          <img class="d-md-none" src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="768" height="768" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
                           @endif
				        </div>
			          </div>
			      @endif
           
            @if($LandingPage->blog_section_id == 10)
			<div class="row pb-md-5 pb-4">
				 <div class="col-12">
					<p>{!!$LandingPage->banner_description!!}</p>
				</div>
				
			</div>
			@endif

			@if($LandingPage->blog_section_id == 11)
			<div class="row pb-md-5 pb-4">	
				<div class="col-12">
					<p>{!!$LandingPage->banner_description!!}</p>
					@if($LandingPage->banner_link_title!='')
					<p><a href="{{$LandingPage->banner_link}}" class="btn btn-primary mt-2" target="_blank">{{$LandingPage->banner_link_title}}</a></p>
					@endif
				</div>
			</div>
			@endif
			@endforeach

			<div class="row pb-md-5 pb-4">
                  @foreach($BlogSection as $key => $LandingPage)
				 @if($LandingPage->blog_section_id == 2)
				<div class="col-12 pt-4 pb-4">
					<div class="blog-left-rbox">
						<div class="row no-gutters">
							<div class="col-lg-6 text-center">
								@if($LandingPage->main_banner_img!='')
								<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="800" height="500" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@else
                                <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/800x500" width="800" height="500" loading="lazy">
								@endif
							</div>
							<div class="col-lg-6 d-flex align-items-center">
								<div class="blog-textbox">
									<h2 class="pb-2">{!!$LandingPage->banner_title!!}</h2>
									<p>{!!$LandingPage->banner_description!!}</p>
									@if($LandingPage->blog_section_id == 2 && $LandingPage->banner_link_title!='')
									<p><a href="{{$LandingPage->banner_link}}" class="btn btn-primary mt-2" target="_blank">{{$LandingPage->banner_link_title}}</a></p>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif

               @if($LandingPage->blog_section_id == 3)

				<div class="col-12 pt-4 pb-4">
					<div class="blog-left-rbox">
						<div class="row no-gutters">
							<div class="col-lg-6 order-lg-2 text-center">
								@if($LandingPage->main_banner_img!='')
								<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="800" height="500" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@else
                                <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/800x500" width="800" height="500" loading="lazy">
								@endif
							</div>
							<div class="col-lg-6 d-flex align-items-center order-lg-1">
								<div class="blog-textbox blog-lefttextbox">
									<h2 class="pb-2">{!!$LandingPage->banner_title!!}</h2>
									<p>{!!$LandingPage->banner_description!!}</p>
									@if($LandingPage->blog_section_id == 3 && $LandingPage->banner_link_title!='')
									<p><a href="{{$LandingPage->banner_link}}" class="btn btn-primary mt-2" target="_blank">{{$LandingPage->banner_link_title}}</a></p>
									@endif
                               </div>
							</div>
						</div>
					</div>
				</div>
				@endif
				@endforeach
				
			</div>
			<div class="row pb-md-5 pb-4">
				@foreach($BlogSection as $key => $LandingPage)
                 @if($LandingPage->blog_section_id == 6)

				<div class="col-12 pt-3 pb-3">
					<div class="blog-left-rbox small-rbox">
						<div class="row">
							<div class="col-md-3 text-center pb-3">
								@if($LandingPage->main_banner_img!='')
								<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="400" height="400" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@else
                                <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/400x400" width="800" height="500" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@endif
							</div>
							<div class="col-md-9 d-flex align-items-center">
								<div class="blog-textbox p-0">
									<h2 class="pb-2">{!!$LandingPage->banner_title!!}</h2>
									<p>{!!$LandingPage->banner_description!!}</p>
									@if($LandingPage->blog_section_id == 6 && $LandingPage->banner_link_title!='')
									<p><a href="{{$LandingPage->banner_link}}" class="btn btn-primary mt-2" target="_blank">{{$LandingPage->banner_link_title}}</a></p>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				
                 @if($LandingPage->blog_section_id == 7)
				<div class="col-12 pt-3 pb-3">
					<div class="blog-left-rbox small-rbox">
						<div class="row">
							<div class="col-md-3 text-center order-md-2 pb-3">
								@if($LandingPage->main_banner_img!='')
								<img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="400" height="400" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@else
                                <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/400x400" width="800" height="500" loading="lazy" alt="{{$LandingPage->banner_title}}" title="{{$LandingPage->banner_title}}">
								@endif
							</div>
							<div class="col-md-9 d-flex align-items-center order-md-1">
								<div class="blog-textbox p-0">
									<h2 class="pb-2">{!!$LandingPage->banner_title!!}</h2>
									<p>{!!$LandingPage->banner_description!!}</p>
									@if($LandingPage->blog_section_id == 7 && $LandingPage->banner_link_title!='')
									<p><a href="{{$LandingPage->banner_link}}" class="btn btn-primary mt-2" target="_blank">{{$LandingPage->banner_link_title}}</a></p>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				@endforeach
			</div>


			<div class="row pb-md-5 pb-4">
				<div class="col-12">
					<div class="row row10">
						@foreach($BlogSection as $key => $LandingPage)
                         @if($LandingPage->blog_section_id == 5)
						<div class="col-sm-6 pb-4">
							<div class="row row5">
								@if($LandingPage->main_banner_img!='')
								<div class="col-12 text-center"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$LandingPage->main_banner_img}}" width="800" height="500" loading="lazy" title="{{$LandingPage->banner_title}}"></div>
								@else
								<div class="col-12 text-center"><img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/800x500" width="800" height="500" loading="lazy"></div>
								@endif
								<div class="col-12">
									@if($LandingPage->banner_title!='')
									<h3 class="pt-4 pb-2">{{$LandingPage->banner_title}}</h3>
								   @endif

								   @if($LandingPage->banner_link_title!='')
									<p><a href="{{$LandingPage->banner_link}}" class="ulink" target="_blank"><i>{{$LandingPage->banner_link_title}}</i></a></p>
									@endif

									 @if($LandingPage->banner_description!='')
							          <p>{!!$LandingPage->banner_description!!}</p>
							         @endif
								</div>
							</div>
						</div>
						@endif
						@endforeach
						
					</div>
				</div>
			</div>
			
            @foreach($BlogSection as $key => $LandingPage)
			@if($LandingPage->blog_section_id == 8)

			<div class="row pb-md-5 pb-4">
				<div class="col-12">
					@if($TotalProducts > 0)
					<div class="blog-prd">
						
						<div class="row row10 text-center">
							@foreach($ProductData as $key => $Product)

							<div class="col-md-3 col-sm-6 col-6 pb-4 pt-4">
								<div class="blog-prdbox">
									<x-productbox :prodData="$Product"/>
									<!-- <a href="{{$Product->product_url}}"><img src="{{$Product->prod_image}}" width="400" height="400"></a>
									<h4 class="pt-3">{!!$Product->referencedName!!} {{$Product->vtype}}</h4>
									<p class="blog-price">${{$Product->minPrice}} - ${{$Product->maxPrice}}</p>
									<a href="javascript:void(0);" class="btn btn-primary prodaddcart" data-product="{{$Product->products_id}}">Add to cart</a>
 -->
								</div>
							</div>
						@endforeach


						</div>
						<div class="pb-2" id="loadmore" style="@if($TotalProducts <= 10) display:none @endif">
						<a class="list-more d-block" data-page='1'> Load More... </a>
					</div>
					<div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>

					</div>
					@endif
				</div>
			</div>
			@endif
			@endforeach
			
			@else
			<p>No Result Found!</p>
			@endif
			
		</div>
	</div>

@endsection




