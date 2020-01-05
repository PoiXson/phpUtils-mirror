<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class Strings {
	private final function __construct() {}

	const DEFAULT_TRIM_CHARS = [ ' ', "\t", "\r", "\n" ];



//TODO: is this useful?
/*
	public static function mb_ucfirst($str, $full=TRUE) {
		return \mb_strtoupper(\mb_substr($str, 0, 1)).
			($full
				? \strtolower(\mb_substr($str, 1))
				: \mb_substr($str, 1)
			);
	}
*/



/*
//TODO: untested
//	public static function isBase64($str) {
//		return ( FALSE != preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $this->getValue()) );
//	}
*/



	#################
	## Trim String ##
	#################



	public static function Trim(string $text, string...$remove): string {
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
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
			}
			while (\in_array(\mb_substr($text,-1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
			}
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						$more = TRUE;
					}
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						$more = TRUE;
					}
					if ($more)
						break;
				}
			} while ($more);
		}
		return $text;
	}
	public static function TrimFront(string $text, string...$remove): string {
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
			while (\in_array(\mb_substr($text, 0, 1), $remove)) {
				$text = \mb_substr($text, 1);
			}
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0, $len) == $str) {
						$text = \mb_substr($text, $len);
						$more = TRUE;
					}
					if ($more)
						break;
				}
			} while ($more);
		}
		return $text;
	}
	public static function TrimEnd(string $text, string...$remove): string {
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
			while (\in_array(\mb_substr($text, -1, 1), $remove)) {
				$text = \mb_substr($text, 0, -1);
			}
		// trim variable length strings
		} else {
			do {
				$more = FALSE;
				foreach ($remove as $str) {
					$len = \mb_strlen($str);
					if ($len == 0)
						continue;
					while (\mb_substr($text, 0 - $len, $len) == $str) {
						$text = \mb_substr($text, 0, 0 - $len);
						$more = TRUE;
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
	public static function TrimQuotes(string $text): string {
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



//TODO: is this useful?
/*
	public static function MergeDuplicates($text, ...$search) {
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
*/



//TODO
/*
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
//TODO: is this needed?
//		Arrays::TrimFlat($lineEndings);
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
				$char = \mb_substr($text, $i, 1);
				if (\in_array($char, $lineEndings)) {
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
*/



	##################
	## Pad to Width ##
	##################



	public static function PadLeft(string $str, string $size, string $char=' '): string {
		$padding = self::getPadding($str, $size, $char);
		return $padding.$str;
	}
	public static function PadRight(string $str, string $size, string $char=' '): string {
		$padding = self::getPadding($str, $size, $char);
		return $str.$padding;
	}
	private static function getPadding(string $str, string $size, string $char=' '): string {
		if (empty($char)) {
			$char = ' ';
		}
		$len = $size - \mb_strlen($str);
		if ($len < 0)
			return '';
		$charLen = \mb_strlen($char);
		if ($charLen > 1) {
			$padding = \str_repeat(
				$char,
				\floor($len / $charLen)
			);
			$padding .= \mb_substr(
				$char,
				0,
				$len % $charLen
			);
			return $padding;
		}
		return \str_repeat($char, $len);
	}
	public static function PadCenter(string $str, string $size, string $char=' '): string {
		if (empty($char)) {
			$char = ' ';
		}
		$len = $size - \mb_strlen($str);
		if ($len < 0)
			return $str;
		$padLeft  = \floor( ((double) $len) / 2.0);
		$padRight = \ceil(  ((double) $len) / 2.0);
		return \str_repeat($char, $padLeft) . $str . \str_repeat($char, $padRight);
	}



//TODO: is this useful?
/*
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
*/



	######################
	## Starts/Ends With ##
	######################



	public static function StartsWith(string $haystack, string $needle, bool $ignoreCase=FALSE): bool {
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
	public static function EndsWith(string $haystack, string $needle, bool $ignoreCase=FALSE): bool {
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



	public static function ForceStartsWith(string $haystack, string $prepend): string {
		if (empty($haystack) || empty($prepend)) {
			return $haystack;
		}
		if (self::StartsWith($haystack, $prepend)) {
			return $haystack;
		}
		return $prepend.$haystack;
	}
	public static function ForceEndsWith(string $haystack, string $append): string {
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



	public static function Contains(string $haystack, string $needle, bool $ignoreCase=FALSE): bool {
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



//TODO: is this useful?
/*
	public static function peakPart(&$data, $patterns=' ') {
		$result = self::findPart($data, $patterns);
		// pattern not found
		if ($result == NULL)
			return $data;
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
		if (empty($data))
			return NULL;
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
		if ($delim == NULL)
			return NULL;
		return [
			'POS' => $pos,
			'PAT' => $delim
		];
	}
*/



	################
	## File Paths ##
	################



	public static function getFileName($filePath) {
		if (empty($filePath)) {
			return '';
		}
		$pos = \mb_strrpos($filePath, '/');
		if ($pos === FALSE) {
			return $filePath;
		}
		return \mb_substr($filePath, $pos+1);
	}



	public static function BuildPath(string...$parts): string {
		if (empty($parts))
			return '';
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



	public static function getAbsolutePath(string $path): string {
		if (empty($path)) {
			return Paths::pwd();
		}
		$first = \mb_substr($path, 0, 1);
		if ($first == '/') {
			return $path;
		}
		if ($first == '.') {
			$path = \mb_substr($path, 1);
		}
		return self::BuildPath(
			Paths::pwd(),
			$path
		);
	}



	public static function CommonPath(string $pathA, string $pathB): string {
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
