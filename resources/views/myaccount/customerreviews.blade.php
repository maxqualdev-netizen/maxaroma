@extends('layouts.app')
@section('content')
<main>
	<div class="myact">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="mainhd">
						<h1>Customers Reviews</h1>
					</div>
				</div>
			</div>
			@if(count($Customer_Reviews) > 0)
				@foreach($Customer_Reviews as $customer_review_key => $customer_review_value)
				<div class="mt-4 mb-2 border-bottom">
					<h3>Review for <a href="{{$customer_review_value['product_url']}}"><strong>{{$customer_review_value['product_name']}}</strong></a></h3>
          			<p class="pt5 pb10">{{$customer_review_value['user_review']}}</p>
          			<x-ratingstar :starRating="$customer_review_value['product_star']" />
          			<p class="col-xs-12 pt5 pb5 bot-review">
          				<i>Written by</i> <strong>{{$customer_review_value['first_name']}}</strong>
          			</p>
      			</div>
			    @endforeach
				<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
					{!! $Customer_Reviews->onEachSide(0)->links('myaccount.pagination') !!}
				</div>
		    @else
		    	<div class="row">
		    		<div class="col-md-12 mt20 mb20 clear errmsg tac text-center">No Reviews</div>
		    	</div>
		    @endif
		</div>
	</div>
</main>	

@endsection