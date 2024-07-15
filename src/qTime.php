<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


class qTime {

	protected $start = Defines::INT_MIN;
	protected $last  = Defines::INT_MIN;

	protected static $global = NULL;



	public static function getGlobal() {
		if (self::$global == NULL)
			self::$global = new static(TRUE);
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



	public function __construct($startNow=TRUE) {
		if($startNow)
			$this->Start();
	}



	public function Start() {
		if ($this->start == Defines::INT_MIN)
			$this->start = self::Timestamp();
	}
	public function Reset() {
		$this->start = self::Timestamp();
	}



	public function getTimeSinceStart() {
		$now   = self::Timestamp();
		$start = $this->start;
		if ($start == Defines::INT_MIN)
			return FALSE;
		if ($start > $now)
			return 0.0;
		return $now - $start;
	}
	public function getTimeSinceLast() {
		$now   = self::Timestamp();
		$start = $this->start;
		$last  = $this->last;
		if ($last == Defines::INT_MIN) {
			if ($start == Defines::INT_MIN)
				return FALSE;
			$last = $start;
		}
		if ($last > $now)
			return 0.0;
		$since = $now - $last;
		$this->last = $now;
		return $since;
	}



	public static function Timestamp() {
		return General::Timestamp();
	}



}
