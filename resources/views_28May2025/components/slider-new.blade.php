<section class="home-product pt-3 pt-lg-5 slider-arrow-space" @if(isset($attr['Page']) && $attr['Page'] == 'Landing') style="padding:0px !important;" @endif >
	<div class="container">
		@if(!isset($attr['Page']))
		<div class="pt-3 text-center pb-4">
			<div class="main-heading">
				<h3><span>{{$attr['Title']}}</span></h3>
				<a href="{{$attr['SeeMore']}}" class="viewall">See More</a> 
			</div>
		</div>
		@endif
		<div class="cover-spin"></div>
		<div class="{{$attr['Slider']}}">
            @if($type == 'NewArrival' && config('typevalofcriteo') == "d")    
            <div class="slick-slide">
				<div class="slinner-box">
					<div class="slinner-boxtext">
						<h5>{{$attr['TotalProd']}}</h5>
						<p>Fresh Finds This Week</p>
						<a href="{{url($sliderLink)}}" class="btn btn-primary d-block">Shop Now</a>
					</div>
				</div>
			</div>
            @endif
			@foreach($sliderData as $key => $Product)
				<x-productbox :prodData="$Product" sliderClass="slick-slide" />
			@endforeach
            @if($type == 'NewArrival' && config('typevalofcriteo') == "m")    
                <div class="slick-slide">
					<div class="slinner-box">
						<div class="slinner-boxtext">
							<h5>{{$attr['TotalProd']}}</h5>
							<p>Fresh Finds This Week</p>
							<a href="{{url($sliderLink)}}" class="btn btn-primary d-block">Shop Now</a>
						</div>
					</div>
				</div>
            @endif
		</div>
    </div>
</section>
