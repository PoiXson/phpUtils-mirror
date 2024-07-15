<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\tools;

use \pxn\phpUtils\tools\FileFinder;


/**
 * @coversDefaultClass \pxn\phpUtils\tools\FileFinder
 */
class test_FileFinder extends \PHPUnit\Framework\TestCase {



	/**
	 * @covers ::__construct
	 * @covers ::search_path_parents
	 * @covers ::find
	 * @covers ::doFind
	 */
	public function test_search_path_parents(): void {
		// depth 0
		{
			$finder = new FileFinder();
			$this->assertEquals(expected: null, actual: $finder->find());
			$finder->search_path_parents(path: __DIR__.'/testfiles/abcd/efgh', depth: 0);
			$finder->search_files('file_at_root.txt');
			$this->assertEquals(expected: null, actual: $finder->find());
			unset($finder);
		}
		// depth 1
		{
			$finder = new FileFinder();
			$finder->search_path_parents(path: __DIR__.'/testfiles/abcd/efgh', depth: 1);
			$finder->search_files('file_at_root.txt');
			$this->assertEquals(expected: null, actual: $finder->find());
			unset($finder);
		}
		// depth 2
		{
			$finder = new FileFinder();
			$finder->search_path_parents(path: __DIR__.'/testfiles/abcd/efgh', depth: 2);
			$finder->search_files('file_at_root.txt');
			$this->assertEquals(expected: __DIR__.'/testfiles/file_at_root.txt', actual: $finder->find());
			unset($finder);
		}
	}



	/**
	 * @covers ::search_paths
	 * @covers ::search_files
	 * @covers ::search_extensions
	 * @covers ::search_paths_array
	 * @covers ::search_files_array
	 * @covers ::search_extensions_array
	 * @covers ::get_search_paths
	 * @covers ::get_search_files
	 * @covers ::get_search_extensions
	 * @covers ::findAll
	 * @covers ::doFind
	 */
	public function test_search_paths(): void {
		$finder = new FileFinder();
		$this->assertEquals(expected: [], actual: $finder->findAll());
		$finder->search_paths(__DIR__.'/testfiles');
		$finder->search_files('123');
		$finder->search_extensions('txt');
		$this->assertEquals(expected: [__DIR__.'/testfiles'], actual: $finder->get_search_paths());
		$this->assertEquals(expected: ['123'],                actual: $finder->get_search_files());
		$this->assertEquals(expected: ['txt'],                actual: $finder->get_search_extensions());
		$this->assertEquals(expected: [__DIR__.'/testfiles/123.txt'], actual: $finder->findAll());
		unset($finder);
	}



}
