<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Agent\Agent;

/**
 * Storefront matcher for pu-panel Search Redirect Rules.
 * Prefers Laravel file cache written by admin "Rebuild Cache"; warms from DB if cold.
 */
class SearchRedirectRuleService
{
	private const META_KEY = 'search_redirect_rules:meta';

	private const CACHE_KEYS = [
		'exact' => 'search_redirect_rules:exact',
		'starts_with' => 'search_redirect_rules:starts_with',
		'contains' => 'search_redirect_rules:contains',
		'regex' => 'search_redirect_rules:regex',
	];

	private const BUCKET_ORDER = ['exact', 'starts_with', 'contains', 'regex'];

	private static bool $matchersLoaded = false;

	/** @var array<string, list<array<string, mixed>>>|null */
	private static ?array $rulesBuckets = null;

	/**
	 * @return array<string, mixed>|null
	 */
	public function match(string $query, string $device = 'all'): ?array
	{
		$query = trim($query);
		if ($query === '') {
			return null;
		}

		$this->loadMatchers();
		$normalized = function_exists('search_redirect_rules_normalize_query')
			? search_redirect_rules_normalize_query($query)
			: strtolower($query);

		foreach (self::BUCKET_ORDER as $type) {
			foreach ($this->getRulesBuckets()[$type] ?? [] as $rule) {
				if (!is_array($rule)) {
					continue;
				}
				if (!$this->deviceMatches((string) ($rule['device_type'] ?? 'all'), $device)) {
					continue;
				}
				if (!$this->ruleMatches($normalized, $query, $rule)) {
					continue;
				}
				$url = trim((string) ($rule['resolved_url'] ?? ''));
				if ($url === '') {
					continue;
				}

				return $rule;
			}
		}

		return null;
	}

	/**
	 * Resolved absolute redirect URL for a product search request, or null.
	 */
	public function resolveRedirectUrlForRequest(Request $request): ?string
	{
		if (!$this->isProductSearchRequest($request)) {
			return null;
		}

		$keyword = $this->keywordFromRequest($request);
		if ($keyword === '') {
			return null;
		}

		try {
			$device = $this->requestDevice();
			$rule = $this->match($keyword, $device);
			if ($rule === null) {
				return null;
			}

			$url = trim((string) ($rule['resolved_url'] ?? ''));
			if ($url === '') {
				return null;
			}

			return $this->normalizeRedirectUrl($url);
		} catch (\Throwable $e) {
			Log::warning('Search redirect resolve failed: ' . $e->getMessage());
			return null;
		}
	}

	public function isProductSearchRequest(Request $request): bool
	{
		$path = trim($request->path(), '/');
		// Maxaroma search PLP: /p4u/key-{keyword}/view
		if (preg_match('#(?:^|/)p4u/key-.+/view$#i', $path)) {
			return true;
		}
		// gbproduction-style paths (if ever reused)
		return $path === 'search-products'
			|| $path === 'search_products'
			|| str_ends_with($path, '/search-products')
			|| str_ends_with($path, '/search_products');
	}

	public function keywordFromRequest(Request $request): string
	{
		$keyword = trim((string) $request->input('keyword', ''));
		if ($keyword === '' && $request->route('keyword') !== null) {
			$keyword = trim((string) $request->route('keyword'));
		}
		if ($keyword === '') {
			return '';
		}
		// URL form uses dashes / andd / backslash tokens — match rules on readable text.
		$keyword = str_replace(['andd', 'backslash'], ['&', '/'], $keyword);
		$keyword = trim(preg_replace('/\s+/u', ' ', str_replace('-', ' ', $keyword)) ?? '');
		return $keyword;
	}

	public function normalizeRedirectUrl(string $url): string
	{
		$siteUrl = rtrim((string) config('global.SITE_URL', config('app.url')), '/');

		if (preg_match('#^https?://#i', $url)) {
			return $url;
		}

		if ($url !== '' && $url[0] === '/') {
			return $siteUrl !== '' ? $siteUrl . $url : $url;
		}

		return $siteUrl !== '' ? $siteUrl . '/' . ltrim($url, '/') : '/' . ltrim($url, '/');
	}

	public function requestDevice(): string
	{
		try {
			$agent = new Agent();
			return $agent->isMobile() ? 'mobile' : 'desktop';
		} catch (\Throwable $e) {
			return 'desktop';
		}
	}

	/**
	 * @return array<string, list<array<string, mixed>>>
	 */
	private function getRulesBuckets(): array
	{
		if (self::$rulesBuckets !== null) {
			return self::$rulesBuckets;
		}

		$buckets = $this->emptyBuckets();

		if (!$this->loadBucketsFromLaravelCache($buckets)) {
			$this->warmCacheFromDatabase();
			$this->loadBucketsFromLaravelCache($buckets);
		}

		self::$rulesBuckets = $buckets;

		return $buckets;
	}

	/**
	 * @param array<string, list<array<string, mixed>>> $buckets
	 */
	private function loadBucketsFromLaravelCache(array &$buckets): bool
	{
		try {
			$meta = Cache::store('file')->get(self::META_KEY);
			$any = false;
			foreach (self::BUCKET_ORDER as $type) {
				$data = Cache::store('file')->get(self::CACHE_KEYS[$type]);
				if (is_array($data)) {
					$buckets[$type] = $data;
					if (count($data) > 0) {
						$any = true;
					}
				}
			}
			return $any || (is_array($meta) && (($meta['rule_count'] ?? 0) > 0 || isset($meta['built_at'])));
		} catch (\Throwable $e) {
			Log::debug('Search redirect Laravel cache read failed: ' . $e->getMessage());
			return false;
		}
	}

	private function warmCacheFromDatabase(): void
	{
		try {
			Cache::lock('search_redirect_rules_warm', 15)->block(5, function () {
				$buckets = $this->buildBucketsFromDatabase();
				$this->persistBucketsToLaravelCache($buckets);
				self::$rulesBuckets = $buckets;
			});
		} catch (\Throwable $e) {
			Log::warning('Search redirect cache warm failed: ' . $e->getMessage());
		}
	}

	/**
	 * @return array<string, list<array<string, mixed>>>
	 */
	private function buildBucketsFromDatabase(): array
	{
		$buckets = $this->emptyBuckets();
		$table = 'pu_search_redirect_rules';

		try {
			if (!Schema::hasTable($table)) {
				return $buckets;
			}

			$rows = DB::table($table)
				->where('status', 1)
				->orderByDesc('priority')
				->orderByDesc('id')
				->get();
		} catch (\Throwable $e) {
			Log::warning('Search redirect rules DB read failed: ' . $e->getMessage());
			return $buckets;
		}

		$this->loadMatchers();

		foreach ($rows as $row) {
			$mt = (string) ($row->match_type ?? '');
			if (!isset($buckets[$mt])) {
				continue;
			}

			$resolved = $this->resolveUrl(
				(string) ($row->redirect_type ?? 'custom_url'),
				(string) ($row->redirect_target ?? '')
			);

			$rule = [
				'id' => (int) $row->id,
				'keyword' => (string) $row->keyword,
				'match_type' => $mt,
				'redirect_type' => (string) ($row->redirect_type ?? 'custom_url'),
				'redirect_target' => (string) ($row->redirect_target ?? ''),
				'priority' => (int) ($row->priority ?? 0),
				'device_type' => (string) ($row->device_type ?? 'all'),
				'apply_plurals' => (int) ($row->apply_plurals ?? 1),
				'apply_synonyms' => (int) ($row->apply_synonyms ?? 1),
				'apply_typos' => (int) ($row->apply_typos ?? 1),
				'synonyms' => (string) ($row->synonyms ?? ''),
				'resolved_url' => $resolved,
			];

			if ($mt === 'exact' && function_exists('search_redirect_rules_build_match_terms')) {
				$rule['match_terms'] = search_redirect_rules_build_match_terms($rule);
			}

			$buckets[$mt][] = $rule;
		}

		return $buckets;
	}

	/**
	 * @param array<string, list<array<string, mixed>>> $buckets
	 */
	private function persistBucketsToLaravelCache(array $buckets): void
	{
		try {
			$store = Cache::store('file');
			$total = 0;
			foreach (self::BUCKET_ORDER as $type) {
				$list = $buckets[$type] ?? [];
				$total += count($list);
				$store->forever(self::CACHE_KEYS[$type], $list);
			}
			$store->forever(self::META_KEY, [
				'built_at' => gmdate('c'),
				'version' => 1,
				'rule_count' => $total,
				'source' => 'laravel_warm',
			]);
		} catch (\Throwable $e) {
			Log::warning('Search redirect cache persist failed: ' . $e->getMessage());
		}
	}

	/**
	 * @return array<string, list<array<string, mixed>>>
	 */
	private function emptyBuckets(): array
	{
		return [
			'exact' => [],
			'starts_with' => [],
			'contains' => [],
			'regex' => [],
		];
	}

	private function deviceMatches(string $ruleDevice, string $requestDevice): bool
	{
		if ($ruleDevice === 'all') {
			return true;
		}
		return $ruleDevice === $requestDevice;
	}

	/**
	 * @param array<string, mixed> $rule
	 */
	private function ruleMatches(string $normalizedQuery, string $originalQuery, array $rule): bool
	{
		$keyword = (string) ($rule['keyword'] ?? '');
		if ($keyword === '') {
			return false;
		}

		if (function_exists('search_redirect_rules_rule_matches')) {
			return search_redirect_rules_rule_matches($normalizedQuery, $originalQuery, $rule);
		}

		$matchType = (string) ($rule['match_type'] ?? 'exact');
		$kwLower = function_exists('search_redirect_rules_normalize_query')
			? search_redirect_rules_normalize_query($keyword)
			: strtolower($keyword);

		switch ($matchType) {
			case 'exact':
				if (function_exists('search_redirect_rules_exact_match_with_options')) {
					return search_redirect_rules_exact_match_with_options($normalizedQuery, $rule);
				}
				return $normalizedQuery === $kwLower;
			case 'contains':
				return str_contains($normalizedQuery, $kwLower);
			case 'starts_with':
				return str_starts_with($normalizedQuery, $kwLower);
			case 'regex':
				if (@preg_match($keyword, $originalQuery) === 1) {
					return true;
				}
				return @preg_match('/' . str_replace('/', '\/', $keyword) . '/i', $originalQuery) === 1;
			default:
				return false;
		}
	}

	private function resolveUrl(string $redirectType, string $redirectTarget): string
	{
		$redirectTarget = trim($redirectTarget);
		if ($redirectTarget === '') {
			return '';
		}

		$siteUrl = rtrim((string) config('global.SITE_URL', config('app.url')), '/');

		if ($redirectType === '' || $redirectType === 'custom_url') {
			return $this->normalizeRedirectUrl($redirectTarget);
		}

		if ($redirectType === 'cms_page') {
			$slug = ltrim($redirectTarget, '/');
			if (strpos($slug, 'pages/') === 0) {
				return $siteUrl . '/' . $slug;
			}
			return $siteUrl . '/pages/' . $slug;
		}

		if ($redirectType === 'category') {
			return $siteUrl . '/' . trim($redirectTarget, '/');
		}

		// product / legacy types: prefer absolute or site-relative target already stored
		return $this->normalizeRedirectUrl($redirectTarget);
	}

	private function loadMatchers(): void
	{
		if (self::$matchersLoaded) {
			return;
		}

		$matchingPath = base_path('pu-panel/functions/search_redirect_rules_matching.php');
		if (is_file($matchingPath)) {
			require_once $matchingPath;
		}

		self::$matchersLoaded = true;
	}
}
