<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use \pxn\phpUtils\tools\FileFinder;
use \pxn\phpUtils\utils\SystemUtils;

use \Kint\Kint;


final class Debug {
	/** @codeCoverageIgnore */
	private function __construct() {}

	private static bool $inited = false;

	private static ?bool $enabled = null;
	private static ?string $desc  = null;



//TODO: enable by remote ip
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
		// by run mode
		$sapi = \php_sapi_name();
		switch ($sapi) {
			case 'cli-server': self::debug(true, 'php internal server'); break;
			case 'phpdbg':     self::debug(true, 'by phpdbg');           break;
			default: break;
		}
		// .debug file
		{
			$finder = new FileFinder();
			$finder->search_path_parents(path: xPaths::pwd(), depth: 2);
			$finder->search_files('debug', '.debug');
			$found = $finder->find();
			if (!empty($found)) {
				self::debug(true, "by $found file");
			}
		}
		// filp whoops
		if (self::debug()) {
			if (\class_exists('Whoops\\Run')) {
				$whoops = new \Whoops\Run();
				$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
				$whoops->register();
			}
		}
		// default
		if (self::$enabled === null)
			self::debug(false, 'default');
	}



	public static function debug(?bool $enable=null, ?string $desc=null): bool {
		self::init();
		if ($enable !== null) {
			if (self::$enabled !== $enable) {
				self::$enabled = $enable;
				self::EnableDisable(self::$enabled);
				self::desc($desc);
			}
		}
		// default
		if (self::$enabled === null)
			return false;
		return self::$enabled;
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



	public static function dd($var=null): void {
		if ($var !== null) {
			if (self::hasKint())
				\Kint\Renderer\RichRenderer::$folder = false;
			self::dump($var);
		}
		exit(1);
	}

	public static function dump($var): void {
		if (self::hasKint()) {
			Kint::dump($var);
		} else {
			\var_dump($var);
		}
	}

	public static function trace(): void {
		if (self::hasKint()) {
			Kint::trace();
		} else {
			\debug_print_backtrace();
		}
	}



	private static function EnableDisable(bool $enable): void {
		$isShell = SystemUtils::isShell();
		\error_reporting(\E_ALL);
		\ini_set('display_errors', $enable  ? 'On' : 'Off');
		\ini_set('html_errors',    $isShell ? 'Off' : 'On');
		\ini_set('log_errors',     'On');
		\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
		if ($enable && self::hasKint()) {
			Kint::$expanded = true;
			Kint::$aliases[] = 'dd';
			Kint::$aliases[] = 'dump';
		}
	}



	public static function hasKint(): bool {
		return \class_exists('Kint\\Kint');
	}



}
