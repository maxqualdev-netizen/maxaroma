<style>
.mini-products-slider .product .product_icon{opacity:1;}
.stock_left{font-weight: 600;display: block;padding-bottom: 5px;color: #ec040f;font-size: 11px;line-height: 18px;}
</style>

<!--<link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE_CORE')}}common.css?ver=6666779">-->
<!-- <link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE_CORE')}}slick.css?ver=66667"> -->

<div class="sidepanel" id="cart-open" role="complementary" aria-label="Shopping Cart Side Panel">
    <a href="javascript:void(0);" class="close" aria-label="Close Cart Panel">
        <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
            <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
        </svg>
    </a>
    <div class="pl-4 pr-4 pt-4">
        <h2 id="cart-heading">Your Cart</h2>
    </div>
    <div class="sp-header" id="shopcart" role="region" aria-labelledby="cart-heading">
        @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
        <div class="sp-header-link border-bottom">
            <a href="{{url('shoppingcart')}}" id="view-bag" class="more-link" aria-label="View Your Cart">View Your Cart</a>
            <a href="javascript:void(0);" id="clear-bag" class="more-link" aria-label="Clear Your Cart">Clear Your Cart</a>
        </div>
        @endif
        <div id="sidec" class="sp-content"></div>
        <div class="sp-content">
            @if(Session::has('CartErrors') && count(Session::get('CartErrors')) > 0)
                @foreach(Session::get('CartErrors') as $ekey => $CartError)
                    <x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => $CartError ]" />
                @endforeach
            @endif
            <div class="sp-inner sp-basket">
                <ol class="items empty-items" style="@if(!isset($CartDetails['Cart']) || (isset($CartDetails['Cart']) && count($CartDetails['Cart']) == 0 )) display: block; @else display: none; @endif" role="list" aria-label="Empty Cart Items">
                    <li>
                        <p>Your Basket is Empty!</p>
                        <p><a href="{{config('global.SITE_URL')}}" class="btn_link action primary btn-full" aria-label="Continue Shopping">Continue Shopping</a></p>
                    </li>
                </ol>
                <ol class="items" role="list" aria-label="Cart Items">
                    @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
                        @foreach($CartDetails['Cart'] as $key => $CartItem)
                        <li class="item" role="listitem" aria-label="Cart Item: {{$CartItem['ProductName']}}">
                            <div class="product">
                                <div class="product-thumb">
                                    <a href="{{$CartItem['Prod_URL']}}" tabindex="0" aria-label="View {{$CartItem['ProductName']}}">
                                        {!!$CartItem['image_forpopup']!!}
                                    </a>
                                </div>
                                <div class="product-info-right">
                                    <div class="product-name" style="min-height:60px;">
                                        <a href="{{$CartItem['Prod_URL']}}" tabindex="0" aria-label="Product: {{$CartItem['ProductName']}}">
                                            <strong>{{$CartItem['ProductName']}}</strong>
                                        </a>
                                        @if(isset($CartItem['short_description']) && $CartItem['short_description'] != '')
                                            <div>{!! $CartItem['short_description'] !!}</div>
                                        @endif
                                        @if(isset($CartItem['stock_left']) && $CartItem['stock_left'] > 0 && $CartItem['stock_left'] < 6)
                                            <span class="stock_left">Only {{ $CartItem['stock_left'] }} left in stock</span>
                                        @endif
                                        @if(isset($CartItem['BogoDiscountMessage']) && !empty($CartItem['BogoDiscountMessage']))
                                            {!! $CartItem['BogoDiscountMessage'] !!}
                                        @endif
                                    </div>
                                    @if(isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes')
                                        <div class="yotpo-product pb-2" style="font-size:11px;color:#000;">
                                            Free Product Reward - <strong>Yotpo</strong>
                                        </div>
                                    @endif
                                    @if(isset($CartItem['FinalSale']) && $CartItem['FinalSale']!='' )
                                    <div class="yotpo-product pb-2" style="font-size:11px;color:#000;">
                                        {{$CartItem['FinalSale']}}
                                    </div>
                                    @endif

                                    @if(Auth::guard('store')->check())
                                        @if(isset($CartItem['OrderType']) && $CartItem['OrderType'] == 'Store')
                                            <div class="itemType ml-0 f12 pb-2">Store Item</div>
                                        @else
                                            <div class="itemType ml-0 f12 pb-2">Online Item</div>
                                        @endif
                                    @endif

                                    <div class="product-price">
                                        @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes')  || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes') )
                                            <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price Free">{{$CartItem['Qty']}} X {{Price(0.00)}}</div>
                                        @else
                                            @if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
                                                <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price {{Price($CartItem['Price'])}}">{{$CartItem['Qty']}} X {{Price($CartItem['Price'])}}</div>
                                            @else
                                                <div class="price" aria-label="Quantity {{$CartItem['Qty']}}, Price {{Price($CartItem['ItemPrice'])}}">{{$CartItem['Qty']}} X {{Price($CartItem['ItemPrice'])}}</div>
                                            @endif
                                        @endif
                                        @if(isset($CartItem['ORGSKU']) && $CartItem['ORGSKU'] != '')
                                        @else
                                        <!-- <a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" > Remove </a> -->
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="prd-cartbot">
                                <a href="javascript:void(0);" class="delete-icon btn_link cartItemDel" data-index="{{$key}}" aria-label="Remove {{$CartItem['ProductName']}} from cart">Remove</a>
                                <div class="qtybox product-cart-plusminus cart-table" role="group" aria-label="Change quantity for {{$CartItem['ProductName']}}">

                                    @if((isset($CartItem['IS_Free_Gift']) && $CartItem['IS_Free_Gift'] == 'Yes') || (isset($CartItem['IsYotpoFreeProduct']) && $CartItem['IsYotpoFreeProduct'] == 'Yes') || (isset($CartItem['Is_Free_Sample']) && $CartItem['Is_Free_Sample'] == 'Yes'))
                                    <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="1" data-qty="1" min="1" max="3" style="display:none;" aria-label="Quantity 1">
                                    @else
                                        <button type="button" class="left-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="minus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Decrease quantity for {{$CartItem['ProductName']}}">-</button>
                                        <input type="text" name="prodqty_{{$CartItem['ProductID']}}" id="prodqty_{{$CartItem['ProductID']}}" class="form-control input-number" value="{{$CartItem['Qty']}}" data-qty="{{$CartItem['Qty']}}" min="1" style="width:30%;" aria-label="Quantity for {{$CartItem['ProductName']}}">
                                        <button type="button" class="right-qty btn-number" data-usertype="{{Session::get('eusertype')}}" data-type="plus" data-field="prodqty_{{$CartItem['ProductID']}}" data-product="{{$CartItem['ProductID']}}" aria-label="Increase quantity for {{$CartItem['ProductName']}}">+</button>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    @endif
                </ol>

                @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0 )
                    <!-- Recommended Products Start -->
                    <?php
                    if(isset($allSectionRecommendedPrds) && count($allSectionRecommendedPrds) > 0){
                    for($i=0;$i<count($allSectionRecommendedPrds);$i++){
                    if(count($allSectionRecommendedPrds[$i]['slider_products']) > 0){ ?>
                        <section class="pt-3 pt-lg-4 sectionMiniCart" role="region" aria-label="Recommended Products">
                                <div class="pt-3 text-center pb-3">
                                    <div class="text-left">
                                        <h3><span><?=$allSectionRecommendedPrds[$i]['title'];?></span></h3>
                                        <!--<a href="javascript:void(0);" class="viewall">See More</a>-->
                                    </div>
                                </div>
                                <div class="cover-spin" role="status" aria-live="polite"></div>
                                <div class="mini-products-slider" role="region" aria-label="Recommended Product List">
                                     @foreach($allSectionRecommendedPrds[$i]['slider_products'] as $key => $Product)
                                        <x-productrec :prodData="$Product" sliderClass="slick-slide" />
                                    @endforeach
                                </div>
                        </section>
                    <?php
                    }
                    }
                    } ?>
                    <!-- Recommended Products End -->
                @endif

                @if(isset($CartDetails['Cart']) && count($CartDetails['Cart']) > 0)
                <div class="sp-bottom" role="region" aria-label="Cart Subtotal and Checkout">
                    <p>
                        <span class="price-text d-flex align-items-center justify-content-between">
                            CART SUBTOTAL:
                            <strong id="subtotal" aria-live="polite">@if(isset($CartDetails['SubTotal'])) {{Price($CartDetails['SubTotal'])}} @else 0.00 @endif</strong>
                        </span>
                    </p>
                    <a href="{{url('checkout')}}" class="btn btn-primary secure-checkoutbtn" aria-label="Proceed To Checkout">
                        <svg class="svg-securelock" aria-hidden="true" role="img" width="16" height="16">
							<use href="#svg-securelock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-securelock"></use>
						</svg>
                        <span>Proceed To Checkout</span>
                    </a>
                    {{--
                    <div id="SidePaymethods">
                        @if($CartDetails['IsPaypalExpressCheckout'] == 'Yes')
                        <div id="paypal-button-container" class="pb-2 mb-1">
                            <a class="paypal-btn" href="{{url('paypal/placeorder')}}">
                                <img alt="Paypal" src="{{config('global.SITE_IMAGES')}}paypal-cart.png" class="img-fluid">
                            </a>
                        </div>
                        @endif

                        @if($CartDetails['Amazon_pay_Checkout'] == 'Yes')
                        <div>
                            <div id="AmazonPayButtonAll"></div>
                        </div>
                        @endif
                    </div>
                    <p><a href="{{url('shoppingcart')}}" class="btn_link">View Your Cart</a></p>
                    --}}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="black-grbg cart-bg" aria-hidden="true"></div>

<!--<script type="text/javascript" src="{{config('global.SITE_JS_CORE')}}jquery-3.4.1.min.js?ver=11111123"></script>
<script src="{{ config('global.SITE_JS_CORE')}}slick.js?ver=666677"></script>-->

<script>

/*$('.mini-products-slider').on('init', function(event, slick){
	$('.cover-spin').hide();
    //$('.sectionMiniCart').find(".cover-spin").hide();
});

$('.mini-products-slider').slick({
        lazyLoad: 'ondemand',
        dots: true,
        infinite: false,
        speed: 300,
        arrows: true,
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        slidesToScroll: 2,
        nextArrow: '<button class="slick-next round-btn-sl" aria-label="Next recommended product"><svg class="svg svg-slick-right" aria-hidden="true" width="24" height="24" role="img"><use href="#sv-right-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-right-arrow"></use></svg></button>',
        prevArrow: '<button class="slick-prev round-btn-sl" aria-label="Previous recommended product"><svg class="svg svg-slick-left" width="24" height="24" aria-hidden="true" role="img"><use href="#sv-left-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-left-arrow"></use></svg></button>',
    });*/
</script>
