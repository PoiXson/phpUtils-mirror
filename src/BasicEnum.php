<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


abstract class BasicEnum {
	private function __construct() {}

	private static $ConstMapArrays  = array();



	protected static function getConstants() {
		$classname = \get_called_class();
		if (!isset(self::$ConstMapArrays[$classname])) {
			$reflect = new \ReflectionClass($classname);
			self::$ConstMapArrays[$classname] = $reflect->getConstants();
			unset($reflect);
		}
		return self::$ConstMapArrays[$classname];
	}



	public static function isValidName($name, $ignoreCase=true) {
		$constants = self::getConstants();
		if (\array_key_exists($name, $constants)) {
			return true;
		}
		if (!$ignoreCase) {
			return false;
		}
		$keys = \array_map('\\mb_strtolower', \array_keys($constants));
		return \in_array(\mb_strtolower($name), $keys);
	}
	public static function isValidValue($value, $ignoreCase=true) {
		$values = \array_values(self::getConstants());
		if (\in_array($value, $values))
			return true;
		if (!$ignoreCase)
			return false;
		$vals = \array_map('\\mb_strtolower', \array_values($values));
		return \in_array(\mb_strtolower($value), $vals);
	}



	public static function getByName($name, $ignoreCase=true) {
		$constants = self::getConstants();
		if (\array_key_exists($name, $constants))
			return $constants[$name];
		if (!$ignoreCase)
			return null;
		$n = \mb_strtolower($name);
		foreach ($constants as $k => $v) {
			if(\mb_strtolower($k) == $n)
				return $v;
		}
		return null;
	}
	public static function getByValue($value, $ignoreCase=true) {
		$constants = self::getConstants();
		$result = \array_search($value, $constants, true);
		if ($result != false)
			return $result;
		if (!$ignoreCase)
			return null;
		$val = \mb_strtolower($value);
		foreach ($constants as $k => $v) {
			if (\mb_strtolower($v) == $val) {
				return $k;
			}
		}
		return null;
	}



}
