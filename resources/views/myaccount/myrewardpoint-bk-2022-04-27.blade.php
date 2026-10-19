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
				   <div class="myact_hd">
				      <svg class="sv_reward_points" aria-hidden="true" role="img">
				         <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
				      </svg>
				      <h2>My Reward Points</h2>
				   </div>
				   <div class="myre_points">
				      <div class="you_points text-center">
				         <svg class="sv_reward_points" aria-hidden="true" role="img">
				            <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
				         </svg>
				         <h2>You have {{ $reward_point }} points in your account</h2>
				         <p>Minimum redeem points must be {{ $maximum_reward_point }}. <!-- <br />You are 10 points away to get the $5 --></p>
				      </div>
				      <!-- <div class="help_op">
				         <h4>Need help with these options?</h4>
				         <div class="field required">
				            <form>
				               <label for="you_message">Your message</label>
				               <textarea id="you_message" rows="4" cols="50" type="text" placeholder="Your message" class="form-control"></textarea>
				               <div class="mt-2"><a href="#" class="btn btn-primary">Submit</a></div>
				            </form>
				         </div>
				      </div>
				      <div id="accordion" class="rewa_points">
				         <h4 class="accordion_title">How you can avail points in your next order.</h4>
				         <div class="accordion_content">
				            <p>Lorem Ipsum</p>
				         </div>
				      </div> -->
				   </div>
				</div>
			</div>
		</div>
	</div>
</main>	

@endsection