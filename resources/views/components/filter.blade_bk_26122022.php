<div class="filter_acrd filter_acrd_act">
	<div class="filter_acrd_hd">
		<span class="title">{{$filterAttr['title']}}</span> 
		<a class="reset_link" id="reset-{{$filterAttr['id']}}" data-filter="{{$filterAttr['id']}}" >Reset</a>
	</div>
	<div class="filter_acrd_con">
		<div class="inner">
			<div class="scroll">
				<ul class="filter_checkbox" id="{{$filterAttr['id']}}">
					@if(isset($filterAttr['filterval']) && $filterAttr['filterval'] == 'key')
						@if(isset($filterAttr['id']) && $filterAttr['id'] == 'brands')
							@php $i=0; @endphp
							@foreach($filterData as $key => $fData)
								@if($i < 10)
								<li><a href="#" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" @endif ><span>{{stripcslashes($fData)}}</span></a></li>							
								@endif	
								@php $i++; @endphp
							@endforeach
						@else 	
							@foreach($filterData as $key => $fData)
								<li><a href="#" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" @endif ><span>{{$fData}}</span></a></li>							
							@endforeach
						@endif			
						
					@else
						@foreach($filterData as $key => $fData)
							<li><a href="#" data-id="{{$fData}}" @if(in_array($fData,$filterSelected)) class="active" @endif ><span>{{$fData}}</span></a></li>
						@endforeach
					@endif		
				</ul>
			</div>
			@if(isset($filterAttr['id']) && $filterAttr['id'] == 'brands' && count($filterData) >= 10)
						<a href="{{config('global.SITE_URL')}}brand-name-perfumes.html" class="list-seemore">See More <span>+</span></a>	
						@endif
		</div>
	</div>
</div>
