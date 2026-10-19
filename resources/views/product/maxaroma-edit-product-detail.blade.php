@extends('layouts.app', ['body_class' => 'prd-bot-space'])
@section('content')
<main class="box-greybg brand-newdetail" id="pageTemplate" data-design="{{ $productDetails->template }}">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <ol>
                @foreach ($breadcrumbs as $key => $breadcrumb)
                    <li>
                        @if ((count($breadcrumbs) - 1) == $key)
                            <span aria-current="page">{{ $breadcrumb['title'] }}</span>
                        @else
                            <a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </nav>
    <section class="container" id="proddetails" data-type="{{Session::get('eusertype')}}">
        <div class="brand-dtl-prdtext">
            <div class="row m-0">
                <div class="col-md-6 col-lg-7 p-0" id="productimgicon_manufacture_detail">
                    @include('product.product_details.productimgicon_manufacture_detail')
                </div>
                <div class="col-md-6 col-lg-5 p-0">
                    <div class="brand-dtl-info">
                        <div class="dtl_prinfo_left dtl-imgbot-mob" id="product_descriptionpart">
                            @include('product.product_details.product_descriptionpart')
                        </div>

                        @if (isset($productDetails->referenced_products)) <!-- (!$isMobile || $isIpad == 1) -->
                        <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJ" referencedProductevent="change_product" new=1 referenceProductCategoryId="{{$produ_categoryid}}" />
                        @endif

                     <div class="dtl_exp_sip-main" id="maxtwoandreward" role="region" aria-label="Max Two and Reward">
                        @include('product.product_details.maxtwoandreward')
                    </div>
                       <div class="brand-dtlbottom-row-cont">
                            <div id="iAdvizeSection" role="region" aria-labelledby="fragrance-heading"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fragrance-notes" class="fragrance-notes-sec brandpage-space" role="region" aria-labelledby="fragrance-heading">
        <h2 id="fragrance-heading" class="sr-only">Fragrance Notes</h2>
        @include('product.product_details.fragrance-notes')
    </section>

    <section id="scent-profile-sec" class="scent-profile-sec brandpage-space" role="region" aria-labelledby="scent-heading">
        <h2 id="scent-heading" class="sr-only">Scent Profile</h2>
        @include('product.product_details.scent-profile-sec')
    </section>

    <section id="brands-banner-sec" class="brands-banner dtl-banner-sec" role="region" aria-labelledby="brands-heading">
        <h2 id="brands-heading" class="sr-only">Brands</h2>
        @include('product.product_details.brands-banner-sec')
    </section>

    {{--@include('product.product_details.you_may_also_like')--}}

    <section class="container" aria-label="Product Gallery">
        <div class="dtl_gallery" role="region" aria-label="Product gallery images"></div>
    </section>

    @include('product.product_details.other-brand-scents')

    <section class="dtl_rview detail-page-yotporeview mb-5" id="productreview" aria-labelledby="review-heading">
        <div class="container">
            <div class="text-center pb-4">
                <div class="main-heading">
                    <h3><span>What people are saying</span></h3>
                </div>
            </div>
            <div class="yotpo yotpo-main-widget"
                data-product-id="{{ $productDetails->products_id }}"
                @if(isset($proprice))
                    data-price="{{$proprice}}"
                @else
                    data-price="0"
                @endif
                data-currency="USD"
                data-count="5"
                data-name="{{ $productDetails->product_name }}"
                data-url="{{$CanonicalURL}}"
                data-image-url="{{ $productDetails->mainImage }}">
            </div>
        </div>
    </section>

    <section class="dtl_sticky d-none d-md-block" id="productBottomsticky" aria-label="Product sticky controls" aria-live="polite">
        @include('product.product_details.product_bottomsticky')
    </section>
</main>
<script>
    (function($) {
        $(function() {

            if (typeof $.fn.slick === 'undefined') {
                console.error('Slick.js not loaded.');
                return;
            }

            function initSlider($slider, options) {
                function initOrDestroy() {
                    var isMobile = window.innerWidth <= 1024;

                    if (isMobile) {
                        if (!$slider.hasClass('slick-initialized')) {
                            $slider.slick(options);
                        }
                    } else {
                        if ($slider.hasClass('slick-initialized')) {
                            $slider.slick('unslick');
                        }
                    }
                }

                initOrDestroy();

                $(window).on('resize.slider' + $slider.index(), function() {
                    initOrDestroy();
                });
            }

            var sliderOptionsMap = {
                'scent-itemslider': {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: true,
                    dots: true,
                    accessibility: true,
                    autoplay: false,
                    responsive: [
                        { breakpoint: 575, settings: { slidesToShow: 2 } }
                    ]
                }
            };

            $('.brandProducts-sl').each(function() {
                var $slider = $(this);
                var key = $slider.data('slider');
                var options = sliderOptionsMap[key];

                if (options) {
                    initSlider($slider, options);
                } else {
                    console.warn('No options found for slider key: ' + key);
                }
            });
        });
    })(jQuery);
</script>
{{--
<script>
    document.addEventListener("scroll", function () {
    const trigger = document.querySelector(".mob-dtl_rbtn");
    const target = document.querySelector(".dtl_rbtn");

    if (!trigger || !target) return;

    const rect = trigger.getBoundingClientRect();

    if (rect.top <= 0) {
        target.classList.add("mob-sticktbtn");
    } else {
        target.classList.remove("mob-sticktbtn");
    }
    });
</script>
--}}
@endsection
<script type="text/javascript">
    var Site_URL = "{{config('global.SITE_URL')}}";
    var isMobile = "{{isMobile()}}";
    var DealStartDate = "{{ $DealStartDate ?? '' }}";
    var DealEndDate = "{{ $DealEndDate ?? '' }}";
    @if(Session::has('eusertype') && Session::get('eusertype') == 'Wholesaler')
    const userType = "wholesaler";
    @else
    const userType = "retailer";
    @endif
</script>
<script src="https://www.paypal.com/sdk/js?client-id=AQymJLkSRgzhHf0AjiYOGL_OHQZ60bCggeySkd8F31n_2ery6HK7HXYQGeeBCfszGgAin8XfJbvZuByn&components=buttons&commit=false"></script>
<script>
    var shippingmodes = [];

    function getShippingInfo(state = '', zip = '', country = '')
    {
        var productID = $(".addtocart").attr('data-product');
        var prodqty = $("#prodqty").val();
        var prod_price = $("#show_sort_priceDiv strong.prodprice").text().trim().replace("$", ""); //$("#js_prod_price").val();
        var total_price = parseFloat(prodqty) * parseFloat(prod_price);

        $.ajax({
            type: 'POST',
            url: site_url + 'shipping',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            data: {
                'OnlyHead': '0',
                'action': 'shippinginfo',
                'subaction': 'paypalproductpage', //'stripecart',
                'state': state,
                'zip': zip,
                'country': country,
                'Gpay': 'Yes',
                'FirstStepGpay': 'FirstStep',
                'paypal_productid': productID,
                'paypal_prodqty': prodqty,
                'paypal_prod_price': prod_price,
                'paypal_total_price': total_price

            },
            datatype: 'JSON',
            success: function(data) {
                // alert('shippingOptions');
                // console.log('shippingOptions');
                // console.log(data);
                shippingmodes = data;
            }
        });
        return shippingmodes;
    }

    function setPayPal()
    {
        var CREATE_CAPTUREPAYMENT_URL = '{{url('placepaypalorder')}}';
        var updatePayPalOrder = '{{url('update_paypal_order')}}';
        var ref_id = "testOrderSandBox_" + Date.now();
        paypal.Buttons({
            style: {
                layout: 'horizontal',
                color: 'gold',
                shape: 'rect',
                label: 'paypal',
                tagline: false
            },
            onInit(data, actions) {
                //getShippingInfo('NY','10018','US');
                //getShippingInfo('CA','95131','US');
            },
            onClick: function() {
                //AddToCart($(".addtocart").attr('data-product'), $("#prodqty").val());
                //getShippingInfo('NY','10018','US');
            },
            createOrder: function(data, actions) {
                //console.log("product price is "+$("#show_sort_priceDiv strong.prodprice").text().trim().replace("$",""));
                var productID = $(".addtocart").attr('data-product');
                var prodqty = $("#prodqty").val();
                var prod_price = $("#show_sort_priceDiv strong.prodprice").text().trim().replace("$", ""); //$("#js_prod_price").val();

                var total_price = parseFloat(prodqty) * parseFloat(prod_price);
                var tax_amount = parseFloat("0.00"); // parseFloat("10.00");
                var full_amount = total_price + tax_amount;

                return actions.order.create({
                    purchase_units: [{
                        //reference_id: ref_id,//"testOrderSandBox",
                        amount: {
                            value: full_amount.toFixed(2),
                            breakdown: {
                                item_total: {
                                    currency_code: 'USD',
                                    value: total_price.toFixed(2)
                                },
                                tax_total: {
                                    currency_code: 'USD',
                                    value: tax_amount
                                }
                            }
                        },
                        currency: "USD",
                        name: {
                            full_name: "Qualdev Test"
                        },
                        items: [{
                            name: $(".bottomLine").attr("data-name"), //'Paco Rabanne',
                            quantity: prodqty, //'1',
                            url: $(".bottomLine").attr("data-url"),
                            unit_amount: {
                                currency_code: 'USD',
                                value: prod_price
                            },
                            sku: $("#detail_prod_sku").text(), //'UP3349668597963',
                            category: 'PHYSICAL_GOODS',
                            tax: {
                                currency_code: 'USD',
                                value: tax_amount
                            },
                        }],
                        shipping: {
                            options: shippingmodes.length > 0 ? shippingmodes : null
                        }
                    }]
                });
            },
            onShippingOptionsChange(data, actions) {
                console.log('ShippingOptionsChange');
                console.log(data);
                console.log(actions);
                // if (data.selectedShippingOption.type === 'PICKUP') {
                //     return actions.reject(data.errors.STORE_UNAVAILABLE);
                // }
            },
            //onShippingChange(data, actions) {
            onShippingAddressChange(data, actions) {
                if (data.shippingAddress.countryCode !== "US") {
                    return actions.reject(data.errors.COUNTRY_ERROR);
                } else {
                    return fetch(updatePayPalOrder, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            shippingAddress: data.shippingAddress,
                            shipping_city: data.shippingAddress.city,
                            shipping_countrycode: data.shippingAddress.countryCode,
                            shipping_postalcode: data.shippingAddress.postalCode,
                            shipping_state: data.shippingAddress.state,
                            shipping_info: getShippingInfo(data.shippingAddress.state, data.shippingAddress.postalCode, data.shippingAddress.countryCode),
                            ref_id: ref_id,
                            prod_price: $("#show_sort_priceDiv strong.prodprice").text().trim().replace("$", ""), //$("#js_prod_price").val(),
                            prod_qty: $("#prodqty").val(),
                            prod_id: $(".addtocart").attr('data-product')
                        })
                    })
                }

            },
            onCancel(data) {
                // Show a cancel page, or return to cart
                //window.location.assign("/your-cancel-page");
                ////AddToCart($(".addtocart").attr('data-product'), $("#prodqty").val());
                //window.location.assign(site_url+'shoppingcart');
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(order_data) {
                    fetch(CREATE_CAPTUREPAYMENT_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: JSON.stringify({
                                product_sku: $("#detail_prod_sku").text(),
                                product_id: $(".addtocart").attr('data-product'),
                                prod_price: $("#show_sort_priceDiv strong.prodprice").text().trim().replace("$", ""), //$("#js_prod_price").val(),
                                prod_qty: $("#prodqty").val(),
                                orderData: order_data
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data == 'success') {
                                //window.location.href = site_url+'order-receipt';
                            }
                            //window.location.href = site_url+'order-receipt';
                            console.log('Success:', data);
                        })
                        .catch((error) => {
                            //window.location.href = site_url+'order-receipt';
                            console.error('Error:', error);
                        });
                });
                console.log('approve');
                console.log(data);
                console.log(actions);

            }
        }).render('#paypal-button-container');
    }
    setPayPal();
</script>
