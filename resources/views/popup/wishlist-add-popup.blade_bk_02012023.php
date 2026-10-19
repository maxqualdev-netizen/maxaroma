@if ($isAction == 'wish_login')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-md vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>PLEASE SIGN IN TO MAXAROMA</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmLogin" id="frmLogin" action="javascript:void(0)">
                            <input type="hidden" name="check_value" value="1" id="check_value">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <h3>Member Login</h3>
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    	@if (Session::has('failed'))
                            							<x-message :attr="[
                            										'classname' => 'frmerror frmerror_shw pb-3', 
                            										'message' => Session::get('failed')]"
                            							/>
                            							@endif
                            							@if (Session::has('success'))
                            							<x-message :attr="[
                            										'classname' => 'frmsuccess', 
                            										'message' => Session::get('success')]"
                            							/>
                            							@endif
                                                    <div class="form-group">
                                                        <label for="email" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" autofocus />
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password" class="col-form-label pt-0 text-left">Password<span class="errmsg">*</span></label>
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_password']" />
                                                        <small class="pt-2 d-block">Don't use space when entering your password.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <p class="float-right w-100"><a class="btn_link pt-0 pb-0 mb-0" href="javascript:void(0);" onclick="ShowForgetPassword();">Forgot Password?</a></p>
                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Join" classname="btn btn-primary" bid="btnsubmit" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel spg-panel1">
                                            <h3>New Member</h3>
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    <p>Creating an account is simple and fast. Just click the button below. Once it's been set up, you can take advantage of all the benefits of MaxAroma.com</p>
                                                </div>
                                                <div class=""><a href="{{$SITE_URL}}register.html" class="btn btn-secondary">Sign up</a></div>
                                            </div>
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
@endif

@if ($isAction == 'wish_forget')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <a class="close" type="button" data-dismiss="modal" aria-hidden="true">
                <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg>
            </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>FORGOT PASSWORD</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmForgotPwd" id="frmForgotPwd" action="javascript:void(0)">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    <div id="errorBox" style="margin-bottom:10px;" class="errorBox systemErrorBox"><span>{{$var_msg}}</span></div>
                        							@if (Session::has('failedfp'))
                        							<x-message :attr="[
                        										'classname' => 'frmerror frmerror_shw', 
                        										'message' => Session::get('failedfp')]"
                        							/>
                        							@endif
                        							@if (Session::has('successfp'))
                        							<x-message :attr="[
                        										'classname' => 'frmsuccess pb-3', 
                        										'message' => Session::get('successfp')]"
                        							/>
                        							@endif

                                                    <div class="form-group">
                                                        <label for="lname" class="col-form-label pt-0 text-left">Email Address<span class="errmsg">*</span></label>
                                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" autofocus="">
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_email']" />
                                                    </div>
                                                </div>
                                                <x-button btype="submit" btntext="Submit" classname="btn btn-primary" bid="btnsubmit" />
                                            </div>
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
@endif

@if ($isAction == 'wish_product')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>WISHLIST</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmproduct" id="frmproduct" action="javascript:void(0)">
                            <input type="hidden" name="isAction" value="AddWishProduct">
                            <input type="hidden" name="sku" value="{{ $prod_info[0]->sku ?? '' }}">
                            <input type="hidden" name="productsId" value="{{ $prod_info[0]->products_id ?? '' }}">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                                                    @if (Session::has('failed'))
                        							<x-message :attr="[
                        										'classname' => 'frmerror frmerror_shw', 
                        										'message' => Session::get('failed')]"
                        							/>
                        							@endif
                        							@if (Session::has('success'))
                        							<x-message :attr="[
                        										'classname' => 'frmsuccess pb-3', 
                        										'message' => Session::get('success')]"
                        							/>
                        						    @endif
                                                    <div class="form-group">
                                                        <label for="email" class="col-form-label pt-0 text-left">Category<span class="errmsg">*</span></label>
                                                        <select class="form-control" name="wishlist_category_id" id="wishlist_category_id">
                                                            @if(isset($WishCatRS))
                                                            @foreach($WishCatRS as $key => $value)
                                                            <option value="{{$value['wishlist_category_id']}}">{{$value['name']}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                        <x-message :attr="[
                                                            'classname' => 'frmerror', 
                                                            'message' => '',
                                                            'mid' => 'error_wishlist_category_id']" />
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <input class="btn btn-primary" type="button" onclick="showWishCat()" value="Create New Category" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="questions" class="col-form-label">Product Name : <span class="errmsg">*</span></label>
                                                        <span>{{ remove_html_entities($prod_info[0]->product_name) ?? '' }} </span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="questions" class="col-form-label">Description<span class="errmsg">*</span></label>
                                                        <textarea rows="4" cols="50" type="text" placeholder="Description" name="description" id="description" class="form-control"></textarea>
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_description']" />
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                <x-button btype="submit" btntext="Submit" classname="btn btn-primary" bid="btnsubmit" />
                                                </div>
                                                <hr>
                                                <h4>HOW TO SET WISHLIST</h4>
                                                <div class="md_list mt-2 pt-2 text-left">
                                                    <ol class="com_clist">
                                                        <li>
                                                            <h6>1. First create whatever categories needed to help organize your wishlist items. (i.e. 'My Wishes', 'Family Gifts', etc.)</h6>
                                                        </li>
                                                        <li>
                                                            <h6>2. You can add products to your wishlist by finding the product, clicking on the add to wishlist button, and choosing which category you would like to save it to.</h6>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
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
@endif

@if ($isAction == 'wish_category')
<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>WISHLIST</h1>
                    </div>
                    <div class="modal-space">
                        <form method="post" name="frmcategory" id="frmcategory" action="javascript:void(0)">
                            <input type="hidden" name="isAction" value="AddWishCategory">
                            <div class="login">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="spg-panel">
                                            <div class="spg-body loginbody pb-sm-0">
                                                <div class="eheight">
                    							@if (Session::has('failedwc'))
                    							<x-message :attr="[
                    										'classname' => 'frmerror frmerror_shw', 
                    										'message' => Session::get('failedwc')]"
                    							/>
                    							@endif
                    							@if (Session::has('successwc'))
                    							<x-message :attr="[
                    										'classname' => 'frmsuccess pb-3', 
                    										'message' => Session::get('successwc')]"
                    							/>
                    						    @endif
                                                    <div class="form-group">
                                                        <label for="email" class="col-form-label pt-0 text-left">Category Name<span class="errmsg">*</span></label>
                                                        <input type="text" class="form-control" placeholder="Category Name" id="category_name" name="category_name">
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_category_name']" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="questions" class="col-form-label">Description<span class="errmsg">*</span></label>
                                                        <textarea rows="4" cols="50" type="text" placeholder="Description" name="description" id="description" class="form-control"></textarea>
                                                        <x-message :attr="[
												            'classname' => 'frmerror', 
												            'message' => '',
												            'mid' => 'error_description']" />
                                                    </div>
                                                </div>
												<div class="wish-btn-p d-flex justify-content-between align-items-center">
                                                <div class="back-link pt10">
                                                    <a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$Wish_ProductsID}}','Yes')" class="button btn-4 d-flex justify-content-between align-items-center">
                                                       <svg class="sv-back mr-1" width="14" height="14" aria-hidden="true" role="img"><use href="#sv-back" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-back"></use></svg> Back
                                                    </a>
                                                </div>
                                                <x-button btype="submit" btntext="Add Wish Category" classname="btn btn-primary" />
                                            </div>
                                        </div>
										{{--
                                        <br>
                                        <div class="back-link pt10">
                                            <a href="javascript:void(0);" onclick="DisplayPopupBoxWishlist('{{$Wish_ProductsID}}','Yes')" class="button btn-4">
                                                Back
                                            </a>
                                        </div>
										--}}
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
@endif
<script src="{{ asset('public/js/core/wishlist_add_validate.js') }}"></script>
