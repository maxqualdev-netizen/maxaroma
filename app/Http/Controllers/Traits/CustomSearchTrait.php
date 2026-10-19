<?php

namespace App\Http\Controllers\Traits;

use App\Services\CustomProductSearchService;
use App\Services\SearchResultRulesService;

trait CustomSearchTrait
{
	protected function customProductSearch(): CustomProductSearchService
	{
		return app(CustomProductSearchService::class);
	}

	/**
	 * gbproduction-style keyword cleanup before Meilisearch (keeps spaces; URL dashes decoded).
	 */
	protected function normalizeSearchKeywordForMeilisearch(string $keyword): string
	{
		$keyword = strip_tags($keyword);
		$keyword = str_replace(['andd', 'backslash'], ['&', '/'], $keyword);
		$keyword = preg_replace('/[\x80-\xFF]/', '', $keyword) ?? '';
		$keyword = trim(preg_replace('/\s+/u', ' ', str_replace('-', ' ', $keyword)) ?? '');
		return $keyword;
	}

	/**
	 * @param array<string, mixed> $filters
	 * @param string|null $ruleMatchQuery Shopper-facing query used to match pin/hide rules (not NLP-stripped).
	 */
	protected function runMeilisearchProductSearch(
		string $keyword,
		array $filters,
		int $page,
		int $perPage,
		bool $captureDebug = false,
		?string $ruleMatchQuery = null
	): array {
		$result = $this->customProductSearch()->search(
			$this->normalizeSearchKeywordForMeilisearch($keyword),
			$filters,
			$page,
			$perPage,
			$captureDebug
		);

		$matchQuery = $this->normalizeSearchKeywordForMeilisearch(
			$ruleMatchQuery !== null && trim($ruleMatchQuery) !== '' ? $ruleMatchQuery : $keyword
		);
		if ($matchQuery === '') {
			return $result;
		}

		$search = $this->customProductSearch();

		return app(SearchResultRulesService::class)->applyToSearchResult(
			$result,
			$matchQuery,
			$page,
			$perPage,
			static function (string $sku) use ($search) {
				return $search->findExactSku($sku);
			}
		);
	}

	/**
	 * As-you-type popup: lightweight Meili window (not full PLP 2500-hit fetch).
	 *
	 * @param array<string, mixed> $filters
	 */
	protected function runMeilisearchAutocomplete(string $keyword, array $filters = [], int $perPage = 15): array
	{
		$result = $this->customProductSearch()->autocomplete(
			$this->normalizeSearchKeywordForMeilisearch($keyword),
			$filters,
			$perPage
		);

		$matchQuery = $this->normalizeSearchKeywordForMeilisearch($keyword);
		if ($matchQuery === '') {
			$rankHint = trim((string) ($filters['_rank_query'] ?? ''));
			$matchQuery = $this->normalizeSearchKeywordForMeilisearch($rankHint);
		}
		if ($matchQuery === '') {
			return $result;
		}

		$search = $this->customProductSearch();

		return app(SearchResultRulesService::class)->applyToSearchResult(
			$result,
			$matchQuery,
			1,
			$perPage,
			static function (string $sku) use ($search) {
				return $search->findExactSku($sku);
			}
		);
	}

	/**
	 * Convert Meilisearch facets into the listing filter HTML shape.
	 *
	 * @param list<array<string, mixed>> $facets
	 * @param array<string, mixed> $selected
	 * @return list<array<string, mixed>>
	 */
	protected function listingFiltersFromFacets(array $facets, array $selected = []): array
	{
		$allFilters = [];
		$search = $this->customProductSearch();

		foreach ($facets as $facet) {
			$field = $facet['field'] ?? '';
			$facet['Attr'] = $facet['Selected'] = $facet['Data'] = [];
			$facet['Attr']['title'] = $facet['label'] ?? $field;
			$facet['Attr']['id'] = $field;
			$facet['Attr']['filterval'] = 'key';
			$facet['Attr']['name'] = $this->searchFilterInputName((string) $field);
			$facet['Selected'] = $this->selectedValuesForFacet((string) $field, $selected);

			// Size: keep checkbox selected across equivalent labels (28 ml ↔ 0.94 Oz / 28 ml).
			if ($field === 'size' && $facet['Selected'] !== []) {
				$expanded = [];
				foreach ($facet['Selected'] as $sel) {
					$decoded = $search->decodeFilterValue((string) $sel);
					foreach (\App\Support\SizeNormalizer::matchValues($decoded) as $alias) {
						$expanded[] = $search->encodeFilterValue($alias);
					}
					$expanded[] = (string) $sel;
				}
				$facet['Selected'] = array_values(array_unique($expanded));
			}

			$items = [];
			foreach ((array) ($facet['values'] ?? []) as $item) {
				if (!isset($item['value']) || $item['value'] === '') {
					continue;
				}
				$encoded = $search->encodeFilterValue((string) $item['value']);
				$items[$encoded] = $item['label'] ?? $item['value'];
			}
			$facet['Data'] = $items;
			$allFilters[][$facet['Attr']['title']] = $facet;
		}

		return $allFilters;
	}

	protected function searchFilterInputName(string $field): string
	{
		return match ($field) {
			'brand' => 'mid',
			'category' => 'cid',
			'size' => 'size',
			'badges' => 'special',
			'top_note' => 'top_note',
			'middle_note' => 'middle_note',
			'base_note' => 'base_note',
			'fragrance_notes' => 'fragrance_notes',
			'rating_avg' => 'rating_avg',
			default => '',
		};
	}

	/**
	 * @param array<string, mixed> $selected
	 * @return list<mixed>
	 */
	protected function selectedValuesForFacet(string $field, array $selected): array
	{
		$map = [
			'brand' => 'brands',
			'category' => 'categories',
			'size' => 'size',
			'badges' => 'special',
			'formulation' => 'formulation',
			'by_feature' => 'features',
			'fragrance_family' => 'fragrance_family',
			'type' => 'vtype',
			'coverage' => 'coverage',
			'finish' => 'finish',
			'skin_type' => 'skin_type',
			'seasons' => 'fragrance_seasons',
			'occasion' => 'fragrance_occasion',
			'personality' => 'fragrance_personality',
			'rating_avg' => 'rating_avg',
			'top_note' => 'top_note',
			'middle_note' => 'middle_note',
			'base_note' => 'base_note',
			'fragrance_notes' => 'fragrance_notes',
		];
		$key = $map[$field] ?? $field;
		$values = $selected[$key] ?? [];
		return is_array($values) ? $values : [];
	}

	/**
	 * @return list<array<string, mixed>>
	 */
	protected function hitsToAutocompleteArrays(array $hits): array
	{
		$out = [];
		foreach ($hits as $hit) {
			$stock = max((int) ($hit->website_stock ?? 0), (int) ($hit->inventory_count ?? 0));
			$price = (float) ($hit->price ?? 0);
			$msrp = (float) ($hit->msrp ?? 0);
			$minPrice = (float) ($hit->same_variation_min_price ?? $price);
			$maxPrice = (float) ($hit->same_variation_max_price ?? $price);
			$optionCount = (int) ($hit->same_variation_product_count ?? 1);
			$youSave = 0;
			if ($msrp > 0 && $price > 0 && $msrp > $price) {
				$youSave = (int) round((($msrp - $price) / $msrp) * 100);
			}
			$out[] = [
				'uid' => $hit->uid ?? '',
				'sku' => $hit->sku ?? '',
				'name' => $hit->name ?? '',
				'product_url' => $hit->product_url ?? '',
				'imageUrl' => $hit->imageUrl ?? '',
				'website_stock' => $stock,
				'inventory_count' => $stock,
				'max_two_day_delivery' => $hit->max_two_day_delivery ?? 'No',
				'is_deal_of_week' => $hit->is_deal_of_week ?? 'No',
				'sale_price' => $hit->sale_price ?? 0,
				'price' => $price,
				'msrp' => $msrp,
				'yousave' => $youSave,
				'category_id' => $hit->category_id ?? '',
				'same_variation_min_price' => $minPrice,
				'same_variation_max_price' => $maxPrice,
				'same_variation_product_count' => $optionCount,
				'product_type' => $hit->product_type ?? 'both',
				'gender' => $hit->gender ?? '',
				'rating' => $hit->rating ?? 0,
				'rating_avg' => $hit->rating_avg ?? 0,
				'intellisuggestData' => '',
				'intellisuggestSignature' => '',
			];
		}
		return $out;
	}
}
