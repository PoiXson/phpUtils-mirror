<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils;


class CoolDown {

	public $duration = 1.0;
	public $last = Defines::INT_MIN;



	public function __construct($duration) {
		$this->duration = (double) $duration;
	}



	public function runAgain() {
		$current = self::Timestamp();
		// first run
		if ($this->last < 0.0) {
			$this->last = $current;
			return true;
		}
		// run again
		if ($current - $this->last >= $this->duration) {
			$this->last = $current;
			return true;
		}
		// cooling
		return false;
	}



	public function getTimeSince() {
		if ($this->last < 0.0) {
			return -1.0;
		}
		return self::Timestamp() - $this->last;
	}
	public function getTimeUntil() {
		if ($this->last < 0.0) {
			return -1.0;
		}
		return ($this->last + $this->duration) - self::Timestamp();
	}



	public function reset() {
		$this->last = Defines::INT_MIN;
	}
	public function resetRun() {
		$this->last = self::Timestamp();
	}



	public static function Timestamp() {
		return General::Timestamp();
	}



}
*/
