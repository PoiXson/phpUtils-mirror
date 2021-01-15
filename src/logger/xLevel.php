<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\xLogger;

use pxn\phpUtils\Defines;
use pxn\phpUtils\Numbers;


final class xLevel {
	private function __construct() {}


	const OFF     = Defines::INT_MAX;
	const STDERR  = 9000;
	const STDOUT  = 8000;
	const FATAL   = 2000;
	const SEVERE  = 1000;
	const NOTICE  = 900;
	const WARNING = 800;
	const INFO    = 700;
	const STATS   = 600;
	const FINE    = 500;
	const FINER   = 400;
	const FINEST  = 300;
	const ALL     = Defines::INT_MIN;

	protected static $knownLevels = [
		'OFF'     => self::OFF,
		'ERR'     => self::STDERR,
		'OUT'     => self::STDOUT,
		'FATAL'   => self::FATAL,
		'SEVERE'  => self::SEVERE,
		'NOTICE'  => self::NOTICE,
		'WARNING' => self::WARNING,
		'INFO'    => self::INFO,
		'STATS'   => self::STATS,
		'FINE'    => self::FINE,
		'FINER'   => self::FINER,
		'FINEST'  => self::FINEST,
		'ALL'     => self::ALL,
	];



	public static function FindLevel($value) {
		if (empty($value))
			return NULL;
		// number value
		if (Numbers::isNumber($value)) {
			$value = (int) $value;
			if ($value == self::OFF)
				return self::OFF;
			if ($value == self::ALL)
				return self::ALL;
			// find nearest value
			$level  = self::OFF;
			$offset = self::OFF;
			foreach (self::$knownLevels as $key => $val) {
				if ($val == self::OFF)
					continue;
				if ($value < $val)
					continue;
				if ($value - $val < $offset) {
					$offset = $value - $val;
					$level = $val;
				}
			}
			return $level;
		}
		// word value
		$value = \mb_strtoupper($value);
		if (isset(self::$knownLevels[$value])) {
			return self::$knownLevels[$value];
		}
		// unknown level
		return NULL;
	}
	public static function FindLevelName($value) {
		$level = self::FindLevel($value);
		if ($level == NULL)
			return NULL;
		return self::LevelToName($level);
	}
	public static function LevelToName($value) {
		if (empty($value))
			return NULL;
		foreach (self::$knownLevels as $key => $val) {
			if ($val == $value)
				return $key;
		}
		return NULL;
	}



	public static function isLoggable($configuredLevel, $recordLevel) {
		if ($configuredLevel == NULL)
			return TRUE;
		if ($recordLevel == NULL)
			return TRUE;
		return ($configuredLevel < $recordLevel);
	}



	// match levels
	public static function MatchLevel($levelA, $levelB) {
		if ($levelA == NULL || $levelB == NULL) {
			return NULL;
		}
		$lvlA = self::FindLevel($levelA);
		$lvlB = self::FindLevel($levelB);
		return ($lvlA == $lvlB);
	}
	public static function MatchLevels(... $levels) {
		if (\count($levels) == 0) {
			return NULL;
		}
		$match = NULL;
		foreach ($levels as $lvl) {
			if ($match == NULL) {
				$match = self::FindLevel($lvl);
			} else {
				$result = self::MatchLevel(
					$lvl,
					$match
				);
				if (!$result) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}



}
*/
