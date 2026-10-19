<section class="home-product pt-3 pt-lg-5 pb-lg-4 pb-3 pb-sm-3 slider-arrow-space" @if(isset($attr['Page']) && $attr['Page'] == 'Landing') style="padding:0px !important;" @endif >
	<div class="container">
		@if(!isset($attr['Page']))
		<div class="pt-3 text-center pb-4">
			<div class="main-heading">
				<h3>{{$attr['Title']}}</h3>
				<a href="{{$attr['SeeMore']}}" class="viewall">See More</a> 
			</div>
		</div>
		@endif
		<div class="pb-4 pb-sm-4 pb-lg-5">
			<div class="cover-spin"></div>
			<div class="{{$attr['Slider']}}">
				@foreach($sliderData as $key => $Product)
					<x-productbox :prodData="$Product" sliderClass="slick-slide" />
				@endforeach
			</div>
		</div>
    </div>
</section>