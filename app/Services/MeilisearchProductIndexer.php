<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Meili\Client;
use App\Support\SizeNormalizer;
use Throwable;

class MeilisearchProductIndexer
{
	public function __construct(
		private Client $client,
		private ProductFilterCatalogService $catalog
	) {
	}

	public function indexName(): string
	{
		return (string) config('global.CUSTOM_PRODUCT_INDEX', 'products_maxaroma');
	}

	/**
	 * @return array{indexed: int, index: string}
	 */
	public function rebuild(): array
	{
		$documents = $this->buildDocuments();
		$indexUid = $this->indexName();
		try {
			$create = $this->client->createIndex($indexUid, ['primaryKey' => 'uid']);
			$this->waitForTask($create);
		} catch (Throwable $e) {
			// index already exists
		}
		$index = $this->client->index($indexUid);

		$this->applyIndexSettings($index);

		// Do not pass primaryKey on addDocuments when the index already has one
		// (live index uses `id`; passing `uid` fails with index_primary_key_already_exists).
		foreach (array_chunk($documents, 200) as $batch) {
			$batch = array_map([self::class, 'utf8Deep'], $batch);
			$task = $index->addDocuments($batch);
			$this->waitForTask($task);
		}

		return ['indexed' => count($documents), 'index' => $indexUid];
	}

	/**
	 * Restore Meilisearch default relevance ranking without reindexing documents.
	 * Live dumps sometimes arrive with rankingRules = ["sort"] only, which ignores query relevance.
	 *
	 * @return array{index: string, rankingRules: list<string>}
	 */
	public function restoreDefaultRankingRules(): array
	{
		$rules = ['words', 'typo', 'proximity', 'attribute', 'sort', 'exactness'];
		$index = $this->client->index($this->indexName());
		$task = $index->updateRankingRules($rules);
		$this->waitForTask($task);

		return [
			'index' => $this->indexName(),
			'rankingRules' => $index->getRankingRules(),
		];
	}

	/**
	 * Apply searchable/filterable/sortable/pagination settings from the product catalog
	 * without rebuilding documents.
	 *
	 * @return array{index: string, searchableAttributes: list<string>, pagination: array<string, mixed>}
	 */
	public function syncIndexSettings(): array
	{
		// fragrance_family CONTAINS "woody" needs this experimental flag on Meili.
		if (method_exists($this->client, 'enableContainsFilterFeature')) {
			$this->client->enableContainsFilterFeature();
		}

		$index = $this->client->index($this->indexName());
		$this->applyIndexSettings($index);
		$settings = $index->getSettings();

		return [
			'index' => $this->indexName(),
			'searchableAttributes' => is_array($settings['searchableAttributes'] ?? null)
				? $settings['searchableAttributes']
				: $this->catalog->searchableAttributes(),
			'pagination' => is_array($settings['pagination'] ?? null)
				? $settings['pagination']
				: ['maxTotalHits' => 20000],
		];
	}

	private function applyIndexSettings($index): void
	{
		$settings = [
			'searchableAttributes' => $this->catalog->searchableAttributes(),
			'filterableAttributes' => $this->catalog->facetAttributes(),
			'sortableAttributes' => $this->catalog->sortableAttributes(),
			'rankingRules' => ['words', 'typo', 'proximity', 'attribute', 'sort', 'exactness'],
			'pagination' => ['maxTotalHits' => 20000],
			'faceting' => [
				// Brand catalogs exceed Meili's default 100 and our old 1000 cap
				// (Roja Parfums was missing → ROAJ typos failed while AKRO worked).
				'maxValuesPerFacet' => 10000,
			],
			'typoTolerance' => [
				'enabled' => true,
				'minWordSizeForTypos' => [
					// 4-letter houses (Roja/roaj, Akro/akor) need oneTypo below Meili's default 5–6.
					'oneTypo' => 4,
					'twoTypos' => 8,
				],
			],
		];
		$task = $index->updateSettings($settings);
		$this->waitForTask($task);
	}

	private function waitForTask($task): void
	{
		$uid = null;
		if (is_object($task)) {
			if (method_exists($task, 'getTaskUid')) {
				$uid = $task->getTaskUid();
			} elseif (method_exists($task, 'getUid')) {
				$uid = $task->getUid();
			}
		} elseif (is_array($task)) {
			$uid = $task['taskUid'] ?? $task['uid'] ?? null;
		}
		if ($uid && method_exists($this->client, 'waitForTask')) {
			$this->client->waitForTask($uid, 120000, 100);
		}
	}

	/** @return list<array<string, mixed>> */
	private function buildDocuments(): array
	{
		$dealSkus = $this->activeDealSkus();
		$reviews = $this->reviewStats();
		$categoryNames = $this->categoryNameMap();
		$documents = [];
		$variationStats = [];
		$seen = [];

		DB::table('pu_products as p')
			->leftJoin('pu_products_one as po', 'po.products_id', '=', 'p.products_id')
			->leftJoin('pu_manufacture as m', 'm.imanufactureid', '=', 'p.imanufactureid')
			->leftJoin('pu_brand as b', 'b.brand_id', '=', 'p.brand_id')
			->leftJoin('pu_products_attribute as pa', 'pa.products_id', '=', 'p.products_id')
			->where('p.status', '1')
			->select([
				'p.products_id', 'p.sku', 'p.image', 'p.product_name', 'p.product_description',
				'p.short_description', 'p.vtype', 'p.gender', 'p.retail_price', 'p.our_price',
				'p.sale_price', 'p.current_stock', 'p.celebrity', 'p.new_arrival', 'p.featured',
				'p.clearance', 'p.top_seller', 'p.product_type', 'p.refine_feature',
				'p.fragrance_family', 'p.formulation', 'p.coverage', 'p.finish', 'p.skin_type',
				'p.fragrance_occasion', 'p.fragrance_personality', 'p.fragrance_seasons', 'p.size',
				'p.variation_id', 'p.maxtwodaydelivery',
				'p.cosmo_sku', 'p.cosmo_current_stock', 'p.cosmo_our_price', 'p.cosmo_retail_price',
				'p.pca_sku', 'p.pca_current_stock', 'p.pca_our_price', 'p.pca_retail_price',
				'p.nandansons_sku', 'p.nandansons_current_stock', 'p.nandansons_our_price', 'p.nandansons_retail_price',
				'po.meta_keyword', 'po.extra_images', 'm.vmanufacture', 'b.brand_name',
				'pa.topnote', 'pa.middlenote', 'pa.basenote',
			])
			->orderBy('p.products_id')
			->chunk(200, function ($rows) use (&$documents, &$variationStats, &$seen, $dealSkus, $reviews, $categoryNames) {
				$ids = $rows->pluck('products_id')->all();
				$productCategories = $this->categoriesForProducts($ids, $categoryNames);
				foreach ($rows as $row) {
					$uid = (string) $row->products_id;
					if (isset($seen[$uid])) {
						continue;
					}
					$seen[$uid] = true;
					$doc = $this->documentFromRow($row, $productCategories[$row->products_id] ?? [], $dealSkus, $reviews);
					$documents[] = $doc;
					$vid = trim((string) ($row->variation_id ?? ''));
					if ($vid === '') {
						continue;
					}
					if (!isset($variationStats[$vid])) {
						$variationStats[$vid] = ['min' => $doc['price'], 'max' => $doc['price'], 'count' => 0];
					}
					$variationStats[$vid]['min'] = min($variationStats[$vid]['min'], $doc['price']);
					$variationStats[$vid]['max'] = max($variationStats[$vid]['max'], $doc['price']);
					$variationStats[$vid]['count']++;
				}
			});

		foreach ($documents as &$doc) {
			$vid = (string) ($doc['variation_id'] ?? '');
			if ($vid !== '' && isset($variationStats[$vid])) {
				$doc['same_variation_min_price'] = (float) $variationStats[$vid]['min'];
				$doc['same_variation_max_price'] = (float) $variationStats[$vid]['max'];
				$doc['same_variation_product_count'] = (int) $variationStats[$vid]['count'];
			} else {
				$doc['same_variation_min_price'] = (float) $doc['price'];
				$doc['same_variation_max_price'] = (float) $doc['price'];
				$doc['same_variation_product_count'] = 1;
			}
			unset($doc['variation_id']);
		}
		unset($doc);

		return $documents;
	}

	/**
	 * @param array<int, array{id:int, name:string}> $categories
	 * @param array<string, bool> $dealSkus
	 * @param array<int, array{rating:int, rating_avg:float}> $reviews
	 * @return array<string, mixed>
	 */
	private function documentFromRow(object $row, array $categories, array $dealSkus, array $reviews): array
	{
		$pricing = $this->resolvePriceAndStock($row);
		$primaryCategory = $categories[0] ?? ['id' => 0, 'name' => ''];
		$categoryNames = array_values(array_unique(array_filter(array_column($categories, 'name'))));
		$imageUrl = $this->thumbUrl((string) $row->image);
		$extra = $this->firstExtraImageUrl((string) ($row->extra_images ?? ''));
		$review = $reviews[(int) $row->products_id] ?? ['rating' => 0, 'rating_avg' => 0.0];
		$sku = (string) $row->sku;

		return [
			'uid' => (string) $row->products_id,
			'id' => (int) $row->products_id,
			'sku' => $sku,
			'name' => $this->cleanText((string) $row->product_name),
			'description' => $this->cleanText((string) $row->product_description),
			'searchKeywords' => $this->cleanText((string) ($row->meta_keyword ?? $row->short_description ?? $row->product_name)),
			'search_keywords' => $this->cleanText((string) ($row->meta_keyword ?? $row->short_description ?? $row->product_name)),
			'brand' => $this->cleanText((string) (($row->vmanufacture ?? '') !== '' ? $row->vmanufacture : ($row->brand_name ?? ''))),
			'category' => $categoryNames,
			'category_id' => (int) $primaryCategory['id'],
			'product_url' => $this->productUrl((int) $row->products_id, (string) $row->product_name, (int) $primaryCategory['id']),
			'imageUrl' => $imageUrl,
			'thumbnail_url' => $imageUrl,
			'extra_image_url' => $extra,
			'price' => $pricing['price'],
			'msrp' => $pricing['msrp'],
			'sale_price' => (float) $row->sale_price,
			'website_stock' => $pricing['stock'],
			'inventory_count' => $pricing['stock'],
			'product_type' => (string) ($row->product_type ?: 'both'),
			'gender' => $this->resolveIndexedGender(
				(string) ($row->gender ?? ''),
				(string) ($row->product_name ?? '')
			),
			'size' => SizeNormalizer::indexValues(
				(string) ($row->size ?? ''),
				(string) ($row->short_description ?? '')
			),
			'badges' => $this->badgesFromRow($row),
			'formulation' => $this->splitValues((string) $row->formulation),
			'by_feature' => $this->splitValues((string) $row->refine_feature),
			'fragrance_family' => $this->splitValues((string) $row->fragrance_family),
			'type' => $this->splitValues((string) $row->vtype),
			'coverage' => $this->splitValues((string) $row->coverage),
			'finish' => $this->splitValues((string) $row->finish),
			'skin_type' => $this->splitValues((string) $row->skin_type),
			'seasons' => $this->splitValues((string) $row->fragrance_seasons),
			'occasion' => $this->splitValues((string) $row->fragrance_occasion),
			'personality' => $this->splitValues((string) $row->fragrance_personality),
			'top_note' => $this->splitValues((string) ($row->topnote ?? '')),
			'middle_note' => $this->splitValues((string) ($row->middlenote ?? '')),
			'base_note' => $this->splitValues((string) ($row->basenote ?? '')),
			// Unified unique notes for one PLP facet (SearchSpring could not merge these cleanly).
			'fragrance_notes' => $this->uniqueMergedNotes(
				(string) ($row->topnote ?? ''),
				(string) ($row->middlenote ?? ''),
				(string) ($row->basenote ?? '')
			),
			'rating' => (int) $review['rating'],
			'rating_avg' => (float) $review['rating_avg'],
			'max_two_day_delivery' => ((string) $row->maxtwodaydelivery === 'Yes') ? 'Yes' : 'No',
			'is_deal_of_week' => isset($dealSkus[$sku]) ? 'Yes' : 'No',
			'variation_id' => trim((string) ($row->variation_id ?? '')),
		];
	}

	/** @return array{price: float, msrp: float, stock: int} */
	private function resolvePriceAndStock(object $row): array
	{
		if ((int) $row->current_stock > 0) {
			$price = ($row->sale_price > 0 && $row->sale_price < $row->our_price) ? $row->sale_price : $row->our_price;
			return ['price' => (float) $price, 'msrp' => (float) $row->retail_price, 'stock' => (int) $row->current_stock];
		}
		if ($row->cosmo_sku !== '' && (int) $row->cosmo_current_stock > 0) {
			return ['price' => (float) $row->cosmo_our_price, 'msrp' => (float) $row->cosmo_retail_price, 'stock' => (int) $row->cosmo_current_stock];
		}
		if ($row->pca_sku !== '' && (int) $row->pca_current_stock > 0) {
			return ['price' => (float) $row->pca_our_price, 'msrp' => (float) $row->pca_retail_price, 'stock' => (int) $row->pca_current_stock];
		}
		if ($row->nandansons_sku !== '' && (int) $row->nandansons_current_stock > 0) {
			return ['price' => (float) $row->nandansons_our_price, 'msrp' => (float) $row->nandansons_retail_price, 'stock' => (int) $row->nandansons_current_stock];
		}
		$price = ($row->sale_price > 0 && $row->sale_price < $row->our_price) ? $row->sale_price : $row->our_price;
		return ['price' => (float) $price, 'msrp' => (float) $row->retail_price, 'stock' => 0];
	}

	/** @return list<string> */
	private function badgesFromRow(object $row): array
	{
		$badges = [];
		if (($row->top_seller ?? '') === 'Yes') {
			$badges[] = 'Top Seller';
		}
		if (($row->new_arrival ?? '') === 'Yes') {
			$badges[] = 'New Arrivals';
		}
		if (($row->featured ?? '') === 'Yes') {
			$badges[] = 'Featured';
		}
		if (($row->clearance ?? '') === 'Yes') {
			$badges[] = 'Clearance';
		}
		if (($row->celebrity ?? '') === 'Yes') {
			$badges[] = 'Celebrity Perfume';
		}
		return $badges;
	}

	/** @return list<string> */
	private function splitValues(string $raw): array
	{
		$raw = html_entity_decode(strip_tags(self::utf8String($raw)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$raw = self::utf8String($raw);
		// NBSP (UTF-8 C2 A0) often arrives as Latin-1 mojibake "Â" + space / &Acirc;&nbsp;.
		$raw = str_replace(["\xc2\xa0", "\xA0", '&nbsp;', '&NBSP;'], ' ', $raw);
		$raw = trim($raw);
		if ($raw === '') {
			return [];
		}
		$parts = preg_split('/[,|]+/', $raw) ?: [];
		$out = [];
		foreach ($parts as $part) {
			$part = trim(self::utf8String($part));
			// Strip leading Â left from a mis-decoded NBSP before the note name.
			$part = preg_replace('/^\x{00C2}+/u', '', $part) ?? $part;
			$part = trim($part);
			if ($part !== '') {
				$out[] = $part;
			}
		}
		return array_values(array_unique($out));
	}

	/**
	 * Unique note labels across top / middle / base (case-insensitive dedupe, keep first spelling).
	 *
	 * @return list<string>
	 */
	private function uniqueMergedNotes(string $top, string $middle, string $base): array
	{
		$seen = [];
		$out = [];
		foreach (array_merge(
			$this->splitValues($top),
			$this->splitValues($middle),
			$this->splitValues($base)
		) as $note) {
			$key = mb_strtolower(trim($note));
			if ($key === '' || isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;
			$out[] = $note;
		}
		return $out;
	}

	/**
	 * Prefer strong title gender cues when they conflict with DB gender
	 * (e.g. "… Perfume for Women" wrongly stored as M).
	 */
	private function resolveIndexedGender(string $dbGender, string $productName): string
	{
		$code = strtoupper(trim($dbGender));
		if (!in_array($code, ['M', 'W', 'U', 'K', ''], true)) {
			$code = '';
		}
		$name = strtolower($productName);
		$signalsWomen = (bool) preg_match(
			'/\b(?:for\s+women|pour\s+femme|women\'?s|for\s+her)\b/u',
			$name
		);
		$signalsMen = (bool) preg_match(
			'/\b(?:for\s+men|pour\s+homme|men\'?s|for\s+him)\b/u',
			$name
		);
		if ($signalsWomen && !$signalsMen) {
			return 'W';
		}
		if ($signalsMen && !$signalsWomen) {
			return 'M';
		}
		return $code;
	}

	private function cleanText(string $value): string
	{
		$value = html_entity_decode(strip_tags(self::utf8String($value)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		return trim(str_replace('"', '', self::utf8String($value)));
	}

	/**
	 * Recursively coerce values to valid UTF-8 so Meilisearch json_encode does not fail.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public static function utf8Deep($value)
	{
		if (is_array($value)) {
			$out = [];
			foreach ($value as $k => $v) {
				$out[is_string($k) ? self::utf8String($k) : $k] = self::utf8Deep($v);
			}
			return $out;
		}
		if (is_string($value)) {
			return self::utf8String($value);
		}
		return $value;
	}

	public static function utf8String(string $value): string
	{
		if ($value === '') {
			return '';
		}
		if (strncmp($value, "\xEF\xBB\xBF", 3) === 0) {
			$value = substr($value, 3);
		}
		if (!mb_check_encoding($value, 'UTF-8')) {
			$from = mb_detect_encoding($value, ['UTF-8', 'Windows-1252', 'ISO-8859-1'], true) ?: 'Windows-1252';
			$converted = @mb_convert_encoding($value, 'UTF-8', $from);
			$value = is_string($converted) ? $converted : (string) @mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
		}
		if (function_exists('iconv')) {
			$ignored = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
			if (is_string($ignored)) {
				$value = $ignored;
			}
		}
		$json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
		if ($json !== false) {
			$decoded = json_decode($json);
			if (is_string($decoded)) {
				return $decoded;
			}
		}
		return preg_replace('/[^\P{C}\n\t]/u', '', $value) ?? '';
	}

	private function thumbUrl(string $image): string
	{
		$image = trim($image);
		$base = rtrim($this->siteBaseUrl(), '/');
		$path = config('global.PRD_THUMB_IMG_PATH') . $image;
		if ($image !== '' && is_file($path)) {
			return $base . '/productimages/thumb/' . rawurlencode($image);
		}
		return $base . '/images/noimage-th.jpg';
	}

	private function firstExtraImageUrl(string $extraImages): string
	{
		$parts = array_values(array_filter(explode('#', $extraImages)));
		if ($parts === []) {
			return '';
		}
		return $this->thumbUrl($parts[0]);
	}

	private function productUrl(int $productId, string $name, int $categoryId): string
	{
		try {
			$url = SetProductURL($productId, $name, $categoryId);
		} catch (Throwable $e) {
			$slug = function_exists('remove_special_chars') ? remove_special_chars($name) : $name;
			$url = $this->siteBaseUrl() . '/' . $slug . '/pid/' . $productId . '/' . $categoryId;
		}
		if (!preg_match('#^https?://[A-Za-z0-9]#', $url)) {
			$relative = preg_replace('#^:?/+#', '', $url);
			$relative = preg_replace('#^maxaroma/#i', '', (string) $relative);
			$url = $this->siteBaseUrl() . '/' . ltrim((string) $relative, '/');
		}
		return $url;
	}

	private function siteBaseUrl(): string
	{
		$site = rtrim((string) config('global.SITE_URL'), '/');
		if (preg_match('#^https?://[A-Za-z0-9]#', $site)) {
			return $site;
		}
		$app = rtrim((string) config('app.url'), '/');
		$base = trim((string) config('global.SITE_BASE', 'maxaroma'), '/');
		if (preg_match('#^https?://[A-Za-z0-9]#', $app)) {
			if ($base !== '' && !str_ends_with($app, $base)) {
				return $app . '/' . $base;
			}
			return $app;
		}
		return 'http://localhost/' . ($base !== '' ? $base : 'maxaroma');
	}

	/** @return array<string, bool> */
	private function activeDealSkus(): array
	{
		try {
			$now = date('Y-m-d H:i');
			$rows = DB::table('pu_dealofweek')
				->where('status', '1')
				->where('start_date', '<=', $now)
				->where('end_date', '>=', $now)
				->pluck('product_sku');
			$skus = [];
			foreach ($rows as $sku) {
				$skus[(string) $sku] = true;
			}
			return $skus;
		} catch (\Throwable $e) {
			return [];
		}
	}

	/** @return array<int, array{rating:int, rating_avg:float}> */
	private function reviewStats(): array
	{
		try {
			$rows = DB::table('pu_products_review')
				->selectRaw('products_id, COUNT(*) as rating, AVG(star_rate) as rating_avg')
				->where('approved', 'Yes')
				->groupBy('products_id')
				->get();
			$stats = [];
			foreach ($rows as $row) {
				$stats[(int) $row->products_id] = [
					'rating' => (int) $row->rating,
					'rating_avg' => round((float) $row->rating_avg, 2),
				];
			}
			return $stats;
		} catch (\Throwable $e) {
			return [];
		}
	}

	/** @return array<int, string> */
	private function categoryNameMap(): array
	{
		$map = [];
		foreach (DB::table('pu_category')->select('category_id', 'parent_id', 'category_name')->where('status', '1')->get() as $cat) {
			$map[(int) $cat->category_id] = $cat;
		}
		return $map;
	}

	/**
	 * @param list<int> $productIds
	 * @param array<int, object> $categoryNames
	 * @return array<int, list<array{id:int, name:string}>>
	 */
	private function categoriesForProducts(array $productIds, array $categoryNames): array
	{
		if ($productIds === []) {
			return [];
		}
		$rows = DB::table('pu_products_category as pc')
			->join('pu_category as c', 'c.category_id', '=', 'pc.category_id')
			->whereIn('pc.products_id', $productIds)
			->where('c.status', '1')
			->select('pc.products_id', 'pc.category_id', 'c.category_name', 'c.parent_id')
			->get();
		$out = [];
		foreach ($rows as $row) {
			$parentName = '';
			$parentId = (int) $row->parent_id;
			if ($parentId > 0 && isset($categoryNames[$parentId])) {
				$parentName = (string) $categoryNames[$parentId]->category_name;
			}
			$child = (string) $row->category_name;
			$label = $parentName !== '' ? ($parentName . ' : ' . $child) : $child;
			$out[(int) $row->products_id][] = [
				'id' => (int) $row->category_id,
				'name' => $label,
			];
		}
		return $out;
	}
}
