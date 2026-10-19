@extends('layouts.app')
@section('content')
<main>
	<!-- Content Part Start Here -->
	<section class="breadcrumb">
		<div class="container">
			<a href="{{url('/')}}">Home</a>
			<span class="active">All Brands</span>
		</div>
	</section>

	<div class="container">
		<div class="static-inner brandpage-main">
			<div class="row">
				<div class="col-12">
					<div class="mainhd">
						<h1>Brand Name Perfumes</h1>
					</div>
				</div>
			</div>

			<div class="brandpage">
				<div class="text-center"><strong class="color-blk">Choose from our selection of designer brands below</strong></div>
				<div class="all-brands">
					<div id="alp-sticky-anchor"></div>
					<div></div><div></div>
					<div id="alphabets_brands" class="alphabets1 mt-3 mb-3 text-center">
						<a class="scroll active" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('a')">A</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('b')">B</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('c')">C</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('d')">D</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('e')">E</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('f')">F</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('g')">G</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('h')">H</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('i')">I</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('j')">J</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('k')">K</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('l')">L</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('m')">M</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('n')">N</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('o')">O</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('p')">P</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('q')">Q</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('r')">R</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('s')">S</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('t')">T</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('u')">U</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('v')">V</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('w')">W</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('x')">X</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('y')">Y</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('z')">Z</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('ot')">#</a>
						<div class="clearfix"></div>
					</div>
					@foreach($BrandsList as $key => $BrandAlfa)
					<div class="mb-4 clearfix">
						<div class="alpha-bot-box">
							<div class="alpha-type border-bottom"><a href="#" id="@if($key != '#'){{strtolower($key)}}@else{{'ot'}}@endif">{{$key}}</a></div>
							<div class="mt-2">
								<div class="alpha-cat">
									<ul>
										@foreach($BrandAlfa as $Brand)
										<li><a href="{{$Brand['Link']}}">{{$Brand['Name']}}</a></li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>
					@endforeach

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		{{-- <div class="home_banner pb-4 pt-4">
			<div class="container">
				<!-- TrustBox widget - Carousel -->
				<div class="trustpilot-widget" data-locale="en-US" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="56b63b590000ff00058894fd" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="en">
					<a href="https://www.trustpilot.com/review/maxaroma.com" target="_blank" rel="noopener">Trustpilot</a>
				</div>
				<!-- End TrustBox widget -->
			</div>
		</div> --}}
	</div>
	<!-- Content Part End Here -->
</main>
@endsection
