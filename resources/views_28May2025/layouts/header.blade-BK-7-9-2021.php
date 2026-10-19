@if(config('global.cntOffer') == 1)
<div class="h-fixed d-flex">
	<div class="parsent-hd">%</div>
	<div class="parsent-box"> <a href="#" onclick="DisplayPopupOffer();" data-toggle="modal" data-target="#sales-offers-popup">New & Sale</a> </div>
</div>
@endif
<div id="sales-offers-popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true"></div>
<div id="coupon-modal-popup_ajax" data-status="{{ Session::get('showPopUp')}}" ></div>
<div id="myModalPopUpLogin" class=" modal fade login_popup" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="NicheFragrancesPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="ProductAlertMePopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="EmailFriendPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="ProductRatingsReviewPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="ProductQuickViewPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="FreeGiftViewPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="AddFundPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
<div id="ShippingCalculatePopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true"></div>
<div id="FreeShippingPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true"></div>
<div id="ShippingServicePopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true"></div>
<div id="WholesalerShippingPolicyPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="small_popup1" aria-hidden="true"></div>

@include('layouts.sidecart')
@include('layouts.mobile_menu')
<div class="sb-slidebar sb-left" data-sb-width="290px">
	<div class="mobile-nav" id="mobiNav"></div>
</div>
<div id="sb-site">
	@include('layouts.menu')
