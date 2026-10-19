<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>MaxAroma Receipt</title>

<style>

body{
    width:302px;
    margin:0 auto;
    padding:10px;
    font-family: "Courier New", monospace;
    font-size:12px;
    color:#000;
}

.center{
    text-align:center;
}

.left{
    text-align:left;
}

.right{
    text-align:right;
}

.bold{
    font-weight:bold;
}

.line{
    border-top:1px dashed #000;
    margin:8px 0;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:2px 0;
}

th{
    text-align:left;
}

.total-box{
    font-size:20px;
    font-weight:bold;
}

.barcode{
    text-align:center;
    margin-top:10px;
}

.barcode div{
    font-size:28px;
    letter-spacing:3px;
}

@media print{
    body{
        width:302px;
    }
    @page{
        margin:0;
    }
}

</style>

</head>

<body onload="window.print()">

<div class="center bold">
@if($logo != '')
<img src="{{ $logo }}" width="100"><br><br><br>
@endif
</div>

<div class="center">
MAXAROMA<br>
3117 38th Ave<br>
Long Island City, NY 11101<br>
United States
</div>

<div class="line"></div>

<table>
<tr>
<td>Date:{{$OrderRs->datetime}}</td>
<td class="right" style="display:none;">Time:HH:MM PM</td>
</tr>
</table>
@if(count($OrderDetailRs) > 0)
<div class="line"></div>

<div class="bold">SALE</div>
 
<table>

<tr class="bold">
<th>SKU</th>
<th>Description</th>
<th>Qty.</th>
<th class="right">Total</th>
</tr>

@foreach($OrderDetailRs as $od)
<tr>
<td>{{ $od['UPC'] }}</td>
<td>{!! $od['prodname'] !!}</td>
<td>{{ $od['quantity'] }}</td>
<td class="right">{{ $od['total'] }}</td>
</tr>
@endforeach

</table>

<div class="line"></div>
@endif
<table>

<tr>
<td>Subtotal</td>
<td class="right">{{$OrderRs->sub_total}}</td>
</tr>
@if($OrderRs->tax > 0 )
<tr>
<td>Tax</td>
<td class="right">{{$OrderRs->tax}}</td>
</tr>
@endif
@if($OrderRs->gift_charge > 0 )
<tr>
<td>Gift Wrap Charge</td>
<td class="right">{{$OrderRs->gift_charge}}</td>
</tr>
@endif
@if($OrderRs->auto_discount > 0 )
<tr>
<td>Auto Discount</td>
<td class="right">{{$OrderRs->auto_discount}}</td>
</tr>
@endif
@if($OrderRs->quantity_discount > 0 )
<tr>
<td>Quantity Discount</td>
<td class="right">{{$OrderRs->quantity_discount}}</td>
</tr>
@endif
@if($OrderRs->coupon_amount > 0 )
<tr>
<td>Coupon Discount</td>
<td class="right">{{$OrderRs->coupon_amount}}</td>
</tr>
@endif
@if($OrderRs->gc_amount > 0 )
<tr>
<td>Gift Certificate</td>
<td class="right">{{$OrderRs->gc_amount}}</td>
</tr>
@endif
@if($OrderRs->refer_amount > 0 )
<tr>
<td>Refer Discount</td>
<td class="right">{{$OrderRs->refer_amount}}</td>
</tr>
@endif
@if($OrderRs->reward_discount > 0 )
<tr>
<td>Reward Discount</td>
<td class="right">{{$OrderRs->reward_discount}}</td>
</tr>
@endif
@if($OrderRs->bogo_discount > 0 )
<tr>
<td>BOGO Discount</td>
<td class="right">{{$OrderRs->bogo_discount}}</td>
</tr>
@endif
@if($OrderRs->apply_credit > 0 )
<tr>
<td>Credit Discount</td>
<td class="right">{{$OrderRs->apply_credit}}</td>
</tr>
@endif
</table>

<div class="line"></div>

<table>
<tr class="total-box">
<td>Total</td>
<td class="right">{{ config('global.SITE_CURRENCY_SYMBOL') }}{{ $OrderRs->order_total }}</td>
</tr>
</table>

<div class="line"></div>

<div>
Charge &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $OrderRs->order_total }}<br>
Visa XXXX-XXXX-XXXX-0123
</div>

<div class="line"></div>

<div class="center bold">
Refunds and Exchanges
</div>

<div class="center">
Within 30 days with receipt<br>
And tags attached
</div>

<div class="barcode">
<div></div>
<div>MaxAroma</div>
</div>

</body>
</html>