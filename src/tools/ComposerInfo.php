<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\tools;


class ComposerInfo {

	private static $instances = [];

	protected $filePath;
	protected $json;



	public static function findJson($depth=2) {
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
	public static function get($path=null) {
		$path = self::SanPath($path);
		try {
			if (isset(self::$instances[$path])) {
				$instance = self::$instances[$path];
			} else {
				$instance = new static($path);
				self::$instances[$path] = $instance;
			}
		} catch (\Exception $e) {
			fail('Failed to get composer instance!',
				Defines::EXIT_CODE_INTERNAL_ERROR, $e);
		}
		return $instance;
	}
	protected function __construct($filePath=null) {
		if (empty($filePath) || !\is_file($filePath)) {
			throw new \Exception("Invalid composer.json file: $filePath");
		}
		// read file contents
		$data = \file_get_contents($filePath);
		if ($data === false) {
			throw new \Exception("Failed to load composer.json $filePath");
		}
		$this->json = \json_decode($data);
		unset($data);
		if ($this->json == null) {
			throw new \Exception("Failed to parse composer.json $filePath");
		}
//		if (!isset($this->json->version)) {
//			throw new \Exception("Version field not found in composer.json $filePath");
//		}
		$this->filePath = $filePath;
	}



	public static function SanPath($filePath) {
		// trim filename from end
		if (\str_ends_with(haystack: $filePath, needle: 'composer.json')) {
			$filePath = \dirname($filePath);
		}
		// normalize path
		$filePath = \realpath($filePath);
		// trim /src from end of path
		if (\str_ends_with(haystack: $filePath, needle: '/src')) {
			$filePath = \realpath($filePath.'/../');
		}
		// validate path
		if (empty($filePath) || $filePath == '/') {
			throw new \Exception('Invalid path');
		}
		// append filename
		return $filePath.'/composer.json';
	}
	public function getFilePath() {
		return $this->filePath;
	}



	public function getName() {
		if (!isset($this->json->name)) {
			return null;
		}
		return $this->json->name;
	}
	public function getVersion() {
		if (!isset($this->json->version)) {
			return null;
		}
		return $this->json->version;
	}
	public function getHomepage() {
		if (!isset($this->json->homepage)) {
			return null;
		}
		return $this->json->homepage;
	}



}
*/
