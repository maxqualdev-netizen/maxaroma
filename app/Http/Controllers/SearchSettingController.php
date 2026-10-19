<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Meili\Client;
use App\Services\MeilisearchProductIndexer;
use App\Support\SizeNormalizer;

class SearchSettingController extends Controller
{
    protected $client;
    protected $searchUrl;
    protected $masterKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->searchUrl = config('global.CUSTOM_SEARCH_URL');
        $this->masterKey = config('global.CUSTOM_SEARCH_KEY');
    }

    /**
     * Get or create client
     */
    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->searchUrl, $this->masterKey);
        }
        return $this->client;
    }

    /**
     * Display the index management interface
     * GET /search-settings
     */
    public function index()
    {
        return view('searchsettings.index');
    }

    /**
     * Get index information including primary key
     * GET /indexes/{indexName}
     */
    public function getIndexInfo(Request $request, $indexName = 'products')
    {
        try {
            $client = $this->getClient();
            $index = $client->index($indexName);
            
            // Get index information
            $indexInfo = $index->fetchRawInfo();
            
            // Get index settings (filterable, searchable, sortable attributes, pagination)
            $settings = $index->getSettings();

            // Document count: prefer /stats, fallback to empty search totalHits
            $stats = [];
            $numberOfDocuments = 0;
            try {
                $stats = $index->stats();
                $numberOfDocuments = (int) ($stats['numberOfDocuments'] ?? $stats['number_of_documents'] ?? 0);
            } catch (\Throwable $e) {
                Log::warning('Index stats failed, falling back to search count: ' . $e->getMessage());
            }
            if ($numberOfDocuments <= 0) {
                try {
                    $probe = $index->search('', ['limit' => 0]);
                    $numberOfDocuments = (int) $probe->getTotalHits();
                    $stats = array_merge($stats, [
                        'numberOfDocuments' => $numberOfDocuments,
                        'source' => 'search_fallback',
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Index search count fallback failed: ' . $e->getMessage());
                }
            }
            
            return response()->json([
                'success' => true,
                'index' => $indexName,
                'numberOfDocuments' => $numberOfDocuments,
                'info' => [
                    'uid' => $indexInfo['uid'] ?? null,
                    'primaryKey' => $indexInfo['primaryKey'] ?? null,
                    'createdAt' => $indexInfo['createdAt'] ?? null,
                    'updatedAt' => $indexInfo['updatedAt'] ?? null,
                    'numberOfDocuments' => $numberOfDocuments,
                ],
                'stats' => $stats,
                'settings' => [
                    'filterableAttributes' => $settings['filterableAttributes'] ?? [],
                    'searchableAttributes' => $settings['searchableAttributes'] ?? [],
                    'sortableAttributes' => $settings['sortableAttributes'] ?? [],
                    'pagination' => $settings['pagination'] ?? null,
                    'displayedAttributes' => $settings['displayedAttributes'] ?? null,
                    'rankingRules' => $settings['rankingRules'] ?? null,
                    'stopWords' => $settings['stopWords'] ?? null,
                    'synonyms' => $settings['synonyms'] ?? null,
                    'distinctAttribute' => $settings['distinctAttribute'] ?? null,
                ],
                'fullInfo' => $indexInfo,
                'fullSettings' => $settings
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to get index info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get index info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an index
     * DELETE /indexes/{indexName}
     */
    public function deleteIndex(Request $request, $indexName = 'products')
    {	
        try {
            $client = $this->getClient();
            $client->index($indexName)->delete();

            return response()->json([
                'success' => true,
                'message' => "Index '{$indexName}' deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete index: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create an index with primary key
     * POST /indexes
     * 
     * Request body:
     * {
     *   "uid": "products",
     *   "primaryKey": "id"  // optional, defaults to "id"
     * }
     */
    public function createIndex(Request $request)
    {
        try {
            $request->validate([
                'uid' => 'required|string',
                'primaryKey' => 'nullable|string'
            ]);

            $uid = $request->input('uid');
            // Use provided primaryKey or default to 'id'
            $primaryKey = $request->input('primaryKey', 'id');

            $client = $this->getClient();
            $client->createIndex($uid, ['primaryKey' => $primaryKey]);
            
            return response()->json([
                'success' => true,
                'message' => "Index '{$uid}' created successfully with primary key '{$primaryKey}'",
                'index' => [
                    'uid' => $uid,
                    'primaryKey' => $primaryKey
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create index: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update pagination settings
     * PATCH /indexes/{indexName}/settings/pagination
     */
    public function updatePagination(Request $request, $indexName = 'products')
    {
        try {
            $request->validate([
                'maxTotalHits' => 'required|integer|min:1'
            ]);

            $maxTotalHits = $request->input('maxTotalHits');
            $client = $this->getClient();
            $index = $client->index($indexName);
            
            // Use updateSettings with pagination key
            $index->updateSettings(['pagination' => ['maxTotalHits' => $maxTotalHits]]);
			
			$settings = [
				'typoTolerance' => [
					'enabled' => true,  // enable typo tolerance
					'minWordSizeForTypos' => [
						'oneTypo' => 6,  // words shorter than 6 letters → no typos
						'twoTypos' => 8   // words shorter than 8 letters → max 1 typo
					]
				]
			];

			$index->updateSettings($settings);
            
            return response()->json([
                'success' => true,
                'message' => "Pagination settings updated for index '{$indexName}'",
                'maxTotalHits' => $maxTotalHits
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update pagination: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update pagination: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update filterable attributes
     * PUT /indexes/{indexName}/settings/filterable-attributes
     */
    public function updateFilterableAttributes(Request $request, $indexName = 'products')
    {
        try {
            // Accept array directly from request body, or from 'attributes' key
            $attributes = $request->input('attributes', $request->all());
            
            // Validate that we have an array
            if (!is_array($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format. Expected an array of attributes.'
                ], 422);
            }
            
            // Ensure it's a simple array (not associative)
            if (!array_is_list($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attributes must be a simple array (list), not an associative array.'
                ], 422);
            }

            $client = $this->getClient();
            $index = $client->index($indexName);
            
            // Use updateSettings with filterableAttributes key
            $index->updateSettings(['filterableAttributes' => $attributes]);
            
            return response()->json([
                'success' => true,
                'message' => "Filterable attributes updated for index '{$indexName}'",
                'attributes' => $attributes
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update filterable attributes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update filterable attributes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update searchable attributes
     * PUT /indexes/{indexName}/settings/searchable-attributes
     */
    public function updateSearchableAttributes(Request $request, $indexName = 'products')
    {
        try {
            // Accept array directly from request body, or from 'attributes' key
            $attributes = $request->input('attributes', $request->all());
            
            // Validate that we have an array
            if (!is_array($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format. Expected an array of attributes.'
                ], 422);
            }
            
            // Ensure it's a simple array (not associative)
            if (!array_is_list($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attributes must be a simple array (list), not an associative array.'
                ], 422);
            }

            $client = $this->getClient();
            $index = $client->index($indexName);
            
            // Use updateSettings with searchableAttributes key
            $index->updateSettings(['searchableAttributes' => $attributes]);
            
            return response()->json([
                'success' => true,
                'message' => "Searchable attributes updated for index '{$indexName}'",
                'attributes' => $attributes
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update searchable attributes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update searchable attributes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sortable attributes
     * PUT /indexes/{indexName}/settings/sortable-attributes
     */
    public function updateSortableAttributes(Request $request, $indexName = 'products')
    {
        try {
            // Accept array directly from request body, or from 'attributes' key
            $attributes = $request->input('attributes', $request->all());
            
            // Validate that we have an array
            if (!is_array($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format. Expected an array of attributes.'
                ], 422);
            }
            
            // Ensure it's a simple array (not associative)
            if (!array_is_list($attributes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attributes must be a simple array (list), not an associative array.'
                ], 422);
            }

            $client = $this->getClient();
            $index = $client->index($indexName);
            
            // Use updateSettings with sortableAttributes key
            $index->updateSettings(['sortableAttributes' => $attributes]);
            
            return response()->json([
                'success' => true,
                'message' => "Sortable attributes updated for index '{$indexName}'",
                'attributes' => $attributes
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update sortable attributes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sortable attributes: ' . $e->getMessage()
            ], 500);
        }
    }
	
	/**
     * Update custom ranking rules (customRanking)
     * PUT /indexes/{indexName}/settings/custom-ranking
     *
     * Example JSON body:
     * [
     *   "desc(totalGroupRevenue)",
     *   "asc(displayRank)",
     *   "desc(qualityTier)",
     *   "desc(diamondQuality)",
     *   "desc(id)",
     *   "desc(totalSold)",
     *   "desc(ratingCount)",
     *   "desc(inStock)"
     * ]
     */
    public function updateCustomRanking(Request $request, $indexName = 'products')
    {
        try {
            // Accept array directly from request body, or from 'rules' key
            $rules = $request->input('rules', $request->all());

            if (!is_array($rules)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format. Expected an array of custom ranking rules.'
                ], 422);
            }

            if (!array_is_list($rules)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Custom ranking rules must be a simple array (list), not an associative array.'
                ], 422);
            }

            $client = $this->getClient();
            $index  = $client->index($indexName);
			
            $index->updateSettings(['rankingRules' => $rules]);

            return response()->json([
                'success' => true,
                'message' => "Custom ranking rules updated for index '{$indexName}'",
                'rules'   => $rules,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update custom ranking rules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update custom ranking rules: ' . $e->getMessage(),
            ], 500);
        }
    }
	

    /**
     * Setup complete index configuration (all settings at once)
     * This is a convenience method to set up the entire index
     */
    public function setupIndex(Request $request, $indexName = 'products')
    {
        try {
            $client = $this->getClient();
            
            // Step 1: Delete existing index if it exists
            try {
                $index = $client->index($indexName);
                $index->delete();
                Log::info("Deleted existing index: {$indexName}");
            } catch (\Exception $e) {
                Log::info("Index '{$indexName}' does not exist or already deleted");
            }

            // Step 2: Create index with primary key
            $client->createIndex($indexName, ['primaryKey' => 'id']);
            Log::info("Created index: {$indexName} with primary key: id");

            // Step 3: Update pagination
            $index = $client->index($indexName);
            $index->updateSettings(['pagination' => ['maxTotalHits' => 5000]]);
            Log::info("Updated pagination settings");

            // Step 4: Update filterable attributes
            $filterableAttributes = [
                'plpCode', 'productName', 'objectID', 'productFullName', 'category',
                'price', 'categoryIds', 'metalType', 'carat', 'color', 'stone',
                'shape', 'gender', 'maincat', 'subcat', 'sideStone', 'stoneFilter',
                'productStyle', 'metalTypeFilter', 'searchKeyword', 'department'
            ];
            $index->updateSettings(['filterableAttributes' => $filterableAttributes]);
            Log::info("Updated filterable attributes");

            // Step 5: Update searchable attributes
            $searchableAttributes = [
                'caratFraction', 'metalType', 'shape', 'productName', 'carat',
                'category.lvl0', 'color', 'upcNumber', 'objectID', 'productFullName',
                'shortName', 'searchKeyword'
            ];
            $index->updateSettings(['searchableAttributes' => $searchableAttributes]);
            Log::info("Updated searchable attributes");

            // Step 6: Update sortable attributes
            $sortableAttributes = [
                'totalGroupRevenue', 'displayRank', 'qualityTier', 'diamondQuality',
                'id', 'totalSold', 'ratingCount', 'inStock'
            ];
            $index->updateSettings(['sortableAttributes' => $sortableAttributes]);
            Log::info("Updated sortable attributes");

            return response()->json([
                'success' => true,
                'message' => "Index '{$indexName}' setup completed successfully",
                'settings' => [
                    'primaryKey' => 'id',
                    'pagination' => ['maxTotalHits' => 5000],
                    'filterableAttributes' => $filterableAttributes,
                    'searchableAttributes' => $searchableAttributes,
                    'sortableAttributes' => $sortableAttributes
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to setup index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to setup index: ' . $e->getMessage()
            ], 500);
        }
    }
	
	/**
     * Run the index-products-csv command via URL
     * GET /api/index-products-csv
     */
	public function indexProductsFromCsv()
    {	
        try {	
            // Increase execution time limit for large CSV files
            set_time_limit(0);
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');
            
            $csvPath = 'csv_download/product_feed.csv';
            
            if (!file_exists($csvPath)) {
                return response()->json([
                    'success' => false,
                    'message' => "CSV file not found at: {$csvPath}"
                ], 404);
            }

            // Ensure Meili filterable attrs include fragrance_notes (and other catalog facets).
            // Without this, documents can have the field but PLP never gets facet values → no filter section.
            $synced = app(MeilisearchProductIndexer::class)->syncIndexSettings();
            
            $client = $this->getClient();
            $index = $client->index('products_maxaroma');
            
            // CSV feed often omits short_description; PDP Size comes from that DB field.
            $pdpShortById = [];
            try {
                $pdpShortById = \Illuminate\Support\Facades\DB::table('pu_products')
                    ->pluck('short_description', 'products_id')
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('Size PDP short_description map unavailable: ' . $e->getMessage());
            }

            $handle = fopen($csvPath, 'r');
            if (!$handle) {
                return response()->json([
                    'success' => false,
                    'message' => "Could not open CSV file: {$csvPath}"
                ], 500);
            }
            
			$delimiter = ",";
			$enclosure = '"';
			$escape = "\\";
			
            // Read header row
            $headers = fgetcsv($handle, 0, $delimiter, $enclosure, $escape);
            if (!$headers) {
                fclose($handle);
                return response()->json([
                    'success' => false,
                    'message' => "Could not read CSV headers"
                ], 500);
            }
            
            // Trim headers (strip UTF-8 BOM on first column)
            $headers = array_map('trim', $headers);
            if (isset($headers[0])) {
                $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
            }
            
            $batchSize = 500;
            $batch = [];
            $count = 0;
            $output = [];
            
		
            // Read data rows
			$headerCount = count($headers);
			while (($row = fgetcsv($handle, 0, $delimiter, $enclosure, $escape)) !== false) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // CSV rows with unescaped commas (common in note fields) can have
                // more/fewer columns than the header — pad or truncate so combine works on PHP 8+.
                $rowCount = count($row);
                if ($rowCount < $headerCount) {
                    $row = array_pad($row, $headerCount, '');
                } elseif ($rowCount > $headerCount) {
                    $row = array_slice($row, 0, $headerCount);
                }

                $record = array_combine($headers, $row);
                if ($record === false) {
                    continue;
                }
                
				$clean = [];
				$sizeRaw = '';
				$shortDescRaw = '';
                foreach ($record as $key => $value) {
                    $key = trim($key);
                    $value = trim((string) $value);
                    
                    switch ($key) {
                        case 'ga_item_revenue':
						case 'ga_items_per_purchase':
						case 'ga_product_revenue_per_purchase':
						case 'rating_avg':
						case 'price':
						case 'retail_price':
						case 'sale_price':
						case 'same_variation_max_price':
						case 'same_variation_min_price':
                            $clean[$key] = (float) $value;
                            break;
						case 'category_id':	
						case 'id':
						case 'ga_item_quantity':
						case 'ga_unique_purchases':
						case 'inventory_count':
						case 'rating_count':
						case 'same_variation_product_count':
						case 'website_stock':
                            $clean[$key] = $value === '' ? 0 : (int) $value;
                            break;		
                        case 'date_created':
                            if ($value === '') {
                                $clean[$key] = 0;
                            } elseif (preg_match('/^\d+$/', $value)) {
                                $clean[$key] = (int) $value;
                            } else {
                                $ts = strtotime($value);
                                $clean[$key] = ($ts !== false) ? $ts : 0;
                            }
                            break;
						case 'top_note':
						case 'middle_note':
						case 'base_note':
							// Match MeilisearchProductIndexer::splitValues — decode entities
							// (&Acirc;&nbsp; → clean note) instead of leaving &Acirc; in the index.
							$value = html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
							$value = str_replace(["\xc2\xa0", "\xA0", '&nbsp;', '&NBSP;'], ' ', $value);
							$clean[$key] = array_values(array_filter(
								array_map(static function ($item) {
									$item = trim((string) $item);
									$item = preg_replace('/^\x{00C2}+/u', '', $item) ?? $item;
									return trim($item);
								}, preg_split('/[,|]+/', $value) ?: []),
								static fn ($item) => $item !== ''
							));
							break;

						case 'size':
							$sizeRaw = str_replace('&nbsp;', '', $value);
							break;

						case 'short_description':
							$shortDescRaw = str_replace('&nbsp;', '', $value);
							$clean[$key] = MeilisearchProductIndexer::utf8String($value);
							break;

						case 'badges':
							// Remove HTML non-breaking space
							$value = str_replace('&nbsp;', '', $value);

							// Split by pipe
							$clean[$key] = array_values(array_filter(
								array_map('trim', explode('|', $value)),
								fn($item) => $item !== ''
							));
							break;
                        default:
                            $clean[$key] = MeilisearchProductIndexer::utf8String((string) $value);
                    }
                }

                // Same source as PDP Size: short_description (CSV or DB lookup), else size column.
                if ($shortDescRaw === '' && isset($clean['id'])) {
                    $shortDescRaw = (string) ($pdpShortById[(int) $clean['id']] ?? '');
                }
                $clean['size'] = SizeNormalizer::indexValues($sizeRaw, $shortDescRaw);

                // Merged unique notes for the single Fragrance Notes PLP filter
                $clean['fragrance_notes'] = array_values(array_unique(array_merge(
                    $clean['top_note'] ?? [],
                    $clean['middle_note'] ?? [],
                    $clean['base_note'] ?? []
                )));
                
                $batch[] = MeilisearchProductIndexer::utf8Deep($clean);
                $count++;
                
                if (count($batch) >= $batchSize) {
                    $index->addDocuments($batch);
                    $output[] = "Indexed {$count} records so far...";
                    $batch = [];
                }
            }
            
            fclose($handle);
            
            // Add remaining records
            if (!empty($batch)) {
                $index->addDocuments($batch);
            }
            
            $output[] = "All {$count} products indexed successfully!";
            $output[] = "Index settings synced for: " . ($synced['index'] ?? 'products_maxaroma');
            
            return response()->json([
                'success' => true,
                'message' => "Successfully indexed {$count} products (filterable attrs synced from catalog, including fragrance_notes)",
                'total_indexed' => $count,
                'settings_synced' => $synced,
                'output' => implode("\n", $output)
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Failed to index products from CSV: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to index products: ' . $e->getMessage()
            ], 500);
        }
    } 
	
	/**
     * Run the index-pages command via URL
     * GET /api/index-pages
     */
    public function indexPages()
    {
        try {
            // Increase execution time limit
            set_time_limit(0);
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');
           
            $client = $this->getClient();

            $SITE_URL = config('global.SITE_URL', 'https://www.grownbrilliance.com/');
            
            // Set up image URLs
            $SPEED_SIZE_URL = 'https://media.grownbrilliance.com/a2c5372a-e16a-4ea3-b361-da9fdc89a59f/';
            $SITE_IMAGES_URL = 'https://images.grownbrilliance.com/';
            $SPEED_SIZE_SITE_IMAGES_URL = $SPEED_SIZE_URL . $SITE_IMAGES_URL;
            $SPEED_SIZE_SITE_IMAGES = $SPEED_SIZE_SITE_IMAGES_URL . 'images/';
            
            $output = [];
            $results = [
                'static_pages' => 0,
                'education_pages' => 0,
                'support_pages' => 0
            ];
            
            // Index Static Pages
            $output[] = 'Indexing static pages...';
            try {
                $staticPages = \App\Models\StaticPage::where('status', '1')
					->whereNotIn('name', ['site-map', 'faqs', 'special-offers'])
                    ->orderBy('position', 'ASC')
                    ->get();
               
                $staticObjects = array();
                foreach ($staticPages as $page) {
                    $content = stripcslashes($page->content);
                    $content = str_replace('{$site_url}', $SITE_URL, $content);
                    $content = str_replace('{$Site_URL}', $SITE_URL, $content);
                    $content = str_replace('{$site_images}', $SPEED_SIZE_SITE_IMAGES, $content);
                    $content = str_replace('{$TOLL_FREE_NO}', '877-476-9627', $content);
                    $content = str_replace('{$CONTACT_MAIL}', 'customerservice@grownbrilliance.com', $content);
                    $content = strip_tags($content);
                    
                    $data_row = [
                        "id" => 'page_' . $page->static_pages_id,
                        "objectID" => 'page_' . $page->static_pages_id,
                        "page_title" => $page->title,
                        "short_description" => $page->short_content ?? "",
                        "description" => $content,
                        "page_url" => $SITE_URL . "pages/" . $page->name,
                        "type" => "static_pages",
                    ];
                    $staticObjects[] = $data_row;
                }
                
                if (!empty($staticObjects)) {
                    $staticIndex = $client->index('gb_static_pages');
                    $aaa = $client->index('gb_static_pages')->addDocuments($staticObjects, 'id');
                    $results['static_pages'] = count($staticObjects);
                    $output[] = "Static pages indexed: " . count($staticObjects) . " pages";
                } else {
                    $output[] = "No static pages to index";
                }
            } catch (\Exception $e) {
                $output[] = "Error indexing static pages: " . $e->getMessage();
                Log::error('Static pages indexing error: ' . $e->getMessage());
            }
            
            // Index Education Pages
            $output[] = 'Indexing education pages...';
            try {
                $educationPages = \App\Models\EducationPage::where('status', '1')
                    ->orderBy('position', 'ASC')
                    ->get();
                
                $educationObjects = [];
                foreach ($educationPages as $education_page) {
                    $content = stripcslashes($education_page->content);
                    $content = str_replace('{$site_url}', $SITE_URL, $content);
                    $content = str_replace('{$Site_URL}', $SITE_URL, $content);
                    $content = str_replace('{$site_images}', $SPEED_SIZE_SITE_IMAGES, $content);
                    $content = str_replace('{$TOLL_FREE_NO}', '877-476-9627', $content);
                    $content = str_replace('{$CONTACT_MAIL}', 'customerservice@grownbrilliance.com', $content);
                    $content = strip_tags($content);
                    
                    // Clean and ensure UTF-8 encoding
                    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                    /* $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'); */
                    $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                    $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);
                    
                    $title = $education_page->title ?? 'Untitled';
                    $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
                    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $title = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $title);
                    
                    $data_row = [
                        "id" => "education_guide_" . $education_page->education_pages_id,
                        "objectID" => "education_guide_" . $education_page->education_pages_id,
                        "page_title" => $title,
                        "short_description" => "",
                        "description" => $content,
                        "page_url" => $SITE_URL . "education-guide/" . $education_page->name,
                        "type" => "education_pages",
                    ];
                    
                    if (empty($data_row['id']) || empty($data_row['page_title'])) {
                        continue;
                    }
                    
                    $educationObjects[] = $data_row;
                }
                
                if (!empty($educationObjects)) {
                    $educationIndex = $client->index('gb_education_pages');
                    
                    // Configure searchable attributes
                    try {
                        $educationIndex->updateSearchableAttributes([
                            'page_title',
                            'short_description',
                            'description'
                        ]);
                    } catch (\Exception $e) {
                        // Ignore if already configured
                    }
                    
                    $educationIndex->addDocuments($educationObjects, 'id');
                    $results['education_pages'] = count($educationObjects);
                    $output[] = "Education pages indexed: " . count($educationObjects) . " pages";
                } else {
                    $output[] = "No education pages to index";
                }
            } catch (\Exception $e) {
                $output[] = "Error indexing education pages: " . $e->getMessage();
                Log::error('Education pages indexing error: ' . $e->getMessage());
            }
            
            // Index Support Articles from Gladly API
            $output[] = 'Indexing support articles from Gladly...';
            try {
                $baseUrl = "https://cleanorigin.gladly.com/api/v1/orgs/PRpUlRuHSyShDEnfKMONqA/answers?audienceId=uv3LsFPNR7uNh5KBd45uow";
                $allArticles = [];
                $objects = [];
                
                do {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $baseUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                    ]);
                    $response = curl_exec($ch);
                    if (curl_errno($ch)) {
                        break;
                    }
                    $data = json_decode($response, true);
                    if (isset($data)) {
                        $allArticles = array_merge($allArticles, $data);
                    }
                    $baseUrl = $data['next_page'] ?? null;
                    curl_close($ch);
                } while ($baseUrl);
                
                if (isset($allArticles) && count($allArticles) > 0) {
                    foreach ($allArticles as $article) {
                        if (!empty($article['name'])) {
                            $content = stripcslashes($article['bodyHtml']);
                            $content = str_replace('{$site_url}', $SITE_URL, $content);
                            $content = str_replace('{$Site_URL}', $SITE_URL, $content);
                            $content = str_replace('{$site_images}', $SPEED_SIZE_SITE_IMAGES, $content);
                            $content = str_replace('{$TOLL_FREE_NO}', '877-476-9627', $content);
                            $content = str_replace('{$CONTACT_MAIL}', 'customerservice@grownbrilliance.com', $content);
                            $content = strip_tags($content);
                            
                            $PageTitle = strip_tags($article['name']);
                            $PageTitle = str_replace(' ', '-', $PageTitle);
                            $PageTitle = str_replace('?', '-', $PageTitle);
                            
                            $PageLink = 'customer-support?a=' . $PageTitle . '--id--' . $article['id'];
                            
                            $data_row = [
                                "id" => "support_" . $article['id'],
                                "objectID" => "support_" . $article['id'],
                                "page_title" => $article['name'],
                                "short_description" => "",
                                "description" => $content,
                                "page_url" => $SITE_URL . $PageLink,
                                "type" => "support_pages",
                            ];
                            $objects[] = $data_row;
                        }
                    }
                }
                
                if (!empty($objects)) {
                    $supportIndex = $client->index('gladly_support_pages');
                    
                    try {
                        $client->createIndex('gladly_support_pages', ['primaryKey' => 'id']);
                    } catch (\Exception $e) {
                        // Index might already exist
                    }
                    
                    try {
                        $supportIndex->updateSearchableAttributes([
                            'page_title',
                            'description'
                        ]);
                    } catch (\Exception $e) {
                        // Ignore if already configured
                    }
                    
                    $supportIndex->addDocuments($objects, 'id');
                    $results['support_pages'] = count($objects);
                    $output[] = "Support articles indexed: " . count($objects) . " articles";
                } else {
                    $output[] = "No support articles to index";
                }
            } catch (\Exception $e) {
                $output[] = "Error indexing support articles: " . $e->getMessage();
                Log::error('Support articles indexing error: ' . $e->getMessage());
            }
            
            $output[] = 'Indexing completed successfully!';
            
            return response()->json([
                'success' => true,
                'message' => 'Pages indexing completed',
                'results' => $results,
                'output' => implode("\n", $output)
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Failed to index pages: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to index pages: ' . $e->getMessage()
            ], 500);
        }
    }
	 
}

