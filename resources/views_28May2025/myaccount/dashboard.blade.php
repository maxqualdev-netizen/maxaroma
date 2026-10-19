@extends('layouts.app')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row row10">
				<div class="col-lg-3 col-md-12 col-xs-12">
					@include('myaccount.myaccountmenu')
				</div>	
				<div class="col-lg-9 col-md-12 col-xs-12">
					<!--<img src="{{config('global.SITE_IMAGES')}}myaccount.jpg" width="980" height="1626" alt="" style="width:100%;" />-->
					<img src="{{ config('global.SPEED_SIZE_URL')}}{{$account_image}}" width="980" height="1626" alt="" style="width:100%;" />
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection
