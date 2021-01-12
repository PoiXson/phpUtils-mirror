<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


class FileFinder {

	protected $searchPaths = [];
	protected $searchFiles = [];
	protected $searchExts  = [];



	public function __construct() {
	}



	// ------------------------------------------------------------------------------- //
	// settings



	public function searchPath(string $path, int $depth=2): void {
		$pth = \realpath($path);
		if (empty($pth)) throw new NullPointerException("Path not found: $path");
		$this->searchPaths[] = $pth;
		if ($depth == -1) $depth = 100;
		if ($depth > 0) {
			for ($i=0; $i<$depth; $i++) {
				$p = \realpath($pth.\str_repeat('/..', $i+1));
				if (empty($p)) break;
				$this->searchPaths[] = $p;
				if ($p == '/') break;
			}
		}
	}
	public function searchPaths(string...$paths): void {
		$this->searchPathsArray($paths);
	}
	public function searchPathsArray(array $paths): void {
		$this->searchPaths = \array_merge($this->searchPaths, $paths);
	}
	public function getSearchPaths(): array {
		return $this->searchPaths;
	}
	public function clearSearchPaths(): void {
		$this->searchPaths = [];
	}



	public function searchFiles(string...$files): void {
		$this->searchFilesArray($files);
	}
	public function searchFilesArray(array $files): void {
		$this->searchFiles = \array_merge($this->searchFiles, $files);
	}
	public function getSearchFiles(): array {
		return $this->searchFiles;
	}
	public function clearSearchFiles(): void {
		$this->searchFiles = [];
	}



	public function searchExtensions(string...$exts): void {
		$this->searchExts = \array_merge($this->searchExts, $exts);
	}
	public function getSearchExtensions(): array {
		return $this->searchExts;
	}
	public function clearSearchExtensions(): void {
		$this->searchExts = [];
	}



	// ------------------------------------------------------------------------------- //
	// do search



	public function find(): ?string {
		$result = $this->doFind(FALSE);
		if ($result === NULL || \count($result) < 1)
			return NULL;
		return $result[0];
	}
	public function findAll(): array {
		$result = $this->doFind(TRUE);
		if ($result === NULL || \count($result) < 1)
			return [];
		return $result;
	}
	protected function doFind(bool $all=FALSE): ?array {
		$found = [];
		foreach ($this->searchPaths as $path) {
			if (\count($this->searchFiles) == 0) {
				if (\is_dir($path)) {
					$path = \realpath($path);
					if (!empty($path)) {
						if ($all) {
							$found[$path] = TRUE;
						} else {
							return [ $path ];
						}
					}
				}
			} else {
				foreach ($this->searchFiles as $file) {
					$path = Strings::ForceEndsWith($path, '/');
					$filePath = $path.$file;
					if (\count($this->searchExts) == 0) {
						if (\file_exists($filePath)) {
							$filePath = \realpath($filePath);
							if (!empty($filePath)) {
								if ($all) {
									$found[$filePath] = TRUE;
								} else {
									return [ $filePath ];
								}
							}
						}
					} else {
						foreach ($this->searchExts as $ext) {
							$ext = Strings::TrimFront($ext, '.');
							$fileExtPath = "$filePath.$ext";
							if (\file_exists($fileExtPath)) {
								$fileExtPath = \realpath($fileExtPath);
								if (!empty($fileExtPath)) {
									if ($all) {
										$found[$fileExtPath] = TRUE;
									} else {
										return [ $fileExtPath ];
									}
								}
							}
						} // end foreach searchExts
					}
				} // end foreach searchFiles
			}
		} // end foreach searchPaths
		if ($all) {
			return \array_keys($found);
		}
		return NULL;
	}



}
