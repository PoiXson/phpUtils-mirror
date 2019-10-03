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
			$finder = new FileFinder();
			$finder->searchPath(Paths::entry(), 2);
//TODO
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
			$finder->searchFiles(
				'.debug',
				'debug',
				'DEBUG',
			);
			$found = $finder->find();
			if (!empty($found)) {
				self::setDebug(TRUE, 'by file');
			}
			unset($finder, $found);
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
			self::$desc = 'default';
			self::EnableDisable(FALSE);
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
			self::EnableDisable(self::$debug);
		}
	}



	public static function desc() {
		return self::$desc;
	}



	private static function EnableDisable($debug) {
		$isShell = System::isShell();
		\error_reporting(\E_ALL);
		\ini_set('display_errors', $debug   ? 'On' : 'Off');
		\ini_set('html_errors',    $isShell ? 'Off' : 'On');
		\ini_set('log_errors',     'On');
		\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
		// filp whoops
		if (\class_exists('Whoops\\Run')) {
			if ($debug) {
				// @codeCoverageIgnoreStart
				$whoops = new \Whoops\Run();
				if ($isShell) {
					$whoops->prependHandler(new \Whoops\Handler\PlainTextHandler());
				} else {
					$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler());
				}
				$whoops->register();
				// @codeCoverageIgnoreEnd
			} else {
//TODO: clear whoops handlers or unregister
			}
		}
		unset($isShell);
	}



}
