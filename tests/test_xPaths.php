<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\xPaths;


/**
 * @coversDefaultClass \pxn\phpUtils\xPaths
 */
class test_xPaths extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::init
	 * @covers ::pwd
	 * @covers ::entry
	 * @covers ::get
	 * @covers ::set
	 * @covers ::GetAll
	 */
	public function test_xPaths(): void {
		$all = [];
		// pwd path
		$path = xPaths::pwd();
		$all['pwd'] = $path;
		$this->assertNotEmpty($path);
		$this->assertTrue(\str_starts_with(haystack: $path, needle: '/'));
		$this->assertEquals(expected: $path, actual: xPaths::get('pwd'));
		$this->assertEquals(expected: \getcwd(), actual: $path);
		// entry path
		$path = xPaths::entry();
		$all['entry'] = $path;
		$this->assertNotEmpty($path);
		$this->assertTrue(\str_starts_with(haystack: $path, needle: '/'));
		$this->assertEquals(expected: $path, actual: xPaths::get('entry'));
		// common path
		$path = xPaths::common();
		$all['common'] = $path;
		$this->assertNotEmpty($path);
		$this->assertTrue(\str_starts_with(haystack: $path, needle: '/'));
		$this->assertEquals(expected: $path, actual: xPaths::get('common'));
		// set custom path
		$all['home'] = '/home';
		xPaths::set(key: 'home', path: '/home');
		$this->assertEquals(expected: '/home', actual: xPaths::get('home'));
		// set with {pwd}
		$all['abc'] = xPaths::pwd().'/abc';
		xPaths::set(key: 'abc', path: '{pwd}/abc');
		$this->assertEquals(expected: $all['abc'], actual: xPaths::get('abc'));
		// set with {entry}
		$all['abcd'] = xPaths::entry().'/abcd';
		xPaths::set(key: 'abcd', path: '{entry}/abcd');
		$this->assertEquals(expected: $all['abcd'], actual: xPaths::get('abcd'));
		// set with {home}
		$all['docs'] = '/home/user/docs';
		xPaths::set(key: 'docs', path: '{home}/user/docs');
		$this->assertEquals(expected: $all['docs'], actual: xPaths::get('docs'));
		// all paths
		$this->assertEquals(expected: $all, actual: xPaths::GetAll());
	}



	/**
	 * @covers ::ReplaceTags
	 */
	public function test_xPaths_ReplaceTags(): void {
		// replace in string
		$common = \realpath(__dir__.'/..');
		$this->assertEquals(expected: $common, actual: xPaths::common());
		$this->assertEquals(expected: $common        .'/abc', actual: xPaths::ReplaceTags('{common}/abc'  ));
		$this->assertEquals(expected: xPaths::pwd()  .'/abc', actual: xPaths::ReplaceTags(   '{pwd}/abc'  ));
		$this->assertEquals(expected: xPaths::entry().'/abc', actual: xPaths::ReplaceTags( '{entry}/abc'  ));
		$this->assertEquals(expected: '/abc/{pwd}/def',       actual: xPaths::ReplaceTags('/abc/{pwd}/def'));
	}



	/**
	 * @covers ::assert
	 */
	public function test_assert(): void {
		xPaths::assert('pwd');
		xPaths::assert('entry');
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Path not set: test');
		xPaths::assert('test');
	}

	/**
	 * @covers ::get
	 */
	public function test_path_unknown(): void {
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Path not set: unknown');
		xPaths::get('unknown');
	}

	/**
	 * @covers ::set
	 */
	public function test_path_tag_unknown(): void {
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Unknown path tag: unknown');
		xPaths::set(key: 'fail', path: '{unknown}/fail');
	}



}
