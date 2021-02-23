<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\utils\SanUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\SanUtils
 */
class test_SanUtils extends \PHPUnit\Framework\TestCase {

	const TEST_DATA = 'abcd ABCD 1234 _-=+ ,.?! @#$%^&*~ ()<>[]{};:`\'" \\|/';



	/**
	 * @covers ::AlphaNum
	 * @covers ::AlphaNumDash
	 * @covers ::AlphaNumSpaces
	 * @covers ::AlphaNumFile
	 * @covers ::Base64
	 */
	public function test_San(): void {
		$this->assertEquals(expected: 'abcdABCD1234',              actual: SanUtils::AlphaNum(self::TEST_DATA)      );
		$this->assertEquals(expected: 'abcdABCD1234_-',            actual: SanUtils::AlphaNumDash(self::TEST_DATA)  );
		$this->assertEquals(expected: 'abcd ABCD 1234 _-    ',     actual: SanUtils::AlphaNumSpaces(self::TEST_DATA));
		$this->assertEquals(expected: 'abcdABCD1234_-=+,.?!&()\'', actual: SanUtils::AlphaNumFile(self::TEST_DATA)  );
		$data = 'abcxyz-123890_ABCXYZ==';
		$this->assertEquals(expected: 'abcxyz123890ABCXYZ==',      actual: SanUtils::Base64($data)                  );
	}



	/**
	 * @covers ::isAlphaNum
	 * @covers ::isAlphaNumDash
	 * @covers ::isAlphaNumSpaces
	 * @covers ::isAlphaNumFile
	 * @covers ::isBase64
	 * @covers ::isVersion
	 */
	public function test_isSan(): void {
		// isAlphaNum()
		$this->assertTrue(  SanUtils::isAlphaNum('abc123')             );
		$this->assertFalse( SanUtils::isAlphaNum(self::TEST_DATA)      );
		// isAlphaNumDash()
		$this->assertTrue(  SanUtils::isAlphaNumDash('abc123')         );
		$this->assertFalse( SanUtils::isAlphaNumDash(self::TEST_DATA)  );
		// isAlphaNumSpaces()
		$this->assertTrue(  SanUtils::isAlphaNumSpaces('abc123')       );
		$this->assertFalse( SanUtils::isAlphaNumSpaces(self::TEST_DATA));
		// isAlphaNumFile()
		$this->assertTrue(  SanUtils::isAlphaNumFile('abc123')         );
		$this->assertFalse( SanUtils::isAlphaNumFile(self::TEST_DATA)  );
		// isBase64()
		$this->assertTrue(  SanUtils::isBase64('YWJjLTEyMw==')         );
		$this->assertFalse( SanUtils::isBase64(self::TEST_DATA)        );
		// isVersion()
		$this->assertTrue(  SanUtils::isVersion('1.2.3')               );
		$this->assertFalse( SanUtils::isVersion('a.b.c')               );
	}



}
