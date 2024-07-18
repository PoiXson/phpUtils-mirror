<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


class GitTools {

	private static $instances = [];

	protected $path = null;

	protected $git_tag_info = null;



	public static function get($path=null) {
		if (empty($path)) {
			$path = Paths::pwd();
		}
		$p = \realpath($path);
		if (empty($p))
			throw new \Exception('Invalid path: '.$path);
		// existing instance
		if (isset(self::$instances[$path]) && self::$instances[$path] != null)
			return self::$instances[$path];
		// new instance
		$instance = new static($p);
		self::$instances[$path] = $instance;
		return $instance;
	}
	protected function __construct($path) {
		$this->path = $path;
	}



	public function getTagInfo() {
		if ($this->git_tag_info != null
		&& \is_array($this->git_tag_info)
		&& \count($this->git_tag_info) > 0)
			return $this->tag_info;
		// get tag info from git
		$cmd = "/usr/bin/git describe --tags";
		$result = \shell_exec($cmd);
		// parse result
		$tag          = Strings::grabPart($result, '-');
		$commit_count = Strings::grabPart($result, '-g');
		$commit       = $result;
		$this->tag_info = [
			'tag'     => $tag,
			'current' => empty($commit),
			'count'   => (empty($commit_count) ? null : $commit_count ),
			'commit'  => (empty($commit)       ? null : $commit       ),
		];
		return $this->tag_info;
	}



}
