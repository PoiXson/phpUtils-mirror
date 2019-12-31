<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


class ComposerAdditionalVendor {

	protected static $autoload = NULL;



	// register the parent autoloader
	public static function Register(\Composer\Autoload\ClassLoader $autoload): void {
		self::$autoload = $autoload;
	}



	// example: AddVendor('pxn\\LibName', '../../LibName/')
	public static function AddVendorClassMap(string $namespace, string $path,
	array $whitelist=[], array $blacklist=[]): void {
		if (self::$autoload == NULL) {
			throw new \RuntimeException();
		}
		// defaults
		$blacklist[] = 'pxn\\ComposerLocalDev';
		$namespace = Strings::ForceEndsWith($namespace, '\\');
		$namespace = Strings::TrimFront(    $namespace, '\\');
		$path = Strings::ForceEndsWith($path, '/');
		// load autoload_classmap.php file
		$filePath = "{$path}vendor/composer/autoload_classmap.php";
		$classMap = require($filePath);
		// only add classes that don't already exist
		$existingClassMap = self::$autoload->getClassMap();
		foreach ($classMap as $key => $val) {
			if (isset($existingClassMap[$key]))
				continue;
			// check blacklists
			if (\count($blacklist) > 0) {
				foreach ($blacklist as $entry) {
					$entry = Strings::ForceEndsWith($entry, '\\');
					if (Strings::StartsWith($key, $entry))
						continue 2;
				}
			}
			// check whitelists
			if (\count($whitelist) > 0) {
				$found = FALSE;
				foreach ($whitelist as $entry) {
					$entry = Strings::ForceEndsWith($entry, '\\');
					if (Strings::StartsWith($key, $entry)) {
						$found = TRUE;
						break;
					}
				}
				if (!$found)
					continue;
			}
			self::$autoload->addClassMap([$key => $val]);
		}
	}



}
