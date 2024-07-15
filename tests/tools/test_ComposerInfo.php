<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\tests;

use \pxn\phpUtils\ComposerInfo;
use \pxn\phpUtils\Strings;


/ **
 * @coversDefaultClass \pxn\phpUtils\ComposerInfo
 * /
class ComposerInfoTest extends \PHPUnit\Framework\TestCase {



	/ **
	 * @covers ::get
	 * @covers ::__construct
	 * /
	public function testInstances() {
		$a = ComposerInfo::get();
		$b = ComposerInfo::get();
		$this->assertNotNull($a);
		$this->assertNotNull($b);
		$this->assertTrue($a === $b);
	}



	/ **
	 * @covers ::getFilePath
	 * @covers ::SanPath
	 * /
	public function testPaths() {
		$expect = \realpath(__DIR__.'/../composer.json');
		// default path
		$composer = ComposerInfo::get();
		$this->assertNotNull($composer);
		$this->assertEquals($expect, $composer->getFilePath());
		unset($composer);
		// exact path
		$path = \realpath(__DIR__.'/../');
		$composer = ComposerInfo::get($path.'/composer.json');
		$this->assertNotNull($composer);
		$this->assertEquals($expect, $composer->getFilePath());
		unset($composer, $path);
		// invalid path
		try {
			$composer = ComposerInfo::get('notexisting');
			$this->assertFalse(TRUE, 'Expected exception not thrown!');
			return;
		} catch (\Exception $ignore) {}
	}



	/ **
	 * @covers ::getName
	 * @covers ::getVersion
	 * @covers ::getHomepage
	 * /
	public function testValues() {
		$composer = ComposerInfo::get();
		$this->assertNotNull($composer);
		// name
		$name = $composer->getName();
		$this->assertEquals('pxn/phputils', $name);
		// version
		//$version = $composer->getVersion();
		//$this->assertNotEmpty($version);
		//$this->assertTrue(\mb_strpos($version, '.') !== FALSE);
		// homepage
		$homepage = $composer->getHomepage();
		$this->assertNotEmpty($homepage);
		$this->assertTrue(\str_starts_with(haystack: $homepage, needle: 'http'));
		$this->assertTrue(\mb_strpos($homepage, '.') !== FALSE);
	}



}
*/
