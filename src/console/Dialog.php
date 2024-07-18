<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\console;


abstract class Dialog {

	protected $cmd    = null;
	protected $result = null;



	public abstract function getCommand();



	public function run() {
		$cmd = (string) $this->cmd;
		if (empty($cmd))
			throw new \Exception('cmd argument is required!');
		$pipes = [null, null, null];
		$in  = fopen ('php://stdin',  'r');
		$out = fopen ('php://stdout', 'w');
		$streams = [
			0 => $in,
			1 => $out,
			2 => ['pipe', 'w']
		];
		$p = \proc_open($cmd, $streams, $pipes);
		$this->result = \stream_get_contents($pipes[2]);
		\fclose($pipes[2]);
		\fclose($out);
		\fclose($in);
		\proc_close($p);
		return $this->result;
	}



	public function setCmd($cmd) {
		$this->cmd = $cmd;
	}
	public function getResult() {
		return $this->result;
	}



}
*/
