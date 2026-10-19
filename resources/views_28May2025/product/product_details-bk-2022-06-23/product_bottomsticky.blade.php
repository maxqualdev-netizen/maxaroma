<div class="container">
<a class="thumb"><img width="{{getImageWidth(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}"  height="{{getImageHeight(config('global.PRD_MEDIUM_IMG_PATH').$productDetails->image)}}"  src="{{ $productDetails->mainImage }}" alt=""></a>
    <div class="info dtl_salestext">
        <div class="info_to">
            <div class="name">
            @if(!empty($productDetails->vmanufacture))
                {{ stripslashes($productDetails->vmanufacture) }}
                {!! stripslashes($productDetails->referencedName_main) !!}
            @else
                {{ stripslashes($productDetails->brand_name) }}
                {{ stripslashes($productDetails->referencedName_main) }}
            @endif
            </div>
            <!-- <span class="our_price">Our Price <strong>$102.36</strong></span> <span class="save_text">You Sav: 36% ($56.64)</span> -->
           <span class="our_price">
            @if($productDetails->stock=='In')
                @if(count($dealofdayRS) > 0)
                    @if(count($dealofdayRS)>0)
                    <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike>&nbsp;</strong>
                    @endif
                @else
                    @if($productDetails->product_price!='')
                        @if($productDetails->product_price!=$productDetails->retail_price)
                        <strong class="text-red"><strike>{{ Price($productDetails->retail_price) }}</strike>&nbsp;</strong>
                        @endif
                    Now: <strong class="prodprice">{{ Price($productDetails->product_price) }} </strong>
                        @if($productDetails->yousave!='0')
                        <span class="greentext f12">You Save: ({{$productDetails->yousave}})% OFF</span>
                        @endif
                        @php $proprice = $productDetails->product_price; @endphp
                    @endif
                @endif
            @endif
       
            @if($productDetails->stock=='In')
                @if(count($dealofdayRS)>0 && isset($productDetails->dealprice))
                <span class="pdetail_dealprice prodprice" data-flag="deal_price">Deal Price: {{ Price($productDetails->dealprice) }}</span>

                    @if($productDetails->yousave >0 && $productDetails->yousave!='')
                    <span class="greentext f12">You Save: {{ $productDetails->yousave }}% OFF ({{Price($productDetails->yousaveprice)}})</span>
                    @endif

                @php $proprice = $productDetails->dealprice; @endphp
                @endif
            @endif
        </span>
        @if($productDetails->stock=='In')
            <div class="dtl_rbtn">
                <div class="input-group qty-input sticky-qty">
                    <button type="button" class="left-qty btn-number" disabled="disabled" data-type="minus" data-field="prodqty">-</button>
                    <input type="text" name="prodqty" class="form-control input-number" id="prodqty" value="1" min="1" max="10">
                    <button type="button" class="right-qty btn-number" data-type="plus" data-field="prodqty">+</button>
                </div>
                <a href="javascript:void(0);" data-product="{{ $productDetails->products_id }}" class="btn btn-addtocart d-block addtocart" data-type="sticky">Add to cart</a>
            </div>
        @else
        <div class="dtl_rbtn">
            <span class="text-red outstock-text" style="color: red!important;">Out Of Stock</span>
        </div>
        @endif
        </div>
        <div class="info_bo">
            <a href="javascript:void(0)" onclick="changestickoption('proddetails','1')">Product Details</a>
            <a href="javascript:void(0)" onclick="changetab('Read')">Reviews</a>
            <a href="javascript:void(0)" onclick="changestickoption('dtl_tab','2')">Shipping Information</a>
            <a href="javascript:void(0)" onclick="changestickoption('dtl_tab','3')">Return & Exchange</a>
            <strong class="free_sipping">Free Shipping on all U.S. Orders</strong>
        </div>
    </div>
</div>
