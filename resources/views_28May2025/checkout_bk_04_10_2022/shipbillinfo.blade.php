<div class="mb-0 mb-md-4">
	<h3 class="cart-hd d-none d-md-block">
		<span>Billing Information</span> 
		<a href="javascript:void(0);" class="float-right edit-btn btn btn-primary billback" data-index="1">Edit</a>
	</h3>
	<h3 class="cart-hd d-md-none">
		<span>1. Shipping Information</span> 
		<a href="javascript:void(0);" class="float-right edit-btn btn btn-primary billback" data-index="2">Edit</a>
	</h3>
	<div class="pt-3">
		<p class="mb-0">
			{{$Billing['first_name']}} {{$Billing['last_name']}}<br>
			{{$Billing['address1']}},
			@if($Billing['address2'] != '')
				{{$Billing['address2']}},
			@endif
			<br> 
			{{$Billing['city']}}, {{$Billing['state']}} - {{$Billing['zip']}}, {{$Billing['country']}}<br> 
			{{$Billing['email']}}
		</p>
	</div>
</div>
<div class="border-top pt-4 d-none d-md-block">
	<h3 class="cart-hd">
		<span>Shipping Information</span> 
		<a href="javascript:void(0);" class="float-right edit-btn btn btn-primary billback" data-index="2">Edit</a>
	</h3>
	<div class="pt-3">
		<p class="mb-0">
			{{$Shipping['first_name']}} {{$Shipping['last_name']}}<br>
			{{$Shipping['address1']}},
			@if($Shipping['address2'] != '')
				{{$Shipping['address2']}},
			@endif
			<br> 
			{{$Shipping['city']}}, {{$Shipping['state']}} - {{$Shipping['zip']}}, {{$Shipping['country']}}<br> 
			{{$Shipping['email']}}
		</p>
	</div>
</div>