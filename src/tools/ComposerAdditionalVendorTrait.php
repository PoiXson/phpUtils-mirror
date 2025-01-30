<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use \Composer\Autoload\ClassLoader;


trait ComposerAdditionalVendorTrait {

	protected ComposerAdditionalVendor $addVendor;



	protected function init_addvendor(ClassLoader $autoload): void {
		$this->addVendor = new ComposerAdditionalVendor($autoload);
	}



}
