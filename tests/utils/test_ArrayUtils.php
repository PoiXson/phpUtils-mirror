<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\ArrayUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\ArrayUtils
 */
class test_ArrayUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::Flatten
	 */
	public function test_Flatten(): void {
		// strings
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::Flatten( 'a', 'b', 'c' )
		);
		// deep
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::Flatten( 'a', ['b', [['c']]] )
		);
		// null
		$this->assertEquals(
			[ null ],
			ArrayUtils::Flatten( null )
		);
		$this->assertEquals(
			[ null, null, null ],
			ArrayUtils::Flatten( null, [null, [[null]]] )
		);
		// blank string
		$this->assertEquals(
			['', '', ''],
			ArrayUtils::Flatten( '', ['', [['']]] )
		);
		// zero 0
		$this->assertEquals(
			[0, 0, 0],
			ArrayUtils::Flatten( 0, [0, [[0]]] )
		);
		// empty arrays
		$this->assertEquals(
			[],
			ArrayUtils::Flatten( [], [ [[]], [] ] )
		);
	}



	/**
	 * @covers ::TrimFlat
	 */
	public function test_TrimFlat(): void {
		// strings
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::TrimFlat( 'a', 'b', 'c', '', null )
		);
		// deep
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::TrimFlat( 'a', ['b', [['c']]], '', null )
		);
		// null
		$this->assertEquals(
			[],
			ArrayUtils::TrimFlat( null )
		);
		$this->assertEquals(
			[],
			ArrayUtils::TrimFlat( null, [null, [[null]]] )
		);
		// blank string
		$this->assertEquals(
			[],
			ArrayUtils::TrimFlat( '', ['', [['']]] )
		);
		// zero 0
		$this->assertEquals(
			[0, 0, 0],
			ArrayUtils::TrimFlat( 0, [0, [[0]]] )
		);
		// empty arrays
		$this->assertEquals(
			[],
			ArrayUtils::TrimFlat( [], [ [[]], [] ] )
		);
	}



/*
	/ **
	 * @covers ::Trim
	 * /
	public function test_Trim() {
		// strings
		$array = [ 'a', 'b', 'c', '', null ];
		ArrayUtils::Trim($array);
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			$array
		);
		// deep
		$array = [ 'a', 'b', ['c'], [[]], '', null ];
		ArrayUtils::Trim($array);
		$this->assertEquals(
			[ 'a', 'b', ['c'], [[]] ],
			$array
		);
		// null
		$array = null;
		ArrayUtils::Trim($array);
		$this->assertEquals(
			null,
			$array
		);
		// blank/empty
		$array = [ 0, false, '', [] ];
		ArrayUtils::Trim($array);
		$this->assertEquals(
			[ 0, false ],
			$array
		);
		// string (not array)
		$array = 'abc';
		ArrayUtils::Trim($array);
		$this->assertEquals(
			[ 'abc' ],
			$array
		);
	}
*/



	/**
	 * @covers ::MakeArray
	 */
	public function test_MakeArray(): void {
		// clean
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::MakeArray([ 'a', 'b', 'c' ])
		);
		// null
		$this->assertEquals(
			[],
			ArrayUtils::MakeArray(null)
		);
		// empty string
		$this->assertEquals(
			[''],
			ArrayUtils::MakeArray('')
		);
		// string
		$this->assertEquals(
			[ 'abc' ],
			ArrayUtils::MakeArray('abc')
		);
	}



	/**
	 * @covers ::Explode
	 */
	public function test_Explode(): void {
		// simple string
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::Explode( 'a b,c' )
		);
		// string
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			ArrayUtils::Explode('a-b-++=c', '-', '=', '+')
		);
		// array
		$this->assertEquals(
			[ 'abc' ],
			ArrayUtils::Explode([ 'abc' ])
		);
	}



}
