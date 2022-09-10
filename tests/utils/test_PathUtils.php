<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\PathUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\PathUtils
 */
class test_PathUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::FileName
	 */
	public function test_FileName(): void {
		$this->assertEquals(expected: '',         actual: PathUtils::FileName(''             ));
		$this->assertEquals(expected: 'hostname', actual: PathUtils::FileName('/etc/hostname'));
		$this->assertEquals(expected: 'hostname', actual: PathUtils::FileName('hostname'     ));
	}



	/**
	 * @covers ::TrimPath
	 */
	public function test_TrimPath(): void {
		$path = '/etc/nginx/conf.d/website.conf';
		$this->assertEquals(expected: false,                 actual: PathUtils::TrimPath('',    '/etc'              ));
		$this->assertEquals(expected: false,                 actual: PathUtils::TrimPath($path, '/var/www'          ));
		$this->assertEquals(expected: 'conf.d/website.conf', actual: PathUtils::TrimPath($path, '/etc/nginx'        ));
		$this->assertEquals(expected: 'website.conf',        actual: PathUtils::TrimPath($path, '/etc/nginx/conf.d/'));
	}



}
