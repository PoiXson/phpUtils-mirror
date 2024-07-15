<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\logger\xLog;


/**
 * @coversDefaultClass \pxn\phpUtils\Logger\xLog
 */
class test_Logger extends \PHPUnit\Framework\TestCase {



//	public function test_ValidateName() {
//		$expected = 'LoggerTest';
//		// null
//		$result = xLog::ValidateName(null);
//		$this->assertEquals($expected, $result);
//		// blank
//		$result = xLog::ValidateName('');
//		$this->assertEquals($expected, $result);
//		// string
//		$result = xLog::ValidateName('testname');
//		$this->assertEquals('testname', $result);
//	}



	/**
	 * @covers ::GetRoot
	 */
	public function test_Instances() {
		$a = xLog::GetRoot('a');
		$b = xLog::GetRoot('b');
		$this->assertNotNull($a);
		$this->assertNotNull($b);
		$this->assertTrue($a !== $b);
		$this->assertTrue($a === xLog::GetRoot('a'));
		unset($a, $b);
	}



	/**
	 * @covers ::GetRoot
	 */
	public function test_Empty() {
		$a = xLog::GetRoot();
		$b = xLog::GetRoot('');
		$c = xLog::GetRoot(null);
		$this->assertTrue ($a === $b);
		$this->assertTrue ($a === $c);
		$this->assertFalse($a === xLog::GetRoot('test'));
	}



	/**
	 * @covers ::GetRoot
	 * @covers ::set
	 */
	public function test_Set() {
		$this->assertFalse(xLog::GetRoot('a') === xLog::GetRoot('b'));
		xLog::set(
			'a',
			xLog::GetRoot('b')
		);
		$this->assertTrue (xLog::GetRoot('a') === xLog::GetRoot('b'));
	}



}
