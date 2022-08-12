<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - PHP Utilities Library
 * @copyright 2004-2022
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpUtils\utils;


final class SanUtils {
	/** @codeCoverageIgnore */
	private final function __construct() {}



	public static function alpha_num(string $value, string $extra=null): string {
		return \preg_replace(
			pattern: '/[^a-zA-Z0-9'.($extra??'').']+/',
			replacement: '',
			subject: $value
		);
	}

	public static function alpha_num_simple(string $value): string {
		return self::alpha_num(value: $value, extra: '\_\-');
	}

	public static function rep_space(string $value): string {
		$result = self::alpha_num(value: $value, extra: '\_\-\s');
		return \str_replace(search: ' ', replace: '_', subject: $result);
	}

	public static function path_safe(string $path): string {
		$result = self::alpha_num(value: $path, extra: '\.\/\_\-\s');
		return \str_replace(search: ' ', replace: '_', subject: $result);
	}

	public static function base64(string $value): string {
		return self::alpha_num(value: $value, extra: '\=');
	}



	public static function is_alpha_num(string $value, string $extra=null): bool {
		return ($value === self::alpha_num(value: $value, extra: $extra));
	}
	public static function is_alpha_num_simple(string $value): bool {
		return ($value === self::alpha_num_simple(value: $value));
	}
	public static function is_path_safe(string $path): bool {
		return ($path === self::path_safe(path: $path));
	}
	public static function is_base64(string $value): bool {
		return ($value === self::base64(value: $value));
	}

	public static function is_version(string $version): bool {
		return (\preg_match(pattern: '/[^0-9.]+/', subject: $version) === 0);
	}



}
