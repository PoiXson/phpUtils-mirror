<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use pxn\phpUtils\exceptions\RequiredArgumentException;


final class PathUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	public static function get_filename(string $filePath): string {
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
			$trimmed = StringUtils::trim($str, '/', '\\', ' ');
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
			$pathA = StringUtils::trim_front($pathA, '/');
			$pathB = StringUtils::trim_front($pathB, '/');
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
			? StringUtils::force_starts_with(haystack: $result, prepend: '/')
			: $result
		);
	}



	public static function resolve_symlinks(string $path): string {
		$path = StringUtils::trim_end($path, '/');
		if (empty($path)) throw new RequiredArgumentException('path');
		for ($i=0; $i<10; $i++) {
			if (!\is_link($path))
				break;
			$link = \readlink($path);
			if (empty($link))
				break;
			$path = $link;
		}
		return $path;
	}



}
