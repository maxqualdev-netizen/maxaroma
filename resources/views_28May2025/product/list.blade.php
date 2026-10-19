@foreach($Products as $key => $Product)
	<li><x-productbox :prodData="$Product"/></li>
@endforeach