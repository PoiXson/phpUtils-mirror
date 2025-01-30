<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use \pxn\phpUtils\pxnDefines as xDef;
use \pxn\phpUtils\exceptions\RequiredArgumentException;


final class NumberUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	/**
	 * This function is a more specific test of a value. The native
	 * \is_numeric() function also returns true for hex values.
	 * @param string $value
	 * @return boolean Returns true if only contains number characters after being trimmed.
	 */
	public static function isNumber($value): bool {
		if ($value === null) return false;
		$value = \trim( (string)$value );
		if ($value === '') return false;
		$value = StringUtils::trim_front($value, '0');
		if ($value == '') return true;
		$i = ((string) ((int)$value) );
		return ($value === $i);
	}



	/**
	 * Check the min and max of a value and return the result.
	 * @param int $value
	 * @param int $min
	 * @param int $max
	 * @return int value
	 */
	public static function MinMax(int $value, int $min=\PHP_INT_MIN, int $max=\PHP_INT_MAX): int {
		if ($min !== false && $max !== false && $min > $max)
			throw new \Exception('Min must be less than Max!');
		if ($min !== false && $value < $min) return $min;
		if ($max !== false && $value > $max) return $max;
		return $value;
	}



	############
	## Format ##
	############



	public static function Round(float $value, int $places=0): float {
		if ($places == 0)
			return \round($value);
		$pow = \pow(10, $places);
		return \round($value * $pow) / $pow;
	}

	public static function Floor(float $value, int $places=0): float {
		if ($places == 0)
			return \floor($value);
		$pow = \pow(10, $places);
		return \floor($value * $pow) / $pow;
	}

	public static function Ceil(float $value, int $places=0): float {
		if ($places == 0)
			return \ceil($value);
		$pow = \pow(10, $places);
		return \ceil($value * $pow) / $pow;
	}



	public static function PadZeros(float $value, int $places): string {
		$str = (string) (float) $value;
		if ($places <= 0)
			return $str;
		$pos = \mb_strrpos($str, '.');
		if ($pos === false)
			return $str . '.' . \str_repeat('0', $places);
		$pos = \mb_strlen($str) - ($pos + 1);
		if ($pos < $places)
			return $str . \str_repeat('0', $places - $pos);
		return $str;
	}



	/**
	 * Convert bytes to human readable format.
	 * @param int $size - Integer in bytes to convert.
	 * @return string - Formatted size.
	 */
	public static function FormatBytes(int|string $size): string {
		if (empty($size)) return '';
		$size = \trim((string) $size);
		if ( \mb_strtolower(\mb_substr($size, -1, 1)) == 'b' )
			$size = \trim(\mb_substr($size, 0, -1));
		switch ( \mb_strtolower(\mb_substr($size, -1, 1)) ) {
		case 'k': $size = ((float) $size) * xDef::KB; break;
		case 'm': $size = ((float) $size) * xDef::MB; break;
		case 'g': $size = ((float) $size) * xDef::GB; break;
		case 't': $size = ((float) $size) * xDef::TB; break;
		default: $size = (float) $size;               break;
		}
		if ($size < 0)        return '';
		if ($size < xDef::KB) return \round($size, 0) . 'B';
		if ($size < xDef::MB) return \round($size / xDef::KB, 2) . 'KB';
		if ($size < xDef::GB) return \round($size / xDef::MB, 2) . 'MB';
		if ($size < xDef::TB) return \round($size / xDef::GB, 2) . 'GB';
		return \round($size / xDef::TB, 2) . 'TB';
	}



	/**
	 * Convert a number to roman numerals.
	 * @param int $value -
	 * @param int $max -
	 * @return string - Roman numerals string representing input number.
	 */
	public static function FormatRoman(int $value, ?int $max=null): string {
		if ( ($max !== null && $value > $max) || $value < 0)
			return (string) $value;
		$result = '';
		$lookup = [
			'M' => 1000,
			'CM'=> 900,
			'D' => 500,
			'CD'=> 400,
			'C' => 100,
			'XC'=> 90,
			'L' => 50,
			'XL'=> 40,
			'X' => 10,
			'IX'=> 9,
			'V' => 5,
			'IV'=> 4,
			'I' => 1
		];
		foreach ($lookup as $roman => $num) {
			$matches = \intval($value / $num);
			$result .= \str_repeat($roman, $matches);
			$value = $value % $num;
		}
		return $result;
	}



	############
	## Colors ##
	############



	public static function ColorPercent(float $percent, string...$colors): string {
		$count = \count($colors);
		if ($count == 0) throw new RequiredArgumentException('colors');
		if ($count == 1)     return self::ShorthandHexColor( \reset($colors) );
		if ($percent <= 0.0) return self::ShorthandHexColor( \reset($colors) );
		if ($percent >= 1.0) return self::ShorthandHexColor( $colors[$count - 1] );
		$addhash = '';
		$r = $g = $b = [];
		$i = 0;
		foreach ($colors as &$c) {
			if (\mb_substr($c, 0, 1) == '#') {
				$c = \mb_substr($c, 1);
				$addhash = '#';
			}
			if (\mb_strlen($c) == 3) {
				$c =
					\mb_substr($c, 0, 1).\mb_substr($c, 0, 1).
					\mb_substr($c, 1, 1).\mb_substr($c, 1, 1).
					\mb_substr($c, 2, 1).\mb_substr($c, 2, 1);
			}
			if (\mb_strlen($c) != 6) throw new RequiredArgumentException("color$i");
			$r[$i] = \hexdec(\mb_substr($c, 0, 2));
			$g[$i] = \hexdec(\mb_substr($c, 2, 2));
			$b[$i] = \hexdec(\mb_substr($c, 4, 2));
			$i++;
		}
		$mod = 1.0 / ($count-1);
		$index = \floor($percent / $mod);
		if ($index < 0) $index = 0;
		elseif ($index > $count - 1)
			$index = $count - 1;
		$percent = ($percent - ($mod * $index)) / $mod;
		$Hr = \dechex((int)\floor( ((1.0-$percent) * $r[$index]) + ($percent * $r[$index+1]) ));
		$Hg = \dechex((int)\floor( ((1.0-$percent) * $g[$index]) + ($percent * $g[$index+1]) ));
		$Hb = \dechex((int)\floor( ((1.0-$percent) * $b[$index]) + ($percent * $b[$index+1]) ));
		if (\mb_strlen($Hr) < 2) $Hr = "0$Hr";
		if (\mb_strlen($Hg) < 2) $Hg = "0$Hg";
		if (\mb_strlen($Hb) < 2) $Hb = "0$Hb";
		return self::ShorthandHexColor( "$addhash$Hr$Hg$Hb" );
	}

	public static function ShorthandHexColor(string $color): string {
		$addhash = '';
		if (\mb_substr($color, 0, 1) == '#') {
			$color = \mb_substr($color, 1);
			$addhash = '#';
		}
		if (\mb_strlen($color) == 6) {
			if (\mb_substr($color, 0, 1) == \mb_substr($color, 1, 1)) {
			if (\mb_substr($color, 2, 1) == \mb_substr($color, 3, 1)) {
			if (\mb_substr($color, 4, 1) == \mb_substr($color, 5, 1)) {
				return $addhash.\mb_substr($color, 0, 1).
					\mb_substr($color, 2, 1).\mb_substr($color, 4, 1);
			}}}
		}
		return $addhash.$color;
	}



	##########
	## Time ##
	##########



	/**
	 * String to seconds.
	 * @param string $text - String to convert.
	 * @return int seconds
	 */
	public static function StringToSeconds(string $text): int {
		$str = '';
		$value = 0;
		for ($i=0; $i<\mb_strlen($text); $i++) {
			$s = \mb_substr($text, $i, 1);
			if (self::isNumber($s)) {
				$str .= $s;
				continue;
			}
			if ($s === ' ') continue;
			$val = (int) $str;
			$str = '';
			if ($val == 0) continue;
			switch (\mb_strtolower($s)) {
			case 's';
				$value += ($val * xDef::S_SECOND);
				break;
			case 'm':
				$value += ($val * xDef::S_MINUTE);
				break;
			case 'h':
				$value += ($val * xDef::S_HOUR);
				break;
			case 'd':
				$value += ($val * xDef::S_DAY);
				break;
			case 'w':
				$value += ($val * xDef::S_WEEK);
				break;
			case 'o':
				$value += ($val * xDef::S_MONTH);
				break;
			case 'y':
				$value += ($val * xDef::S_YEAR);
				break;
			default:
				$value += $val;
				break;
			}
		}
		if (!empty($str)) {
			$value += (int) $str;
		}
		return $value;
	}

	/**
	 * Seconds to formatted string.
	 * @param int $seconds - Integer to convert.
	 * @return string
	 */
	public static function SecondsToString(int $seconds,
	bool $shorthand=true, ?int $maxParts=null, float $deviance=1.0): string {
		$maxParts = (
			$maxParts == null
			? null
			: (int) $maxParts
		);
		$result = array();
		// years
		if ($seconds >= (xDef::S_YEAR * $deviance)) {
			$v = \ceil(\floor($seconds / xDef::S_YEAR / $deviance) * $deviance);
			$seconds -= $v * xDef::S_YEAR;
			$result[] = $v.(
				$shorthand
				? 'yr'
				: ' Year'.($v > 1 ? 's' : '')
			);
		}
		// months
		if ($deviance !== 1.0) {
		if ($maxParts === null || count($result) < $maxParts) {
		if ($seconds >= (xDef::S_MONTH * $deviance)) {
			$v = \ceil(\floor($seconds / xDef::S_MONTH / $deviance) * $deviance);
			$seconds -= $v * xDef::S_MONTH;
			$result[] = $v.(
				$shorthand
				? 'mon'
				: ' Month'.($v > 1 ? 's' : '')
			);
		}}}
		// days
		if ($maxParts === null || count($result) < $maxParts) {
		if ($seconds >= (xDef::S_DAY * $deviance)) {
			$v = \ceil(\floor($seconds / xDef::S_DAY / $deviance) * $deviance);
			$seconds -= $v * xDef::S_DAY;
			$result[] = $v.(
				$shorthand
				? 'day'
				: ' Day'.($v > 1 ? 's' : '')
			);
		}}
		// hours
		if ($maxParts === null || count($result) < $maxParts) {
		if ($seconds >= (xDef::S_HOUR * $deviance)) {
			$v = \ceil(\floor($seconds / xDef::S_HOUR / $deviance) * $deviance);
			$seconds -= $v * xDef::S_HOUR;
			$result[] = $v.(
				$shorthand
				? 'hr'
				: ' Hour'.($v > 1 ? 's' : '')
			);
		}}
		// minutes
		if ($maxParts === null || count($result) < $maxParts) {
		if ($seconds >= (xDef::S_MINUTE * $deviance)) {
			$v = \ceil(\floor($seconds / xDef::S_MINUTE / $deviance) * $deviance);
			$seconds -= $v * xDef::S_MINUTE;
			$result[] = $v.(
				$shorthand
				? 'min'
				: ' Minute'.($v > 1 ? 's' : '')
			);
		}}
		// seconds
		if ($maxParts === null || count($result) < $maxParts) {
		if ($seconds > 0) {
			$result[] = $seconds.(
				$shorthand
				? 'sec'
				: ' Second'.
				($seconds > 1 ? 's' : '')
			);
		}}
		if (\count($result) == 0)
			return '--';
		if ($shorthand)
			return \implode(' ', $result);
		return \implode('  ', $result);
	}

	public static function SecondsToText(int $seconds,
	bool $shorthand=false, ?int $maxParts=null, float $deviance=1.0): string {
		// future
		if ( $seconds < 0 ) {
			$seconds = 0 - $seconds;
			if ( ($seconds * $deviance) < xDef::S_HOUR )
				return 'Soon';
			if ( ($seconds * $deviance) < xDef::S_DAY )
				return 'Soon Today';
			if ( ($seconds * $deviance) < (xDef::S_DAY * 2) )
				return 'Tomorrow';
			return self::SecondsToString($seconds, $shorthand, $maxParts, $deviance).' from now';
		}
		// now
		if ( $seconds * $deviance < xDef::S_HOUR )
			return 'Now';
		// past
		if ( ($seconds * $deviance) < xDef::S_DAY )
			return 'Today';
		if ( ($seconds * $deviance) < (xDef::S_DAY * 2) )
			return 'Yesterday';
		return self::SecondsToString($seconds, $shorthand, $maxParts, $deviance).' ago';
	}



}
