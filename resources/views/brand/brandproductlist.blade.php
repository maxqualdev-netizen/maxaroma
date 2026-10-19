@foreach($Products as $key => $Product)
    <li class="brand-page-list" role="listitem" aria-label="Product {{ $Product->product_name ?? $Product->name ?? 'Item' }}">
        <x-productbox :prodData="$Product"/>
    </li>
@endforeach