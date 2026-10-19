<h4 class="cart-hd-sub">
	<span>Shipping Method</span> 
	<a href="javascript:void(0);" class="float-right btn btn-secondary shipback" data-index="3">Edit</a>
</h4>
<div class="pt-3">
	@if(Session::has('ShoppingCart.Shipping') && count(Session::get('ShoppingCart.Shipping')) > 0)
		<p class="mb-0">
			<strong>{{strip_tags(Session::get('ShoppingCart.Shipping.ShippingMethodName'))}}</strong>
			@if(Session::has('ShoppingCart.Shipping.ShippingDays') && Session::get('ShoppingCart.Shipping.ShippingDays') != '')
				<br> 
				EST : {!!Session::get('ShoppingCart.Shipping.ShippingDays')!!}
			@endif
		</p>
	@endif
</div>
