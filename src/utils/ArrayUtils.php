<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class Arrays {
	private final function __construct() {}

	const DEFAULT_EXPLODE_DELIMS = [ ' ', ',', ';', "\t", "\r", "\n" ];



	public static function Flatten(...$data): array {
		$result = [];
		\array_walk_recursive(
			$data,
			function($arr) use (&$result) {
				$result[] = $arr;
			}
		);
		return $result;
	}



	public static function TrimFlat(...$data): array {
		$result = [];
		\array_walk_recursive(
			$data,
			function($arr) use (&$result) {
				if ($arr === null || $arr === '')         return;
				if (\is_array($arr) && \count($arr) == 0) return;
				$result[] = $arr;
			}
		);
		return $result;
	}



//TODO: is this useful?
/*
	public static function Trim(&$data) {
		if ($data === null)
			return;
		if (!\is_array($data)) {
			$data = [$data];
		}
		foreach ($data as $k => $v) {
			if ($v === null || $v === ''
			|| (\is_array($v) && \count($v) == 0) ) {
				unset($data[$k]);
			}
		}
	}
*/



	// make array if not already
	public static function MakeArray($data): array {
		if ($data === null)
			return [];
		if (\is_array($data))
			return $data;
		return [ $data ];
	}



	// explode() with multiple delimiters
	public static function Explode($data, string...$delims): array {
		$data = self::MakeArray($data);
		// default delims
		if (\count($delims) == 0)
			$delims = self::DEFAULT_EXPLODE_DELIMS;
		foreach ($delims as $delim) {
			if (empty($delim)) continue;
			$result = [];
			foreach ($data as $part) {
				if (empty($part)) continue;
				$pos = \mb_strpos($part, $delim);
				if ($pos === false) {
					$result[] = $part;
				} else {
					$array = \explode($delim, $part);
					foreach ($array as $str) {
						if (!empty($str))
							$result[] = $str;
					}
				}
			}
			$data = $result;
		}
		return $data;
	}



}
