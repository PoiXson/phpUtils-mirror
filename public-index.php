<?php declare(strict_types=1);

// class loader
$loader = null;
if (\is_file(__DIR__.'/vendor/autoload.php')) {
	$loader = require(__DIR__.'/vendor/autoload.php');
} else
if (\is_file(__DIR__.'/../vendor/autoload.php')) {
	$loader = require(__DIR__.'/../vendor/autoload.php');
}
if ($loader == null) { echo "Failed to detect autoload.php\n"; exit(1); }

// find app class
$app_class = null;
if (\file_exists(__DIR__.'/.xapp')) {
	$app_class = \file_get_contents(__DIR__.'/.xapp');
} else
if (\file_exists(__DIR__.'/../.xapp')) {
	$app_class = \file_get_contents(__DIR__.'/../.xapp');
}
if (empty($app_class)) { echo "Failed to detect app class\n"; exit(1); }
$app_class = \explode("\n", $app_class, 2);
$app_class = \trim(\reset($app_class));
if (\str_ends_with($app_class, '\\'))
	$app_class = \mb_substr($app_class, 0, -1);
if (empty($app_class)) { echo "Invalid app class\n"; exit(1); }
if (!\str_ends_with($app_class, '\\Website')
&&  !\str_ends_with($app_class, '\\ShellApp'))
	throw new \RuntimeException("Invalid app class: $app_class");
if (!\class_exists($app_class)) { echo "App class not found: $app_class\n"; exit(1); }

// load app
$app = new $app_class($loader);
$app->run();
