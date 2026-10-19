<main id="checkout-page" class="business_page" data-total="{{$CartAttr['TotalValue']}}" role="main" tabindex="-1" aria-label="Checkout Main Content">
    <section class="checkout-page" data-section="0" role="region" aria-label="Checkout Progress Section">
        <div class="container">
            <div class="text-center d-md-none">
                <div class="cartnw-step" role="navigation" aria-label="Checkout Steps Navigation">
                    <ul role="list" aria-label="Checkout Steps">
                        <li role="listitem"><a href="{{config('global.SITE_URL')}}shoppingcart/view" aria-label="View Cart">Cart</a></li>
                        <li class="@if($CurrentRoute == 'billing') active @endif" role="listitem">Information</li>
                        <li class="@if($CurrentRoute == 'shippinginfo') active @endif" role="listitem">Shipping</li>
                        <li role="listitem">Payment</li>
                    </ul>
                </div>
            </div>
            <div class="checkout-middle-sec">
                <div class="checkout-left-part">
                    <div class="checkout-cont-lg">
                        <div class="row">
                            <div class="col-12 text-center d-none d-md-block">
                                <div class="cartnw-step" role="navigation" aria-label="Checkout Steps Navigation">
                                    <ul role="list" aria-label="Checkout Steps">
                                        <li role="listitem">
											<a href="{{config('global.SITE_URL')}}shoppingcart/view" aria-label="View Cart">Cart</a>
										</li>
                                        <li class="@if($CurrentRoute == 'billing') active @endif" role="listitem">
											@if($CurrentRoute == 'billing-shipping' || $CurrentRoute == 'billing-payment')
												@if(isset($SelPayMethod) && $SelPayMethod == 'paypal')
												<a href="{{config('global.SITE_URL')}}checkout/paypal" aria-label="Information">Information</a>
												@elseif(isset($SelPayMethod) && $SelPayMethod == 'AP')
												<a href="{{config('global.SITE_URL')}}checkout/AP" aria-label="Information">Information</a>
												@else
												<a href="{{config('global.SITE_URL')}}checkout" aria-label="Information">Information</a>
												@endif
											@else
                                         		Information
											@endif
										</li>
                                         <li class="@if($CurrentRoute == 'billing-shipping') active @endif" role="listitem">
                                             @if($CurrentRoute == 'billing-payment') 
                                             	<a href="{{config('global.SITE_URL')}}shipping" aria-label="Shipping">Shipping</a>
                                             @else 
                                             	Shipping 
                                             @endif
                                         </li>
                                         <li class="@if($CurrentRoute == 'billing-payment') active @endif" role="listitem">Payment</li>
                                    </ul>
                                </div>
                            </div>