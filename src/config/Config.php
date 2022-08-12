<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\config;


class Config extends ConfigDAO {

	protected $file  = NULL;
	protected $mtime = NULL;



	public function __construct(?string $file=NULL) {
		if ($file !== NULL) {
			$this->loadFile($file);
		}
		parent::__construct();
	}



	protected function initDefaults(): void {
		parent::initDefaults();
	}



	#################
	## Load Config ##
	#################



	public function loadFile(?string $file=NULL): void {
		if (!empty($file)) {
			$file = (string) $file;
			if (!empty($file)) {
				$this->file = $file;
			}
		}
		if (empty($this->file)) {
			throw new \RuntimeException('Config file not set');
		}
		{
			$f = \realpath($this->file);
			if (empty($f) || !\file_exists($f)) {
				throw new \RuntimeException("Config file not found: {$this->file}");
			}
			$this->file = $f;
			unset($f);
		}
		$data = \file_get_contents($this->file);
		if ($data === FALSE) {
			throw new \RuntimeException("Failed to read config file: {$this->file}");
		}
		// detect file type
		{
			$pos = \mb_strrpos($this->file, '.');
			if ($pos === FALSE) {
				throw new \RuntimeException("Unknown config file type: {$this->file}");
			}
			$ext = \mb_substr($this->file, $pos);
			if ($ext == '.json') {
				$this->loadStringJson($data);
			} elseif ($ext == '.yml') {
				$this->loadStringYaml($data);
			} else {
				throw new \RuntimeException("Unknown config file type: $ext");
			}
			unset($pos);
		}
		$this->mtime = \filemtime($this->file);
	}
	public function loadStringJson(string $data): void {
		if (empty($data)) return;
		$json = \json_decode($data, TRUE, \JSON_OBJECT_AS_ARRAY | \JSON_THROW_ON_ERROR);
		if ($json === NULL) {
			throw new \RuntimeException("Failed to parse config file: {$this->file}");
		}
		$this->data = $json;
	}
	public function loadStringYaml(string $data): void {
		if (empty($data)) return;
		$yaml = \yaml_parse($data);
		if ($yaml === NULL) {
			throw new \RuntimeException("Failed to parse config file: {$this->file}");
		}
		$this->data = $yaml;
	}



	public function isFileChanged(): bool {
		$mtimeCurrent = \filemtime($this->file);
		return ($mtimeCurrent != $this->mtime);
	}



}
*/
