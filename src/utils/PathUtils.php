<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use \pxn\phpUtils\pxnDefines as xDef;


final class PathUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	public static function FileName(string $path): string {
		$pos = \mb_strrpos($path, '/');
		if ($pos === false)
			return $path;
		return \mb_substr($path, $pos+1);
	}



	public static function TrimPath(string $path, string $trim): string|false {
		if (empty($trim))
			throw new \RuntimeException('trim argument is empty');
		if (!\str_starts_with($path, $trim))
			return false;
		$path = \mb_substr($path, \mb_strlen($trim));
		while (\mb_substr($path, 0, 1) == '/')
			$path = \mb_substr($path, 1);
		return $path;
	}



	public static function NormPath(string $path): string {
		$path = \preg_replace('/[^\x20-\x7E]/', '', $path);
		$path = \str_replace(['\\', '/'], xDef::DIR_SEP, $path);
		$isAbs = \str_starts_with($path, '/');
		$parts = \explode('/', $path);
		$result = [];
		foreach ($parts as $part) {
			if (empty($part) || $part === '.') {
			} else
			if ($part !== '..') {
				\array_push($result, $part);
			} else
			if (!empty($result)) {
				\array_pop($result);
			}
		}
		return ($isAbs ? xDef::DIR_SEP : '') . \implode('/', $result);
	}



}
