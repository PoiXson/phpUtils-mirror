<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
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
		// enable debug
		Debug::Reset();
		Debug::debug(true, 'unit-test');
		$this->assertTrue(Debug::debug());
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(), needle: 'unit-test'));
		// disable debug
		Debug::debug(false, 'unit-test');
		$this->assertFalse(Debug::debug());
		$this->assertEquals(null, Debug::desc());
		// set desc
		Debug::desc('desc()-test');
		$this->assertEquals(null, Debug::desc());
		Debug::debug(true);
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(), needle: 'unit-test ; desc()-test'));
		// enable debug
		Debug::Reset();
		Debug::debug(true, 'unit-test');
		$this->assertTrue(Debug::debug());
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(), needle: 'unit-test'));
		// disable debug
		Debug::debug(false, 'unit-test');
		$this->assertFalse(Debug::debug());
		$this->assertEquals(null, Debug::desc());
		// set desc
		Debug::debug(true, 'desc-test-1');
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(),              needle: 'unit-test ; desc-test-1'              ));
		$this->assertTrue(\str_ends_with(haystack: Debug::desc('desc-test-2'), needle: 'unit-test ; desc-test-1 ; desc-test-2'));
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(),              needle: 'unit-test ; desc-test-1 ; desc-test-2'));
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
		$this->assertTrue(\str_ends_with(haystack: Debug::desc(), needle: 'unit-test'));
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
