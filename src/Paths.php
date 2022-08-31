<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use \pxn\phpUtils\utils\StringUtils;
use \pxn\phpUtils\exceptions\RequiredArgumentException;


class Paths {
	/** @codeCoverageIgnore */
	private final function __construct() {}

	private static bool $inited = false;

	// pwd
	// entry
	public static array $paths = [];



	/** @codeCoverageIgnore */
	public static function init(): void {
		if (self::$inited) return;
		self::$inited = true;
		// pwd path
		self::$paths['pwd'] = \realpath( \getcwd() );
		// entry path
		{
			$path = null;
			if (isset($_SERVER['DOCUMENT_ROOT']))
				$path = $_SERVER['DOCUMENT_ROOT'];
			// find entry path from backtrace
			// (slow but needed for shell)
			if (empty($path)) {
				$trace = \debug_backtrace();
				$last  = \end($trace);
				$path  = \dirname($last['file']);
			}
			self::$paths['entry'] = \realpath($path);
		}
		// common root
		self::$paths['common'] = StringUtils::trim(self::$paths['entry'], '/public', '/scripts');
		// ensure all is good
		self::assert('pwd');
		self::assert('entry');
		self::assert('common');
		self::addIfExists('static', self::$paths['entry'].'/static');
		self::addIfExists('html',   self::$paths['common'].'/html');
		self::addIfExists('data',   self::$paths['common'].'/data');
	}



	public static function addIfExists(string $key, string $path): bool {
		if (empty($key)) throw new RequiredArgumentException('key');
		if (\is_dir($path)) {
			self::$paths[$key] = $path;
			return true;
		}
		return false;
	}



	public static function assert(string $key): void {
		if (empty($key)) throw new RequiredArgumentException('key');
		if (!isset(self::$paths[$key]) || empty(self::$paths[$key]))
			throw new \RuntimeException("Path not set: $key");
		$path = self::get($key);
		if (!\is_dir($path))
			throw new \RuntimeException("Path not found: $path");
	}



	public static function get(string $key): string {
		if (empty($key))                throw new RequiredArgumentException('key');
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
				$var  = \mb_substr($path, 1, $pos-1);
				$path = \mb_substr($path, $pos+1);
				if ($var == 'pwd') {
					$path = Paths::pwd().$path;
				} else
				if ($var == 'entry') {
					$path = Paths::entry().$path;
				} else {
					if (!isset(self::$paths[$var]))
						throw new \RuntimeException("Unknown path tag: $var");
					$p = self::get($var);
					if (empty($p))
						throw new \RuntimeException("Unknown path tag: $var");
					$path = $p.$path;
				}
			}
		}
		self::$paths[$key] = $path;
	}



	public static function create(string $key, int $perms=0700): bool {
		$path = self::get($key);
		// path already exists
		if (\is_dir($path))
			return false;
		// create path
		\mkdir(
			directory:   $path,
			permissions: $perms,
			recursive:   true
		);
		return true;
	}



	public static function pwd(): string {
		self::init();
		return self::$paths['pwd'];
	}
	public static function entry(): string {
		self::init();
		return self::$paths['entry'];
	}
	public static function common(): string {
		self::init();
		return self::$paths['common'];
	}



}
