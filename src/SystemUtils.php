<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class SystemUtils {
	private function __construct() {}

	private static $isShell = NULL;



	public static function RequireLinux() {
		$os = \PHP_OS;
		if ($os != 'Linux') {
			fail(
				'Sorry, only Linux is currently supported. Contact '.
				'the developer if you\'d like to help add support for '.\PHP_OS,
				Defines::EXIT_CODE_GENERAL
			);
		}
	}



	###########
	## Shell ##
	###########



	public static function isShell() {
		if (self::$isShell == NULL) {
			self::$isShell =
				isset($_SERVER['SHELL']) &&
				! empty($_SERVER['SHELL']);
		}
		return self::$isShell;
	}
	public static function isWeb() {
		return ! self::isShell();
	}
	public static function RequireShell() {
		$isShell = self::isShell();
		if (!$isShell) {
			fail('This script can only run in shell!',
				Defines::EXIT_CODE_GENERAL);
		}
	}
	public static function RequireWeb() {
		$isShell = self::isShell();
		if ($isShell) {
			fail('Cannot run this script in shell!',
				Defines::EXIT_CODE_GENERAL);
		}
	}



	public static function isUsrInstalled() {
		return Strings::StartsWith(Paths::entry(), '/usr/');
	}



	public static function getUser() {
		if (isset($_SERVER['USER']))
			return $_SERVER['USER'];
		$who = \exec('whoami');
		if (empty($who))
			return NULL;
		return $who;
	}
	public static function denySuperUser() {
		$who = self::isSuperUser();
		if (!empty($who)) {
			fail("Cannot run this script as super user: $who",
				Defines::EXIT_CODE_NOPERM);
		}
	}
	public static function isSuperUser($who=NULL) {
		if (empty($who)) {
			$who = self::getUser();
		}
		$who = \strtolower($who);
		if ($who == 'root') {
			return $who;
		}
		if ($who == 'system') {
			return $who;
		}
		if ($who == 'administrator') {
			return $who;
		}
		return FALSE;
	}



/*
	public static function exec($command) {
		$command = \trim($command);
		if (empty($command))
			return FALSE;
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



	public static function mkdir($dir, $mode=644) {
		if (empty($dir))     throw new \Exception('dir argument is required');
		if (!\is_int($mode)) throw new \Exception('mode argument must be an integer!');
		$oct = \octdec($mode);
		$log = self::log();
		// prepend cwd
		if (!Strings::StartsWith($dir, '/')) {
			$dir = Strings::BuildPath(\getcwd(), $dir);
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
//		\clearstatcache(TRUE, $dir);
		// ensure created directories exist
		if (!\is_dir($dir)) throw new \Exception("Failed to create directory: $dir");
	}
	public static function rmdir($dir) {
		if (empty($dir)) throw new \Exception('dir argument is required');
		$log = self::log();
		// ensure exists
		$temp = \realpath($dir);
		if (empty($temp)) throw new \Exception("dir not found, cannot delete! $dir");
		$dir = $temp;
		unset($temp);
		\clearstatcache(TRUE, $dir);
		if (!\is_dir($dir)) throw new \Exception("dir argument is not a directory! $dir");
		if ($dir == '/')    throw new \Exception('cannot delete / directory!');
		// list contents
		$array = \scandir($dir);
		if ($array == FALSE) throw new \Exception("Failed to list contents of directory: $dir");
		foreach ($array as $entry) {
			if ($entry == '.' || $entry == '..')
				continue;
			$full = Strings::BuildPath($dir, $entry);
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
//		\clearstatcache(TRUE, $dir);
		if (\is_dir($dir)) throw new \Exception("Failed to remove directory: $dir");
		$log->debug("Removing directory: $dir");
	}



	public static function log() {
		return Logger::get('SHELL');
	}
*/



}
