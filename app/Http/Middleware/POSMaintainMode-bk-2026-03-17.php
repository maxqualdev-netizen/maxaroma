<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class POSMaintainMode
{
    public function handle(Request $request, Closure $next)
    {
        $maintenanceMode = config('global.POSMode'); // or any other way you define your global variable

        if (!$maintenanceMode) {
            return response('Please contact admin for this', 503);
        }

        return $next($request);
    }
}

?>
