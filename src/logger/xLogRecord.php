<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\xLogger;


class xLogRecord {

	public string $msg;
	public xLevel $level;



	public function __construct(?xLevel $level=null, ?string $msg='') {
		$this->level = $level;
		$this->msg   = $msg;
	}



	public function isLoggable(xLevel $level): bool {
		return xLevel::isLoggable($this->level, $level);
	}
	public function getLevelFormatted(): string {
		return xLevel::FindLevelName($this->level);
	}



}
