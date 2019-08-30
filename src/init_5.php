<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */

# init 5 - debug
namespace pxn\phpUtils;



// atomic state
if (\defined('pxn\\phpUtils\\inited_5')) {
	throw new \RuntimeException();
}
define('pxn\\phpUtils\\inited_5', TRUE);



// debug mode
global $pxnUtils_DEBUG;
$pxnUtils_DEBUG = NULL;
\pxn\phpUtils\ConfigGeneral::setDebugRef($pxnUtils_DEBUG);
$cfg = \pxn\phpUtils\Config::get(\pxn\phpUtils\Defines::KEY_CONFIG_GROUP_GENERAL);
$dao = $cfg->getDAO(\pxn\phpUtils\Defines::KEY_CFG_DEBUG);
$dao->setValidHandler('bool');

function debug($debug=NULL, $msg=NULL) {
	if ($debug !== NULL) {
		\pxn\phpUtils\ConfigGeneral::setDebug($debug, $msg);
	}
	return \pxn\phpUtils\ConfigGeneral::isDebug();
}

// by define
{
	if (\defined('\DEBUG')) {
		debug(\DEBUG, 'by define');
	}
	if (\defined('pxn\\phpUtils\\DEBUG')) {
		debug(\pxn\phpUtils\DEBUG, 'by namespaced define');
	}
}
// by file
{
	$entry  = Paths::entry();
	$base   = Paths::base();
	$common = Strings::CommonPath(
		$entry,
		$base
	);
	$paths = [
		$entry,
		$base,
		$common,
		$common."/.."
	];
	foreach ($paths as $path) {
		if (\file_exists($path."/debug")) {
			debug(TRUE, 'by file');
			break;
		}
		if (\file_exists($path."/DEBUG")) {
			debug(TRUE, 'by file');
			break;
		}
		if (\file_exists($path."/.debug")) {
			debug(TRUE, 'by file');
			break;
		}
	}
}
// by url
{
	$val = General::getVar('debug', 'bool');
	if ($val !== NULL) {
		// set cookie
		\setcookie(
			Defines::DEBUG_COOKIE,
			($val === TRUE ? '1' : '0'),
			0
		);
		debug($val, 'set cookie');
	} else {
		// from cookie
		$val = General::getVar(
			Defines::DEBUG_COOKIE,
			'bool',
			'cookie'
		);
		if ($val === TRUE) {
			debug($val, 'by cookie');
		}
	}
}
unset($val);
// ensure inited (default to false)
if ($pxnUtils_DEBUG === NULL) {
	debug(FALSE, 'default');
}



/*
// Kint backtracer
if (file_exists(paths::getLocal('portal').DIR_SEP.'kint.php')) {
	include(paths::getLocal('portal').DIR_SEP.'kint.php');
}
// php_error
if (file_exists(paths::getLocal('portal').DIR_SEP.'php_error.php')) {
	include(paths::getLocal('portal').DIR_SEP.'php_error.php');
}
// Kint backtracer
$kintPath = paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'kint'.DIR_SEP.'Kint.class.php';
if (file_exists($kintPath)) {
	//global $GLOBALS;
	//if (!@is_array(@$GLOBALS)) $GLOBALS = array();
	//$_kintSettings = &$GLOBALS['_kint_settings'];
	//$_kintSettings['traceCleanupCallback'] = function($traceStep) {
	//echo '<pre>';print_r($traceStep);exit();
	//	if (isset($traceStep['class']) && $traceStep['class'] === 'Kint')
	//		return NULL;
	//	if (isset($traceStep['function']) && \mb_strtolower($traceStep['function']) === '__tostring')
	//		$traceStep['function'] = '[object converted to string]';
	//	return $traceStep;
	//};
	//echo '<pre>';print_r($_kintSettings);exit();
	include($kintPath);
	}
	// php_error
	$phpErrorPath = paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'php_error.php';
	if (file_exists($phpErrorPath))
		include($phpErrorPath);
		if (function_exists('php_error\\reportErrors')) {
			$reportErrors = '\\php_error\\reportErrors';
			$reportErrors([
					'catch_ajax_errors'      => TRUE,
					'catch_supressed_errors' => FALSE,
					'catch_class_not_found'  => FALSE,
					'snippet_num_lines'      => 11,
					'application_root'       => __DIR__,
					'background_text'        => 'PSM',
			]);
		}
	}
}
// error page
public static function Error($msg) {
	echo '<div style="background-color: #ffbbbb;">'.CRLF;
	if (!empty($msg))
		echo '<h4>Error: '.$msg.'</h4>'.CRLF;
	echo '<h3>Backtrace:</h3>'.CRLF;
//	if (\method_exists('Kint', 'trace'))
//		\Kint::trace();
//	else
		echo '<pre>'.print_r(\debug_backtrace(), TRUE).'</pre>';
	echo '</div>'.CRLF;
//	\psm\Portal::Unload();
	exit(1);
}
*/

/*
\set_error_handler(
function ($severity, $msg, $filename, $line, array $err_context) {
	if (0 === error_reporting())
		return FALSE;
	switch($severity) {
	case E_ERROR:             throw new ErrorException            ($msg, 0, $severity, $filename, $line);
	case E_WARNING:           throw new WarningException          ($msg, 0, $severity, $filename, $line);
	case E_PARSE:             throw new ParseException            ($msg, 0, $severity, $filename, $line);
	case E_NOTICE:            throw new NoticeException           ($msg, 0, $severity, $filename, $line);
	case E_CORE_ERROR:        throw new CoreErrorException        ($msg, 0, $severity, $filename, $line);
	case E_CORE_WARNING:      throw new CoreWarningException      ($msg, 0, $severity, $filename, $line);
	case E_COMPILE_ERROR:     throw new CompileErrorException     ($msg, 0, $severity, $filename, $line);
	case E_COMPILE_WARNING:   throw new CoreWarningException      ($msg, 0, $severity, $filename, $line);
	case E_USER_ERROR:        throw new UserErrorException        ($msg, 0, $severity, $filename, $line);
	case E_USER_WARNING:      throw new UserWarningException      ($msg, 0, $severity, $filename, $line);
	case E_USER_NOTICE:       throw new UserNoticeException       ($msg, 0, $severity, $filename, $line);
	case E_STRICT:            throw new StrictException           ($msg, 0, $severity, $filename, $line);
	case E_RECOVERABLE_ERROR: throw new RecoverableErrorException ($msg, 0, $severity, $filename, $line);
	case E_DEPRECATED:        throw new DeprecatedException       ($msg, 0, $severity, $filename, $line);
	case E_USER_DEPRECATED:   throw new UserDeprecatedException   ($msg, 0, $severity, $filename, $line);
	}
});
class WarningException          extends \ErrorException {}
class ParseException            extends \ErrorException {}
class NoticeException           extends \ErrorException {}
class CoreErrorException        extends \ErrorException {}
class CoreWarningException      extends \ErrorException {}
class CompileErrorException     extends \ErrorException {}
class CompileWarningException   extends \ErrorException {}
class UserErrorException        extends \ErrorException {}
class UserWarningException      extends \ErrorException {}
class UserNoticeException       extends \ErrorException {}
class StrictException           extends \ErrorException {}
class RecoverableErrorException extends \ErrorException {}
class DeprecatedException       extends \ErrorException {}
class UserDeprecatedException   extends \ErrorException {}
*/

/*
\set_exception_handler(
function (\Exception $e) {
	echo '<h1>Uncaught Exception</h1>'.CRLF;
	echo '<h2>'.$e->getMessage().'</h2>'.CRLF;
	echo '<h3>Line '.$e->getLine().' of '.$e->getFile().'</h3>'.CRLF;
	foreach ($e->getTrace() as $t)
		\var_dump($t);
	exit(1);
});
*/
