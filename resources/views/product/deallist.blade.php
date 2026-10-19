@foreach($DealProds as $key => $Product)
    <div class="col-md-4 col-6" aria-label="Deal Product {{ $loop->iteration }}">
        <x-productbox :prodData="$Product"/>
    </div>
@endforeach