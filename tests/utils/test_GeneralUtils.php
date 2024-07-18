<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
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
//		// null
//		$this->assertSame(expected: 123, actual: GeneralUtils::CastType(123, null));
		// string
		$this->assertSame(expected: '123', actual: GeneralUtils::CastType(123, 's'));
		// integer
		$this->assertSame(expected: 123, actual: GeneralUtils::CastType('123', 'i'));
		// long
		$this->assertSame(expected: 123, actual: GeneralUtils::CastType('123', 'l'));
		// float
		$this->assertSame(expected: 123e0, actual: GeneralUtils::CastType('123', 'f'));
		// double
		$this->assertSame(expected: 123.0, actual: GeneralUtils::CastType('123', 'd'));
		// boolean
		$this->assertSame(expected: true, actual: GeneralUtils::CastType('t', 'b'));
		// unknown
		$this->assertSame(expected: 'abc', actual: GeneralUtils::CastType('abc', 'z'));
	}



	/**
	 * @covers ::castBoolean
	 */
	public function test_CastBoolean(): void {
		// null
		$this->assertNull( GeneralUtils::castBoolean(null)       );
		// boolean
		$this->assertTrue(  GeneralUtils::castBoolean(true)      );
		$this->assertFalse( GeneralUtils::castBoolean(false)     );
		// true/false
		$this->assertTrue(  GeneralUtils::castBoolean('true')    );
		$this->assertFalse( GeneralUtils::castBoolean('false')   );
		// yes/no
		$this->assertTrue(  GeneralUtils::castBoolean('yes')     );
		$this->assertFalse( GeneralUtils::castBoolean('no')      );
		// allow/deny
		$this->assertTrue(  GeneralUtils::castBoolean('allow')   );
		$this->assertFalse( GeneralUtils::castBoolean('deny')    );
		// enable/disable
		$this->assertTrue(  GeneralUtils::castBoolean('enable')  );
		$this->assertFalse( GeneralUtils::castBoolean('disable') );
		// on/off
		$this->assertTrue(  GeneralUtils::castBoolean('on')      );
		$this->assertFalse( GeneralUtils::castBoolean('off')     );
		// 1/0
		$this->assertTrue(  GeneralUtils::castBoolean(1)         );
		$this->assertFalse( GeneralUtils::castBoolean(0)         );
	}



	/**
	 * @covers ::GetVar
	 */
	public function test_GetVar(): void {
		$key = 'abcd';
		$_GET[$key]     = '1234';
		$_POST[$key]    = '5678';
		$_COOKIE[$key]  = '9123';
		$_SESSION[$key] = '4567';
		$this->assertEquals(expected: '1234', actual: GeneralUtils::GetVar($key, 's', 'g')); // string/get
		$this->assertEquals(expected: '5678', actual: GeneralUtils::GetVar($key, 's', 'p')); // string/post
		$this->assertEquals(expected: '5678', actual: GeneralUtils::GetVar($key, 's'     )); // string/get/post
		$this->assertEquals(expected: '9123', actual: GeneralUtils::GetVar($key, 's', 'c')); // string/cookie
		$this->assertEquals(expected: '4567', actual: GeneralUtils::GetVar($key, 's', 's')); // string/session
		$this->assertEquals(expected: '5678', actual: GeneralUtils::GetVar($key, 's','gp')); // string/get-post
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
