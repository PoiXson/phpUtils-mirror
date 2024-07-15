<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;

use \Composer\Autoload\ClassLoader;


abstract class xApp {

	protected ?ClassLoader $loader = null;

	public array $config = [];



	public function __construct(?ClassLoader $loader=null) {
		$this->loader = $loader;
		if (\method_exists($this, 'init_addvendor')) {
			if ($loader === null)
				throw new \RuntimeException('Class loader not provided to construct');
			$this->init_addvendor($loader);
		}
	}



	public abstract function run(): void;



	protected function loadConfig(String $file): void {
		$cfg = require($file);
		if (\is_array($cfg)) {
			$this->config = \array_merge($this->config, $cfg);
		}
	}



}
