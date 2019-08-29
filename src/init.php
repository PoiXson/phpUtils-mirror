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
if (\class_exists('\\pxn\\phpPortal\\DefinesPortal')) {
	\pxn\phpPortal\DefinesPortal::init();
}
// paths
\pxn\phpUtils\Paths::init();



# init 4 - configs
\pxn\phpUtils\ConfigGeneral::init();
if (\class_exists('\\pxn\\phpPortal\\ConfigPortal')) {
	\pxn\phpPortal\ConfigPortal::init();
}
// load shell args
if (System::isShell()) {
	ShellTools::init();
}



# init 5 - debug
require('init_5.php');



# init 6 - globals
require ('Globals.php');



return TRUE;
