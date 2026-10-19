@foreach($Products as $key => $Product)
    <div class="col-md-4 col-6" aria-label="Deal of the Week Product {{ $loop->iteration }}">
        <x-productbox :prodData="$Product" page="dealofweek" />
    </div>
@endforeach