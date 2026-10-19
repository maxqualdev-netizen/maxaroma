<ul>
<li>Sub Total:<span>{{Price($CartDetails['SubTotal'])}}</span></li>

@foreach($AllCharges as $key => $Charge)
    <li>{{$Charge['label']}}:<span>{{Price($Charge['charge'])}}</span></li>
@endforeach

@foreach($AllDiscounts as $key => $Discount)
    @if($Discount['discount'] != '' && $Discount['discount'] > 0)
        <li>
            @if(isset($Discount['Ricon']) && $Discount['Ricon'] == 'Yes')
                <a class="label-cart-outstock remdis" href="javascript:void(0);" rel="nofollow" data-id="{{$Discount['dataid']}}" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </a>
            @endif	
            {{$Discount['label']}}:<span>-{{Price($Discount['discount'])}}</span>
            </li>
    @endif
@endforeach
<li><strong>Total Amount:<span>{{Price($NetTotal)}}</span></strong></li>
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