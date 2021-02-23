<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class SanUtils {
	private final function __construct() {}



	public static function AlphaNum(string $str): string {
		return \preg_replace("/[^a-z0-9]+/i", '', $str);
	}
	public static function AlphaNumDash(string $str): string {
		return \preg_replace("/[^a-z0-9_-]+/i", '', $str);
	}
	public static function AlphaNumSpaces(string $str): string {
		return \preg_replace("/[^\sa-z0-9-_]+/i", '', $str);
	}
	public static function AlphaNumFile(string $str): string {
		$filter = '[:alnum:]\(\)\_\.\,\'\&\?\+\-\=\!';
		return \preg_replace('/[^'.$filter.']/', '', $str);
	}
	public static function Base64(string $str): string {
		return \preg_replace("/[^a-z0-9=]+/i", '', $str);
	}



	public static function isAlphaNum(string $str): bool {
		$str = (string) $str;
		$compare = self::AlphaNum($str);
		return ($compare === $str);
	}
	public static function isAlphaNumDash(string $str): bool {
		$str = (string) $str;
		$compare = self::AlphaNumDash($str);
		return ($compare === $str);
	}
	public static function isAlphaNumSpaces(string $str): bool {
		$str = (string) $str;
		$compare = self::AlphaNumSpaces($str);
		return ($compare === $str);
	}
	public static function isAlphaNumFile(string $str): bool {
		$str = (string) $str;
		$compare = self::AlphaNumFile($str);
		return ($compare === $str);
	}
	public static function isBase64(string $str): bool {
		$str = (string) $str;
		$compare = self::Base64($str);
		return ($compare === $str);
	}



//TODO: is this needed?
/*
	public static function SafePath($path) {
		$path = Strings::Trim($path, ' ');
		if (empty($path))
			$path = \getcwd();
		$temp = \realpath($path);
		if (empty($temp))
			throw new \Exception(\sprintf('Path not found! %s', $path));
		$path = $temp;
		unset($temp);
		return $path.'/';
	}
	public static function SafeDir($dir) {
		$dir = Strings::Trim($dir, ' ', '/');
		if (empty($dir))
			throw new \Exception('dir argument is required');
		$temp = self::AlphaNumSafe($dir);
		if ($dir != $temp) {
			throw new \Exception(sprintf(
				'dir argument contains illegal characters! %s != %s',
				$dir,
				$temp
			));
		}
		unset($temp);
		if (Strings::StartsWith($dir, '.'))
			throw new \Exception('Invalid dir argument, cannot start with .');
		if (\mb_strpos($dir, '..') !== FALSE)
			throw new \Exception('Invalid dir argument, cannot contain ..');
		return $dir.'/';
	}
*/



}
