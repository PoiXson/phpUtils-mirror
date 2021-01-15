<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\config;


class ConfigDAO {

	protected $data = NULL;
	protected $dataDefault = [];
	protected $dataSuper   = [];

	protected $changed = FALSE;



	protected function __construct(?array $data=NULL) {
		if ($data !== NULL) {
			$this->data = $data;
		}
		// defaults
		$this->initDefaults();
	}



	protected function initDefaults(): void {
	}



	public function data(?array $data=NULL): ?array {
		$previous = $this->data;
		if ($data !== NULL) {
			$this->data = $data;
		}
		return $previous;
	}



	###############
	## get value ##
	###############



	public function getValue(string $key) {
		// super value
		if (isset($this->dataSuper[$key])) {
			return $this->dataSuper[$key];
		}
		// config value
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
		// default value
		if (isset($this->dataDefault[$key])) {
			return $this->dataDefault[$key];
		}
		// no value set
		return NULL;
	}
	public function peakValue(string $key) {
		// config value
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
		// no value set
		return NULL;
	}
	public function getDefault(string $key) {
		if (isset($this->dataDefault[$key])) {
			return $this->dataDefault[$key];
		}
		return NULL;
	}
	public function getSuper(string $key) {
		if (isset($this->dataSuper[$key])) {
			return $this->dataSuper[$key];
		}
		return NULL;
	}



//TODO
//	public function getDAO($key) {
//		$dao = $this->peakDAO($key);
//		if ($dao == NULL) {
//			$dao = new ConfigDAO(
//				$this->name,
//				$key
//			);
//			$this->daoArray[$key] = $dao;
//		}
//		return $dao;
//	}
//	public function peakDAO($key) {
//		$key = self::ValidateKey($key);
//		if (isset($this->daoArray[$key])) {
//			return $this->daoArray[$key];
//		}
//		return NULL;
//	}



	public function getString(string $key): string {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return (string) $value;
	}
	public function getInt(string $key): ?int {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return (integer) $value;
	}
	public function getLong(string $key): ?int {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return (integer) $value;
	}
	public function getFloat(string $key): ?float {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return (float) $value;
	}
	public function getDouble(string $key): ?float {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return (double) $value;
	}
	public function getBool(string $key): ?bool {
		$value = $this->getValue($key);
		if ($value === NULL) {
			return NULL;
		}
		return ($value != FALSE);
	}



	###############
	## set value ##
	###############



	public function setValue(string $key, $value): void {
		if ($this->data[$key] !== $value) {
			$this->data[$key] = $value;
			$this->changed = TRUE;
		}
	}
	public function setRef(string $key, &$value): void {
		$this->data[$key] = &$value;
	}
	public function setDefault(string $key, $value): void {
		$this->dataDefault[$key] = $value;
	}
	public function setSuper(string $key, $value): void {
		$this->dataSuper[$key] = $value;
	}



	public function isDataChanged(): bool {
		return $this->changed;
	}



}
