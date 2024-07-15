<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\logger;

use \pxn\phpUtils\Defines;
use \pxn\phpUtils\Numbers;


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



	public static function FindLevel($value): ?xLevel {
		if (empty($value))
			return null;
		// number value
		if (Numbers::isNumber($value)) {
			$value = (int) $value;
			if ($value == self::OFF) return self::OFF;
			if ($value == self::ALL) return self::ALL;
			// find nearest value
			$level  = self::OFF;
			$offset = self::OFF;
			foreach (self::$knownLevels as $key => $val) {
				if ($val == self::OFF) continue;
				if ($value < $val)     continue;
				if ($value - $val < $offset) {
					$offset = $value - $val;
					$level = $val;
				}
			}
			return $level;
		}
		// word value
		$value = \mb_strtoupper($value);
		if (isset(self::$knownLevels[$value]))
			return self::$knownLevels[$value];
		// unknown level
		return null;
	}
	public static function FindLevelName($value): ?string {
		$level = self::FindLevel($value);
		if ($level == null)
			return null;
		return self::LevelToName($level);
	}
	public static function LevelToName($value): ?string {
		if (empty($value))
			return null;
		foreach (self::$knownLevels as $key => $val) {
			if ($val == $value)
				return $key;
		}
		return null;
	}



	public static function isLoggable(?xLevel $configured_level, ?xLevel $record_level): bool {
		if ($configured_level == null) return true;
		if ($record_level     == null) return true;
		return ($configured_level < $record_level);
	}



	// match levels
	public static function MatchLevel(?xLevel $levelA, ?xLevel $levelB): bool {
		if ($levelA == null || $levelB == null)
			return null;
		$lvlA = self::FindLevel($levelA);
		$lvlB = self::FindLevel($levelB);
		return ($lvlA == $lvlB);
	}
	public static function MatchLevels(xLevel ... $levels): bool {
		if (\count($levels) == 0)
			return null;
		$match = null;
		foreach ($levels as $lvl) {
			if ($match == null) {
				$match = self::FindLevel($lvl);
			} else {
				$result = self::MatchLevel($lvl, $match);
				if (!$result)
					return false;
			}
		}
		return true;
	}



}
