<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\exceptions;


class ConcurrentLockException extends \RuntimeException {



	public function __construct(?string $file=null) {
		if (empty($file)) {
			parent::__construct();
		} else {
			parent::__construct("Concurrent lock on file: $file");
		}
	}



}
*/
