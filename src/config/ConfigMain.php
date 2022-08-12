<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\config;

use pxn\phpUtils\Defines;


class ConfigMain extends Config {

	// config keys
	const KEY_DEBUG = 'Debug';



	public function __construct(?string $file=NULL) {
		parent::__construct($file);
		// debug value
		{
			$debugValue = $this->getBool(self::KEY_DEBUG);
			if ($debugValue !== NULL) {
				\pxn\phpUtils\Debug::setDebug($debugValue);
			}
		}
	}



	protected function initDefaults(): void {
		parent::initDefaults();
	}



}
*/
