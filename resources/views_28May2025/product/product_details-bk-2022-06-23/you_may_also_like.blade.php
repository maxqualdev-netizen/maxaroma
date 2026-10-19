@if(count($productDetails->related_item) > 0)
<section class="container" id="SortRelatedProducts">
    <div class="dtl_gallery">
        <div class="hd">You May Also Like</div>
        <div class="dtl_gallery_slider slick-dotted">
            @foreach ($productDetails->related_item as $key => $Product)
            <li>
                <x-productbox :prodData="$Product" />
            </li>
            @endforeach
        </div>
    </div>
</section>    
@endif