<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\BasicEnum;


class BasicEnumExample extends BasicEnum {

	const DOG     = 'woof';
	const CAT     = 'meow';
	const FISH    = 'bloop';
	const PENGUIN = 'sqeuaaaa';
	const BIRD    = 'churp';



	public static function getConstants() {
		return parent::getConstants();
	}



}
