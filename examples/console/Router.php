<?php declare(strict_types=1);
/*
 * PoiXson phpUtils - Website Utilities Library
 * @copyright 2004-2025
 * @license AGPLv3+ADD-PXN-V1
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 * /
namespace pxn\phpUtils\examples\console;

use \pxn\phpUtils\console\Command;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;


class Router implements \pxn\phpUtils\console\Router {

	protected static $instance = null;



	public static function get() {
		if (self::$instance == null) {
			self::$instance = new static();
		}
		return self::$instance;
	}
	protected function __construct() {
		// example commands
		commands\Random::get();
		commands\Sequential::get();
		// inline example command
		$command = Command::RegisterNew(
			'inline',
			function(InputInterface $input, OutputInterface $output) {
				echo "\n\n";
				echo 'Running Command: INLINE'."\n";
				echo "\n\n";
			}
		);
		$command->setInfo(
			'Example command calls inline callable',
			'HELP!',
			'USAGE?'
		);
		// method command
		$command = Command::RegisterNew(
			'method',
			[ $this, 'runCommand' ]
		);
		$command->setInfo(
			'Example command runs a method',
			'HELP!',
			'USAGE?'
		);
	}



	public function runCommand(InputInterface $input, OutputInterface $output) {
		echo "\n\n";
		echo 'Running Command: METHOD'."\n";
		echo "\n\n";
	}



}
*/
