<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
# init 1 - startup
# init 2 - functions
# init 3 - defines
# init 4 - configs
# init 5 - debug
# init 6 - globals
namespace pxn\phpUtils;



// atomic state
if (\defined('pxn\\phpUtils\\inited')) {
	throw new \RuntimeException();
}
define('pxn\\phpUtils\\inited', TRUE);



class init {
	public static function init() {}
}



# init 1 - startup
require('init_1.php');



# init 2 - functions
require('init_2.php');



# init 3 - defines
\pxn\phpUtils\Defines::init();
{
	$clss = '\\pxn\\phpPortal\\DefinesPortal';
	if (\class_exists($clss)) {
		$clss::init();
	}
	unset($clss);
}
// paths
\pxn\phpUtils\Paths::init();



// check php version
if (\PHP_VERSION_ID < \pxn\phpUtils\Defines::PHP_MIN_VERSION) {
	echo '<p>PHP '.\pxn\phpUtils\Defines::PHP_MIN_VERSION_STR
		.' or newer is required!</p>'; exit(1);
}



# init 4 - configs
\pxn\phpUtils\ConfigGeneral::init();
{
	$clss = '\\pxn\\phpPortal\\ConfigPortal';
	if (\class_exists($clss)) {
		$clss::init();
	}
	unset($clss);
}
// load shell args
if (System::isShell()) {
	ShellTools::init();
}



# init 5 - debug
require('init_5.php');



# init 6 - globals
require ('Globals.php');
