<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
        <div class="modal-content"> <a class="close" type="button" data-dismiss="modal" aria-hidden="true"> <svg class="sv-close vam" aria-hidden="true" role="img" width="16" height="16">
                    <use href="#sv-close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-close"></use>
                </svg> </a>
            <div class="modal-body pick-gift">
                <div class="friend-mail-modal">
                    <div class="modal-hd">
                        <h1>PICK YOUR GIFT</h1>
                    </div>
                    <div class="modal-space f14">
						<div class="row pb-3">
							<div class="col-4 text-center"><div class="pl-3"><strong>Image</strong></div></div>
							<div class="col-8"><strong>Product Name</strong></div>
						</div>
						@foreach($Free_Gift_Res as $key => $Product)
						<div class="row pb-3">
							<div class="col-4 text-center">
								<div class="pl-3">
									<div class="gift-checkbox">
										<input type="hidden" name="productsid{{$key}}" id="productsid{{$key}}" value="{{$Product['products_id']}}">
										<input type="hidden" name="freeproductsid{{$key}}" id="freeproductsid{{$key}}" value="{{$Product['free_gift_products_id']}}">
										<input type="radio" class="rdofreegift" name="txtradio" id="txtradio{{$key}}" value="{{$key}}">	
										<input type="hidden" name="sku{{$key}}" id="sku{{$key}}" value="{{$Product['sku']}}">
									</div>
									<img width="90" height="90" title="{{$Product['product_name']}}" alt="{{$Product['product_name']}}" src="{{$Product['thumb_image']}}">
								</div>	
							</div>
							<div class="col-8">
								<div class="pb-2">{{remove_html_entities(strip_tags($Product['product_name']))}}</div>			  
								<div class="pb-2"><strong>Item SKU :</strong>{{$Product['sku']}}</div>
								<div class="pb-2">{{remove_html_entities(strip_tags($Product['short_description']))}}</div>
							</div>
						</div>	
						@endforeach
						<div class="row">
							<div class="col-12">
								<div class="pb-3 text-right">
									<x-button btype="button" btntext="Add To Cart" classname="btn btn-primary" bid="btnfreegift" />
								</div>
							</div>	
						</div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>