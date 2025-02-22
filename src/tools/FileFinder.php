<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;

use \pxn\phpUtils\utils\StringUtils;


class FileFinder {

	protected array $search_paths = [];
	protected array $search_files = [];
	protected array $search_exts  = [];



	public function __construct() {
	}



	// ------------------------------------------------------------------------------- //
	// settings



	public function search_path_parents(string $path, int $depth): void {
		if (empty($path)) throw new RequiredArgumentException('path');
		$pth = \realpath($path);
		if (empty($pth)) throw new NullPointerException("Path not found: $path");
		$this->search_paths[] = $pth;
		if ($depth == -1) $depth = 100;
		if ($depth > 0) {
			for ($i=0; $i<$depth; $i++) {
				$p = \realpath($pth.\str_repeat('/..', $i+1));
				if (empty($p)) break;
				$this->search_paths[] = $p;
				if ($p == '/') break;
			}
		}
	}

	public function search_paths(string...$paths): void {
		$this->search_paths_array($paths);
	}
	public function search_paths_array(array $paths): void {
		$this->search_paths = \array_merge($this->search_paths, $paths);
	}
	public function get_search_paths(): array {
		return $this->search_paths;
	}



	public function search_files(string...$files): void {
		$this->search_files_array($files);
	}
	public function search_files_array(array $files): void {
		$this->search_files = \array_merge($this->search_files, $files);
	}
	public function get_search_files(): array {
		return $this->search_files;
	}



	public function search_extensions(string...$exts): void {
		$this->search_extensions_array($exts);
	}
	public function search_extensions_array(array $exts): void {
		$this->search_exts = \array_merge($this->search_exts, $exts);
	}
	public function get_search_extensions(): array {
		return $this->search_exts;
	}



	// ------------------------------------------------------------------------------- //
	// do search



	public function find(): ?string {
		$result = $this->doFind(all: false);
		if ($result === null || \count($result) == 0)
			return null;
		return \reset($result);
	}

	public function findAll(): array {
		$result = $this->doFind(all: true);
		if ($result === null || \count($result) == 0)
			return [];
		return $result;
	}

	protected function doFind(bool $all=false): ?array {
		$found = [];
		foreach ($this->search_paths as $path) {
			if (\count($this->search_files) == 0) {
				if (\is_dir($path)) {
					$path = \realpath($path);
					if (!empty($path)) {
						if (!$all)
							return [ $path ];
						$found[$path] = true;
					}
				}
			} else {
				foreach ($this->search_files as $file) {
					$path = StringUtils::force_ends_with($path, '/');
					$filePath = $path.$file;
					if (\count($this->search_exts) == 0) {
						if (\file_exists($filePath)) {
							$filePath = \realpath($filePath);
							if (!empty($filePath)) {
								if (!$all)
									return [ $filePath ];
								$found[$filePath] = true;
							}
						}
					} else {
						foreach ($this->search_exts as $ext) {
							$ext = StringUtils::trim_front($ext, '.');
							$fileExtPath = "$filePath.$ext";
							if (\file_exists($fileExtPath)) {
								$fileExtPath = \realpath($fileExtPath);
								if (!empty($fileExtPath)) {
									if (!$all)
										return [ $fileExtPath ];
									$found[$fileExtPath] = true;
								}
							}
						} // end foreach search_exts
					}
				} // end foreach search_files
			}
		} // end foreach search_paths
		if ($all)
			return \array_keys($found);
		return null;
	}



}
