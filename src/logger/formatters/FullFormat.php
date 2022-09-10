<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\xLogger\formatters;

use \pxn\phpUtils\xLogger\xLogFormatter;
use \pxn\phpUtils\xLogger\xLogRecord;
use \pxn\phpUtils\xLogger\xLevel;

use \pxn\phpUtils\Strings;


class FullFormat implements xLogFormatter {

	const DATE_FORMAT = 'Y-m-d H:i:s';
	const LEVEL_PAD   = 7;

	protected $datetime;

	protected static $isCapture = FALSE;



	public function __construct() {
		$this->datetime = new \DateTime();
	}



	public function getFormatted(xLogRecord $record) {
		$msg = &$record->msg;
		$msg = \str_replace("\r", '', $msg);
		if ($record->msg == NULL) {
			$record->msg = '<NULL>';
		}
		if (empty($record->msg)) {
			return '';
		}
		// stdOut block
		$isOut = xLevel::MatchLevel($record->level, xLevel::STDOUT);
		$isErr = xLevel::MatchLevel($record->level, xLevel::STDERR);
		if ($isOut) {
			if (!self::$isCapture) {
				$msg = "\n <<=== OUT ===>>\n\n{$msg}";
				self::$isCapture = TRUE;
			}
		} else
		// stdErr block
		if ($isErr) {
			if (!self::$isCapture) {
				$msg = "\n <<=== ERR ===>>\n\n{$msg}";
				self::$isCapture = TRUE;
			}
		}
		// format message
		$date  = $this->datetime->format(self::DATE_FORMAT);
		$level = $record->getLevelFormatted();
		$level = Strings::PadCenter($level, self::LEVEL_PAD);
		$msg = " $date [{$level}]  $msg";
		// close stdOut/stdErr blocks
		if (!$isOut && !$isErr) {
			if (self::$isCapture) {
				$msg = "\n <<===========>>\n\n{$msg}";
				self::$isCapture = FALSE;;
			}
		}
		// finished formatting
		return \explode("\n", $msg);
	}



}
*/
