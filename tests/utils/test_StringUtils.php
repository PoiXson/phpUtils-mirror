<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use pxn\phpUtils\utils\StringUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\StringUtils
 */
class test_StringUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::mb_ucfirst
	 */
	public function test_mb_ucfirst(): void {
		$data = 'Abc Def Ghi jkl mnp qrs tuv wxyz';
		$this->assertEquals(expected: 'Abc def ghi jkl mnp qrs tuv wxyz', actual:   StringUtils::mb_ucfirst($data)       );
		$this->assertEquals(expected: 'Abc def ghi jkl mnp qrs tuv wxyz', actual:   StringUtils::mb_ucfirst($data, FALSE));
		$this->assertEquals(expected: 'Abc Def Ghi jkl mnp qrs tuv wxyz', actual:   StringUtils::mb_ucfirst($data, TRUE) );
	}



	#################
	## Trim String ##
	#################



	const TRIM_TEST_DATA = "\t--   == test ==   --\t";



	/**
	 * @covers ::Trim
	 */
	public function test_Trim(): void {
		$this->assertEquals(expected: '--   == test ==   --', actual: StringUtils::Trim(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: 'test',                 actual: StringUtils::Trim(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: 'test',                 actual: StringUtils::Trim(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: '--   == test ==   --', actual: StringUtils::Trim(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '123',                  actual: StringUtils::Trim('01230', '0')                               );
		$this->assertEquals(expected: '',                     actual: StringUtils::Trim('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                  actual: StringUtils::Trim('123', '  ', '')                            );
	}

	/**
	 * @covers ::TrimFront
	 */
	public function test_TrimFront(): void {
		$this->assertEquals(expected: "--   == test ==   --\t", actual: StringUtils::TrimFront(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: "test ==   --\t",         actual: StringUtils::TrimFront(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: "test ==   --\t",         actual: StringUtils::TrimFront(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: "--   == test ==   --\t", actual: StringUtils::TrimFront(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '1230',                   actual: StringUtils::TrimFront('01230', '0')                               );
		$this->assertEquals(expected: '',                       actual: StringUtils::TrimFront('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                    actual: StringUtils::TrimFront('123', '  ', '')                            );
	}

	/**
	 * @covers ::TrimEnd
	 */
	public function test_TrimEnd(): void {
		$this->assertEquals(expected: "\t--   == test ==   --", actual: StringUtils::TrimEnd(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: "\t--   == test",         actual: StringUtils::TrimEnd(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: "\t--   == test",         actual: StringUtils::TrimEnd(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: "\t--   == test ==   --", actual: StringUtils::TrimEnd(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '0123',                   actual: StringUtils::TrimEnd('01230', '0')                               );
		$this->assertEquals(expected: '',                       actual: StringUtils::TrimEnd('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                    actual: StringUtils::TrimEnd('123', '  ', '')                            );
	}



	/**
	 * @covers ::TrimQuotes
	 */
	public function test_TrimQuotes(): void {
		// matching quotes
		$this->assertEquals(expected: 'test',    actual: StringUtils::TrimQuotes( '"test"' ) );
		$this->assertEquals(expected: 'test',    actual: StringUtils::TrimQuotes( "'test'" ) );
		$this->assertEquals(expected: 'test',    actual: StringUtils::TrimQuotes('``test``') );
		// mis-matched quotes
		$this->assertEquals(expected:  'test"',  actual: StringUtils::TrimQuotes(  '"test""'));
		$this->assertEquals(expected: '"test',   actual: StringUtils::TrimQuotes( '""test"' ));
		$this->assertEquals(expected:  "test'",  actual: StringUtils::TrimQuotes(   "test'" ));
		$this->assertEquals(expected: "'test",   actual: StringUtils::TrimQuotes(  "'test"  ));
		$this->assertEquals(expected:  'test``', actual: StringUtils::TrimQuotes(   'test``'));
		$this->assertEquals(expected:'``test',   actual: StringUtils::TrimQuotes('"``test"' ));
		// blank strings
		$this->assertEquals(expected: '',        actual: StringUtils::TrimQuotes('""')       );
		$this->assertEquals(expected: '',        actual: StringUtils::TrimQuotes("''")       );
		$this->assertEquals(expected: '',        actual: StringUtils::TrimQuotes('``')       );
	}



	################
	## Pad String ##
	################



	/**
	 * @covers ::PadLeft
	 * @covers ::getPadding
	 */
	public function test_PadLeft(): void {
		$this->assertEquals(expected: '  abc', actual: StringUtils::PadLeft('abc', 5)      );
		$this->assertEquals(expected: '  abc', actual: StringUtils::PadLeft('abc', 5, '')  );
		$this->assertEquals(expected:   'abc', actual: StringUtils::PadLeft('abc', 2)      );
		$this->assertEquals(expected: '-+abc', actual: StringUtils::PadLeft('abc', 5, '-+'));
	}

	/**
	 * @covers ::PadRight
	 * @covers ::getPadding
	 */
	public function test_PadRight(): void {
		$this->assertEquals(expected: 'abc  ', actual: StringUtils::PadRight('abc', 5)      );
		$this->assertEquals(expected: 'abc  ', actual: StringUtils::PadRight('abc', 5, '')  );
		$this->assertEquals(expected: 'abc',   actual: StringUtils::PadRight('abc', 2)      );
		$this->assertEquals(expected: 'abc-+', actual: StringUtils::PadRight('abc', 5, '-+'));
	}

	/**
	 * @covers ::PadCenter
	 * @covers ::getPadding
	 */
	public function test_PadCenter(): void {
		$this->assertEquals(expected: ' abc ',  actual: StringUtils::PadCenter('abc', 5)      );
		$this->assertEquals(expected: ' abc ',  actual: StringUtils::PadCenter('abc', 5, '')  );
		$this->assertEquals(expected:  'abc',   actual: StringUtils::PadCenter('abc', 2)      );
		$this->assertEquals(expected:'-+abc-+', actual: StringUtils::PadCenter('abc', 5, '-+'));
	}



	#####################
	## Force Start/End ##
	#####################



	/**
	 * @covers ::force_starts_with
	 */
	public function test_force_starts_with(): void {
		$this->assertEquals(expected: 'test',    actual: StringUtils::force_starts_with('test', '')      );
		$this->assertEquals(expected: '123test', actual: StringUtils::force_starts_with('test', '123')   );
		$this->assertEquals(expected: '123test', actual: StringUtils::force_starts_with('123test', '123'));
	}

	/**
	 * @covers ::force_ends_with
	 */
	public function test_force_ends_with(): void {
		$this->assertEquals(expected: 'test',    actual: StringUtils::force_ends_with('test', '')      );
		$this->assertEquals(expected: 'test123', actual: StringUtils::force_ends_with('test', '123')   );
		$this->assertEquals(expected: 'test123', actual: StringUtils::force_ends_with('test123', '123'));
	}



	##############
	## Contains ##
	##############



	/**
	 * @covers ::Contains
	 */
	public function test_Contains(): void {
		$this->assertFalse(StringUtils::Contains(self::TRIM_TEST_DATA, '')           );
		$this->assertTrue (StringUtils::Contains(self::TRIM_TEST_DATA, 'test')       );
		$this->assertTrue (StringUtils::Contains(self::TRIM_TEST_DATA, 'Test', TRUE) );
		$this->assertFalse(StringUtils::Contains(self::TRIM_TEST_DATA, 'Test', FALSE));
	}



	################
	## File Paths ##
	################



	/**
	 * @covers ::getFileName
	 */
	public function test_getFileName(): void {
		$this->assertEquals(expected: '',         actual: StringUtils::getFileName('')             );
		$this->assertEquals(expected: 'hostname', actual: StringUtils::getFileName('/etc/hostname'));
		$this->assertEquals(expected: 'hostname', actual: StringUtils::getFileName('hostname')     );
	}



	/**
	 * @covers ::BuildPath
	 */
	public function test_BuildPath(): void {
		$this->assertEquals(expected: '',                    actual: StringUtils::BuildPath()                                );
		$this->assertEquals(expected: 'home',                actual: StringUtils::BuildPath('', 'home', '')                  );
		$this->assertEquals(expected: 'home/user/Desktop',   actual: StringUtils::BuildPath('home', 'user', 'Desktop')       );
		$this->assertEquals(expected: '/home/user/Desktop',  actual: StringUtils::BuildPath('/', 'home', 'user', 'Desktop')  );
		$this->assertEquals(expected: '/home/user/Desktop/', actual: StringUtils::BuildPath('/home', 'user', 'Desktop', '/') );
		$this->assertEquals(expected: '/home/user/Desktop/', actual: StringUtils::BuildPath('/home/', '/user/', '/Desktop/') );
	}



	/**
	 * @covers ::getAbsolutePath
	 */
	public function test_getAbsolutePath(): void {
		$this->assertEquals(expected: '/etc/hostname', actual: StringUtils::getAbsolutePath('/etc/hostname'));
		$this->assertEquals(expected: '',              actual: StringUtils::getAbsolutePath('')             );
		$this->assertEquals(expected: \getcwd(),       actual: StringUtils::getAbsolutePath('.')            );
	}



	/**
	 * @covers ::CommonPath
	 */
	public function test_CommonPath(): void {
		$this->assertEquals(expected: '',           actual: StringUtils::CommonPath('', '')                                       );
		$this->assertEquals(expected: '/home/user', actual: StringUtils::CommonPath('/home/user/Desktop', '/home/user/Documents') );
		$this->assertEquals(expected: '/home/user', actual: StringUtils::CommonPath('/home/user/',        '/home/user/Documents/'));
		$this->assertEquals(expected: '/',          actual: StringUtils::CommonPath('/usr/bin',           '/etc/profile.d')       );
	}



}
