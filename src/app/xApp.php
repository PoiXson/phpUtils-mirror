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
use pxn\phpUtils\Paths;


abstract class xApp {

	protected string $appName;
	protected string $appNS;
	protected string $version;



	public function __construct() {
		$this->check_run_mode();
		// find class name and namespace
		{
			$clss = StringUtils::Trim(\get_called_class(), '\\');
			$pos = \mb_strrpos($clss, '\\');
			if ($pos === false || $pos <= 3) {
				$this->appName = $clss;
				$this->appNS   = '';
			} else {
				$this->appName = StringUtils::Trim(\mb_substr($clss, $pos),    '\\');
				$this->appNS   = StringUtils::Trim(\mb_substr($clss, 0, $pos), '\\');
			}
			if (empty($this->appName))
				throw new \RuntimeException('Failed to find app name');
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
		// paths
		$this->load_paths();
		// init app
		$this->init();
	}



	protected function init(): void {
	}
	protected function load_paths(): void {
		Paths::init();
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
