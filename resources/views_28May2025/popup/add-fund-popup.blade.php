<link rel="stylesheet" href="{{ asset('public/css/core/addfundpopup.css') }}">
<div class="vertical-alignment-helper">
   <div class="modal-dialog modal-md vertical-align-center">
      <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
         <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
         </svg> </a>
         <div class="modal-body">
            <div class="sales-offers-modal">
               <div class="modal-hd">
                  <h1>Add Funds</h1>
               </div> 
               <div class="modal-space">
					<form id="frmfund" name="frmfund" method="post" action="{{url('/custaddfund')}}">
						{{ csrf_field() }}
						<input type="hidden" name="fund_type" id="fund_type" value=""/>
						<input type="hidden" name="page_from" id="page_from" value=""/> 
						<input type="hidden" name="fund_amount" id="fund_amount" value="{{$FundAmount}}"/>
					</form>
                     <div class="login">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="so-hot-list">
                                 <div class="row">
                                    <div class="col-md-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                   <div class="form-group">
                                                        <label class="col-form-label pt-0 text-left"><strong>Available Funds: </strong><span>{{Price(Auth::user()->available_funds)}}</span></label>
                                                   </div>
                                                   <div class="form-group">
                                                        <label class="col-form-label pt-0 text-left"><strong>Fund Amount: </strong><span>{{Price($FundAmount)}}</span></label>
                                                   </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="so-nom-list">
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
												<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
													 <input type="hidden" name="cmd" value="_xclick">
													 <input type="hidden" name="business" value="info@peraroma.com">
													 <input type="hidden" name="lc" value="BM">
													 <input type="hidden" name="item_name" value="DropShipper Deposit">
													 <input type="hidden" name="item_number" value="DDDeposit">
													 <input type="hidden" name="amount" value="{{$FundAmount}}">
													 <input type="hidden" name="currency_code" value="USD">
													 <input type="hidden" name="button_subtype" value="services">
													 <input type="hidden" name="no_note" value="0">
													 <input type="hidden" name="tax_rate" value="0.000">
													 <input type="hidden" name="shipping" value="0.00">
													 <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
													 <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
													 <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
													 <input name="notify_url" value="{{url('/paypal_fund_process').'/'.Session::get('sess_icustomerid')}}" type="hidden">
													 <input name="return" id ="paypal_return" value="{{url('/paypal_fund_response')}}" type="hidden">
												</form>
												
												<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
													 <input type="hidden" name="cmd" value="_xclick">
													 <input type="hidden" name="business" value="sb-3ejvm31442335@business.example.com">
													 <input type="hidden" name="lc" value="US">
													 <input type="hidden" name="item_name" value="DropShipper Deposit">
													 <input type="hidden" name="item_number" value="DDDeposit">
													 <input type="hidden" name="amount" value="{{$FundAmount}}">
													 <input type="hidden" name="currency_code" value="USD">
													 <input type="hidden" name="button_subtype" value="services">
													 <input type="hidden" name="no_note" value="0">
													 <input type="hidden" name="tax_rate" value="0.000">
													 <input type="hidden" name="shipping" value="0.00">
													 <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
													 <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
													 <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
													 <input name="notify_url" value="{{url('/paypal_fund_process').'/'.Session::get('sess_icustomerid')}}" type="hidden">
													 <input name="return" id ="paypal_return" value="{{url('/paypal_fund_response')}}" type="hidden">
												</form>-->
                                            </div>
                                       </div>

                                       <div class="border">
                                            <h5 onclick="openpaymethod('AmazonPayButton')" class="p10 bg-f2 cp"><span>Pay by Amazon</span><div class="float-right amazonslide">+</div></h5>
                                            <div id="AmazonPayFundButton"></div>
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
      </div>
   </div>
</div>

<script src="{{ asset('public/js/core/addfundpopup.js') }}"></script>
