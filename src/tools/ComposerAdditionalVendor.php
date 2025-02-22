<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use \pxn\phpUtils\utils\StringUtils;


class ComposerAdditionalVendor {

	protected $autoload;



	public function __construct(\Composer\Autoload\ClassLoader $autoload) {
		$this->autoload = $autoload;
	}



	// example: AddVendor('pxn\\LibName', '../../LibName/')
	public function addVendorClassMap(string $path,
	array $whitelist=[], array $blacklist=[]): void {
		if ($this->autoload == null)
			throw new \RuntimeException('Composer autoload not registered');
		// defaults
		$blacklist[] = 'pxn\\ComposerLocalDev';
		$path = StringUtils::force_ends_with(haystack: $path, append: '/');
		// load autoload_classmap.php file
		$filePath = "{$path}vendor/composer/autoload_classmap.php";
		if (!\file_exists($filePath))
			throw new \RuntimeException("Module class loader not found: $filePath");
		$classMap = require($filePath);
		// only add classes that don't already exist
		$existingClassMap = $this->autoload->getClassMap();
		foreach ($classMap as $key => $val) {
			if (isset($existingClassMap[$key]))
				continue;
			$key = StringUtils::trim_front($key, '\\');
			// check blacklists
			if (\count($blacklist) > 0) {
				foreach ($blacklist as $entry) {
					$entry = StringUtils::trim_front($entry, '\\');
					$entry = StringUtils::force_ends_with(  haystack: $entry, append: '\\');
					if (\str_starts_with(haystack: $key, needle: $entry))
						continue 2;
				}
			}
			// check whitelists
			if (\count($whitelist) > 0) {
				$found = false;
				foreach ($whitelist as $entry) {
					$entry = StringUtils::trim_front($entry, '\\');
					$entry = StringUtils::force_ends_with(  haystack: $entry, append: '\\');
					if (\str_starts_with(haystack: $key, needle: $entry)) {
						$found = true;
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
