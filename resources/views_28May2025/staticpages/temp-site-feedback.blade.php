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
						<h1>{{$PageTitle_new}}</h1>
					</div>
				</div>
			</div>

			
			
			<script type="text/javascript"> (function e(){var e=document.createElement("script");e.type="text/javascript",e.async=!0, e.src="//staticw2.yotpo.com/MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8/widget.js";var t=document.getElementsByTagName("script")[0]; t.parentNode.insertBefore(e,t)})(); </script>
			<div class="yotpo yotpo-reviews-carousel" 
 data-background-color="transparent" 
 data-mode="top_rated" 
 data-type="product" 
 data-count="9" 
 data-show-bottomline="1" 
 data-autoplay-enabled="1" 
 data-autoplay-speed="3000" 
 data-show-navigation="1">&nbsp;</div>
		</div>
	</div>

	



</main>
@endsection
