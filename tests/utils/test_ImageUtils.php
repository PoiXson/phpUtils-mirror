<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\ImageUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\ImageUtils
 */
class test_ImageUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::GDSupported
	 */
	public function test_GDSupported(): void {
		$this->assertEquals(
			\function_exists('imagepng'),
			ImageUtils::GDSupported()
		);
	}



}
