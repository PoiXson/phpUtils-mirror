<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\GeneralUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\GeneralUtils
 */
class test_GeneralUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::CastType
	 */
	public function test_CastType(): void {
//		$this->assertSame(expected: 123, actual: GeneralUtils::CastType(123, null)); // null
		$this->assertSame(expected: '123', actual: GeneralUtils::CastType( 123,  's')); // string
		$this->assertSame(expected: 123,   actual: GeneralUtils::CastType('123', 'i')); // integer
		$this->assertSame(expected: 123,   actual: GeneralUtils::CastType('123', 'l')); // long
		$this->assertSame(expected: 123e0, actual: GeneralUtils::CastType('123', 'f')); // float
		$this->assertSame(expected: 123.0, actual: GeneralUtils::CastType('123', 'd')); // double
		$this->assertSame(expected: true,  actual: GeneralUtils::CastType('t',   'b')); // boolean
		$this->assertSame(expected: 'abc', actual: GeneralUtils::CastType('abc', 'z')); // unknown
	}



	/**
	 * @covers ::castBoolean
	 */
	public function test_CastBoolean(): void {
		$this->assertNull(  GeneralUtils::castBoolean(null)      ); // null
		$this->assertTrue(  GeneralUtils::castBoolean(true)      ); // boolean
		$this->assertFalse( GeneralUtils::castBoolean(false)     );
		$this->assertTrue(  GeneralUtils::castBoolean('true')    ); // true/false
		$this->assertFalse( GeneralUtils::castBoolean('false')   );
		$this->assertTrue(  GeneralUtils::castBoolean('yes')     ); // yes/no
		$this->assertFalse( GeneralUtils::castBoolean('no')      );
		$this->assertTrue(  GeneralUtils::castBoolean('allow')   ); // allow/deny
		$this->assertFalse( GeneralUtils::castBoolean('deny')    );
		$this->assertTrue(  GeneralUtils::castBoolean('enable')  ); // enable/disable
		$this->assertFalse( GeneralUtils::castBoolean('disable') );
		$this->assertTrue(  GeneralUtils::castBoolean('on')      ); // on/off
		$this->assertFalse( GeneralUtils::castBoolean('off')     );
		$this->assertTrue(  GeneralUtils::castBoolean(1)         ); // 1/0
		$this->assertFalse( GeneralUtils::castBoolean(0)         );
	}



	/**
	 * @covers ::GetVar
	 */
	public function test_GetVar(): void {
		$key = 'abcd';
		$_GET[$key]     = 'abcd';
		$_POST[$key]    = 'efgh';
		$_COOKIE[$key]  = 'ijkl';
		$_SESSION[$key] = 'mnop';
		$this->assertEquals(expected: 'abcd', actual: GeneralUtils::GetVar(name: $key, type: 's', src: 'g' )); // string/get
		$this->assertEquals(expected: 'efgh', actual: GeneralUtils::GetVar(name: $key, type: 's', src: 'p' )); // string/post
		$this->assertEquals(expected: 'efgh', actual: GeneralUtils::GetVar(name: $key, type: 's'           )); // string/get/post
		$this->assertEquals(expected: 'ijkl', actual: GeneralUtils::GetVar(name: $key, type: 's', src: 'c' )); // string/cookie
		$this->assertEquals(expected: 'mnop', actual: GeneralUtils::GetVar(name: $key, type: 's', src: 's' )); // string/session
		$this->assertEquals(expected: 'efgh', actual: GeneralUtils::GetVar(name: $key, type: 's', src: 'gp')); // string/get-post
		// unknown source
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown value source: z');
		GeneralUtils::getVar($key, 's', 'z');
		unset($_GET[$key]);
		unset($_POST[$key]);
		unset($_COOKIE[$key]);
		unset($_SESSION[$key]);
	}



	/**
	 * @covers ::get
	 */
	public function test_Get(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::get($key) );
		$_GET[$key] = 'abc';
		$this->assertEquals(expected: 'abc', actual: GeneralUtils::get($key));
		unset($_GET[$key]);
	}
	/**
	 * @covers ::post
	 */
	public function test_Post(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::post($key) );
		$_POST[$key] = 'abc';
		$this->assertEquals(expected: 'abc', actual: GeneralUtils::post($key));
		unset($_POST[$key]);
	}
	/**
	 * @covers ::GetCookie
	 */
	public function test_Cookie(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::GetCookie($key) );
		$_COOKIE[$key] = 'abc';
		$this->assertEquals(expected: 'abc', actual: GeneralUtils::GetCookie($key));
		unset($_COOKIE[$key]);
	}
	/**
	 * @covers ::GetSession
	 */
	public function test_Session(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::GetSession($key) );
		$_SESSION[$key] = 'abc';
		$this->assertEquals(expected: 'abc', actual: GeneralUtils::GetSession($key));
		unset($_SESSION[$key]);
	}



/*
	/ **
	 * @covers ::ParseModRewriteVars
	 * /
	public function test_ParseModRewriteVars() {
		$this->markTestIncomplete('Unfinished ParseModRewriteVars test');
//		$_SERVER[''] = '';
//		GeneralUtils::ParseModRewriteVars();
//		$this->assertEquals(
//			'',
//			''
//		);
	}
*/



	/**
	 * @covers ::Timestamp
	 */
	public function test_GetTimestamp(): void {
		$tim = GeneralUtils::Timestamp();
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000.0, $tim);
		$tim = GeneralUtils::Timestamp(0);
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000, $tim);
	}
	/**
	 * @covers ::Sleep
	 */
	public function test_Sleep(): void {
		$timA = GeneralUtils::Timestamp();
		GeneralUtils::Sleep(10);
		$timB = GeneralUtils::Timestamp();
		$this->assertGreaterThanOrEqual(0.009, $timB - $timA);
	}



/*
	/ **
	 * @covers ::InstanceOfClass
	 * /
	public function test_InstanceOfClass() {
		$this->assertTrue(GeneralUtils::InstanceOfClass(self::EXPECTED_CLASS_STRING, $this));
	}



	/ **
	 * @covers ::ValidateClass
	 * /
	public function test_ValidateClass() {
//TODO: needs assert "This test did not perform any assertions"
		GeneralUtils::ValidateClass(self::EXPECTED_CLASS_STRING, $this);
$this->assertTrue(true);
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function test_ValidateClass_NullString() {
		try {
			GeneralUtils::ValidateClass(null, $this);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(expected: 'classname not defined', actual: $e->getMessage());
			return;
		}
		$this->assertTrue(false, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function test_ValidateClass_BlankString() {
		try {
			GeneralUtils::ValidateClass('', $this);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(expected: 'classname not defined', actual: $e->getMessage());
			return;
		}
		$this->assertTrue(false, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function test_ValidateClass_NullObject() {
		try {
			GeneralUtils::ValidateClass(self::EXPECTED_CLASS_STRING, null);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(expected: 'object not defined', actual: $e->getMessage());
			return;
		}
		$this->assertTrue(false, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function test_ValidateClass_ClassType() {
		try {
			GeneralUtils::ValidateClass(self::EXPECTED_CLASS_STRING.'_invalid', $this);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(
				expected: 'Class object isn\'t of type '.self::EXPECTED_CLASS_STRING.'_invalid',
				actual: $e->getMessage()
			);
			return;
		}
		$this->assertTrue(false, 'Failed to throw expected exception');
	}
*/



}
