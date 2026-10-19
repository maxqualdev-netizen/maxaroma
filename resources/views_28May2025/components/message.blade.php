@php 
$mid = (array_key_exists('mid',$attr) && $attr['mid'] != '') ? 'id="'.$attr["mid"].'"' : ''; 
@endphp

<div class="{{$attr['classname']}}" role="alert" {!! $mid !!} style="@if(isset($attr['style'])){{$attr['style']}}@endif">{{ $attr['message'] }}</div>