@if(count($bsvg)>0)
	@if(isset($link) && $link != '')
		<a href="{{$link}}" title="{{$bsvg['title']}}" class="{{$classname}}"> 
			<svg class="{{$bsvg['classname']}}" aria-hidden="true" role="img" width="{{$bsvg['width']}}" height="{{$bsvg['height']}}">
				<use href="#{{$bsvg['symbol']}}" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#{{$bsvg['symbol']}}"></use>
			</svg> {{$btntext}}
		</a>
	@endif
@elseif(isset($link) && $link != '')
	<a href="{{$link}}" class="{{$classname}}" id="{{$bid}}"> 
		{{$btntext}}
	</a>
@else	
	<button type="{{$btype}}" name="{{$name}}" class="{{$classname}}" id="{{$bid}}" >{{$btntext}}</button>
@endif
