<?php declare(strict_types = 1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class WebUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	// g - GET
	// p - POST
	// c - Cookie
	// s - Session
	// e - Environment
	// v - Server
	public static function getVar(string $name, ?string $params=NULL) {
		$pos = \mb_strpos(haystack: $name, needle: ':');
		if ($pos !== FALSE) {
			if ($params === NULL) $params = '';
			$params .= \mb_substr($name, 0, $pos);
			$name = \mb_substr($name, $pos+1);
		}
		if (empty($params))
			$params = 'pg';
		$param_count = \mb_strlen($params);
		for ($i=0; $i<$param_count; $i++) {
			switch ($params[$i]) {
			// GET
			case 'g':
				if (isset($_GET[$name]))
					if (!empty($_GET[$name]))
						return $_GET[$name];
				break;
			// POST
			case 'p':
				if (isset($_POST[$name]))
					if (!empty($_POST[$name]))
						return $_POST[$name];
				break;
			// Cookie
			case 'c':
				if (isset($_COOKIE[$name]))
					if (!empty($_COOKIE[$name]))
						return $_COOKIE[$name];
				break;
			// Session
			case 's':
				if (isset($_SESSION[$name]))
					if (!empty($_SESSION[$name]))
						return $_SESSION[$name];
				break;
			// Environment
			case 'e':
				if (isset($_ENV[$name]))
					if (!empty($_ENV[$name]))
						return $_ENV[$name];
				break;
			// Server
			case 'v':
				if (isset($_SERVER[$name]))
					if (!empty($_SERVER[$name]))
						return $_SERVER[$name];
				break;
			default:
				break;
//TODO: logging
//echo 'Unknown param: '.$params[$i];
			}
		}
		return NULL;
	}

	public static function LoadVarsFromURI(): void {
		if (!isset($_SERVER['REQUEST_URI']))
			return;
		$uri = $_SERVER['REQUEST_URI'];
		$pos = \mb_strpos(haystack: $uri, needle: '?');
		if ($pos === FALSE)
			return;
		$params = \mb_substr($uri, $pos+1);
		if (empty($params))
			return;
		$entries = \explode(string: $params, separator: '&');
		foreach ($entries as $entry) {
			$pos = \mb_strpos(haystack: $entry, needle: '=');
			if ($pos !== FALSE) {
				$key = \mb_substr($entry, 0, $pos);
				$val = \mb_substr($entry, $pos+1);
				$_GET[$key] = $val;
			}
		}
	}



	public static function ForwardTo(string $url): void {
		if (\headers_sent()) {
			echo <<<EOF
<html><header><meta http-equiv="refresh" content="0;url={$url}"></header>
<body><p><a href="$url"><font size="+1">Continue..</font></a></p></body></html>
EOF;
		} else {
			\header('HTTP/1.0 302 Found');
			\header("Location: $url");
		}
	}



}
