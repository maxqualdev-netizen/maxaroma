@extends('layouts.app')
<div class="ncslider-circal">
@section('content')
</div>
<style type="text/css">
.scroll-filter-div {
   max-height:150px;
   overflow-y:scroll;
}
</style>
<main>
<section class="breadcrumb">
   <div class="container">
      <!--<a href="{{config('global.SITE_URL')}}">Home</a>
      <span class="active">{{$Bredcrum}}</span>--> 
   </div>
</section>

<section class="new-cat-section">
   <div class="container">
      <div class="nc-top">
         <div class="row">
            <div class="col-md-7 col-12">
                @if(isset($MainBanner) && $MainBanner->count() > 0)
               <div class="nc-main-banner">
                  <a href="{{$MainBanner->banner_link}}" class="ncsb-link"><img src="{{$MainBanner->banner}}" width="850" height="850" alt="{{ $MainBanner->banner_title  }}" title="{{ $MainBanner->banner_title  }}"></a>
                  <div class="ncm-dtl">
                     <h3 class="nc-hd" style="color:#{{$MainBanner->font_color}} !important;">{{$MainBanner->banner_title}}</h3>
                     <div class="ncm-btn-list">
                        @if(isset($MainBannerLinks) && $MainBannerLinks->count() > 0)
                        <ul>
                           @foreach($MainBannerLinks as $bannerLinks)
                           <li><a href="{{$bannerLinks->banner_link}}" class="ncm-link">{{$bannerLinks->banner_title}}</a></li>
                           @endforeach
                        </ul>
                        @endif
                     </div>
                  </div>
               </div>
               @endif
            </div>
            <div class="col-md-5 col-12">
            @if(isset($RightBanners) && $RightBanners->count() > 0)
               <div class="nc-sub-banner row">
                @foreach($RightBanners as $rightBanner)
                  <div class="col-md-12 col-6 ncsb-col-right">
                     <a href="{{$rightBanner->banner_link}}" class="ncsb-link"><img src="{{$rightBanner->banner}}" width="605" height="350" class="d-none d-md-block" alt="{{$rightBanner->banner_title}}" title="{{$rightBanner->banner_title}}"></a>
                     <a href="{{$rightBanner->banner_link}}" class="ncsb-link"><img src="{{$rightBanner->banner}}" width="605" height="605" class="d-md-none" alt="{{$rightBanner->banner_title}}" title="{{$rightBanner->banner_title}}"></a>
                     <div class="ncsb-dtl">
                        <h3 class="nc-hd">{{$rightBanner->banner_title}}</h3>
                        <a href="{{$rightBanner->banner_link}}" class="ncsb-link">{{$rightBanner->banner_link_title}}</a>
                     </div>
                  </div>
                  @endforeach
               </div>
            @endif 
            </div>
         </div>
      </div>
      @if(isset($BottomSliders) && $BottomSliders->count() > 0)
      <div class="nc-slider">
         <div class="nc-new-sl slick-slider slick-dotted">
            @foreach($BottomSliders as $bottomSlider)
            <div class="nc-slide slick-slide">
                <a href="{{$bottomSlider->banner_link}}" class="ncslider-dtl">
                   <div class="ncslider-circal">
                      <img src="{{$bottomSlider->banner}}" width="365" height="365" alt="{{$bottomSlider->banner_title}}" title="{{$bottomSlider->banner_title}}">
                   </div>
                   <h3 class="nc-hd">{{$bottomSlider->banner_title}}</h3>
                </a>
             </div>
           @endforeach
         </div>
      </div>
      @endif 
      <div class="clearfix"></div>
   </div>
</section>
</main>

@endsection
