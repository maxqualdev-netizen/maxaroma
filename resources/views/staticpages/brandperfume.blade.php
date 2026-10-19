@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="All Brands Main Content">
    <!-- Content Part Start Here -->
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{url('/')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">All Brands</span>
        </div>
    </section>

    <div class="container">
        <div class="static-inner brandpage-main">
            <div class="row">
                <div class="col-12">
                    <div class="mainhd">
                        <h1 id="brand-perfume-title">Brand Name Perfumes</h1>
                    </div>
                </div>
            </div>

            <div class="brandpage" role="region" aria-labelledby="brand-perfume-title">
                <div class="text-center"><strong class="color-blk">Choose from our selection of designer brands below</strong></div>
                <div class="all-brands">
                    <div id="alp-sticky-anchor"></div>
                    <div></div><div></div>
                    <!-- <div id="alphabets_brands" class="alphabets1 mt-3 mb-3 text-center" role="navigation" aria-label="Brand Alphabet Navigation">
                        @foreach(range('a','z') as $letter)
                            <a class="scroll @if($letter=='a')active @endif" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('{{$letter}}')" aria-label="Jump to brands starting with {{ strtoupper($letter) }}">{{ strtoupper($letter) }}</a>
                        @endforeach
                        <a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('ot')" aria-label="Jump to brands starting with #">#</a>
                        <div class="clearfix"></div>
                    </div> -->

					<div id="alphabets_brands" class="alphabets1 mt-3 mb-3 text-center" role="navigation" aria-label="Brand Alphabet Navigation">
						<a class="scroll active" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('a')" aria-label="Jump to brands starting with A">A</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('b')" aria-label="Jump to brands starting with B">B</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('c')" aria-label="Jump to brands starting with C">C</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('d')" aria-label="Jump to brands starting with D">D</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('e')" aria-label="Jump to brands starting with E">E</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('f')" aria-label="Jump to brands starting with F">F</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('g')" aria-label="Jump to brands starting with G">G</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('h')" aria-label="Jump to brands starting with H">H</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('i')" aria-label="Jump to brands starting with I">I</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('j')" aria-label="Jump to brands starting with J">J</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('k')" aria-label="Jump to brands starting with K">K</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('l')" aria-label="Jump to brands starting with L">L</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('m')" aria-label="Jump to brands starting with M">M</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('n')" aria-label="Jump to brands starting with N">N</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('o')" aria-label="Jump to brands starting with O">O</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('p')" aria-label="Jump to brands starting with P">P</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('q')" aria-label="Jump to brands starting with Q">Q</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('r')" aria-label="Jump to brands starting with R">R</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('s')" aria-label="Jump to brands starting with S">S</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('t')" aria-label="Jump to brands starting with T">T</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('u')" aria-label="Jump to brands starting with U">U</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('v')" aria-label="Jump to brands starting with V">V</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('w')" aria-label="Jump to brands starting with W">W</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('x')" aria-label="Jump to brands starting with X">X</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('y')" aria-label="Jump to brands starting with Y">Y</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('z')" aria-label="Jump to brands starting with Z">Z</a>
						<a class="scroll" data-speed="2000" href="javascript:void(0)" onclick="animatebrand('ot')" aria-label="Jump to brands starting with #">#</a>
						<div class="clearfix"></div>
					</div>

                    @foreach($BrandsList as $key => $BrandAlfa)
                    <div class="mb-4 clearfix" role="region" aria-label="Brands starting with {{ $key }}">
                        <div class="alpha-bot-box">
                            <div class="alpha-type border-bottom">
                                <a href="#" id="@if($key != '#'){{strtolower($key)}}@else{{'ot'}}@endif" aria-label="Brands starting with {{ $key }}">{{ $key }}</a>
                            </div>
                            <div class="mt-2">
                                <div class="alpha-cat">
                                    <ul role="list" aria-label="Brand list for {{ $key }}">
                                        @foreach($BrandAlfa as $Brand)
                                        <li role="listitem">
                                            <a href="{{$Brand['Link']}}" aria-label="View {{ $Brand['Name'] }} brand">{{ $Brand['Name'] }}</a>
                                        </li>
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