@extends('layouts.app')
@section('content')
<style type="text/css">
   .scroll-filter-div {
      max-height: 150px;
      overflow-y: scroll;
   }
</style>
<main>
   <section class="breadcrumb">
      <div class="container">
         <a href="{{config('global.SITE_URL')}}">Home</a>
         <span class="active">{{$Bredcrum}}</span> 
      </div>
   </section>
   <section class="cat_banner_new">
      <div class="container">
         <div class="over">
            <h1>{{$PageTitle}} {{'('.$TotalCatProds.')'}}</h1>
         </div>
      </div>
   </section>
   <section class="listing_mid">
      <div class="container">
         <div class="listing_left">
            <div class="fra_man">
               <a href="javascript:void(0)" class="fra_toggle d-lg-none btn btn-primary d-block">Category list</a>
               <div class="fra_link">
                  <div><a href="javascript:void(0);" class="arrow filter_main" data-filter-key="special" data-filter-value="top_seller"><strong>Bestsellers</strong></a></div>
                  <div class="pt-2"><a href="javascript:void(0);" class="arrow filter_main" data-filter-key="special" data-filter-value="new_arrival"><strong>Just Arrived</strong></a></div>
                  @if(count($Categories) > 0)
					<div class="pt-2">	  
						<a href="javascript:void(0);" class="arrow filter_main" data-filter-key="category_id" data-filter-value="{{ $Categories[0]['category_id'] }}"><strong>{{$Categories[0]['category_name']}}</strong></a>
						<div class="pt-1">
						@foreach($Categories as $key => $Cat)
							@if($key != 0)
								<a href="{{ $Cat['link'] }}" class="arrow" data-filter-key="category_id" data-filter-value="{{ $Cat['category_id'] }}">{{$Cat['category_name']}}</a>
							@endif
						@endforeach
						</div>
					</div>
                  @endif

                  {{-- @if(count($Filters) > 0)
                  <div class="filter_mid">
                     @foreach($Filters as $Filter)
                           @foreach($Filter as $FilterKey => $FilterValue)
                           @if($FilterKey == 'Size' || $FilterKey == 'FragranceFamily')
                              <div class="filter_acrd filter_acrd_act">
                                 <div class="filter_acrd_hd">
                                    <span class="title fra_link"><strong>{{ $FilterValue['Attr']['title'] }}</strong></span> 
                                    <a class="reset_link" data-filter="categories" >Reset</a>
                                 </div>
                                 <!-- <a href="javascript:void(0);" class="arrow"><strong>{{ $FilterValue['Attr']['title'] }}</strong></a> -->
                                 @if(count($FilterValue['Data']) > 0)
                                    <div class="filter_acrd_con">
                                       <div class="inner">
                                          <div class="scroll">
                                             <ul id="{{ $FilterValue['Attr']['id'] }}">
                                                @foreach($FilterValue['Data'] as $DataKey => $DataValue)
                                                <li><a href="javascript:void(0);" class="filter_main" data-filter-key="{{ $FilterValue['Attr']['id'] }}" data-filter-value="{{ $DataValue }}">{{ $DataValue }}</a></li>
                                                @endforeach
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 @endif
                              </div>
                           @endif
                           @endforeach
                     @endforeach
                  </div>
                  @endif --}}


                  @if(count($Filters) > 0)
                  <div class="pt-2 mt-1">
                     @foreach($Filters as $Filter)
                           @foreach($Filter as $FilterKey => $FilterValue)
                           @if($FilterKey == 'Size' || $FilterKey == 'FragranceFamily')
                                 <a href="javascript:void(0);" class="arrow"><strong>{{ $FilterValue['Attr']['title'] }}</strong></a>
                                 <div class="pt-1 scroll-filter-div">
                                 @if(count($FilterValue['Data']) > 0)
                                    @foreach($FilterValue['Data'] as $DataKey => $DataValue)
                                    <a href="javascript:void(0);" class="filter_main" data-filter-key="{{ $FilterValue['Attr']['id'] }}" data-filter-value="{{ $DataValue }}">{{ $DataValue }}</a>
                                    @endforeach
                                 @endif
                                 </div>
                           @endif
                           @endforeach
                     @endforeach
                  </div>
                  @endif


                  {{-- @if(count($sizeFilter) > 0)
                  <div class="pt-1">
                     <a href="javascript:void(0);" class="arrow"><strong>Size</strong></a>
                     <div class="pt-1">
                        @foreach($sizeFilter as $size_key => $size_value)
                        <a href="javascript:void(0);" class="filter" data-filter-key="size" data-filter-value="{{ $size_value }}">{{ $size_value }}</a>
                        @endforeach
                     </div>
                  </div>
                  @endif 
                  <div class="separator"></div>
                  @if(count($familyFilter) > 0)
                  <div class="">
                     <a href="#" class="arrow"><strong>Shop by Fragrance Family</strong></a>
                     <div class="pt-1">
                        @foreach($familyFilter as $family_key => $family_value)
                        <a href="javascript:void(0);" class="filter_main" data-filter-key="size" data-filter-value="{{ $family_value }}">{{ ucfirst($family_value) }}</a>
                        @endforeach
                     </div>
                  </div>
                  <div class="separator"></div>
                  @endif --}}
               </div>
            </div>
         </div>
         <div class="listing_right">
            <div class="pb-2 text-right">
               <div class="select-auto">
                  <select class="select" id="sort_by">
                     <option>Sort By</option>
                     <option value="priceLH">Price Low to High</option>
                     <option value="priceHL">Price High to Low</option>
                     <option value="priceAZ">A-Z</option>
                     <option value="priceZA">Z-A</option>
                  </select>
                  <input type="hidden" id="sortselect" value=""/>
               </div>
               <!-- <div class="select-auto">
                  <select class="select" id="sort_by">
                     <option>Sort By</option>
                     <option>Price Low to High</option>
                     <option>Price High to Low</option>
                     <option>A-Z</option>
                     <option>Z-A</option>
                  </select>
               </div> -->
            </div>
            <div class="pb-2">
               <ul class="listing_grid" data-load='0'>
                  @foreach($Products as $key => $Product)
                     <li><x-productbox :prodData="$Product"/></li>
                  @endforeach 
               </ul>
			   <span class="list-more" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>
            </div>
			{{--
            <div class="pb-4 mb-2" id="loadmore" style="@if($TotalProducts <= 12) display:none @endif">
               <a class="list-more d-block" data-page='1'> Load More... </a>
            </div>--}}
            <div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif">No Record Found</div>
            <!-- <div class="pb-2"><a class="list-more d-block"> Load More... </a></div> -->
         </div>
         <div class="clearfix"></div>
      </div>
   </section>
</main>

@endsection