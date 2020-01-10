<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\Strings;


/**
 * @coversDefaultClass \pxn\phpUtils\Strings
 */
class StringsTest extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::mb_ucfirst
	 */
	public function testMbUcFirst() {
		$data = 'Abc Def Ghi jkl mnp qrs tuv wxyz';
		$this->assertEquals(
			'Abc def ghi jkl mnp qrs tuv wxyz',
			Strings::mb_ucfirst($data)
		);
		$this->assertEquals(
			'Abc def ghi jkl mnp qrs tuv wxyz',
			Strings::mb_ucfirst($data, TRUE)
		);
		$this->assertEquals(
			'Abc Def Ghi jkl mnp qrs tuv wxyz',
			Strings::mb_ucfirst($data, FALSE)
		);
	}



	#################
	## Trim String ##
	#################



	const TRIM_TEST_DATA = "\t--   == test ==   --\t";



	/**
	 * @covers ::Trim
	 */
	public function testTrim(): void {
		$this->assertEquals(
			'--   == test ==   --',
			Strings::Trim(self::TRIM_TEST_DATA)
		);
		$this->assertEquals(
			'test',
			Strings::Trim(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")
		);
		$this->assertEquals(
			'test',
			Strings::Trim(self::TRIM_TEST_DATA, ' ', '--', '==', "\t")
		);
		$this->assertEquals(
			'--   == test ==   --',
			Strings::Trim(self::TRIM_TEST_DATA, ' ', '=', "\t")
		);
		$this->assertEquals(
			'123',
			Strings::Trim('01230', '0')
		);
		$this->assertEquals(
			'',
			Strings::Trim('01230', '0', '1', '2', '3')
		);
		$this->assertEquals(
			'123',
			Strings::Trim('123', '  ', '')
		);
	}
	/**
	 * @covers ::TrimFront
	 */
	public function testTrimFront(): void {
		$this->assertEquals(
			"--   == test ==   --\t",
			Strings::TrimFront(self::TRIM_TEST_DATA)
		);
		$this->assertEquals(
			"test ==   --\t",
			Strings::TrimFront(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")
		);
		$this->assertEquals(
			"test ==   --\t",
			Strings::TrimFront(self::TRIM_TEST_DATA, ' ', '--', '==', "\t")
		);
		$this->assertEquals(
			"--   == test ==   --\t",
			Strings::TrimFront(self::TRIM_TEST_DATA, ' ', '=', "\t")
		);
		$this->assertEquals(
			'1230',
			Strings::TrimFront('01230', '0')
		);
		$this->assertEquals(
			'',
			Strings::TrimFront('01230', '0', '1', '2', '3')
		);
		$this->assertEquals(
			'123',
			Strings::TrimFront('123', '  ', '')
		);
	}
	/**
	 * @covers ::TrimEnd
	 */
	public function testTrimEnd(): void {
		$this->assertEquals(
			"\t--   == test ==   --",
			Strings::TrimEnd(self::TRIM_TEST_DATA)
		);
		$this->assertEquals(
			"\t--   == test",
			Strings::TrimEnd(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")
		);
		$this->assertEquals(
			"\t--   == test",
			Strings::TrimEnd(self::TRIM_TEST_DATA, ' ', '--', '==', "\t")
		);
		$this->assertEquals(
			"\t--   == test ==   --",
			Strings::TrimEnd(self::TRIM_TEST_DATA, ' ', '=', "\t")
		);
		$this->assertEquals(
			'0123',
			Strings::TrimEnd('01230', '0')
		);
		$this->assertEquals(
			'',
			Strings::TrimEnd('01230', '0', '1', '2', '3')
		);
		$this->assertEquals(
			'123',
			Strings::TrimEnd('123', '  ', '')
		);
	}



	/**
	 * @covers ::TrimQuotes
	 */
	public function testTrimQuotes(): void {
		// matching quotes
		$this->assertEquals('test', Strings::TrimQuotes( '"test"' ));
		$this->assertEquals('test', Strings::TrimQuotes( "'test'" ));
		$this->assertEquals('test', Strings::TrimQuotes('``test``'));
		// mis-matched quotes
		$this->assertEquals(  'test"',  Strings::TrimQuotes(  '"test""'));
		$this->assertEquals( '"test',   Strings::TrimQuotes( '""test"' ));
		$this->assertEquals(  "test'",  Strings::TrimQuotes(   "test'" ));
		$this->assertEquals( "'test",   Strings::TrimQuotes(  "'test"  ));
		$this->assertEquals(  'test``', Strings::TrimQuotes(   'test``'));
		$this->assertEquals('``test',   Strings::TrimQuotes('"``test"' ));
		// blank strings
		$this->assertEquals('', Strings::TrimQuotes('""'));
		$this->assertEquals('', Strings::TrimQuotes("''"));
		$this->assertEquals('', Strings::TrimQuotes('``'));
	}



/*
	/ **
	 * @covers ::MergeDuplicates
	 * /
	public function testMergeDuplicates() {
	}
*/



//TODO
/*
	/ **
	 * @covers ::WrapLines
	 * /
	public function testWrapLines() {
		$this->assertEquals(
			"abcdefghij\nklmnopqrst\nuvwxyz",
			Strings::WrapLines(
				'abcdefghijklmnopqrstuvwxyz',
				10
			)
		);
		$this->assertEquals(
			"abcdefghij\nklmnopqr\nst\nuvwxyz",
			Strings::WrapLines(
				'abcdefghij klmnopqr st uvwxyz',
				10
			)
		);
	}
*/



	################
	## Pad String ##
	################



	const PAD_DATA = 'abc';



	/**
	 * @covers ::PadLeft
	 * @covers ::getPadding
	 */
	public function testPadLeft(): void {
		$this->assertEquals('  abc', Strings::PadLeft(self::PAD_DATA, 5));
		$this->assertEquals('  abc', Strings::PadLeft(self::PAD_DATA, 5, ''));
		$this->assertEquals('abc',   Strings::PadLeft(self::PAD_DATA, 2));
		$this->assertEquals('-+abc', Strings::PadLeft(self::PAD_DATA, 5, '-+'));
	}
	/**
	 * @covers ::PadRight
	 * @covers ::getPadding
	 */
	public function testPadRight(): void {
		$this->assertEquals('abc  ', Strings::PadRight(self::PAD_DATA, 5));
		$this->assertEquals('abc  ', Strings::PadRight(self::PAD_DATA, 5, ''));
		$this->assertEquals('abc',   Strings::PadRight(self::PAD_DATA, 2));
		$this->assertEquals('abc-+', Strings::PadRight(self::PAD_DATA, 5, '-+'));
	}
	/**
	 * @covers ::PadCenter
	 * @covers ::getPadding
	 */
	public function testPadCenter(): void {
		$this->assertEquals(' abc ', Strings::PadCenter(self::PAD_DATA, 5));
		$this->assertEquals(' abc ', Strings::PadCenter(self::PAD_DATA, 5, ''));
		$this->assertEquals('abc',   Strings::PadCenter(self::PAD_DATA, 2));
		$this->assertEquals('-+abc-+', Strings::PadCenter(self::PAD_DATA, 5, '-+'));
	}



/*
	/ **
	 * @covers ::PadColumns
	 * /
	public function testPadColumns() {
	}
*/



	######################
	## Starts/Ends With ##
	######################



	const STARTS_ENDS_DATA = 'abcdefg';



	/**
	 * @covers ::StartsWith
	 */
	public function testStartsWith(): void {
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, ''));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, self::STARTS_ENDS_DATA.'123'));
		// case-sensitive
		$this->assertTrue (Strings::StartsWith(self::STARTS_ENDS_DATA, 'abc', FALSE));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, 'Abc', FALSE));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, 'bcd', FALSE));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, 'Bcd', FALSE));
		// ignore case
		$this->assertTrue (Strings::StartsWith(self::STARTS_ENDS_DATA, 'abc', TRUE));
		$this->assertTrue (Strings::StartsWith(self::STARTS_ENDS_DATA, 'Abc', TRUE));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, 'bcd', TRUE));
		$this->assertFalse(Strings::StartsWith(self::STARTS_ENDS_DATA, 'Bcd', TRUE));
	}
	/**
	 * @covers ::EndsWith
	 */
	public function testEndsWith(): void {
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, ''));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, self::STARTS_ENDS_DATA.'123'));
		// case-sensitive
		$this->assertTrue (Strings::EndsWith(self::STARTS_ENDS_DATA, 'efg', FALSE));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, 'Efg', FALSE));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, 'def', FALSE));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, 'Def', FALSE));
		// ignore case
		$this->assertTrue (Strings::EndsWith(self::STARTS_ENDS_DATA, 'efg', TRUE));
		$this->assertTrue (Strings::EndsWith(self::STARTS_ENDS_DATA, 'Efg', TRUE));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, 'def', TRUE));
		$this->assertFalse(Strings::EndsWith(self::STARTS_ENDS_DATA, 'Def', TRUE));
	}



	#####################
	## Force Start/End ##
	#####################



	const FORCE_DATA   = 'test';
	const FORCE_APPEND = '123';



	/**
	 * @covers ::ForceStartsWith
	 */
	public function testForceStartsWith(): void {
		$this->assertEquals(
			self::FORCE_DATA,
			Strings::ForceStartsWith(self::FORCE_DATA, '')
		);
		$this->assertEquals(
			self::FORCE_APPEND . self::FORCE_DATA,
			Strings::ForceStartsWith(self::FORCE_DATA, self::FORCE_APPEND)
		);
		$this->assertEquals(
			self::FORCE_APPEND . self::FORCE_DATA,
			Strings::ForceStartsWith(self::FORCE_APPEND . self::FORCE_DATA, self::FORCE_APPEND)
		);
	}
	/**
	 * @covers ::ForceEndsWith
	 */
	public function testForceEndsWith(): void {
		$this->assertEquals(
			self::FORCE_DATA,
			Strings::ForceEndsWith(self::FORCE_DATA, '')
		);
		$this->assertEquals(
			self::FORCE_DATA . self::FORCE_APPEND,
			Strings::ForceEndsWith(self::FORCE_DATA, self::FORCE_APPEND)
		);
		$this->assertEquals(
			self::FORCE_DATA . self::FORCE_APPEND,
			Strings::ForceEndsWith(self::FORCE_DATA . self::FORCE_APPEND, self::FORCE_APPEND)
		);
	}



	##############
	## Contains ##
	##############



	/**
	 * @covers ::Contains
	 */
	public function testContains(): void {
		$this->assertFalse(Strings::Contains(self::TRIM_TEST_DATA, ''         ));
		$this->assertTrue (Strings::Contains(self::TRIM_TEST_DATA, 'test'       ));
		$this->assertTrue (Strings::Contains(self::TRIM_TEST_DATA, 'Test', TRUE ));
		$this->assertFalse(Strings::Contains(self::TRIM_TEST_DATA, 'Test', FALSE));
	}



	##############
	## Get Part ##
	##############



/*
	const PART_TEST_DATA  = "aaa bbb  ccc\tddd";

	/ **
	 * @covers ::findPart
	 * /
	public function test_findPart() {
		$data = self::PART_TEST_DATA;
		// find space
		$result = Strings::findPart($data, ' ');
		$this->assertTrue(\is_array($result));
		$this->assertEquals(3,   $result['POS']);
		$this->assertEquals(' ', $result['PAT']);
		// find double-space
		$result = Strings::findPart($data, '  ');
		$this->assertTrue(\is_array($result));
		$this->assertEquals(7,    $result['POS']);
		$this->assertEquals('  ', $result['PAT']);
		// find tab
		$result = Strings::findPart($data, "\t");
		$this->assertTrue(\is_array($result));
		$this->assertEquals(12,   $result['POS']);
		$this->assertEquals("\t", $result['PAT']);
		// find nothing
		$result = Strings::findPart($data, '-');
		$this->assertFalse(\is_array($result));
		$this->assertEquals(NULL, $result);
		unset($data, $result);
	}
	/ **
	 * @covers ::peakPart
	 * @covers ::grabPart
	 * /
	public function test_peakPart_grapPart() {
		$data = self::PART_TEST_DATA;
		// aaa
		$this->assertEquals('aaa', Strings::peakPart($data, ' '));
		$this->assertEquals('aaa', Strings::grabPart($data, ' '));
		$this->assertEquals("bbb  ccc\tddd", $data);
		// bbb
		$this->assertEquals('bbb', Strings::peakPart($data, ' '));
		$this->assertEquals('bbb', Strings::grabPart($data, ' '));
		$this->assertEquals("ccc\tddd", $data);
		// ccc
		$this->assertEquals("ccc\tddd", Strings::peakPart($data, ' '));
//		$this->assertEquals('ccc',      Strings::peakPart($data, [' ', "\t"]));
//		$this->assertEquals('ccc',      Strings::grabPart($data, [' ', "\t"]));
		$this->assertEquals('ddd', $data);
		// ddd
		$this->assertEquals('ddd', Strings::peakPart($data, ' '));
		$this->assertEquals('ddd', Strings::grabPart($data, ' '));
		$this->assertEquals('', $data);
		unset($data);
	}
*/



	################
	## File Paths ##
	################



	/**
	 * @covers ::BuildPath
	 */
	public function testBuildPath(): void {
		$this->assertEquals('', Strings::BuildPath());
		$this->assertEquals('home', Strings::BuildPath('', 'home', ''));
		$this->assertEquals( 'home/user/Desktop',  Strings::BuildPath(     'home',   'user',   'Desktop'     ));
		$this->assertEquals('/home/user/Desktop',  Strings::BuildPath('/', 'home',   'user',   'Desktop'     ));
		$this->assertEquals('/home/user/Desktop/', Strings::BuildPath(    '/home',   'user',   'Desktop', '/'));
		$this->assertEquals('/home/user/Desktop/', Strings::BuildPath(    '/home/', '/user/', '/Desktop/'    ));
	}



	/**
	 * @covers ::CommonPath
	 */
	public function testCommonPath(): void {
		$this->assertEquals('', Strings::CommonPath('', ''));
		$this->assertEquals('/home/user', Strings::CommonPath('/home/user/Desktop', '/home/user/Documents' ));
		$this->assertEquals('/home/user', Strings::CommonPath('/home/user/',        '/home/user/Documents/'));
		$this->assertEquals('/',          Strings::CommonPath('/usr/bin',           '/etc/profile.d'       ));
	}



}
