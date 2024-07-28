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



	/**
	 * Sends http headers to disable page caching.
	 * @return boolean - true if successful; false if headers already sent.
	 * @codeCoverageIgnore
	 */
	private static bool $INITED_NoPageCache = false;
	public static function NoPageCache(): bool {
		if (SystemUtils::IsShell())    return false;
		if (self::$INITED_NoPageCache) return true;
		if (\headers_sent())           return false;
		@\header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@\header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@\header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		@\header('Cache-Control: post-check=0, pre-check=0', false);
		@\header('Pragma: no-cache');
		self::$INITED_NoPageCache = true;
		return true;
	}



	/**
	 * Forward to the provided url.
	 * @param string $url - The url/address in which to forward to.
	 * @param number $delay - Optional delay in seconds before forwarding.
	 * @codeCoverageIgnore
	 */
	public static function ForwardTo(?string $url=null, int $delay=0): void {
		if (empty($url)) $url = '/';
		if (SystemUtils::IsShell()) {
			echo '--FORWARD: '.$url."\n";
			exit(1);
		} else
		if (\headers_sent() || $delay > 0) {
			echo '<html><header><meta http-equiv="refresh" content="'.$delay.';url='.$url.'"></header>'.
				'<body><p><a href="'.$url.'"><font size="+1">Continue..</font></a></p></body></html>'."\n";
		} else {
			\header('HTTP/1.0 302 Found');
			\header('Location: '.$url);
		}
		exit(0);
	}



	/**
	 * Scroll to the bottom of the page.
	 * @param string $id - Optional id of element in which to scroll.
	 * @codeCoverageIgnore
	 */
	public static function ScrollToBottom(?string $id=null): void {
		if (SystemUtils::IsShell()) {
			echo "--SCROLL--\n";
		} else {
			if (empty($id))
				$id = 'document';
			echo <<<EOF
<!-- ScrollToBottom() -->
<script type="text/javascript"><!--//
{$id}.scrollTop={$id}.scrollHeight;
window.scroll(0, document.body.offsetHeight);
//--></script>
EOF;
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
