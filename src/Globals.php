<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils {
	final class Globals {
		public static function init() {}
	}
}
namespace {

use pxn\phpUtils\Defines;



# debug()
if (!\function_exists('debug')) {
	function debug($debug=NULL, $desc=NULL) {
		return \pxn\phpUtils\Debug::debug($debug, $desc);
	}
}



} // end namespace
