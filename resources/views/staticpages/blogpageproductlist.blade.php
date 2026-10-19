@foreach($ProductData as $key => $Product)
<div class="col-md-3 col-sm-6 col-6 pb-4 pt-4" role="listitem">
    <div class="blog-prdbox" tabindex="0">
        <x-productbox :prodData="$Product"/>
    </div>
</div>
@endforeach