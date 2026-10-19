<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Dedicated custom-search / NLP / Meili logger → storage/logs/search-*.log
 *
 * Disable with SEARCH_LOG_ENABLED=false in .env
 */
final class SearchLog
{
	public static function enabled(): bool
	{
		$flag = env('SEARCH_LOG_ENABLED', true);
		if (is_bool($flag)) {
			return $flag;
		}
		$flag = strtolower(trim((string) $flag));

		return !in_array($flag, ['0', 'false', 'off', 'no'], true);
	}

	public static function debug(string $message, array $context = []): void
	{
		self::write('debug', $message, $context);
	}

	public static function info(string $message, array $context = []): void
	{
		self::write('info', $message, $context);
	}

	public static function warning(string $message, array $context = []): void
	{
		self::write('warning', $message, $context);
	}

	public static function error(string $message, array $context = []): void
	{
		self::write('error', $message, $context);
	}

	private static function write(string $level, string $message, array $context): void
	{
		if (!self::enabled()) {
			return;
		}

		try {
			Log::channel('search')->{$level}($message, self::withRequestMeta($context));
		} catch (Throwable $e) {
			try {
				Log::{$level}('[search] ' . $message, $context);
			} catch (Throwable $ignored) {
				// never break search on logging failure
			}
		}
	}

	/**
	 * @param array<string, mixed> $context
	 * @return array<string, mixed>
	 */
	private static function withRequestMeta(array $context): array
	{
		try {
			$req = request();
			if ($req) {
				$context['_req'] = [
					'path' => '/' . ltrim((string) $req->path(), '/'),
					'method' => $req->method(),
					'is_custom' => $req->query('is_custom', $req->input('is_custom')),
					'ip' => $req->ip(),
				];
			}
		} catch (Throwable $e) {
			// CLI / early boot
		}

		return $context;
	}
}
