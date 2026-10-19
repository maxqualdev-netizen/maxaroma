<?php

namespace App\Console\Commands;

use App\Support\EnvSecret;
use Illuminate\Console\Command;

class EncryptOpenAiApiKeyCommand extends Command
{
	protected $signature = 'openai:encrypt-key
		{key? : Plain OpenAI API key (omit to read OPENAI_SEARCH_API_KEY from .env)}
		{--plain : Output only the encrypted value}';

	protected $description = 'Encrypt OPENAI_SEARCH_API_KEY for .env as enc:... (decrypted at runtime via APP_KEY)';

	public function handle(): int
	{
		$key = $this->argument('key');

		if ($key === null || $key === '') {
			$raw = env('OPENAI_SEARCH_API_KEY');
			if ($raw === null || $raw === '') {
				$this->error('No key provided and OPENAI_SEARCH_API_KEY is not set in .env.');
				return self::FAILURE;
			}
			if (str_starts_with(trim($raw), EnvSecret::PREFIX)) {
				$key = EnvSecret::decrypt($raw);
				if ($key === null) {
					$this->error('OPENAI_SEARCH_API_KEY looks encrypted but could not be decrypted. Check APP_KEY.');
					return self::FAILURE;
				}
				$this->warn('OPENAI_SEARCH_API_KEY is already encrypted; re-encrypting the decrypted value.');
			} else {
				$key = trim($raw);
			}
		}

		$encrypted = EnvSecret::encryptForEnv((string) $key);

		if ($this->option('plain')) {
			$this->line($encrypted);
			return self::SUCCESS;
		}

		$this->info('Add or replace in .env (keep quotes):');
		$this->line('');
		$this->line('OPENAI_SEARCH_API_KEY="' . $encrypted . '"');
		$this->line('');
		$this->comment('Requires the same APP_KEY on every server. After deploy: php artisan config:clear');
		$this->comment('Plain keys (without enc:) still work.');

		return self::SUCCESS;
	}
}
