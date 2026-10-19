<div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.85); z-index:9999; text-align:center;">
    <div style="position:absolute; top:40%; left:50%; transform:translate(-50%, -50%);">
        <div class="spinner" style="border:6px solid #f3f3f3; border-top:6px solid #3498db; border-radius:50%; width:60px; height:60px; animation:spin 1s linear infinite; margin:auto;"></div>
        <p style="margin-top:15px; font-size:16px; color:#333;">Processing payment... Please wait</p>
        <button id="cancelPaymentBtn" style="margin-top:10px; background:#e74c3c; color:#fff; border:none; padding:8px 18px; border-radius:6px; cursor:pointer;">Cancel Payment</button>
    </div>
</div>

<style>
    .clsgreen{color:green;font-weight:600;}
    @keyframes spin {
        0%{transform:rotate(0deg);}
        100%{transform:rotate(360deg);}
    }
</style>

<form method="post" action="{{route('storeupdateorder')}}" name="order_process" id="order_process">
    <input type="hidden" name="shipping_signature" id="shipping_signature" value="{{$shipping_signature}}" />
    <input type="hidden" name="OrderStoreTypeValue" id="OrderStoreTypeValue" value="@if(session('ShoppingCart.OrderStoreTypeValue')){{session('ShoppingCart.OrderStoreTypeValue')}}@endif" />
    <div class="row">
        <div class="col-12 d-md-none">
            <div class="mobile-shadow mb-0">&nbsp;</div>
        </div>
        <div class="col-12">
            <h4 class="cart-hd-sub text-center pb-4">Payment</h4>
        </div>
        <div class="col-12">
            @if(isset($CardReaderName))
                <div class="pb-2">
                    @if(!empty($CardReaderName))
                        <strong>Card Reader:</strong> {{ $CardReaderName }}
                    @elseif(Session::has('ConnectedCardReaderName') && !empty(Session::get('ConnectedCardReaderName')))
                        </strong>Card Reader:</strong> {{ Session::get('ConnectedCardReaderName') }}
                    @else
                        </strong>Card Reader:</strong>
                    @endif

                    @if($CardReaderStatus == 'ONLINE')
                        <span style="color:green;font-weight:600;padding:5px;">({{ $CardReaderStatus }})</span>
                    @else
                        <span style="color:#ec040f;font-weight:600;padding:5px;">({{ $CardReaderStatus }})</span>
                    @endif
                    {{--
                    @if((!empty($CardReaderName) || Session::has('ConnectedCardReaderName')) && $CardReaderStatus == 'OFFLINE')
                        <span><a href="javascript:void(0);" class="posstatus-link" id="resetReader" style="text-decoration: none;">Reset</a></span>
                    @endif
                    --}}

                    @if(isset($CardReaders) && count($CardReaders) > 1)
                        <a href="javascript:void(0);" class="posstatus-link" id="change_cardreader_link" title="Change Card Reader" aria-label="Change Card Reader">
                            <svg class="sv-edit" aria-hidden="true" role="img" width="18" height="18">
                                <use href="#sv-edit" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-edit"></use>
                            </svg>
                        </a>
                        <div id="change_cardreader_div" style="display:none;">
                            <input type="hidden" name="selcardreader" id="selcardreader" value="{{ Session::get('ConnectedCardReaderId') }}">
                            <select class="form-control mt-2" name="sel_cardreader_id" id="sel_cardreader_id">
                                @foreach($CardReaders as $card_reader)
                                    <option value="{{ $card_reader->reader_id }}" @if($card_reader->reader_id == Session::get('ConnectedCardReaderId')) {{ 'selected' }} @endif >{{ $card_reader->reader_name }}</option>
                                @endforeach
                            </select>
                            <a href="javascript:void(0);" id="btnChangeCardReader" class="btn-sm btn-primary mt-3 mb-2 p-2 pl-4 pr-4 d-inline-block">Save</a>&nbsp;&nbsp;
                            <a href="javascript:void(0);" id="btnCancelChangeCardReader" class="btn-sm btn-secondary mt-3 mb-2 p-2 pl-3 pr-3 d-inline-block">Cancel</a>
                        </div>
                    @endif
                </div>
            @endif

            <div>
                @if(count($PaymentMethodList) > 0)
                    <input type="hidden" name="shipsignatureflag" id="shipsignatureflag" value="" />
                    <input type="hidden" name="ap_psChecksum" id="ap_psChecksum" value="">

                    {{ csrf_field() }}

                    <div class="pb-2 mb-1">All transactions are secure and encrypted.</div>
                    @foreach($PaymentMethodList as $pkey => $PMethod)
                        <label class="comcheck radio checkbox-label pay-radio card-pay credit-methods w-100">
                            <div class="chebox">
                                <input type="radio" name="PaymentMethod" id="paytypeID{{$pkey}}" value="{{$PMethod['pm_group_name']}}"
                                    onclick="ShowPaymentMethod()">
                                <span class="checkmark"></span>
                                <span class="fw500">{{$PMethod['pm_name']}} @if(isset($PMethod['pm_pos_message']) && !empty($PMethod['pm_pos_message'])) ({{$PMethod['pm_pos_message']}}) @endif</span>
                            </div>
                        </label>
                    @endforeach

                    <div id="extraField" style="display:none;" class="pt-2">
                        <div id="textbox-container" class="split-container">
                            <div class="split-cash">
                                <div class="form-group mb-0 d-flex">
                                    <label for="CashPayment" class="col-form-label">Cash</label>
                                    <input id="CashPayment" name="CashPayment" type="text" class="form-control cash-input">
                                    <a href="javascript:void(0)" class="edit-link" style="display:none;">
                                        <svg class="sv-edit" aria-hidden="true" role="img" width="18" height="18">
                                            <use href="#sv-edit" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-edit"></use>
                                        </svg>
                                    </a>
                                </div>
                                <a href="javascript:void(0)" class="confirm-link1 btn btn-secondary" id="ConfirmLink">Confirm</a>
                                <!-- <a href="javascript:void(0)" class="edit-link btn btn-secondary" style="display:none;">✏️</a> -->
                                <span class="confirm-msg"></span>
                            </div>
                            <div>
                                <div class="textbox-wrapper form-group mb-0">
                                    <label for="txtcreditcard" class="col-form-label">CreditCard</label>
                                    <input id="txtcreditcard" name="txtcreditcard[]" type="text" class="form-control dynamic-textbox">
                                </div>
                                <a href="javascript:void(0)" class="confirm-link btn btn-secondary">Confirm</a>
                                <span class="confirm-msg"> </span>
                                <div id="error-msg" style="color:red;"></div>
                            </div>
                        </div>
                        <div id="remaining-msg" class="col-12 text-center pt-3 fw500 f14" style="color:green;">Total Amount : ${{ $NetTotal }} , Remaining Balance : $<span id="remaining-amount">{{ $NetTotal }}</span></div>
                    </div>
                </div>
                <!--Store Payment Design Start-->
                <!--Store Payment Design End-->
            @endif
            <div id="card-element" style="display:none;" class="card-manuallyDtl"> </div>
        </div>

        @if(count($PaymentMethodList) > 0)
            <div class="col-12">
                @if($giftflag == 1)
                    <div class="gift-card">
                        <div class="actmenuhd">
                            <svg class="svg-newgift vam" aria-hidden="true" role="img" width="24" height="24">
                                <use href="#svg-newgift" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-newgift"></use>
                            </svg>
                            <span>Add gift card</span>
                            <svg id="giftcard-down-arraw" class="sv-down-arrow vam float-right" aria-hidden="true" role="img" width="20" height="20">
                                <use href="#sv-down-arrow" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-down-arrow"></use>
                            </svg>
                        </div>
                        <div class="actmenu-inner mt-4" id="divgiftcard">
                            <form name="frmgiftcard" id="frmgiftcard">
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">From<span class="errmsg">*</span></label>
                                            <input id="gift_from" name="gift_from" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftFrom')}}">
                                            <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_from']" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-form-label">To<span class="errmsg">*</span></label>
                                            <input id="gift_to" name="gift_to" type="text" class="form-control" value="{{Session::get('ShoppingCart.GiftTo')}}">
                                            <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_to']" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label">Gift Message</label>
                                            <textarea id="gift_message_customer" name="gift_message_customer" rows="4" cols="50" type="text" class="form-control">{{Session::get('ShoppingCart.GiftMessageCustomer')}}</textarea>
                                            <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_gift_message_customer']" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Select Gift<span class="errmsg">*</span></label>
                                            {!! $freegiftcombo !!}
                                            <x-message :attr="['classname' => 'frmerror', 'message' => '', 'mid' => 'error_freegiftvalue']" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-actions clearfix pt-0 pb-0">
                                            <div class="float-left">
                                                <a href="javascript:void(0);" id="btnappygiftcard" class="btn btn-primary">Apply</a>
                                                <a href="#" class="ulink cancel-btn">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <h4 class="cart-hd-sub text-center pt-4 mt-md-3 mb-md-2 pb-4 fw500">Special Request</h4>
                            <textarea id="customer_comment" class="form-control" name="customer_comment" placeholder="Special Request"></textarea>
                        </div>
                    </div>
                    <div class="col-12 pt-1 pb-md-3">
                        <label class="checkbox-label che-box">
                            <div class="chebox">
                                <input type="checkbox" checked id="chkagree"><span class="checkmark"></span>
                                <span>By placing and order on MaxAroma.com you agree with the <a href="{{url('/terms-and-conditions.html')}}" target="_blank">terms and conditions</a>.</span>
                            </div>
                        </label>
                    </div>
                    @if(Session::has('ShowStockLeftMessage') && Session::get('ShowStockLeftMessage') == 'Yes')
                        <div class="col-12 text-center clsgreen pb-3">Your items are almost sold out - secure them now.</div>
                    @endif
                    <div class="col-12 d-md-none">
                        <div class="mobile-shadow">&nbsp;</div>
                    </div>
                    <div class="col-12 pt-md-3 pb-md-5">
                        <div class="chekout-main-btn" id="btnPlaceOrderVal" style="display:none;">
                            <a href="javascript:void(0);" class="btn btn-primary d-block checkout-btn" id="btnPlaceOrder">Place Order</a>
                        </div>
                    </div>
                    @if(session('ShoppingCart.OrderStoreTypeValue')=="Both")
                        <div class="col-12 f12 text-center">
                            <a href="{{config('global.SITE_URL')}}shipping" class="ulink"><strong>Return to Shipping</strong></a>
                        </div>
                    @else
                        <div class="col-12 f12 text-center">
                            <a href="{{config('global.SITE_URL')}}checkout" class="ulink"><strong>Return to Checkout</strong></a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</form>
<script src="https://js.stripe.com/v3/"></script>

<script>
    const maxTotal ={{ $NetTotal }}
    const stripe = Stripe('{{ $PublicKey }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    let isCardComplete = false;
    card.on('change', function (event) {
        isCardComplete = event.complete;
    });
</script>
