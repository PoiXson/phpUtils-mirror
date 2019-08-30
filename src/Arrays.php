<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils;


final class Arrays {
	private final function __construct() {}



	public static function Flatten(...$data) {
		$result = [];
		\array_walk_recursive(
			$data,
			function($arr) use (&$result) {
				$result[] = $arr;
			}
		);
		return $result;
	}



	public static function TrimFlat(...$data) {
		$result = [];
		\array_walk_recursive(
			$data,
			function($arr) use (&$result) {
				if ($arr === NULL || $arr === '')         return;
				if (\is_array($arr) && \count($arr) == 0) return;
				$result[] = $arr;
			}
		);
		return $result;
	}



	public static function Trim(&$data) {
		if ($data === NULL)
			return;
		if (!\is_array($data)) {
			$data = [$data];
		}
		foreach ($data as $k => $v) {
			if ($v === NULL || $v === ''
			|| (\is_array($v) && \count($v) == 0) ) {
				unset($data[$k]);
			}
		}
	}



	// make array if not already
	public static function MakeArray($data) {
		if ($data === NULL)
			return NULL;
		if (\is_array($data))
			return $data;
		$str = (string) $data;
		if (empty($str))
			return [];
		return [ $str ];
	}



	// explode() with multiple delimiters
	public static function Explode($data, ...$delims) {
		if (\is_array($data))
			return $data;
		// default delims
		if (\count($delims) == 0)
			$delims = [ ' ', ',', ';', "\t", "\r", "\n" ];
		$data = (string) $data;
		$first_delim = NULL;
		foreach ($delims as $v) {
			if (empty($v)) continue;
			$first_delim = $v;
			break;
		}
		if (empty($first_delim))
			throw new \RuntimeException('Delim argument is required!');
		foreach ($delims as $v) {
			if (empty($v)) continue;
			if ($v == $first_delim) continue;
			$data = \str_replace($v, $first_delim, $data);
		}
		$result = \explode($first_delim, $data);
		self::Trim($result);
		return $result;
	}



}
*/
