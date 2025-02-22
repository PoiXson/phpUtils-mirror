<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use \pxn\phpUtils\utils\StringUtils;
use \pxn\phpUtils\exceptions\RequiredArgumentException;


class xPaths {
	/** @codeCoverageIgnore */
	private final function __construct() {}

	private static bool $inited = false;

	// pwd
	// entry
	// common
	// twig-cache
	// static
	// html
	// data
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
		{
			$path = self::$paths['entry'];
			$remove = [
				'/public',
				'/scripts',
				'/vendor',
				'/vendor/bin',
			];
			foreach ($remove as $entry) {
				if (\str_ends_with(haystack: $path, needle: $entry))
					$path = \mb_substr($path, 0, \mb_strlen($path)-\mb_strlen($entry));
			}
			if (empty($path))
				throw new \Exception('Failed to detect common path');
			self::$paths['common'] = $path;
		}
		// twig cache
		if (\is_dir(self::$paths['common'].'/cache'))
			self::$paths['twig-cache'] = self::$paths['common'].'/cache';
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
					$path = self::pwd().$path;
				} else
				if ($var == 'entry') {
					$path = self::entry().$path;
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



	public static function ReplaceTags(string $path) {
		if (empty($path)) return $path;
		if (\mb_substr($path, 0, 1) === '{') {
			foreach (self::$paths as $key => $val) {
				if (\str_starts_with(haystack: $path, needle: '{'.$key.'}'))
					return $val.\mb_substr($path, \mb_strlen($key)+2);
			}
		}
		return $path;
	}



	public static function GetAll(): array {
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
	public static function common(): string {
		self::init();
		return self::$paths['common'];
	}



}
