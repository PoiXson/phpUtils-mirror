<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\utils;

use \pxn\phpUtils\utils\SystemUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\utils\SystemUtils
 */
class test_SystemUtils extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::IsShell
	 * @covers ::IsWeb
	 */
	public function test_IsShell(): void {
		$this->assertTrue( SystemUtils::IsShell());
		$this->assertFalse(SystemUtils::IsWeb());
	}



	/**
	 * @covers ::IsUsrInstalled
	 */
	public function test_IsUsrInstalled(): void {
		$this->assertFalse(SystemUtils::IsUsrInstalled());
	}



	/**
	 * @covers ::GetUser
	 * @covers ::DenySuperUser
	 * @covers ::IsSuperUser
	 */
	public function test_GetUser(): void {
		SystemUtils::DenySuperUser();
		$this->assertNotEmpty(SystemUtils::GetUser());
		$this->assertTrue( SystemUtils::IsSuperUser('root'));
		$this->assertTrue( SystemUtils::IsSuperUser('system'));
		$this->assertTrue( SystemUtils::IsSuperUser('admin'));
		$this->assertTrue( SystemUtils::IsSuperUser('administrator'));
		$this->assertFalse(SystemUtils::IsSuperUser('user'));
	}



}
/*
	const TEST_DIR1 = '_SystemTest_TEMP_/';
	const TEST_DIR2 = 'AnotherDir/';
	const TEST_FILE = 'testfilename.txt';



	/ **
	 * @covers ::GetUser
	 * /
	public function testGetUser(): void {
		$originalUser = (
			isset($_SERVER['USER'])
			? $_SERVER['USER']
			: null
		);
		// test SERVER[USER]
		$_SERVER['USER'] = 'testuser';
		$this->assertEquals('testuser', SystemUtils::GetUser());
		// test whoami
		unset($_SERVER['USER']);
		$this->assertIsString(SystemUtils::GetUser());
		$this->assertEquals($originalUser, SystemUtils::GetUser());
		// restore _SERVER[USER]
		if ($originalUser === null) {
			if (isset($_SERVER['USER'])) {
				unset($_SERVER['USER']);
			}
		} else {
			$_SERVER['USER'] = $originalUser;
		}
	}



/ *
	/ **
	 * @ covers ::mkDir
	 * @ covers ::rmDir
	 * /
	public function test_mkDir_rmDir() {
		$cwd = $this->getCWD();
		// ensure clean
		$this->assertFalse(
			\is_dir($cwd.self::TEST_DIR1),
			\sprintf(
				'Temporary test directory already exists: %s',
				self::TEST_DIR1
			)
		);
		// create test dirs
		SystemUtils::mkDir($cwd.self::TEST_DIR1.self::TEST_DIR2, 700);
		$this->assertTrue(
			\is_dir($cwd.self::TEST_DIR1.self::TEST_DIR2),
			\sprintf(
				'Failed to create temporary test directory: %s',
				$cwd.self::TEST_DIR1.self::TEST_DIR2
			)
		);
		// create test file
		$this->assertTrue(\touch(
			$cwd.self::TEST_DIR1.self::TEST_DIR2.'TestFile.txt'
		));
		$this->assertTrue(\is_file(
			$cwd.self::TEST_DIR1.self::TEST_DIR2.'TestFile.txt'
		));
		// recursively delete test directories
		SystemUtils::rmDir($cwd.self::TEST_DIR1);
		// ensure removed
		$this->assertFalse(
			\is_dir($cwd.self::TEST_DIR1),
			\sprintf(
				'Failed to remove temporary test directory: %s',
				self::TEST_DIR1
			)
		);
	}



	private function getCWD() {
		$cwd = Strings::ForceEndsWith(\getcwd(), '/');
		$this->assertFalse(empty($cwd));
		$this->assertFalse($cwd == '/');
		return $cwd;
	}
*/
