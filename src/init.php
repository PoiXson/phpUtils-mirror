<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils;

use pxn\phpUtils\pxnDefines as xDef;
//use pxn\phpUtils\Debug;



\error_reporting(\E_ALL);



// atomic state
if (\defined('pxn\\phpUtils\\inited'))
	throw new \RuntimeException();
define('pxn\\phpUtils\\inited', true);



final class init {
	public static function init(): void {}
}



// defines
xDef::init();
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
{
	$version = xDef::PHP_MIN_VERSION;
	if (\PHP_VERSION_ID < $version) {
		$version_str = xDef::PHP_MIN_VERSION;
		echo "PHP $version_str or newer is required\n";
		exit(1);
	}
}

// check mbstring
if (!\function_exists('mb_substr')) {
	echo "mbstring library not installed\n";
	exit(1);
}
*/
