<section class="home-product pt-3 pt-lg-5 slider-arrow-space" 
    @if(isset($attr['Page']) && $attr['Page'] == 'Landing') style="padding:0px !important;" @endif
    role="region" 
    aria-labelledby="slider-ss-title"
>
    <div class="container">
        @if(!isset($attr['Page']))
        <div class="pt-3 text-center pb-4">
            <div class="main-heading">
                <h3 id="slider-ss-title"><span>{{$attr['Title']}}</span></h3>
                <a href="{{$attr['SeeMore']}}" class="viewall" aria-label="See more {{ $attr['Title'] }}">See More</a> 
            </div>
        </div>
        @endif
        <div class="cover-spin"></div>
        <div class="{{$attr['Slider']}}" aria-label="{{ $attr['Title'] }} products">
            @if($type == 'NewArrival' && config('typevalofcriteo') == "d")    
            <div aria-label="Fresh Finds This Week">
                <div class="slinner-box">
                    <div class="slinner-boxtext">
                        <h5>{{$attr['TotalProd']}}</h5>
                        <p>Fresh Finds This Week{{$ver}}</p>
                        <a href="{{url($sliderLink)}}" class="btn btn-primary d-block" aria-label="Shop Fresh Finds This Week">Shop Now</a>
                    </div>
                </div>
            </div>
            @endif
            @foreach($sliderData as $key => $Product)
                <x-productboxss :prodData="$Product" verVal="$ver" aria-label="{{ $Product['product_name'] ?? 'Product' }}" />
            @endforeach
            @if($type == 'NewArrival' && config('typevalofcriteo') == "m")    
                <div aria-label="Fresh Finds This Week">
                    <div class="slinner-box">
                        <div class="slinner-boxtext">
                            <h5>{{$attr['TotalProd']}}</h5>
                            <p>Fresh Finds This Week</p>
                            <a href="{{url($sliderLink)}}" class="btn btn-primary d-block" aria-label="Shop Fresh Finds This Week">Shop Now</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>