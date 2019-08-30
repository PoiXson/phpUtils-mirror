<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\app;

use pxn\phpUtils\ShellTools;
use pxn\phpUtils\System;
use pxn\phpUtils\ConfigGeneral;
use pxn\phpUtils\Defines;


abstract class ShellApp extends App {



//	public function __construct() {
//		parent::__construct();
//	}
	public static function ValidateShell() {
		if (!System::isShell()) {
			$name = $this->getName();
			fail("This ShellApp class can only run as shell! $name",
				Defines::EXIT_CODE_NOPERM);
		}
	}



	public function &getPageContents() {
		return 'PAGE';
	}



	protected function getWeight() {
		return System::isShell()
			? 1000
			: -1;
	}



	public static function setAllowShortFlagValues($enabled=TRUE) {
		ConfigGeneral::setAllowShortFlagValues($enabled);
	}



	protected function doRender() {
		self::ValidateShell();
		ShellTools::init();
		echo "\n";
		if (Debug()) {
			echo " [Debug Mode] \n";
		}
		// return false in case not overridden
		return FALSE;
	}



}
*/
