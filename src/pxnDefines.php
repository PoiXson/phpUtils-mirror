<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class pxnDefines {
	/** @codeCoverageIgnore */
	private final function __construct() {}
	public static function init(): void {}


//TODO
//	const phpUtilsVersion = \pxn\phpUtils\Version::phpUtilsVersion;


	const PHP_MIN_VERSION     = 80000;
	const PHP_MIN_VERSION_STR = '8.0';


	const DEBUG_COOKIE = 'pxn_debug';


	const INT_MAX      = \PHP_INT_MAX;
	const INT_MIN      = \PHP_INT_MIN;
	const NET_PORT_MAX = 65535;


	const DIR_SEP      = \DIRECTORY_SEPARATOR;
	const NEWLINE      = "\n";
	const CRLF         = self::NEWLINE;
	const EOL          = self::NEWLINE;
	const TAB          = "\t";
	const QUOTE_S      = '\'';
	const QUOTE_D      = "\"";
	const S_QUOTE      = self::QUOTE_S;
	const D_QUOTE      = self::QUOTE_D;
	const ACCENT       = '`';


	// number of seconds
	const S_MS      =    0.001;
	const S_SECOND  =        1;
	const S_MINUTE  =       60;
	const S_HOUR    =     3600;
	const S_DAY     =    86400;
	const S_WEEK    =   604800;
	const S_MONTH   =  2592000;
	const S_YEAR    = 31536000;


	// number of bytes
	const KB =          1024;
	const MB =       1048576;
	const GB =    1073741824;
	const TB = 1099511627776;


	const EXIT_CODE_OK               = 0x0;
	const EXIT_CODE_GENERAL          = 0x1;
	const EXIT_CODE_HELP             = 0x2;
	const EXIT_CODE_INTERNAL_ERROR   = 0x4;
	const EXIT_CODE_NOPERM           = 0x8;


}
