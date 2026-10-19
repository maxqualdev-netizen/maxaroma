<?php

namespace App\Console\Commands;

use App\Services\MeilisearchProductIndexer;
use Illuminate\Console\Command;
use Throwable;

class MeilisearchIndexProductsCommand extends Command
{
	protected $signature = 'meilisearch:index-products
		{--ranking-only : Restore default ranking rules without rebuilding documents}
		{--settings-only : Sync searchable/filterable/sortable/pagination settings without rebuilding documents}';

	protected $description = 'Rebuild the Maxaroma perfume product search index in Meilisearch';

	public function handle(MeilisearchProductIndexer $indexer): int
	{
		if ($this->option('ranking-only')) {
			$this->info('Restoring default ranking rules on "' . $indexer->indexName() . '"...');
			try {
				$result = $indexer->restoreDefaultRankingRules();
				$this->info('Index: ' . $result['index']);
				$this->info('rankingRules: ' . implode(', ', $result['rankingRules']));
				return self::SUCCESS;
			} catch (Throwable $e) {
				$this->error($e->getMessage());
				return self::FAILURE;
			}
		}

		if ($this->option('settings-only')) {
			$this->info('Syncing index settings on "' . $indexer->indexName() . '"...');
			try {
				$result = $indexer->syncIndexSettings();
				$this->info('Index: ' . $result['index']);
				$this->info('searchableAttributes: ' . implode(', ', $result['searchableAttributes']));
				$this->info('pagination: ' . json_encode($result['pagination']));
				return self::SUCCESS;
			} catch (Throwable $e) {
				$this->error($e->getMessage());
				return self::FAILURE;
			}
		}

		try {
			$result = $indexer->rebuild();
			$this->info('Indexed ' . $result['indexed'] . ' products into "' . $result['index'] . '".');
			return self::SUCCESS;
		} catch (Throwable $e) {
			$this->error($e->getMessage());
			return self::FAILURE;
		}
	}
}
