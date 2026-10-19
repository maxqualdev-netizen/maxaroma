@extends('layouts.app')
@section('content')
<main>
	<section class="breadcrumb">
		<div class="container">
			<a href="{{url('/')}}">Home</a>
			<span class="active">{{$PageTitle}}</span>
		</div>
	</section>
	<!-- Content Part Start Here -->
	<div class="container">
		<div class="static-inner">
			<div class="row">
				<div class="col-12">
					<div class="mainhd">
						<h1>{{$PageTitle}}</h1>
					</div>
				</div>
			</div>
			
			@if(count($PData) > 0)
			<div id="accordion" class="faq-page static-accordion">
				<div class="pb-3">
					@foreach($PData as $key => $Data)
					<h4 class="accordion_title">Q. {!! $Data->question !!} <svg class="sv-down-arrow vam" id="mainsearch" aria-hidden="true" role="img" width="24" height="24"> <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use> </svg></h4>
					<div class="accordion_content">
						<p><span class="color-blk"><strong>A.</strong></span> {!! $Data->answer !!}</p>
						@if(isset($Data->image) && $Data->image!="")
					
						@php
							$newimageVal = config('global.HOME_IMAGE_PATH')  . stripslashes($Data->image);
							$verP = filemtime($newimageVal);
						@endphp 
						<div class="text-center"><img src='{{ config('global.HOME_IMAGE_URL').$Data->image }}?ver={{ $verP }}' alt={{$Data->question}} title={{$Data->question}}/></div>
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
