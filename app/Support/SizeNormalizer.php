<?php

namespace App\Support;

/**
 * Size facets for Meili — one rule for every size value:
 * 1) Always display as dual "X Oz / Y ml" (never bare "28 ml" / "5 ml" / "20g")
 * 2) Prefer oz spelling from PDP short_description when present
 * 3) Otherwise derive oz from retail map, then math
 * 4) Group filter matches by volume (ml); treat g ≈ ml for solids
 */
class SizeNormalizer
{
	private const ML_PER_OZ = 29.5735;

	/** @var list<float> */
	private const STANDARD_ML = [
		1.5, 2.0, 3.0, 5.0, 7.5, 8.0, 10.0, 15.0, 20.0, 25.0, 28.0, 30.0,
		40.0, 45.0, 50.0, 60.0, 75.0, 80.0, 90.0, 100.0, 125.0,
		150.0, 175.0, 200.0, 250.0, 500.0, 1000.0,
	];

	/**
	 * Volume grouping only (not display). Both 0.16 and 0.17 → 5 ml bucket.
	 *
	 * @var array<string, float>
	 */
	private const RETAIL_OZ_TO_ML = [
		'0.04' => 1.5,
		'0.05' => 1.5,
		'0.06' => 2.0,
		'0.07' => 2.0,
		'0.1' => 3.0,
		'0.10' => 3.0,
		'0.16' => 5.0,
		'0.17' => 5.0,
		'0.33' => 10.0,
		'0.34' => 10.0,
		'0.5' => 15.0,
		'0.74' => 20.0,
		'0.75' => 20.0,
		'0.94' => 28.0,
		'0.95' => 28.0,
		'1' => 30.0,
		'1.7' => 50.0,
		'2.5' => 75.0,
		'2.7' => 80.0,
		'3.4' => 100.0,
		'4.2' => 125.0,
		'5' => 150.0,
		'6.7' => 200.0,
		'6.8' => 200.0,
		'16.9' => 500.0,
		'17' => 500.0,
		'33.8' => 1000.0,
		'34' => 1000.0,
	];

	/**
	 * Preferred oz spelling when inventing a dual from ml-only / g-only data.
	 * Prefer the common PDP figure (0.17 not 0.16, 0.94 not 0.95).
	 *
	 * @var array<string, float>
	 */
	private const ML_TO_PREFERRED_OZ = [
		'1.5' => 0.05,
		'2' => 0.07,
		'3' => 0.1,
		'5' => 0.17,
		'7.5' => 0.25,
		'8' => 0.27,
		'10' => 0.34,
		'15' => 0.5,
		'20' => 0.74,
		'25' => 0.85,
		'28' => 0.94,
		'30' => 1.0,
		'40' => 1.35,
		'45' => 1.5,
		'50' => 1.7,
		'60' => 2.0,
		'75' => 2.5,
		'80' => 2.7,
		'90' => 3.0,
		'100' => 3.4,
		'125' => 4.2,
		'150' => 5.0,
		'175' => 5.9,
		'200' => 6.8,
		'250' => 8.4,
		'500' => 16.9,
		'1000' => 33.8,
	];

	/** Number token: 28, 0.94, .28 (leading-dot fractions). */
	private const NUM = '(?:\d+\.?\d*|\.\d+)';

	/**
	 * Values stored on the Meili `size` attribute.
	 * Always store dual "X Oz / Y ml" when a volume can be parsed.
	 *
	 * @return list<string>
	 */
	public static function indexValues(string $sizeColumn, string $shortDescription = ''): array
	{
		$candidates = [];

		$fromPdp = self::extractSizeFromPdpText($shortDescription);
		if ($fromPdp !== null) {
			$candidates[] = $fromPdp;
		}

		foreach (preg_split('/[,|]+/', self::cleanRaw($sizeColumn)) ?: [] as $part) {
			$part = trim($part);
			if ($part === '') {
				continue;
			}
			$candidates[] = self::extractSizeFromPdpText($part) ?? $part;
		}

		$bestDual = null;
		$bestAny = null;
		foreach ($candidates as $source) {
			$label = self::pdpLabel($source);
			if ($label === '') {
				continue;
			}
			if ($bestAny === null) {
				$bestAny = $label;
			}
			if (preg_match('/oz\s*\/\s*[\d.]+\s*ml/iu', $label)) {
				$bestDual = $label;
				break;
			}
		}

		$chosen = $bestDual ?? $bestAny;
		return $chosen !== null && $chosen !== '' ? [$chosen] : [];
	}

	/**
	 * Pull the size token out of PDP short_description HTML / marketing copy.
	 * Handles: "0.74 oz / 20 ml …", "33.8 Oz (1000 Ml)", "1000ml - 34OZ", "20g".
	 */
	public static function extractSizeFromPdpText(string $raw): ?string
	{
		$raw = self::cleanRaw($raw);
		if ($raw === '') {
			return null;
		}
		$num = self::NUM;

		// Oz / ml with slash or parentheses: 33.8 Oz (1000 Ml), 0.74 oz / 20 ml, .28 Oz (8 Ml)
		if (preg_match('/(?i)(' . $num . ')\s*(fl\.?\s*)?oz\.?\s*(?:\/|\()\s*(' . $num . ')\s*ml\.?\s*\)?/u', $raw, $m)) {
			return self::formatNumber((float) $m[1]) . ' oz / ' . self::formatNumber((float) $m[3]) . ' ml';
		}
		if (preg_match('/(?i)(' . $num . ')\s*ml\.?\s*(?:\/|\()\s*(' . $num . ')\s*(fl\.?\s*)?oz\.?\s*\)?/u', $raw, $m)) {
			return self::formatNumber((float) $m[2]) . ' oz / ' . self::formatNumber((float) $m[1]) . ' ml';
		}

		// Collect every unit size mention; prefer oz+ml pairs when both appear (1000ml - 34OZ).
		if (!preg_match_all('/(?i)(?<![A-Za-z0-9])(' . $num . ')\s*(fl\.?\s*)?(oz|ml|g|gr|gram|grams)\.?\b/u', $raw, $matches, PREG_SET_ORDER)) {
			return null;
		}

		$oz = null;
		$ml = null;
		foreach ($matches as $m) {
			$amount = (float) $m[1];
			$unit = strtolower($m[3]);
			if ($amount <= 0) {
				continue;
			}
			if ($unit === 'oz') {
				$oz = $amount;
			} else {
				// ml and g/gram — fragrance solids often use g ≈ ml volume.
				$ml = $amount;
			}
		}

		if ($oz !== null && $ml !== null) {
			return self::formatNumber($oz) . ' oz / ' . self::formatNumber($ml) . ' ml';
		}
		if ($oz !== null) {
			return self::formatNumber($oz) . ' oz';
		}
		if ($ml !== null) {
			return self::formatNumber($ml) . ' ml';
		}

		return null;
	}

	/**
	 * @param list<string> $rawValues
	 * @return list<string>
	 */
	public static function canonicalizeList(array $rawValues): array
	{
		$out = [];
		$seen = [];
		foreach ($rawValues as $raw) {
			if (!is_scalar($raw)) {
				continue;
			}
			$label = self::pdpLabel((string) $raw);
			if ($label === '') {
				continue;
			}
			$key = mb_strtolower($label);
			if (isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;
			$out[] = $label;
		}
		return $out;
	}

	public static function canonical(string $raw): string
	{
		return self::pdpLabel($raw);
	}

	public static function label(string $raw): string
	{
		return self::pdpLabel($raw);
	}

	/**
	 * PDP-style dual label from any size string.
	 * Always "X Oz / Y ml" for parseable volumes — never bare "28 ml" / "20g".
	 */
	public static function pdpLabel(string $raw): string
	{
		$extracted = self::extractSizeFromPdpText($raw);
		if ($extracted !== null) {
			$raw = $extracted;
		} else {
			$raw = self::cleanRaw($raw);
		}
		if ($raw === '') {
			return '';
		}
		$parsed = self::parse($raw);
		if ($parsed === null) {
			return self::normalizeLooseLabel($raw);
		}

		$ml = $parsed['ml'];
		$mlFmt = self::formatNumber($ml);

		if (isset($parsed['display_oz']) && (float) $parsed['display_oz'] > 0) {
			$ozFmt = self::formatNumber((float) $parsed['display_oz']);
		} else {
			$ozFmt = self::formatNumber(self::preferredOzForMl($ml));
		}

		return $ozFmt . ' Oz / ' . $mlFmt . ' ml';
	}

	/**
	 * @return list<string>
	 */
	public static function matchValues(string $selected): array
	{
		$selected = trim($selected);
		if ($selected === '') {
			return [];
		}
		$parsed = self::parse($selected) ?? self::parse((string) (self::extractSizeFromPdpText($selected) ?? ''));
		$out = [$selected, self::pdpLabel($selected)];

		if ($parsed === null) {
			return array_values(array_unique(array_filter(array_map('trim', $out), static fn ($v) => $v !== '')));
		}

		$ml = $parsed['ml'];
		$mlFmt = self::formatNumber($ml);
		$out[] = $mlFmt . ' ml';
		$out[] = $mlFmt . 'ml';
		$out[] = $mlFmt . ' ML';
		$out[] = $mlFmt . ' g';
		$out[] = $mlFmt . 'g';
		$out[] = $mlFmt . ' G';

		$ozAmounts = [];
		if (isset($parsed['display_oz']) && (float) $parsed['display_oz'] > 0) {
			$ozAmounts[] = (float) $parsed['display_oz'];
		}
		$ozAmounts[] = self::preferredOzForMl($ml);
		foreach (self::RETAIL_OZ_TO_ML as $ozKey => $mappedMl) {
			if (self::formatNumber((float) $mappedMl) === $mlFmt) {
				$ozAmounts[] = (float) $ozKey;
			}
		}
		$ozMath = $ml / self::ML_PER_OZ;
		foreach ([
			round($ozMath, 2),
			round($ozMath, 1),
			floor($ozMath * 100) / 100,
			ceil($ozMath * 100) / 100,
		] as $approx) {
			if ($approx > 0) {
				$ozAmounts[] = (float) $approx;
			}
		}

		foreach (array_unique($ozAmounts) as $ozAmount) {
			$ozFmt = self::formatNumber((float) $ozAmount);
			$out[] = $ozFmt . ' Oz';
			$out[] = $ozFmt . ' oz';
			$out[] = $ozFmt . 'Oz';
			$out[] = $ozFmt . 'oz';
			$out[] = $ozFmt . ' Oz / ' . $mlFmt . ' ml';
			$out[] = $ozFmt . ' oz / ' . $mlFmt . ' ml';
			$out[] = $ozFmt . 'oz / ' . $mlFmt . 'ml';
			$out[] = $ozFmt . 'oz/' . $mlFmt . 'ml';
			$out[] = $ozFmt . ' oz. / ' . $mlFmt . ' ml.';
		}

		return array_values(array_unique(array_filter(array_map('trim', $out), static fn ($v) => $v !== '')));
	}

	/**
	 * @param array<string|int, int> $distribution
	 * @return list<array{value:string,label:string,count:int,active:int}>
	 */
	public static function bucketFacetValues(array $distribution): array
	{
		$buckets = [];
		foreach ($distribution as $raw => $count) {
			$raw = trim((string) $raw);
			if ($raw === '') {
				continue;
			}
			$count = (int) $count;
			$parsed = self::parse($raw);
			if ($parsed === null) {
				$key = 'other:' . mb_strtolower(self::normalizeLooseLabel($raw));
				if (!isset($buckets[$key])) {
					$buckets[$key] = [
						'value' => self::normalizeLooseLabel($raw),
						'label' => self::normalizeLooseLabel($raw),
						'count' => 0,
						'active' => 0,
						'sort_ml' => 1_000_000.0,
						'raws' => [],
					];
				}
				$buckets[$key]['count'] += $count;
				$buckets[$key]['raws'][$raw] = ($buckets[$key]['raws'][$raw] ?? 0) + $count;
				continue;
			}

			$key = 'ml:' . self::formatNumber($parsed['ml']);
			if (!isset($buckets[$key])) {
				$buckets[$key] = [
					'value' => '',
					'label' => '',
					'count' => 0,
					'active' => 0,
					'sort_ml' => $parsed['ml'],
					'raws' => [],
				];
			}
			$buckets[$key]['count'] += $count;
			$buckets[$key]['raws'][$raw] = ($buckets[$key]['raws'][$raw] ?? 0) + $count;
		}

		foreach ($buckets as &$bucket) {
			$raws = $bucket['raws'] ?? [];
			unset($bucket['raws']);
			$labelRaw = self::pickPdpRawForLabel($raws);
			$label = self::pdpLabel($labelRaw);
			if ($label === '') {
				$label = $labelRaw;
			}
			// Always dual for volume buckets (even if every raw was bare "28 ml" / "20g").
			$bucket['value'] = $label;
			$bucket['label'] = $label;
		}
		unset($bucket);

		$list = array_values($buckets);
		usort($list, static function (array $a, array $b): int {
			if ($a['sort_ml'] === $b['sort_ml']) {
				return strcasecmp($a['label'], $b['label']);
			}
			return $a['sort_ml'] <=> $b['sort_ml'];
		});

		return array_map(static function (array $row): array {
			unset($row['sort_ml']);
			return $row;
		}, $list);
	}

	/**
	 * Prefer a PDP-style raw that includes oz; never prefer a bare "{n} ml" / "{n}g"
	 * when a dual label exists in the same volume bucket.
	 *
	 * @param array<string, int> $raws
	 */
	private static function pickPdpRawForLabel(array $raws): string
	{
		if ($raws === []) {
			return '';
		}

		$withOz = [];
		$others = [];
		foreach ($raws as $raw => $count) {
			$raw = (string) $raw;
			$parsed = self::parse($raw);
			$label = self::pdpLabel($raw);
			$isBareVolume = (bool) preg_match('/^\d+(\.\d+)?\s*(ml|g)$/iu', trim((string) $raw));
			if ($parsed !== null && isset($parsed['display_oz'])) {
				$withOz[$raw] = $count;
			} elseif (!$isBareVolume) {
				$others[$raw] = $count;
			}
		}

		$pool = $withOz !== [] ? $withOz : ($others !== [] ? $others : $raws);
		arsort($pool, SORT_NUMERIC);
		return (string) array_key_first($pool);
	}

	/**
	 * @return array{ml: float, prefer_ml: bool, display_oz?: float}|null
	 */
	public static function parse(string $raw): ?array
	{
		$raw = self::cleanRaw($raw);
		if ($raw === '') {
			return null;
		}

		if (preg_match('/(?i)^\d+\s*[xX×]\s*[\d.]+\s*(ml|oz|g)\.?$/u', $raw)) {
			return null;
		}

		$num = self::NUM;

		// PDP dual: "0.17oz / 5ml" or "33.8 Oz (1000 Ml)" or ".28 Oz (8 Ml)"
		if (preg_match('/(?i)^(' . $num . ')\s*(fl\.?\s*)?oz\.?\s*(?:\/|\()\s*(' . $num . ')\s*ml\.?\s*\)?$/u', $raw, $m)) {
			$oz = (float) $m[1];
			$ml = self::snapMl((float) $m[3]);
			if ($oz > 0 && $ml > 0) {
				return ['ml' => $ml, 'prefer_ml' => $ml < 20, 'display_oz' => $oz];
			}
		}
		if (preg_match('/(?i)^(' . $num . ')\s*ml\.?\s*(?:\/|\()\s*(' . $num . ')\s*(fl\.?\s*)?oz\.?\s*\)?$/u', $raw, $m)) {
			$ml = self::snapMl((float) $m[1]);
			$oz = (float) $m[2];
			if ($oz > 0 && $ml > 0) {
				return ['ml' => $ml, 'prefer_ml' => $ml < 20, 'display_oz' => $oz];
			}
		}

		// Single unit: oz, ml, or g (grams treated as ml for fragrance solids).
		if (preg_match('/(?i)^(' . $num . ')\s*(fl\.?\s*)?(oz|ml|g|gr|gram|grams)\.?$/u', $raw, $m)) {
			$amount = (float) $m[1];
			if ($amount <= 0) {
				return null;
			}
			$unit = strtolower($m[3]);
			if ($unit === 'oz') {
				$ozKey = self::formatNumber($amount);
				if (isset(self::RETAIL_OZ_TO_ML[$ozKey])) {
					return [
						'ml' => self::RETAIL_OZ_TO_ML[$ozKey],
						'prefer_ml' => self::RETAIL_OZ_TO_ML[$ozKey] < 20,
						'display_oz' => $amount,
					];
				}
				$ml = self::snapMl($amount * self::ML_PER_OZ);
				return ['ml' => $ml, 'prefer_ml' => false, 'display_oz' => $amount];
			}
			$ml = self::snapMl($amount);
			return ['ml' => $ml, 'prefer_ml' => $ml < 20];
		}

		if (preg_match('/(?i)^(' . $num . ')$/u', $raw, $m)) {
			$amount = (float) $m[1];
			if ($amount <= 0) {
				return null;
			}
			if ($amount <= 12) {
				$looksLikeMlSample = $amount >= 5 && $amount <= 10 && abs($amount - (int) $amount) < 0.001;
				if ($looksLikeMlSample) {
					return ['ml' => self::snapMl($amount), 'prefer_ml' => true];
				}
				$ozKey = self::formatNumber($amount);
				if (isset(self::RETAIL_OZ_TO_ML[$ozKey])) {
					return [
						'ml' => self::RETAIL_OZ_TO_ML[$ozKey],
						'prefer_ml' => self::RETAIL_OZ_TO_ML[$ozKey] < 20,
						'display_oz' => $amount,
					];
				}
				$ml = self::snapMl($amount * self::ML_PER_OZ);
				return ['ml' => $ml, 'prefer_ml' => false, 'display_oz' => $amount];
			}
			$ml = self::snapMl($amount);
			return ['ml' => $ml, 'prefer_ml' => $ml < 20];
		}

		return null;
	}

	/** Preferred oz for a given ml when inventing dual labels. */
	private static function preferredOzForMl(float $ml): float
	{
		$mlFmt = self::formatNumber($ml);
		if (isset(self::ML_TO_PREFERRED_OZ[$mlFmt])) {
			return self::ML_TO_PREFERRED_OZ[$mlFmt];
		}
		$candidates = [];
		foreach (self::RETAIL_OZ_TO_ML as $ozKey => $mappedMl) {
			if (self::formatNumber((float) $mappedMl) === $mlFmt) {
				$candidates[] = (float) $ozKey;
			}
		}
		if ($candidates !== []) {
			return max($candidates);
		}
		return round($ml / self::ML_PER_OZ, 2);
	}

	private static function snapMl(float $ml): float
	{
		$best = null;
		$bestDiff = null;
		foreach (self::STANDARD_ML as $standard) {
			if ($standard <= 0) {
				continue;
			}
			$diff = abs($ml - $standard);
			$pct = $diff / $standard;
			$maxPct = $standard < 20 ? 0.08 : 0.015;
			$maxAbs = $standard < 20 ? 0.25 : 0.0;
			if ($pct > $maxPct && !($maxAbs > 0 && $diff <= $maxAbs)) {
				continue;
			}
			if ($bestDiff === null || $diff < $bestDiff) {
				$best = $standard;
				$bestDiff = $diff;
			}
		}
		if ($best !== null) {
			return $best;
		}
		if ($ml >= 15) {
			return (float) round($ml);
		}
		return round($ml, 2);
	}

	private static function cleanRaw(string $raw): string
	{
		$raw = html_entity_decode(strip_tags(trim($raw)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$raw = str_replace(["\xc2\xa0", '&nbsp;'], ' ', $raw);
		return trim(preg_replace('/\s+/u', ' ', $raw) ?? $raw);
	}

	private static function formatNumber(float $n): string
	{
		$formatted = rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
		return $formatted === '' ? '0' : $formatted;
	}

	private static function normalizeLooseLabel(string $raw): string
	{
		$raw = self::cleanRaw($raw);
		if ($raw === '') {
			return '';
		}
		if (preg_match('/(?i)^(\d+)\s*[xX×]\s*([\d.]+)\s*(ml|oz|g)\.?$/u', $raw, $m)) {
			$unit = strtolower($m[3]);
			$unitLabel = $unit === 'oz' ? 'Oz' : ($unit === 'g' ? 'g' : 'ml');
			return $m[1] . ' x ' . self::formatNumber((float) $m[2]) . ' ' . $unitLabel;
		}
		if (preg_match('/^[A-Za-z][A-Za-z\s\-]{0,40}$/u', $raw)) {
			return mb_convert_case(mb_strtolower($raw), MB_CASE_TITLE, 'UTF-8');
		}
		return $raw;
	}
}
