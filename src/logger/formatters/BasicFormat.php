<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\logger\formatters;

use \pxn\phpUtils\logger\xLogFormatter;
use \pxn\phpUtils\logger\xLogRecord;


class BasicFormat implements xLogFormatter {



	public function getFormatted(xLogRecord $record) {
		$msg = &$record->msg;
		$msg = \str_replace("\r", '', $msg);
		if ($record->msg == null)
			$record->msg = '<NULL>';
		if (empty($record->msg))
			return '';
		$msg = $record->msg;
		return \explode("\n", $msg);
	}



}
