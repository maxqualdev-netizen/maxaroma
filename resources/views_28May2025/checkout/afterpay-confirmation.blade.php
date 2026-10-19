<!DOCTYPE html>
<html>
<head>
    <title>Deferred Payment Auth Request Sample</title>
</head>
<body>
    @if($type == 'error')
        <h3>Error</h3>
        <strong>{{ $error->message }}</strong>
        <p><a href="{{ url('afterpay/placeorder') }}">Try again</a></p>
    @elseif($type == 'order')
        <h3>Order Record Created</h3>
        <ul>
            <li>Order ID: {{ $order->id }}</li>
            <li>Status: {{ $order->status }}</li>
            <li>Is Approved? {!! $isApproved !!}</li>
        </ul>
        @if($isApproved == 'YES')
            <p><a href="{{ url('afterpay/dopayment/'.$order->id) }}">Capture Payment for this order</a></p>
            <p><a href="{{ url('afterpay/void/'.$order->id) }}">Void Payment for this order</a></p>
        @else
            <p><a href="{{ url('afterpay/placeorder') }}">Start again</a></p>
        @endif
    @else
        <h3>Deferred Payment Auth</h3>
        <form method="POST">
            <div>Return here after checkout: <input type="text" name="redirectReturnUrl" value="<?php echo 'http' . ((! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://' . htmlspecialchars($_SERVER['HTTP_HOST']) . (strstr($_SERVER['REQUEST_URI'], '?') ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) : $_SERVER['REQUEST_URI']); ?>"></div>
            <div><button type="submit">Proceed to Afterpay</button></div>
        </form>
    @endif
</body>
</html>