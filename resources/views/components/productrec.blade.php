<div class="product-youmay" role="region" aria-label="Recommended Product">
    <div class="product-yousec product" role="group" aria-label="Product {{ $prodData->product_name }}">
        <div class="thumbh">
            <a href="{{$prodData->product_url}}" title="{{$prodData->product_name}}" onclick="GAEventVal('{{$prodData->sku}}','{{$prodData->product_name}}','{{$prodData->vmanufacture}}');" tabindex="{{$prodData->sku}}" aria-label="View details for {{ $prodData->product_name }}">
                <img src="{{ config('global.SPEED_SIZE_URL')}}{{$prodData->prod_image}}" alt="{{$prodData->product_name}}" width="70" height="70" />
            </a>
        </div>
        <div class="product-info">
            <div class="product-name">
                <div class="prd-namesec">
                    <a href="{{$prodData->product_url}}" tabindex="0" aria-label="View details for {{ $prodData->product_name }}">
                        <strong>{{$prodData->product_name}}</strong>
                    </a>
                    <div>
                        {!! stripslashes($prodData->referencedName) !!}
                        @if($prodData->vtype != '' && isset($prodData->size_cnt) && $prodData->size_cnt > 1)
                            {{substr($prodData->vtype,0,25)}}
                        @else
                            {{ \Illuminate\Support\Str::limit($prodData->short_description, 21, $end='...') }}
                        @endif
                    </div>
                </div>
                <a href="javascript:void(0);" class="svg-h" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('{{$prodData->products_id}}','yes');" tabindex="0" role="button" aria-label="Add {{ $prodData->product_name }} to wishlist">
                    <svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                        <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
                    </svg>
                    <svg class="sv-heart-fill sv-fill" aria-hidden="true" role="img" width="14" height="14">
                        <use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
                    </svg>
                </a>
            </div>
            <div class="product-price pt-1 text-left" aria-label="Product price">
                <div class="price">
                    <!-- $35 - $160 -->
                    @if($prodData->minPrice != $prodData->maxPrice)
                        @if($prodData->size_cnt < 1 || $prodData->size_cnt == 1)
                            @if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
                                {{Price($prodData->deal_price)}}
                                <strike>{{Price($prodData->retail_price)}}</strike>
                            @else
                                @if(isset($prodData->deal_price) && $prodData->deal_price > 0)
                                    {{Price($prodData->deal_price)}}
                                    @if($prodData->minPrice == $prodData->maxPrice)
                                        <strike>{{Price($prodData->retail_price)}}</strike>
                                    @else
                                        {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                    @endif
                                @else
                                    @if($prodData->minPrice == $prodData->maxPrice)
                                        {{Price($prodData->minPrice)}}
                                    @else
                                        {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
                            <a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0" aria-label="Niche Fragrances"> <span class="avl-round"></span></a>
                        @endif
                        @if($prodData->stock == 'In')
                            @if($prodData->size_cnt > 1)
                                @if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
                                    {{Price($prodData->deal_price)}}
                                    <strike>{{Price($prodData->retail_price)}}</strike>
                                @else
                                    @if($prodData->minPrice != $prodData->maxPrice)
                                        {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if($prodData->category_id == 2)
                            @if($prodData->size_cnt > 1)
                                <div class="opstion-avl" aria-label="Options available">{{$prodData->size_cnt}} Options Available</div>
                                <a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0" aria-label="Niche Fragrances"> <span class="avl-round"></span></a>
                            @endif
                        @else
                            @if($prodData->size_cnt > 1)
                                <div class="opstion-avl" aria-label="Options available">{{$prodData->size_cnt}} Options Available</div>
                            @endif
                        @endif
                        @if($prodData->yousave != '' && $prodData->yousave != 0 && $prodData->stock == 'In' && $prodData->size_cnt <= 1)
                            <div class="opstion-avl" aria-label="You save {{$prodData->yousave}} percent">You Save: {{$prodData->yousave}}%
                                @if(isset($prodData->BrandInSearch) && $prodData->BrandInSearch == 1)
                                    Off
                                @endif
                            </div>
                        @endif
                    @else
                        @if($prodData->stock == 'In')
                            @if($prodData->size_cnt < 1 || $prodData->size_cnt == 1)
                                @if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
                                    {{Price($prodData->deal_price)}}
                                    <strike>{{Price($prodData->retail_price)}}</strike>
                                @else
                                    @if(isset($prodData->deal_price) && $prodData->deal_price > 0)
                                        {{Price($prodData->deal_price)}}
                                        @if($prodData->minPrice == $prodData->maxPrice)
                                            <strike>{{Price($prodData->retail_price)}}</strike>
                                        @else
                                            {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                        @endif
                                    @else
                                        @if($prodData->minPrice == $prodData->maxPrice)
                                            {{Price($prodData->minPrice)}}
                                        @else
                                            {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                        @endif
                                    @endif
                                @endif
                            @endif
                            @if($prodData->category_id == 2 && $prodData->size_cnt <= 1)
                                <a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0" aria-label="Niche Fragrances"> <span class="avl-round"></span></a>
                            @endif
                        @endif
                        @if($prodData->stock == 'In')
                            @if($prodData->size_cnt > 1)
                                @if(isset($page) && $page == 'dealofweek' && isset($prodData->deal_price))
                                    {{Price($prodData->deal_price)}}
                                    <strike>{{Price($prodData->retail_price)}}</strike>
                                @else
                                    @if($prodData->minPrice != $prodData->maxPrice)
                                        {{Price($prodData->minPrice).' - '.Price($prodData->maxPrice)}}
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if($prodData->category_id == 2)
                            @if($prodData->size_cnt > 1)
                                <div class="opstion-avl" aria-label="Options available">{{$prodData->size_cnt}} Options Available</div>
                                <a href="javascript:void(0);" onclick="NicheFragrances()" title="NicheFragrances" tabindex="0" aria-label="Niche Fragrances"> <span class="avl-round"></span></a>
                            @endif
                        @else
                            @if($prodData->size_cnt > 1)
                                <div class="opstion-avl" aria-label="Options available">{{$prodData->size_cnt}} Options Available</div>
                            @endif
                        @endif
                        @if($prodData->yousave != 0 && $prodData->stock == 'In')
                            <div class="opstion-avl" aria-label="You save {{$prodData->yousave}} percent">You Save: {{$prodData->yousave}}%
                                @if(isset($prodData->BrandInSearch) && $prodData->BrandInSearch == 1)
                                    Off
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                @if($prodData->stock == 'In')
                    <a href="javascript:void(0);" title="Add to cart" data-product="{{$prodData->products_id}}" class="btn btn-primary d-block prodaddcart" role="button" aria-label="Add {{ $prodData->product_name }} to cart">Add</a>
                @endif
                @if($prodData->stock == 'Out')
                    <a href="javascript:void(0);" onclick="DisplayPopupBoxAlertMe('{{$prodData->products_id}}','{{$prodData->sku}}');" class="btn btn-primary lable-quickview" data-pid="{{$prodData->products_id}}" rel="nofollow" role="button" aria-label="Notify me when {{ $prodData->product_name }} is in stock">Notify Me</a>
                @endif
            </div>
        </div>
    </div>
</div>