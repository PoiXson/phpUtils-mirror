<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils;


final class GeneralUtils {
	private final function __construct() {}



	###############
	## Cast Type ##
	###############



	// cast variable type
	public static function castType($data, string $type) {
		if (empty($type))
			return $data;
		switch (\mb_strtolower(\mb_substr( (string) $type, 0, 1))) {
			// string
			case 's':
				return ((string) $data);
			// integer/long
			case 'i':
			case 'l':
				return ((integer) $data);
			// float
			case 'f':
				return ((float) $data);
			// double
			case 'd':
				return ((float) $data);
			// boolean
			case 'b':
				return self::castBoolean($data);
			default:
				break;
		}
		return $data;
	}



	// convert to boolean
	public static function castBoolean($value): ?bool {
		if ($value === NULL)
			return NULL;
		if (\gettype($value) === 'boolean')
			return $value;
		$val = \mb_strtolower(trim( (string) $value ));
		if ($val == 'on')  return TRUE;
		if ($val == 'off') return FALSE;
		switch (mb_substr($val, 0, 1)) {
			case 't': // true
			case 'y': // yes
			case 'a': // allow
			case 'e': // enable
				return TRUE;
			case 'f': // false
			case 'n': // no
			case 'd': // deny/disable
				return FALSE;
		}
		return ((boolean) $value);
	}



	##############
	## URL Vars ##
	##############



	// get,post,cookie (highest priority last)
	/ **
	 * Gets a value from a specific list of sources.
	 * @param string $name - Name or key requested.
	 * @param string $type - Casts value to this type.
	 *     Possible values: str, int, float, double, bool
	 * @param string $source - Strings representing the data source. (from least to greatest importance)
	 *     Possible values: get, post, cookie, session
	 * @return object - Returns the requested value, cast to requested type.
	 * /
	public static function getVar(string $name, string $type='s', string...$sources) {
		if (\count($sources) == 0) {
			$sources = ['g', 'p'];
		}
		$value = NULL;
		foreach ($sources as $src) {
			$v = NULL;
			$char = \mb_substr($src, 0, 1);
			switch (\mb_strtolower($char)) {
			// get
			case 'g':
				$v = self::get($name, $type);
				break;
			// post
			case 'p':
				$v = self::post($name, $type);
				break;
			// cookie
			case 'c':
				$v = self::cookie($name, $type);
				break;
			// env
			case 'e':
				$v = self::env($name, $type);
				break;
			// server
			case 'v':
				$v = self::server($name, $type);
				break;
			// session
			case 's':
				$v = self::session($name, $type);
				break;
			default:
				throw new \InvalidArgumentException("Unknown value source: $src");
			}
			// value found
			if ($v !== NULL) {
				$value = $v;
			}
		}
		return $value;
	}



	// get var
	public static function get(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_GET[$name]))
			return self::castType($_GET[$name], $type);
		return NULL;
	}
	// post var
	public static function post(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_POST[$name]))
			return self::castType($_POST[$name], $type);
		return NULL;
	}
	// cookie var
	public static function cookie(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_COOKIE[$name]))
			return self::castType($_COOKIE[$name], $type);
		return NULL;
	}
	// php session var
	public static function session(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_SESSION[$name]))
			return self::castType($_SESSION[$name], $type);
		return NULL;
	}
	// environment variables
	public static function env(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_ENV[$name]))
			return self::castType($_ENV[$name], $type);
		return NULL;
	}
	// server variables
	public static function server(string $name, ?string $type=NULL) {
		if (empty($type)) $type = 's';
		if (isset($_SERVER[$name]))
			return self::castType($_SERVER[$name], $type);
		return NULL;
	}



//TODO
/ *
	/ **
	 * Parses REQUEST_URI from http request header and inserts into $_GET array.
	 * @example:
	 * URL: http://example.com/page/home/?action=display
	 * // After processing, $_GET contains:
	 * array(
	 *     'page' => 'home',
	 *     'action' => 'display'
	 * );
	 * /
	public static function ParseModRewriteVars() {
		// parse mod_rewrite uri
		if (isset($_SERVER['REDIRECT_STATUS'])) {
			$data = $_SERVER['REQUEST_URI'];
			// parse ? query string
			if (\mb_strpos($data, '?') !== FALSE) {
				list($data, $query) = \explode('?', $data, 2);
				if (!empty($query)) {
					//$arr = explode('&', $query);
					//echo 'query: ?'.$query.'<br />';
				}
			}
			// parse url path
			$data = \array_values(\psm\utils::array_remove_empty(\explode('/', $data)));
			// needs to be even
			if ((\count($data) % 2) != 0)
				$data[] = '';
			// merge values into GET
			for ($i=0; $i<\count($data); $i++) {
				$_GET[$data[$i]] = $data[++$i];
			}
		}
	}
* /



	##########
	## Time ##
	##########



	/ **
	 * @return double - Returns current timestamp in seconds.
	 * /
	public static function getTimestamp(int $places=3): float {
		$places = Numbers::MinMax($places, 0, 4);
		$time = \explode(' ', \microtime(), 2);
		if ($places <= 0)
			return (int) $time[1];
		$timestamp = ((float) $time[1]) + ((float) $time[0]);
		return Numbers::Round($timestamp, $places);
	}
	/ **
	 * Sleep execution for x milliseconds.
	 * @param int $ms - Milliseconds to sleep.
	 * /
	public static function Sleep(int $ms): void {
		if ($ms > 0.0) {
			\usleep($ms * 1000);
		}
	}



	##################
	## HTTP Headers ##
	##################



/ *
	/ **
	 * Sends http headers to disable page caching.
	 * @return boolean - TRUE if successful; FALSE if headers already sent.
	 * @codeCoverageIgnore
	 * /
	public static function NoPageCache() {
		if (SystemUtils::isShell()) {
			return;
		}
		if (self::$INITED_NoPageCache)
			return TRUE;
		if (\headers_sent())
			return FALSE;
		@\header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@\header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@\header('Cache-Control: no-store, no-cache, must-revalidate');
		@\header('Cache-Control: post-check=0, pre-check=0', FALSE);
		@\header('Pragma: no-cache');
		self::$INITED_NoPageCache = TRUE;
		return TRUE;
	}
	private static $INITED_NoPageCache = FALSE;



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
	public static function ScrollToBottom(?string $id=NULL): void {
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



	/ **
	 * Checks for GD support.
	 * @return boolean - TRUE if GD functions are available.
	 * /
	public static function GDSupported(): bool {
		return \function_exists('gd_info');
	}



//TODO: is this useful?
/ *
	/ **
	 * Validates an object by class name.
	 * @param string $className - Name of class to look for.
	 * @param object $object - Object to validate.
	 * @return boolean - TRUE if object matches class name.
	 * /
	public static function InstanceOfClass($className, $object) {
		if (empty($className)) return FALSE;
		if ($object == NULL)   return FALSE;
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
		if ($object == NULL)
			throw new \InvalidArgumentException('object not defined');
		if (!self::InstanceOfClass($className, $object))
			throw new \InvalidArgumentException('Class object isn\'t of type '.$className);
	}
* /



}
*/
