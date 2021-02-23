<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\San;


/**
 * @coversDefaultClass \pxn\phpUtils\San
 */
class SanTest extends \PHPUnit\Framework\TestCase {

	const TEST_DATA = 'abcd ABCD 1234 _-=+ ,.?! @#$%^&*~ ()<>[]{};:`\'" \\|/';



	/**
	 * @covers ::AlphaNum
	 */
	public function testAlphaNum() {
		$this->assertEquals(
			'abcdABCD1234',
			San::AlphaNum(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumDash
	 */
	public function testAlphaNumDash() {
		$this->assertEquals(
			'abcdABCD1234_-',
			San::AlphaNumDash(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumSpaces
	 */
	public function testAlphaNumSpaces() {
		$this->assertEquals(
			'abcd ABCD 1234 _-    ',
			San::AlphaNumSpaces(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumFile
	 */
	public function testAlphaNumFile() {
		$this->assertEquals(
			'abcdABCD1234_-=+,.?!&()\'',
			San::AlphaNumFile(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::Base64
	 */
	public function testBase64() {
		$data = 'abcxyz-123890_ABCXYZ==';
		$this->assertEquals(
			'abcxyz123890ABCXYZ==',
			San::Base64($data)
		);
	}



	/**
	 * @covers ::isAlphaNum
	 */
	public function testIsAlphaNum() {
		$this->assertTrue(  San::isAlphaNum('abc123')        );
		$this->assertFalse( San::isAlphaNum(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumDash
	 */
	public function testIsAlphaNumDash() {
		$this->assertTrue(  San::isAlphaNumDash('abc123')        );
		$this->assertFalse( San::isAlphaNumDash(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumSpaces
	 */
	public function testIsAlphaNumSpaces() {
		$this->assertTrue(  San::isAlphaNumSpaces('abc123')        );
		$this->assertFalse( San::isAlphaNumSpaces(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumFile
	 */
	public function testIsAlphaNumFile() {
		$this->assertTrue(  San::isAlphaNumFile('abc123')        );
		$this->assertFalse( San::isAlphaNumFile(self::TEST_DATA) );
	}
	/**
	 * @covers ::isBase64
	 */
	public function testIsBase64() {
		$this->assertTrue(  San::isBase64('YWJjLTEyMw==')  );
		$this->assertFalse( San::isBase64(self::TEST_DATA) );
	}



}
