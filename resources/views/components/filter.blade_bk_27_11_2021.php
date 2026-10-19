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
						@foreach($filterData as $key => $fData)
							<li><a href="#" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" @endif ><span>{{$fData}}</span></a></li>
						@endforeach
					@else
						@foreach($filterData as $key => $fData)
							<li><a href="#" data-id="{{$fData}}" @if(in_array($fData,$filterSelected)) class="active" @endif ><span>{{$fData}}</span></a></li>
						@endforeach
					@endif		
				</ul>
			</div>
		</div>
	</div>
</div>