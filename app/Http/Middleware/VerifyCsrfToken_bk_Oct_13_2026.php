<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //'clearfrontcache','/paypal_fund_process/*','/autologin' //commented while adding phone order stuff on 16 Feb 2024
        'clearfrontcache','/paypal_fund_process/*','/autologin','/phone_order_discount','createfrontcache',
		'max_analytics/*',
    ];
}
