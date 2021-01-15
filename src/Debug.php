<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
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
	public static function init(): void {
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
			$val = GeneralUtils::getVar('debug', 'bool');
			if ($val !== NULL) {
				// set cookie
				\setcookie(
					Defines::DEBUG_COOKIE,
					($val ? '1', '0'),
					0
				);
				self::setDebug($val, 'by url');
			} else {
				$val = GeneralUtils::getVar(Defines::DEBUG_COOKIE, 'bool', 'cookie');
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



	public static function debug(?bool $debug=NULL, ?string $desc=NULL): bool {
		$last = self::isDebug();
		if ($debug !== NULL) {
			self::setDebug($debug, $desc);
		}
		return $last;
	}
	public static function isDebug(): bool {
		return (self::$debug === TRUE);
	}
	public static function setDebug(bool $debug, ?string $desc=NULL): void {
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



	public static function desc(?string $desc=NULL): string {
		$last = self::getDesc();
		if ($desc !== NULL) {
			self::setDesc($desc);
		}
		return $last;
	}
	public static function getDesc(): string {
		return self::$desc;
	}
	public static function setDesc(string $desc): void {
		self::$desc = $desc;
	}



	private static function EnableDisable(bool $debug): void {
		$isShell = SystemUtils::isShell();
		\error_reporting(\E_ALL);
		\ini_set('display_errors', $debug   ? 'On' : 'Off');
		\ini_set('html_errors',    $isShell ? 'Off' : 'On');
		\ini_set('log_errors',     'On');
		\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
		unset($isShell);
	}



}
