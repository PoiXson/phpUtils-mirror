<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\exceptions;


class NullPointerException extends \RuntimeException {



	public function __construct(?string $msg=null) {
		if (empty($msg)) parent::__construct();
		else             parent::__construct('Null Pointer Exception: '.$msg);
	}



}
