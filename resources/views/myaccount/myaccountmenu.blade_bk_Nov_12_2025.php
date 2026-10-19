<?php $current_route = Route::current()->uri(); ?>
@php
	$initials = '';
	if(Auth::user()->first_name != '') {
		$initials = substr(trim(Auth::user()->first_name), 0, 1);
	}
	if(Auth::user()->first_name != '' && Auth::user()->last_name != '') {
		$initials = substr(trim(Auth::user()->first_name), 0, 1).substr(trim(Auth::user()->last_name), 0, 1);
	}
@endphp

<div class="myact_box">
	<div class="myact_user_name"><span class="circle">{{ $initials }}</span>
		<div class="text">
			Hi,<span>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
			@if(Session::has('sess_icustomerid') && Session::get('sess_icustomerid') != '')
				<span>Account #{{Session::get('sess_icustomerid')}}</span>
			@endif
		</div>
	</div>
	
	<div class="myact_userstatus">
		@if(Session::get('InProgressOrders') != '')
		<div class="userstatus-box orders_status">
			<svg class="sv_orders" aria-hidden="true" role="img">
				<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
			</svg>
			<span>You have <b>{{ Session::get('InProgressOrders') }}</b> orders in progress</span>
		</div>
		@endif
		
		@if(Session::get('eusertype')=='Retailer')
			@if(config('global.YOTPO_PROG') == true)
				<div class="userstatus-box reward_status">
					<svg class="sv_reward_points" aria-hidden="true" role="img">
						<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
					</svg>
					<span>You have <b>{{ Session::get('YotpoRewardPoints') }}</b> Reward points</span>
				</div>
			@else
				<div class="userstatus-box reward_status">
					<svg class="sv_reward_points" aria-hidden="true" role="img">
						<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
					</svg>
					<span>You have <b>{{ Session::get('reward_point') }}</b> Reward points</span>
				</div>
			@endif
			@if(config('global.YOTPO_PROG') == true && (Session::get('LoyaltyTier') != ''))
				<div class="userstatus-box membership_status">
					@if(Session::get('LoyaltyTier') == 'Bronze' || Session::get('LoyaltyTier') == 'bronze')
					<svg class="svg-membership-bronze" aria-hidden="true" role="img">
						<use href="#svg-membership-bronze" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-membership-bronze"></use>
					</svg>
					@elseif(Session::get('LoyaltyTier') == 'Silver' || Session::get('LoyaltyTier') == 'silver')
					<svg class="svg-membership-silver" aria-hidden="true" role="img">
						<use href="#svg-membership-silver" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-membership-silver"></use>
					</svg>
					@elseif(Session::get('LoyaltyTier') == 'Gold' || Session::get('LoyaltyTier') == 'gold')
					<svg class="svg-membership-gold" aria-hidden="true" role="img">
						<use href="#svg-membership-gold" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-membership-gold"></use>
					</svg>
					@endif
					<span>You have <b>{{ Session::get('LoyaltyTier') }}</b> membership</span>
				</div>
			@endif
		@endif
	</div>
</div>

<div class="myact_nav d-lg-none">
	<svg class="svg-list vam" aria-hidden="true" role="img" width="24" height="24">
   		<use href="#svg-list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-list"></use>
   	</svg>My Account Links
</div>
<div class="myact_nav_cot">
   <?php /* ?>
   <div class="myact_box">
	  <ul class="myact_left_link">
			<li><a href="{{config('global.SITE_URL').'myaccount.html'}}" @if($current_route == 'myaccount.html') class="active" @endif><svg class="sv_account_overview" aria-hidden="true" role="img">
				<use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
				</svg><span>Account Overview</span></a>
			</li>
			<li><a href="{{config('global.SITE_URL').'order-history.html'}}" @if($current_route == 'order-history.html') class="active" @endif><svg class="sv_orders" aria-hidden="true" role="img">
				<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
				</svg><span>My Orders</span></a>
			</li>
			<li><a href="{{config('global.SITE_URL').'return-orders.html'}}" @if($current_route == 'return-orders.html') class="active" @endif><svg class="sv_returns" aria-hidden="true" role="img">
				<use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
				</svg><span>My Returns</span></a>
			</li>
	        @if(Session::get('eusertype')=='Retailer')
			<li><a href="{{config('global.SITE_URL').'myrewardpoint.html'}}" @if($current_route == 'myrewardpoint.html') class="active" @endif><svg class="sv_reward_points" aria-hidden="true" role="img">
				<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
				</svg><span>My Reward Points</span></a>
			</li>
			@endif
			<li><a href="{{config('global.SITE_URL').'wish-category.html'}}" @if($current_route == 'wish-category.html') class="active" @endif><svg class="sv_wishlist" aria-hidden="true" role="img">
				<use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
				</svg><span>My Wishlist</span></a>
			</li>
			<li><a href="{{config('global.SITE_URL').'cancel-orders.html'}}" @if($current_route == 'cancel-orders.html') class="active" @endif><svg class="sv_cancellation" aria-hidden="true" role="img">
				<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
				</svg><span>My Cancellation</span></a>
			</li>
			@if(Session::get('is_dropshipper')!='Yes')
			 	<li><a href="{{config('global.SITE_URL').'credit-summary.html'}}" @if($current_route == 'credit-summary.html') class="active" @endif><svg class="sv_cancellation" aria-hidden="true" role="img">
				<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
				</svg><span>
		        @if(Session::get('eusertype')=='Wholesaler')
		        My Credit Report
		        @else
		        My Store Credit
		        @endif
	    		</span></a>
    		</li>
			@endif
	  </ul>
   </div>
   <div class="myact_box">
	  <ul class="myact_left_link">
		 <li><a href="{{config('global.SITE_URL').'editprofile.html'}}" @if($current_route == 'editprofile.html') class="active" @endif><svg class="sv_details" aria-hidden="true" role="img">
			<use href="#sv_details" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_details"></use>
			</svg><span>My Details</span></a>
		 </li>
		 <li><a href="{{config('global.SITE_URL').'changepassword.html'}}" @if($current_route == 'changepassword.html') class="active" @endif><svg class="sv_change_password" aria-hidden="true" role="img">
			<use href="#sv_change_password" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_change_password"></use>
			</svg><span>Change Password</span></a>
		 </li>
		 <li><a href="{{config('global.SITE_URL').'addressbook.html'}}" @if($current_route == 'addressbook.html') class="active" @endif><svg class="sv_address_book" aria-hidden="true" role="img">
			<use href="#sv_address_book" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_address_book"></use>
			</svg><span>Address Book</span></a>
		 </li>
        <!--  
		 <li><a href="{{config('global.SITE_URL').'referral-customer.html'}}" @if($current_route == 'referral-customer.html') class="active" @endif><svg class="sv_referral_customer" aria-hidden="true" role="img">
			<use href="#sv_referral_customer" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_referral_customer"></use>
			</svg><span>Referral Customer</span></a>
		 </li> -->
		 <!-- <li><a href="#"><svg class="sv_payment_methods" aria-hidden="true" role="img">
			<use href="#sv_payment_methods" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_payment_methods"></use>
			</svg><span>Payment Methods</span></a>
		 </li> -->		 
		 <li><a href="{{config('global.SITE_URL').'contact-us.html'}}"><svg class="sv_contact_preferances" aria-hidden="true" role="img">
			<use href="#sv_contact_preferances" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_contact_preferances"></use>
			</svg><span>Contact Preferances</span></a>
		 </li>
	  </ul>
   </div>
	@if (Session::get('eusertype')=='Wholesaler')
	<div class="myact_box">
	  <ul class="myact_left_link">
		<li><a href="{{config('global.SITE_URL').'special-product-list'}}"><svg class="sv_spp_list" aria-hidden="true" role="img">
			<use href="#sv_spp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_spp_list"></use>
			</svg><span>Special Product Price List</span></a>
		</li>
		<li><a href="{{config('global.SITE_URL').'downloadpricelist.html'}}"><svg class="sv_dpp_list" aria-hidden="true" role="img">
			<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
			</svg><span>Download Product Price List</span></a>
		</li>
		@if(Session::get('is_dropshipper')=='Yes' && config('Settings.DROPSHIPPER_SYSTEM_FLAG')=="Yes")
		<li><a href="{{config('global.SITE_URL').'orderftp.html'}}" @if($current_route == 'orderftp.html') class="active" @endif><svg class="sv_edit_ftp" aria-hidden="true" role="img">
			<use href="#sv_edit_ftp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_edit_ftp"></use>
			</svg><span>Order FTP</span></a>
		</li>
		<li><a href="{{config('global.SITE_URL').'productftp.html'}}" @if($current_route == 'productftp.html') class="active" @endif><svg class="sv_edit_ftp" aria-hidden="true" role="img">
			<use href="#sv_edit_ftp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_edit_ftp"></use>
			</svg><span>Product FTP</span></a>
		</li>
		<li><a href="{{config('global.SITE_URL').'import-order.html'}}" @if($current_route == 'import-order.html') class="active" @endif><svg class="sv_id_orders" aria-hidden="true" role="img">
			<use href="#sv_id_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_id_orders"></use>
			</svg><span>Import your orders using CSV file </span></a>
		</li>
		<li><a href="{{config('global.SITE_URL').'imported-order-list.html'}}" @if($current_route == 'imported-order-list.html') class="active" @endif><svg class="sv_dio_list" aria-hidden="true" role="img">
			<use href="#sv_dio_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dio_list"></use>
			</svg><span>Review and pay for the imported orders<br/><small>*Note: When you pay for your orders you can see them in MY ORDERS TAB.</small></span></a>
		</li>
		@endif
		@if(Session::get('is_dropshipper')=='Yes')
		<li><a href="{{config('global.SITE_URL').'exportorders.html'}}" @if($current_route == 'exportorders.html') class="active" @endif><svg class="sv_df_list" aria-hidden="true" role="img">
			<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
			</svg><span>Download Tracking numbers in Bulk<br/><small>*Note: You can download your orders tracking number in bulk in order to upload it in to your website.</small></span></a>
		</li>
		<li><a href="{{config('global.SITE_URL').'dropshipper-fund-summary.html'}}" @if($current_route == 'dropshipper-fund-summary.html') class="active" @endif><svg class="sv_df_list" aria-hidden="true" role="img">
			<use href="#sv_df_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_df_list"></use>
			</svg><span>Dropshipper Fund List</span></a>
		</li>
		@endif
		
		@if(Session::get('is_dropshipper')!='Yes' && Session::has('SpecialCustomerFlag') && Session::get('SpecialCustomerFlag')=="Yes")
		<li><a href="{{config('global.SITE_URL').'specialwholesaleproductpricelist'}}"><svg class="sv_df_list" aria-hidden="true" role="img">
			<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
			</svg><span>Download Special Wholesaler List</span></a>
		</li>
		@endif
	  </ul>
	</div>
	@endif
   <div class="myact_box">
	  <ul class="myact_left_link">
		 <!-- <li><a href="#"><svg class="sv_gift_card" aria-hidden="true" role="img">
			<use href="#sv_gift_card" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_gift_card"></use>
			</svg><span>E-Gift Cards</span></a></li> -->
		@if(Session::get('is_dropshipper')=='Yes' && generalsetting("DROPSHIPPER_VIDEO1") != '' && generalsetting("DROPSHIPPER_VIDEO2") != '' && generalsetting("DROPSHIPPER_VIDEO3") != '' && generalsetting("DROPSHIPPER_VIDEO4") != '')	
			<li><a href="{{config('global.SITE_URL').'help-videos.html'}}"><svg class="sv_helpvideo" aria-hidden="true" role="img">
				<use href="#sv_helpvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_helpvideo"></use>
				</svg><span>Help Videos</span></a></li>	
		 @endif	
		 <li><a href="{{config('global.SITE_URL').'contact-us.html'}}"><svg class="sv_help" aria-hidden="true" role="img">
			<use href="#sv_help" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_help"></use>
			</svg><span>Need Help?</span></a></li>
		 <li><a href="{{config('global.SITE_URL').'track-order.html'}}"><svg class="sv_location" aria-hidden="true" role="img">
			<use href="#sv_location" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_location"></use>
			</svg><span>Where’s My Order?</span></a></li>
		 <li><a href="{{config('global.SITE_URL').'return-exchange-policy.html'}}"><svg class="sv_return_box" aria-hidden="true" role="img">
			<use href="#sv_return_box" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_return_box"></use>
			</svg><span>How Do I Make a Return?</span></a></li>
	  </ul>
   </div>
   <div class="myact_box last">
	  <ul class="myact_left_link">
		 <li><a href="{{url('/logout.html')}}"><svg class="sv_logout" aria-hidden="true" role="img">
			<use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use>
			</svg><span>Sign Out</span></a></li>
	  </ul>
   </div>
   <?php */ ?>
   
   
   <!-- new menu for menu & submenu structure  --> 
	<div class="myact_box">
		<ul class="myact_left_link" id="dashboard-menu">
			<li>
				<a href="{{config('global.SITE_URL').'myaccount.html'}}" @if($current_route == 'myaccount.html') class="active" @endif>
					<svg class="sv_account_overview" aria-hidden="true" role="img">
						<use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
					</svg>
					<span>Dashboard</span>
				</a>
			</li>
			
			<li>
				<a href="javascript:void(0);" class="has-submenu" role="button">
					<svg class="sv_account_overview" aria-hidden="true" role="img">
						<use href="#sv_account_overview" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_account_overview"></use>
					</svg>
					<span>Account Details</span>
					<div class="submenu-toggle"></div>
				</a>
				<ul class="myact_left_link submenu" style="display:none;">
					<li>
						<a href="{{config('global.SITE_URL').'editprofile.html'}}" @if($current_route == 'editprofile.html') class="active" @endif>
							<svg class="sv_details" aria-hidden="true" role="img">
								<use href="#sv_details" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_details"></use>
							</svg>
							<span>My Details</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'changepassword.html'}}" @if($current_route == 'changepassword.html') class="active" @endif>
							<svg class="sv_change_password" aria-hidden="true" role="img">
								<use href="#sv_change_password" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_change_password"></use>
							</svg>
							<span>Change Password</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'addressbook.html'}}" @if($current_route == 'addressbook.html') class="active" @endif>
							<svg class="sv_address_book" aria-hidden="true" role="img">
								<use href="#sv_address_book" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_address_book"></use>
							</svg>
							<span>Address Book</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'contact-us.html'}}">
							<svg class="sv_contact_preferances" aria-hidden="true" role="img">
								<use href="#sv_contact_preferances" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_contact_preferances"></use>
							</svg>
							<span>Contact Preferances</span>
						</a>
					</li>
				</ul>
			</li>
			
			<li>
				<a href="javascript:void(0);" class="has-submenu" role="button">
					<svg class="sv_orders" aria-hidden="true" role="img">
						<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
					</svg>
					<span>Orders & Returns</span>
					<div class="submenu-toggle"></div>
				</a>
				<ul class="myact_left_link submenu" style="display:none;">
					<li>
						<a href="{{config('global.SITE_URL').'order-history.html'}}" @if($current_route == 'order-history.html') class="active" @endif>
							<svg class="sv_orders" aria-hidden="true" role="img">
								<use href="#sv_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_orders"></use>
							</svg>
							<span>Orders</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'return-orders.html'}}" @if($current_route == 'return-orders.html') class="active" @endif>
							<svg class="sv_returns" aria-hidden="true" role="img">
								<use href="#sv_returns" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_returns"></use>
							</svg>
							<span>Returns</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'cancel-orders.html'}}" @if($current_route == 'cancel-orders.html') class="active" @endif>
							<svg class="sv_cancellation" aria-hidden="true" role="img">
								<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
							</svg>
							<span>Cancellation</span>
						</a>
					</li>
				</ul>
			</li>
			
			<li>
				<a href="javascript:void(0);" class="has-submenu" role="button">
					<svg class="sv_reward_points" aria-hidden="true" role="img">
						<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
					</svg>
					<span>Rewards & Wishlist</span>
					<div class="submenu-toggle"></div>
				</a>
				<ul class="myact_left_link submenu" style="display:none;">
					@if(Session::get('eusertype')=='Retailer')
						<li>
							<a href="{{config('global.SITE_URL').'myrewardpoint.html'}}" @if($current_route == 'myrewardpoint.html') class="active" @endif>
								<svg class="sv_reward_points" aria-hidden="true" role="img">
									<use href="#sv_reward_points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_reward_points"></use>
								</svg>
								<span>Reward Points</span>
							</a>
						</li>
					@endif
					<li>
						<a href="{{config('global.SITE_URL').'wish-category.html'}}" @if($current_route == 'wish-category.html') class="active" @endif>
							<svg class="sv_wishlist" aria-hidden="true" role="img">
								<use href="#sv_wishlist" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_wishlist"></use>
							</svg>
							<span>Wishlist</span>
						</a>
					</li>
					@if(Session::get('is_dropshipper')!='Yes')
						<li>
							<a href="{{config('global.SITE_URL').'credit-summary.html'}}" @if($current_route == 'credit-summary.html') class="active" @endif>
								<!-- <svg class="sv_cancellation" aria-hidden="true" role="img">
									<use href="#sv_cancellation" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_cancellation"></use>
								</svg> -->
								<svg class="svg-Credit-report" aria-hidden="true" role="img">
									<use href="#svg-Credit-report" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-Credit-report"></use>
								</svg>
								<span>
									@if(Session::get('eusertype')=='Wholesaler')
										Credit Report
									@else
										Store Credit
									@endif
								</span>
							</a>
						</li>
					@endif
				</ul>
			</li>
			
			@if (Session::get('eusertype')=='Wholesaler')
				<li>
					<a href="javascript:void(0);" class="has-submenu" role="button">
						<svg class="sv_spp_list" aria-hidden="true" role="img">
							<use href="#sv_spp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_spp_list"></use>
						</svg>
						<span>FTP & Price List</span>
						<div class="submenu-toggle"></div>
					</a>
					<ul class="myact_left_link submenu" style="display:none;">
						<li>
							<a href="{{config('global.SITE_URL').'special-product-list'}}">
								<svg class="sv_spp_list" aria-hidden="true" role="img">
									<use href="#sv_spp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_spp_list"></use>
								</svg>
								<span>Special Product Price List</span>
							</a>
						</li>
						<li>
							<a href="{{config('global.SITE_URL').'downloadpricelist.html'}}">
								<svg class="sv_dpp_list" aria-hidden="true" role="img">
									<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
								</svg>
								<span>Download Product Price List</span>
							</a>
						</li>
						@if(Session::get('is_dropshipper')=='Yes' && config('Settings.DROPSHIPPER_SYSTEM_FLAG')=="Yes")
							<li>
								<a href="{{config('global.SITE_URL').'orderftp.html'}}" @if($current_route == 'orderftp.html') class="active" @endif>
									<svg class="sv_edit_ftp" aria-hidden="true" role="img">
										<use href="#sv_edit_ftp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_edit_ftp"></use>
									</svg>
									<span>Order FTP</span>
								</a>
							</li>
							<li>
								<a href="{{config('global.SITE_URL').'productftp.html'}}" @if($current_route == 'productftp.html') class="active" @endif>
									<svg class="sv_edit_ftp" aria-hidden="true" role="img">
										<use href="#sv_edit_ftp" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_edit_ftp"></use>
									</svg>
									<span>Product FTP</span>
								</a>
							</li>
							<li>
								<a href="{{config('global.SITE_URL').'import-order.html'}}" @if($current_route == 'import-order.html') class="active" @endif>
									<svg class="sv_id_orders" aria-hidden="true" role="img">
										<use href="#sv_id_orders" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_id_orders"></use>
									</svg>
									<span>Import your orders using CSV file</span>
								</a>
							</li>
							<li>
								<a href="{{config('global.SITE_URL').'imported-order-list.html'}}" @if($current_route == 'imported-order-list.html') class="active" @endif>
									<svg class="sv_dio_list" aria-hidden="true" role="img">
										<use href="#sv_dio_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dio_list"></use>
									</svg>
									<span>Review and pay for the imported orders<br/><small>*Note: When you pay for your orders you can see them in MY ORDERS TAB.</small></span>
								</a>
							</li>
						@endif

						@if(Session::get('is_dropshipper')=='Yes')
							<li>
								<a href="{{config('global.SITE_URL').'exportorders.html'}}" @if($current_route == 'exportorders.html') class="active" @endif>
									<svg class="sv_df_list" aria-hidden="true" role="img">
										<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
									</svg>
									<span>Download Tracking numbers in Bulk<br/><small>*Note: You can download your orders tracking number in bulk in order to upload it in to your website.</small></span>
								</a>
							</li>
							<li>
								<a href="{{config('global.SITE_URL').'dropshipper-fund-summary.html'}}" @if($current_route == 'dropshipper-fund-summary.html') class="active" @endif>
									<svg class="sv_df_list" aria-hidden="true" role="img">
										<use href="#sv_df_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_df_list"></use>
									</svg>
									<span>Dropshipper Fund List</span>
								</a>
							</li>
						@endif

						@if(Session::get('is_dropshipper')!='Yes' && Session::has('SpecialCustomerFlag') && Session::get('SpecialCustomerFlag')=="Yes")
							<li>
								<a href="{{config('global.SITE_URL').'specialwholesaleproductpricelist'}}">
									<svg class="sv_df_list" aria-hidden="true" role="img">
										<use href="#sv_dpp_list" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_dpp_list"></use>
									</svg>
									<span>Download Special Wholesaler List</span>
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif
			
			<li>
				<a href="javascript:void(0);" class="has-submenu" role="button">
					<svg class="sv_help" aria-hidden="true" role="img">
						<use href="#sv_help" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_help"></use>
					</svg>
					<span>Help & Info</span>
					<div class="submenu-toggle"></div>
				</a>
				<ul class="myact_left_link submenu" style="display:none;">
					<li>
						<a href="{{config('global.SITE_URL').'contact-us.html'}}">
							<svg class="sv_help" aria-hidden="true" role="img">
								<use href="#sv_help" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_help"></use>
							</svg>
							<span>Need Help?</span>
						</a>
					</li>
					@if(Session::get('is_dropshipper')=='Yes' && generalsetting("DROPSHIPPER_VIDEO1") != '' && generalsetting("DROPSHIPPER_VIDEO2") != '' && generalsetting("DROPSHIPPER_VIDEO3") != '' && generalsetting("DROPSHIPPER_VIDEO4") != '')	
						<li>
							<a href="{{config('global.SITE_URL').'help-videos.html'}}">
								<svg class="sv_helpvideo" aria-hidden="true" role="img">
									<use href="#sv_helpvideo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_helpvideo"></use>
								</svg>
								<span>Help Videos</span>
							</a>
						</li>	
					@endif
					<li>
						<a href="{{config('global.SITE_URL').'track-order.html'}}">
							<svg class="sv_location" aria-hidden="true" role="img">
								<use href="#sv_location" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_location"></use>
							</svg>
							<span>Where’s My Order?</span>
						</a>
					</li>
					<li>
						<a href="{{config('global.SITE_URL').'return-exchange-policy.html'}}">
							<svg class="sv_return_box" aria-hidden="true" role="img">
								<use href="#sv_return_box" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_return_box"></use>
							</svg>
							<span>How Do I Make a Return?</span>
						</a>
					</li>
				</ul>
			</li>

			<li>
				<a href="{{url('/logout.html')}}">
					<svg class="sv_logout" aria-hidden="true" role="img">
						<use href="#sv_logout" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv_logout"></use>
					</svg>
					<span>Sign Out</span>
				</a>
			</li>
		</ul>
	</div>
</div>

<script>
	$(document).ready(function() {
		//$('.submenu').css("display","none");
	});

	$(".has-submenu").click(function (e) {
		e.preventDefault();

		let $parentLi = $(this).closest("li");
		let $submenu = $parentLi.children(".submenu");
		let $toggle = $(this).find(".submenu-toggle");

		if ($submenu.is(":visible")) {
			$submenu.slideUp();
			$toggle.removeClass("submenu-open");
		} else {
			$submenu.slideDown();
			$toggle.addClass("submenu-open");
		}
	});

	
	// $(".submenu-toggle").click(function(){
	// 	if($(this).text() == '+'){
	// 		$(this).text('-');
	// 		$(this).next('.submenu').toggle();
	// 	}else{
	// 		$(this).text('+');
	// 		$(this).next('.submenu').toggle();
	// 	}
	// });
</script>
