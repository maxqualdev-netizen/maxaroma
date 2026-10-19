<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AutoClearLargeCookies
{
   protected $maxCookieSize = 8000; // 8 KB

   public function handle(Request $request, Closure $next): Response
	{
    if ($request->session()->get('cookies_already_cleared')) {
        return $next($request);
    }
    $cookieSize = 0;
    foreach ($request->cookies->all() as $key => $value) {
        $cookieSize += strlen($key . '=' . $value . ';');
    }
    if ($cookieSize > $this->maxCookieSize && !$request->query('cookie_checked')) {
        \Log::info('Cookie Size: ' . $cookieSize); // Safe logging

        $request->session()->put('cookies_already_cleared', true);
        $request->session()->save();

        $excludeCookies = ['laravel_session', 'XSRF-TOKEN'];
        foreach ($request->cookies->all() as $key => $value) {
            if (!in_array($key, $excludeCookies)) {
                Cookie::queue(Cookie::forget($key));
            }
        }
        return redirect($request->fullUrl());
    }
    return $next($request);
}
}
