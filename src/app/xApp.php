<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;

use pxn\phpUtils\utils\StringUtils;


abstract class xApp {

	protected string $appName;
	protected string $appNS;
	protected string $version;



	public function __construct() {
		$this->check_run_mode();
		// find app name and namespace
		{
			$tmp = \get_called_class();
			$tmp = StringUtils::Trim($tmp, '\\');
			$pos = \mb_strrpos($tmp, '\\');
			if ($pos === FALSE || $pos <= 3) {
				$this->appName = $tmp;
				$this->namespacePath = '';
			} else {
				$this->appName       = StringUtils::Trim(\mb_substr($tmp, $pos),    '\\');
				$this->namespacePath = StringUtils::Trim(\mb_substr($tmp, 0, $pos), '\\');
			}
			unset($tmp, $pos);
		}
		// app version
		{
			$clss = "\\{$this->appNS}\\Version";
			$cnst = "{$clss}::version";
			if (!\class_exists($clss) || !\defined($cnst))
				throw new \RuntimeException("Class not found: $clss");
			$this->version = \constant($cnst);
			if (empty($this->version))
				throw new \RuntimeException('App version not found');
		}
	}



	protected abstract function check_run_mode(): void;

	public abstract function run(): void;



	public function getName(): string {
		return $this->appName;
	}
	public function getNamespace(): string {
		return $this->appNS;
	}
	public function getVersion(): string {
		return $this->version;
	}



}
