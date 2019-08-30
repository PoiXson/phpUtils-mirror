<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */

# init 2 - functions
namespace pxn\phpUtils;



// atomic state
if (\defined('pxn\\phpUtils\\inited_2')) {
	throw new \RuntimeException();
}
define('pxn\\phpUtils\\inited_2', TRUE);



//$phpUtils_logger = NULL;
//if (!\function_exists('log')) {
//	function log() {
//		global $phpUtils_logger;
//		if ($phpUtils_logger == NULL)
//TODO:
//			$phpUtils_logger = new logger();
//		return $phpUtils_logger;
//	}
//}
//class logger {
//TODO:
//}



//// php session
//if (function_exists('session_status'))
//	if (session_status() == PHP_SESSION_DISABLED){
//	echo '<p>PHP Sessions are disabled. This is a requirement, please enable this.</p>';
//	exit;
//}
//session_init();



//// init php sessions
//private static $session_init_had_run = FALSE;
//public static function session_init() {
//	if (self::$session_init_had_run) return;
//	\session_start();
//	self::$session_init_had_run = TRUE;
//}



//function addlog($text){global $config,$pathroot;
//if (mb_substr($config['log file'],-4)!='.txt'){die('error in log file var');}
//$fp=@fopen($pathroot.$config['log file'],'a') or die('failed to write log');
//fwrite($fp,date('Y-m-d H:i:s').' - '.trim($text)."\r\n");
//fclose($fp);
//}



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



// exit functions
function ExitNow($code=1) {
	// be sure captured buffer is dumped when in web mode
	{
		$app = \pxn\phpUtils\app\App::peak();
		if ($app != NULL) {
			$app->terminating();
		}
		unset($app);
	}
	// set rendered
	{
		$clss = '\\pxn\\phpPortal\\WebApp';
		if (\class_exists($clss)) {
			$app = $clss::peak();
			if ($app != NULL) {
				$app->setRendered();
			}
		}
		unset($clss);
	}
	// exit code
	if ($code !== NULL) {
		exit( ((int) $code) );
	}
	exit(0);
}
function fail($msg=NULL, $code=1, \Exception $e=NULL) {
	$CRLF = "\n";
	if (empty($msg)) {
		$msg = System::isShell()
			? '<NULL>'
			: '&lt;NULL&gt;';
	} else
	if (!\is_string($msg)) {
		$msg = \print_r($msg, TRUE);
	}
	if (System::isShell()) {
		echo "\n *** FATAL: $msg *** \n\n";
	} else {
		echo '<pre style="color: black; background-color: #ffaaaa; '.
				'padding: 10px;"><font size="+2">FATAL: '.$msg.'</font></pre>'.$CRLF;
	}
	if ($code instanceof \Exception) {
		$e = $code;
		$code = 1;
	}
	if (debug()) {
		backtrace($e);
	}
	@\ob_flush();
	if ($code !== NULL) {
		ExitNow($code);
	}
}
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
