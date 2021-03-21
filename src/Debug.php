<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use pxn\phpUtils\tools\FileFinder;
use pxn\phpUtils\utils\SystemUtils;


final class Debug {
	private function __construct() {}

	private static bool $inited = false;

	private static ?bool   $value = null;
	private static ?string $desc  = null;



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
		{
			$finder = new FileFinder();
			$finder->search_path_parents(path: Paths::pwd(), depth: 2);
			$finder->search_files('debug', '.debug');
			$found = $finder->find();
			if (!empty($found)) {
				self::debug(true, "by $found file");
			}
		}
	}



	private static function EnableDisable(bool $enable): void {
		$isShell = SystemUtils::isShell();
		\error_reporting(\E_ALL);
		\ini_set('display_errors', $enable  ? 'On' : 'Off');
		\ini_set('html_errors',    $isShell ? 'Off' : 'On');
		\ini_set('log_errors',     'On');
		\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
		unset($isShell);
	}



	public static function debug(?bool $enable=null, ?string $desc=null): bool {
		self::init();
		if ($enable !== null) {
			if (self::$value !== $enable) {
				self::$value = $enable;
				self::EnableDisable(self::$value);
				self::desc($desc);
			}
		}
		// default
		if (self::$value === null)
			return false;
		return self::$value;
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
