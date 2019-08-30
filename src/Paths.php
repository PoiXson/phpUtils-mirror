<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils;


final class Paths {
	private function __construct() {}

	// local paths
	protected static $local_pwd    = NULL;
	protected static $local_entry  = NULL;
	protected static $local_base   = NULL;
	protected static $local_src    = NULL;
	protected static $local_utils  = NULL;
	protected static $local_portal = NULL;

	// web paths
	protected static $web_base   = NULL;
	protected static $web_images = NULL;



	public static function init() {
		// local paths
		self::$local_pwd   = \getcwd();
		self::$local_entry = @$_SERVER['DOCUMENT_ROOT'];
		self::$local_utils = __DIR__;
		// find entry path from backtrace (shell mode)
		if (empty(self::$local_entry)) {
			$trace = \debug_backtrace();
			$last  = \end($trace);
			self::$local_entry = \dirname($last['file']);
		}
		self::$local_pwd   = \realpath(self::$local_pwd);
		self::$local_entry = \realpath(self::$local_entry);
		// find src/
		{
			$search_paths = [
					self::$local_pwd,
					self::$local_pwd.'/..'
			];
			$found = FALSE;
			foreach ($search_paths as $path) {
				if (empty($path)) continue;
				$path = \realpath($path);
				if (empty($path)) continue;
				if (\is_dir("{$path}/src/")) {
					self::$local_src = "{$path}/src";
					$found = TRUE;
					break;
				}
			}
			if (!$found) {
				self::$local_src = self::$local_entry;
			}
		}
		// find base path (common between entry and src)
		{
			$A = self::$local_entry;
			$B = self::$local_src;
			$lenA = \mb_strlen($A);
			$lenB = \mb_strlen($B);
			if ($lenA < $lenB) {
				list ($A, $B) = [$B, $A];
			}
			$found = FALSE;
			for ($i=\mb_strlen($A); $i>0; $i--) {
				$A = \mb_substr($A, 0, $i);
				if ($i < \mb_strlen($B)) {
					$B = \mb_substr($B, 0, -1);
				}
				if (\mb_substr($A, 0, $i) == $B) {
					$path = \mb_substr($A, 0, $i);
					$path = Strings::TrimEnd($path, '/', '\\', ' ');
					self::$local_base = $path;
					$found = TRUE;
					break;
				}
			}
			if (!$found) {
				fail('Failed to find common base path!',
					Defines::EXIT_CODE_INTERNAL_ERROR);
			}
		}
		// find phpPortal
		if (\class_exists('\\pxn\phpPortal\\Website')) {
			$reflect = new \ReflectionClass('\pxn\phpPortal\Website');
			$path = $reflect->getFileName();
			unset($reflect);
			if (!empty($path)) {
				$pos = \mb_strrpos($path, '/');
				if ($pos !== FALSE) {
					$path = \mb_substr($path, 0, $pos);
					$path = Strings::TrimEnd($path, '/', '\\', ' ');
					self::$local_portal = $path;
				}
			}
		}
		// web paths
		self::$web_base   = '/';
		self::$web_images = '/static';
		// ensure all is good
		{
			$paths = [
					// local paths
					'local_pwd'    => self::$local_pwd,
					'local_entry'  => self::$local_entry,
					'local_base'   => self::$local_base,
					'local_src'    => self::$local_src,
					'local_utils'  => self::$local_utils,
					//'local_portal' => self::$local_portal,
					// web paths
					'web_base'     => self::$web_base,
					'web_images'   => self::$web_images
			];
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



	public static function all() {
		return [
			'local' => [
				'pwd'    => self::$local_pwd,
				'entry'  => self::$local_entry,
				'base'   => self::$local_base,
				'src'    => self::$local_src,
				'utils'  => self::$local_utils,
				'portal' => self::$local_portal,
			],
			'web' => [
				'base'   => self::$web_base,
				'images' => self::$web_images,
			],
		];
	}



	// local paths
	public static function pwd() {
		return self::$local_pwd;
	}
	public static function entry() {
		return self::$local_entry;
	}
	public static function base() {
		return self::$local_base;
	}
	public static function src() {
		return self::$local_src;
	}
	public static function utils() {
		return self::$local_utils;
	}
	public static function portal() {
		return self::$local_portal;
	}
	public static function data() {
		$basePath = self::base();
		return "$basePath/data";
	}



	// web paths
	public static function web_base() {
		return self::$web_base;
	}
	public static function web_images() {
		return self::$web_images;
	}



}
*/
