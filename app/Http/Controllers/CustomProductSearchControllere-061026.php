<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Traits\CommonTrait;
	use App\Http\Controllers\Traits\CartTrait;
	use App\Http\Controllers\Traits\CustomSearchTrait;
	use App\Services\PerfumeNlpSearchService;
	use DB;
	use Session;
	use Cache;

class CustomProductSearchController extends Controller
	{
		use CommonTrait;
		use CartTrait;
		use CustomSearchTrait;
		public $PageData = [];

		public function productListPage(Request $request)
		{	
			if ($request->keyword != '') {
				return $this->searchAjax($request);
			}
			return app(ProductController::class)->ProductListPage($request);
		}

		public function search(Request $request)
		{
			$rawKeyword = trim((string) ($request->keyword ?? $request->route('keyword') ?? ''));
			if ($rawKeyword !== '') {
				$redirectResponse = $this->trySearchRedirectRule($rawKeyword);
				if ($redirectResponse !== null) {
					return $redirectResponse;
				}
			}

            if(Cache::has('DefaultMetaInfo'))
            {
                $MetaInfo = Cache::get('DefaultMetaInfo');
                if (is_object($MetaInfo)) {
                    $this->PageData['meta_title'] = getMetaTitleDescription($MetaInfo->meta_title);
                    $this->PageData['meta_description'] = getMetaTitleDescription($MetaInfo->meta_description);
                    $this->PageData['meta_keywords'] = getMetaTitleDescription($MetaInfo->meta_keywords);
                }
            }

			$this->PageData['CSSFILES'] = ['listing.css','jquery-ui-slider.css','listpage.css','custom.css'];
			$this->PageData['JSFILES'] = ['jquery.mCustomScrollbar.concat.min.js','jquery-ui-slider.min.js','listing_page.js'];

			$GTMDATA = ['page' => 'productlist', 'pagetype' => 'searchresults'];
			$GTMDATA['search_query'] = isset($request->keyword)?$request->keyword:'';
			$this->PageData['GTMDATA'] = $this->GoogleTagManager($GTMDATA);

			$GA4 = "";
			$GA4 .= googleAnalyticsGA4("CategoryPageView");
			$this->PageData['GA4'] = $GA4;

			return $this->searchPage($request);
		}

		public function searchPage($request)
		{
				$searchKeyword = $this->normalizeSearchKeywordForMeilisearch((string) $request->keyword);
				$pageTitle = $searchKeyword;
				
				if ($searchKeyword !== '' && preg_match('/[^\x00-\x7F]/', $searchKeyword)) {
					$searchKeyword = 'Adidas';
				}

				$nlpFilters = [];
				$nlpExtracted = null;
				$nlpApplied = false;
				try {
					$nlp = app(PerfumeNlpSearchService::class)->resolveForSearchPage($searchKeyword);
					if (!empty($nlp['applied'])) {
						$searchKeyword = (string) ($nlp['keyword'] ?? $searchKeyword);
						$nlpFilters = is_array($nlp['filters'] ?? null) ? $nlp['filters'] : [];
						$nlpExtracted = is_array($nlp['nlp'] ?? null) ? $nlp['nlp'] : null;
						$nlpApplied = true;
					}
				} catch (\Throwable $e) {
					\Log::warning('Perfume NLP search page failed: ' . $e->getMessage());
				}

				// Shareable query-string filters (?OnTopRated=yes&InStockItems=yes&…) win over NLP defaults.
				$urlFilters = $this->filtersFromShareableQuery($request);
				if ($urlFilters !== []) {
					$nlpFilters = app(PerfumeNlpSearchService::class)
						->mergeNlpFiltersPreserveUser($nlpFilters, $urlFilters);
				}

				$trackedBrand = null;
				try {
					// Use shopper-facing query (hyphens already → spaces), not NLP-stripped keyword.
					$brandSource =
						$pageTitle !== ''
							? $pageTitle
							: $this->normalizeSearchKeywordForMeilisearch((string) $request->keyword);
					$trackedBrand = app(PerfumeNlpSearchService::class)->resolveTrackedBrand(
						$brandSource,
						$nlpFilters,
						$nlpExtracted
					);
				} catch (\Throwable $e) {
					// non-fatal for analytics
				}
				$this->PageData['NlpTrackedBrand'] = $trackedBrand;
				$this->PageData['NlpApplied'] = $nlpApplied || $trackedBrand !== null;
			
				$result = $this->runMeilisearchProductSearch($searchKeyword, $nlpFilters, 1, 24, false, $pageTitle);
				$Products = $result['hits'];
				$TotalProducts = $result['total'];

				$this->PageData['Products'] = $Products;
				$this->PageData['TotalProducts'] = $TotalProducts;
				// URL slug uses hyphens (key-long-lasting-perfumes); show spaces in the H1.
				$this->PageData['PageTitle'] = $pageTitle !== '' ? $pageTitle : (string) $request->keyword;
				$setMinPrice = $result['minPrice'];
				$setMaxPrice = $result['maxPrice'];
				$this->PageData['OMinPrice'] = $setMinPrice;
				$this->PageData['OMaxPrice'] = $setMaxPrice;
				$nlpMin = $nlpFilters['minprice'] ?? '';
				$nlpMax = $nlpFilters['maxprice'] ?? '';
				$nlpPrice = (($nlpFilters['ochangeprice'] ?? '') === '1')
					|| $nlpMin !== '' || $nlpMax !== '';
				$this->PageData['OChangePrice'] = $nlpPrice ? 1 : 0;
				$this->PageData['MinPrice'] = $nlpMin !== '' ? $nlpMin : $setMinPrice;
				$this->PageData['MaxPrice'] = $nlpMax !== '' ? $nlpMax : $setMaxPrice;
				$this->PageData['Filters'] = $this->listingFiltersFromFacets($result['facets'], $nlpFilters);
				$this->PageData['chkTopRated'] = !empty($nlpFilters['OnTopRated']) ? 'Yes' : 'No';
				$this->PageData['chkInStockItems'] = !empty($nlpFilters['InStockItems']) ? 'Yes' : 'No';
				$this->PageData['chkOnSaleDeal'] = !empty($nlpFilters['OnSaleDeal']) ? 'Yes' : 'No';
				$nlpSort = trim((string) ($nlpFilters['sortby'] ?? ''));
				$this->PageData['SelectedSort'] = ($nlpSort !== '' && strcasecmp($nlpSort, 'Sort By') !== 0)
					? $nlpSort
					: '';

				$GA4 = "";
				if(isset($request->keyword) && $request->keyword!='')
				{
					$GA4 = googleAnalyticsGA4("SearchPage",'','','',$request->keyword,'',$TotalProducts);
					$this->PageData['GA4'] = $GA4;
				}

				$Bredcrum = $this->Bredcrum($request);
				$this->PageData['Bredcrum'] = $Bredcrum['BredLink'];
				return view('product.listing_search')->with($this->PageData);
		}
		public function searchAjax(Request $request)
		{
			$Filters = json_decode($request->filters, true) ?: [];
			$Filters['page'] = $request->page;
			$fromClearAll = in_array(
				strtolower(trim((string) $request->input('fromClearAll', ''))),
				['1', 'true', 'yes'],
				true
			);

			$searchKeyword = $this->normalizeSearchKeywordForMeilisearch((string) $request->keyword);
			$orgKeyword = $this->normalizeSearchKeywordForMeilisearch((string) ($request->org_search_keyword ?? $request->keyword ?? ''));

			$nlpFromSession = false;
			try {
				$nlpAjax = app(PerfumeNlpSearchService::class)->resolveForAjax($searchKeyword, $Filters, $orgKeyword);
				$searchKeyword = (string) ($nlpAjax['keyword'] ?? $searchKeyword);
				$Filters = is_array($nlpAjax['filters'] ?? null) ? $nlpAjax['filters'] : $Filters;
				$nlpFromSession = !empty($nlpAjax['from_session']);
			} catch (\Throwable $e) {
				\Log::warning('Perfume NLP search ajax failed: ' . $e->getMessage());
			}

			// Clear all / Reset all: drop toolbar toggles even if an NLP session still has them.
			if ($fromClearAll) {
				foreach (['OnTopRated', 'InStockItems', 'OnSaleDeal'] as $toggleKey) {
					unset($Filters[$toggleKey]);
				}
				try {
					app(PerfumeNlpSearchService::class)->clearSessionToolbarToggles();
				} catch (\Throwable $e) {
					// non-fatal
				}
			}

			$perPage = 24;
			$begin = (int) ($Filters['page'] ?? 1);
			if ($begin < 1) {
				$begin = 1;
			}

			$result = $this->runMeilisearchProductSearch(
				$searchKeyword,
				$Filters,
				$begin,
				$perPage,
				false,
				$orgKeyword !== '' ? $orgKeyword : (string) $request->keyword
			);
			
			$Products = $result['hits'];
			$TotalProducts = $result['total'];

			$this->PageData['Products'] = $Products;
			$this->PageData['TotalProducts'] = $TotalProducts;
			$this->PageData['Filters'] = $this->listingFiltersFromFacets($result['facets'], $Filters);

			$setMinPrice = $result['minPrice'];
			$setMaxPrice = $result['maxPrice'];
			$MinPrice = $Filters['minprice'] ?? '';
			$MaxPrice = $Filters['maxprice'] ?? '';
			$this->PageData['MinPrice'] = $MinPrice;
			$this->PageData['MaxPrice'] = $MaxPrice;
			$this->PageData['OMinPrice'] = (isset($Filters['ominprice'])) ? $Filters['ominprice'] : 0;
			$this->PageData['OMaxPrice'] = (isset($Filters['omaxprice'])) ? $Filters['omaxprice'] : 0;
			$this->PageData['OChangePrice'] = (isset($Filters['ochangeprice'])) ? $Filters['ochangeprice'] : 0;

			if (($Filters['ochangeprice'] ?? '') === '') {
				$this->PageData['OChangePrice'] = 0;
			}

			if ($MinPrice == '') {
				$MinPrice = $setMinPrice;
				$this->PageData['MinPrice'] = $MinPrice;
				$Filters['ominprice'] = $setMinPrice;
				$this->PageData['OMinPrice'] = $setMinPrice;
			}

			if ($MinPrice == '' && $setMinPrice == '') {
				$MinPrice = $Filters['ominprice'] ?? '';
				$this->PageData['MinPrice'] = $MinPrice;
			}

			if ($MaxPrice == '') {
				$MaxPrice = $setMaxPrice;
				$this->PageData['MaxPrice'] = $MaxPrice;
				$Filters['omaxprice'] = $setMaxPrice;
				$this->PageData['OMaxPrice'] = $setMaxPrice;
			}

			if ($MaxPrice == '' && $setMaxPrice == '') {
				$MaxPrice = $Filters['omaxprice'] ?? '';
				$this->PageData['MaxPrice'] = $MaxPrice;
			}

			$BredcrumHTML = $this->BredcrumAjax($request);
			$ProductHTML = view('product.list_search')->with($this->PageData)->render();
			$allFilters = view('product.filter_search')->with($this->PageData)->render();

			$nlpBrand = null;
			try {
				$nlpBrand = app(PerfumeNlpSearchService::class)->resolveTrackedBrand(
					$orgKeyword !== '' ? $orgKeyword : (string) $request->keyword,
					$Filters,
					null
				);
			} catch (\Throwable $e) {
				// non-fatal
			}

			return response()->json(array(
				'TotalProducts' => $TotalProducts,
				'ProductHTML' => $ProductHTML,
				'Filters' => $allFilters,
				'OMinPrice' => $Filters['ominprice'] ?? 0,
				'OMaxPrice' => $Filters['omaxprice'] ?? 0,
				'MinPrice' => $MinPrice,
				'MaxPrice' => $MaxPrice,
				'BredcrumHTML' => $BredcrumHTML,
				'NlpBrand' => $nlpBrand,
				'IsNlp' => $nlpFromSession || $nlpBrand !== null,
			));
		}
		public function autocomplete(Request $request)
		{
			// Accept normal jQuery POSTs even if X-Requested-With is stripped by a proxy.
			if (!$request->ajax() && !$request->has('keyword')) {
				return '';
			}

			$rawKeyword = (string) $request->input('keyword', '');
			$searchKeyword = $this->normalizeSearchKeywordForMeilisearch($rawKeyword);

			if ($searchKeyword === '') {
				return '';
			}
			$originalKeyword = $searchKeyword;

			$type = $request->type;
			$autoFilters = [];
			$userPriceRefine = null;

			$decodeAutoFacetValue = static function (string $extraSearchQuery): string {
				$extraSearchQuery = str_replace('doubledot', ':', $extraSearchQuery);
				$extraSearchQuery = str_replace('dot', '.', $extraSearchQuery);
				$extraSearchQuery = str_replace('dash', '-', $extraSearchQuery);
				$extraSearchQuery = str_replace('andd', '&', $extraSearchQuery);
				$extraSearchQuery = str_replace('singlecomma', "'", $extraSearchQuery);
				$extraSearchQuery = str_replace('_', ' ', $extraSearchQuery);
				return $extraSearchQuery;
			};

			$applyAutoFacet = function (string $extraSearchQueryName, string $extraSearchQuery) use (&$autoFilters, &$userPriceRefine, $decodeAutoFacetValue): void {
				$extraSearchQuery = $decodeAutoFacetValue($extraSearchQuery);
				if ($extraSearchQueryName === 'cid') {
					$autoFilters['categories'] = array_values(array_unique(array_merge(
						$autoFilters['categories'] ?? [],
						[$extraSearchQuery]
					)));
				} elseif ($extraSearchQueryName === 'mid') {
					$autoFilters['brands'] = array_values(array_unique(array_merge(
						$autoFilters['brands'] ?? [],
						[$extraSearchQuery]
					)));
				} elseif ($extraSearchQueryName === 'size') {
					$autoFilters['size'] = array_values(array_unique(array_merge(
						$autoFilters['size'] ?? [],
						[$extraSearchQuery]
					)));
				} elseif ($extraSearchQueryName === 'special') {
					$autoFilters['special'] = array_values(array_unique(array_merge(
						$autoFilters['special'] ?? [],
						[$extraSearchQuery]
					)));
				} elseif ($extraSearchQueryName === 'rating_avg') {
					$starBucket = (int) preg_replace('/\D+/', '', $extraSearchQuery);
					if (in_array($starBucket, [3, 4, 5], true)) {
						$autoFilters['rating_avg'] = [(string) $starBucket];
					}
				} elseif ($extraSearchQueryName === 'price') {
					$priceRange = trim($extraSearchQuery);
					if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)?$/u', $priceRange, $pm)) {
						$userPriceRefine = [
							'ochangeprice' => '1',
							'minprice' => (string) (float) $pm[1],
						];
						if (isset($pm[2]) && $pm[2] !== '') {
							$userPriceRefine['maxprice'] = (string) (float) $pm[2];
						}
						$autoFilters = array_merge($autoFilters, $userPriceRefine);
					}
				}
			};

			// Multi-filter refine from popup (JSON list of {name,value}).
			$extraFiltersRaw = $request->input('extraFilters', '');
			$extraFiltersList = [];
			if (is_string($extraFiltersRaw) && $extraFiltersRaw !== '') {
				$decoded = json_decode($extraFiltersRaw, true);
				if (is_array($decoded)) {
					$extraFiltersList = $decoded;
				}
			}
			if ($extraFiltersList !== []) {
				foreach ($extraFiltersList as $item) {
					if (!is_array($item)) {
						continue;
					}
					$name = trim((string) ($item['name'] ?? ''));
					$value = (string) ($item['value'] ?? '');
					if ($name === '' || $value === '') {
						continue;
					}
					$applyAutoFacet($name, $value);
				}
			} elseif ($request->extraSearchQuery != '') {
				// Legacy single-filter refine.
				$applyAutoFacet(
					(string) $request->extraSearchQueryName,
					(string) $request->extraSearchQuery
				);
			}

			// Fast popup path: no OpenAI / Meili ranking probe (those dominate autocomplete latency).
			$nlpApplied = false;
			$nlpExtracted = null;
			try {
				$nlp = app(PerfumeNlpSearchService::class)->resolveForAutocomplete($searchKeyword);
				if (!empty($nlp['applied'])) {
					$searchKeyword = (string) ($nlp['keyword'] ?? $searchKeyword);
					$nlpFilters = is_array($nlp['filters'] ?? null) ? $nlp['filters'] : [];
					$autoFilters = app(PerfumeNlpSearchService::class)
						->mergeNlpFiltersPreserveUser($nlpFilters, $autoFilters);
					$nlpApplied = true;
					$nlpExtracted = is_array($nlp['nlp'] ?? null) ? $nlp['nlp'] : null;
				}
			} catch (\Throwable $e) {
				\Log::warning('Perfume NLP autocomplete failed: ' . $e->getMessage());
			}

			$trackedBrand = null;
			try {
				$trackedBrand = app(PerfumeNlpSearchService::class)->resolveTrackedBrand(
					$originalKeyword,
					$autoFilters,
					$nlpExtracted
				);
			} catch (\Throwable $e) {
				// non-fatal for analytics
			}

			// Price chip must fully replace NLP budget (incl. clearing max for "$200 & Above").
			if (is_array($userPriceRefine)) {
				$autoFilters['ochangeprice'] = '1';
				$autoFilters['minprice'] = $userPriceRefine['minprice'];
				if (isset($userPriceRefine['maxprice'])) {
					$autoFilters['maxprice'] = $userPriceRefine['maxprice'];
				} else {
					unset($autoFilters['maxprice']);
				}
			}

			$searchService = $this->customProductSearch();
			$result = $this->runMeilisearchAutocomplete($searchKeyword, $autoFilters, 15);

			// Slider track = price span for current facets WITHOUT the price refine
			// (same idea as PLP OMinPrice / OMaxPrice), so bounds don't collapse after sliding.
			$priceActive = ((string) ($autoFilters['ochangeprice'] ?? '') === '1')
				&& (
					(isset($autoFilters['minprice']) && $autoFilters['minprice'] !== '')
					|| (isset($autoFilters['maxprice']) && $autoFilters['maxprice'] !== '')
				);
			$oMinPrice = (float) ($result['minPrice'] ?? 0);
			$oMaxPrice = (float) ($result['maxPrice'] ?? 0);
			if ($priceActive) {
				$boundsFilters = $autoFilters;
				unset($boundsFilters['minprice'], $boundsFilters['maxprice'], $boundsFilters['ochangeprice']);
				$boundsResult = $this->runMeilisearchAutocomplete($searchKeyword, $boundsFilters, 1);
				$oMinPrice = (float) ($boundsResult['minPrice'] ?? $oMinPrice);
				$oMaxPrice = (float) ($boundsResult['maxPrice'] ?? $oMaxPrice);
			}
			if ($oMaxPrice < $oMinPrice) {
				$oMaxPrice = $oMinPrice;
			}
			$catalogMin = $oMinPrice;
			$catalogMax = $oMaxPrice;
			// Room to drag the high handle when every hit shares one price (e.g. $40–$40).
			if (($oMaxPrice - $oMinPrice) < 1.0) {
				$oMaxPrice = $oMinPrice + max(10.0, round($oMinPrice * 0.25, 2));
			}
			$selMinPrice = $priceActive && isset($autoFilters['minprice']) && $autoFilters['minprice'] !== ''
				? (float) $autoFilters['minprice']
				: $catalogMin;
			$selMaxPrice = $priceActive && isset($autoFilters['maxprice']) && $autoFilters['maxprice'] !== ''
				? (float) $autoFilters['maxprice']
				: $catalogMax;
			$selMinPrice = max($oMinPrice, min($selMinPrice, $oMaxPrice));
			$selMaxPrice = max($oMinPrice, min($selMaxPrice, $oMaxPrice));
			if ($selMaxPrice < $selMinPrice) {
				$selMaxPrice = $selMinPrice;
			}

			$suggestedQry = $searchService->normalizeQuery($originalKeyword);
			// Popup "current query" tab must show what the user typed — NLP often empties
			// $searchKeyword when everything is expressed as filters (e.g. best sellers + budget).
			$displayQuery = $suggestedQry !== '' ? $suggestedQry : $originalKeyword;
			$GA4 = "";
			if ($suggestedQry !== '') {
				$GA4 = googleAnalyticsGA4("SearchAutoComplete", "", "", "", $suggestedQry);
			}
			$jsonArrayResponse_popular = $this->fetchPopularSearchQueries();
			$jsonArrayResponse = (object) [
				'suggested' => (object) ['text' => $displayQuery],
				'alternatives' => [],
			];
			$jsonArrayResponse1 = (object) [
				'results' => $this->hitsToAutocompleteArrays($result['hits']),
				'facets' => json_decode(json_encode($result['facets'])),
			];

			$iSBrand = collect();
			try {
				$iSBrand = DB::table('pu_manufacture')
					->select('imanufactureid', 'vmanufacture', 'status')
					->where('vmanufacture', 'like', $originalKeyword)
					->where('status', '=', '1')
					->get();
			} catch (\Throwable $e) {
				// Brand lookup must never break autocomplete products.
			}

			$aProducts = [];
			if (isset($jsonArrayResponse1->results)) {
				$aProducts = json_decode(json_encode($jsonArrayResponse1->results), true);
			}
			$cntaProducts = is_array($aProducts) ? count($aProducts) : 0;

			$filters = "";
			if (isset($jsonArrayResponse1->facets)) {
				$filters = json_decode(json_encode($jsonArrayResponse1->facets), true);
			}

			$cntSetLinkOnTop = 0;
			if (!empty($jsonArrayResponse1->facets) && $jsonArrayResponse1->facets[0]->field == 'brand' && count($jsonArrayResponse1->facets[0]->values) == 1 && strtolower($jsonArrayResponse1->facets[0]->values[0]->value) == strtolower($suggestedQry)) {
				$cntSetLinkOnTop++;
			}

			$data = $fltrsData = $data1 = $data2 = $allData = '';

			$analyticsBrandSafe = htmlspecialchars((string) ($trackedBrand ?? ''), ENT_QUOTES, 'UTF-8');
			$analyticsNlpFlag = ($nlpApplied || $trackedBrand !== null) ? '1' : '0';
			$data .= '<div id="max-analytics-nlp" hidden'
				. ' data-brand="' . $analyticsBrandSafe . '"'
				. ' data-nlp="' . $analyticsNlpFlag . '"></div>';

			$data .= '
				<script type="text/javascript"> var yotpo_app_key = "MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8";
              (function e(){var e=document.createElement("script");e.type="text/javascript",e.async=true,e.src="//staticw2.yotpo.com/MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8/widget.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
              </script>
				<div class="serach-dropdown" id="serach-dropdownauto" role="region" aria-label="Search Suggestions">';

			if ($cntaProducts <= 0) {
				$data .= '<div class="src-sec" style="text-align:center;display: block;" role="alert" aria-live="polite"><strong>Sorry, No records found.</strong></div></div>';
				return $data;
			}

			if ($cntSetLinkOnTop > 0) {
				$data .= '<div class="src-sec1 headingLink" style="text-align:center;display: block;" role="heading" aria-level="2"><a href="' . config('global.SITE_URL') . 'p4u/key-' . rawurlencode(str_replace(' ', '-', $suggestedQry)) . '/view" aria-label="View all results for ' . htmlspecialchars($suggestedQry, ENT_QUOTES, 'UTF-8') . '"><strong>' . htmlspecialchars($suggestedQry, ENT_QUOTES, 'UTF-8') . '</strong></a></div><br/>';
			}
			$data .= '<div class="src-sec" role="region" aria-label="Search Filters and Results">';

			if ($type != 'changeTabData') {
				$fltrsData .= '<div class="src-left-filter" role="navigation" aria-label="Filter Options">';
			}

				if (isset($jsonArrayResponse_popular['trending']['queries']) && count($jsonArrayResponse_popular['trending']['queries']) > 0) {
					$popular_queries = $jsonArrayResponse_popular['trending']['queries'];
					$fltrsData .= '<div class="serch-ltab"><ul>';
					$fltrsData .= '<li class="changeTab"><a href="javascript:void(0);" class="serch-ltab-active">Popular Search</a></li>';
					for ($p = 0; $p < count($popular_queries); $p++) {
						// Show shopper-facing text with spaces (not URL-slug hyphens).
						$popular_search_query = trim((string) ($popular_queries[$p]['searchQuery'] ?? ''));
						$popular_search_query = str_replace(['andd', 'backslash', '-'], ['&', '/', ' '], $popular_search_query);
						$popular_search_query = trim(preg_replace('/\s+/u', ' ', $popular_search_query) ?? $popular_search_query);
						if ($popular_search_query === '') {
							continue;
						}
						$popularSafe = htmlspecialchars($popular_search_query, ENT_QUOTES, 'UTF-8');
						$fltrsData .= '<li class="changeTab"><a href="javascript:void(0);" aria-label="Popular search: '
							. $popularSafe . '">' . $popularSafe . '</a></li>';
					}
					$fltrsData .= '</ul></div>';
				}

				$currentQueryLabel = trim((string) ($jsonArrayResponse->suggested->text ?? ''));
				if ($currentQueryLabel === '') {
					$currentQueryLabel = $displayQuery;
				}
				$currentQuerySafe = htmlspecialchars($currentQueryLabel, ENT_QUOTES, 'UTF-8');
				$setTabKeyword = str_replace(['andd', '/', ' '], ['&', 'backslash', '-'], $currentQueryLabel);

				if(!empty($jsonArrayResponse->alternatives)){
				$fltrsData .= '<div class="serch-ltab" role="tablist" aria-label="Search Tabs">
				<ul><li class="changeTab" role="presentation"><a href="javascript:void(0);" class="serch-ltab-active" role="tab" aria-selected="true" tabindex="0" aria-label="Current search: '.$currentQuerySafe.'">'.$currentQuerySafe.'</a></li>';
				for($a=0;$a<count($jsonArrayResponse->alternatives);$a++){
				$altText = (string) ($jsonArrayResponse->alternatives[$a]->text ?? '');
				$altSafe = htmlspecialchars($altText, ENT_QUOTES, 'UTF-8');
				$setTabKeyword1 = str_replace(['andd', '/', ' '], ['&', 'backslash', '-'], $altText);

					$fltrsData .= '<li class="changeTab" role="presentation"><a href="javascript:void(0);" role="tab" aria-selected="false" tabindex="-1" aria-label="Alternative search: '.$altSafe.'">'.$altSafe.'</a></li>';
				}
				$fltrsData .= '</ul>
				</div>';
				}elseif ($currentQueryLabel !== '') {
				$fltrsData .= '<div class="serch-ltab" role="tablist" aria-label="Search Tabs">
				<ul><li class="changeTab" role="presentation"><a href="javascript:void(0);" class="serch-ltab-active" role="tab" aria-selected="true" tabindex="0" aria-label="Current search: '.$currentQuerySafe.'">'.$currentQuerySafe.'</a></li>';
				$fltrsData .= '</ul></div>';
				}
				$fltrsData .= '<div class="list-toggle visible-xs" role="button" tabindex="0" aria-pressed="false" aria-label="Toggle Refine Search">
				<h2><span><svg class="filter_icon" aria-hidden="true" role="img" width="20" height="20">
				<use href="#filter_icon" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#filter_icon"></use>
				</svg> <strong>Refine</strong> Search</span>
				<span id="closeSearchAutoComplete" style="display: none;">Close</span>
				</h2>
				</div>	';

				//changes of div end

				for($f=0;$f<count($filters);$f++){
				if($filters[$f]['field'] != 'fragrance_family' && $filters[$f]['field'] != 'type' && $filters[$f]['field'] != 'formulation' && $filters[$f]['field'] != 'coverage' && $filters[$f]['field'] != 'finish' && $filters[$f]['field'] != 'skin_type' && $filters[$f]['field'] != 'seasons' && $filters[$f]['field'] != 'occasion' && $filters[$f]['field'] != 'personality' && $filters[$f]['field'] != 'by_feature' && $filters[$f]['field'] != 'top_note' && $filters[$f]['field'] != 'middle_note' && $filters[$f]['field'] != 'base_note' && $filters[$f]['field'] != 'fragrance_notes'){
				if($filters[$f]['field'] != 'price'){
				$facetField = (string) ($filters[$f]['field'] ?? '');
				// Only facets that autocomplete can apply (incl. Star Rating).
				if (!in_array($facetField, ['category', 'brand', 'size', 'badges', 'rating_avg'], true)) {
					continue;
				}
				$catArr = $filters[$f]['values'];
				$facetHeading = $facetField === 'rating_avg'
					? 'Star Rating'
					: (string) ($filters[$f]['label'] ?? $facetField);

				$fltrsData .= '<div class="src-fbox" role="group" aria-labelledby="filter-'.$facetField.'">
				<h3 id="filter-'.$facetField.'">'.$facetHeading.'</h3>
				<ul class="filter_checkbox" aria-label="Filter Options for '.$facetHeading.'">';
				for($j=0;$j<count($catArr);$j++){
				if($j < 6){

				$sentData = '';

				if($catArr[$j]['active'] == '1'){
				//$sentData = 'checked';
				$sentData = ' class="active"';
				}

				if($facetField == 'badges'){
				if(strpos($catArr[$j]['value'], ' ') !== false){
				$getInitials = $this->initialsFN($catArr[$j]['value']);
				}else{
				$getInitials = strtolower(substr($catArr[$j]['value'], 0, 2));
				}
				}

				$idsss = str_replace(":","doubledot",$catArr[$j]['value']);
				$idsss = str_replace("&","andd",$idsss);
				$idsss = str_replace(".", "dot",$idsss);
				$idsss = str_replace("-", "dash",$idsss);
				$idsss = str_replace("'", "singlecomma",$idsss);
				$idsss = str_replace(" ","_",$idsss);

				$name = '';
				if($facetField == 'category'){
				$name = 'cid';
				}else if($facetField == 'brand'){
				$name = 'mid';
				}else if($facetField == 'size'){
				$name = 'size';
				}else if($facetField == 'badges'){
				$name = 'special';
				}else if($facetField == 'rating_avg'){
				$name = 'rating_avg';
				}
				if ($name === '') {
					continue;
				}

				// Keep selected facet highlighted after AJAX refine (facets always ship active=0).
				$selectedForFacet = [];
				if ($name === 'cid') {
					$selectedForFacet = $autoFilters['categories'] ?? [];
				} elseif ($name === 'mid') {
					$selectedForFacet = $autoFilters['brands'] ?? [];
				} elseif ($name === 'size') {
					$selectedForFacet = $autoFilters['size'] ?? [];
				} elseif ($name === 'special') {
					$selectedForFacet = $autoFilters['special'] ?? [];
				} elseif ($name === 'rating_avg') {
					$selectedForFacet = $autoFilters['rating_avg'] ?? [];
				}
				$facetValue = (string) ($catArr[$j]['value'] ?? '');
				$isSelected = in_array($facetValue, array_map('strval', (array) $selectedForFacet), true);
				if ($isSelected || (string) ($catArr[$j]['active'] ?? '') === '1') {
					$sentData = ' class="active"';
				} else {
					$sentData = '';
				}

				$searchKeyword1 = str_replace("/","backslash",$searchKeyword);
				$setURL = config('global.SITE_URL')."p4u/key-".$searchKeyword1."/view";

				$optionLabel = (string) ($catArr[$j]['label'] ?? $facetValue);
				$ariaLabel = $optionLabel;
				if ($name === 'rating_avg') {
					$starLevel = (int) $facetValue;
					if ($starLevel < 3 || $starLevel > 5) {
						continue;
					}
					$starTitle = $starLevel === 5 ? '5' : $starLevel . '+';
					$ariaLabel = $starTitle . ' star rating';
					$optionLabel = '<span class="yotpo yotpo-filter-stars filter-star-rating" aria-hidden="true"><span class="yotpo-stars">';
					for ($s = 1; $s <= 5; $s++) {
						$icon = $s <= $starLevel ? 'yotpo-icon-star' : 'yotpo-icon-empty-star';
						$optionLabel .= '<span class="yotpo-icon ' . $icon . ' rating-star pull-left"></span>';
					}
					$optionLabel .= '</span>';
					if ($starLevel >= 3 && $starLevel <= 4) {
						$optionLabel .= '<span class="star-plus">+</span>';
					}
					$optionLabel .= '</span>';
				}

				$ariaChecked = ($isSelected || (string) ($catArr[$j]['active'] ?? '') === '1') ? 'true' : 'false';
				if($facetField == 'badges'){
				$fltrsData .= '<li id="li'.$name.$idsss.'" class="setChk"><a href="javascript:void(0);" name="chk'.$name.'" id="'.$name.$idsss.'" value="'.$getInitials.'" data-url="'.$setURL.'" data-object-val="txt'.$name.'" data-name="'.$name.'" '.$sentData.' role="checkbox" aria-checked="'.$ariaChecked.'" aria-label="'.htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8').'">'.$optionLabel.'</a></li>';
				}else{
				$fltrsData .= '<li id="li'.$name.$idsss.'" class="setChk"><a href="javascript:void(0);" name="chk'.$name.'" id="'.$name.$idsss.'" value="'.$idsss.'" data-url="'.$setURL.'" data-object-val="txt'.$name.'" data-name="'.$name.'" '.$sentData.' role="checkbox" aria-checked="'.$ariaChecked.'" aria-label="'.htmlspecialchars($ariaLabel, ENT_QUOTES, 'UTF-8').'">'.$optionLabel.'</a></li>';
				}

				}}
				$fltrsData .= '</ul></div>';
				}}}

				// Price range slider — bounds follow current result set (not fixed buckets).
				if ($oMaxPrice > 0 || $oMinPrice > 0) {
					$fmt = static function (float $n): string {
						return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
					};
					$fltrsData .= '<div class="src-fbox auto-price-box" role="group" aria-labelledby="filter-price">
				<h3 id="filter-price">Price</h3>
				<div class="auto-price-slider filters-p">
					<input type="hidden" id="auto-pricechange" value="' . ($priceActive ? '1' : '0') . '"/>
					<input type="hidden" id="auto-minprice" value="' . htmlspecialchars((string) $oMinPrice, ENT_QUOTES, 'UTF-8') . '"/>
					<input type="hidden" id="auto-maxprice" value="' . htmlspecialchars((string) $oMaxPrice, ENT_QUOTES, 'UTF-8') . '"/>
					<input type="hidden" id="auto-chgminprice" value="' . htmlspecialchars((string) $selMinPrice, ENT_QUOTES, 'UTF-8') . '"/>
					<input type="hidden" id="auto-chgmaxprice" value="' . htmlspecialchars((string) $selMaxPrice, ENT_QUOTES, 'UTF-8') . '"/>
					<div class="range_slider_controller"><div id="auto-slider-range"></div></div>
					<input type="text" id="auto-amount" readonly class="renge-price" aria-label="Selected price range" value="$' . htmlspecialchars($fmt($selMinPrice), ENT_QUOTES, 'UTF-8') . ' - $' . htmlspecialchars($fmt($selMaxPrice), ENT_QUOTES, 'UTF-8') . '"/>
				</div>
				</div>';
				}

				if($type != 'changeTabData'){$fltrsData .= '</div>';}
				if($type != 'changeTabData'){$data1 .= '<div class="src-plist" role="region" aria-label="Product List">';}
				if($cntaProducts > 0){
				
				$data1 .= '<div class="src-name" role="heading" aria-level="2"><span>Search result for <strong>"'.$suggestedQry.'"</strong></span><span style="float:right;"><input type="hidden" id="sskword" value="'.$suggestedQry.'">';
				
				if($iSBrand && $iSBrand->count() > 0){
				$BName = stripcslashes(remove_special_chars($iSBrand[0]->vmanufacture));
				$data1 .= '<a href="'.config('global.SITE_URL').$BName.'/smid-'.$iSBrand[0]->imanufactureid.'" target="_blank" aria-label="View '.$iSBrand[0]->vmanufacture.'"><strong>View '.$iSBrand[0]->vmanufacture.'</strong></a>&nbsp;&nbsp;';
				}
				
				$data1 .= '<a href="javascript:void(0);" onClick="setFilterValue();" aria-label="View All Products"><strong>View All Products</strong></a></span></div><div class="src-prdlist">';
				for($i=0;$i<$cntaProducts;$i++){
				$skuAttr = htmlspecialchars(trim((string) ($aProducts[$i]['sku'] ?? $aProducts[$i]['uid'] ?? '')), ENT_QUOTES, 'UTF-8');
				$uidAttr = htmlspecialchars(trim((string) ($aProducts[$i]['uid'] ?? '')), ENT_QUOTES, 'UTF-8');
				$data1 .= '<div class="product" data-product-id="'.$uidAttr.'" data-analytics-sku="'.$skuAttr.'" data-analytics-position="'.($i + 1).'">
				<div class="thumb lazythumb">
				<a href="'.$aProducts[$i]['product_url'].'" data-analytics-sku="'.$skuAttr.'" data-analytics-position="'.($i + 1).'" onmousedown="if(window.maxAnalyticsTrackProductClick){window.maxAnalyticsTrackProductClick(this);} return IntelliSuggest.clickItem(this, { siteId: \'faltym\', intellisuggestData:  \'' . $aProducts[$i]['intellisuggestData'] . '\', intellisuggestSignature: \''. $aProducts[$i]['intellisuggestSignature'] .'\'})" aria-label="View details for '.$aProducts[$i]['name'].'"><img class="owl-lazy" src="'.$aProducts[$i]['imageUrl'].'" alt="'.$aProducts[$i]['name'].'" style="opacity: 1;"><span class="cornar">';
				if((isset($aProducts[$i]['website_stock']) && $aProducts[$i]['website_stock'] > 0) && (isset($aProducts[$i]['max_two_day_delivery']) && $aProducts[$i]['max_two_day_delivery'] == "Yes") && (isset($aProducts[$i]['is_deal_of_week']) && $aProducts[$i]['is_deal_of_week'] == "No")){
					$data1 .= '<img src="'.config('global.SITE_IMAGES').'2day.png" alt="2 Day Delivery" width="57" height="26" />';
				}
				if(isset($aProducts[$i]['is_deal_of_week']) && $aProducts[$i]['is_deal_of_week'] == "Yes"){
					$data1 .= '
					<span class="d-block">
						<svg class="sv sv-topdeal" aria-hidden="true" role="img" width="57" height="26">
							<use href="#sv-topdeal" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-topdeal"></use>
						</svg>
					</span>
					';
				}
				$data1 .= '</span>';

				$aProducts[$i]['sale_item'] = '0';
				if(isset($aProducts[$i]['sale_price']) && $aProducts[$i]['sale_price'] > 0 && Session::get('sess_icustomerid') != '' && Session::get('eusertype') == 'Wholesaler')
				{
					$aProducts[$i]['sale_item'] = '1';
				}
				if(isset($aProducts[$i]['sale_item']) && $aProducts[$i]['sale_item'] == 1){
					$data1 .= '<svg class="sv-sale" aria-hidden="true" role="img" width="100" height="20">
						<use href="#sv-sale" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-sale"></use>
					</svg>';
				}
				$data1 .= '</a>
				</div>
				<!-- Product Icon -->
				<!--<div class="icon">-->
				<ul class="product_icon" role="group" aria-label="Product Actions">';
				/*$data1 .= '<!-- Gender Icon -->';
				if(isset($aProducts[$i]['gender'])){

				if ($aProducts[$i]['gender'] == 'M'){
					$aProducts[$i]['gender'] = "sv-men";
					$aProducts[$i]['gendernames'] = "Men";
					$for_gender = ' for Men';
				} elseif ($aProducts[$i]['gender'] == 'W'){
					$aProducts[$i]['gender'] = "sv-women";
					$aProducts[$i]['gendernames'] = "Women";
					$for_gender = ' for Women';
				} elseif ($aProducts[$i]['gender'] == 'K'){
					$aProducts[$i]['gender'] = "sv-kids";
					$aProducts[$i]['gendernames'] = "Kids";
					$for_gender = ' for Kids';
				} elseif ($aProducts[$i]['gender'] == 'U'){
					$aProducts[$i]['gender'] = "sv-unisex";
					$aProducts[$i]['gendernames'] = "Unisex";
					$for_gender = ' Unisex';
				} else{
					$aProducts[$i]['gender'] = "";
					$aProducts[$i]['gendernames'] = "";
					$for_gender = '';
				}

				$data1 .= '<li>
				<!--<a href="javascript:void(0);" title="'.$aProducts[$i]['gendernames'].' Item"><svg class="'.$aProducts[$i]['gender'].'" aria-hidden="true" role="img" width="8" height="14"><use href="#'.$aProducts[$i]['gender'].'" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'.$aProducts[$i]['gender'].'"></use></svg></a>-->
				<a href="javascript:void(0);" title="'.$aProducts[$i]['gendernames'].' Item">
					<svg class="'.$aProducts[$i]['gender'].' sv-bor" aria-hidden="true" role="img" width="15" height="15"><use href="#'.$aProducts[$i]['gender'].'" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'.$aProducts[$i]['gender'].'"></use></svg>
				</a>
				</li>';
				}
				$data1 .= '<!-- Gender Icon -->';*/
				$data1 .= '<!-- Add To Wislist Icon -->
				<li>
					<!--<a href="javascript:void(0);" class="svg-h" title="Add to wishlist" onclick="DisplayPopupBoxWishlist(\''.trim($aProducts[$i]['uid']).'\',\'yes\');"><svg class="sv-heart" aria-hidden="true" role="img" width="14" height="13"><use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use></svg></a>-->
					<a href="javascript:void(0);" class="svg-h" aria-label="Add '.$aProducts[$i]['name'].' to wishlist" onclick="DisplayPopupBoxWishlist(\''.trim($aProducts[$i]['uid']).'\',\'yes\');">
						<svg class="sv-heart sv-bor" aria-hidden="true" role="img" width="14" height="14">
							<use href="#sv-heart" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart"></use>
						</svg>
						<svg class="sv-heart-fill sv-fill" aria-hidden="true" role="img" width="14" height="14">
							<use href="#sv-heart-fill" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-heart-fill"></use>
						</svg>
					</a>
				</li>
				<!-- Add To Wislist Icon -->

				<!-- Add To Cart Icon -->';
				if(max((int)($aProducts[$i]['inventory_count'] ?? 0), (int)($aProducts[$i]['website_stock'] ?? 0)) <= 0){
				$data1 .= '<li><a class="label-cart-outstock" href="javascript:void(0);" rel="nofollow" aria-label="Out of stock: '.$aProducts[$i]['name'].'" onclick="DisplayPopupBoxAlertMe(\''.trim($aProducts[$i]['uid']).'\',\''.trim($aProducts[$i]['sku']).'\');" onmousedown="return IntelliSuggest.clickItem(this, { siteId: \'faltym\', intellisuggestData:  \'' . $aProducts[$i]['intellisuggestData'] . '\', intellisuggestSignature: \''. $aProducts[$i]['intellisuggestSignature'] .'\'})"><svg class="sv-cart-outstock" aria-hidden="true" role="img" width="14" height="13"><use href="#sv-cart-outstock" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cart-outstock"></use></svg></a></li>';
				}else{
				if(Session::get('sess_icustomerid') != '' && Session::get('eusertype') == 'Wholesaler' && isset($aProducts[$i]['product_type']) && $aProducts[$i]['product_type']== 'retailer'){
				}else{
				$data1 .= '<li><a href="javascript:void(0);" class="prodaddcart" data-product="'.trim($aProducts[$i]['uid']).'" aria-label="Add '.$aProducts[$i]['name'].' to cart" onmousedown="return IntelliSuggest.clickItem(this, { siteId: \'faltym\', intellisuggestData:  \'' . $aProducts[$i]['intellisuggestData'] . '\', intellisuggestSignature: \''. $aProducts[$i]['intellisuggestSignature'] .'\'})"><svg class="sv-cartnw vam" aria-hidden="true" role="img" width="15" height="15"><use href="#sv-cartnw" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-cartnw"></use></svg></a></li>';
				}
				}
				$data1 .= '<!-- Add To Cart Icon -->
				</ul>
				<!--</div>-->
				<!-- Product Icon -->

				<div class="product-name"><a href="'.$aProducts[$i]['product_url'].'" data-analytics-sku="'.$skuAttr.'" data-analytics-position="'.($i + 1).'" onmousedown="if(window.maxAnalyticsTrackProductClick){window.maxAnalyticsTrackProductClick(this);} return IntelliSuggest.clickItem(this, { siteId: \'faltym\', intellisuggestData:  \'' . $aProducts[$i]['intellisuggestData'] . '\', intellisuggestSignature: \''. $aProducts[$i]['intellisuggestSignature'] .'\'})" aria-label="Product: '.$aProducts[$i]['name'].'"><u><strong>'.$aProducts[$i]['name'].'</strong></u></a></div>

				<div class="minheight">
				';
				// Same Yotpo bottomLine widget as search PLP product cards (unified star UI/size).
				$data1 .= '<div class="yotpo bottomLine pb-2"'
					. ' data-appkey="MQY5nd09CBJk1IVKoMXrZmiUjvJj7s9krlkG1eL8"'
					. ' data-domain="' . htmlspecialchars(rtrim((string) (config('global.BASE_URL') ?: config('global.SITE_URL') ?: config('app.url')), '/'), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-product-id="' . htmlspecialchars(trim((string) ($aProducts[$i]['uid'] ?? '')), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-product-models="' . htmlspecialchars(trim((string) ($aProducts[$i]['uid'] ?? '')), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-name="' . htmlspecialchars((string) ($aProducts[$i]['name'] ?? ''), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-url="' . htmlspecialchars((string) ($aProducts[$i]['product_url'] ?? ''), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-image-url="' . htmlspecialchars((string) (config('global.SPEED_SIZE_URL') . ($aProducts[$i]['imageUrl'] ?? '')), ENT_QUOTES, 'UTF-8') . '"'
					. ' data-description="' . htmlspecialchars((string) ($aProducts[$i]['name'] ?? ''), ENT_QUOTES, 'UTF-8') . '">&nbsp;</div>';

				$minPrice = (float) ($aProducts[$i]['same_variation_min_price'] ?? ($aProducts[$i]['price'] ?? 0));
				$maxPrice = (float) ($aProducts[$i]['same_variation_max_price'] ?? ($aProducts[$i]['price'] ?? 0));
				$optionCount = (int) ($aProducts[$i]['same_variation_product_count'] ?? 1);
				$inStock = max((int) ($aProducts[$i]['inventory_count'] ?? 0), (int) ($aProducts[$i]['website_stock'] ?? 0)) > 0;
				$categoryId = $aProducts[$i]['category_id'] ?? '';
				$youSave = (int) ($aProducts[$i]['yousave'] ?? 0);

				if ($minPrice > 0 || $maxPrice > 0) {
					$priceLabel = ($minPrice == $maxPrice)
						? Price($minPrice)
						: Price($minPrice) . ' - ' . Price($maxPrice);
					$nicheBadge = '';
					if ((string) $categoryId === '2') {
						// IntelliSuggest is not loaded on Meili PLP; calling it on mousedown blocked NicheFragrances().
						$nicheBadge = ' <a href="javascript:void(0);" onclick="NicheFragrances(); return false;" title="NicheFragrances" tabindex="0" aria-label="Niche Fragrances"><span class="avl-round"></span></a>';
					}

					if ($inStock || $optionCount <= 1) {
						$data1 .= '<div class="product-price pb-1" aria-label="Product price"><div class="price opstion-avl">' . $priceLabel . $nicheBadge . '</div></div>';
					}

					if ($optionCount > 1) {
						if ((string) $categoryId === '2') {
							$data1 .= '<div class="opstion-avl" aria-label="Options available">' . $optionCount . ' Options Available' . $nicheBadge . '</div>';
						} else {
							$data1 .= '<div class="opstion-avl" aria-label="Options available">' . $optionCount . ' Options Available</div>';
						}
					}

					if ($youSave > 0 && $inStock && $optionCount <= 1) {
						$data1 .= '<div class="you-save pb-1" aria-label="You save ' . $youSave . ' percent">You Save: ' . $youSave . '%</div>';
					}
				}

				$data1 .= '</div>
				</div>';
				}}
				if($type != 'changeTabData'){$data1 .= '</div></div>';}
				$data2 .= '</div>';
				$data2 .= '</div>

					<script type="text/javascript">'.$GA4.'</script>';

				$allData = $data.$fltrsData.$data1.$data2;

				if($type == 'changeTabData'){
					//echo $suggestedQry.'@@@'.$fltrsData.'@@@'.$data1;
					return $suggestedQry.'@@@'.$fltrsData.'@@@'.$data1;
					//return $fltrsData.'@@@'.$data1;
				}else{
					//echo $allData;
					return $allData;
				}

				/*echo json_encode($resultArr);
				exit;
				return json_encode($resultArr);*/
		}

		public function initialsFN($str) {
		    $ret = '';
		    foreach (explode(' ', $str) as $word){
		        $ret .= strtolower($word[0]);
			}
		    return $ret;
		}

		/**
		 * JSON for header search-box product rail (Trending / Popular / New).
		 * Add ?debug=1 to include analytics SKUs + which ones failed to hydrate.
		 */
		public function popularSearches(Request $request)
		{
			$result = $this->fetchSearchBoxProducts();
			if ($request->boolean('debug')) {
				$buckets = $this->fetchSearchBoxProductSkusFromAnalytics();
				$wanted = [];
				foreach (['trending', 'popular', 'new'] as $key) {
					foreach ($buckets[$key] as $row) {
						$wanted[] = [
							'badge' => ucfirst($key === 'new' ? 'New' : $key),
							'sku' => $row['sku'],
							'clicks' => $row['clicks'] ?? 0,
						];
					}
				}
				$shown = array_map(static fn ($p) => strtolower((string) ($p['sku'] ?? '')), $result['products'] ?? []);
				$missing = [];
				foreach ($wanted as $w) {
					if (!in_array(strtolower($w['sku']), $shown, true)) {
						$missing[] = $w;
					}
				}
				$result['_debug'] = [
					'analytics' => $buckets,
					'shown_count' => count($result['products'] ?? []),
					'missing' => $missing,
					'hydrate_errors' => $result['_hydrate_errors'] ?? new \stdClass(),
				];
				unset($result['_hydrate_errors']);
			} else {
				unset($result['_hydrate_errors']);
			}

			return response()->json($result);
		}

		/**
		 * Search-box focus list: Trending / Popular / New from Max click analytics only.
		 * Hydrated via Meili by exact SKU. No catalog filler — thin click data = shorter list.
		 *
		 * @return array{products: list<array{name:string,meta:string,thumb:string,link:string,badge:string,sku?:string}>}
		 */
		private function fetchSearchBoxProducts(): array
		{
			$cacheKey = 'max_search_box_products_v11';
			$cached = Cache::get($cacheKey);
			if (is_array($cached) && isset($cached['products']) && $cached['products'] !== []) {
				return $cached;
			}

			$seen = [];
			$byBadge = ['Trending' => [], 'Popular' => [], 'New' => []];
			$hydrateErrors = [];

			try {
				$buckets = $this->fetchSearchBoxProductSkusFromAnalytics();
			} catch (\Throwable $e) {
				\Log::warning('Search box analytics fetch failed: ' . $e->getMessage());
				$buckets = ['trending' => [], 'popular' => [], 'new' => []];
			}

			foreach (['Trending' => $buckets['trending'], 'Popular' => $buckets['popular'], 'New' => $buckets['new']] as $badge => $rows) {
				foreach ($rows as $row) {
					$sku = trim((string) ($row['sku'] ?? ''));
					if ($sku === '') {
						continue;
					}
					$key = strtolower($sku);
					if (isset($seen[$key])) {
						continue;
					}
					try {
						$card = $this->hydrateSearchBoxProductBySku($sku, $badge);
					} catch (\Throwable $e) {
						$hydrateErrors[$sku] = $e->getMessage();
						\Log::warning('Search box hydrate failed: ' . $e->getMessage(), ['sku' => $sku]);
						$card = null;
					}
					if ($card === null) {
						if (!isset($hydrateErrors[$sku])) {
							$hydrateErrors[$sku] = 'not found in Meili or DB';
						}
						continue;
					}
					$seen[$key] = true;
					$seen[strtolower((string) ($card['sku'] ?? $sku))] = true;
					$byBadge[$badge][] = $card;
				}
			}

			// If 48h trending is empty, promote top popular clicks (still analytics).
			if ($byBadge['Trending'] === [] && $byBadge['Popular'] !== []) {
				$byBadge['Trending'] = array_splice($byBadge['Popular'], 0, min(2, count($byBadge['Popular'])));
				foreach ($byBadge['Trending'] as &$card) {
					$card['badge'] = 'Trending';
				}
				unset($card);
			}

			$products = array_merge(
				array_slice($byBadge['Trending'], 0, 2),
				array_slice($byBadge['Popular'], 0, 2),
				array_slice($byBadge['New'], 0, 2)
			);

			$result = ['products' => $products];
			if ($hydrateErrors !== []) {
				$result['_hydrate_errors'] = $hydrateErrors;
			}
			if ($products !== []) {
				Cache::put($cacheKey, $result, now()->addMinutes(2));
			}
			return $result;
		}

		/**
		 * SKU buckets from Max click analytics (click_events_max).
		 *
		 * @return array{trending: list<array{sku:string,clicks?:int}>, popular: list<array{sku:string,clicks?:int}>, new: list<array{sku:string,clicks?:int}>}
		 */
		private function fetchSearchBoxProductSkusFromAnalytics(): array
		{
			$empty = ['trending' => [], 'popular' => [], 'new' => []];
			$decoded = $this->fetchMaxAnalyticsJson('/analytics/max/search-box-products?trending=2&popular=2&new=2');
			if (!is_array($decoded)) {
				return $empty;
			}
			$norm = static function ($rows): array {
				$out = [];
				if (!is_array($rows)) {
					return $out;
				}
				foreach ($rows as $row) {
					$sku = trim((string) ($row['sku'] ?? $row['ObjectID'] ?? ''));
					if ($sku === '') {
						continue;
					}
					$out[] = [
						'sku' => $sku,
						'clicks' => (int) ($row['clicks'] ?? 0),
					];
				}
				return $out;
			};

			return [
				'trending' => $norm($decoded['trending'] ?? []),
				'popular' => $norm($decoded['popular'] ?? []),
				'new' => $norm($decoded['new'] ?? []),
			];
		}

		/**
		 * Resolve a product card: DB first (reliable on UAT), then Meili.
		 *
		 * @return array{name:string,meta:string,thumb:string,link:string,badge:string,sku:string}|null
		 */
		private function hydrateSearchBoxProductBySku(string $sku, string $badge): ?array
		{
			$sku = trim($sku);
			if ($sku === '') {
				return null;
			}

			// Prefer DB on UAT — Meili index / product_type filters often miss clicked SKUs.
			$dbCard = $this->hydrateSearchBoxProductFromDb($sku, $badge);
			if ($dbCard !== null) {
				return $dbCard;
			}

			try {
				$svc = $this->customProductSearch();
				if (method_exists($svc, 'findExactSku')) {
					$direct = $svc->findExactSku($sku);
					if ($direct !== null) {
						return $this->formatSearchBoxProductHit($direct, $badge, $sku);
					}
				}
			} catch (\Throwable $e) {
				\Log::warning('Search box Meili exact lookup failed: ' . $e->getMessage(), ['sku' => $sku]);
			}

			try {
				$skuLower = strtolower($sku);
				$result = $this->runMeilisearchProductSearch($sku, [], 1, 24);
				foreach (($result['hits'] ?? []) as $hit) {
					$hit = is_object($hit) ? $hit : (object) $hit;
					$hitSku = strtolower(trim((string) ($hit->sku ?? '')));
					$hitUid = strtolower(trim((string) ($hit->uid ?? '')));
					if ($hitSku === $skuLower || $hitUid === $skuLower) {
						return $this->formatSearchBoxProductHit($hit, $badge, $sku);
					}
				}
			} catch (\Throwable $e) {
				\Log::warning('Search box Meili search failed: ' . $e->getMessage(), ['sku' => $sku]);
			}

			return null;
		}

		/**
		 * Build a search-box card from MySQL when Meili cannot resolve the SKU.
		 *
		 * @return array{name:string,meta:string,thumb:string,link:string,badge:string,sku:string}|null
		 */
		private function hydrateSearchBoxProductFromDb(string $sku, string $badge): ?array
		{
			try {
				$skuNorm = strtolower(trim($sku));
				$row = DB::table('pu_products as p')
					->leftJoin('pu_brand as b', 'b.brand_id', '=', 'p.brand_id')
					->whereRaw('LOWER(TRIM(p.sku)) = ?', [$skuNorm])
					->select([
						'p.products_id',
						'p.sku',
						'p.product_name',
						'p.image',
						'p.gender',
						'p.status',
						'b.brand_name',
					])
					->orderByRaw("CASE WHEN p.status = '1' THEN 0 ELSE 1 END")
					->first();
				if (!$row) {
					return null;
				}

				$categoryId = 0;
				try {
					$categoryId = (int) (DB::table('pu_products_category')
						->where('products_id', (int) $row->products_id)
						->orderBy('category_id')
						->value('category_id') ?? 0);
				} catch (\Throwable $e) {
					$categoryId = 0;
				}

				$name = trim((string) ($row->product_name ?? ''));
				if ($name === '') {
					return null;
				}

				$link = '';
				if (function_exists('SetProductURL')) {
					try {
						$link = (string) SetProductURL((int) $row->products_id, $name, $categoryId > 0 ? $categoryId : '');
					} catch (\Throwable $e) {
						$link = '';
					}
				}

				$img = $this->normalizeSearchBoxImageUrl((string) ($row->image ?? ''));

				$genderRaw = strtoupper(trim((string) ($row->gender ?? '')));
				$genderLabel = '';
				if ($genderRaw === 'M') {
					$genderLabel = 'Men';
				} elseif ($genderRaw === 'W') {
					$genderLabel = 'Women';
				} elseif ($genderRaw === 'U') {
					$genderLabel = 'Unisex';
				} elseif ($genderRaw === 'K') {
					$genderLabel = 'Kids';
				}
				$brand = trim((string) ($row->brand_name ?? ''));
				$meta = implode(' · ', array_values(array_filter([$genderLabel, $brand !== '' ? $brand : 'Perfume'])));

				return [
					'name' => $name,
					'meta' => $meta,
					'thumb' => $img,
					'link' => $link,
					'badge' => $badge,
					'sku' => trim((string) ($row->sku ?? $sku)),
				];
			} catch (\Throwable $e) {
				\Log::warning('Search box DB hydrate failed: ' . $e->getMessage(), ['sku' => $sku]);
				return null;
			}
		}

		/**
		 * Force search-box thumbs onto live maxaroma.com (not localhost / UAT).
		 */
		private function normalizeSearchBoxImageUrl(string $img): string
		{
			$img = trim($img);
			if ($img === '' || preg_match('#noimage#i', $img)) {
				return '';
			}

			$liveBase = 'https://www.maxaroma.com';

			// Absolute URL → rewrite host to live, keep path.
			if (preg_match('#^https?://#i', $img)) {
				$path = (string) (parse_url($img, PHP_URL_PATH) ?? '');
				$query = (string) (parse_url($img, PHP_URL_QUERY) ?? '');
				if ($path === '' || preg_match('#noimage#i', $path)) {
					return '';
				}
				return $liveBase . $path . ($query !== '' ? ('?' . $query) : '');
			}

			// Filename only or relative path.
			$file = basename(str_replace('\\', '/', $img));
			if ($file === '' || preg_match('#noimage#i', $file)) {
				return '';
			}
			if (stripos($img, 'productimages/') !== false) {
				$path = '/' . ltrim(str_replace('\\', '/', $img), '/');
				return $liveBase . $path;
			}

			return $liveBase . '/productimages/large/' . $file;
		}

		/**
		 * @param object $hit
		 * @return array{name:string,meta:string,thumb:string,link:string,badge:string,sku:string}
		 */
		private function formatSearchBoxProductHit(object $hit, string $badge, string $skuFallback = ''): array
		{
			$sku = trim((string) ($hit->sku ?? $hit->uid ?? $skuFallback));
			$name = trim((string) ($hit->name ?? ''));
			$img = $this->normalizeSearchBoxImageUrl((string) ($hit->imageUrl ?? ''));

			$genderRaw = strtoupper(trim((string) ($hit->gender ?? '')));
			$genderLabel = '';
			if ($genderRaw === 'M') {
				$genderLabel = 'Men';
			} elseif ($genderRaw === 'W') {
				$genderLabel = 'Women';
			} elseif ($genderRaw === 'U') {
				$genderLabel = 'Unisex';
			} elseif ($genderRaw === 'K') {
				$genderLabel = 'Kids';
			}
			$brand = trim((string) ($hit->brand ?? ''));
			$metaParts = array_values(array_filter([$genderLabel, $brand !== '' ? $brand : 'Perfume']));
			$meta = implode(' · ', $metaParts);

			return [
				'name' => $name,
				'meta' => $meta,
				'thumb' => $img,
				'link' => (string) ($hit->product_url ?? ''),
				'badge' => $badge,
				'sku' => $sku,
			];
		}

		/**
		 * Same-origin proxy: browser → Laravel → analytics (avoids ngrok interstitial on UAT).
		 */
		public function proxyAnalyticsSearch(Request $request)
		{
			return $this->proxyAnalyticsIngest('/analytics/search', $request);
		}

		/**
		 * Same-origin proxy for product click events from search listing.
		 */
		public function proxyAnalyticsClick(Request $request)
		{
			return $this->proxyAnalyticsIngest('/analytics/click', $request);
		}

		/**
		 * Forward JSON ingest body to Max analytics server.
		 */
		private function proxyAnalyticsIngest(string $path, Request $request)
		{
			$payload = $request->all();
			if (!is_array($payload)) {
				$payload = [];
			}
			if (empty($payload['project'])) {
				$payload['project'] = 'max';
			}

			$body = json_encode($payload);
			$path = '/' . ltrim($path, '/');
			$bases = [];
			foreach ([
				env('MAX_ANALYTICS_ENDPOINT'),
				config('services.max_analytics.endpoint'),
				'https://lianne-barefaced-soberly.ngrok-free.dev',
				'http://127.0.0.1:3001',
			] as $candidate) {
				$base = rtrim(trim((string) $candidate), '/');
				if ($base === '' || isset($bases[$base])) {
					continue;
				}
				$bases[$base] = true;
			}

			$lastError = 'analytics unreachable';
			foreach (array_keys($bases) as $base) {
				$ctx = stream_context_create([
					'http' => [
						'method' => 'POST',
						'header' => "Content-Type: application/json\r\nAccept: application/json\r\nUser-Agent: MaxaromaProxy/1.0\r\nngrok-skip-browser-warning: 1\r\nContent-Length: " . strlen((string) $body) . "\r\n",
						'content' => $body,
						'timeout' => 4,
						'ignore_errors' => true,
					],
					'ssl' => [
						'verify_peer' => false,
						'verify_peer_name' => false,
					],
				]);
				$raw = @file_get_contents($base . $path, false, $ctx);
				if (!is_string($raw) || $raw === '') {
					$lastError = 'empty response from ' . $base;
					continue;
				}
				$trimmed = ltrim($raw);
				if ($trimmed === '' || ($trimmed[0] !== '{' && $trimmed[0] !== '[')) {
					$lastError = 'non-json from ' . $base;
					continue;
				}
				$decoded = json_decode($raw, true);
				if (!is_array($decoded)) {
					$lastError = 'invalid json from ' . $base;
					continue;
				}
				$status = 200;
				if (isset($http_response_header) && is_array($http_response_header)) {
					foreach ($http_response_header as $hdr) {
						if (preg_match('#^HTTP/\S+\s+(\d+)#', $hdr, $m)) {
							$status = (int) $m[1];
							break;
						}
					}
				}
				return response()->json($decoded, $status >= 100 && $status < 600 ? $status : 200);
			}

			return response()->json(['status' => 'error', 'error' => $lastError], 502);
		}

		/**
		 * GET JSON from Max analytics.
		 * Tries env/config endpoint, then public tunnel, then local :3001.
		 *
		 * @return array<string, mixed>|null
		 */
		private function fetchMaxAnalyticsJson(string $pathAndQuery): ?array
		{
			$pathAndQuery = '/' . ltrim($pathAndQuery, '/');
			$bases = [];
			foreach ([
				env('MAX_ANALYTICS_ENDPOINT'),
				config('services.max_analytics.endpoint'),
				// Same public tunnel the browser uses (required on UAT — 127.0.0.1 is the UAT box, not analytics).
				'https://lianne-barefaced-soberly.ngrok-free.dev',
				'http://127.0.0.1:3001',
			] as $candidate) {
				$base = rtrim(trim((string) $candidate), '/');
				if ($base === '' || isset($bases[$base])) {
					continue;
				}
				$bases[$base] = true;
			}

			$ctx = stream_context_create([
				'http' => [
					'timeout' => 4,
					'ignore_errors' => true,
					'header' => "Accept: application/json\r\nUser-Agent: MaxaromaSearchBox/1.0\r\nngrok-skip-browser-warning: 1\r\n",
				],
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
				],
			]);

			$tryDecode = static function (?string $raw): ?array {
				if (!is_string($raw) || $raw === '') {
					return null;
				}
				$trimmed = ltrim($raw);
				if ($trimmed === '' || ($trimmed[0] !== '{' && $trimmed[0] !== '[')) {
					return null;
				}
				$decoded = json_decode($raw, true);
				return is_array($decoded) ? $decoded : null;
			};

			foreach (array_keys($bases) as $base) {
				$decoded = $tryDecode(@file_get_contents($base . $pathAndQuery, false, $ctx));
				if ($decoded !== null) {
					return $decoded;
				}
			}

			return null;
		}

		/**
		 * Popular Search panel — top queries from Max analytics (search_events_max).
		 *
		 * @return array{trending: array{queries: list<array{searchQuery: string, popularity?: int}>}}
		 */
		private function fetchPopularSearchQueries(): array
		{
			$empty = ['trending' => ['queries' => []]];
			$cacheKey = 'max_analytics_popular_searches_v1';
			$cached = Cache::get($cacheKey);
			if (is_array($cached) && isset($cached['trending']['queries'])) {
				return $cached;
			}
			try {
				$decoded = $this->fetchMaxAnalyticsJson('/analytics/max/popular-searches?range=7d&limit=8');
				$queries = is_array($decoded)
					? ($decoded['trending']['queries'] ?? $decoded['queries'] ?? null)
					: null;
				if (!is_array($queries) || $queries === []) {
					Cache::put($cacheKey, $empty, now()->addMinutes(2));
					return $empty;
				}
				$normalized = [];
				foreach ($queries as $row) {
					$q = trim((string) ($row['searchQuery'] ?? $row['query'] ?? ''));
					if ($q === '') {
						continue;
					}
					$normalized[] = [
						'searchQuery' => $q,
						'popularity' => (int) ($row['popularity'] ?? $row['searches'] ?? 0),
					];
				}
				$result = ['trending' => ['queries' => $normalized]];
				Cache::put($cacheKey, $result, now()->addMinutes(10));
				return $result;
			} catch (\Throwable $e) {
				\Log::warning('Popular search analytics fetch failed: ' . $e->getMessage());
				Cache::put($cacheKey, $empty, now()->addMinutes(2));
				return $empty;
			}
		}

		/**
		 * Debug: exact Meilisearch + NLP payload for a keyword.
		 * GET /__search/meili-debug?q=long+lasting+perfumes
		 * Allowed when APP_DEBUG=true, or ?token= matches MEILI_DEBUG_TOKEN in .env
		 */
		public function meilisearchDebug(Request $request)
		{
			$token = (string) env('MEILI_DEBUG_TOKEN', '');
			$tokenOk = $token !== ''
				&& hash_equals($token, (string) $request->query('token', ''));
			if (!config('app.debug') && !$tokenOk) {
				abort(404);
			}

			$keyword = (string) $request->query('q', $request->query('keyword', ''));
			$filters = $request->query('filters', []);
			if (is_string($filters) && $filters !== '') {
				$filters = json_decode($filters, true) ?: [];
			}
			if (!is_array($filters)) {
				$filters = [];
			}

			$page = max(1, (int) $request->query('page', 1));
			$perPage = max(1, min(100, (int) $request->query('per_page', 24)));

			$normalized = $this->normalizeSearchKeywordForMeilisearch($keyword);
			$search = $this->customProductSearch();

			$nlpDebug = [
				'enabled' => false,
				'applied' => false,
				'probe' => null,
				'extracted' => null,
				'mapped_filters' => [],
				'resolved_keyword' => $normalized,
			];
			$searchFilters = $filters;
			$searchKeyword = $normalized;
			try {
				$nlpService = app(PerfumeNlpSearchService::class);
				$nlpDebug['enabled'] = $nlpService->enabled();
				$resolved = $nlpService->resolveForSearchPage($normalized);
				$nlpDebug['probe'] = $resolved['probe'] ?? null;
				$nlpDebug['applied'] = !empty($resolved['applied']);
				$nlpDebug['extracted'] = $resolved['nlp'] ?? null;
				$nlpDebug['mapped_filters'] = $resolved['filters'] ?? [];
				$nlpDebug['resolved_keyword'] = $resolved['keyword'] ?? $normalized;
				if (!empty($resolved['applied'])) {
					$searchKeyword = (string) ($resolved['keyword'] ?? $normalized);
					$searchFilters = array_merge($filters, is_array($resolved['filters'] ?? null) ? $resolved['filters'] : []);
				}
			} catch (\Throwable $e) {
				$nlpDebug['error'] = $e->getMessage();
			}

			$result = $search->search($searchKeyword, $searchFilters, $page, $perPage, true);

			return response()->json([
				'input' => [
					'q' => $keyword,
					'normalized_keyword' => $normalized,
					'filters' => $filters,
					'page' => $page,
					'per_page' => $perPage,
				],
				'deploy_fingerprint' => [
					'custom_product_search_service' => [
						'path' => (new \ReflectionClass($search))->getFileName(),
						'mtime' => @filemtime((new \ReflectionClass($search))->getFileName()) ?: null,
						'sha1_12' => substr(sha1_file((new \ReflectionClass($search))->getFileName()) ?: '', 0, 12),
						'has_foldEnglishPlurals' => method_exists($search, 'prepareSearchQuery')
							&& str_contains(
								(string) @file_get_contents((new \ReflectionClass($search))->getFileName()),
								'foldEnglishPluralsInQuery'
							),
					],
					'product_filter_catalog' => [
						'path' => app_path('Catalog/product_filter_catalog.php'),
						'exists' => is_file(app_path('Catalog/product_filter_catalog.php')),
						'mtime' => @filemtime(app_path('Catalog/product_filter_catalog.php')) ?: null,
						'sha1_12' => is_file(app_path('Catalog/product_filter_catalog.php'))
							? substr(sha1_file(app_path('Catalog/product_filter_catalog.php')) ?: '', 0, 12)
							: null,
						'searchable_attributes' => app(\App\Services\ProductFilterCatalogService::class)
							->searchableAttributes(),
						'active' => app(\App\Services\ProductFilterCatalogService::class)->activeName(),
					],
					'prepareSearchQuery_sample' => $search->prepareSearchQuery('long lasting perfumes'),
					'php_sapi' => PHP_SAPI,
					'opcache_enabled' => function_exists('opcache_get_status')
						? (bool) (opcache_get_status(false)['opcache_enabled'] ?? false)
						: null,
				],
				'nlp' => $nlpDebug,
				'product_type_rule' => $search->getLastSearchDebug()['product_type_rule'] ?? null,
				'meilisearch_filter' => $search->getLastSearchDebug()['meilisearch_filter'] ?? null,
				'meilisearch' => $search->getLastSearchDebug(),
				'result' => [
					'total' => $result['total'],
					'hits_on_page' => count($result['hits']),
					'prepared_query' => $result['query'],
					'min_price' => $result['minPrice'],
					'max_price' => $result['maxPrice'],
				],
			]);
		}

		/**
		 * Shareable search query params → Meili filter array.
		 * Supports ?OnTopRated=yes&InStockItems=yes&family=Woody&mid=123,456&price=10-50
		 *
		 * @return array<string, mixed>
		 */
		protected function filtersFromShareableQuery(Request $request): array
		{
			$out = [];
			$yes = static function ($v): bool {
				return in_array(strtolower(trim((string) $v)), ['yes', 'true', '1'], true);
			};

			if ($request->filled('OnTopRated') && $yes($request->query('OnTopRated'))) {
				$out['OnTopRated'] = true;
			}
			if ($request->filled('InStockItems') && $yes($request->query('InStockItems'))) {
				$out['InStockItems'] = true;
			}
			if ($request->filled('OnSaleDeal') && $yes($request->query('OnSaleDeal'))) {
				$out['OnSaleDeal'] = true;
			}

			$map = [
				'mid' => 'brands',
				'brands' => 'brands',
				'brand' => 'brands',
				'cid' => 'categories',
				'categories' => 'categories',
				'category' => 'categories',
				'family' => 'fragrance_family',
				'fragrance_family' => 'fragrance_family',
				'personality' => 'fragrance_personality',
				'fragrance_personality' => 'fragrance_personality',
				'seasons' => 'fragrance_seasons',
				'fragrance_seasons' => 'fragrance_seasons',
				'occasion' => 'fragrance_occasion',
				'fragrance_occasion' => 'fragrance_occasion',
				'type' => 'vtype',
				'vtype' => 'vtype',
				'formulation' => 'formulation',
				'size' => 'size',
				'features' => 'features',
				'by_feature' => 'features',
				'special' => 'special',
				'badges' => 'special',
				'coverage' => 'coverage',
				'finish' => 'finish',
				'skin_type' => 'skin_type',
				'rating_avg' => 'rating_avg',
				'top_note' => 'top_note',
				'middle_note' => 'middle_note',
				'base_note' => 'base_note',
				'fragrance_notes' => 'fragrance_notes',
			];

			foreach ($map as $queryKey => $filterKey) {
				if (!$request->filled($queryKey)) {
					continue;
				}
				$raw = (string) $request->query($queryKey);
				$values = array_values(array_filter(array_map('trim', explode(',', $raw)), static fn ($v) => $v !== ''));
				if ($values === []) {
					continue;
				}
				$encoded = [];
				foreach ($values as $v) {
					$encoded[] = method_exists($this, 'customProductSearch')
						? $this->customProductSearch()->encodeFilterValue($v)
						: $v;
				}
				$out[$filterKey] = array_values(array_unique(array_merge($out[$filterKey] ?? [], $encoded)));
			}

			if ($request->filled('price')) {
				$rawPrice = trim((string) $request->query('price'));
				if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)?$/u', $rawPrice, $pm)) {
					$out['minprice'] = (float) $pm[1];
					$out['ochangeprice'] = '1';
					if (isset($pm[2]) && $pm[2] !== '') {
						$out['maxprice'] = (float) $pm[2];
					}
				}
			}
			if ($request->filled('sortby')) {
				$out['sortby'] = (string) $request->query('sortby');
			}

			return $out;
		}

		/**
		 * gbproduction-style admin redirect rules (cache from pu-panel).
		 *
		 * @return \Illuminate\Http\RedirectResponse|null
		 */
		protected function trySearchRedirectRule(string $keyword)
		{
			$keyword = trim($keyword);
			if ($keyword === '') {
				return null;
			}
			try {
				$svc = app(\App\Services\SearchRedirectRuleService::class);
				$matchKeyword = $this->normalizeSearchKeywordForMeilisearch($keyword);
				if ($matchKeyword === '') {
					return null;
				}
				$rule = $svc->match($matchKeyword, $svc->requestDevice());
				if ($rule === null) {
					return null;
				}
				$url = trim((string) ($rule['resolved_url'] ?? ''));
				if ($url === '') {
					return null;
				}
				return $this->searchRedirectResponse($url);
			} catch (\Throwable $e) {
				\Log::warning('Search redirect rule lookup failed: ' . $e->getMessage());
				return null;
			}
		}

		/**
		 * @return \Illuminate\Http\RedirectResponse
		 */
		protected function searchRedirectResponse(string $url)
		{
			$siteUrl = rtrim((string) config('global.SITE_URL', config('app.url')), '/');
			if (preg_match('#^https?://#i', $url) && $siteUrl !== '' && stripos($url, $siteUrl) === 0) {
				$path = substr($url, strlen($siteUrl));
				if ($path === '' || $path === false) {
					$path = '/';
				}
				if ($path[0] !== '/') {
					$path = '/' . $path;
				}
				return redirect($path, 302);
			}
			if (preg_match('#^https?://#i', $url)) {
				return redirect()->away($url, 302);
			}
			if ($url[0] !== '/') {
				$url = '/' . $url;
			}
			return redirect($url, 302);
		}
	}