@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="pageTitle">
	<section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{url('/')}}/zendesk.html" aria-label="Home">Home</a>
            <span class="active" aria-current="page">{{$PageTitle}}</span>
        </div>
    </section>
	<!-- Content Part Start Here -->
	<div class="container">
		<div class="static-inner">
			<div class="row">
				<div class="col-12">
					<div class="mainhd">
						<h2 class="h1" id="pageTitle">{{$PageTitle}}</h2>
					</div>
				</div>
			</div>
			@if(count($PData) > 0)
				<div id="accordion" class="faq-page static-accordion" role="region" aria-labelledby="faqHeading">
					<div class="pb-3">
						<h2 id="faqHeading" class="sr-only">Frequently Asked Questions</h2>
						@foreach($PData as $key => $Data)
						<h4 class="accordion_title" role="button" id="faqHeading{{$key}}" aria-expanded="false" aria-controls="accordion-content-{{$key}}" tabindex="0">
							Q. {!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$Data->question)) !!} 
							<svg class="sv-down-arrow vam" id="mainsearch" aria-hidden="true" role="img" width="24" height="24">
								<use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
							</svg>
						</h4>
						<div class="accordion_content" id="accordion-content-{{$key}}" aria-labelledby="faqHeading{{$key}}" role="region" aria-hidden="true">
							<p>
								<span class="color-blk"><strong>A.</strong></span>
								{!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$Data->answer)) !!}</p>
							@if(isset($Data->image) && $Data->image!="")
								@php
									$newimageVal = config('global.HOME_IMAGE_PATH') . stripslashes($Data->image);
									$verP = filemtime($newimageVal);
								@endphp
								<div class="text-center">
									<img src='{{ config('global.HOME_IMAGE_URL').$Data->image }}?ver={{ $verP }}' alt="{{ $Data->question }}" role="img" aria-label="{{ $Data->question }}"/>
								</div>
							@endif
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