<main id="checkout-page" class="business_page" data-total="{{$CartAttr['TotalValue']}}">
		<section class="checkout-page" data-section="0">
			<div class="container">
				<div class="text-center d-md-none">
					<div class="cartnw-step">
						<ul>
							<li><a href="{{config('global.SITE_URL')}}shoppingcart">Cart</a></li>
							<li class="@if($CurrentRoute == 'billing') active @endif">Information</li>
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
											 <li><a href="{{config('global.SITE_URL')}}shoppingcart">Cart</a></li>
											 <li class="@if($CurrentRoute == 'billing') active @endif">@if($CurrentRoute == 'billing-shipping' || $CurrentRoute == 'billing-payment') <a href="{{config('global.SITE_URL')}}checkout">Information</a>@else Information @endif</li>
							                 <li class="@if($CurrentRoute == 'billing-shipping') active @endif">@if($CurrentRoute == 'billing-payment') <a href="{{config('global.SITE_URL')}}shipping">Shipping</a>@else Shipping @endif</li>
											 <li class="@if($CurrentRoute == 'billing-payment') active @endif">Payment</li>
										</ul>
									</div>
								</div>
                                
