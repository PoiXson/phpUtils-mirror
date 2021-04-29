<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use pxn\phpUtils\Paths;
use pxn\phpUtils\pxnDefines as xDef;


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



	################
	## File Paths ##
	################



	public static function get_filename($filePath): string {
		if (empty($filePath))
			return '';
		$pos = \mb_strrpos($filePath, '/');
		if ($pos === false)
			return $filePath;
		return \mb_substr($filePath, $pos+1);
	}



	public static function build_path(string...$parts): string {
		if (empty($parts))
			return '';
		$prepend = \str_starts_with(haystack: \reset($parts), needle: '/');
		$append  = \str_ends_with  (haystack: \end($parts),   needle: '/');
		$cleaned = [];
		foreach ($parts as $str) {
			if (empty($str))
				continue;
			$trimmed = self::trim($str, '/', '\\', ' ');
			if (empty($trimmed))
				continue;
			$cleaned[] = $trimmed;
		}
		return
			($prepend ? '/' : '').
			\implode('/', $cleaned).
			($append  ? '/' : '');
	}



	public static function common_path(string $pathA, string $pathB): string {
		$prepend = \str_starts_with(haystack: $pathA, needle: '/')
				|| \str_starts_with(haystack: $pathB, needle: '/');
		if ($prepend) {
			$pathA = self::trim_front($pathA, '/');
			$pathB = self::trim_front($pathB, '/');
		}
		$partsA = \explode('/', $pathA);
		$partsB = \explode('/', $pathB);
		$endIndex =
			\min(
				\count($partsA),
				\count($partsB)
			) - 1;
		$result = [];
		for ($i=0; $i<$endIndex; $i++) {
			if ($partsA[$i] != $partsB[$i])
				break;
			$result[] = $partsA[$i];
		}
		if (\count($result) == 0)
			return ($prepend ? '/' : '');
		$result = \implode('/', $result);
		return (
			$prepend
			? self::force_starts_with(haystack: $result, prepend: '/')
			: $result
		);
	}



}
