<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\app;


/**
 * @coversDefaultClass \pxn\phpUtils\app\xApp
 */
class test_xApp extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::__construct
	 * @covers ::getAppName
	 * @covers ::getNamespace
	 * @covers ::getVersion
	 * @covers ::check_run_mode
	 */
	public function test_xApp() {
		$app = new TestApp();
		$this->assertNotNull($app);
		$this->assertEquals(expected: "TestApp",                  actual: $app->getAppName()  );
		$this->assertEquals(expected: "pxn\\phpUtils\\tests\app", actual: $app->getNamespace());
		$this->assertEquals(expected: 'x.y.z',                    actual: $app->getVersion()  );
	}



}
