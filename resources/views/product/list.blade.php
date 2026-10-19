@foreach($Products as $key => $Product)
    <li role="listitem" aria-label="Product {{ $loop->iteration }}"><x-productbox :prodData="$Product"/></li>
@endforeach