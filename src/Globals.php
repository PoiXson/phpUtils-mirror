<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils {



	final class Globals {
		public static function init(): void {}
	}



}
namespace {



	# debug()
	if (!\function_exists('debug')) {
		function debug(?bool $debug=NULL, ?string $desc=NULL): bool {
			return \pxn\phpUtils\Debug::debug($debug, $desc);
		}
	}

	# dd()
	if (!\function_exists('dd')) {
		function dd($data=null): void {
			\pxn\phpUtils\Debug\dd($data);
		}
	}



}
