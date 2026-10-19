@extends('layouts.app')
@section('content')
<main role="main" id="main-content" tabindex="-1" aria-label="Blog Landing Main Content">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <a href="{{url('/')}}" aria-label="Home">Home</a>
            <input type="hidden" name="main_blogid" id="main_blogid" value="{{$main_blogid}}" />
            <span class="active" aria-current="page">@if($title != ''){{ucwords(str_replace('-', ' ', ucwords($title)))}}@endif</span>
        </div>
    </section>
    <section class="pb-md-5 pb-4 text-center pt-md-4 pt-3">
        <h2 id="blog-landing-title">@if($title != ''){{ucwords(str_replace('-', ' ', ucwords($title)))}}@endif</h2>
    </section>
    <section class="landing-dtlblog pb-4" aria-labelledby="blog-landing-title">
        <div class="container">
            @if(count($mainBlogData) > 0)
                <?php $i = 0; ?>
                @foreach($mainBlogData as $key => $LandingPage)
                    @if($LandingPage->blog_section_id == 1 && (isset($Wide_Banner) && $Wide_Banner->count() > 0))
                        <div class="row pb-md-5 pb-4" role="region" aria-label="Wide Banner Section">
                            <div class="col-12 text-center">
                                @if($Wide_Banner[0]->main_banner_img!='')
                                    <img class="d-md-block d-none" src="{{$Wide_Banner[0]->main_banner_img}}" width="1600" height="500" alt="{{$Wide_Banner[0]->banner_title}}">
                                @endif
                                @if($Wide_Banner[0]->mobile_banner_img!='')
                                    <img class="d-md-none" src="{{$Wide_Banner[0]->mobile_banner_img}}" width="768" height="768" alt="{{$Wide_Banner[0]->banner_title}}">
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 10 && (isset($Banner_Desc) && $Banner_Desc->count() > 0))
                        <div class="post-list-sec pb-md-5 pb-4" role="region" aria-label="Banner Description Section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center postlist-p">
                                        @if($Banner_Desc[0]->banner_description != '')
                                            <p>{!!$Banner_Desc[0]->banner_description!!}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 11 && (isset($Coll_Desc) && $Coll_Desc->count() > 0))
                        <div class="post-list-sec pb-md-5 pb-4" role="region" aria-label="Collection Description Section">
                            <div class="container">
                                <div class="border-top border-bottom">
                                    <div class="row pb-2 pb-md-0">
                                        @if($Coll_Desc[0]->main_banner_img!='' || $Coll_Desc[0]->banner_img1!='')
                                            <div class="col-md-7 pb-3 pt-3">
                                                <div class="row row10 justify-content-center">
                                                    @if($Coll_Desc[0]->main_banner_img!='')
                                                        <div class="col-6 pb-2 pt-2">
                                                            <picture>
                                                                <img src="{{$Coll_Desc[0]->main_banner_img}}" alt="{{$Coll_Desc[0]->banner_title}}" width="400" height="400">
                                                            </picture>
                                                        </div>
                                                    @endif
                                                    @if($Coll_Desc[0]->banner_img1!='')
                                                        <div class="col-6 pb-2 pt-2">
                                                            <picture>
                                                                <img src="{{$Coll_Desc[0]->banner_img1}}" alt="{{$Coll_Desc[0]->banner_title}}" width="400" height="400">
                                                            </picture>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if($Coll_Desc[0]->main_banner_img=='' && $Coll_Desc[0]->banner_img1=='')
                                        <div class="col-md-12 pb-3 pt-md-3">
                                        @else
                                        <div class="col-md-5 pb-3 pt-md-3">
                                        @endif
                                            @if($Coll_Desc[0]->banner_link_title!='')
                                                <h2 class="pb-3 pt-md-2">{{$Coll_Desc[0]->banner_link_title}}</h2>
                                            @endif
                                            @if($Coll_Desc[0]->banner_description!='')
                                                <p>{!!$Coll_Desc[0]->banner_description!!}</p>
                                            @endif
                                            @if($Coll_Desc[0]->banner_link!='')
                                                <a href="{{$Coll_Desc[0]->banner_link}}" class="btn btn-primary mt-2" target="_blank" aria-label="{{$Coll_Desc[0]->banner_link_title}}">{{$Coll_Desc[0]->banner_link_title}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 12 && (isset($Blocks) && $Blocks->count() > 0))
                        <div class="post-list-sec pb-md-5 pb-4" role="region" aria-label="Blocks Section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        @foreach($Blocks as $key => $LPageData)
                                            @if(($LPageData->main_banner_img!='' || $LPageData->banner_title!='' || $LPageData->description_title!='' || $LPageData->banner_description!='' || $LPageData->banner_link!='' || $LPageData->banner_link_title!=''))
                                                <div class="post-list-box" role="region" aria-label="Blog Block: {{$LPageData->banner_title}}">
                                                    <div class="post-list">
                                                        @if($LPageData->main_banner_img!='')
                                                            <div class="post-list-img">
                                                                <picture>
                                                                    <img src="{{$LPageData->main_banner_img}}" alt="{{$LPageData->banner_title}}" width="150" height="150">
                                                                </picture>
                                                            </div>
                                                        @endif
                                                        <div class="post-list-text">
                                                            @if($LPageData->banner_title!='')<h3>{{$LPageData->banner_title}}</h3>@endif
                                                            @if($LPageData->description_title!='')<h4 class="pt-2">{{$LPageData->description_title}}</h4>@endif
                                                            <div class="post-list-tittle">
                                                                @if($LPageData->banner_description!='')
                                                                    <div class="post-readmore">
                                                                        <a href="javascript:void(0);" data-setid="text-{{$loop->iteration}}" class="ulink setText" aria-label="Read more about {{$LPageData->banner_title}}">Read More</a>
                                                                    </div>
                                                                @endif
                                                                @if($LPageData->banner_link_title!='')
                                                                    <div class="post-price-btn">
                                                                        <a href="{{$LPageData->banner_link}}" class="btn btn-primary d-block" aria-label="{{$LPageData->banner_link_title}}">{{$LPageData->banner_link_title}}</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pt-4 detailCls" style="display:none;" id="text-{{$loop->iteration}}">
                                                        @if($LPageData->banner_description!='')<p>{!!$LPageData->banner_description!!}</p>@endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 2 || $LandingPage->blog_section_id == 3)
                        <div class="row pb-md-5 pb-4" role="region" aria-label="Banner Section">
                            @if($LandingPage->blog_section_id == 2 && (isset($Right_Banners) && $Right_Banners->count() > 0))
                                <div class="col-12">
                                    <div class="blog-left-rbox" role="region" aria-label="Right Banner">
                                        <div class="row no-gutters">
                                            <div class="col-lg-6 text-center">
                                                @if($Right_Banners[0]->main_banner_img!='')
                                                    <img src="{{$Right_Banners[0]->main_banner_img}}" width="800" height="500" alt="{{$Right_Banners[0]->banner_title}}">
                                                @endif
                                            </div>
                                            <div class="col-lg-6 d-flex align-items-center">
                                                <div class="blog-textbox">
                                                    <h2 class="pb-2">{!!$Right_Banners[0]->banner_title!!}</h2>
                                                    <p>{!!$Right_Banners[0]->banner_description!!}</p>
                                                    @if($Right_Banners[0]->blog_section_id == 2 && $Right_Banners[0]->banner_link_title!='')
                                                        <p><a href="{{$Right_Banners[0]->banner_link}}" class="btn btn-primary mt-2" target="_blank" aria-label="{{$Right_Banners[0]->banner_link_title}}">{{$Right_Banners[0]->banner_link_title}}</a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($LandingPage->blog_section_id == 3 && (isset($Left_Banners) && $Left_Banners->count() > 0))
                                <div class="col-12">
                                    <div class="blog-left-rbox" role="region" aria-label="Left Banner">
                                        <div class="row no-gutters">
                                            <div class="col-lg-6 order-lg-2 text-center">
                                                @if($Left_Banners[0]->main_banner_img!='')
                                                    <img src="{{$Left_Banners[0]->main_banner_img}}" width="800" height="500" alt="{{$Left_Banners[0]->banner_title}}">
                                                @endif
                                            </div>
                                            <div class="col-lg-6 d-flex align-items-center order-lg-1">
                                                <div class="blog-textbox blog-lefttextbox">
                                                    <h2 class="pb-2">{!!$Left_Banners[0]->banner_title!!}</h2>
                                                    <p>{!!$Left_Banners[0]->banner_description!!}</p>
                                                    @if($Left_Banners[0]->blog_section_id == 3 && $Left_Banners[0]->banner_link_title!='')
                                                        <p><a href="{{$Left_Banners[0]->banner_link}}" class="btn btn-primary mt-2" target="_blank" aria-label="{{$Left_Banners[0]->banner_link_title}}">{{$Left_Banners[0]->banner_link_title}}</a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 6 || $LandingPage->blog_section_id == 7)
                        <div class="row pb-md-5 pb-4" role="region" aria-label="Small Banner Section">
                            @if($LandingPage->blog_section_id == 6 && (isset($Right_Small_Banners) && $Right_Small_Banners->count() > 0))
                                <div class="col-12">
                                    <div class="blog-left-rbox small-rbox" role="region" aria-label="Right Small Banner">
                                        <div class="row">
                                            <div class="col-md-3 text-center pb-3">
                                                @if($Right_Small_Banners[0]->main_banner_img!='')
                                                    <img src="{{$Right_Small_Banners[0]->main_banner_img}}" width="400" height="400" alt="{{$Right_Small_Banners[0]->banner_title}}">
                                                @else
													<!--<img src="https://via.placeholder.com/400x400" width="800" height="500" alt="{{$Right_Small_Banners[0]->banner_title}}">-->
												@endif
                                            </div>
                                            <div class="col-md-9 d-flex align-items-center">
                                                <div class="blog-textbox p-0">
                                                    <h2 class="pb-2">{!!$Right_Small_Banners[0]->banner_title!!}</h2>
                                                    <p>{!!$Right_Small_Banners[0]->banner_description!!}</p>
                                                    @if($Right_Small_Banners[0]->blog_section_id == 6 && $Right_Small_Banners[0]->banner_link_title!='')
                                                        <p><a href="{{$Right_Small_Banners[0]->banner_link}}" class="btn btn-primary mt-2" target="_blank" aria-label="{{$Right_Small_Banners[0]->banner_link_title}}">{{$Right_Small_Banners[0]->banner_link_title}}</a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($LandingPage->blog_section_id == 7 && (isset($Left_Small_Banners) && $Left_Small_Banners->count() > 0))
                                <div class="col-12">
                                    <div class="blog-left-rbox small-rbox" role="region" aria-label="Left Small Banner">
                                        <div class="row">
                                            <div class="col-md-3 text-center order-md-2 pb-3">
                                                @if($Left_Small_Banners[0]->main_banner_img!='')
                                                    <img src="{{$Left_Small_Banners[0]->main_banner_img}}" width="400" height="400" alt="{{$Left_Small_Banners[0]->banner_title}}">
                                                @else
													<!--<img src="https://via.placeholder.com/400x400" width="800" height="500" alt="{{$Left_Small_Banners[0]->banner_title}}">-->
												@endif
                                            </div>
                                            <div class="col-md-9 d-flex align-items-center order-md-1">
                                                <div class="blog-textbox p-0">
                                                    <h2 class="pb-2">{!!$Left_Small_Banners[0]->banner_title!!}</h2>
                                                    <p>{!!$Left_Small_Banners[0]->banner_description!!}</p>
                                                    @if($Left_Small_Banners[0]->blog_section_id == 7 && $Left_Small_Banners[0]->banner_link_title!='')
                                                        <p><a href="{{$Left_Small_Banners[0]->banner_link}}" class="btn btn-primary mt-2" target="_blank" aria-label="{{$Left_Small_Banners[0]->banner_link_title}}">{{$Left_Small_Banners[0]->banner_link_title}}</a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 8 && (isset($Set_Products) && $Set_Products->count() > 0))
                        <div class="row pb-md-5 pb-4" role="region" aria-label="Product List Section">
                            <div class="col-12">
                                @if($TotalProducts > 0)
                                    <div class="blog-prd">
                                        <div class="row row10 text-center" id="setBlogProducts" role="list" aria-label="Blog Product List">
                                            @foreach($ProductData as $key => $Product)
                                                <div class="col-md-3 col-sm-6 col-6 pb-4 pt-4" role="listitem">
                                                    <div class="blog-prdbox" tabindex="0">
                                                        <x-productbox :prodData="$Product" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="pb-2" id="loadmore" style="@if($TotalProducts <= 10) display:none @endif">
                                            <a class="list-more d-block" data-page='1' aria-label="Load more products"> Load More... </a>
                                        </div>
                                        <div class="pb-2" id="noprod" style="@if($TotalProducts > 0) display:none @endif" aria-live="polite">No Record Found</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($LandingPage->blog_section_id == 5 && (isset($Banner_Grid) && $Banner_Grid->count() > 0))
                        <div class="row pb-md-5 pb-4" role="region" aria-label="Banner Grid Section">
                            <div class="col-12">
                                <div class="row row10">
                                    @foreach($Banner_Grid as $key => $LPageData)
                                        @if($LPageData->banner_title!='')
                                            <div class="col-sm-6 pb-4" role="region" aria-label="Banner Grid: {{$LPageData->banner_title}}">
                                                <div class="row row5">
                                                    @if($LPageData->main_banner_img!='')
                                                        <div class="col-12 text-center">
                                                            <img src="{{$LPageData->main_banner_img}}" width="800" height="500" alt="{{$LPageData->banner_title}}">
                                                        </div>
                                                    @else
														<!--<div class="col-12 text-center"><img src="https://via.placeholder.com/800x500" width="800" height="500"></div>-->
													@endif
                                                    <div class="col-12">
                                                        @if($LPageData->banner_title!='')
                                                            <h3 class="pt-4 pb-2">{{$LPageData->banner_title}}</h3>
                                                        @endif
                                                        @if($LPageData->banner_link_title!='')
                                                            <p><a href="{{$LPageData->banner_link}}" class="ulink" target="_blank" aria-label="{{$LPageData->banner_link_title}}"><i>{{$LPageData->banner_link_title}}</i></a></p>
                                                        @endif
                                                        @if($LPageData->banner_description!='')
                                                            <p>{!!$LPageData->banner_description!!}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <?php $i++; ?>
                @endforeach
            @else
                <p>No Result Found!</p>
            @endif
        </div>
	</section>
</main>
@endsection