<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SearchVarsAuth
{
    public function handle(Request $request, Closure $next)
    {
		
        $username = env('SEARCHVARS_USER', 'minisha');
        $password = env('SEARCHVARS_PASS', 'minisha75');
		
        if (
            !isset($_SERVER['PHP_AUTH_USER']) ||
            !isset($_SERVER['PHP_AUTH_PW']) ||
            $_SERVER['PHP_AUTH_USER'] !== $username ||
            $_SERVER['PHP_AUTH_PW'] !== $password
        ) {
            return response('Unauthorized', 401)
                ->header('WWW-Authenticate', 'Basic realm="SearchVars"');
        }

        return $next($request);
    }
}