<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils;

use pxn\phpUtils\Strings;


final class Paths {
	private function __construct() {}

	// local paths
	protected static $local_pwd   = '';
	protected static $local_entry = '';
	protected static $local_base  = '';
	protected static $local_src   = '';
	protected static $local_utils = '';

	// web paths
	protected static $web_base   = '';
	protected static $web_images = '';



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
			$lenA = \strlen($A);
			$lenB = \strlen($B);
			if ($lenA < $lenB) {
				list ($A, $B) = [$B, $A];
			}
			$found = FALSE;
			for ($i=\strlen($A); $i>0; $i--) {
				$A = \substr($A, 0, $i);
				if ($i < \strlen($B)) {
					$B = \substr($B, 0, -1);
				}
				if (\substr($A, 0, $i) == $B) {
					$path = \substr($A, 0, $i);
					$path = Strings::TrimEnd($path, '/', '\\', ' ');
					self::$local_base = $path;
					$found = TRUE;
					break;
				}
			}
			if (!$found) {
				fail('Failed to find common base path!');
				exit();
			}
		}
		// web paths
		self::$web_base   = '/';
		self::$web_images = '/static';
		// ensure all is good
		{
			$paths = [
					// local paths
					'local_pwd'   => self::$local_pwd,
					'local_entry' => self::$local_entry,
					'local_base'  => self::$local_base,
					'local_src'   => self::$local_src,
					'local_utils' => self::$local_utils,
					// web paths
					'web_base'    => self::$web_base,
					'web_images'  => self::$web_images
			];
			foreach ($paths as $name => $path) {
				if (empty($path)) {
					fail("Failed to detect path: {$name} !");
					exit(1);
				}
			}
			unset($paths);
		}
	}



	public static function pwd() {
		return self::$local_pwd;
	}
	public static function utils() {
		return self::$local_utils;
	}
	public static function base() {
		return self::$local_base;
	}
	public static function site() {
fail ('Paths::site() is unfinished!');
exit(1);
//		// find site path
//		if (empty(self::$local_site)) {
//self::$site = self::pwd().DIR_SEP.
//  \psm\portal::get()->getWebsite()->siteName();
//			$website = \pxn\phpUtils\portal\Website::peak();
//			// website object not loaded yet
//			if ($website === NULL) {
//TODO:
//				echo '<p>Website object not loaded yet!</p>'.\CRLF;
//				exit(1);
//			}
//TODO:
//return NULL;
//			$path = self::pwd().\DIR_SEP.
//				$website->getName();
//			unset($path);
//		}
//		return self::$local_site;
	}



	public static function getTwigCachePath() {
		$path = Config::get(Defines::KEY_TWIG_CACHE_PATH);
//		if (empty($path)) {
			$path = self::base().'/.cache';
//		}
		if (!\is_dir($path)) {
			\mkdir($path, 0700);
		}
		return $path;
	}



}
