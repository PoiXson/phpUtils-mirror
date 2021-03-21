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

	private static bool $inited = false;

	private static ?bool $debug = null;
	private static ?string $desc = null;



//TODO: enable by remote ip
//TODO: disable in production
	public static function init(): void {
		if (self::$inited) return;
		self::$inited = true;
		// by define
		if (\defined('DEBUG')) {
			self::debug(true, 'by define');
		} else
		if (\defined('pxn\\phpUtils\\DEBUG')) {
			self::debug(true, 'by phpUtils define');
		}
		// .debug file
		$paths = [
			Paths::pwd().'/',
			Paths::pwd().'/../',
		];
		$files = [
			'debug',
			'.debug',
		];
		foreach ($paths as $path) {
			foreach ($files as $file) {
				if (\is_file($path.$file)) {
					self::debug(true, "by $file file");
					break 2;
				}
			}
		}
	}



	private static function EnableDisable(bool $debug): void {
//TODO
//		$isShell = SystemUtils::isShell();
$isShell = false;
		\error_reporting(\E_ALL);
		\ini_set('display_errors', $debug   ? 'On' : 'Off');
		\ini_set('html_errors',    $isShell ? 'Off' : 'On');
		\ini_set('log_errors',     'On');
		\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
		unset($isShell);
	}



	public static function debug(?bool $enable=null, ?string $desc=null): bool {
		if ($enable !== null) {
			if ($enable !== self::$debug) {
				self::$debug = $enable;
				self::EnableDisable(self::$enable);
				self::addDesc($desc);
			}
		}
		// default
		if (self::$debug === null)
			return false;
		return self::$debug;
	}



	public static function desc(?string $desc=null): ?string {
		if (!empty($desc)) {
			if (!empty(self::$desc))
				self::$desc .= '; ';
			self::$desc .= $desc;
		}
		if (self::debug())
			return (empty(self::$desc) ? '' : self::$desc);
		return null;
	}



	public static function dd($data=null): void {
		if ($data !== null)
			\print_r($data);
		exit(1);
	}



}
