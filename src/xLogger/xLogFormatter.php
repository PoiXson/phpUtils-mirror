<?php
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2016
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpUtils\xLogger;

use pxn\phpUtils\xLogger\xLogRecord;


interface xLogFormatter {


	public function getFormatted(xLogRecord $record);


}
