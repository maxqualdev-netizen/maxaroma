<label for="{{$attr['name']}}" id="{{$attr['id']}}-label">{{$attr['label']}}</label>
<select 
    id="{{$attr['id']}}" 
    class="{{$attr['classname']}} {{ $errors->has($attr['name']) ? ' is-invalid' : '' }}" 
    name="{{$attr['name']}}"
    role="listbox"
    aria-labelledby="{{$attr['id']}}-label"
    @if(isset($attr['aria-required']) && $attr['aria-required'] == 'true') aria-required="true" @endif
    @if(isset($attr['aria-label']) && $attr['aria-label'] != '') aria-label="{{ $attr['aria-label'] }}" @endif
>
    @if($attr['firstopt'] != '')
        <option value="">{{$attr['firstopt']}}</option>	
    @endif
    @foreach($attr['data'] as $key => $sdata)
        @if(isset($attr['datafield']) && $attr['datafield'] != '')
            <option value="{{$sdata[$attr['datafield']]}}" 
                @if($attr['selected'] == $sdata[$attr['datafield']])
                    selected
                @endif		
            >{{$sdata[$attr['datafield']]}}</option>	
        @else	
            <option value="{{$key}}" 
                @if($attr['selected'] == $key)
                    selected
                @endif		
            >{{$sdata}}</option>
        @endif
    @endforeach
</select>