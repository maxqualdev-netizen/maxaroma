@extends('layouts.app')

@section('content')
	<main>
		<section class="col-md-12 pb-4">
	    	<div class="container error404">
				<div class="col-md-12 text-center pt-5"><h3>Error : Page Not Found</h3></div>
				<div class="col-md-12 text-center pt-2"><h2>404</h2></div>
				<div class="col-md-12 text-center pt-2"><p>We are sorry!</p></div>
				<div class="col-md-12 text-center pt-2"><p>Sorry we did not find the page you were looking for. The link might be out-dated or might not be proper.</p></div>
				<div class="col-md-12 text-center pt-2"><p class="errmsg">Sorry, requested page not found, please contact Administrator.</p></div>
				<div class="col-md-12 text-center pt-2">
					<a href="{{ config('global.SITE_URL') }}" class="btn btn-primary">Home</a>	
					<a href="{{ config('global.SITE_URL') }}login.html" class="btn btn-primary">Login</a>	
				</div>
			</div>
		</section>
	</main>
@endsection
