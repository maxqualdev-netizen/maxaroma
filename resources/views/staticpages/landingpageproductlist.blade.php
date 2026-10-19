@foreach($BestSellers as $key => $Product)
	<li class="brand-page-list">
		<x-productbox :prodData="$Product"/>
	</li>
@endforeach
