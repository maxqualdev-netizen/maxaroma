<div class="your-bag d-md-none">
	<div class="actmenuhd">
		<div class="row">
			<div class="col-4 text-left"><span>{{$CartDetails['TotalItemInCart']}} items</span> </div>
			<div class="col-8 text-right"><strong>Total Amount: {{Price($NetTotal)}}</strong> </div>
		</div>
	</div>
</div>
@if(($CartAttr['onlyWireTrabsfer'] == 1 && $CartAttr['CheckGCPurchasedVal'] == 0) || $NetTotal <= 0)
<div class="row">
	<div class="col-12 mt-3 mt-md-0"> <a class="btn btn-secondary d-block checkout-btn" href="{{config('global.SITE_URL').'billing'}}">Proceed to Checkout</a> </div>
</div>
@endif