<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
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

	public const SEARCH_DEPTH = 3;

	private static bool $inited = false;

	private static ?bool $enabled = null;
	private static array $desc = [];



//TODO: enable by remote ip
	public static function init(): void {
		if (self::$inited) return;
		self::$inited = true;
		// by define
		if (\defined('DEBUG')) {
			self::debug(true, 'by-define');
		} else
		if (\defined('pxn\\phpUtils\\DEBUG')) {
			self::debug(true, 'by-phpUtils-define');
		}
		// by run mode
		$sapi = \php_sapi_name();
		switch ($sapi) {
			case 'cli-server': self::debug(true, 'php-internal-server'); break;
			case 'phpdbg':     self::debug(true, 'by-phpdbg');           break;
			default: break;
		}
		// .debug file
		{
			$finder = new FileFinder();
			$finder->search_path_parents(path: xPaths::pwd(), depth: self::SEARCH_DEPTH);
			$finder->search_files('debug', '.debug');
			$found = $finder->find();
			if (!empty($found)) {
				self::debug(true, "by-file-$found");
			}
		}
		if (self::debug()) {
			// filp whoops
			if (\class_exists('Whoops\\Run')) {
				$whoops = new \Whoops\Run();
				$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
				$whoops->register();
			}
			// collision
			if (\class_exists('NunoMaduro\\Collision\\Provider')) {
				$collision = new \NunoMaduro\Collision\Provider();
				$collision->register();
			}
		}
		// default
		if (self::$enabled === null)
			self::debug(false);
	}
	public static function Reset(): void {
		self::$inited  = false;
		self::$enabled = null;
		self::$desc    = [];
	}



	public static function debug(?bool $enable=null, ?string $desc=null): bool {
		self::init();
		if ($enable !== null) {
			if (self::$enabled !== $enable) {
				self::$enabled = $enable;
				self::EnableDisable(self::$enabled);
			}
			self::desc($desc);
		}
		// default
		if (self::$enabled === null)
			return false;
		return self::$enabled;
	}

	public static function desc(?string $desc=null): ?string {
		if (!empty($desc)) {
			if (!\in_array(needle: $desc, haystack: self::$desc))
				self::$desc[] = $desc;
		}
		if (!self::debug()) return null;
		return \implode(' ; ', self::$desc);
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
//TODO: improve this - displays wrong source line
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
