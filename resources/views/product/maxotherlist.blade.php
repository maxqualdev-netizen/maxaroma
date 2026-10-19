@foreach($Products as $key => $Product)
    <div class="col-md-4 col-6" aria-label="Max Two Day Product {{ $loop->iteration }}">
        <x-productbox :prodData="$Product" page="maxtwoday" />
    </div>
@endforeach