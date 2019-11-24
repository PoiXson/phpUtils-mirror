<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use pxn\phpUtils\Defines;
use pxn\phpUtils\Debug;



\error_reporting(\E_ALL);



// atomic state
if (\defined('pxn\\phpUtils\\inited')) {
	throw new \RuntimeException();
}
define('pxn\\phpUtils\\inited', TRUE);

//TODO
//if (\defined('pxn\\phpUtils\\PORTAL_LOADED')) {
//	echo '<h2>Unknown state! Portal already loaded?</h2>';
//	exit(1);
//}



final class init {
	public static function init(): void {}
}



// defines
\pxn\phpUtils\Defines::init();
{
	$clss = 'pxn\\phpPortal\\DefinesPortal';
	if (\class_exists($clss)) {
		$clss::init();
	}
	unset($clss);
}



// paths
Paths::init();

// debug
Debug::init();

// global functions
\pxn\phpUtils\Globals::init();



// check php version
if (\PHP_VERSION_ID < \pxn\phpUtils\Defines::PHP_MIN_VERSION) {
	echo '<p>PHP '.\pxn\phpUtils\Defines::PHP_MIN_VERSION_STR
		.' or newer is required!</p>'; exit(1);
}

// check mbstring
if (!\function_exists('mb_substr')) {
	echo '<h2>mbstring library not installed?</h2>';
	exit(1);
}
