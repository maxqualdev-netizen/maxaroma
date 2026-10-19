@foreach($DealProds as $key => $Product)
	<div class="col-md-4 col-6">
		<x-productbox :prodData="$Product"/>
	</div>
@endforeach