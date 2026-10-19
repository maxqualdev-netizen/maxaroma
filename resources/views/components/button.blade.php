@if(count($bsvg)>0)
    @if(isset($link) && $link != '')
        <a href="{{$link}}" title="{{$bsvg['title']}}" class="{{$classname}}" @if(isset($ariaLabel) && $ariaLabel != '') aria-label="{{ $ariaLabel }}" @endif role="button" @if(isset($bid) && $bid != '') id="{{$bid}}" @endif>
            <svg class="{{$bsvg['classname']}}" aria-hidden="true" role="img" width="{{$bsvg['width']}}" height="{{$bsvg['height']}}">
                <use href="#{{$bsvg['symbol']}}" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#{{$bsvg['symbol']}}"></use>
            </svg> {{$btntext}}
        </a>
    @endif
@elseif(isset($link) && $link != '')
    <a href="{{$link}}" class="{{$classname}}" id="{{$bid}}" role="button" @if(isset($ariaLabel) && $ariaLabel != '') aria-label="{{ $ariaLabel }}" @endif> 
        {{$btntext}}
    </a>
@else	
    <button type="{{$btype}}" name="{{$name}}" class="{{$classname}}" id="{{$bid}}" @if(isset($ariaLabel) && $ariaLabel != '') aria-label="{{ $ariaLabel }}" @endif>
        {{$btntext}}
    </button>
@endif