<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class ShellUtils {
	private final function __construct() {}

	private static bool $inited = false;

	private static ?array $flags = null;
	private static ?array $args  = null;
	private static ?array $stat  = null;



	public static function init(): void {
		if (self::$inited)
			return;
		self::InitConsoleVars();
		self::$inited = true;
		// ansi color enabled
		if (self::HasFlag('--ansi'))
			ConfigGeneral::setAnsiColorEnabled(true);
		// ansi color disabled
		if (self::HasFlag('--no-ansi'))
			ConfigGeneral::setAnsiColorEnabled(false);
		// detect color support
		ConfigGeneral::defaultAnsiColorEnabled(
			(self::$stat['stdout'] == 'chr')
		);
		// clear screen
		if (ConfigGeneral::isAnsiColorEnabled())
			echo self::FormatString('{clear}');
	}
	private static function InitConsoleVars(): bool {
		if (self::$inited)      return false;
		if (!SystemUtils::IsShell()) return false;
		if (self::$flags !== null || self::$args !== null)
			return false;
		// detect shell state
		self::$stat = [
			'stdin'  => self::GetStat(\STDIN),
			'stdout' => self::GetStat(\STDOUT),
			'stderr' => self::GetStat(\STDERR)
		];
		// parse shell arguments
		$AllowShortFlagValues = ConfigGeneral::getAllowShortFlagValues();
		global $argv;
		self::$flags = [];
		self::$args  = [];
		$allArgs = false;
		for ($index=1; $index<count($argv); $index++) {
			$arg = $argv[$index];
			if (empty($arg)) continue;
			// --
			if ($allArgs) {
				self::$args[] = $arg;
				continue;
			}
			if ($arg == '--') {
				$allArgs = true;
				continue;
			}
			// --flag
			if (\str_starts_with(haystack: $arg, needle: '--')) {
				// --flag=value
				$pos = \mb_strpos($arg, '=');
				if ($pos !== false) {
					$val = \mb_substr($arg, $pos);
					$arg = \mb_substr($arg, 0, $pos);
					self::$flags[$arg] = $val;
					continue;
				}
				// --flag value
				if (isset($argv[$index+1])) {
					$val = $argv[$index+1];
					if (!\str_starts_with(haystack: $val, needle: '-')) {
						$index++;
						self::$flags[$arg] = $val;
						continue;
					}
				}
				// --flag
				if (!isset(self::$flags[$arg]))
					self::$flags[$arg] = true;
				continue;
			}
			// -flag
			if (\str_starts_with(haystack: $arg, needle: '-')) {
				// attached value
				$len = \mb_strlen($arg);
				if ($len > 2) {
					$val = \mb_substr($arg, 2);
					$arg = \mb_substr($arg, 0, 2);
					if (\mb_substr($val, 0, 1) == '=')
						$val = \mb_substr($val, 1);
					self::$flags[$arg] = $val;
					continue;
				}
				// -f value
				if ($AllowShortFlagValues) {
					if (isset($argv[$index+1])) {
						$val = $argv[$index+1];
						if (!\str_starts_with(haystack: $val, needle: '-')) {
							$index++;
							self::$flags[$arg] = $val;
							continue;
						}
					}
				}
				// -f
				if (!isset(self::$flags[$arg]))
					self::$flags[$arg] = true;
				continue;
			}
			// not flag, must be argument
			self::$args[] = $arg;
		}
		return true;
	}



	public static function GetStat(\resource $handle): ?string {
		$stat = \fstat($handle);
		$mode = $stat['mode'] & 0170000;
		switch ($mode) {
		case 0010000: return 'fifo';
		case 0020000: return 'chr';
		case 0040000: return 'dir';
		case 0060000: return 'blk';
		case 0100000: return 'reg';
		case 0120000: return 'lnk';
		case 0140000: return 'sock';
		}
		return null;
	}


	// get all as array
	public static function GetArgs(): array {
		return self::$args;
	}
	public static function GetFlags(): array {
		return self::$flags;
	}



	// get argument (starts at 0)
	public static function GetArg(?int $index=null): ?string {
		if ($index === null)
			return self::GetArg(0);
		$index = (int) $index;
		if (!isset(self::$args[$index]))
			return null;
		return self::$args[$index];
	}
	public static function HasArg(?string $arg): ?bool {
		if (empty($arg))
			return null;
		// match case
		if (\in_array($arg, self::$args))
			return true;
		// case-insensitive
		if (\in_array( \strtolower($arg), \array_map('\\strtolower', self::$args) ))
			return true;
		return false;
	}



	// get one flag
	public static function GetFlag(string ... $keys): ?string {
		if (\count($keys) == 0)
			return null;
		foreach ($keys as $key) {
			$val = self::GetFlag_Single($key);
			if ($val !== null)
				return $val;
		}
		return null;
	}
	private static function GetFlag_Single(?string $key): ?string {
		if (empty($key))
			return null;
		if (isset(self::$flags[$key])) {
			// don't allow "-x value"
			$AllowShortFlagValues = ConfigGeneral::getAllowShortFlagValues();
			if (!$AllowShortFlagValues) {
				if (!\str_starts_with(haystack: $key, needle: '--'))
					return true;
			}
			return self::$flags[$key];
		}
		return null;
	}



	// get boolean flag
	public static function GetFlagBool(string ... $keys): ?bool {
		if (\count($keys) == 0)
			return null;
		foreach ($keys as $key) {
			$val = self::GetFlagBool_Single($key);
			if ($val !== null)
				return $val;
		}
		return null;
	}
	private static function GetFlagBool_Single(?string $key): ?bool {
		if (empty($key))
			return null;
		return GeneralUtils::CastBoolean(self::GetFlag($key));
	}



	// flag exists
	public static function HasFlag(string ... $keys): ?bool {
		if (\count($keys) == 0)
			return null;
		foreach ($keys as $key) {
			$result = self::HasFlag_Single($key);
			if ($result === true)
				return true;
		}
		return false;
	}
	private static function HasFlag_Single(?string $key): ?bool {
		if (empty($key))
			return null;
		return isset(self::$flags[$key]);
	}



	// has -h or --help flag
	public static function IsHelp(): bool {
		return self::HasFlag('-h') ||
			self::HasFlag('--help');
	}



	############
	## Format ##
	############



	public static function FormatString(string $str): string {
		return \preg_replace_callback(
			'#\{[a-z0-9,=]+\}#i',
			[ __CLASS__, 'FormatString_Callback' ],
			$str
		);
	}
	public static function FormatString_Callback(array $matches): string {
		$match = \reset($matches);
		if (!ConfigGeneral::isAnsiColorEnabled())
			return '';
		if (!\str_starts_with(haystack: $match, needle: '{')
		||  !\str_ends_with(  haystack: $match, needle: '}'))
			return $match;
		$match = \mb_substr($match, 1, -1);
		$codes = [];
		$bold = null;
		$parts = \explode(',', \mb_strtolower($match));
		foreach ($parts as $part) {
			if (empty($part)) continue;
			$pos = \mb_strpos($part, '=');
			// {tag}
			if ($pos === false) {
				switch ($part) {
				case 'bold':  $bold = true; break;
				case 'reset': return "\033[0m";
				case 'clear': return "\033[2J\033[1;1H";
				}
			// {tag=value}
			} else {
				$key = \mb_substr($part, 0, $pos);
				$val = \mb_substr($part, $pos+1);
				if ($key == 'color') {
					switch ($val) {
					// dark colors
					case 'black':     $codes[] = 30; $bold = ($bold === null ? false : $bold); break;
					case 'red':       $codes[] = 31; $bold = ($bold === null ? false : $bold); break;
					case 'green':     $codes[] = 32; $bold = ($bold === null ? false : $bold); break;
					case 'orange':    $codes[] = 33; $bold = ($bold === null ? false : $bold); break;
					case 'blue':      $codes[] = 34; $bold = ($bold === null ? false : $bold); break;
					case 'magenta':   $codes[] = 35; $bold = ($bold === null ? false : $bold); break;
					case 'cyan':      $codes[] = 36; $bold = ($bold === null ? false : $bold); break;
					case 'lightgray': $codes[] = 37; $bold = ($bold === null ? false : $bold); break;
					// light colors
					case 'gray':      $codes[] = 30; $bold = ($bold === null ? true : $bold); break;
					case 'lightred':  $codes[] = 31; $bold = ($bold === null ? true : $bold); break;
					case 'lime':      $codes[] = 32; $bold = ($bold === null ? true : $bold); break;
					case 'yellow':    $codes[] = 33; $bold = ($bold === null ? true : $bold); break;
					case 'lightblue': $codes[] = 34; $bold = ($bold === null ? true : $bold); break;
					case 'pink':      $codes[] = 35; $bold = ($bold === null ? true : $bold); break;
					case 'lightcyan': $codes[] = 36; $bold = ($bold === null ? true : $bold); break;
					case 'white':     $codes[] = 37; $bold = ($bold === null ? true : $bold); break;
					}
				} else // end color tag
				if ($key == 'bg' || $key == 'bgcolor' || $key == 'back') {
					switch ($val) {
					// dark colors
					case 'black':     $codes[] = 40; $bold = ($bold === null ? false : $bold); break;
					case 'red':       $codes[] = 41; $bold = ($bold === null ? false : $bold); break;
					case 'green':     $codes[] = 42; $bold = ($bold === null ? false : $bold); break;
					case 'orange':    $codes[] = 43; $bold = ($bold === null ? false : $bold); break;
					case 'blue':      $codes[] = 44; $bold = ($bold === null ? false : $bold); break;
					case 'magenta':   $codes[] = 45; $bold = ($bold === null ? false : $bold); break;
					case 'cyan':      $codes[] = 46; $bold = ($bold === null ? false : $bold); break;
					case 'lightgray': $codes[] = 47; $bold = ($bold === null ? false : $bold); break;
					// light colors
					case 'gray':      $codes[] = 40; $bold = ($bold === null ? true : $bold); break;
					case 'lightred':  $codes[] = 41; $bold = ($bold === null ? true : $bold); break;
					case 'lime':      $codes[] = 42; $bold = ($bold === null ? true : $bold); break;
					case 'yellow':    $codes[] = 43; $bold = ($bold === null ? true : $bold); break;
					case 'lightblue': $codes[] = 44; $bold = ($bold === null ? true : $bold); break;
					case 'pink':      $codes[] = 45; $bold = ($bold === null ? true : $bold); break;
					case 'lightcyan': $codes[] = 46; $bold = ($bold === null ? true : $bold); break;
					case 'white':     $codes[] = 47; $bold = ($bold === null ? true : $bold); break;
					}
				} // end bgcolor tag
			} // end {tag=value}
		} // end for
		if ($bold !== null)
			\array_unshift($codes, ($bold !== false ? 1 : 0));
		if (\count($codes) > 0) {
			$code = \implode(';', $codes);
			return "\033[{$code}m";
		}
		return '{'.$match.'}';
	}



}
