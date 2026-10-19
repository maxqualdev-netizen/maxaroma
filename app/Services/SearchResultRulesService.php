<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Storefront reader for Search Result Rules (pin / hide).
 * Rules are authored in pu-panel and compiled on Rebuild.
 */
class SearchResultRulesService
{
	/**
	 * @return array{pinned_positions:list<array<string,mixed>>,hidden_items:list<array<string,mixed>>}
	 */
	public function resolveForQuery(string $query, string $categoryId = ''): array
	{
		$context = [
			'category_id' => trim($categoryId),
			'query' => trim($query),
			'index_uid' => (string) config('search_result_rules.index_uid', 'products_maxaroma'),
			'facet_filters' => [],
		];
		if ($context['category_id'] !== '') {
			$context['facet_filters']['categoryids'] = $context['category_id'];
		}

		return [
			'pinned_positions' => $this->resolvePinnedPositions($context),
			'hidden_items' => $this->resolveHiddenItems($context),
		];
	}

	/**
	 * Condition-scoped sort from matching Search Result Rules (empty if none / shopper should use default).
	 *
	 * @return list<string> e.g. ['inventory_count:desc','price:asc']
	 */
	public function resolveSortOverrideForQuery(string $query, string $categoryId = '', array $facetFilters = []): array
	{
		$context = [
			'category_id' => trim($categoryId),
			'query' => trim($query),
			'index_uid' => (string) config('search_result_rules.index_uid', 'products_maxaroma'),
			'facet_filters' => $facetFilters,
		];
		if ($context['category_id'] !== '' && !isset($context['facet_filters']['categoryids'])) {
			$context['facet_filters']['categoryids'] = $context['category_id'];
		}

		$this->ensureMatchingHelpersLoaded();
		if (function_exists('search_result_rules_resolve_sort_override')) {
			$clauses = search_result_rules_resolve_sort_override($context);
			return is_array($clauses) ? $clauses : [];
		}

		return [];
	}

	/**
	 * @return array{pinned_positions:list<array<string,mixed>>,hidden_items:list<array<string,mixed>>}
	 */
	public function resolveForRequest(?Request $request): array
	{
		if ($request === null) {
			return ['pinned_positions' => [], 'hidden_items' => []];
		}

		$query = '';
		if ($request->filled('org_search_keyword')) {
			$query = trim((string) $request->input('org_search_keyword'));
		} elseif ($request->filled('keyword')) {
			$query = trim((string) $request->input('keyword'));
		} elseif ($request->filled('q')) {
			$query = trim((string) $request->input('q'));
		}

		$categoryId = '';
		if (isset($request->bcategory_id) && is_numeric($request->bcategory_id) && (int) $request->bcategory_id > 0) {
			$categoryId = (string) (int) $request->bcategory_id;
		} elseif (isset($request->category_id) && is_numeric($request->category_id) && (int) $request->category_id > 0) {
			$categoryId = (string) (int) $request->category_id;
		}

		return $this->resolveForQuery($query, $categoryId);
	}

	/**
	 * Splice pin/hide into a Meilisearch search result page.
	 *
	 * @param array{hits:list<object|array>,total:int,facets?:mixed,minPrice?:float,maxPrice?:float} $result
	 * @param callable(string):(?object) $fetchBySku
	 * @return array{hits:list<object|array>,total:int,facets?:mixed,minPrice?:float,maxPrice?:float}
	 */
	public function applyToSearchResult(array $result, string $matchQuery, int $page, int $perPage, callable $fetchBySku): array
	{
		$matchQuery = trim($matchQuery);
		if ($matchQuery === '') {
			return $result;
		}

		$page = max(1, $page);
		$perPage = max(1, $perPage);
		$rules = $this->resolveForQuery($matchQuery);
		$pinned = $rules['pinned_positions'];
		$hidden = $rules['hidden_items'];
		if ($pinned === [] && $hidden === []) {
			return $result;
		}

		$hiddenSkus = [];
		foreach ($hidden as $row) {
			$sku = strtoupper(trim((string) ($row['SKU'] ?? '')));
			if ($sku !== '') {
				$hiddenSkus[$sku] = true;
			}
		}

		$allPinSkus = [];
		$pagePins = [];
		foreach ($pinned as $pin) {
			if (!is_array($pin)) {
				continue;
			}
			$sku = trim((string) ($pin['SKU'] ?? ''));
			if ($sku === '') {
				continue;
			}
			$skuKey = strtoupper($sku);
			if (isset($hiddenSkus[$skuKey])) {
				continue;
			}
			$allPinSkus[$skuKey] = $sku;
			if ((int) ($pin['page'] ?? 1) === $page) {
				$pagePins[] = $pin;
			}
		}

		$hitsBySku = [];
		foreach ($allPinSkus as $skuKey => $sku) {
			$hit = $fetchBySku($sku);
			if (is_object($hit) || is_array($hit)) {
				$hitsBySku[$skuKey] = $hit;
			}
		}

		$organic = [];
		foreach ((array) ($result['hits'] ?? []) as $hit) {
			$sku = strtoupper(trim((string) $this->hitSku($hit)));
			if ($sku === '' || isset($hiddenSkus[$sku]) || isset($allPinSkus[$sku])) {
				continue;
			}
			$organic[] = $hit;
		}

		$insertMap = [];
		foreach ($pagePins as $pin) {
			$skuKey = strtoupper(trim((string) ($pin['SKU'] ?? '')));
			if ($skuKey === '' || !isset($hitsBySku[$skuKey])) {
				continue;
			}
			$pos = (int) ($pin['position'] ?? 0);
			if ($pos < 0) {
				$pos = 0;
			}
			$insertMap[$pos] = $hitsBySku[$skuKey];
		}
		ksort($insertMap);

		$merged = [];
		$organicIdx = 0;
		$organicCount = count($organic);
		$slot = 0;
		while (count($merged) < $perPage && ($organicIdx < $organicCount || $insertMap !== [])) {
			if (isset($insertMap[$slot])) {
				$merged[] = $insertMap[$slot];
				unset($insertMap[$slot]);
			} elseif ($organicIdx < $organicCount) {
				$merged[] = $organic[$organicIdx];
				$organicIdx++;
			} else {
				// Remaining inserts beyond current organic length (e.g. late positions).
				$nextPos = array_key_first($insertMap);
				if ($nextPos === null) {
					break;
				}
				$merged[] = $insertMap[$nextPos];
				unset($insertMap[$nextPos]);
			}
			$slot++;
		}

		$resolvablePins = count($hitsBySku);
		$baseTotal = (int) ($result['total'] ?? 0);
		// Approximate: organic total minus hidden, plus pins that were not already in organic set.
		$result['hits'] = $merged;
		$result['total'] = max(count($merged), $baseTotal - count($hiddenSkus) + $resolvablePins);

		return $result;
	}

	/**
	 * @param array<string, mixed> $context
	 * @return list<array<string, mixed>>
	 */
	public function resolvePinnedPositions(array $context): array
	{
		return $this->resolveConsequenceItems($context, 'pinned_positions');
	}

	/**
	 * @param array<string, mixed> $context
	 * @return list<array<string, mixed>>
	 */
	public function resolveHiddenItems(array $context): array
	{
		return $this->resolveConsequenceItems($context, 'hidden_items');
	}

	/**
	 * @param array<string, mixed> $context
	 * @param 'pinned_positions'|'hidden_items' $key
	 * @return list<array<string, mixed>>
	 */
	protected function resolveConsequenceItems(array $context, string $key): array
	{
		$this->ensureMatchingHelpersLoaded();
		if (function_exists('search_result_rules_resolve_pinned_positions') && $key === 'pinned_positions') {
			return search_result_rules_resolve_pinned_positions($context);
		}
		if (function_exists('search_result_rules_resolve_hidden_items') && $key === 'hidden_items') {
			return search_result_rules_resolve_hidden_items($context);
		}

		$cache = $this->readFullCache();
		if ($cache === null) {
			return [];
		}
		$out = [];
		foreach (($cache['rules'] ?? []) as $rule) {
			if (!is_array($rule) || !$this->ruleMatchesContext($rule, $context)) {
				continue;
			}
			foreach ((array) ($rule[$key] ?? []) as $item) {
				if (is_array($item) && !empty($item['SKU'])) {
					$out[] = $item;
				}
			}
		}

		return $out;
	}

	/** @return array<string, mixed>|null */
	public function readFullCache(): ?array
	{
		$this->ensureMatchingHelpersLoaded();
		if (function_exists('search_result_rules_read_full_cache')) {
			$data = search_result_rules_read_full_cache();
			if (is_array($data)) {
				return $data;
			}
		}

		try {
			$data = Cache::store('file')->get('search_result_rules:full');
			if (is_array($data)) {
				return $data;
			}
		} catch (\Throwable $e) {
			// continue
		}

		foreach ($this->cacheFileCandidates() as $path) {
			if ($path === '' || !is_file($path)) {
				continue;
			}
			$raw = @file_get_contents($path);
			if (!is_string($raw) || trim($raw) === '') {
				continue;
			}
			$data = json_decode($raw, true);
			if (is_array($data)) {
				return $data;
			}
		}

		return null;
	}

	/** @return list<string> */
	protected function cacheFileCandidates(): array
	{
		$paths = [];
		$primary = (string) config('search_result_rules.cache_full', '');
		if ($primary !== '') {
			$paths[] = $primary;
		}
		$legacy = base_path('pu-panel/cache/search_result_rules.json');
		if ($legacy !== $primary) {
			$paths[] = $legacy;
		}

		return $paths;
	}

	protected function ensureMatchingHelpersLoaded(): void
	{
		static $loaded = false;
		if ($loaded) {
			return;
		}
		$path = base_path('pu-panel/functions/search_result_rules_matching.php');
		if (is_file($path)) {
			require_once $path;
		}
		$loaded = true;
	}

	/**
	 * @param array<string, mixed> $rule
	 * @param array<string, mixed> $context
	 */
	protected function ruleMatchesContext(array $rule, array $context): bool
	{
		if (function_exists('search_result_rules_rule_matches_context')) {
			return search_result_rules_rule_matches_context($rule, $context);
		}

		$ruleIndex = (string) ($rule['index_uid'] ?? 'products_maxaroma');
		$ctxIndex = (string) ($context['index_uid'] ?? 'products_maxaroma');
		if ($ruleIndex !== '' && $ctxIndex !== '' && $ruleIndex !== $ctxIndex) {
			return false;
		}

		if (!empty($rule['apply_globally'])) {
			return true;
		}

		$conditions = (array) ($rule['conditions'] ?? []);
		if ($conditions === []) {
			return true;
		}

		foreach ($conditions as $cond) {
			if (!is_array($cond)) {
				continue;
			}
			$queryText = trim((string) ($cond['query_text'] ?? ''));
			$queryEnabled = !empty($cond['query_enabled']) && $queryText !== '';
			$filtersEnabled = !empty($cond['filters_enabled']);
			if (!$queryEnabled && !$filtersEnabled) {
				continue;
			}
			if ($queryEnabled) {
				$user = $this->normalizeQuery((string) ($context['query'] ?? ''));
				$ruleQ = $this->normalizeQuery($queryText);
				$match = (string) ($cond['query_match'] ?? 'is');
				$ok = $match === 'contains'
					? ($user !== '' && $ruleQ !== '' && stripos($user, $ruleQ) !== false)
					: ($user === $ruleQ);
				if (!$ok) {
					continue;
				}
			}
			if ($filtersEnabled) {
				continue;
			}

			return true;
		}

		return false;
	}

	protected function normalizeQuery(string $query): string
	{
		$query = trim(str_replace(['-', '_'], ' ', $query));
		$query = function_exists('mb_strtolower') ? mb_strtolower($query, 'UTF-8') : strtolower($query);

		return (string) preg_replace('/\s+/', ' ', $query);
	}

	/** @param object|array<string,mixed> $hit */
	protected function hitSku($hit): string
	{
		if (is_object($hit)) {
			return (string) ($hit->sku ?? $hit->uid ?? '');
		}
		if (is_array($hit)) {
			return (string) ($hit['sku'] ?? $hit['uid'] ?? $hit['objectID'] ?? '');
		}

		return '';
	}
}
