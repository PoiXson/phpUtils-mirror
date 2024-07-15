<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\Debug;


/**
 * @coversDefaultClass \pxn\phpUtils\Debug
 */
class test_Debug extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::debug
	 * @covers ::desc
	 * @covers ::EnableDisable
	 */
	public function test_GetSetDebug(): void {
		$originalDebug = Debug::debug();
		$originalDesc  = Debug::desc();
		$this->assertEquals(null, $originalDesc);
		// enable debug
		Debug::Reset();
		Debug::debug(true, 'unit-test');
		$this->assertTrue(Debug::debug());
		$this->assertEquals('unit-test', Debug::desc());
		// disable debug
		Debug::debug(false, 'unit-test');
		$this->assertFalse(Debug::debug());
		$this->assertEquals(null, Debug::desc());
		// set desc
		Debug::desc('desc()-test');
		$this->assertEquals(null, Debug::desc());
		Debug::debug(true);
		$this->assertEquals('unit-test; desc()-test', Debug::desc());
		// enable debug
		Debug::Reset();
		Debug::debug(true, 'unit-test');
		$this->assertTrue(Debug::debug());
		$this->assertEquals('unit-test', Debug::desc());
		// disable debug
		Debug::debug(false, 'unit-test');
		$this->assertFalse(Debug::debug());
		$this->assertEquals(null, Debug::desc());
		// set desc
		Debug::debug(true, 'desc-test-1');
		$this->assertEquals('unit-test; desc-test-1; desc-test-2', Debug::desc('desc-test-2'));
		$this->assertEquals('unit-test; desc-test-1; desc-test-2', Debug::desc());
		// restore debug
		Debug::Reset();
		Debug::debug($originalDebug, $originalDesc);
		$this->assertEquals($originalDebug, Debug::debug());
	}



	/**
	 * @covers \debug
	 */
	public function test_GlobalDebug(): void {
		$originalDebug = Debug::debug();
		$originalDesc  = Debug::desc();
		// enable debug
		Debug::Reset();
		\debug(true, 'unit-test');
		$this->assertTrue(\debug());
		$this->assertEquals('unit-test', Debug::desc());
		// disable debug
		\debug(false, 'unit-test');
		$this->assertFalse(\debug());
		$this->assertEquals(null, Debug::desc());
		// restore debug
		Debug::Reset();
		Debug::debug($originalDebug, $originalDesc);
		$this->assertEquals($originalDebug, Debug::debug());
	}



}
