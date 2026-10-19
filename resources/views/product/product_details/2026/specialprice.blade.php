@if ($SpecialPriceDetails!='')
<div class="sublist_prd" role="region" aria-label="Special Quantity Discount Price Section">
    <h4 class="text-red" id="special-price-title">Special Quantity Discount Price</h4>
    <div aria-labelledby="special-price-title">
        {!! $SpecialPriceDetails !!}
    </div>
</div>
@endif