@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="MaxAroma Bundles Main Content">
    <!-- Content Part Start Here -->
    <div class="influencer-banner" role="banner" aria-label="Brand Banner">
        @if(isset($mainBrandLanding[0]['mega_menu_image']) &&  $mainBrandLanding[0]['mega_menu_image']!='')
            <img src="{{$mainBrandLanding[0]['mega_menu_image']}}" width="1920" height="317" class="d-none d-md-block" alt="Brand Banner Image">
            <img src="{{$mainBrandLanding[0]['mega_menu_image']}}" width="767" height="300" class="d-md-none" alt="fragrances">
        @endif
        
        <div class="banner-inner">
            <h2 id="bundle-title">{{Str::upper($mainBrandLanding[0]['title'])}}</h2>
        </div>
    </div>
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="#" aria-label="Home">Home</a>
            <span class="active" aria-current="page">{{$mainBrandLanding[0]['title']}}</span> 
        </div>
    </section>

    <section class="influencer-box mb-3" role="region" aria-labelledby="bundle-title">
        <div class="container">
            <ul role="list" aria-label="Bundle List">
                @foreach($brandlanding as $key => $brand)
                <li role="listitem" aria-label="Bundle {{ $brand['title'] }}">
                    <div class="influencer-inner">
                        <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $brand['banner_link']) }}" aria-label="View details for {{ $brand['title'] }}">
                            <img src="{{$brand['banner_image']}}" width="459" height="456" alt="{{ $brand['title'] }} image" />
                        </a>
                        <div class="influencer-bg">
                            <div class="influencer-text">
                                <p>{{ $brand['title'] }}</p>
                                <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $brand['banner_link']) }}" class="btn btn-primary" target="_blank" aria-label="Shop {{ $brand['title'] }}">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </section>
</main>
@endsection