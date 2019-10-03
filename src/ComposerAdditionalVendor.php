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
	public static function Register(\Composer\Autoload\ClassLoader $autoload) {
		self::$autoload = $autoload;
	}



	// example: AddVendor('pxn\\LibName', '../../LibName/')
	public static function AddVendorClassMap(string $namespace, string $path) {
		if (self::$autoload == NULL) {
			throw new \RuntimeException();
		}
		$namespace = Strings::ForceEndsWith($namespace, '\\');
		$namespace = Strings::TrimFront($namespace, '\\');
		$path = Strings::ForceEndsWith($path, '/');
		// load autoload_classmap.php file
		$filePath = "{$path}vendor/composer/autoload_classmap.php";
		$classMap = require($filePath);
		// only add classes that don't already exist
		$existingClassMap = self::$autoload->getClassMap();
		foreach ($classMap as $key => $val) {
			if (isset($existingClassMap[$key]))
				continue;
			if (Strings::StartsWith($key, 'pxn\\ComposerLocalDev\\'))
				continue;
			self::$autoload->addClassMap([$key => $val]);
		}
	}



}
