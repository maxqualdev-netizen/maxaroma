@php 
$mid = (array_key_exists('mid',$attr) && $attr['mid'] != '') ? 'id="'.$attr["mid"].'"' : ''; 
$ariaLabel = (array_key_exists('aria-label', $attr) && $attr['aria-label'] != '') ? 'aria-label="'.$attr['aria-label'].'"' : '';
$ariaLive = (array_key_exists('aria-live', $attr) && $attr['aria-live'] != '') ? 'aria-live="'.$attr['aria-live'].'"' : '';
$ariaDescribedby = (array_key_exists('aria-describedby', $attr) && $attr['aria-describedby'] != '') ? 'aria-describedby="'.$attr['aria-describedby'].'"' : '';
@endphp

<div class="{{$attr['classname']}}" role="alert" {!! $mid !!} {!! $ariaLabel !!} {!! $ariaLive !!} {!! $ariaDescribedby !!} style="@if(isset($attr['style'])){{$attr['style']}}@endif">
    {!! $attr['message'] !!}
</div>
