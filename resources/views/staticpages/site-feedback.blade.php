
@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Site Feedback Main Content">
    <section class="breadcrumb" role="navigation" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{url('/')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">{{$PageTitle}}</span>
        </div>
    </section>
    <!-- Content Part Start Here -->
    <div class="container">
        <div class="static-inner" role="region" aria-labelledby="site-feedback-heading">
            <div class="row">
                <div class="col-12">
                    <div class="mainhd">
                        <h1 id="site-feedback-heading">{{$PageTitle_new}}</h1>
                    </div>
                </div>
            </div>
            <script type="text/javascript"> (function e(){var e=document.createElement("script");e.type="text/javascript",e.async=!0, e.src="//staticw2.yotpo.com/MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8/widget.js";var t=document.getElementsByTagName("script")[0]; t.parentNode.insertBefore(e,t)})(); </script>
            <div id='yotpo-testimonials-custom-tab' role="region" aria-label="Customer Testimonials"></div>
        </div>
    </div>
</main>
@endsection