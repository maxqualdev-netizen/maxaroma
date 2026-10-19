@if($CartAttr['onlyWireTrabsfer'] == 1  || $NetTotal <= 0)
<div class="row">
	<div class="col-12 pl-md-4"> 
		<a class="btn btn-primary d-block ml-md-2" href="{{config('global.SITE_URL').'checkout'}}">Checkout</a>
	</div>
</div>
@endif
