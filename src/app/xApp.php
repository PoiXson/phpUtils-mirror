<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\app;


abstract class xApp {

	public array $paths = [];



	public function __construct() {
		$this->load_paths();
	}



	public function load_paths(): void {
		$entry = $_SERVER['DOCUMENT_ROOT'];
		$root = (
			\str_ends_with($entry, '/public')
			? \mb_substr($entry, 0, -7)
			: $entry
		);
		$this->paths['root']  = $root;
		$this->paths['entry'] = $entry;
		$this->paths['html']   = "$root/html";
		$this->paths['static'] = "$entry/static";
		$this->paths['data']   = "$root/data";
	}



	public function run(): void {
	}



}
