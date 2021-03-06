<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils;

use pxn\phpUtils\Arrays;


final class Strings {
	private final function __construct() {}



	#################
	## Trim String ##
	#################



	public static function Trim($text, ...$remove) {
		if (!\is_array($remove)) {
			$remove = [ (string)$remove ];
		}
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = [ ' ', "\t", "\r", "\n" ];
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\substr($text, 0, 1), $remove)) {
				$text = \substr($text, 1);
				if ($text === $last) break;
				$last = $text;
			}
			while (\in_array(\substr($text,-1, 1), $remove)) {
				$text = \substr($text, 0, -1);
				if ($text === $last) break;
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \strlen($str);
					if ($len == 0) continue;
					$last = $text;
					while (\substr($text, 0, $len) == $str) {
						$text = \substr($text, $len);
						if($text === $last) break;
						$last = $text;
						$more = TRUE;
					}
					while (\substr($text, 0 - $len, $len) == $str) {
						$text = \substr($text, 0, 0 - $len);
						if ($text === $last) break;
						$last = $text;
						$more = TRUE;
					}
					if ($more) break;
				}
			} while ($more);
			unset($last);
		}
		return $text;
	}
	public static function TrimFront($text, ...$remove) {
		if (!\is_array($remove)) {
			$remove = [ (string)$remove ];
		}
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = [ ' ', "\t", "\r", "\n" ];
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\substr($text, 0, 1), $remove)) {
				$text = \substr($text, 1);
				if ($text === $last) break;
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \strlen($str);
					if ($len == 0) continue;
					$last = $text;
					while (\substr($text, 0, $len) == $str) {
						$text = \substr($text, $len);
						if ($text === $last) break;
						$last = $text;
						$more = TRUE;
					}
					if ($more) break;
				}
			} while ($more);
			unset($last);
		}
		return $text;
	}
	public static function TrimEnd($text, ...$remove) {
		if (!\is_array($remove)) {
			$remove = [ (string)$remove ];
		}
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = [ ' ', "\t", "\r", "\n" ];
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\substr($text, -1, 1), $remove)) {
				$text = \substr($text, 0, -1);
				if ($text === $last) break;
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \strlen($str);
					if ($len == 0) continue;
					$last = $text;
					while (\substr($text, 0 - $len, $len) == $str) {
						$text = \substr($text, 0, 0 - $len);
						if ($text === $last) break;
						$last = $text;
						$more = TRUE;
					}
					if ($more) break;
				}
			} while ($more);
			unset($last);
		}
		return $text;
	}



	/**
	 * Removes paired quotes from a string.
	 * @param string $text - String in which to remove quotes.
	 * @return string - String with ' and " quotes removed.
	 */
	public static function TrimQuotes($text) {
		while (\strlen($text) > 1) {
			$f = \substr($text, 0, 1);
			$e = \substr($text, -1, 1);
			// trim ' quotes
			if ($f == Defines::QUOTE_S && $e == Defines::QUOTE_S) {
				$text = \substr($text, 1, -1);
			} else
			// trim " quotes
			if ($f == Defines::QUOTE_D && $e == Defines::QUOTE_D) {
				$text = \substr($text, 1, -1);
			} else
			if ($f == Defines::ACCENT && $e == Defines::ACCENT) {
				$text = \substr($text, 1, -1);
			} else {
				break;
			}
		}
		return $text;
	}



	##############
	## Pad With ##
	##############



	public static function PadCenter($str, $size, $chr=' ') {
		$len = $size - \strlen($str);
		if ($len < 0)
			return $str;
		$padLeft  = \floor( ((double) $len) / 2.0);
		$padRight = \ceil(  ((double) $len) / 2.0);
		return \str_repeat($chr, $padLeft) . $str . \str_repeat($chr, $padRight);
	}



	######################
	## Starts/Ends With ##
	######################



	public static function StartsWith($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle))
			return FALSE;
		$len = \strlen($needle);
		if ($len > \strlen($haystack))
			return FALSE;
		if ($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return \substr($haystack, 0, $len) == $needle;
	}
	public static function EndsWith($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle))
			return FALSE;
		$len = \strlen($needle);
		if ($len > \strlen($haystack))
			return FALSE;
			if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return \substr($haystack, 0 - $len) == $needle;
	}



	#####################
	## Force Start/End ##
	#####################



	public static function ForceStartsWith($haystack, $prepend) {
		if (empty($haystack) || empty($prepend))
			return $haystack;
		if (self::StartsWith($haystack, $prepend))
			return $haystack;
		return $prepend.$haystack;
	}
	public static function ForceEndsWith($haystack, $append) {
		if (empty($haystack) || empty($append))
			return $haystack;
		if (self::EndsWith($haystack, $append))
			return $haystack;
		return $haystack.$append;
	}



	##############
	## Contains ##
	##############



	public static function Contains($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle))
			return FALSE;
		if ($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return (\strpos($haystack, $needle) !== FALSE);
	}



	##############
	## Get Part ##
	##############



	public static function peakPart(&$data, $patterns=' ') {
		$result = self::findPart($data, $patterns);
		// pattern not found
		if ($result == NULL)
			return $data;
		return \substr($data, 0, $result['POS']);
	}
	public static function grabPart(&$data, $patterns=' ') {
		$result = self::findPart($data, $patterns);
		// pattern not found
		if ($result == NULL) {
			$part = $data;
			$data = '';
			return $part;
		}
		// get part
		$part = \substr(
				$data,
				0,
				$result['POS']
		);
		// remove part from data
		$data = self::TrimFront(
				\substr(
						$data,
						$result['POS']+\strlen($result['PAT'])
				),
				$result['PAT']
		);
		return $part;
	}
	public static function findPart(&$data, $patterns) {
		if (empty($data))
			return NULL;
		$data = (string) $data;
		if (!\is_array($patterns))
			$patterns = [$patterns];
		// find next delim
		$pos   = \strlen($data);
		$delim = NULL;
		foreach ($patterns as $pat) {
			if (empty($pat)) continue;
			$pat = (string) $pat;
			$p = \strpos($data, $pat);
			if ($p === FALSE) continue;
			// found a sooner delim
			if ($p < $pos) {
				$pos   = $p;
				$delim = $pat;
			}
			// found delim at start
			if ($p == 0)
				break;
		}
		if ($delim == NULL)
			return NULL;
		return [
			'POS' => $pos,
			'PAT' => $delim
		];
	}



	################
	## File Paths ##
	################



	public static function BuildPath(...$parts) {
		if (empty($parts))
			return '';
		$prepend = self::StartsWith(\reset($parts), '/');
		$append  = self::EndsWith  (\end($parts),   '/');
		$cleaned = [];
		foreach ($parts as $str) {
			if (empty($str)) continue;
			$trimmed = self::Trim($str, '/', '\\', ' ');
			if (empty($trimmed)) continue;
			$cleaned[] = $trimmed;
		}
		return
			($prepend ? '/' : '').
			\implode('/', $cleaned).
			($append  ? '/' : '');
	}



}
