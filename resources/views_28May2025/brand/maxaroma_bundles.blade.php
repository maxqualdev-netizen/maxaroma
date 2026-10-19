@extends('layouts.app')
@section('content')
<main>
    <!-- Content Part Start Here -->
    <div class="influencer-banner">
        @if(isset($mainBrandLanding[0]['mega_menu_image']) &&  $mainBrandLanding[0]['mega_menu_image']!='')
            <img src="{{$mainBrandLanding[0]['mega_menu_image']}}" width="1920" height="317" class="d-none d-md-block">
            <img src="{{$mainBrandLanding[0]['mega_menu_image']}}" width="767" height="300" class="d-md-none" alt="fragrances">
        @endif
        
        <div class="banner-inner">
            <h2>{{Str::upper($mainBrandLanding[0]['title'])}}</h2>
        </div>
    </div>
    <section class="breadcrumb">
        <div class="container">
            <a href="#">Home</a>
            <span class="active">{{$mainBrandLanding[0]['title']}}</span> 
        </div>
    </section>

    <section class="influencer-box mb-3">

        <div class="container">
            <ul>
                @foreach($brandlanding as $key => $brand)
                <li>
                    <div class="influencer-inner">
                        <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $brand['banner_link']) }}"><img src="{{$brand['banner_image']}}" width="459" height="456" /></a>
                        <div class="influencer-bg">
                            <div class="influencer-text">
                                <p>{{$brand['title']}}</p>
                                <a href="{{ str_replace('{$Site_URL}', config('global.SITE_URL'), $brand['banner_link']) }}" class="btn btn-primary" target="_blank">Shop Now</a>
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