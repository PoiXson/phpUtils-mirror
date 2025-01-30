<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\exceptions;


class ReturnException extends \RuntimeException {

	public $result = null;



	public function __construct($result=null) {
		$this->result = $result;
		if (empty($result)) parent::__construct();
		else                parent::__construct('Return: '.$result);
	}



	public function getResult() {
		return $this->result;
	}



}
