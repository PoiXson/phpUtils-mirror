<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\config;


class ConfigDAO {

	protected $data = null;
	protected $dataDefault = [];
	protected $dataSuper   = [];

	protected $changed = false;



	protected function __construct(?array $data=null) {
		if ($data !== null) {
			$this->data = $data;
		}
		// defaults
		$this->initDefaults();
	}



	protected function initDefaults(): void {
	}



	public function data(?array $data=null): ?array {
		$previous = $this->data;
		if ($data !== null) {
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
		return null;
	}
	public function peakValue(string $key) {
		// config value
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
		// no value set
		return null;
	}
	public function getDefault(string $key) {
		if (isset($this->dataDefault[$key])) {
			return $this->dataDefault[$key];
		}
		return null;
	}
	public function getSuper(string $key) {
		if (isset($this->dataSuper[$key])) {
			return $this->dataSuper[$key];
		}
		return null;
	}



//TODO
//	public function getDAO($key) {
//		$dao = $this->peakDAO($key);
//		if ($dao == null) {
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
//		return null;
//	}



	public function getString(string $key): string {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return (string) $value;
	}
	public function getInt(string $key): ?int {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return (integer) $value;
	}
	public function getLong(string $key): ?int {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return (integer) $value;
	}
	public function getFloat(string $key): ?float {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return (float) $value;
	}
	public function getDouble(string $key): ?float {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return (double) $value;
	}
	public function getBool(string $key): ?bool {
		$value = $this->getValue($key);
		if ($value === null) {
			return null;
		}
		return ($value != false);
	}



	###############
	## set value ##
	###############



	public function setValue(string $key, $value): void {
		if ($this->data[$key] !== $value) {
			$this->data[$key] = $value;
			$this->changed = true;
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
*/
