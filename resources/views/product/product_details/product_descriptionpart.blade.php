
@if($productDetails->template == 'Maxaroma-Edit')
    @include('product.product_details.product_descriptionpart_maxaroma_edit')
@else
    @include('product.product_details.product_descriptionpart_default')
@endif