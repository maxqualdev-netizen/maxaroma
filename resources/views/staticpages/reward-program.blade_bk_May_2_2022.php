@extends('layouts.app')
@section('content')
<main>
	<section class="breadcrumb">
		<div class="container">
			<a href="#">Home</a>
			<span class="active">Reward Point Program</span>
		</div>
	</section>
	<div class="reward-page">
		@if(count($RewardProgram) > 0)
			@foreach($RewardProgram as $key => $RewardProgram)
				@if($RewardProgram->section_id == 1)
					<section class="reward-wel">
						<img class="d-none d-md-block" src="{{config('global.SITE_URL').'/images/'.$RewardProgram->image_name}}" width="1965" height="650">
						<img class="d-md-none" src="{{config('global.SITE_URL').'/images/'.$RewardProgram->mobile_image_name}}" width="768" height="550">
						<div class="rewards-weltext">
							@if($RewardProgram->section_title != '')
								<h1 class="Rewardsection">{{$RewardProgram->section_title}} </font></h1>
							@endif
							@if($RewardProgram->value1 != '')
								<p class="RewardData">{{$RewardProgram->value1}}</font></p>
							@endif
							@if($RewardProgram->button_text != '')
								<a href="{{$RewardProgram->button_link}}" class="btn btn-primary">{{$RewardProgram->button_text}}</a>
							@endif
						</div>
					</section>
				@endif
				
			<div class="container">
			@if($RewardProgram->section_id == 2)
				<section class="ways-earn rewards-rbox">
					<div class="row">
						@if($RewardProgram->section_title != '')
						<div class="col-12">
							<h3 class="reward-hd">{{$RewardProgram->section_title}}</h3>
						</div>
						@endif
						@if($RewardProgram->value1 != '')
						<div class="col-12">
							{!! $RewardProgram->value1 !!}
						</div>
						@endif
					</div>
				</section>
			@endif
			
			@if($RewardProgram->section_id == 3)
				<section class="refer-f rewards-rbox">
					<div class="row">
						<div class="col-12">
							@if($RewardProgram->section_title != '')
							<h3 class="reward-hd">{{$RewardProgram->section_title}}</h3>
							@endif
						</div>
						<div class="col-12">
							@if($RewardProgram->value1 != '')<p>{{$RewardProgram->value1}}</p>@endif
							@if($RewardProgram->value2 != '')<p>{{$RewardProgram->value2}}</p>@endif
						</div>
						<div class="col-12">
							<div class="refer-input">
								<input type="search" class="form-control" id="keyword" placeholder="Your email">
								@if($RewardProgram->button_text != '')
								<a href="#" class="btn btn-primary ml-1">{{$RewardProgram->button_text}}</a>
								@endif							
							</div>
						</div>
					</div>
				</section>
			@endif
			
			@if($RewardProgram->section_id == 4)
				<section class="rewards-table">
					@if($RewardProgram->section_title != '')
						<p class="mb-4">{{$RewardProgram->section_title}}</p>
					@endif
					@if($RewardProgram->value1 != '')
					<div id="rewards-t">
						 {!! $RewardProgram->value1 !!}
					</div>
					@endif
				</section>
			@endif
			
			@if($RewardProgram->section_id == 5)
				<section class="rewards-gift">
					<div class="row">
						<div class="col-12 pb-2">
							@if($RewardProgram->section_title != '')
								<h3 class="reward-hd">{{$RewardProgram->section_title}}</h3>
							@endif
						</div>
						@if(count($subArray) > 0)
						<div class="col-12">
							<div class="row">
								@foreach($subArray as $key => $subArray)
								<div class="col-lg-6 pr-lg-4">
									<div class="row">
										<div class="col-12">
											@if($subArray->section_header != '')
												<h4>{{$subArray->section_header}}</h4>
											@endif
										</div>
										<div class="col-6 pb-3">
											<div class="points-box">
												<div class="points-img dollar-box">
													{{$subArray->value1}}
												</div>
												<div class="points-text  pt-3">
													<h5>@if($subArray->value1 != ''){{$subArray->value1}} Off @endif<span>{{$subArray->points}} points</span></h5>
												</div>
											</div>
										</div>
										<div class="col-6 pb-3">
											<div class="points-box">
												<div class="points-img">
													@if($subArray->image_name != '')
													<img src="{{config('global.SITE_URL').'/images/'.$subArray->image_name}}" alt="Fragrance Du Bois London Spice" width="185" height="185">@endif
												</div>
												<div class="points-text pt-3">
													<h5>{{$subArray->value2}} <span>{{$subArray->points}} points</span></h5>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
						@endif
					</div>
				</section>
			@endif
		</div>
		@endforeach
		@endif
	</div>
	<!-- Content Part End Here -->
</main>
@endsection
