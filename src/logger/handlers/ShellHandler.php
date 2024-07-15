<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\logger\handlers;


class ShellHandler implements \pxn\phpUtils\logger\handler\xLogHandler {

	protected $streamOut;
	protected $streamErr;



	public function __construct() {
		$this->streamOut = \fopen('php://stdout', 'w');
		$this->streamErr = \fopen('php://stderr', 'w');
	}



	public function write($msg) {


//TODO:
$handle = $this->streamOut;


		if (\is_array($msg)) {
			foreach ($msg as $m)
				$this->write($m);
			return;
		}
		\fwrite($handle, $msg."\n");
	}



}
