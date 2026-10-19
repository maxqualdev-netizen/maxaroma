
@extends('layouts.app')
@section('content')
    <main>

        <section class="brand-expore-coll">
          <div class="container-fluid">
              <div class="brand_banner d-flex justify-content-center">
                  @if($res->banner_image!='')    
                  <img class="d-none d-md-block" src="{{$res->banner_image}}" width="1667" height="220" alt="" />
                  @endif
                  @if($res->mob_image!='')  
                  <img class="d-md-none" src="{{$res->mob_image}}" width="1920" height="650" alt="" />
                  @endif
                  <!-- <h1 class="heading-1"><span>Best-seller Trio Bundles</span></h1> -->
              </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-12 text-center">
                 <div class="brand-phd brand-phd2">
                    <h1>{{$res->title}}</h1>
                 </div>
              </div>
              <div class="col-12 pt-3 pb-3">
                 <div class="brand-ex-list">
                    <ul class="brand-page-listul" id="brand-history-grid" data-load='0'>
                      @if(count($Products) > 0)
                      @foreach($Products as $key => $Product)
                      <li class="brand-page-list">
                        <x-productbox :prodData="$Product"/>
                      </li>
                      @endforeach
                      @endif
                      
                    </ul>
					<span class="list-more" data-brand-id="{{ $res->id }}" data-page='1' data-total="{{$TotalProducts}}" style="display:none"></span>
                 </div> 
              </div>
			  {{--
              <div class="col-12 pb-2" id="loadmore" style="@if($TotalProducts <= 15)display:none @endif">
                <a class="list-more d-block" data-page="1" data-brand-id="{{ $res->id }}"> Load More... </a>
              </div>
			  --}}
              <div class="errmsg text-center col-12 pb-5 mb-3" id="noprod" style="@if($TotalProducts > 0) display:none @endif">Sorry, no products found.</div>
            </div>
          </div>
        </section>

        @if($res->video_show=='Yes')   
        <section class="brand-video-sec pb-md-5 pt-md-5 pb-3 pt-3">
          <div class="container">
             <div class="row row10">
                @if($cntYoutubeLink == 1)
                <div class="col-sm-12 pb-2 pt-2">
                    <iframe class="embed-responsive-item" src="{{$youtubeLinks[0]}}" height="100%" width="100%"></iframe>
                </div>
                @else
                  @if(isset($youtubeLinks[0]) && $youtubeLinks[0] != '')
                  <div class="col-sm-6 pb-2 pt-2">
                      <iframe class="embed-responsive-item" src="{{$youtubeLinks[0]}}" height="100%" width="100%"></iframe>
                  </div>
                  @endif
                  @if(isset($youtubeLinks[1]) && $youtubeLinks[1] != '')
                  <div class="col-sm-6 pb-2 pt-2">
                      <iframe class="embed-responsive-item" src="{{$youtubeLinks[1]}}" height="100%" width="100%"></iframe>
                  </div>
                  @endif
                @endif
             </div>
          </div>
        </section>
        @endif
       <!-- Content Part End Here -->
    </main>
@endsection