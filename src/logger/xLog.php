<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\logger;

use \pxn\phpUtils\xLogger\formatters\BasicFormat;


class xLog extends xLogPrinting {

	const DEFAULT_LOGGER = '';

	private static $root = null;
	private static $loggers = [];
	private $DefaultFormatter = null;

	protected $name;
	protected $level;
	protected $parent;
	protected $formatter = null;
	protected $handlers = [];



	public static function Init(): void {
		if (self::$root == null) {
			self::$root = new self(null, null);
		}
	}



	public static function GetRoot(?string $name=null): xLog {
		if (self::$root == null)
			self::init();
		if (!empty($name))
			return self::$root->get($name);
		return self::$root;
	}
	public function get(?string $name=''): xLog {
		if (self::$root == null)
			self::init();
		if (empty($name))
			return $this;
		$name = self::ValidateName($name);
		if (isset(self::$loggers[$name]))
			return self::$loggers[$name];
		// new logger
//			$handler = new StreamHandler('php://stderr', Logger::DEBUG);
//			$formatter = new LineFormatter(
//					'[%datetime%] [%level_name%] [%channel%]  %message%  %context% %extra%'."\n",
//					'Y-m-d H:i:s',
//					false,
//					true
//			);
//			$handler->setFormatter($formatter);
//			$log->pushHandler($handler);
		$log = new self($name, $this);
		self::$loggers[$name] = $log;
		return $log;
	}
	public static function Set(string $name, xLog $log): bool {
		$name = self::ValidateName($name);
		$existed = isset(self::$loggers[$name]) && self::$loggers[$name] != null;
		self::$loggers[$name] = $log;
		return $existed;
	}
	public function getWeak(?string $name=''): xLog {
		$name = self::ValidateName($name);
		if (isset(self::$loggers[$name]))
			return self::$loggers[$name];
		return new self($name);
	}



	public static function ValidateName(?string $name): string {
		// default to class name
		if (empty($name)) {
			$trace = \debug_backtrace(limit: 3);
			$str = $trace[2]['class'];
			if ($str == 'ReflectionMethod')
				$str = $trace[1]['class'];
			$pos = \mb_strrpos($str, '\\');
			$name = (
				$pos===false
				? $str
				: \mb_substr($str, $pos+1)
			);
		}
		if (empty($name))
			return self::DEFAULT_LOGGER;
		return \trim($name);
	}



	public static function CaptureBuffer(): void {
		\ob_start([
			self::GetRoot(),
			'ProcessOB'
		]);
//		$func = function($buffer) {
//			$this->publish($buffer);
//if (empty($buffer))
//	return;
//$h = \fopen(__DIR__.'/test.222', 'a');
//\fwrite($h, $buffer."\n");
//\fclose($h);
//		};
//		\ob_start($func);
	}
	public function ProcessOB(?string $buffer): void {
		if (empty($buffer))
			return;
		$this->out($buffer);
	}



	public function __construct(?string $name, ?xLog $parent=null) {
		$this->name = self::ValidateName($name);
		$this->parent = $parent;
	}



	public function isRoot(): bool {
		return (empty($this->name) && $this->parent == null);
	}



	public function setLevel(xLevel $level): void {
		$this->level = xLevel::FindLevel($level);
	}
	public function getLevel(): xLevel {
		return $this->level;
	}
	public function isLoggable(xLevel $level): bool {
		if ($level == null || $this->level == null)
			return true;
		// force debug mode
//TODO:
//		if (xVars::debug())
//			return true;
		if (xLevel::isLoggable($this->level, $level))
			return true;
		return false;
	}



	protected function buildNameTree(array &$list): void {
		if ($this->parent != null) {
			$this->parent->buildNameTree($list);
			if (!empty($this->name))
				$list[] = $this->name;
		}
	}
	public function getNameTree(): array {
		return $this->buildNameTree($this);
	}



	public function addHandler(xLogHandler $handler): void {
		$this->handlers[] = $handler;
	}
	public function setHandler(xLogHandler $handler): void {
		$this->handlers = [ $handler ];
	}



	public function setFormatter(xLogFormatter $formatter): void {
		$this->formatter = $formatter;
	}



	public function publish(?string $msg=''): void {
		if ($this->parent != null) {
			$this->parent->publish($msg);
			//TODO: maybe not return here
			return;
		}
		if ($msg instanceof xLogRecord) {
			// not loggable
//TODO:
//			if (!$msg->isLoggable($this->level))
//				return;
			$msg = $this->getFormatter()
					->getFormatted($msg);
		}
		$this->writeToHandlers($msg);
	}
	protected function writeToHandlers(string $msg): void {
		foreach ($this->handlers as $handler)
			$handler->write($msg);
	}



	public function getFormatter(): xLogFormatter {
		// specific formatter
		if($this->formatter != null)
			return $this->formatter;
		// get from parent
		if ($this->parent != null) {
			$parentFormatter = $this->parent->getFormatter();
			if ($parentFormatter != null)
				return $parentFormatter;
		}
		// default formatter
		if ($this->DefaultFormatter == null)
			$this->DefaultFormatter = new BasicFormat();
		return $this->DefaultFormatter;
	}



}
