<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\xLogger;

use \pxn\phpUtils\xLogger\formatters\BasicFormat;


class xLog extends xLogPrinting {

	const DEFAULT_LOGGER = '';

	private static $root = NULL;
	private static $loggers = [];
	private $DefaultFormatter = NULL;

	protected $name;
	protected $level;
	protected $parent;
	protected $formatter = NULL;
	protected $handlers = [];



	public static function init() {
		if (self::$root == NULL) {
			self::$root = new self(
					NULL,
					NULL
			);
		}
	}



	public static function getRoot($name=NULL) {
		if (self::$root == NULL) {
			self::init();
		}
		if (!empty($name)) {
			return self::$root
					->get($name);
		}
		return self::$root;
	}
	public function get($name='') {
		if (self::$root == NULL) {
			self::init();
		}
		if (empty($name)) {
			return $this;
		}
		$name = self::ValidateName($name);
		if (isset(self::$loggers[$name])) {
			return self::$loggers[$name];
		}
		// new logger
//			$handler = new StreamHandler('php://stderr', Logger::DEBUG);
//			$formatter = new LineFormatter(
//					'[%datetime%] [%level_name%] [%channel%]  %message%  %context% %extra%'."\n",
//					'Y-m-d H:i:s',
//					FALSE,
//					TRUE
//			);
//			$handler->setFormatter($formatter);
//			$log->pushHandler($handler);
		$log = new self(
				$name,
				$this
		);
		self::$loggers[$name] = $log;
		return $log;
	}
	public static function set($name, $log) {
		$name = self::ValidateName($name);
		$existed = isset(self::$loggers[$name]) && self::$loggers[$name] != NULL;
		self::$loggers[$name] = $log;
		return $existed;
	}
	public function getWeak($name='') {
		$name = self::ValidateName($name);
		if (isset(self::$loggers[$name]))
			return self::$loggers[$name];
		return new self($name);
	}



	public static function ValidateName($name) {
		$name = \trim($name);

//TODO:

		if (empty($name))
			return self::DEFAULT_LOGGER;
		return $name;
	}



	public static function CaptureBuffer() {
		\ob_start([
			self::getRoot(),
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
	public function ProcessOB($buffer) {
		if (empty($buffer))
			return;
		$this->out($buffer);
	}



	public function __construct($name, $parent=NULL) {
		$this->name = self::ValidateName($name);
		$this->parent = $parent;
	}



	public function isRoot() {
		return (empty($this->name) && $this->parent == NULL);
	}



	public function setLevel($level) {
		$this->level = xLevel::FindLevel($level);
	}
	public function getLevel() {
		return $this->level;
	}
	public function isLoggable($level) {
		if ($level == NULL || $this->level == NULL)
			return TRUE;
		// force debug mode
//TODO:
//		if (xVars::debug())
//			return TRUE;
		if (xLevel::isLoggable($this->level, $level))
			return TRUE;
		return FALSE;
	}



	protected function buildNameTree(&$list) {
		if ($this->parent != NULL) {
			$this->parent->buildNameTree($list);
			if (!empty($this->name))
				$list[] = $this->name;
		}
	}
	public function getNameTree() {
		return $this->buildNameTree($this);
	}



	public function addHandler($handler) {
		$this->handlers[] = $handler;
	}
	public function setHandler($handler) {
		$this->handlers = [ $handler ];
	}



	public function setFormatter(xLogFormatter $formatter) {
		$this->formatter = $formatter;
	}



	public function publish($msg='') {
		if ($this->parent != NULL) {
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
	protected function writeToHandlers($msg) {
		foreach ($this->handlers as $handler) {
			$handler->write($msg);
		}
	}



	public function getFormatter() {
		// specific formatter
		if($this->formatter != NULL) {
			return $this->formatter;
		}
		// get from parent
		if ($this->parent != NULL) {
			$parentFormatter = $this->parent->getFormatter();
			if ($parentFormatter != NULL)
				return $parentFormatter;
		}
		// default formatter
		if ($this->DefaultFormatter == NULL)
			$this->DefaultFormatter = new BasicFormat();
			return $this->DefaultFormatter;
	}



//	public static function ValidateName($name) {
//		// default to class name
//		if (empty($name)) {
//			$trace = \debug_backtrace(FALSE, 3);
//			$str = $trace[2]['class'];
//			if ($str == 'ReflectionMethod') {
//				$str = $trace[1]['class'];
//			}
//			$pos = \mb_strrpos($str, '\\');
//			$name = (
//				$pos === FALSE
//				? $str
//				: \mb_substr($str, $pos+1)
//			);
//		}
//		if (empty($name)) $name = '';
//		return $name;
//	}



}
*/
