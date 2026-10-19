<div class="container">
<a class="thumb"><img width="{{getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}"  height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}"  src="{{ $productDetails->mainImage }}" alt=""></a>
    <div class="info dtl_salestext">
        <div class="info_to">
            <div class="name">
                {{ remove_html_entities($productDetails->referencedName_main) }} 
                <small class="vtype">{{$productDetails->vtype}}</small>
            </div>
            
            <!-- <span class="our_price">Our Price <strong>$102.36</strong></span> <span class="save_text">You Sav: 36% ($56.64)</span> -->
            @if($productDetails->stock=='In')
                @if(count($dealofdayRS)>0)
                    <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong>
                    @if(isset($productDetails->dealprice))
                        <span class="pdetail_dealprice prodprice" data-flag="deal_price">DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong></span>
                        @php $proprice = $productDetails->dealprice; @endphp
                    @else
                        @php $proprice = $productDetails->retail_price; @endphp
                    @endif
                @else 
                    @if($productDetails->product_price!='')
                        @if($productDetails->product_price != $productDetails->retail_price)
                            <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong> 
                            <span class="pdetail_dealprice">SALE: </span> <strong class="prodprice pdetail_dealprice"> {{ Price($productDetails->product_price) }} </strong> 
                        @else
                            <strong class="prodprice">{{ Price($productDetails->product_price) }} </strong>
                        @endif
                        @php $proprice = $productDetails->product_price; @endphp
                    @endif
                @endif

                @if($productDetails->yousave!='' && $productDetails->yousave > 0 )
                    @if($productDetails->retail_price < 100 )
                        <span class="yousave-dtl">(You Save {{$productDetails->yousave}}%)</span>
                    @else
                        <span class="yousave-dtl">(You Save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})</span>
                    @endif
                @endif
            @endif
            
            {{--
            <span class="our_price text-red">
            @if($productDetails->stock=='In')
                @if(count($dealofdayRS) > 0)
                    @if(count($dealofdayRS)>0)
                    <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong>
                    @endif
                @else
                    @if($productDetails->product_price!='')
                        @if($productDetails->product_price!=$productDetails->retail_price)
                        <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong> SALE: 
                        @endif
                        <strong class="prodprice">{{ Price($productDetails->product_price) }} </strong>
                        @if($productDetails->yousave!='0')
                            @if($productDetails->retail_price < 100 ) 
                                <span class="yousave-dtl">(You Save {{$productDetails->yousave}}%)</span>
                            @else
                                <span class="yousave-dtl">(You Save {{Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})</span>
                            @endif
                        @endif
                        @php $proprice = $productDetails->product_price; @endphp
                    @endif
                @endif
            @endif
       
            @if($productDetails->stock=='In')
                @if(count($dealofdayRS)>0 && isset($productDetails->dealprice))
                <span class="pdetail_dealprice prodprice" data-flag="deal_price">DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong></span></span>

                    @if($productDetails->yousave >0 && $productDetails->yousave!='')
                        @if($productDetails->retail_price < 100 )
                            <span class="yousave-dtl">(You Save {{ $productDetails->yousave }}%)</span>
                        @else
                            <span class="yousave-dtl">(You Save {{ Price(GetYouSaveAmount($productDetails->retail_price,$productDetails->yousave))}})</span>
                        @endif
                    @endif

                @php $proprice = $productDetails->dealprice; @endphp
                @endif
            @endif
        </span>
        --}}
        @if($productDetails->stock=='In')
            <div class="dtl_rbtn">
                 @if (Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler' && $productDetails->product_type == 'retailer')
                <div class="pt10"><span class="text-red pt-2">* <b>Retail Only.</b></span></div>
				@else
                <div class="input-group qty-input sticky-qty">
                    <button type="button" class="left-qty btn-number" disabled="disabled" data-type="minus" data-field="prodqty">-</button>
                    <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1" max="10">
                    <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
                </div>
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart" data-type="sticky">Add to cart</a>
                @endif
            </div>
        @else
        <div class="dtl_rbtn">
            <span class="text-red outstock-text" style="color: red!important;">Out Of Stock</span>
        </div>
        @endif
        </div>
        <div class="info_bo">
            <a href="javascript:void(0)" class="detailtab" data-id="tab-details" >Product Details</a>
            {{-- <a href="javascript:void(0)" onclick="changetab('Read')">Reviews ({{ count($arr_product_review) }})</a> --}}
            <a class="detailtab"><div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}"></div></a>
            <a href="javascript:void(0)" class="detailtab" data-id="tab-shippinginfo">Shipping Information</a>
            <a href="javascript:void(0)" class="detailtab" data-id="tab-returnexch">Return & Exchange</a>
            @if ($productDetails->maxtwodaydelivery == 'Yes')
                <strong class="free_sipping">FREE 2DAY SHIPPING</strong>
            @else
                <strong class="free_sipping">FREE STANDARD SHIPPING</strong>
            @endif
        </div>
    </div>
</div>
