<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2020
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
	protected static $local_utils  = NULL;

	// web paths
//	protected static $web_base   = NULL;
//	protected static $web_images = NULL;



	/**
	 * @codeCoverageIgnore
	 */
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
//		// ensure in proper dir
//		\chdir(self::entry());
	}



	public static function all(): array {
		$all = [];
		$all['local'] = [
			'pwd'    => self::$local_pwd,
			'entry'  => self::$local_entry,
			'utils'  => self::$local_utils,
		];
//		if (System::isWeb()) {
//			$all['web'] = [
//				'base'   => self::$web_base,
//				'images' => self::$web_images,
//			];
//		}
		return $all;
	}



	// local paths
	public static function pwd(): string {
		return self::$local_pwd;
	}
	public static function entry(): string {
		return self::$local_entry;
	}
	public static function utils(): string {
		return self::$local_utils;
	}



//	// web paths
//	public static function web_base() {
//		return self::$web_base;
//	}
//	public static function web_images() {
//		return self::$web_images;
//	}



}
