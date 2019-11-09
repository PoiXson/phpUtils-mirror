<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\config;

use pxn\phpUtils\Defines;


class ConfigMain extends Config {



	public function __construct(string $file=NULL) {
		parent::__construct($file);
		// debug value
		{
			$debugValue = $this->getBool(Defines::CFG_MAIN_DEBUG);
			if ($debugValue !== NULL) {
				\pxn\phpUtils\Debug::setDebug($debugValue);
			}
		}
	}



	protected function initDefaults(): void {
		parent::initDefaults();
	}



}
