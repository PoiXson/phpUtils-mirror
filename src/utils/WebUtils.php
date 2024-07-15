<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2024
 * @license AGPL-3
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



	public static function Paginate($pageNum, $pageLast, $pageWidth=2) {
		$pageLast  = NumberUtils::MinMax( (int) $pageLast,  1);
		$pageNum   = NumberUtils::MinMax( (int) $pageNum,   1, $pageLast);
		$pageWidth = NumberUtils::MinMax( (int) $pageWidth, 1, 5);
		$pageFrom  = NumberUtils::MinMax($pageNum - $pageWidth, 2);
		$pageTo    = NumberUtils::MinMax($pageNum + $pageWidth, false, $pageLast - 1);
		return [
			'current' => $pageNum,
			'last'    => $pageLast,
			'from'    => $pageFrom,
			'to'      => $pageTo,
		];
	}



}
