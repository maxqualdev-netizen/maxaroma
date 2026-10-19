@if($CartAttr['onlyWireTrabsfer'] == 1  || $NetTotal <= 0)
	<div class="row" role="region" aria-label="Checkout Section">
		<div class="col-12 pl-md-4">
			<a class="btn btn-primary secure-checkoutbtn ml-md-2" href="{{config('global.SITE_URL').'secure-checkout1'}}" role="button" aria-label="Proceed to Checkout">
				<svg class="svg-securelock" aria-hidden="true" role="img" width="16" height="16">
					<use href="#svg-securelock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-securelock"></use>
				</svg>
				<span>Checkout</span>
			</a>
		</div>
	</div>
@endif