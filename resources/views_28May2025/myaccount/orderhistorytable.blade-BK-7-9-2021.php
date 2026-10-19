
				   @if(count($OrdResult) > 0)
				   @foreach($OrdResult as $order_result_key => $order_result_value)
				   @php
						if($order_result_value->payment_type!='PAYMENT_CL' && $order_result_value->use_credit_limit=='Yes') {
							$pay_method = $order_result_value->payment_method.'/Credit Limit';
						} else {
							$pay_method = $order_result_value->payment_method;
						}

					    $phoneorder_pay_url="";
					    if($order_result_value->status == 'Pending - PhoneOrder' && $order_result_value->phoneorder_payby == 'Customer') {
							$enc_order = base64_encode($order_result_value->orders_id);
							$phoneorder_pay_url = config('global.SITE_URL').'payment/'.$enc_order;
					    }
				   @endphp
				   <div class="myact_obox">
				      <ul class="thead">
				         <li class="col_1"><strong>order status</strong><span class="{{ GetOrderStatusClass($order_result_value->status) }}">{{ $order_result_value->status }}</span>
						</li>
				         <li class="col_2"><strong>order no</strong><span>{{ $order_result_value->orders_no }}</span></li>
				         <li class="col_3"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
				         <li class="col_4"><strong>PAYMENT STATUS</strong><span>{{ $order_result_value->pay_status }}</span></li>
				      </ul>
				      @php $i = 0; @endphp
				      @if(count($order_result_value->order_items) > 0)
					      @foreach($order_result_value->order_items as $order_items_key => $order_items_value)
					      <ul class="body">
					         <li class="col_1"> <img src="{{ $order_items_value->Image }}" alt="" /> </li>
					         <li class="col_2">
					            <div class="info"> <a href="#"><strong>{!! $order_items_value->product_name !!}</strong></a><br />
					               Item SKU : {{ $order_items_value->sku }}<br />
					               Unit Price: {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_items_value->price }}<br />
					               Quantity: {{ $order_items_value->quantity }}
					            </div>
					         </li>
					      	
					         <li class="col_3">
					            <div class="amount">tOTAL aMOUNT <strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->order_total }}</strong></div>
					            <div class="refund">REFUND AMT. {{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_result_value->refund_amount }}<br />
					               PAYMENT METHOD: {{ $pay_method }}
					            </div>
					         </li>
					        
					         <li class="col_4">
					            <div class="row row3">
					               <div class="col-6"><a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html" class="btn btn-primary">View Order</a></div>
					               @if($order_result_value->is_only_gc==0)
					               <div class="col-6"><a href="{{config('global.SITE_URL')}}re-order-detail/{{$order_result_value->orders_id}}.html" class="btn btn-secondary">reoRDER</a></div>
					               @endif
					               @if($view_fileType == 'print')
					               <div class="col-6 pt-2"><a href="javascript:void(0);" class="btn btn-secondary" onclick="window.open('{{config('global.SITE_URL')}}order-detail-print/{{$order_result_value->orders_id}}.html', 'xwin','toolbar=0,scrollbars=1,location=0,status=0,menubars=0,resizable=0, width=820,height=600,top=0,left=0,maximize=0')">Print</a></div>
					               @else
					               <div class="col-6 pt-2"><a href="{{config('global.SITE_URL')}}order-detail-pdf/{{$order_result_value->orders_id}}.html" class="btn btn-secondary">Invoice</a></div>
					               @endif
								   @if($phoneorder_pay_url!="")
					               		<div class="col-6 pt-2"><a href="{{ $phoneorder_pay_url }}" class="btn btn-secondary">Pay Now</a></div>
								   @endif
					               <!-- <div class="col-6 pt-2"><a href="#" class="btn btn-secondary">Cancel My Order</a></div>
					               <div class="col-6 pt-2"><a href="#" class="btn btn-secondary">Return</a></div> -->
					            </div>
					         </li>
					      </ul>
					      @php $i++; @endphp
					      @endforeach
				      @endif
				   </div>
				   @endforeach
					<div class="text-center pb-5 pt-3 fadeInUp" id="list_pagination">
					   {!! $OrdResult->onEachSide(0)->links('myaccount.pagination') !!}
					</div>
				   @else
				    <div class="row pt-3">
					   <div class="col-md-12 text-center">
					      <span class="errmsg tac">Sorry, No orders found!</span>
					   </div>
					</div>
				   @endif
