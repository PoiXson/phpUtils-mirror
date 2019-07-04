<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils;


final class Strings {
	private final function __construct() {}

	const DEFAULT_TRIM_CHARS = [ ' ', "\t", "\r", "\n" ];


	public static function mb_ucfirst($str, $full=TRUE) {
		return \mb_strtoupper(\mb_substr($str, 0, 1)).
			($full
				? \strtolower(\mb_substr($str, 1))
				: \mb_substr($str, 1)
			);
	}



//TODO: untested
//	public static function isBase64($str) {
//		return ( FALSE != preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $this->getValue()) );
//	}



	#################
	## Trim String ##
	#################



	public static function Trim($text, ...$remove) {
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
				if ($text === $last) {
					break;
				}
				$last = $text;
			}
			while (\in_array(\mb_substr($text,-1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
				if ($text === $last) {
					break;
				}
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0) {
						continue;
					}
					$last = $text;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						if ($text === $last) {
							break;
						}
						$last = $text;
						$more = TRUE;
					}
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						if ($text === $last) {
							break;
						}
						$last = $text;
						$more = TRUE;
					}
					if ($more) {
						break;
					}
				}
			} while ($more);
			unset($last);
		}
		return $text;
	}
	public static function TrimFront($text, ...$remove) {
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
				if ($text === $last) {
					break;
				}
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0) {
						continue;
					}
					$last = $text;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						if ($text === $last) {
							break;
						}
						$last = $text;
						$more = TRUE;
					}
					if ($more) {
						break;
					}
				}
			} while ($more);
			unset($last);
		}
		return $text;
	}
	public static function TrimEnd($text, ...$remove) {
		Arrays::TrimFlat($remove);
		if (\count($remove) == 0) {
			$remove = self::DEFAULT_TRIM_CHARS;
		}
		$allshort = TRUE;
		foreach ($remove as $str) {
			if (\mb_strlen($str) > 1) {
				$allshort = FALSE;
				break;
			}
		}
		// trim single chars
		if ($allshort) {
			$last = $text;
			while (\in_array(\mb_substr($text, -1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
				if ($text === $last) {
					break;
				}
				$last = $text;
			}
			unset($last);
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0) {
						continue;
					}
					$last = $text;
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						if ($text === $last) {
							break;
						}
						$last = $text;
						$more = TRUE;
					}
					if ($more) {
						break;
					}
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
		while (\mb_strlen($text) > 1) {
			$f = \mb_substr($text,  0, 1);
			$e = \mb_substr($text, -1, 1);
			// trim ' quotes
			if ($f == Defines::QUOTE_S && $e == Defines::QUOTE_S) {
				$text = \mb_substr($text, 1, -1);
			} else
			// trim " quotes
			if ($f == Defines::QUOTE_D && $e == Defines::QUOTE_D) {
				$text = \mb_substr($text, 1, -1);
			} else
			if ($f == Defines::ACCENT && $e == Defines::ACCENT) {
				$text = \mb_substr($text, 1, -1);
			} else {
				break;
			}
		}
		return $text;
	}



	public static function MergeDuplicates($text, ...$search) {
		Arrays::TrimFlat($search);
		if (\count($search) == 0) {
			$search = [ ' ' ];
		}
		while (TRUE) {
			$changed = FALSE;
			foreach ($search as $str) {
				$last = $text;
				$text = \str_replace(
					$str.$str,
					$str,
					$text
				);
				if ($text !== $last) {
					$changed = TRUE;
				}
			}
			if (!$changed) {
				break;
			}
		}
		unset ($last);
		return $text;
	}



	public static function WrapLines($text, $width, ...$lineEndings) {
		// array of lines
		if (\is_array($text)) {
			$result = [];
			foreach ($text as $line) {
				$result[] = self::WrapLines($line, $width, $lineEndings);
			}
			return Arrays::Flatten($result);
		}
		// already has multiple lines
		$text = (string) $text;
		if (\mb_strpos($text, "\n") !== FALSE) {
			$result = [];
			$lines = \explode("\n", $text);
			return self::WrapLines($lines, $width, $lineEndings);
		}
		// prepare
		$text = str_replace("\r", '', (string) $text);
		$width = (int) $width;
		if ($width < 1) {
			fail('WrapLines width is invalid!',
				Defines::EXIT_CODE_INTERNAL_ERROR);
		}
		if (\mb_strlen($text) <= $width) {
			return [ $text ];
		}
		Arrays::TrimFlat($lineEndings);
		if (\count($lineEndings) == 0) {
			$lineEndings = self::DEFAULT_TRIM_CHARS;
		}
		// wrap lines to max width
		$lines = [];
		while (TRUE) {
			if (\mb_strlen($text) <= $width) {
				$lines[] = $text;
				break;
			}
			// find place to wrap line
			for ($i=$width; $i>0; $i--) {
				$chr = \mb_substr($text, $i, 1);
				if (\in_array($chr, $lineEndings)) {
					$lines[] = \mb_substr($text, 0, $i);
					$text = \mb_substr($text, $i + 1);
					continue 2;
				}
			}
			// force split a line
			$lines[] = \mb_substr($text, 0, $width);
			$text = \mb_substr($text, $width);
		}
		return $lines;
	}



	##################
	## Pad to Width ##
	##################



	public static function PadLeft($str, $size, $chr=' ') {
		$padding = self::getPadding($str, $size, $chr);
		return $str.$padding;
	}
	public static function PadRight($str, $size, $chr=' ') {
		$padding = self::getPadding($str, $size, $chr);
		return $padding.$str;
	}
	private static function getPadding($str, $size, $chr=' ') {
		if (empty($chr)) {
			$chr = ' ';
		}
		$len = $size - \mb_strlen($str);
		if ($len < 0) {
			return '';
		}
		$chrLen = \mb_strlen($chr);
		if ($chrLen > 1) {
			$padding = \str_repeat(
				$chr,
				\floor($len / $chrLen)
			);
			$padding .= \mb_substr(
				$chr,
				0,
				$len % $chrLen
			);
			return $padding;
		}
		return \str_repeat($chr, $len);
	}
	public static function PadCenter($str, $size, $chr=' ') {
		if (empty($chr)) {
			$chr = ' ';
		}
		$len = $size - \mb_strlen($str);
		if ($len < 0) {
			return $str;
		}
		$padLeft  = \floor( ((double) $len) / 2.0);
		$padRight = \ceil(  ((double) $len) / 2.0);
		return \str_repeat($chr, $padLeft) . $str . \str_repeat($chr, $padRight);
	}



	// array [row] [column]
	public static function PadColumns($data, ...$widths) {
		// find column widths
		foreach ($data as $row) {
			if (!\is_array($row)) {
				continue;
			}
			$i = 0;
			foreach ($row as $cell) {
				if (\is_array($cell)) {
					$cell = \implode(', ', Arrays::Flatten($cell));
				}
				$len = \strlen($cell);
				if (!isset($widths[$i])) {
					$widths[$i] = 0;
				}
				if ($len > $widths[$i]) {
					$widths[$i] = $len;
				}
				$i++;
			}
		}
		// build output
		$output = [];
		foreach ($data as $row) {
			if (!\is_array($row)) {
				continue;
			}
			$i = 0;
			$msg = '';
			foreach ($row as $cell) {
				if (\is_array($cell)) {
					$cell = \implode(', ', Arrays::Flatten($cell));
				}
				$msg .= self::PadLeft($cell, $widths[$i]+2);
				$i++;
			}
			$output[] = $msg;
		}
		return $output;
	}



	######################
	## Starts/Ends With ##
	######################



	public static function StartsWith($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle)) {
			return FALSE;
		}
		$len = \mb_strlen($needle);
		if ($len > \mb_strlen($haystack)) {
			return FALSE;
		}
		if ($ignoreCase) {
			$haystack = \mb_strtolower($haystack);
			$needle   = \mb_strtolower($needle);
		}
		return \mb_substr($haystack, 0, $len) == $needle;
	}
	public static function EndsWith($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle)) {
			return FALSE;
		}
		$len = \mb_strlen($needle);
		if ($len > \mb_strlen($haystack)) {
			return FALSE;
		}
		if ($ignoreCase) {
			$haystack = \mb_strtolower($haystack);
			$needle   = \mb_strtolower($needle);
		}
		return \mb_substr($haystack, 0 - $len) == $needle;
	}



	#####################
	## Force Start/End ##
	#####################



	public static function ForceStartsWith($haystack, $prepend) {
		if (empty($haystack) || empty($prepend)) {
			return $haystack;
		}
		if (self::StartsWith($haystack, $prepend)) {
			return $haystack;
		}
		return $prepend.$haystack;
	}
	public static function ForceEndsWith($haystack, $append) {
		if (empty($haystack) || empty($append)) {
			return $haystack;
		}
		if (self::EndsWith($haystack, $append)) {
			return $haystack;
		}
		return $haystack.$append;
	}



	##############
	## Contains ##
	##############



	public static function Contains($haystack, $needle, $ignoreCase=FALSE) {
		if (empty($haystack) || empty($needle)) {
			return FALSE;
		}
		if ($ignoreCase) {
			$haystack = \mb_strtolower($haystack);
			$needle   = \mb_strtolower($needle);
		}
		return (\mb_strpos($haystack, $needle) !== FALSE);
	}



	##############
	## Get Part ##
	##############



	public static function peakPart(&$data, $patterns=' ') {
		$result = self::findPart($data, $patterns);
		// pattern not found
		if ($result == NULL) {
			return $data;
		}
		return \mb_substr($data, 0, $result['POS']);
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
		$part = \mb_substr(
			$data,
			0,
			$result['POS']
		);
		// remove part from data
		$data = self::TrimFront(
			\mb_substr(
				$data,
				$result['POS'] + \mb_strlen($result['PAT'])
			),
			$result['PAT']
		);
		return $part;
	}
	public static function findPart(&$data, ...$patterns) {
		if (empty($data)) {
			return NULL;
		}
		$data = (string) $data;
		// find next delim
		$pos   = \mb_strlen($data);
		$delim = NULL;
		foreach ($patterns as $pat) {
			if (empty($pat)) {
				continue;
			}
			$pat = (string) $pat;
			$p = \mb_strpos($data, $pat);
			if ($p === FALSE) {
				continue;
			}
			// found a sooner delim
			if ($p < $pos) {
				$pos   = $p;
				$delim = $pat;
			}
			// found delim at start
			if ($p == 0) {
				break;
			}
		}
		if ($delim == NULL) {
			return NULL;
		}
		return [
			'POS' => $pos,
			'PAT' => $delim
		];
	}



	################
	## File Paths ##
	################



	public static function BuildPath(...$parts) {
		if (empty($parts)) {
			return '';
		}
		$prepend = self::StartsWith(\reset($parts), '/');
		$append  = self::EndsWith  (\end($parts),   '/');
		$cleaned = [];
		foreach ($parts as $str) {
			if (empty($str)) {
				continue;
			}
			$trimmed = self::Trim($str, '/', '\\', ' ');
			if (empty($trimmed)) {
				continue;
			}
			$cleaned[] = $trimmed;
		}
		return
			($prepend ? '/' : '').
			\implode('/', $cleaned).
			($append  ? '/' : '');
	}



	public static function CommonPath($pathA, $pathB) {
		$pathA = (string) $pathA;
		$pathB = (string) $pathB;
		$prepend = self::StartsWith($pathA, '/')
				|| self::StartsWith($pathB, '/');
		if ($prepend) {
			$pathA = self::TrimFront($pathA, '/');
			$pathB = self::TrimFront($pathB, '/');
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
		if (\count($result) == 0) {
			return ($prepend ? '/' : '');
		}
		$result = \implode($result, '/');
		return (
			$prepend
			? self::ForceStartsWith($result, '/')
			: $result
		);
	}



}
