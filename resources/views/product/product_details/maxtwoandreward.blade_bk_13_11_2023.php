<div class="row">
    @if ($productDetails->maxtwodaydelivery == 'Yes' && $Max2Day == 'Yes' && $productDetails->IsCosmo == 'No' && $productDetails->IsPCA == 'No' && $productDetails->IsNandansons == 'No')
        <div class="col-lg-6 col-md-12 col-sm-6">
            <div class="dtl_exp_sip">
                <img class="maxday-ico svg-watch icon" src="{{config('global.SITE_URL')}}images/maxtwoday-dtl.png" loading="lazy"  width="36" height="17">
                <span>
                    {{ $maxtwodaycontent }}, Order within 
                    <strong>
                    @if($Maxhour > 0){{ $Maxhour }} hrs @endif
                    @if($Maxmin > 0){{ $Maxmin }} minutes. @endif 
                    </strong> and choose <strong>2-day Shipping!</strong> at checkout.
                </span>
                <div class="max_qttable">
                    <a href="javascript:void(0);"><u>i</u></a>
                    <div class="tables">
                        We offer guaranteed delivery on certain fast delivery speeds and select products. When guaranteed delivery is available on an order, it will be clearly marked on the checkout page with an associated delivery date and fee (if applicable).<br />
                        If we provide a guaranteed delivery date and a delivery attempt is not made by this date, we will provide a refund of any shipping fees associated with that order.<br />
                        Note: The order within countdown reflects Maxaroma's delivery capability at the moment the page was opened. The delivery day may become unavailable within that time frame, prior to completing your order, due to changes in inventory or delivery capacity.
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-6 col-md-12 col-sm-6">
            <div class="dtl_exp_sip">
                <svg class="svg-newship icon" aria-hidden="true" role="img" width="35" height="20">
                    <use href="#svg-newship" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newship"></use>
                </svg>
                <span>
                    <strong>Get it as soon as {{ $totalEstimate }}</strong><br />
                    When you choose <strong>Expedited shipping</strong> at checkout.
                </span>
                <div class="max_qttable">
                    <a href="javascript:void(0);"><u>i</u></a>
                    <div class="tables">This is an estimated delivery date, and it is not guaranteed.</div>
                </div>
            </div>
        </div>
    @endif
    @if ($reward_points_text)
        <div class="col-lg-6 col-md-12 col-sm-6">
            <div class="dtl_exp_sip">
                <svg class="svg-points icon" aria-hidden="true" role="img" width="25" height="25">
                    <use href="#svg-points" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-points"></use>
                </svg>
                <span>
                    Members Get More <strong>{{$productDetails->point_multiplier}}x Points</strong> for this item {!! $reward_points_text !!}
                    after purchase.<br>
                    <a class="ulink" href="<?=config('global.SITE_URL')?>reward-point-program.html" target="_blank">
                        <span>View Reward Program</span> 
                    </a>
                </span>
            </div>
        </div>
    @endif
</div>