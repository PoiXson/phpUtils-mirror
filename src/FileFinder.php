<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
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



	public function searchPath(string $path, int $depth=2) {
		$pth = \realpath($path);
		if (empty($path)) throw new NullPointerException("Path not found: $path");
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
	public function searchPaths(string...$paths) {
		$this->searchPaths = \array_merge($this->searchPaths, $paths);
	}
	public function searchFiles(string...$files) {
		$this->searchFiles = \array_merge($this->searchFiles, $files);
	}
	public function searchExtensions(string...$exts) {
		$this->searchExts = \array_merge($this->searchExts, $exts);
	}



	public function find() {
		return $this->doFind(FALSE);
	}
	public function findAll() {
		return $this->doFind(TRUE);
	}
	protected function doFind(bool $all=FALSE) {
		$found = [];
		foreach ($this->searchPaths as $path) {
			if (\count($this->searchFiles) == 0) {
				if (\is_dir($path)) {
					if ($all) {
						$found[$path] = TRUE;
					} else {
						return $path;
					}
				}
			} else {
				foreach ($this->searchFiles as $file) {
					$path = Strings::ForceEndsWith($path, '/');
					$filePath = $path.$file;
					if (\count($this->searchExts) == 0) {
						if (\file_exists($filePath)) {
							if ($all) {
								$found[$filePath] = TRUE;
							} else {
								return $filePath;
							}
						}
					} else {
						foreach ($this->searchExts as $ext) {
							$ext = Strings::TrimFront($ext, '.');
							$fileExtPath = "$filePath.$ext";
							if (\file_exists($fileExtPath)) {
								if ($all) {
									$found[$fileExtPath] = TRUE;
								} else {
									return $fileExtPath;
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



	public function getSearchPaths() {
		return $this->searchPaths;
	}
	public function getSearchFiles() {
		return $this->searchFiles;
	}



}
