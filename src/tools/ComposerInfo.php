<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\tools;


class ComposerInfo {

	private static $instances = [];

	protected $file_path;
	protected $json;



	public static function FindJson(int $depth=2): ?ComposerInfo {
		for ($i=0; $i<=$depth; $i++) {
			$path = \str_repeat('/..', $i);
			$path = \realpath( '.'.$path.'/' ).'/composer.json';
			// found file
			if (\file_exists($path)) {
				return self::get($path);
				break;
			}
		}
		return null;
	}
	public static function Get(?string $path=null): ComposerInfo {
		$path = self::SanPath($path);
		if (isset(self::$instances[$path])) {
			$instance = self::$instances[$path];
		} else {
			$instance = new static($path);
			self::$instances[$path] = $instance;
		}
		return $instance;
	}
	protected function __construct(string $file_path=null) {
		if (empty($file_path))
			throw new \Exception('Path not provided for composer.json file');
		if (!\is_file($file_path))
			throw new \Exception('Invalid composer.json file: '.$file_path);
		// read file contents
		$data = \file_get_contents($file_path);
		if ($data === false)
			throw new \Exception('Failed to load composer.json '.$file_path);
		$this->json = \json_decode($data);
		unset($data);
		if ($this->json == null)
			throw new \Exception('Failed to parse composer.json '.$file_path);
//		if (!isset($this->json->version)) {
//			throw new \Exception('Version field not found in composer.json '.$file_path);
//		}
		$this->file_path = $file_path;
	}



	public static function SanPath(?string $path): ?string {
		if (empty($path)) $path = '';
		// trim filename from end
		if (\str_ends_with(haystack: $path, needle: 'composer.json'))
			$path = \dirname($path);
		// normalize path
		$result = \realpath($path);
		if (empty($result))
			throw new \Exception('Path not found: '.$path);
		$path = $result;
		// trim /src from end of path
		if (\str_ends_with(haystack: $path, needle: '/src'))
			$path = \realpath($path.'/../');
		// validate path
		if (empty($path) || $path == '/')
			throw new \Exception('Invalid path: '.$path);
		// append filename
		return $path.'/composer.json';
	}
	public function getFilePath(): string {
		return $this->file_path;
	}



	public function getName(): string {
		if (!isset($this->json->name))
			return null;
		return $this->json->name;
	}
	public function getVersion(): string {
		if (!isset($this->json->version))
			return null;
		return $this->json->version;
	}
	public function getHomepage(): string {
		if (!isset($this->json->homepage))
			return null;
		return $this->json->homepage;
	}



}
