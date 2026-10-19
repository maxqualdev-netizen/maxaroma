@foreach($Products as $key => $Product)
	<li><x-productboxsearch :prodData="$Product"/></li>
@endforeach
