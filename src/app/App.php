<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;

use pxn\phpUtils\Strings;


abstract class App {

	protected $appName;
	protected $namespacePath;



	public function __construct() {
		// find app name and namespace path
		{
			$tmp = \get_called_class();
			$tmp = Strings::Trim($tmp, '\\');
			$pos = \mb_strrpos($tmp, '\\');
			if ($pos === FALSE || $pos <= 3) {
				$this->appName = $tmp;
				$this->namespacePath = '';
			} else {
				$this->appName =
					Strings::Trim(
						\mb_substr($tmp, $pos),
						'\\'
					);
				$this->namespacePath =
					Strings::Trim(
						\mb_substr($tmp, 0, $pos),
						'\\'
					);
			}
			unset($tmp, $pos);
		}
	}



	public abstract function run(): void;



	public function getName(): string {
		return $this->appName;
	}
	public function getNamespace(): string {
		return $this->namespacePath;
	}
	public function getVersion(): string {
//TODO
		return '1.x.x';
	}



}
