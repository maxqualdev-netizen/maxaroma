<main id="checkout-page" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
		<section class="checkout-page" data-section="0">
			<div class="container">
				<div class="text-center d-md-none">
					<div class="cartnw-step">
						<ul>
							<li><a href="{{config('global.SITE_URL')}}shoppingcart/new">Cart</a></li>
							<li class="@if($CurrentRoute == 'billing_new') active @endif">Information</li>
							<li class="@if($CurrentRoute == 'shippinginfo') active @endif">Shipping</li>
							<li>Payment</li>
						</ul>
					</div>
				</div>
				<div class="checkout-middle-sec">
					<div class="checkout-left-part">
						<div class="checkout-cont-lg">
							<div class="row">
								<div class="col-12 text-center d-none d-md-block">
									<div class="cartnw-step">
										<ul>
											 <li><a href="{{config('global.SITE_URL')}}shoppingcart/new">Cart</a></li>
											 <li class="@if($CurrentRoute == 'billing_new') active @endif">@if($CurrentRoute == 'billing_new-shipping' || $CurrentRoute == 'billing_new-payment') <a href="{{config('global.SITE_URL')}}checkout">Information</a>@else Information @endif</li>
							                 <li class="@if($CurrentRoute == 'billing_new-shipping') active @endif">@if($CurrentRoute == 'billing_new-payment') <a href="{{config('global.SITE_URL')}}shipping">Shipping</a>@else Shipping @endif</li>
											 <li class="@if($CurrentRoute == 'billing_new-payment') active @endif">Payment</li>
										</ul>
									</div>
								</div>
                                
