<div class="filter_acrd filter_acrd_act" role="region" aria-labelledby="filter-{{$filterAttr['id']}}-title">
    <div class="filter_acrd_hd">
        <span class="title" id="filter-{{$filterAttr['id']}}-title">{{$filterAttr['title']}}</span> 
        <a class="reset_link" id="reset-{{$filterAttr['id']}}" data-filter="{{$filterAttr['id']}}" role="button" tabindex="0" aria-label="Reset {{ $filterAttr['title'] }} filter">Reset</a>
    </div>
    <div class="filter_acrd_con">
        <div class="inner">
            <div class="scroll">
                <ul class="filter_checkbox" id="{{$filterAttr['id']}}" role="list" aria-label="{{ $filterAttr['title'] }} options">
                    @if(isset($filterAttr['filterval']) && $filterAttr['filterval'] == 'key')
                        @foreach($filterData as $key => $fData)
                            <li role="listitem">
                                <a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$key}}" data-id="{{$key}}" @if(in_array($key,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
                                    <span>{{$fData}}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        @foreach($filterData as $key => $fData)
                            <li role="listitem">
                                <a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$key}}" data-id="{{$fData}}" @if(in_array($fData,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $fData }}">
                                    <span>{{$fData}}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif		
                </ul>
            </div>
        </div>
    </div>
</div>