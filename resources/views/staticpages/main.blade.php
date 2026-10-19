@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-labelledby="pageTitle">
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{url('/')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">{{$PageTitle}}</span>
        </div>
    </section>
    <!-- Content Part Start Here -->
    <div class="container">
        <div class="static-inner">
            <div class="row">
                <div class="col-12">
                    <div class="mainhd">
                        <h1 id="pageTitle">{{$PageTitle}}</h1>
                    </div>
                </div>
            </div>
            {!! $PageContent !!}
        </div>
    </div>
    <!-- Content Part End Here -->
</main>
{{-- @if (Str::contains(Request::path(), 'coupons-promotional') || Str::contains(Request::path(), 'about-us') || Str::contains(Request::path(), 'authenticity_promise') )
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
@endif --}}
@endsection