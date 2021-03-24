<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\app;
include_once('Version.php');

use pxn\phpUtils\app\xApp;


/**
 * @coversDefaultClass \pxn\phpUtils\app\xApp
 */
class test_StringUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::__construct
	 * @covers ::getName
	 * @covers ::getNamespace
	 * @covers ::getVersion
	 * @covers ::check_run_mode
	 */
	public function test_xApp() {
		$app = new xAppTest();
		$this->assertNotNull($app);
		$this->assertEquals(expected: "xAppTest", actual: $app->getName());
		$this->assertEquals(expected: "pxn\\phpUtils\\tests\app", actual: $app->getNamespace());
		$this->assertEquals(expected: 'x.y.z', actual: $app->getVersion());
		$this->assertTrue($app->has_checked_run_state);
	}



}

class xAppTest extends xApp {

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
