<div class="container" role="region" aria-label="Product Reviews Section">
    <div class="text-center pb-4">
        <div class="main-heading">
            <h3 id="yotpo-reviews-title"><span>What people are saying</span></h3>
        </div>
    </div>            
    <div class="yotpo yotpo-main-widget"
        data-product-id="{{ $productDetails->products_id }}"
        @if(isset($proprice))
            data-price="{{$proprice}}"
        @else
            data-price="0"
        @endif
        data-currency="USD"
        data-count="5"
        data-name="{{ $productDetails->product_name }}"
        data-url="{{$CanonicalURL}}"
        data-image-url="{{ $productDetails->mainImage }}"
        role="region"
        aria-labelledby="yotpo-reviews-title">
    </div>
</div>