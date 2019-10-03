<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class Debug {
	private function __construct() {}

	private static $inited = FALSE;

	protected static $debug = NULL;
	protected static $desc  = NULL;



	/**
	 * @codeCoverageIgnore
	 */
	public static function init() {
		if (self::$inited) return;
		self::$inited = TRUE;
		// by define
		if (\defined('DEBUG')) {
			self::setDebug(\DEBUG, 'by define');
		}
		if (\defined('pxn\\phpUtils\\DEBUG')) {
			self::setDebug(\pxn\phpUtils\DEBUG, 'by namespace define');
		}
		// by file
		{
			$searchPaths = [
				Paths::entry(),
//TODO
			];
			$debugFiles = [
				'.debug',
				'debug',
				'DEBUG',
			];
//$common = Strings::CommonPath(
//	$entry
//	$base
//);
//$paths = [
//	$entry,
//	$base,
//	$common,
//	$common."/.."
//];
			foreach ($searchPaths as $path) {
				foreach ($debugFiles as $file) {
					if (\file_exists("$path/$file")) {
						self::setDebug(TRUE, 'by file');
						break 2;
					}
				}
			}
		}
//TODO: disable in production
/*
		// by url
		{
			$val = General::getVar('debug', 'bool');
			if ($val !== NULL) {
				// set cookie
				\setcookie(
					Defines::DEBUG_COOKIE,
					($val ? '1', '0'),
					0
				);
				self::setDebug($val, 'by url');
			} else {
				$val = General::getVar(Defines::DEBUG_COOKIE, 'bool', 'cookie');
				if ($val === TRUE) {
					self::setDebug($val, 'by cookie');
				}
			}
			unset($val);
		}
*/
		// default off
		if (self::$debug === NULL) {
			self::setDebug(FALSE, 'default');
		}
	}



	public static function debug($debug=NULL, $desc=NULL) {
		if ($debug !== NULL) {
			self::setDebug($debug, $desc);
		}
		return self::isDebug();
	}
	public static function isDebug() {
		return (self::$debug === TRUE);
	}
	public static function setDebug($debug, $desc=NULL) {
		if (!self::$inited) self::init();
		if ($debug === NULL) return;
		$last = self::$debug;
		self::$debug = ($debug !== FALSE);
		self::$desc = $desc;
		// set change
		if (self::$debug != $last) {
			if (self::$debug) {
				self::EnableDebug();
			} else {
				self::DisableDebug();
			}
		}
	}



	public static function desc() {
		return self::$desc;
	}



	private static function EnableDebug() {
		// filp whoops
		if (\class_exists('Whoops\\Run')) {
			// @codeCoverageIgnoreStart
			$whoops = new \Whoops\Run();
			if (System::isShell()) {
				$whoops->prependHandler(new \Whoops\Handler\PlainTextHandler());
			} else {
				$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler());
			}
			$whoops->register();
			// @codeCoverageIgnoreEnd
		}
	}
	private static function DisableDebug() {
//TODO: clear whoops handlers or unregister
	}



/*
	// dump()
	function dump($var, $msg=NULL) {
		if (System::isShell()) {
			if (empty($msg)) {
				echo "--DUMP--\n";
			} else {
				$msg = \str_replace(' ', '-', $msg);
				echo "--DUMP-{$msg}--\n";
			}
			\var_dump($var);
			echo "--------\n";
		} else {
			$CRLF = "\n";
			echo '<pre style="color: black; background-color: #dfc0c0; padding: 10px;">';
			\var_dump($var);
			echo '</pre>'.$CRLF;
		}
		@\ob_flush();
	}
	// d()
	function d($var, $msg=NULL) {
		dump($var, $msg);
	}
	// dd()
	function dd($var, $msg=NULL) {
		dump($var, $msg);
		ExitNow(1);
	}
*/



/*
	function backtrace($e=NULL) {
		$isShell = System::isShell();
		$CRLF = "\n";
		$TAB  = "\t";
		if ($e == NULL) {
			$trace = \debug_backtrace();
		} else {
			$trace = $e->getTrace();
		}

		// ignored trace entries
		$ignore = [
			'init.php' => [
				'pxn\\phpUtils\\fail',
				'pxn\\phpUtils\\backtrace',
				'autoload',
				'__autoload',
			],
			'Globals.php' => [
				'pxn\\phpUtils\\fail',
				'pxn\\phpUtils\\backtrace',
			]
		];
		foreach ($trace as $index => $tr) {
			if (!isset($tr['file'])) {
				continue;
			}
			$file = $tr['file'];
			$func = $tr['function'];
			foreach ($ignore as $ignoreFile => $ignoreEntry) {
				if (Strings::EndsWith($file, $ignoreFile)) {
					if (\in_array($func, $ignoreEntry)) {
						unset ($trace[$index]);
						break;
					}
				}
			}
		}
		$trace = \array_values($trace);
		$trace = \array_reverse($trace, TRUE);
		if (!$isShell) {
			echo '<table style="background-color: #ffeedd; padding: 10px; '.
				'border-width: 1px; border-style: solid; border-color: #aaaaaa;">'.$CRLF;
		}

		// display trace
		$first   = TRUE;
		$evenodd = FALSE;
		foreach ($trace as $num => $tr) {
			$index = ((int) $num) + 1;
			if (!$first) {
				if ($isShell) {
					echo ' ----- ----- ----- ----- '.$CRLF;
				} else {
					echo '<tr><td height="20">&nbsp;</td></tr>'.$CRLF;
				}
			}
			$first = FALSE;
			$trArgs = '';
			foreach ($tr['args'] as $arg) {
				if (!empty($trArgs)) {
					$trArgs .= ', ';
				}
				if (!\is_string($arg)) {
					$trArgs .= \print_r($arg, TRUE);
				} else
				if (!$isShell && \mb_strpos($arg, $CRLF)) {
					$trArgs .= "<pre>{$arg}</pre>";
				} else {
					$trArgs .= $arg;
				}
			}
			$trFile = (isset($tr['file'])     ? $tr['file']     : '');
			$trClas = (isset($tr['class'])    ? $tr['class']    : '');
			$trFunc = (isset($tr['function']) ? $tr['function'] : '');
			$trLine = (isset($tr['line'])     ? $tr['line']     : '');
			$trBase = \basename($trFile);
			$trContainer = (empty($trClas) ? $trBase : $trClas);
			if ($isShell) {
				if (!empty($trLine)) {
					$trLine = Strings::PadLeft($trLine, 2, ' ');
					$trLine = "Line: $trLine - ";
				}
				echo "#{$index} - {$trLine}{$trFile}\n";
				echo " CALL: $trContainer -> {$trFunc}()\n";
				if (!empty($trArgs)) {
					echo " ARGS: $trArgs\n";
				}
			} else {
				$evenodd = ! $evenodd;
				$bgcolor = ($evenodd ? '#ffe0d0' : '#fff8e8');
				echo '<tr style="background-color: '.$bgcolor.';">'.$CRLF;
				echo $TAB.'<td><font size="-2">#'.$index.')</font></td>'.$CRLF;
				echo "$TAB<td>$trFile</td>$CRLF";
				echo "</tr>$CRLF";
				echo '<tr style="background-color: '.$bgcolor.';">'.$CRLF;
				echo "$TAB<td></td>$CRLF";
				echo "$TAB<td>".
					"<i>{$trContainer}</i> ".
					'<font size="-1">-&gt;</font> '.
					"<b>{$trFunc}()</b> ".
					" ( {$trArgs} ) ".
					(empty($trLine) ? '' : '<font size="-1">line: '.$trLine.'</font>' ).
					"</td>$CRLF";
				echo "</tr>$CRLF";
			}
		}
		if (!$isShell) {
			echo "</table>$CRLF";
		}
		echo $CRLF;
		@\ob_flush();
	}
*/



}
