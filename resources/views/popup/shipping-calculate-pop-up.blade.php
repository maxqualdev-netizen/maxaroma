<div class="vertical-alignment-helper" role="dialog" aria-modal="true" aria-labelledby="shipping-calc-title" aria-describedby="shipping-calc-desc">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-label="Close dialog">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body" id="shipping-calc-desc">
                <div class="sales-offers-modal">
                    <div class="modal-hd">
                        <h1 id="shipping-calc-title">SHIPPING CALCULATOR</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="shippingfield" id="shippingfield" aria-labelledby="shipping-calc-title" aria-describedby="shipping-calc-desc">
                            <input type="hidden" name="value_check" value="1" id="value_check">
                            <div class="login">
                                <div class="row" role="region" aria-labelledby="shipping-calc-title">
                                    <div class="col-md-12">
                                        <div class="so-hot-list">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="spg-panel">
                                                        <div class="spg-body loginbody pb-sm-0">
                                                            <div class="form-group selectbox-form">
                                                                <x-selectbox :attr="[
                                                                'label' => 'Country',
                                                                'id' => 'country', 'name' => 'country',
                                                                'firstopt' => '',
                                                                'classname' => 'form-control',
                                                                'data' => $Countries,
                                                                'selected' => (old('country')?old('country'):'US'),
                                                                ]" />
                                                            </div>
                                                            <div class="form-group selectbox-form" id="divstate">
                                                                <x-selectbox :attr="[
                                                                'label' => 'State',
                                                                'id' => 'state', 'name' => 'state',
                                                                'firstopt' => '',
                                                                'classname' => 'form-control',
                                                                'data' => $States,
                                                                'selected' => old('state'),
                                                                ]" />
                                                            </div>
                                                            <div class="form-group otherstate" id="divotherstate" style="display: none;">
                                                                <label for="state" class="col-form-label pt-0 text-left" id="label-other-state">State</label>
                                                                <input type="text" id="other_state" name="other_state" placeholder="Other State" class="form-control" aria-labelledby="label-other-state" aria-label="Other State">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="zip" class="col-form-label pt-0 text-left" id="label-zipcode">Zipcode</label>
                                                                <input type="text" id="zip" name="zip" placeholder="Zipcode" class="form-control" aria-labelledby="label-zipcode" aria-label="Zipcode">
                                                                <x-message :attr="[
                                                                'classname' => 'frmerror', 
                                                                'message' => '',
                                                                'mid' => 'error_zip']" />
                                                            </div>
                                                            <div class="pb-3 text-right">
                                                                <x-button btype="submit" btntext="Submit" classname="btn btn-primary" bid="btnShippingCalculate" aria-label="Submit Shipping Calculation" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="show_shipping_method_box" class="pt10" role="region" aria-label="Shipping Methods"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="so-nom-list">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="border" role="region" aria-labelledby="paybycc-title">
                                                        <h5 onclick="openpaymethod('paybycc')" class="p10 bg-f2 cp" id="paybycc-title" tabindex="0" aria-controls="paybycc" aria-expanded="true">
                                                            <span>Pay by Credit Card</span>
                                                            <div class="float-right ccslide">-</div>
                                                        </h5>
                                                        <div id="paybycc" class="m10" aria-labelledby="paybycc-title">
                                                            <x-button btype="button" btntext="Pay by Credit Card" classname="btn btn-primary" bid="btnpaybycc" aria-label="Pay by Credit Card" />
                                                        </div>
                                                    </div>
                                                    <div class="border" role="region" aria-labelledby="paybypaypal-title">
                                                        <h5 onclick="openpaymethod('paybypaypal')" class="p10 bg-f2 cp" id="paybypaypal-title" tabindex="0" aria-controls="paybypaypal" aria-expanded="false">
                                                            <span>Pay by Paypal</span>
                                                            <div class="float-right paypalslide">+</div>
                                                        </h5>
                                                        <div id="paybypaypal" class="m10 display-none" aria-labelledby="paybypaypal-title">
                                                            <x-button btype="button" btntext="Buy Now" classname="btn btn-primary" bid="btnpaybypaypal" aria-label="Buy Now with PayPal" />
                                                        </div>
                                                    </div>

                                                    <div class="border" role="region" aria-labelledby="paybyamazon-title">
                                                        <h5 onclick="openpaymethod('paybyamazon')" class="p10 bg-f2 cp" id="paybyamazon-title" tabindex="0" aria-controls="paybyamazon" aria-expanded="false">
                                                            <span>Pay by Amazon</span>
                                                            <div class="float-right amazonslide">+</div>
                                                        </h5>
                                                        <div id="paybyamazon" class="m10 display-none" aria-labelledby="paybyamazon-title">
                                                            <x-button btype="button" btntext="Pay With Amazon" classname="btn btn-primary" bid="btnpaybyamazon" aria-label="Pay With Amazon" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/js/core/shippingcalculate.js') }}"></script>