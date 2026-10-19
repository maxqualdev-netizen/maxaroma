        
@if ($paginator->hasPages())
        <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:void(0)" class="pvs disabled" onclick="return false;">previous</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pvs active">previous</a>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a href="javascript:void(0)" class="disabled">{{ $element }}</a>
            @endif
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                      <a href="javascript:void(0)" class="act">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="nex active">next</a>
        @else
            <a href="javascript:void(0)" class="nex disabled">next</a>
        @endif
        </div>
@endif
