<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use pxn\phpUtils\Paths;


/**
 * @coversDefaultClass \pxn\phpUtils\Paths
 */
class PathsTest extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::all()
	 */
	public function testAll(): void {
		$all = Paths::all();
		$this->assertIsArray($all);
		$this->assertNotEmpty($all);
		foreach ($all['local'] as $key => $path) {
			$this->assertNotEmpty($key);
			$this->assertNotEmpty($path);
		}
	}



	/**
	 * @covers ::pwd()
	 * @covers ::entry()
	 * @covers ::project()
	 * @covers ::utils()
	 */
	public function testPaths(): void {
		// pwd()
		$path = Paths::pwd();
		$this->assertNotEmpty($path);
		$this->assertTrue(\mb_substr($path, 0, 1) == '/');
		$this->assertEquals($path, Paths::getPath('pwd'));
		// entry()
		$path = Paths::entry();
		$this->assertNotEmpty($path);
		$this->assertTrue(\mb_substr($path, 0, 1) == '/');
		$this->assertEquals($path, Paths::getPath('entry'));
		// project()
		$path = Paths::project();
		$this->assertNotEmpty($path);
		$this->assertTrue(\mb_substr($path, 0, 1) == '/');
		$this->assertEquals($path, Paths::getPath('project'));
		// utils()
		$path = Paths::utils();
		$this->assertNotEmpty($path);
		$this->assertTrue(\mb_substr($path, 0, 1) == '/');
		$this->assertEquals($path, Paths::getPath('utils'));
	}



	/**
	 * @covers ::setPath()
	 * @covers ::getPath()
	 */
	public function testAdd(): void {
		$key = 'abc';
		$testPath = '/var/log';
		// path not set
		$path = Paths::getPath($key);
		$this->assertEquals(null, $path);
		// set test path
		Paths::setPath($key, $testPath);
		$path = Paths::getPath($key);
		$this->assertNotEmpty($path);
		$this->assertEquals($testPath, $path);
		// clear test path
		Paths::setPath($key, null);
		$path = Paths::getPath($key);
		$this->assertEquals(null, $path);
	}



}
