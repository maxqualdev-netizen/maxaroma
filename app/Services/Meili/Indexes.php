<?php

namespace App\Services\Meili;

class Indexes
{
	public function __construct(
		private Client $client,
		private string $uid
	) {
	}

	/**
	 * @param array<string, mixed> $searchParams
	 */
	public function search(?string $query, array $searchParams = []): SearchResult
	{
		$body = array_merge($searchParams, ['q' => $query ?? '']);
		$raw = $this->client->request('POST', '/indexes/' . rawurlencode($this->uid) . '/search', $body);
		return new SearchResult($raw);
	}

	/**
	 * @param list<array<string, mixed>> $documents
	 * @return array<string, mixed>
	 */
	public function addDocuments(array $documents, ?string $primaryKey = null): array
	{
		$path = '/indexes/' . rawurlencode($this->uid) . '/documents';
		if ($primaryKey) {
			$path .= '?primaryKey=' . rawurlencode($primaryKey);
		}
		return $this->client->request('POST', $path, $documents);
	}

	/**
	 * @param array<string, mixed> $settings
	 * @return array<string, mixed>
	 */
	public function updateSettings(array $settings): array
	{
		return $this->client->request('PATCH', '/indexes/' . rawurlencode($this->uid) . '/settings', $settings);
	}

	/** @return array<string, mixed> */
	public function delete(): array
	{
		return $this->client->request('DELETE', '/indexes/' . rawurlencode($this->uid));
	}

	/** @return list<string> */
	public function getRankingRules(): array
	{
		$data = $this->client->request('GET', '/indexes/' . rawurlencode($this->uid) . '/settings/ranking-rules');
		return is_array($data) ? array_values($data) : [];
	}

	/** @return array<string, mixed> */
	public function getSettings(): array
	{
		return $this->client->request('GET', '/indexes/' . rawurlencode($this->uid) . '/settings');
	}

	/**
	 * Index metadata (uid, primaryKey, createdAt, updatedAt).
	 * Matches meilisearch-php Indexes::fetchRawInfo().
	 *
	 * @return array<string, mixed>
	 */
	public function fetchRawInfo(): array
	{
		return $this->client->request('GET', '/indexes/' . rawurlencode($this->uid));
	}

	/** Alias used by some Meilisearch PHP versions. */
	public function getRawInfo(): array
	{
		return $this->fetchRawInfo();
	}

	/**
	 * Index stats including document count.
	 * GET /indexes/{uid}/stats → numberOfDocuments, isIndexing, fieldDistribution, …
	 *
	 * @return array<string, mixed>
	 */
	public function stats(): array
	{
		return $this->client->request('GET', '/indexes/' . rawurlencode($this->uid) . '/stats');
	}
}
