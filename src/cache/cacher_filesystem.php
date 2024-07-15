<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\cache;

use \pxn\phpUtils\Arrays;
use \pxn\phpUtils\Strings;
use \pxn\phpUtils\GeneralUtils;
use \pxn\phpUtils\Defines;


class cacher_filesystem extends cacher {

	protected $cachePath = null;



	public function __construct($expireSeconds=null, $cachePath=null) {
		parent::__construct($expireSeconds);
		$this->cachePath = $cachePath;
	}



	public function hasEntry(...$context) {
		$context = self::BuildContext($context);
		$filePath = $this->_getFilePath($context);
		return \file_exists($filePath);
	}



	public function getEntry($source, ...$context) {
		return $this->_getEntry(
			$source,
			self::BuildContext($context)
		);
	}
	public function setEntry($value, ...$context) {
		return $this->_setEntry(
			$value,
			self::BuildContext($context)
		);
	}



	protected function _getEntry($source, $context) {
		// cache disabled
		if (\debug())
			return $source();
		// existing entry
		$filePath = $this->_getFilePath($context);
		if (empty($filePath))
			return null;
		if (\file_exists($filePath)) {
			// read cache file
			$data = \file_get_contents($filePath);
			$result = $this->ValidateData($data, $context);
			if ($result !== null) {
				// not expired
				if ($result['expired'] == false
				&& isset($result['value'])
				&& !empty($result['value']) )
					return $result['value'];
			}
		}
		// set new entry
		$value = $source();
		$this->_setEntry($value, $context);
		return $value;
	}
	protected function _setEntry($value, $context) {
		$timestamp = GeneralUtils::Timestamp(0);
		$filePath = $this->_getFilePath($context);
		if (empty($filePath))
			return false;
		// write cache file
		$result = \file_put_contents(
			$filePath,
			"Timestamp: {$timestamp}\n\n{$value}",
			\LOCK_EX
		);
		if ($result === false)
			return false;
		return true;
	}



	protected function ValidateData($data, $context) {
		$pos = \mb_strpos($data, "\n\n");
		if ($pos === false)
			return null;
		list($meta, $data) = \explode("\n\n", $data, 2);
		$timestamp = null;
		foreach(\explode("\n", $meta) as $line) {
			$line = \trim($line);
			if (empty($line)) continue;
			list($key, $val) = \explode(':', $line, 2);
			$key = \mb_strtolower(\trim($key));
			switch ($key) {
			case 'timestamp':
				$timestamp = (int) $val;
				continue;
			// unknown key
			default:
				fail("Unknown tag in cache file: $context - $line",
					Defines::EXIT_CODE_UNAVAILABLE);
				break;
			}
		}
		// timestamp not set or invalid
		if ($timestamp == null || $timestamp <= 0)
			return null;
		// timestamp expired
		$timeNow = GeneralUtils::Timestamp(0);
		$timeSince = $timeNow - $timestamp;
		if ($timeSince > $this->expireSeconds)
			return [ 'expired' => true ];
		// cache value good
		return [
			'expired' => false,
			'value'   => $data
		];
	}



	protected function _getFilePath($context) {
		if (empty($this->cachePath))
			return null;
		return Strings::BuildPath(
			$this->cachePath,
			".cache__{$context}"
		);
	}
	protected static function BuildContext(...$context) {
		if (empty($context))
			return null;
		return Arrays::TrimFlatMerge(
			'__',
			$context
		);
	}



}
*/
