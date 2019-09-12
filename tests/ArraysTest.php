<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\Arrays;


/**
 * @coversDefaultClass \pxn\phpUtils\Arrays
 */
class ArraysTest extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::Flatten
	 */
	public function testFlatten() {
		// strings
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::Flatten( 'a', 'b', 'c' )
		);
		// deep
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::Flatten( 'a', ['b', [['c']]] )
		);
		// null
		$this->assertEquals(
			[ NULL ],
			Arrays::Flatten( NULL )
		);
		$this->assertEquals(
			[ NULL, NULL, NULL ],
			Arrays::Flatten( NULL, [NULL, [[NULL]]] )
		);
		// blank string
		$this->assertEquals(
			['', '', ''],
			Arrays::Flatten( '', ['', [['']]] )
		);
		// zero 0
		$this->assertEquals(
			[0, 0, 0],
			Arrays::Flatten( 0, [0, [[0]]] )
		);
		// empty arrays
		$this->assertEquals(
			[],
			Arrays::Flatten( [], [ [[]], [] ] )
		);
	}



	/**
	 * @covers ::TrimFlat
	 */
	public function testTrimFlat() {
		// strings
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::TrimFlat( 'a', 'b', 'c', '', NULL )
		);
		// deep
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::TrimFlat( 'a', ['b', [['c']]], '', NULL )
		);
		// null
		$this->assertEquals(
			[],
			Arrays::TrimFlat( NULL )
		);
		$this->assertEquals(
			[],
			Arrays::TrimFlat( NULL, [NULL, [[NULL]]] )
		);
		// blank string
		$this->assertEquals(
			[],
			Arrays::TrimFlat( '', ['', [['']]] )
		);
		// zero 0
		$this->assertEquals(
			[0, 0, 0],
			Arrays::TrimFlat( 0, [0, [[0]]] )
		);
		// empty arrays
		$this->assertEquals(
			[],
			Arrays::TrimFlat( [], [ [[]], [] ] )
		);
	}



/*
	/ **
	 * @covers ::Trim
	 * /
	public function testTrim() {
		// strings
		$array = [ 'a', 'b', 'c', '', NULL ];
		Arrays::Trim($array);
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			$array
		);
		// deep
		$array = [ 'a', 'b', ['c'], [[]], '', NULL ];
		Arrays::Trim($array);
		$this->assertEquals(
			[ 'a', 'b', ['c'], [[]] ],
			$array
		);
		// null
		$array = NULL;
		Arrays::Trim($array);
		$this->assertEquals(
			NULL,
			$array
		);
		// blank/empty
		$array = [ 0, FALSE, '', [] ];
		Arrays::Trim($array);
		$this->assertEquals(
			[ 0, FALSE ],
			$array
		);
		// string (not array)
		$array = 'abc';
		Arrays::Trim($array);
		$this->assertEquals(
			[ 'abc' ],
			$array
		);
	}
*/



	/**
	 * @covers ::MakeArray
	 */
	public function testMakeArray() {
		// clean
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::MakeArray([ 'a', 'b', 'c' ])
		);
		// null
		$this->assertEquals(
			NULL,
			Arrays::MakeArray(NULL)
		);
		// empty string
		$this->assertEquals(
			[''],
			Arrays::MakeArray('')
		);
		// string
		$this->assertEquals(
			[ 'abc' ],
			Arrays::MakeArray('abc')
		);
	}



	/**
	 * @covers ::Explode
	 */
	public function testExplode() {
		// simple string
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::Explode( 'a b,c' )
		);
		// string
		$this->assertEquals(
			[ 'a', 'b', 'c' ],
			Arrays::Explode('a-b-++=c', '-', '=', '+')
		);
		// array
		$this->assertEquals(
			[ 'abc' ],
			Arrays::Explode([ 'abc' ])
		);
	}



}
