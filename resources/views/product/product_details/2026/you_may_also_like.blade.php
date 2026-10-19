@if(count($productDetails->related_item) > 0)
<section class="container" id="SortRelatedProducts" role="region" aria-label="You May Also Like Section">
    <div class="dtl_gallery">
        <div class="hd" id="you-may-also-like-title">You May Also Like</div>
        <div class="dtl_gallery_slider slick-dotted">
            <ul role="list" aria-labelledby="you-may-also-like-title">
                @foreach ($productDetails->related_item as $key => $Product)
                <li role="listitem" aria-label="Related Product {{ $loop->iteration }}">
                    <x-productbox :prodData="$Product" />
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>    
@endif