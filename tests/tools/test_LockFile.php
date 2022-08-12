<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2022
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
		if (!$this->hasFailed()) {
			if (\is_file($this->file)) {
				\unlink($this->file);
			}
		}
	}



	/**
	 * @covers ::__construct
	 * @covers ::getLock
	 */
	public function test_getLock(): void {
		if ($this->hasFailed()) return;
		$this->assertNull($this->lockA);
		$this->assertNull($this->lockB);
		$this->lockA = new LockFile($this->file);
		$this->lockB = new LockFile($this->file);
		$this->lockA->getLock(0);
		$this->assertTrue($this->lockA->hasLock());
		$this->expectException(ConcurrentLockException::class);
		$this->expectExceptionMessage('Concurrent lock on file: '.$this->file);
		try {
			$this->lockB->getLock(0);
		} finally {
			$this->assertFalse($this->lockB->hasLock());
		}
	}



}
