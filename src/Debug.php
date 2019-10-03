<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class Debug {
	private function __construct() {}

	private static $inited = FALSE;

	protected static $debug = NULL;
	protected static $desc  = NULL;



	/**
	 * @codeCoverageIgnore
	 */
	public static function init() {
		if (self::$inited) return;
		self::$inited = TRUE;
		// by define
		if (\defined('DEBUG')) {
			self::setDebug(\DEBUG, 'by define');
		}
		if (\defined('pxn\\phpUtils\\DEBUG')) {
			self::setDebug(\pxn\phpUtils\DEBUG, 'by namespace define');
		}
		// by file
		{
			$searchPaths = [
				Paths::entry(),
//TODO
			];
			$debugFiles = [
				'.debug',
				'debug',
				'DEBUG',
			];
//$common = Strings::CommonPath(
//	$entry
//	$base
//);
//$paths = [
//	$entry,
//	$base,
//	$common,
//	$common."/.."
//];
			foreach ($searchPaths as $path) {
				foreach ($debugFiles as $file) {
					if (\file_exists("$path/$file")) {
						self::setDebug(TRUE, 'by file');
						break 2;
					}
				}
			}
		}
//TODO: disable in production
/*
		// by url
		{
			$val = General::getVar('debug', 'bool');
			if ($val !== NULL) {
				// set cookie
				\setcookie(
					Defines::DEBUG_COOKIE,
					($val ? '1', '0'),
					0
				);
				self::setDebug($val, 'by url');
			} else {
				$val = General::getVar(Defines::DEBUG_COOKIE, 'bool', 'cookie');
				if ($val === TRUE) {
					self::setDebug($val, 'by cookie');
				}
			}
			unset($val);
		}
*/
		// default off
		if (self::$debug === NULL) {
			self::setDebug(FALSE, 'default');
		}
	}



	public static function debug($debug=NULL, $desc=NULL) {
		if ($debug !== NULL) {
			self::setDebug($debug, $desc);
		}
		return self::isDebug();
	}
	public static function isDebug() {
		return (self::$debug === TRUE);
	}
	public static function setDebug($debug, $desc=NULL) {
		if (!self::$inited) self::init();
		if ($debug === NULL) return;
		$last = self::$debug;
		self::$debug = ($debug !== FALSE);
		self::$desc = $desc;
		// set change
		if (self::$debug != $last) {
			if (self::$debug) {
				self::EnableDebug();
			} else {
				self::DisableDebug();
			}
		}
	}



	public static function desc() {
		return self::$desc;
	}



	private static function EnableDebug() {
		// filp whoops
		if (\class_exists('Whoops\\Run')) {
			// @codeCoverageIgnoreStart
			$whoops = new \Whoops\Run();
			if (System::isShell()) {
				$whoops->prependHandler(new \Whoops\Handler\PlainTextHandler());
			} else {
				$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler());
			}
			$whoops->register();
			// @codeCoverageIgnoreEnd
		}
	}
	private static function DisableDebug() {
//TODO: clear whoops handlers or unregister
	}



}
