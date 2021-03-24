<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use pxn\phpUtils\exceptions\RequiredArgumentException;


final class Paths {
	/** @codeCoverageIgnore */
	private function __construct() {}

	private static bool $inited = false;

	private static array $paths = [];



	public static function init(): void {
		if (self::$inited) return;
		self::$inited = true;
		// pwd path
		self::$paths['pwd'] = \realpath( \getcwd() );
		// entry path
		{
			$path = null;
			if (isset($_SERVER['DOCUMENT_ROOT'])) {
				$path = $_SERVER['DOCUMENT_ROOT'];
			}
			// find entry path from backtrace
			// (slow but needed for shell)
			if (empty($path)) {
				$trace = \debug_backtrace();
				$last  = \end($trace);
				$path = \dirname($last['file']);
			}
			self::$paths['entry'] = \realpath($path);
		}
//TODO: is this needed?
//		// project path
//		self::$local_project = Strings::trim(self::$local_entry, '/public', '/scripts');
//TODO: is this needed?
//		// utils path
//		self::$local_utils = __DIR__;
		// ensure all is good
		self::assertPathSet('pwd');
		self::assertPathSet('entry');
	}



	protected static function assertPathSet(string $key): void {
		if (empty($key)) throw new RequiredArgumentException('key');
		if (!isset(self::$paths[$key]) || empty(self::$paths[$key])) {
			echo "Failed to detect path: $key\n";
			exit(xDef::EXIT_CODE_INTERNAL_ERROR);
		}
	}



	public static function get(string $key): string {
		if (empty($key)) throw new RequiredArgumentException('key');
		if (!isset(self::$paths[$key])) throw new \RuntimeException("Path not set: $key");
		if (empty(self::$paths[$key]))  throw new \RuntimeException("Path not set: $key");
		return self::$paths[$key];
	}
	public static function set(string $key, string $path): void {
		if (empty($key)) throw new RequiredArgumentException('key');
		// path starts with {tag}
		if (\mb_strpos(haystack: $path, needle: '{') === 0) {
			$pos = \mb_strpos(haystack: $path, needle: '}');
			if ($pos !== false) {
				$var = \mb_substr($path, 1, $pos-1);
				$path = \mb_substr($path, $pos+1);
				if ($var == 'pwd') {
					$path = Paths::pwd().$path;
				} else
				if ($var == 'entry') {
					$path = Paths::entry().$path;
				} else {
					$p = self::get($var);
					if (empty($p))
						throw new \RuntimeException("Unknown path tag: $var");
					$path = $p.$path;
				}
			}
		}
		self::$paths[$key] = $path;
	}

	public static function getAll(): array {
		return self::$paths;
	}



	public static function pwd(): string {
		self::init();
		return self::$paths['pwd'];
	}
	public static function entry(): string {
		self::init();
		return self::$paths['entry'];
	}



}
/*
	protected static $local_project= NULL;
	protected static $local_utils  = NULL;


	public static function setProjectPath(string $path) {
		self::$local_project = $path;
	}



	public static function getPath(string $key): ?string {
		if (empty($key)) throw new \NullPointerException();
		$key = \mb_strtolower($key);
		switch ($key) {
		case 'pwd':     return self::pwd();
		case 'entry':   return self::entry();
		case 'project': return self::project();
		case 'utils':   return self::utils();
		default:
		}
		if (isset(self::$extra_paths[$key]))
			return self::$extra_paths[$key];
		return null;
	}
	public static function setPath(string $key, ?string $path): void {
		if (empty($path)) {
			unset(self::$extra_paths[$key]);
		} else {
			self::$extra_paths[$key] = $path;
		}
	}
*/
