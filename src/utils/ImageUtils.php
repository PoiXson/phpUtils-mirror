<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2024
 * @license AGPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class ImageUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	public static function RequireGD(): void {
		if (!self::GDSupported())
			throw new \Exception('GD2 library not found');
	}
	/**
	 * Checks for GD support.
	 * @return boolean - true if GD functions are available.
	 */
	public static function GDSupported(): bool {
		return \function_exists('gd_info');
	}



	public static function LoadImage(string $file): GdImage {
		if (\mb_str_ends_with($file, '.png')) return \imagecreatefrompng($file);
		if (\mb_str_ends_with($file, '.jpg')
		|| \mb_str_ends_with($file, '.jpeg')) return \imagecreatefromjpeg($file);
		if (\mb_str_ends_with($file, '.gif')) return \imagecreatefromgif($file);
		throw new \Exception('Image file type not supported: '.$file);
	}



}
