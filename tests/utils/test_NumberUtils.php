<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\NumberUtils;
use \pxn\phpUtils\pxnDefines;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\NumberUtils
 */
class test_NumberUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::isNumber
	 */
	public function test_isNumber(): void {
		$this->assertFalse(NumberUtils::isNumber(null      ), "Value: NULL"      );
		$this->assertFalse(NumberUtils::isNumber(''        ), "Value: ''"        );
		$this->assertTrue (NumberUtils::isNumber('1'       ), "Value: '1'"       );
		$this->assertTrue (NumberUtils::isNumber('0'       ), "Value: '0'"       );
		$this->assertTrue (NumberUtils::isNumber('000'     ), "Value: '000'"     );
		$this->assertTrue (NumberUtils::isNumber('-1'      ), "Value: '-1'"      );
		$this->assertTrue (NumberUtils::isNumber(' 1 '     ), "Value: ' 1 '"     );
		$this->assertTrue (NumberUtils::isNumber('99999999'), "Value: '99999999'");
		$this->assertTrue (NumberUtils::isNumber('007'     ), "Value: '007'"     );
		$this->assertFalse(NumberUtils::isNumber(' - 1 '   ), "Value: ' - 1 '"   );
		$this->assertFalse(NumberUtils::isNumber('1a'      ), "Value: '1a'"      );
		$this->assertFalse(NumberUtils::isNumber('a1'      ), "Value: 'a1'"      );
		$this->assertFalse(NumberUtils::isNumber('1 a'     ), "Value: '1 a'"     );
		$this->assertFalse(NumberUtils::isNumber('a'       ), "Value: 'a'"       );
		$this->assertFalse(NumberUtils::isNumber('0x5F12'  ), "Value: '0x5F12'"  );
	}



	/**
	 * @covers ::MinMax
	 */
	public function test_MinMax(): void {
		$this->assertEquals(expected:  999, actual: NumberUtils::MinMax(999)                 );
		// min/max
		$this->assertEquals(expected:    1, actual: NumberUtils::MinMax( 999, -1, 1)         );
		$this->assertEquals(expected:   -1, actual: NumberUtils::MinMax(-999, -1, 1)         );
		// min only
		$this->assertEquals(expected:  999, actual: NumberUtils::MinMax( 999, 1, PHP_INT_MAX));
		$this->assertEquals(expected:    1, actual: NumberUtils::MinMax(-999, 1, PHP_INT_MAX));
		// max only
		$this->assertEquals(expected:    1, actual: NumberUtils::MinMax( 999, PHP_INT_MIN, 1));
		$this->assertEquals(expected: -999, actual: NumberUtils::MinMax(-999, PHP_INT_MIN, 1));
		// exception
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Min must be less than Max!');
		NumberUtils::MinMax(1, 2, 0);
	}



	############
	## Format ##
	############



	/**
	 * @covers ::Round
	 */
	public function test_Round(): void {
		$this->assertEquals(expected: '123',    actual: NumberUtils::Round(123,     0));
		$this->assertEquals(expected: '123.00', actual: NumberUtils::Round(123,     2));
		$this->assertEquals(expected: '123.45', actual: NumberUtils::Round(123.45,  2));
		$this->assertEquals(expected: '123.46', actual: NumberUtils::Round(123.456, 2));
		$this->assertEquals(expected:  '123.4', actual: NumberUtils::Round(123.44,  1));
		$this->assertEquals(expected:  '123.5', actual: NumberUtils::Round(123.45,  1));
		$this->assertEquals(expected:    '130', actual: NumberUtils::Round(125.6,  -1));
	}

	/**
	 * @covers ::Floor
	 */
	public function test_Floor(): void {
		$this->assertEquals(expected: '123',    actual: NumberUtils::Floor(123,     0));
		$this->assertEquals(expected: '123.00', actual: NumberUtils::Floor(123,     2));
		$this->assertEquals(expected: '123.45', actual: NumberUtils::Floor(123.45,  2));
		$this->assertEquals(expected: '123.45', actual: NumberUtils::Floor(123.456, 2));
		$this->assertEquals(expected:  '123.4', actual: NumberUtils::Floor(123.44,  1));
		$this->assertEquals(expected:  '123.4', actual: NumberUtils::Floor(123.45,  1));
		$this->assertEquals(expected:    '120', actual: NumberUtils::Floor(125.6,  -1));
	}

	/**
	 * @covers ::Ceil
	 */
	public function test_Ceil(): void {
		$this->assertEquals(expected: '123',    actual: NumberUtils::Ceil(123,      0));
		$this->assertEquals(expected: '123.00', actual: NumberUtils::Ceil(123,      2));
		$this->assertEquals(expected: '123.45', actual: NumberUtils::Ceil(123.45,   2));
		$this->assertEquals(expected: '123.46', actual: NumberUtils::Ceil(123.456,  2));
		$this->assertEquals(expected:  '123.5', actual: NumberUtils::Ceil(123.44,   1));
		$this->assertEquals(expected:  '123.5', actual: NumberUtils::Ceil(123.45,   1));
		$this->assertEquals(expected:    '130', actual: NumberUtils::Ceil(125.6,   -1));
	}



	/**
	 * @covers ::PadZeros
	 */
	public function test_PadZeros(): void {
		$this->assertEquals(expected:     '1', actual: NumberUtils::PadZeros(    1, 0));
		$this->assertEquals(expected:   '1.2', actual: NumberUtils::PadZeros(  1.2, 0));
		$this->assertEquals(expected: '1.000', actual: NumberUtils::PadZeros(    1, 3));
		$this->assertEquals(expected: '1.200', actual: NumberUtils::PadZeros(  1.2, 3));
		$this->assertEquals(expected: '1.234', actual: NumberUtils::PadZeros(1.234, 3));
		$this->assertEquals(expected: '1.234', actual: NumberUtils::PadZeros(1.234, 2));
	}



	/**
	 * @covers ::FormatBytes
	 */
	public function test_FormatBytes(): void {
		$this->assertEquals(expected:    null, actual: NumberUtils::FormatBytes(-1  ));
		$this->assertEquals(expected:    '1B', actual: NumberUtils::FormatBytes(1   ));
		$this->assertEquals(expected:   '1KB', actual: NumberUtils::FormatBytes(1024));
		$this->assertEquals(expected:'1.01KB', actual: NumberUtils::FormatBytes(1030));
		$this->assertEquals(expected:   '2MB', actual: NumberUtils::FormatBytes(1024 * 1024 * 2              ));
		$this->assertEquals(expected:   '3GB', actual: NumberUtils::FormatBytes(1024 * 1024 * 1024 * 3       ));
		$this->assertEquals(expected:   '4TB', actual: NumberUtils::FormatBytes(1024 * 1024 * 1024 * 1024 * 4));
		// from string
		$this->assertEquals(expected:    null, actual: NumberUtils::FormatBytes(''     ));
		$this->assertEquals(expected:    '1B', actual: NumberUtils::FormatBytes(' 1 B '));
		$this->assertEquals(expected:   '1KB', actual: NumberUtils::FormatBytes('1 K B'));
		$this->assertEquals(expected:   '1KB', actual: NumberUtils::FormatBytes('1024B'));
		$this->assertEquals(expected:'1.01KB', actual: NumberUtils::FormatBytes('1030B'));
		$this->assertEquals(expected:   '2MB', actual: NumberUtils::FormatBytes('2048K'));
		$this->assertEquals(expected:   '3GB', actual: NumberUtils::FormatBytes('3072M'));
		$this->assertEquals(expected:   '4TB', actual: NumberUtils::FormatBytes('4096G'));
		$this->assertEquals(expected:   '6TB', actual: NumberUtils::FormatBytes('6T'   ));
	}



	/**
	 * @covers ::FormatRoman
	 */
	public function test_FormatRoman(): void {
		$this->assertEquals(expected: 'I',       actual: NumberUtils::FormatRoman(1     ));
		$this->assertEquals(expected: 'II',      actual: NumberUtils::FormatRoman(2     ));
		$this->assertEquals(expected: 'III',     actual: NumberUtils::FormatRoman(3     ));
		$this->assertEquals(expected: 'IV',      actual: NumberUtils::FormatRoman(4     ));
		$this->assertEquals(expected: 'V',       actual: NumberUtils::FormatRoman(5     ));
		$this->assertEquals(expected: 'VI',      actual: NumberUtils::FormatRoman(6     ));
		$this->assertEquals(expected: 'VII',     actual: NumberUtils::FormatRoman(7     ));
		$this->assertEquals(expected: 'VIII',    actual: NumberUtils::FormatRoman(8     ));
		$this->assertEquals(expected: 'IX',      actual: NumberUtils::FormatRoman(9     ));
		$this->assertEquals(expected: 'X',       actual: NumberUtils::FormatRoman(10    ));
		$this->assertEquals(expected: 'XI',      actual: NumberUtils::FormatRoman(11    ));
		$this->assertEquals(expected: 'XV',      actual: NumberUtils::FormatRoman(15    ));
		$this->assertEquals(expected: 'XVI',     actual: NumberUtils::FormatRoman(16    ));
		$this->assertEquals(expected: 'XXII',    actual: NumberUtils::FormatRoman(22    ));
		$this->assertEquals(expected: 'XLII',    actual: NumberUtils::FormatRoman(42    ));
		$this->assertEquals(expected: 'LIII',    actual: NumberUtils::FormatRoman(53    ));
		$this->assertEquals(expected: 'XCI',     actual: NumberUtils::FormatRoman(91    ));
		$this->assertEquals(expected: 'CIV',     actual: NumberUtils::FormatRoman(104   ));
		$this->assertEquals(expected: 'CLV',     actual: NumberUtils::FormatRoman(155   ));
		$this->assertEquals(expected: 'CD',      actual: NumberUtils::FormatRoman(400   ));
		$this->assertEquals(expected: 'D',       actual: NumberUtils::FormatRoman(500   ));
		$this->assertEquals(expected: 'DC',      actual: NumberUtils::FormatRoman(600   ));
		$this->assertEquals(expected: 'CM',      actual: NumberUtils::FormatRoman(900   ));
		$this->assertEquals(expected: 'M',       actual: NumberUtils::FormatRoman(1000  ));
		$this->assertEquals(expected: 'MCCXXXIV',actual: NumberUtils::FormatRoman(1234  ));
		$this->assertEquals(expected: '-20',     actual: NumberUtils::FormatRoman(-20   ));
		$this->assertEquals(expected: '11',      actual: NumberUtils::FormatRoman(11, 10));
		$this->assertEquals(expected: 'IX',      actual: NumberUtils::FormatRoman(9,  10));
	}



	############
	## Colors ##
	############



	/**
	 * @covers ::ColorPercent
	 */
	public function test_ColorPercent(): void {
		$this->assertEquals(expected: '#7f7f7f', actual: NumberUtils::ColorPercent(0.5, '#000000', '#ffffff'));
		$this->assertEquals(expected: '000',     actual: NumberUtils::ColorPercent(0.0, '000', 'fff'));
		$this->assertEquals(expected: 'fff',     actual: NumberUtils::ColorPercent(1.0, '000', 'fff'));
		$this->assertEquals(expected: '7f7f7f',  actual: NumberUtils::ColorPercent(0.5, '000', 'fff'));
		$this->assertEquals(expected: '191919',  actual: NumberUtils::ColorPercent(0.1, '000', 'fff'));
		$this->assertEquals(expected: '020202',  actual: NumberUtils::ColorPercent(0.01,'000', 'fff'));
		$this->assertEquals(expected: '00557f',  actual: NumberUtils::ColorPercent(0.5, '000', '0af'));
		$this->assertEquals(expected: '640',     actual: NumberUtils::ColorPercent(0.4, '000', 'fa0'));
		$this->assertEquals(expected: '012',     actual: NumberUtils::ColorPercent(0.0, '012', '555', 'fff'));
		$this->assertEquals(expected: '011223',  actual: NumberUtils::ColorPercent(0.01,'012', '555', 'fed'));
		$this->assertEquals(expected: '111e2c',  actual: NumberUtils::ColorPercent(0.1, '012', '555', 'fed'));
		$this->assertEquals(expected: '535353',  actual: NumberUtils::ColorPercent(0.49,'012', '555', 'fed'));
		$this->assertEquals(expected: '555',     actual: NumberUtils::ColorPercent(0.5, '012', '555', 'fed'));
		$this->assertEquals(expected: '585857',  actual: NumberUtils::ColorPercent(0.51,'012', '555', 'fed'));
		$this->assertEquals(expected: 'ddcfc1',  actual: NumberUtils::ColorPercent(0.9, '012', '555', 'fed'));
		$this->assertEquals(expected: 'fed',     actual: NumberUtils::ColorPercent(1.0, '012', '555', 'fed'));
	}



	/**
	 * @covers ::ShorthandHexColor
	 */
	public function test_ShorthandHexColor(): void {
		$this->assertEquals(expected:'#abcdef', actual: NumberUtils::ShorthandHexColor('#abcdef'));
		$this->assertEquals(expected: 'abcdef', actual: NumberUtils::ShorthandHexColor( 'abcdef'));
		$this->assertEquals(expected:'#abc',    actual: NumberUtils::ShorthandHexColor('#aabbcc'));
		$this->assertEquals(expected: 'abc',    actual: NumberUtils::ShorthandHexColor( 'aabbcc'));
	}



	##########
	## Time ##
	##########



	/**
	 * @covers ::StringToSeconds
	 */
	public function test_StringToSeconds(): void {
		$this->assertEquals(expected:       1, actual: NumberUtils::StringToSeconds('1'                   ));
		$this->assertEquals(expected:       1, actual: NumberUtils::StringToSeconds('1s'                  ));
		$this->assertEquals(expected:      42, actual: NumberUtils::StringToSeconds('42s'                 ));
		$this->assertEquals(expected:      60, actual: NumberUtils::StringToSeconds('1m'                  ));
		$this->assertEquals(expected:      62, actual: NumberUtils::StringToSeconds('1m 2s  '             ));
		$this->assertEquals(expected:    4010, actual: NumberUtils::StringToSeconds('1h 5m   110s'        ));
		$this->assertEquals(expected:   18121, actual: NumberUtils::StringToSeconds('5h 2m   1s'          ));
		$this->assertEquals(expected:  432000, actual: NumberUtils::StringToSeconds('5d'                  ));
		$this->assertEquals(expected: 1296000, actual: NumberUtils::StringToSeconds('2w 1d'               ));
		$this->assertEquals(expected: 2592000, actual: NumberUtils::StringToSeconds('1o'                  ));
		$this->assertEquals(expected:31536000, actual: NumberUtils::StringToSeconds('1y'                  ));
		$this->assertEquals(expected:31536000, actual: NumberUtils::StringToSeconds('1 Year'              ));
		$this->assertEquals(expected:34822861, actual: NumberUtils::StringToSeconds('1y 1o 1w 1d 1h 1m 1s'));
		$this->assertEquals(expected:       1, actual: NumberUtils::StringToSeconds('1b'                  ));
		$this->assertEquals(expected:       2, actual: NumberUtils::StringToSeconds('1b 1s'               ));
		$this->assertEquals(expected:       2, actual: NumberUtils::StringToSeconds('1s 1b'               ));
	 }



	/**
	 * @covers ::SecondsToString
	 */
	public function test_SecondsToString(): void {
		$this->assertEquals(expected:                     '--', actual: NumberUtils::SecondsToString(       0));
		$this->assertEquals(expected:                   '1sec', actual: NumberUtils::SecondsToString(       1));
		$this->assertEquals(expected:                  '42sec', actual: NumberUtils::SecondsToString(      42));
		$this->assertEquals(expected:                   '1min', actual: NumberUtils::SecondsToString(      60));
		$this->assertEquals(expected:              '1min 2sec', actual: NumberUtils::SecondsToString(      62));
		$this->assertEquals(expected:         '1hr 6min 50sec', actual: NumberUtils::SecondsToString(    4010));
		$this->assertEquals(expected:          '5hr 2min 1sec', actual: NumberUtils::SecondsToString(   18121));
		$this->assertEquals(expected:               '5hr 2min', actual: NumberUtils::SecondsToString(   18121, true, 2));
		$this->assertEquals(expected:                   '5day', actual: NumberUtils::SecondsToString(  432000));
		$this->assertEquals(expected:                  '15day', actual: NumberUtils::SecondsToString( 1296000));
		$this->assertEquals(expected:                    '1yr', actual: NumberUtils::SecondsToString(31536000));
		$this->assertEquals(expected:'1yr 38day 1hr 1min 1sec', actual: NumberUtils::SecondsToString(34822861));
		$this->assertEquals(expected:               '1 Second', actual: NumberUtils::SecondsToString(       1, false));
		$this->assertEquals(expected:     '2 Hours  2 Minutes', actual: NumberUtils::SecondsToString(    7320, false));
		$this->assertEquals(expected:                 '1 Year', actual: NumberUtils::SecondsToString(31536000, false));
		$this->assertEquals(expected:        '2 Years  2 Days', actual: NumberUtils::SecondsToString(63244800, false));
		$this->assertEquals(expected:                   '1mon', actual: NumberUtils::SecondsToString( 2592000, true, 0, 0.9));
	}



	/**
	 * @covers ::SecondsToText
	 */
	public function test_SecondsToText(): void {
		// future
		$this->assertEquals(expected: 'Soon',            actual: NumberUtils::SecondsToText( -1    ));
		$this->assertEquals(expected: 'Soon',            actual: NumberUtils::SecondsToText( -3599 ));
		$this->assertEquals(expected: 'Soon Today',      actual: NumberUtils::SecondsToText( -3600 ));
		$this->assertEquals(expected: 'Soon Today',      actual: NumberUtils::SecondsToText( -86399));
		$this->assertEquals(expected: 'Tomorrow',        actual: NumberUtils::SecondsToText( -86400));
		$this->assertEquals(expected: '5 Days from now', actual: NumberUtils::SecondsToText(-432000));
		// now
		$this->assertEquals(expected: 'Now',             actual: NumberUtils::SecondsToText(0)      );
		$this->assertEquals(expected: 'Now',             actual: NumberUtils::SecondsToText(3599)   );
		$this->assertEquals(expected: 'Today',           actual: NumberUtils::SecondsToText(3600)   );
		$this->assertEquals(expected: 'Today',           actual: NumberUtils::SecondsToText(86399)  );
		// past
		$this->assertEquals(expected: 'Yesterday',       actual: NumberUtils::SecondsToText(86400)  );
		$this->assertEquals(expected: '5 Days ago',      actual: NumberUtils::SecondsToText(432000) );
	}



}
