<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

//use \pxn\phpUtils\Paths;
//use \pxn\phpUtils\pxnDefines as xDef;


final class SystemUtils {
	/** @codeCoverageIgnore */
	private function __construct() {}

	private static ?bool $isCLI = null;



//TODO: logging
	protected static function DetectCLI(): bool {
		if (self::$isCLI !== null)
			return self::$isCLI;
		$sapi = \php_sapi_name();
		if (\str_starts_with(haystack: $sapi, needle: 'apache'))
			return false;
		switch ($sapi) {
			case 'fpm-fcgi':
			case 'cli-server':
				return false;
			case 'cli':
				return true;
			default: break;
		}
		if ( isset($_SERVER['SHELL'])           && !empty($_SERVER['SHELL'])           )
			return true;
		if ( isset($_SERVER['REDIRECT_STATUS']) && !empty($_SERVER['REDIRECT_STATUS']) )
			return false;
		if ( isset($_SERVER['HTTP_HOST'])       && !empty($_SERVER['HTTP_HOST'])       )
			return false;
//TODO
		echo "Unknown web/shell mode\n";
		exit(xDef::EXIT_CODE_GENERAL);
	}

	public static function isShell(): bool {
		if (self::$isCLI === null)
			self::$isCLI = self::DetectCLI();
		return self::$isCLI;
	}
	public static function isWeb(): bool {
		if (self::$isCLI === null)
			self::$isCLI = self::DetectCLI();
		return ! self::isShell();
	}



	/** @codeCoverageIgnore */
	public static function AssertShell(): void {
		if (!self::isShell()) {
//TODO
			echo "This is a CLI script\n";
			exit(xDef::EXIT_CODE_GENERAL);
		}
	}
	/** @codeCoverageIgnore */
	public static function AssertWeb(): void {
		if (!self::isWeb()) {
//TODO
			echo "This script is a website\n";
			exit(xDef::EXIT_CODE_GENERAL);
		}
	}



	/** @codeCoverageIgnore */
	public static function AssertLinux(): void {
		$os = \PHP_OS;
		if ($os != 'Linux') {
//TODO
			echo "Sorry, only Linux is currently supported. Contact the developer if you'd like to help add support for $os\n";
			exit(xDef::EXIT_CODE_GENERAL);
		}
	}



	###########
	## Shell ##
	###########



	public static function isUsrInstalled(): bool {
		return \str_starts_with(haystack: Paths::entry(), needle: '/usr/');
	}



	public static function GetUser(): ?string {
		if (isset($_SERVER['USER']))
			if (!empty($_SERVER['USER']))
				return $_SERVER['USER'];
		$who = \exec('whoami');
		if (empty($who))
			return null;
		return $who;
	}
	public static function DenySuperUser(): void {
		if (self::isSuperUser()) {
//TODO
			echo "Cannot run this script as super user\n";
			exit(xDef::EXIT_CODE_NOPERM);
		}
	}
	public static function isSuperUser(?string $who=null): bool {
		if (empty($who))
			$who = self::getUser();
		$who = \strtolower($who);
		switch ($who) {
			case 'root':
			case 'system':
			case 'admin':
			case 'administrator':
				return true;
			default: break;
		}
		return false;
	}



/*
	public static function exec($command): bool {
		$command = \trim($command);
		if (empty($command))
			return false;
		$log = self::log();
		// run the command
		\exec($command, $output, $return);
		// command failed
		if ($return !== 0) {
			$log->warning("Command failed: $command");
		}
		// log output
		if (!empty($output) && \is_array($output)) {
			foreach ($output as $line) {
				if (empty($line)) continue;
				$log->info($line);
			}
		}
		return ($return === 0);
	}



	#################
	## File System ##
	#################



	public static function mkdir($dir, $mode=644): void {
		if (empty($dir))     throw new \Exception('dir argument is required');
		if (!\is_int($mode)) throw new \Exception('mode argument must be an integer!');
		$oct = \octdec($mode);
		$log = self::log();
		// prepend cwd
		if (!Strings::StartsWith($dir, '/')) {
			$dir = Strings::build_path(\getcwd(), $dir);
		}
		// dir already exists
		if (\is_dir($dir)) {
			$log->debug("Found existing directory: $dir");
			return;
		}
		// build paths array
		$path  = '/';
		$array = \explode('/', $dir);
		$nodes = [];
		$index = 0;
		foreach ($array as $part) {
			if (empty($part)) continue;
			$path .= San::SafeDir($part);
			$nodes[$index++] = $path;
		}
		unset($path, $array);
		$count = \count($nodes);
		// find first not existing
		for ($start = 0; $start < $count; $start++) {
			if (!\is_dir($nodes[$start]))
				break;
		}
		// all exist
		if ($start == $count)
			return;
		// create directories
		for ($index = $start; $index < $count; $index++) {
			$path = $nodes[$index];
			$log->debug("Creating: $path");
			\mkdir($path, $oct);
		}
//		\clearstatcache(true, $dir);
		// ensure created directories exist
		if (!\is_dir($dir)) throw new \Exception("Failed to create directory: $dir");
	}
	public static function rmdir($dir): void {
		if (empty($dir)) throw new \Exception('dir argument is required');
		$log = self::log();
		// ensure exists
		$temp = \realpath($dir);
		if (empty($temp)) throw new \Exception("dir not found, cannot delete! $dir");
		$dir = $temp;
		unset($temp);
		\clearstatcache(true, $dir);
		if (!\is_dir($dir)) throw new \Exception("dir argument is not a directory! $dir");
		if ($dir == '/')    throw new \Exception('cannot delete / directory!');
		// list contents
		$array = \scandir($dir);
		if ($array == false) throw new \Exception("Failed to list contents of directory: $dir");
		foreach ($array as $entry) {
			if ($entry == '.' || $entry == '..')
				continue;
			$full = Strings::build_path($dir, $entry);
			if (\is_dir($full)) {
				self::rmDir($full);
			} else {
				$log->debug("Removing file: $entry");
				\unlink($full);
			}
//			\rmdir($full);
//			$log->debug("Removing directory: $entry");
		}
		\rmdir($dir);
//		\clearstatcache(true, $dir);
		if (\is_dir($dir)) throw new \Exception("Failed to remove directory: $dir");
		$log->debug("Removing directory: $dir");
	}



	public static function log(): Logger {
		return Logger::get('SHELL');
	}
*/



}
