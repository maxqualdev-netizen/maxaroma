<?php

namespace App\Support;

/**
 * Send an HTTP redirect without Laravel's RedirectResponse (avoids "HTTP/1.0 302 Found" printed as page text on some servers).
 */
final class SearchRedirectHttp
{
	public static function send(string $url): never
	{
		$url = trim($url);
		if ($url === '') {
			exit;
		}

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		if (!headers_sent()) {
			header('Location: ' . $url, true, 302);
			exit;
		}

		$safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
		echo '<!DOCTYPE html><html><head>'
			. '<meta http-equiv="refresh" content="0;url=' . $safe . '">'
			. '</head><body></body></html>';
		exit;
	}
}
