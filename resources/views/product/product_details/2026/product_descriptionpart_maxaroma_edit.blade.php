<div class="dtlbrand-pricesize-row">
    <div class="dtlbrand-pricesize-left">
        <div class="dtl-brandname-text-row">
            <div class="dtl-brandname-text">
                <div class="dtl_addrview">
                    <div class="yotpo bottomLine" data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8" data-domain="<?=config('global.BASE_URL')?>" data-product-id="{{ $productDetails->products_id }}" data-product-models="{{ $productDetails->products_id }}" data-name="{{ $productDetails->product_name }}" data-url="{{$CanonicalURL}}" data-image-url="{{ $productDetails->mainImage}}" data-description="{{ $productDetails->short_description }}"></div>
                    {{---
                    <span><a href="javascript:void(0)" onclick="$('.write-review-button').click();" >Write a Review</a></span>
                    <span>&nbsp;|&nbsp;</span>
                    <span><a href="javascript:void(0)" onclick="$('.write-question-button').click();">Ask A Question</a></span>
                    --}}
                </div>
                <div class="dtl_name">
                    <h2>
                    <a target="_blank" href="{{$productDetails->vmanufacture_link}}" aria-label="Manufacturer: {{ stripslashes($productDetails->vmanufacture) }}">
                        {{ remove_html_entities($productDetails->vmanufacture) }}
                    </a>
                    <small class="vtype">{{remove_html_entities($productDetails->vtype)}}</small></h2>
                </div>
                <div class="dtl-newwave text-center text-md-left">
                    {{ remove_html_entities($productDetails->referencedName_main) }}
                </div>
            </div>
        </div>
        @if($productDetails->stock=='In')
            <div class="dtl_salestext" id="show_sort_priceDiv" role="region" aria-label="Product pricing information">
                <div class="dtlbrand-price">
                        @if(count($dealofdayRS)>0)
                            <span aria-label="Regular price"><strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong></span>
                            @if(isset($productDetails->dealprice))
                                <span class="pdetail_dealprice prodprice" data-flag="deal_price" aria-label="Deal price">DEAL: <strong>{{ Price($productDetails->dealprice) }}</strong></span>
                                @php $proprice = $productDetails->dealprice; @endphp
                            @else
                                @php $proprice = $productDetails->retail_price; @endphp
                            @endif
                        @else
                            @if($productDetails->product_price!='')
                                @if($productDetails->product_price != $productDetails->retail_price)
                                    <span aria-label="Regular price"><strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike></strong></span>
                                    <!-- <span class="pdetail_dealprice">SALE: </span>--> <span class="pdetail_dealprice" aria-label="Sale price"><strong class="prodprice pdetail_dealprice"> {{ Price($productDetails->product_price) }} </strong></span>
                                @else
                                    <strong class="prodprice" aria-label="Product price">{{ Price($productDetails->product_price) }} </strong>
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
                        {{--
                        @if($productDetails->category_id == 2 && $productDetails->stock=='In')
                            <a href="javascript:void(0)"><img src="{{config('global.SITE_URL')}}public/images/dtl_salestext.jpg" onclick="NicheFragrances()" title="NicheFragrances" alt="" width="24" height="26" loading="lazy" /></a>
                        @endif
                        --}}
                </div>

                @if(isset($PromoBadge) && count($PromoBadge)>0)
                <div class="dtl_exp_sip pdp_badge"
                @if(!empty($PromoBadge['BackColor']))
                    style="background:{{ $PromoBadge['BackColor'] }};background-image:linear-gradient(0deg, {{ $PromoBadge['BackColor'] }} 50%, {{ $PromoBadge['GradientColor'] }} 100%)"
                @endif
                >
                    <span
                        @if(!empty($PromoBadge['TextColor']))
                            style="color:{{ $PromoBadge['TextColor'] }}"
                        @endif
                    >
                        {{ html_entity_decode($PromoBadge['BadgeTitle']) }}
                    </span>
                    @if(!empty($PromoBadge['BadgeDetails']))
                    <div class="max_qttable">
                        <a href="javascript:void(0);" role="button" class="infoBtn" aria-label="More information about {{ html_entity_decode($PromoBadge['BadgeTitle']) }}"><u>i</u></a>
                        <div class="tables infoTables" role="tooltip" aria-live="polite">{{ html_entity_decode($PromoBadge['BadgeDetails']) }}</div>
                    </div>
                    @endif
                </div>
                @endif
                <input type="hidden" id="prod_pro_price" value="{{$proprice}}" aria-label="Hidden product price for calculations">
                {{-- @if(config('typevalofcriteo') == 'd' && $Afterpay_Checkout == 'Yes')--}}

                @if($Afterpay_Checkout == 'Yes' && $productDetails->template != 'Maxaroma-Edit')
                    <!-- {{Session::get('Afterpay.Min_AP_AMT')}} {{Session::get('Afterpay.Max_AP_AMT')}} -->
                    <div class="dtl_salestext pt-0">
                        @if(isset($proprice))
                            <!--<div id="shoptotal"  class="shoptotald" style="display:none;">{{$proprice * 100}}</div>-->
                            <afterpay-placement
                            data-locale="en_US"
                            data-currency="USD"
                            data-amount="{{$proprice}}"
                            data-logo-type="lockup"
                            data-min="35"
                            data-max="1000"
                            data-size="xs"
                        ></afterpay-placement>
                        @else
                            <!--<div id="shoptotal" class="shoptotald" style="display:none;">0.00</div>-->
                            <afterpay-placement
                            data-locale="en_US"
                            data-currency="USD"
                            data-amount="0.00"
                            data-logo-type="lockup"
                            data-min="35"
                            data-max="1000"
                            data-size="xs"
                        ></afterpay-placement>
                        @endif
                    </div>
                @endif

                {{-- @if(($produ_categoryid == 68 || $produ_categoryid == 69 || $produ_categoryid == 70 || $produ_categoryid == 71) && $productDetails->is_atomizer == 'Yes') --}}
                    {{-- {{config('global.FINALSALE')}}         --}}
                @if($is_finalsale == 'Y')
                    <strong style="font-size:12px;">FINAL SALE - NOT ELIGIBLE FOR RETURN</strong><br>
                @endif
            </div>
        @endif
        @if(config('typevalofcriteo') == 'd')
            <div class="pb-2 pt-2">
                <div class="dtl_itemid" id="psort_descriptn">
                    <span>Size:</span>
                    @if (trim($productDetails->short_description) != '')
                        {!! stripslashes(str_replace('\\r\n','',trim($productDetails->short_description))) !!}
                    @endif
                </div>
            </div>
        @endif
   <!--      @if(isset($productDetails->sec1_right_image) && !empty($productDetails->sec1_right_image))
        <div class="dtlbrand-pricesize-prd d-lg-none">
            {{--
            <img src="https://staging.maxaroma.com/public/images/dtlcart-product.png" alt="Product display image" width="500" height="500" loading="lazy" />
            --}}
            <img src="{{ $productDetails->sec1_right_image }}" alt="Product display image" width="500" height="500" loading="lazy" />
        </div>
        @endif -->
        @if (isset($productDetails->referenced_products) && count($productDetails->referenced_products)>0)
            <div class="size-options" role="group" aria-label="Available sizes">
            @foreach($productDetails->referenced_products as $key1 => $value1)
                @foreach($value1 as $key2 => $value2)
                    @php
                        if($value2['is_atomizer'] == 'Yes'){
                            $refCatId = $produ_categoryid;
                        } else {
                            $refCatId = $value2['category_id'];
                        }
                    @endphp
                    @if($key1 !='OutOfStock')
                    <button id="{{$value2['products_id']}}" data-design="{{ $value2['template']??'' }}" type="button"
                        class="size-pill {{ $value2['sku'] }} @if($value2['sku'] == $productDetails->sku) active @endif"
                        @if(count($value1)> 1)
                            onclick="change_product('{{$value2['products_id']}}','{{$refCatId}}');"
                        @elseif (count($productDetails->referenced_products) > 1)
                            onclick="change_product('{{$value2['products_id']}}','{{$refCatId}}');"
                        @endif
                    >
                        @php
                            //$ExpSize = explode("ml", stripslashes($value2['short_description']));
                            //$Size = trim($ExpSize[0]);
                            $Size = $value2['size'];
                        @endphp
                        {{ stripslashes($Size) }}
                    </button>
                    @endif
                @endforeach
            @endforeach
            {{--
            <button type="button" class="size-pill">
                1.0 oz / 30 ml
            </button>
            <button type="button" class="size-pill">
                0.5 oz / 1.5 ml
            </button>
            --}}
            </div>
        @endif

        <div class="dtl_prinfo_right" id="addtocart_max2dayreward">
            @include('product.product_details.addtocart_max2dayreward')
        </div>

    </div>

</div>
