<header role="banner" aria-label="Site Header">
<? 
    $topHeader = setTopHeaderBar();
?>
    <div class="header-save d-none d-md-block" role="region" aria-label="Top Header Bar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-md-between justify-content-center headertop-row">
                <!-- Reviews Section -->
                <? if(isset($topHeader['first'][0]['BarId']) && $topHeader['first'][0]['BarId']==1 && isset($topHeader['first'][0]['link_title']) && $topHeader['first'][0]['link_title']!='' && isset($topHeader['first'][0]['link']) && $topHeader['first'][0]['link']!='' && isset($topHeader['first'][0]['status']) && $topHeader['first'][0]['status']=='1'){ ?> 
                <div class="head-review d-flex" role="region" aria-label="Reviews">
                    <a href="<?=$topHeader['first'][0]['link']?>" class="menu-a d-flex align-items-center" target="_blank" aria-label="<?=$topHeader['first'][0]['link_title']?>" rel="noopener">
                        <span><?=$topHeader['first'][0]['link_title']?></span>
                        <!-- ⭐⭐⭐⭐⭐ -->
                    </a>
                </div>
                <? } ?>

                <!-- Offers and Other Links -->
                <div class="head-middletext" role="region" aria-label="Offers and Other Links">
                    <? if(isset($topHeader['first'])){
                        for($f = 0; $f < count($topHeader['first']); $f++){
                            if(isset($topHeader['first'][$f]['status']) && $topHeader['first'][$f]['status']=='1' && isset($topHeader['first'][$f]['BarId']) && $topHeader['first'][$f]['BarId'] > 1 && $topHeader['first'][$f]['BarId'] < 6){ ?>
                                <a href="<?=$topHeader['first'][$f]['link']?>" class="menu-a" aria-label="<?=$topHeader['first'][$f]['link_title']?>" rel="noopener"><?=$topHeader['first'][$f]['link_title']?></a>
                            <?
                            }
                        }
                    } ?>
                </div>

                <!-- Chat and Call Section -->
                <div class="head-chatsec d-flex justify-content-end" role="region" aria-label="Chat and Call">
                    <div class="head-chat">
                        <div class="call-chattext" id="chat-or-call" aria-label="Chat or Call">
                            <svg class="chat-nw vam mr-2" aria-hidden="true" role="img" width="18" height="18" focusable="false">
                                <use href="#chat-nw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat-nw"></use>
                            </svg>
                            <? if(isset($topHeader['first'][5]['link_title']) && $topHeader['first'][5]['link_title']!='' && isset($topHeader['first'][5]['link_title']) && $topHeader['first'][5]['status'] == '1'){  ?>
                                <span><?=$topHeader['first'][5]['link_title']?></span>
                            <? } ?>
                        </div>

                        <!-- Dropdown for Chat or Call -->
                        <ul class="chat-call-dropdown" role="menu" aria-labelledby="chat-or-call">
                            <li role="menuitem">
                                <a href="javascript:void(0);" id="getHelpChat" rel="noreferrer" role="button" aria-label="Start Live Chat">
                                    <svg class="chat-nw vam" aria-hidden="true" role="img" width="18" height="18" focusable="false">
                                        <use href="#chat-nw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat-nw"></use>
                                    </svg><span class="dropdown-text">Chat</span>
                                </a>
                            </li>
                            <li role="menuitem">
                                <a href="https://www.maxaroma.com/contact-us.html" role="link" aria-label="Call us">
                                    <svg class="sv-call vam" aria-hidden="true" role="img" width="18" height="18" focusable="false">
                                        <use href="#sv-call" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-call"></use>
                                    </svg><span class="dropdown-text">Call</span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-save d-md-none" role="region" aria-label="Mobile Top Header Bar">
        <div class="container">
            <div class="headertop-row">
                <div class="head-review head-mobile" role="region" aria-label="Mobile Reviews and Offers">
                <? if(isset($topHeader['first'])){
                    for($f = 0; $f < count($topHeader['first']); $f++){
                        if(isset($topHeader['first'][$f]['status']) && $topHeader['first'][$f]['status']=='1' && isset($topHeader['first'][$f]['BarId']) && ($topHeader['first'][$f]['BarId'] == 1 || $topHeader['first'][$f]['BarId'] == 2 || $topHeader['first'][$f]['BarId'] == 4)){ ?>
                            <a href="<?=$topHeader['first'][$f]['link']?>" target="_blank" class="menu-a d-flex align-items-center" aria-label="<?=$topHeader['first'][$f]['link_title']?>" rel="noopener"><?=$topHeader['first'][$f]['link_title']?></a>
                        <?
                        }
                    }
                } ?>	
                </div>
                <div id="load_head_mobile" class="cover-spin" aria-live="polite" aria-busy="true"></div>
            </div>
        </div>
    </div>


    <!-- Bar 2 -->
    <? if(isset($topHeader['second']) && count($topHeader['second']) > 0){ ?>
        <? if(isset($topHeader['second'][0]['bar_type']) && $topHeader['second'][0]['status'] == 1 && $topHeader['second'][0]['bar_type'] == 2 && $topHeader['second'][0]['BarId'] == 7) { ?>
        <div class="holidaybar-head d-flex align-items-center justify-content-center" role="region" aria-label="Holiday Bar">			
            <?=$topHeader['second'][0]['link_title']?>
                <a href="javascript:void(0);" onclick="DisplaySecondBarPopup();" class="ulink ml-2" aria-label="More Holiday Info"><?=$topHeader['second'][0]['link']?></a>
        </div>
        <? } ?>
    <? } ?>

    <div id="header-sticky-anchor"></div>

    <div id="header-sticky" role="navigation" aria-label="Main Navigation">
        <div class="header_mid">
            <div class="container">
                <div class="header-middle  position-relative">
                    @if(Session::has('sess_store_id') && Session::get('sess_store_id')!='' && Session::has('sess_storeuserid') && Session::get('sess_storeuserid')!='')
						<div class="header-posNavigation">
							<a href="{{config('global.SITE_URL').'store/store-dashboard.html'}}">POS Home</a>
						</div>
					@endif
                    <div class="row row5">
                        <div class="col-lg-2 col-md-3 col-5">
                            <h1 class="header_logo"><a href="{{config('global.SITE_URL')}}" aria-label="Home">
                                <img src="{{config('global.SPEED_SIZE_URL')}}{{config('global.SITE_IMAGES')}}logo.jpg" width="224" height="55" alt="MaxAroma Logo">
                            </a>
                        </h1>
                        </div>
                        <div class="col-lg-10 col-md-9 col-7 poi">
                            <div class="h-sec-right">
                                <div class="row row5">
                                    <div class="col-12 poi">
                                        <div class="header-curright">
                                            <ul class="header_icon" role="list" aria-label="Header Icons">
                                                <li class="d-none1 d-lg-inline src-hsec" role="listitem">
                                                    <div class="src-nwicon">
                                                        <a href="javascript:void(0)" title="Search" aria-label="Open Search">
                                                            <svg class="vam" id="mainsearch" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                                                                    <use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
                                                            </svg>
                                                            <svg class="sv-close" aria-hidden="true" role="img" width="17" height="17" focusable="false">
                                                                <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="header_search" role="search" aria-label="Site Search">
                                                        <input type="hidden" name="csrf-token" value="<?php echo csrf_token(); ?>">
                                                        <!--Please do not change this div's position-->
                                                        <div class="showLoader" style="display:none;" aria-live="polite" aria-busy="true"></div>
                                                        <!--Please do not change this div's position-->
                                                        <input type="search" class="form-control" id="keyword"  placeholder="Search" autocomplete="off" aria-label="Search Products">
                                                        <!--Please do not change this div's position-->
                                                        <div id="autoSuggesstion" style="display:none;" aria-live="polite"></div>
                                                        <!--Please do not change this div's position-->
                                                        <svg class="sv-search vam" aria-hidden="true" role="img" width="20" height="20" id="sidesearch" focusable="false">
                                                            <use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
                                                        </svg>
                                                    </div>
                                                </li>
                                                <li class="d-lg-none" role="listitem">
                                                    <a href="javascript:void(0)" class="cart-link" title="View your cart" aria-label="View your cart">
                                                        <svg class="sv-cartnw vam" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                                                            <use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
                                                        </svg>
                                                        @if(Session::has ('ShoppingCart.TotalItemInCart') && Session::get('ShoppingCart.TotalItemInCart') > 0)	
                                                             <span class="cart-qty" aria-label="Items in cart">
                                                                {{Session::get('ShoppingCart.TotalItemInCart')}}
                                                            </span>
                                                        @else
                                                            <span class="cart-qty" style="display:none;" aria-label="No items in cart">
                                                                {{Session::get('ShoppingCart.TotalItemInCart')}}
                                                            </span>
                                                        @endif 
                                                    </a>
                                                </li>
                                                <li class="d-lg-none" role="listitem">
                                                    <a href="javascript:void(0)" class="menu_bar" aria-label="Open Menu">
                                                        <svg class="menu_icon" aria-hidden="true" role="img" width="23" height="18" focusable="false">
                                                            <use href="#menu_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#menu_icon"></use>
                                                        </svg>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="header_currency" role="region" aria-label="Currency Selector">
                                                <div class="select-auto">
                                                    <select class="select" sb="5982666" id="selcurrency" aria-label="Select Currency">
                                                        @foreach(config('Currencies') as $Currency)
                                                            <option value="{{$Currency['currency_id']}}" @if(Session::get('currency_code') == $Currency['currency_code']) selected @endif>{{$Currency['currency_code']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-lg-block d-none poi">
                                        <div class="h-menu-sec" role="navigation" aria-label="Main Menu">
                                            <div class="menu_move">
                                            	<div class="menu_des">
                                                 <ul class="menu" role="menubar">
                                                    @if(count($menu_array) > 0)
                                                        @foreach($menu_array as $menu_key => $menu_value)
                                                        <li class="sub-nav" role="none">
                                                            @php 
                                                                $colourClass =  ($menu_value['menu_title'] == 'SALES & OFFERS') ? 'class="sale-menu ga4c"' : 'class="ga4c"';
                                                            @endphp

                                                            @php 
                                                                $alignClass =  ($menu_value['menu_title'] == 'Discovery Bundles' || $menu_value['menu_title'] == 'Candles' || $menu_value['menu_title'] == 'Bath & Body') ? 'mm-last-img' : '';
                                                            @endphp

                                                           <a href="{{ $menu_value['menu_link'] }}" {!! $colourClass !!} role="menuitem" aria-label="{{ $menu_value['menu_title'] }}">{{ $menu_value['menu_title'] }}</a>
                                                            @if($menu_value['menu_title'] != "SALES & OFFERS")
                                                    			<div class="navsub-arrow" aria-hidden="true"></div>
                                                            @endif
                                                    		@if($menu_value['label_count'] > 0)
                                                            <div class="nav-dropdown nav-sub-mm" role="region" aria-label="{{ $menu_value['menu_title'] }} Submenu">
                                                                <div class="container">
                                                                    @php  
                                                                        $loop_count = 0;
                                                                        $next_place = 'beside';
                                                                        $in_single = 1;
                                                                    @endphp
                                                                    @foreach($menu_value['label'] as $label_key => $label_value)
                                                                    	@if($label_value['menu_title'] == 'Custom Tag Link - Banner Section')
                                                                    		@if(!empty($label_value['childs']))
                                                                        		@if($menu_value['display_banners_count'] == 1)
                                                                            		@if($label_value['childs'][0]['menu_image'] != '')
                                                                                    <div class="drop_inner drop_innernon {!! $alignClass !!}">
                                                                                        @if($label_value['childs'][0]['menu_label'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                            <a href="{{ $banner_link }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                                                                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image'] }}" width="250" height="250" alt="{{ $label_value['childs'][0]['menu_label'] }}" />
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @elseif($label_value['childs'][0]['menu_image1'] != '')
                                                                                    <div class="drop_inner drop_innernon">
                                                                                        @if($label_value['childs'][0]['menu_label1'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label1'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                            <a href="{{ $banner_link1 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                                                                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image1'] }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250" />
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @elseif($label_value['childs'][0]['menu_image2'] != '')
                                                                                    <div class="drop_inner drop_innernon">
                                                                                        @if($label_value['childs'][0]['menu_label2'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label2'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                            <a href="{{ $banner_link2 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                                                                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image2'] }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250" />
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                        		@elseif($menu_value['display_banners_count'] == 2)
                                                                            		@php 
                                                                            			$a = 0;
                                                                            		@endphp
                                                                        			@if($label_value['childs'][0]['menu_image'] != '')
                                                                                        <div class="drop_inner drop_innernon {!! $alignClass !!}">
                                                                                            @if($label_value['childs'][0]['menu_label'] != '')
                                                                                            <h5>{{ $label_value['childs'][0]['menu_label'] }}</h5>
                                                                                            @endif
                                                                                            @php $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  @endphp
                                                                                            <div class="thumb mm-sub text-lg-left text-center">
                                                                                               <a href="{{ $banner_link }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                                                                               <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image'] }}" alt="{{ $label_value['childs'][0]['menu_label'] }}" width="250" height="250" />
                                                                                               </a>
                                                                                            </div>
                                                                                        </div>
                                                                        				@php $a++; @endphp
                                                                        			@endif
                                                                        			@if($a < 2 && $label_value['childs'][0]['menu_image1'] != '')
                                                                                        <div class="drop_inner drop_innernon">
                                                                                            @if($label_value['childs'][0]['menu_label1'] != '')
                                                                                            <h5>{{ $label_value['childs'][0]['menu_label1'] }}</h5>
                                                                                            @endif
                                                                                            @php $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  @endphp
                                                                                            <div class="thumb mm-sub text-lg-left text-center">
                                                                                               <a href="{{ $banner_link1 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                                                                               <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image1'] }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250" />
                                                                                               </a>
                                                                                            </div>
                                                                                        </div>
                                                                        				@php $a++; @endphp
                                                                        			@endif
                                                                        			@if($a < 2 && $label_value['childs'][0]['menu_image2'] != '')
                                                                                        <div class="drop_inner drop_innernon">
                                                                                            @if($label_value['childs'][0]['menu_label2'] != '')
                                                                                            <h5>{{ $label_value['childs'][0]['menu_label2'] }}</h5>
                                                                                            @endif
                                                                                            @php $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  @endphp
                                                                                            <div class="thumb mm-sub text-lg-left text-center">
                                                                                               <a href="{{ $banner_link2 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                                                                               <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image2'] }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250" />
                                                                                               </a>
                                                                                            </div>
                                                                                        </div>
                                                                        				@php $a++; @endphp
                                                                        			@endif
                                                                        		@elseif($menu_value['display_banners_count'] >= 3)
                                                                            		@if($label_value['childs'][0]['menu_image'] != '')
                                                                                    <div class="drop_inner drop_innernon {!! $alignClass !!}">
                                                                                        @if($label_value['childs'][0]['menu_label'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link = ($label_value['childs'][0]['menu_custom_link'] != '') ? $label_value['childs'][0]['menu_custom_link'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                           <a href="{{ $banner_link }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label'] }}">
                                                                                           <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image'] }}" alt="{{ $label_value['childs'][0]['menu_label'] }}" width="250" height="250" />
                                                                                           </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                            		@if($label_value['childs'][0]['menu_image1'] != '')
                                                                                    <div class="drop_inner drop_innernon">
                                                                                        @if($label_value['childs'][0]['menu_label1'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label1'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link1 = ($label_value['childs'][0]['menu_custom_link1'] != '') ? $label_value['childs'][0]['menu_custom_link1'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                           <a href="{{ $banner_link1 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label1'] }}">
                                                                                           <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image1'] }}" alt="{{ $label_value['childs'][0]['menu_label1'] }}" width="250" height="250" />
                                                                                           </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                            		@if($label_value['childs'][0]['menu_image2'] != '')
                                                                                    <div class="drop_inner drop_innernon">
                                                                                        @if($label_value['childs'][0]['menu_label2'] != '')
                                                                                        <h5>{{ $label_value['childs'][0]['menu_label2'] }}</h5>
                                                                                        @endif
                                                                                        @php $banner_link2 = ($label_value['childs'][0]['menu_custom_link2'] != '') ? $label_value['childs'][0]['menu_custom_link2'] : 'javascript:void(0);';  @endphp
                                                                                        <div class="thumb mm-sub text-lg-left text-center">
                                                                                           <a href="{{ $banner_link2 }}" class="ga4c" aria-label="{{ $label_value['childs'][0]['menu_label2'] }}">
                                                                                           <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $label_value['childs'][0]['menu_image2'] }}" alt="{{ $label_value['childs'][0]['menu_label2'] }}" width="250" height="250" />
                                                                                           </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                @else
                                                                                @endif
                                                                            @endif
                                                                    	@else
                                                                            @php 
                                                                                $child_count = count($label_value['childs']);
                                                                			@endphp
                                                                			@if($next_place == 'beside' || $in_single > 2)
                                                                            <div class="drop_inner">
                                                                            @endif
                                                                                @php
                                                                                    if($next_place == 'below' && $in_single <= 2) {
                                                                                        $h5_class = 'class="de_spacetp"';
                                                                                    } else {
                                                                                        $h5_class = '';
                                                                                    }
                                                                                    $menu_link = ($label_value['menu_link'] != '') ? $label_value['menu_link'] : 'javascript:void(0);';
                                                                                @endphp
                                                                                @if($menu_key != 0 && $menu_value['menu_title'] != 'Discovery Bundles' && (($menu_value['menu_title'] != 'Fragrance' || $label_value['menu_title'] != 'Categories') || config('typevalofcriteo') == 'd'))
                                                                                <h5 {!! $h5_class !!}><a href="{{ $menu_link }}" aria-label="{{ $label_value['menu_title'] }}">{{ $label_value['menu_title'] }}</a></h5>
                                                                                @endif
                                                                                @if(!empty($label_value['childs']) && count($label_value['childs']) > 0)
                                                                                <ul @if($menu_key != 0 && (($menu_value['menu_title'] != 'Fragrance' || $label_value['menu_title'] != 'Categories') || config('typevalofcriteo') == 'd')) class="mm-sub" @endif role="list" aria-label="{{ $label_value['menu_title'] }} Submenu">
                                                                                    {!! getCategoriesHTML($label_value['childs']) !!} 
                                                                                </ul>
                                                                                @endif
                                                                            @php 
                                                                        		if($child_count <= 4 && $in_single <= 2 && false) {
                                                                        			$next_place = 'below';
                                                                        			$in_single++;
                                                                    			} else {
                                                                    				$next_place = 'beside';
                                                                    			}
                                                                    			if(array_key_exists($label_key+1,$menu_value['label'])) {
                                                                    				$next_section = $menu_value['label'][$label_key+1]['menu_title'];
                                                                    			} else {
                                                                    				$next_section = 'Not Exists';
                                                                    			}
                                                                			@endphp
                                                                			@if($next_place == 'beside' || $next_section == 'Custom Tag Link - Banner Section' || $in_single > 2)
                                                                            </div>
                                                                            @endif

                                                                    	@endif
                                                                    	@php $loop_count++; @endphp
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                    		@endif
                                                       	</li>
                                                        @endforeach
                                                    @endif
                                                    <li class="sub-nav clearfix" role="none">
                                                       <a href="{{ config('global.SITE_URL') }}brand-name-perfumes.html" role="menuitem" aria-label="Brands">Brands</a>
                                                       <div class="navsub-arrow" aria-hidden="true"></div>
                                                       <div class="nav-dropdown nav-sub-mm brands_tpmain" role="region" aria-label="Brands Submenu">
                                                          <div class="container">
                                                             <div class="brands_tpmenu">
                                                                <div class="left_box">
                                                                   <form>
                                                                      <div class="inner_search">
                                                                         <div class="insearch_box">
                                                                            <input type="text" id="search_brands" name="search_brands" placeholder="Search Brands" class="form-control" aria-label="Search Brands">
                                                                            <svg class="sv-search vam" aria-hidden="true" role="img" width="25" height="27" focusable="false">
                                                                               <use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
                                                                            </svg>
                                                                         </div>
                                                                      </div>
                                                                      <div class="drop_inner drop_innersfd">
                                                                         @if(count($brandlist) > 0)
                                                                         <ul class="mmsub_alp text-center" role="list" aria-label="Brand Alphabet Navigation">
                                                                         	@foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
                                                                            <li role="listitem"><a href="javascript:void(0);" onclick="move('{{ $brand_alpha_key }}')" aria-label="Brands starting with {{ $brand_alpha_key }}">{{ $brand_alpha_key }}</a></li>
                                                                            @endforeach
                                                                         </ul>
                                                                         @endif
                                                                         @if(count($brandlist) > 0)
                                                                         <h5>Top Brands</h5>
                                                                         <ul id="topbrands_scoller" class="mm-sub" role="list" aria-label="Top Brands">
                                                                         	@include('layouts.defaulttopbrand')
                                                                         </ul>
                                                                         @endif
                                                                      </div>
                                                                   </form>
                                                                </div>
                                                                <div class="right_box">
                                                                   <div class="text-center">
                                                                      <h5>POPULAR BRANDS</h5>
                                                                   </div>
                                                                   <div class="brand_box">
                                                                      <ul class="inner_newbrand" role="list" aria-label="Popular Brands">
                                                                         @if(count($popular_brands) > 0)
                                                                         	@foreach($popular_brands as $popular_brand_key => $popular_brand_value)
                                                                             <li role="listitem">
                                                                                <a href="{{ $popular_brand_value['Link'] }}" aria-label="{{ $popular_brand_value['Name'] }}">
                                                                                <img src="{{ config('global.SPEED_SIZE_URL')}}{{ $popular_brand_value['ImageLogo'] }}" alt="{{ $popular_brand_value['Name'] }}" width="108" height="73" />
                                                                                </a>
                                                                             </li>
                                                                            @endforeach
                                                                         @endif
                                                                         <div class="text-center viewmore_brand">
                                                                            <a href="{{ config('global.SITE_URL') }}brand-name-perfumes.html" aria-label="View More Brands">View More Brands</a>
                                                                         </div>
                                                                      </ul>
                                                                   </div>
                                                                </div>
                                                             </div>
                                                          </div>
                                                       </div>
                                                    </li>
                                                 </ul>
                                              	</div>
                                            </div>
                                            <ul class="header_icon" role="list" aria-label="Header User and Cart Icons">
                                                <li class="d-lg-inline-block d-none user-hlink" role="listitem"> 
                                                    @guest
                                                    <a href="{{config('global.SITE_URL').'login.html'}}" title="Sign In" aria-label="Sign In">
                                                        <svg class="sv-user vam" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                                                            <use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
                                                        </svg>
                                                    </a>
                                                    @endguest
                                                    @auth
                                                    <a href="{{config('global.SITE_URL').'myaccount.html'}}" title="My Account" aria-label="My Account">
                                                        <svg class="sv-user vam" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                                                            <use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
                                                        </svg>
                                                    </a>
                                                    @endauth
                                                    <div class="user-dropdown" role="region" aria-label="User Menu">
                                     					<div class="user-top-head">
                                                            <div class="row">
                                                                @auth
                                                                <div class="col-12">
                                                                    <div class="user-sec-text">
                                                                        <div class="drop-user-icon pr-2">
                                                                            <span class="circle" aria-label="User Initials">
                                                                                {{substr(trim(strtoupper(Auth::user()->first_name)),0,1)}}{{substr(trim(strtoupper(Auth::user()->last_name)),0,1)}}
                                                                            </span>
                                                                        </div>
                                                                        <div class="drop-user-text">
                                                                            <strong>Hi, {{Auth::user()->first_name}} {{Auth::user()->last_name}} <!-- 👋 --></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endauth
                                                                @guest
                                                                <div class="col-12">
                                                                    <div class="row row5">
                                                                        <div class="col-sm-6">
                                                                            <a href="javascript:void(0)" data-action="sign_in" class="btn btn-primary d-block signinsignup" aria-label="Sign In">Sign In</a></div>
                                                                        <div class="col-sm-6">
                                                                            <a href="javascript:void(0);" data-action="sign_upfirstpart" class="btn btn-secondary d-block signinsignup" aria-label="Sign Up">Sign up</a></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 pt-2 pb-1">
                                                                    <div class="dividerr-or"><span>or login with</span></div>
                                                                </div>
                                                                <div class="col-12 pb-1 d-flex justify-content-center align-items-center">
                                                                    <a href="{{url('/redirect')}}" title="Login with Facebook" class="user-soc-link" aria-label="Login with Facebook"> <svg class="sv-facebook vam" aria-hidden="true" role="img" width="18" height="17" focusable="false"><use href="#sv-facebook" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-facebook"></use></svg>Facebook</a>
                                                                    <a href="{{url('/redirectgoogle')}}"  title="Login with Gmail" class="user-soc-link ml-4" aria-label="Login with Gmail"><svg class="sv-gmail-0 vam mr-1" aria-hidden="true" role="img" width="18" height="18" focusable="false"><use href="#sv-gmail-0" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-gmail-0"></use>
                                                                            </svg>Gmail
                                                                    </a>
                                                                </div>
                                                                @endguest
                                                            </div>
                                                        </div>
                                                        @auth	
                                                        <div class="user-top-middle">
                                                            <ul role="list" aria-label="User Menu Options">
                                                                <li role="listitem">
                                                                    <a href="{{config('global.SITE_URL')}}order-history.html" aria-label="My Orders">
                                                                        <svg class="sv_orders vam mr-1" aria-hidden="true" role="img" width="24" height="24" focusable="false">
                                                                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
                                                                        </svg>
                                                                        <span>My Orders</span>
                                                                    </a>
                                                                </li>
                                                                <li role="listitem">
                                                                    <a href="{{config('global.SITE_URL')}}return-orders.html" aria-label="My Returns">
                                                                        <svg class="sv_returns vam mr-1" aria-hidden="true" role="img" width="24" height="24" focusable="false">
                                                                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
                                                                        </svg> 
                                                                        <span>My Returns</span>
                                                                    </a>
                                                                </li>
                                                                <li role="listitem">
                                                                    <a href="{{config('global.SITE_URL')}}myrewardpoint.html" aria-label="My Aroma Reward">
                                                                        <svg class="sv_reward_points vam mr-1" aria-hidden="true" role="img" width="24" height="24" focusable="false">
                                                                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
                                                                        </svg> 
                                                                        <span>My Aroma Reward</span>
                                                                    </a>
                                                                </li>
                                                                <li role="listitem">
                                                                    <a href="{{config('global.SITE_URL')}}wish-category.html" aria-label="My Wishlist">
                                                                        <svg class="sv_wishlist vam mr-1" aria-hidden="true" role="img" width="24" height="24" focusable="false">
                                                                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
                                                                        </svg> 
                                                                        <span>My Wishlist</span>
                                                                    </a>
                                                                </li>
                                                                <li role="listitem">
                                                                    <a href="{{config('global.SITE_URL')}}logout.html" aria-label="Sign Out">
                                                                        <svg class="sv_logout vam mr-1" aria-hidden="true" role="img" width="24" height="24" focusable="false">
                                                                        <use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use>
                                                                        </svg> 
                                                                        <span>Sign Out</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        @endauth
                                    				</div>	
                                                </li>
                                                <li role="listitem">
                                                    <a href="javascript:void(0)" class="cart-link" title="View your cart" aria-label="View your cart">
                                                        <svg class="sv-cartnw vam" aria-hidden="true" role="img" width="22" height="22" focusable="false">
                                                            <use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
                                                        </svg>
                                                        @if(Session::has ('ShoppingCart.TotalItemInCart') && Session::get('ShoppingCart.TotalItemInCart') > 0)	
                                                             <span class="cart-qty" aria-label="Items in cart">
                                                                {{Session::get('ShoppingCart.TotalItemInCart')}}
                                                            </span>
                                                        @else
                                                            <span class="cart-qty" style="display:none;" aria-label="No items in cart">
                                                                {{Session::get('ShoppingCart.TotalItemInCart')}}
                                                            </span>
                                                        @endif 
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</header>