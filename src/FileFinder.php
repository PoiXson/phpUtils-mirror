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



	public function find() {
		foreach ($this->searchPaths as $path) {
			if (count($this->searchFiles) == 0) {
				if (\is_dir($path)) {
					return $path;
				}
			} else {
				foreach ($this->searchFiles as $file) {
					$path = Strings::ForceEndsWith($path, '/');
					$filePath = $path.$file;
					if (\file_exists($filePath)) {
						return $filePath;
					}
				}
			}
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
