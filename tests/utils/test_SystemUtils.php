<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use pxn\phpUtils\utils\SystemUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\SystemUtils
 */
class test_SystemUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::isShell
	 * @covers ::isWeb
	 */
	public function test_isShell(): void {
		$this->assertTrue( SystemUtils::isShell());
		$this->assertFalse(SystemUtils::isWeb());
	}



	/**
	 * @covers ::isUsrInstalled
	 */
	public function test_isUsrInstalled(): void {
		$this->assertFalse(SystemUtils::isUsrInstalled());
		
	}



	/**
	 * @covers ::GetUser
	 * @covers ::DenySuperUser
	 * @covers ::isSuperUser
	 */
	public function test_GetUser(): void {
		SystemUtils::DenySuperUser();
		$this->assertNotEmpty(SystemUtils::GetUser());
		$this->assertTrue( SystemUtils::isSuperUser('root'));
		$this->assertTrue( SystemUtils::isSuperUser('system'));
		$this->assertTrue( SystemUtils::isSuperUser('admin'));
		$this->assertTrue( SystemUtils::isSuperUser('administrator'));
		$this->assertFalse(SystemUtils::isSuperUser('user'));
	}



}
