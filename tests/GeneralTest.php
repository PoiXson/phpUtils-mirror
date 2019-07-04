<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\General;


/**
 * @coversDefaultClass \pxn\phpUtils\General
 */
class GeneralTest extends \PHPUnit\Framework\TestCase {

	const EXPECTED_CLASS_STRING = 'pxn\\phpUtils\\tests\\GeneralTest';



	public function testArray() {
		$this->assertEmpty([]);
	}



	public function testClassName() {
		$this->assertEquals(
			self::EXPECTED_CLASS_STRING,
			\get_class($this)
		);
	}



	/**
	 * @covers ::castType
	 */
	public function testCastType() {
		// null
		$this->assertSame(
			123,
			General::castType(123, NULL)
		);
		// string
		$this->assertSame(
			'123',
			General::castType(123, 's')
		);
		// integer
		$this->assertSame(
			123,
			General::castType('123', 'i')
		);
		// long
		$this->assertSame(
			123,
			General::castType('123', 'l')
		);
		// float
		$this->assertSame(
			123e0,
			General::castType('123', 'f')
		);
		// double
		$this->assertSame(
			123.0,
			General::castType('123', 'd')
		);
		// boolean
		$this->assertSame(
			TRUE,
			General::castType('t', 'b')
		);
		// unknown
		$this->assertSame(
			'abc',
			General::castType('abc', 'z')
		);
	}



	/**
	 * @covers ::castBoolean
	 */
	public function testCastBoolean() {
		// null
		$this->assertNull( General::castBoolean(NULL)       );
		// boolean
		$this->assertTrue(  General::castBoolean(TRUE)      );
		$this->assertFalse( General::castBoolean(FALSE)     );
		// true/false
		$this->assertTrue(  General::castBoolean('true')    );
		$this->assertFalse( General::castBoolean('false')   );
		// yes/no
		$this->assertTrue(  General::castBoolean('yes')     );
		$this->assertFalse( General::castBoolean('no')      );
		// allow/deny
		$this->assertTrue(  General::castBoolean('allow')   );
		$this->assertFalse( General::castBoolean('deny')    );
		// enable/disable
		$this->assertTrue(  General::castBoolean('enable')  );
		$this->assertFalse( General::castBoolean('disable') );
		// on/off
		$this->assertTrue(  General::castBoolean('on')      );
		$this->assertFalse( General::castBoolean('off')     );
		// 1/0
		$this->assertTrue(  General::castBoolean(1)         );
		$this->assertFalse( General::castBoolean(0)         );
	}



	/**
	 * @covers ::getVar
	 */
	public function testGetVar() {
		$key = 'abcd';
		$_GET[$key]     = '1234';
		$_POST[$key]    = '5678';
		$_COOKIE[$key]  = '9123';
		$_SESSION[$key] = '4567';
		// string/get
		$this->assertEquals(
			'1234',
			General::getVar($key, 's', 'g')
		);
		// string/post
		$this->assertEquals(
			'5678',
			General::getVar($key, 's', 'p')
		);
		// string/get/post
		$this->assertEquals(
			'5678',
			General::getVar($key, 's')
		);
		// string/cookie
		$this->assertEquals(
			'9123',
			General::getVar($key, 's', 'c')
		);
		// string/session
		$this->assertEquals(
			'4567',
			General::getVar($key, 's', 's')
		);
		// unknown source
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown value source: z');
		General::getVar($key, 's', 'z');
		unset($_GET[$key]);
		unset($_POST[$key]);
		unset($_COOKIE[$key]);
		unset($_SESSION[$key]);
	}



	/**
	 * @covers ::get
	 */
	public function testGet() {
		$key = 'test';
		$this->assertNull( General::get($key) );
		$_GET[$key] = 'abc';
		$this->assertEquals( 'abc', General::get($key) );
		unset($_GET[$key]);
	}
	/**
	 * @covers ::post
	 */
	public function testPost() {
		$key = 'test';
		$this->assertNull( General::post($key) );
		$_POST[$key] = 'abc';
		$this->assertEquals( 'abc', General::post($key) );
		unset($_POST[$key]);
	}
	/**
	 * @covers ::cookie
	 */
	public function testCookie() {
		$key = 'test';
		$this->assertNull( General::cookie($key) );
		$_COOKIE[$key] = 'abc';
		$this->assertEquals( 'abc', General::cookie($key) );
		unset($_COOKIE[$key]);
	}
	/**
	 * @covers ::session
	 */
	public function testSession() {
		$key = 'test';
		$this->assertNull( General::session($key) );
		$_SESSION[$key] = 'abc';
		$this->assertEquals( 'abc', General::session($key) );
		unset($_SESSION[$key]);
	}



/*
	/ **
	 * @covers ::ParseModRewriteVars
	 * /
	public function testParseModRewriteVars() {
		$this->markTestIncomplete('Unfinished ParseModRewriteVars test');
//		$_SERVER[''] = '';
//		General::ParseModRewriteVars();
//		$this->assertEquals(
//			'',
//			''
//		);
	}
*/



	/**
	 * @covers ::getTimestamp
	 */
	public function testGetTimestamp() {
		$tim = General::getTimestamp();
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000.0, $tim);
		$tim = General::getTimestamp(0);
		$this->assertIsNumeric($tim);
		$this->assertGreaterThan(1500000000, $tim);
	}
	/**
	 * @covers ::Sleep
	 */
	public function testSleep() {
		$timA = General::getTimestamp();
		General::Sleep(10);
		$timB = General::getTimestamp();
		$this->assertGreaterThanOrEqual(0.009, $timB - $timA);
	}



/*
	public function testTimestamp() {
		// all timings are in ms
		$this->PerformTimestampTest(
			10, // sleep time
			8,  // min expected time
			30  // max expected time
		);
	}
	/ **
	 * @covers ::getTimestamp
	 * @covers ::Sleep
	 * /
	private function PerformTimestampTest($sleepTime, $minExpected, $maxExpected) {
		$a = General::getTimestamp();
		General::Sleep( (double)$sleepTime );
		$b = General::getTimestamp();
		$c = $b - $a;
		// > 1
		$this->assertGreaterThan(1.0, $a);
		$this->assertGreaterThan(1.0, $b);
		// within 5-15ms
		$this->assertGreaterThan( ((double)$minExpected) / 1000.0, $c);
		$this->assertLessThan(    ((double)$maxExpected) / 1000.0, $c);
	}
*/



	/**
	 * @covers ::GDSupported
	 */
	public function testGDSupported() {
		$this->assertEquals(
			\function_exists('imagepng'),
			General::GDSupported()
		);
	}



/*
	/ **
	 * @covers ::InstanceOfClass
	 * /
	public function testInstanceOfClass() {
		$this->assertTrue(
			General::InstanceOfClass(
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
		General::ValidateClass(
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
			General::ValidateClass(
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
			General::ValidateClass(
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
			General::ValidateClass(
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
			General::ValidateClass(
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
*/



}
