@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" media="all" href="{{config('global.SITE_STYLE')}}myaccount.css" />
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
				<div class="col-lg-9 col-md-12 col-xs-12">
					<img src="{{config('global.SITE_IMAGES')}}myaccount.jpg" alt="" style="width:100%;" />
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection