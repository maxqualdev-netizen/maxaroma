<header>
	<div class="header-save d-flex align-items-center justify-content-center">
	@if(Session::get('eusertype')=="Wholesaler" && config('Settings.WHOLESALER_FREE_DOMESTIC_SHIPPING')!='')
	{!! config('Settings.WHOLESALER_FREE_DOMESTIC_SHIPPING') !!}
	@else
		@if(config('Settings.FREE_DOMESTIC_SHIPPING')!='' && Session::get('eusertype')!="Wholesaler")
		@php 
			$linkText = stripslashes(config('Settings.FREE_DOMESTIC_SHIPPING'));
		@endphp
		<a href="javascript:void(0);" onclick="DisplayFreeShippingPopUp();" class="menu-a">{!! $linkText !!}{{Session::get('eusertype')}}</a>
		@endif
	@endif
	<a href="{{ url('/gift-certificate.html') }}" class="menu-a">Shop E-Gift Cards</a>
	<a href="{{ url('/track-order.html') }}" class="menu-a">Track Order</a>
	</div>
	<div id="header-sticky-anchor"></div>

	<div id="header-sticky">
		<div class="header_mid">
			<div class="container">
				<div class="header-middle">
					<div class="row row5">
						<div class="col-lg-2 col-md-3 col-5">
							<h1 class="header_logo"><a href="{{config('global.SITE_URL')}}"><img src="{{config('global.SITE_IMAGES')}}logo.jpg" width="224" height="55" alt=""></a></h1>
						</div>
						<div class="col-lg-10 col-md-9 col-7 poi">
							<div class="h-sec-right">
								<div class="row row5">
									<div class="col-12 poi">
										<div class="header-curright">
											<ul class="header_icon">
												<li class="d-none1 d-lg-inline src-hsec">
													<div class="src-nwicon">
														<a href="javascript:void(0)" title="Search">
															<svg class="vam" id="mainsearch" aria-hidden="true" role="img" width="22" height="22">
																	<use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
															</svg>
															<svg class="sv-close" aria-hidden="true" role="img" width="17" height="17">
																<use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
															</svg>
														</a>
													</div>
													<div class="header_search">
														<input type = "hidden" name = "csrf-token" value = "<?php echo csrf_token(); ?>">
														<!--Please do not change this div's position-->
														<div class="showLoader" style="display:none;"></div>
														<!--Please do not change this div's position-->
														<input type="search" class="form-control" id="keyword"  placeholder="Search" autocomplete="off">
														<!--Please do not change this div's position-->
														<div id="autoSuggesstion" style="display:none;"></div>
														<!--Please do not change this div's position-->
														<svg class="sv-search vam" aria-hidden="true" role="img" width="20" height="20" id="sidesearch">
															<use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
														</svg>
													</div>
												</li>
												<li class="d-lg-none">
													<a href="javascript:void(0)" class="cart-link" title="View your cart">
														<svg class="sv-cartnw vam" aria-hidden="true" role="img" width="22" height="22">
															<use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
														</svg>
														@if(Session::has ('ShoppingCart.TotalItemInCart') && Session::get('ShoppingCart.TotalItemInCart') > 0)	
															 <span class="cart-qty">
																{{Session::get('ShoppingCart.TotalItemInCart')}}
															</span>
														@else
															<span class="cart-qty" style="display:none;">
																{{Session::get('ShoppingCart.TotalItemInCart')}}
															</span>
														@endif 
													</a>
												</li>
												<li class="d-lg-none">
													<a href="javascript:void(0)" class="menu_bar">
														<svg class="menu_icon" aria-hidden="true" role="img" width="23" height="18">
															<use href="#menu_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#menu_icon"></use>
														</svg>
													</a>
												</li>
											</ul>
											<div class="header_currency">
												<div class="select-auto">
													<select class="select" sb="5982666" id="selcurrency" >
														@foreach(config('Currencies') as $Currency)
															<option value="{{$Currency['currency_id']}}" @if(Session::get('currency_code') == $Currency['currency_code']) selected @endif >{{$Currency['currency_code']}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 d-lg-block d-none poi">
										<div class="h-menu-sec">
											<div class="menu_move">
										    	<div class="menu_des">
										         <ul class="menu">
										            @if(count($menu_array) > 0)
											            @foreach($menu_array as $menu_key => $menu_value)
											            <li class="sub-nav">
															@php 
																$colourClass =  ($menu_value['menu_title'] == 'SALES & OFFERS') ? 'class="sale-menu"' : '';
															@endphp

															@php 
																$alignClass =  ($menu_value['menu_title'] == 'Discovery Bundles' || $menu_value['menu_title'] == 'Candles' || $menu_value['menu_title'] == 'Bath & Body') ? 'mm-last-img' : '';
															@endphp

											               <a href="{{ $menu_value['menu_link'] }}" {!! $colourClass !!}>{{ $menu_value['menu_title'] }}</a>
														    @if($menu_value['menu_title'] != "SALES & OFFERS")
										            			<div class="navsub-arrow"></div>
															@endif
										            		@if($menu_value['label_count'] > 0)
															<div class="nav-dropdown nav-sub-mm">
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
																						   <a href="{{ $banner_link }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image'] }}" width="250" height="250" alt="" />
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
																						   <a href="{{ $banner_link1 }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image1'] }}" alt="" width="250" height="250" />
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
																						   <a href="{{ $banner_link2 }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image2'] }}" alt="" width="250" height="250" />
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
																							   <a href="{{ $banner_link }}">
																							   <img src="{{ $label_value['childs'][0]['menu_image'] }}" alt="" width="250" height="250" />
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
																							   <a href="{{ $banner_link1 }}">
																							   <img src="{{ $label_value['childs'][0]['menu_image1'] }}" alt="" width="250" height="250" />
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
																							   <a href="{{ $banner_link2 }}">
																							   <img src="{{ $label_value['childs'][0]['menu_image2'] }}" alt="" width="250" height="250" />
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
																						   <a href="{{ $banner_link }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image'] }}" alt="" width="250" height="250" />
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
																						   <a href="{{ $banner_link1 }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image1'] }}" alt="" width="250" height="250" />
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
																						   <a href="{{ $banner_link2 }}">
																						   <img src="{{ $label_value['childs'][0]['menu_image2'] }}" alt="" width="250" height="250" />
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
																				<h5 {!! $h5_class !!}><a href="{{ $menu_link }}">{{ $label_value['menu_title'] }}</a></h5>
																				@endif
																				@if(!empty($label_value['childs']) && count($label_value['childs']) > 0)
																				<ul @if($menu_key != 0 && (($menu_value['menu_title'] != 'Fragrance' || $label_value['menu_title'] != 'Categories') || config('typevalofcriteo') == 'd')) class="mm-sub" @endif >
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
										            <li class="sub-nav clearfix">
										               <a href="{{ url('brand-name-perfumes.html') }}">Brands</a>
										               <div class="navsub-arrow"></div>
										               <div class="nav-dropdown nav-sub-mm brands_tpmain">
										                  <div class="container">
										                     <div class="brands_tpmenu">
										                        <div class="left_box">
										                           <form>
										                              <div class="inner_search">
										                                 <div class="insearch_box">
										                                    <input type="text" id="search_brands" name="search_brands" placeholder="Search Brands" class="form-control">
										                                    <svg class="sv-search vam" aria-hidden="true" role="img" width="25" height="27">
										                                       <use href="#sv-search" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-search"></use>
										                                    </svg>
										                                 </div>
										                              </div>
										                              <div class="drop_inner drop_innersfd">
										                                 @if(count($brandlist) > 0)
										                                 <ul class="mmsub_alp text-center">
										                                 	@foreach($brandlist as $brand_alpha_key => $brand_alpha_value)
										                                    <li><a href="javascript:void(0);" onclick="move('{{ $brand_alpha_key }}')">{{ $brand_alpha_key }}</a></li>
										                                    @endforeach
										                                 </ul>
										                                 @endif
										                                 @if(count($brandlist) > 0)
										                                 <h5>Top Brands</h5>
										                                 <ul id="topbrands_scoller" class="mm-sub">
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
										                              <ul class="inner_newbrand">
										                                 @if(count($popular_brands) > 0)
										                                 	@foreach($popular_brands as $popular_brand_key => $popular_brand_value)
											                                 <li>
											                                    <a href="{{ $popular_brand_value['Link'] }}">
											                                    <img src="{{ $popular_brand_value['ImageLogo'] }}" alt="{{ $popular_brand_value['Name'] }}" width="108" height="73" />
											                                    </a>
											                                 </li>
										                                    @endforeach
										                                 @endif
										                                 <div class="text-center viewmore_brand">
										                                    <a href="{{ config('global.SITE_URL') }}brand-name-perfumes.html">View More Brands</a>
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
											<ul class="header_icon">
												<li class="d-lg-inline-block d-none user-hlink"> 
													@guest
													<a href="{{config('global.SITE_URL').'login.html'}}" title="Sign In">
														<svg class="sv-user vam" aria-hidden="true" role="img" width="22" height="22">
															<use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
														</svg>
													</a>
													@endguest
													@auth
													<a href="{{config('global.SITE_URL').'myaccount.html'}}" title="Sign In">
														<svg class="sv-user vam" aria-hidden="true" role="img" width="22" height="22">
															<use href="#sv-user" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-user"></use>
														</svg>
													</a>
													@endauth
													<div class="user-dropdown">
						             					<div class="user-top-head">
															<div class="row">
																@auth
																<div class="col-12">
																	<div class="user-sec-text">
																		<div class="drop-user-icon pr-2">
																			<span class="circle">
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
																		<div class="col-sm-6"><a href="{{config('global.SITE_URL').'login.html'}}" class="btn btn-primary d-block">Sign In</a></div>
																		<div class="col-sm-6"><a href="{{config('global.SITE_URL').'register.html'}}" class="btn btn-secondary d-block">Sign up</a></div>
																	</div>
																</div>
																<div class="col-12 pt-2 pb-1">
																	<div class="dividerr-or"><span>or login with</span></div>
																</div>
																<div class="col-12 pb-1 d-flex justify-content-center align-items-center">
																	<a href="{{url('/redirect')}}" title="Login with Facebook" class="user-soc-link"> <svg class="sv-facebook vam" aria-hidden="true" role="img" width="18" height="17"><use href="#sv-facebook" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-facebook"></use></svg>Facebook</a>
																	<a href="{{url('/redirectgoogle')}}"  title="Login with Gmail" class="user-soc-link ml-4"><svg class="sv-gmail-0 vam mr-1" aria-hidden="true" role="img" width="18" height="18"><use href="#sv-gmail-0" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-gmail-0"></use>
																			</svg>Gmail
																	</a>
																</div>
																@endguest
															</div>
														</div>
														@auth	
														<div class="user-top-middle">
															<ul>
																<li>
																	<a href="{{config('global.SITE_URL')}}order-history.html">
																		<svg class="sv_orders vam mr-1" aria-hidden="true" role="img" width="24" height="24">
												                        <use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
																		</svg>
																		<span>My Orders</span>
																	</a>
																</li>
																<li>
																	<a href="{{config('global.SITE_URL')}}order-item-return.html">
																		<svg class="sv_returns vam mr-1" aria-hidden="true" role="img" width="24" height="24">
												                        <use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
																		</svg> 
																		<span>My Returns</span>
																	</a>
																</li>
																<li>
																	<a href="{{config('global.SITE_URL')}}myrewardpoint.html">
																		<svg class="sv_reward_points vam mr-1" aria-hidden="true" role="img" width="24" height="24">
												                        <use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
																		</svg> 
																		<span>My Aroma Reward</span>
																	</a>
																</li>
																<li>
																	<a href="{{config('global.SITE_URL')}}wish-category.html">
																		<svg class="sv_wishlist vam mr-1" aria-hidden="true" role="img" width="24" height="24">
												                        <use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
																		</svg> 
																		<span>My Wishlist</span>
																	</a>
																</li>
																<li>
																	<a href="{{config('global.SITE_URL')}}logout.html">
																		<svg class="sv_logout vam mr-1" aria-hidden="true" role="img" width="24" height="24">
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
												<li>
													<a href="javascript:void(0)" class="cart-link" title="View your cart">
														<svg class="sv-cartnw vam" aria-hidden="true" role="img" width="22" height="22">
															<use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use>
														</svg>
														@if(Session::has ('ShoppingCart.TotalItemInCart') && Session::get('ShoppingCart.TotalItemInCart') > 0)	
															 <span class="cart-qty">
																{{Session::get('ShoppingCart.TotalItemInCart')}}
															</span>
														@else
															<span class="cart-qty" style="display:none;">
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
