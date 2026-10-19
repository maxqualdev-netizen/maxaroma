@extends('layouts.app')
@section('content')
@include('checkout.checkout-header')
<div class="col-12">
    <div class="checkout-cont" style="max-width:460px;">
        @if(Session::has('PlaceOrderError'))
            <x-message :attr="[ 'classname' => 'alert alert-danger', 'message' => Session::get('PlaceOrderError'), 'mid' => 'error_order']"/>
        @endif	
		
        <ul class="checkout-steps">
            <li>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="cart-hd-sub text-center pt-md-0">
                            <span>Shipping Information</span> 
                        </h3>
                    </div>
                    <div class="col-8 pb-md-5">
                        <p class="mb-0 lheight-18">
							@if(isset($Shipping))
                            {{$Shipping['first_name']}} {{$Shipping['last_name']}}<br>
                            {{$Shipping['address1']}},
                            @if($Shipping['address2'] != '')
                                {{$Shipping['address2']}},
                            @endif
                            <br> 
                            {{$Shipping['city']}}, {{$Shipping['state']}} - {{$Shipping['zip']}}, {{$Shipping['country']}}<br> 
                            {{$Shipping['email']}}
                            @endif
                        </p>
                    </div>
                    @if(Session::has('ShoppingCart.AfterPay.Checkout_Token'))
                    <div class="col-4" align="right">
                        <a href="javascript:void(0);" class="float-right edit-btn btn btn-secondary" data-index="2" id="EditAfter">Edit</a>
                    </div>
                     @else
                    <div class="col-4" align="right">
                        <a href="{{config('global.SITE_URL')}}checkout{{$payment_method_url}}" class="float-right edit-btn btn btn-secondary" data-index="2">Edit</a>
                    </div>
                    @endif   
                </div>    
            </li>
            <li class="billtab shipping-method border-md-top">
                @include('checkout.shipping-methods',[$CartDetails,$CartAttr])
            </li>
        </ul>
    </div>
</div>
@include('checkout.checkout-footer')
@endsection
<script type="text/javascript">
var routenm='<?php echo Route::currentRouteName();?>'; 
</script>

