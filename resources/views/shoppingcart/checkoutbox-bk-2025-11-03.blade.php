@if($CartAttr['onlyWireTrabsfer'] == 1  || $NetTotal <= 0)
	<div class="row" role="region" aria-label="Checkout Section">
		<div class="col-12 pl-md-4"> 
			<a class="btn btn-primary d-block ml-md-2" href="{{config('global.SITE_URL').'checkout'}}" role="button" aria-label="Proceed to Checkout">Checkout</a>
		</div>
	</div>
@endif