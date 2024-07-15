<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\StringUtils;


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
		$this->assertEquals(expected: 'Abc def ghi jkl mnp qrs tuv wxyz', actual:   StringUtils::mb_ucfirst($data, false));
		$this->assertEquals(expected: 'Abc Def Ghi jkl mnp qrs tuv wxyz', actual:   StringUtils::mb_ucfirst($data, true) );
	}



	#################
	## Trim String ##
	#################



	const TRIM_TEST_DATA = "\t--   == test ==   --\t";



	/**
	 * @covers ::trim
	 */
	public function test_trim(): void {
		$this->assertEquals(expected: '--   == test ==   --', actual: StringUtils::trim(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: 'test',                 actual: StringUtils::trim(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: 'test',                 actual: StringUtils::trim(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: '--   == test ==   --', actual: StringUtils::trim(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '123',                  actual: StringUtils::trim('01230', '0')                               );
		$this->assertEquals(expected: '',                     actual: StringUtils::trim('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                  actual: StringUtils::trim('123', '  ', '')                            );
	}

	/**
	 * @covers ::trim_front
	 */
	public function test_trim_front(): void {
		$this->assertEquals(expected: "--   == test ==   --\t", actual: StringUtils::trim_front(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: "test ==   --\t",         actual: StringUtils::trim_front(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: "test ==   --\t",         actual: StringUtils::trim_front(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: "--   == test ==   --\t", actual: StringUtils::trim_front(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '1230',                   actual: StringUtils::trim_front('01230', '0')                               );
		$this->assertEquals(expected: '',                       actual: StringUtils::trim_front('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                    actual: StringUtils::trim_front('123', '  ', '')                            );
	}

	/**
	 * @covers ::trim_end
	 */
	public function test_trim_end(): void {
		$this->assertEquals(expected: "\t--   == test ==   --", actual: StringUtils::trim_end(self::TRIM_TEST_DATA)                       );
		$this->assertEquals(expected: "\t--   == test",         actual: StringUtils::trim_end(self::TRIM_TEST_DATA, ' ', '-', '=', "\t")  );
		$this->assertEquals(expected: "\t--   == test",         actual: StringUtils::trim_end(self::TRIM_TEST_DATA, ' ', '--', '==', "\t"));
		$this->assertEquals(expected: "\t--   == test ==   --", actual: StringUtils::trim_end(self::TRIM_TEST_DATA, ' ', '=', "\t")       );
		$this->assertEquals(expected: '0123',                   actual: StringUtils::trim_end('01230', '0')                               );
		$this->assertEquals(expected: '',                       actual: StringUtils::trim_end('01230', '0', '1', '2', '3')                );
		$this->assertEquals(expected: '123',                    actual: StringUtils::trim_end('123', '  ', '')                            );
	}



	/**
	 * @covers ::trim_quotes
	 */
	public function test_trim_quotes(): void {
		// matching quotes
		$this->assertEquals(expected: 'test',    actual: StringUtils::trim_quotes( '"test"' ) );
		$this->assertEquals(expected: 'test',    actual: StringUtils::trim_quotes( "'test'" ) );
		$this->assertEquals(expected: 'test',    actual: StringUtils::trim_quotes('``test``') );
		// mis-matched quotes
		$this->assertEquals(expected:  'test"',  actual: StringUtils::trim_quotes(  '"test""'));
		$this->assertEquals(expected: '"test',   actual: StringUtils::trim_quotes( '""test"' ));
		$this->assertEquals(expected:  "test'",  actual: StringUtils::trim_quotes(   "test'" ));
		$this->assertEquals(expected: "'test",   actual: StringUtils::trim_quotes(  "'test"  ));
		$this->assertEquals(expected:  'test``', actual: StringUtils::trim_quotes(   'test``'));
		$this->assertEquals(expected:'``test',   actual: StringUtils::trim_quotes('"``test"' ));
		// blank strings
		$this->assertEquals(expected: '',        actual: StringUtils::trim_quotes('""')       );
		$this->assertEquals(expected: '',        actual: StringUtils::trim_quotes("''")       );
		$this->assertEquals(expected: '',        actual: StringUtils::trim_quotes('``')       );
	}



	################
	## Pad String ##
	################



	/**
	 * @covers ::pad_left
	 * @covers ::get_padding
	 */
	public function test_pad_left(): void {
		$this->assertEquals(expected: '  abc', actual: StringUtils::pad_left('abc', 5)      );
		$this->assertEquals(expected: '  abc', actual: StringUtils::pad_left('abc', 5, '')  );
		$this->assertEquals(expected:   'abc', actual: StringUtils::pad_left('abc', 2)      );
		$this->assertEquals(expected: '-+abc', actual: StringUtils::pad_left('abc', 5, '-+'));
	}

	/**
	 * @covers ::pad_right
	 * @covers ::get_padding
	 */
	public function test_pad_right(): void {
		$this->assertEquals(expected: 'abc  ', actual: StringUtils::pad_right('abc', 5)      );
		$this->assertEquals(expected: 'abc  ', actual: StringUtils::pad_right('abc', 5, '')  );
		$this->assertEquals(expected: 'abc',   actual: StringUtils::pad_right('abc', 2)      );
		$this->assertEquals(expected: 'abc-+', actual: StringUtils::pad_right('abc', 5, '-+'));
	}

	/**
	 * @covers ::pad_center
	 * @covers ::get_padding
	 */
	public function test_pad_center(): void {
		$this->assertEquals(expected: ' abc ',  actual: StringUtils::pad_center('abc', 5)      );
		$this->assertEquals(expected: ' abc ',  actual: StringUtils::pad_center('abc', 5, '')  );
		$this->assertEquals(expected:  'abc',   actual: StringUtils::pad_center('abc', 2)      );
		$this->assertEquals(expected:'-+abc-+', actual: StringUtils::pad_center('abc', 5, '-+'));
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



	#####################
	## String Contains ##
	#####################



	/**
	 * @covers ::str_contains
	 */
	public function test_str_contains(): void {
		$this->assertFalse(StringUtils::str_contains(self::TRIM_TEST_DATA, '')           );
		$this->assertTrue (StringUtils::str_contains(self::TRIM_TEST_DATA, 'test')       );
		$this->assertTrue (StringUtils::str_contains(self::TRIM_TEST_DATA, 'Test', true) );
		$this->assertFalse(StringUtils::str_contains(self::TRIM_TEST_DATA, 'Test', false));
	}



	##############
	## Versions ##
	##############



	/**
	 * @covers ::compare_versions
	 */
	public function test_compare_versions(): void {
		$this->assertEquals(expected: '=', actual: StringUtils::compare_versions('',      ''     ));
		$this->assertEquals(expected: '>', actual: StringUtils::compare_versions('1',     ''     ));
		$this->assertEquals(expected: '<', actual: StringUtils::compare_versions('',      '1'    ));
		$this->assertEquals(expected: '=', actual: StringUtils::compare_versions('1',     '1'    ));
		$this->assertEquals(expected: '=', actual: StringUtils::compare_versions('1.0',   '1'    ));
		$this->assertEquals(expected: '>', actual: StringUtils::compare_versions('1.1',   '1.0'  ));
		$this->assertEquals(expected: '<', actual: StringUtils::compare_versions('1.0',   '1.1'  ));
		$this->assertEquals(expected: '<', actual: StringUtils::compare_versions('1.2.3', '1.2.4'));
		$this->assertEquals(expected: '=', actual: StringUtils::compare_versions('1.x',   '1.x'  ));
		$this->assertEquals(expected: '<', actual: StringUtils::compare_versions('1.9',   '1.x'  ));
		$this->assertEquals(expected: '<', actual: StringUtils::compare_versions('1.9',   '1.10' ));
		$this->assertEquals(expected: '>', actual: StringUtils::compare_versions('1.11',  '1.10' ));
	}



}
