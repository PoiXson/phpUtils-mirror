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
class test_Paths extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::pwd()
	 * @covers ::entry()
	 * @covers ::get()
	 * @covers ::all()
	 */
	public function test_paths(): void {
		$all = [];
		// pwd path
		$path = Paths::pwd();
		$all['pwd'] = $path;
		$this->assertNotEmpty($path);
		$this->assertTrue(\str_starts_with(haystack: $path, needle: '/'));
		$this->assertEquals(expected: $path, actual: Paths::get('pwd'));
		$this->assertEquals(expected: \getcwd(), actual: $path);
		// entry path
		$path = Paths::entry();
		$all['entry'] = $path;
		$this->assertNotEmpty($path);
		$this->assertTrue(\str_starts_with(haystack: $path, needle: '/'));
		$this->assertEquals(expected: $path, actual: Paths::get('entry'));
		// all paths
		$this->assertEquals(expected: $all, actual: Paths::all());
	}
	/**
	 * @covers ::get()
	 */
	public function test_path_unknown(): void {
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Path not set: unknown');
		Paths::get('unknown');
	}



}
