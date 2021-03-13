<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class Paths {
	private function __construct() {}

	private static $inited = FALSE;

	// local paths
	protected static $local_pwd    = NULL;
	protected static $local_entry  = NULL;
	protected static $local_project= NULL;
	protected static $local_utils  = NULL;

	protected static $extra_paths = [];



	/ **
	 * @codeCoverageIgnore
	 * /
	public static function init(): void {
		if (self::$inited) return;
		self::$inited = TRUE;
		// pwd path
		self::$local_pwd =
			\realpath(
				\getcwd()
			);
		// entry path
		{
			$local_entry = NULL;
			if (isset($_SERVER['DOCUMENT_ROOT'])) {
				$local_entry = $_SERVER['DOCUMENT_ROOT'];
			}
			// find entry path from backtrace (shell mode)
			if (empty($local_entry)) {
				$trace = \debug_backtrace();
				$last  = \end($trace);
				$local_entry = \dirname($last['file']);
			}
			self::$local_entry = \realpath($local_entry);
		}
		// project path
		self::$local_project = Strings::trim(self::$local_entry, '/public', '/scripts');
		// utils path
		self::$local_utils = __DIR__;
		// ensure all is good
		{
			$paths = self::all();
			foreach ($paths as $name => $path) {
				if (empty($path)) {
					fail("Failed to detect path: $name !",
						Defines::EXIT_CODE_INTERNAL_ERROR);
				}
			}
			unset($paths);
		}
	}



	public static function all(): array {
		$all = [];
		$all['local'] = [
			'pwd'    => self::$local_pwd,
			'entry'  => self::$local_entry,
			'project'=> self::$local_project,
			'utils'  => self::$local_utils,
		];
		return $all;
	}



	// local paths
	public static function pwd(): string {
		return self::$local_pwd;
	}
	public static function entry(): string {
		return self::$local_entry;
	}
	public static function project(): string {
		return self::$local_project;
	}
	public static function utils(): string {
		return self::$local_utils;
	}



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



}
