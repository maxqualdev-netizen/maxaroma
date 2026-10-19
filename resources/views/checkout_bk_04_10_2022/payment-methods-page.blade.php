@extends('layouts.app')
@section('content')
<div class="showDwnMessage" style="display:none">Please wait while your transaction is being processed.... Please do not refresh the page.</div>
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
                            <span>Shipping & Billing Information</span>
                        </h3>    
                    </div>
                    <div class="col-8 pb-md-5">
                        <p class="mb-0 lheight-18">
                            {{$Shipping['first_name']}} {{$Shipping['last_name']}}<br>
                            {{$Shipping['address1']}},
                            @if($Shipping['address2'] != '')
                                {{$Shipping['address2']}},
                            @endif
                            <br> 
                            {{$Shipping['city']}}, {{$Shipping['state']}} - {{$Shipping['zip']}}, {{$Shipping['country']}}<br> 
                            {{$Shipping['email']}}
                        </p>
                    </div>
                    <div class="col-4 pb-md-5" align="right">
                        <a href="{{config('global.SITE_URL')}}checkout{{$payment_method_url}}" class="edit-btn btn btn-secondary" data-index="2">Edit</a>    
                    </div>
                </div>   
            </li>
            <li class="border-md-top">
                <div class="row">
                    <div class="col-12 d-md-none">
                        <div class="mobile-shadow mb-0">&nbsp;</div>
                    </div>
                    <div class="col-md-12">
                        <h3 class="cart-hd-sub text-center">
                            <span>Shipping Method</span>
                        </h3> 
                    </div>
                    <div class="col-8 pb-md-5">
                        @if(Session::has('ShoppingCart.Shipping') && count(Session::get('ShoppingCart.Shipping')) > 0)
                            <p class="mb-0 lheight-18">
                                {{strip_tags(Session::get('ShoppingCart.Shipping.ShippingMethodName'))}}
                                @if(Session::has('ShoppingCart.Shipping.ShippingDays') && Session::get('ShoppingCart.Shipping.ShippingDays') != '')
                                    <br> 
                                    EST : {!!Session::get('ShoppingCart.Shipping.ShippingDays')!!}
                                    
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="col-4 pb-md-5" align="right">
                        <a href="{{config('global.SITE_URL')}}checkout{{$payment_method_url}}" class="edit-btn btn btn-secondary" data-index="3">Edit</a>
                    </div>
                </div>  
            </li>
            <li class="billtab shipping-method border-md-top">
                @include('checkout.payment-methods',[$CartDetails,$CartAttr])
            </li>
        </ul>
    </div>
</div>
@include('checkout.checkout-footer')	
@endsection
