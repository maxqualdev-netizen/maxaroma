<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <!--<div class="modal-hd">
                        <h1>Quick View</h1>
                    </div>-->
                    <div class="modal-space">
                        <div class="row">
                            <div class="col-md-6 pb-3">
                                <span id="productIcons"> 
                                    @include('popup.quick_view_details.quickview_producticon')
                                </span>
                                <div class="dtl_large_man" id="quickviewprodimg">
                                    @include('popup.quick_view_details.quickview_productimg')
                                </div>
                            </div>
                            <div class="col-md-6 pb-4 pb-md-3">
                                <div class="dtl_prinfo_left" id="quickview_price_details">
                                    @include('popup.quick_view_details.quickview_price_details')
                                </div>
                                <div class="dtl_prinfo_right pb-md-3 pt-2" id="quickview_addtocart_description">
                                    @include('popup.quick_view_details.quickview_addtocart_description')
                                </div>
                                <div class="clearfix d-none d-lg-block"></div>
                                @if (isset($productDetails->referenced_products))
                                <x-productboxref :prodData="$productDetails->referenced_products" :productDetailsku="$productDetails->sku" referencedProductclass="detailboxJquickview" referencedProductevent="change_product_quickview"  />
                                @endif
                                @if ($OutOfStockCount > 0)
                                <div class="stockitem border-bottom pb-3 mb-3">
                                    <div class="outofstockitems">
                                        <a href="javascript:void(0);">This product has {{ $OutOfStockCount }} Out of Stock
                                            items</a>
                                        <span class="d-none d-md-inline-block">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                        <a href="javascript:void(0);" class="n-ppost uppercase" id="togglestockitem"><strong>Show out of stock items</strong></a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var Site_URL = "{{config('global.SITE_URL')}}";
        var DealStartDate = "{{ $DealStartDate ?? '' }}";
        var DealEndDate = "{{ $DealEndDate ?? '' }}";
    </script>
    <link rel="stylesheet" href="{{ asset('public/css/core/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/core/detail.css') }}">
    <script src="{{ asset('public/js/core/product_quickview.js') }}"></script>
