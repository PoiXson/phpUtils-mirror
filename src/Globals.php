<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils {



	final class Globals {
		public static function init(): void {}
	}



}
namespace {



	// debug()
	if (!\function_exists('debug')) {
		function debug(?bool $debug=null, ?string $desc=null): bool {
			return \pxn\phpUtils\Debug::debug($debug, $desc);
		}
	}

	// dd()
	if (!\function_exists('dd')) {
		function dd($data=null): void {
			\pxn\phpUtils\Debug::dd($data);
		}
	}

	// dump()
	if (!\function_exists('dump')) {
		function dump($var): void {
			\pxn\phpUtils\Debug::dump($var);
		}
	}

	// trace()
	if (!\function_exists('trace')) {
		function trace(): void {
			\pxn\phpUtils\Debug::trace();
		}
	}



}
