<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use \pxn\phpUtils\pxnDefines as xDef;


final class StringUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}

	const DEFAULT_TRIM_CHARS = [ ' ', "\t", "\r", "\n" ];



	public static function mb_ucfirst(string $str, bool $weak=false): string {
		return \mb_strtoupper(
			\mb_substr($str, 0, 1)).
			(
				$weak
				? \mb_substr($str, 1)
				: \strtolower(\mb_substr($str, 1))
			);
	}



	#################
	## Trim String ##
	#################



	public static function trim(string $text, string...$remove): string {
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = true;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = false;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
			}
			while (\in_array(\mb_substr($text,-1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
			}
		// trim variable length strings
		} else {
			do {
				$more = false;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						$more = true;
					}
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						$more = true;
					}
					if ($more)
						break;
				}
			} while ($more);
		}
		return $text;
	}

	public static function trim_front(string $text, string...$remove): string {
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = true;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = false;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
			}
		// trim variable length strings
		} else {
			do {
				$more = false;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						$more = true;
					}
					if ($more)
						break;
				}
			} while ($more);
		}
		return $text;
	}

	public static function trim_end(string $text, string...$remove): string {
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = true;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = false;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			while (\in_array(\mb_substr($text, -1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
			}
		// trim variable length strings
		} else {
			do {
				$more = false;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						$more = true;
					}
					if ($more)
						break;
				}
			} while ($more);
		}
		return $text;
	}



	/**
	 * Removes paired quotes from a string.
	 * @param string $text - String in which to remove quotes.
	 * @return string - String with ' and " quotes removed.
	 */
	public static function trim_quotes(string $text): string {
		while (\mb_strlen($text) > 1) {
			$f = \mb_substr($text,  0, 1);
			$e = \mb_substr($text, -1, 1);
			// trim ' quotes
			if ($f == xDef::QUOTE_S && $e == xDef::QUOTE_S) {
				$text = \mb_substr($text, 1, -1);
			} else
			// trim " quotes
			if ($f == xDef::QUOTE_D && $e == xDef::QUOTE_D) {
				$text = \mb_substr($text, 1, -1);
			} else
			if ($f == xDef::ACCENT  && $e == xDef::ACCENT) {
				$text = \mb_substr($text, 1, -1);
			} else {
				break;
			}
		}
		return $text;
	}



	################
	## Pad String ##
	################



	public static function pad_left(string $str, int $size, string $char=' '): string {
		return self::get_padding($str, $size, $char) . $str;
	}

	public static function pad_right(string $str, int $size, string $char=' '): string {
		return $str . self::get_padding($str, $size, $char);
	}

	private static function get_padding(string $str, int $size, string $char=' '): string {
		if (empty($char))
			$char = ' ';
		$len = $size - \mb_strlen($str);
		if ($len < 0)
			return '';
		$char_len = \mb_strlen($char);
		if ($char_len > 1) {
			$padding = \str_repeat(
				$char,
				(int) \floor( ((float)$len) / ((float)$char_len) )
			);
			$padding .= \mb_substr(
				$char,
				0,
				$len % $char_len
			);
			return $padding;
		}
		return \str_repeat($char, $len);
	}

	public static function pad_center(string $str, int $size, string $char=' '): string {
		if (empty($char))
			$char = ' ';
		$len = $size - \mb_strlen($str);
		if ($len < 0)
			return $str;
		$pad_left  = (int) \floor( ((double) $len) / 2.0);
		$pad_right = (int) \ceil(  ((double) $len) / 2.0);
		return \str_repeat($char, $pad_left) . $str . \str_repeat($char, $pad_right);
	}



	#####################
	## Force Start/End ##
	#####################



	public static function force_starts_with(string $haystack, string $prepend): string {
		if (empty($haystack) || empty($prepend))
			return $haystack;
		if (\str_starts_with(haystack: $haystack, needle: $prepend))
			return $haystack;
		return $prepend . $haystack;
	}

	public static function force_ends_with(string $haystack, string $append): string {
		if (empty($haystack) || empty($append))
			return $haystack;
		if (\str_ends_with(haystack: $haystack, needle: $append))
			return $haystack;
		return $haystack . $append;
	}



	#####################
	## String Contains ##
	#####################



	public static function str_contains(string $haystack, string $needle, bool $ignoreCase=false): bool {
		if (empty($haystack) || empty($needle))
			return false;
		if ($ignoreCase) {
			$haystack = \mb_strtolower($haystack);
			$needle   = \mb_strtolower($needle);
		}
		return (\mb_strpos(haystack: $haystack, needle: $needle) !== false);
	}



	##############
	## Versions ##
	##############



	public static function compare_versions(string $versionA, string $versionB): string {
		if ($versionA === $versionB)
			return '=';
		$partsA = \explode('.', $versionA);
		$partsB = \explode('.', $versionB);
		$count = \max( \count($partsA), \count($partsB) );
		for ($i=0; $i<$count; $i++) {
			if (isset($partsA[$i])) {
				if ($partsA[$i] == 'x') {
					$a = xDef::INT_MAX;
				} else {
					$a = (int) $partsA[$i];
				}
			} else {
				$a = 0;
			}
			if (isset($partsB[$i])) {
				if ($partsB[$i] == 'x') {
					$b = xDef::INT_MAX;
				} else {
					$b = (int) $partsB[$i];
				}
			} else {
				$b = 0;
			}
			if ($a > $b) return '>';
			if ($a < $b) return '<';
		}
		return '=';
	}



}
