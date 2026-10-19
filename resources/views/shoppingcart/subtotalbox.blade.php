<ul role="list" aria-label="Order Subtotal and Charges">
    <li role="listitem">
        <span>Sub Total:</span>
        <span aria-label="Subtotal Amount">{{Price($CartDetails['SubTotal'])}}</span>
    </li>

    @foreach($AllCharges as $key => $Charge)
        <li role="listitem">
            <span>{{$Charge['label']}}:</span>
            <span aria-label="{{$Charge['label']}} Amount">{{Price($Charge['charge'])}}</span>
        </li>
    @endforeach

    @foreach($AllDiscounts as $key => $Discount)
        @if($Discount['discount'] != '' && $Discount['discount'] > 0)
            <li role="listitem">
                @if(isset($Discount['Ricon']) && $Discount['Ricon'] == 'Yes')
                    <a class="label-cart-outstock remdis" href="javascript:void(0);" rel="nofollow" data-id="{{$Discount['dataid']}}" aria-label="Remove {{$Discount['label']}}" role="button" tabindex="0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                            <title>Remove {{$Discount['label']}}</title>
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </a>
                @endif	
                <span>{{$Discount['label']}}:</span>
                <span aria-label="{{$Discount['label']}} Discount">-{{Price($Discount['discount'])}}</span>
            </li>
        @endif
    @endforeach
    <li role="listitem">
        <strong>Total Amount:</strong>
        <span aria-label="Total Amount">{{Price($NetTotal)}}</span>
    </li>
</ul>
{{--
<div class="shipping-calc-link">
	<p class="text-red f12">*This is only an estimated total. The final amount will be determine at checkout.</p>
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
    <x-button link='javascript:void(0);' btntext='Shipping Rate Calculator' classname="shippingCalculate" :bsvg="$fsvg"/>
    @endif
</div>
--}}