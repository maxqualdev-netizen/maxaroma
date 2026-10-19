@extends('layouts.app')
@section('content')
<main class="pos-page" role="main" aria-label="POS Page">
    <section class="breadcrumb" aria-label="Breadcrumb">
        <div class="container d-flex align-items-center" role="navigation" aria-label="Breadcrumb Navigation">
            <a href="{{url('store/store-dashboard.html')}}" aria-label="Home">Home</a>
            <span class="active" aria-current="page">POS</span>
        </div>
    </section>
    <!-- New Order Page Content Page Start Here -->
    <div class="container pt-lg-5 pt-2 pb-5 pos-new-order-main" role="region" aria-labelledby="pos-new-order-title">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-xs-12" role="navigation" aria-label="My Account Sidebar">
                @include('pos.leftmenu')
            </div>
            <div class="col-lg-9 col-md-12 col-xs-12 pt-lg-0 pt-3" role="region" aria-label="POS New Order Management">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="posmain-hd" id="pos-new-order-title">POS Customer Autologin</h2>
                </div>
                <div class="row">
                    @if($msg != '')
                    <p class="mb-3 alert alert-danger p-2 text-center">
                        {{ $msg }}</p>
                    @endif    
                    <form id="frmCustomerAutoLogin" action="{{ $action }}" method="post">                        
                        <input type="hidden" name="file" value="login_a" />
                        <input type="hidden" id="vemail" name="vemail" value="{{ $email }}">
                        <input type="hidden" id="vpassword" name="vpassword" value="{{ $password }}">
                        <input type="hidden" name="automated_login" value="yes">
                    </form>                                       
                </div>
                <!-- Back Link -->
                
            </div>
        </div>
    </div>
    <!-- New Order Page Content Page End Here -->
</main>
<script>
//document.getElementById('frmCustomerAutoLogin').submit();

$(document).ready(function () {
    if($("#vemail").val()!='' && $("vpassword").val()!=''){
        $("#page-spinner").show();
        $('#frmCustomerAutoLogin').submit();
    }    
});


</script>
@endsection
