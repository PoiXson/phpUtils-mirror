<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


class ComposerAdditionalVendor {

	protected $autoload;



	public function __construct(\Composer\Autoload\ClassLoader $autoload) {
		$this->autoload = $autoload;
	}



	// example: AddVendor('pxn\\LibName', '../../LibName/')
	public function addVendorClassMap(string $path,
	array $whitelist=[], array $blacklist=[]): void {
		if ($this->autoload == NULL) {
			throw new \RuntimeException('Composer autoload not registered');
		}
		// defaults
		$blacklist[] = 'pxn\\ComposerLocalDev';
		$path = Strings::ForceEndsWith($path, '/');
		// load autoload_classmap.php file
		$filePath = "{$path}vendor/composer/autoload_classmap.php";
		if (!\file_exists($filePath)) {
			throw new \RuntimeException("Module class loader not found: $filePath");
		}
		$classMap = require($filePath);
		// only add classes that don't already exist
		$existingClassMap = $this->autoload->getClassMap();
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
			$this->autoload->addClassMap([$key => $val]);
		}
	}



}
