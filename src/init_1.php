<?php
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */


# init 1 - startup
namespace pxn\phpUtils;



// atomic state
if (\defined('pxn\\phpUtils\\inited_1')) {
	throw new \RuntimeException();
}
define('pxn\\phpUtils\\inited_1', TRUE);



// default error reporting
{
	$isShell = System::isShell();
	\error_reporting(\E_ALL);
	\ini_set('display_errors', 'On');
	\ini_set('html_errors',    $isShell ? 'Off' : 'On');
	\ini_set('log_errors',     'On');
	\ini_set('error_log',      $isShell ? '/var/log/php_shell_error' : 'error_log');
}

// php version 5.6 required
if (\PHP_VERSION_ID < 50600) {
	echo '<p>PHP 5.6 or newer is required!</p>'; exit(1);
}

// atomic defines
if (\defined('pxn\\phpUtils\\INDEX_DEFINE')) {
	echo '<h2>Unknown state! init.php already loaded?</h2>';
	exit(1);
}
if (\defined('pxn\\phpUtils\\PORTAL_LOADED')) {
	echo '<h2>Unknown state! Portal already loaded?</h2>';
	exit(1);
}
if (!\function_exists('mb_substr')) {
	echo '<h2>mbstring library not installed?</h2>';
	exit(1);
}
\define('pxn\\phpUtils\\INDEX_DEFINE', TRUE);

// timezone
//TODO: will make a config entry for this
try {
	$zone = @date_default_timezone_get();
	if ($zone == 'UTC') {
		@date_default_timezone_set(
			'America/New_York'
		);
	} else {
		@date_default_timezone_set(
			@date_default_timezone_get()
		);
	}
	unset($zone);
} catch (\Exception $ignore) {}
