@if(count($dealofdayRS)>0)
    {!! $DealIcon !!}
@else
    @if($productDetails->GiftwrapIcon!='')
        {!! $productDetails->GiftwrapIcon !!}
    @endif
    @if($productDetails->SaleIcon!='')
        {!! $productDetails->SaleIcon !!}
    @endif
@endif