<table class="table text-left table-border-none table-borderless">
	<tbody>
		<tr>
			<td>Subtotal</td>
			<td class="text-right"><strong>{{Price($CartDetails['SubTotal'])}}</strong></td>
		</tr>
		@foreach($AllCharges as $key => $Charge)
		<tr>
			<td>{{$Charge['label']}}</td>
			<td class="text-right"><strong>{{Price($Charge['charge'])}}</strong></td>
		</tr>
		@endforeach
		@foreach($AllDiscounts as $key => $Discount)
			@if($Discount['discount'] != '' && $Discount['discount'] > 0)
			<tr>
				<td>
					@if(isset($Discount['Ricon']) && $Discount['Ricon'] == 'Yes')
						<a class="label-cart-outstock remdis" href="javascript:void(0);" rel="nofollow" data-id="{{$Discount['dataid']}}" >
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
								<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
							</svg>
						</a>
					@endif	
					{{$Discount['label']}}
				</td>
				<td class="text-right"><strong>-{{Price($Discount['discount'])}}</strong></td>
			</tr>
			@endif
		@endforeach
		<tr class="cart-total">
			<td class="border-top">Total Amount</td>
			<td class="text-right border-top">{{Price($NetTotal)}}</td>
		</tr>
	</tbody>	
</table>
<div class="shipping-calc-link">
	<p class="text-red">*This is only an estimated total. The final amount will be determine at checkout.</p>
	@if($CartAttr['onlyGCPurchased'] != 1)
	@php 
		$fsvg = [
			'title' => 'Shipping Rate Calculator',
			'symbol' => 'sv-calculator',
			'height' => '20',
			'width' => '20',
			'classname' => 'sv-calculator vam',
		];
	@endphp
	<!-- <a href="javascript:void(0);" class="">
		<svg class="sv-calculator vam" aria-hidden="true" role="img" width="20" height="20">
			<use href="#sv-calculator" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-calculator"></use>
		</svg>
		Shipping Rate Calculator 
	</a> -->

	<x-button link='javascript:void(0);' btntext='Shipping Rate Calculator' classname="shippingCalculate" :bsvg="$fsvg"/>
	@endif
</div>