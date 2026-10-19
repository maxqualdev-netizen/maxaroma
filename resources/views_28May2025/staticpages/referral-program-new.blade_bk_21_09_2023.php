@extends('layouts.app')
@section('content')
<main>
	<section class="breadcrumb">
		<div class="container">
			<a href="{{config('global.SITE_URL')}}">Home</a>
			<span class="active">{{$PageTitle}}</span>
		</div>
	</section>
	<div class="referral-program-page pb-5">

		<div class="container">
			<div class="refer-friends">
				<div class="row">
					<div class="col-12">
                        <div class="yotpo-widget-instance" data-yotpo-instance-id="401616"></div>
					</div>
				</div>
			</div>
			
			<!-- FAQ's Section -->
			<div class="row">&nbsp;</div>
			<div class="row">&nbsp;</div>
			@if(count($PData) > 0)
			<div id="accordion" class="faq-page static-accordion">				
				<div>
					<h1>FAQ's</h1>
				</div>
				
				<div class="pb-3">
					@foreach($PData as $key => $Data)
					<h4 class="accordion_title">Q. {!! stripslashes(str_ireplace(array("\r","\n",'\r','\n'),'',$Data->question)) !!} <svg class="sv-down-arrow vam" id="mainsearch" aria-hidden="true" role="img" width="24" height="24"> <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use> </svg></h4>
					<div class="accordion_content">
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
