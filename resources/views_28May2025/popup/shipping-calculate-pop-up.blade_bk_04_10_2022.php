<div class="vertical-alignment-helper">
   <div class="modal-dialog modal-md vertical-align-center">
      <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
               <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
            </svg> </a>
         <div class="modal-body">
            <div class="sales-offers-modal">
               <div class="modal-hd">
                  <h1>SHIPPING CALCULATOR</h1>
               </div>
               <div class="modal-space">
                  <form method="post" name="shippingfield" id="shippingfield">
                  <input type="hidden" name="value_check" value="1" id="value_check">
                     <div class="login">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="so-hot-list">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="spg-panel">
                                          <div class="spg-body loginbody pb-sm-0">
                                             <div class="form-group">
                                                <x-selectbox :attr="[
                                                          'label' => 'Country',
                                                          'id' => 'country', 'name' => 'country',
                                                          'firstopt' => '',
                                                          'classname' => 'form-control',
                                                          'data' => $Countries,
                                                          'selected' => (old('country')?old('country'):'US'),
                                                          ]" />
                                             </div>
                                             <div class="form-group" id="divstate">
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
                                                <label for="state" class="col-form-label pt-0 text-left">State</label>
                                                <input type="text" id="other_state" name="other_state" placeholder="Other State" class="form-control">
                                             </div>
                                             <div class="form-group">
                                                <label for="zipcode" class="col-form-label pt-0 text-left">Zipcode</label>
                                                <input type="text" id="zip" name="zip" placeholder="Zipcode" class="form-control">
                                                <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_zip']" />
                                             </div>
                                             <div class="pb-3 text-right">
                                                <x-button btype="submit" btntext="Submit" classname="btn btn-primary" bid="btnShippingCalculate" />
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div id="show_shipping_method_box" class="pt10"></div>
                                    </div>
                                 </div>
                              </div>
                              <!-- <div class="so-nom-list">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="border">
                                            <h5 onclick="openpaymethod('paybycc')" class="p10 bg-f2 cp"><span>Pay by Credit Card</span><div class="float-right ccslide">-</div></h5>
                                             <div id="paybycc" class="m10">
                                                <x-button btype="button" btntext="Pay by Credit Card" classname="btn btn-primary" bid="btnpaybycc" />
                                             </div>
                                       </div>
                                       <div class="border">
                                            <h5 onclick="openpaymethod('paybypaypal')" class="p10 bg-f2 cp"><span>Pay by Paypal</span><div class="float-right paypalslide">+</div></h5>
                                            <div id="paybypaypal" class="m10 display-none">
                                                <x-button btype="button" btntext="Buy Now" classname="btn btn-primary" bid="btnpaybypaypal" />
                                            </div>
                                       </div>

                                       <div class="border">
                                            <h5 onclick="openpaymethod('paybyamazon')" class="p10 bg-f2 cp"><span>Pay by Amazon</span><div class="float-right amazonslide">+</div></h5>
                                            <div id="paybyamazon" class="m10 display-none">
                                                <x-button btype="button" btntext="Pay With Amazon" classname="btn btn-primary" bid="btnpaybyamazon" />
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