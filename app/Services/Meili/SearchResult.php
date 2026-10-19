<?php

namespace App\Services\Meili;

class SearchResult
{
	/** @param array<string, mixed> $raw */
	public function __construct(private array $raw)
	{
	}

	/** @return array<string, mixed> */
	public function getRaw(): array
	{
		return $this->raw;
	}

	/** @return list<array<string, mixed>> */
	public function getHits(): array
	{
		$hits = $this->raw['hits'] ?? [];
		return is_array($hits) ? $hits : [];
	}

	/** @return array<string, array<string, int>> */
	public function getFacetDistribution(): array
	{
		$dist = $this->raw['facetDistribution'] ?? [];
		return is_array($dist) ? $dist : [];
	}

	/** @return array<string, mixed> */
	public function getFacetStats(): array
	{
		$stats = $this->raw['facetStats'] ?? [];
		return is_array($stats) ? $stats : [];
	}

	public function getTotalHits(): int
	{
		if (isset($this->raw['totalHits'])) {
			return (int) $this->raw['totalHits'];
		}
		if (isset($this->raw['estimatedTotalHits'])) {
			return (int) $this->raw['estimatedTotalHits'];
		}
		return count($this->getHits());
	}
}
