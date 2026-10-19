<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>ALERT ME</h1>
                    </div>
                    @if (Session::has('successam'))
                    <x-message :attr="[
										'classname' => 'frmsuccess text-center pt-3', 
										'message' => Session::get('successam')]" />
                    @endif
                    <div class="modal-space">
                        <form method="post" name="frmproductalert" id="frmproductalert" action="javascript:void(0)">
                            <input name="isAction" value="AddProductAlertMe" type="hidden">
                            <input name="productsId" value="{{$prod_detail[0]->products_id}}" type="hidden">
                            <input name="imanufactureid" value="" type="hidden">
                            <input name="sku" value="{{$prod_detail[0]->sku}}" type="hidden">
                            <div class="login">
                                @if (Session::has('failedam'))
                                <x-message :attr="[
										'classname' => 'frmerror', 
										'message' => Session::get('successam')]" />
                                @endif
                                <div class="row spg-body loginbody pb-sm-0 alertdiv">
                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 text-center pb-3">
                                        <div class="quickview-thumb">
                                            <img alt="{{$prod_detail[0]->product_name}}" title="{{$prod_detail[0]->product_name}}" src="{{$prod_detail[0]->image}}" id="QuickviewExtraImages">
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                        <h4 id="quickviewprd_nm" class="prname">{{$prod_detail[0]->product_name}}</h4>
                                        <div id="quickview_sort_descrition">
                                            <div class="dscn">
                                                <strong>Description:</strong>
                                                <p>{!! $prod_detail[0]->short_description !!}</p>
                                                <p>{!! $prod_detail[0]->product_description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group alert-input">
                                            <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                            <input type="text" class="form-control" placeholder="Email Address" id="email" name="email">   
                                            <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                            <x-button btype="submit" btntext="Submit" classname="btn btn-primary" />         
                                        </div>
                                        
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
<script src="{{ asset('public/js/core/product_alert_validate.js') }}"></script>