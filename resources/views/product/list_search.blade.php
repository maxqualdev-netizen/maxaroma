@foreach($Products as $key => $Product)
    <li role="listitem" aria-label="Search Result Product {{ $loop->iteration }}"><x-productboxsearch :prodData="$Product"/></li>
@endforeach