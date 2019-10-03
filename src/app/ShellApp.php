<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;

use pxn\phpUtils\System;
use pxn\phpUtils\Defines;


abstract class ShellApp extends App {

	protected $symfonyApp = NULL;



	public function __construct() {
		self::ValidateShell();
		$this->symfonyApp = new \Symfony\Component\Console\Application();
		parent::__construct();
	}



	public function run() {
		if (Debug()) {
			echo " [Debug Mode] \n";
		}
		$this->symfonyApp->run();
	}



	public function printFail($msg) {
		echo "\n *** FATAL: $msg *** \n\n";
	}



	public static function ValidateShell() {
		if (!System::isShell()) {
			$name = $this->getName();
			fail("This ShellApp class can only run as shell! $name",
				Defines::EXIT_CODE_NOPERM);
		}
	}



}
