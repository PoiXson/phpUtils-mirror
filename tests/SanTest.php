<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\San;


/**
 * @coversDefaultClass \pxn\phpUtils\San
 */
class SanTest extends \PHPUnit\Framework\TestCase {

	const TEST_DATA = 'abcd ABCD 1234 -_=+ ,.?! @#$%^&*~ ()<>[]{};:`\'" \\|/';



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
	 * @covers ::AlphaNumSafe
	 */
	public function testAlphaNumSafe() {
		$this->assertEquals(
			'abcdABCD1234-_.',
			San::AlphaNumSafe(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumSafeMore
	 */
	public function testAlphaNumSafeMore() {
		$this->assertEquals(
			'abcdABCD1234-_.:',
			San::AlphaNumSafeMore(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumSpaces
	 */
	public function testAlphaNumSpaces() {
		$this->assertEquals(
			'abcd ABCD 1234 -_    ',
			San::AlphaNumSpaces(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumUnderscore
	 */
	public function testAlphaNumUnderscore() {
		$this->assertEquals(
			'abcdABCD1234_',
			San::AlphaNumUnderscore(self::TEST_DATA)
		);
	}
	/**
	 * @covers ::AlphaNumFile
	 */
	public function testAlphaNumFile() {
		$this->assertEquals(
			'abcdABCD1234-_=+,.?!&()\'',
			San::AlphaNumFile(self::TEST_DATA)
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
	 * @covers ::isAlphaNumSafe
	 */
	public function testIsAlphaNumSafe() {
		$this->assertTrue(  San::isAlphaNumSafe('abc123')        );
		$this->assertFalse( San::isAlphaNumSafe(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumSafeMore
	 */
	public function testIsAlphaNumSafeMore() {
		$this->assertTrue(  San::isAlphaNumSafeMore('abc123')        );
		$this->assertFalse( San::isAlphaNumSafeMore(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumSpaces
	 */
	public function testIsAlphaNumSpaces() {
		$this->assertTrue(  San::isAlphaNumSpaces('abc123')        );
		$this->assertFalse( San::isAlphaNumSpaces(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumUnderscore
	 */
	public function testIsAlphaNumUnderscore() {
		$this->assertTrue(  San::isAlphaNumUnderscore('abc123')        );
		$this->assertFalse( San::isAlphaNumUnderscore(self::TEST_DATA) );
	}
	/**
	 * @covers ::isAlphaNumFile
	 */
	public function testIsAlphaNumFile() {
		$this->assertTrue(  San::isAlphaNumFile('abc123')        );
		$this->assertFalse( San::isAlphaNumFile(self::TEST_DATA) );
	}



}
