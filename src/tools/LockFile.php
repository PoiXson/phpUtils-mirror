<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use pxn\phpUtils\exceptions\ConcurrentLockException;


class LockFile {

	protected string $file;

	protected $handle = false;



	public function __construct(string $file) {
		$this->file = $file;
	}



	public function getLock(int $wait=0): void {
		if ($this->handle != false)
			throw new \RuntimeException('File already locked by this process: '.$this->file);
		// open file
		$handle = \fopen($this->file, 'a');
		if ($handle == false)
			throw new \RuntimeException('Failed to open lock file: '.$this->file);
		$loops = 0;
		while (true) {
			$wouldblock = 0;
			if (\flock($handle, \LOCK_EX|\LOCK_NB, $wouldblock)) {
				$this->handle = $handle;
				return;
			}
			// unknown failure
			if ($wouldblock != 1) {
				\fclose($handle);
				throw new \RuntimeException('Failed to get lock: '.$this->file);
			}
			if ($wait >= 0) {
				if ($loops >= $wait)
					throw new ConcurrentLockException($this->file);
			}
			$loops++;
			\sleep(1);
		}
	}

	public function release(): void {
		if ($this->handle == false)
			return;
		\flock($this->handle, \LOCK_UN);
		\fclose($this->handle);
		$this->handle = false;
	}



	public function hasLock(): bool {
		return ($this->handle != false);
	}



	public function getHandle() {
		return $this->handle;
	}



}
