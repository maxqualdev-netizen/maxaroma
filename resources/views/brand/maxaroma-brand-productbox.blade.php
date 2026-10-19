<div class="brand-page-list">
    <div class="product product-brand"  productname="{{$Product->product_name}}" brand="{{$Product->vmanufacture}}"  aria-label="{{$Product->vmanufacture}}">
        <div class="prdbox-image">
            <div class="product-image-wrapper {{ !empty($Product->second_image) ? 'has-hover-image' : '' }}">
                <a href="{{$Product->product_url}}" class="p-thumb" aria-label="View product {{$Product->vmanufacture}}">
                    <img src="{{ config('global.SPEED_SIZE_URL')}}{{$Product->prod_image}}" class="img1" loading="lazy" alt="{{$Product->product_name}}" width="240" height="240">
                    @if(!empty($Product->second_image))
                        <img class="img2" src="{{ $Product->second_image }}"  alt="{{ $Product->product_name }}" >
                    @endif
                </a>
                <a href="javascript:void(0);" onclick="productquickview('{{$Product->products_id}}','2')" class="lable-quickview" rel="nofollow" aria-label="Quick View for {{$Product->product_name}}" tabindex="0">Quick View</a>
            </div>
            <ul class="product_icon" role="list" aria-label="Product Actions">
                <li role="listitem">
                    <a href="javascript:void(0);" class="svg-h" title="Add to wishlist" onclick="DisplayPopupBoxWishlist('36086','yes');" aria-label="Add House of Dastan Light Breeze to wishlist">
                        <svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
                            <use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
                        </svg>
                        <svg class="sv-heart-fill sv-fill" aria-hidden="true" role="img" width="14" height="14">
                            <use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
                        </svg>
                    </a>
                </li>
                        <li role="listitem">
                    <a href="javascript:void(0);" title="Add to cart" class="prodaddcart" data-product="36086" aria-label="Add House of Dastan Light Breeze to cart">
                        <svg class="sv-cartnw vam" aria-hidden="true" role="img" width="15" height="15">
                            <use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <div class="product-name">
            <a href="{{ $Product->vmanufacture_link }}" tabindex="0"><strong>{{$Product->vmanufacture}}</strong></a>
            <a href="{{ $Product->product_url }}" class="prd-nametext">{{remove_html_entities(strip_tags($Product->brand_name))}}</a>
        </div>
        <div class="fragrance-notes">
        @if(isset($Product->fragrance_family) && !empty($Product->fragrance_family))

            @php
                $FragranceFamily = explode(',', $Product->fragrance_family);
            @endphp
            {{--<a href="#" class="fragrance-note">Iris</a>
            <a href="#" class="fragrance-note">Violet</a>--}}
            @for($i=0;$i<3;$i++)
                @if(isset($FragranceFamily[$i]))
                    <!-- <span class="fragrance-note">{{ ucwords(trim($FragranceFamily[$i])) }}</span> -->
                     <a href="{{ url('p4u/ftype-'.trim(strtolower($FragranceFamily[$i]))).'/view' }}" target="_blank" class="fragrance-note">{{ ucwords(trim($FragranceFamily[$i])) }}</a>
                @endif
            @endfor
        @endif
        </div>
        <div class="minheightprodbox">
            <div class="product-price">
                <div class="price pb-1 opstion-avl">
                </div>
            </div>
            <div class="product-price">
                <div class="price">
                    @if($Product->minPrice != $Product->maxPrice)
                        @if($Product->size_cnt < 1 || $Product->size_cnt == 1)
                            @if(isset($page) && $page == 'dealofweek' && isset($Product->deal_price))
                                {{Price($Product->deal_price)}}
                                <strike>{{Price($Product->retail_price)}}</strike>
                            @else
                                @if(isset($Product->deal_price) && $Product->deal_price > 0)
                                    {{Price($Product->deal_price)}}
                                    @if($Product->minPrice == $Product->maxPrice)
                                        <strike>{{Price($Product->retail_price)}}</strike>
                                    @else
                                        {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                                    @endif
                                @else
                                    @if($Product->minPrice == $Product->maxPrice)
                                        {{Price($Product->minPrice)}}
                                    @else
                                        {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                                    @endif
                                @endif
                            @endif
                        @endif

                        @if($Product->size_cnt > 1)
                            @if($Product->minPrice != $Product->maxPrice)
                                {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                            @endif
                        @endif
                    @else
                        @if($Product->size_cnt < 1 || $Product->size_cnt == 1)
                            @if(isset($page) && $page == 'dealofweek' && isset($Product->deal_price))
                                {{Price($Product->deal_price)}}
                                <strike>{{Price($Product->retail_price)}}</strike>
                            @else
                                @if(isset($Product->deal_price) && $Product->deal_price > 0)
                                    {{Price($Product->deal_price)}}bb
                                    @if($Product->minPrice == $Product->maxPrice)
                                        <strike>{{Price($Product->retail_price)}}</strike>
                                    @else
                                        {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                                    @endif
                                @else
                                    @if($Product->minPrice == $Product->maxPrice)
                                        {{Price($Product->minPrice)}}
                                    @else
                                        {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if($Product->size_cnt > 1)
                            @if($Product->minPrice != $Product->Product)
                                {{Price($Product->minPrice).' - '.Price($Product->maxPrice)}}
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
       <!--  <div class="product-action pt-4">
            @if($Product->stock == 'In')
                <button type="button" class="btn btn-secondary prodaddcart" aria-label="Add {{ $Product->product_name }} to cart" title="Add to cart" data-product="{{$Product->products_id}}">Add to cart</button>
            @else
                <a href="javascript:void(0);" class="btn btn-secondary" onclick="DisplayPopupBoxAlertMe('{{$Product->products_id}}','{{$Product->sku}}');"  data-pid="{{$Product->products_id}}" rel="nofollow">
                    Notify Me
                </a>
            @endif
        </div> -->
    </div>
</div>