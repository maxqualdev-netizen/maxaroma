<?php

namespace App\Services\Meili;

use GuzzleHttp\Client as GuzzleClient;
use RuntimeException;

/**
 * Lightweight Meilisearch HTTP client (Guzzle only — no meilisearch/meilisearch-php package).
 * Upload this folder to live; no composer install required.
 */
class Client
{
	private GuzzleClient $http;
	private string $baseUrl;
	private string $apiKey;

	public function __construct(string $url, ?string $apiKey = null, $httpClient = null)
	{
		$this->baseUrl = rtrim($url, '/');
		$this->apiKey = (string) ($apiKey ?? '');
		$this->http = $httpClient instanceof GuzzleClient
			? $httpClient
			: new GuzzleClient([
				'timeout' => 30,
				'connect_timeout' => 5,
			]);
	}

	public function index(string $uid): Indexes
	{
		return new Indexes($this, $uid);
	}

	/**
	 * @param array{primaryKey?: string} $options
	 * @return array<string, mixed>
	 */
	public function createIndex(string $uid, array $options = []): array
	{
		$body = ['uid' => $uid];
		if (!empty($options['primaryKey'])) {
			$body['primaryKey'] = $options['primaryKey'];
		}
		return $this->request('POST', '/indexes', $body);
	}

	public function waitForTask($taskUid, int $timeoutInMs = 5000, int $intervalInMs = 50): array
	{
		if (is_array($taskUid)) {
			$taskUid = $taskUid['taskUid'] ?? $taskUid['uid'] ?? null;
		}
		$taskUid = (string) $taskUid;
		if ($taskUid === '') {
			return [];
		}

		$deadline = microtime(true) + ($timeoutInMs / 1000);
		do {
			$task = $this->request('GET', '/tasks/' . rawurlencode($taskUid));
			$status = (string) ($task['status'] ?? '');
			if (in_array($status, ['succeeded', 'failed', 'canceled'], true)) {
				if ($status === 'failed') {
					throw new RuntimeException('Meilisearch task failed: ' . json_encode($task['error'] ?? $task));
				}
				return $task;
			}
			usleep(max(1000, $intervalInMs) * 1000);
		} while (microtime(true) < $deadline);

		throw new RuntimeException('Meilisearch task timed out: ' . $taskUid);
	}

	/**
	 * @param array<string, mixed>|null $json
	 * @return array<string, mixed>
	 */
	public function request(string $method, string $path, ?array $json = null): array
	{
		$options = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
			'http_errors' => false,
		];
		if ($json !== null) {
			$options['json'] = $json;
		}

		$response = $this->http->request($method, $this->baseUrl . $path, $options);
		$code = $response->getStatusCode();
		$body = (string) $response->getBody();
		$data = $body !== '' ? json_decode($body, true) : [];
		if (!is_array($data)) {
			$data = [];
		}

		if ($code >= 400) {
			$message = $data['message'] ?? $data['error'] ?? $body;
			throw new RuntimeException('Meilisearch HTTP ' . $code . ': ' . (is_string($message) ? $message : json_encode($message)));
		}

		return $data;
	}
}
