
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
						 @if($order_result_value->status == 'Pending')
						 <li class="col_1"><strong>order status</strong><span class="{{ GetOrderStatusClass($order_result_value->status) }}">{{ $order_result_value->status }}</span></li>
						 <li class="col_1"><strong>order no</strong><span>{{ $order_result_value->orders_no }}</span></li>
				         <li class="col_2"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
				         <li class="col_1"><strong>PAYMENT STATUS</strong><span>{{ $order_result_value->pay_status }}</span></li>
					         <li class="col_3">
					            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxCancelOrder('{{$order_result_value->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Cancel Order</a>
					         </li>
						@else
						<li class="col_1"><strong>order status</strong><span class="{{ GetOrderStatusClass($order_result_value->status) }}"><a href="{{config('global.SITE_URL')}}order-detail/{{$order_result_value->orders_id}}.html">
							{{ $order_result_value->status }}</a></span></li>
						<li class="col_2"><strong>order no</strong><span>{{ $order_result_value->orders_no }}</span></li>
				         <li class="col_3"><strong>order DATE</strong><span>{{ $order_result_value->datetime }}</span></li>
				         <li class="col_4"><strong>PAYMENT STATUS</strong><span>{{ $order_result_value->pay_status }}</span></li>
					     @endif
				      </ul>
				      @php $i = 0; @endphp
				      @if(count($order_result_value->order_items) > 0)
					      @foreach($order_result_value->order_items as $order_items_key => $order_items_value)
					      <ul class="body">
					         <li class="col_1"> <img src="https://media.maxaroma.com/9cd29e92-4b8f-436f-ad1f-8e368b30dc1d/{{ $order_items_value->Image }}" alt="" /> </li>
					         <li class="col_2">
					            <div class="info"> <a href="#"><strong>{!! remove_html_entities($order_items_value->product_name) !!}</strong></a><br />
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
						    @if($i == 0)
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
						    
							   @if(Session::get('sess_useremail') == '11gequaldev@gmail.com')
								<div class="col-6 pt-2">
										   <!--<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a>-->
										   <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrderNew('{{$order_result_value->orders_no}}','{{$order_result_value->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
										</div>
								  
							   @else    
								   @if($order_result_value->days <= 30 && $order_result_value->ship_status =='Shipped' && in_array($order_result_value->status, ['Completed','Return Requested','Return Approved','Return Rejected','Item Received']) && in_array($order_items_value->ostatus,['Pending','Completed']))
										<div class="col-6 pt-2">
											<!--<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item</a>-->
											<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxReturnOrderNew('{{$order_result_value->orders_no}}','{{$order_result_value->bill_email}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Return Item(s)</a>
										</div>
								   @endif
							   @endif
							@endif
							
							@if($order_items_value->return_shipping_label != '')
								<div class="col-6 pt-2">{!! $order_items_value->return_shipping_label !!}</div>
							@endif
							@if($order_items_value->COrderNo != '')
								<div class="col-6 pt-2">
									<a href="{{ $order_items_value->COrderNo }}" target="_blank" class="btn btn-sm btn-primary" rel="nofollow">Return Label </a>
								</div>
							@endif
								   
									@if($i == 0)
									@if(Session::get('sess_useremail') == '11gequaldev@gmail.com' && $order_result_value->pay_status =='Paid' &&$order_result_value->route_shipping_insurance_charge >0 && !in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
										<div class="col-6 pt-2">
												   <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}','{{$order_result_value->bill_first_name}}','{{$order_result_value->bill_last_name}}','{{$order_result_value->bill_email}}','{{$order_result_value->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
									   </div>
									@elseif($order_result_value->days <= 30 && $order_result_value->pay_status =='Paid' && $order_result_value->ship_status =='Shipped' &&$order_result_value->route_shipping_insurance_charge >0 && !in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
									   <div class="col-6 pt-2">
												   <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimOrder('{{$order_result_value->orders_id}}','{{$order_items_value->quantity}}','{{$order_items_value->orders_detail_id}}','{{$order_result_value->bill_first_name}}','{{$order_result_value->bill_last_name}}','{{$order_result_value->bill_email}}','{{$order_result_value->orders_no}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claim </a>
									   </div>
								   @elseif(in_array($order_result_value->status, ['Claim Requested','Claim Approved','Claim Rejected','Claim In Review']))
									   <div class="col-6 pt-2">
											   <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimedOrder('{{$order_result_value->orders_id}}');"  data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Claimed </a>
										</div>
								   @endif
								   @if($order_result_value->status == "Claim In Review - Police Report Required")
									<div class="col-6 pt-2">
										<a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="DisplayPopupBoxClaimPoliceReportOrder('{{$order_result_value->orders_id}}','Y');" data-toggle="modal" data-target="#myModalPopUp" rel="nofollow">Upload Police Report</a>
									</div>
									@if($order_result_value->policeReportAttach != "")
									<div class="col-6 pt-2">
										<a href="{{$order_result_value->policeReportAttach}}" class="btn btn-secondary" target="_blank">View Police Report</a>
									</div>
									@endif
								   @endif
								   @endif
								
                                   @if($order_items_value->Reason!='')
                                       <div class="info pt-2 col-12">Reason: {{ $order_items_value->Reason }}</div>
                                       <div class="info pt-1 col-12">Status: {{ $order_items_value->ostatus }}</div>
                                   @endif
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
