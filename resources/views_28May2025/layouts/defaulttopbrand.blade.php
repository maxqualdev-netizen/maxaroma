@foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
	@if(count($brand_alpha_value) > 0)
		@foreach($brand_alpha_value as $brand_key => $brand_value)
		<li @if($brand_key == 0) id="{{ $brand_alpha_key }}_top" @endif >
			<a href="{{ $brand_value['Link'] }}">{{ $brand_value['Name'] }}</a>
		</li>
		@endforeach
	@endif
@endforeach