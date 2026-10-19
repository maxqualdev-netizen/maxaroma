<?php

namespace App\Services;

use App\Services\Meili\Client;
use App\Support\EnvSecret;
use App\Support\SizeNormalizer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

/**
 * Grown Brilliance–style OpenAI NLP for Maxaroma perfume search.
 *
 * Env:
 *   OPENAI_NLP_ENABLED=true
 *   OPENAI_SEARCH_API_KEY=enc:...   (preferred) or plain sk-...
 *   Generate enc value: php artisan openai:encrypt-key "sk-..."
 *   OPENAI_NLP_MODEL=gpt-4.1-mini
 *   OPENAI_NLP_TIMEOUT=15
 *   OPENAI_NLP_CONNECT_TIMEOUT=3
 *   OPENAI_NLP_CIRCUIT_SECONDS=120
 *   OPENAI_NLP_CACHE_MINUTES=5   (skip OpenAI for same query; searchbar → PLP)
 *   OPENAI_NLP_SESSION_SECONDS=300
 *   MEILISEARCH_NLP_RANK_THRESHOLD=0.8   (top hit score; below → NLP / OpenAI)
 *
 * PLP flow: Meili ranking probe first; OpenAI runs only when probe says needs_nlp
 * (0 hits or score below threshold), unless an NLP session plan is replayed.
 */
class PerfumeNlpSearchService
{
	/** @var array<string, array{needs_nlp: bool, highest_rank: float, hit_count: int}> */
	private array $probeRequestCache = [];

	/** @var array<string, array{filters: array<string, mixed>|null, source: string}> */
	private array $openAiFiltersRequestCache = [];

	public function __construct(
		private Client $client,
		private CustomProductSearchService $productSearch,
		private ProductFilterCatalogService $catalog
	) {
	}

	public function enabled(): bool
	{
		$flag = config('global.OPENAI_NLP_ENABLED', env('OPENAI_NLP_ENABLED', false));
		if (is_bool($flag)) {
			return $flag;
		}
		return in_array(strtolower((string) $flag), ['1', 'true', 'yes', 'on'], true);
	}

	/**
	 * Run probe (+ OpenAI when needed) for an initial search page load.
	 *
	 * @return array{
	 *   applied: bool,
	 *   keyword: string,
	 *   filters: array<string, mixed>,
	 *   nlp: array<string, mixed>|null,
	 *   probe: array{needs_nlp: bool, highest_rank: float, hit_count: int}
	 * }
	 */
	public function resolveForSearchPage(string $normalizedKeyword): array
	{
		// Identity for cache/session = shopper wording (before Meili filler strip).
		$shopperQuery = trim($normalizedKeyword);
		$normalizedKeyword = $this->normalizeUtteranceForNlp($normalizedKeyword);
		// New/changed query must not reuse a previous NLP session plan.
		$this->forgetSessionIfQueryChanged($shopperQuery);
		$probe = $this->probeMeilisearchRanking($normalizedKeyword);
		$out = [
			'applied' => false,
			'keyword' => $normalizedKeyword,
			'filters' => [],
			'nlp' => null,
			'probe' => $probe,
		];

		if ($normalizedKeyword === '' && $shopperQuery === '') {
			return $out;
		}

		// Searchbar → PLP (same shopper query only): reuse session plan — no OpenAI.
		$sessionPlan = $this->planFromFreshSession($shopperQuery);
		if ($sessionPlan !== null) {
			$sessionFilters = is_array($sessionPlan['filters'] ?? null) ? $sessionPlan['filters'] : [];
			$sessionKeyword = trim((string) ($sessionPlan['keyword'] ?? ''));
			// Stale sort-only plans (e.g. "onam luxury" → empty q + priceHL) must not stick —
			// re-resolve so catalog brand fuzzy can apply.
			$staleSortOnly = $sessionKeyword === ''
				&& !empty($sessionFilters['sortby'])
				&& !$this->hasStructuredIntentFilters(array_diff_key($sessionFilters, [
					'sortby' => true,
					'_rank_query' => true,
					'_search_description' => true,
				]));
			$catalogBrand = $this->matchCatalogBrand($normalizedKeyword);
			$ratingIntent = $this->utteranceHasTopRatingIntent($normalizedKeyword);
			$staleMissingRating = $ratingIntent !== null
				&& empty($sessionFilters['rating_avg'])
				&& empty($sessionFilters['OnTopRated']);
			$staleFillerBrand = false;
			if (!empty($sessionFilters['brands']) && is_array($sessionFilters['brands'])) {
				foreach ($sessionFilters['brands'] as $encodedBrand) {
					$brandLabel = $this->productSearch->decodeFilterValue((string) $encodedBrand);
					$collapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($brandLabel)) ?? '';
					if ($this->isAmbiguousFillerBrandToken($collapsed)
						&& !$this->utteranceIsPrimarilyBrand($normalizedKeyword, $brandLabel)
					) {
						$staleFillerBrand = true;
						break;
					}
				}
			}
			$staleFalseRating = $ratingIntent === null
				&& (!empty($sessionFilters['rating_avg']) || !empty($sessionFilters['OnTopRated']));
			// Stale mood-copy plans for "date night" (description only) — must use date occasion facets.
			$staleDateNightMoodOnly = (bool) preg_match('/\bdate\s*nights?\b|\bdinner\s+dates?\b/iu', $normalizedKeyword)
				&& empty($sessionFilters['fragrance_occasion']);
			// Cached brand-only empty-q plans that Meili can't satisfy (UAT brand facet issues).
			$staleEmptyBrandBrowse = $sessionKeyword === ''
				&& !empty($sessionFilters['brands'])
				&& $this->planHitCount('', $sessionFilters) === 0;
			if ($staleSortOnly || $staleMissingRating || $staleFillerBrand || $staleFalseRating || $staleDateNightMoodOnly || $staleEmptyBrandBrowse || ($catalogBrand !== null && empty($sessionFilters['brands']))) {
				$sessionPlan = null;
			}
		}
		if ($sessionPlan !== null) {
			$extracted = is_array($sessionPlan['extracted'] ?? null) ? $sessionPlan['extracted'] : [];
			$filters = is_array($sessionPlan['filters'] ?? null) ? $sessionPlan['filters'] : [];
			$keyword = (string) ($sessionPlan['keyword'] ?? '');
			if (
				!empty($filters['_search_description'])
				&& $this->isDescriptionNoisyQualityUtterance(trim($normalizedKeyword . ' ' . $keyword))
			) {
				unset($filters['_search_description']);
			}
			$this->logNlpExtraction(
				$shopperQuery,
				'session',
				$extracted,
				$this->collectFragranceConcepts($extracted),
				$keyword,
				$filters
			);
			$out['applied'] = true;
			$out['keyword'] = $keyword;
			$out['filters'] = $filters;
			$out['filters']['_rank_query'] = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
			$out['nlp'] = $extracted !== [] ? $extracted : null;
			return $out;
		}

		// Brand browse without OpenAI: "roja" / "roja perfums" → brand facet when the
		// utterance is essentially that house (Meili free-text often ranks wrong houses first).
		$brandProbe = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
		$catalogBrand = $this->matchCatalogBrand($brandProbe);
		if ($catalogBrand !== null && $this->utteranceIsPrimarilyBrand($brandProbe, $catalogBrand)) {
			$extracted = ['brand' => $catalogBrand, 'query' => ''];
			$mapped = $this->toSearchFilters($extracted, $brandProbe);
			$mapped = $this->finalizeNlpPlan($mapped, $brandProbe, $extracted);
			$this->rememberSession($shopperQuery !== '' ? $shopperQuery : $normalizedKeyword, $extracted, $mapped['keyword'], $mapped['filters']);
			$this->logNlpExtraction(
				$shopperQuery !== '' ? $shopperQuery : $normalizedKeyword,
				'local-brand',
				$extracted,
				[],
				$mapped['keyword'],
				$mapped['filters']
			);
			$out['applied'] = true;
			$out['keyword'] = $mapped['keyword'];
			$out['filters'] = $mapped['filters'];
			$out['filters']['_rank_query'] = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
			$out['nlp'] = $extracted;
			return $out;
		}

		// Meili-first: OpenAI / full NLP remap only when the probe is weak
		// (0 hits or top score below MEILISEARCH_NLP_RANK_THRESHOLD).
		$wantsNlp = !empty($probe['needs_nlp']);
		if (!$wantsNlp) {
			return $out;
		}

		$cacheQuery = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
		$extracted = null;
		$nlpSource = 'local';
		if ($this->enabled()) {
			$cached = $this->getCachedFiltersResult($cacheQuery);
			$extracted = $cached['filters'];
			if (is_array($extracted) && $extracted !== []) {
				$nlpSource = $cached['source'] === 'openai' ? 'openai+local' : 'cache+local';
			}
		}
		$local = $this->localStructuralExtract($brandProbe);
		if (!is_array($extracted) || $extracted === []) {
			$extracted = $local;
			$nlpSource = 'local';
		} else {
			$extracted = $this->applyUtterancePriceOverrides($extracted, $brandProbe);
			foreach ($local as $key => $value) {
				if (!array_key_exists($key, $extracted) || $extracted[$key] === null || $extracted[$key] === '') {
					$extracted[$key] = $value;
				}
			}
			// Merge local + model scent concepts (backend resolves against catalog).
			$extracted = $this->mergeFragranceConcepts($extracted, $local);
			// Closed structural cues on the corrected utterance beat speculative LLM guesses.
			if (!empty($local['gender'])) {
				$extracted['gender'] = $local['gender'];
			}
			if (!empty($local['similar_to_product'])) {
				$extracted['similar_to_product'] = $local['similar_to_product'];
				if (!empty($local['price_constraint'])) {
					$extracted['price_constraint'] = $local['price_constraint'];
				}
				if (!empty($local['reference_variant'])) {
					$extracted['reference_variant'] = $local['reference_variant'];
				}
				if (!empty($local['_reference_size_ml'])) {
					$extracted['_reference_size_ml'] = $local['_reference_size_ml'];
				}
				// Brand only when local marked an explicit house constraint
				// ("Dior perfumes similar to Sauvage"), never from the reference title alone.
				if (!empty($local['_similar_keep_brand']) && !empty($local['brand'])) {
					$extracted['brand'] = $local['brand'];
				} else {
					unset($extracted['brand']);
				}
				// Similarity ranking > "cheaper" sort — price is enforced as a maxprice filter.
				if (($local['price_constraint'] ?? '') === 'lower_than_reference') {
					unset($extracted['SortBy']);
				}
			} elseif (!empty($local['brand'])) {
				$extracted['brand'] = $local['brand'];
				if ($this->utteranceIsPrimarilyBrand($brandProbe, (string) $local['brand'])) {
					unset($extracted['SortBy']);
				}
			}
			// LLM brand guesses that only share a scent adjective ("fresh" → Aura Fresh)
			// are stripped in dropAmbiguousFillerBrandGuesses / brandLabelJustifiedByUtterance.
			if (!empty($local['rating_avg']) || !empty($local['OnTopRated'])) {
				if (!empty($local['rating_avg'])) {
					$extracted['rating_avg'] = $local['rating_avg'];
				}
				if (!empty($local['OnTopRated'])) {
					$extracted['OnTopRated'] = true;
				}
				// Rating intent beats false brand="Best" from the model.
				unset($extracted['brand'], $extracted['SortBy']);
			}
			// Local price-sort only when this is not a resolved brand-name / similar-to query.
			if (
				!empty($local['SortBy'])
				&& empty($local['brand'])
				&& empty($local['rating_avg'])
				&& empty($local['similar_to_product'])
			) {
				$extracted['SortBy'] = $local['SortBy'];
			}
			$extracted = $this->dropUngroundedOccasionGuesses($extracted, $brandProbe);
		}
		$extracted = $this->dropAmbiguousFillerBrandGuesses($extracted, $brandProbe);
		$extracted = $this->dropUngroundedRatingGuesses($extracted, $brandProbe);
		$extracted = $this->applySceneMoodLocalOverrides($extracted, $brandProbe);
		if ($extracted === []) {
			return $out;
		}

		$conceptsBeforeResolve = $this->collectFragranceConcepts($extracted);
		$mapped = $this->toSearchFilters($extracted, $brandProbe);
		$mapped = $this->finalizeNlpPlan($mapped, $brandProbe, $extracted);
		$this->rememberSession($cacheQuery, $extracted, $mapped['keyword'], $mapped['filters']);
		$this->logNlpExtraction(
			$cacheQuery,
			$nlpSource,
			$extracted,
			$conceptsBeforeResolve,
			$mapped['keyword'],
			$mapped['filters']
		);

		$out['applied'] = true;
		$out['keyword'] = $mapped['keyword'];
		$out['filters'] = $mapped['filters'];
		$out['filters']['_rank_query'] = $cacheQuery;
		$out['nlp'] = $extracted;
		return $out;
	}

	/**
	 * Popup path: never call OpenAI or Meili ranking probe (those add hundreds of ms).
	 * Uses session / NLP cache / local brand match only so suggestions feel instant.
	 *
	 * @return array{
	 *   applied: bool,
	 *   keyword: string,
	 *   filters: array<string, mixed>,
	 *   nlp: array<string, mixed>|null,
	 *   probe: array{needs_nlp: bool, highest_rank: float, hit_count: int}|null
	 * }
	 */
	public function resolveForAutocomplete(string $normalizedKeyword): array
	{
		$shopperQuery = trim($normalizedKeyword);
		$normalizedKeyword = $this->normalizeUtteranceForNlp($normalizedKeyword);
		$this->forgetSessionIfQueryChanged($shopperQuery);
		$out = [
			'applied' => false,
			'keyword' => $normalizedKeyword,
			'filters' => [],
			'nlp' => null,
			'probe' => null,
		];
		if ($normalizedKeyword === '' && $shopperQuery === '') {
			return $out;
		}

		// Reuse a fresh PLP/session plan when the shopper already resolved this same query.
		$sessionPlan = $this->planFromFreshSession($shopperQuery);
		if ($sessionPlan !== null) {
			$sessionFilters = is_array($sessionPlan['filters'] ?? null) ? $sessionPlan['filters'] : [];
			$sessionKeyword = trim((string) ($sessionPlan['keyword'] ?? ''));
			$staleSortOnly = $sessionKeyword === ''
				&& !empty($sessionFilters['sortby'])
				&& !$this->hasStructuredIntentFilters(array_diff_key($sessionFilters, [
					'sortby' => true,
					'_rank_query' => true,
					'_search_description' => true,
				]));
			if (!$staleSortOnly) {
				$extracted = is_array($sessionPlan['extracted'] ?? null) ? $sessionPlan['extracted'] : [];
				$filters = $sessionFilters;
				if (
					!empty($filters['_search_description'])
					&& $this->isDescriptionNoisyQualityUtterance(trim($shopperQuery . ' ' . $sessionKeyword))
				) {
					unset($filters['_search_description']);
				}
				$out['applied'] = true;
				$out['keyword'] = $sessionKeyword;
				$out['filters'] = $filters;
				$out['filters']['_rank_query'] = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
				$out['nlp'] = $extracted !== [] ? $extracted : null;
				return $out;
			}
		}

		// Local brand browse (roja / roja perfums) — no OpenAI.
		$brandProbe = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
		$catalogBrand = $this->matchCatalogBrand($brandProbe);
		if ($catalogBrand !== null && $this->utteranceIsPrimarilyBrand($brandProbe, $catalogBrand)) {
			$extracted = ['brand' => $catalogBrand, 'query' => ''];
			$mapped = $this->toSearchFilters($extracted, $brandProbe);
			$mapped = $this->finalizeNlpPlan($mapped, $brandProbe, $extracted);
			$out['applied'] = true;
			$out['keyword'] = $mapped['keyword'];
			$out['filters'] = $mapped['filters'];
			$out['filters']['_rank_query'] = $brandProbe;
			$out['nlp'] = $extracted;
			return $out;
		}

		// Prior OpenAI/NLP cache only — never fetch on the popup path.
		$cached = $this->peekCachedFilters($brandProbe);
		if (is_array($cached) && $cached !== []) {
			$local = $this->localStructuralExtract($brandProbe);
			$extracted = $cached;
			foreach ($local as $key => $value) {
				if (!array_key_exists($key, $extracted) || $extracted[$key] === null || $extracted[$key] === '') {
					$extracted[$key] = $value;
				}
			}
			if (!empty($local['brand'])) {
				$extracted['brand'] = $local['brand'];
			}
			$extracted = $this->dropAmbiguousFillerBrandGuesses($extracted, $brandProbe);
			$extracted = $this->dropUngroundedRatingGuesses($extracted, $brandProbe);
			if ($extracted !== []) {
				$mapped = $this->toSearchFilters($extracted, $brandProbe);
				$mapped = $this->finalizeNlpPlan($mapped, $brandProbe, $extracted);
				$out['applied'] = true;
				$out['keyword'] = $mapped['keyword'];
				$out['filters'] = $mapped['filters'];
				$out['filters']['_rank_query'] = $brandProbe;
				$out['nlp'] = $extracted;
				return $out;
			}
		}

		return $out;
	}

	/**
	 * Read NLP cache only — never calls OpenAI (safe for autocomplete latency).
	 *
	 * @return array<string, mixed>|null
	 */
	public function peekCachedFilters(string $userQuery): ?array
	{
		$normalized = trim($userQuery);
		if ($normalized === '') {
			return null;
		}
		foreach ($this->nlpFiltersCacheKeyCandidates($normalized) as $cacheKey) {
			if (!Cache::has($cacheKey)) {
				continue;
			}
			$cached = Cache::get($cacheKey);
			if (!is_array($cached) || $cached === []) {
				continue;
			}
			return $this->applyUtterancePriceOverrides($cached, $normalized);
		}
		return null;
	}

	private function looksLikeNaturalLanguage(string $query): bool
	{
		$budget = $this->extractBudgetFromUtterance($query);
		if ($budget['min'] !== null || $budget['max'] !== null) {
			return true;
		}
		// Short queries with shopper filter cues ("men perfume", "floral for her").
		$structural = $this->localStructuralExtract($query);
		if ($structural !== []) {
			return true;
		}
		$tokens = preg_split('/\s+/u', strtolower(trim($query)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		// No static sight-word list — longer phrases invite OpenAI when available.
		return count($tokens) >= 4;
	}

	/**
	 * Merge session NLP filters into AJAX facet payload (user facets win).
	 *
	 * @param array<string, mixed> $filters
	 * @return array{keyword: string, filters: array<string, mixed>, from_session: bool}
	 */
	public function resolveForAjax(string $normalizedKeyword, array $filters, string $orgSearchKeyword = ''): array
	{
		$out = [
			'keyword' => $normalizedKeyword,
			'filters' => $filters,
			'from_session' => false,
		];

		if (!$this->enabled() || $normalizedKeyword === '') {
			return $out;
		}

		$shopperQuery = trim($orgSearchKeyword !== '' ? $orgSearchKeyword : $normalizedKeyword);
		$normalizedKeyword = $this->normalizeUtteranceForNlp($normalizedKeyword);
		$orgSearchKeyword = $orgSearchKeyword !== ''
			? $this->normalizeUtteranceForNlp($orgSearchKeyword)
			: '';

		// Facet AJAX for a different q must not keep the previous NLP filter plan.
		$this->forgetSessionIfQueryChanged($shopperQuery);

		$state = Session::get('nlp_search_filters');
		$matched = $this->sessionMatchesKeyword($state, $shopperQuery);
		if (!$matched && $orgSearchKeyword !== '') {
			$matched = $this->sessionMatchesKeyword($state, $orgSearchKeyword);
		}
		if (!$matched) {
			$matched = $this->sessionMatchesKeyword($state, $normalizedKeyword);
		}

		if ($matched && is_array($state)) {
			$sessionFilters = is_array($state['filters'] ?? null) ? $state['filters'] : [];
			// Applied keyword may be intentionally empty (filter-only NLP, e.g. candles under $100).
			// Never fall back to the raw URL utterance or matchingStrategy=all returns 0 hits.
			$out['keyword'] = trim((string) ($state['applied_keyword'] ?? ''));
			$out['filters'] = $this->mergeNlpFiltersPreserveUser($sessionFilters, $filters);
			$out['filters']['_rank_query'] = (string) ($state['org_keyword'] ?? $shopperQuery);
			$out['from_session'] = true;
			if ($out['keyword'] === '' && $this->shouldDefaultEmptyKeywordToPerfume($out['filters'], $normalizedKeyword)) {
				$out['keyword'] = 'perfume';
			}
			// Stale sessions may still carry search_description:true for longevity queries.
			$descProbe = trim(((string) ($state['org_keyword'] ?? '')) . ' ' . $out['keyword'] . ' ' . $normalizedKeyword);
			if (!empty($out['filters']['_search_description']) && $this->isDescriptionNoisyQualityUtterance($descProbe)) {
				unset($out['filters']['_search_description']);
			}
			return $out;
		}

		// First AJAX for this keyword with no session: same probe+OpenAI path as page load.
		if ($this->probeNeedsNlp($normalizedKeyword)) {
			$cached = $this->getCachedFiltersResult($shopperQuery !== '' ? $shopperQuery : $normalizedKeyword);
			$extracted = $cached['filters'];
			if (is_array($extracted) && $extracted !== []) {
				$conceptsBeforeResolve = $this->collectFragranceConcepts($extracted);
				$mapped = $this->toSearchFilters($extracted, $normalizedKeyword);
				$mapped = $this->finalizeNlpPlan($mapped, $normalizedKeyword, $extracted);
				$this->rememberSession($shopperQuery !== '' ? $shopperQuery : $normalizedKeyword, $extracted, $mapped['keyword'], $mapped['filters']);
				$this->logNlpExtraction(
					$shopperQuery !== '' ? $shopperQuery : $normalizedKeyword,
					$cached['source'] === 'openai' ? 'openai-ajax' : 'cache-ajax',
					$extracted,
					$conceptsBeforeResolve,
					$mapped['keyword'],
					$mapped['filters']
				);
				$out['filters'] = $this->mergeNlpFiltersPreserveUser($mapped['filters'], $filters);
				$out['filters']['_rank_query'] = $shopperQuery !== '' ? $shopperQuery : $normalizedKeyword;
				$out['keyword'] = (string) ($mapped['keyword'] ?? '');
				$out['from_session'] = true;
			}
		}

		return $out;
	}

	public function probeNeedsNlp(string $normalizedKeyword): bool
	{
		return $this->probeMeilisearchRanking($normalizedKeyword)['needs_nlp'];
	}

	/**
	 * @return array{needs_nlp: bool, highest_rank: float, hit_count: int}
	 */
	public function probeMeilisearchRanking(string $normalizedKeyword): array
	{
		$normalizedKeyword = trim($normalizedKeyword);
		if ($normalizedKeyword === '') {
			return ['needs_nlp' => false, 'highest_rank' => 0.0, 'hit_count' => 0];
		}
		if (isset($this->probeRequestCache[$normalizedKeyword])) {
			return $this->probeRequestCache[$normalizedKeyword];
		}

		$sessionProbe = $this->getSessionRankingProbe($normalizedKeyword);
		if ($sessionProbe !== null) {
			$this->probeRequestCache[$normalizedKeyword] = $sessionProbe;
			return $sessionProbe;
		}

		$cacheKey = 'meili_rank_probe:' . md5($normalizedKeyword);
		$result = Cache::remember($cacheKey, now()->addMinutes(2), function () use ($normalizedKeyword) {
			try {
				$index = $this->client->index($this->productSearch->indexName());
				$response = $index->search($normalizedKeyword, [
					'limit' => 5,
					'matchingStrategy' => 'all',
					'showRankingScore' => true,
					// Keep probe attrs conservative — invalid attributesToSearchOn makes Meili error
					// and previously short-circuited NLP (needs_nlp=false).
					'attributesToSearchOn' => ['name', 'brand', 'sku', 'category'],
				]);
				$hits = method_exists($response, 'getHits') ? $response->getHits() : [];
				$highestRank = 0.0;
				if (count($hits) > 0) {
					$highestRank = (float) ($hits[0]['_rankingScore'] ?? 0);
				}
				$hitCount = count($hits);
				return [
					'needs_nlp' => ($hitCount === 0 || $highestRank < $this->rankThreshold()),
					'highest_rank' => $highestRank,
					'hit_count' => $hitCount,
				];
			} catch (Throwable $e) {
				Log::warning('Meilisearch NLP ranking probe failed: ' . $e->getMessage(), [
					'keyword' => $normalizedKeyword,
				]);
				// Probe failure / 0 visibility → still try NLP (do not block OpenAI).
				return ['needs_nlp' => true, 'highest_rank' => 0.0, 'hit_count' => 0, 'probe_error' => $e->getMessage()];
			}
		});

		if (!is_array($result)) {
			$result = ['needs_nlp' => true, 'highest_rank' => 0.0, 'hit_count' => 0];
		}
		// Cached legacy "0 hits but needs_nlp false" entries (from older bug) — force NLP.
		if ((int) ($result['hit_count'] ?? 0) === 0) {
			$result['needs_nlp'] = true;
		}

		$this->rememberSessionRankingProbe($normalizedKeyword, $result);
		$this->probeRequestCache[$normalizedKeyword] = $result;
		return $result;
	}

	/**
	 * @return array<string, mixed>|null null = OpenAI unavailable / circuit open
	 */
	public function getCachedFilters(string $userQuery): ?array
	{
		return $this->getCachedFiltersResult($userQuery)['filters'];
	}

	/**
	 * Shared OpenAI extract cache (searchbar + PLP + AJAX).
	 * Same key as gbproduction: nlp_search_filters:{md5(normalized)}.
	 *
	 * @return array{filters: array<string, mixed>|null, source: string}
	 */
	private function getCachedFiltersResult(string $userQuery): array
	{
		$normalized = trim($userQuery);
		if ($normalized === '') {
			return ['filters' => [], 'source' => 'empty'];
		}

		$memoKey = $this->nlpCacheKeySource($normalized);
		if (isset($this->openAiFiltersRequestCache[$memoKey])) {
			return $this->openAiFiltersRequestCache[$memoKey];
		}

		$primaryKey = $this->nlpFiltersCacheKey($normalized);
		foreach ($this->nlpFiltersCacheKeyCandidates($normalized) as $cacheKey) {
			if (!Cache::has($cacheKey)) {
				continue;
			}
			$cached = Cache::get($cacheKey);
			$cached = is_array($cached) ? $cached : [];
			$cached = $this->applyUtterancePriceOverrides($cached, $normalized);
			// Migrate legacy / case-variant keys onto the stable primary key.
			if ($cacheKey !== $primaryKey && $cached !== []) {
				Cache::put($primaryKey, $cached, $this->nlpFiltersCacheTtl());
			}
			$result = ['filters' => $cached, 'source' => 'cache'];
			$this->openAiFiltersRequestCache[$memoKey] = $result;
			return $result;
		}

		if ($this->isOpenAiCircuitOpen()) {
			$result = ['filters' => null, 'source' => 'circuit'];
			$this->openAiFiltersRequestCache[$memoKey] = $result;
			return $result;
		}

		// Searchbar + PLP often race; only one OpenAI call should win.
		$lock = Cache::lock('nlp_openai_fetch:' . md5($memoKey), 20);
		try {
			$lock->block(15);

			// Re-check after waiting — the other request may have filled cache.
			foreach ($this->nlpFiltersCacheKeyCandidates($normalized) as $cacheKey) {
				if (!Cache::has($cacheKey)) {
					continue;
				}
				$cached = Cache::get($cacheKey);
				$cached = is_array($cached) ? $cached : [];
				$cached = $this->applyUtterancePriceOverrides($cached, $normalized);
				if ($cacheKey !== $primaryKey && $cached !== []) {
					Cache::put($primaryKey, $cached, $this->nlpFiltersCacheTtl());
				}
				$result = ['filters' => $cached, 'source' => 'cache'];
				$this->openAiFiltersRequestCache[$memoKey] = $result;
				return $result;
			}

			if ($this->isOpenAiCircuitOpen()) {
				$result = ['filters' => null, 'source' => 'circuit'];
				$this->openAiFiltersRequestCache[$memoKey] = $result;
				return $result;
			}

			$extracted = $this->fetchFromOpenAi($this->normalizeUtteranceForNlp($normalized));
			if ($extracted === null) {
				$result = ['filters' => null, 'source' => 'openai-failed'];
				$this->openAiFiltersRequestCache[$memoKey] = $result;
				return $result;
			}
			$extracted = $this->applyUtterancePriceOverrides($extracted, $normalized);
			Cache::put($primaryKey, $extracted, $this->nlpFiltersCacheTtl());
			$result = ['filters' => $extracted, 'source' => 'openai'];
			$this->openAiFiltersRequestCache[$memoKey] = $result;
			return $result;
		} catch (Throwable $e) {
			Log::warning('OpenAI NLP cache lock skipped', ['error' => $e->getMessage()]);
			$extracted = $this->fetchFromOpenAi($this->normalizeUtteranceForNlp($normalized));
			if ($extracted === null) {
				$result = ['filters' => null, 'source' => 'openai-failed'];
				$this->openAiFiltersRequestCache[$memoKey] = $result;
				return $result;
			}
			$extracted = $this->applyUtterancePriceOverrides($extracted, $normalized);
			Cache::put($primaryKey, $extracted, $this->nlpFiltersCacheTtl());
			$result = ['filters' => $extracted, 'source' => 'openai'];
			$this->openAiFiltersRequestCache[$memoKey] = $result;
			return $result;
		} finally {
			optional($lock)->release();
		}
	}

	/**
	 * Query identity for NLP cache/session — must change when the shopper changes wording.
	 * Plural-fold only (perfume≈perfumes). Do NOT strip "I want" etc. or different
	 * utterances collapse onto the same cache entry.
	 */
	private function nlpCacheKeySource(string $userQuery): string
	{
		$q = trim($userQuery);
		if ($q === '') {
			return '';
		}
		$q = $this->normalizeQuerySeparators($q);
		$q = $this->correctStructuralTyposInUtterance($q);
		$q = $this->expandPriceShorthandInQuery($q);
		$q = preg_replace('/\s+/u', ' ', $q) ?? $q;
		$q = $this->productSearch->normalizeQueryIdentity($q);
		return mb_strtolower(trim($q));
	}

	private function nlpFiltersCacheKey(string $userQuery): string
	{
		// v4: cache key follows full shopper wording (not Meili-simplified q).
		return 'nlp_search_filters:v4:' . md5($this->nlpCacheKeySource($userQuery));
	}

	/**
	 * Primary key only (do not resurrect pre-v2 extracts that missed rating/brand rules).
	 *
	 * @return list<string>
	 */
	private function nlpFiltersCacheKeyCandidates(string $userQuery): array
	{
		return [$this->nlpFiltersCacheKey(trim($userQuery))];
	}

	private function nlpFiltersCacheTtl(): \DateTimeInterface
	{
		// gbproduction OpenAI filter cache is 7 days; Maxaroma default 5 min (override via env).
		$minutes = (int) env('OPENAI_NLP_CACHE_MINUTES', 5);
		if ($minutes <= 0) {
			return now()->addDays(7);
		}
		return now()->addMinutes(max(1, $minutes));
	}

	/**
	 * Map OpenAI JSON into Maxaroma listing filter keys + cleaned keyword.
	 *
	 * @param array<string, mixed> $nlp
	 * @return array{keyword: string, filters: array<string, mixed>}
	 */
	public function toSearchFilters(array $nlp, string $fallbackKeyword = ''): array
	{
		$filters = [];
		$orphanFacetKeywords = [];
		$nlp = $this->canonicalizeBadgeIntent($nlp, $fallbackKeyword);

		// AI / local scent concepts (any tokens NLP extracted — not a closed word list).
		// Keep them as fragrance_family_contains for Meili LIKE-style matching when catalog
		// coverage is strong; always also keep them in the search keyword via finalizeNlpPlan.
		$concepts = $this->collectFragranceConcepts($nlp);
		if ($concepts !== []) {
			$filters['fragrance_family_contains'] = $concepts;
			unset($nlp['fragrance_family'], $nlp['fragrance_concepts']);
		}

		$facetMap = [
			'brand' => 'brands',
			'category' => 'categories',
			'fragrance_family' => 'fragrance_family',
			'type' => 'vtype',
			'vtype' => 'vtype',
			'top_note' => 'fragrance_notes',
			'middle_note' => 'fragrance_notes',
			'base_note' => 'fragrance_notes',
			'fragrance_notes' => 'fragrance_notes',
			'notes' => 'fragrance_notes',
			'seasons' => 'fragrance_seasons',
			'fragrance_seasons' => 'fragrance_seasons',
			'occasion' => 'fragrance_occasion',
			'fragrance_occasion' => 'fragrance_occasion',
			'personality' => 'fragrance_personality',
			'fragrance_personality' => 'fragrance_personality',
			'size' => 'size',
			'formulation' => 'formulation',
			'features' => 'features',
			'by_feature' => 'features',
			'badges' => 'special',
			'special' => 'special',
			'coverage' => 'coverage',
			'finish' => 'finish',
			'skin_type' => 'skin_type',
		];

		foreach ($facetMap as $nlpKey => $filterKey) {
			if (!array_key_exists($nlpKey, $nlp)) {
				continue;
			}
			$values = $this->normalizeToEncodedList($nlp[$nlpKey]);
			if ($values === []) {
				continue;
			}
			if ($filterKey === 'fragrance_occasion') {
				$values = $this->expandOccasionNlpSynonyms($values);
			} elseif ($filterKey === 'fragrance_seasons') {
				$values = $this->expandSeasonNlpSynonyms($values);
			} elseif ($filterKey === 'fragrance_family') {
				$values = $this->expandFamilyNlpSynonyms($values);
				// Families are already resolved against catalog when concepts were present.
				// Still expand soft synonyms / exact facet matches below.
			}
			$meiliAttr = $this->filterKeyToMeiliAttribute($filterKey);
			$intentValues = $values;
			$expanded = $this->expandEncodedFacetValues($meiliAttr, $values);
			if ($filterKey === 'fragrance_seasons' && $expanded !== []) {
				$expanded = $this->filterExpandedSeasonsByIntent($intentValues, $expanded);
			}
			if ($filterKey === 'fragrance_family' && $expanded !== []) {
				// Drop the raw unresolved concept label if it was kept as a placeholder;
				// keep only real catalog facet strings (+ encoded originals that match).
				$expanded = $this->preferResolvedCatalogFamilyValues($expanded, $meiliAttr);
				// Never OR on comma-compounds — use atomic labels ("Amber Woody") only.
				$expanded = $this->keepAtomicFamilyValuesOnly($expanded);
			}
			if ($filterKey === 'fragrance_notes' && $expanded !== []) {
				// Do not select every "Vanilla and Musk" compound in the sidebar/chips.
				// Keep only the shopper's note tokens; soft-match compounds via CONTAINS.
				$expanded = $this->preferAtomicNoteIntentValues($expanded, $intentValues);
				$noteContains = [];
				foreach ($intentValues as $enc) {
					$raw = strtolower(trim($this->productSearch->decodeFilterValue((string) $enc)));
					if ($raw !== '' && mb_strlen($raw) >= 3) {
						$noteContains[] = $raw;
					}
				}
				if ($noteContains !== []) {
					$filters['fragrance_notes_contains'] = array_values(array_unique(array_merge(
						is_array($filters['fragrance_notes_contains'] ?? null) ? $filters['fragrance_notes_contains'] : [],
						$noteContains
					)));
				}
			}
			if ($expanded === []) {
				// Unmatched facet labels must not become empty filters (0 hits).
				// Keep them as searchable text instead.
				foreach ($values as $encoded) {
					$raw = trim($this->productSearch->decodeFilterValue((string) $encoded));
					if ($raw !== '') {
						$orphanFacetKeywords[] = $raw;
					}
				}
				continue;
			}
			// Merge when several NLP keys map to one filter (e.g. top/middle/base → fragrance_notes).
			if (!empty($filters[$filterKey]) && is_array($filters[$filterKey])) {
				$filters[$filterKey] = array_values(array_unique(array_merge($filters[$filterKey], $expanded)));
			} else {
				$filters[$filterKey] = $expanded;
			}
		}

		// LLM asks to scan marketing/description copy (mood, scene phrases, …).
		// Never for longevity/quality phrases — those words flood description and inflate totals
		// (~808 vs ~298 SearchSpring for "long lasting perfumes").
		if (
			$this->nlpWantsDescriptionSearch($nlp)
			&& !$this->isDescriptionNoisyQualityUtterance($fallbackKeyword)
		) {
			$filters['_search_description'] = true;
		}

		// Gender cues → real Meili gender codes (M/W/U), not Type/vtype (EDT/EDP).
		if (empty($filters['gender']) && isset($nlp['gender'])) {
			$genderValues = $this->normalizeToEncodedList($nlp['gender']);
			$mapped = [];
			foreach ($genderValues as $g) {
				$raw = strtolower($this->productSearch->decodeFilterValue($g));
				if (in_array($raw, ['women', 'woman', 'her', 'female', 'ladies', 'w'], true)) {
					array_push($mapped, 'W', 'U');
				} elseif (in_array($raw, ['men', 'man', 'him', 'male', 'guys', 'm'], true)) {
					array_push($mapped, 'M', 'U');
				} elseif (in_array($raw, ['unisex', 'u'], true)) {
					$mapped[] = 'U';
				}
			}
			$mapped = array_values(array_unique($mapped));
			if ($mapped !== []) {
				$filters['gender'] = $mapped;
			}
		}

		// "women fragrance" must not keep Fragrance : Men* chips — parent "Fragrance"
		// expands to every leaf; trim opposite-gender category leaves when gender is set.
		if (!empty($filters['categories']) && is_array($filters['categories']) && !empty($filters['gender'])) {
			$filters['categories'] = $this->filterCategoriesByGenderIntent(
				$filters['categories'],
				$filters['gender']
			);
			if ($filters['categories'] === []) {
				unset($filters['categories']);
			}
		}

		$minPrice = $nlp['min_price'] ?? $nlp['minprice'] ?? null;
		$maxPrice = $nlp['max_price'] ?? $nlp['maxprice'] ?? null;
		$budget = $this->extractBudgetFromUtterance($fallbackKeyword);
		if ($budget['min'] !== null) {
			$minPrice = $budget['min'];
		}
		if ($budget['max'] !== null) {
			$maxPrice = $budget['max'];
		}
		if (is_numeric($minPrice) || is_numeric($maxPrice)) {
			$filters['ochangeprice'] = '1';
			if (is_numeric($minPrice) && (float) $minPrice > 0) {
				$filters['minprice'] = (string) (float) $minPrice;
			}
			if (is_numeric($maxPrice) && (float) $maxPrice > 0) {
				$filters['maxprice'] = (string) (float) $maxPrice;
			}
		}

		if (array_key_exists('in_stock', $nlp)) {
			$inStock = $nlp['in_stock'];
			$truthy = $inStock === true || $inStock === 1 || $inStock === '1'
				|| (is_string($inStock) && in_array(strtolower($inStock), ['yes', 'true', 'in_stock'], true));
			if ($truthy) {
				$filters['InStockItems'] = true;
			}
		}

		// Top / best / highest rated → Star Rating bucket + Top Rated sort.
		// Accept any LLM phrasing that lands in these fields (schema-validated in toSearchFilters).
		$ratingBucket = null;
		foreach (['rating_avg', 'star_rating', 'stars', 'StarRating'] as $ratingKey) {
			if (!array_key_exists($ratingKey, $nlp)) {
				continue;
			}
			$rawRating = $nlp[$ratingKey];
			if (is_array($rawRating)) {
				$rawRating = $rawRating[0] ?? '';
			}
			$bucket = (int) preg_replace('/\D+/', '', (string) $rawRating);
			if (in_array($bucket, [3, 4, 5], true)) {
				$ratingBucket = (string) $bucket;
				break;
			}
		}
		$wantsTopRated = false;
		foreach (['OnTopRated', 'top_rated', 'TopRated', 'topRated'] as $trKey) {
			if (!array_key_exists($trKey, $nlp)) {
				continue;
			}
			$tr = $nlp[$trKey];
			$wantsTopRated = $tr === true || $tr === 1 || $tr === '1'
				|| (is_string($tr) && in_array(strtolower($tr), ['yes', 'true', 'on'], true));
			if ($wantsTopRated) {
				break;
			}
		}
		// LLM may put rating intent in SortBy as RatingDesc / TopRated.
		$sortByProbe = $nlp['SortBy'] ?? $nlp['sortby'] ?? null;
		if (is_string($sortByProbe) && preg_match('/rating|top.?rated/i', $sortByProbe)) {
			$wantsTopRated = true;
		}
		if ($ratingBucket === null && $wantsTopRated) {
			$ratingBucket = '5';
		}
		if ($ratingBucket !== null) {
			$filters['rating_avg'] = [$ratingBucket];
			$filters['OnTopRated'] = true;
			unset($filters['brands']); // never keep false "Best" brand with rating intent
		} elseif ($wantsTopRated) {
			$filters['OnTopRated'] = true;
		}

		$sortBy = $nlp['SortBy'] ?? $nlp['sortby'] ?? null;
		if (is_string($sortBy) && preg_match('/rating|top.?rated/i', $sortBy)) {
			$sortBy = null; // handled via OnTopRated → rating_avg:desc
		}
		if (is_string($sortBy) && $sortBy !== '') {
			$sortMap = [
				'PriceAsc' => 'priceLH',
				'priceAsc' => 'priceLH',
				'PriceDesc' => 'priceHL',
				'priceDesc' => 'priceHL',
				'priceLH' => 'priceLH',
				'priceHL' => 'priceHL',
			];
			if (isset($sortMap[$sortBy])) {
				$filters['sortby'] = $sortMap[$sortBy];
			}
		}

		$keyword = '';
		if (isset($nlp['query']) && is_scalar($nlp['query'])) {
			$keyword = trim((string) $nlp['query']);
		}

		// Trust the LLM: when it omitted query and filters already express intent,
		// do NOT rebuild q from the raw NL utterance (that re-injects gender/price words
		// and breaks matchingStrategy=all). Only derive text when there are no filters.
		if ($keyword !== '') {
			if (strcasecmp($keyword, trim($fallbackKeyword)) === 0) {
				$keyword = $this->contentKeywordAfterFilters($fallbackKeyword, $filters);
			} else {
				// Strip any filter-covered tokens the model left inside query.
				$keyword = $this->contentKeywordAfterFilters($keyword, $filters);
			}
		} elseif ($fallbackKeyword !== '' && !$this->hasStructuredIntentFilters($filters)) {
			$keyword = $this->contentKeywordAfterFilters($fallbackKeyword, $filters);
		}

		// Generic product-type words under active attribute filters over-constrain
		// matchingStrategy=all when the same idea is already a facet (e.g. family=Citrus
		// plus q="citrus" intersects to near-zero). Prefer filter-only q.
		if ($this->hasProductAttributeFilters($filters) || !empty($filters['fragrance_occasion'])) {
			if ($this->isGenericCategoryKeyword($keyword) || $this->keywordRedundantWithFilters($keyword, $nlp, $filters)) {
				$keyword = '';
			}
		}
		if ($orphanFacetKeywords !== []) {
			$orphanText = implode(' ', array_values(array_unique($orphanFacetKeywords)));
			$keyword = trim($orphanText . ($keyword !== '' ? ' ' . $keyword : ''));
		}

		// Drop broad category catch-alls when product-attribute facets already express intent.
		// Keep category when it is the product class (Gift Sets, Pocket Perfume, Candles, …)
		// even if occasion/gender/price also apply.
		if (!empty($filters['categories']) && $this->hasProductAttributeFilters($filters)) {
			unset($filters['categories']);
		}
		if (
			!empty($filters['categories'])
			&& ($keyword === '' || $this->isGenericCategoryKeyword($keyword))
		) {
			$fromCat = $this->keywordFromCategoryFilters($filters['categories']);
			if ($fromCat !== '') {
				$keyword = $fromCat;
			}
		}

		// Drop facet keys that expanded to nothing (invalid NLP labels).
		foreach ($filters as $key => $value) {
			if (is_array($value) && $value === []) {
				unset($filters[$key]);
			}
		}

		return [
			'keyword' => $keyword,
			'filters' => $filters,
		];
	}

	/**
	 * Trust LLM filters. Only scrub leftover q when it over-constrains or equals the raw utterance.
	 * On absolute zero hits from AND-ed facets, drop the rarest conflicting facet first
	 * (general catalog conflict resolution — not phrase-specific).
	 *
	 * @param array{keyword: string, filters: array<string, mixed>} $mapped
	 * @param array<string, mixed> $extracted
	 * @return array{keyword: string, filters: array<string, mixed>}
	 */
	private function finalizeNlpPlan(array $mapped, string $utterance, array $extracted): array
	{
		$keyword = trim((string) ($mapped['keyword'] ?? ''));
		$filters = is_array($mapped['filters'] ?? null) ? $mapped['filters'] : [];

		if ($keyword !== '' && strcasecmp($keyword, trim($utterance)) === 0) {
			$keyword = $this->contentKeywordAfterFilters($utterance, $filters);
		} elseif ($keyword === '' && !$this->hasStructuredIntentFilters($filters)) {
			$keyword = $this->contentKeywordAfterFilters($utterance, $filters);
		}
		if ($this->hasProductAttributeFilters($filters) || !empty($filters['fragrance_occasion'])) {
			if ($this->isGenericCategoryKeyword($keyword) || $this->keywordRedundantWithFilters($keyword, $extracted, $filters)) {
				$keyword = '';
			}
		}
		// Leftover q that is only a fragment of an applied category label (e.g. "set" from Gift Sets).
		if ($keyword !== '' && !empty($filters['categories'])) {
			$catLabel = strtolower($this->keywordFromCategoryFilters($filters['categories']));
			$kw = strtolower(trim($keyword));
			if ($catLabel !== '') {
				if ($kw === $catLabel) {
					$keyword = '';
				} else {
					foreach (preg_split('/\s+/u', $catLabel) ?: [] as $part) {
						$part = trim($part);
						if ($part === '') {
							continue;
						}
						if ($kw === $part || $kw . 's' === $part || rtrim($part, 's') === $kw) {
							$keyword = '';
							break;
						}
					}
				}
			}
		}

		// matchingStrategy=all: if leftover q yields 0 but filters alone match, drop q only.
		if ($keyword !== '' && $this->hasStructuredIntentFilters($filters)) {
			$withKw = $this->planHitCount($keyword, $filters);
			if ($withKw === 0 && $this->planHitCount('', $filters) > 0) {
				$keyword = '';
			}
		}

		if ($this->planHitCount($keyword, $filters) === 0) {
			$this->resolveZeroHitFilterConflicts($keyword, $filters);
		}

		// Brand-only browse (empty q + brands facet): SearchSpring brand pages (/smid-…) still
		// work via designerid, but Meili brand filters can return 0 on UAT (not filterable /
		// label mismatch). Fall back to textual brand search so "Xerjoff" is not empty.
		if (
			$this->planHitCount($keyword, $filters) === 0
			&& !empty($filters['brands'])
			&& is_array($filters['brands'])
		) {
			$brandText = trim($utterance);
			if ($brandText === '') {
				$brandText = trim($this->productSearch->decodeFilterValue((string) ($filters['brands'][0] ?? '')));
			}
			if ($brandText !== '') {
				$textFilters = $filters;
				unset($textFilters['brands']);
				if ($this->planHitCount($brandText, $textFilters) > 0) {
					Log::info('NLP brand facet empty; falling back to text search', [
						'utterance' => $utterance,
						'brand_facet' => $filters['brands'],
						'keyword' => $brandText,
					]);
					unset($filters['brands']);
					$keyword = $brandText;
				} elseif ($this->planHitCount($brandText, $filters) > 0) {
					$keyword = $brandText;
				} else {
					// Probe may itself fail (invalid filterable attr) — still prefer text q.
					Log::info('NLP brand facet empty; forcing text search fallback', [
						'utterance' => $utterance,
						'brand_facet' => $filters['brands'],
						'keyword' => $brandText,
					]);
					unset($filters['brands']);
					$keyword = $brandText;
				}
			}
		}

		// Gift / budget shopper phrases often leave q empty (filters-only). Empty q browses
		// the whole index and star/price counts look stuck near the fetch cap (500).
		// Prefer a product-class seed so Meili relevance + facets stay meaningful.
		if ($keyword === '' && $this->shouldDefaultEmptyKeywordToPerfume($filters, $utterance)) {
			$keyword = 'perfume';
		}

		// Scent concepts (oud, citrus, …) must remain searchable text so name hits survive.
		// Keep shopper phrasing like "long lasting" together with the concept.
		$keyword = $this->ensureFragranceConceptsInKeyword($keyword, $filters, $utterance);

		// Hard family CONTAINS only when the catalog has a rich facet match (e.g. citrus).
		// Name-heavy notes like oud often live in product titles, not fragrance_family — skip hard filter.
		$filters = $this->maybeDropWeakFragranceFamilyContains($filters, $keyword);

		// Drop description scan for longevity/quality even if a cached LLM plan set it true.
		if (
			!empty($filters['_search_description'])
			&& $this->isDescriptionNoisyQualityUtterance(trim($utterance . ' ' . $keyword))
		) {
			unset($filters['_search_description']);
		}

		$mapped = ['keyword' => $keyword, 'filters' => $filters];
		return $this->applySimilarToProductPlan($mapped, $utterance, $extracted);
	}

	/**
	 * Keep scent concepts in q. Prefer shopper utterance order when merging
	 * (e.g. "long lasting oud", not only "oud").
	 *
	 * @param array<string, mixed> $filters
	 */
	private function ensureFragranceConceptsInKeyword(string $keyword, array $filters, string $utterance = ''): string
	{
		$concepts = [];
		if (!empty($filters['fragrance_family_contains']) && is_array($filters['fragrance_family_contains'])) {
			foreach ($filters['fragrance_family_contains'] as $c) {
				if (is_scalar($c) && trim((string) $c) !== '') {
					$concepts[] = strtolower(trim((string) $c));
				}
			}
		}
		if ($concepts === []) {
			return $keyword;
		}

		$kw = strtolower(trim($keyword));
		$missing = [];
		foreach ($concepts as $concept) {
			if ($concept === '' || mb_strlen($concept) < 3) {
				continue;
			}
			if ($kw === '' || !preg_match('/\b' . preg_quote($concept, '/') . '\b/u', $kw)) {
				$missing[] = $concept;
			}
		}

		// Prefer a short utterance remnant that keeps modifiers with concepts
		// (e.g. "long lasting oud") — never dump a long NL leftover into q.
		$u = strtolower(trim($utterance));
		if ($u !== '') {
			$fromUtterance = $this->contentKeywordAfterFilters($utterance, $filters);
			$fromTokens = preg_split('/\s+/u', strtolower(trim($fromUtterance)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
			$hasAllConcepts = true;
			foreach ($concepts as $concept) {
				if ($fromUtterance === '' || !preg_match('/\b' . preg_quote($concept, '/') . '\b/u', strtolower($fromUtterance))) {
					$hasAllConcepts = false;
					break;
				}
			}
			$maxTokens = max(3, count($concepts) + 2);
			if ($fromUtterance !== '' && $hasAllConcepts && count($fromTokens) <= $maxTokens) {
				return $fromUtterance;
			}
		}

		if ($missing === []) {
			return trim($keyword);
		}
		// Keep LLM/product-class q (e.g. perfume) and only append missing scent tokens.
		return trim(($keyword !== '' ? $keyword . ' ' : '') . implode(' ', $missing));
	}

	/**
	 * Drop hard fragrance_family_contains only when NO extracted concept has useful
	 * catalog coverage. Check each concept alone (e.g. "citrus" is strong even when
	 * paired with "fresh", which must not collapse coverage to the few dual labels).
	 *
	 * @param array<string, mixed> $filters
	 * @return array<string, mixed>
	 */
	private function maybeDropWeakFragranceFamilyContains(array $filters, string $keyword): array
	{
		if (empty($filters['fragrance_family_contains']) || !is_array($filters['fragrance_family_contains'])) {
			return $filters;
		}
		$concepts = [];
		foreach ($filters['fragrance_family_contains'] as $c) {
			if (is_scalar($c) && trim((string) $c) !== '') {
				$concepts[] = strtolower(trim((string) $c));
			}
		}
		$concepts = array_values(array_unique($concepts));
		if ($concepts === []) {
			unset($filters['fragrance_family_contains']);
			return $filters;
		}

		$bestCoverage = 0;
		foreach ($concepts as $concept) {
			$bestCoverage = max(
				$bestCoverage,
				count($this->resolveFragranceConceptsToCatalogValues([$concept]))
			);
		}
		// Weak catalog facet coverage → keyword-only (name matches still work).
		if ($bestCoverage < 5) {
			unset($filters['fragrance_family_contains']);
		}

		return $filters;
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function shouldDefaultEmptyKeywordToPerfume(array $filters, string $utterance): bool
	{
		if (!$this->hasStructuredIntentFilters($filters)) {
			return false;
		}
		// Already scoped to a concrete category (Gift Sets, Candles, …).
		if (!empty($filters['categories'])) {
			return false;
		}
		$u = strtolower(trim($utterance));
		if ($u === '') {
			return !empty($filters['gender']) || isset($filters['maxprice']) || isset($filters['minprice']);
		}
		if (preg_match('/\b(candle|candles|gift\s*sets?|skincare|makeup|hair)\b/u', $u)) {
			return false;
		}
		// Relationship / gift / budget utterances with gender or price only.
		return (bool) preg_match(
			'/\b(gift|present|wife|husband|girlfriend|boyfriend|mom|mother|dad|father|under|below|less\s+than|\$\s*\d+)\b/u',
			$u
		) || (!empty($filters['gender']) && (isset($filters['maxprice']) || isset($filters['minprice'])));
	}

	/**
	 * When several facets AND to zero, drop the rarest droppable facet until hits appear.
	 * Hard intents (gender / price / season / brand / stock / sort) are never dropped.
	 *
	 * @param array<string, mixed> $filters
	 */
	private function resolveZeroHitFilterConflicts(string &$keyword, array &$filters): void
	{
		$hardKeys = [
			'gender', 'minprice', 'maxprice', 'ochangeprice', 'InStockItems', 'brands', 'sortby',
			'fragrance_seasons',
		];
		$droppableOrder = [
			'fragrance_personality',
			'top_note',
			'middle_note',
			'base_note',
			'fragrance_notes',
			'features',
			'formulation',
			'coverage',
			'finish',
			'skin_type',
			'special',
			'size',
			'fragrance_occasion',
			'vtype',
			'fragrance_notes_contains',
			'fragrance_family_contains',
			'fragrance_family',
			'categories',
		];

		$guard = 0;
		while ($this->planHitCount($keyword, $filters) === 0 && $guard < 12) {
			$guard++;
			if ($keyword !== '' && $this->planHitCount('', $filters) > 0) {
				// Sort-only (or empty intent) must NOT wipe a leftover brand/product typo —
				// that surfaces unrelated expensive bestsellers (Creed / Tom Ford).
				$intentFilters = $filters;
				unset(
					$intentFilters['sortby'],
					$intentFilters['_rank_query'],
					$intentFilters['_search_description']
				);
				$hasRealIntent = $this->hasStructuredIntentFilters($intentFilters)
					|| $this->hasProductAttributeFilters($intentFilters)
					|| !empty($intentFilters['InStockItems']);
				if ($hasRealIntent) {
					$keyword = '';
					return;
				}
				if (!empty($filters['sortby'])) {
					unset($filters['sortby']);
					continue;
				}
				break;
			}

			$candidates = [];
			foreach ($droppableOrder as $key) {
				if (!isset($filters[$key]) || $filters[$key] === '' || $filters[$key] === []) {
					continue;
				}
				if (in_array($key, $hardKeys, true)) {
					continue;
				}
				// Solo breadth of this facet — rarest conflicting facet drops first.
				$candidates[$key] = $this->planHitCount('', [$key => $filters[$key]]);
			}
			if ($candidates === []) {
				break;
			}
			asort($candidates, SORT_NUMERIC);
			$dropKey = (string) array_key_first($candidates);
			unset($filters[$dropKey]);
		}
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function planHitCount(string $keyword, array $filters): int
	{
		try {
			$result = $this->productSearch->search($keyword, $filters, 1, 1, false);
			$total = (int) ($result['total'] ?? 0);
			if ($total > 0) {
				return $total;
			}
			return count($result['hits'] ?? []);
		} catch (Throwable $e) {
			Log::warning('NLP plan hit-check failed: ' . $e->getMessage());
			return 0;
		}
	}

	private function isGenericCategoryKeyword(string $keyword): bool
	{
		return in_array(strtolower(trim($keyword)), [
			'perfume', 'fragrance', 'fragrances', 'candles', 'candle',
		], true);
	}

	/**
	 * True when q only repeats labels already applied as facet filters.
	 *
	 * @param array<string, mixed> $nlp
	 * @param array<string, mixed> $filters
	 */
	private function keywordRedundantWithFilters(string $keyword, array $nlp, array $filters): bool
	{
		$kw = strtolower(trim($keyword));
		if ($kw === '') {
			return false;
		}
		$familyKw = strtolower($this->keywordFromFragranceFamily($nlp, $filters));
		if ($familyKw !== '' && ($kw === $familyKw || preg_match('/^' . preg_quote($familyKw, '/') . '(\s+perfume|\s+cologne|\s+fragrance)?$/u', $kw))) {
			return !empty($filters['fragrance_family']) || !empty($filters['fragrance_family_contains']);
		}
		return false;
	}

	/**
	 * @param array<string, mixed> $nlp
	 * @param array<string, mixed> $filters
	 */
	private function keywordFromFragranceFamily(array $nlp, array $filters): string
	{
		if (isset($nlp['fragrance_family']) && is_scalar($nlp['fragrance_family'])) {
			$raw = trim((string) $nlp['fragrance_family']);
			if ($raw !== '') {
				return strtolower($raw);
			}
		}
		if (!empty($filters['fragrance_family']) && is_array($filters['fragrance_family'])) {
			$first = $filters['fragrance_family'][0] ?? null;
			if (is_scalar($first)) {
				$decoded = trim($this->productSearch->decodeFilterValue((string) $first));
				// Prefer a single family token from compounds like "Amber, Citrus".
				foreach (['citrus', 'floral', 'woody', 'oriental', 'spicy', 'aquatic', 'gourmand', 'fresh'] as $token) {
					if (preg_match('/\b' . preg_quote($token, '/') . '\b/iu', $decoded)) {
						return $token;
					}
				}
			}
		}
		return '';
	}

	/**
	 * Offline / cache-miss fallback: only closed structural cues + dynamic catalog parents.
	 * Semantic synonyms (office→Day, wedding gift, long lasting, …) belong in the LLM prompt.
	 *
	 * @return array<string, mixed>
	 */
	private function localStructuralExtract(string $utterance): array
	{
		$nlp = [];
		$u = strtolower(trim($utterance));
		if ($u === '') {
			return [];
		}

		// Similar-to-product before brand match — "Dior Sauvage" must not become brand=Dior only.
		$similar = $this->extractSimilarToProductIntent($utterance);
		if ($similar !== null) {
			$nlp['similar_to_product'] = $similar['reference_product'];
			if (!empty($similar['price_constraint'])) {
				$nlp['price_constraint'] = $similar['price_constraint'];
			}
			if (!empty($similar['reference_variant'])) {
				$nlp['reference_variant'] = $similar['reference_variant'];
			}
			if (!empty($similar['_reference_size_ml'])) {
				$nlp['_reference_size_ml'] = $similar['_reference_size_ml'];
			}
			if (!empty($similar['brand'])) {
				$nlp['brand'] = $similar['brand'];
				$nlp['_similar_keep_brand'] = true;
			}
			$nlp['query'] = 'perfume';
		}

		if (preg_match('/\b(women|woman|ladies|female|her|hers|wife|wives|girlfriend|mother|mom|mum|sister|daughter)\b/u', $u)) {
			$nlp['gender'] = 'Women';
		} elseif (preg_match('/\b(men|man|guys|male|him|his|husband|husbands|boyfriend|father|dad|brother|son)\b/u', $u)) {
			$nlp['gender'] = 'Men';
		} elseif (preg_match('/\bunisex\b/u', $u)) {
			$nlp['gender'] = 'Unisex';
		}

		$nlp = $this->applyUtterancePriceOverrides($nlp, $utterance);

		// Local occasion: only unambiguous wear cues. Ambiguous day/night phrases
		// (date night, lasts all day, …) are the LLM's job — never guess Night/Day here.
		if (preg_match('/\b(nightlife|nighttime|evening\s+wear|night\s+wear)\b/u', $u)) {
			$nlp['occasion'] = 'Night';
		} elseif (preg_match('/\b(daytime|day\s*wear|office\s+wear|work\s*wear)\b/u', $u)) {
			$nlp['occasion'] = 'Day';
		}

		// Date night / dinner date → catalog occasion values that mention "date"
		// (SearchSpring "datenights" facet) — never the generic Night facet / Night titles.
		if (preg_match('/\bdate\s*nights?\b/u', $u) || preg_match('/\bdinner\s+dates?\b/u', $u)) {
			$dateOccasions = $this->catalogDateOccasionValues();
			if ($dateOccasions !== []) {
				$nlp['occasion'] = $dateOccasions;
				$nlp['query'] = 'perfume';
				$nlp['search_description'] = false;
			} else {
				$nlp['query'] = preg_match('/\bdinner\s+dates?\b/u', $u) ? 'dinner date' : 'date night';
				$nlp['search_description'] = true;
			}
		}

		if (preg_match('/\beau\s+de\s+(parfum|toilette|cologne)\b/u', $u)) {
			// concentration phrase → text search only
		} else {
			$testerCategory = $this->resolveTesterCategoryFromUtterance($u, $nlp['gender'] ?? null);
			if ($testerCategory !== null) {
				$nlp['category'] = $testerCategory;
			} else {
				$category = $this->matchCatalogCategoryParent($utterance);
				if ($category !== null) {
					$nlp['category'] = $category;
				}
			}
		}

		if (preg_match('/\b(summer|winter|spring|fall|autumn)\b/u', $u, $m)) {
			$season = strtolower($m[1]);
			$nlp['seasons'] = $season === 'autumn' ? 'Fall' : ucfirst($season);
		}

		$concepts = $this->localFragranceConceptsFromUtterance($utterance);
		if ($concepts !== []) {
			$nlp['fragrance_concepts'] = $concepts;
		}

		if (preg_match('/\b(in\s+stock|available\s+now)\b/u', $u)) {
			$nlp['in_stock'] = true;
		}

		// "New arrivals" → Special badge (Meili: "New Arrivals"), not text q="new".
		if (preg_match('/\bnew\s+arrivals?\b/u', $u)) {
			$nlp['badges'] = 'New Arrivals';
		}

		// "Best rating / top rated / 5 star" → Star Rating 5 + Top Rated sort (exclude unrated).
		$ratingIntent = $this->utteranceHasTopRatingIntent($u);
		if ($ratingIntent !== null) {
			$nlp['rating_avg'] = $ratingIntent;
			$nlp['OnTopRated'] = true;
		}

		// Catalog brand (incl. typos / spaced vs concatenated forms like "onam luxury" → OMANLUXURY).
		// Never treat quality filler "best" as brand when this is a rating query.
		// Never brand-match the reference title for similar-to intents (unless keep-brand).
		$matchedBrand = null;
		if ($ratingIntent === null && empty($nlp['similar_to_product'])) {
			$matchedBrand = $this->matchCatalogBrand($utterance);
			if ($matchedBrand !== null) {
				$nlp['brand'] = $matchedBrand;
			}
		} elseif ($ratingIntent === null && !empty($nlp['_similar_keep_brand']) && empty($nlp['brand'])) {
			$matchedBrand = $this->matchCatalogBrand((string) ($nlp['brand'] ?? ''));
		}

		// Price-intent cues (also in OpenAI prompt) — local fallback so sort always applies.
		// "isn't too expensive" / "not expensive" → affordable (PriceAsc), not luxury.
		// Do NOT treat "luxury" as PriceDesc when it is part of a brand name ("Oman Luxury").
		// Skip cheap→PriceAsc when similar-to + cheaper (price becomes a hard max vs reference).
		if (($nlp['price_constraint'] ?? '') !== 'lower_than_reference') {
			if (preg_match('/\b(?:not|isn\'?t|aren\'?t|aint)\s+(?:too\s+|very\s+|that\s+)?(?:expensive|pricey|costly)\b/u', $u)
				|| preg_match('/\b(?:affordable|cheap|budget|inexpensive|bargain)\b/u', $u)
			) {
				$nlp['SortBy'] = 'PriceAsc';
			} elseif (
				preg_match('/\b(?:expensive|premium|high[\s-]?end|pricey|costly)\b/u', $u)
				|| (
					preg_match('/\bluxury\b/u', $u)
					&& $matchedBrand === null
					&& empty($nlp['similar_to_product'])
					&& !$this->luxuryLooksLikeBrandFragment($u)
				)
			) {
				$nlp['SortBy'] = 'PriceDesc';
			}
		}

		// Brand match wins over accidental price-sort from brand-name words.
		if ($matchedBrand !== null && $this->utteranceIsPrimarilyBrand($utterance, $matchedBrand)) {
			unset($nlp['SortBy']);
		}
		// Rating intent owns ranking — don't also apply price sort.
		if ($ratingIntent !== null) {
			unset($nlp['SortBy'], $nlp['brand']);
		}

		return $nlp;
	}

	/**
	 * Detect "similar to / like / alternative to {product}" (+ optional cheaper / brand constraint).
	 *
	 * @return array{reference_product:string,price_constraint?:string,brand?:string}|null
	 */
	private function extractSimilarToProductIntent(string $utterance): ?array
	{
		$raw = trim(preg_replace('/\s+/u', ' ', $utterance) ?? $utterance);
		if ($raw === '') {
			return null;
		}
		$u = strtolower($raw);
		if (!preg_match('/\b(?:like|similar|alternative|dupe)/u', $u)) {
			return null;
		}

		$cheaper = (bool) preg_match(
			'/\b(?:cheaper|more\s+affordable|less\s+expensive|inexpensive|budget\s+alternative|affordable\s+alternative)\b/u',
			$u
		);

		$reference = null;
		$brand = null;

		// Prefer "find/show … similar to X" before "{Brand} perfumes similar to …"
		if (preg_match(
			'/\b(?:find|show|get|looking\s+for|search\s+for)\s+(?:(?:a|an|some|something)\s+)?(?:perfumes?|fragrances?|colognes?)?\s*(?:that\s+are\s+|which\s+are\s+)?(?:similar\s+to|like)\s+(.+)$/iu',
			$raw,
			$m
		)) {
			$reference = trim($m[1]);
		} elseif (preg_match(
			'/\b([a-z0-9][a-z0-9&.\'\-\s]{1,40}?)\s+(?:perfumes?|fragrances?|colognes?)\s+(?:that\s+are\s+|which\s+are\s+)?(?:similar\s+to|like)\s+(.+)$/iu',
			$raw,
			$m
		)) {
			$maybeBrand = trim($m[1]);
			// Leading verbs are not houses.
			if (!preg_match('/^(?:find|show|get|looking|search|buy|need|want)$/iu', $maybeBrand)) {
				$brand = $maybeBrand;
			}
			$reference = trim($m[2]);
		} elseif (preg_match(
			'/\b(?:i\s+want\s+a\s+)?(?:cheaper|more\s+affordable|less\s+expensive)\s+alternative\s+to\s+(.+)$/iu',
			$raw,
			$m
		)) {
			$reference = trim($m[1]);
			$cheaper = true;
		} elseif (preg_match(
			'/\b(?:alternative|dupes?|dupe)\s+to\s+(.+)$/iu',
			$raw,
			$m
		)) {
			$reference = trim($m[1]);
		} elseif (preg_match(
			'/\b(?:(?:a|an|some|something)\s+)?(?:perfume|fragrance|cologne)?s?\s*(?:like|similar\s+to)\s+(.+)$/iu',
			$raw,
			$m
		)) {
			$reference = trim($m[1]);
		}

		if ($reference === null || $reference === '') {
			return null;
		}

		// Strip trailing price / cheaper clauses from the product phrase.
		$reference = preg_replace(
			'/\s+but\s+(?:cheaper|more\s+affordable|less\s+expensive|inexpensive).*$/iu',
			'',
			$reference
		) ?? $reference;
		$reference = preg_replace(
			'/\s+(?:under|below|less\s+than|upto|up\s+to)\s*\$?\s*[\d,]+(?:\.\d+)?\s*[kmb]?.*$/iu',
			'',
			$reference
		) ?? $reference;
		$reference = trim($reference, " \t\n\r\0\x0B.,;:!?'\"");
		$sizeHint = $this->extractReferenceSizeHint($reference);
		$reference = $sizeHint['product'];
		if ($reference === '' || mb_strlen($reference) < 3) {
			return null;
		}

		$out = ['reference_product' => $reference];
		if ($cheaper) {
			$out['price_constraint'] = 'lower_than_reference';
		}
		if ($sizeHint['label'] !== null) {
			$out['reference_variant'] = $sizeHint['label'];
			if ($sizeHint['ml'] !== null) {
				$out['_reference_size_ml'] = $sizeHint['ml'];
			}
		}
		if ($brand !== null && $brand !== '') {
			$catalogBrand = $this->matchCatalogBrand($brand);
			if ($catalogBrand === null) {
				$catalogBrand = $this->matchCatalogBrandContaining($brand);
			}
			$out['brand'] = $catalogBrand ?? $brand;
		}
		return $out;
	}

	/**
	 * Pull "100ml" / "3.4 oz" out of a reference product phrase.
	 *
	 * @return array{product:string,label:?string,ml:?float}
	 */
	private function extractReferenceSizeHint(string $reference): array
	{
		$reference = trim($reference);
		if ($reference === '') {
			return ['product' => '', 'label' => null, 'ml' => null];
		}
		if (!preg_match('/\b(\d+(?:\.\d+)?)\s*(ml|oz)\b/iu', $reference, $m)) {
			return ['product' => $reference, 'label' => null, 'ml' => null];
		}
		$amount = $m[1];
		$unit = strtolower($m[2]);
		$label = $amount . $unit;
		$parsed = SizeNormalizer::parse($amount . ' ' . $unit);
		$ml = is_array($parsed) && isset($parsed['ml']) ? (float) $parsed['ml'] : null;
		$product = trim(preg_replace(
			'/\b' . preg_quote($amount, '/') . '\s*' . preg_quote($m[2], '/') . '\b/iu',
			'',
			$reference
		) ?? $reference);
		$product = trim(preg_replace('/\s+/u', ' ', $product) ?? $product);

		return [
			'product' => $product !== '' ? $product : $reference,
			'label' => $label,
			'ml' => $ml,
		];
	}

	/** Resolve short house names (Dior → Christian Dior) via catalog brand facets. */
	private function matchCatalogBrandContaining(string $brandFragment): ?string
	{
		$needle = preg_replace('/[^a-z0-9]+/u', '', strtolower(trim($brandFragment))) ?? '';
		if ($needle === '' || mb_strlen($needle) < 3) {
			return null;
		}
		$brands = $this->catalogBrandIndex();
		$hits = [];
		foreach ($brands['by_collapsed'] as $collapsed => $label) {
			$collapsed = (string) $collapsed;
			if (
				$collapsed === $needle
				|| str_ends_with($collapsed, $needle)
				|| str_contains($collapsed, $needle)
			) {
				$hits[$label] = mb_strlen($collapsed);
			}
		}
		if ($hits === []) {
			return null;
		}
		// Prefer exact / ends-with over loose contains when multiple houses match.
		$exact = [];
		foreach ($hits as $label => $len) {
			$c = preg_replace('/[^a-z0-9]+/u', '', strtolower($label)) ?? '';
			if ($c === $needle || str_ends_with($c, $needle)) {
				$exact[$label] = $len;
			}
		}
		$pool = $exact !== [] ? $exact : $hits;
		if (count($pool) === 1) {
			return array_key_first($pool);
		}
		// Ambiguous (Dior → Christian Dior / Miss Dior / …): pick strongest catalog house.
		$bestLabel = null;
		$bestScore = PHP_INT_MIN;
		foreach ($pool as $label => $len) {
			$count = (int) ($brands['counts'][$label] ?? 0);
			// Prefer higher product count, then shorter house labels over long line names.
			$score = ($count * 1000) - (int) $len;
			if ($score > $bestScore) {
				$bestScore = $score;
				$bestLabel = $label;
			}
		}
		return $bestLabel;
	}

	/**
	 * Expand similar_to_product → catalog attributes + price + exclude reference uid.
	 *
	 * @param array{keyword:string,filters:array<string,mixed>} $mapped
	 * @param array<string, mixed> $extracted
	 * @return array{keyword:string,filters:array<string,mixed>}
	 */
	private function applySimilarToProductPlan(array $mapped, string $utterance, array $extracted): array
	{
		$reference = trim((string) ($extracted['similar_to_product'] ?? ''));
		if ($reference === '') {
			$parsed = $this->extractSimilarToProductIntent($utterance);
			$reference = trim((string) ($parsed['reference_product'] ?? ''));
			if ($reference === '') {
				return $mapped;
			}
			if (!empty($parsed['price_constraint']) && empty($extracted['price_constraint'])) {
				$extracted['price_constraint'] = $parsed['price_constraint'];
			}
		}

		$lookupPhrase = $reference;
		if (!empty($extracted['brand']) || !empty($extracted['_similar_keep_brand'])) {
			$brandHint = trim((string) ($extracted['brand'] ?? ''));
			if ($brandHint !== '' && stripos($reference, $brandHint) === false) {
				$lookupPhrase = $brandHint . ' ' . $reference;
			}
		}
		// Size may still be on the raw utterance when LLM omitted reference_variant.
		$sizeMl = isset($extracted['_reference_size_ml']) ? (float) $extracted['_reference_size_ml'] : null;
		$variantLabel = trim((string) ($extracted['reference_variant'] ?? ''));
		if ($sizeMl === null || $sizeMl <= 0 || $variantLabel === '') {
			$fromUtterance = $this->extractReferenceSizeHint($utterance);
			if ($variantLabel === '' && $fromUtterance['label'] !== null) {
				$variantLabel = $fromUtterance['label'];
			}
			if (($sizeMl === null || $sizeMl <= 0) && $fromUtterance['ml'] !== null) {
				$sizeMl = $fromUtterance['ml'];
			}
		}
		$hit = $this->productSearch->findBestProductByName($lookupPhrase, 20, $sizeMl);
		if ($hit === null && $lookupPhrase !== $reference) {
			$hit = $this->productSearch->findBestProductByName($reference, 20, $sizeMl);
		}
		if ($hit === null) {
			$kw = trim((string) ($mapped['keyword'] ?? ''));
			if ($kw === '' || strcasecmp($kw, 'perfume') === 0) {
				$mapped['keyword'] = $reference;
			}
			return $mapped;
		}

		$filters = is_array($mapped['filters'] ?? null) ? $mapped['filters'] : [];
		$uid = trim((string) ($hit['uid'] ?? $hit['id'] ?? ''));
		if ($uid !== '') {
			$filters['exclude_uid'] = $uid;
		}

		$familyVals = $hit['fragrance_family'] ?? [];
		if (is_string($familyVals)) {
			$familyVals = [$familyVals];
		}
		$concepts = [];
		foreach ((array) $familyVals as $fam) {
			if (!is_scalar($fam)) {
				continue;
			}
			$fam = strtolower(trim((string) $fam));
			if ($fam === '') {
				continue;
			}
			foreach (preg_split('/[\s,\/|&]+/u', $fam) ?: [] as $token) {
				$token = trim($token);
				if ($token !== '' && mb_strlen($token) >= 3 && !in_array($token, ['and', 'the', 'for'], true)) {
					$concepts[] = $token;
				}
			}
		}
		$concepts = array_values(array_unique($concepts));
		// Hard family filter only when the reference actually has fragrance_family values.
		if ($concepts !== []) {
			$existing = is_array($filters['fragrance_family_contains'] ?? null)
				? $filters['fragrance_family_contains']
				: [];
			$filters['fragrance_family_contains'] = array_values(array_unique(array_merge(
				$existing,
				array_slice($concepts, 0, 4)
			)));
		}

		$gender = strtolower(trim((string) ($hit['gender'] ?? '')));
		if ($gender !== '' && empty($filters['gender'])) {
			if (str_contains($gender, 'w') || str_contains($gender, 'women') || $gender === 'f') {
				$filters['gender'] = ['W', 'U'];
			} elseif (str_contains($gender, 'm') || str_contains($gender, 'men') || $gender === 'm') {
				$filters['gender'] = ['M', 'U'];
			}
		}

		$noteTokens = [];
		$notes = $hit['fragrance_notes'] ?? [];
		if (is_string($notes)) {
			$notes = [$notes];
		}
		foreach (array_slice((array) $notes, 0, 6) as $note) {
			if (!is_scalar($note)) {
				continue;
			}
			$n = strtolower(trim((string) $note));
			if ($n !== '' && mb_strlen($n) >= 3) {
				$noteTokens[] = $n;
			}
		}
		// Catalog often stores scent vibe on personality when notes/family are empty.
		$personalityTokens = [];
		$personality = strtolower(trim((string) ($hit['personality'] ?? '')));
		if ($personality !== '') {
			foreach (preg_split('/[\s,\/|&\-]+/u', $personality) ?: [] as $token) {
				$token = trim($token);
				if ($token !== '' && mb_strlen($token) >= 4 && !in_array($token, ['and', 'the', 'with', 'from'], true)) {
					$personalityTokens[] = $token;
				}
			}
		}
		// Last resort: harvest known scent tokens from description (e.g. Baccarat copy).
		$descTokens = [];
		if ($concepts === [] && $noteTokens === [] && $personalityTokens === []) {
			$desc = strtolower(html_entity_decode(strip_tags((string) ($hit['description'] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
			foreach (['woody', 'amber', 'floral', 'citrus', 'spicy', 'fresh', 'oriental', 'aquatic', 'gourmand', 'aromatic', 'vanilla', 'oud', 'musk', 'saffron', 'jasmine'] as $token) {
				if ($desc !== '' && preg_match('/\b' . preg_quote($token, '/') . '\b/u', $desc)) {
					$descTokens[] = $token;
				}
			}
			$descTokens = array_slice($descTokens, 0, 4);
		}

		// Reference price priority for "cheaper" / lower_than_reference:
		// 1) Explicit user cap ("under $50") — already on $filters
		// 2) Explicit size/variant ("Sauvage 100ml") → that SKU's price
		// 3) Otherwise the line's cheapest available size (same_variation_min_price)
		//    so "cheaper than Sauvage" means cheaper than you can already buy Sauvage for
		//    (not cheaper than a mid/full bottle while a $26 mini exists on the same line).
		// Never use same_variation_max_price.
		$skuPrice = (float) ($hit['price'] ?? 0);
		$lineMin = (float) ($hit['same_variation_min_price'] ?? 0);
		$priceConstraint = strtolower(trim((string) ($extracted['price_constraint'] ?? '')));
		$hasExplicitMax = isset($filters['maxprice']) && $filters['maxprice'] !== '' && $filters['maxprice'] !== null;
		$matchedSize = $sizeMl !== null && $sizeMl > 0 && $this->productSearch->hitMatchesSizeMl($hit, $sizeMl);

		$refPrice = $skuPrice;
		$priceType = 'canonical_sku';
		if ($hasExplicitMax) {
			$priceType = 'explicit_user';
			$refPrice = (float) $filters['maxprice'];
		} elseif ($matchedSize && $skuPrice > 0) {
			$priceType = 'variant';
			$refPrice = $skuPrice;
		} elseif ($priceConstraint === 'lower_than_reference') {
			if ($lineMin > 0) {
				$refPrice = $lineMin;
				$priceType = 'line_min';
			} elseif ($skuPrice > 0) {
				$refPrice = $skuPrice;
				$priceType = 'canonical_sku';
			}
		}

		if ($priceConstraint === 'lower_than_reference' && !$hasExplicitMax && $refPrice > 0) {
			$filters['ochangeprice'] = '1';
			$filters['maxprice'] = (string) max(0.01, round($refPrice - 0.01, 2));
		}

		$refBrand = trim((string) ($hit['brand'] ?? ''));
		if (empty($extracted['brand']) && empty($extracted['_similar_keep_brand'])) {
			unset($filters['brands']);
		} else {
			// "Dior" → catalog facet "Christian Dior" via the resolved reference.
			$wantBrand = trim((string) ($extracted['brand'] ?? ($filters['brands'][0] ?? '')));
			if ($refBrand !== '' && $wantBrand !== '') {
				$wantCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($wantBrand)) ?? '';
				$refCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($refBrand)) ?? '';
				if (
					$wantCollapsed !== ''
					&& $refCollapsed !== ''
					&& (
						$wantCollapsed === $refCollapsed
						|| str_contains($refCollapsed, $wantCollapsed)
						|| str_contains($wantCollapsed, $refCollapsed)
					)
				) {
					$filters['brands'] = [$refBrand];
				}
			}
		}

		// Soft ranking text: family / notes / personality — NOT the line name (avoids more Sauvage SKUs).
		$nameTokens = [];
		$refName = strtolower(trim((string) ($hit['name'] ?? $reference)));
		$nameRest = $refName;
		if ($refBrand !== '') {
			$nameRest = trim(preg_replace('/\b' . preg_quote(strtolower($refBrand), '/') . '\b/iu', ' ', $nameRest) ?? $nameRest);
		}
		foreach (preg_split('/[^a-z0-9]+/u', $nameRest) ?: [] as $tok) {
			$tok = trim($tok);
			if ($tok !== '' && mb_strlen($tok) >= 4 && !in_array($tok, ['perfume', 'parfum', 'cologne', 'edt', 'edp'], true)) {
				$nameTokens[] = $tok;
			}
		}

		$rankParts = array_values(array_unique(array_filter(array_merge(
			array_slice($concepts, 0, 3),
			array_slice($noteTokens, 0, 4),
			array_slice($personalityTokens, 0, 4),
			array_slice($descTokens, 0, 4)
		))));
		$keyword = trim((string) ($mapped['keyword'] ?? ''));
		if ($keyword === '' || strcasecmp($keyword, 'perfume') === 0 || strcasecmp($keyword, $reference) === 0) {
			$keyword = $rankParts !== [] ? implode(' ', $rankParts) : 'perfume';
		} else {
			foreach ($rankParts as $part) {
				if ($part !== '' && stripos($keyword, $part) === false) {
					$keyword .= ' ' . $part;
				}
			}
			$keyword = trim(preg_replace('/\s+/u', ' ', $keyword) ?? $keyword);
		}

		$filters['_rank_query'] = trim((string) ($hit['name'] ?? $reference));
		$filters['_similar_to_uid'] = $uid;
		$filters['_similar_to_name'] = (string) ($hit['name'] ?? $reference);
		$filters['_similar_to_price'] = $refPrice;
		$filters['_similar_to_price_type'] = $priceType;
		$filters['_similar_to_brand'] = $refBrand;
		if ($variantLabel !== '') {
			$filters['_similar_to_variant'] = $variantLabel;
		}
		// Drop other SKUs/sizes of the same reference line (not just one uid).
		if ($nameTokens !== []) {
			$filters['_exclude_same_line_tokens'] = array_values(array_unique($nameTokens));
		}

		if ($priceConstraint === 'lower_than_reference') {
			unset($filters['sortby']);
		}

		$mapped['keyword'] = $keyword;
		$mapped['filters'] = $filters;
		return $mapped;
	}

	/**
	 * Map "men tester(s)" / bare "tester(s)" onto Meili leaf categories
	 * (Fragrance : Men Testers, …) — same PLP family as cid-43.
	 */
	private function resolveTesterCategoryFromUtterance(string $utteranceLower, ?string $gender): ?string
	{
		if (!preg_match('/\btesters?\b/u', $utteranceLower)) {
			return null;
		}
		$g = strtolower(trim((string) $gender));
		if ($g === 'men') {
			return 'Men Testers';
		}
		if ($g === 'women') {
			return 'Women Testers';
		}
		if ($g === 'unisex') {
			return 'Unisex Testers';
		}
		// Gender-less "tester" → all tester leaves via multi-word segment expand.
		return 'Testers';
	}

	/**
	 * Match the longest catalog category parent phrase present in the utterance
	 * (dynamic from Meili facets — no hardcoded product-type word lists).
	 */
	private function matchCatalogCategoryParent(string $utterance): ?string
	{
		$u = strtolower(trim($utterance));
		if ($u === '') {
			return null;
		}
		$parents = [];
		foreach (array_keys($this->facetDistribution('category')) as $facetValue) {
			$parent = trim(explode(':', (string) $facetValue, 2)[0]);
			if ($parent === '' || mb_strlen($parent) < 3) {
				continue;
			}
			$parents[strtolower($parent)] = $parent;
		}
		if ($parents === []) {
			return null;
		}
		// Longer parents first so "Pocket Perfume" wins over a bare "Perfume" leaf collision.
		uksort($parents, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
		foreach ($parents as $lower => $canonical) {
			if (preg_match('/\b' . preg_quote($lower, '/') . '\b/u', $u)) {
				return $canonical;
			}
		}
		return null;
	}

	/**
	 * Offline / empty-OpenAI safety net for rating intent.
	 * Primary path is the OpenAI prompt (intent taxonomy). This only fires when the model
	 * is unavailable or returns nothing for rating — not a per-query allowlist of shopper phrases.
	 */
	private function utteranceHasTopRatingIntent(string $utteranceLower): ?string
	{
		$u = strtolower(trim($utteranceLower));
		if ($u === '') {
			return null;
		}
		// Explicit star level wins.
		if (preg_match('/\b(?:5|five)[\s-]?stars?\b/u', $u)) {
			return '5';
		}
		if (preg_match('/\b(?:4|four)[\s-]?stars?\b/u', $u)) {
			return '4';
		}
		if (preg_match('/\b(?:3|three)[\s-]?stars?\b/u', $u)) {
			return '3';
		}
		// Best / top / highest rated|rating → 5★ bucket (strict, excludes unrated).
		if (preg_match(
			'/\b(?:best|top|highest|highly)\s+(?:rated|rating|ratings)\b/u',
			$u
		) || preg_match(
			'/\b(?:rated|rating|ratings)\s+(?:best|top|highest)\b/u',
			$u
		) || preg_match(
			'/\b(?:top[\s-]?rated|best[\s-]?rated|highest[\s-]?rated)\b/u',
			$u
		) || preg_match(
			'/\bbest\s+rating\b/u',
			$u
		)) {
			return '5';
		}
		return null;
	}

	/**
	 * Short catalog brand labels / query tokens that are usually English quality,
	 * season, or scent adjectives — not house names.
	 * ("Best perfume…", "fresh citrus…") — only treat as brand when the query is
	 * essentially that brand alone.
	 */
	private function isAmbiguousFillerBrandToken(string $collapsed): bool
	{
		return in_array($collapsed, [
			'best', 'top', 'new', 'love', 'pure', 'true', 'real', 'great', 'super', 'ultra', 'unique',
			// Conversational verbs / pronouns — catalog may have houses named Want, Love, etc.
			'want', 'need', 'buy', 'like', 'get', 'find', 'show', 'looking', 'search', 'please',
			'wish', 'prefer', 'trying', 'lookingfor',
			// Scent / mood / longevity adjectives (must not become brand=Aura Fresh etc.)
			'fresh', 'freshness', 'lasting', 'longlasting', 'long', 'strong', 'soft', 'light', 'dark',
			'sweet', 'warm', 'cool', 'rich', 'deep', 'clean', 'crisp', 'bright', 'bold',
			'summer', 'winter', 'spring', 'autumn', 'fall', 'night', 'day', 'daily', 'evening',
			'citrus', 'floral', 'woody', 'oriental', 'spicy', 'aquatic', 'gourmand', 'aromatic',
			'musk', 'musky', 'oud', 'vanilla', 'amber', 'rose', 'leather', 'smoky',
			'perfume', 'perfumes', 'fragrance', 'fragrances', 'cologne', 'colognes', 'scent', 'scents',
			'parfums', 'parfum', 'eau', 'collection', 'editions', 'edition',
		], true);
	}

	/**
	 * A catalog brand is only valid if the shopper typed a distinctive house token —
	 * not merely a shared adjective ("fresh" ⊆ "Aura Fresh").
	 * Keeps "Chanel perfume for summer" and "roaj" → Roja working.
	 */
	private function brandLabelJustifiedByUtterance(string $utterance, string $brandLabel): bool
	{
		$u = strtolower(trim(preg_replace('/\s+/u', ' ', $utterance) ?? $utterance));
		if ($u === '' || trim($brandLabel) === '') {
			return false;
		}
		$uCollapsed = preg_replace('/[^a-z0-9]+/u', '', $u) ?? '';
		$parts = preg_split('/\s+/u', strtolower(trim($brandLabel)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		$distinctive = [];
		foreach ($parts as $part) {
			$c = preg_replace('/[^a-z0-9]+/u', '', $part) ?? '';
			if ($c === '' || mb_strlen($c) < 3) {
				continue;
			}
			if ($this->isAmbiguousFillerBrandToken($c)) {
				continue;
			}
			$distinctive[] = $c;
		}
		// Brand is only filler words (e.g. label "Fresh") — allow only if query ≈ that brand.
		if ($distinctive === []) {
			return $this->utteranceIsPrimarilyBrand($utterance, $brandLabel);
		}
		foreach ($distinctive as $token) {
			if (preg_match('/\b' . preg_quote($token, '/') . '\b/u', $u)) {
				return true;
			}
			if ($uCollapsed !== '' && str_contains($uCollapsed, $token)) {
				return true;
			}
			// Typo of house token (roaj → roja / lengling only if they typed something close to lengling).
			$maxDist = mb_strlen($token) >= 8 ? 2 : 1;
			$uTokens = preg_split('/\s+/u', $u, -1, PREG_SPLIT_NO_EMPTY) ?: [];
			foreach ($uTokens as $ut) {
				$uc = preg_replace('/[^a-z0-9]+/u', '', strtolower($ut)) ?? '';
				if ($uc === '' || mb_strlen($uc) < 3 || $this->isAmbiguousFillerBrandToken($uc)) {
					continue;
				}
				if (abs(mb_strlen($uc) - mb_strlen($token)) > $maxDist) {
					continue;
				}
				if ($this->collapsedEditDistance($uc, $token) <= $maxDist) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Brand label for Maxaroma search analytics (Brand / house health).
	 * Prefer NLP extract / applied brand facets, then Meili catalog fuzzy (typos).
	 * No hardcoded house list — catalog is the source of truth.
	 *
	 * @param array<string, mixed>|null $filters
	 * @param array<string, mixed>|null $extracted
	 */
	public function resolveTrackedBrand(
		string $utterance,
		?array $filters = null,
		?array $extracted = null
	): ?string {
		if (is_array($extracted)) {
			$raw = $extracted['brand'] ?? null;
			if (is_array($raw)) {
				$raw = $raw[0] ?? null;
			}
			if (is_string($raw) && trim($raw) !== '') {
				$label = trim($raw);
				$canonical = $this->matchCatalogBrand($label);
				if ($canonical !== null) {
					return $canonical;
				}
				return $label;
			}
			// Similar-to extract: house lives on the reference ("like Dior Sauvage"), not as brand facet.
			if (!empty($extracted['similar_to_product'])) {
				$fromSimilar = $this->resolveHouseFromReferencePhrase(
					(string) $extracted['similar_to_product']
				);
				if ($fromSimilar !== null) {
					return $fromSimilar;
				}
			}
		}

		if (is_array($filters) && !empty($filters['brands']) && is_array($filters['brands'])) {
			foreach ($filters['brands'] as $encoded) {
				$label = trim($this->productSearch->decodeFilterValue((string) $encoded));
				if ($label !== '') {
					return $label;
				}
			}
		}

		$utterance = $this->normalizeQuerySeparators(trim($utterance));
		if ($utterance === '') {
			return null;
		}

		// "Something like Dior Sauvage" → Dior (house), not a product-name brand facet.
		if (preg_match(
			'/\b(?:similar(?:\s+to)?|like|alternative(?:\s+to)?|dupe(?:\s+of)?)\s+(.+)$/iu',
			$utterance,
			$m
		)) {
			$ref = trim((string) ($m[1] ?? ''));
			$ref = trim(preg_replace('/\b(?:but|that is|that\'s|which)\b.*$/iu', '', $ref) ?? $ref);
			$fromRef = $this->resolveHouseFromReferencePhrase($ref);
			if ($fromRef !== null) {
				return $fromRef;
			}
		}

		return $this->matchCatalogBrand($utterance);
	}

	/**
	 * Prefer the leading house token in a product reference ("Dior Sauvage" → Christian Dior).
	 * Catalog often stores "Christian Dior" — short "Dior" needs containing/fuzzy match.
	 * Avoid product-name brand facets (e.g. "Sauvage") winning over the house.
	 */
	private function resolveHouseFromReferencePhrase(string $ref): ?string
	{
		$ref = trim(preg_replace('/\s+/u', ' ', $ref) ?? $ref);
		if ($ref === '') {
			return null;
		}
		$parts = preg_split('/\s+/u', $ref, -1, PREG_SPLIT_NO_EMPTY) ?: [];
		if ($parts === []) {
			return null;
		}

		// 1) Leading token as house (Dior → Christian Dior via containing match).
		$first = $parts[0];
		$house = $this->matchCatalogBrand($first)
			?? $this->matchCatalogBrandContaining($first);
		if ($house !== null) {
			return $house;
		}

		// 2) Two-word houses (Tom Ford …) — containing only; do NOT run matchCatalogBrand on
		//    "Dior Sauvage" (that resolves the product-name brand facet "Sauvage").
		if (count($parts) >= 2) {
			$two = $parts[0] . ' ' . $parts[1];
			$house2 = $this->matchCatalogBrandContaining($two);
			if ($house2 === null) {
				$house2 = $this->matchCatalogBrand($two);
				// Reject when the match is only the last product token (Sauvage).
				if ($house2 !== null) {
					$last = strtolower($parts[1]);
					$houseLast = strtolower(
						(preg_split('/\s+/u', trim($house2)) ?: [])[0] ?? $house2
					);
					$houseCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($house2)) ?? '';
					$lastCollapsed = preg_replace('/[^a-z0-9]+/u', '', $last) ?? '';
					if ($houseCollapsed === $lastCollapsed || $houseLast === $last) {
						$house2 = null;
					}
				}
			}
			if ($house2 !== null) {
				return $house2;
			}
		}

		// 3) Full reference last — may hit a product-name brand facet; only keep if
		//    the label is justified by the leading token.
		$full = $this->matchCatalogBrand($ref)
			?? $this->matchCatalogBrandContaining($ref);
		if ($full !== null) {
			$firstCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($first)) ?? '';
			$fullCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($full)) ?? '';
			if (
				$firstCollapsed !== ''
				&& (
					str_contains($fullCollapsed, $firstCollapsed)
					|| $this->collapsedEditDistance($firstCollapsed, substr($fullCollapsed, 0, max(mb_strlen($firstCollapsed), 1))) <= 1
				)
			) {
				return $full;
			}
		}
		return null;
	}

	/**
	 * Resolve a catalog brand from the utterance — exact, collapsed (OMANLUXURY ↔
	 * "oman luxury"), or unique fuzzy typo ("onam luxury" → OMANLUXURY).
	 * Uses Meili brand facets; no hardcoded brand list.
	 */
	private function matchCatalogBrand(string $utterance): ?string
	{
		$u = strtolower(trim(preg_replace('/\s+/u', ' ', $this->normalizeQuerySeparators($utterance)) ?? $utterance));
		if ($u === '') {
			return null;
		}
		// Quality filler "best …" is not a brand browse.
		if ($this->utteranceHasTopRatingIntent($u) !== null) {
			return null;
		}

		// Strip ultra-generic product-class tails so "oman luxury perfume" / "roja perfums" still matches.
		$uBrand = trim(preg_replace(
			'/\b(?:perfumes?|perfums|perfum|parfums?|fragrances?|colognes?|edt|edp)\b/iu',
			' ',
			$u
		) ?? $u);
		$uBrand = trim(preg_replace('/\s+/u', ' ', $uBrand) ?? $uBrand);
		if ($uBrand === '') {
			$uBrand = $u;
		}

		$phrases = $this->brandMatchPhrases($uBrand);
		if ($phrases === []) {
			return null;
		}

		$brands = $this->catalogBrandIndex();
		if ($brands === []) {
			return null;
		}

		// 1) Exact collapsed match (handles spaced vs concatenated catalog labels).
		foreach ($phrases as $phrase) {
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', $phrase) ?? '';
			if ($collapsed === '' || mb_strlen($collapsed) < 4) {
				continue;
			}
			if (!isset($brands['by_collapsed'][$collapsed])) {
				continue;
			}
			$label = $brands['by_collapsed'][$collapsed];
			// "Best perfume…" / "fresh" alone must not become a brand facet.
			if ($this->isAmbiguousFillerBrandToken($collapsed)
				&& !$this->utteranceIsPrimarilyBrand($utterance, $label)
			) {
				continue;
			}
			if (!$this->brandLabelJustifiedByUtterance($utterance, $label)) {
				continue;
			}
			return $label;
		}

		// 2) Unique fuzzy match on collapsed forms (typos like onam→oman, akor→akro, roaj→roja).
		$bestLabel = null;
		$bestDist = PHP_INT_MAX;
		$bestCount = -1;
		$ties = 0;
		foreach ($phrases as $phrase) {
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', $phrase) ?? '';
			$len = mb_strlen($collapsed);
			// Allow 4+ chars (Akro / Creed-length houses); shorter is too ambiguous.
			if ($len < 4) {
				continue;
			}
			if ($this->isAmbiguousFillerBrandToken($collapsed)
				&& !$this->utteranceIsPrimarilyBrand($utterance, $phrase)
			) {
				continue;
			}
			// Short brands: 1 edit unit (incl. adjacent transposition); longer: up to 2.
			$maxDist = $len >= 8 ? 2 : 1;
			foreach ($brands['by_collapsed'] as $brandCollapsed => $label) {
				$candidates = [$brandCollapsed];
				// Multi-word houses ("Roja Parfums"): also match the first word ("roja").
				$labelParts = preg_split('/\s+/u', trim($label), 2) ?: [];
				$firstWord = strtolower(trim((string) ($labelParts[0] ?? '')));
				$firstCollapsed = preg_replace('/[^a-z0-9]+/u', '', $firstWord) ?? '';
				if ($firstCollapsed !== '' && $firstCollapsed !== $brandCollapsed && mb_strlen($firstCollapsed) >= 4) {
					$candidates[] = $firstCollapsed;
				}
				$dist = PHP_INT_MAX;
				foreach ($candidates as $brandForm) {
					$brandLen = mb_strlen($brandForm);
					if (abs($brandLen - $len) > $maxDist && $collapsed !== $brandForm) {
						continue;
					}
					$d = $this->collapsedEditDistance($collapsed, $brandForm);
					// Include d=0 so first-word exact ("roja" → Roja Parfums) beats typos ("rosa").
					if ($d <= $maxDist && $d < $dist) {
						$dist = $d;
					}
				}
				if ($dist === PHP_INT_MAX) {
					continue;
				}
				$count = (int) ($brands['counts'][$label] ?? 0);
				// Prefer exact house-token matches over 1-edit near-misses (roja ≻ rosa).
				if ($dist < $bestDist || ($dist === $bestDist && $count > $bestCount)) {
					$bestDist = $dist;
					$bestLabel = $label;
					$bestCount = $count;
					$ties = 1;
				} elseif ($dist === $bestDist && $count === $bestCount && $label !== $bestLabel) {
					$ties++;
				}
			}
		}

		if ($bestLabel !== null && $ties === 1) {
			$bestCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($bestLabel)) ?? '';
			if ($this->isAmbiguousFillerBrandToken($bestCollapsed)
				&& !$this->utteranceIsPrimarilyBrand($utterance, $bestLabel)
			) {
				return null;
			}
			if (!$this->brandLabelJustifiedByUtterance($utterance, $bestLabel)) {
				return null;
			}
			return $bestLabel;
		}

		// 3) Facet card is capped (~1000) and can omit real houses (Roja Parfums).
		// Resolve short typos via Meili brand search + adjacent letter swap (roaj→roja).
		$viaMeili = $this->matchCatalogBrandViaMeiliTypo($utterance, $phrases);
		if ($viaMeili !== null && $this->brandLabelJustifiedByUtterance($utterance, $viaMeili)) {
			return $viaMeili;
		}
		return null;
	}

	/**
	 * When brand facets miss a house, probe Meili with the typed token and its
	 * adjacent transpositions; return a unique matching brand label.
	 *
	 * @param list<string> $phrases
	 */
	private function matchCatalogBrandViaMeiliTypo(string $utterance, array $phrases): ?string
	{
		// Keep this path for short brand-like queries (roaj, akor) — not long NL sentences.
		$u = strtolower(trim(preg_replace('/\s+/u', ' ', $utterance) ?? $utterance));
		$u = trim(preg_replace(
			'/\b(?:perfumes?|perfums|perfum|parfums?|fragrances?|colognes?|edt|edp|by)\b/iu',
			' ',
			$u
		) ?? $u);
		$u = trim(preg_replace('/\s+/u', ' ', $u) ?? $u);
		$tokens = preg_split('/\s+/u', $u, -1, PREG_SPLIT_NO_EMPTY) ?: [];
		if ($tokens === [] || count($tokens) > 2) {
			return null;
		}

		$candidates = [];
		foreach ($phrases as $phrase) {
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower(trim($phrase))) ?? '';
			$len = mb_strlen($collapsed);
			if ($len < 4 || $len > 12 || $this->isAmbiguousFillerBrandToken($collapsed)) {
				continue;
			}
			$candidates[$collapsed] = true;
			$chars = preg_split('//u', $collapsed, -1, PREG_SPLIT_NO_EMPTY) ?: [];
			for ($i = 0, $n = count($chars); $i < $n - 1; $i++) {
				$swap = $chars;
				$tmp = $swap[$i];
				$swap[$i] = $swap[$i + 1];
				$swap[$i + 1] = $tmp;
				$cand = implode('', $swap);
				if ($cand !== '' && $cand !== $collapsed) {
					$candidates[$cand] = true;
				}
			}
		}
		if ($candidates === []) {
			return null;
		}

		$brandHits = [];
		foreach (array_keys($candidates) as $cand) {
			$brand = $this->productSearch->resolveBrandLabelFromQuery($cand);
			if ($brand === null || $brand === '') {
				continue;
			}
			$brandCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($brand)) ?? '';
			// Candidate must look like the house name (roja ⊂ rojaparfums), not a random hit.
			$ok = false;
			foreach (array_keys($candidates) as $c) {
				if (
					$brandCollapsed === $c
					|| str_starts_with($brandCollapsed, $c)
					|| $this->collapsedEditDistance($c, substr($brandCollapsed, 0, max(mb_strlen($c), 1))) <= 1
				) {
					$ok = true;
					break;
				}
			}
			if ($ok) {
				$brandHits[$brand] = true;
			}
		}
		$labels = array_keys($brandHits);
		return count($labels) === 1 ? $labels[0] : null;
	}

	/**
	 * Candidate phrases to match against catalog brands (full query + n-grams).
	 *
	 * @return list<string>
	 */
	private function brandMatchPhrases(string $utteranceLower): array
	{
		$tokens = preg_split('/\s+/u', $utteranceLower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
		if ($tokens === []) {
			return [];
		}
		$phrases = [implode(' ', $tokens)];
		$count = count($tokens);
		for ($n = min(4, $count); $n >= 1; $n--) {
			for ($i = 0; $i <= $count - $n; $i++) {
				$phrases[] = implode(' ', array_slice($tokens, $i, $n));
			}
		}
		return array_values(array_unique($phrases));
	}

	/**
	 * @return array{by_collapsed: array<string, string>, counts: array<string, int>}
	 */
	private function catalogBrandIndex(): array
	{
		static $memo = null;
		if (is_array($memo)) {
			return $memo;
		}
		$byCollapsed = [];
		$counts = [];
		foreach ($this->facetDistribution('brand') as $facetValue => $count) {
			$label = trim((string) $facetValue);
			if ($label === '') {
				continue;
			}
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($label)) ?? '';
			if ($collapsed === '' || mb_strlen($collapsed) < 3) {
				continue;
			}
			// Prefer longer/canonical label if collisions (rare).
			if (!isset($byCollapsed[$collapsed]) || mb_strlen($label) > mb_strlen($byCollapsed[$collapsed])) {
				$byCollapsed[$collapsed] = $label;
			}
			$counts[$label] = (int) $count;
		}
		$memo = ['by_collapsed' => $byCollapsed, 'counts' => $counts];
		return $memo;
	}

	/**
	 * "onam luxury" / "unique luxury" look like brand names, not "sort by expensive".
	 */
	private function luxuryLooksLikeBrandFragment(string $utteranceLower): bool
	{
		if (preg_match('/\b([a-z][a-z0-9]{2,})\s+luxury\b/u', $utteranceLower, $m)) {
			$lead = strtolower($m[1]);
			if (!in_array($lead, [
				'the', 'for', 'and', 'very', 'too', 'most', 'more', 'super', 'ultra',
				'real', 'true', 'pure', 'best', 'top', 'buy', 'want', 'need', 'like',
			], true)) {
				return true;
			}
		}
		if (preg_match('/\bluxury\s+([a-z][a-z0-9]{2,})\b/u', $utteranceLower, $m)) {
			$tail = strtolower($m[1]);
			if (!in_array($tail, [
				'perfume', 'perfumes', 'fragrance', 'fragrances', 'cologne', 'colognes',
				'scent', 'scents', 'brand', 'brands', 'item', 'items', 'gift', 'gifts',
			], true)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * True when the utterance is essentially just the brand (optional product-class word).
	 */
	private function utteranceIsPrimarilyBrand(string $utterance, string $brandLabel): bool
	{
		$u = strtolower(trim(preg_replace('/\s+/u', ' ', $utterance) ?? $utterance));
		$u = trim(preg_replace(
			'/\b(?:perfumes?|perfums|perfum|parfums?|fragrances?|colognes?|edt|edp|by)\b/iu',
			' ',
			$u
		) ?? $u);
		$u = trim(preg_replace('/\s+/u', ' ', $u) ?? $u);
		$uCollapsed = preg_replace('/[^a-z0-9]+/u', '', $u) ?? '';
		$brandCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($brandLabel)) ?? '';
		if ($uCollapsed === '' || $brandCollapsed === '') {
			return false;
		}

		// Filler houses ("Want", "Love", "Fresh"): only exact brand-only queries.
		// "I want perfume" / "want perfume" must never become brand=Want.
		if ($this->isAmbiguousFillerBrandToken($brandCollapsed)) {
			$rawTokens = preg_split('/\s+/u', strtolower(trim($utterance)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
			return count($rawTokens) === 1 && $uCollapsed === $brandCollapsed;
		}

		if ($uCollapsed === $brandCollapsed) {
			return true;
		}
		// "roja" / "roja perfume" ≈ house "Roja Parfums" (match first distinctive word).
		$labelParts = preg_split('/\s+/u', strtolower(trim($brandLabel)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		foreach ($labelParts as $part) {
			$partCollapsed = preg_replace('/[^a-z0-9]+/u', '', $part) ?? '';
			if ($partCollapsed === '' || mb_strlen($partCollapsed) < 4 || $this->isAmbiguousFillerBrandToken($partCollapsed)) {
				continue;
			}
			if ($uCollapsed === $partCollapsed) {
				return true;
			}
			$maxPartDist = mb_strlen($partCollapsed) >= 8 ? 2 : 1;
			if ($this->collapsedEditDistance($uCollapsed, $partCollapsed) <= $maxPartDist) {
				return true;
			}
		}
		$maxDist = mb_strlen($brandCollapsed) >= 8 ? 2 : 1;
		return $this->collapsedEditDistance($uCollapsed, $brandCollapsed) <= $maxDist;
	}

	/**
	 * Edit distance on collapsed brand strings. Adjacent letter swaps (akor↔akro)
	 * count as 1 — classic levenshtein treats them as 2 and misses short-brand typos.
	 */
	private function collapsedEditDistance(string $a, string $b): int
	{
		if ($a === $b) {
			return 0;
		}
		$dist = levenshtein($a, $b);
		if ($dist !== 2) {
			return $dist;
		}
		$lenA = strlen($a);
		$lenB = strlen($b);
		if ($lenA !== $lenB || $lenA < 2) {
			return $dist;
		}
		for ($i = 0; $i < $lenA - 1; $i++) {
			if ($a[$i] === $b[$i + 1]
				&& $a[$i + 1] === $b[$i]
				&& substr($a, 0, $i) === substr($b, 0, $i)
				&& substr($a, $i + 2) === substr($b, $i + 2)
			) {
				return 1;
			}
		}
		return $dist;
	}

	/**
	 * Prefer a more specific catalog family token when several appear
	 * (closed perfume taxonomy — not open-ended shopper vocabulary).
	 *
	 * @deprecated Prefer localFragranceConceptsFromUtterance() + catalog resolve.
	 */
	private function resolveFragranceFamilyFromUtterance(string $utterance): ?string
	{
		$concepts = $this->localFragranceConceptsFromUtterance($utterance);
		if ($concepts === []) {
			return null;
		}
		return ucfirst($concepts[0]);
	}

	/**
	 * Pull scent-style concepts mentioned in the utterance (catalog-aware when facets load).
	 *
	 * @return list<string>
	 */
	private function localFragranceConceptsFromUtterance(string $utterance): array
	{
		$u = strtolower(trim($utterance));
		if ($u === '') {
			return [];
		}
		$found = [];
		foreach ($this->fragranceConceptVocabulary() as $concept) {
			if ($concept === '' || mb_strlen($concept) < 3) {
				continue;
			}
			if (preg_match('/\b' . preg_quote($concept, '/') . '\b/u', $u)) {
				$found[] = $concept;
			}
		}
		// Longer / more specific first so "ambergris" wins over bare "amber" ordering later.
		usort($found, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
		return array_values(array_unique($found));
	}

	/**
	 * Unique scent tokens mined from Meili fragrance_family facets only (no hardcoded families).
	 *
	 * @return list<string>
	 */
	private function fragranceConceptVocabulary(): array
	{
		static $vocab = null;
		if (is_array($vocab)) {
			return $vocab;
		}
		$stop = [
			'and', 'the', 'for', 'with', 'from', 'perfume', 'fragrance', 'scent', 'notes', 'note',
			'summer', 'winter', 'spring', 'fall', 'autumn', 'day', 'night', 'men', 'women', 'unisex',
			'eau', 'parfum', 'toilette', 'cologne', 'extrait',
		];
		$stopSet = array_fill_keys($stop, true);
		$tokens = [];
		foreach (array_keys($this->facetDistribution('fragrance_family')) as $facetValue) {
			foreach (preg_split('/[\s,\/\|;&]+/u', strtolower((string) $facetValue)) ?: [] as $bit) {
				$bit = trim(preg_replace('/[^a-z0-9\-]+/u', '', $bit) ?? '');
				if ($bit === '' || isset($stopSet[$bit]) || mb_strlen($bit) < 3 || is_numeric($bit)) {
					continue;
				}
				$tokens[$bit] = true;
			}
		}
		$vocab = array_keys($tokens);
		usort($vocab, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
		return $vocab;
	}

	/**
	 * Collect free-form scent concepts from NLP JSON (fragrance_concepts and/or fragrance_family).
	 *
	 * @param array<string, mixed> $nlp
	 * @return list<string>
	 */
	private function collectFragranceConcepts(array $nlp): array
	{
		$raw = [];
		foreach (['fragrance_concepts', 'fragrance_family'] as $key) {
			if (!array_key_exists($key, $nlp)) {
				continue;
			}
			$val = $nlp[$key];
			if (is_array($val)) {
				foreach ($val as $item) {
					if (is_scalar($item)) {
						$raw[] = (string) $item;
					}
				}
			} elseif (is_scalar($val)) {
				$raw[] = (string) $val;
			}
		}
		$out = [];
		foreach ($raw as $item) {
			$item = strtolower(trim($item));
			if ($item === '') {
				continue;
			}
			// Split compounds the model may still emit ("woody amber", "Woody, Amber").
			foreach (preg_split('/[\s,\/\|;&]+/u', $item) ?: [] as $bit) {
				$bit = trim($bit);
				if ($bit === '' || mb_strlen($bit) < 3) {
					continue;
				}
				$out[] = $bit;
			}
		}
		return array_values(array_unique($out));
	}

	/**
	 * @param array<string, mixed> $extracted
	 * @param array<string, mixed> $local
	 * @return array<string, mixed>
	 */
	private function mergeFragranceConcepts(array $extracted, array $local): array
	{
		$merged = $this->collectFragranceConcepts($extracted);
		foreach ($this->collectFragranceConcepts($local) as $concept) {
			$merged[] = $concept;
		}
		$merged = array_values(array_unique($merged));
		if ($merged !== []) {
			$extracted['fragrance_concepts'] = $merged;
			// Avoid double-processing stale scalar fragrance_family from the model.
			unset($extracted['fragrance_family']);
		}
		return $extracted;
	}

	/**
	 * Map scent concepts onto atomic fragrance_family facet labels.
	 * Prefer short catalog tokens ("Amber Woody") — never ship comma-compounds
	 * ("Almond, Amber Spicy, Amber Woody"). Meili equality on array/split values
	 * already matches products that store those atomics.
	 *
	 * @param list<string> $concepts
	 * @return list<string> raw catalog labels (not encoded)
	 */
	private function resolveFragranceConceptsToCatalogValues(array $concepts): array
	{
		$concepts = array_values(array_filter(array_map(
			static fn ($c) => strtolower(trim((string) $c)),
			$concepts
		), static fn (string $c): bool => $c !== '' && mb_strlen($c) >= 3));
		if ($concepts === []) {
			return [];
		}

		$distribution = $this->facetDistribution('fragrance_family');
		if ($distribution === []) {
			return [];
		}

		// Build atomic label → max product count (split compounds on commas).
		$atomicCounts = [];
		foreach ($distribution as $facetValue => $count) {
			$count = (int) $count;
			foreach ($this->splitFragranceFamilyAtoms((string) $facetValue) as $atom) {
				$key = strtolower($atom);
				$atomicCounts[$key] = [
					'value' => $atom,
					'count' => max($count, (int) ($atomicCounts[$key]['count'] ?? 0)),
				];
			}
		}

		$scored = [];
		foreach ($atomicCounts as $row) {
			$atom = (string) $row['value'];
			$hay = strtolower($atom);
			$hitCount = 0;
			foreach ($concepts as $concept) {
				if ($this->fragranceConceptMatchesHaystack($concept, $hay)) {
					$hitCount++;
				}
			}
			if ($hitCount === 0) {
				continue;
			}
			$scored[] = [
				'value' => $atom,
				'hits' => $hitCount,
				'count' => (int) $row['count'],
				'len' => mb_strlen($atom),
			];
		}
		if ($scored === []) {
			return [];
		}

		$need = count($concepts);
		$allConceptHits = array_values(array_filter($scored, static fn (array $row): bool => $row['hits'] >= $need));
		// Prefer labels matching every concept only when that set is rich enough;
		// otherwise keep all single-concept matches (citrus must not collapse to 3 dual labels).
		$pool = (count($allConceptHits) >= 5) ? $allConceptHits : $scored;

		usort($pool, static function (array $a, array $b): int {
			if ($a['hits'] !== $b['hits']) {
				return $b['hits'] <=> $a['hits'];
			}
			if ($a['count'] !== $b['count']) {
				return $b['count'] <=> $a['count'];
			}
			return $a['len'] <=> $b['len'];
		});

		$max = 40;
		$out = [];
		foreach (array_slice($pool, 0, $max) as $row) {
			// Never emit comma-compounds into the Meili OR filter.
			if (str_contains($row['value'], ',')) {
				continue;
			}
			$out[] = $row['value'];
		}
		return array_values(array_unique($out));
	}

	/**
	 * Split a catalog family string into atomic labels (comma / pipe separated).
	 *
	 * @return list<string>
	 */
	private function splitFragranceFamilyAtoms(string $facetValue): array
	{
		$facetValue = trim($facetValue);
		if ($facetValue === '' || is_numeric($facetValue)) {
			return [];
		}
		$parts = preg_split('/\s*[,|;]\s*/u', $facetValue) ?: [];
		$out = [];
		foreach ($parts as $part) {
			$part = trim($part);
			if ($part === '' || is_numeric($part) || mb_strlen($part) < 3) {
				continue;
			}
			$out[] = $part;
		}
		// Also keep the original when it is already atomic (no comma).
		if ($out === [] && !str_contains($facetValue, ',')) {
			$out[] = $facetValue;
		}
		return array_values(array_unique($out));
	}

	private function fragranceConceptMatchesHaystack(string $concept, string $hay): bool
	{
		$stem = rtrim($concept, 's');
		if ($stem === '' || mb_strlen($stem) < 3) {
			$stem = $concept;
		}
		return (bool) preg_match('/\b' . preg_quote($stem, '/') . 's?\b/u', $hay);
	}

	/**
	 * Keep only atomic family labels in the filter (drop "A, B, C" compounds).
	 *
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function keepAtomicFamilyValuesOnly(array $encodedValues): array
	{
		$kept = [];
		foreach ($encodedValues as $encoded) {
			$raw = trim($this->productSearch->decodeFilterValue((string) $encoded));
			if ($raw === '' || str_contains($raw, ',')) {
				continue;
			}
			$kept[] = $encoded;
		}
		return array_values(array_unique($kept));
	}

	/**
	 * Keep only family labels that exist in the Meili facet card (drop unresolved placeholders).
	 *
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function preferResolvedCatalogFamilyValues(array $encodedValues, string $meiliAttr): array
	{
		$distribution = $this->facetDistribution($meiliAttr);
		if ($distribution === []) {
			return $encodedValues;
		}
		$known = [];
		foreach (array_keys($distribution) as $facetValue) {
			$known[strtolower((string) $facetValue)] = true;
		}
		$kept = [];
		foreach ($encodedValues as $encoded) {
			$raw = trim($this->productSearch->decodeFilterValue((string) $encoded));
			if ($raw === '') {
				continue;
			}
			if (isset($known[strtolower($raw)])) {
				$kept[] = $encoded;
			}
		}
		return $kept !== [] ? array_values(array_unique($kept)) : $encodedValues;
	}

	/**
	 * NLP note intent → only exact/atomic facet selections for the sidebar.
	 * Compounds like "Vanilla and Musk" stay out of selected filters (matched via CONTAINS).
	 *
	 * @param list<string> $expanded
	 * @param list<string> $intentValues
	 * @return list<string>
	 */
	private function preferAtomicNoteIntentValues(array $expanded, array $intentValues): array
	{
		$intentNeedles = [];
		foreach ($intentValues as $enc) {
			$raw = strtolower(trim($this->productSearch->decodeFilterValue((string) $enc)));
			if ($raw === '') {
				continue;
			}
			$intentNeedles[] = [
				'enc' => (string) $enc,
				'raw' => $raw,
				'collapsed' => preg_replace('/[^a-z0-9]+/u', '', $raw) ?? '',
			];
		}
		if ($intentNeedles === []) {
			return [];
		}

		$kept = [];
		foreach ($expanded as $enc) {
			$raw = strtolower(trim($this->productSearch->decodeFilterValue((string) $enc)));
			if ($raw === '' || str_contains($raw, ' and ') || str_contains($raw, ',')) {
				continue;
			}
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', $raw) ?? '';
			foreach ($intentNeedles as $intent) {
				if (
					$raw === $intent['raw']
					|| ($intent['collapsed'] !== '' && $collapsed === $intent['collapsed'])
				) {
					$kept[] = (string) $enc;
					break;
				}
			}
		}

		// Always retain the shopper's note tokens so checkboxes can select them.
		foreach ($intentNeedles as $intent) {
			$kept[] = $intent['enc'];
		}

		return array_values(array_unique($kept));
	}

	/**
	 * Normalize occasion labels already chosen by NLP to nearby catalog values.
	 * Only expand clear wear synonyms — open shopper vocabulary stays with the LLM.
	 *
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function expandOccasionNlpSynonyms(array $encodedValues): array
	{
		$extra = [];
		foreach ($encodedValues as $encoded) {
			$raw = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
			$raw = str_replace(['_', '-'], ' ', $raw);
			if (in_array($raw, ['evening', 'nightlife', 'nighttime', 'night wear', 'evening wear'], true)) {
				$extra[] = $this->productSearch->encodeFilterValue('Night');
			} elseif ($raw === 'night') {
				$extra[] = $this->productSearch->encodeFilterValue('Night');
			}
			if (in_array($raw, ['daytime', 'day wear', 'office', 'office wear', 'work wear'], true)
				|| $raw === 'day'
			) {
				$extra[] = $this->productSearch->encodeFilterValue('Day');
			}
		}
		return array_values(array_unique(array_merge($encodedValues, $extra)));
	}

	/** LLM flagged marketing-copy search (mood, longevity, projection, …). */
	private function nlpWantsDescriptionSearch(array $nlp): bool
	{
		foreach (['search_description', 'searchDescription', 'use_description'] as $key) {
			if (!array_key_exists($key, $nlp)) {
				continue;
			}
			$v = $nlp[$key];
			if ($v === true || $v === 1 || $v === '1') {
				return true;
			}
			if (is_string($v) && in_array(strtolower(trim($v)), ['true', 'yes', 'y'], true)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Quality/longevity phrases appear in almost every marketing blurb — searching description
	 * inflates hit counts far above SearchSpring / title+keywords baseline.
	 */
	private function isDescriptionNoisyQualityUtterance(string $text): bool
	{
		return (bool) preg_match(
			'/\b(?:'
			. 'long[\s\-]?lasting|long[\s\-]?wear|lasts?\s+all\s+day|all[\s\-]?day(?:\s+long)?|'
			. 'lasts?\s+forever|high\s+longevity|strong\s+longevity|good\s+longevity|'
			. 'strong\s+projection|high\s+sillage|long\s+wear'
			. ')\b/iu',
			$text
		);
	}

	/**
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function expandSeasonNlpSynonyms(array $encodedValues): array
	{
		$extra = [];
		foreach ($encodedValues as $encoded) {
			$raw = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
			if (in_array($raw, ['autumn', 'fall'], true)) {
				$extra[] = $this->productSearch->encodeFilterValue('Fall');
				$extra[] = $this->productSearch->encodeFilterValue('Autumn');
			}
		}
		return array_values(array_unique(array_merge($encodedValues, $extra)));
	}

	/**
	 * Canonical season token from an NLP / facet label.
	 */
	private function canonicalSeasonToken(string $raw): string
	{
		$t = strtolower(trim($raw));
		if ($t === 'autumn') {
			return 'fall';
		}
		return $t;
	}

	/**
	 * Seasons that may share a compound facet with the shopper's intent season.
	 * Opposing seasons (Summer vs Fall/Winter, etc.) must not ride along.
	 *
	 * @return list<string>
	 */
	private function seasonCompatibleTokens(string $intent): array
	{
		return match ($this->canonicalSeasonToken($intent)) {
			'summer' => ['summer', 'spring'],
			'winter' => ['winter', 'fall'],
			'spring' => ['spring', 'summer'],
			'fall' => ['fall', 'winter'],
			default => [$this->canonicalSeasonToken($intent)],
		};
	}

	/**
	 * Parse season tokens out of a Meili seasons facet value
	 * (e.g. "Spring-Summer", "fall, summer, spring").
	 *
	 * @return list<string>
	 */
	private function seasonTokensFromFacetValue(string $facetValue): array
	{
		$parts = preg_split('/[\s,\/\-]+/u', strtolower(trim($facetValue))) ?: [];
		$out = [];
		foreach ($parts as $part) {
			$part = trim($part);
			if ($part === '') {
				continue;
			}
			$canon = $this->canonicalSeasonToken($part);
			if (in_array($canon, ['summer', 'winter', 'spring', 'fall'], true)) {
				$out[] = $canon;
			}
		}
		return array_values(array_unique($out));
	}

	/**
	 * True when a catalog seasons value honestly satisfies a single-season intent.
	 * Fall-Winter must not match Summer; all-season catch-alls that mix cold+warm are rejected.
	 */
	private function seasonFacetMatchesIntent(string $facetValue, string $intentSeason): bool
	{
		$intent = $this->canonicalSeasonToken($intentSeason);
		if (!in_array($intent, ['summer', 'winter', 'spring', 'fall'], true)) {
			return strcasecmp(trim($facetValue), trim($intentSeason)) === 0;
		}
		$tokens = $this->seasonTokensFromFacetValue($facetValue);
		if ($tokens === []) {
			return $this->canonicalSeasonToken($facetValue) === $intent;
		}
		if (!in_array($intent, $tokens, true)) {
			return false;
		}
		$allowed = $this->seasonCompatibleTokens($intent);
		foreach ($tokens as $token) {
			if (!in_array($token, $allowed, true)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * After broad facet expand, keep only season values compatible with each NLP intent.
	 *
	 * @param list<string> $intentEncoded  NLP season labels before expand
	 * @param list<string> $expandedEncoded
	 * @return list<string>
	 */
	private function filterExpandedSeasonsByIntent(array $intentEncoded, array $expandedEncoded): array
	{
		$intents = [];
		foreach ($intentEncoded as $encoded) {
			$raw = trim($this->productSearch->decodeFilterValue((string) $encoded));
			if ($raw !== '') {
				$intents[] = $raw;
			}
		}
		if ($intents === []) {
			return $expandedEncoded;
		}
		$kept = [];
		foreach ($expandedEncoded as $encoded) {
			$facet = trim($this->productSearch->decodeFilterValue((string) $encoded));
			if ($facet === '') {
				continue;
			}
			foreach ($intents as $intent) {
				if ($this->seasonFacetMatchesIntent($facet, $intent)) {
					$kept[] = $encoded;
					break;
				}
			}
		}
		return array_values(array_unique($kept));
	}

	/**
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function expandFamilyNlpSynonyms(array $encodedValues): array
	{
		// No closed synonym map — pass through whatever NLP/catalog produced.
		return array_values(array_unique($encodedValues));
	}

	/**
	 * Drop tokens already captured as filters; keep the strongest leftover product word(s).
	 *
	 * @param array<string, mixed> $filters
	 */
	private function contentKeywordAfterFilters(string $utterance, array $filters): string
	{
		$q = $this->expandPriceShorthandInQuery($utterance);
		$q = preg_replace('/\$\s*[\d,]+(?:\.\d+)?\s*[kmb]?/iu', ' ', $q) ?? $q;
		$q = preg_replace('/\b\d+(?:\.\d+)?\s*[kmb]\b/iu', ' ', $q) ?? $q;
		if (
			(isset($filters['maxprice']) && $filters['maxprice'] !== '' && $filters['maxprice'] !== null)
			|| (isset($filters['minprice']) && $filters['minprice'] !== '' && $filters['minprice'] !== null)
		) {
			$q = preg_replace('/\b(?:under|below|less\s+than|over|above|at\s+least|upto|up\s+to|than)\b/iu', ' ', $q) ?? $q;
		}
		if (!empty($filters['gender'])) {
			// Recipient / relationship cues already mapped to gender — never keep in q
			// (matchingStrategy=all would otherwise require "wife" in the product text).
			$q = preg_replace(
				'/\b(?:men|man|women|woman|unisex|male|female|guys|ladies|him|her|hers|his|wife|wives|husband|husbands|girlfriend|boyfriend|mother|mom|mum|sister|daughter|father|dad|brother|son|spouse|partner|fiance|fiancée|bride|groom)\b/iu',
				' ',
				$q
			) ?? $q;
		}
		if (!empty($filters['fragrance_occasion'])) {
			$q = preg_replace(
				'/\b(?:evening|nightlife|nighttime|daytime|office|workplace|workday|work\s*wear|casual\s*wear|day\s*wear|night\s*wear)\b/iu',
				' ',
				$q
			) ?? $q;
		}
		if (!empty($filters['sortby'])) {
			$q = preg_replace(
				'/\b(?:expensive|affordable|cheap|budget|luxury|premium|pricey|costly|inexpensive|bargain|high[\s-]?end)\b/iu',
				' ',
				$q
			) ?? $q;
			$q = preg_replace('/\b(?:isn\'?t|aren\'?t|aint|not|too|very|that)\b/iu', ' ', $q) ?? $q;
		}
		if (!empty($filters['rating_avg']) || !empty($filters['OnTopRated'])) {
			$q = preg_replace(
				'/\b(?:best|top|highest|highly|rated|rating|ratings|stars?|five|four|three)\b/iu',
				' ',
				$q
			) ?? $q;
		}
		if (!empty($filters['fragrance_seasons'])) {
			$q = preg_replace('/\b(?:summer|winter|spring|fall|autumn)\b/iu', ' ', $q) ?? $q;
		}
		// Drop tokens only from applied equality facet labels (dynamic — never a fixed scent list).
		// fragrance_family_contains concepts stay in q so any extracted term can match product names.
		if (!empty($filters['fragrance_family']) && empty($filters['fragrance_family_contains']) && is_array($filters['fragrance_family'])) {
			foreach ($filters['fragrance_family'] as $encoded) {
				$decoded = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
				foreach (preg_split('/[\s,\/\|;&:\-]+/u', $decoded) ?: [] as $part) {
					$part = preg_replace('/[^a-z0-9]+/i', '', $part) ?? '';
					if ($part !== '' && mb_strlen($part) >= 3) {
						$q = preg_replace('/\b' . preg_quote($part, '/') . '\b/iu', ' ', $q) ?? $q;
					}
				}
			}
		}
		if (!empty($filters['categories'])) {
			// Drop words already captured by category facets (parent / leaf tokens).
			foreach ($filters['categories'] as $encoded) {
				$decoded = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
				foreach (preg_split('/\s*:\s*|\s+/u', $decoded) ?: [] as $part) {
					$part = preg_replace('/[^a-z0-9]+/i', '', $part) ?? '';
					if ($part !== '' && mb_strlen($part) >= 3) {
						$q = preg_replace('/\b' . preg_quote($part, '/') . '\b/iu', ' ', $q) ?? $q;
					}
				}
			}
		}
		if (!empty($filters['special']) && is_array($filters['special'])) {
			$q = preg_replace('/\b(?:new\s+arrivals?|latest\s+arrivals?|just\s+in|newest)\b/iu', ' ', $q) ?? $q;
			foreach ($filters['special'] as $encoded) {
				$decoded = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
				foreach (preg_split('/[\s,\/\|;&:\-]+/u', $decoded) ?: [] as $part) {
					$part = preg_replace('/[^a-z0-9]+/i', '', $part) ?? '';
					if ($part !== '' && mb_strlen($part) >= 3) {
						$q = preg_replace('/\b' . preg_quote($part, '/') . '\b/iu', ' ', $q) ?? $q;
					}
				}
			}
		}
		if (!empty($filters['brands'])) {
			// Drop brand tokens already applied as a brand facet (incl. concatenated labels).
			foreach ($filters['brands'] as $encoded) {
				$decoded = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
				$collapsed = preg_replace('/[^a-z0-9]+/u', '', $decoded) ?? '';
				foreach (preg_split('/[\s,\/\|;&:\-]+/u', $decoded) ?: [] as $part) {
					$part = preg_replace('/[^a-z0-9]+/i', '', $part) ?? '';
					if ($part !== '' && mb_strlen($part) >= 3) {
						$q = preg_replace('/\b' . preg_quote($part, '/') . '\b/iu', ' ', $q) ?? $q;
					}
				}
				// Also strip the full collapsed brand from a spaced query ("onam luxury" vs OMANLUXURY).
				if ($collapsed !== '' && mb_strlen($collapsed) >= 4) {
					$qCollapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($q)) ?? '';
					if ($qCollapsed === $collapsed || levenshtein($qCollapsed, $collapsed) <= 2) {
						$q = '';
					}
				}
			}
		}
		// Drop only ultra-generic product-type words when scent filters already apply.
		if ($this->hasScentIntentFilters($filters)) {
			$q = preg_replace('/\b(?:perfume|perfumes|fragrance|fragrances|candle|candles)\b/iu', ' ', $q) ?? $q;
		}
		$tokens = preg_split('/\s+/u', strtolower(trim(preg_replace('/\s+/u', ' ', $q) ?? $q)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
		$kept = [];
		foreach ($tokens as $t) {
			$t = preg_replace('/[^a-z0-9]+/i', '', $t) ?? '';
			if ($t === '' || is_numeric($t) || mb_strlen($t) < 3) {
				continue;
			}
			// Non-catalog filler / conversational tokens (same class as jewelry NLP "ignore in query").
			if (in_array($t, [
				'for', 'with', 'from', 'that', 'this', 'need', 'want', 'like', 'wear', 'the', 'and', 'are',
				'please', 'gift', 'gifts', 'present', 'presents',
				'isnt', 'arent', 'aint', 'not', 'too', 'very', 'office', 'workplace',
			], true)) {
				continue;
			}
			$kept[] = $t;
		}
		if ($kept === []) {
			return '';
		}
		// Preserve utterance order; keep enough tokens for phrases like "long lasting oud".
		return implode(' ', array_slice(array_values(array_unique($kept)), 0, 8));
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function hasStructuredIntentFilters(array $filters): bool
	{
		if ($this->hasScentIntentFilters($filters)) {
			return true;
		}
		if (!empty($filters['gender']) || !empty($filters['categories']) || !empty($filters['brands'])) {
			return true;
		}
		if (!empty($filters['special']) && is_array($filters['special'])) {
			return true;
		}
		if (!empty($filters['rating_avg']) || !empty($filters['OnTopRated'])) {
			return true;
		}
		if (
			(isset($filters['maxprice']) && $filters['maxprice'] !== '' && $filters['maxprice'] !== null)
			|| (isset($filters['minprice']) && $filters['minprice'] !== '' && $filters['minprice'] !== null)
		) {
			return true;
		}
		return false;
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function hasProductAttributeFilters(array $filters): bool
	{
		foreach (['fragrance_family', 'fragrance_family_contains', 'fragrance_notes_contains', 'fragrance_seasons', 'fragrance_notes', 'top_note', 'middle_note', 'base_note'] as $key) {
			if (!empty($filters[$key])) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function hasScentIntentFilters(array $filters): bool
	{
		if ($this->hasProductAttributeFilters($filters)) {
			return true;
		}
		return !empty($filters['fragrance_occasion']);
	}

	/**
	 * When shopper gender is Women (or Men), drop opposite-gender Fragrance leaves
	 * that were pulled in by expanding parent category "Fragrance".
	 * Keeps Unisex / Niche / bare parent and matching gender leaves.
	 *
	 * @param list<string> $encodedCategories
	 * @param list<string>|string $genderCodes Meili codes M/W/U
	 * @return list<string>
	 */
	private function filterCategoriesByGenderIntent(array $encodedCategories, $genderCodes): array
	{
		$codes = [];
		foreach (is_array($genderCodes) ? $genderCodes : [$genderCodes] as $g) {
			if (!is_scalar($g)) {
				continue;
			}
			$c = strtoupper(trim((string) $g));
			if ($c !== '') {
				$codes[$c] = true;
			}
		}
		$wantWomen = isset($codes['W']);
		$wantMen = isset($codes['M']);
		// Both genders (or neither) → do not prune.
		if ($wantWomen === $wantMen) {
			return array_values($encodedCategories);
		}

		$out = [];
		foreach ($encodedCategories as $encoded) {
			$decoded = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
			if ($decoded === '') {
				continue;
			}
			// Leaf / label after "Parent :"
			$leaf = $decoded;
			if (str_contains($decoded, ':')) {
				$parts = preg_split('/\s*:\s*/u', $decoded, 2) ?: [];
				$leaf = strtolower(trim((string) ($parts[1] ?? $decoded)));
			}
			$isWomenLeaf = (bool) preg_match('/\bwomen\b/u', $leaf);
			$isMenLeaf = (bool) preg_match('/\bmen\b/u', $leaf) && !$isWomenLeaf;

			if ($wantWomen && $isMenLeaf) {
				continue;
			}
			if ($wantMen && $isWomenLeaf) {
				continue;
			}
			$out[] = (string) $encoded;
		}

		return array_values(array_unique($out));
	}

	/**
	 * @param list<string> $encodedCategories
	 */
	private function keywordFromCategoryFilters(array $encodedCategories): string
	{
		foreach ($encodedCategories as $encoded) {
			$decoded = trim($this->productSearch->decodeFilterValue((string) $encoded));
			if ($decoded === '') {
				continue;
			}
			$parent = trim(explode(':', $decoded, 2)[0]);
			if ($parent !== '') {
				return $parent;
			}
		}
		return '';
	}

	/**
	 * Expand NLP facet labels to real Meili facet values.
	 * Prefer parent/exact matches; use word-containment only for multi-word labels
	 * (so "Gift Sets" can match "Fragrance : Men Gift Sets", but "Perfume" does not
	 * steal "Pocket Perfume").
	 *
	 * Always retain the original NLP label — Meili facetDistribution is truncated and
	 * may omit exact values that still match equality filters.
	 *
	 * @param list<string> $encodedValues
	 * @return list<string>
	 */
	private function expandEncodedFacetValues(string $meiliAttribute, array $encodedValues): array
	{
		if ($meiliAttribute === '' || $encodedValues === []) {
			return $encodedValues;
		}
		$distribution = $this->facetDistribution($meiliAttribute);
		if ($distribution === []) {
			return $encodedValues;
		}

		// Brands: never expand a single word like "fresh" into every house containing
		// that word (Aura Fresh, Wonderlust Eau Fresh, …). Exact / collapsed only.
		// Notes: same — "vanilla" must not select "Vanilla and Jasmine", "Musk and Amber", …
		$isBrandAttr = ($meiliAttribute === 'brand');
		$isNotesAttr = ($meiliAttribute === 'fragrance_notes');
		$isBadgesAttr = ($meiliAttribute === 'badges');

		$expanded = [];
		foreach ($encodedValues as $encoded) {
			$needle = strtolower(trim($this->productSearch->decodeFilterValue((string) $encoded)));
			if ($needle === '') {
				continue;
			}
			$needles = [$needle];
			// Light plural fold only for multi-word labels ("gift sets" → "gift set").
			if (str_contains($needle, ' ') && str_ends_with($needle, 's')) {
				$needles[] = substr($needle, 0, -1);
			}
			$strict = [];
			$segment = [];
			foreach ($needles as $n) {
				if ($n === '' || mb_strlen($n) < 3) {
					continue;
				}
				$multiWord = str_contains(trim($n), ' ');
				$needleCollapsed = preg_replace('/[^a-z0-9]+/u', '', $n) ?? '';
				foreach ($distribution as $facetValue => $_count) {
					$facetValue = (string) $facetValue;
					$hay = strtolower($facetValue);
					$hayCollapsed = preg_replace('/[^a-z0-9]+/u', '', $hay) ?? '';
					if (
						$hay === $n
						|| ($needleCollapsed !== '' && $hayCollapsed === $needleCollapsed)
						|| str_starts_with($hay, $n . ' :')
						|| str_starts_with($hay, $n . ':')
					) {
						$strict[] = $this->productSearch->encodeFilterValue($facetValue);
						continue;
					}
					// Brand / notes / badges soft-expand is unsafe (token ⊆ compound facet labels).
					if ($isBrandAttr || $isNotesAttr || $isBadgesAttr) {
						continue;
					}
					// Single-word labels: match as a token anywhere (comma OR hyphen compounds),
					// so truncated facet cards still surface related compound values.
					if (!$multiWord && preg_match('/\b' . preg_quote($n, '/') . '\b/u', $hay)) {
						$segment[] = $this->productSearch->encodeFilterValue($facetValue);
						continue;
					}
					foreach (preg_split('/\s*:\s*/u', $hay) ?: [] as $seg) {
						foreach (preg_split('/\s*,\s*/u', trim($seg)) ?: [] as $part) {
							$part = trim($part);
							if ($part === '') {
								continue;
							}
							if ($part === $n || str_starts_with($part, $n . ' ') || str_ends_with($part, ' ' . $n)) {
								$segment[] = $this->productSearch->encodeFilterValue($facetValue);
								continue 3;
							}
							foreach (preg_split('/[\-\/]+/u', $part) ?: [] as $bit) {
								$bit = trim($bit);
								if ($bit !== '' && $bit === $n) {
									$segment[] = $this->productSearch->encodeFilterValue($facetValue);
									continue 4;
								}
							}
							// Multi-word labels may appear inside a leaf ("Men Gift Sets").
							if ($multiWord && preg_match('/\b' . preg_quote($n, '/') . '\b/u', $part)) {
								$segment[] = $this->productSearch->encodeFilterValue($facetValue);
								continue 3;
							}
						}
					}
				}
			}
			// Keep the original NLP label so truncated facet cards cannot drop a real equality match.
			$matched = array_values(array_unique(array_merge([(string) $encoded], $strict, $segment)));
			foreach ($matched as $enc) {
				$expanded[] = $enc;
			}
		}

		return array_values(array_unique($expanded));
	}

	/**
	 * @return array<string, int>
	 */
	private function facetDistribution(string $meiliAttribute): array
	{
		static $memo = [];
		if (isset($memo[$meiliAttribute])) {
			return $memo[$meiliAttribute];
		}
		$cacheKey = 'meili_facet_dist:v4:' . $this->productSearch->indexName() . ':' . $meiliAttribute;
		$dist = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($meiliAttribute) {
			try {
				if (in_array($meiliAttribute, ['fragrance_family', 'brand', 'category'], true)) {
					$this->ensureLargeFacetCardSize();
				}
				$index = $this->client->index($this->productSearch->indexName());
				$result = $index->search('', [
					'limit' => 0,
					'facets' => [$meiliAttribute],
				]);
				$raw = method_exists($result, 'getRaw') ? $result->getRaw() : [];
				$distribution = $raw['facetDistribution'][$meiliAttribute] ?? [];
				return is_array($distribution) ? $distribution : [];
			} catch (Throwable $e) {
				Log::warning('NLP facet distribution failed: ' . $e->getMessage(), [
					'attribute' => $meiliAttribute,
				]);
				return [];
			}
		});
		$memo[$meiliAttribute] = is_array($dist) ? $dist : [];
		return $memo[$meiliAttribute];
	}

	/**
	 * Meili default facet card is 100; brand/family catalogs need far more so NLP
	 * typo matching (ROAJ→Roja) sees every house — not only the top 1000.
	 */
	private function ensureLargeFacetCardSize(): void
	{
		static $done = false;
		if ($done) {
			return;
		}
		$done = true;
		$target = 10000;
		try {
			$index = $this->client->index($this->productSearch->indexName());
			$settings = $index->getSettings();
			$faceting = is_array($settings['faceting'] ?? null) ? $settings['faceting'] : [];
			$current = (int) ($faceting['maxValuesPerFacet'] ?? 100);
			$typo = is_array($settings['typoTolerance'] ?? null) ? $settings['typoTolerance'] : [];
			$minWord = is_array($typo['minWordSizeForTypos'] ?? null) ? $typo['minWordSizeForTypos'] : [];
			$oneTypo = (int) ($minWord['oneTypo'] ?? 5);
			$needFacets = $current < $target;
			$needTypo = $oneTypo > 4;
			if (!$needFacets && !$needTypo) {
				return;
			}
			$patch = [];
			if ($needFacets) {
				$patch['faceting'] = array_merge($faceting, ['maxValuesPerFacet' => $target]);
			}
			if ($needTypo) {
				$patch['typoTolerance'] = array_merge($typo, [
					'enabled' => true,
					'minWordSizeForTypos' => array_merge($minWord, [
						'oneTypo' => 4,
						'twoTypos' => (int) ($minWord['twoTypos'] ?? 8),
					]),
				]);
			}
			$task = $index->updateSettings($patch);
			$uid = is_array($task) ? ($task['taskUid'] ?? null) : null;
			if ($uid !== null) {
				$this->client->waitForTask($uid, 60000);
			}
			// Drop stale facet caches capped at the old 1000.
			Cache::forget('meili_facet_dist:v3:' . $this->productSearch->indexName() . ':brand');
			Cache::forget('meili_facet_dist:v3:' . $this->productSearch->indexName() . ':fragrance_family');
			Cache::forget('meili_facet_dist:v4:' . $this->productSearch->indexName() . ':brand');
			Cache::forget('meili_facet_dist:v4:' . $this->productSearch->indexName() . ':fragrance_family');
		} catch (Throwable $e) {
			Log::warning('Could not raise Meili facet/typo settings: ' . $e->getMessage());
		}
	}

	/**
	 * Map shopper / LLM badge labels to indexed Meili badge strings.
	 *
	 * @param array<string, mixed> $nlp
	 * @return array<string, mixed>
	 */
	private function canonicalizeBadgeIntent(array $nlp, string $utterance): array
	{
		$u = strtolower(trim($utterance));
		if ($u !== '' && preg_match('/\bnew\s+arrivals?\b/u', $u)) {
			$nlp['badges'] = 'New Arrivals';
		}
		foreach (['badges', 'special'] as $key) {
			if (!array_key_exists($key, $nlp)) {
				continue;
			}
			$val = $nlp[$key];
			if (is_string($val) && strcasecmp(trim($val), 'New') === 0 && preg_match('/\bnew\s+arrivals?\b/u', $u)) {
				$nlp['badges'] = 'New Arrivals';
				unset($nlp['special']);
			}
		}
		return $nlp;
	}

	private function filterKeyToMeiliAttribute(string $filterKey): string
	{
		$map = [
			'brands' => 'brand',
			'categories' => 'category',
			'fragrance_family' => 'fragrance_family',
			'vtype' => 'type',
			'top_note' => 'top_note',
			'middle_note' => 'middle_note',
			'base_note' => 'base_note',
			'fragrance_notes' => 'fragrance_notes',
			'fragrance_seasons' => 'seasons',
			'fragrance_occasion' => 'occasion',
			'fragrance_personality' => 'personality',
			'size' => 'size',
			'formulation' => 'formulation',
			'features' => 'by_feature',
			'special' => 'badges',
			'coverage' => 'coverage',
			'finish' => 'finish',
			'skin_type' => 'skin_type',
		];
		return $map[$filterKey] ?? $filterKey;
	}

	/**
	 * @param array<string, mixed> $nlpFilters
	 * @param array<string, mixed> $userFilters
	 * @return array<string, mixed>
	 */
	public function mergeNlpFiltersPreserveUser(array $nlpFilters, array $userFilters): array
	{
		$merged = $userFilters;
		foreach ($nlpFilters as $key => $nlpVal) {
			if ($this->userHasFilterValue($userFilters, (string) $key)) {
				continue;
			}
			$merged[$key] = $nlpVal;
		}
		return $merged;
	}

	/**
	 * @param array<string, mixed> $extracted
	 * @param list<string> $concepts
	 * @param array<string, mixed> $filters
	 */
	private function logNlpExtraction(
		string $query,
		string $source,
		array $extracted,
		array $concepts,
		string $appliedKeyword,
		array $filters
	): void {
		$familyFilters = is_array($filters['fragrance_family'] ?? null) ? $filters['fragrance_family'] : [];
		$familyDecoded = [];
		foreach (array_slice($familyFilters, 0, 25) as $enc) {
			$familyDecoded[] = $this->productSearch->decodeFilterValue((string) $enc);
		}
		$contains = [];
		if (!empty($filters['fragrance_family_contains']) && is_array($filters['fragrance_family_contains'])) {
			foreach ($filters['fragrance_family_contains'] as $c) {
				if (is_scalar($c) && trim((string) $c) !== '') {
					$contains[] = strtolower(trim((string) $c));
				}
			}
		}

		Log::info('NLP extract', [
			'query' => $query,
			'source' => $source,
			'extracted' => $extracted,
			'fragrance_concepts' => $concepts !== [] ? $concepts : $contains,
			'fragrance_family_contains' => $contains,
			'resolved_family_count' => count($familyFilters),
			'resolved_family_sample' => $familyDecoded,
			'applied_keyword' => $appliedKeyword,
			'applied_filters' => $this->summarizeFiltersForLog($filters),
			'similar_to' => $this->summarizeSimilarToForLog($filters, $extracted, $appliedKeyword),
		]);
	}

	/**
	 * Compact similar_to_product debug for logs (null when not a similar-to query).
	 *
	 * @param array<string, mixed> $filters
	 * @param array<string, mixed> $extracted
	 * @return array<string, mixed>|null
	 */
	private function summarizeSimilarToForLog(array $filters, array $extracted, string $appliedKeyword): ?array
	{
		$refName = trim((string) ($filters['_similar_to_name'] ?? ''));
		$refProduct = trim((string) ($extracted['similar_to_product'] ?? ''));
		if ($refName === '' && $refProduct === '') {
			return null;
		}

		$out = [
			'intent' => 'similar_to_product',
			'reference_product' => $refName !== '' ? $refName : $refProduct,
			'reference_uid' => $filters['_similar_to_uid'] ?? ($filters['exclude_uid'] ?? null),
			'reference_brand' => $filters['_similar_to_brand'] ?? null,
			'price_constraint' => $extracted['price_constraint'] ?? null,
			'reference_price' => $filters['_similar_to_price'] ?? null,
			'reference_price_type' => $filters['_similar_to_price_type'] ?? null,
			'reference_variant' => $filters['_similar_to_variant'] ?? ($extracted['reference_variant'] ?? null),
			'maxprice' => $filters['maxprice'] ?? null,
			'gender' => $filters['gender'] ?? null,
			'brands' => $filters['brands'] ?? null,
			'fragrance_family_contains' => $filters['fragrance_family_contains'] ?? null,
			'rank_keyword' => $appliedKeyword,
			'exclude_same_line_tokens' => $filters['_exclude_same_line_tokens'] ?? null,
		];

		return array_filter(
			$out,
			static fn ($v) => $v !== null && $v !== '' && $v !== []
		);
	}

	/**
	 * Decode filter values for readable logs (skip internal ranking hints).
	 *
	 * @param array<string, mixed> $filters
	 * @return array<string, mixed>
	 */
	private function summarizeFiltersForLog(array $filters): array
	{
		$out = [];
		foreach ($filters as $key => $value) {
			if ($key === '_rank_query') {
				continue;
			}
			if (is_array($value)) {
				$decoded = [];
				foreach (array_slice($value, 0, 30) as $item) {
					if (!is_scalar($item)) {
						continue;
					}
					$decoded[] = $this->productSearch->decodeFilterValue((string) $item);
				}
				if (count($value) > 30) {
					$decoded[] = '… +' . (count($value) - 30) . ' more';
				}
				$out[$key] = $decoded;
				continue;
			}
			if (is_scalar($value) || $value === null) {
				$out[$key] = $value;
			}
		}
		return $out;
	}

	/**
	 * @param array<string, mixed> $extracted
	 * @param array<string, mixed> $mappedFilters
	 */
	public function rememberSession(string $orgKeyword, array $extracted, string $appliedKeyword, array $mappedFilters): void
	{
		$orgKeyword = trim($orgKeyword);
		if ($orgKeyword === '') {
			return;
		}
		$keySrc = $this->nlpCacheKeySource($orgKeyword);
		Session::put('nlp_search_filters', [
			'keyword_hash' => md5($keySrc),
			'applied_keyword_hash' => $appliedKeyword !== '' ? md5(mb_strtolower(trim($appliedKeyword))) : null,
			'org_keyword' => $orgKeyword,
			'applied_keyword' => $appliedKeyword,
			'extracted' => $extracted,
			'filters' => $mappedFilters,
			'at' => time(),
		]);
	}

	/** Drop Top Rated / In Stock / On Sale from the NLP session after Clear all / Reset all. */
	public function clearSessionToolbarToggles(): void
	{
		$state = Session::get('nlp_search_filters');
		if (!is_array($state) || !is_array($state['filters'] ?? null)) {
			return;
		}
		foreach (['OnTopRated', 'InStockItems', 'OnSaleDeal'] as $key) {
			unset($state['filters'][$key]);
			if (is_array($state['extracted'] ?? null)) {
				unset($state['extracted'][$key]);
			}
		}
		Session::put('nlp_search_filters', $state);
	}

	/**
	 * Reuse a prior searchbar/PLP NLP plan for the same query (default 5 minutes).
	 *
	 * @return array{keyword: string, filters: array<string, mixed>, extracted: array<string, mixed>}|null
	 */
	private function planFromFreshSession(string $normalizedKeyword): ?array
	{
		$state = Session::get('nlp_search_filters');
		if (!$this->sessionMatchesKeyword(is_array($state) ? $state : null, $normalizedKeyword)) {
			return null;
		}
		if (!is_array($state)) {
			return null;
		}
		$ttl = max(60, (int) env('OPENAI_NLP_SESSION_SECONDS', 300));
		if (isset($state['at']) && (time() - (int) $state['at']) > $ttl) {
			return null;
		}
		$filters = is_array($state['filters'] ?? null) ? $state['filters'] : [];
		$extracted = is_array($state['extracted'] ?? null) ? $state['extracted'] : [];
		if ($filters === [] && $extracted === [] && trim((string) ($state['applied_keyword'] ?? '')) === '') {
			return null;
		}
		return [
			'keyword' => (string) ($state['applied_keyword'] ?? ''),
			'filters' => $filters,
			'extracted' => $extracted,
		];
	}

	/**
	 * @param array<string, mixed>|null $state
	 */
	public function sessionMatchesKeyword(?array $state, string $normalizedKeyword): bool
	{
		if (!is_array($state) || $normalizedKeyword === '') {
			return false;
		}
		$needle = trim($normalizedKeyword);
		$needleKey = $this->nlpCacheKeySource($needle);
		if ($needleKey === '') {
			return false;
		}
		$hash = md5($needleKey);
		$org = trim((string) ($state['org_keyword'] ?? ''));

		// Strict: only the same shopper query reuses the session plan.
		// Never match on applied_keyword (e.g. many NL queries collapse to "perfume").
		return ($state['keyword_hash'] ?? '') === $hash
			|| ($org !== '' && $this->nlpCacheKeySource($org) === $needleKey);
	}

	/** Drop NLP session when the shopper changed the query. */
	public function forgetSessionIfQueryChanged(string $normalizedKeyword): void
	{
		$state = Session::get('nlp_search_filters');
		if (!is_array($state)) {
			return;
		}
		if (!$this->sessionMatchesKeyword($state, $normalizedKeyword)) {
			Session::forget('nlp_search_filters');
		}
	}

	public function forgetSession(): void
	{
		Session::forget('nlp_search_filters');
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function fetchFromOpenAi(string $userQuery): ?array
	{
		$apiKey = EnvSecret::decrypt((string) (
			config('global.OPENAI_SEARCH_API_KEY', env('OPENAI_SEARCH_API_KEY', ''))
		));
		$apiKey = is_string($apiKey) ? trim($apiKey) : '';
		if ($apiKey === '') {
			return null;
		}

		$prompt = $this->buildPrompt();
		$model = (string) env('OPENAI_NLP_MODEL', 'gpt-4.1-mini');
		$payload = [
			'model' => $model,
			'messages' => [['role' => 'user', 'content' => $prompt . "\nUser: " . $userQuery]],
			'temperature' => 0,
		];

		$connectTimeout = max(1, (int) env('OPENAI_NLP_CONNECT_TIMEOUT', 3));
		$totalTimeout = max($connectTimeout + 1, (int) env('OPENAI_NLP_TIMEOUT', 15));

		$ch = curl_init('https://api.openai.com/v1/chat/completions');
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_CONNECTTIMEOUT => $connectTimeout,
			CURLOPT_TIMEOUT => $totalTimeout,
			CURLOPT_NOSIGNAL => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . $apiKey,
				'Content-Type: application/json',
			],
			CURLOPT_POSTFIELDS => json_encode($payload),
		]);
		$res = curl_exec($ch);
		$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($res === false || $httpCode !== 200) {
			$this->tripOpenAiCircuit();
			Log::warning('OpenAI NLP request failed', ['http' => $httpCode]);
			return null;
		}

		Log::info('OpenAI NLP fetch', ['query' => $userQuery, 'http' => $httpCode]);

		$data = json_decode($res, true);
		$content = $data['choices'][0]['message']['content'] ?? '';
		$extracted = [];
		if (preg_match('/Output:\s*(\{.*\})/s', $content, $matches)) {
			$jsonString = $matches[1];
			$extracted = json_decode($jsonString, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				$jsonString = str_replace("'", '"', $jsonString);
				$extracted = json_decode($jsonString, true);
			}
		} elseif (preg_match('/(\{.*\})/s', $content, $matches)) {
			$extracted = json_decode($matches[1], true);
		}

		if (!is_array($extracted)) {
			$extracted = [];
		}
		return $extracted;
	}

	private function buildPrompt(): string
	{
		$validOccasions = 'Day, Night, Everyday use, Special Occasions, Weddings, Beach, Casual Wear, Statement wear';
		$validSeasons = 'Summer, Winter, Spring, Fall';
		$validCategories = 'Fragrance, Pocket Perfume, Gift Sets, Candles, Bath & Body, Face, Eyes, Lips, Hair, Skincare, Makeup, Sunglasses, Unboxed';
		$validTypes = 'Eau De Parfum, Eau De Toilette, Extrait de Parfum, Eau De Cologne, Candle, Gift Set, Cologne, Attar, Body Lotion, Body Mist, Deodorant';

		return <<<EOD
You are a fragrance specialist who needs to understand customer preferences and extract structured filters from natural language queries for a perfume/beauty retailer (Maxaroma).

## GOAL
Understand the shopper's INTENT for ANY wording — not only the examples. Map intent onto the SAME filters shoppers see on the PLP. Prefer fewer correct filters over speculative ones. Generalize every rule to paraphrases, typos, and novel phrasing. If a filter is listed below, you MAY set it whenever intent is clear — do not leave it as free text when a facet exists.

## VALID FILTER FIELDS (emit only these keys; backend resolves labels against the live catalog)
fragrance_concepts: short lowercase scent tokens (woody, amber, floral, oud, vanilla, …). Backend resolves to Fragrance Family. Prefer this over hard fragrance_family labels.
brand: manufacturer / house name (correct typos). Never from fillers (best, top, good, nice) or rating words.
category: $validCategories (plus tester leaves when relevant: Men Testers, Women Testers, …)
type: $validTypes
seasons: $validSeasons
occasion: $validOccasions
gender: Women, Men, Unisex
size: bottle/size phrases as shoppers say them (e.g. 100ml, 3.4 oz, travel size) — backend normalizes
badges / special: Special offers (e.g. On Sale, New Arrivals, Deal of the Week) when clearly asked. For "new arrivals" use badges "New Arrivals" (not "New").
formulation: e.g. Spray, Oil, Rollerball when clearly asked
by_feature / features: product features when clearly asked
personality: fragrance personality when clearly asked
fragrance_notes / notes: specific note names (bergamot, vanilla, sandalwood, …) — use when shopper asks for a note, not only a family
rating_avg: "5", "4", or "3" (Star Rating buckets; 5 = avg >= 4.75, 4 = 4–4.74 as "4+", 3 = 3–3.99 as "3+")
OnTopRated: true with rating_avg for top/best/highest-rated intent
coverage / finish / skin_type: beauty facets only when clearly relevant
min_price / max_price: USD decimals
in_stock: boolean
SortBy: PriceAsc, PriceDesc
search_description: boolean — true for scene/mood prose rare in titles; false for longevity/quality words that flood descriptions
similar_to_product: product/perfume NAME used as a reference ("Dior Sauvage", "Baccarat Rouge 540"). Use for like / similar to / alternative to / dupe of intents. Backend resolves the catalog product and seeds scent attributes. Do NOT put the reference into brand unless the shopper also constrains the house separately.
price_constraint: "lower_than_reference" when they want cheaper / more affordable / less expensive than that reference (no explicit dollar cap). If they say under \$X, use max_price instead.
query: leftover product-class / product-name text. Omit tokens already mapped to filters.

## CORE PRINCIPLE — INTENT FIRST (critical)
For EVERY query, classify intent before extracting words. These intents apply to ANY similar phrasing:

1. **RATING / REVIEW QUALITY** — best rating, top rated, highest rated, highly rated, best reviews, 5 star, …
   → rating_avg "5" (or 4/3 if asked) + OnTopRated true. Never brand "Best".

2. **BRAND / HOUSE** — manufacturer name (incl. typos). "X Luxury" is brand, not PriceDesc.
   Do NOT set brand from a product title when intent is similar_to_product ("like Dior Sauvage" → similar_to_product only, not brand=Dior).

3. **PRICE** — under \$X, affordable, expensive, budget, …
   → min_price / max_price and/or SortBy PriceAsc|PriceDesc.
   "cheaper than {product}" / "alternative to {product} but cheaper" → similar_to_product + price_constraint lower_than_reference (not SortBy alone).

4. **GENDER / RECIPIENT** — for her/him, women/men, wife/husband, …
   → gender. Ambiguous spouse/partner → omit.

5. **WEAR CONTEXT** — summer/winter, office wear, nightlife, wedding, date night, …
   → seasons / occasion. "date night" / "dinner date" → catalog occasion labels that mention date
   (e.g. "date nights"), NEVER the generic "Night" facet. "lasts all day" is NOT Day.

6. **SCENT FAMILY** — woody, floral, citrus, amber, …
   → fragrance_concepts.

7. **NOTES** — named ingredients (bergamot, vanilla, rose, oud as a note, …)
   → fragrance_notes (or notes). Prefer notes when they say "notes of X"; prefer fragrance_concepts for broad families.

8. **PRODUCT CLASS** — perfume, cologne, gift set, candles, pocket perfume, tester, …
   → category and/or type and/or query.

9. **SIZE** — 100ml, 3.4oz, travel size, decant, …
   → size.

10. **SPECIAL / SALE** — on sale, deal, new arrivals (when clearly a Special badge intent)
    → badges/special. Also in_stock when they require available now.

11. **FORMULATION / FEATURE / PERSONALITY** — spray, oil, rollerball; feature browse; personality labels
    → formulation / by_feature / personality when clear.

12. **LONGEVITY / PERFORMANCE** — long lasting, lasts all day, strong projection
    → query only, search_description false.

13. **SCENE / MOOD** — date night, dinner date
    → occasion from catalog date labels + query "perfume". search_description false.

14. **SIMILAR TO PRODUCT** — like / similar to / alternative to / dupe of a named perfume
    → similar_to_product = the product name. Optional price_constraint lower_than_reference when cheaper.
    → "Dior perfumes similar to Sauvage" → brand=Dior AND similar_to_product=Sauvage (or Dior Sauvage).
    → "Dior perfumes under \$50" is NOT this intent (brand + max_price only).
    → query "perfume". Do not dump the whole reference sentence into query.

## FACET vs COPY
- Prefer a catalog filter when intent matches a field above.
- Prefer no facet over a wrong facet.
- Prefer fragrance_concepts for broad families; fragrance_notes for specific note names.
- Strip from query any token already expressed as a filter.

## STEP-BY-STEP
1. TOKENIZE the utterance.
2. Decide which INTENT(s) apply (can be multiple).
3. Map each intent to fields. Correct typos first.
4. Remove from query any token already expressed as a filter.
5. Output JSON. Omit empty fields.

## EXAMPLES WITH REASONING (illustrative — apply the same logic to unseen phrasing)
Input: "affordable floral perfume for her under \$50"
Output: {"fragrance_concepts":["floral"],"gender":"Women","max_price":50,"SortBy":"PriceAsc","query":"perfume"}

Input: "I want a woody amber perfume for winter"
Output: {"fragrance_concepts":["woody","amber"],"seasons":["Winter"],"query":"perfume"}

Input: "gift for my wife under \$100"
Output: {"gender":"Women","max_price":100,"query":"perfume"}

Input: "Men's cologne for office wear"
Output: {"gender":"Men","occasion":"Day","query":"cologne"}

Input: "Fresh citrus perfume for summer"
Output: {"fragrance_concepts":["fresh","citrus"],"seasons":"Summer","query":"perfume"}

Input: "gift set as a wedding gift for a couple"
Output: {"category":"Gift Sets","occasion":"Weddings","query":"gift set"}

Input: "Best perfume for a date night"
Reasoning: "best" alone is filler; date night → date occasion facet (not Night)
Output: {"occasion":["date nights"],"query":"perfume"}

Input: "Perfume that lasts all day"
Output: {"query":"long lasting perfume","search_description":false}

Input: "pocket perfume"
Output: {"category":"Pocket Perfume","query":"pocket perfume"}

Input: "long lasting perfumes"
Output: {"query":"long lasting perfume","search_description":false}

Input: "florl perfume for hir under \$40"
Output: {"fragrance_concepts":["floral"],"gender":"Women","max_price":40,"query":"perfume"}

Input: "Dreaming With Ghosts by Mar"
Output: {"query":"Dreaming With Ghosts Mar"}

Input: "onam luxury"
Output: {"brand":"Oman Luxury"}

Input: "Best rating perfumes"
Output: {"rating_avg":"5","OnTopRated":true,"query":"perfume"}

Input: "highest rated women's fragrance"
Output: {"rating_avg":"5","OnTopRated":true,"gender":"Women","query":"fragrance"}

Input: "vanilla notes eau de parfum"
Output: {"fragrance_notes":["vanilla"],"type":"Eau De Parfum","query":"perfume"}

Input: "travel size creed"
Output: {"size":"travel size","brand":"Creed"}

Input: "oud perfume on sale"
Output: {"fragrance_concepts":["oud"],"badges":"On Sale","query":"perfume"}

Input: "I want candles under \$1K"
Output: {"category":"Candles","max_price":1000,"query":"candles"}

Input: "Something like Dior Sauvage but cheaper"
Output: {"similar_to_product":"Dior Sauvage","price_constraint":"lower_than_reference","query":"perfume"}

Input: "Similar to Dior Sauvage under \$50"
Output: {"similar_to_product":"Dior Sauvage","max_price":50,"query":"perfume"}

Input: "Dior perfumes similar to Sauvage"
Output: {"brand":"Dior","similar_to_product":"Sauvage","query":"perfume"}

Input: "A perfume like Baccarat Rouge 540 but cheaper"
Output: {"similar_to_product":"Baccarat Rouge 540","price_constraint":"lower_than_reference","query":"perfume"}

Input: "Dior perfumes under \$50"
Output: {"brand":"Dior","max_price":50,"query":"perfume"}

## OUTPUT FORMAT
First show your reasoning for each intent/token, then output the final JSON on a line starting with "Output: "
Always end with valid JSON. Omit fields with no value. Prefer fragrance_concepts (array) over fragrance_family.
EOD;
	}

	/**
	 * Expand \$1K / 1k / 2.5K / 1M style amounts so OpenAI and cache keys see full numbers.
	 */
	private function expandPriceShorthandInQuery(string $query): string
	{
		$expanded = preg_replace_callback(
			'/(\$?)\s*([\d,]+(?:\.\d+)?)\s*([kmb])\b/i',
			function (array $m): string {
				$n = $this->parsePriceMagnitude($m[2], $m[3]);
				$prefix = str_contains($m[1], '$') ? '$' : '';
				return $prefix . (fmod($n, 1.0) === 0.0 ? (string) (int) $n : (string) $n);
			},
			$query
		);
		return is_string($expanded) ? $expanded : $query;
	}

	/**
	 * Shared NL normalization before probe / OpenAI / local extract.
	 * Typo-corrected forms share cache keys with the intended spelling.
	 */
	private function normalizeUtteranceForNlp(string $query): string
	{
		$query = trim($query);
		if ($query === '') {
			return '';
		}
		// URL / slug queries use hyphens ("Bond-No.9-perfumes-…") — treat as spaces.
		$query = $this->normalizeQuerySeparators($query);
		$query = $this->correctStructuralTyposInUtterance($query);
		$query = $this->expandPriceShorthandInQuery($query);
		$query = preg_replace('/\s+/u', ' ', $query) ?? $query;
		// Same plural fold as Meili search — "27 87 perfumes" must probe/cache as "27 87 perfume".
		$query = $this->productSearch->prepareSearchQuery($query);
		return trim($query);
	}

	/**
	 * Turn slug/URL separators into spaces so catalog brand matching sees real tokens.
	 * Shoppers type "Bond No.9 …"; routing may store "Bond-No.9-…".
	 */
	private function normalizeQuerySeparators(string $query): string
	{
		$query = str_replace(["\xC2\xA0", '–', '—', '_'], [' ', '-', '-', '-'], $query);
		$query = preg_replace('/-+/u', ' ', $query) ?? $query;
		$query = preg_replace('/\s+/u', ' ', $query) ?? $query;
		return trim($query);
	}

	/**
	 * Fuzzy-correct tokens toward a closed structural vocabulary (gender/season/occasion/product-class).
	 * Distance budget scales with token length — not a per-misspelling map.
	 */
	private function correctStructuralTyposInUtterance(string $query): string
	{
		$tokens = preg_split('/(\s+)/u', $query, -1, PREG_SPLIT_DELIM_CAPTURE);
		if (!is_array($tokens) || $tokens === []) {
			return $query;
		}
		$vocab = $this->structuralTypoVocabulary();
		$out = [];
		foreach ($tokens as $token) {
			if ($token === '' || preg_match('/^\s+$/u', $token)) {
				$out[] = $token;
				continue;
			}
			if (!preg_match('/^[A-Za-z]{4,}$/u', $token)) {
				$out[] = $token;
				continue;
			}
			$lower = strtolower($token);
			if (isset($vocab[$lower])) {
				$out[] = $token;
				continue;
			}
			$maxDist = strlen($lower) >= 6 ? 2 : 1;
			$best = null;
			$bestDist = PHP_INT_MAX;
			$ties = 0;
			// Never fuzzy-correct toward relationship tokens (tester→sister, etc.).
			$exactOnlyTargets = $this->structuralTypoExactOnlyTargets();
			foreach ($vocab as $candidate => $_true) {
				if (isset($exactOnlyTargets[$candidate])) {
					continue;
				}
				$dist = levenshtein($lower, $candidate);
				if ($dist < $bestDist) {
					$bestDist = $dist;
					$best = $candidate;
					$ties = 1;
				} elseif ($dist === $bestDist) {
					$ties++;
				}
			}
			if ($best !== null && $bestDist > 0 && $bestDist <= $maxDist && $ties === 1) {
				if ($token === strtoupper($token) && strlen($token) > 1) {
					$out[] = strtoupper($best);
				} elseif (isset($token[0]) && $token[0] === strtoupper($token[0])) {
					$out[] = ucfirst($best);
				} else {
					$out[] = $best;
				}
				continue;
			}
			$out[] = $token;
		}
		return implode('', $out);
	}

	/**
	 * Closed cue vocabulary for fuzzy repair — catalog intent words only.
	 *
	 * @return array<string, true>
	 */
	private function structuralTypoVocabulary(): array
	{
		$words = [
			// Gender / recipient
			'women', 'woman', 'ladies', 'female', 'her', 'hers', 'wife', 'wives',
			'girlfriend', 'mother', 'mom', 'mum', 'sister', 'daughter',
			'men', 'man', 'guys', 'male', 'him', 'his', 'husband', 'husbands',
			'boyfriend', 'father', 'dad', 'brother', 'son', 'unisex',
			// Seasons / occasions
			'summer', 'winter', 'spring', 'fall', 'autumn',
			'evening', 'night', 'nightlife', 'nighttime', 'daytime',
			'wedding', 'weddings',
			// Common product-class tokens
			'perfume', 'perfumes', 'fragrance', 'fragrances', 'cologne', 'colognes',
			'gift', 'gifts', 'set', 'sets', 'candle', 'candles',
			'tester', 'testers', 'sample', 'samples', 'unboxed',
		];
		$out = [];
		foreach ($words as $w) {
			$out[$w] = true;
		}
		return $out;
	}

	/**
	 * Relationship words stay exact-match only — fuzzy repair must not map
	 * product terms onto them (e.g. levenshtein("tester","sister") === 2).
	 *
	 * @return array<string, true>
	 */
	private function structuralTypoExactOnlyTargets(): array
	{
		$words = [
			'her', 'hers', 'wife', 'wives', 'girlfriend', 'mother', 'mom', 'mum',
			'sister', 'daughter', 'him', 'his', 'husband', 'husbands', 'boyfriend',
			'father', 'dad', 'brother', 'son',
		];
		$out = [];
		foreach ($words as $w) {
			$out[$w] = true;
		}
		return $out;
	}

	/**
	 * Drop brand guesses that are really quality fillers ("Best perfume for a date night").
	 *
	 * @param array<string, mixed> $extracted
	 * @return array<string, mixed>
	 */
	private function dropAmbiguousFillerBrandGuesses(array $extracted, string $utterance): array
	{
		if (empty($extracted['brand'])) {
			return $extracted;
		}
		$raw = $extracted['brand'];
		$labels = is_array($raw) ? $raw : [$raw];
		$kept = [];
		foreach ($labels as $label) {
			if (!is_scalar($label)) {
				continue;
			}
			$brandLabel = trim((string) $label);
			if ($brandLabel === '') {
				continue;
			}
			$collapsed = preg_replace('/[^a-z0-9]+/u', '', strtolower($brandLabel)) ?? '';
			if ($this->isAmbiguousFillerBrandToken($collapsed)
				&& !$this->utteranceIsPrimarilyBrand($utterance, $brandLabel)
			) {
				continue;
			}
			// "fresh citrus…" must not keep brand=Aura Fresh (only shares adjective "fresh").
			if (!$this->brandLabelJustifiedByUtterance($utterance, $brandLabel)) {
				continue;
			}
			$kept[] = $brandLabel;
		}
		if ($kept === []) {
			unset($extracted['brand']);
		} else {
			$extracted['brand'] = count($kept) === 1 ? $kept[0] : $kept;
		}
		return $extracted;
	}

	/**
	 * "Best perfume for a date night" must not become 5★ / Top Rated — only explicit
	 * rating phrases ("best rating", "top rated", "5 star") ground those filters.
	 *
	 * @param array<string, mixed> $extracted
	 * @return array<string, mixed>
	 */
	private function dropUngroundedRatingGuesses(array $extracted, string $utterance): array
	{
		if ($this->utteranceHasTopRatingIntent($utterance) !== null) {
			return $extracted;
		}
		unset(
			$extracted['rating_avg'],
			$extracted['star_rating'],
			$extracted['stars'],
			$extracted['StarRating'],
			$extracted['OnTopRated'],
			$extracted['top_rated'],
			$extracted['TopRated'],
			$extracted['topRated']
		);
		$sortBy = $extracted['SortBy'] ?? $extracted['sortby'] ?? null;
		if (is_string($sortBy) && preg_match('/rating|top.?rated/i', $sortBy)) {
			unset($extracted['SortBy'], $extracted['sortby']);
		}
		return $extracted;
	}

	/**
	 * Date night / dinner date → real Fragrance Occasion facet values that mention date
	 * (e.g. "date nights"), matching SearchSpring datenights — not generic Night.
	 *
	 * @param array<string, mixed> $extracted
	 * @return array<string, mixed>
	 */
	private function applySceneMoodLocalOverrides(array $extracted, string $utterance): array
	{
		$u = strtolower(trim($utterance));
		if (!preg_match('/\bdate\s*nights?\b/u', $u) && !preg_match('/\bdinner\s+dates?\b/u', $u)) {
			return $extracted;
		}
		unset($extracted['brand']);
		$dateOccasions = $this->catalogDateOccasionValues();
		if ($dateOccasions !== []) {
			$extracted['occasion'] = $dateOccasions;
			$extracted['query'] = 'perfume';
			$extracted['search_description'] = false;
			unset($extracted['fragrance_occasion']);
		} else {
			$extracted['query'] = preg_match('/\bdinner\s+dates?\b/u', $u) ? 'dinner date' : 'date night';
			$extracted['search_description'] = true;
			unset($extracted['occasion'], $extracted['fragrance_occasion']);
		}
		return $extracted;
	}

	/**
	 * Catalog occasion labels that are date-specific (not the broad "Night" facet).
	 *
	 * @return list<string>
	 */
	private function catalogDateOccasionValues(): array
	{
		$out = [];
		foreach (array_keys($this->facetDistribution('occasion')) as $value) {
			$v = trim((string) $value);
			if ($v === '') {
				continue;
			}
			// "date nights", "Upscale evening dates", "romantic dates, …"
			if (preg_match('/\bdates?\b/iu', $v)) {
				$out[] = $v;
			}
		}
		return array_values(array_unique($out));
	}

	/**
	 * Drop occasion facets that are not grounded in the (corrected) utterance.
	 * Prevents typo tokens from being misread as an unrelated occasion.
	 *
	 * @param array<string, mixed> $extracted
	 * @return array<string, mixed>
	 */
	private function dropUngroundedOccasionGuesses(array $extracted, string $utterance): array
	{
		$occasion = $extracted['occasion'] ?? $extracted['fragrance_occasion'] ?? null;
		if ($occasion === null || $occasion === '' || $occasion === []) {
			return $extracted;
		}
		$label = is_array($occasion) ? strtolower(implode(' ', $occasion)) : strtolower((string) $occasion);
		$u = strtolower($utterance);
		$grounded = false;
		if (str_contains($label, 'wedding')) {
			$grounded = (bool) preg_match('/\bwedding/u', $u);
		} elseif (str_contains($label, 'date')) {
			// Date-night scene → catalog "date nights" / "… dates" facets (not generic Night).
			$grounded = (bool) preg_match('/\bdate\s*nights?\b|\bdinner\s+dates?\b/u', $u);
		} elseif (str_contains($label, 'night') || str_contains($label, 'evening')) {
			// Wear-context cues only — bare "night" must not ground Night (LLM decides phrases).
			$grounded = (bool) preg_match(
				'/\b(nightlife|nighttime|evening(?:\s+wear)?|night\s+wear|(?:for|at|by)\s+(?:the\s+)?night|night\s+out)\b/u',
				$u
			);
		} elseif (str_contains($label, 'day')) {
			// Wear-context cues only — bare "day" / "all day" must not ground Day.
			$grounded = (bool) preg_match(
				'/\b(daytime|day\s*wear|office|work(?:place|day)?|work\s*wear|(?:for|during)\s+(?:the\s+)?day)\b/u',
				$u
			);
		} else {
			// Unknown occasion labels: keep only if the label token itself appears.
			$grounded = $label !== '' && preg_match('/\b' . preg_quote($label, '/') . '\b/u', $u);
		}
		if (!$grounded) {
			unset($extracted['occasion'], $extracted['fragrance_occasion']);
		}
		return $extracted;
	}

	/**
	 * @param array<string, mixed> $nlp
	 * @return array<string, mixed>
	 */
	private function applyUtterancePriceOverrides(array $nlp, string $utterance): array
	{
		$budget = $this->extractBudgetFromUtterance($utterance);
		if ($budget['min'] !== null) {
			$nlp['min_price'] = $budget['min'];
		}
		if ($budget['max'] !== null) {
			$nlp['max_price'] = $budget['max'];
		}
		return $nlp;
	}

	/**
	 * @return array{min: ?float, max: ?float}
	 */
	private function extractBudgetFromUtterance(string $utterance): array
	{
		$out = ['min' => null, 'max' => null];
		$u = trim($utterance);
		if ($u === '') {
			return $out;
		}

		// under/below/less than / up to $1K|1k|1000|1,000
		if (preg_match(
			'/\b(?:under|below|less\s+than|upto|up\s+to)\s*\$?\s*([\d,]+(?:\.\d+)?)\s*([kmb])?\b/i',
			$u,
			$m
		)) {
			$out['max'] = $this->parsePriceMagnitude($m[1], $m[2] ?? '');
		} elseif (preg_match(
			'/\b(?:under|below|less\s+than|upto|up\s+to)\s*\$?\s*([\d,]+(?:\.\d+)?)\s*(?:thousand)\b/i',
			$u,
			$m
		)) {
			$out['max'] = $this->parsePriceMagnitude($m[1], 'k');
		}

		// over/above/at least / more than
		if (preg_match(
			'/\b(?:over|above|at\s+least|more\s+than|from)\s*\$?\s*([\d,]+(?:\.\d+)?)\s*([kmb])?\b/i',
			$u,
			$m
		)) {
			$out['min'] = $this->parsePriceMagnitude($m[1], $m[2] ?? '');
		}

		return $out;
	}

	private function parsePriceMagnitude(string $number, string $suffix = ''): float
	{
		$n = (float) str_replace(',', '', trim($number));
		$s = strtolower(trim($suffix));
		if ($s === 'k') {
			$n *= 1000;
		} elseif ($s === 'm') {
			$n *= 1000000;
		} elseif ($s === 'b') {
			$n *= 1000000000;
		}
		return $n;
	}

	/**
	 * @param mixed $value
	 * @return list<string>
	 */
	private function normalizeToEncodedList($value): array
	{
		if ($value === null || $value === '' || $value === []) {
			return [];
		}
		$items = is_array($value) ? $value : [$value];
		$out = [];
		foreach ($items as $item) {
			if (!is_scalar($item)) {
				continue;
			}
			$trimmed = trim((string) $item);
			if ($trimmed === '') {
				continue;
			}
			$out[] = $this->productSearch->encodeFilterValue($trimmed);
		}
		return array_values(array_unique($out));
	}

	/**
	 * @param array<string, mixed> $filters
	 */
	private function userHasFilterValue(array $filters, string $key): bool
	{
		if (!array_key_exists($key, $filters)) {
			return false;
		}
		$val = $filters[$key];
		if ($val === null || $val === '' || $val === []) {
			return false;
		}
		$toggleKeys = ['OnTopRated', 'InStockItems', 'OnSaleDeal', 'ochangeprice'];
		// Explicit on OR off for toolbar toggles — Clear all sends false; do not restore from NLP/session.
		if (in_array($key, $toggleKeys, true)) {
			if (is_bool($val)) {
				return true;
			}
			if (is_string($val)) {
				$norm = strtolower(trim($val));
				if (in_array($norm, ['0', '1', 'true', 'false', 'yes', 'no', 'on', 'off'], true)) {
					return true;
				}
			}
		}
		if (is_bool($val)) {
			return $val === true;
		}
		if (is_array($val)) {
			return array_filter($val, static fn ($v) => $v !== null && $v !== '') !== [];
		}
		if (is_string($val) && strcasecmp(trim($val), 'Sort By') === 0) {
			return false;
		}
		return true;
	}

	private function rankThreshold(): float
	{
		return (float) env('MEILISEARCH_NLP_RANK_THRESHOLD', 0.8);
	}

	private function openAiCircuitCacheKey(): string
	{
		return 'openai_nlp_circuit_open';
	}

	private function isOpenAiCircuitOpen(): bool
	{
		return (bool) Cache::get($this->openAiCircuitCacheKey(), false);
	}

	private function tripOpenAiCircuit(): void
	{
		$seconds = max(30, (int) env('OPENAI_NLP_CIRCUIT_SECONDS', 120));
		Cache::put($this->openAiCircuitCacheKey(), true, now()->addSeconds($seconds));
	}

	/**
	 * @return array{needs_nlp: bool, highest_rank: float, hit_count: int}|null
	 */
	private function getSessionRankingProbe(string $normalizedKeyword): ?array
	{
		$state = Session::get('meili_rank_probe');
		if (!is_array($state) || ($state['keyword_hash'] ?? '') !== md5($normalizedKeyword)) {
			return null;
		}
		$ttl = max(60, (int) env('MEILISEARCH_RANK_PROBE_SESSION_SECONDS', 300));
		if (isset($state['at']) && (time() - (int) $state['at']) > $ttl) {
			return null;
		}
		return [
			'needs_nlp' => (bool) ($state['needs_nlp'] ?? false),
			'highest_rank' => (float) ($state['highest_rank'] ?? 0),
			'hit_count' => (int) ($state['hit_count'] ?? 0),
		];
	}

	/**
	 * @param array{needs_nlp: bool, highest_rank: float, hit_count: int} $probe
	 */
	private function rememberSessionRankingProbe(string $normalizedKeyword, array $probe): void
	{
		Session::put('meili_rank_probe', [
			'keyword_hash' => md5($normalizedKeyword),
			'needs_nlp' => $probe['needs_nlp'],
			'highest_rank' => $probe['highest_rank'],
			'hit_count' => $probe['hit_count'],
			'at' => time(),
		]);
	}
}
