<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\GeneralUtils;


/ **
 * @coversDefaultClass \pxn\phpUtils\GeneralUtils
 * /
class GeneralUtilsTest extends \PHPUnit\Framework\TestCase {

	const THIS_CLASS_STRING = 'pxn\\phpUtils\\tests\\GeneralUtilsTest';



	/ **
	 * @covers
	 * /
	public function testArray(): void {
		$this->assertEmpty([]);
	}



	/ **
	 * @covers
	 * /
	public function testClassName(): void {
		$this->assertEquals(
			self::THIS_CLASS_STRING,
			\get_called_class($this)
		);
	}



	/ **
	 * @covers ::castType
	 * /
	public function testCastType(): void {
//		// null
//		$this->assertSame(
//			123,
//			GeneralUtils::castType(123, NULL)
//		);
		// string
		$this->assertSame(
			'123',
			GeneralUtils::castType(123, 's')
		);
		// integer
		$this->assertSame(
			123,
			GeneralUtils::castType('123', 'i')
		);
		// long
		$this->assertSame(
			123,
			GeneralUtils::castType('123', 'l')
		);
		// float
		$this->assertSame(
			123e0,
			GeneralUtils::castType('123', 'f')
		);
		// double
		$this->assertSame(
			123.0,
			GeneralUtils::castType('123', 'd')
		);
		// boolean
		$this->assertSame(
			TRUE,
			GeneralUtils::castType('t', 'b')
		);
		// unknown
		$this->assertSame(
			'abc',
			GeneralUtils::castType('abc', 'z')
		);
	}



	/ **
	 * @covers ::castBoolean
	 * /
	public function testCastBoolean(): void {
		// null
		$this->assertNull( GeneralUtils::castBoolean(NULL)       );
		// boolean
		$this->assertTrue(  GeneralUtils::castBoolean(TRUE)      );
		$this->assertFalse( GeneralUtils::castBoolean(FALSE)     );
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



	/ **
	 * @covers ::getVar
	 * /
	public function testGetVar(): void {
		$key = 'abcd';
		$_GET[$key]     = '1234';
		$_POST[$key]    = '5678';
		$_COOKIE[$key]  = '9123';
		$_SESSION[$key] = '4567';
		// string/get
		$this->assertEquals(
			'1234',
			GeneralUtils::getVar($key, 's', 'g')
		);
		// string/post
		$this->assertEquals(
			'5678',
			GeneralUtils::getVar($key, 's', 'p')
		);
		// string/get/post
		$this->assertEquals(
			'5678',
			GeneralUtils::getVar($key, 's')
		);
		// string/cookie
		$this->assertEquals(
			'9123',
			GeneralUtils::getVar($key, 's', 'c')
		);
		// string/session
		$this->assertEquals(
			'4567',
			GeneralUtils::getVar($key, 's', 's')
		);
		// unknown source
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown value source: z');
		GeneralUtils::getVar($key, 's', 'z');
		unset($_GET[$key]);
		unset($_POST[$key]);
		unset($_COOKIE[$key]);
		unset($_SESSION[$key]);
	}



	/ **
	 * @covers ::get
	 * /
	public function testGet(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::get($key) );
		$_GET[$key] = 'abc';
		$this->assertEquals( 'abc', GeneralUtils::get($key) );
		unset($_GET[$key]);
	}
	/ **
	 * @covers ::post
	 * /
	public function testPost(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::post($key) );
		$_POST[$key] = 'abc';
		$this->assertEquals( 'abc', GeneralUtils::post($key) );
		unset($_POST[$key]);
	}
	/ **
	 * @covers ::cookie
	 * /
	public function testCookie(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::cookie($key) );
		$_COOKIE[$key] = 'abc';
		$this->assertEquals( 'abc', GeneralUtils::cookie($key) );
		unset($_COOKIE[$key]);
	}
	/ **
	 * @covers ::session
	 * /
	public function testSession(): void {
		$key = 'test';
		$this->assertNull( GeneralUtils::session($key) );
		$_SESSION[$key] = 'abc';
		$this->assertEquals( 'abc', GeneralUtils::session($key) );
		unset($_SESSION[$key]);
	}



/ *
	/ **
	 * @covers ::ParseModRewriteVars
	 * /
	public function testParseModRewriteVars() {
		$this->markTestIncomplete('Unfinished ParseModRewriteVars test');
//		$_SERVER[''] = '';
//		GeneralUtils::ParseModRewriteVars();
//		$this->assertEquals(
//			'',
//			''
//		);
	}
* /



	/ **
	 * @covers ::Timestamp
	 * /
	public function testGetTimestamp(): void {
		$tim = GeneralUtils::Timestamp();
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000.0, $tim);
		$tim = GeneralUtils::Timestamp(0);
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000, $tim);
	}
	/ **
	 * @covers ::Sleep
	 * /
	public function testSleep(): void {
		$timA = GeneralUtils::Timestamp();
		GeneralUtils::Sleep(10);
		$timB = GeneralUtils::Timestamp();
		$this->assertGreaterThanOrEqual(0.009, $timB - $timA);
	}



	/ **
	 * @covers ::GDSupported
	 * /
	public function testGDSupported(): void {
		$this->assertEquals(
			\function_exists('imagepng'),
			GeneralUtils::GDSupported()
		);
	}



/ *
	/ **
	 * @covers ::InstanceOfClass
	 * /
	public function testInstanceOfClass() {
		$this->assertTrue(
			GeneralUtils::InstanceOfClass(
				self::EXPECTED_CLASS_STRING,
				$this
			)
		);
	}



	/ **
	 * @covers ::ValidateClass
	 * /
	public function testValidateClass() {
//TODO: needs assert "This test did not perform any assertions"
		GeneralUtils::ValidateClass(
			self::EXPECTED_CLASS_STRING,
			$this
		);
$this->assertTrue(TRUE);
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function testValidateClass_NullString() {
		try {
			GeneralUtils::ValidateClass(
				NULL,
				$this
			);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(
				'classname not defined',
				$e->getMessage()
			);
			return;
		}
		$this->assertTrue(FALSE, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function testValidateClass_BlankString() {
		try {
			GeneralUtils::ValidateClass(
				'',
				$this
			);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(
				'classname not defined',
				$e->getMessage()
			);
			return;
		}
		$this->assertTrue(FALSE, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function testValidateClass_NullObject() {
		try {
			GeneralUtils::ValidateClass(
				self::EXPECTED_CLASS_STRING,
				NULL
			);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(
				'object not defined',
				$e->getMessage()
			);
			return;
		}
		$this->assertTrue(FALSE, 'Failed to throw expected exception');
	}
	/ **
	 * @covers ::ValidateClass
	 * /
	public function testValidateClass_ClassType() {
		try {
			GeneralUtils::ValidateClass(
				self::EXPECTED_CLASS_STRING.'_invalid',
				$this
			);
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals(
				'Class object isn\'t of type '.
					self::EXPECTED_CLASS_STRING.'_invalid',
				$e->getMessage()
			);
			return;
		}
		$this->assertTrue(FALSE, 'Failed to throw expected exception');
	}
* /



}
*/
