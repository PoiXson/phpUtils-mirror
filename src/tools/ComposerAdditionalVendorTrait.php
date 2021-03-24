<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use Composer\Autoload\ClassLoader;

use pxn\phpUtils\exceptions\RequiredArgumentException;


trait ComposerAdditionalVendorTrait {

	protected ComposerAdditionalVendor $addVendor;



	protected function init_addvendor(ClassLoader $autoload): void {
		if ($autoload == null) throw new RequiredArgumentException('autoload');
		$this->addVendor = new ComposerAdditionalVendor($autoload);
	}



}
