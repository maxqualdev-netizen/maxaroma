@if($filterAttr['title'] == 'Category')   
    <ul class="cf-list" id="{{$filterAttr['id']}}">
        @foreach($filterData as $key => $fData)
            <li>
                <a href="javascript:void(0);" data-name="{{$fData['label']}}" class="cf-link @if($fData['selected'] == 'Yes') cf-active @endif " id="cat-{{$fData['value']}}" data-id="{{$fData['data_value']}}" @if(isset($fData['is_gender']))data-type="{{$fData['is_gender']}}"@endif @if(isset($fData['is_eightml']))data-type="{{trim($fData['is_eightml'])}}"@endif>
                    {{$fData['label']}}
                </a>
            </li>
        @endforeach
    </ul>
@elseif($filterAttr['title'] == 'Deals') 
<div class="deal-src-f">
    <label class="switch active mb-2 input-fcs" id="insurance">
        <input type="checkbox" id="chksold">
        <span class="slider round"></span>
        </label>
    Hide sold out
    <br>
    <input type="text" id="searchkey" name="searchkey" value="" placeholder="Search By UPC" class="form-control">
    <a href="javascript:void(0);" class="btn btn-primary" id="btnupcsearch">Search</a>
</div>     
@else
    {{-- <div class="filter_acrd filter_acrd_act"> --}}        
        <div class="filter_acrd" @if(isset($filterAttr['list_from']) && $filterAttr['list_from'] == 'Brand') style="display: none;" @endif> 
        <div class="filter_acrd_hd" @if(isset($filterAttr['id']) && $filterAttr['id'] == 'brands') id="brand_filter" @endif >
            <span class="title">{{$filterAttr['title']}}</span> 
            <span class="fdown-icon">
                <svg class="sv_farrow" aria-hidden="true" role="img" width="20" height="20">
                    <use href="#sv_farrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_farrow"></use>
                </svg>
            </span>
            <a class="reset_link" id="reset-{{$filterAttr['id']}}" data-filter="{{$filterAttr['id']}}" >Reset</a>
        </div>
        <div class="filter_acrd_con" style="display: none;">
            <div class="inner">
                @if(isset($filterAttr['id']) && $filterAttr['id'] == 'brands')
                    <div class="filter-search" id="filter_brand_search" style="">
				        <input type="search" id="filterbrand" class="form-control" id="" placeholder="Search Brands" autocomplete="off">
				        <svg class="sv-search vam" aria-hidden="true" role="img" width="14.21" height="15" id="sidesearch">
					       <use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
				        </svg>
			         </div>
                
                    <div class="scroll">
                        <ul class="filter_checkbox" id="{{$filterAttr['id']}}">
                            @foreach($filterData as $key => $BrnadAlpha)
                                @foreach($BrnadAlpha as $bkey => $Brand)
                                    @if(isset($Brand['ID']))
                                        <li><a href="#" data-id="{{$Brand['ID']}}" ><span>{{$Brand['Name']}}</span></a></li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="scroll">
                        <ul class="filter_checkbox" id="{{$filterAttr['id']}}">
                            @foreach($filterData as $key => $fData)
                                @if(isset($filterAttr['id']) && ($filterAttr['id'] == 'size' || $filterAttr['id'] == 'price'))
                                    <li><a href="#" data-id="{{$fData['value']}}" data-min="{{$fData['min']}}" data-max="{{$fData['max']}}" ><span>{{$fData['label']}}</span></a></li>
                                @else
                                    <li><a href="#" data-id="{{$fData['value']}}" ><span>{{$fData['label']}}</span></a></li>
                                @endif
                            @endforeach		
                        </ul>
                    </div>
                @endif
            </div>    
        </div>    
    </div>    
@endif
