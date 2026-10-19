<?php

namespace App\Http\Middleware;

use App\Services\SearchRedirectRuleService;
use App\Support\SearchRedirectHttp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin search redirect rules — must exit before controller/views (staging-safe).
 */
class SearchRedirectRules
{
	public function handle(Request $request, Closure $next): Response
	{
		try {
			if (!class_exists(SearchRedirectRuleService::class)) {
				return $next($request);
			}
			$target = app(SearchRedirectRuleService::class)->resolveRedirectUrlForRequest($request);
			if ($target !== null) {
				SearchRedirectHttp::send($target);
			}
		} catch (\Throwable $e) {
			\Log::warning('search.redirect middleware skipped: ' . $e->getMessage());
		}

		return $next($request);
	}
}
