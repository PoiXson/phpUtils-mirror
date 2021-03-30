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
	 * @covers ::alpha_num
	 * @covers ::alpha_num_simple
	 * @covers ::rep_space
	 * @covers ::path_safe
	 * @covers ::base64
	 */
	public function test_San(): void {
		$this->assertEquals(expected: 'abcdABCD1234',            actual: SanUtils::alpha_num(value: self::TEST_DATA));
		$this->assertEquals(expected: 'abcd ABCD 1234 _-    ',   actual: SanUtils::alpha_num(value: self::TEST_DATA, extra: '\_\-\s'));
		$this->assertEquals(expected: 'abcdABCD1234_-',          actual: SanUtils::alpha_num_simple(value: self::TEST_DATA));
		$this->assertEquals(expected: 'abcxyz123890ABCXYZ==',    actual: SanUtils::base64('abcxyz-123890_ABCXYZ=='));
		$this->assertEquals(expected: 'abcd_ABCD_1234__-____',   actual: SanUtils::rep_space(self::TEST_DATA));
		$this->assertEquals(expected: 'abcd_ABCD_1234__-_.___/', actual: SanUtils::path_safe(self::TEST_DATA));
	}



	/**
	 * @covers ::is_alpha_num
	 * @covers ::is_alpha_num_simple
	 * @covers ::is_path_safe
	 * @covers ::is_base64
	 * @covers ::is_version
	 */
	public function test_isSan(): void {
		// is_alpha_num
		$this->assertTrue(  SanUtils::is_alpha_num('Abc123')        );
		$this->assertFalse( SanUtils::is_alpha_num('Abc 123')       );
		$this->assertFalse( SanUtils::is_alpha_num('Abc-123')       );
		$this->assertFalse( SanUtils::is_alpha_num(self::TEST_DATA) );
		// is_alpha_num_simple
		$this->assertTrue(  SanUtils::is_alpha_num_simple('Abc123')        );
		$this->assertTrue(  SanUtils::is_alpha_num_simple('Abc_-123')      );
		$this->assertFalse( SanUtils::is_alpha_num_simple('Abc 123')       );
		$this->assertFalse( SanUtils::is_alpha_num_simple(self::TEST_DATA) );
		// is_path_safe
		$this->assertTrue(  SanUtils::is_path_safe('Abc/12.3')      );
		$this->assertFalse( SanUtils::is_path_safe('Abc 12.3')      );
		$this->assertFalse( SanUtils::is_path_safe('Abc!12.3')      );
		$this->assertFalse( SanUtils::is_path_safe(self::TEST_DATA) );
		// is_base64
		$this->assertTrue(  SanUtils::is_base64('Abc123==')      );
		$this->assertFalse( SanUtils::is_base64(self::TEST_DATA) );
		// is_version
		$this->assertTrue(  SanUtils::is_version('1.2.3')  );
		$this->assertFalse( SanUtils::is_version('a.b.c')  );
		$this->assertFalse( SanUtils::is_version('Abc123') );
	}



}
