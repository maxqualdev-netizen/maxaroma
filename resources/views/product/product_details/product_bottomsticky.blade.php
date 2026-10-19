<div class="container" role="region" aria-label="Product Quick View">
    <a class="thumb" href="#" tabindex="0" aria-label="Product image: {{ remove_html_entities($productDetails->referencedName_main) }}">
        <img width="{{getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->thumb_image)}}" height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->thumb_image)}}" src="{{ config('global.SPEED_SIZE_URL')}}{{ $productDetails->thumb_image }}" alt="{{ remove_html_entities($productDetails->referencedName_main) }}">
    </a>
    <div class="info dtl_salestext" role="region" aria-labelledby="product-name">
        <div class="info_to">
            <div class="name" id="product-name">
                {{ remove_html_entities($productDetails->referencedName_main) }}
                <small class="vtype" aria-label="Variant type">{{$productDetails->vtype}}</small>
            </div>

            @if($productDetails->stock=='In')
                @if($productDetails->template == 'Maxaroma-Edit')
                <div class="d-flex align-items-center">
                @endif
                @if(count($dealofdayRS)>0)
                    <strong class="text-red" aria-label="Retail price, discounted"><strike>{{ Price($productDetails->retail_price) }}</strike></strong>
                    @if(isset($productDetails->dealprice))
                        <span class="pdetail_dealprice prodprice" data-flag="deal_price" aria-label="Deal price">DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong></span>
                        @php $proprice = $productDetails->dealprice; @endphp
                    @else
                        @php $proprice = $productDetails->retail_price; @endphp
                    @endif
                @else
                    @if($productDetails->product_price!='')
                        @if($productDetails->product_price != $productDetails->retail_price)
                            <strong class="text-red" aria-label="Retail price, discounted"><strike>{{ Price($productDetails->retail_price) }}</strike></strong>
                            <span class="pdetail_dealprice" aria-label="Sale price">SALE: </span>
                            <strong class="prodprice pdetail_dealprice" aria-label="Current price"> {{ Price($productDetails->product_price) }} </strong>
                        @else
                            <strong class="prodprice" aria-label="Current price">{{ Price($productDetails->product_price) }} </strong>
                        @endif
                        @php $proprice = $productDetails->product_price; @endphp
                    @endif
                @endif

                @if($productDetails->yousave!='' && $productDetails->yousave > 0 )
                    @if($productDetails->retail_price < 100 )
                        <span class="yousave-dtl" aria-label="You save {{$productDetails->yousave}} percent">(You Save {{$productDetails->yousave}}%)</span>
                    @else
                        <span class="yousave-dtl" aria-label="You save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}}">(You Save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})</span>
                    @endif
                @endif
                @if($productDetails->template == 'Maxaroma-Edit')
                </div>
                @endif
            @endif

            {{-- <span class="our_price text-red" role="region" aria-label="Product pricing information">
                @if($productDetails->stock=='In')
                    @if(count($dealofdayRS) > 0)
                        @if(count($dealofdayRS)>0)
                            <strong class="text-red" aria-label="Retail price, discounted">
                                <strike>{{ Price($productDetails->retail_price) }}</strike>
                            </strong>
                        @endif
                    @else
                        @if($productDetails->product_price!='')
                            @if($productDetails->product_price!=$productDetails->retail_price)
                                <strong class="text-red" aria-label="Retail price, discounted">
                                    <strike>{{ Price($productDetails->retail_price) }}</strike>
                                </strong>
                                <span aria-label="Sale price">SALE:</span>
                            @endif
                            <strong class="prodprice" aria-label="Current price">
                                {{ Price($productDetails->product_price) }}
                            </strong>
                            @if($productDetails->yousave!='0')
                                @if($productDetails->retail_price < 100 )
                                    <span class="yousave-dtl" aria-label="You save {{$productDetails->yousave}} percent">
                                        (You Save {{$productDetails->yousave}}%)
                                    </span>
                                @else
                                    <span class="yousave-dtl" aria-label="You save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}}">
                                        (You Save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})
                                    </span>
                                @endif
                            @endif
                            @php $proprice = $productDetails->product_price; @endphp
                        @endif
                    @endif
                @endif

                @if($productDetails->stock=='In')
                    @if(count($dealofdayRS)>0 && isset($productDetails->dealprice))
                        <span class="pdetail_dealprice prodprice" data-flag="deal_price" aria-label="Deal price">
                            DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong>
                        </span>
                        @if($productDetails->yousave >0 && $productDetails->yousave!='')
                            @if($productDetails->retail_price < 100 )
                                <span class="yousave-dtl" aria-label="You save {{ $productDetails->yousave }} percent">
                                    (You Save {{ $productDetails->yousave }}%)
                                </span>
                            @else
                                <span class="yousave-dtl" aria-label="You save {{ Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave)) }}">
                                    (You Save {{ Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})
                                </span>
                            @endif
                        @endif
                        @php $proprice = $productDetails->dealprice; @endphp
                    @endif
                @endif
            </span> --}}

            @if($productDetails->stock=='In')
                <div class="dtl_rbtn" role="group" aria-label="Purchase options">
                    @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
                        <div class="pt10">
                            <span class="text-red pt-2" aria-label="Retail only notice">* <b>Retail Only.</b></span>
                        </div>
                    @else
                        <div class="input-group qty-input sticky-qty">
                    <button type="button" class="left-qty btn-number" data-type="minus" data-field="prodqty">-</button>
                    <input type="text" name="prodqty" class="form-control input-number" id="prodqty-sticky" value="1" min="1" max="20">
                    <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
                </div>
                        <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart" data-type="sticky" role="button" aria-label="Add to cart">Add to cart</a>
                    @endif
                </div>
            @else
                <div class="dtl_rbtn">
                    <span class="text-red outstock-text" style="color: red!important;" aria-label="Out of stock">Out Of Stock</span>
                </div>
            @endif
        </div>
        <div class="info_bo" role="tablist" aria-label="Product details navigation">
            <a href="javascript:void(0)" class="detailtab" data-id="tab-details" role="tab" aria-selected="false" aria-controls="tab-details">Product Details</a>
            {{-- <a href="javascript:void(0)" onclick="changetab('Read')" aria-label="Read product reviews ({{ count($arr_product_review) }})" role="tab" aria-selected="false" aria-controls="tab-reviews" id="tab-reviews-trigger" >Reviews ({{ count($arr_product_review) }})</a> --}}
            <a class="detailtab" tabindex="0" aria-label="Product reviews">
                <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}"></div>
            </a>
            <a href="javascript:void(0)" class="detailtab" data-id="tab-shippinginfo" role="tab" aria-selected="false" aria-controls="tab-shippinginfo">Shipping Information</a>
            <a href="javascript:void(0)" class="detailtab" data-id="tab-returnexch" role="tab" aria-selected="false" aria-controls="tab-returnexch">Return & Exchange</a>
            @if ($productDetails->maxtwodaydelivery == 'Yes')
                <strong class="free_sipping" aria-label="Free two day shipping">FREE 2DAY SHIPPING</strong>
            @else
                <strong class="free_sipping" aria-label="Free standard shipping">Free shipping on orders over $75</strong>
            @endif
        </div>
    </div>
</div>