<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
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



	public static function isValidName($name, $ignoreCase=TRUE) {
		$constants = self::getConstants();
		if (\array_key_exists($name, $constants)) {
			return TRUE;
		}
		if (!$ignoreCase) {
			return FALSE;
		}
		$keys = \array_map('\\mb_strtolower', \array_keys($constants));
		return \in_array(\mb_strtolower($name), $keys);
	}
	public static function isValidValue($value, $ignoreCase=TRUE) {
		$values = \array_values(self::getConstants());
		if (\in_array($value, $values))
			return TRUE;
		if (!$ignoreCase)
			return FALSE;
		$vals = \array_map('\\mb_strtolower', \array_values($values));
		return \in_array(\mb_strtolower($value), $vals);
	}



	public static function getByName($name, $ignoreCase=TRUE) {
		$constants = self::getConstants();
		if (\array_key_exists($name, $constants))
			return $constants[$name];
		if (!$ignoreCase)
			return NULL;
		$n = \mb_strtolower($name);
		foreach ($constants as $k => $v) {
			if(\mb_strtolower($k) == $n)
				return $v;
		}
		return NULL;
	}
	public static function getByValue($value, $ignoreCase=TRUE) {
		$constants = self::getConstants();
		$result = \array_search($value, $constants, TRUE);
		if ($result != FALSE)
			return $result;
		if (!$ignoreCase)
			return NULL;
		$val = \mb_strtolower($value);
		foreach ($constants as $k => $v) {
			if (\mb_strtolower($v) == $val) {
				return $k;
			}
		}
		return NULL;
	}



}
*/
