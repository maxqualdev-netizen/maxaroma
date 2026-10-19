
@if (count($manuf_prods_man) > 0 || count($manuf_prods_wom) > 0 || count($manuf_prods_uni) > 0)
<div class="d-none d-md-block ">
<div class="row pt-5 mt-2">
<div class="col-12 pb-2 mb-1 other-hd-prd">Other product by
        <strong>{{ $productDetails->vmanufacture }}</strong>
    </div>
</div>
<div class="other-prdlist">
	<div class="row row10">
    
    @if (count($manuf_prods_man) > 0)
    <div class="col-sm-6 col-xs-6 pb-2">
        <div class="ship-faq-tab thumb_bot">
            <a name="mens" href="#"></a>
            <h5>MEN</h5>
            <ul class="other-scroll">
                <input type="hidden" name="other_man_count" id="man_other_count" value="{{ count($manuf_prods_man) }}" />
                @foreach ($manuf_prods_man as $key => $value)
                @if($key < 4) <div class="li_hide_other_men" style="display:block">
                    <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
        </div>
        @else
        <div style="display:none" class="li_show_other_men_{{$key}}">
            <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name'] }}</a></li>
        </div>
        @endif
        @endforeach
        </ul>
    </div>
    @if (count($manuf_prods_man) > 4)
    <div class="f-fix button_show_other_men" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_men(this);">See More</a></div>
    <div class="f-fix button_hide_other_men" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_men(this);">See Less</a>
    </div>
    @endif
</div>
@endif

@if (count($manuf_prods_wom)>0)
<div class="col-sm-6 col-xs-6 pt15 ">
    <div class="ship-faq-tab thumb_bot">
        <a name="womens" href="#"></a>
        <h5>WOMEN</h5>
        <ul class="other-scroll">
            <input type="hidden" name="other_women_count" id="women_other_count" value="{{count($manuf_prods_wom)}}" />
            @foreach($manuf_prods_wom as $key => $value)
            @if ($key < 4) <div class="li_hide_other_women" style="display:block">
                <li class="pt-1"><a href="{{$value['brand_link']}}">{{$value['brand_name']}}</a></li>
    </div>
    @else
    <div class="li_show_other_women_{{$key}}" style="display:none">
        <li class="pt-1"><a href="{{$value['brand_link']}}">{{$value['brand_name']}}</a></li>
    </div>
    @endif
    @endforeach
    </ul>
</div>
@if (count($manuf_prods_wom) > 4)
<div class="f-fix button_show_other_women" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_women(this);">See More </a></div>
<div class="f-fix button_hide_other_women" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_women(this);">See Less </a></div>
@endif
</div>
@endif

@if(count($manuf_prods_uni)>0)
<div class="col-sm-6 col-xs-6 pt15 @if(count($manuf_prods_man)>0 && count($manuf_prods_wom)>0) clear @endif">
    <div class="ship-faq-tab thumb_bot">
        <a name="unisexs" href="#"></a>
        <h5>UNISEX</h5>
        <ul class="other-scroll">
            <input type="hidden" name="other_uni_count" id="uni_other_count" value="{{count($manuf_prods_uni)}}" />
            @foreach($manuf_prods_uni as $key => $value)
            @if($key < 4) <div class="li_hide_other_unisex" style="display:block">
                <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
    </div>
    @else
    <div style="display:none" class="li_show_other_unisex_{{$key}}">
        <li class="pt-1"><a href="{{ $value['brand_link'] }}">{{ $value['brand_name']}}</a></li>
    </div>
    @endif
    @endforeach
    </ul>
</div>
@if(count($manuf_prods_uni) > 4)
<div class="f-fix button_show_other_unisex" style="display:block"><a id="moremanu" class="more_link" href="javascript:void(0);" onclick="more_other_unisex(this);">See More </a>
</div>
<div class="f-fix button_hide_other_unisex" style="display:none"><a id="moremanu" class="more_link more-minus" href="javascript:void(0);" onclick="more_other_unisex(this);">See Less </a>
</div>
@endif
</div>
@endif
</div>
</div>
</div>
@endif