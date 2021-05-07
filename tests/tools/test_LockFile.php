<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tests\tools;

use pxn\phpUtils\tools\LockFile;
use pxn\phpUtils\exceptions\ConcurrentLockException;


/**
 * @coversDefaultClass \pxn\phpUtils\tools\LockFile
 */
class test_LockFile extends \PHPUnit\Framework\TestCase {

	protected string $file = '/tmp/test.txt';

	protected ?LockFile $lockA = null;
	protected ?LockFile $lockB = null;



	public function tearDown(): void {
		if ($this->lockA != null) {
			$this->lockA->release();
			$this->lockA = null;
		}
		if ($this->lockB != null) {
			$this->lockB->release();
			$this->lockB = null;
		}
		// remove test file
		if (\is_file($this->file)) {
			\unlink($this->file);
		}
	}



	/**
	 * @covers ::__construct
	 * @covers ::getLock
	 */
	public function test_getLock(): void {
		if (\file_exists($this->file))
			throw new \RuntimeException('File already exists: '.$this->file);
		$lockA = new LockFile($this->file);
		$lockB = new LockFile($this->file);
		$lockA->getLock(1);
		$this->assertTrue($lockA->hasLock());
		$this->expectException(ConcurrentLockException::class);
		$this->expectExceptionMessage('Concurrent lock on file: '.$this->file);
		try {
			$lockB->getLock(0);
		} finally {
			$this->assertFalse($lockB->hasLock());
		}
	}



}
