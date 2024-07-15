<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\app;


class TestApp extends \pxn\phpUtils\app\xApp {

	public bool $has_checked_run_state = false;



	public function __construct() {
		parent::__construct();
	}



	protected function check_run_mode(): void {
		parent::check_run_mode();
		$this->has_checked_run_state = true;
	}



	public function run(): void {
	}



}
