@extends('layouts.app')
@section('content')
	<main>
		<!-- Content Part Start Here -->
		<div class="barnd-banner">
			@if($BrandDetail[0]->header_image != '')
				<img src="{{$BrandDetail[0]->header_image}}">
			@endif
		</div>
		<div class="bdk-perfume">
			<div class="container">
				<div class="bdk-main">
					@if($BrandDetail[0]->imglogo != '')
					<div class="bdk-logo"><img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$BrandDetail[0]->imglogo}}"></div>
					@endif
					@if($BrandDetail[0]->header_desc != '')
                    <p>{{$BrandDetail[0]->header_desc}}</p>
					@endif
                    <a href="{{$BrandDetail[0]->vlink}}" class="btn btn-secondary">See All Prodcuts</a>    
				</div>
			</div>
		</div>    
        <div class="perfume-detail">    
			@if($Submanufacture && count($Submanufacture) > 0)
				@foreach($Submanufacture as $key => $SubManu)	
					@if(($key+1) % 2 != 0)
					<div class="loop-sec common-specing">
						<div class="img-sec">
							@if($SubManu->image != '')
							<a href="{{$SubManu->button_link}}"><img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$SubManu->image}}"></a>
							@endif
						</div>
						<div class="text-sec">
							<h2 class="lending-hd"><a href="{{$SubManu->button_link}}"><span class="big-text">{{substr($SubManu->title,0,1)}}</span>{{substr($SubManu->title,1,strlen($SubManu->title))}}</a></h2>
							{!! stripcslashes($SubManu->left_description) !!}
							<div class="shop-btn"><a href="{{$SubManu->button_link}}" class="btn btn-primary">Shop Now</a></div>
						</div>
					</div>    
					@else 
					<div class="loop-sec img-right-loop common-specing">
						<div class="img-sec">
							@if($SubManu->image != '')
							<a href="{{$SubManu->button_link}}"><img src="{{config('global.SITE_BRAND_IMAGES')}}/{{$SubManu->image}}"></a>
							@endif
						</div>
						<div class="text-sec">
							<h2 class="lending-hd"><a href="{{$SubManu->button_link}}"><span class="big-text">{{substr($SubManu->title,0,1)}}</span>{{substr($SubManu->title,1,strlen($SubManu->title))}}</a></h2>
							@if($SubManu->right_desciption != '')
								{!! stripcslashes($SubManu->right_desciption) !!}
							@endif
							<div class="shop-btn"><a href="{{$SubManu->button_link}}" class="btn btn-primary">Shop Now</a></div>
						</div>
					</div>
					@endif
				@endforeach	
			@endif	
        </div>  
		@if($BrandDetail[0]->video_show=='Yes')     
			@if($BrandDetail[0]->youtubelink1 != "")	
				<div class="vedio-sec common-specing">
					<iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink1}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</div>     
			@endif
			@if($BrandDetail[0]->youtubelink2 != "")	
				<div class="vedio-sec common-specing">
					<iframe width="100%" height="514" src="{{$BrandDetail[0]->youtubelink2}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</div>     
			@endif	
		@endif
        <!-- Content Part End Here -->
	</main>
@endsection