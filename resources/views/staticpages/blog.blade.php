@extends('layouts.app')
@section('content')
<div class="blog-landingnw" role="main"  id="main-content" tabindex="-1" aria-label="Blog Main Content">
    <div class="pt-md-4 pt-3">
        <div class="mainhd">
            <h2 id="blog-page-title">@if(!empty($blog_page_title)){{$blog_page_title}}@endif</h2>
        </div>
    </div>
    @if($blog_detail_video != '' || $mobile_detail_video != '')
    <div class="blog-video" aria-label="Blog Video Section">
        @if($blog_detail_video != '')
        <div class="d-md-block d-none">
            <div class="fullscreen">
                <div class="video-full">
                    <div class="wrapper-f">
                        {!! $blog_detail_video !!}
                        <!-- <iframe src="https://player.vimeo.com/video/750453586?background=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen=""></iframe> -->
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($mobile_detail_video != '')
        <div class="d-md-none">
            <div class="fullscreen">
                <div class="video-full">
                    <div class="wrapper-f">
                        {!! $mobile_detail_video !!}
                        <!-- <iframe src="https://player.vimeo.com/video/752252382?background=1" width="640" height="640" frameborder="0" allow="autoplay; fullscreen" allowfullscreen=""></iframe> -->
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
    <div class="container" aria-labelledby="blog-page-title">
        @if(count($Blog) > 0)
            <div class="row pt-md-5 pt-4 pb-3">
                <div class="col-12">
                    <div class="row mb-md-5 mb-4" role="list" aria-label="Featured Blog Posts">
                        <?php
                        foreach ($Blog as $key => $PData) {
                            $yeardata = strtotime("" . $PData->date . "");
                            if ($PData->date !== '0000-00-00') {
                                $date = date('F d, Y', $yeardata);
                            } else {
                                $date = '';
                            }

                            if ($PData->blog_img_link != '') {
                                $blogimg_link = str_replace('{$Site_URL}', config('global.SITE_URL'), $PData->blog_img_link);
                            } else {
                                $blogimglink = 'blog/' . str_replace(" ", "-", strtolower($PData->title)) . '/' . $PData->id;
                                $blogimg_link = str_replace('{$Site_URL}', config('global.SITE_URL'), $blogimglink);
                            }
                        ?>
                            @if($PData->banner_type=='2')
                                <div class="col-md-6 col-12 mb-md-2 pb-4" role="listitem" aria-label="Blog post: {{ strip_tags($PData->title) }}">
                                    <div class="giftbox-blog text-center" tabindex="0" aria-labelledby="blog-title-{{$PData->id}}">
                                        <a href="{{$blogimg_link}}" aria-label="Read blog post: {{ strip_tags($PData->title) }}">
                                            @if($PData->blog_image!='')
                                            <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{$PData->blog_image}}" loading="lazy" width="700" height="400" alt="{{ strip_tags($PData->title) }}">
                                            @else
                                            <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/https://via.placeholder.com/700x400" loading="lazy" width="700" height="400" alt="Blog post image placeholder">
                                            @endif
                                        </a>
                                        <div class="giftbox-blogtext pt-2 pb-2">
                                            <h3 id="blog-title-{{$PData->id}}" class="pt-sm-2 pb-sm-3 pb-2" style="color:#{{$title_font_color}};font-size:@if($title_font_size!='0'){{$title_font_size}}px;@else {{14}}px; @endif">{!! str_replace("\'", "'", $PData->title) !!}</h3>
                                            <p class="mb-1" style="color:#{{$author_font_color}};font-size:@if($author_font_size!='0'){{$author_font_size}}px;@else {{14}}px; @endif">{{$PData->author_name}}</p>
                                            <p class="mb-0" style="color:#{{$date_font_color}};font-size:@if($date_font_size!='0'){{$date_font_size}}px;@else {{14}}px; @endif">{{$date}}</p>
                                            @if($PData->link_title!='')
                                            <p class="mb-0 pt-2"><a href="{{$blogimg_link}}" class="ulink" style="color:#{{$link_font_color}};font-size:@if($link_font_size!='0'){{$link_font_size}}px;@else {{14}}px; @endif" aria-label="Read more: {{ strip_tags($PData->title) }}"><i>{{$PData->link_title}}</i></a></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        <?php } ?>
                    </div>
                    <div class="row mt-md-3" role="list" aria-label="Other Blog Posts">
                        <?php
                        foreach ($Blog as $key => $PData) {
                            $yeardata = strtotime("" . $PData->date . "");
                            if ($PData->date !== '0000-00-00') {
                                $date = date('F d, Y', $yeardata);
                            } else {
                                $date = '';
                            }
                            if ($PData->blog_img_link != '') {
                                $blogimg_link = str_replace('{$Site_URL}', config('global.SITE_URL'), $PData->blog_img_link);
                            } else {
                                $blogimglink = str_replace(" ", "-", strtolower($PData->title)) . '/' . $PData->id;
                                $blogimg_link = str_replace('{$Site_URL}', config('global.SITE_URL'), $blogimglink);
                            }
                            // $blogimg_link=str_replace('{$Site_URL}', config('global.SITE_URL'), $PData->blog_img_link)
                        ?>
                            @if($PData->banner_type=='3')
                            <div class="col-md-4 col-12 mb-md-2 pb-4" role="listitem" aria-label="Blog post: {{ strip_tags($PData->title) }}">
                                <div class="giftbox-blog text-center" tabindex="0" aria-labelledby="blog-title-{{$PData->id}}">
                                    <a href="{{$blogimg_link}}" aria-label="Read blog post: {{ strip_tags($PData->title) }}">
                                        <img src="{{$PData->blog_image}}" loading="lazy" width="500" height="500" alt="{{ strip_tags($PData->title) }}">
                                    </a>
                                    <div class="giftbox-blogtext pt-2 pb-2">
                                        <h3 id="blog-title-{{$PData->id}}" class="pt-sm-2 pb-sm-3 pb-2" style="color:#{{$title_font_color}};font-size:@if($title_font_size!='0'){{$title_font_size}}px;@else {{14}}px; @endif">{!! str_replace("\'", "'", $PData->title) !!}</h3>
                                        <p class="mb-1" style="color:#{{$author_font_color}};font-size:@if($author_font_size!='0'){{$author_font_size}}px;@else {{14}}px; @endif">{{$PData->author_name}}</p>
                                        <p class="mb-0" style="color:#{{$date_font_color}};font-size:@if($date_font_size!='0'){{$date_font_size}}px;@else {{14}}px; @endif">{{$date}}</p>
                                        @if($PData->link_title!='')
                                        <p class="mb-0 pt-2"><a href="{{$blogimg_link}}" class="ulink" style="color:#{{$link_font_color}};font-size:@if($link_font_size!='0'){{$link_font_size}}px;@else {{14}}px; @endif" aria-label="Read more: {{ strip_tags($PData->title) }}"><i>{{$PData->link_title}}</i></a></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        <?php } ?>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection