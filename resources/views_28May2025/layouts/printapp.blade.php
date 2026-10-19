<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Responsive Metatags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		<!-- Responsive Metatags -->
		<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
        <title>@if(isset($meta_title) && $meta_title!=''){{$meta_title}}@else{{config('global.META_TITLE')}}@endif</title>
		<meta name="Keywords" data-type="text" content="@if(isset($meta_keywords) && $meta_keywords!=''){{$meta_keywords}}@else{{config('global.META_KEYWORDS')}}@endif">
		<meta name="Description" data-type="text" content="@if(isset($meta_description) && $meta_description!=''){{$meta_description}}@else{{config('global.META_DESCRIPTION')}}@endif">
        <!-- Favicon Icon -->
		<link rel="icon" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" type="image/x-icon" />
		<link rel="icon" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" type="ico" />
		<link rel="SHORTCUT ICON" href="{{ config('global.SITE_IMAGES').'favicon.ico' }}" />
		<!-- Favicon Icon -->

		<!-- Stylesheet File Start -->
		@if(config('global.ENV') != 'Dev')
		<link rel="stylesheet" type="text/css" media="all" href="{{ config('global.SITE_STYLE')}}all.css">
		@endif
		@php 
			$FilePath = config('global.SITE_URL').'public'; 
			$ConfigMsg = json_encode(config('message'));
		@endphp
		@include('css.indexcss',(isset($CSSFILES)?['CSSFILES' => $CSSFILES]:['CSSFILES' =>[]]))
    </head>
    <body>
		@yield('content')
		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		@if(config('global.ENV') != 'Dev')
		<script src="{{config('global.SITE_JS')}}all.js"></script>
		@endif
		 
		@include('js.indexjs',(isset($JSFILES)?['JSFILES' => $JSFILES]:['JSFILES' =>[]]))
		@include('layouts.svg')
    </body>
</html>	