<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class WebUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



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
