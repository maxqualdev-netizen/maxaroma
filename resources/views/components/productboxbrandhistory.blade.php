<div class="product" role="group" aria-label="Product {{ $ProductDetails->referencedName ?? '' }}">
    <a href="{{ $ProductDetails->product_url }}" class="p-thumb" aria-label="View details for {{ $ProductDetails->referencedName ?? '' }}">
        <img src="{{config('global.SITE_IMAGES')}}listing_pd.jpg" alt="{{ $ProductDetails->referencedName ?? 'Product image' }}">
        @if($ProductDetails->WebsiteStock =='In' && $ProductDetails->maxtwodaydelivery == "Yes")
        <span class="cornar">
            <img src="{{config('global.SITE_IMAGES')}}2day.png" alt="2 Day Delivery Available" />
        </span>
        @endif
        @if($ProductDetails->is_gift_wrap == 'Yes')
        <span class="cornar">   
            <svg class="sv-giftwrap vam" aria-hidden="true" role="img" width="60" height="60">
                <use href="#sv-giftwrap" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-giftwrap"></use>
            </svg>
        </span> 
        @endif  
        @if(isset($ProductDetails->deal_price) && $ProductDetails->deal_price > 0)
        <svg id="sv-topdeal" viewBox="0 0 130 28" aria-label="Top Deal">
            <defs><style>.cls-1{fill:#ed2224;}.cls-2{font-size:16px;fill:#fff;font-family:Montserrat-Medium, Montserrat;font-weight:500;letter-spacing:0.02em;}.cls-3{letter-spacing:0.02em;}.cls-4{letter-spacing:0.02em;}</style></defs><polygon class="cls-1" points="116 28 0 28 0 0 130 0 116 28"/><text class="cls-2" transform="translate(11.88 20.17)">T<tspan class="cls-3" x="9.63" y="0">OP DEA</tspan><tspan class="cls-4" x="76.98" y="0">L</tspan><tspan class="cls-3" x="86.77" y="0">S</tspan></text>
        </svg>
        @endif
        @if(isset($ProductDetails->sale_item) && $ProductDetails->sale_item == 1)
        <svg class="sv-sale" aria-hidden="true" role="img" width="100" height="20" aria-label="Sale">
            <use href="#sv-sale" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-sale"></use>
        </svg>
        @endif
    </a>
    @if($ProductDetails->WebsiteStock == 'In')
    <a href="javascript:void(0);" class="productquickview lable-quickview" data-pid="14160" rel="nofollow" role="button" aria-label="Quick View {{ $ProductDetails->referencedName ?? '' }}">Quick View</a>
    @endif
    @if($ProductDetails->WebsiteStock == 'Out')
    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$ProductDetails->products_id}}','{{$ProductDetails->sku}}');" class="lable-quickview" data-pid="14160" rel="nofollow" role="button" aria-label="Notify me when {{ $ProductDetails->referencedName ?? '' }} is in stock">Notify Me</a>
    @endif  
    <ul class="product_icon" role="list" aria-label="Product actions">
        <li role="listitem">
            <a href="#" aria-label="For Men">
                <svg class="sv-boy" aria-hidden="true" role="img" width="8" height="14">
                    <use href="#sv-boy" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-boy"></use>
                </svg>
            </a>
        </li>
        <li role="listitem">
            <a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$ProductDetails->products_id}}','yes');" aria-label="Add {{ $ProductDetails->referencedName ?? '' }} to wishlist" role="button">
                <svg class="sv-heart" aria-hidden="true" role="img" width="14" height="13">
                    <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
                </svg>
            </a>
        </li>
        @if($ProductDetails->WebsiteStock == 'Out')
        <li role="listitem">
            <a class="label-cart-outstock" href="javascript:void(0);" rel="nofollow" onclick="DisplayPopupBoxAlertMe('{{$ProductDetails->products_id}}','{{$ProductDetails->sku}}');" aria-label="Out of stock: Notify me for {{ $ProductDetails->referencedName ?? '' }}" role="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>
        </li>           
        @endif
   </ul>
    <div class="product-name" aria-label="Product name">
        {!! $ProductDetails->referencedName !!}
        @if($ProductDetails->vtype != '' && $ProductDetails->size_cnt > 1)
            {{substr($ProductDetails->vtype,0,25)}}
        @else
            {{substr($ProductDetails->short_description,0,25)}}   
        @endif
    </div>
    @if($ProductDetails->minPrice != $ProductDetails->maxPrice)
    <div class="product-price pt-1" aria-label="Product price">
        @if($ProductDetails->size_cnt < 1 || $ProductDetails->size_cnt == 1)
            @if(isset($ProductDetails->deal_price) && $ProductDetails->deal_price !='' && $ProductDetails->deal_price !='0') 
                <div class="price pb-1">Deal Price : ${{$ProductDetails->deal_price}}</div>
            @endif  
            @if($ProductDetails->minPrice == $ProductDetails->maxPrice)
                @if(isset($ProductDetails->deal_price) && $ProductDetails->deal_price !='' && $ProductDetails->deal_price !='0') 
                    <div class="price pb-1">Deal Price : <strike>${{$ProductDetails->deal_price}}</strike></div>
                @else
                    <div class="price pb-1">${{$ProductDetails->minPrice}}</div>
                @endif
            @else
                <div class="price pb-1">{{'$'.$ProductDetails->minPrice.' - $'.$ProductDetails->maxPrice}}</div>   
            @endif
        @endif
    </div>
    @else
        @if($ProductDetails->stock == 'In')
        <div class="product-price pt-1" aria-label="Product price">
            @if($ProductDetails->size_cnt < 1 || $ProductDetails->size_cnt == 1)
                @if(isset($ProductDetails->deal_price) && $ProductDetails->deal_price !='' && $ProductDetails->deal_price !='0') 
                    <div class="price pb-1">Deal Price : ${{$ProductDetails->deal_price}}</div>
                @endif
            @endif
            @if($ProductDetails->minPrice == $ProductDetails->maxPrice)
                @if(isset($ProductDetails->deal_price) && $ProductDetails->deal_price !='' && $ProductDetails->deal_price !='0') 
                    <div class="price pb-1"><strike>${{$ProductDetails->retail_price}}</strike></div>
                @else
                    <div class="price pb-1">${{$ProductDetails->minPrice}}</div>
                @endif
            @else
                <div class="price pb-1">{{'$'.$ProductDetails->minPrice.' - $'.$ProductDetails->maxPrice}}</div>   
            @endif  
        </div>
        @endif
    @endif  
    @if($ProductDetails->yousave != 0 && $ProductDetails->stock == 'In')
        <div class="you-save pb-2" aria-label="You save {{$ProductDetails->yousave}} percent">You Save: {{$ProductDetails->yousave}}%</div>
    @endif  
   <div class="rating pb-2 d-flex align-items-center justify-content-center" aria-label="Product rating"><span class="d-inline-block">(38)</span> <span class="star"></span><span class="star active"></span><span class="star active"></span><span class="star active"></span><span class="star active"></span></div>
    @if($ProductDetails->size_cnt > 1)
        <div class="opstion-avl" aria-label="Options available">{{$ProductDetails->size_cnt}} Options Available <span class="avl-round"></span></div>
    @endif
</div>