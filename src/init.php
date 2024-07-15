<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use \pxn\phpUtils\pxnDefines as xDef;
use \pxn\phpUtils\Debug;



\error_reporting(\E_ALL);



// atomic state
if (\defined('pxn\\phpUtils\\inited'))
	throw new \RuntimeException('phpUtils already inited');
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
xPaths::init();



// debug
Debug::init();



// global functions
\pxn\phpUtils\Globals::init();



// check php version
{
	$version = xDef::PHP_MIN_VERSION;
	if (\PHP_VERSION_ID < $version)
		throw new \RuntimeException('PHP '.xDef::PHP_MIN_VERSION.' or newer is required');
}

// check mbstring
if (!\function_exists('mb_substr'))
	throw new \RuntimeException('mbstring library not installed');
