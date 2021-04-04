<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\app;


class xAppTest extends \pxn\phpUtils\app\xApp {

	public bool $has_checked_run_state = false;



	public function __construct() {
		parent::__construct();
	}



	protected function check_run_mode(): void {
		$this->has_checked_run_state = true;
	}



	public function run(): void {
	}



}
