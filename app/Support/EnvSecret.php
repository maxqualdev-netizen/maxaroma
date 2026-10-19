<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Decrypt .env values prefixed with enc: (uses APP_KEY).
 * Plain values pass through unchanged.
 *
 * Encrypt for .env:
 *   php artisan openai:encrypt-key "sk-..."
 * Then set:
 *   OPENAI_SEARCH_API_KEY=enc:...
 */
class EnvSecret
{
	public const PREFIX = 'enc:';

	public static function encryptForEnv(string $plain): string
	{
		return self::PREFIX . Crypt::encryptString($plain);
	}

	public static function decrypt(?string $value): ?string
	{
		if ($value === null || $value === '') {
			return null;
		}

		$value = trim($value);
		if (!str_starts_with($value, self::PREFIX)) {
			return $value;
		}

		try {
			return Crypt::decryptString(substr($value, strlen(self::PREFIX)));
		} catch (DecryptException $e) {
			return null;
		}
	}
}
