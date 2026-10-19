<section class="home-product pt-3 pt-lg-5 slider-arrow-space" @if(isset($attr['Page']) && $attr['Page'] == 'Landing') style="padding:0px !important;" @endif role="region" @if(isset($attr['Title'])) aria-labelledby="slider-heading-{{ \Illuminate\Support\Str::slug($attr['Title']) }}" @else aria-label="Product Slider" @endif>
    <div class="container">
        @if(!isset($attr['Page']))
        <div class="pt-3 text-center pb-4">
            <div class="main-heading">
                <h2 class="sliderHeading" id="slider-heading-{{ \Illuminate\Support\Str::slug($attr['Title']) }}"><span>{{$attr['Title']}}</span></h2>
                <a href="{{$attr['SeeMore']}}" class="viewall" aria-label="See more products in {{$attr['Title']}}">See More</a>
            </div>
        </div>
        @endif
        <div class="cover-spin" role="status" aria-live="polite"></div>
        <div class="{{$attr['Slider']}}" aria-label="Product List">
          <!--  @if($type == 'NewArrival' && config('typevalofcriteo') == "d")
            <div aria-label="Fresh Finds This Week">
                <div class="slinner-box">
                    <div class="slinner-boxtext">
                        <h5>{{$attr['TotalProd']}}</h5>
                        <p>Fresh Finds This Week</p>
                        <a href="{{url($sliderLink)}}" class="btn btn-primary d-block" aria-label="Shop all new arrivals">Shop Now</a>
                    </div>
                </div>
            </div>
            @endif -->
            @foreach($sliderData as $key => $Product)
                <x-productbox :prodData="$Product" :ariaPosInSet="$key+1" :ariaSetSize="count($sliderData)" />
            @endforeach
            @if($type == 'NewArrival' && config('typevalofcriteo') == "m")
                <div aria-label="Fresh Finds This Week">
                    <div class="slinner-box">
                        <div class="slinner-boxtext">
                            <h5>{{$attr['TotalProd']}}</h5>
                            <p>Fresh Finds This Week</p>
                            <a href="{{url($sliderLink)}}" class="btn btn-primary d-block" aria-label="Shop all new arrivals">Shop Now</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
