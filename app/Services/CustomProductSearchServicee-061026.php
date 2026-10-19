<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\Meili\Client;
use App\Support\SizeNormalizer;
use Throwable;

class CustomProductSearchService
{
	private ?array $lastSearchDebug = null;

	/** @var list<string> */
	private array $activeGenderFilter = [];

	/** Exclusive Star Rating bucket(s) for PHP post-filter: 3, 4, and/or 5. */
	/** @var list<int> */
	private array $activeStarBuckets = [];

	/** Similar-to: drop reference uid / same product line from ranked hits. */
	private string $similarExcludeUid = '';
	/** @var list<string> */
	private array $similarExcludeTokens = [];
	private string $similarExcludeBrand = '';

	/** Original shopper utterance used for PHP relevance (may differ from Meili q). */
	private string $rankingQuery = '';

	/** LLM asked to scan description / marketing copy for this query. */
	private bool $searchDescription = false;

	/** Popup autocomplete: smaller Meili fetch, skip heavy secondary queries. */
	private bool $popupFastMode = false;

	public function __construct(
		private Client $client,
		private ProductFilterCatalogService $catalog
	) {
	}

	public function catalog(): ProductFilterCatalogService
	{
		return $this->catalog;
	}

	public function indexName(): string
	{
		return (string) config('global.CUSTOM_PRODUCT_INDEX', 'products_maxaroma');
	}

	/**
	 * Exact SKU/uid lookup for search-box rail — no product_type / stock filters.
	 * Returns a mapped hit object or null.
	 */
	public function findExactSku(string $sku): ?object
	{
		$sku = trim($sku);
		if ($sku === '') {
			return null;
		}
		$skuLower = strtolower($sku);
		$quoted = $this->quoteValue($sku);
		$variants = array_values(array_unique([
			$sku,
			strtoupper($sku),
			strtolower($sku),
		]));

		try {
			$index = $this->client->index($this->indexName());

			// Prefer filter lookup when sku is filterable.
			foreach ($variants as $variant) {
				$q = $this->quoteValue($variant);
				foreach ([
					'sku = ' . $q,
					'uid = ' . $q,
					'(sku = ' . $q . ' OR uid = ' . $q . ')',
				] as $filter) {
					try {
						$raw = $index->search('', [
							'filter' => $filter,
							'limit' => 3,
						]);
						$hits = is_array($raw) ? ($raw['hits'] ?? []) : [];
						if (method_exists($raw, 'getHits')) {
							$hits = $raw->getHits();
						}
						foreach ((array) $hits as $hit) {
							if (!is_array($hit)) {
								continue;
							}
							$hitSku = strtolower(trim((string) ($hit['sku'] ?? '')));
							$hitUid = strtolower(trim((string) ($hit['uid'] ?? $hit['id'] ?? '')));
							if ($hitSku === $skuLower || $hitUid === $skuLower) {
								$mapped = $this->mapHits([$hit]);
								return $mapped[0] ?? null;
							}
						}
					} catch (Throwable $e) {
						// sku may not be filterable on this index — fall through to q search.
					}
				}
			}

			// Fallback: free-text search, exact SKU match only.
			$raw = $index->search($sku, ['limit' => 20]);
			$hits = is_array($raw) ? ($raw['hits'] ?? []) : [];
			if (method_exists($raw, 'getHits')) {
				$hits = $raw->getHits();
			}
			foreach ((array) $hits as $hit) {
				if (!is_array($hit)) {
					continue;
				}
				$hitSku = strtolower(trim((string) ($hit['sku'] ?? '')));
				$hitUid = strtolower(trim((string) ($hit['uid'] ?? $hit['id'] ?? '')));
				if ($hitSku === $skuLower || $hitUid === $skuLower) {
					$mapped = $this->mapHits([$hit]);
					return $mapped[0] ?? null;
				}
			}
		} catch (Throwable $e) {
			Log::warning('findExactSku failed: ' . $e->getMessage(), ['sku' => $sku]);
		}

		return null;
	}

	/**
	 * @param array<string, mixed> $filters
	 * @return array{hits: array, total: int, facets: array, minPrice: float, maxPrice: float, query: string}
	 */
	public function search(string $query, array $filters = [], int $page = 1, int $perPage = 24, bool $captureDebug = false): array
	{
		$empty = [
			'hits' => [],
			'total' => 0,
			'facets' => [],
			'minPrice' => 0.0,
			'maxPrice' => 0.0,
			'query' => $query,
		];

		$page = max(1, $page);
		$perPage = max(1, min(100, $perPage));
		$rawQuery = $query;
		$rankHint = trim((string) ($filters['_rank_query'] ?? ''));
		// Shopper-facing query for Search Result Rules (NLP brand browse often clears $query).
		$ruleMatchQuery = $rankHint !== '' ? $rankHint : $rawQuery;
		$searchDescription = !empty($filters['_search_description']);
		unset($filters['_rank_query'], $filters['_search_description']);
		// Longevity/quality phrases flood description matches (SS ~title/keywords). Keep description
		// for scene/mood copy only (e.g. date night), not "long lasting perfume".
		$this->searchDescription = $searchDescription && !$this->isDescriptionNoisyQualityQuery(
			$rankHint !== '' ? $rankHint : $rawQuery
		);
		// Scene/mood: rank on the applied keyword ("date night"), not the full NL utterance.
		// Ranking on "Best perfume for a date night" makes last-token "night" over-boost Night titles.
		if ($this->searchDescription && trim($rawQuery) !== '') {
			$this->rankingQuery = $this->prepareSearchQuery($rawQuery);
		} else {
			$this->rankingQuery = $this->prepareSearchQuery($rankHint !== '' ? $rankHint : $rawQuery);
		}
		$query = $this->prepareSearchQuery($query);
		[$filters, $query] = $this->applyGenderIntent($query, $filters);
		// Structural budget parse (under $X) — not a sight-word list.
		foreach ($this->autocompleteBudgetFilters($rawQuery) as $k => $v) {
			if (!$this->filtersHaveConstraint([$k => $v])) {
				continue;
			}
			if (!isset($filters[$k]) || $filters[$k] === '' || $filters[$k] === []) {
				$filters[$k] = $v;
			}
		}
		$this->activeGenderFilter = $this->normalizeGenderFilterCodes($filters['gender'] ?? null);
		$this->activeStarBuckets = $this->selectedStarBuckets($filters);
		$this->similarExcludeUid = trim((string) ($filters['exclude_uid'] ?? ''));
		$this->similarExcludeBrand = strtolower(trim((string) ($filters['_similar_to_brand'] ?? '')));
		$this->similarExcludeTokens = [];
		foreach ((array) ($filters['_exclude_same_line_tokens'] ?? []) as $tok) {
			if (is_scalar($tok) && trim((string) $tok) !== '') {
				$this->similarExcludeTokens[] = strtolower(trim((string) $tok));
			}
		}
		// Drop tokens already expressed as filters (price / gender) so NL phrases can match.
		$query = $this->simplifyQueryForAppliedFilters($query, $filters);
		$query = $this->simplifyQueryForBadgeIntent($query, $filters);
		// Long conversational phrases: keep the strongest product tokens (e.g. perfume evening).
		if ($this->isLikelyNaturalLanguageQuery($query)) {
			$content = $this->preferContentTokens($query);
			if ($content !== '' && strcasecmp($content, $query) !== 0) {
				$query = $content;
			}
		}

		try {
			$index = $this->client->index($this->indexName());
			$searchFilters = $this->buildMeilisearchFilter($filters);
			$sort = $this->sortFromFilters($filters);
			// Condition-scoped sort from Search Result Rules (only when shopper has no explicit sort).
			if ($sort === []) {
				$ruleSort = $this->sortOverrideFromResultRules($ruleMatchQuery, $filters);
				if ($ruleSort !== []) {
					$sort = $ruleSort;
				}
			}
			$meiliSort = $this->resolveSortForIndex($sort);

			$result = $this->executeProductSearch($index, $query, $searchFilters, $sort, $meiliSort, $page, $perPage);
			$result = $this->guardHitsToSelectedStarBucket($result, $filters);
			$result = $this->guardHitsExcludeUid($result, $filters);

			// Short brand typos Meili won't correct (oneTypo needs 4+; older indexes may still be 6):
			// "roaj" → "roja". Try adjacent letter swaps when the typed query has 0 hits.
			if (($result['total'] ?? 0) <= 0) {
				$typoAlt = $this->findAdjacentTranspositionQuery($index, $query, $searchFilters);
				if ($typoAlt !== null && strcasecmp($typoAlt, $query) !== 0) {
					$query = $typoAlt;
					$result = $this->executeProductSearch($index, $query, $searchFilters, $sort, $meiliSort, $page, $perPage);
					$result = $this->guardHitsToSelectedStarBucket($result, $filters);
					$result = $this->guardHitsExcludeUid($result, $filters);
				}
			}

			// No static sight-word list: if matchingStrategy=all yields 0, drop tokens
			// until Meili finds hits (OpenAI NLP also drops sight words when enabled).
			// Skip on popup — each probe is another Meili round-trip.
			if (!$this->popupFastMode && ($result['total'] ?? 0) <= 0 && trim($query) !== '') {
				if (
					!empty($filters['special'])
					&& $this->isBadgeOnlyQueryText($query)
					&& $this->probeQueryHasHits($index, '', $searchFilters)
				) {
					$query = '';
					$result = $this->executeProductSearch($index, $query, $searchFilters, $sort, $meiliSort, $page, $perPage);
					$result = $this->guardHitsToSelectedStarBucket($result, $filters);
					$result = $this->guardHitsExcludeUid($result, $filters);
				} elseif ($this->isMultiWordQuery($query)) {
					$relaxed = $this->findRelaxedQuery($index, $query, $searchFilters);
					if (
						$relaxed !== null
						&& $relaxed !== $query
						&& !(
							!empty($filters['special'])
							&& $this->isBadgeOnlyQueryText($query)
							&& $this->isBadgeOnlyQueryText($relaxed)
							&& $this->probeQueryHasHits($index, '', $searchFilters)
						)
					) {
						$query = $relaxed;
						$result = $this->executeProductSearch($index, $query, $searchFilters, $sort, $meiliSort, $page, $perPage);
						$result = $this->guardHitsToSelectedStarBucket($result, $filters);
						$result = $this->guardHitsExcludeUid($result, $filters);
					} elseif (
						!empty($filters['special'])
						&& $this->isBadgeOnlyQueryText($query)
						&& $this->probeQueryHasHits($index, '', $searchFilters)
					) {
						$query = '';
						$result = $this->executeProductSearch($index, $query, $searchFilters, $sort, $meiliSort, $page, $perPage);
						$result = $this->guardHitsToSelectedStarBucket($result, $filters);
						$result = $this->guardHitsExcludeUid($result, $filters);
					}
				}
			}

			$useExactFirst = $this->isMultiWordQuery($query);
			
			if ($captureDebug || config('app.debug')) {
				$this->lastSearchDebug = array_merge([
					'index' => $this->indexName(),
					'raw_query' => $rawQuery,
					'normalized_query' => $this->normalizeQuery($rawQuery),
					'prepared_query' => $query,
					'filters_input' => $filters,
					'meilisearch_filter' => $searchFilters,
					'product_type_rule' => $this->productTypeDebugInfo(),
					'attributesToSearchOn' => $this->keywordSearchAttributes(),
					'sort_preferred' => $sort,
					'sort_meili' => $meiliSort,
					'ranking_mode' => $useExactFirst
						? ($sort === [] ? 'searchspring_best_match' : 'multiword_sorted')
						: ($sort === [] ? 'prefer_in_stock' : 'all'),
					'matchingStrategy' => 'all',
				], $result['debug'] ?? []);
				if ($captureDebug) {
					Log::info('Meilisearch product search payload', $this->lastSearchDebug);
				}
			}

			unset($result['debug'], $result['rawHits']);

			return [
				'hits' => $result['hits'],
				'total' => $result['total'],
				'facets' => $result['facets'],
				'minPrice' => $result['minPrice'],
				'maxPrice' => $result['maxPrice'],
				'query' => $query,
			];
		} catch (Throwable $e) {
			Log::error('Custom product search failed: ' . $e->getMessage(), [
				'query' => $query,
			]);
			return $empty;
		}
	}

	/**
	 * @param list<string> $sort
	 * @param list<string> $meiliSort
	 * @return array{hits: list<object>, total: int, facets: list<array<string, mixed>>, minPrice: float, maxPrice: float, debug?: array<string, mixed>}
	 */
	private function executeProductSearch($index, string $query, string $searchFilters, array $sort, array $meiliSort, int $page, int $perPage): array
	{
		$useExactFirst = $this->isMultiWordQuery($query);
		// PHP ranking paths need resolvable attrs (price fallback when same_variation_* missing).
		$phpSort = $meiliSort !== [] ? $meiliSort : $sort;

		if ($useExactFirst) {
			return $this->searchExactFirstThenLast($index, $query, $searchFilters, $phpSort, $page, $perPage);
		}
		if ($sort === []) {
			return $this->searchPreferInStock($index, $query, $searchFilters, $sort, $page, $perPage);
		}
		// Explicit PLP sorts (price / name / rating): always PHP so OOS stays after in-stock.
		// Native Meili sort alone would interleave Notify Me by price.
		return $this->searchSortedInPhp($index, $query, $searchFilters, $phpSort !== [] ? $phpSort : $sort, $page, $perPage);
	}

	/**
	 * Remove tokens already handled by filters so matchingStrategy=all can succeed.
	 *
	 * @param array<string, mixed> $filters
	 */
	/**
	 * When Special / badges filter is active, do not require "new" in product text.
	 *
	 * @param array<string, mixed> $filters
	 */
	private function simplifyQueryForBadgeIntent(string $query, array $filters): string
	{
		if (empty($filters['special']) || !is_array($filters['special'])) {
			return trim($query);
		}
		$q = trim($query);
		if ($q === '') {
			return $q;
		}
		if (preg_match('/\b(?:new\s+arrivals?|latest\s+arrivals?|just\s+in)\b/iu', $q)) {
			$stripped = trim((string) preg_replace('/\b(?:new\s+arrivals?|latest\s+arrivals?|just\s+in)\s*/iu', ' ', $q));
			$stripped = trim(preg_replace('/\s+/u', ' ', $stripped) ?? $stripped);
			if ($stripped === '' || $this->isBadgeOnlyQueryText($stripped)) {
				return '';
			}
			return $stripped;
		}
		if ($this->isBadgeOnlyQueryText($q)) {
			return '';
		}
		return $q;
	}

	private function isBadgeOnlyQueryText(string $query): bool
	{
		$q = strtolower(trim(preg_replace('/\s+/u', ' ', $query) ?? $query));
		if ($q === '') {
			return false;
		}
		if (!preg_match('/\b(?:new|arrival|arrivals|latest|newest|just\s+in|na)\b/u', $q)) {
			return false;
		}
		$remainder = trim((string) preg_replace(
			'/\b(?:new|arrival|arrivals|latest|newest|just|in|na)\b/u',
			' ',
			$q
		));
		$remainder = trim((string) preg_replace(
			'/\b(?:perfume|perfumes|fragrance|fragrances|cologne|colognes)\b/u',
			' ',
			$remainder
		));
		return $remainder === '';
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function simplifyQueryForAppliedFilters(string $query, array $filters): string
	{
		$q = trim($query);
		if ($q === '') {
			return $q;
		}
		$q = preg_replace('/\$\s*[\d,]+(?:\.\d+)?\s*[kmb]?/iu', ' ', $q) ?? $q;
		$q = preg_replace('/\b\d+(?:\.\d+)?\s*[kmb]\b/iu', ' ', $q) ?? $q;
		if (
			(isset($filters['maxprice']) && $filters['maxprice'] !== '' && $filters['maxprice'] !== null)
			|| (isset($filters['minprice']) && $filters['minprice'] !== '' && $filters['minprice'] !== null)
		) {
			$q = preg_replace('/\b(?:under|below|less\s+than|over|above|at\s+least|upto|up\s+to|than)\b/iu', ' ', $q) ?? $q;
		}
		if ($this->activeGenderFilter !== []) {
			$q = preg_replace(
				'/\b(?:men|man|women|woman|unisex|male|female|guys|ladies|him|her|hers|his|wife|wives|husband|husbands|girlfriend|boyfriend|mother|mom|mum|sister|daughter|father|dad|brother|son|spouse|partner|fiance|fiancée|bride|groom)\b/iu',
				' ',
				$q
			) ?? $q;
		}
		if (!empty($filters['fragrance_occasion'])) {
			$q = preg_replace(
				'/\b(?:evening|nightlife|nighttime|daytime|day\s*wear|night\s*wear)\b/iu',
				' ',
				$q
			) ?? $q;
		}
		$q = trim(preg_replace('/\s+/u', ' ', $q) ?? $q);
		return $q !== '' ? $q : trim($query);
	}

	private function isLikelyNaturalLanguageQuery(string $query): bool
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		return is_array($tokens) && count($tokens) >= 5;
	}

	/** Keep the longest product-like tokens from a long NL leftover phrase. */
	private function preferContentTokens(string $query): string
	{
		$tokens = preg_split('/\s+/u', strtolower(trim($query)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		$kept = [];
		foreach ($tokens as $t) {
			$t = preg_replace('/[^a-z0-9]+/i', '', $t) ?? '';
			if ($t === '' || is_numeric($t) || mb_strlen($t) < 4) {
				continue;
			}
			$kept[] = $t;
		}
		if ($kept === []) {
			return trim($query);
		}
		usort($kept, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
		return implode(' ', array_slice(array_values(array_unique($kept)), 0, 2));
	}

	/**
	 * Short single-token typos like roaj→roja: try adjacent letter swaps until Meili has hits.
	 */
	private function findAdjacentTranspositionQuery($index, string $query, string $searchFilters): ?string
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || $tokens === [] || count($tokens) > 3) {
			return null;
		}

		foreach ($tokens as $ti => $tok) {
			$lower = strtolower($tok);
			if (!preg_match('/^[a-z]{4,10}$/u', $lower)) {
				continue;
			}
			$chars = preg_split('//u', $lower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
			for ($i = 0, $n = count($chars); $i < $n - 1; $i++) {
				$swap = $chars;
				$tmp = $swap[$i];
				$swap[$i] = $swap[$i + 1];
				$swap[$i + 1] = $tmp;
				$candTok = implode('', $swap);
				if ($candTok === $lower) {
					continue;
				}
				$trial = $tokens;
				$trial[$ti] = $candTok;
				$candidate = implode(' ', $trial);
				if ($this->probeQueryHasHits($index, $candidate, $searchFilters)) {
					return $candidate;
				}
			}
		}
		return null;
	}

	/**
	 * Resolve a catalog brand label from a short query (used for typo brand matching).
	 * Searches brand/name and returns the dominant brand when clearly unique.
	 */
	public function resolveBrandLabelFromQuery(string $query): ?string
	{
		$query = trim(preg_replace('/\s+/u', ' ', $query) ?? $query);
		if ($query === '' || mb_strlen($query) < 3) {
			return null;
		}
		try {
			$index = $this->client->index($this->indexName());
			$result = $this->runIndexSearch(
				$index,
				$query,
				$this->productTypeClause(),
				[],
				1,
				8,
				'last',
				false,
				0,
				['brand', 'name'],
				true
			);
			$counts = [];
			$needle = strtolower(preg_replace('/[^a-z0-9]+/u', '', $query) ?? '');
			foreach ((array) ($result['rawHits'] ?? []) as $hit) {
				if (!is_array($hit)) {
					continue;
				}
				$brand = trim((string) ($hit['brand'] ?? ''));
				if ($brand === '') {
					continue;
				}
				$collapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($brand)) ?? '';
				if ($needle === '' || $collapsed === '') {
					continue;
				}
				$matched = str_starts_with($collapsed, $needle) || str_contains($collapsed, $needle);
				if (!$matched) {
					$head = substr($collapsed, 0, max(mb_strlen($needle), 1));
					similar_text($needle, $head, $pct);
					$matched = $pct >= 70;
				}
				if ($matched) {
					$counts[$brand] = ($counts[$brand] ?? 0) + 1;
				}
			}
			if ($counts === []) {
				return null;
			}
			arsort($counts);
			$top = array_key_first($counts);
			$topCount = (int) ($counts[$top] ?? 0);
			// Require a clear winner among brand-matching hits.
			if ($topCount <= 0) {
				return null;
			}
			$second = 0;
			$i = 0;
			foreach ($counts as $c) {
				if ($i === 1) {
					$second = (int) $c;
					break;
				}
				$i++;
			}
			if ($second > 0 && $topCount <= $second) {
				return null;
			}
			return $top;
		} catch (Throwable $e) {
			Log::warning('resolveBrandLabelFromQuery failed: ' . $e->getMessage(), ['query' => $query]);
			return null;
		}
	}

	/**
	 * Drop query tokens until matchingStrategy=all finds hits — no static sight-word list.
	 * Prefers dropping shorter tokens first (function words are usually short).
	 */
	private function findRelaxedQuery($index, string $query, string $searchFilters): ?string
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || count($tokens) < 2) {
			return null;
		}

		// Price amounts are filters / noise for keyword matching.
		$tokens = array_values(array_filter($tokens, static function (string $t): bool {
			return !preg_match('/^\$?\d+(?:[.,]\d+)?[kmb]?$/i', $t);
		}));
		if (count($tokens) < 2) {
			$one = implode(' ', $tokens);
			return ($one !== '' && $this->probeQueryHasHits($index, $one, $searchFilters)) ? $one : null;
		}

		$probes = 0;
		$maxProbes = 48;
		$current = array_values($tokens);

		while (count($current) > 1 && $probes < $maxProbes) {
			$order = range(0, count($current) - 1);
			usort($order, static function (int $a, int $b) use ($current): int {
				$la = mb_strlen((string) $current[$a]);
				$lb = mb_strlen((string) $current[$b]);
				if ($la !== $lb) {
					return $la <=> $lb;
				}
				return $a <=> $b;
			});

			foreach ($order as $i) {
				$trial = $current;
				array_splice($trial, $i, 1);
				$candidate = implode(' ', $trial);
				$probes++;
				if ($this->probeQueryHasHits($index, $candidate, $searchFilters)) {
					return $candidate;
				}
				if ($probes >= $maxProbes) {
					break 2;
				}
			}

			// No single drop unlocked hits — remove shortest and keep relaxing.
			$shortest = $order[0] ?? 0;
			array_splice($current, $shortest, 1);
			$candidate = implode(' ', $current);
			$probes++;
			if ($candidate !== '' && $this->probeQueryHasHits($index, $candidate, $searchFilters)) {
				return $candidate;
			}
		}

		// Last resort: longest content tokens only (e.g. "perfume", "perfume evening").
		$byLen = $tokens;
		usort($byLen, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
		for ($n = 1; $n <= min(3, count($byLen)); $n++) {
			$candidate = implode(' ', array_slice($byLen, 0, $n));
			if ($candidate !== '' && $this->probeQueryHasHits($index, $candidate, $searchFilters)) {
				return $candidate;
			}
		}

		return null;
	}

	private function probeQueryHasHits($index, string $query, string $searchFilters): bool
	{
		$query = trim($query);
		if ($query === '') {
			return false;
		}
		try {
			$probe = $this->runIndexSearch(
				$index,
				$query,
				$searchFilters,
				[],
				1,
				1,
				'all',
				false,
				0,
				$this->structuredSearchAttributes(),
				false
			);
			return ((int) ($probe['total'] ?? 0)) > 0 || (($probe['rawHits'] ?? []) !== []);
		} catch (Throwable $e) {
			return false;
		}
	}

	/**
	 * As-you-type popup: same ranking idea as PLP, but a small Meili window so it feels instant.
	 *
	 * @param array<string, mixed> $filters
	 * @return array{hits: array, total: int, facets: array, minPrice: float, maxPrice: float, query: string}
	 */
	public function autocomplete(string $query, array $filters = [], int $perPage = 15): array
	{
		$perPage = max(1, min(24, $perPage));
		$this->popupFastMode = true;
		try {
			return $this->search($query, $filters, 1, $perPage, false);
		} finally {
			$this->popupFastMode = false;
		}
	}

	/**
	 * @return array<string, mixed>
	 */
	private function autocompleteBudgetFilters(string $query): array
	{
		$filters = [];
		if (preg_match(
			'/\b(?:under|below|less\s+than|upto|up\s+to)\s*\$?\s*([\d,]+(?:\.\d+)?)\s*([kmb])?\b/i',
			$query,
			$m
		)) {
			$n = (float) str_replace(',', '', $m[1]);
			$s = strtolower($m[2] ?? '');
			if ($s === 'k') {
				$n *= 1000;
			} elseif ($s === 'm') {
				$n *= 1000000;
			}
			if ($n > 0) {
				$filters['ochangeprice'] = '1';
				$filters['maxprice'] = (string) $n;
			}
		}
		return $filters;
	}

	/**
	 * True when filters include at least one facet/price/toggle constraint (not just sort/page).
	 *
	 * @param array<string, mixed> $filters
	 */
	private function filtersHaveConstraint(array $filters): bool
	{
		$ignore = ['sortby', 'page', 'ominprice', 'omaxprice'];
		foreach ($filters as $key => $val) {
			if (in_array((string) $key, $ignore, true)) {
				continue;
			}
			if ($val === null || $val === '' || $val === [] || $val === false || $val === '0' || $val === 0) {
				continue;
			}
			if (is_bool($val) && $val === true) {
				return true;
			}
			if (is_array($val) && array_filter($val, static fn ($v) => $v !== null && $v !== '') !== []) {
				return true;
			}
			if (is_scalar($val) && trim((string) $val) !== '') {
				return true;
			}
		}
		return false;
	}

	/** @return array<string, mixed>|null */
	public function getLastSearchDebug(): ?array
	{
		return $this->lastSearchDebug;
	}

	public function normalizeQuery(string $query): string
	{
		$query = str_replace(['andd', 'backslash'], ['&', '/'], $query);
		$query = str_replace('-', ' ', $query);
		return trim($query);
	}

	/**
	 * Stable identity for NLP cache/session keys (plural fold only — keep shopper wording).
	 */
	public function normalizeQueryIdentity(string $query): string
	{
		$query = $this->normalizeQuery($query);
		return $this->foldEnglishPluralsInQuery($query);
	}

	public function prepareSearchQuery(string $query): string
	{
		$query = $this->normalizeQueryIdentity($query);
		// "I want perfume" must search as "perfume" — requiring "I"/"want" collapses to ~10 hits.
		return $this->stripConversationalQueryTokens($query);
	}

	/**
	 * Drop shopper chatter so matchingStrategy=all can match catalog text.
	 * Only strips when at least one content token remains.
	 */
	private function stripConversationalQueryTokens(string $query): string
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		if ($tokens === [] || count($tokens) < 2) {
			return trim($query);
		}
		$stop = [
			'i', 'im', 'ive', 'id', 'me', 'my', 'we', 'you', 'your', 'a', 'an', 'the',
			'want', 'wanna', 'need', 'looking', 'look', 'find', 'show', 'get', 'buy', 'please',
			'search', 'searching', 'for', 'to', 'of', 'and', 'or', 'with', 'from', 'that', 'this',
			'something', 'some', 'any', 'really', 'just', 'like',
		];
		$kept = [];
		foreach ($tokens as $t) {
			$lower = strtolower(preg_replace('/[^a-z0-9]+/iu', '', $t) ?? '');
			if ($lower === '' || in_array($lower, $stop, true)) {
				continue;
			}
			$kept[] = $t;
		}
		return $kept !== [] ? trim(implode(' ', $kept)) : trim($query);
	}

	/**
	 * Light morphology only — not a product-word list.
	 * "long lasting perfumes" → "long lasting perfume"; "gift sets" → "gift set".
	 * Protects -is/-us/-ss endings (paris, focus, glass) and very short tokens.
	 */
	private function foldEnglishPluralsInQuery(string $query): string
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || $tokens === []) {
			return $query;
		}
		$out = [];
		foreach ($tokens as $token) {
			$out[] = $this->foldEnglishPluralToken($token);
		}
		return trim(implode(' ', $out));
	}

	private function foldEnglishPluralToken(string $token): string
	{
		if ($token === '' || !preg_match('/^[A-Za-z]+$/u', $token)) {
			return $token;
		}
		// Brand acronyms / ALL CAPS (ALPS, DIOR) are not English plurals — never strip "S".
		if (strlen($token) > 1 && $token === strtoupper($token)) {
			return $token;
		}
		$lower = strtolower($token);
		$len = strlen($lower);
		if ($len < 4) {
			return $token;
		}
		// Proper-ish endings that look plural but are not (paris, focus, glass).
		if (preg_match('/(?:is|us|ss)$/u', $lower)) {
			return $token;
		}
		$folded = $lower;
		if ($len >= 5 && str_ends_with($folded, 'ies')) {
			$folded = substr($folded, 0, -3) . 'y';
		} elseif ($len >= 5 && preg_match('/(?:ches|shes|xes|zes|sses)$/u', $folded)) {
			$folded = substr($folded, 0, -2);
		} elseif (str_ends_with($folded, 's')) {
			// Bare trailing "s": keep short brand-like tokens (alps). Only fold clear
			// 4-letter product plurals (sets/kits/oils) or longer words (perfumes).
			if ($len < 5 && !in_array($lower, ['sets', 'kits', 'oils', 'bags', 'caps', 'gels', 'jars'], true)) {
				return $token;
			}
			$folded = substr($folded, 0, -1);
		} else {
			return $token;
		}
		if ($folded === '' || $folded === $lower) {
			return $token;
		}
		// Preserve original casing style when the shopper typed Title Case / ALL CAPS.
		if ($token === strtoupper($token) && strlen($token) > 1) {
			return strtoupper($folded);
		}
		if ($token !== $lower && isset($token[0]) && $token[0] === strtoupper($token[0])) {
			return ucfirst($folded);
		}
		return $folded;
	}

	/**
	 * SearchSpring-like Best Match for multi-word queries.
	 * SS default (no sort.*): textual relevance + soft popularity tie-break; no query boost required.
	 * Keep "1 million" / "one million" preference without destroying Meili relevance order.
	 *
	 * @param list<string> $sort
	 * @return array{hits: list<object>, total: int, facets: list<array<string, mixed>>, minPrice: float, maxPrice: float, debug?: array<string, mixed>}
	 */
	private function searchExactFirstThenLast($index, string $query, string $searchFilters, array $sort, int $page, int $perPage): array
	{
		// Large concentration queries (e.g. "Eau de parfum") can exceed 2k hits; keep
		// enough for PHP Best Match / load-more without capping the reported total at 500.
		// Popup only needs the first page of suggestions — keep Meili cheap.
		$fetchCap = $this->popupFastMode ? 48 : 2500;
		$structuredAttrs = $this->structuredSearchAttributes();
		$keywordOnlyAttrs = $this->keywordOnlySearchAttributes();
		$nameAttrs = $this->nameFirstSearchAttributes();
		$queries = $this->equivalentQueries($query);
		$subject = $this->broadTokenQuery($query);

		// Primary Best Match: full query on name/brand/sku/category; all tokens required.
		// Do not pass PLP sort into Meili here — missing sortable fields would drop docs and change totals.
		$primary = $this->runIndexSearch(
			$index,
			$query,
			$searchFilters,
			[],
			1,
			$fetchCap,
			'all',
			true,
			0,
			$structuredAttrs,
			true
		);

		$byUid = [];
		$meiliOrd = 0;
		foreach ($primary['rawHits'] as $hit) {
			$uid = (string) ($hit['uid'] ?? $hit['id'] ?? '');
			if ($uid === '' || isset($byUid[$uid])) {
				continue;
			}
			$hit['_meiliOrd'] = $meiliOrd++;
			$byUid[$uid] = $hit;
		}

		// Include "one million" ↔ "1 million" name matches Meili may not surface from digit query alone.
		// Skip on popup — secondary Meili round-trips dominate autocomplete latency.
		if (!$this->popupFastMode) {
			foreach ($queries as $qVariant) {
				if ($qVariant === $query) {
					continue;
				}
				$alt = $this->runIndexSearch($index, $qVariant, $searchFilters, [], 1, min(200, $fetchCap), 'all', false, 0, $nameAttrs, true);
				foreach ($alt['rawHits'] as $hit) {
					$uid = (string) ($hit['uid'] ?? $hit['id'] ?? '');
					if ($uid === '' || isset($byUid[$uid])) {
						continue;
					}
					$hit['_meiliOrd'] = $meiliOrd++;
					$byUid[$uid] = $hit;
				}
			}

			// Curated keywords only when ALL tokens match (sampler kits; not "a million dollars").
			if ($keywordOnlyAttrs !== []) {
				foreach ($queries as $qVariant) {
					$kw = $this->runIndexSearch($index, $qVariant, $searchFilters, [], 1, min(200, $fetchCap), 'all', false, 0, $keywordOnlyAttrs, true);
					foreach ($kw['rawHits'] as $hit) {
						$uid = (string) ($hit['uid'] ?? $hit['id'] ?? '');
						if ($uid === '' || isset($byUid[$uid])) {
							continue;
						}
						$hit['_meiliOrd'] = 100000 + $meiliOrd++;
						$byUid[$uid] = $hit;
					}
				}
			}
		}

		$allHits = $this->filterHitsByGender(array_values($byUid));
		$starFiltered = $this->activeStarBuckets !== [];
		if ($starFiltered) {
			$allHits = $this->filterHitsToStarBuckets($allHits, $this->activeStarBuckets);
		}
		$similarFiltered = $this->similarExcludeUid !== '' || $this->similarExcludeTokens !== [];
		if ($similarFiltered) {
			$allHits = $this->filterHitsExcludeSimilarLine($allHits);
		}
		// Date-night / dinner-date: keep products whose Occasion & mood copy has the phrase —
		// not titles that only contain "Night" / "Date".
		$sceneFiltered = false;
		if ($this->searchDescription && $this->isSceneMoodPhraseQuery($query)) {
			$allHits = $this->filterHitsBySceneMoodPhrase($allHits, $query);
			$sceneFiltered = true;
		}
		if ($sort !== []) {
			$ranked = $this->sortCollectedHits($allHits, $sort);
		} else {
			$ranked = $this->rankHitsBestMatch($allHits, $query);
		}
		// Meili total can exceed the PHP fetch cap. Use index total when we did not
		// post-filter gender in PHP only (gender already in Meili filter is accurate).
		$meiliTotal = (int) ($primary['total'] ?? 0);
		$phpOnlyGender = $this->activeGenderFilter !== [] && !$this->indexSupportsGenderFilter();
		$total = ($phpOnlyGender || $sceneFiltered || $starFiltered || $similarFiltered)
			? count($ranked)
			: max($meiliTotal, count($ranked));
		$globalOffset = ($page - 1) * $perPage;
		if ($globalOffset < count($ranked)) {
			$pageHits = array_slice($ranked, $globalOffset, $perPage);
		} elseif (!$phpOnlyGender && !$sceneFiltered && !$starFiltered && !$similarFiltered && $meiliTotal > count($ranked)) {
			// Past the PHP-ranked window — continue with Meili order for deeper pages.
			$deep = $this->runIndexSearch(
				$index,
				$query,
				$searchFilters,
				[],
				$page,
				$perPage,
				'all',
				false,
				0,
				$structuredAttrs,
				false
			);
			$pageHits = $deep['rawHits'] ?? [];
			if ($this->activeStarBuckets !== []) {
				$pageHits = $this->filterHitsToStarBuckets($pageHits, $this->activeStarBuckets);
			}
			if ($similarFiltered) {
				$pageHits = $this->filterHitsExcludeSimilarLine($pageHits);
			}
		} else {
			$pageHits = [];
		}

		// Facets must come from the same document set as the ranked hits.
		// A full-searchable facet probe (incl. description) was surfacing brands that
		// only match marketing copy — clicking them with title/keyword search → 0 hits
		// (e.g. "5th Ave Nyc" on "long lasting perfume").
		$mergedFacets = $this->buildFacetsFromHits($allHits);
		if ($mergedFacets === []) {
			$mergedFacets = $primary['facets'] ?? [];
		}
		$mergedMinPrice = $primary['minPrice'];
		$mergedMaxPrice = $primary['maxPrice'];
		foreach ($allHits as $h) {
			$p = (float) ($h['price'] ?? 0);
			if ($p > 0) {
				if ($p < $mergedMinPrice || $mergedMinPrice <= 0) $mergedMinPrice = $p;
				if ($p > $mergedMaxPrice) $mergedMaxPrice = $p;
			}
		}

		return [
			'hits' => $this->mapHits($pageHits),
			'total' => $total,
			'facets' => $mergedFacets,
			'minPrice' => $mergedMinPrice,
			'maxPrice' => $mergedMaxPrice,
			'debug' => [
				'ranking_note' => $sort === []
					? 'SS Best Match style: typed phrase > alt phrase > subject; tighter titles; soft stock'
					: 'Same multi-word hit set as Best Match, then explicit PLP sort',
				'mode' => $sort === [] ? 'searchspring_best_match' : 'multiword_sorted',
				'q' => $query,
				'subject' => $subject,
				'queries' => $queries,
				'sort' => $sort,
				'matchingStrategy' => 'all',
				'attributesToSearchOn' => $structuredAttrs,
				'filter' => $searchFilters,
				'total' => $total,
				'primary_total' => $primary['total'],
			],
		];
	}

	/** Last query token for the broad tier (e.g. "1 million" → "million") — mirrors SS query.subject. */
	private function broadTokenQuery(string $query): string
	{
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || count($tokens) < 2) {
			return $query;
		}
		return (string) end($tokens);
	}

	/**
	 * "1 million" and "one million" are the same fragrance line.
	 *
	 * @return list<string>
	 */
	private function equivalentQueries(string $query): array
	{
		$query = trim($query);
		$queries = [$query];
		$tokens = preg_split('/\s+/u', strtolower($query), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || $tokens === [] || !isset($tokens[0])) {
			return $queries;
		}
		if ($tokens[0] === '1') {
			$tokens[0] = 'one';
			$queries[] = implode(' ', $tokens);
		} elseif ($tokens[0] === 'one') {
			$tokens[0] = '1';
			$queries[] = implode(' ', $tokens);
		}

		return array_values(array_unique($queries));
	}

	/**
	 * Exact title matches should not scan search_keywords (that buries "1 Million" under sampler kits).
	 *
	 * @return list<string>
	 */
	private function nameFirstSearchAttributes(): array
	{
		$preferred = ['name', 'brand'];
		$configured = $this->keywordSearchAttributes();
		$on = array_values(array_intersect($preferred, $configured));
		return $on !== [] ? $on : ['name'];
	}

	/**
	 * Title-like fields SearchSpring typically matches — not description marketing prose
	 * (except date-night phrase intent, which lives in Occasion & mood copy).
	 *
	 * @return list<string>
	 */
	private function structuredSearchAttributes(): array
	{
		$preferred = ['name', 'brand', 'sku', 'category', 'search_keywords'];
		if ($this->searchDescription) {
			$preferred[] = 'description';
		}
		$configured = $this->keywordSearchAttributes();
		$on = array_values(array_intersect($preferred, $configured));
		return $on !== [] ? $on : ['name'];
	}

	/**
	 * Curated keywords only. matchingStrategy=all so "1 million" is required as a pair
	 * (excludes "feel like a million dollars").
	 *
	 * @return list<string>
	 */
	private function keywordOnlySearchAttributes(): array
	{
		$configured = $this->keywordSearchAttributes();
		$on = [];
		foreach (['search_keywords'] as $attr) {
			if (in_array($attr, $configured, true)) {
				$on[] = $attr;
			}
		}
		return $this->clampSearchableAttributes($on);
	}

	/**
	 * Approximate SearchSpring Best Match when Meili scores are nearly tied.
	 * Buckets mirror SS: core "1 Million" titles → longer variants → One Million →
	 * subject (Lady Million / Million Gold) → OOS / keywords.
	 *
	 * @param list<array<string, mixed>> $hits
	 * @return list<array<string, mixed>>
	 */
	private function rankHitsBestMatch(array $hits, string $query): array
	{
		$rankSource = trim($query) !== '' ? $query : $this->rankingQuery;
		$tokens = preg_split('/\s+/u', strtolower(trim($rankSource)), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens)) {
			$tokens = [];
		}
		// Drop relationship / budget filler so "perfume for my sister" ranks on perfume + gender.
		$tokens = $this->rankingContentTokens($tokens);

		$scored = [];
		foreach ($hits as $i => $hit) {
			// Same rule as productboxsearch: Notify Me when inventory_count <= 0.
			$stock = max((int) ($hit['inventory_count'] ?? 0), (int) ($hit['website_stock'] ?? 0));
			$inStock = $stock > 0 ? 1 : 0;
			$genderRank = $this->primaryGenderRank((string) ($hit['gender'] ?? ''));
			$sceneOcc = $this->sceneOccasionRank($hit);
			$catScore = $tokens !== [] ? $this->categoryRelevanceScore($hit, $tokens) : 0;
			if ($tokens === []) {
				$bucket = $inStock === 1 ? 1 : 8;
				$extraWords = 0;
				$fillerPenalty = 0;
			} else {
				[$form, $extraWords, $fillerPenalty] = $this->bestMatchSignals($hit, $tokens);
				$bucket = $this->bestMatchBucket($form, $inStock, $fillerPenalty);
			}
			$scored[] = [
				'bucket' => $bucket,
				'extra' => $extraWords,
				'filler' => $fillerPenalty,
				'cat' => $catScore,
				'gender' => $genderRank,
				'sceneOcc' => $sceneOcc,
				'meili' => (float) ($hit['_rankingScore'] ?? 0),
				'stock' => $stock,
				'ord' => (int) ($hit['_meiliOrd'] ?? $i),
				'hit' => $hit,
			];
		}
		usort($scored, static function (array $a, array $b): int {
			// Gender intent (Women→W+U / Men→M+U): all primary gender before Unisex,
			// even if Unisex is in stock and primary is Notify Me.
			if ($a['gender'] !== $b['gender']) {
				return $a['gender'] <=> $b['gender'];
			}
			// Date-night intent: occasion facet containing "date" before mood-copy-only hits.
			if ($a['sceneOcc'] !== $b['sceneOcc']) {
				return $a['sceneOcc'] <=> $b['sceneOcc'];
			}
			// Within the same gender band: never show Notify Me above in-stock.
			$aIn = ($a['stock'] > 0) ? 1 : 0;
			$bIn = ($b['stock'] > 0) ? 1 : 0;
			if ($aIn !== $bIn) {
				return $bIn <=> $aIn;
			}
			if ($a['bucket'] !== $b['bucket']) {
				return $a['bucket'] <=> $b['bucket'];
			}
			// candles before diffuser / leaf match before sibling under same parent.
			if ($a['cat'] !== $b['cat']) {
				return $b['cat'] <=> $a['cat'];
			}
			if ($a['filler'] !== $b['filler']) {
				return $a['filler'] <=> $b['filler'];
			}
			if ($a['extra'] !== $b['extra']) {
				return $a['extra'] <=> $b['extra'];
			}
			if (abs($a['meili'] - $b['meili']) > 0.0001) {
				return $b['meili'] <=> $a['meili'];
			}
			if ($a['stock'] !== $b['stock']) {
				return $b['stock'] <=> $a['stock'];
			}
			return $a['ord'] <=> $b['ord'];
		});

		$out = [];
		foreach ($scored as $row) {
			$hit = $row['hit'];
			unset($hit['_meiliOrd'], $hit['_rankingScore']);
			$out[] = $hit;
		}
		return $out;
	}

	/**
	 * Keep product-class tokens for ranking; drop relationship / budget chatter.
	 *
	 * @param list<string> $tokens
	 * @return list<string>
	 */
	private function rankingContentTokens(array $tokens): array
	{
		$stop = [
			'for', 'my', 'me', 'a', 'an', 'the', 'and', 'or', 'with', 'to', 'of', 'in', 'on',
			'please', 'gift', 'gifts', 'present', 'presents',
			'wife', 'wives', 'husband', 'husbands', 'girlfriend', 'boyfriend',
			'mother', 'mom', 'mum', 'sister', 'daughter', 'father', 'dad', 'brother', 'son',
			'her', 'hers', 'him', 'his', 'under', 'below', 'over', 'above', 'than', 'less',
		];
		$out = [];
		foreach ($tokens as $t) {
			$t = strtolower(trim($t));
			if ($t === '' || is_numeric($t) || in_array($t, $stop, true)) {
				continue;
			}
			if (preg_match('/^\$?\d+(?:\.\d+)?[kmb]?$/i', $t)) {
				continue;
			}
			$out[] = $t;
		}
		return array_values($out);
	}

	/**
	 * When NLP expands Women→W+U (or Men→M+U), prefer the primary gender code.
	 * Lower = earlier.
	 */
	private function primaryGenderRank(string $genderCode): int
	{
		$codes = $this->activeGenderFilter;
		if ($codes === []) {
			return 0;
		}
		$hasW = in_array('W', $codes, true);
		$hasM = in_array('M', $codes, true);
		$hasU = in_array('U', $codes, true);
		$g = strtoupper(trim($genderCode));
		if ($hasW && $hasU && !$hasM) {
			if ($g === 'W') {
				return 0;
			}
			if ($g === 'U') {
				return 1;
			}
			return 2;
		}
		if ($hasM && $hasU && !$hasW) {
			if ($g === 'M') {
				return 0;
			}
			if ($g === 'U') {
				return 1;
			}
			return 2;
		}
		return 0;
	}

	/**
	 * Prefer category leaf matching the query (Candle) over sibling leaves (Diffuser).
	 * Higher = better.
	 *
	 * @param list<string> $tokens
	 */
	private function categoryRelevanceScore(array $hit, array $tokens): int
	{
		$catRaw = $hit['category'] ?? '';
		$cats = is_array($catRaw) ? $catRaw : [(string) $catRaw];
		$name = strtolower((string) ($hit['name'] ?? ''));
		$best = 0;
		foreach ($cats as $cat) {
			$cat = strtolower(trim((string) $cat));
			if ($cat === '') {
				continue;
			}
			$segments = preg_split('/\s*:\s*/u', $cat) ?: [$cat];
			$parent = strtolower(trim((string) ($segments[0] ?? '')));
			$leaf = strtolower(trim((string) (end($segments) ?: '')));
			$score = 0;
			foreach ($tokens as $token) {
				$stem = $this->lightStemToken($token);
				if ($stem === '' || mb_strlen($stem) < 3) {
					continue;
				}
				$pat = '/\b' . preg_quote($stem, '/') . 's?\b/u';
				if ($leaf !== '' && preg_match($pat, $leaf)) {
					$score += 40;
				} elseif ($parent !== '' && preg_match($pat, $parent)) {
					$score += 8;
					// Parent matched but leaf is a different product class (Candles : Diffuser).
					if ($leaf !== '' && $leaf !== $parent && !preg_match($pat, $leaf)) {
						$score -= 25;
					}
				}
				if ($name !== '' && preg_match($pat, $name)) {
					$score += 12;
				}
			}
			if ($score > $best) {
				$best = $score;
			}
		}
		return $best;
	}

	private function lightStemToken(string $token): string
	{
		$t = strtolower(trim($token));
		if ($t === '') {
			return '';
		}
		if (str_ends_with($t, 'ies') && mb_strlen($t) > 4) {
			return substr($t, 0, -3) . 'y';
		}
		if (str_ends_with($t, 'ses') || str_ends_with($t, 'xes') || str_ends_with($t, 'zes')) {
			return substr($t, 0, -2);
		}
		if (str_ends_with($t, 's') && !str_ends_with($t, 'ss') && mb_strlen($t) > 3) {
			return substr($t, 0, -1);
		}
		return $t;
	}

	/** Lower bucket = earlier in Best Match. */
	private function bestMatchBucket(int $form, int $inStock, int $fillerPenalty): int
	{
		if ($form === 1 && $inStock === 1 && $fillerPenalty <= 1) {
			return 1; // core typed phrase, in stock (1 Million, Royal, Elixir, Parfum)
		}
		if ($form === 1 && $inStock === 1) {
			return 2; // typed phrase with "for …" / longer suffix
		}
		if ($form === 2 && $inStock === 1) {
			return 3; // One Million ↔ 1 Million alt wording
		}
		if ($form === 3 && $inStock === 1) {
			return 4; // subject token in name (Lady Million, Million Gold)
		}
		if ($form <= 3 && $inStock === 1) {
			return 5;
		}
		if ($form === 1) {
			return 6; // OOS typed phrase
		}
		if ($form <= 3) {
			return 7; // OOS alt/subject
		}
		return 8; // keywords / weak
	}

	/**
	 * @param array<string, mixed> $hit
	 * @param list<string> $tokens
	 * @return array{0:int,1:int,2:int} form, extraWords, fillerPenalty
	 */
	private function bestMatchSignals(array $hit, array $tokens): array
	{
		$name = strtolower((string) ($hit['name'] ?? ''));
		$brand = strtolower((string) ($hit['brand'] ?? ''));
		$kw = strtolower((string) ($hit['search_keywords'] ?? $hit['searchKeywords'] ?? ''));
		$catRaw = $hit['category'] ?? '';
		$cat = strtolower(is_array($catRaw) ? implode(' ', $catRaw) : (string) $catRaw);
		$phrase = '/\b' . implode('\s+', array_map(static fn ($t) => preg_quote($t, '/'), $tokens)) . 's?\b/u';
		$altTokens = null;
		$altPhrase = null;
		if (isset($tokens[0]) && ($tokens[0] === '1' || $tokens[0] === 'one') && count($tokens) >= 2) {
			$altTokens = $tokens;
			$altTokens[0] = $tokens[0] === '1' ? 'one' : '1';
			$altPhrase = '/\b' . implode('\s+', array_map(static fn ($t) => preg_quote($t, '/'), $altTokens)) . 's?\b/u';
		}

		$form = 4;
		$matchedPhrase = null;
		$desc = strtolower((string) ($hit['description'] ?? ''));
		// Scene/mood (date night): phrase in Occasion & mood copy beats a bare "Night" title.
		if ($this->searchDescription && $desc !== '' && preg_match($phrase, $desc)) {
			$form = 2;
			$matchedPhrase = $tokens;
		} elseif ($this->searchDescription && $kw !== '' && preg_match($phrase, $kw)) {
			$form = 2;
			$matchedPhrase = $tokens;
		} elseif ($name !== '' && preg_match($phrase, $name)) {
			$form = 1;
			$matchedPhrase = $tokens;
		} elseif ($altPhrase && $name !== '' && preg_match($altPhrase, $name)) {
			$form = 2;
			$matchedPhrase = $altTokens;
		} elseif ($cat !== '' && preg_match($phrase, $cat)) {
			// Category phrase match (e.g. "candles") beats keyword-only / weak name hits.
			$form = 1;
			$matchedPhrase = $tokens;
		} else {
			$last = (string) end($tokens);
			$lastStem = $this->lightStemToken($last);
			$lastPat = $lastStem !== ''
				? '/\b' . preg_quote($lastStem, '/') . 's?\b/u'
				: '';
			// When scanning mood copy, do not promote titles that only contain the last token
			// ("Night") unless the full scene phrase also appears somewhere on the doc.
			$lastOnlyOk = !$this->searchDescription;
			if ($lastOnlyOk && $last !== '' && $name !== '' && preg_match('/\b' . preg_quote($last, '/') . '\b/u', $name)) {
				$form = 3;
			} elseif ($lastOnlyOk && $lastPat !== '' && $cat !== '' && preg_match($lastPat, $cat)) {
				$form = 3;
			} elseif ($kw !== '' && (preg_match($phrase, $kw) || ($altPhrase && preg_match($altPhrase, $kw)))) {
				$form = 4;
			} else {
				$form = 5;
			}
		}

		$extraWords = 99;
		$fillerPenalty = 0;
		if ($matchedPhrase !== null && $name !== '') {
			$rest = $name;
			if ($brand !== '') {
				$rest = trim(preg_replace('/\b' . preg_quote($brand, '/') . '\b/u', ' ', $rest) ?? $rest);
			}
			$rest = trim(preg_replace(
				'/\b' . implode('\s+', array_map(static fn ($t) => preg_quote($t, '/'), $matchedPhrase)) . '\b/u',
				' ',
				$rest
			) ?? $rest);
			$rest = trim(preg_replace('/\s+/u', ' ', $rest) ?? $rest);
			$extra = $rest === '' ? [] : (preg_split('/\s+/u', $rest, -1, PREG_SPLIT_NO_EMPTY) ?: []);
			$extraWords = count($extra);
			$joined = implode(' ', $extra);
			if (preg_match('/\b(tester|gift|set|sample|unsealed)\b/u', $joined)) {
				$fillerPenalty = 3;
			} elseif (preg_match('/\bfor\b/u', $joined)) {
				$fillerPenalty = 2;
			} elseif ($extraWords > 1) {
				$fillerPenalty = 1;
			}
		} elseif ($form === 3) {
			$extraWords = 50;
			$fillerPenalty = 1;
		}

		return [$form, $extraWords, $fillerPenalty];
	}

	/**
	 * Lower tier = better.
	 *
	 * @param array<string, mixed> $hit
	 * @param list<string> $tokens
	 */
	private function bestMatchTier(array $hit, array $tokens): int
	{
		return $this->bestMatchSignals($hit, $tokens)[0];
	}

	/**
	 * Higher score = more relevant. Prefer name phrase "1 million", then name "million", then keywords.
	 *
	 * @param list<array<string, mixed>> $hits
	 * @return list<array<string, mixed>>
	 */
	private function rankHitsByRelevance(array $hits, string $query): array
	{
		$tokens = preg_split('/\s+/u', strtolower(trim($query)), -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($tokens) || $tokens === []) {
			return $hits;
		}

		$scored = [];
		foreach ($hits as $i => $hit) {
			$scored[] = [
				'score' => $this->relevanceScore($hit, $tokens),
				'ord' => $i,
				'hit' => $hit,
			];
		}
		usort($scored, static function (array $a, array $b): int {
			if ($a['score'] !== $b['score']) {
				return $b['score'] <=> $a['score'];
			}
			return $a['ord'] <=> $b['ord'];
		});

		return array_column($scored, 'hit');
	}

	/**
	 * @param array<string, mixed> $hit
	 * @param list<string> $tokens lowercase query tokens
	 */
	private function relevanceScore(array $hit, array $tokens): int
	{
		$name = strtolower((string) ($hit['name'] ?? ''));
		$brand = strtolower((string) ($hit['brand'] ?? ''));
		$kw = strtolower((string) ($hit['search_keywords'] ?? $hit['searchKeywords'] ?? ''));
		$hayName = trim($name . ' ' . $brand);
		$last = (string) end($tokens);
		$score = 0;

		$phrasePattern = '/\b' . implode('\s+', array_map(static fn ($t) => preg_quote($t, '/'), $tokens)) . '\b/u';
		$altTokens = null;
		$altPattern = null;
		if (isset($tokens[0]) && ($tokens[0] === '1' || $tokens[0] === 'one') && count($tokens) >= 2) {
			$altTokens = $tokens;
			$altTokens[0] = $tokens[0] === '1' ? 'one' : '1';
			$altPattern = '/\b' . implode('\s+', array_map(static fn ($t) => preg_quote($t, '/'), $altTokens)) . '\b/u';
		}

		$hasDigitPhrase = $hayName !== '' && preg_match($phrasePattern, $hayName);
		$hasWordPhrase = $altPattern !== null && $hayName !== '' && preg_match($altPattern, $hayName);
		if ($hasDigitPhrase || $hasWordPhrase) {
			$score += 100000;
		} else {
			$allInName = true;
			foreach ($tokens as $token) {
				if ($token === '' || !preg_match('/\b' . preg_quote($token, '/') . '\b/u', $hayName)) {
					$allInName = false;
					break;
				}
			}
			$allAltInName = false;
			if ($altTokens !== null) {
				$allAltInName = true;
				foreach ($altTokens as $token) {
					if ($token === '' || !preg_match('/\b' . preg_quote($token, '/') . '\b/u', $hayName)) {
						$allAltInName = false;
						break;
					}
				}
			}
			if ($allInName || $allAltInName) {
				$score += 80000;
			} elseif ($last !== '' && preg_match('/\b' . preg_quote($last, '/') . '\b/u', $hayName)) {
				$score += 50000;
			}
		}

		if ($kw !== '' && preg_match($phrasePattern, $kw)) {
			$score += 20000;
		} elseif ($altPattern !== null && $kw !== '' && preg_match($altPattern, $kw)) {
			$score += 20000;
		} elseif ($last !== '' && $kw !== '' && preg_match('/\b' . preg_quote($last, '/') . '\b/u', $kw)) {
			$score += 10000;
		}

		return $score;
	}

	/**
	 * Pull broad (last-token) hits while skipping exact-tier products in PHP — uid is not filterable in Meilisearch.
	 *
	 * @param list<string> $sort
	 * @param list<string> $excludeUids
	 * @return list<array<string, mixed>>
	 */
	private function fetchBroadHitsExcludingUids($index, string $query, string $searchFilters, array $sort, array $excludeUids, int $skip, int $limit): array
	{
		if ($limit <= 0) {
			return [];
		}

		$exclude = array_fill_keys($excludeUids, true);
		$meiliOffset = 0;
		$skipped = 0;
		$collected = [];
		$batchSize = max($limit * 3, 50);

		while (count($collected) < $limit) {
			$batch = $this->runIndexSearch($index, $query, $searchFilters, $sort, 1, $batchSize, 'all', false, $meiliOffset);
			$hits = $batch['rawHits'];
			if ($hits === []) {
				break;
			}

			foreach ($hits as $hit) {
				$uid = (string) ($hit['uid'] ?? $hit['id'] ?? '');
				if ($uid !== '' && isset($exclude[$uid])) {
					continue;
				}
				if ($skipped < $skip) {
					$skipped++;
					continue;
				}
				$collected[] = $hit;
				if (count($collected) >= $limit) {
					break 2;
				}
			}

			$meiliOffset += count($hits);
			if (count($hits) < $batchSize) {
				break;
			}
		}

		return $collected;
	}

	/**
	 * @param list<string> $sort
	 * @param list<string>|null $attributesToSearchOn
	 * @return array{hits: list<object>, total: int, facets: list<array<string, mixed>>, minPrice: float, maxPrice: float, rawHits: list<array<string, mixed>>, debug?: array<string, mixed>}
	 */
	private function runIndexSearch($index, string $query, string $searchFilters, array $sort, int $page, int $perPage, string $matchingStrategy, bool $withFacets, int $offset = 0, ?array $attributesToSearchOn = null, bool $showRankingScore = false): array
	{
		$searchOn = $this->clampSearchableAttributes($attributesToSearchOn ?? $this->keywordSearchAttributes());
		$options = [
			'attributesToRetrieve' => ['*'],
			'matchingStrategy' => $matchingStrategy,
		];
		if ($searchOn !== []) {
			$options['attributesToSearchOn'] = $searchOn;
		}
		if ($showRankingScore) {
			$options['showRankingScore'] = true;
		}
		if ($offset > 0) {
			$options['offset'] = $offset;
			$options['limit'] = max(1, $perPage);
		} else {
			$options['page'] = max(1, $page);
			$options['hitsPerPage'] = max(1, $perPage);
		}
		if ($withFacets) {
			$options['facets'] = $this->catalog->broadSearchFacets();
		}
		if ($searchFilters !== '') {
			$options['filter'] = $searchFilters;
		}
		if ($sort !== []) {
			$options['sort'] = $sort;
		}

		$result = $index->search($query, $options);
		$raw = method_exists($result, 'getRaw') ? $result->getRaw() : [];
		$hits = method_exists($result, 'getHits') ? $result->getHits() : ($raw['hits'] ?? []);
		$distribution = method_exists($result, 'getFacetDistribution') ? ($result->getFacetDistribution() ?? []) : ($raw['facetDistribution'] ?? []);
		$stats = method_exists($result, 'getFacetStats') ? ($result->getFacetStats() ?? []) : ($raw['facetStats'] ?? []);
		$total = 0;
		if (method_exists($result, 'getTotalHits')) {
			$total = (int) $result->getTotalHits();
		}
		if ($total <= 0) {
			$total = (int) ($raw['totalHits'] ?? $raw['estimatedTotalHits'] ?? 0);
		}

		return [
			'hits' => $this->mapHits($hits),
			'total' => $total,
			'facets' => $this->mapFacets($distribution),
			'minPrice' => (float) ($stats['price']['min'] ?? 0),
			'maxPrice' => (float) ($stats['price']['max'] ?? 0),
			'rawHits' => is_array($hits) ? $hits : [],
			'debug' => [
				'meilisearch' => array_merge(['q' => $query, 'matchingStrategy' => $matchingStrategy], $options),
			],
		];
	}

	/**
	 * Default sort for single-word (and other non–Best Match) queries:
	 * keep Meili relevance, but hard-prefer in-stock over Notify Me / OOS.
	 *
	 * @param list<string> $sort
	 * @return array{hits: list<object>, total: int, facets: list<array<string, mixed>>, minPrice: float, maxPrice: float, debug?: array<string, mixed>}
	 */
	private function searchPreferInStock($index, string $query, string $searchFilters, array $sort, int $page, int $perPage): array
	{
		// Parity with Best Match / PHP sort — same window so totals & ranking stay consistent.
		// Popup only needs the first suggestion page.
		$fetchCap = $this->popupFastMode ? 48 : 2500;
		$primary = $this->runIndexSearch(
			$index,
			$query,
			$searchFilters,
			[],
			1,
			$fetchCap,
			'all',
			true,
			0,
			null,
			true
		);

		$rawHits = [];
		$meiliOrd = 0;
		foreach ($primary['rawHits'] as $hit) {
			if (!is_array($hit)) {
				continue;
			}
			$hit['_meiliOrd'] = $meiliOrd++;
			$rawHits[] = $hit;
		}

		$rawHits = $this->filterHitsByGender($rawHits);
		$starFiltered = $this->activeStarBuckets !== [];
		if ($starFiltered) {
			$rawHits = $this->filterHitsToStarBuckets($rawHits, $this->activeStarBuckets);
		}
		$similarFiltered = $this->similarExcludeUid !== '' || $this->similarExcludeTokens !== [];
		if ($similarFiltered) {
			$rawHits = $this->filterHitsExcludeSimilarLine($rawHits);
		}
		$sceneFiltered = false;
		if ($this->searchDescription && $this->isSceneMoodPhraseQuery($query)) {
			$rawHits = $this->filterHitsBySceneMoodPhrase($rawHits, $query);
			$sceneFiltered = true;
		}
		$ranked = $this->rankHitsBestMatch($rawHits, $query);
		$meiliTotal = (int) ($primary['total'] ?? 0);
		// Only shrink to the PHP window when gender is post-filtered in PHP.
		// When gender is already in the Meili filter, trust Meili's total (star/price counts).
		$phpOnlyGender = $this->activeGenderFilter !== [] && !$this->indexSupportsGenderFilter();
		$total = ($phpOnlyGender || $sceneFiltered || $starFiltered || $similarFiltered)
			? count($ranked)
			: max($meiliTotal, count($ranked));
		$globalOffset = ($page - 1) * $perPage;
		if ($globalOffset < count($ranked)) {
			$pageHits = array_slice($ranked, $globalOffset, $perPage);
		} elseif (!$phpOnlyGender && !$sceneFiltered && !$starFiltered && !$similarFiltered && $meiliTotal > count($ranked)) {
			$deep = $this->runIndexSearch(
				$index,
				$query,
				$searchFilters,
				[],
				$page,
				$perPage,
				'all',
				false,
				0,
				null,
				false
			);
			$pageHits = $this->filterHitsByGender($deep['rawHits'] ?? []);
			if ($starFiltered) {
				$pageHits = $this->filterHitsToStarBuckets($pageHits, $this->activeStarBuckets);
			}
			if ($similarFiltered) {
				$pageHits = $this->filterHitsExcludeSimilarLine($pageHits);
			}
		} else {
			$pageHits = [];
		}

		return [
			'hits' => $this->mapHits($pageHits),
			'total' => $total,
			'facets' => $primary['facets'],
			'minPrice' => $primary['minPrice'],
			'maxPrice' => $primary['maxPrice'],
			'debug' => [
				'ranking_note' => 'Default sort: Meili relevance with hard in-stock before Notify Me',
				'mode' => 'prefer_in_stock',
				'q' => $query,
				'fetched' => count($rawHits),
				'total' => $total,
				'meili_total' => $meiliTotal,
			],
		];
	}

	/** Multi-word queries use the merged Best Match hit set (also for price/name sorts). */
	private function isMultiWordQuery(string $query): bool
	{
		if (trim($query) === '') {
			return false;
		}
		$tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY);
		return is_array($tokens) && count($tokens) >= 2;
	}

	/** @deprecated Use isMultiWordQuery(); kept for any older call sites. */
	private function shouldRankExactMatchesFirst(string $query, array $sort): bool
	{
		return $this->isMultiWordQuery($query);
	}

	/**
	 * Apply Meili-style sort clauses to an already-collected hit set (keeps totals stable).
	 *
	 * @param list<array<string, mixed>> $hits
	 * @param list<string> $sort e.g. ['same_variation_min_price:asc']
	 * @return list<array<string, mixed>>
	 */
	private function sortCollectedHits(array $hits, array $sort): array
	{
		if ($hits === [] || $sort === []) {
			return $hits;
		}

		$clauses = [];
		foreach ($sort as $clause) {
			$clause = trim((string) $clause);
			if ($clause === '' || !str_contains($clause, ':')) {
				continue;
			}
			[$attr, $dir] = array_pad(explode(':', $clause, 2), 2, 'asc');
			$attr = trim($attr);
			$dir = strtolower(trim($dir)) === 'desc' ? 'desc' : 'asc';
			if ($attr === '') {
				continue;
			}
			// Prefer catalog attr, then display-price / name fallbacks used on indexed docs.
			$attrs = [$attr];
			if (in_array($attr, ['same_variation_min_price', 'same_variation_max_price', 'sale_price'], true)) {
				$attrs[] = 'price';
			} elseif ($attr === 'price') {
				$attrs[] = 'same_variation_max_price';
				$attrs[] = 'same_variation_min_price';
			}
			$clauses[] = ['attrs' => array_values(array_unique($attrs)), 'dir' => $dir];
		}
		if ($clauses === []) {
			return $hits;
		}

		$pick = static function (array $hit, array $attrs) {
			foreach ($attrs as $attr) {
				$v = $hit[$attr] ?? null;
				if ($v !== null && $v !== '') {
					return $v;
				}
			}
			return null;
		};

		$stockOf = static function (array $hit): int {
			return max((int) ($hit['inventory_count'] ?? 0), (int) ($hit['website_stock'] ?? 0));
		};

		$genderRankOf = function (array $hit): int {
			return $this->primaryGenderRank((string) ($hit['gender'] ?? ''));
		};

		usort($hits, function (array $a, array $b) use ($clauses, $pick, $stockOf, $genderRankOf): int {
			// Gender intent: all Women (or Men) before Unisex, then stock within band.
			$aGender = $genderRankOf($a);
			$bGender = $genderRankOf($b);
			if ($aGender !== $bGender) {
				return $aGender <=> $bGender;
			}
			// Within the same gender band: in-stock before Notify Me.
			$aIn = $stockOf($a) > 0 ? 1 : 0;
			$bIn = $stockOf($b) > 0 ? 1 : 0;
			if ($aIn !== $bIn) {
				return $bIn <=> $aIn;
			}
			foreach ($clauses as $c) {
				$av = $pick($a, $c['attrs']);
				$bv = $pick($b, $c['attrs']);
				$aMissing = $av === null || $av === '';
				$bMissing = $bv === null || $bv === '';
				if ($aMissing !== $bMissing) {
					return $aMissing ? 1 : -1; // missing sort values last
				}
				if ($aMissing) {
					continue;
				}
				if (is_numeric($av) && is_numeric($bv)) {
					$cmp = ((float) $av) <=> ((float) $bv);
				} else {
					$cmp = strnatcasecmp((string) $av, (string) $bv);
				}
				if ($cmp !== 0) {
					return $c['dir'] === 'desc' ? -$cmp : $cmp;
				}
			}
			$aOrd = (int) ($a['_meiliOrd'] ?? 0);
			$bOrd = (int) ($b['_meiliOrd'] ?? 0);
			return $aOrd <=> $bOrd;
		});

		foreach ($hits as &$hit) {
			unset($hit['_meiliOrd'], $hit['_rankingScore']);
		}
		unset($hit);

		return $hits;
	}

	/**
	 * Attributes used for keyword matching (Best Match tiers + default search).
	 * Keep names aligned with catalog searchableAttributes / Meili documents.
	 *
	 * @return list<string>
	 */
	private function keywordSearchAttributes(): array
	{
		// Primary lexical search mirrors SearchSpring-style fields.
		// Description is omitted by default: marketing copy creates false positives
		// for attribute phrases — except date-night intent (SS matches Occasion & mood copy).
		$preferred = [
			'name', 'brand', 'sku', 'category', 'search_keywords',
		];
		if ($this->searchDescription) {
			$preferred[] = 'description';
		}
		$configured = $this->catalog->searchableAttributes();
		$on = array_values(array_unique(array_intersect($preferred, $configured)));
		if ($on !== []) {
			return $on;
		}
		return array_values($configured);
	}

	/** @deprecated LLM search_description flag is the source of truth (with quality-noise guard). */
	private function queryNeedsDescriptionSearch(string $query): bool
	{
		return $this->searchDescription;
	}

	/**
	 * Quality/longevity phrases appear in almost every marketing blurb — searching description
	 * inflates hit counts far above SearchSpring. Scene phrases (date night) still need it.
	 */
	private function isDescriptionNoisyQualityQuery(string $query): bool
	{
		return (bool) preg_match(
			'/\b(?:'
			. 'long[\s\-]?lasting|long[\s\-]?wear|lasts?\s+all\s+day|all[\s\-]?day(?:\s+long)?|'
			. 'lasts?\s+forever|high\s+longevity|strong\s+longevity|good\s+longevity|'
			. 'strong\s+projection|high\s+sillage|long\s+wear'
			. ')\b/iu',
			$query
		);
	}

	/**
	 * @param array<int, array<string, mixed>> $hits
	 * @return list<object>
	 */
	private function mapHits(array $hits): array
	{
		$mapped = [];
		foreach ($hits as $hit) {
			if (!is_array($hit)) {
				continue;
			}
			$mapped[] = (object) [
				'uid' => $hit['uid'] ?? $hit['id'] ?? '',
				'sku' => $hit['sku'] ?? '',
				'name' => $hit['name'] ?? '',
				'brand' => $hit['brand'] ?? '',
				'product_url' => $hit['product_url'] ?? '',
				'imageUrl' => $this->resolveImageUrl($hit),
				'extra_image_url' => (string) ($hit['extra_image_url'] ?? ''),
				'price' => (float) ($hit['price'] ?? 0),
				'msrp' => (float) ($hit['msrp'] ?? 0),
				'sale_price' => (float) ($hit['sale_price'] ?? 0),
				'website_stock' => max((int) ($hit['website_stock'] ?? 0), (int) ($hit['inventory_count'] ?? 0)),
				'inventory_count' => max((int) ($hit['inventory_count'] ?? 0), (int) ($hit['website_stock'] ?? 0)),
				'max_two_day_delivery' => $hit['max_two_day_delivery'] ?? 'No',
				'is_deal_of_week' => $hit['is_deal_of_week'] ?? 'No',
				'product_type' => $hit['product_type'] ?? 'both',
				'gender' => $hit['gender'] ?? '',
				'rating' => (int) ($hit['rating'] ?? $hit['rating_count'] ?? 0),
				'rating_avg' => (float) ($hit['rating_avg'] ?? 0),
				'category_id' => $hit['category_id'] ?? '',
				'same_variation_min_price' => (float) ($hit['same_variation_min_price'] ?? ($hit['price'] ?? 0)),
				'same_variation_max_price' => (float) ($hit['same_variation_max_price'] ?? ($hit['price'] ?? 0)),
				'same_variation_product_count' => (int) ($hit['same_variation_product_count'] ?? 1),
				'intellisuggestData' => '',
				'intellisuggestSignature' => '',
			];
		}
		return $mapped;
	}

	/** @param array<string, mixed> $hit */
	private function resolveImageUrl(array $hit): string
	{
		foreach (['imageUrl', 'thumbnail_url'] as $key) {
			$url = trim((string) ($hit[$key] ?? ''));
			if ($url !== '') {
				return $url;
			}
		}
		return trim((string) ($hit['extra_image_url'] ?? ''));
	}

	/**
	 * Decode HTML entities and strip NBSP-mojibake "Â" so facet labels like
	 * "&Acirc;Sicilian Lemon" display as "Sicilian Lemon". Meili filter value stays raw.
	 */
	private function cleanFacetDisplayLabel(string $value): string
	{
		$label = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$label = str_replace(["\xc2\xa0", "\xA0", '&nbsp;', '&NBSP;'], ' ', $label);
		$label = preg_replace('/^\x{00C2}+/u', '', $label) ?? $label;
		$label = trim(preg_replace('/\s+/u', ' ', $label) ?? $label);
		return $label !== '' ? $label : $value;
	}

	/**
	 * SearchSpring-shaped facet list for existing listing views.
	 *
	 * @param array<string, array<string, int>> $distribution
	 * @return list<array<string, mixed>>
	 */
	private function mapFacets(array $distribution): array
	{
		$facets = [];
		foreach ($this->catalog->filters() as $field) {
			if (($field['is_display'] ?? 'yes') === 'no') {
				continue;
			}
			$meili = (string) ($field['meili'] ?? '');
			$ssField = (string) ($field['ss_field'] ?? $meili);
			if ($meili === '' || $ssField === 'price') {
				continue;
			}
			$values = $distribution[$meili] ?? [];
			if (!is_array($values) || $values === []) {
				continue;
			}
			$mappedValues = [];
			foreach ($values as $value => $count) {
				$value = (string) $value;
				if (trim($value) === '') {
					continue;
				}
				$mappedValues[] = [
					'value' => $value,
					'label' => $this->cleanFacetDisplayLabel($value),
					'count' => (int) $count,
					'active' => 0,
				];
			}
			// Live SearchSpring-style Star Rating: exclusive 5 / 4+ / 3+ buckets.
			if ($meili === 'rating_avg' || $ssField === 'rating_avg') {
				$mappedValues = $this->bucketRatingAvgFacetValues($values);
			}
			// Group oz/ml casing variants + dual labels; sort by volume.
			if ($meili === 'size' || $ssField === 'size') {
				$mappedValues = SizeNormalizer::bucketFacetValues($values);
			}
			if ($mappedValues === []) {
				continue;
			}
			$facets[] = [
				'field' => $ssField,
				'label' => (string) ($field['label'] ?? $ssField),
				'values' => $mappedValues,
			];
		}
		return $facets;
	}

	/**
	 * Collapse continuous rating_avg facet keys into exclusive 5 / 4+ / 3+ buckets.
	 * 5 = avg >= 4.75; 4+ = 4–4.74; 3+ = 3–3.99 (4+ does not include visual-5★ scores).
	 *
	 * @param array<string|int, int> $values
	 * @return list<array{value:string,label:string,count:int,active:int}>
	 */
	private function bucketRatingAvgFacetValues(array $values): array
	{
		$bucketCounts = [5 => 0, 4 => 0, 3 => 0];
		foreach ($values as $value => $valueCount) {
			$star = $this->starBucketForAverage((float) $value);
			if ($star === 0) {
				continue;
			}
			$bucketCounts[$star] += (int) $valueCount;
		}
		$mapped = [];
		foreach ([5, 4, 3] as $star) {
			if ($bucketCounts[$star] <= 0) {
				continue;
			}
			$mapped[] = [
				'value' => (string) $star,
				'label' => $star === 5 ? '5' : $star . '+',
				'count' => $bucketCounts[$star],
				'active' => 0,
			];
		}
		return $mapped;
	}

	/** Star buckets aligned to half-star display (Yotpo-style):
	 * 5  = avg >= 4.75 (reads as five solid stars)
	 * 4+ = 4.00–4.74
	 * 3+ = 3.00–3.99
	 */
	private function starBucketForAverage(float $avg): int
	{
		if ($avg >= 4.75) {
			return 5;
		}
		if ($avg >= 4.0) {
			return 4;
		}
		if ($avg >= 3.0) {
			return 3;
		}
		return 0;
	}

	/**
	 * Hard-drop any hit outside the selected Star Rating bucket (safety net if Meili
	 * filter was missing/OR'd with another bucket via multi-select).
	 *
	 * @param array<string, mixed> $result
	 * @param array<string, mixed> $filters
	 * @return array<string, mixed>
	 */
	private function guardHitsToSelectedStarBucket(array $result, array $filters): array
	{
		$wanted = $this->selectedStarBuckets($filters);
		if ($wanted === []) {
			return $result;
		}
		$before = count((array) ($result['hits'] ?? []));
		$result['hits'] = $this->filterHitsToStarBuckets((array) ($result['hits'] ?? []), $wanted);
		$after = count($result['hits']);
		// Recalculate total when we stripped higher-rated products from this page.
		if ($after < $before) {
			$total = (int) ($result['total'] ?? 0);
			$result['total'] = max($after, $total - ($before - $after));
		}
		if ($result['hits'] === [] && (int) ($result['total'] ?? 0) > 0 && $before > 0) {
			$result['total'] = 0;
		}
		return $result;
	}

	/**
	 * Drop the reference product (and other sizes of the same line) from similar-to results.
	 *
	 * @param array<string, mixed> $result
	 * @param array<string, mixed> $filters
	 * @return array<string, mixed>
	 */
	private function guardHitsExcludeUid(array $result, array $filters): array
	{
		$exclude = trim((string) ($filters['exclude_uid'] ?? ''));
		$lineTokens = [];
		foreach ((array) ($filters['_exclude_same_line_tokens'] ?? []) as $tok) {
			if (is_scalar($tok) && trim((string) $tok) !== '') {
				$lineTokens[] = strtolower(trim((string) $tok));
			}
		}
		$refBrand = strtolower(trim((string) ($filters['_similar_to_brand'] ?? '')));
		if ($exclude === '' && $lineTokens === []) {
			return $result;
		}
		$hits = [];
		$removed = 0;
		foreach ((array) ($result['hits'] ?? []) as $hit) {
			if (is_object($hit)) {
				$uid = trim((string) ($hit->uid ?? $hit->id ?? ''));
				$name = strtolower((string) ($hit->name ?? ''));
				$brand = strtolower((string) ($hit->brand ?? ''));
			} else {
				$uid = trim((string) ($hit['uid'] ?? $hit['id'] ?? ''));
				$name = strtolower((string) ($hit['name'] ?? ''));
				$brand = strtolower((string) ($hit['brand'] ?? ''));
			}

			$drop = false;
			if ($exclude !== '' && $uid === $exclude) {
				$drop = true;
			}
			// Same house + all distinctive reference tokens in the name → same product line.
			// Brand facet is sometimes the line name (e.g. brand="Sauvage") — also match brand in title.
			if (!$drop && $lineTokens !== [] && $refBrand !== '') {
				$brandMatch = $brand !== '' && (
					$brand === $refBrand
					|| str_contains($brand, $refBrand)
					|| str_contains($refBrand, $brand)
				);
				$nameHasBrand = str_contains($name, $refBrand);
				if ($brandMatch || $nameHasBrand) {
					$allInName = true;
					foreach ($lineTokens as $tok) {
						if ($tok !== '' && !str_contains($name, $tok)) {
							$allInName = false;
							break;
						}
					}
					if ($allInName) {
						$drop = true;
					}
				}
			}
			if ($drop) {
				$removed++;
				continue;
			}
			$hits[] = $hit;
		}
		if ($removed === 0) {
			return $result;
		}
		$result['hits'] = $hits;
		$total = (int) ($result['total'] ?? 0);
		if ($total > 0) {
			$result['total'] = max(count($hits), $total - $removed);
		}
		return $result;
	}

	/**
	 * @param list<object|array<string, mixed>> $hits
	 * @return list<object|array<string, mixed>>
	 */
	private function filterHitsExcludeSimilarLine(array $hits): array
	{
		if ($hits === [] || ($this->similarExcludeUid === '' && $this->similarExcludeTokens === [])) {
			return $hits;
		}
		$out = [];
		foreach ($hits as $hit) {
			if (is_object($hit)) {
				$uid = trim((string) ($hit->uid ?? $hit->id ?? ''));
				$name = strtolower((string) ($hit->name ?? ''));
				$brand = strtolower((string) ($hit->brand ?? ''));
			} else {
				$uid = trim((string) ($hit['uid'] ?? $hit['id'] ?? ''));
				$name = strtolower((string) ($hit['name'] ?? ''));
				$brand = strtolower((string) ($hit['brand'] ?? ''));
			}
			if ($this->similarExcludeUid !== '' && $uid === $this->similarExcludeUid) {
				continue;
			}
			if ($this->similarExcludeTokens !== [] && $this->similarExcludeBrand !== '') {
				$brandMatch = $brand !== '' && (
					$brand === $this->similarExcludeBrand
					|| str_contains($brand, $this->similarExcludeBrand)
					|| str_contains($this->similarExcludeBrand, $brand)
				);
				$nameHasBrand = str_contains($name, $this->similarExcludeBrand);
				if ($brandMatch || $nameHasBrand) {
					$allInName = true;
					foreach ($this->similarExcludeTokens as $tok) {
						if ($tok !== '' && !str_contains($name, $tok)) {
							$allInName = false;
							break;
						}
					}
					if ($allInName) {
						continue;
					}
				}
			}
			$out[] = $hit;
		}
		return $out;
	}

	/**
	 * Resolve a catalog product by name for NLP "similar to X" intents.
	 * Prefers exact line match (Sauvage ≠ Eau Sauvage). Optional sizeMl picks that variant.
	 *
	 * @return array<string, mixed>|null
	 */
	public function findBestProductByName(string $phrase, int $limit = 8, ?float $sizeMl = null): ?array
	{
		$phrase = trim(preg_replace('/\s+/u', ' ', $phrase) ?? $phrase);
		if ($phrase === '' || mb_strlen($phrase) < 3) {
			return null;
		}
		// Drop size tokens from the Meili q — size is applied as a post-score preference.
		$searchPhrase = trim(preg_replace('/\b\d+(?:\.\d+)?\s*(?:ml|oz)\b/iu', ' ', $phrase) ?? $phrase);
		$searchPhrase = trim(preg_replace('/\s+/u', ' ', $searchPhrase) ?? $searchPhrase);
		if ($searchPhrase === '') {
			$searchPhrase = $phrase;
		}
		try {
			$index = $this->client->index($this->indexName());
			$fetch = max(8, min(40, $sizeMl !== null && $sizeMl > 0 ? max($limit, 30) : $limit));
			$result = $this->runIndexSearch(
				$index,
				$searchPhrase,
				$this->productTypeClause(),
				[],
				1,
				$fetch,
				'all',
				false,
				0,
				['name', 'brand', 'sku'],
				true
			);
			$hits = (array) ($result['rawHits'] ?? []);
			if ($hits === []) {
				$result = $this->runIndexSearch(
					$index,
					$searchPhrase,
					$this->productTypeClause(),
					[],
					1,
					$fetch,
					'last',
					false,
					0,
					['name', 'brand', 'sku'],
					true
				);
				$hits = (array) ($result['rawHits'] ?? []);
			}
			if ($hits === []) {
				return null;
			}
			$needle = strtolower(preg_replace('/[^a-z0-9]+/u', '', $searchPhrase) ?? '');
			$queryTokens = $this->productNameTokens($searchPhrase);
			$best = null;
			$bestScore = -1.0;
			foreach ($hits as $hit) {
				if (!is_array($hit)) {
					continue;
				}
				$name = strtolower((string) ($hit['name'] ?? ''));
				$brand = strtolower((string) ($hit['brand'] ?? ''));
				$nameSansBrand = $name;
				if ($brand !== '') {
					$nameSansBrand = trim(preg_replace('/\b' . preg_quote($brand, '/') . '\b/iu', ' ', $name) ?? $name);
				}
				$hay = preg_replace('/[^a-z0-9]+/u', '', $nameSansBrand) ?? '';
				$hayWithBrand = preg_replace('/[^a-z0-9]+/u', '', $name . $brand) ?? '';
				$rank = (float) ($hit['_rankingScore'] ?? 0);
				$overlap = 0.0;
				if ($needle !== '' && ($hay !== '' || $hayWithBrand !== '')) {
					if ($hay === $needle || str_starts_with($hay, $needle) || str_ends_with($hay, $needle)) {
						$overlap = 1.0;
					} elseif (str_contains($hayWithBrand, $needle) || str_contains($needle, $hay)) {
						$overlap = 0.85;
					} else {
						similar_text($needle, $hay !== '' ? $hay : $hayWithBrand, $pct);
						$overlap = $pct / 100.0;
					}
				}
				$nameTokens = $this->productNameTokens($nameSansBrand);
				// Penalize extra line qualifiers not asked for (Eau Sauvage, Elixir, After Shave…).
				$penalty = 0.0;
				foreach (['eau', 'elixir', 'forte', 'aftershave', 'deodorant', 'shave', 'tester', 'very', 'cool'] as $extra) {
					if (!in_array($extra, $queryTokens, true) && in_array($extra, $nameTokens, true)) {
						$penalty += 0.35;
					}
				}
				$sizeBonus = 0.0;
				if ($sizeMl !== null && $sizeMl > 0) {
					$sizeBonus = $this->hitMatchesSizeMl($hit, $sizeMl) ? 0.55 : -0.15;
				}
				$score = ($rank * 0.4) + ($overlap * 0.45) + $sizeBonus - $penalty;
				if ($score > $bestScore) {
					$bestScore = $score;
					$best = $hit;
				}
			}
			return is_array($best) ? $best : null;
		} catch (Throwable $e) {
			Log::warning('findBestProductByName failed: ' . $e->getMessage(), ['phrase' => $phrase]);
			return null;
		}
	}

	/** @return list<string> */
	private function productNameTokens(string $text): array
	{
		$tokens = [];
		foreach (preg_split('/[^a-z0-9]+/u', strtolower($text)) ?: [] as $tok) {
			$tok = trim($tok);
			if ($tok === '' || mb_strlen($tok) < 3) {
				continue;
			}
			if (in_array($tok, ['the', 'and', 'for', 'men', 'women', 'unisex', 'perfume', 'parfum', 'cologne'], true)) {
				continue;
			}
			$tokens[] = $tok;
		}
		return array_values(array_unique($tokens));
	}

	/** True when the hit's indexed size facet matches the requested ml volume. */
	public function hitMatchesSizeMl(array $hit, float $sizeMl): bool
	{
		if ($sizeMl <= 0) {
			return false;
		}
		$target = round($sizeMl, 1);
		foreach ((array) ($hit['size'] ?? []) as $sz) {
			if (!is_scalar($sz)) {
				continue;
			}
			$raw = trim((string) $sz);
			if ($raw === '') {
				continue;
			}
			$parsed = SizeNormalizer::parse($raw);
			if (is_array($parsed) && isset($parsed['ml']) && abs((float) $parsed['ml'] - $target) < 0.6) {
				return true;
			}
			if (preg_match('/\b' . preg_quote((string) (int) round($target), '/') . '\s*ml\b/iu', $raw)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param list<object|array<string, mixed>> $hits
	 * @param list<int> $wanted
	 * @return list<object|array<string, mixed>>
	 */
	private function filterHitsToStarBuckets(array $hits, array $wanted): array
	{
		if ($wanted === [] || $hits === []) {
			return $hits;
		}
		$wantedSet = array_fill_keys($wanted, true);
		$out = [];
		foreach ($hits as $hit) {
			$avg = is_object($hit)
				? (float) ($hit->rating_avg ?? 0)
				: (float) ($hit['rating_avg'] ?? 0);
			if (isset($wantedSet[$this->starBucketForAverage($avg)])) {
				$out[] = $hit;
			}
		}
		return $out;
	}

	/**
	 * @param array<string, mixed> $filters
	 * @return list<int>
	 */
	private function selectedStarBuckets(array $filters): array
	{
		if (!array_key_exists('rating_avg', $filters) || $filters['rating_avg'] === '' || $filters['rating_avg'] === null) {
			return [];
		}
		$raw = is_array($filters['rating_avg']) ? $filters['rating_avg'] : [$filters['rating_avg']];
		$star = 0;
		foreach ($raw as $value) {
			if (!is_scalar($value)) {
				continue;
			}
			$decoded = strtolower(trim(str_replace(
				['+', 'stars', 'star'],
				'',
				$this->decodeFilterValue((string) $value)
			)));
			if ($decoded === '' || !is_numeric($decoded)) {
				continue;
			}
			$n = (int) round((float) $decoded);
			if (in_array($n, [3, 4, 5], true)) {
				$star = $n; // last wins (radio)
			}
		}
		return $star === 0 ? [] : [$star];
	}

	/**
	 * Build facet distribution from an array of raw hit arrays (PHP-side).
	 * Covers all merged hits so facets reflect the full result set, not just one sub-query.
	 *
	 * @param list<array<string, mixed>> $hits
	 * @return list<array<string, mixed>>
	 */
	private function buildFacetsFromHits(array $hits): array
	{
		$distribution = [];
		foreach ($this->catalog->filters() as $field) {
			if (($field['is_display'] ?? 'yes') === 'no') {
				continue;
			}
			$meili = (string) ($field['meili'] ?? '');
			if ($meili === '' || ($field['ss_field'] ?? $meili) === 'price') {
				continue;
			}
			$counts = [];
			foreach ($hits as $hit) {
				$val = $hit[$meili] ?? null;
				if ($val === null || $val === '') {
					continue;
				}
				$values = is_array($val) ? $val : [(string) $val];
				foreach ($values as $v) {
					$v = trim((string) $v);
					if ($v === '') continue;
					$counts[$v] = ($counts[$v] ?? 0) + 1;
				}
			}
			if ($counts !== []) {
				ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);
				$distribution[$meili] = $counts;
			}
		}
		return $this->mapFacets($distribution);
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function buildMeilisearchFilter(array $filters): string
	{
		// NLP note soft-match uses CONTAINS. Do not also AND equality on the same
		// attribute — compounds like "Vanilla and Musk" would be excluded.
		$filterInput = $filters;
		if (!empty($filterInput['fragrance_notes_contains'])) {
			unset($filterInput['fragrance_notes']);
		}

		$input = $this->filtersToRequestInput($filterInput);
		$request = Request::create('/', 'GET', $input);
		$applied = $this->catalog->applyRequestFilters($request, []);
		$groups = $this->facetFiltersToGroups($applied['facetFilters'] ?? []);

		foreach ((array) ($applied['numericFilters'] ?? []) as $numeric) {
			$numeric = trim((string) $numeric);
			if ($numeric !== '') {
				$groups[] = $numeric;
			}
		}

		$productType = $this->productTypeClause();
		if ($productType !== '') {
			$groups[] = $productType;
		}

		// Top Rated alone ≈ avg >= 4. Skip when an exclusive Star Rating bucket is already set
		// (otherwise 4+ is fine, but 3+ AND >=4 would empty the set; never widen 4+ to include 5★).
		if (
			!empty($filters['OnTopRated'])
			&& ($filters['OnTopRated'] === true || $filters['OnTopRated'] === 'true')
			&& $this->selectedStarBuckets($filters) === []
		) {
			$groups[] = 'rating_avg >= 4';
		}
		if (!empty($filters['InStockItems']) && ($filters['InStockItems'] === true || $filters['InStockItems'] === 'true')) {
			$groups[] = 'website_stock >= 1';
		}
		if (!empty($filters['OnSaleDeal']) && ($filters['OnSaleDeal'] === true || $filters['OnSaleDeal'] === 'true')) {
			$groups[] = 'is_deal_of_week = "Yes"';
		}

		// Prefer Meili gender filter when the index allows it; PHP also post-filters hits
		// so results stay correct before filterableAttributes includes "gender".
		$genderCodes = $this->normalizeGenderFilterCodes($filters['gender'] ?? null);
		if ($genderCodes !== [] && $this->indexSupportsGenderFilter()) {
			$parts = [];
			foreach ($genderCodes as $code) {
				$parts[] = 'gender = "' . addcslashes($code, '\\"') . '"';
			}
			$groups[] = count($parts) === 1 ? $parts[0] : '(' . implode(' OR ', $parts) . ')';
		}

		$familyContains = $this->fragranceFamilyContainsClause($filters);
		if ($familyContains !== '') {
			$groups[] = $familyContains;
		}

		$notesContains = $this->fragranceNotesContainsClause($filters);
		if ($notesContains !== '') {
			$groups[] = $notesContains;
		}

		return implode(' AND ', array_filter($groups));
	}

	/**
	 * NLP scent concepts → Meili LIKE/CONTAINS on fragrance_family (e.g. citrus matches
	 * "Citrus", "Citrus Aromatic", "Fresh Citrus Aromatic", …).
	 *
	 * @param array<string, mixed> $filters
	 */
	private function fragranceFamilyContainsClause(array $filters): string
	{
		return $this->facetContainsClause(
			$filters['fragrance_family_contains'] ?? null,
			'fragrance_family'
		);
	}

	/**
	 * NLP note tokens → CONTAINS on fragrance_notes so compounds ("Vanilla and Musk")
	 * match without selecting every compound facet in the sidebar/chips.
	 *
	 * @param array<string, mixed> $filters
	 */
	private function fragranceNotesContainsClause(array $filters): string
	{
		return $this->facetContainsClause(
			$filters['fragrance_notes_contains'] ?? null,
			'fragrance_notes'
		);
	}

	/**
	 * @param mixed $raw
	 */
	private function facetContainsClause($raw, string $meiliAttribute): string
	{
		if ($raw === null || $raw === '' || $raw === []) {
			return '';
		}
		$concepts = [];
		foreach (is_array($raw) ? $raw : [$raw] as $item) {
			if (!is_scalar($item)) {
				continue;
			}
			$c = strtolower(trim((string) $item));
			if ($c !== '' && mb_strlen($c) >= 3) {
				$concepts[] = $c;
			}
		}
		$concepts = array_values(array_unique($concepts));
		if ($concepts === []) {
			return '';
		}

		// Prefer native CONTAINS (true "like %vanilla%") when the server allows it.
		$containsParts = [];
		foreach ($concepts as $concept) {
			$containsParts[] = $meiliAttribute . ' CONTAINS "' . addcslashes($concept, '\\"') . '"';
		}
		$containsClause = count($containsParts) === 1
			? $containsParts[0]
			: '(' . implode(' OR ', $containsParts) . ')';
		if ($this->meiliSupportsContainsFilter($containsClause)) {
			return $containsClause;
		}

		// Fallback: OR every catalog facet value that contains the concept token(s).
		$expanded = $this->expandConceptsToEqualityValues($concepts, $meiliAttribute);
		if ($expanded === []) {
			return '';
		}
		$ors = [];
		foreach ($expanded as $value) {
			$ors[] = $meiliAttribute . ' = "' . addcslashes($value, '\\"') . '"';
		}
		return count($ors) === 1 ? $ors[0] : '(' . implode(' OR ', $ors) . ')';
	}

	/**
	 * Probe once whether CONTAINS filters are enabled on this Meili instance.
	 * If the experimental feature is off, try enabling it once, then fall back to equality OR.
	 */
	private function meiliSupportsContainsFilter(string $sampleClause): bool
	{
		static $supported = null;
		if ($supported !== null) {
			return $supported;
		}

		$supported = $this->probeContainsFilter($sampleClause);
		if ($supported) {
			return true;
		}

		// Auto-enable experimental containsFilter (requires master/admin key).
		try {
			if (method_exists($this->client, 'enableContainsFilterFeature')
				&& $this->client->enableContainsFilterFeature()
			) {
				$supported = $this->probeContainsFilter($sampleClause);
				if ($supported) {
					Log::info('Meili containsFilter experimental feature enabled');
					return true;
				}
			}
		} catch (Throwable $e) {
			// Fall through to equality expansion.
		}

		Log::info('Meili CONTAINS filter unavailable; using fragrance_family equality expansion');
		return false;
	}

	private function probeContainsFilter(string $sampleClause): bool
	{
		try {
			$index = $this->client->index($this->indexName());
			$index->search('', [
				'limit' => 0,
				'filter' => $sampleClause,
			]);
			return true;
		} catch (Throwable $e) {
			return false;
		}
	}

	/**
	 * Expand scent concepts to all known fragrance_family facet labels containing them.
	 *
	 * @param list<string> $concepts
	 * @return list<string>
	 */
	private function expandFragranceConceptsToEqualityValues(array $concepts): array
	{
		return $this->expandConceptsToEqualityValues($concepts, 'fragrance_family');
	}

	/**
	 * Expand concept tokens to catalog facet labels containing them (CONTAINS fallback).
	 *
	 * @param list<string> $concepts
	 * @return list<string>
	 */
	private function expandConceptsToEqualityValues(array $concepts, string $meiliAttribute): array
	{
		if ($meiliAttribute === '' || $concepts === []) {
			return [];
		}
		try {
			$index = $this->client->index($this->indexName());
			$result = $index->search('', [
				'limit' => 0,
				'facets' => [$meiliAttribute],
			]);
			$raw = method_exists($result, 'getRaw') ? $result->getRaw() : [];
			$distribution = $raw['facetDistribution'][$meiliAttribute] ?? [];
			if (!is_array($distribution) || $distribution === []) {
				return [];
			}
		} catch (Throwable $e) {
			return [];
		}

		$matched = [];
		foreach (array_keys($distribution) as $facetValue) {
			$facetValue = (string) $facetValue;
			$hay = strtolower($facetValue);
			if ($hay === '' || is_numeric($hay)) {
				continue;
			}
			foreach ($concepts as $concept) {
				$stem = rtrim($concept, 's');
				if ($stem === '' || mb_strlen($stem) < 3) {
					$stem = $concept;
				}
				if (preg_match('/\b' . preg_quote($stem, '/') . 's?\b/u', $hay)) {
					// Prefer atomic parts when the facet row is a comma-compound.
					$parts = preg_split('/\s*[,|;]\s*/u', $facetValue) ?: [$facetValue];
					foreach ($parts as $part) {
						$part = trim($part);
						if ($part === '' || str_contains($part, ',')) {
							continue;
						}
						$partHay = strtolower($part);
						if (preg_match('/\b' . preg_quote($stem, '/') . 's?\b/u', $partHay)) {
							$matched[$part] = true;
						}
					}
					// Also keep exact atomic facet rows (and "X and Y" note compounds for notes).
					if (!str_contains($facetValue, ',')) {
						$matched[$facetValue] = true;
					}
					break;
				}
			}
		}
		return array_keys($matched);
	}

	/**
	 * "men perfume" / "perfume for women": apply real gender codes (M/W/U).
	 * Keep gender words in q so titles like "… for Men" still match; opposite-gender
	 * hits pulled in via noisy searchKeywords are removed in filterHitsByGender().
	 *
	 * @param array<string, mixed> $filters
	 * @return array{0: array<string, mixed>, 1: string}
	 */
	private function applyGenderIntent(string $query, array $filters): array
	{
		if ($this->normalizeGenderFilterCodes($filters['gender'] ?? null) !== []) {
			return [$filters, $query];
		}

		$intent = $this->detectGenderIntent($query);
		if ($intent === null) {
			return [$filters, $query];
		}

		$filters['gender'] = $intent['codes'];
		return [$filters, $query];
	}

	/**
	 * @return array{codes: list<string>}|null
	 */
	private function detectGenderIntent(string $query): ?array
	{
		$q = strtolower(trim($query));
		if ($q === '') {
			return null;
		}

		// Check women before men — "women" contains the letters "men".
		if (preg_match('/\b(women|woman|ladies|female|hers?)\b/u', $q)) {
			return ['codes' => ['W', 'U']];
		}
		if (preg_match('/\b(men|man|guys|male|him)\b/u', $q)) {
			return ['codes' => ['M', 'U']];
		}
		if (preg_match('/\bunisex\b/u', $q)) {
			return ['codes' => ['U']];
		}

		return null;
	}

	/**
	 * Date night / dinner date — SearchSpring matches Occasion & mood copy, not Night titles.
	 */
	private function isSceneMoodPhraseQuery(string $query): bool
	{
		$q = strtolower(trim($query));
		if ($q === '') {
			return false;
		}
		return (bool) preg_match('/\bdate\s*nights?\b/u', $q)
			|| (bool) preg_match('/\bdinner\s+dates?\b/u', $q);
	}

	/**
	 * Prefer catalog occasion values that mention date (e.g. "date nights") over
	 * products that only mention the phrase in marketing copy.
	 * Lower = earlier. No-op when not in scene/mood mode.
	 */
	private function sceneOccasionRank(array $hit): int
	{
		$rankQ = $this->rankingQuery !== '' ? $this->rankingQuery : '';
		if (!$this->searchDescription || !$this->isSceneMoodPhraseQuery($rankQ)) {
			return 0;
		}
		$occ = $hit['occasion'] ?? [];
		if (!is_array($occ)) {
			$occ = [$occ];
		}
		foreach ($occ as $o) {
			if (stripos((string) $o, 'date') !== false) {
				return 0;
			}
		}
		return 1;
	}

	/**
	 * Keep hits whose description / keywords / name contain the scene phrase
	 * (e.g. "date nights" in Occasion & mood), not loose token matches.
	 *
	 * @param list<array<string, mixed>> $hits
	 * @return list<array<string, mixed>>
	 */
	private function filterHitsBySceneMoodPhrase(array $hits, string $phrase): array
	{
		$phrase = strtolower(trim(preg_replace('/\s+/u', ' ', $phrase) ?? $phrase));
		if ($phrase === '') {
			return $hits;
		}
		$parts = preg_split('/\s+/u', $phrase, -1, PREG_SPLIT_NO_EMPTY) ?: [];
		if ($parts === []) {
			return $hits;
		}
		// Word-boundary phrase with optional plural on the last token ("date night" ≈ "date nights").
		// Do NOT use space-stripped substring matching — "update"+"nightly" false-positives "datenight".
		$escaped = [];
		foreach ($parts as $i => $t) {
			$e = preg_quote($t, '/');
			if ($i === count($parts) - 1) {
				$e .= 's?';
			}
			$escaped[] = $e;
		}
		$flex = '/\b' . implode('\s+', $escaped) . '\b/iu';
		$closed = '/\b' . preg_quote(implode('', $parts), '/') . 's?\b/iu';
		$out = [];
		foreach ($hits as $hit) {
			if (!is_array($hit)) {
				continue;
			}
			$kw = $hit['search_keywords'] ?? $hit['searchKeywords'] ?? '';
			if (is_array($kw)) {
				$kw = implode(' ', $kw);
			}
			$blob = strtolower(trim(implode("\n", [
				(string) ($hit['description'] ?? ''),
				(string) $kw,
				(string) ($hit['name'] ?? ''),
			])));
			if ($blob === '') {
				continue;
			}
			$blob = html_entity_decode($blob, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			if (preg_match($flex, $blob) || preg_match($closed, $blob)) {
				$out[] = $hit;
			}
		}
		return $out;
	}

	/**
	 * @param list<array<string, mixed>> $hits
	 * @return list<array<string, mixed>>
	 */
	private function filterHitsByGender(array $hits): array
	{
		if ($this->activeGenderFilter === []) {
			return $hits;
		}
		$set = array_flip($this->activeGenderFilter);
		$out = [];
		foreach ($hits as $hit) {
			if (!is_array($hit)) {
				continue;
			}
			$g = (string) ($hit['gender'] ?? '');
			if ($g === '' || !isset($set[$g])) {
				continue;
			}
			// Catalog typos: exclude titles that clearly belong to the opposite gender
			// (e.g. "Perfume for Women" wrongly indexed as M on a men query).
			if ($this->hitNameConflictsWithGenderIntent($hit)) {
				continue;
			}
			$out[] = $hit;
		}
		return $out;
	}

	/**
	 * Men intent must never show clear women titles; women intent never clear men titles.
	 * Unisex stays allowed unless the title explicitly signals the opposite gender.
	 */
	private function hitNameConflictsWithGenderIntent(array $hit): bool
	{
		$codes = $this->activeGenderFilter;
		if ($codes === []) {
			return false;
		}
		$hasW = in_array('W', $codes, true);
		$hasM = in_array('M', $codes, true);
		if ($hasW === $hasM) {
			// Unisex-only or both M+W — no opposite-gender rule.
			return false;
		}
		$name = strtolower((string) ($hit['name'] ?? ''));
		if ($name === '') {
			return false;
		}
		$signalsWomen = (bool) preg_match(
			'/\b(?:for\s+women|pour\s+femme|women\'?s|for\s+her)\b/u',
			$name
		);
		$signalsMen = (bool) preg_match(
			'/\b(?:for\s+men|pour\s+homme|men\'?s|for\s+him)\b/u',
			$name
		);
		if ($hasM && !$hasW && $signalsWomen && !$signalsMen) {
			return true;
		}
		if ($hasW && !$hasM && $signalsMen && !$signalsWomen) {
			return true;
		}
		return false;
	}

	private function indexSupportsGenderFilter(): bool
	{
		static $supported = null;
		if ($supported !== null) {
			return $supported;
		}
		try {
			$settings = $this->client->index($this->indexName())->getSettings();
			$filterable = is_array($settings) ? ($settings['filterableAttributes'] ?? []) : [];
			if (is_object($settings) && method_exists($settings, 'getFilterableAttributes')) {
				$filterable = $settings->getFilterableAttributes();
			}
			$supported = is_array($filterable) && in_array('gender', $filterable, true);
		} catch (Throwable $e) {
			$supported = false;
		}
		return $supported;
	}

	/**
	 * @param mixed $value
	 * @return list<string>
	 */
	private function normalizeGenderFilterCodes($value): array
	{
		if ($value === null || $value === '' || $value === []) {
			return [];
		}
		if (!is_array($value)) {
			$value = [$value];
		}

		$out = [];
		foreach ($value as $item) {
			$raw = strtolower(trim((string) $item));
			$raw = strtolower(trim($this->decodeFilterValue($raw)));
			if (in_array($raw, ['w', 'women', 'woman', 'female', 'ladies'], true)) {
				$out[] = 'W';
			} elseif (in_array($raw, ['m', 'men', 'man', 'male', 'guys'], true)) {
				$out[] = 'M';
			} elseif (in_array($raw, ['u', 'unisex'], true)) {
				$out[] = 'U';
			} elseif (in_array($raw, ['k', 'kids', 'kid', 'children'], true)) {
				$out[] = 'K';
			} elseif (in_array(strtoupper($raw), ['M', 'W', 'U', 'K'], true)) {
				$out[] = strtoupper($raw);
			}
		}

		return array_values(array_unique($out));
	}

	/**
	 * @param array<string, mixed> $filters
	 * @return list<string>
	 */
	private function sortFromFilters(array $filters): array
	{
		if (!empty($filters['OnTopRated']) && ($filters['OnTopRated'] === true || $filters['OnTopRated'] === 'true')) {
			return ['rating_avg:desc'];
		}
		$sortKey = trim((string) ($filters['sortby'] ?? ''));
		if ($sortKey === '' || strcasecmp($sortKey, 'Sort By') === 0) {
			return [];
		}
		$options = $this->catalog->sortOptions();
		if (isset($options[$sortKey]['meili'])) {
			return [(string) $options[$sortKey]['meili']];
		}
		return [];
	}

	/**
	 * Apply Search Result Rule sort override when conditions match this request.
	 *
	 * @param array<string, mixed> $filters
	 * @return list<string>
	 */
	private function sortOverrideFromResultRules(string $query, array $filters): array
	{
		try {
			/** @var SearchResultRulesService $rules */
			$rules = app(SearchResultRulesService::class);
			$categoryId = '';
			foreach (['categoryids', 'category_id', 'cid', 'scid'] as $k) {
				if (!empty($filters[$k]) && is_scalar($filters[$k])) {
					$categoryId = trim((string) $filters[$k]);
					break;
				}
			}
			$facetFilters = [];
			foreach ($filters as $k => $v) {
				if (!is_string($k) || $k === '' || str_starts_with($k, '_')) {
					continue;
				}
				if (in_array(strtolower($k), ['sortby', 'page', 'keyword', 'q', 'org_search_keyword'], true)) {
					continue;
				}
				if (is_scalar($v) || is_array($v)) {
					$facetFilters[strtolower($k)] = $v;
				}
			}

			return $rules->resolveSortOverrideForQuery($query, $categoryId, $facetFilters);
		} catch (Throwable $e) {
			Log::debug('Result-rule sort override skipped: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Map preferred sort clauses to attributes that are actually sortable on this index.
	 * Server indexes often only have price/name/rating_avg — same_variation_* would 400 and empty the PLP.
	 *
	 * @param list<string> $sort
	 * @return list<string>
	 */
	private function resolveSortForIndex(array $sort): array
	{
		if ($sort === []) {
			return [];
		}
		$sortable = $this->indexSortableAttributes();
		$resolved = [];
		foreach ($sort as $clause) {
			$clause = trim((string) $clause);
			if ($clause === '' || !str_contains($clause, ':')) {
				continue;
			}
			[$attr, $dir] = array_pad(explode(':', $clause, 2), 2, 'asc');
			$attr = trim($attr);
			$dir = strtolower(trim($dir)) === 'desc' ? 'desc' : 'asc';
			if ($attr === '') {
				continue;
			}
			if ($sortable !== [] && in_array($attr, $sortable, true)) {
				$resolved[] = $attr . ':' . $dir;
				continue;
			}
			// Unknown / empty sortable list: map display-price fields to SKU price when possible.
			$fallback = null;
			if (in_array($attr, ['same_variation_min_price', 'same_variation_max_price', 'sale_price'], true)) {
				$fallback = 'price';
			} elseif ($sortable === [] && in_array($attr, ['price', 'name', 'rating_avg'], true)) {
				$fallback = $attr;
			}
			if ($fallback !== null && ($sortable === [] || in_array($fallback, $sortable, true))) {
				$resolved[] = $fallback . ':' . $dir;
			}
		}
		return array_values(array_unique($resolved));
	}

	/** @return list<string> */
	private function indexSortableAttributes(): array
	{
		static $memo = null;
		if (is_array($memo)) {
			return $memo;
		}
		try {
			$settings = $this->client->index($this->indexName())->getSettings();
			$raw = $settings['sortableAttributes'] ?? [];
			$memo = [];
			if (is_array($raw)) {
				foreach ($raw as $attr) {
					if (is_string($attr) && trim($attr) !== '') {
						$memo[] = trim($attr);
					}
				}
			}
		} catch (Throwable $e) {
			Log::warning('Could not read Meilisearch sortableAttributes: ' . $e->getMessage());
			$memo = [];
		}
		return $memo;
	}

	/** @return list<string> */
	private function indexSearchableAttributes(): array
	{
		static $memo = null;
		if (is_array($memo)) {
			return $memo;
		}
		try {
			$settings = $this->client->index($this->indexName())->getSettings();
			$raw = $settings['searchableAttributes'] ?? [];
			$memo = [];
			if (is_array($raw)) {
				foreach ($raw as $attr) {
					if (is_string($attr) && trim($attr) !== '') {
						$memo[] = trim($attr);
					}
				}
			}
		} catch (Throwable $e) {
			Log::warning('Could not read Meilisearch searchableAttributes: ' . $e->getMessage());
			$memo = [];
		}
		return $memo;
	}

	/**
	 * Meili 400s if attributesToSearchOn includes a non-searchable field.
	 *
	 * @param list<string> $wanted
	 * @return list<string>
	 */
	private function clampSearchableAttributes(array $wanted): array
	{
		$available = $this->indexSearchableAttributes();
		if ($available === []) {
			return array_values(array_filter($wanted, static fn ($a) => is_string($a) && $a !== ''));
		}
		$set = array_fill_keys($available, true);
		$out = [];
		foreach ($wanted as $attr) {
			if (is_string($attr) && isset($set[$attr])) {
				$out[] = $attr;
			}
		}
		return array_values(array_unique($out));
	}

	private function isInvalidSortError(Throwable $e): bool
	{
		$msg = strtolower($e->getMessage());
		return str_contains($msg, 'sortable')
			|| str_contains($msg, 'invalid sort')
			|| str_contains($msg, 'attribute') && str_contains($msg, 'sort');
	}

	/**
	 * Fetch then PHP-sort when Meili cannot apply the requested sort attribute.
	 *
	 * @param list<string> $sort
	 * @return array{hits: list<object>, total: int, facets: list<array<string, mixed>>, minPrice: float, maxPrice: float, debug?: array<string, mixed>}
	 */
	private function searchSortedInPhp($index, string $query, string $searchFilters, array $sort, int $page, int $perPage): array
	{
		// Same window as multi-word Best Match — never report total = min(500, fetched).
		$fetchCap = $this->popupFastMode ? 48 : 2500;
		$primary = $this->runIndexSearch(
			$index,
			$query,
			$searchFilters,
			[],
			1,
			$fetchCap,
			'all',
			true,
			0,
			null,
			false
		);

		$rawHits = [];
		$meiliOrd = 0;
		foreach ($primary['rawHits'] as $hit) {
			if (!is_array($hit)) {
				continue;
			}
			$hit['_meiliOrd'] = $meiliOrd++;
			$rawHits[] = $hit;
		}

		$rawHits = $this->filterHitsByGender($rawHits);
		$starFiltered = $this->activeStarBuckets !== [];
		if ($starFiltered) {
			$rawHits = $this->filterHitsToStarBuckets($rawHits, $this->activeStarBuckets);
		}
		$similarFiltered = $this->similarExcludeUid !== '' || $this->similarExcludeTokens !== [];
		if ($similarFiltered) {
			$rawHits = $this->filterHitsExcludeSimilarLine($rawHits);
		}
		$sceneFiltered = false;
		if ($this->searchDescription && $this->isSceneMoodPhraseQuery($query)) {
			$rawHits = $this->filterHitsBySceneMoodPhrase($rawHits, $query);
			$sceneFiltered = true;
		}
		$ranked = $this->sortCollectedHits($rawHits, $sort);
		$meiliTotal = (int) ($primary['total'] ?? 0);
		$phpOnlyGender = $this->activeGenderFilter !== [] && !$this->indexSupportsGenderFilter();
		$total = ($phpOnlyGender || $sceneFiltered || $starFiltered || $similarFiltered)
			? count($ranked)
			: max($meiliTotal, count($ranked));
		$globalOffset = ($page - 1) * $perPage;
		if ($globalOffset < count($ranked)) {
			$pageHits = array_slice($ranked, $globalOffset, $perPage);
		} elseif (!$phpOnlyGender && !$sceneFiltered && !$starFiltered && !$similarFiltered && $meiliTotal > count($ranked)) {
			// Beyond PHP window: Meili-sorted page (stock/gender preference only on first window).
			$deep = $this->runIndexSearch(
				$index,
				$query,
				$searchFilters,
				$sort,
				$page,
				$perPage,
				'all',
				false,
				0,
				null,
				false
			);
			$pageHits = $this->filterHitsByGender($deep['rawHits'] ?? []);
			if ($starFiltered) {
				$pageHits = $this->filterHitsToStarBuckets($pageHits, $this->activeStarBuckets);
			}
			if ($similarFiltered) {
				$pageHits = $this->filterHitsExcludeSimilarLine($pageHits);
			}
		} else {
			$pageHits = [];
		}

		return [
			'hits' => $this->mapHits($pageHits),
			'total' => $total,
			'facets' => $primary['facets'],
			'minPrice' => $primary['minPrice'],
			'maxPrice' => $primary['maxPrice'],
			'debug' => [
				'ranking_note' => 'PHP sort: in-stock first, primary gender before unisex, then price/name/rating',
				'mode' => 'php_sorted_instock_first',
				'q' => $query,
				'sort' => $sort,
				'fetched' => count($rawHits),
				'total' => $total,
				'meili_total' => $meiliTotal,
			],
		];
	}

	/**
	 * @param array<int, mixed> $facetFilters
	 * @return list<string>
	 */
	private function facetFiltersToGroups(array $facetFilters): array
	{
		$groups = [];
		foreach ($facetFilters as $group) {
			if (!is_array($group)) {
				continue;
			}
			$ors = [];
			foreach ($group as $item) {
				$item = (string) $item;
				$pos = strpos($item, ':');
				if ($pos === false) {
					continue;
				}
				$attr = substr($item, 0, $pos);
				$value = substr($item, $pos + 1);
				if ($attr === 'size') {
					foreach (SizeNormalizer::matchValues($value) as $alias) {
						$ors[] = $attr . ' = ' . $this->quoteValue($alias);
					}
					continue;
				}
				$ors[] = $attr . ' = ' . $this->quoteValue($value);
			}
			if (count($ors) === 1) {
				$groups[] = $ors[0];
			} elseif (count($ors) > 1) {
				$ors = array_values(array_unique($ors));
				$groups[] = count($ors) === 1 ? $ors[0] : '(' . implode(' OR ', $ors) . ')';
			}
		}
		return $groups;
	}

	private function quoteValue(string $value): string
	{
		if (is_numeric($value) && !str_contains($value, ' ')) {
			return $value;
		}
		return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
	}

	/**
	 * Match SearchSpring product_type filters:
	 * - logged-in wholesaler → wholesaler OR both
	 * - logged-in retailer → retailer OR both
	 * - guest / not logged in → retailer OR both
	 */
	private function productTypeClause(): string
	{
		$loggedIn = Session::get('sess_icustomerid') != '';
		$userType = strtolower((string) Session::get('eusertype', ''));

		if ($loggedIn && $userType === 'wholesaler') {
			return '(product_type = "wholesaler" OR product_type = "both" OR product_type IS EMPTY OR product_type NOT EXISTS)';
		}

		// Logged-in retailer, or guest (not logged in)
		return '(product_type = "retailer" OR product_type = "both" OR product_type IS EMPTY OR product_type NOT EXISTS)';
	}

	/** @return array<string, mixed> */
	private function productTypeDebugInfo(): array
	{
		$loggedIn = Session::get('sess_icustomerid') != '';
		$userType = strtolower((string) Session::get('eusertype', ''));
		$mode = 'guest_or_retailer';
		if ($loggedIn && $userType === 'wholesaler') {
			$mode = 'wholesaler';
		} elseif ($loggedIn && $userType === 'retailer') {
			$mode = 'retailer';
		} elseif (!$loggedIn) {
			$mode = 'guest';
		}

		return [
			'sess_icustomerid' => Session::get('sess_icustomerid') ?: null,
			'eusertype' => Session::get('eusertype') ?: null,
			'logged_in' => $loggedIn,
			'mode' => $mode,
			'equivalent_searchspring' => ($loggedIn && $userType === 'wholesaler')
				? '&filter.product_type=wholesaler&filter.product_type=both'
				: '&filter.product_type=retailer&filter.product_type=both',
			'meilisearch_clause' => $this->productTypeClause(),
		];
	}

	/**
	 * @param array<string, mixed> $filters
	 * @return array<string, mixed>
	 */
	private function filtersToRequestInput(array $filters): array
	{
		$input = [];
		foreach ($this->catalog->filters() as $field) {
			foreach ((array) ($field['request'] ?? []) as $key) {
				if (!array_key_exists($key, $filters) || $filters[$key] === '' || $filters[$key] === null) {
					continue;
				}
				$values = is_array($filters[$key]) ? $filters[$key] : [$filters[$key]];
				$input[$key] = $this->decodeFilterValues($values);
			}
			if (($field['type'] ?? '') === 'range') {
				$applyRange = (string) ($filters['ochangeprice'] ?? '') === '1';
				$minKey = (string) ($field['request_min'] ?? '');
				$maxKey = (string) ($field['request_max'] ?? '');
				if ($applyRange && $minKey !== '' && isset($filters[$minKey]) && $filters[$minKey] !== '') {
					$input[$minKey] = $filters[$minKey];
				}
				if ($applyRange && $maxKey !== '' && isset($filters[$maxKey]) && $filters[$maxKey] !== '') {
					$input[$maxKey] = $filters[$maxKey];
				}
			}
		}
		return $input;
	}

	/**
	 * @param list<mixed> $values
	 * @return list<string>
	 */
	public function decodeFilterValues(array $values): array
	{
		$decoded = [];
		foreach ($values as $value) {
			if (!is_scalar($value)) {
				continue;
			}
			$decoded[] = $this->decodeFilterValue((string) $value);
		}
		return array_values(array_filter($decoded, fn ($v) => $v !== ''));
	}

	public function decodeFilterValue(string $value): string
	{
		$value = str_replace('doubledot', ':', $value);
		$value = str_replace('dot', '.', $value);
		$value = str_replace('andd', '&', $value);
		$value = str_replace('dash', '-', $value);
		$value = str_replace('singlecomma', "'", $value);
		$value = str_replace('_', ' ', $value);
		return trim($value);
	}

	public function encodeFilterValue(string $value): string
	{
		$value = str_replace(':', 'doubledot', $value);
		$value = str_replace('.', 'dot', $value);
		$value = str_replace('-', 'dash', $value);
		$value = str_replace('&', 'andd', $value);
		$value = str_replace("'", 'singlecomma', $value);
		$value = str_replace(' ', '_', $value);
		return $value;
	}
}
