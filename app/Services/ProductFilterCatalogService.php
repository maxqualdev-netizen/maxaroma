<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Field-driven search/PLP filters and sort. Catalog: app/Catalog/product_filter_catalog.php
 */
class ProductFilterCatalogService
{
	private array $config;

	private array $catalog;

	public function __construct()
	{
		$path = app_path('Catalog/product_filter_catalog.php');
		$this->config = is_file($path) ? (array) require $path : [];
		$active = (string) ($this->config['active'] ?? config('global.PRODUCT_CATALOG', 'perfume'));
		$catalogs = (array) ($this->config['catalogs'] ?? []);
		$this->catalog = (array) ($catalogs[$active] ?? ($catalogs['perfume'] ?? []));
	}

	public function activeName(): string
	{
		return (string) ($this->config['active'] ?? 'perfume');
	}

	/** @return array<string, array<string, mixed>> */
	public function filters(): array
	{
		return (array) ($this->catalog['filters'] ?? []);
	}

	/** @return array<string, array{disp_val: string, meili?: string}> */
	public function sortOptions(): array
	{
		$opts = (array) ($this->catalog['sort_options'] ?? []);
		return $opts !== [] ? $opts : [
			'priceLH' => ['disp_val' => 'Price Low to High', 'meili' => 'same_variation_min_price:asc'],
			'priceHL' => ['disp_val' => 'Price High to Low', 'meili' => 'same_variation_max_price:desc'],
			'ATZ' => ['disp_val' => 'A-Z', 'meili' => 'name:asc'],
			'ZTA' => ['disp_val' => 'Z-A', 'meili' => 'name:desc'],
		];
	}

	/** @return list<string> */
	public function searchableAttributes(): array
	{
		$attrs = (array) ($this->catalog['searchable_attributes'] ?? []);
		return $attrs !== [] ? array_values($attrs) : ['name', 'sku', 'brand'];
	}

	/** @return list<string> */
	public function sortableAttributes(): array
	{
		$attrs = (array) ($this->catalog['sortable_attributes'] ?? []);
		return $attrs !== [] ? array_values($attrs) : ['price', 'name', 'rating_avg'];
	}

	/** @return list<string> */
	public function facetAttributes(): array
	{
		$attrs = (array) ($this->catalog['extra_facet_attributes'] ?? []);
		foreach ($this->filters() as $field) {
			$meili = trim((string) ($field['meili'] ?? ''));
			if ($meili !== '') {
				$attrs[] = $meili;
			}
			$source = trim((string) ($field['facet_source'] ?? ''));
			if ($source !== '') {
				$attrs[] = $source;
			}
		}
		$attrs = array_values(array_unique(array_filter($attrs)));
		sort($attrs);
		return $attrs;
	}

	/** @return list<string> */
	public function broadSearchFacets(): array
	{
		$facets = (array) ($this->catalog['broad_search_facets'] ?? []);
		return $facets !== [] ? array_values($facets) : $this->facetAttributes();
	}

	public function field(string $key): ?array
	{
		$filters = $this->filters();
		return isset($filters[$key]) && is_array($filters[$key]) ? $filters[$key] : null;
	}

	public function fieldByMeili(string $meili): ?array
	{
		foreach ($this->filters() as $field) {
			if (($field['meili'] ?? '') === $meili) {
				return $field;
			}
		}
		return null;
	}

	public function fieldBySsField(string $ssField): ?array
	{
		foreach ($this->filters() as $field) {
			if (($field['ss_field'] ?? $field['meili'] ?? '') === $ssField) {
				return $field;
			}
		}
		return null;
	}

	/**
	 * @return array<string, mixed>|string
	 */
	public function selectedRequestValue(Request $request, array $field)
	{
		foreach ((array) ($field['request'] ?? []) as $key) {
			$raw = $request->{$key} ?? $request->input($key);
			if (is_array($raw) && $raw !== []) {
				return $raw;
			}
			if (is_scalar($raw) && trim((string) $raw) !== '') {
				return (string) $raw;
			}
		}
		return '';
	}

	/** @return list<string> */
	public function selectedValues(Request $request, array $field): array
	{
		$raw = $this->selectedRequestValue($request, $field);
		if (is_array($raw)) {
			$values = [];
			foreach ($raw as $value) {
				if (is_scalar($value) && trim((string) $value) !== '') {
					$values[] = (string) $value;
				}
			}
			return array_values(array_unique($values));
		}
		if (!is_scalar($raw) || trim((string) $raw) === '') {
			return [];
		}
		return array_values(array_unique(explode('~', (string) $raw)));
	}

	/**
	 * @param array<int, mixed> $facetFilters
	 * @return array{facetFilters: array, numericFilters: array}
	 */
	public function applyRequestFilters(Request $request, array $facetFilters): array
	{
		$numericFilters = [];

		foreach ($this->filters() as $field) {
			if (($field['apply'] ?? true) === false) {
				continue;
			}
			$type = (string) ($field['type'] ?? 'facet');
			$meili = trim((string) ($field['meili'] ?? ''));
			if ($meili === '') {
				continue;
			}

			if ($type === 'range') {
				try {
					$minKey = (string) ($field['request_min'] ?? '');
					$maxKey = (string) ($field['request_max'] ?? '');
					$min = $minKey !== '' ? $request->{$minKey} : null;
					$max = $maxKey !== '' ? $request->{$maxKey} : null;
					if (is_numeric($min) && (float) $min > 0) {
						$numericFilters[] = $meili . ' >= ' . (float) $min;
					}
					if (is_numeric($max) && (float) $max > 0) {
						$numericFilters[] = $meili . ' <= ' . (float) $max;
					}
				} catch (\Throwable $e) {
					// skip invalid range
				}
				continue;
			}

			if ($type !== 'facet') {
				continue;
			}
			if (!empty($field['nlp_implicit'])) {
				continue;
			}

			try {
				$values = $this->selectedValues($request, $field);
				if ($values === []) {
					continue;
				}
				$normalized = [];
				foreach ($values as $value) {
					$value = $this->normalizeFacetValue((string) $value, $field);
					if ($value === '') {
						continue;
					}
					$normalized[] = $value;
				}
				$normalized = array_values(array_unique($normalized));
				if ($normalized === []) {
					continue;
				}
				if (($field['filter_op'] ?? '') === 'gte') {
					$numericFilters[] = $meili . ' >= ' . (float) min(array_map('floatval', $normalized));
					continue;
				}
				if (($field['filter_op'] ?? '') === 'star_range') {
					$rangeClause = $this->starRangeFilterClause($meili, $normalized);
					if ($rangeClause !== '') {
						$numericFilters[] = $rangeClause;
					}
					continue;
				}
				$ors = [];
				foreach ($normalized as $value) {
					$ors[] = $meili . ':' . $value;
				}
				if (count($ors) > 0) {
					$facetFilters[] = $ors;
				}
			} catch (\Throwable $e) {
				// skip invalid facet
			}
		}

		return ['facetFilters' => $facetFilters, 'numericFilters' => $numericFilters];
	}

	public function normalizeFacetValue(string $value, array $field): string
	{
		$value = trim($value);
		if ($value === '') {
			return '';
		}
		if (!empty($field['urldecode'])) {
			$value = urldecode($value);
		}
		foreach ((array) ($field['value_replace'] ?? []) as $from => $to) {
			$value = str_replace((string) $from, (string) $to, $value);
		}
		return trim($value);
	}

	/**
	 * Exclusive star buckets aligned to half-star display:
	 * 5 = avg >= 4.75; 4+ = 4–4.74; 3+ = 3–3.99.
	 * Star Rating UI is radio-style — only one bucket is applied.
	 *
	 * @param list<string> $normalized
	 */
	private function starRangeFilterClause(string $meili, array $normalized): string
	{
		if ($normalized === []) {
			return '';
		}
		// One bucket only (prefer last selected). "4+" / "4" → 4.
		$star = 0;
		foreach ($normalized as $raw) {
			$parsed = $this->parseStarBucketValue((string) $raw);
			if ($parsed !== 0) {
				$star = $parsed;
			}
		}
		if ($star === 5) {
			return '(' . $meili . ' >= 4.75)';
		}
		if ($star === 4) {
			return '(' . $meili . ' >= 4 AND ' . $meili . ' < 4.75)';
		}
		if ($star === 3) {
			return '(' . $meili . ' >= 3 AND ' . $meili . ' < 4)';
		}
		return '';
	}

	/** Normalize "4", "4+", "4.0" → 3|4|5 (0 = invalid). */
	private function parseStarBucketValue(string $raw): int
	{
		$raw = strtolower(trim(str_replace(['+', 'stars', 'star'], '', $raw)));
		if ($raw === '' || !is_numeric($raw)) {
			return 0;
		}
		$star = (int) round((float) $raw);
		return in_array($star, [3, 4, 5], true) ? $star : 0;
	}
}
