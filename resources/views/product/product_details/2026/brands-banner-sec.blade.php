<div class="container">
    <div class="brands-content">
        <div class="brands-text text-lg-right">
            <h2 class="brands-heading m-f32" id="brands-heading"><span>The Brand</span> {{ $productDetails->vmanufacture }}</h2>
            <p class="brands-description">{!! $productDetails->brand_history !!}</p>
            <div id="dtl_tab">
                <ul class="resp-tabs-list">
                    <li id="tab-details"><span>Details</span></li>
                    <li id="tab-shippinginfo"><span>Shipping Information</span></li>
                    <li id="tab-returnexch"><span>Return & Exchange</span></li>
                </ul>
                <div class="resp-tabs-container static-inner">
                    <div class="prdetail-tab" id="prodetail">
                        @include('product.product_details.product_detail')
                    </div>
                    <div class="prship-tab">
                        {!! $Shipping_info_Content !!}
                    </div>
                    <div class="prreturn-tab">{!! $Return_info_Content !!}</div>
                </div>
            </div>
        </div>
        @if(!empty($productDetails->brand_banner))
        <div class="brands-image-wrapper">
            <img src="{{$productDetails->brand_banner}}" alt="Les Bains Paris brand heritage and signature fragrance showcase" class="brands-image" loading="lazy" decoding="async" width="860" height="720">
        </div>
        @endif
    </div>
</div>