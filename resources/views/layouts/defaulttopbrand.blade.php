@foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
    @if(count($brand_alpha_value) > 0)
        @foreach($brand_alpha_value as $brand_key => $brand_value)
        <li @if($brand_key == 0) id="{{ $brand_alpha_key }}_top" @endif role="listitem" aria-label="Brand: {{ $brand_value['Name'] }}" >
            <a href="{{ $brand_value['Link'] }}" aria-labelledby="brand_{{ $brand_alpha_key }}_{{ $brand_key }}" id="brand_{{ $brand_alpha_key }}_{{ $brand_key }}" >
                {{ $brand_value['Name'] }}
            </a>
        </li>
        @endforeach
    @endif
@endforeach