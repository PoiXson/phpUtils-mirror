<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class GeneralUtils {
	private final function __construct() {}



	###############
	## Cast Type ##
	###############



	// cast variable type
	public static function CastType($data, string $type) {
		if (empty($type)) return $data;
		switch (\mb_strtolower(\mb_substr((string)$type, 0, 1))) {
			case 's': return (string) $data;  // string
			case 'i':
			case 'l': return (integer) $data; // integer/long
			case 'f': return (float) $data;   // float
			case 'd': return (float) $data;   // double
			case 'b': return self::CastBoolean($data); // boolean
			default: break;
		}
		return $data;
	}



	// convert to boolean
	public static function CastBoolean($value): ?bool {
		if ($value === null) return null;
		if (\gettype($value) === 'boolean')
			return $value;
		$val = \mb_strtolower(trim( (string) $value ));
		if ($val == 'on')  return true;
		if ($val == 'off') return false;
		switch (mb_substr($val, 0, 1)) {
			case 't': // true
			case 'y': // yes
			case 'a': // allow
			case 'e': // enable
				return true;
			case 'f': // false
			case 'n': // no
			case 'd': // deny/disable
				return false;
		}
		return ((boolean) $value);
	}



	##############
	## URL Vars ##
	##############



	// get,post,cookie (highest priority last)
	/**
	 * Gets a value from a specific list of sources.
	 * @param string $name - Name or key requested.
	 * @param string $type - Casts value to this type.
	 *     Possible values: str, int, float, double, bool
	 * @param string $source - Strings representing the data source. (from least to greatest importance)
	 *     Possible values: get, post, cookie, session
	 * @return object - Returns the requested value, cast to requested type.
	 */
	public static function GetVar(string $name, string $type='s', string...$sources) {
		if (\count($sources) == 0)
			$sources = ['g', 'p'];
		$value = null;
		$val = null;
		foreach ($sources as $src) {
			switch (\mb_strtolower(\mb_substr($src, 0, 1))) {
				case 'g': $val = self::Get(       $name, $type); break;
				case 'p': $val = self::Post(      $name, $type); break;
				case 'c': $val = self::GetCookie( $name, $type); break;
				case 'e': $val = self::GetEnv(    $name, $type); break;
				case 'v': $val = self::GetServer( $name, $type); break;
				case 's': $val = self::GetSession($name, $type); break;
				default: throw new \InvalidArgumentException('Unknown value source: '.$src);
			}
			if ($val !== null)
				$value = $val;
		}
		return $value;
	}



	// get var
	public static function Get(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_GET[$name]))
			return self::CastType($_GET[$name], $type);
		return null;
	}
	// post var
	public static function Post(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_POST[$name]))
			return self::CastType($_POST[$name], $type);
		return null;
	}
	// cookie var
	public static function GetCookie(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_COOKIE[$name]))
			return self::CastType($_COOKIE[$name], $type);
		return null;
	}
	// php session var
	public static function GetSession(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_SESSION[$name]))
			return self::CastType($_SESSION[$name], $type);
		return null;
	}
	// environment variables
	public static function GetEnv(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_ENV[$name]))
			return self::CastType($_ENV[$name], $type);
		return null;
	}
	// server variables
	public static function GetServer(string $name, ?string $type=null) {
		if (empty($type)) $type = 's';
		if (isset($_SERVER[$name]))
			return self::CastType($_SERVER[$name], $type);
		return null;
	}



	/**
	 * Parses REQUEST_URI from http request header and inserts into $_GET array.
	 * @example:
	 * URL: http://example.com/home/?action=display
	 * // After processing, $_GET contains:
	 * [ 'action' => 'display' ]
	 */
	public static function ParseVarsURI(\pxn\phpPortal\WebApp $app): array {
		$uri = $_SERVER['REQUEST_URI'];
		// uri vars
		$pos = \mb_strpos($uri, '?');
		if ($pos !== false) {
			$args = \mb_substr($uri, $pos+1);
			$uri = \mb_substr($uri, 0, $pos);
			$args = \explode('&', $args);
			foreach ($args as $arg) {
				$pos = \mb_strpos($arg, '=');
				if ($pos === false) {
					$_GET[$arg] = '';
				} else {
					$key = \mb_substr($arg, 0, $pos);
					$_GET[$key] = \mb_substr($arg, $pos+1);
				}
			}
		}
		// uri path
		$uri = StringUtils::trim($uri, '/');
		$args = \explode('/', $uri);
		return $args;
	}



	##########
	## Time ##
	##########



	/**
	 * @return double - Returns current timestamp in seconds.
	 */
	public static function Timestamp(int $places=3): float {
		$places = NumberUtils::MinMax($places, 0, 4);
		$time = \explode(' ', \microtime(), 2);
		if ($places <= 0)
			return (int) $time[1];
		$timestamp = ((float) $time[1]) + ((float) $time[0]);
		return NumberUtils::Round($timestamp, $places);
	}
	/**
	 * Sleep execution for x milliseconds.
	 * @param int $ms - Milliseconds to sleep.
	 */
	public static function Sleep(int $ms): void {
		if ($ms > 0.0)
			\usleep($ms * 1000);
	}



	##################
	## HTTP Headers ##
	##################



//TODO
/*
	/ **
	 * Sends http headers to disable page caching.
	 * @return boolean - true if successful; false if headers already sent.
	 * @codeCoverageIgnore
	 * /
	public static function NoPageCache() {
		if (SystemUtils::isShell())
			return;
		if (self::$INITED_NoPageCache)
			return true;
		if (\headers_sent())
			return false;
		@\header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@\header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@\header('Cache-Control: no-store, no-cache, must-revalidate');
		@\header('Cache-Control: post-check=0, pre-check=0', false);
		@\header('Pragma: no-cache');
		self::$INITED_NoPageCache = true;
		return true;
	}
	private static $INITED_NoPageCache = false;



	/ **
	 * Forward to provided url.
	 * @param string $url - The url/address in which to forward to.
	 * @param number $delay - Optional delay in seconds before forwarding.
	 * @codeCoverageIgnore
	 * /
	public static function ForwardTo(string $url='/', int $delay=0):void {
		if (empty($url)) {
			$url = '/';
		}
		if (SystemUtils::isShell()) {
			echo "--FORWARD: $url\n";
		} else {
			if (\headers_sent() || $delay > 0) {
				echo <<<EOF
<header><meta http-equiv="refresh" content="{$delay};url={$url}"></header>
<p><a href="{$url}"><font size="+1">Continue..</font></a></p>
EOF;
			} else {
				\header('HTTP/1.0 302 Found');
				\header("Location: $url");
			}
		}
		exit(0);
	}
* /



	/ **
	 * Scroll to the bottom of the page.
	 * @param string $id - Optional id of element in which to scroll.
	 * @codeCoverageIgnore
	 * /
	public static function ScrollToBottom(?string $id=null): void {
		if (SystemUtils::isShell()) {
			echo "--SCROLL--\n";
		} else {
			if (empty($id)) {
				$id = 'document';
			}
			echo <<<EOF
<!-- ScrollToBottom() -->
<script type="text/javascript"><!--//
{$id}.scrollTop={$id}.scrollHeight;
window.scroll(0, document.body.offsetHeight);
//--></script>
EOF;
		}
	}



//TODO: is this useful?
/ *
	/ **
	 * Validates an object by class name.
	 * @param string $className - Name of class to look for.
	 * @param object $object - Object to validate.
	 * @return boolean - true if object matches class name.
	 * /
	public static function InstanceOfClass($className, $object) {
		if (empty($className)) return false;
		if ($object == null)   return false;
		//echo '<p>$className - '.$className.'</p>';
		//echo '<p>get_class($clss) - '.get_class($clss).'</p>';
		//echo '<p>get_parent_class($clss) - '.get_parent_class($clss).'</p>';
		return
			\get_class($object) == $className ||
//			get_parent_class($clss) == $className ||
			\is_subclass_of($object, $className);
	}
	/ **
	 * Validates an object by class name, throwing an exception if invalid.
	 * @param string $className - Name of class to check for.
	 * @param object $object - Object to validate.
	 * /
	public static function ValidateClass($className, $object) {
		if (empty($className))
			throw new \InvalidArgumentException('classname not defined');
		if ($object == null)
			throw new \InvalidArgumentException('object not defined');
		if (!self::InstanceOfClass($className, $object))
			throw new \InvalidArgumentException('Class object isn\'t of type '.$className);
	}
*/



}
