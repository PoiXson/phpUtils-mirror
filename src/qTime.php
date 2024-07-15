<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;

use \pxn\phpUtils\utils\GeneralUtils;
use \pxn\phpUtils\pxnDefines as xDef;


class qTime {

	protected $start = xDef::INT_MIN;
	protected $last  = xDef::INT_MIN;

	protected static $global = null;



	public static function getGlobal() {
		if (self::$global == null)
			self::$global = new static(true);
		return self::$global;
	}
	public static function getGlobalSinceStart() {
		return self::getGlobal()
				->getTimeSinceStart();
	}
	public static function getGlobalSinceLast() {
		return self::getGlobal()
				->getTimeSinceLast();
	}



	public function __construct($start_now=true) {
		if($start_now)
			$this->Start();
	}



	public function Start() {
		if ($this->start == xDef::INT_MIN)
			$this->start = self::Timestamp();
	}
	public function Reset() {
		$this->start = self::Timestamp();
	}



	public function getTimeSinceStart() {
		$now   = self::Timestamp();
		$start = $this->start;
		if ($start == xDef::INT_MIN)
			return false;
		if ($start > $now)
			return 0.0;
		return $now - $start;
	}
	public function getTimeSinceLast() {
		$now   = self::Timestamp();
		$start = $this->start;
		$last  = $this->last;
		if ($last == xDef::INT_MIN) {
			if ($start == xDef::INT_MIN)
				return false;
			$last = $start;
		}
		if ($last > $now)
			return 0.0;
		$since = $now - $last;
		$this->last = $now;
		return $since;
	}



	public static function Timestamp() {
		return GeneralUtils::Timestamp();
	}



}
