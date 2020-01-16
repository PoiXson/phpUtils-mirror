<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\exceptions;


class NullPointerException extends \RuntimeException {



	public function __construct(?string $msg=NULL) {
		if (empty($msg)) {
			parent::__construct();
		} else {
			parent::__construct("File not found: $msg");
		}
	}



}
