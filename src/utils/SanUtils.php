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
	/** @codeCoverageIgnore */
	private final function __construct() {}



	public static function AlphaNum(string $str): string {
		return \preg_replace(pattern: '/[^a-z0-9]+/i',     replacement: '', subject: $str);
	}
	public static function AlphaNumDash(string $str): string {
		return \preg_replace(pattern: '/[^a-z0-9_-]+/i',   replacement: '', subject: $str);
	}
	public static function AlphaNumSpaces(string $str): string {
		return \preg_replace(pattern: '/[^\sa-z0-9-_]+/i', replacement: '', subject: $str);
	}
	public static function AlphaNumFile(string $str): string {
		return \preg_replace(pattern: '/[^[:alnum:]\(\)\_\.\,\'\&\?\+\-\=\!]/', replacement: '', subject: $str);
	}
	public static function Base64(string $str): string {
		return \preg_replace(pattern: '/[^a-z0-9=]+/i',    replacement: '', subject: $str);
	}



	public static function isAlphaNum(string $str): bool {
		return ($str === self::AlphaNum($str));
	}
	public static function isAlphaNumDash(string $str): bool {
		return ($str === self::AlphaNumDash($str));
	}
	public static function isAlphaNumSpaces(string $str): bool {
		return ($str === self::AlphaNumSpaces($str));
	}
	public static function isAlphaNumFile(string $str): bool {
		return ($str === self::AlphaNumFile($str));
	}
	public static function isBase64(string $str): bool {
		return ($str === self::Base64($str));
	}
	public static function isVersion(string $str): bool {
		return (\preg_match(pattern: '/[^0-9.]+/', subject: $str) === 0);
	}



}
