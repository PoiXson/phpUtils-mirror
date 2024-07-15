<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;


/**
 * @coversDefaultClass \pxn\phpUtils\BasicEnum
 */
class test_BasicEnum extends \PHPUnit\Framework\TestCase {

	const EXAMPLE_CONSTANTS = [
		'DOG'     => 'woof',
		'CAT'     => 'meow',
		'FISH'    => 'bloop',
		'PENGUIN' => 'sqeuaaaa',
		'BIRD'    => 'churp'
	];



	/**
	 * @covers ::getConstants
	 */
	public function test_Constants() {
		// verify constants exist
		$this->assertEquals(
			print_r(self::EXAMPLE_CONSTANTS,          true),
			print_r(BasicEnumExample::getConstants(), true)
		);
	}



	/**
	 * @covers ::isValidName
	 * @covers ::getByName
	 */
	public function test_ByName() {
		// isValidName
		$this->assertTrue ( BasicEnumExample::isValidName('DOG'       ) );
		$this->assertFalse( BasicEnumExample::isValidName('COW'       ) );
		$this->assertTrue ( BasicEnumExample::isValidName('DOG', false) );
		$this->assertFalse( BasicEnumExample::isValidName('COW', false) );
		$this->assertFalse( BasicEnumExample::isValidName('Dog', false) );
		$this->assertFalse( BasicEnumExample::isValidName('Cow', false) );
		$this->assertTrue ( BasicEnumExample::isValidName('Dog',  true) );
		$this->assertFalse( BasicEnumExample::isValidName('Cow',  true) );
		// getByName
		$this->assertEquals( 'churp', BasicEnumExample::getByName('BIRD'       ) );
		$this->assertEquals( null,    BasicEnumExample::getByName('Brd'        ) );
		$this->assertEquals( 'churp', BasicEnumExample::getByName('BIRD', false) );
		$this->assertEquals( null,    BasicEnumExample::getByName('Bird', false) );
		$this->assertEquals( 'churp', BasicEnumExample::getByName('Bird',  true) );
		$this->assertEquals( null,    BasicEnumExample::getByName('Brd',   true) );
	}



	/**
	 * @covers ::isValidValue
	 * @covers ::getByValue
	 */
	public function test_ByValue() {
		// isValidValue
		$this->assertTrue ( BasicEnumExample::isValidValue('woof'       ) );
		$this->assertFalse( BasicEnumExample::isValidValue('moo'        ) );
		$this->assertTrue ( BasicEnumExample::isValidValue('woof', false) );
		$this->assertFalse( BasicEnumExample::isValidValue('moo',  false) );
		$this->assertFalse( BasicEnumExample::isValidValue('Woof', false) );
		$this->assertFalse( BasicEnumExample::isValidValue('Moo',  false) );
		$this->assertTrue ( BasicEnumExample::isValidValue('Woof',  true) );
		$this->assertFalse( BasicEnumExample::isValidValue('Moo',   true) );
		// getByValue
		$this->assertEquals( 'CAT', BasicEnumExample::getByValue('meow'       ) );
		$this->assertEquals( null,  BasicEnumExample::getByValue('mow'        ) );
		$this->assertEquals( 'CAT', BasicEnumExample::getByValue('meow', false) );
		$this->assertEquals( null,  BasicEnumExample::getByValue('Meow', false) );
		$this->assertEquals( 'CAT', BasicEnumExample::getByValue('Meow',  true) );
		$this->assertEquals( null,  BasicEnumExample::getByValue('mow',   true) );
	}



}
