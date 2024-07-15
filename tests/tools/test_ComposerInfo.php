<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\tools;

use \pxn\phpUtils\tools\ComposerInfo;
use \pxn\phpUtils\utils\StringUtils;


/**
 * @coversDefaultClass \pxn\phpUtils\tools\ComposerInfo
 */
class test_ComposerInfo extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::Get
	 * @covers ::__construct
	 */
	public function test_Instances() {
		$a = ComposerInfo::Get();
		$b = ComposerInfo::Get();
		$this->assertNotNull($a);
		$this->assertNotNull($b);
		$this->assertTrue($a === $b);
	}



	/**
	 * @covers ::getFilePath
	 * @covers ::SanPath
	 */
	public function test_Paths() {
		$expect = \realpath(__DIR__.'/../../composer.json');
		// default path
		$composer = ComposerInfo::Get();
		$this->assertNotNull($composer);
		$this->assertEquals($expect, $composer->getFilePath());
		unset($composer);
		// exact path
		$path = \realpath(__DIR__.'/../../');
		$composer = ComposerInfo::Get($path.'/composer.json');
		$this->assertNotNull($composer);
		$this->assertEquals($expect, $composer->getFilePath());
		unset($composer, $path);
		// invalid path
		try {
			$composer = ComposerInfo::Get('notexisting');
			$this->assertFalse(true, 'Expected exception not thrown!');
			return;
		} catch (\Exception $ignore) {}
	}



	/**
	 * @covers ::getName
	 * @covers ::getVersion
	 * @covers ::getHomepage
	 */
	public function test_Values() {
		$composer = ComposerInfo::Get();
		$this->assertNotNull($composer);
		// name
		$name = $composer->getName();
		$this->assertEquals('pxn/phputils', $name);
		// version
		//$version = $composer->getVersion();
		//$this->assertNotEmpty($version);
		//$this->assertTrue(\mb_strpos($version, '.') !== false);
		// homepage
		$homepage = $composer->getHomepage();
		$this->assertNotEmpty($homepage);
		$this->assertTrue(\str_starts_with(haystack: $homepage, needle: 'http'));
		$this->assertTrue(\mb_strpos($homepage, '.') !== false);
	}



}
