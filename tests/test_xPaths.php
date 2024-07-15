<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\Paths;


/ **
 * @coversDefaultClass \pxn\phpUtils\Paths
 * /
class test_Paths extends \PHPUnit\Framework\TestCase {



	/ **
	 * @covers ::init
	 * @covers ::pwd
	 * @covers ::entry
	 * @covers ::get
	 * @covers ::set
	 * @covers ::getAll
	 * /
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
		// set custom path
		$all['home'] = '/home';
		Paths::set(key: 'home', path: '/home');
		$this->assertEquals(expected: '/home', actual: Paths::get('home'));
		// set with {pwd}
		$all['abc'] = Paths::pwd().'/abc';
		Paths::set(key: 'abc', path: '{pwd}/abc');
		$this->assertEquals(expected: $all['abc'], actual: Paths::get('abc'));
		// set with {entry}
		$all['abcd'] = Paths::entry().'/abcd';
		Paths::set(key: 'abcd', path: '{entry}/abcd');
		$this->assertEquals(expected: $all['abcd'], actual: Paths::get('abcd'));
		// set with {home}
		$all['docs'] = '/home/user/docs';
		Paths::set(key: 'docs', path: '{home}/user/docs');
		$this->assertEquals(expected: $all['docs'], actual: Paths::get('docs'));
		// all paths
		$this->assertEquals(expected: $all, actual: Paths::getAll());
	}

	/ **
	 * @covers ::assertPathSet
	 * /
	public function test_assertPathSet(): void {
		Paths::assertPathSet('pwd');
		Paths::assertPathSet('entry');
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Path not set: test');
		Paths::assertPathSet('test');
	}

	/ **
	 * @covers ::get
	 * /
	public function test_path_unknown(): void {
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Path not set: unknown');
		Paths::get('unknown');
	}

	/ **
	 * @covers ::set
	 * /
	public function test_path_tag_unknown(): void {
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Unknown path tag: unknown');
		Paths::set(key: 'fail', path: '{unknown}/fail');
	}



}
*/
