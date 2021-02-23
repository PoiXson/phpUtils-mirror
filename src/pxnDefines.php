<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils;


final class pxnDefines {
	private final function __construct() {}
	public static function init(): void {}


	const PHP_MIN_VERSION     = 70300;
	const PHP_MIN_VERSION_STR = '7.3';

	const DEBUG_COOKIE = 'pxn_debug';


//	const KEY_CFG_ANSI_COLOR_ENABLED      = 'ansi color enabled';
//	const KEY_CFG_ALLOW_SHORT_FLAG_VALUES = 'allow short flag values';
//	const KEY_CFG_DISPLAY_MODE            = 'display mode';


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

	const INT_MAX      = 2147483647;
	const INT_MIN      =-2147483648;
	const NET_PORT_MAX =      65535;

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

	const EXIT_CODE_OK               =   0;
	const EXIT_CODE_GENERAL          =   1;
	const EXIT_CODE_HELP             =   2;
	const EXIT_CODE_INTERNAL_ERROR   =   4;
	const EXIT_CODE_NOPERM           =   8;
//	const EXIT_CODE_USAGE_ERROR      =  64;
//	const EXIT_CODE_INVALID_FORMAT   =  65;
//	const EXIT_CODE_UNAVAILABLE      =  69;
//	const EXIT_CODE_IO_ERROR         =  74;
//	const EXIT_CODE_CONFIG_ERROR     =  78;
//	const EXIT_CODE_INVALID_COMMAND  = 127;
//	const EXIT_CODE_INVALID_ARGUMENT = 128;


}
