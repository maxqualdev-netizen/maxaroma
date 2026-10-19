@extends('layouts.app')

@section('content')
	<main>
		<section class="col-md-12 pb-4">
	    	<div class="container error404">
				<div class="col-md-12 text-center pt-5"><h3>Error : Session Expired</h3></div>
				<div class="col-md-12 text-center pt-2"><p>We are sorry!</p></div>
				<div class="col-md-12 text-center pt-2"><p>Your session expired due to inactivity. Please try again.</p></div>
				<div class="col-md-12 text-center pt-2">
					<a href="{{ config('global.SITE_URL') }}" class="btn btn-primary">Home</a>	
					<a href="{{ url()->previous() }}" class="btn btn-primary">Go to previous page</a>	
				</div>
			</div>
		</section>
	</main>
@endsection
