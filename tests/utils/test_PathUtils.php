<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use pxn\phpUtils\utils\PathUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\PathUtils
 */
class test_PathUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::get_filename
	 */
	public function test_get_filename(): void {
		$this->assertEquals(expected: '',         actual: PathUtils::get_filename('')             );
		$this->assertEquals(expected: 'hostname', actual: PathUtils::get_filename('/etc/hostname'));
		$this->assertEquals(expected: 'hostname', actual: PathUtils::get_filename('hostname')     );
	}



	/**
	 * @covers ::build_path
	 */
	public function test_build_path(): void {
		$this->assertEquals(expected: '',                    actual: PathUtils::build_path()                                );
		$this->assertEquals(expected: 'home',                actual: PathUtils::build_path('', 'home', '')                  );
		$this->assertEquals(expected: 'home/user/Desktop',   actual: PathUtils::build_path('home', 'user', 'Desktop')       );
		$this->assertEquals(expected: '/home/user/Desktop',  actual: PathUtils::build_path('/', 'home', 'user', 'Desktop')  );
		$this->assertEquals(expected: '/home/user/Desktop/', actual: PathUtils::build_path('/home', 'user', 'Desktop', '/') );
		$this->assertEquals(expected: '/home/user/Desktop/', actual: PathUtils::build_path('/home/', '/user/', '/Desktop/') );
	}



	/**
	 * @covers ::common_path
	 */
	public function test_common_path(): void {
		$this->assertEquals(expected: '',           actual: PathUtils::common_path('', '')                                       );
		$this->assertEquals(expected: '/home/user', actual: PathUtils::common_path('/home/user/Desktop', '/home/user/Documents') );
		$this->assertEquals(expected: '/home/user', actual: PathUtils::common_path('/home/user/',        '/home/user/Documents/'));
		$this->assertEquals(expected: '/',          actual: PathUtils::common_path('/usr/bin',           '/etc/profile.d')       );
	}



	/**
	 * @covers ::resolve_symlinks
	 */
	public function test_resolve_symlinks(): void {
		$this->assertEquals(expected: 'usr/bin', actual: PathUtils::resolve_symlinks('/bin'));
	}



}
