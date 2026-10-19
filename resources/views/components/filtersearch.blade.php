<div class="filter_acrd" role="region" aria-labelledby="filter-{{$filterAttr['id']}}-title">
    <div class="filter_acrd_hd">
		@if($filterAttr['title'] == 'Rating')
		<span class="title" id="filter-{{$filterAttr['id']}}-title">Star Rating</span>
		@else
        <span class="title" id="filter-{{$filterAttr['id']}}-title">{{$filterAttr['title']}}</span> 
        @endif
        <a class="reset_link" id="reset-{{$filterAttr['id']}}" data-filter="{{$filterAttr['id']}}" role="button" tabindex="0" aria-label="Reset {{ $filterAttr['title'] }} filter">Reset</a>
    </div>
    <div class="filter_acrd_con" style="display: none;">
        <div class="inner">
            <div class="scroll">
                <ul class="filter_checkbox" id="{{$filterAttr['id']}}" role="list" aria-label="{{ $filterAttr['title'] }} options">
                    @if(isset($filterAttr['filterval']) && $filterAttr['filterval'] == 'key')
                        @foreach($filterData as $key => $fData)
							
							@if($filterAttr['title'] == 'Rating')
								@if($fData == 3 || $fData == 4 || $fData == 5)
									<li role="listitem">
										<a href="javascript:void(0);"id="{{$filterAttr['name']}}{{$key}}" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
										<span>
											@if($fData == 3)
												<img src="https://staging.maxaroma.com/public/images/star-3.png">
											@elseif($fData == 4)
												<img src="https://staging.maxaroma.com/public/images/star-4.png">
											@elseif($fData == 5)
												<img src="https://staging.maxaroma.com/public/images/star-5.png">
											@endif
										</span>
									</a></li>
								@endif
							@else
								<li role="listitem">
									<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$key}}" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
										<span>{{$fData}}</span>
									</a>
								</li>
							@endif
							
                        @endforeach
                    @else
                        @foreach($filterData as $key => $fData)
                        
							@if($filterAttr['title'] == 'Rating')
								@if($fData == 3 || $fData == 4 || $fData == 5)
									<li role="listitem">
										<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$key}}" data-id="{{$fData}}" @if(in_array($fData,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
										<span>
											@if($fData == 3)
												<img src="https://staging.maxaroma.com/public/images/star-3.png">
											@elseif($fData == 4)
												<img src="https://staging.maxaroma.com/public/images/star-4.png">
											@elseif($fData == 5)
												<img src="https://staging.maxaroma.com/public/images/star-5.png">
											@endif
										</span>
									</a></li>
								@endif
							@else
								<li role="listitem">
									<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$key}}" data-id="{{$fData}}" @if(in_array($fData,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
										<span>{{$fData}}</span>
									</a>
								</li>
							@endif
							
                        @endforeach
                    @endif		
                </ul>
            </div>
        </div>
    </div>
</div>
