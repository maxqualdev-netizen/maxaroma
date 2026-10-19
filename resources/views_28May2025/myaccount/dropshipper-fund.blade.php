@if(Session::get('is_dropshipper') == 'Yes' && Session::get('eusertype') =='Wholesaler')
	<div class="row">	
		<div class="col-lg-12 mt-0 mt-md-2">
			<div class="max_coupon_box">
				<div class="coupan_boxhd">
					<div class="text-center">	
						<table width="100%" border="0">
							<thead>
								<tr>
									<th>Total Fund</th>
									<th>Required Amount</th>
									<th>
										@if($DropshipperAccountDetails['fund_available'] == 'Yes')
											Remaining Fund
										@else
											Required Fund
										@endif
									</th>
								</tr>
							</thead>
							<tbody>
							<tr>
								<td data-title="Total Fund" align="center">{{Price($DropshipperAccountDetails['total_fund'])}}</td>
								<td data-title="Required Amount" align="center">{{Price($order_amount)}}</td>
								<td data-title="@if($DropshipperAccountDetails['fund_available'] == 'Yes')
										Remaining Fund
									@else
										Required Fund
									@endif" align="center">
									@if($DropshipperAccountDetails['total_fund'] > $order_amount)
										{{Price($DropshipperAccountDetails['total_fund'] - $order_amount)}}
									@else
										{{Price($order_amount - $DropshipperAccountDetails['total_fund'])}} | <a href="javascript:void(0);" id="btnImportDropshipOrders" >Add Fund</a>
									@endif
								</td>
							</tr>
							@if($DropshipperAccountDetails['fund_available'] == 'Yes')
								<tr>
									<td colspan="3" align="center" class="nodata-title">
										<a href="javascript:void(0);" class="btn btn-primary" id="btnDropshipPayment">Payment</a>
									</td>
								</tr>
								<tr>
									<td colspan="3" style="background:transparent;border:none;" class="border-row nodata-title">
										<span>OR</span>
									</td>
								</tr>	
							@endif	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
@endif
