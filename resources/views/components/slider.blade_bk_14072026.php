<section class="home-product pt-3 pt-lg-5 pb-lg-4 pb-3 pb-sm-3 slider-arrow-space" @if(isset($attr['Page']) && $attr['Page'] == 'Landing') style="padding:0px !important;" @endif role="region" @if(isset($attr['Title'])) aria-labelledby="slider-heading-{{ \Illuminate\Support\Str::slug($attr['Title']) }}" @else aria-label="Product Slider" @endif>
    <div class="container">
        @if(!isset($attr['Page']))
        <div class="pt-3 text-center pb-4">
            <div class="main-heading">
                <h2 class="sliderHeading" id="slider-heading-{{ \Illuminate\Support\Str::slug($attr['Title']) }}">{{$attr['Title']}}</h2>
                <a href="{{$attr['SeeMore']}}" class="viewall" aria-label="See more products in {{$attr['Title']}}">See More</a> 
            </div>
        </div>
        @endif
        <div class="pb-4 pb-sm-4 pb-lg-5">
            <div class="cover-spin" role="status" aria-live="polite"></div>
            <div class="{{$attr['Slider']}}" aria-label="Product List">
                @foreach($sliderData as $key => $Product)
                    <x-productbox :prodData="$Product" :ariaPosInSet="$key+1" :ariaSetSize="count($sliderData)" />
                @endforeach
            </div>
        </div>
    </div>
</section>