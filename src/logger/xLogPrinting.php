<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\logger;

use \pxn\phpUtils\Strings;


abstract class xLogPrinting {

	protected $formatter;



	public function __construct(xLogFormatter $formatter) {
		$this->formatter = $formatter;
	}



	public abstract function publish(?string $msg=''): void;



	public function title(string $msg): void {
		if (!\is_array($msg)) {
			$this->title( [ $title ] );
			return;
		}
		$len = 0;
		foreach ($msg as $m) {
			$size = \mb_strlen($m);
			if ($size > $len)
				$len = $size;
		}
		$topbottom = \str_repeat('*', $len);
		$this->publish();
		$this->publish(" ***{$topbottom}*** ");
		foreach ($msg as $m) {
			$line = Strings::PadCenter($m, $len, ' ');
			$this->publish(" ** $line ** ");
		}
		$this->publish(" ***{$topbottom}*** ");
		$this->publish();
	}



	public function trace(\Exception $e): void {
//TODO:
fail ('trace() function not finished!!! '.__LINE__.' '.__FILE__);
	}



	public function out(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::STDOUT, $msg));
	}
	public function err(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::STDERR, $msg));
	}



	public function finest(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::FINEST, $msg));
	}
	public function finer(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::FINER, $msg));
	}
	public function fine(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::FINE, $msg));
	}
	public function stats(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::STATS, $msg));
	}
	public function info(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::INFO, $msg));
	}
	public function warning(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::WARNING, $msg));
	}
	public function notice(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::NOTICE, $msg));
	}
	public function severe(?string $msg=''): void {
		$this->publish(new xLogRecord(xLevel::SEVERE, $msg));
	}
	public function fatal(?string $msg=''): void {
		$this->publish (new xLogRecord(xLevel::FATAL, $msg));
	}



}
