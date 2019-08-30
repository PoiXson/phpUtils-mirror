<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;


abstract class Render {

	protected $app = NULL;



	public function __construct(App $app) {
		$this->app = $app;
	}



	public abstract function getName();
	public abstract function getWeight();

	public abstract function doRender();



}
