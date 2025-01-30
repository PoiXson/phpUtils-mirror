<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\cache;


abstract class cacher {

	protected $expireSeconds = 120;



	public function __construct($expireSeconds=null) {
		if ($expireSeconds != null) {
			$expireSeconds = (int) $expireSeconds;
			if ($expireSeconds > 0) {
				$this->expireSeconds = $expireSeconds;
			}
		}
	}



	public abstract function hasEntry(...$context);
	public abstract function getEntry($source, ...$context);
	public abstract function setEntry($value,  ...$context);



}
*/
