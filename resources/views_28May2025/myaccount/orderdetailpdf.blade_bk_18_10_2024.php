<!DOCTYPE html>
<html>
<head>
	<title>Invoice - {{ $OrderRs->orders_no }}</title>
	<style type="text/css">
		.table > tbody > tr > .no-line {
		    border-top: none;
		}

		.table > thead > tr > .no-line {
		    border-bottom: none;
		}

		.table > tbody > tr > .thick-line {
		    border-top: 2px solid;
		}
		.body {
		    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		    /*font-family: 'montserrat';*/
		    font-size: 12px;
		    line-height: 1.428571429;
		    color: #000000;
		    background-color: #fff;
		}
		.container {
		    margin-right: auto;
		    margin-left: auto;
		    padding-left: 15px;
		    padding-right: 15px;
		    width: 700px;
		}
		* {
		    -webkit-box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    box-sizing: border-box;
		}
		.row {
		    margin-left: -15px;
		    margin-right: -15px;
		    clear: both;
		}
		h2, .h2 {
		    font-size: 30px;
		}
		h1, .h1, h2, .h2, h3, .h3 {
		    margin-top: 20px;
		    margin-bottom: 10px;
		}
		h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		    font-family: inherit;
		    font-weight: 500;
		    line-height: 1.1;
		    color: inherit;
		}
		h3, .h3 {
		    font-size: 24px;
		}
		hr {
		    margin-top: 20px;
		    margin-bottom: 20px;
		    border: 0;
		    border-top: 1px solid #eee;
		}
		address {
		    margin-bottom: 20px;
		    font-style: normal;
		    line-height: 1.428571429;
		}
		b, strong {
		    font-weight: 700;
		}
		.text-right {
		    text-align: right;
		}
		.col-md-12 {
		    width: 100%;
		    float: left;
		    position: relative;
		    min-height: 1px;
		    padding-left: 15px;
		    padding-right: 15px;
		}
		.panel-default {
		    border-color: #ddd;
		}
		.panel {
		    margin-bottom: 20px;
		    background-color: #fff;
		    border: 1px solid transparent;
		    border-radius: 4px;
		    -webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
		    box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
		}
		.panel-default>.panel-heading {
		    color: #333;
		    background-color: #f5f5f5;
		    border-color: #ddd;
		}
		.panel-heading {
		    padding: 10px 15px;
		    border-bottom: 1px solid transparent;
		    border-top-right-radius: 3px;
		    border-top-left-radius: 3px;
		}
		.panel-title {
		    margin-top: 0;
		    margin-bottom: 0;
		    font-size: 16px;
		    color: inherit;
		}
		.panel-body {
		    /*padding: 15px;*/
		}
		.table {
		    width: 100%;
		    margin-bottom: 20px;
		}
		table {
		    max-width: 100%;
		    background-color: transparent;
		    border-collapse: collapse;
		    border-spacing: 0;
		}
		thead {
		    display: table-header-group;
		    vertical-align: middle;
		    border-color: inherit;
		}
		tr {
		    display: table-row;
		    vertical-align: inherit;
		    border-color: inherit;
		}
		.table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>th, .table>caption+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>td, .table>thead:first-child>tr:first-child>td {
		    border-top: 0;
		}
		.table-condensed>thead>tr>th, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>tbody>tr>td, .table-condensed>tfoot>tr>td {
		    padding: 5px;
		}
		.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
		    padding: 8px;
		    line-height: 1.428571429;
		    vertical-align: top;
		    border-top: 1px solid #ddd;
		}
		.text-center {
		    text-align: center;
		}
		tbody {
		    display: table-row-group;
		    vertical-align: middle;
		    border-color: inherit;
		}
		.arrow-bullet{
			display:none
		}
		.terms_conditions_title{font-size:25px;text-align:center}
		.terms_conditions_section h2 a{font-size:22px;color:inherit;text-decoration:none}
		.terms_conditions11{padding-top:40px !important;}
		ol li p,ul li p{margin:0;padding:0}
	</style>
</head>
<body class="body">

	<div class="container">
	    <div class="row">
	        <div class="col-md-12">

	    			<table class="table table-borderless" style="border: none;">
	    				<tbody>
	    				<tr>
	    					<td style="border: none;">
			    				@if($logo != '')
			    				<img src="{{ $logo }}" width="100"><br><br><br>
			    				@endif
		    					<span style="font-size:14px;">MAXAROMA</span><br><span style="font-size:12px">3117 38th Ave<br>Long Island City, NY 11101<br>United States<br><br>Phone: 212-203-0004<br>info@maxaroma.com<br>www.maxaroma.com</span>
	    					</td>
	    					<td style="text-align: right;border: none;">
		    					<span style="font-size:18px;"><strong>INVOICE</strong></span><br>
								<span style="font-size:12px"><strong>{{$OrderRs->status}}</strong></span><br><br>
								<span style="font-size:12px">Invoice #: {{$OrderRs->orders_no}}<br>Invoice date: {{$OrderRs->datetime}}</span><br><br>
								<span style="font-size:12px">Amount due:</span><br><span style="font-size:14px; font-weight:bold">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->order_total}}</span>
							</td>
						</tr>
						</tbody>
	    			</table>

	    		<hr>

	    			<table class="table table-borderless" style="border: none;">
	    				<tbody>
	    				<tr>
	    					<td style="border: none;">
			    				<address>
			    				<span style="font-size:14px"><strong>Bill To:</strong></span><br>
			    				<span style="font-size:12px;">
			    					{{$OrderRs->bill_first_name}} {{$OrderRs->bill_last_name}}<br>
			    					@if( $OrderRs->bill_company != '' && Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') == 'Wholesaler' )
			    					{{$OrderRs->bill_company}}<br>
			    					@endif
			    					{{$OrderRs->bill_address1}}<br>
			    					@if( $OrderRs->bill_address2 != '')
			    					{{$OrderRs->bill_address2}}<br>
			    					@endif
			    					{{$OrderRs->bill_city}}, {{$OrderRs->bill_state}} {{$OrderRs->bill_zip}}<br>
			    					{{$OrderRs->bill_country}}<br><br>
			    					{{$OrderRs->bill_email}}<br>
			    					{{$OrderRs->bill_phone}}
			    					@if(isset($OrderRs->EstimatedDeliveryDate) && $OrderRs->EstimatedDeliveryDate!='0000-00-00 00:00:00')<br/><br/><b>Estimate Delivery Date:</b> {{\Carbon\Carbon::parse($EstimatedDeliveryDate)->format('m/d/Y')}}@endif
			    					@if(isset($terms_conditions_content) && $terms_conditions_content)<br/><b>Note:</b> {{$terms_conditions_content}}@endif
			    				</span>
			    				</address>
	    					</td>
	    					<td style="text-align: right;border: none;">
			    				<address>
			    				<span style="font-size:14px"><strong>Ship To:</strong></span><br>
			        			<span style="font-size:12px;">
			    					{{$OrderRs->ship_first_name}} {{$OrderRs->ship_last_name}}<br>
			    					@if( $OrderRs->ship_company != '' && Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') == 'Wholesaler' )
			    					{{$OrderRs->ship_company}}<br>
			    					@endif
			    					{{$OrderRs->ship_address1}}<br>
			    					@if( $OrderRs->ship_address2 != '')
			    					{{$OrderRs->ship_address2}}<br>
			    					@endif
			    					{{$OrderRs->ship_city}}, {{$OrderRs->ship_state}} {{$OrderRs->ship_zip}}<br>
			    					{{$OrderRs->ship_country}}<br><br>
			    					{{$OrderRs->ship_email}}<br>
			    					{{$OrderRs->ship_phone}}<br/>
			    					
			    					
			    					
			    				</span>
			    				</address>
							</td>
						</tr>
						</tbody>
	    			</table>

	    	</div>
	    </div>
	    
	    <div class="row">
	    	<div class="col-md-12">
	    		<div class="panel panel-default">
	    			<!-- <div class="panel-heading">
	    				<h3 class="panel-title"><strong>Order summary</strong></h3>
	    			</div> -->
	    			<div class="panel-body">
	    				<div class="table-responsive">
	    					<table class="table table-condensed">
	    						<thead>
	                                <tr>
	        							<td style="font-size:12px"><strong>Description</strong></td>
	        							<td class="text-center" style="font-size:12px"><strong>Quantity</strong></td>
	        							<td class="text-center" style="font-size:12px"><strong>Price</strong></td>
	        							<td class="text-right" style="font-size:12px"><strong>Amount</strong></td>
	                                </tr>
	    						</thead>
	    						<tbody>
	    							@if(count($OrderDetailRs) > 0)
	    							@foreach($OrderDetailRs as $order_detail_key => $order_detail_value)
	    							<tr>
	    								<td style="font-size:12px">{!! $order_detail_value['product_name'] !!}<br>UPC: {{ $order_detail_value['UPC'] }}
											@if($order_detail_value->excluded_flag=='Final Sale')
											<br>
											<strong>{{config('global.FINALSALE');}}</strong>
											@endif</td>
	    								<td class="text-center" style="font-size:12px">{{ $order_detail_value['quantity'] }}</td>
	    								<td class="text-center" style="font-size:12px">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_detail_value['price'] }}</td>
	    								<td class="text-right" style="font-size:12px">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $order_detail_value['total'] }}</td>
	    							</tr>
	    							@endforeach
	    							@endif

	    							<tr>
	    								<td class="thick-line"></td>
	    								<td class="thick-line"></td>
	    								<td class="thick-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Subtotal</td>
	    								<td class="thick-line text-right" style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{$OrderRs->sub_total}}</td>
	    							</tr>
	    							@if($OrderRs->shipping_amt > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Shipping Charge</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->shipping_amt }}</td>
	    							</tr>
	    							@endif
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Shipping Signature ({{$OrderRs->is_shipping_signatureOff}})</td>
	    								<td class="no-line text-right"  style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->shipping_signature }}</td>
	    							</tr>
									@if($OrderRs->route_shipping_insurance_charge > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Shipping Insurance*</td>
	    								<td class="no-line text-right"  style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->route_shipping_insurance_charge }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->tax > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Sales Tax</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->tax }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->gift_charge > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Gift Wrapping/strong></td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->gift_charge }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->auto_discount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Auto Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->auto_discount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->quantity_discount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Quantity Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->quantity_discount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->coupon_amount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Coupon Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->coupon_amount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->gc_amount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Gift Certificate</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->gc_amount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->refer_amount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Refer Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->refer_amount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->reward_discount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Reward Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->reward_discount }}</td>
	    							</tr>
	    							@endif
									@if($OrderRs->bogo_discount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Bogo Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->bogo_discount }}</td>
	    							</tr>
	    							@endif
	    							@if($OrderRs->apply_credit > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;">Credit Discount</td>
	    								<td class="no-line text-right" style="border-right: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->apply_credit }}</td>
	    							</tr>
	    							@endif
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="text-right" style="border: 1px solid #dfdfdf;"><strong>Total</strong></td>
	    								<td class="text-right" style="border: 1px solid #dfdfdf;"><strong>{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->order_total }} USD</strong></td>
	    							</tr>
	    							
	    							@if(!empty($OrderRs->total_refund_amount) && $OrderRs->total_refund_amount > 0 )
	    							<tr>
	    								<td class="no-line"></td>
	    								<td class="no-line"></td>
	    								<td class="no-line text-right" style="border: 1px solid #dfdfdf;">Total Refund</td>
	    								<td class="no-line text-right" style="border: 1px solid #dfdfdf;">-{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->total_refund_amount }} USD</td>
	    							</tr>
	    							@endif
	    							
	    						</tbody>
	    					</table>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col-md-12">
	    		<span style="font-size:12px">
					{{$str_terms_condition}}<br><br>
					{{$insuranceText}}<br>
					@if(!empty($InsuranceText11) && $InsuranceText11!='')
					{{$InsuranceText11}}<br>
					@endif
					@if(!empty($OrderRs->is_shipping_signature) && $OrderRs->is_shipping_signature == "No")
					{{$signatureText}}<br>
					@endif
	    		<br>THANK YOU FOR YOUR BUSINESS.<br>ALL CLAIMS MUST BE MADE WITHIN 48 HOURS AFTER THE SHIPMENT IS RECEIVED.</span>
	    	</div>
	    </div>
		
		<!-- <div class="row terms_conditions11">
	    	<div class="col-md-12 terms_conditions_section">
	    		<h1 class="terms_conditions_title" style="">Terms & Conditions</h1>
				{{$str_terms_condition}}<br>
				{!! $terms_conditions_content !!}
	    	</div>
	    </div> -->
		
	</div>

</body>
</html>
