@foreach($ProductData as $key => $Product)
<div class="col-md-3 col-sm-6 col-6 pb-4 pt-4">
	<div class="blog-prdbox">
		<x-productbox :prodData="$Product"/>
	</div>
</div>
@endforeach
