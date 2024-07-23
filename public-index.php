<?php declare(strict_types=1);

// class loader
$loader = null;
if (\is_file(__DIR__.'/vendor/autoload.php')) {
	$loader = require(__DIR__.'/vendor/autoload.php');
} else
if (\is_file(__DIR__.'/../vendor/autoload.php')) {
	$loader = require(__DIR__.'/../vendor/autoload.php');
}
if ($loader == null) { echo "\nFailed to detect autoload.php\n\n"; exit(1); }

// load composer.json
$data = null;
if (\is_file(__DIR__.'/composer.json')) {
	$data = \file_get_contents(__DIR__.'/composer.json');
} else
if (\is_file(__DIR__.'/../composer.json')) {
	$data = \file_get_contents(__DIR__.'/../composer.json');
}
if (empty($data)) throw new \RuntimeException("Failed to detect composer.json");
$json = \json_decode($data, true);
unset($data);
if ($json == null) throw new \RuntimeException("Failed to decode composer.json");

// find namespace
if (!isset($json['autoload']))
	throw new \RuntimeException('autoload key not found in composer.json file');
if (!isset($json['autoload']['psr-4']))
	throw new \RuntimeException('autoload\\psr-4 key not found in composer.json file');
$app_ns = \array_search('src/', $json['autoload']['psr-4']);
if (empty($app_ns)) throw new \RuntimeException('Failed to find namespace in composer.json file');
if (!\str_starts_with($app_ns, '\\')) $app_ns  = '\\'.$app_ns;
if (!\str_ends_with(  $app_ns, '\\')) $app_ns .= '\\';

// find class
$app_class = (
	\pxn\phpUtils\utils\SystemUtils::IsShell()
	? $app_ns.'Tool'
	: $app_ns.'Website'
);
if (!\class_exists($app_class))
	throw new \RuntimeException('Failed to detect app class: '.$app_class);

// load app
$app = new $app_class($loader);
$app->run();
