@php
	// Custom search (Meili): Star Rating facet may use id rating_avg / title Rating|Star Rating
	$isStarRating = isset($filterAttr['id']) && (
		$filterAttr['id'] === 'rating_avg'
		|| ($filterAttr['title'] ?? '') === 'Rating'
		|| ($filterAttr['title'] ?? '') === 'Star Rating'
	);
	$shownStarLevels = [];

	/**
	 * Exact same Yotpo icon markup as product cards (.yotpo-icon-star / empty-star).
	 * Titles: 5, 4+, 3+ (plus after the star row for 3/4).
	 */
	$renderFilterStars = function (int $starLevel) {
		$html = '<span class="yotpo yotpo-filter-stars filter-star-rating">'
			. '<span class="yotpo-stars">';
		for ($s = 1; $s <= 5; $s++) {
			$icon = $s <= $starLevel ? 'yotpo-icon-star' : 'yotpo-icon-empty-star';
			$html .= '<span class="yotpo-icon ' . $icon . ' rating-star pull-left"></span>';
		}
		$html .= '</span>';
		if ($starLevel >= 3 && $starLevel <= 4) {
			$html .= '<span class="star-plus" aria-hidden="true">+</span>';
		}
		$html .= '</span>';
		return $html;
	};

	$starLevelFromRaw = function ($raw): int {
		$avg = is_numeric($raw) ? (float) $raw : (float) str_replace(['dot', '_', '+'], ['.', '', ''], (string) $raw);
		if ($avg >= 4.75) {
			return 5;
		}
		if ($avg >= 4.0) {
			return 4;
		}
		if ($avg >= 3.0) {
			return 3;
		}
		// Encoded bucket keys "3" / "4" / "5" (and labels "3+" / "4+") also arrive as ints.
		$asInt = (int) round($avg);
		return in_array($asInt, [3, 4, 5], true) ? $asInt : 0;
	};
@endphp
<div class="filter_acrd" role="region" aria-labelledby="filter-{{$filterAttr['id']}}-title">
    <div class="filter_acrd_hd">
		@if($isStarRating)
		<span class="title" id="filter-{{$filterAttr['id']}}-title">Star Rating</span>
		@else
        <span class="title" id="filter-{{$filterAttr['id']}}-title">{{$filterAttr['title']}}</span> 
        @endif
        <a class="reset_link" id="reset-{{$filterAttr['id']}}" data-filter="{{$filterAttr['id']}}" role="button" tabindex="0" aria-label="Reset {{ $filterAttr['title'] }} filter">Reset</a>
    </div>
    <div class="filter_acrd_con" style="display: none;">
        <div class="inner">
            <div class="scroll">
                <ul class="filter_checkbox" id="{{$filterAttr['id']}}" role="list" aria-label="{{ $isStarRating ? 'Star Rating' : $filterAttr['title'] }} options">
                    @if(isset($filterAttr['filterval']) && $filterAttr['filterval'] == 'key')
                        @foreach($filterData as $key => $fData)
							@if($isStarRating)
								@php
									// Prefer the facet key (3/4/5) when present — labels may be "4+".
									$starLevel = is_numeric($key) ? (int) $key : $starLevelFromRaw($fData);
									if ($starLevel < 3 || $starLevel > 5) {
										$starLevel = $starLevelFromRaw($key);
									}
									$starTitle = $starLevel === 5 ? '5' : $starLevel . '+';
								@endphp
								@if($starLevel >= 3 && $starLevel <= 5 && empty($shownStarLevels[$starLevel]))
									@php
										$shownStarLevels[$starLevel] = true;
										$starDataId = (string) $starLevel;
									@endphp
									<li role="listitem">
										<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$starDataId}}" data-id="{{$starDataId}}" @if(in_array($starDataId,$filterSelected) || in_array($key,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $starTitle }} star rating">
											{!! $renderFilterStars($starLevel) !!}
										</a>
									</li>
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
							@if($isStarRating)
								@php
									$starLevel = is_numeric($fData) ? (int) $fData : $starLevelFromRaw($fData);
									if ($starLevel < 3 || $starLevel > 5) {
										$starLevel = $starLevelFromRaw($key);
									}
									$starTitle = $starLevel === 5 ? '5' : $starLevel . '+';
								@endphp
								@if($starLevel >= 3 && $starLevel <= 5 && empty($shownStarLevels[$starLevel]))
									@php
										$shownStarLevels[$starLevel] = true;
										$starDataId = (string) $starLevel;
									@endphp
									<li role="listitem">
										<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$starDataId}}" data-id="{{$starDataId}}" @if(in_array($starDataId,$filterSelected) || in_array($fData,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $starTitle }} star rating">
											{!! $renderFilterStars($starLevel) !!}
										</a>
									</li>
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

					@if($isStarRating && $shownStarLevels === [])
						@foreach([5, 4, 3] as $starLevel)
							@php $starTitle = $starLevel === 5 ? '5' : $starLevel . '+'; @endphp
							<li role="listitem">
								<a href="javascript:void(0);" id="{{$filterAttr['name']}}{{$starLevel}}" data-id="{{$starLevel}}" @if(in_array((string)$starLevel,$filterSelected) || in_array($starLevel,$filterSelected)) class="active" aria-pressed="true" @else aria-pressed="false" @endif role="button" tabindex="0" aria-label="Filter by {{ $starTitle }} star rating">
									{!! $renderFilterStars($starLevel) !!}
								</a>
							</li>
						@endforeach
					@endif
                </ul>
            </div>
        </div>
    </div>
</div>
