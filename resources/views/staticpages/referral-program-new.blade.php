@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="referralProgramTitle">
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{config('global.SITE_URL')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">{{$PageTitle}}</span>
        </div>
    </section>
    <div class="referral-program-page pb-5" role="region" aria-labelledby="referralProgramTitle">
        <div class="container">
            <div class="refer-friends">
                <div class="row">
                    <div class="col-12">
                        <div class="yotpo-widget-instance" data-yotpo-instance-id="401616" aria-label="Yotpo Reviews Widget"></div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ's Section -->
            <div class="row" aria-hidden="true">&nbsp;</div>
            <div class="row" aria-hidden="true">&nbsp;</div>
            @if(count($PData) > 0)
            <div id="accordion" class="faq-page static-accordion" role="region" aria-labelledby="faqHeading">
                <div>
                    <h2 class="h1" id="faqHeading">FAQ's</h2>
                </div>
                <div class="pb-3">
                    @foreach($PData as $key => $Data)
                    <h4 class="accordion_title" role="button" id="faqHeading{{$key}}" aria-expanded="false" aria-controls="accordion-content-{{$key}}" tabindex="0">
                        Q. {!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$Data->question)) !!}
                        <svg class="sv-down-arrow vam" id="mainsearch" aria-hidden="true" role="img" width="24" height="24">
                            <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
                        </svg>
                    </h4>
                    <div class="accordion_content" id="accordion-content-{{$key}}" aria-labelledby="faqHeading{{$key}}" role="region" aria-hidden="true">
                        <p><span class="color-blk"><strong>A.</strong></span> {!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$Data->answer)) !!}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div> 
    </div>
    <!-- Content Part End Here -->
</main>
@endsection