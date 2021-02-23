<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\pxnDefines as xDef;


/**
 * @coversDefaultClass \pxn\phpUtils\pxnDefines
 */
class test_pxnDefines extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::init
	 */
	public function test_Values(): void {
		xDef::init();
		// phpUtils version
		$this->assertNotEmpty(xDef::phpUtilsVersion);
		$this->assertTrue(\str_starts_with(haystack: xDef::phpUtilsVersion, needle: "0."));
		// php version
		$this->assertGreaterThanOrEqual(expected: 80000, actual: xDef::PHP_MIN_VERSION);
		$this->assertLessThan(          expected: 90000, actual: xDef::PHP_MIN_VERSION);
		// common characters
		$this->assertEquals(expected: xDef::DIR_SEP, actual: \DIRECTORY_SEPARATOR);
		$this->assertEquals(expected: xDef::NEWLINE, actual: xDef::EOL           );
		$this->assertEquals(expected: xDef::TAB,     actual: "\t"                );
		$this->assertEquals(expected: xDef::QUOTE_S, actual: '\''                );
		$this->assertEquals(expected: xDef::QUOTE_D, actual: "\""                );
		$this->assertEquals(expected: xDef::ACCENT,  actual: '`'                 );
		// common numbers
		$this->assertGreaterThanOrEqual(expected: 2147483647, actual: xDef::INT_MAX);
		$this->assertLessThanOrEqual(   expected:-2147483648, actual: xDef::INT_MIN);
		$this->assertEquals(            expected: xDef::NET_PORT_MAX, actual: 65535);
		// number of seconds
		$this->assertEquals(expected: xDef::S_MS,     actual:               0.001);
		$this->assertEquals(expected: xDef::S_SECOND, actual:                   1);
		$this->assertEquals(expected: xDef::S_MINUTE, actual:                  60);
		$this->assertEquals(expected: xDef::S_HOUR,   actual:                3600);
		$this->assertEquals(expected: xDef::S_DAY,    actual:               86400);
		$this->assertEquals(expected: xDef::S_WEEK,   actual:              604800);
		$this->assertEquals(expected: xDef::S_MONTH,  actual:             2592000);
		$this->assertEquals(expected: xDef::S_YEAR,   actual:            31536000);
		// number of bytes
		$this->assertEquals(expected: xDef::KB,       actual:                1024);
		$this->assertEquals(expected: xDef::MB,       actual:           1024*1024);
		$this->assertEquals(expected: xDef::GB,       actual:      1024*1024*1024);
		$this->assertEquals(expected: xDef::TB,       actual: 1024*1024*1024*1024);
	}



}
