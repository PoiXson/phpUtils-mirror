<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;

use pxn\phpUtils\pxnDefines as xDef;


final class NumberUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	/**
	 * This function is a more specific test of a value. The native
	 * \is_numeric() function also returns true for hex values.
	 * @param string $value
	 * @return boolean Returns true if only contains number characters after
	 *         being trimmed.
	 */
	public static function isNumber($value): bool {
		if ($value === NULL) return FALSE;
		$value = \trim( (string)$value );
		if ($value === '') return FALSE;
		$value = StringUtils::TrimFront($value, '0');
		if ($value == '') return TRUE;
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
		if ($min !== FALSE && $max !== FALSE && $min > $max)
			throw new \Exception('Min must be less than Max!');
		if ($min !== FALSE && $value < $min) return $min;
		if ($max !== FALSE && $value > $max) return $max;
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
		if ($pos === FALSE)
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
	public static function FormatRoman(int $value, ?int $max=NULL): string {
		if ( ($max !== NULL && $value > $max) || $value < 0)
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
	bool $shorthand=TRUE, ?int $maxParts=NULL, float $deviance=1.0): string {
		$maxParts = (
			$maxParts == NULL
			? NULL
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
		if ($maxParts === NULL || count($result) < $maxParts) {
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
		if ($maxParts === NULL || count($result) < $maxParts) {
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
		if ($maxParts === NULL || count($result) < $maxParts) {
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
		if ($maxParts === NULL || count($result) < $maxParts) {
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
		if ($maxParts === NULL || count($result) < $maxParts) {
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
	bool $shorthand=FALSE, ?int $maxParts=NULL, float $deviance=1.0): string {
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
