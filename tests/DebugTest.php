<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\Debug;


/**
 * @coversDefaultClass \pxn\phpUtils\Debug
 */
class DebugTest extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::isDebug
	 * @covers ::setDebug
	 * @covers ::getDesc
	 * @covers ::setDesc
	 * @covers ::EnableDisable
	 */
	public function testGetSetDebug(): void {
		$originalDebug = Debug::isDebug();
		$originalDesc  = Debug::getDesc();
		// enable debug
		Debug::setDebug(TRUE, 'unit test');
		$this->assertTrue(Debug::isDebug());
		$this->assertEquals('unit test', Debug::getDesc());
//		// null
//		Debug::setDebug(NULL, 'null');
//		$this->assertTrue(Debug::isDebug());
//		$this->assertEquals('unit test', Debug::getDesc());
		// disable debug
		Debug::setDebug(FALSE, 'unit test');
		$this->assertFalse(Debug::isDebug());
		$this->assertEquals('unit test', Debug::getDesc());
		// set desc
		Debug::setDesc('setDesc() test');
		$this->assertEquals('setDesc() test', Debug::getDesc());
		// restore debug
		Debug::setDebug($originalDebug, $originalDesc);
		$this->assertEquals($originalDebug, Debug::isDebug());
	}



	/**
	 * @covers ::debug
	 * @covers ::desc
	 * @covers ::EnableDisable
	 */
	public function testDebug(): void {
		$originalDebug = Debug::debug();
		$originalDesc  = Debug::desc();
		// enable debug
		Debug::debug(TRUE, 'unit test');
		$this->assertTrue(Debug::debug());
		$this->assertEquals('unit test', Debug::desc());
		// disable debug
		Debug::debug(FALSE, 'unit test');
		$this->assertFalse(Debug::debug());
		$this->assertEquals('unit test', Debug::desc());
		// set desc
		Debug::desc('desc test 1');
		$this->assertEquals('desc test 1', Debug::desc('desc test 2'));
		$this->assertEquals('desc test 2', Debug::desc());
		// restore debug
		Debug::debug($originalDebug, $originalDesc);
		$this->assertEquals($originalDebug, Debug::debug());
	}



	/**
	 * @covers \debug
	 */
	public function testGlobalDebug(): void {
		$originalDebug = Debug::isDebug();
		$originalDesc  = Debug::getDesc();
		// enable debug
		\debug(TRUE, 'unit test');
		$this->assertTrue(\debug());
		$this->assertEquals('unit test', Debug::getDesc());
		// disable debug
		\debug(FALSE, 'unit test');
		$this->assertFalse(\debug());
		$this->assertEquals('unit test', Debug::getDesc());
		// restore debug
		Debug::setDebug($originalDebug, $originalDesc);
		$this->assertEquals($originalDebug, Debug::isDebug());
	}



}
